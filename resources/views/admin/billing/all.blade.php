@extends('admin.layout')

@section('title', 'All Bills | Salon Management')

@section('page-title', 'All Bills')

@section('content')

<div class="billing-page">

    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="billing-alert billing-alert-success alert alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle-fill me-2"></i>

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
    @if(session('error'))

        <div class="billing-alert billing-alert-danger alert alert-dismissible fade show"
             role="alert">

            <i class="bi bi-exclamation-triangle-fill me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif



    {{-- =========================================================
         WHITE 3D PAGE BANNER
    ========================================================== --}}
    <div class="billing-banner">

        <div class="billing-banner-glow billing-glow-one"></div>
        <div class="billing-banner-glow billing-glow-two"></div>
        <div class="billing-banner-glow billing-glow-three"></div>

        <div class="billing-banner-content">

            <div class="billing-heading">

                <div class="billing-heading-line"></div>

                <div>

                    <h3 class="billing-title mb-1">
                        All Bills
                    </h3>

                    <p class="billing-subtitle mb-0">
                        View and manage all completed salon bills.
                    </p>

                </div>

            </div>


            <div class="billing-header-actions">

                <a href="{{ route('admin.billing.create') }}"
                   class="billing-primary-btn">

                    <i class="bi bi-plus-lg"></i>

                    <span>
                        Create New Bill
                    </span>

                </a>


                <a href="{{ route('admin.billing.held') }}"
                   class="billing-secondary-btn">

                    <i class="bi bi-pause-circle"></i>

                    <span>
                        Held Bills
                    </span>

                </a>

            </div>

        </div>

    </div>



    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}
    <div class="row g-3 billing-summary-row">

        {{-- Total Bills --}}
        <div class="col-xl-3 col-md-6">

            <div class="billing-summary-card summary-purple">

                <div class="summary-card-content">

                    <div class="summary-icon">

                        <i class="bi bi-receipt"></i>

                    </div>

                    <div>

                        <div class="summary-label">
                            Total Bills
                        </div>

                        <div class="summary-value">

                            {{ number_format($summary['total_bills'] ?? 0) }}

                        </div>

                    </div>

                </div>

                <div class="summary-bottom-line"></div>

            </div>

        </div>



        {{-- Total Sales --}}
        <div class="col-xl-3 col-md-6">

            <div class="billing-summary-card summary-blue">

                <div class="summary-card-content">

                    <div class="summary-icon">

                        <i class="bi bi-currency-rupee"></i>

                    </div>

                    <div>

                        <div class="summary-label">
                            Total Sales
                        </div>

                        <div class="summary-value">

                            ₹{{ number_format($summary['total_sales'] ?? 0, 2) }}

                        </div>

                    </div>

                </div>

                <div class="summary-bottom-line"></div>

            </div>

        </div>



        {{-- Paid --}}
        <div class="col-xl-3 col-md-6">

            <div class="billing-summary-card summary-green">

                <div class="summary-card-content">

                    <div class="summary-icon">

                        <i class="bi bi-check-circle"></i>

                    </div>

                    <div>

                        <div class="summary-label">
                            Paid Amount
                        </div>

                        <div class="summary-value">

                            ₹{{ number_format($summary['paid_amount'] ?? 0, 2) }}

                        </div>

                    </div>

                </div>

                <div class="summary-bottom-line"></div>

            </div>

        </div>



        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">

            <div class="billing-summary-card summary-pink">

                <div class="summary-card-content">

                    <div class="summary-icon">

                        <i class="bi bi-hourglass-split"></i>

                    </div>

                    <div>

                        <div class="summary-label">
                            Pending Amount
                        </div>

                        <div class="summary-value">

                            ₹{{ number_format($summary['pending_amount'] ?? 0, 2) }}

                        </div>

                    </div>

                </div>

                <div class="summary-bottom-line"></div>

            </div>

        </div>

    </div>



    {{-- =========================================================
         FILTER CARD
    ========================================================== --}}
    <div class="billing-card billing-filter-card">

        <div class="billing-card-header">

            <div>

                <h6 class="billing-card-title mb-1">
                    Filter Bills
                </h6>

                <span class="billing-card-subtitle">
                    Search and filter billing records
                </span>

            </div>

            <div class="filter-header-dot"></div>

        </div>


        <div class="billing-card-body">

            <form method="GET"
                  action="{{ route('admin.billing.all') }}">

                <div class="row g-3">


                    {{-- Search --}}
                    <div class="col-lg-4 col-md-6">

                        <label class="billing-label">
                            Search
                        </label>

                        <div class="billing-input-wrap">

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="billing-input"
                                placeholder="Invoice, customer, phone..."
                            >

                        </div>

                    </div>



                    {{-- Branch --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="billing-label">
                            Branch
                        </label>

                        <select
                            name="branch_id"
                            class="billing-input billing-select"
                        >

                            <option value="">
                                All Branches
                            </option>

                            @foreach($branches as $branch)

                                <option
                                    value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Payment Status --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="billing-label">
                            Payment Status
                        </label>

                        <select
                            name="payment_status"
                            class="billing-input billing-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="paid"
                                {{ request('payment_status') === 'paid' ? 'selected' : '' }}
                            >
                                Paid
                            </option>

                            <option
                                value="partial"
                                {{ request('payment_status') === 'partial' ? 'selected' : '' }}
                            >
                                Partial
                            </option>

                            <option
                                value="pending"
                                {{ request('payment_status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                        </select>

                    </div>



                    {{-- From Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="billing-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ request('from_date') }}"
                            class="billing-input"
                        >

                    </div>



                    {{-- To Date --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="billing-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ request('to_date') }}"
                            class="billing-input"
                        >

                    </div>



                    {{-- Buttons --}}
                    <div class="col-12">

                        <div class="billing-filter-actions">

                            <button
                                type="submit"
                                class="billing-apply-btn"
                            >

                                <i class="bi bi-funnel"></i>

                                <span>
                                    Apply Filters
                                </span>

                            </button>


                            <a
                                href="{{ route('admin.billing.all') }}"
                                class="billing-reset-btn"
                            >

                                <i class="bi bi-arrow-counterclockwise"></i>

                                <span>
                                    Reset
                                </span>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
         BILLING HISTORY CARD
    ========================================================== --}}
    <div class="billing-card billing-history-card">

        <div class="billing-history-header">

            <div class="billing-history-heading">

                <div class="history-heading-accent"></div>

                <div>

                    <h5 class="billing-history-title mb-1">
                        Billing History
                    </h5>

                    <small class="billing-history-subtitle">
                        Showing completed bills
                    </small>

                </div>

            </div>


            <div class="billing-record-count">

                @if($bills->total() > 0)

                    Showing
                    {{ $bills->firstItem() }}
                    -
                    {{ $bills->lastItem() }}
                    of
                    {{ $bills->total() }}

                @else

                    No bills found

                @endif

            </div>

        </div>



        <div class="billing-table-body">

            @if($bills->count() > 0)

                <div class="table-responsive">

                    <table class="table billing-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4">
                                    #
                                </th>

                                <th>
                                    Invoice
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Branch
                                </th>

                                <th>
                                    Services
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Paid
                                </th>

                                <th>
                                    Pending
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>



                        <tbody>

                            @foreach($bills as $bill)

                                <tr>

                                    {{-- ID --}}
                                    <td class="ps-4 billing-id">

                                        {{ $bill->id }}

                                    </td>



                                    {{-- Invoice --}}
                                    <td>

                                        <span class="invoice-number">

                                            {{ $bill->invoice_number }}

                                        </span>

                                    </td>



                                    {{-- Date --}}
                                    <td>

                                        @if($bill->bill_date)

                                            <span class="billing-date">

                                                {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>



                                    {{-- Customer --}}
                                    <td>

                                        @if($bill->customer)

                                            <div class="customer-name">

                                                {{ $bill->customer->name }}

                                            </div>

                                            @if($bill->customer->phone)

                                                <div class="customer-phone">

                                                    {{ $bill->customer->phone }}

                                                </div>

                                            @endif

                                        @else

                                            <span class="walk-in-customer">

                                                Walk-in Customer

                                            </span>

                                        @endif

                                    </td>



                                    {{-- Branch --}}
                                    <td>

                                        @if($bill->branch)

                                            <span class="branch-badge">

                                                {{ $bill->branch->name }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>



                                    {{-- Services --}}
                                    <td>

                                        @if($bill->items && $bill->items->count())

                                            <div class="service-list">

                                                @foreach($bill->items->take(2) as $item)

                                                    <div class="service-item">

                                                        {{ $item->item_name }}

                                                        <span class="service-quantity">

                                                            × {{ $item->quantity }}

                                                        </span>

                                                    </div>

                                                @endforeach


                                                @if($bill->items->count() > 2)

                                                    <small class="service-more">

                                                        +{{ $bill->items->count() - 2 }}
                                                        more

                                                    </small>

                                                @endif

                                            </div>

                                        @else

                                            <span class="billing-muted">
                                                No services
                                            </span>

                                        @endif

                                    </td>



                                    {{-- Total --}}
                                    <td>

                                        <strong class="amount-total">

                                            ₹{{ number_format((float) $bill->grand_total, 2) }}

                                        </strong>

                                    </td>



                                    {{-- Paid --}}
                                    <td>

                                        <span class="amount-paid">

                                            ₹{{ number_format((float) $bill->paid_amount, 2) }}

                                        </span>

                                    </td>



                                    {{-- Pending --}}
                                    <td>

                                        @if((float) $bill->pending_amount > 0)

                                            <span class="amount-pending">

                                                ₹{{ number_format((float) $bill->pending_amount, 2) }}

                                            </span>

                                        @else

                                            <span class="amount-zero">

                                                ₹0.00

                                            </span>

                                        @endif

                                    </td>



                                    {{-- Payment Status --}}
                                    <td>

                                        @php

                                            $paymentStatus = strtolower(
                                                $bill->payment_status ?? 'pending'
                                            );

                                        @endphp


                                        @if($paymentStatus === 'paid')

                                            <span class="payment-status payment-paid">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Paid

                                            </span>

                                        @elseif($paymentStatus === 'partial')

                                            <span class="payment-status payment-partial">

                                                <i class="bi bi-clock-history me-1"></i>

                                                Partial

                                            </span>

                                        @else

                                            <span class="payment-status payment-pending">

                                                <i class="bi bi-hourglass-split me-1"></i>

                                                Pending

                                            </span>

                                        @endif

                                    </td>



                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <div class="dropdown billing-actions-dropdown">

                                            <button
                                                class="billing-more-btn"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                title="Actions"
                                            >

                                                <i class="bi bi-three-dots"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end billing-dropdown-menu">


                                                {{-- Invoice --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.billing.invoice', $bill->id) }}"
                                                    >

                                                        <i class="bi bi-receipt me-2"></i>

                                                        View Invoice

                                                    </a>

                                                </li>



                                                {{-- Receipt --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.billing.receipt', $bill->id) }}"
                                                        target="_blank"
                                                    >

                                                        <i class="bi bi-printer me-2"></i>

                                                        Print Receipt

                                                    </a>

                                                </li>



                                                {{-- Pay Remaining --}}
                                                @if((float) $bill->pending_amount > 0)

                                                    <li>

                                                        <a
                                                            class="dropdown-item billing-pay-remaining"
                                                            href="{{ route('admin.billing.remaining-payment', $bill->id) }}"
                                                        >

                                                            <i class="bi bi-credit-card me-2"></i>

                                                            Pay Remaining

                                                        </a>

                                                    </li>

                                                @endif



                                                {{-- Delete Bill --}}
                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>

                                                <li>

                                                    <form
                                                        action="{{ route('admin.billing.destroy', $bill->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this bill?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item billing-delete-item border-0 bg-transparent w-100 text-start"
                                                        >

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete Bill

                                                        </button>

                                                    </form>

                                                </li>



                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>



                                                {{-- Create New --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.billing.create') }}"
                                                    >

                                                        <i class="bi bi-plus-circle me-2"></i>

                                                        New Bill

                                                    </a>

                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}

                <div class="billing-empty-state">

                    <div class="billing-empty-icon">

                        <i class="bi bi-receipt"></i>

                    </div>

                    <h5>
                        No Bills Found
                    </h5>

                    <p>
                        There are no completed bills matching your filters.
                    </p>

                    <a
                        href="{{ route('admin.billing.create') }}"
                        class="billing-primary-btn"
                    >

                        <i class="bi bi-plus-lg"></i>

                        <span>
                            Create First Bill
                        </span>

                    </a>

                </div>

            @endif

        </div>



        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($bills->hasPages())

            <div class="billing-pagination">

                {{ $bills->links() }}

            </div>

        @endif

    </div>

</div>



{{-- =============================================================
     BILLING PAGE CSS
============================================================= --}}
<style>

    /*
    |--------------------------------------------------------------------------
    | Main Page
    |--------------------------------------------------------------------------
    */

    .billing-page {
        width: 100%;
        padding: 4px 2px 30px;
    }



    /*
    |--------------------------------------------------------------------------
    | Alerts
    |--------------------------------------------------------------------------
    */

    .billing-alert {
        border: 0;
        border-radius: 15px;
        margin-bottom: 18px;

        box-shadow:
            0 12px 28px rgba(72, 58, 110, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.85);
    }


    .billing-alert-success {
        background: #effbf5;
        color: #34865f;
    }


    .billing-alert-danger {
        background: #fff1f4;
        color: #bd6374;
    }



    /*
    |--------------------------------------------------------------------------
    | WHITE 3D BANNER
    |--------------------------------------------------------------------------
    */

    .billing-banner {
        position: relative;
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #eeeaf6;

        border-radius: 26px;

        padding: 28px 30px;

        margin-bottom: 22px;

        box-shadow:
            0 20px 48px rgba(75, 60, 120, 0.09),
            0 7px 18px rgba(75, 60, 120, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.98);
    }


    .billing-banner::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 40%;
        height: 4px;

        border-radius: 0 0 10px 0;

        background: linear-gradient(
            90deg,
            #7c5cff,
            #a888ff,
            #ff8fbf
        );
    }


    .billing-banner::after {
        content: "";

        position: absolute;

        width: 260px;
        height: 260px;

        right: -120px;
        bottom: -150px;

        border-radius: 50%;

        background: radial-gradient(
            circle,
            rgba(124, 92, 255, 0.10),
            rgba(124, 92, 255, 0)
        );

        pointer-events: none;
    }


    .billing-banner-content {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .billing-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }


    .billing-heading-line {
        width: 6px;
        height: 48px;

        border-radius: 10px;

        background: linear-gradient(
            180deg,
            #7c5cff,
            #ff86ba
        );

        box-shadow:
            0 6px 16px rgba(124, 92, 255, 0.20);
    }


    .billing-title {
        color: #2d293c;
    }


    .billing-subtitle {
        color: #888394;
    }


    .billing-banner-glow {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }


    .billing-glow-one {
        width: 70px;
        height: 70px;

        right: 22%;
        top: -30px;

        background: rgba(255, 126, 182, 0.07);
    }


    .billing-glow-two {
        width: 45px;
        height: 45px;

        right: 11%;
        bottom: 16px;

        background: rgba(110, 168, 254, 0.09);
    }


    .billing-glow-three {
        width: 22px;
        height: 22px;

        right: 31%;
        bottom: 19px;

        background: rgba(83, 184, 137, 0.10);
    }



    /*
    |--------------------------------------------------------------------------
    | Header Buttons
    |--------------------------------------------------------------------------
    */

    .billing-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    .billing-primary-btn,
    .billing-secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        min-height: 42px;

        padding: 9px 17px;

        border-radius: 13px;

        text-decoration: none;

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .billing-primary-btn {
        color: #ffffff;

        background: linear-gradient(
            135deg,
            #7c5cff,
            #9679ff
        );

        box-shadow:
            0 10px 22px rgba(124, 92, 255, 0.24),
            inset 0 1px 0 rgba(255, 255, 255, 0.30);
    }


    .billing-primary-btn:hover {
        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
            0 15px 28px rgba(124, 92, 255, 0.30);
    }


    .billing-secondary-btn {
        color: #8c6a27;

        background: linear-gradient(
            145deg,
            #fffdf8,
            #fff7e7
        );

        border: 1px solid #f0e2bd;

        box-shadow:
            0 7px 16px rgba(140, 106, 39, 0.08),
            inset 0 1px 0 #ffffff;
    }


    .billing-secondary-btn:hover {
        color: #7b5a1e;

        transform: translateY(-2px);

        box-shadow:
            0 12px 23px rgba(140, 106, 39, 0.12);
    }



    /*
    |--------------------------------------------------------------------------
    | Summary Cards
    |--------------------------------------------------------------------------
    */

    .billing-summary-row {
        margin-bottom: 22px;
    }


    .billing-summary-card {
        position: relative;
        overflow: hidden;

        height: 100%;

        min-height: 112px;

        padding: 20px;

        background: #ffffff;

        border: 1px solid #eeeaf6;

        border-radius: 20px;

        box-shadow:
            0 15px 34px rgba(75, 60, 120, 0.08),
            0 5px 12px rgba(75, 60, 120, 0.04),
            inset 0 1px 0 #ffffff;

        transition:
            transform .22s ease,
            box-shadow .22s ease;
    }


    .billing-summary-card:hover {
        transform: translateY(-4px);

        box-shadow:
            0 22px 42px rgba(75, 60, 120, 0.12),
            0 8px 17px rgba(75, 60, 120, 0.05),
            inset 0 1px 0 #ffffff;
    }


    .summary-card-content {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;

        gap: 15px;
    }


    .summary-icon {
        width: 52px;
        height: 52px;

        min-width: 52px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 15px;

        font-size: 21px;

        box-shadow:
            0 9px 18px rgba(75, 60, 120, 0.10),
            inset 0 1px 0 rgba(255, 255, 255, 0.90);
    }


    .summary-purple .summary-icon {
        color: #715bd2;
        background: linear-gradient(145deg, #f3efff, #eae4ff);
    }


    .summary-blue .summary-icon {
        color: #4e86c5;
        background: linear-gradient(145deg, #eef6ff, #e4f0ff);
    }


    .summary-green .summary-icon {
        color: #3e986d;
        background: linear-gradient(145deg, #edfbf4, #e0f6ea);
    }


    .summary-pink .summary-icon {
        color: #ce6d91;
        background: linear-gradient(145deg, #fff1f6, #ffe8f0);
    }


    .summary-label {
        color: #817b8e;
        margin-bottom: 3px;
    }


    .summary-value {
        color: #2f2b3c;
        font-weight: 700;
    }


    .summary-bottom-line {
        position: absolute;

        left: 20px;
        bottom: 0;

        width: 55px;
        height: 3px;

        border-radius: 5px 5px 0 0;
    }


    .summary-purple .summary-bottom-line {
        background: #8b72ed;
    }


    .summary-blue .summary-bottom-line {
        background: #79aaf0;
    }


    .summary-green .summary-bottom-line {
        background: #66bd91;
    }


    .summary-pink .summary-bottom-line {
        background: #ef8eaf;
    }



    /*
    |--------------------------------------------------------------------------
    | Common Cards
    |--------------------------------------------------------------------------
    */

    .billing-card {
        overflow: hidden;

        margin-bottom: 22px;

        background: #ffffff;

        border: 1px solid #eeeaf6;

        border-radius: 23px;

        box-shadow:
            0 18px 45px rgba(75, 60, 120, 0.08),
            0 6px 15px rgba(75, 60, 120, 0.045),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }



    /*
    |--------------------------------------------------------------------------
    | Filter Card
    |--------------------------------------------------------------------------
    */

    .billing-card-header {
        position: relative;

        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 18px 22px;

        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfbff
        );

        border-bottom: 1px solid #efebf6;
    }


    .billing-card-title {
        color: #332f42;
    }


    .billing-card-subtitle {
        color: #9690a1;
    }


    .filter-header-dot {
        width: 10px;
        height: 10px;

        border-radius: 50%;

        background: #a28cff;

        box-shadow:
            0 0 0 5px rgba(162, 140, 255, 0.09);
    }


    .billing-card-body {
        padding: 22px;
    }


    .billing-label {
        display: block;

        margin-bottom: 7px;

        color: #554f63;
    }


    .billing-input {
        width: 100%;

        min-height: 42px;

        padding: 9px 13px;

        color: #403b4c;

        background: #fbfaff;

        border: 1px solid #e5e0ef;

        border-radius: 12px;

        outline: none;

        box-shadow:
            inset 0 2px 5px rgba(74, 58, 120, 0.035),
            0 2px 5px rgba(74, 58, 120, 0.025);

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }


    .billing-input:focus {
        background: #ffffff;

        border-color: #a896ef;

        box-shadow:
            0 0 0 3px rgba(124, 92, 255, 0.08),
            0 8px 18px rgba(78, 61, 133, 0.07);
    }


    .billing-input::placeholder {
        color: #aaa5b3;
    }


    .billing-select {
        cursor: pointer;
    }


    .billing-filter-actions {
        display: flex;
        align-items: center;

        gap: 10px;

        padding-top: 3px;
    }


    .billing-apply-btn {
        min-height: 42px;

        padding: 9px 18px;

        border: 0;

        border-radius: 12px;

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #7c5cff,
            #967aff
        );

        box-shadow:
            0 9px 19px rgba(124, 92, 255, 0.22),
            inset 0 1px 0 rgba(255, 255, 255, 0.25);

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }


    .billing-apply-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 13px 25px rgba(124, 92, 255, 0.28);
    }


    .billing-reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        min-height: 42px;

        padding: 9px 17px;

        color: #726c7f;

        background: #ffffff;

        border: 1px solid #e3deec;

        border-radius: 12px;

        text-decoration: none;

        box-shadow:
            0 5px 12px rgba(72, 59, 105, 0.06),
            inset 0 1px 0 #ffffff;

        transition: .2s ease;
    }


    .billing-reset-btn:hover {
        color: #6653c9;

        border-color: #d6cef2;

        transform: translateY(-2px);

        box-shadow:
            0 9px 17px rgba(72, 59, 105, 0.10);
    }



    /*
    |--------------------------------------------------------------------------
    | Billing History Header
    |--------------------------------------------------------------------------
    */

    .billing-history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 18px 22px;

        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfbff
        );

        border-bottom: 1px solid #eeeaf6;
    }


    .billing-history-heading {
        display: flex;
        align-items: center;

        gap: 12px;
    }


    .history-heading-accent {
        width: 5px;
        height: 39px;

        border-radius: 8px;

        background: linear-gradient(
            180deg,
            #7c5cff,
            #ff8bbd
        );
    }


    .billing-history-title {
        color: #332f42;
    }


    .billing-history-subtitle {
        color: #9892a3;
    }


    .billing-record-count {
        padding: 7px 12px;

        color: #746b88;

        background: #f8f6fd;

        border: 1px solid #ebe6f5;

        border-radius: 10px;
    }



    /*
    |--------------------------------------------------------------------------
    | Billing Table
    |--------------------------------------------------------------------------
    */

    .billing-table-body {
        background: #ffffff;
    }


    .billing-table {
        min-width: 1250px;
    }


    .billing-table thead th {
        padding: 14px 15px;

        color: #6e6879;

        background: #faf8ff;

        border-bottom: 1px solid #ece8f5;

        white-space: nowrap;

        font-weight: 700;
    }


    .billing-table tbody tr {
        transition:
            background .2s ease,
            transform .2s ease;
    }


    .billing-table tbody tr:hover {
        background: linear-gradient(
            90deg,
            #fcfaff,
            #ffffff,
            #fffafd
        );
    }


    .billing-table tbody td {
        padding: 15px;

        color: #484352;

        border-color: #f1eef7;

        vertical-align: middle;
    }


    .billing-id {
        color: #9690a0 !important;
    }


    .invoice-number {
        display: inline-flex;

        padding: 6px 9px;

        color: #6b56c9;

        background: #f3efff;

        border: 1px solid #e6defd;

        border-radius: 8px;

        white-space: nowrap;

        box-shadow:
            0 4px 9px rgba(88, 69, 163, 0.06);
    }


    .billing-date {
        white-space: nowrap;
    }


    .customer-name {
        color: #342f40;
        font-weight: 600;
    }


    .customer-phone {
        margin-top: 4px;

        color: #8d8798;

        font-weight: 500;
    }


    .walk-in-customer {
        color: #96909f;
    }


    .branch-badge {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        color: #655e75;

        background: #f7f4ff;

        border: 1px solid #e9e3fa;

        border-radius: 9px;

        white-space: nowrap;

        box-shadow:
            0 4px 9px rgba(74, 60, 112, 0.045);
    }


    .service-list {
        max-width: 210px;
    }


    .service-item {
        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;

        margin-bottom: 3px;

        color: #494354;
    }


    .service-quantity {
        color: #9b95a5;
    }


    .service-more {
        color: #948d9e;
    }


    .billing-muted {
        color: #a09aa9;
    }


    .amount-total {
        color: #332e40;

        white-space: nowrap;
    }


    .amount-paid {
        color: #3d946b;

        font-weight: 600;

        white-space: nowrap;
    }


    .amount-pending {
        color: #d46d80;

        font-weight: 600;

        white-space: nowrap;
    }


    .amount-zero {
        color: #42966d;

        font-weight: 600;

        white-space: nowrap;
    }



    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    .payment-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 76px;

        padding: 6px 10px;

        border-radius: 20px;

        white-space: nowrap;

        box-shadow:
            0 5px 10px rgba(70, 58, 105, 0.055);
    }


    .payment-paid {
        color: #34865f;

        background: #eaf9f1;

        border: 1px solid #d5efdf;
    }


    .payment-partial {
        color: #a17421;

        background: #fff6df;

        border: 1px solid #f4e4ba;
    }


    .payment-pending {
        color: #bd6374;

        background: #fff0f3;

        border: 1px solid #f6dce2;
    }



    /*
    |--------------------------------------------------------------------------
    | Action Dropdown
    |--------------------------------------------------------------------------
    */

    .billing-more-btn {
        width: 35px;
        height: 35px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        color: #726b7e;

        background: #ffffff;

        border: 1px solid #e4dfed;

        border-radius: 10px;

        box-shadow:
            0 5px 11px rgba(71, 58, 105, 0.07),
            inset 0 1px 0 #ffffff;

        transition: .2s ease;
    }


    .billing-more-btn:hover,
    .billing-more-btn:focus {
        color: #6652c6;

        border-color: #d9d1f2;

        background: #f8f5ff;

        box-shadow:
            0 8px 16px rgba(71, 58, 105, 0.11);
    }


    .billing-dropdown-menu {
        min-width: 190px;

        padding: 7px;

        border: 1px solid #ece7f4;

        border-radius: 14px;

        box-shadow:
            0 18px 40px rgba(54, 44, 83, 0.15),
            0 5px 12px rgba(54, 44, 83, 0.06);
    }


    .billing-dropdown-menu .dropdown-item {
        padding: 9px 11px;

        border-radius: 9px;

        color: #514b5d;

        transition: .18s ease;
    }


    .billing-dropdown-menu .dropdown-item:hover {
        color: #6753c6;

        background: #f5f1ff;
    }


    .billing-dropdown-menu .billing-pay-remaining {
        color: #a47420;
    }


    .billing-dropdown-menu .billing-pay-remaining:hover {
        color: #8d651b;

        background: #fff7e5;
    }


    .billing-dropdown-menu .billing-delete-item {
        color: #c96b7d;
    }


    .billing-dropdown-menu .billing-delete-item:hover {
        color: #b95468;

        background: #fff0f3;
    }



    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .billing-empty-state {
        min-height: 350px;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        padding: 55px 20px;

        text-align: center;
    }


    .billing-empty-icon {
        width: 76px;
        height: 76px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 15px;

        color: #715bd2;

        background: linear-gradient(
            145deg,
            #f3efff,
            #eae4ff
        );

        border: 1px solid #e2dafb;

        border-radius: 50%;

        font-size: 30px;

        box-shadow:
            0 15px 30px rgba(100, 78, 184, 0.12),
            inset 0 1px 0 #ffffff;
    }


    .billing-empty-state h5 {
        color: #3b3647;
    }


    .billing-empty-state p {
        color: #9892a2;

        margin-bottom: 20px;
    }



    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    .billing-pagination {
        display: flex;
        justify-content: center;

        padding: 17px 20px;

        background: #ffffff;

        border-top: 1px solid #eeeaf6;
    }


    .billing-pagination nav {
        margin: 0;
    }


    .billing-pagination svg {
        width: 12px !important;
        height: 12px !important;
    }


    .billing-pagination a,
    .billing-pagination span {
        padding: 6px 10px !important;

        border-radius: 8px !important;
    }



    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {

        .billing-banner-content {
            flex-direction: column;
            align-items: flex-start;
        }


        .billing-header-actions {
            width: 100%;
        }


        .billing-header-actions a {
            flex: 1;
        }


        .billing-history-header {
            align-items: flex-start;
            flex-direction: column;
        }


        .billing-record-count {
            align-self: flex-start;
        }

    }


    @media (max-width: 575.98px) {

        .billing-page {
            padding-left: 0;
            padding-right: 0;
        }


        .billing-banner {
            padding: 22px 18px;

            border-radius: 20px;
        }


        .billing-card {
            border-radius: 19px;
        }


        .billing-card-body {
            padding: 17px;
        }


        .billing-header-actions {
            flex-direction: column;
        }


        .billing-header-actions a {
            width: 100%;
        }


        .billing-summary-card {
            padding: 17px;
        }


        .billing-history-header {
            padding: 16px;
        }


        .billing-pagination {
            overflow-x: auto;
            justify-content: flex-start;
        }

    }

</style>

@endsection