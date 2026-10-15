
@extends('admin.layout')

@section('title', 'Inventory Report | Salon Management')

@section('page-title', 'Inventory Report')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">

        <div>

            <h4 class="mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Inventory Report
            </h4>

            <small class="text-muted">
                Total Records: {{ $products->total() }}
            </small>

        </div>


        <div class="d-flex gap-2">

            <button type="button"
                    class="btn btn-outline-secondary"
                    onclick="window.print()">

                <i class="bi bi-printer me-1"></i>
                Print

            </button>


            <a href="{{ route('admin.reports.inventory.pdf', request()->query()) }}"
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
                  action="{{ route('admin.reports.inventory') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Product name, SKU or brand">

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Stock / Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Products
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                            <option value="low_stock"
                                {{ request('status') === 'low_stock' ? 'selected' : '' }}>
                                Low Stock
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


                        <a href="{{ route('admin.reports.inventory') }}"
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
        INVENTORY LIST
        IMPORTANT: inventory-list-print
    ========================================================== --}}
    <div class="card shadow-sm inventory-list-print">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="55">
                                #
                            </th>

                            <th>
                                Product
                            </th>

                            <th>
                                SKU
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Supplier
                            </th>

                            <th>
                                Purchase Price
                            </th>

                            <th>
                                Selling Price
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Unit
                            </th>

                            <th>
                                Min Stock
                            </th>

                            <th>
                                Expiry
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $index => $product)

                            <tr>

                                <td>
                                    {{ $products->firstItem() + $index }}
                                </td>


                                <td>

                                    <strong>
                                        {{ $product->name }}
                                    </strong>

                                    @if($product->brand)

                                        <div class="small text-muted">
                                            {{ $product->brand }}
                                        </div>

                                    @endif

                                </td>


                                <td>
                                    {{ $product->sku ?: '-' }}
                                </td>


                                <td>
                                    {{ $product->category?->name ?? '-' }}
                                </td>


                                <td>
                                    {{ $product->supplier?->name ?? '-' }}
                                </td>


                                <td>
                                    ₹{{ number_format((float) $product->purchase_price, 2) }}
                                </td>


                                <td>
                                    ₹{{ number_format((float) $product->selling_price, 2) }}
                                </td>


                                <td>

                                    @if((float) $product->stock <= (float) $product->minimum_stock)

                                        <span class="badge bg-danger">
                                            {{ $product->stock }}
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            {{ $product->stock }}
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $product->unit ?: '-' }}
                                </td>


                                <td>
                                    {{ $product->minimum_stock }}
                                </td>


                                <td>
                                    {{ $product->expiry_date?->format('d-m-Y') ?? '-' }}
                                </td>


                                <td>

                                    @if($product->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="12"
                                    class="text-center py-4 text-muted">

                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>

                                    No inventory products found.

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
        @if($products->hasPages())

            <div class="card-footer bg-white no-print">

                {{ $products->links() }}

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
       RESET HTML / BODY
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
       SHOW ONLY INVENTORY LIST
    ========================================================== */

    .inventory-list-print,
    .inventory-list-print * {

        visibility: visible !important;
    }


    /* =========================================================
       POSITION INVENTORY TABLE
    ========================================================== */

    .inventory-list-print {

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

    .inventory-list-print .card {

        border: none !important;
        box-shadow: none !important;

        margin: 0 !important;
        padding: 0 !important;
    }


    /* =========================================================
       TABLE RESPONSIVE RESET
    ========================================================== */

    .inventory-list-print .table-responsive {

        overflow: visible !important;

        width: 100% !important;
        max-width: 100% !important;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .inventory-list-print table {

        width: 100% !important;
        max-width: 100% !important;

        margin: 0 !important;

        font-size: 8px !important;

        border-collapse: collapse !important;
    }


    .inventory-list-print th,
    .inventory-list-print td {

        padding: 4px !important;

        border: 1px solid #000 !important;

        color: #000 !important;

        background: #fff !important;

        vertical-align: middle !important;
    }


    .inventory-list-print th {

        font-weight: 700 !important;

        background: #f2f2f2 !important;
    }


    /* =========================================================
       BADGES
    ========================================================== */

    .inventory-list-print .badge {

        border: none !important;

        background: transparent !important;

        color: #000 !important;

        font-weight: 600 !important;
    }


    /* =========================================================
       BRAND TEXT
    ========================================================== */

    .inventory-list-print .text-muted {

        color: #000 !important;
    }


    /* =========================================================
       PAGE SETTINGS
    ========================================================== */

    @page {

        size: landscape;

        margin: 8mm;
    }

}

</style>

@endsection

