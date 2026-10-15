@extends('admin.layout')

@section('title', 'Bill Details | Salon Management')

@section('page-title', 'Bill Details')

@section('content')

<div class="container-fluid px-0">

{{-- =========================
     HEADER
========================== --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-receipt-cutoff me-2"></i>
            Bill Details
        </h4>

        <p class="text-muted mb-0">
            View invoice and payment information.
        </p>
    </div>

    <div class="d-flex gap-2">

        <button type="button"
                class="btn btn-dark"
                onclick="window.print()">

            <i class="bi bi-printer me-1"></i>
            Print

        </button>

        <a href="{{ route('admin.billing.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>

</div>



{{-- =========================
     INVOICE
========================== --}}
<div class="card border-0 shadow-sm invoice-card">

    <div class="card-body p-4 p-lg-5">

        {{-- Invoice Header --}}
        <div class="row align-items-start mb-4">

            {{-- Salon --}}
            <div class="col-md-6 mb-3 mb-md-0">

                @if($bill->salon?->logo)

                    <img src="{{ asset('storage/' . $bill->salon->logo) }}"
                         alt="Salon Logo"
                         class="salon-logo mb-3">

                @endif

                <h3 class="fw-bold mb-1">

                    {{ $bill->salon?->name ?? 'Salon' }}

                </h3>

                @if($bill->salon?->address)
                    <div class="text-muted">
                        {{ $bill->salon->address }}
                    </div>
                @endif

                <div class="text-muted">

                    @if($bill->salon?->city)
                        {{ $bill->salon->city }}
                    @endif

                    @if($bill->salon?->state)
                        , {{ $bill->salon->state }}
                    @endif

                    @if($bill->salon?->pincode)
                        - {{ $bill->salon->pincode }}
                    @endif

                </div>

                @if($bill->salon?->phone)

                    <div class="text-muted mt-1">
                        <i class="bi bi-telephone me-1"></i>
                        {{ $bill->salon->phone }}
                    </div>

                @endif

                @if($bill->salon?->email)

                    <div class="text-muted">
                        <i class="bi bi-envelope me-1"></i>
                        {{ $bill->salon->email }}
                    </div>

                @endif

                @if($bill->salon?->gst_number)

                    <div class="mt-2">
                        <strong>GSTIN:</strong>
                        {{ $bill->salon->gst_number }}
                    </div>

                @endif

            </div>


            {{-- Invoice Info --}}
            <div class="col-md-6 text-md-end">

                <h2 class="invoice-title fw-bold mb-2">
                    INVOICE
                </h2>

                <div class="invoice-number">
                    {{ $bill->invoice_number }}
                </div>

                <div class="mt-2">

                    <strong>Bill Date:</strong>

                    {{ $bill->bill_date?->format('d M Y') }}

                </div>

                @if($bill->branch)

                    <div class="mt-1">

                        <strong>Branch:</strong>

                        {{ $bill->branch->name }}

                        @if($bill->branch->code)
                            ({{ $bill->branch->code }})
                        @endif

                    </div>

                @endif

            </div>

        </div>


        <hr>


        {{-- =========================
             CUSTOMER DETAILS
        ========================== --}}
        <div class="row g-4 my-3">

            <div class="col-md-6">

                <div class="info-box">

                    <div class="info-title">
                        <i class="bi bi-person me-2"></i>
                        Bill To
                    </div>

                    @if($bill->customer)

                        <div class="fw-bold fs-5">
                            {{ $bill->customer->name }}
                        </div>

                        @if($bill->customer->phone)

                            <div class="text-muted mt-1">
                                <i class="bi bi-telephone me-1"></i>
                                {{ $bill->customer->phone }}
                            </div>

                        @endif

                        @if($bill->customer->email)

                            <div class="text-muted">
                                <i class="bi bi-envelope me-1"></i>
                                {{ $bill->customer->email }}
                            </div>

                        @endif

                        @if($bill->customer->address)

                            <div class="text-muted mt-1">
                                {{ $bill->customer->address }}
                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            Walk-in Customer
                        </div>

                    @endif

                </div>

            </div>


            {{-- Appointment --}}
            <div class="col-md-6">

                <div class="info-box">

                    <div class="info-title">
                        <i class="bi bi-calendar-check me-2"></i>
                        Appointment
                    </div>

                    @if($bill->appointment)

                        <div class="fw-semibold">

                            {{ $bill->appointment->appointment_date?->format('d M Y') }}

                        </div>

                        @if($bill->appointment->start_time)

                            <div class="text-muted">

                                {{ \Carbon\Carbon::parse($bill->appointment->start_time)->format('h:i A') }}

                                @if($bill->appointment->end_time)
                                    -
                                    {{ \Carbon\Carbon::parse($bill->appointment->end_time)->format('h:i A') }}
                                @endif

                            </div>

                        @endif

                        @if($bill->appointment->service)

                            <div class="mt-1">

                                <strong>Service:</strong>

                                {{ $bill->appointment->service->name }}

                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            No appointment linked
                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- =========================
             ITEMS
        ========================== --}}
        <div class="table-responsive mt-4">

            <table class="table invoice-table align-middle">

                <thead>

                    <tr>

                        <th width="5%">
                            #
                        </th>

                        <th>
                            Service / Item
                        </th>

                        <th>
                            Variation
                        </th>

                        <th class="text-center">
                            Qty
                        </th>

                        <th class="text-end">
                            Unit Price
                        </th>

                        <th class="text-end">
                            Discount
                        </th>

                        <th class="text-end">
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($bill->items as $item)

                        <tr>

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <div class="fw-semibold">
                                    {{ $item->item_name }}
                                </div>

                                @if($item->service)

                                    <small class="text-muted">
                                        {{ $item->service->category }}
                                    </small>

                                @endif

                            </td>

                            <td>

                                @if($item->serviceVariation)

                                    {{ $item->serviceVariation->name }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td class="text-center">

                                {{ $item->quantity }}

                            </td>

                            <td class="text-end">

                                ₹{{ number_format((float)$item->unit_price, 2) }}

                            </td>

                            <td class="text-end">

                                ₹{{ number_format((float)$item->discount, 2) }}

                            </td>

                            <td class="text-end fw-semibold">

                                ₹{{ number_format((float)$item->total, 2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-4">

                                No bill items found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



        {{-- =========================
             TOTAL + PAYMENT
        ========================== --}}
        <div class="row justify-content-end mt-4">

            <div class="col-lg-5 col-md-7">

                <div class="totals-box">

                    <div class="total-line">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            ₹{{ number_format((float)$bill->subtotal, 2) }}
                        </strong>

                    </div>


                    <div class="total-line">

                        <span>
                            Discount
                        </span>

                        <strong>
                            - ₹{{ number_format((float)$bill->discount, 2) }}
                        </strong>

                    </div>


                    <div class="total-line">

                        <span>

                            Tax

                            @if($bill->tax_percentage > 0)
                                ({{ number_format((float)$bill->tax_percentage, 2) }}%)
                            @endif

                        </span>

                        <strong>
                            ₹{{ number_format((float)$bill->tax_amount, 2) }}
                        </strong>

                    </div>


                    <hr>


                    <div class="total-line final-total">

                        <span>
                            Grand Total
                        </span>

                        <strong>
                            ₹{{ number_format((float)$bill->total, 2) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================
             PAYMENT DETAILS
        ========================== --}}
        <div class="row g-4 mt-4">

            <div class="col-md-6">

                <div class="payment-box">

                    <div class="info-title mb-3">
                        <i class="bi bi-credit-card me-2"></i>
                        Payment Details
                    </div>


                    <div class="payment-line">

                        <span>
                            Payment Method
                        </span>

                        <strong>

                            @switch($bill->payment_method)

                                @case('cash')
                                    <i class="bi bi-cash-coin me-1"></i>
                                    Cash
                                    @break

                                @case('upi')
                                    <i class="bi bi-phone me-1"></i>
                                    UPI
                                    @break

                                @case('card')
                                    <i class="bi bi-credit-card me-1"></i>
                                    Card
                                    @break

                                @case('other')
                                    <i class="bi bi-wallet2 me-1"></i>
                                    Other
                                    @break

                                @default
                                    —

                            @endswitch

                        </strong>

                    </div>


                    <div class="payment-line">

                        <span>
                            Payment Status
                        </span>

                        <strong>

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

                        </strong>

                    </div>


                    @if($bill->transaction_id)

                        <div class="payment-line">

                            <span>
                                Transaction ID
                            </span>

                            <strong>
                                {{ $bill->transaction_id }}
                            </strong>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Notes --}}
            @if($bill->notes)

                <div class="col-md-6">

                    <div class="payment-box">

                        <div class="info-title mb-3">
                            <i class="bi bi-sticky me-2"></i>
                            Notes
                        </div>

                        <div class="text-muted">
                            {{ $bill->notes }}
                        </div>

                    </div>

                </div>

            @endif

        </div>



        {{-- =========================
             FOOTER
        ========================== --}}
        <div class="invoice-footer text-center mt-5 pt-4">

            @if($bill->salon?->receipt_footer)

                <div class="fw-semibold mb-2">
                    {{ $bill->salon->receipt_footer }}
                </div>

            @else

                <div class="fw-semibold mb-2">
                    Thank you for visiting us!
                </div>

            @endif

            <small class="text-muted">
                This is a computer-generated invoice.
            </small>

        </div>

    </div>

</div>


</div>

<style>

    body {
        background: #f6f7fb;
    }

    .invoice-card {
        border-radius: 16px;
        overflow: hidden;
    }

    .salon-logo {
        max-width: 110px;
        max-height: 80px;
        object-fit: contain;
    }

    .invoice-title {
        letter-spacing: 2px;
        font-size: 32px;
    }

    .invoice-number {
        font-size: 18px;
        font-weight: 600;
    }

    .info-box,
    .payment-box {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        height: 100%;
        background: #fff;
    }

    .info-title {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .invoice-table {
        border: 1px solid #e9ecef;
        overflow: hidden;
    }

    .invoice-table thead th {
        background: #f8f9fa;
        font-size: 13px;
        font-weight: 700;
        padding: 13px 12px;
        white-space: nowrap;
    }

    .invoice-table tbody td {
        padding: 13px 12px;
        font-size: 14px;
    }

    .totals-box {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 18px;
        background: #fff;
    }

    .total-line,
    .payment-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 8px 0;
    }

    .final-total {
        font-size: 20px;
    }

    .payment-line {
        border-bottom: 1px solid #f0f0f0;
    }

    .payment-line:last-child {
        border-bottom: 0;
    }

    .badge {
        padding: 6px 10px;
        border-radius: 20px;
    }

    .invoice-footer {
        border-top: 1px dashed #ced4da;
    }


    /* =========================
       PRINT
    ========================== */

    @media print {

        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            background: #fff !important;
        }

        body * {
            visibility: hidden;
        }

        .invoice-card,
        .invoice-card * {
            visibility: visible;
        }

        .invoice-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
            border: none !important;
        }

        .invoice-card .card-body {
            padding: 0 !important;
        }

        .btn,
        button {
            display: none !important;
        }

        .invoice-table {
            width: 100%;
        }

        .invoice-table th,
        .invoice-table td {
            font-size: 11px !important;
        }

        .info-box,
        .payment-box,
        .totals-box {
            break-inside: avoid;
        }

        .invoice-footer {
            margin-top: 30px !important;
        }

    }


    @media (max-width: 768px) {

        .invoice-title {
            font-size: 26px;
        }

        .invoice-card .card-body {
            padding: 20px !important;
        }

    }

</style>

@endsection
