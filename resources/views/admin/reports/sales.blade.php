@extends('admin.layout')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center no-print">

            <div>
                <h5 class="mb-0">
                    <i class="bi bi-graph-up-arrow me-2"></i>
                    Sales List
                </h5>

                <small class="text-muted">
                    Total Records: {{ $sales->total() }}
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
                    href="{{ route('admin.reports.sales.pdf', request()->query()) }}"
                    class="btn btn-danger">

                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Download PDF

                </a>

            </div>

        </div>


        {{-- =========================================================
             FILTERS
        ========================================================== --}}
        <div class="card-body border-bottom no-print">

            <form
                method="GET"
                action="{{ route('admin.reports.sales') }}">

                <div class="row g-2">

                    {{-- Search --}}
                    <div class="col-md-3">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Invoice / Customer / Phone"
                            value="{{ request('search') }}">

                    </div>


                    {{-- Payment Method --}}
                    <div class="col-md-2">

                        <select
                            name="payment_method"
                            class="form-select">

                            <option value="">
                                All Payment Methods
                            </option>

                            <option
                                value="cash"
                                {{ request('payment_method') == 'cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option
                                value="upi"
                                {{ request('payment_method') == 'upi' ? 'selected' : '' }}>
                                UPI
                            </option>

                            <option
                                value="card"
                                {{ request('payment_method') == 'card' ? 'selected' : '' }}>
                                Card
                            </option>

                            <option
                                value="bank_transfer"
                                {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>

                            <option
                                value="online"
                                {{ request('payment_method') == 'online' ? 'selected' : '' }}>
                                Online
                            </option>

                        </select>

                    </div>


                    {{-- Payment Status --}}
                    <div class="col-md-2">

                        <select
                            name="payment_status"
                            class="form-select">

                            <option value="">
                                All Payment Status
                            </option>

                            <option
                                value="paid"
                                {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option
                                value="partial"
                                {{ request('payment_status') == 'partial' ? 'selected' : '' }}>
                                Partial
                            </option>

                            <option
                                value="pending"
                                {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                Pending
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


                    {{-- Search --}}
                    <div class="col-md-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="bi bi-search"></i>

                        </button>

                    </div>

                </div>


                {{-- Reset --}}
                <div class="mt-2">

                    <a
                        href="{{ route('admin.reports.sales') }}"
                        class="btn btn-outline-secondary btn-sm">

                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset

                    </a>

                </div>

            </form>

        </div>


        {{-- =========================================================
             SALES LIST
        ========================================================== --}}
        <div class="sales-list-print">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="5%">#</th>

                                <th>Invoice No</th>

                                <th>Date</th>

                                <th>Customer</th>

                                <th>Branch</th>

                                <th>Payment</th>

                                <th>Status</th>

                                <th class="text-end">
                                    Subtotal
                                </th>

                                <th class="text-end">
                                    Discount
                                </th>

                                <th class="text-end">
                                    Tax
                                </th>

                                <th class="text-end">
                                    Grand Total
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($sales as $sale)

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        {{ $sales->firstItem() + $loop->index }}
                                    </td>


                                    {{-- Invoice --}}
                                    <td>

                                        <strong>
                                            {{ $sale->invoice_number ?? '-' }}
                                        </strong>

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        @if($sale->bill_date)

                                            {{ \Carbon\Carbon::parse($sale->bill_date)->format('d-m-Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Customer --}}
                                    <td>

                                        @if($sale->customer)

                                            <div class="fw-semibold">
                                                {{ $sale->customer->name }}
                                            </div>

                                            @if($sale->customer->phone)

                                                <small class="text-muted">
                                                    {{ $sale->customer->phone }}
                                                </small>

                                            @endif

                                        @else

                                            Walk-in Customer

                                        @endif

                                    </td>


                                    {{-- Branch --}}
                                    <td>

                                        {{ $sale->branch?->name ?? 'All Branches' }}

                                    </td>


                                    {{-- Payment --}}
                                    <td>

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $sale->payment_method ?? '-'
                                            )
                                        ) }}

                                    </td>


                                    {{-- Payment Status --}}
                                    <td>

                                        @php
                                            $paymentStatus = strtolower(
                                                $sale->payment_status ?? 'pending'
                                            );
                                        @endphp

                                        @if($paymentStatus === 'paid')

                                            <span class="badge bg-success">
                                                Paid
                                            </span>

                                        @elseif($paymentStatus === 'partial')

                                            <span class="badge bg-warning text-dark">
                                                Partial
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Subtotal --}}
                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) (
                                                $sale->subtotal
                                                ?? $sale->service_total
                                                ?? 0
                                            ),
                                            2
                                        ) }}

                                    </td>


                                    {{-- Discount --}}
                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) (
                                                $sale->discount
                                                ?? $sale->overall_discount
                                                ?? 0
                                            ),
                                            2
                                        ) }}

                                    </td>


                                    {{-- Tax --}}
                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) (
                                                $sale->tax
                                                ?? $sale->tax_amount
                                                ?? 0
                                            ),
                                            2
                                        ) }}

                                    </td>


                                    {{-- Grand Total --}}
                                    <td class="text-end">

                                        <strong>

                                            ₹{{ number_format(
                                                (float) (
                                                    $sale->grand_total
                                                    ?? $sale->total_amount
                                                    ?? $sale->total
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </strong>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="11"
                                        class="text-center py-5 text-muted">

                                        <i class="bi bi-receipt fs-2 d-block mb-2"></i>

                                        No sales found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        {{-- =================================================
                             TOTAL
                        ================================================== --}}
                        @if($sales->count())

                            <tfoot>

                                <tr class="table-light fw-bold">

                                    <td
                                        colspan="7"
                                        class="text-end">

                                        Total

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) $sales->sum(function ($sale) {
                                                return $sale->subtotal
                                                    ?? $sale->service_total
                                                    ?? 0;
                                            }),
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) $sales->sum(function ($sale) {
                                                return $sale->discount
                                                    ?? $sale->overall_discount
                                                    ?? 0;
                                            }),
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) $sales->sum(function ($sale) {
                                                return $sale->tax
                                                    ?? $sale->tax_amount
                                                    ?? 0;
                                            }),
                                            2
                                        ) }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format(
                                            (float) $sales->sum(function ($sale) {
                                                return $sale->grand_total
                                                    ?? $sale->total_amount
                                                    ?? $sale->total
                                                    ?? 0;
                                            }),
                                            2
                                        ) }}

                                    </td>

                                </tr>

                            </tfoot>

                        @endif

                    </table>

                </div>

            </div>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($sales->hasPages())

            <div class="card-footer bg-white no-print">

                {{ $sales->appends(request()->query())->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     PRINT CSS
============================================================== --}}
@push('styles')

<style>

@media print {

    /*
    =========================================================
    HIDE ENTIRE ADMIN PANEL
    =========================================================
    */

    .main-sidebar,
    .sidebar,
    aside,
    .main-header,
    .navbar,
    header,
    nav,
    .app-sidebar,
    .app-header,
    .sidebar-wrapper,
    .content-header,
    .page-header,
    .no-print,
    .card-footer {
        display: none !important;
    }


    /*
    =========================================================
    BODY / CONTENT
    =========================================================
    */

    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        min-width: 100% !important;
        background: #fff !important;
    }


    /*
    =========================================================
    REMOVE ADMINLTE CONTENT OFFSET
    =========================================================
    */

    .app-wrapper,
    .app-main,
    .content-wrapper,
    .main-content,
    main,
    .content,
    .container-fluid {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        left: 0 !important;
        right: 0 !important;
    }


    /*
    =========================================================
    SHOW ONLY SALES LIST
    =========================================================
    */

    body * {
        visibility: hidden !important;
    }

    .sales-list-print,
    .sales-list-print * {
        visibility: visible !important;
    }


    .sales-list-print {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: #fff !important;
    }


    /*
    =========================================================
    TABLE
    =========================================================
    */

    .sales-list-print .card-body {
        padding: 0 !important;
        margin: 0 !important;
    }


    .sales-list-print .table-responsive {
        overflow: visible !important;
        width: 100% !important;
    }


    .sales-list-print table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 !important;
        font-size: 9px !important;
    }


    .sales-list-print th,
    .sales-list-print td {
        border: 1px solid #000 !important;
        padding: 5px !important;
        color: #000 !important;
        background: #fff !important;
    }


    .sales-list-print thead th {
        background: #eeeeee !important;
        color: #000 !important;
        font-weight: 700 !important;
    }


    .sales-list-print tfoot td {
        background: #eeeeee !important;
        color: #000 !important;
        font-weight: 700 !important;
    }


    .sales-list-print tr {
        page-break-inside: avoid !important;
    }


    .sales-list-print .badge {
        background: transparent !important;
        color: #000 !important;
        border: none !important;
        padding: 0 !important;
        font-weight: 600 !important;
    }


    /*
    =========================================================
    PRINT PAGE
    =========================================================
    */

    @page {
        size: landscape;
        margin: 10mm;
    }

}

</style>

@endpush

@endsection