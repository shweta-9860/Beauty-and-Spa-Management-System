@extends('admin.layout')

@section('title', 'Billing | Salon Management')

@section('page-title', 'Billing')

@section('content')

<div class="container-fluid px-0">

{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif


{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif


{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-danger shadow-sm">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================
     PAGE HEADER
========================== --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-receipt-cutoff me-2"></i>
            Billing
        </h4>

        <p class="text-muted mb-0">
            Manage salon bills, invoices and payments.
        </p>
    </div>

    <a href="{{ route('admin.billing.create') }}"
       class="btn btn-primary">

        <i class="bi bi-plus-lg me-1"></i>
        Create Bill

    </a>

</div>



{{-- =========================
     FILTER CARD
========================== --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 py-3">

        <h5 class="mb-0 fw-bold">
            <i class="bi bi-funnel me-2"></i>
            Filters
        </h5>

    </div>


    <div class="card-body">

        <form method="GET"
              action="{{ route('admin.billing.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Invoice number, customer name or phone">

                </div>


                {{-- Branch --}}
                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Branch
                    </label>

                    <select name="branch_id"
                            class="form-select">

                        <option value="">
                            All Branches
                        </option>

                        @foreach($branches as $branch)

                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>

                                {{ $branch->name }}

                                @if($branch->code)
                                    ({{ $branch->code }})
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Payment Status --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Payment Status
                    </label>

                    <select name="payment_status"
                            class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option value="pending"
                            {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="paid"
                            {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                            Paid
                        </option>

                        <option value="partial"
                            {{ request('payment_status') == 'partial' ? 'selected' : '' }}>
                            Partial
                        </option>

                        <option value="refunded"
                            {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                            Refunded
                        </option>

                    </select>

                </div>


                {{-- Bill Date --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Bill Date
                    </label>

                    <input type="date"
                           name="bill_date"
                           class="form-control"
                           value="{{ request('bill_date') }}">

                </div>


                {{-- Buttons --}}
                <div class="col-lg-1 col-md-12 d-flex align-items-end">

                    <div class="d-flex gap-2 w-100">

                        <button type="submit"
                                class="btn btn-primary flex-grow-1"
                                title="Filter">

                            <i class="bi bi-search"></i>

                        </button>

                        <a href="{{ route('admin.billing.index') }}"
                           class="btn btn-outline-secondary"
                           title="Reset">

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>



{{-- =========================
     BILLING TABLE
========================== --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>
                <h5 class="mb-1 fw-bold">
                    <i class="bi bi-receipt me-2"></i>
                    Billing Records
                </h5>

                <small class="text-muted">
                    Total records: {{ $bills->total() }}
                </small>
            </div>

        </div>

    </div>


    <div class="card-body p-0">

        @if($bills->count() > 0)

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Invoice
                            </th>

                            <th>
                                Bill Date
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Branch
                            </th>

                            <th>
                                Subtotal
                            </th>

                            <th>
                                Discount
                            </th>

                            <th>
                                Tax
                            </th>

                            <th>
                                Total
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($bills as $bill)

                            <tr>

                                {{-- Serial --}}
                                <td class="px-3">

                                    {{ $bills->firstItem() + $loop->index }}

                                </td>


                                {{-- Invoice --}}
                                <td>

                                    <div class="fw-semibold text-primary">
                                        {{ $bill->invoice_number }}
                                    </div>

                                </td>


                                {{-- Date --}}
                                <td>

                                    {{ $bill->bill_date?->format('d M Y') }}

                                </td>


                                {{-- Customer --}}
                                <td>

                                    @if($bill->customer)

                                        <div class="fw-semibold">
                                            {{ $bill->customer->name }}
                                        </div>

                                        @if($bill->customer->phone)

                                            <small class="text-muted">
                                                {{ $bill->customer->phone }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            Walk-in Customer
                                        </span>

                                    @endif

                                </td>


                                {{-- Branch --}}
                                <td>

                                    @if($bill->branch)

                                        <span class="fw-semibold">
                                            {{ $bill->branch->name }}
                                        </span>

                                        @if($bill->branch->code)

                                            <br>

                                            <small class="text-muted">
                                                {{ $bill->branch->code }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Subtotal --}}
                                <td>

                                    ₹{{ number_format((float)$bill->subtotal, 2) }}

                                </td>


                                {{-- Discount --}}
                                <td>

                                    ₹{{ number_format((float)$bill->discount, 2) }}

                                </td>


                                {{-- Tax --}}
                                <td>

                                    <div>
                                        ₹{{ number_format((float)$bill->tax_amount, 2) }}
                                    </div>

                                    @if($bill->tax_percentage > 0)

                                        <small class="text-muted">
                                            {{ number_format((float)$bill->tax_percentage, 2) }}%
                                        </small>

                                    @endif

                                </td>


                                {{-- Total --}}
                                <td>

                                    <span class="fw-bold">
                                        ₹{{ number_format((float)$bill->total, 2) }}
                                    </span>

                                </td>


                                {{-- Payment Method --}}
                                <td>

                                    @switch($bill->payment_method)

                                        @case('cash')
                                            <span>
                                                <i class="bi bi-cash-coin me-1"></i>
                                                Cash
                                            </span>
                                            @break

                                        @case('upi')
                                            <span>
                                                <i class="bi bi-phone me-1"></i>
                                                UPI
                                            </span>
                                            @break

                                        @case('card')
                                            <span>
                                                <i class="bi bi-credit-card me-1"></i>
                                                Card
                                            </span>
                                            @break

                                        @case('other')
                                            <span>
                                                <i class="bi bi-wallet2 me-1"></i>
                                                Other
                                            </span>
                                            @break

                                        @default
                                            <span class="text-muted">
                                                —
                                            </span>

                                    @endswitch

                                </td>


                                {{-- Payment Status --}}
                                <td>

                                    @if($bill->payment_status === 'paid')

                                        <span class="badge bg-success">
                                            Paid
                                        </span>

                                    @elseif($bill->payment_status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @elseif($bill->payment_status === 'partial')

                                        <span class="badge bg-info text-dark">
                                            Partial
                                        </span>

                                    @elseif($bill->payment_status === 'refunded')

                                        <span class="badge bg-danger">
                                            Refunded
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($bill->payment_status) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="text-center">

                                    <a href="{{ route('admin.billing.show', $bill->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       title="View Bill">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($bills->hasPages())

                <div class="px-3 py-3 border-top">

                    {{ $bills->links() }}

                </div>

            @endif


        @else

            {{-- Empty State --}}
            <div class="text-center py-5">

                <div class="empty-icon mb-3">

                    <i class="bi bi-receipt"></i>

                </div>

                <h5 class="fw-bold">
                    No billing records found
                </h5>

                <p class="text-muted mb-3">
                    No bills match your current filters.
                </p>

                <a href="{{ route('admin.billing.create') }}"
                   class="btn btn-primary">

                    <i class="bi bi-plus-lg me-1"></i>
                    Create First Bill

                </a>

            </div>

        @endif

    </div>

</div>


</div>

<style>

    .card {
        border-radius: 14px;
    }

    .card-header {
        border-radius: 14px 14px 0 0 !important;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-radius: 8px;
    }

    .table th {
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
    }

    .table td {
        font-size: 14px;
        white-space: nowrap;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 20px;
        font-weight: 500;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        margin: auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        font-size: 30px;
    }

    @media (max-width: 768px) {

        .table td,
        .table th {
            font-size: 13px;
        }

    }

</style>

@endsection
