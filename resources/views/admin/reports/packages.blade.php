
@extends('admin.layout')

@section('title', 'Package Report | Salon Management')

@section('page-title', 'Package Report')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Package Report
            </h4>

            <small class="text-muted">
                Total Records: {{ $packages->total() }}
            </small>
        </div>

        <div class="d-flex gap-2 no-print">

            <button type="button"
                    class="btn btn-outline-secondary"
                    onclick="window.print()">
                <i class="bi bi-printer me-1"></i>
                Print
            </button>

            <a href="{{ route('admin.reports.packages.pdf', request()->query()) }}"
               class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Download PDF
            </a>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card shadow-sm mb-3 no-print">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.reports.packages') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Package name, code or description">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search me-1"></i>
                            Search

                        </button>

                        <a href="{{ route('admin.reports.packages') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        PACKAGE LIST
        IMPORTANT: package-list-print
    ========================================================== --}}
    <div class="card shadow-sm package-list-print">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">#</th>

                            <th>
                                Package Name
                            </th>

                            <th>
                                Code
                            </th>

                            <th>
                                Description
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Validity
                            </th>

                            <th>
                                Total Uses
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($packages as $index => $package)

                            <tr>

                                <td>
                                    {{ $packages->firstItem() + $index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $package->name }}
                                    </strong>

                                </td>


                                <td>
                                    {{ $package->code ?: '-' }}
                                </td>


                                <td>
                                    {{ $package->description ?: '-' }}
                                </td>


                                <td>
                                    ₹{{ number_format((float) $package->price, 2) }}
                                </td>


                                <td>
                                    {{ $package->validity_days }} days
                                </td>


                                <td>
                                    {{ $package->total_uses }}
                                </td>


                                <td>

                                    @if($package->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $package->created_at?->format('d-m-Y') ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-4 text-muted">

                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                    No packages found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($packages->hasPages())

            <div class="card-footer bg-white no-print">

                {{ $packages->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    PRINT CSS
========================================================= --}}

<style>

@media print {

    /* =========================================================
       HIDE SIDEBAR / HEADER / FILTERS / BUTTONS
    ========================================================== */

    .main-sidebar,
    .sidebar,
    aside,
    .app-sidebar,
    .sidebar-wrapper,
    .main-header,
    .app-header,
    .navbar,
    header,
    nav,
    footer,
    .content-header,
    .page-header,
    .no-print,
    .card-footer {
        display: none !important;
        visibility: hidden !important;
    }


    /* =========================================================
       RESET ADMINLTE WRAPPERS
    ========================================================== */

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        background: #fff !important;
    }


    .app-wrapper,
    .app-main,
    .content-wrapper,
    .main-content,
    main,
    .content,
    .container,
    .container-fluid {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        left: 0 !important;
        right: 0 !important;
    }


    /* =========================================================
       HIDE EVERYTHING FIRST
    ========================================================== */

    body * {
        visibility: hidden !important;
    }


    /* =========================================================
       SHOW ONLY PACKAGE LIST
    ========================================================== */

    .package-list-print,
    .package-list-print * {
        visibility: visible !important;
    }


    .package-list-print {
        position: absolute !important;

        top: 0 !important;
        left: 0 !important;

        width: 100% !important;
        max-width: 100% !important;

        margin: 0 !important;
        padding: 0 !important;

        border: none !important;
        box-shadow: none !important;

        background: #fff !important;
    }


    /* =========================================================
       REMOVE CARD STYLING
    ========================================================== */

    .package-list-print .card {
        border: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }


    /* =========================================================
       TABLE RESPONSIVE RESET
    ========================================================== */

    .package-list-print .table-responsive {
        overflow: visible !important;
        width: 100% !important;
        max-width: 100% !important;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .package-list-print table {
        width: 100% !important;
        max-width: 100% !important;

        font-size: 10px !important;

        border-collapse: collapse !important;

        margin: 0 !important;
    }


    .package-list-print th,
    .package-list-print td {
        padding: 5px !important;

        border: 1px solid #000 !important;

        color: #000 !important;

        background: #fff !important;

        vertical-align: middle !important;
    }


    .package-list-print th {
        font-weight: 700 !important;
        background: #f2f2f2 !important;
    }


    /* =========================================================
       BADGES
    ========================================================== */

    .package-list-print .badge {
        border: none !important;

        color: #000 !important;

        background: transparent !important;

        font-weight: 600 !important;
    }


    /* =========================================================
       PAGE
    ========================================================== */

    @page {
        size: landscape;
        margin: 10mm;
    }

}

</style>

@endsection

