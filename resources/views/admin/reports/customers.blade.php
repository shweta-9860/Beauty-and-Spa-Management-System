
@extends('admin.layout')

@section('title', 'Customer Report')

@section('page-title', 'Customer Report')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        MAIN CUSTOMER REPORT
    ========================================================== --}}
    <div class="card shadow-sm border-0">

        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center no-print">

            <div>

                <h5 class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    Customer List
                </h5>

                <small class="text-muted">
                    Total: {{ $customers->total() }}
                </small>

            </div>


            <div class="d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-secondary"
                    onclick="window.print()">

                    <i class="bi bi-printer me-1"></i>
                    Print

                </button>


                <a
                    href="{{ route('admin.reports.customers.pdf', request()->query()) }}"
                    class="btn btn-danger">

                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Download PDF

                </a>

            </div>

        </div>


        {{-- =====================================================
            FILTERS
        ====================================================== --}}
        <div class="card-body border-bottom no-print">

            <form method="GET"
                  action="{{ route('admin.reports.customers') }}">

                <div class="row g-2">

                    {{-- Search --}}
                    <div class="col-md-4">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search name, phone or email"
                            value="{{ request('search') }}">

                    </div>


                    {{-- Gender --}}
                    <div class="col-md-2">

                        <select name="gender"
                                class="form-select">

                            <option value="">
                                All Gender
                            </option>

                            <option value="male"
                                {{ request('gender') == 'male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option value="female"
                                {{ request('gender') == 'female' ? 'selected' : '' }}>
                                Female
                            </option>

                            <option value="other"
                                {{ request('gender') == 'other' ? 'selected' : '' }}>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-2">

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="1"
                                {{ request('status') === '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ request('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Date From --}}
                    <div class="col-md-2">

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}">

                    </div>


                    {{-- Date To --}}
                    <div class="col-md-2">

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12 mt-2">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="bi bi-search me-1"></i>
                            Filter

                        </button>


                        <a
                            href="{{ route('admin.reports.customers') }}"
                            class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- =====================================================
            CUSTOMER LIST
            IMPORTANT: customer-list-print
        ====================================================== --}}
        <div class="card-body p-0 customer-list-print">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="5%">
                                #
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Gender
                            </th>

                            <th>
                                Branch
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Registered
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($customers as $customer)

                            <tr>

                                <td>
                                    {{ $customers->firstItem() + $loop->index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $customer->name ?? '-' }}
                                    </strong>

                                </td>


                                <td>
                                    {{ $customer->phone ?? '-' }}
                                </td>


                                <td>
                                    {{ $customer->email ?? '-' }}
                                </td>


                                <td>
                                    {{ ucfirst($customer->gender ?? '-') }}
                                </td>


                                <td>
                                    {{ $customer->branch->name ?? '-' }}
                                </td>


                                <td>

                                    @if($customer->status)

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

                                    {{ $customer->created_at
                                        ? $customer->created_at->format('d-m-Y')
                                        : '-' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8"
                                    class="text-center py-4 text-muted">

                                    <i class="bi bi-people fs-3 d-block mb-2"></i>

                                    No customers found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if($customers->hasPages())

            <div class="card-footer bg-white no-print">

                {{ $customers->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
    PRINT CSS
============================================================= --}}

<style>

@media print {

    /* =========================================================
       HIDE SIDEBAR / HEADER / FILTERS / BUTTONS / PAGINATION
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
       RESET BODY
    ========================================================== */

    html,
    body {

        margin: 0 !important;
        padding: 0 !important;

        width: 100% !important;
        min-width: 100% !important;

        background: #fff !important;
    }


    /* =========================================================
       RESET ADMINLTE WRAPPERS
    ========================================================== */

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
       SHOW ONLY CUSTOMER LIST
    ========================================================== */

    .customer-list-print,
    .customer-list-print * {

        visibility: visible !important;
    }


    /* =========================================================
       POSITION CUSTOMER TABLE
    ========================================================== */

    .customer-list-print {

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

    .customer-list-print .card {

        border: none !important;
        box-shadow: none !important;

        margin: 0 !important;
        padding: 0 !important;
    }


    /* =========================================================
       TABLE RESPONSIVE RESET
    ========================================================== */

    .customer-list-print .table-responsive {

        overflow: visible !important;

        width: 100% !important;
        max-width: 100% !important;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .customer-list-print table {

        width: 100% !important;
        max-width: 100% !important;

        margin: 0 !important;

        font-size: 10px !important;

        border-collapse: collapse !important;
    }


    .customer-list-print th,
    .customer-list-print td {

        padding: 5px !important;

        border: 1px solid #000 !important;

        color: #000 !important;

        background: #fff !important;

        vertical-align: middle !important;
    }


    .customer-list-print th {

        font-weight: 700 !important;

        background: #f2f2f2 !important;
    }


    /* =========================================================
       BADGES
    ========================================================== */

    .customer-list-print .badge {

        border: none !important;

        background: transparent !important;

        color: #000 !important;

        font-weight: 600 !important;
    }


    /* =========================================================
       PAGE SETTINGS
    ========================================================== */

    @page {

        size: landscape;

        margin: 10mm;
    }

}

</style>

@endsection

