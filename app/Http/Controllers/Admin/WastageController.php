<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Staff;
use App\Models\Wastage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WastageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | WASTAGE LIST
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $wastages = Wastage::with([
            'product',
            'branch',
            'staff'
        ])
        ->where('salon_id', $salon->id)

        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('wastage_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('name', 'like', "%{$search}%");
                  });
            });
        })

        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->status);
        })

        ->when($request->filled('reason'), function ($query) use ($request) {
            $query->where('reason', $request->reason);
        })

        ->latest('wastage_date')
        ->latest('id')
        ->paginate(15)
        ->withQueryString();

        return view(
            'admin.inventory.wastage.index',
            compact('wastages')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE WASTAGE FORM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $branches = $salon->branches()
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('salon_id', $salon->id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        $staff = Staff::where('salon_id', $salon->id)
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.inventory.wastage.create',
            compact(
                'branches',
                'products',
                'staff'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE WASTAGE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        $validated = $request->validate([
            'branch_id' => [
                'required',
                'integer',
            ],

            'product_id' => [
                'required',
                'integer',
            ],

            'staff_id' => [
                'nullable',
                'integer',
            ],

            'wastage_date' => [
                'required',
                'date',
            ],

            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'unit_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'reason' => [
                'required',
                'in:damaged,expired,spillage,leakage,broken,contaminated,lost,other',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Validate Branch
        |--------------------------------------------------------------------------
        */
        $branch = Branch::where('id', $validated['branch_id'])
            ->where('salon_id', $salon->id)
            ->where('status', true)
            ->first();

        if (!$branch) {
            return back()
                ->withInput()
                ->withErrors([
                    'branch_id' => 'Invalid branch selected.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Product
        |--------------------------------------------------------------------------
        */
        $product = Product::where('id', $validated['product_id'])
            ->where('salon_id', $salon->id)
            ->where('status', true)
            ->first();

        if (!$product) {
            return back()
                ->withInput()
                ->withErrors([
                    'product_id' => 'Invalid product selected.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Staff
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['staff_id'])) {

            $staffExists = Staff::where('id', $validated['staff_id'])
                ->where('salon_id', $salon->id)
                ->where('status', true)
                ->exists();

            if (!$staffExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'staff_id' => 'Invalid staff selected.'
                    ]);
            }
        }


        $quantity = (float) $validated['quantity'];

        $unitCost = (float) $validated['unit_cost'];

        $totalCost = $quantity * $unitCost;


        /*
        |--------------------------------------------------------------------------
        | Create Wastage
        |--------------------------------------------------------------------------
        |
        | Important:
        | Stock is NOT reduced here.
        |
        | Wastage starts as PENDING.
        |
        */
        $wastage = DB::transaction(function () use (
            $salon,
            $validated,
            $quantity,
            $unitCost,
            $totalCost
        ) {

            $wastage = Wastage::create([
                'salon_id'      => $salon->id,
                'branch_id'     => $validated['branch_id'],
                'product_id'    => $validated['product_id'],
                'staff_id'      => $validated['staff_id'] ?? null,

                'wastage_number' => 'TEMP-' . Str::uuid(),

                'wastage_date'  => $validated['wastage_date'],

                'quantity'      => $quantity,
                'unit_cost'     => $unitCost,
                'total_cost'    => $totalCost,

                'reason'        => $validated['reason'],

                'notes'         => $validated['notes'] ?? null,

                'status'        => 'pending',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Generate final Wastage Number
            |--------------------------------------------------------------------------
            */
            $wastage->update([
                'wastage_number' =>
                    'WAS-' .
                    date('Ymd') .
                    '-' .
                    str_pad($wastage->id, 6, '0', STR_PAD_LEFT)
            ]);

            return $wastage;
        });


        return redirect()
            ->route('admin.inventory.wastage.index')
            ->with(
                'success',
                'Wastage reported successfully. Waiting for approval.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW WASTAGE DETAILS
    |--------------------------------------------------------------------------
    */
    public function show(Wastage $wastage)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        abort_unless(
            $wastage->salon_id == $salon->id,
            404
        );

        $wastage->load([
            'product',
            'branch',
            'staff'
        ]);

        return view(
            'admin.inventory.wastage.show',
            compact('wastage')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | APPROVE WASTAGE
    |--------------------------------------------------------------------------
    */
    public function approve(Wastage $wastage)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        DB::transaction(function () use ($wastage, $salon) {

            $wastage = Wastage::where('id', $wastage->id)
                ->where('salon_id', $salon->id)
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Only Pending Wastage Can Be Approved
            |--------------------------------------------------------------------------
            */
            if ($wastage->status !== 'pending') {

                throw ValidationException::withMessages([
                    'status' => 'Only pending wastage can be approved.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Lock Product Row
            |--------------------------------------------------------------------------
            */
            $product = Product::where('id', $wastage->product_id)
                ->where('salon_id', $salon->id)
                ->lockForUpdate()
                ->firstOrFail();


            /*
            |--------------------------------------------------------------------------
            | Check Available Stock
            |--------------------------------------------------------------------------
            */
            if ((float) $product->stock < (float) $wastage->quantity) {

                throw ValidationException::withMessages([
                    'quantity' =>
                        'Insufficient stock. Available stock: ' .
                        $product->stock .
                        ', required: ' .
                        $wastage->quantity
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Deduct Stock
            |--------------------------------------------------------------------------
            */
            $product->decrement(
                'stock',
                $wastage->quantity
            );


            /*
            |--------------------------------------------------------------------------
            | Mark Wastage Approved
            |--------------------------------------------------------------------------
            */
            $wastage->update([
                'status' => 'approved'
            ]);
        });


        return redirect()
            ->route(
                'admin.inventory.wastage.show',
                $wastage->id
            )
            ->with(
                'success',
                'Wastage approved and stock has been deducted.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT WASTAGE
    |--------------------------------------------------------------------------
    */
    public function reject(Wastage $wastage)
    {
        $salon = Auth::user()->salon;

        abort_if(!$salon, 403, 'Salon not found.');

        if ($wastage->salon_id != $salon->id) {
            abort(404);
        }


        if ($wastage->status !== 'pending') {

            return back()->with(
                'error',
                'Only pending wastage can be rejected.'
            );
        }


        $wastage->update([
            'status' => 'rejected'
        ]);


        return redirect()
            ->route(
                'admin.inventory.wastage.show',
                $wastage->id
            )
            ->with(
                'success',
                'Wastage has been rejected.'
            );
    }
}