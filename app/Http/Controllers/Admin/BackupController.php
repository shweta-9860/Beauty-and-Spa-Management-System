<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    /**
     * Backup directory.
     */
    private function backupDirectory(): string
    {
        $directory = storage_path('app/backups');

        if (!File::exists($directory)) {
            File::makeDirectory(
                $directory,
                0755,
                true
            );
        }

        return $directory;
    }


    /**
     * Backup page.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->salon_id) {
            abort(403, 'Salon not found.');
        }

        $branches = Branch::where(
            'salon_id',
            $user->salon_id
        )
            ->orderBy('name')
            ->get();

        $backupDirectory = $this->backupDirectory();

        $files = collect(
            File::files($backupDirectory)
        )
            ->filter(function ($file) {
                return Str::endsWith(
                    strtolower($file->getFilename()),
                    '.sql'
                );
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            })
            ->values();

        return view(
            'admin.settings.backup',
            compact(
                'branches',
                'files'
            )
        );
    }


    /**
     * Create backup.
     *
     * Types:
     * full
     * current_branch
     * selected_branch
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->salon_id) {
            abort(403, 'Salon not found.');
        }

        $validated = $request->validate([
            'backup_type' => [
                'required',
                'in:full,current_branch,selected_branch',
            ],

            'branch_id' => [
                'nullable',
                'integer',
            ],
        ]);


        $backupType = $validated['backup_type'];

        $branchId = null;

        /*
        |--------------------------------------------------------------------------
        | Current Branch
        |--------------------------------------------------------------------------
        */

        if ($backupType === 'current_branch') {

            if (!session()->has('branch_id')) {

                return back()
                    ->withErrors([
                        'backup_type' =>
                            'No branch is currently selected.',
                    ]);
            }

            $branchId = (int) session('branch_id');
        }


        /*
        |--------------------------------------------------------------------------
        | Selected Branch
        |--------------------------------------------------------------------------
        */

        if ($backupType === 'selected_branch') {

            if (empty($validated['branch_id'])) {

                return back()
                    ->withErrors([
                        'branch_id' =>
                            'Please select a branch.',
                    ]);
            }

            $branchId = (int) $validated['branch_id'];
        }


        /*
        |--------------------------------------------------------------------------
        | Validate branch belongs to current salon
        |--------------------------------------------------------------------------
        */

        if ($branchId !== null) {

            $branchExists = Branch::where('id', $branchId)
                ->where('salon_id', $user->salon_id)
                ->exists();

            if (!$branchExists) {

                return back()
                    ->withErrors([
                        'branch_id' =>
                            'Invalid branch selected.',
                    ]);
            }
        }


        try {

            $sql = $this->generateSqlBackup(
                $user->salon_id,
                $branchId,
                $backupType
            );


            /*
            |--------------------------------------------------------------------------
            | File name
            |--------------------------------------------------------------------------
            */

            if ($backupType === 'full') {

                $prefix = 'full_salon_backup';

            } elseif ($backupType === 'current_branch') {

                $branch = Branch::find($branchId);

                $branchName = $branch
                    ? Str::slug($branch->name)
                    : 'branch';

                $prefix = 'current_branch_' . $branchName . '_backup';

            } else {

                $branch = Branch::find($branchId);

                $branchName = $branch
                    ? Str::slug($branch->name)
                    : 'branch';

                $prefix = 'branch_' . $branchName . '_backup';
            }


            $filename = $prefix . '_'
                . now()->format('Y-m-d_H-i-s')
                . '.sql';


            $path = $this->backupDirectory()
                . DIRECTORY_SEPARATOR
                . $filename;


            File::put(
                $path,
                $sql
            );


            return back()->with(
                'success',
                'Backup created successfully: ' . $filename
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->withErrors([
                'backup' =>
                    'Backup failed: ' . $e->getMessage(),
            ]);
        }
    }


    /**
     * Generate SQL backup.
     */
    private function generateSqlBackup(
        int $salonId,
        ?int $branchId,
        string $backupType
    ): string {

        $databaseName = DB::connection()
            ->getDatabaseName();


        $sql = '';

        $sql .= "-- =====================================================\n";
        $sql .= "-- Salon Management Backup\n";
        $sql .= "-- Database: {$databaseName}\n";
        $sql .= "-- Created: " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Backup Type: {$backupType}\n";

        if ($branchId !== null) {
            $sql .= "-- Branch ID: {$branchId}\n";
        }

        $sql .= "-- =====================================================\n\n";


        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n";
        $sql .= "SET AUTOCOMMIT=0;\n\n";


        /*
        |--------------------------------------------------------------------------
        | Get all tables
        |--------------------------------------------------------------------------
        */

        $tables = DB::select(
            'SHOW TABLES'
        );


        $databaseKey = 'Tables_in_' . $databaseName;


        foreach ($tables as $tableObject) {

            $table = $tableObject->{$databaseKey};


            /*
            |--------------------------------------------------------------------------
            | Skip Laravel migrations table
            |--------------------------------------------------------------------------
            */

            if ($table === 'migrations') {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Get table columns
            |--------------------------------------------------------------------------
            */

            $columns = DB::select(
                "SHOW COLUMNS FROM `{$table}`"
            );


            if (empty($columns)) {
                continue;
            }


            $columnNames = collect($columns)
                ->pluck('Field')
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | Create table structure
            |--------------------------------------------------------------------------
            */

            $createTable = DB::select(
                "SHOW CREATE TABLE `{$table}`"
            );


            if (!empty($createTable)) {

                $createObject = $createTable[0];

                $createSql = null;

                foreach ((array) $createObject as $key => $value) {

                    if (strtolower($key) === 'create table') {
                        $createSql = $value;
                        break;
                    }

                    if (strtolower($key) === 'create view') {
                        $createSql = $value;
                        break;
                    }
                }

                if ($createSql) {

                    $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

                    $sql .= $createSql . ";\n\n";
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Determine filtering columns
            |--------------------------------------------------------------------------
            */

            $hasSalonId = in_array(
                'salon_id',
                $columnNames,
                true
            );

            $hasBranchId = in_array(
                'branch_id',
                $columnNames,
                true
            );


            /*
            |--------------------------------------------------------------------------
            | Build query
            |--------------------------------------------------------------------------
            */

            $query = DB::table($table);


            if ($backupType === 'full') {

                /*
                |--------------------------------------------------------------------------
                | Full salon backup
                |--------------------------------------------------------------------------
                */

                if ($hasSalonId) {

                    $query->where(
                        'salon_id',
                        $salonId
                    );
                }

            } else {

                /*
                |--------------------------------------------------------------------------
                | Branch backup
                |--------------------------------------------------------------------------
                */

                if ($hasBranchId && $hasSalonId) {

                    /*
                    |--------------------------------------------------------------------------
                    | Branch + Salon table
                    |--------------------------------------------------------------------------
                    |
                    | Include:
                    | 1. Selected branch data
                    | 2. Common salon-level data where branch_id = NULL
                    |
                    */

                    $query->where(
                        'salon_id',
                        $salonId
                    )
                        ->where(function ($q) use ($branchId) {

                            $q->where(
                                'branch_id',
                                $branchId
                            )
                                ->orWhereNull(
                                    'branch_id'
                                );
                        });

                } elseif ($hasBranchId) {

                    /*
                    |--------------------------------------------------------------------------
                    | Branch-only table
                    |--------------------------------------------------------------------------
                    */

                    $query->where(function ($q) use ($branchId) {

                        $q->where(
                            'branch_id',
                            $branchId
                        )
                            ->orWhereNull(
                                'branch_id'
                            );
                    });

                } elseif ($hasSalonId) {

                    /*
                    |--------------------------------------------------------------------------
                    | Salon-level table
                    |--------------------------------------------------------------------------
                    */

                    $query->where(
                        'salon_id',
                        $salonId
                    );

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | System/master tables
                    |--------------------------------------------------------------------------
                    |
                    | Include them as they are.
                    |
                    */
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Get rows
            |--------------------------------------------------------------------------
            */

            $rows = $query
                ->get();


            if ($rows->isEmpty()) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Insert statements
            |--------------------------------------------------------------------------
            */

            foreach ($rows as $row) {

                $values = [];

                foreach ($columnNames as $column) {

                    $value = $row->{$column};

                    if ($value === null) {

                        $values[] = 'NULL';

                    } elseif (is_bool($value)) {

                        $values[] = $value
                            ? '1'
                            : '0';

                    } elseif (is_numeric($value)) {

                        /*
                        |--------------------------------------------------------------------------
                        | Keep numeric values numeric
                        |--------------------------------------------------------------------------
                        */

                        $values[] = (string) $value;

                    } else {

                        $values[] = DB::getPdo()->quote(
                            (string) $value
                        );
                    }
                }


                $quotedColumns = collect($columnNames)
                    ->map(function ($column) {
                        return '`' . $column . '`';
                    })
                    ->implode(', ');


                $sql .= "INSERT INTO `{$table}` "
                    . "({$quotedColumns}) VALUES "
                    . "("
                    . implode(', ', $values)
                    . ");\n";
            }


            $sql .= "\n";
        }


        $sql .= "COMMIT;\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "SET AUTOCOMMIT=1;\n";


        return $sql;
    }


    /**
     * Download backup.
     */
    public function download(string $file): BinaryFileResponse
    {
        $file = basename($file);

        $path = $this->backupDirectory()
            . DIRECTORY_SEPARATOR
            . $file;


        if (
            !File::exists($path) ||
            !Str::endsWith(
                strtolower($file),
                '.sql'
            )
        ) {

            abort(404);
        }


        return response()->download(
            $path,
            $file,
            [
                'Content-Type' =>
                    'application/sql',
            ]
        );
    }


    /**
     * Restore backup.
     */
    public function restore(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->salon_id) {
            abort(403, 'Salon not found.');
        }


        $validated = $request->validate([

            'backup_file' => [
                'required',
                'file',
                'max:51200',
                'mimes:sql,txt',
            ],

        ]);


        $file = $validated['backup_file'];


        /*
        |--------------------------------------------------------------------------
        | Read uploaded SQL
        |--------------------------------------------------------------------------
        */

        $sql = File::get(
            $file->getRealPath()
        );


        if (trim($sql) === '') {

            return back()->withErrors([
                'backup_file' =>
                    'The backup file is empty.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Basic SQL validation
        |--------------------------------------------------------------------------
        */

        if (
            !Str::contains(
                strtoupper($sql),
                'CREATE TABLE'
            )
            &&
            !Str::contains(
                strtoupper($sql),
                'INSERT INTO'
            )
        ) {

            return back()->withErrors([
                'backup_file' =>
                    'Invalid SQL backup file.',
            ]);
        }


        try {

            /*
            |--------------------------------------------------------------------------
            | Execute SQL
            |--------------------------------------------------------------------------
            |
            | Laravel DB::unprepared can execute a complete SQL dump.
            |
            */

            DB::unprepared($sql);


            return back()->with(
                'success',
                'Backup restored successfully.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->withErrors([
                'backup_file' =>
                    'Restore failed: ' . $e->getMessage(),
            ]);
        }
    }


    /**
     * Delete backup.
     */
    public function destroy(string $file)
    {
        $file = basename($file);

        $path = $this->backupDirectory()
            . DIRECTORY_SEPARATOR
            . $file;


        if (
            !File::exists($path) ||
            !Str::endsWith(
                strtolower($file),
                '.sql'
            )
        ) {

            abort(404);
        }


        File::delete($path);


        return back()->with(
            'success',
            'Backup deleted successfully.'
        );
    }
}