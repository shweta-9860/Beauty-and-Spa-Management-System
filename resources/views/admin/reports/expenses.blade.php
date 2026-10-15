@extends('admin.layout')

@section('title', 'Expense Report | Salon Management')
@section('page-title', 'Expense Report')

@section('content')

<div class="container-fluid px-0">


{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex justify-content-between align-items-center mb-4 no-print">

    <div>

        <h4 class="fw-bold mb-1">

            <i class="bi bi-cash-stack me-2"></i>

            Expense Report

        </h4>

        <p class="text-muted mb-0">

            Expense List

        </p>

    </div>


    <div class="d-flex gap-2">

        {{-- Print --}}
        <button
            type="button"
            onclick="window.print()"
            class="btn btn-dark">

            <i class="bi bi-printer me-1"></i>

            Print

        </button>


        {{-- Download PDF --}}
        <a
            href="{{ route('admin.reports.expenses.pdf', request()->query()) }}"
            class="btn btn-danger">

            <i class="bi bi-file-earmark-pdf me-1"></i>

            Download PDF

        </a>

    </div>

</div>


{{-- =========================================================
    FILTERS
========================================================== --}}
<div class="card border-0 shadow-sm mb-4 no-print">

    <div class="card-header bg-white border-0 py-3">

        <h6 class="fw-bold mb-0">

            <i class="bi bi-funnel me-2"></i>

            Expense Filters

        </h6>

    </div>


    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.reports.expenses') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Title / Paid To / Reference"
                        value="{{ request('search') }}">

                </div>


                {{-- Category --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Category
                    </label>

                    <select
                        name="category_id"
                        class="form-select">

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Payment Method --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Payment Method
                    </label>

                    <select
                        name="payment_method"
                        class="form-select">

                        <option value="">
                            All Methods
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


                {{-- Date From --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Date From
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        class="form-control"
                        value="{{ request('date_from') }}">

                </div>


                {{-- Date To --}}
                <div class="col-md-2">

                    <label class="form-label fw-semibold">
                        Date To
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        class="form-control"
                        value="{{ request('date_to') }}">

                </div>


                {{-- Search --}}
                <div class="col-md-1 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-primary w-100">

                        <i class="bi bi-search"></i>

                    </button>

                </div>

            </div>


            {{-- Reset --}}
            <div class="mt-3">

                <a
                    href="{{ route('admin.reports.expenses') }}"
                    class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-clockwise me-1"></i>

                    Reset

                </a>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    EXPENSE SUMMARY
========================================================== --}}
<div class="row g-3 mb-4 no-print">


    {{-- Total Records --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Total Records
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $expenses->total() }}
                        </h4>

                    </div>

                    <div class="fs-2 text-primary">

                        <i class="bi bi-receipt"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Total Expense --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Total Expense
                        </div>

                        <h4 class="fw-bold mb-0">
                            ₹{{ number_format((float) $totalExpenses, 2) }}
                        </h4>

                    </div>

                    <div class="fs-2 text-danger">

                        <i class="bi bi-cash-stack"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Categories --}}
    <div class="col-md-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="text-muted small">
                            Expense Categories
                        </div>

                        <h4 class="fw-bold mb-0">
                            {{ $categorySummary->count() }}
                        </h4>

                    </div>

                    <div class="fs-2 text-warning">

                        <i class="bi bi-bar-chart"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    CATEGORY SUMMARY
========================================================== --}}
@if($categorySummary->count())

    <div class="card border-0 shadow-sm mb-4 no-print">

        <div class="card-header bg-white border-0 py-3">

            <h6 class="fw-bold mb-0">

                <i class="bi bi-bar-chart me-2"></i>

                Category-wise Expense Summary

            </h6>

        </div>


        <div class="card-body">

            <div class="row g-3">

                @foreach($categorySummary as $summary)

                    <div class="col-md-3">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small">

                                {{ $summary->category->name ?? 'Uncategorized' }}

                            </div>

                            <div class="fs-5 fw-bold">

                                ₹{{ number_format(
                                    (float) $summary->total,
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

@endif


{{-- =========================================================
    EXPENSE LIST
    IMPORTANT: expense-list-print
========================================================== --}}
<div class="card border-0 shadow-sm expense-list-print">

    {{-- This header will NOT print --}}
    <div class="card-header bg-white border-0 py-3 no-print">

        <div class="d-flex justify-content-between align-items-center">

            <h6 class="fw-bold mb-0">

                <i class="bi bi-list-ul me-2"></i>

                Expense List

            </h6>


            <span class="badge bg-primary">

                Total:
                {{ $expenses->total() }}

            </span>

        </div>

    </div>


    {{-- ONLY THIS TABLE WILL PRINT --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>#</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Branch</th>
                        <th>Paid To</th>
                        <th>Payment</th>
                        <th>Reference</th>
                        <th class="text-end">Amount</th>
                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($expenses as $expense)

                        <tr>

                            {{-- Number --}}
                            <td>

                                {{ $expenses->firstItem() + $loop->index }}

                            </td>


                            {{-- Date --}}
                            <td>

                                @if($expense->expense_date)

                                    {{ $expense->expense_date->format('d-m-Y') }}

                                @else

                                    -

                                @endif

                            </td>


                            {{-- Title --}}
                            <td>

                                <div class="fw-semibold">

                                    {{ $expense->title }}

                                </div>

                            </td>


                            {{-- Category --}}
                            <td>

                                {{ $expense->category?->name ?? '-' }}

                            </td>


                            {{-- Branch --}}
                            <td>

                                {{ $expense->branch?->name ?? 'All Branches' }}

                            </td>


                            {{-- Paid To --}}
                            <td>

                                {{ $expense->paid_to ?? '-' }}

                            </td>


                            {{-- Payment --}}
                            <td>

                                {{ ucfirst(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $expense->payment_method ?? '-'
                                    )
                                ) }}

                            </td>


                            {{-- Reference --}}
                            <td>

                                {{ $expense->reference_no ?? '-' }}

                            </td>


                            {{-- Amount --}}
                            <td class="text-end">

                                <strong>

                                    ₹{{ number_format(
                                        (float) $expense->amount,
                                        2
                                    ) }}

                                </strong>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($expense->status)

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

                            <td
                                colspan="10"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-cash-stack fs-1 d-block mb-2"></i>

                                No expenses found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                {{-- Total --}}
                @if($expenses->count())

                    <tfoot>

                        <tr class="table-light fw-bold">

                            <td colspan="8" class="text-end">

                                Total Expense

                            </td>

                            <td class="text-end">

                                ₹{{ number_format(
                                    (float) $expenses->sum('amount'),
                                    2
                                ) }}

                            </td>

                            <td></td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </div>


    {{-- Pagination --}}
    @if($expenses->hasPages())

        <div class="card-footer bg-white no-print">

            {{ $expenses->appends(request()->query())->links() }}

        </div>

    @endif

</div>


</div>

{{-- =========================================================
PRINT CSS
ONLY EXPENSE TABLE WILL PRINT
========================================================= --}}

<style>

@media print {

    /* Hide complete page first */
    body * {
        visibility: hidden !important;
    }


    /* Show ONLY expense list */
    .expense-list-print,
    .expense-list-print * {
        visibility: visible !important;
    }


    /* Move expense list to top-left */
    .expense-list-print {

        position: absolute !important;

        left: 0 !important;

        top: 0 !important;

        width: 100% !important;

        margin: 0 !important;

        padding: 0 !important;

        border: none !important;

        box-shadow: none !important;

    }


    /* Hide Expense List card header */
    .expense-list-print .card-header {

        display: none !important;

    }


    /* Hide pagination */
    .expense-list-print .card-footer {

        display: none !important;

    }


    /* Table container */
    .expense-list-print .card-body {

        padding: 0 !important;

        margin: 0 !important;

    }


    .expense-list-print .table-responsive {

        overflow: visible !important;

    }


    /* Table */
    .expense-list-print table {

        width: 100% !important;

        border-collapse: collapse !important;

        margin: 0 !important;

        font-size: 10px !important;

    }


    /* Table cells */
    .expense-list-print th,
    .expense-list-print td {

        border: 1px solid #000 !important;

        padding: 5px !important;

        color: #000 !important;

        background: #fff !important;

    }


    /* Table header */
    .expense-list-print thead th {

        background: #eeeeee !important;

        color: #000 !important;

        font-weight: 700 !important;

    }


    /* Total row */
    .expense-list-print tfoot td {

        background: #eeeeee !important;

        font-weight: 700 !important;

    }


    /* Don't break rows */
    .expense-list-print tr {

        page-break-inside: avoid !important;

    }


    /* Remove badge colors in print */
    .expense-list-print .badge {

        background: transparent !important;

        color: #000 !important;

        border: none !important;

        padding: 0 !important;

        font-weight: 600 !important;

    }


    /* Landscape A4 */
    @page {

        size: landscape;

        margin: 10mm;

    }

}

</style>

@endsection
