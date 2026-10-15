@extends('admin.layout')

@section('title', 'Backup & Restore | Salon Management')

@section('page-title', 'Backup & Restore')

@section('content')

<div class="container-fluid px-0 backup-page">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please fix the following error:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h5 class="fw-semibold mb-1">

                        <i class="bi bi-database me-2"></i>

                        Backup & Restore

                    </h5>

                    <div class="text-muted small">

                        Create full salon or branch-wise database backups.

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CREATE BACKUP
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-semibold">

                <i class="bi bi-cloud-arrow-down me-2"></i>

                Create Backup

            </h6>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.settings.backup.create') }}">

                @csrf


                <div class="row g-4">

                    {{-- Backup Type --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Backup Type

                        </label>


                        <select name="backup_type"
                                id="backupType"
                                class="form-select"
                                required>

                            <option value="">

                                Select Backup Type

                            </option>

                            <option value="full">

                                Full Salon Backup

                            </option>

                            <option value="current_branch">

                                Current Branch Backup

                            </option>

                            <option value="selected_branch">

                                Selected Branch Backup

                            </option>

                        </select>


                        <div class="form-text">

                            Choose whether you want the complete salon
                            backup or branch-wise backup.

                        </div>

                    </div>


                    {{-- Branch --}}

                    <div class="col-md-6"
                         id="branchSelectWrapper"
                         style="display:none;">

                        <label class="form-label fw-semibold">

                            Select Branch

                        </label>


                        <select name="branch_id"
                                id="branchId"
                                class="form-select">

                            <option value="">

                                Select Branch

                            </option>

                            @foreach($branches as $branch)

                                <option value="{{ $branch->id }}">

                                    {{ $branch->name }}

                                    @if($branch->code)

                                        ({{ $branch->code }})

                                    @endif

                                </option>

                            @endforeach

                        </select>


                        <div class="form-text">

                            Only data belonging to the selected branch
                            will be included.

                        </div>

                    </div>


                    {{-- Current branch information --}}

                    <div class="col-12"
                         id="currentBranchInfo"
                         style="display:none;">

                        @if(session()->has('branch_id'))

                            @php

                                $currentBranch = $branches->firstWhere(
                                    'id',
                                    session('branch_id')
                                );

                            @endphp

                            <div class="alert alert-info mb-0">

                                <i class="bi bi-building me-2"></i>

                                Current selected branch:

                                <strong>

                                    {{ $currentBranch?->name ?? 'Selected Branch' }}

                                </strong>

                            </div>

                        @else

                            <div class="alert alert-warning mb-0">

                                <i class="bi bi-exclamation-triangle me-2"></i>

                                No branch is currently selected.

                                Please select a branch first or use
                                Full Salon Backup.

                            </div>

                        @endif

                    </div>


                    {{-- Info --}}

                    <div class="col-12">

                        <div class="alert alert-light border mb-0">

                            <div class="fw-semibold mb-2">

                                <i class="bi bi-info-circle me-2"></i>

                                Backup Information

                            </div>

                            <ul class="mb-0 small">

                                <li>

                                    <strong>Full Salon Backup:</strong>

                                    Includes all salon branches and salon data.

                                </li>

                                <li>

                                    <strong>Current Branch Backup:</strong>

                                    Includes the currently selected branch data.

                                </li>

                                <li>

                                    <strong>Selected Branch Backup:</strong>

                                    Includes data of the branch selected above.

                                </li>

                                <li>

                                    Common salon-level data is included
                                    in branch backups where applicable.

                                </li>

                            </ul>

                        </div>

                    </div>


                    {{-- Button --}}

                    <div class="col-12">

                        <button type="submit"
                                class="btn btn-primary"
                                id="createBackupBtn">

                            <i class="bi bi-database-add me-1"></i>

                            Create Backup

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        RESTORE BACKUP
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">

            <h6 class="mb-0 fw-semibold">

                <i class="bi bi-database-up me-2"></i>

                Restore Backup

            </h6>

        </div>


        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.settings.backup.restore') }}"
                  enctype="multipart/form-data"
                  id="restoreForm">

                @csrf


                <div class="row g-3 align-items-end">

                    <div class="col-md-8">

                        <label class="form-label fw-semibold">

                            SQL Backup File

                        </label>

                        <input type="file"
                               name="backup_file"
                               class="form-control"
                               accept=".sql,.txt"
                               required>

                        <div class="form-text">

                            Upload a previously generated SQL backup.

                            Maximum size: 50 MB.

                        </div>

                    </div>


                    <div class="col-md-4">

                        <button type="submit"
                                class="btn btn-warning w-100"
                                onclick="return confirmRestore();">

                            <i class="bi bi-database-up me-1"></i>

                            Restore Backup

                        </button>

                    </div>

                </div>


                <div class="alert alert-danger mt-3 mb-0">

                    <i class="bi bi-exclamation-triangle me-2"></i>

                    <strong>Warning:</strong>

                    Restoring a backup can overwrite existing database
                    records. Always create a current backup before restoring.

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        BACKUP HISTORY
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0 fw-semibold">

                    <i class="bi bi-clock-history me-2"></i>

                    Available Backups

                </h6>

                <span class="badge bg-secondary">

                    {{ $files->count() }}

                    Backup{{ $files->count() == 1 ? '' : 's' }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">#</th>

                            <th>Backup File</th>

                            <th>Type</th>

                            <th>Size</th>

                            <th>Created</th>

                            <th class="text-end px-3">

                                Actions

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($files as $file)

                            @php

                                $filename = $file->getFilename();

                                $lowerName = strtolower($filename);

                                $size = $file->getSize();

                                $modified = $file->getMTime();

                                if (str_contains($lowerName, 'full_salon')) {

                                    $backupLabel = 'Full Salon';

                                    $badgeClass = 'bg-primary';

                                } elseif (str_contains($lowerName, 'current_branch')) {

                                    $backupLabel = 'Current Branch';

                                    $badgeClass = 'bg-success';

                                } elseif (str_contains($lowerName, 'branch_')) {

                                    $backupLabel = 'Branch';

                                    $badgeClass = 'bg-info text-dark';

                                } else {

                                    $backupLabel = 'SQL Backup';

                                    $badgeClass = 'bg-secondary';

                                }

                            @endphp


                            <tr>

                                <td class="px-3">

                                    {{ $loop->iteration }}

                                </td>


                                <td>

                                    <div class="fw-semibold">

                                        <i class="bi bi-filetype-sql me-1"></i>

                                        {{ $filename }}

                                    </div>

                                </td>


                                <td>

                                    <span class="badge {{ $badgeClass }}">

                                        {{ $backupLabel }}

                                    </span>

                                </td>


                                <td>

                                    {{ number_format($size / 1024, 2) }}

                                    KB

                                </td>


                                <td>

                                    {{ date('d-m-Y H:i:s', $modified) }}

                                </td>


                                <td class="text-end px-3">

                                    <div class="d-flex justify-content-end gap-2">

                                        <a href="{{ route(
                                            'admin.settings.backup.download',
                                            ['file' => $filename]
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="bi bi-download"></i>

                                            Download

                                        </a>


                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.settings.backup.destroy',
                                                  ['file' => $filename]
                                              ) }}">

                                            @csrf

                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this backup?');">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-database-x fs-1 d-block mb-2"></i>

                                        No backups found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    PAGE CSS
========================================================== --}}

<style>

    .backup-page .card {

        border-radius: 12px;

    }


    .backup-page .card-header {

        padding: 16px 20px;

    }


    .backup-page .table th {

        white-space: nowrap;

        font-size: 13px;

        text-transform: uppercase;

        letter-spacing: .3px;

    }


    .backup-page .table td {

        font-size: 14px;

    }


    .backup-page .btn {

        border-radius: 7px;

    }

</style>


{{-- =========================================================
    PAGE JS
========================================================== --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const backupType = document.getElementById('backupType');

    const branchWrapper =
        document.getElementById('branchSelectWrapper');

    const branchId =
        document.getElementById('branchId');

    const currentBranchInfo =
        document.getElementById('currentBranchInfo');


    function updateBackupFields() {

        const type = backupType.value;


        branchWrapper.style.display =
            type === 'selected_branch'
                ? 'block'
                : 'none';


        currentBranchInfo.style.display =
            type === 'current_branch'
                ? 'block'
                : 'none';


        if (type !== 'selected_branch') {

            branchId.value = '';

        }


        if (type === 'selected_branch') {

            branchId.required = true;

        } else {

            branchId.required = false;

        }

    }


    backupType.addEventListener(
        'change',
        updateBackupFields
    );


    updateBackupFields();

});


function confirmRestore() {

    return confirm(
        'WARNING!\n\n' +
        'Restoring this backup can overwrite existing database data.\n\n' +
        'Please make sure you have created a current backup before continuing.\n\n' +
        'Do you want to continue?'
    );

}

</script>

@endsection