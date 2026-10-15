@extends('admin.layout')

@section('title', 'Invoice ' . $bill->invoice_number)

@section('page-title', 'Invoice')

@section('content')

<style>

    /* =========================================================
       NORMAL SCREEN
    ========================================================== */

    .invoice-page {
        width: 100%;
    }

    .invoice-box {
        background: #fff;
    }

    .salon-logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
        display: block;
    }

    .authorized-signature {
        width: 180px;
        height: 70px;
        object-fit: contain;
        display: inline-block;
    }


    /* =========================================================
       PRINT
    ========================================================== */

    @media print {

        @page {
            size: A4;
            margin: 10mm;
        }

        html,
        body {
            width: 100% !important;
            min-width: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Hide complete application layout
        |--------------------------------------------------------------------------
        */

        body > * {
            visibility: hidden !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Show only invoice
        |--------------------------------------------------------------------------
        */

        .invoice-print-area,
        .invoice-print-area * {
            visibility: visible !important;
        }

        .invoice-print-area {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove AdminLTE layout spacing
        |--------------------------------------------------------------------------
        */

        .content-wrapper,
        .content,
        .container,
        .container-fluid {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }

        .invoice-box {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        .invoice-box .card-body {
            padding: 0 !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Hide buttons
        |--------------------------------------------------------------------------
        */

        .no-print {
            display: none !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Tables
        |--------------------------------------------------------------------------
        */

        table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid !important;
        }

        .table-responsive {
            overflow: visible !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Remove link styling
        |--------------------------------------------------------------------------
        */

        a {
            color: inherit !important;
            text-decoration: none !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent unwanted page break
        |--------------------------------------------------------------------------
        */

        .invoice-print-area {
            page-break-before: avoid !important;
            page-break-after: avoid !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Keep signature together
        |--------------------------------------------------------------------------
        */

        .signature-section {
            page-break-inside: avoid !important;
        }

        /*
        |--------------------------------------------------------------------------
        | Images
        |--------------------------------------------------------------------------
        */

        .salon-logo,
        .authorized-signature {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

</style>


<div class="invoice-page">


    {{-- =========================================================
        ACTION BUTTONS
    ========================================================== --}}

    <div class="d-flex justify-content-end mb-3 no-print">

        <button
            type="button"
            onclick="window.print()"
            class="btn btn-primary"
        >
            <i class="bi bi-printer me-1"></i>
            Print Invoice
        </button>


        <a
            href="{{ route('admin.billing.invoice.pdf', $bill->id) }}"
            class="btn btn-danger ms-2"
        >
            <i class="bi bi-file-earmark-pdf me-1"></i>
            Download PDF
        </a>


        <a
            href="{{ route('admin.billing.receipt', $bill->id) }}"
            target="_blank"
            class="btn btn-outline-secondary ms-2"
        >
            <i class="bi bi-receipt me-1"></i>
            Receipt
        </a>

    </div>



    {{-- =========================================================
        ONLY THIS AREA WILL PRINT
    ========================================================== --}}

    <div class="invoice-print-area">

        <div class="card border-0 shadow-sm invoice-box">

            <div class="card-body p-4">


                {{-- =================================================
                    HEADER
                ================================================== --}}

                <div class="row align-items-center">

                    <div class="col-md-7">


                        {{-- =================================================
                            SALON LOGO
                        ================================================== --}}

                        @if($bill->salon?->logo)
    <img
        src="{{ Storage::disk('public')->url($bill->salon->logo) }}"
        alt="{{ $bill->salon->name ?? 'Salon Logo' }}"
        class="salon-logo mb-2"
    >
@endif



                        {{-- =================================================
                            SALON NAME
                        ================================================== --}}

                        <h3 class="fw-bold mb-1">

                            {{ $bill->salon?->name ?? 'Salon' }}

                        </h3>



                        {{-- =================================================
                            GST
                        ================================================== --}}

                        @if($bill->salon?->gst_number)

                            <div>

                                GSTIN:
                                {{ $bill->salon->gst_number }}

                            </div>

                        @endif



                        {{-- =================================================
                            PHONE
                        ================================================== --}}

                        @if($bill->salon?->phone)

                            <div>

                                Phone:
                                {{ $bill->salon->phone }}

                            </div>

                        @endif



                        {{-- =================================================
                            EMAIL
                        ================================================== --}}

                        @if($bill->salon?->email)

                            <div>

                                Email:
                                {{ $bill->salon->email }}

                            </div>

                        @endif


                    </div>



                    {{-- =================================================
                        INVOICE INFORMATION
                    ================================================== --}}

                    <div class="col-md-5 text-md-end">

                        <h2 class="fw-bold">

                            INVOICE

                        </h2>


                        <div>

                            <strong>

                                {{ $bill->invoice_number }}

                            </strong>

                        </div>


                        <div>

                            Date:

                            {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}

                        </div>

                    </div>

                </div>


                <hr>



                {{-- =================================================
                    CUSTOMER + BRANCH
                ================================================== --}}

                <div class="row mb-4">


                    {{-- CUSTOMER --}}

                    <div class="col-md-6">

                        <h6 class="fw-bold">

                            Bill To

                        </h6>


                        <div>

                            {{ $bill->customer?->name ?? 'Walk-in Customer' }}

                        </div>


                        @if($bill->customer?->phone)

                            <div>

                                {{ $bill->customer->phone }}

                            </div>

                        @endif


                        @if($bill->customer?->email)

                            <div>

                                {{ $bill->customer->email }}

                            </div>

                        @endif

                    </div>



                    {{-- BRANCH --}}

                    <div class="col-md-6 text-md-end">

                        <h6 class="fw-bold">

                            Branch

                        </h6>


                        <div>

                            {{ $bill->branch?->name ?? '-' }}

                        </div>


                        @if($bill->branch?->phone)

                            <div>

                                {{ $bill->branch->phone }}

                            </div>

                        @endif

                    </div>

                </div>



                {{-- =================================================
                    ITEMS
                ================================================== --}}

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Service
                                </th>

                                <th>
                                    Staff
                                </th>

                                <th class="text-center">
                                    Qty
                                </th>

                                <th class="text-end">
                                    Price
                                </th>

                                <th class="text-end">
                                    Discount
                                </th>

                                <th class="text-end">
                                    Tax
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            @forelse($bill->items as $index => $item)

                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        {{ $item->item_name ?? '-' }}

                                    </td>


                                    <td>

                                        {{ $item->staff?->name ?? '-' }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format((float) $item->quantity, 2) }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format((float) $item->unit_price, 2) }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format((float) $item->discount, 2) }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format((float) $item->tax_amount, 2) }}

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted"
                                    >

                                        No services found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                    TOTALS
                ================================================== --}}

                <div class="row justify-content-end">

                    <div class="col-md-5">

                        <table class="table table-sm">


                            {{-- SERVICE TOTAL --}}

                            <tr>

                                <td>

                                    Service Total

                                </td>

                                <td class="text-end">

                                    ₹{{ number_format((float) $bill->service_total, 2) }}

                                </td>

                            </tr>



                            {{-- MANUAL / OVERALL DISCOUNT --}}

                            <tr>

                                <td>

                                    Discount

                                </td>

                                <td class="text-end text-danger">


                                    @if((float) $bill->discount > 0)

                                        -

                                        ₹{{ number_format((float) $bill->discount, 2) }}

                                    @else

                                        ₹0.00

                                    @endif


                                </td>

                            </tr>



                            {{-- COUPON DISCOUNT --}}

                            @if(
                                !empty($bill->coupon_code)
                                ||
                                (float) ($bill->coupon_discount ?? 0) > 0
                            )

                                <tr>

                                    <td>

                                        <strong>

                                            Coupon Discount

                                        </strong>


                                        @if(!empty($bill->coupon_code))

                                            <div class="small text-muted">

                                                Code:
                                                {{ $bill->coupon_code }}

                                            </div>

                                        @endif

                                    </td>


                                    <td class="text-end text-success">


                                        @if((float) ($bill->coupon_discount ?? 0) > 0)

                                            -

                                            ₹{{ number_format((float) $bill->coupon_discount, 2) }}

                                        @else

                                            ₹0.00

                                        @endif


                                    </td>

                                </tr>

                            @endif



                            {{-- TAX --}}

                            <tr>

                                <td>

                                    Tax
                                    ({{ number_format((float) $bill->tax_percentage, 2) }}%)

                                </td>


                                <td class="text-end">

                                    ₹{{ number_format((float) $bill->tax_amount, 2) }}

                                </td>

                            </tr>



                            {{-- GRAND TOTAL --}}

                            <tr class="fw-bold fs-5">

                                <td>

                                    Grand Total

                                </td>


                                <td class="text-end">

                                    ₹{{ number_format((float) $bill->grand_total, 2) }}

                                </td>

                            </tr>


                        </table>

                    </div>

                </div>



                {{-- =================================================
                    PAYMENTS
                ================================================== --}}

                <div class="mt-3">

                    <h6 class="fw-bold">

                        Payments

                    </h6>


                    <table class="table table-sm table-bordered">

                        <thead>

                            <tr>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Transaction ID
                                </th>

                                <th class="text-end">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            @forelse($bill->payments as $payment)

                                <tr>

                                    <td>

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $payment->payment_method
                                            )
                                        ) }}

                                    </td>


                                    <td>

                                        {{ $payment->transaction_id ?: '-' }}

                                    </td>


                                    <td class="text-end">

                                        ₹{{ number_format((float) $payment->amount, 2) }}

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="text-center text-muted"
                                    >

                                        No payment recorded.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>



                {{-- =================================================
                    PAYMENT STATUS
                ================================================== --}}

                <div class="row mt-3">


                    <div class="col-md-6">

                        <strong>

                            Payment Status:

                        </strong>


                        {{ ucfirst($bill->payment_status ?? 'Pending') }}

                    </div>



                    <div class="col-md-6 text-md-end">

                        <strong>

                            Paid:

                        </strong>


                        ₹{{ number_format((float) $bill->paid_amount, 2) }}


                        <br>


                        <strong>

                            Pending:

                        </strong>


                        ₹{{ number_format((float) $bill->pending_amount, 2) }}

                    </div>

                </div>



                {{-- =================================================
                    NOTES
                ================================================== --}}

                @if($bill->notes)

                    <div class="mt-4">

                        <strong>

                            Notes:

                        </strong>


                        <div>

                            {{ $bill->notes }}

                        </div>

                    </div>

                @endif



                {{-- =================================================
                    AUTHORIZED SIGNATURE
                ================================================== --}}

                @if($bill->salon?->authorized_signature)

                    <div
                        class="signature-section text-end mt-5"
                        style="page-break-inside: avoid;"
                    >

                        <img
                            src="{{ asset('storage/' . $bill->salon->authorized_signature) }}"
                            alt="Authorized Signature"
                            class="authorized-signature"
                        >


                        <div class="mt-1">

                            <strong>

                                Authorized Signature

                            </strong>

                        </div>

                    </div>

                @endif



                {{-- =================================================
                    FOOTER
                ================================================== --}}

                @if($bill->salon?->receipt_footer)

                    <div class="text-center mt-5 text-muted">

                        {{ $bill->salon->receipt_footer }}

                    </div>

                @else

                    <div class="text-center mt-5 text-muted">

                        Thank you for visiting us!

                    </div>

                @endif


            </div>

        </div>

    </div>

</div>

@endsection