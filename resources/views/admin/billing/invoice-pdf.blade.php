<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Invoice {{ $bill->invoice_number }}
    </title>

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            width: 100%;
        }

        /* =========================================================
           HEADER
        ========================================================== */

        .header {
            width: 100%;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .salon-info {
            width: 60%;
        }

        .invoice-info {
            width: 40%;
            text-align: right;
        }

        .logo {
            max-height: 65px;
            max-width: 180px;
            margin-bottom: 8px;
        }

        .salon-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 13px;
            font-weight: bold;
        }

        .line {
            border-top: 1px solid #999;
            margin: 15px 0;
        }

        /* =========================================================
           CUSTOMER / BRANCH
        ========================================================== */

        .customer-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .customer-table td {
            width: 50%;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .text-right {
            text-align: right;
        }

        /* =========================================================
           ITEMS TABLE
        ========================================================== */

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th {
            background: #f2f2f2;
            border: 1px solid #999;
            padding: 7px 5px;
            font-weight: bold;
        }

        .items-table td {
            border: 1px solid #999;
            padding: 7px 5px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* =========================================================
           TOTALS
        ========================================================== */

        .totals-wrapper {
            width: 100%;
            margin-top: 15px;
        }

        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 6px;
        }

        .discount-row td {
            color: #c0392b;
        }

        .coupon-row td {
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .coupon-code {
            font-weight: bold;
            font-size: 11px;
        }

        .coupon-discount {
            color: #16803c;
            font-weight: bold;
        }

        .grand-total td {
            border-top: 2px solid #222;
            font-size: 15px;
            font-weight: bold;
            padding-top: 10px;
        }

        /* =========================================================
           PAYMENTS
        ========================================================== */

        .payment-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 7px;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table th {
            background: #f2f2f2;
            border: 1px solid #999;
            padding: 7px;
        }

        .payments-table td {
            border: 1px solid #999;
            padding: 7px;
        }

        /* =========================================================
           PAYMENT STATUS
        ========================================================== */

        .status-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        .status-table td {
            width: 50%;
            padding: 4px 0;
        }

        .amount-due {
            font-weight: bold;
        }

        /* =========================================================
           NOTES
        ========================================================== */

        .notes {
            margin-top: 20px;
        }

        /* =========================================================
           AUTHORIZED SIGNATURE
        ========================================================== */

        .signature-section {
            width: 100%;
            margin-top: 35px;
            text-align: right;
        }

        .signature-image {
            max-width: 180px;
            max-height: 70px;
            display: inline-block;
        }

        .signature-label {
            margin-top: 5px;
            font-size: 11px;
            font-weight: bold;
        }

        /* =========================================================
           FOOTER
        ========================================================== */

        .footer {
            text-align: center;
            margin-top: 35px;
            color: #666;
            font-size: 11px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

    </style>

</head>


<body>


@php

    /*
    |--------------------------------------------------------------------------
    | LOGO PATH
    |--------------------------------------------------------------------------
    */

    $logoPath = null;

    if ($bill->salon?->logo) {

        $logoPath = storage_path(
            'app/public/' . $bill->salon->logo
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZED SIGNATURE PATH
    |--------------------------------------------------------------------------
    */

    $signaturePath = null;

    if ($bill->salon?->authorized_signature) {

        $signaturePath = storage_path(
            'app/public/' . $bill->salon->authorized_signature
        );

    }

@endphp


<div class="invoice-box">


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="header">

        <table class="header-table">

            <tr>

                {{-- =================================================
                     SALON INFORMATION
                ================================================== --}}

                <td class="salon-info">


                    {{-- LOGO --}}

                    @if($logoPath && file_exists($logoPath))

                        <img
                            src="{{ $logoPath }}"
                            alt="Salon Logo"
                            class="logo"
                        >

                    @endif


                    {{-- SALON NAME --}}

                    <div class="salon-name">

                        {{ $bill->salon?->name ?? 'Salon' }}

                    </div>


                    {{-- GST --}}

                    @if($bill->salon?->gst_number)

                        <div>

                            GSTIN:
                            {{ $bill->salon->gst_number }}

                        </div>

                    @endif


                    {{-- PHONE --}}

                    @if($bill->salon?->phone)

                        <div>

                            Phone:
                            {{ $bill->salon->phone }}

                        </div>

                    @endif


                    {{-- EMAIL --}}

                    @if($bill->salon?->email)

                        <div>

                            Email:
                            {{ $bill->salon->email }}

                        </div>

                    @endif

                </td>


                {{-- =================================================
                     INVOICE INFORMATION
                ================================================== --}}

                <td class="invoice-info">

                    <div class="invoice-title">

                        INVOICE

                    </div>


                    <div class="invoice-number">

                        {{ $bill->invoice_number }}

                    </div>


                    <div style="margin-top: 5px;">

                        Date:

                        {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}

                    </div>

                </td>

            </tr>

        </table>

    </div>


    <div class="line"></div>


    {{-- =========================================================
         CUSTOMER / BRANCH
    ========================================================== --}}

    <table class="customer-table">

        <tr>

            {{-- CUSTOMER --}}

            <td>

                <div class="section-title">

                    Bill To

                </div>


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

            </td>


            {{-- BRANCH --}}

            <td class="text-right">

                <div class="section-title">

                    Branch

                </div>


                <div>

                    {{ $bill->branch?->name ?? '-' }}

                </div>


                @if($bill->branch?->phone)

                    <div>

                        {{ $bill->branch->phone }}

                    </div>

                @endif

            </td>

        </tr>

    </table>


    {{-- =========================================================
         ITEMS
    ========================================================== --}}

    <table class="items-table">

        <thead>

            <tr>

                <th style="width: 5%;">
                    #
                </th>

                <th style="width: 28%;">
                    Service
                </th>

                <th style="width: 20%;">
                    Staff
                </th>

                <th style="width: 8%;">
                    Qty
                </th>

                <th style="width: 14%;">
                    Price
                </th>

                <th style="width: 13%;">
                    Discount
                </th>

                <th style="width: 12%;">
                    Tax
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($bill->items as $index => $item)

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>


                    <td>
                        {{ $item->item_name ?? '-' }}
                    </td>


                    <td>
                        {{ $item->staff?->name ?? '-' }}
                    </td>


                    <td class="center">
                        {{ number_format((float) $item->quantity, 2) }}
                    </td>


                    <td class="right">
                        ₹{{ number_format((float) $item->unit_price, 2) }}
                    </td>


                    <td class="right">
                        ₹{{ number_format((float) $item->discount, 2) }}
                    </td>


                    <td class="right">
                        ₹{{ number_format((float) $item->tax_amount, 2) }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="center">

                        No items found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
         TOTALS
    ========================================================== --}}

    <div class="totals-wrapper">

        <table class="totals-table">


            <tr>

                <td>
                    Service Total
                </td>

                <td class="right">

                    ₹{{ number_format((float) $bill->service_total, 2) }}

                </td>

            </tr>


            <tr class="discount-row">

                <td>
                    Discount
                </td>

                <td class="right">

                    @if((float) $bill->discount > 0)

                        - ₹{{ number_format((float) $bill->discount, 2) }}

                    @else

                        ₹0.00

                    @endif

                </td>

            </tr>


            @if(
                !empty($bill->coupon_code) ||
                (float) ($bill->coupon_discount ?? 0) > 0
            )

                <tr class="coupon-row">

                    <td>

                        <strong>
                            Coupon Discount
                        </strong>


                        @if(!empty($bill->coupon_code))

                            <div class="coupon-code">

                                Code:
                                {{ $bill->coupon_code }}

                            </div>

                        @endif

                    </td>


                    <td class="right coupon-discount">

                        @if((float) ($bill->coupon_discount ?? 0) > 0)

                            - ₹{{ number_format(
                                (float) $bill->coupon_discount,
                                2
                            ) }}

                        @else

                            ₹0.00

                        @endif

                    </td>

                </tr>

            @endif


            <tr>

                <td>

                    Tax
                    ({{ number_format(
                        (float) $bill->tax_percentage,
                        2
                    ) }}%)

                </td>


                <td class="right">

                    ₹{{ number_format(
                        (float) $bill->tax_amount,
                        2
                    ) }}

                </td>

            </tr>


            <tr class="grand-total">

                <td>

                    Grand Total

                </td>


                <td class="right">

                    ₹{{ number_format(
                        (float) $bill->grand_total,
                        2
                    ) }}

                </td>

            </tr>


        </table>

    </div>


    {{-- =========================================================
         PAYMENTS
    ========================================================== --}}

    <div class="payment-title">

        Payments

    </div>


    <table class="payments-table">

        <thead>

            <tr>

                <th>
                    Method
                </th>

                <th>
                    Transaction ID
                </th>

                <th class="right">
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


                    <td class="right">

                        ₹{{ number_format(
                            (float) $payment->amount,
                            2
                        ) }}

                    </td>

                </tr>


            @empty

                <tr>

                    <td colspan="3" class="center">

                        No payment recorded.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- =========================================================
         PAYMENT STATUS
    ========================================================== --}}

    <table class="status-table">

        <tr>

            <td>

                <strong>
                    Payment Status:
                </strong>


                {{ ucfirst(
                    $bill->payment_status ?? 'Pending'
                ) }}

            </td>


            <td class="text-right">

                <strong>
                    Paid:
                </strong>

                ₹{{ number_format(
                    (float) $bill->paid_amount,
                    2
                ) }}


                <br>


                <strong>
                    Pending:
                </strong>

                ₹{{ number_format(
                    (float) $bill->pending_amount,
                    2
                ) }}

            </td>

        </tr>

    </table>


    {{-- =========================================================
         NOTES
    ========================================================== --}}

    @if($bill->notes)

        <div class="notes">

            <strong>
                Notes:
            </strong>


            <div style="margin-top: 5px;">

                {{ $bill->notes }}

            </div>

        </div>

    @endif


    {{-- =========================================================
         AUTHORIZED SIGNATURE
    ========================================================== --}}

    @if($signaturePath && file_exists($signaturePath))

        <div class="signature-section">

            <img
                src="{{ $signaturePath }}"
                alt="Authorized Signature"
                class="signature-image"
            >

            <div class="signature-label">

                Authorized Signature

            </div>

        </div>

    @endif


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    @if($bill->salon?->receipt_footer)

        <div class="footer">

            {{ $bill->salon->receipt_footer }}

        </div>

    @else

        <div class="footer">

            Thank you for visiting us!

        </div>

    @endif


</div>


</body>

</html>