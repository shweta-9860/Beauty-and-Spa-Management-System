
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Receipt {{ $bill->invoice_number }}
    </title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
    <style>

        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 8px;
            width: 80mm;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #000;
        }

        .receipt {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .total {
            font-size: 13px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 8px;
        }

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        .salon-logo {
            text-align: center;
            margin-bottom: 5px;
        }

        .salon-logo img {
            max-width: 120px;
            max-height: 55px;
            object-fit: contain;
        }

        /*
        |--------------------------------------------------------------------------
        | SIGNATURE
        |--------------------------------------------------------------------------
        */

        .signature {
            text-align: right;
            margin-top: 10px;
        }

        .signature img {
            max-width: 100px;
            max-height: 45px;
            object-fit: contain;
        }

        .signature-text {
            font-size: 8px;
            margin-top: 2px;
        }

    </style>

</head>


<body>

<div class="receipt">


    {{-- =========================================================
         SALON HEADER
    ========================================================== --}}

    <div class="center">


        {{-- =====================================================
             SALON LOGO
        ====================================================== --}}

        @if($bill->salon?->logo)

            @php

                $logoPath = public_path(
                    'storage/' . $bill->salon->logo
                );

            @endphp


            @if(file_exists($logoPath))

                <div class="salon-logo">

                    <img
                        src="{{ $logoPath }}"
                        alt="Salon Logo"
                    >

                </div>

            @endif

        @endif


        {{-- =====================================================
             SALON NAME
        ====================================================== --}}

        <div
            class="bold"
            style="font-size:16px;"
        >

            {{ $bill->salon?->name }}

        </div>


        {{-- =====================================================
             GST
        ====================================================== --}}

        @if($bill->salon?->gst_number)

            <div>

                GSTIN:
                {{ $bill->salon->gst_number }}

            </div>

        @endif


        {{-- =====================================================
             BRANCH
        ====================================================== --}}

        @if($bill->branch?->name)

            <div>

                {{ $bill->branch->name }}

            </div>

        @endif


        {{-- =====================================================
             PHONE
        ====================================================== --}}

        @if($bill->salon?->phone)

            <div>

                {{ $bill->salon->phone }}

            </div>

        @endif


        {{-- =====================================================
             EMAIL
        ====================================================== --}}

        @if($bill->salon?->email)

            <div>

                {{ $bill->salon->email }}

            </div>

        @endif


    </div>


    <div class="line"></div>


    {{-- =========================================================
         BILL DETAILS
    ========================================================== --}}

    <table>

        <tr>

            <td>
                Invoice
            </td>

            <td class="right">

                {{ $bill->invoice_number }}

            </td>

        </tr>


        <tr>

            <td>
                Date
            </td>

            <td class="right">

                {{ \Carbon\Carbon::parse($bill->bill_date)->format('d-m-Y') }}

            </td>

        </tr>


        <tr>

            <td>
                Customer
            </td>

            <td class="right">

                {{ $bill->customer?->name ?? 'Walk-in' }}

            </td>

        </tr>

    </table>


    <div class="line"></div>


    {{-- =========================================================
         SERVICES
    ========================================================== --}}

    <table>

        @foreach($bill->items as $item)

            @php

                $quantity = (float) ($item->quantity ?? 0);

                $unitPrice = (float) ($item->unit_price ?? 0);

                $discount = (float) ($item->discount ?? 0);

                $tax = (float) ($item->tax_amount ?? 0);

                $itemTotal = (float) (
                    $item->total_amount ?? 0
                );


                /*
                |--------------------------------------------------------------------------
                | Fallback Calculation
                |--------------------------------------------------------------------------
                */

                if ($itemTotal <= 0) {

                    $itemTotal =
                        max(
                            0,
                            ($unitPrice * $quantity) - $discount
                        ) + $tax;

                }

            @endphp


            {{-- ITEM NAME --}}
            <tr>

                <td colspan="2">

                    <span class="bold">

                        {{ $item->item_name }}

                    </span>

                </td>

            </tr>


            {{-- ITEM PRICE --}}
            <tr>

                <td>

                    {{ rtrim(rtrim(number_format($quantity, 2), '0'), '.') }}

                    ×

                    ₹{{ number_format($unitPrice, 2) }}

                </td>

                <td class="right">

                    ₹{{ number_format($itemTotal, 2) }}

                </td>

            </tr>


            {{-- STAFF --}}
            @if($item->staff)

                <tr>

                    <td colspan="2">

                        Staff:
                        {{ $item->staff->name }}

                    </td>

                </tr>

            @endif


        @endforeach

    </table>


    <div class="line"></div>


    {{-- =========================================================
         TOTALS
    ========================================================== --}}

    <table>


        {{-- SERVICE TOTAL --}}
        <tr>

            <td>

                Service Total

            </td>

            <td class="right">

                ₹{{ number_format($bill->service_total, 2) }}

            </td>

        </tr>


        {{-- DISCOUNT --}}
        <tr>

            <td>

                Discount

            </td>

            <td class="right">

                - ₹{{ number_format($bill->discount, 2) }}

            </td>

        </tr>


        {{-- TAX --}}
        <tr>

            <td>

                Tax

            </td>

            <td class="right">

                ₹{{ number_format($bill->tax_amount, 2) }}

            </td>

        </tr>


        {{-- GRAND TOTAL --}}
        <tr class="total">

            <td>

                GRAND TOTAL

            </td>

            <td class="right">

                ₹{{ number_format($bill->grand_total, 2) }}

            </td>

        </tr>


    </table>


    <div class="line"></div>


    {{-- =========================================================
         PAYMENTS
    ========================================================== --}}

    <div class="bold">

        Payments

    </div>


    @forelse($bill->payments as $payment)

        <table>

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

                <td class="right">

                    ₹{{ number_format($payment->amount, 2) }}

                </td>

            </tr>

        </table>

    @empty

        <div>

            No payment recorded.

        </div>

    @endforelse


    <div class="line"></div>


    {{-- =========================================================
         PAID / PENDING
    ========================================================== --}}

    <table>

        <tr>

            <td>

                Paid

            </td>

            <td class="right">

                ₹{{ number_format($bill->paid_amount, 2) }}

            </td>

        </tr>


        <tr>

            <td>

                Pending

            </td>

            <td class="right">

                ₹{{ number_format($bill->pending_amount, 2) }}

            </td>

        </tr>

    </table>


    <div class="line"></div>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    <div class="footer">


        {{-- PAYMENT STATUS --}}
        <strong>

            {{ ucfirst($bill->payment_status) }}

        </strong>


        {{-- RECEIPT FOOTER --}}
        @if($bill->salon?->receipt_footer)

            <div style="margin-top:6px;">

                {{ $bill->salon->receipt_footer }}

            </div>

        @endif


        {{-- =====================================================
             AUTHORIZED SIGNATURE
             Existing DB field:
             authorized_signature
        ====================================================== --}}

        @if($bill->salon?->authorized_signature)

            @php

                $signaturePath = public_path(
                    'storage/' .
                    $bill->salon->authorized_signature
                );

            @endphp


            @if(file_exists($signaturePath))

                <div class="signature">

                    <img
                        src="{{ $signaturePath }}"
                        alt="Authorized Signature"
                    >

                    <div class="signature-text">

                        Authorized Signature

                    </div>

                </div>

            @endif

        @endif


        {{-- THANK YOU --}}
        <div style="margin-top:6px;">

            Thank you!

        </div>


    </div>


</div>

</body>

</html>
