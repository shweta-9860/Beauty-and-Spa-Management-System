@php
    /*
    |--------------------------------------------------------------------------
    | Salon & Printer Settings
    |--------------------------------------------------------------------------
    */

    $salon = $salon ?? data_get($bill, 'salon');
    $printerSetting = $printerSetting ?? null;


    /*
    |--------------------------------------------------------------------------
    | Image URL Helper
    |--------------------------------------------------------------------------
    */

    $getImageUrl = function ($path) {

        if (empty($path)) {
            return null;
        }

        $path = trim(
            str_replace('\\', '/', (string) $path)
        );

        // Already a complete URL or base64 image
        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://') ||
            str_starts_with($path, 'data:image/')
        ) {
            return $path;
        }

        /*
        |--------------------------------------------------------------------------
        | Convert full storage path
        |--------------------------------------------------------------------------
        */

        $storageMarker = 'storage/app/public/';

        $markerPosition = strpos(
            $path,
            $storageMarker
        );

        if ($markerPosition !== false) {

            $path = substr(
                $path,
                $markerPosition + strlen($storageMarker)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Clean storage/public prefix
        |--------------------------------------------------------------------------
        */

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {

            $cleanPath = substr(
                $cleanPath,
                8
            );

        } elseif (str_starts_with($cleanPath, 'public/')) {

            $cleanPath = substr(
                $cleanPath,
                7
            );
        }

        return asset(
            'storage/' . $cleanPath
        );
    };


    /*
    |--------------------------------------------------------------------------
    | Dynamic Logo
    |--------------------------------------------------------------------------
    */

    $logoPath = data_get(
        $salon,
        'logo'
    );

    $logoSrc = $getImageUrl(
        $logoPath
    );


    /*
    |--------------------------------------------------------------------------
    | Dynamic Authorized Signature
    |--------------------------------------------------------------------------
    */

    $signaturePath = data_get(
        $salon,
        'authorized_signature'
    );

    $signatureSrc = $getImageUrl(
        $signaturePath
    );


    /*
    |--------------------------------------------------------------------------
    | Printer Settings
    |--------------------------------------------------------------------------
    */

    $defaultPrintLogo = (bool) data_get(
        $printerSetting,
        'print_logo',
        true
    );

    $defaultPrintSignature = (bool) data_get(
        $printerSetting,
        'print_signature',
        true
    );

    $defaultPaperSize = data_get(
        $printerSetting,
        'paper_size',
        '80mm'
    );

    $defaultPaperSize = $defaultPaperSize === '58mm'
        ? '58'
        : '80';


    /*
    |--------------------------------------------------------------------------
    | Salon Details
    |--------------------------------------------------------------------------
    */

    $salonName = data_get(
        $salon,
        'name',
        config('app.name')
    );

    $phone = data_get(
        $salon,
        'phone',
        ''
    );

    $email = data_get(
        $salon,
        'email',
        ''
    );

    $gst = data_get(
        $salon,
        'gst_number',
        ''
    );

    $receiptFooter = data_get(
        $salon,
        'receipt_footer',
        ''
    );


    /*
    |--------------------------------------------------------------------------
    | Branch
    |--------------------------------------------------------------------------
    */

    $branchName = data_get(
        $bill,
        'branch.name',
        ''
    );


    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    $customerName = data_get(
        $bill,
        'customer.name',
        'Walk-in'
    );


    /*
    |--------------------------------------------------------------------------
    | Invoice
    |--------------------------------------------------------------------------
    */

    $invoiceNo = data_get(
        $bill,
        'invoice_number',
        data_get(
            $bill,
            'bill_number',
            data_get(
                $bill,
                'id',
                ''
            )
        )
    );


    /*
    |--------------------------------------------------------------------------
    | Bill Date
    |--------------------------------------------------------------------------
    */

    $billDate = data_get(
        $bill,
        'created_at'
    )
        ? \Illuminate\Support\Carbon::parse(
            data_get($bill, 'created_at')
        )->format('d-m-Y h:i A')
        : now()->format('d-m-Y h:i A');


    /*
    |--------------------------------------------------------------------------
    | Items & Payments
    |--------------------------------------------------------------------------
    */

    $items = data_get(
        $bill,
        'items',
        collect()
    );

    $payments = data_get(
        $bill,
        'payments',
        collect()
    );


    /*
    |--------------------------------------------------------------------------
    | Service Items
    |--------------------------------------------------------------------------
    */

    $serviceSubtotal = 0;

    $receiptItems = [];

    foreach ($items as $item) {

        $qtyValue = data_get(
            $item,
            'quantity',
            data_get(
                $item,
                'qty',
                1
            )
        );

        $qty = is_null($qtyValue)
            ? 1
            : (float) $qtyValue;


        $rate = (float) data_get(
            $item,
            'price',
            data_get(
                $item,
                'unit_price',
                data_get(
                    $item,
                    'amount',
                    0
                )
            )
        );


        $lineTotal = round(
            $qty * $rate,
            2
        );


        $itemName = data_get(
            $item,
            'service.name',
            data_get(
                $item,
                'serviceVariation.name',
                data_get(
                    $item,
                    'product.name',
                    data_get(
                        $item,
                        'name',
                        'Service'
                    )
                )
            )
        );


        $staffName = data_get(
            $item,
            'staff.name',
            ''
        );


        $serviceSubtotal += $lineTotal;


        $receiptItems[] = [
            'name' => $itemName,
            'staff' => $staffName,
            'qty' => $qty,
            'rate' => $rate,
            'total' => $lineTotal,
        ];
    }


    $serviceSubtotal = round(
        $serviceSubtotal,
        2
    );


    /*
    |--------------------------------------------------------------------------
    | Totals
    |--------------------------------------------------------------------------
    */

    $discount = (float) data_get(
        $bill,
        'discount_amount',
        data_get(
            $bill,
            'discount',
            0
        )
    );


    $tax = (float) data_get(
        $bill,
        'tax_amount',
        data_get(
            $bill,
            'tax',
            0
        )
    );


    $productAmount = (float) data_get(
        $bill,
        'product_amount',
        data_get(
            $bill,
            'product_total',
            0
        )
    );


    $grandTotal = max(
        0,
        round(
            $serviceSubtotal
            - $discount
            + $tax
            + $productAmount,
            2
        )
    );


    /*
    |--------------------------------------------------------------------------
    | Payment Details
    |--------------------------------------------------------------------------
    */

    $paymentRows = [];

    $paymentsTotal = 0;

    foreach ($payments as $payment) {

        $method = data_get(
            $payment,
            'payment_method',
            data_get(
                $payment,
                'method',
                'Payment'
            )
        );


        $amount = (float) data_get(
            $payment,
            'amount',
            0
        );


        $paymentRows[] = [
            'method' => $method,
            'amount' => $amount,
        ];


        $paymentsTotal += $amount;
    }


    /*
    |--------------------------------------------------------------------------
    | Paid Amount
    |--------------------------------------------------------------------------
    */

    $paidAmount = (float) data_get(
        $bill,
        'paid_amount',
        data_get(
            $bill,
            'amount_paid',
            $paymentsTotal
        )
    );


    if ($paidAmount == 0 && $paymentsTotal > 0) {

        $paidAmount = $paymentsTotal;
    }


    /*
    |--------------------------------------------------------------------------
    | Balance Due
    |--------------------------------------------------------------------------
    */

    $balanceDue = max(
        0,
        round(
            $grandTotal - $paidAmount,
            2
        )
    );


    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */

    $paymentStatus = data_get(
        $bill,
        'payment_status',
        'Pending'
    );


    $paymentMethod = data_get(
        $bill,
        'payment_method',
        'Not recorded'
    );


    /*
    |--------------------------------------------------------------------------
    | Bluetooth Thermal Data
    |--------------------------------------------------------------------------
    */

    $bluetoothData = [

        'salonName' => (string) $salonName,

        'branch' => (string) $branchName,

        'phone' => (string) $phone,

        'email' => (string) $email,

        'gst' => (string) $gst,

        'receiptFooter' => (string) $receiptFooter,

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Dynamic Logo & Signature
        |--------------------------------------------------------------------------
        */

        'logo' => $logoSrc,

        'signature' => $signatureSrc,

        'invoice' => (string) $invoiceNo,

        'customer' => (string) $customerName,

        'date' => (string) $billDate,

        'items' => $receiptItems,

        'serviceSubtotal' => $serviceSubtotal,

        'productAmount' => $productAmount,

        'discount' => $discount,

        'tax' => $tax,

        'grandTotal' => $grandTotal,

        'paymentStatus' => (string) $paymentStatus,

        'paymentMethod' => (string) $paymentMethod,

        'payments' => $paymentRows,

        'paidAmount' => $paidAmount,

        'balanceDue' => $balanceDue,
    ];
@endphp


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>
        Thermal Receipt
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            font-family: Arial, sans-serif;

            color: #111;

            margin: 0;

            background: #f3f4f6;
        }


        .toolbar {

            max-width: 900px;

            margin: 20px auto;

            padding: 14px;

            background: #fff;

            border-radius: 10px;

            display: flex;

            gap: 9px;

            flex-wrap: wrap;

            align-items: center;
        }


        button,
        select {

            border: 0;

            border-radius: 6px;

            padding: 10px 14px;

            font-weight: 600;

            cursor: pointer;
        }


        button {

            color: #fff;
        }


        button:disabled {

            opacity: .55;

            cursor: not-allowed;
        }


        .blue {

            background: #2563eb;
        }


        .green {

            background: #15803d;
        }


        .red {

            background: #b91c1c;
        }


        .toolbar label {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            font-size: 14px;
        }


        #status {

            font-size: 13px;

            color: #374151;

            width: 100%;

            padding-top: 5px;
        }


        .receipt {

            --paper-width: 80mm;

            width: var(--paper-width);

            max-width: calc(100% - 20px);

            margin: 18px auto;

            padding: 4mm;

            background: #fff;

            font-size: 12px;

            box-shadow: 0 2px 12px #00000012;

            overflow-wrap: anywhere;
        }


        .receipt.paper-58 {

            --paper-width: 58mm;
        }


        .center {

            text-align: center;
        }


        .bold {

            font-weight: bold;
        }


        .line {

            border-top: 1px dashed #333;

            margin: 7px 0;

            width: 100%;
        }


        .row {

            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            gap: 7px;

            margin: 4px 0;

            width: 100%;
        }


        .row span:first-child {

            flex: 1;

            min-width: 0;
        }


        .row span:last-child {

            text-align: right;

            white-space: nowrap;

            flex-shrink: 0;
        }


        .item {

            margin: 7px 0;

            page-break-inside: avoid;

            break-inside: avoid;
        }


        .item-name {

            font-weight: 600;

            overflow-wrap: anywhere;

            margin-bottom: 2px;
        }


        .item-detail {

            font-size: 11px;
        }


        .small {

            font-size: 11px;
        }


        /*
        |--------------------------------------------------------------------------
        | Logo
        |--------------------------------------------------------------------------
        */

        .logo-image {

            display: block;

            max-width: 100px;

            max-height: 65px;

            object-fit: contain;

            margin: 0 auto 5px;
        }


        /*
        |--------------------------------------------------------------------------
        | Signature
        |--------------------------------------------------------------------------
        */

        .signature-area {

            text-align: right;

            margin-top: 12px;

            page-break-inside: avoid;

            break-inside: avoid;
        }


        .signature-image {

            display: block;

            max-width: 125px;

            max-height: 55px;

            object-fit: contain;

            margin: 6px 0 0 auto;
        }


        .signature-line {

            width: 120px;

            margin: 8px 0 0 auto;

            border-top: 1px solid #333;

            padding-top: 4px;

            text-align: center;

            font-size: 10px;
        }


        .footer {

            text-align: center;

            margin-top: 8px;

            padding-bottom: 3px;

            font-weight: bold;
        }


        @media print {

            @page {

                size: 80mm auto;

                margin: 0;
            }


            html,
            body {

                background: #fff;

                margin: 0;

                padding: 0;
            }


            .toolbar {

                display: none !important;
            }


            .receipt {

                width: var(--paper-width);

                max-width: none;

                margin: 0;

                padding: 3mm;

                box-shadow: none;

                border-radius: 0;
            }
        }

    </style>

</head>


<body>


<div class="toolbar">


    <!-- Normal Browser / USB Printer -->

    <button class="blue"
            type="button"
            onclick="window.print()">

        Normal Print / USB Printer

    </button>


    <!-- Connect Printer -->

    <button class="green"
            type="button"
            id="connectBluetoothBtn"
            onclick="connectBluetooth()">

        Connect Printer

    </button>


    <!-- Thermal Print -->

    <button class="green"
            type="button"
            id="bluetoothPrintBtn"
            onclick="printBluetooth()"
            disabled>

        Thermal Print

    </button>


    <!-- Disconnect -->

    <button class="red"
            type="button"
            id="disconnectBluetoothBtn"
            onclick="disconnectBluetooth()"
            disabled>

        Disconnect

    </button>


    <!-- Logo -->

    <label>

        <input type="checkbox"
               id="includeLogo"
               {{ $defaultPrintLogo ? 'checked' : '' }}
               onchange="toggleReceiptOptions()">

        Show Logo

    </label>


    <!-- Signature -->

    <label>

        <input type="checkbox"
               id="includeSignature"
               {{ $defaultPrintSignature ? 'checked' : '' }}
               onchange="toggleReceiptOptions()">

        Show Signature

    </label>


    <!-- Paper Size -->

    <label for="paperSize">

        Paper:

    </label>


    <select id="paperSize"
            onchange="changePaperSize()">

        <option value="80"
                {{ $defaultPaperSize == '80' ? 'selected' : '' }}>

            80mm

        </option>


        <option value="58"
                {{ $defaultPaperSize == '58' ? 'selected' : '' }}>

            58mm

        </option>

    </select>


    <div id="status"
         role="status">

        Printer not connected.
        Connect a supported serial printer to print.

    </div>

</div>


<!-- ===================================================================== -->
<!-- RECEIPT -->
<!-- ===================================================================== -->

<main class="receipt"
      id="receipt">


    <!-- Business Header -->

    <div class="center"
         id="businessHeader">


        <!-- Dynamic Logo -->

        @if($logoSrc)

            <img id="salonLogo"
                 class="logo-image"
                 src="{{ $logoSrc }}"
                 alt="Salon Logo"
                 onerror="this.style.display='none'">

        @endif


        <!-- Salon Name -->

        <div class="bold"
             style="font-size:17px">

            {{ $salonName }}

        </div>


        <!-- Branch -->

        @if($branchName)

            <div>

                {{ $branchName }}

            </div>

        @endif


        <!-- Phone -->

        @if($phone)

            <div>

                Phone: {{ $phone }}

            </div>

        @endif


        <!-- Email -->

        @if($email)

            <div>

                {{ $email }}

            </div>

        @endif


        <!-- GST -->

        @if($gst)

            <div>

                GSTIN: {{ $gst }}

            </div>

        @endif

    </div>


    <div class="line"></div>


    <!-- Invoice -->

    <div class="row">

        <span>
            Invoice No
        </span>

        <span>
            {{ $invoiceNo }}
        </span>

    </div>


    <!-- Date -->

    <div class="row">

        <span>
            Date
        </span>

        <span>
            {{ $billDate }}
        </span>

    </div>


    <!-- Customer -->

    <div class="row">

        <span>
            Customer
        </span>

        <span>
            {{ $customerName }}
        </span>

    </div>


    <div class="line"></div>


    <!-- Item Header -->

    <div class="row bold">

        <span>
            Item
        </span>

        <span>
            Amount
        </span>

    </div>


    <!-- Items -->

    @foreach($receiptItems as $item)

        <div class="item">


            <div class="item-name">

                {{ $item['name'] }}

            </div>


            <div class="row item-detail">

                <span>

                    {{ rtrim(
                        rtrim(
                            number_format(
                                $item['qty'],
                                2,
                                '.',
                                ''
                            ),
                            '0'
                        ),
                        '.'
                    ) }}

                    x

                    {{ number_format(
                        $item['rate'],
                        2,
                        '.',
                        ''
                    ) }}


                    @if($item['staff'])

                        · {{ $item['staff'] }}

                    @endif

                </span>


                <span>

                    ₹{{ number_format(
                        $item['total'],
                        2
                    ) }}

                </span>

            </div>

        </div>

    @endforeach


    <div class="line"></div>


    <!-- Service Subtotal -->

    <div class="row">

        <span>
            Service Subtotal
        </span>

        <span>
            ₹{{ number_format(
                $serviceSubtotal,
                2
            ) }}
        </span>

    </div>


    <!-- Product -->

    <div class="row">

        <span>
            Product Amount
        </span>

        <span>
            ₹{{ number_format(
                $productAmount,
                2
            ) }}
        </span>

    </div>


    <!-- Discount -->

    <div class="row">

        <span>
            Discount
        </span>

        <span>
            ₹{{ number_format(
                $discount,
                2
            ) }}
        </span>

    </div>


    <!-- Tax -->

    <div class="row">

        <span>
            Tax
        </span>

        <span>
            ₹{{ number_format(
                $tax,
                2
            ) }}
        </span>

    </div>


    <div class="line"></div>


    <!-- Grand Total -->

    <div class="row bold"
         style="font-size:15px">

        <span>
            GRAND TOTAL
        </span>

        <span>
            ₹{{ number_format(
                $grandTotal,
                2
            ) }}
        </span>

    </div>


    <div class="line"></div>


    <!-- Payment Details -->

    <div class="bold">

        Payment Details

    </div>


    @if(count($paymentRows))


        @foreach($paymentRows as $payment)

            <div class="row">

                <span>

                    {{ $payment['method'] }}

                </span>

                <span>

                    ₹{{ number_format(
                        $payment['amount'],
                        2
                    ) }}

                </span>

            </div>

        @endforeach


    @else


        <div class="row">

            <span>
                Payment Method
            </span>

            <span>
                {{ $paymentMethod }}
            </span>

        </div>


    @endif


    <!-- Status -->

    <div class="row">

        <span>
            Status
        </span>

        <span>

            {{ ucfirst($paymentStatus) }}

        </span>

    </div>


    <!-- Paid -->

    <div class="row">

        <span>
            Paid Amount
        </span>

        <span>

            ₹{{ number_format(
                $paidAmount,
                2
            ) }}

        </span>

    </div>


    <!-- Balance -->

    <div class="row">

        <span>
            Balance / Due
        </span>

        <span>

            ₹{{ number_format(
                $balanceDue,
                2
            ) }}

        </span>

    </div>


    <div class="line"></div>


    <!-- Footer -->

    <div class="footer">

        {{ $receiptFooter ?: 'Thank You! Visit Again.' }}

    </div>


    <!-- ================================================================ -->
    <!-- AUTHORIZED SIGNATURE -->
    <!-- ================================================================ -->

    <div class="signature-area"
         id="signatureSection"
         style="{{ $defaultPrintSignature ? '' : 'display:none;' }}">


        @if($signatureSrc)

            <img id="salonSignature"
                 class="signature-image"
                 src="{{ $signatureSrc }}"
                 alt="Authorized Signature"
                 onerror="this.style.display='none'">

        @endif


        <div class="signature-line">

            Authorized Signature

        </div>

    </div>


</main>


<script>

'use strict';


/*
|--------------------------------------------------------------------------
| Thermal Printer Variables
|--------------------------------------------------------------------------
*/

let thermalPort = null;

let thermalWriter = null;

const BAUD_RATE = 9600;

const IMAGE_THRESHOLD = 160;


/*
|--------------------------------------------------------------------------
| Laravel Bill Data
|--------------------------------------------------------------------------
*/

const billData = @json($bluetoothData);


/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

function setStatus(message) {

    const statusElement =
        document.getElementById('status');

    if (statusElement) {

        statusElement.textContent =
            message || '';

    }
}


/*
|--------------------------------------------------------------------------
| Logo / Signature Toggle
|--------------------------------------------------------------------------
*/

function toggleReceiptOptions() {

    const showLogo =
        document.getElementById(
            'includeLogo'
        ).checked;


    const showSignature =
        document.getElementById(
            'includeSignature'
        ).checked;


    const logo =
        document.getElementById(
            'salonLogo'
        );


    if (logo) {

        logo.style.display =
            showLogo
                ? 'block'
                : 'none';
    }


    const signatureSection =
        document.getElementById(
            'signatureSection'
        );


    if (signatureSection) {

        signatureSection.style.display =
            showSignature
                ? 'block'
                : 'none';
    }
}


/*
|--------------------------------------------------------------------------
| Paper Size
|--------------------------------------------------------------------------
*/

function changePaperSize() {

    const size =
        document.getElementById(
            'paperSize'
        ).value;


    document.getElementById(
        'receipt'
    ).classList.toggle(
        'paper-58',
        size === '58'
    );
}


/*
|--------------------------------------------------------------------------
| Printer Buttons
|--------------------------------------------------------------------------
*/

function updatePrinterButtons(
    connected
) {

    document.getElementById(
        'connectBluetoothBtn'
    ).disabled = connected;


    document.getElementById(
        'bluetoothPrintBtn'
    ).disabled = !connected;


    document.getElementById(
        'disconnectBluetoothBtn'
    ).disabled = !connected;
}


/*
|--------------------------------------------------------------------------
| Connect Printer
|--------------------------------------------------------------------------
*/

async function connectBluetooth() {

    try {

        if (!('serial' in navigator)) {

            throw new Error(
                'Web Serial is not available in this browser. Please use Chrome or Edge on a desktop.'
            );
        }


        setStatus(
            'Select the printer port...'
        );


        thermalPort =
            await navigator.serial.requestPort();


        await thermalPort.open({
            baudRate: BAUD_RATE
        });


        thermalWriter =
            thermalPort.writable.getWriter();


        updatePrinterButtons(true);


        setStatus(
            'Printer connected. Now click "Thermal Print".'
        );


    } catch (error) {

        thermalPort = null;

        thermalWriter = null;


        updatePrinterButtons(false);


        setStatus(
            'Connection error: ' +
            error.message
        );


        alert(error.message);
    }
}


/*
|--------------------------------------------------------------------------
| Disconnect Printer
|--------------------------------------------------------------------------
*/

async function disconnectBluetooth() {

    try {

        if (thermalWriter) {

            thermalWriter.releaseLock();

            thermalWriter = null;
        }


        if (thermalPort) {

            await thermalPort.close();

            thermalPort = null;
        }


        updatePrinterButtons(false);


        setStatus(
            'Printer disconnected.'
        );


    } catch (error) {

        setStatus(
            'Disconnect error: ' +
            error.message
        );
    }
}


/*
|--------------------------------------------------------------------------
| ESC/POS Commands
|--------------------------------------------------------------------------
*/

function command(...bytes) {

    return new Uint8Array(bytes);
}


/*
|--------------------------------------------------------------------------
| Text Bytes
|--------------------------------------------------------------------------
*/

function textBytes(value) {

    return new TextEncoder().encode(
        String(value ?? '') + '\n'
    );
}


/*
|--------------------------------------------------------------------------
| Combine Uint8Arrays
|--------------------------------------------------------------------------
*/

function concatBytes(parts) {

    const totalLength =
        parts.reduce(
            (sum, part) =>
                sum + part.length,
            0
        );


    const output =
        new Uint8Array(totalLength);


    let offset = 0;


    for (const part of parts) {

        output.set(
            part,
            offset
        );

        offset += part.length;
    }


    return output;
}


/*
|--------------------------------------------------------------------------
| Clean Text
|--------------------------------------------------------------------------
*/

function cleanPrinterText(value) {

    return String(value ?? '')

        .replace(/\r/g, ' ')

        .replace(/\n/g, ' ')

        .trim();
}


/*
|--------------------------------------------------------------------------
| Wrap Text
|--------------------------------------------------------------------------
*/

function wrapText(
    value,
    maxChars
) {

    const words =
        cleanPrinterText(value)
            .split(/\s+/);


    const lines = [];

    let current = '';


    for (const word of words) {

        if (!word) {

            continue;
        }


        if (!current) {

            current = word;

        } else if (
            (
                current +
                ' ' +
                word
            ).length <= maxChars
        ) {

            current +=
                ' ' +
                word;

        } else {

            lines.push(
                current
            );

            current = word;
        }
    }


    if (current) {

        lines.push(
            current
        );
    }


    return lines;
}


/*
|--------------------------------------------------------------------------
| Add Wrapped Text
|--------------------------------------------------------------------------
*/

function addWrappedText(
    parts,
    value,
    maxChars
) {

    if (!value) {

        return;
    }


    wrapText(
        value,
        maxChars
    ).forEach(
        line =>
            parts.push(
                textBytes(line)
            )
    );
}


/*
|--------------------------------------------------------------------------
| Number Formatting
|--------------------------------------------------------------------------
*/

function formatNumber(value) {

    const number =
        Number(value) || 0;


    return Number.isInteger(number)

        ? String(number)

        : String(
            Number(
                number.toFixed(2)
            )
        );
}


/*
|--------------------------------------------------------------------------
| Item Amount
|--------------------------------------------------------------------------
*/

function addItemAmountLine(
    parts,
    qty,
    rate,
    amount,
    maxChars
) {

    const left =
        formatNumber(qty) +
        ' x ' +
        formatNumber(rate) +
        ' =';


    const right =
        formatNumber(amount);


    if (
        left.length +
        right.length +
        1 >
        maxChars
    ) {

        const availableLeft =
            Math.max(
                1,
                maxChars -
                right.length -
                1
            );


        const shortLeft =
            left.slice(
                0,
                availableLeft
            );


        const spaces =
            Math.max(
                1,
                maxChars -
                shortLeft.length -
                right.length
            );


        parts.push(
            textBytes(
                shortLeft +
                ' '.repeat(spaces) +
                right
            )
        );


        return;
    }


    const spaces =
        Math.max(
            1,
            maxChars -
            left.length -
            right.length
        );


    parts.push(
        textBytes(
            left +
            ' '.repeat(spaces) +
            right
        )
    );
}


/*
|--------------------------------------------------------------------------
| Load Image
|--------------------------------------------------------------------------
*/

function loadImage(src) {

    return new Promise(
        (resolve, reject) => {

            if (!src) {

                reject(
                    new Error(
                        'Image URL is empty.'
                    )
                );

                return;
            }


            const img =
                new Image();


            img.onload = async () => {

                try {

                    if (img.decode) {

                        await img.decode();
                    }


                    if (
                        !img.naturalWidth ||
                        !img.naturalHeight
                    ) {

                        reject(
                            new Error(
                                'Image dimensions are not available.'
                            )
                        );

                        return;
                    }


                    resolve(img);


                } catch (error) {

                    reject(
                        new Error(
                            'Image decode error: ' +
                            error.message
                        )
                    );
                }
            };


            img.onerror = () => {

                reject(
                    new Error(
                        'Image could not load. Check the URL: ' +
                        src
                    )
                );
            };


            img.src = src;
        }
    );
}


/*
|--------------------------------------------------------------------------
| Convert Image To ESC/POS Raster
|--------------------------------------------------------------------------
*/

async function imageToEscPos(
    src,
    targetWidth,
    threshold = IMAGE_THRESHOLD
) {

    const img =
        await loadImage(src);


    const width =
        Math.max(
            8,
            Math.floor(
                targetWidth / 8
            ) * 8
        );


    const ratio =
        img.naturalHeight /
        img.naturalWidth;


    const height =
        Math.max(
            1,
            Math.round(
                width * ratio
            )
        );


    const canvas =
        document.createElement(
            'canvas'
        );


    canvas.width =
        width;


    canvas.height =
        height;


    const ctx =
        canvas.getContext(
            '2d',
            {
                willReadFrequently: true
            }
        );


    if (!ctx) {

        throw new Error(
            'Canvas context मिळाला नाही.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | White Background
    |--------------------------------------------------------------------------
    */

    ctx.fillStyle =
        '#ffffff';


    ctx.fillRect(
        0,
        0,
        width,
        height
    );


    /*
    |--------------------------------------------------------------------------
    | Draw Image
    |--------------------------------------------------------------------------
    */

    ctx.drawImage(
        img,
        0,
        0,
        width,
        height
    );


    /*
    |--------------------------------------------------------------------------
    | Read Pixels
    |--------------------------------------------------------------------------
    */

    const pixels =
        ctx.getImageData(
            0,
            0,
            width,
            height
        ).data;


    const widthBytes =
        width / 8;


    const bitmap =
        new Uint8Array(
            widthBytes *
            height
        );


    /*
    |--------------------------------------------------------------------------
    | Convert To 1-Bit
    |--------------------------------------------------------------------------
    */

    for (
        let y = 0;
        y < height;
        y++
    ) {

        for (
            let x = 0;
            x < width;
            x++
        ) {

            const i =
                (
                    y * width +
                    x
                ) * 4;


            const red =
                pixels[i];


            const green =
                pixels[i + 1];


            const blue =
                pixels[i + 2];


            const gray =
                (
                    red * 0.299
                ) +
                (
                    green * 0.587
                ) +
                (
                    blue * 0.114
                );


            if (gray < threshold) {

                const byteIndex =
                    (
                        y * widthBytes
                    ) +
                    Math.floor(
                        x / 8
                    );


                bitmap[
                    byteIndex
                ] |= (
                    0x80 >>
                    (
                        x % 8
                    )
                );
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ESC/POS Raster Dimensions
    |--------------------------------------------------------------------------
    */

    const xL =
        widthBytes & 0xff;


    const xH =
        (
            widthBytes >> 8
        ) & 0xff;


    const yL =
        height & 0xff;


    const yH =
        (
            height >> 8
        ) & 0xff;


    /*
    |--------------------------------------------------------------------------
    | GS v 0 Raster Command
    |--------------------------------------------------------------------------
    */

    return concatBytes([

        command(
            0x1d,
            0x76,
            0x30,
            0x00,
            xL,
            xH,
            yL,
            yH
        ),

        bitmap

    ]);
}


/*
|--------------------------------------------------------------------------
| Build Thermal Receipt
|--------------------------------------------------------------------------
*/

async function buildThermalReceipt() {

    const parts = [];


    const showLogo =
        document.getElementById(
            'includeLogo'
        ).checked;


    const showSignature =
        document.getElementById(
            'includeSignature'
        ).checked;


    const paperSize =
        document.getElementById(
            'paperSize'
        ).value;


    const maxChars =
        paperSize === '58'
            ? 32
            : 48;


    const divider =
        '-'.repeat(
            maxChars
        );


    /*
    |--------------------------------------------------------------------------
    | LOGO
    |--------------------------------------------------------------------------
    */

    if (showLogo) {

        if (!billData.logo) {

            throw new Error(
                'Logo checkbox checked आहे, पण logo URL मिळाली नाही.'
            );
        }


        setStatus(
            'Logo image load आणि convert होत आहे...'
        );


        const logoWidth =
            paperSize === '58'
                ? 384
                : 576;


        const logoBytes =
            await imageToEscPos(
                billData.logo,
                logoWidth,
                IMAGE_THRESHOLD
            );


        parts.push(
            command(
                0x1b,
                0x61,
                0x01
            )
        );


        parts.push(
            logoBytes
        );


        parts.push(
            command(0x0a)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Initialize Printer
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(
            0x1b,
            0x40
        )
    );


    /*
    |--------------------------------------------------------------------------
    | Salon Header
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(
            0x1b,
            0x61,
            0x01
        )
    );


    parts.push(
        command(
            0x1b,
            0x45,
            0x01
        )
    );


    addWrappedText(
        parts,
        billData.salonName,
        maxChars
    );


    parts.push(
        command(
            0x1b,
            0x45,
            0x00
        )
    );


    if (billData.branch) {

        addWrappedText(
            parts,
            billData.branch,
            maxChars
        );
    }


    if (billData.phone) {

        addWrappedText(
            parts,
            'Phone: ' +
            billData.phone,
            maxChars
        );
    }


    if (billData.email) {

        addWrappedText(
            parts,
            'Email: ' +
            billData.email,
            maxChars
        );
    }


    if (billData.gst) {

        addWrappedText(
            parts,
            'GSTIN: ' +
            billData.gst,
            maxChars
        );
    }


    parts.push(
        command(0x0a)
    );


    parts.push(
        command(
            0x1b,
            0x61,
            0x00
        )
    );


    parts.push(
        textBytes(divider)
    );


    /*
    |--------------------------------------------------------------------------
    | Bill Details
    |--------------------------------------------------------------------------
    */

    parts.push(
        textBytes(
            'Invoice: ' +
            cleanPrinterText(
                billData.invoice
            )
        )
    );


    parts.push(
        textBytes(
            'Date: ' +
            cleanPrinterText(
                billData.date
            )
        )
    );


    addWrappedText(
        parts,
        'Customer: ' +
        billData.customer,
        maxChars
    );


    parts.push(
        textBytes(divider)
    );


    /*
    |--------------------------------------------------------------------------
    | Items
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(
            0x1b,
            0x45,
            0x01
        )
    );


    parts.push(
        textBytes('ITEMS')
    );


    parts.push(
        command(
            0x1b,
            0x45,
            0x00
        )
    );


    for (
        const item of (
            billData.items || []
        )
    ) {

        const qty =
            Number(item.qty) || 0;


        const rate =
            Number(item.rate) || 0;


        const total =
            Number(item.total) ||
            (
                qty * rate
            );


        addWrappedText(
            parts,
            item.name,
            maxChars
        );


        addItemAmountLine(
            parts,
            qty,
            rate,
            total,
            maxChars
        );


        if (item.staff) {

            addWrappedText(
                parts,
                'Staff: ' +
                item.staff,
                maxChars
            );
        }
    }


    parts.push(
        textBytes(divider)
    );


    /*
    |--------------------------------------------------------------------------
    | Totals
    |--------------------------------------------------------------------------
    */

    parts.push(
        textBytes(
            'Service Subtotal: ' +
            Number(
                billData.serviceSubtotal || 0
            ).toFixed(2)
        )
    );


    parts.push(
        textBytes(
            'Product Amount: ' +
            Number(
                billData.productAmount || 0
            ).toFixed(2)
        )
    );


    parts.push(
        textBytes(
            'Discount: ' +
            Number(
                billData.discount || 0
            ).toFixed(2)
        )
    );


    parts.push(
        textBytes(
            'Tax: ' +
            Number(
                billData.tax || 0
            ).toFixed(2)
        )
    );


    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(
            0x1b,
            0x45,
            0x01
        )
    );


    parts.push(
        textBytes(
            'GRAND TOTAL: ' +
            Number(
                billData.grandTotal || 0
            ).toFixed(2)
        )
    );


    parts.push(
        command(
            0x1b,
            0x45,
            0x00
        )
    );


    parts.push(
        textBytes(divider)
    );


    /*
    |--------------------------------------------------------------------------
    | Payment Details
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(
            0x1b,
            0x45,
            0x01
        )
    );


    parts.push(
        textBytes(
            'PAYMENT DETAILS'
        )
    );


    parts.push(
        command(
            0x1b,
            0x45,
            0x00
        )
    );


    if (
        Array.isArray(
            billData.payments
        ) &&
        billData.payments.length
    ) {

        for (
            const payment of
            billData.payments
        ) {

            parts.push(
                textBytes(
                    cleanPrinterText(
                        payment.method
                    ) +
                    ': ' +
                    Number(
                        payment.amount || 0
                    ).toFixed(2)
                )
            );
        }


    } else {

        addWrappedText(
            parts,
            'Method: ' +
            cleanPrinterText(
                billData.paymentMethod
            ),
            maxChars
        );
    }


    parts.push(
        textBytes(
            'Status: ' +
            cleanPrinterText(
                billData.paymentStatus
            )
        )
    );


    parts.push(
        textBytes(
            'Paid Amount: ' +
            Number(
                billData.paidAmount || 0
            ).toFixed(2)
        )
    );


    parts.push(
        textBytes(
            'Balance / Due: ' +
            Number(
                billData.balanceDue || 0
            ).toFixed(2)
        )
    );


    parts.push(
        textBytes(divider)
    );


    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(
            0x1b,
            0x61,
            0x01
        )
    );


    addWrappedText(
        parts,
        billData.receiptFooter ||
        'Thank You! Visit Again.',
        maxChars
    );


    parts.push(
        command(
            0x1b,
            0x61,
            0x00
        )
    );


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZED SIGNATURE
    |--------------------------------------------------------------------------
    */

    if (
        showSignature &&
        billData.signature
    ) {

        setStatus(
            'Signature image convert होत आहे...'
        );


        const signatureWidth =
            paperSize === '58'
                ? 240
                : 320;


        const signatureBytes =
            await imageToEscPos(
                billData.signature,
                signatureWidth,
                IMAGE_THRESHOLD
            );


        parts.push(
            command(0x0a)
        );


        parts.push(
            command(
                0x1b,
                0x61,
                0x01
            )
        );


        parts.push(
            signatureBytes
        );


        parts.push(
            command(0x0a)
        );


        parts.push(
            textBytes(
                'Authorized Signature'
            )
        );


        parts.push(
            command(
                0x1b,
                0x61,
                0x00
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Feed Paper
    |--------------------------------------------------------------------------
    */

    parts.push(
        command(0x0a)
    );


    parts.push(
        textBytes(divider)
    );


    parts.push(
        command(
            0x1b,
            0x64,
            0x05
        )
    );


    return concatBytes(parts);
}


/*
|--------------------------------------------------------------------------
| Sleep
|--------------------------------------------------------------------------
*/

function sleep(ms) {

    return new Promise(
        resolve =>
            setTimeout(
                resolve,
                ms
            )
    );
}


/*
|--------------------------------------------------------------------------
| Write Data In Chunks
|--------------------------------------------------------------------------
*/

async function writeInChunks(data) {

    if (!thermalWriter) {

        throw new Error(
            'Printer writer उपलब्ध नाही.'
        );
    }


    const CHUNK_SIZE = 128;

    const DELAY_MS = 30;


    for (
        let offset = 0;
        offset < data.length;
        offset += CHUNK_SIZE
    ) {

        const chunk =
            data.slice(
                offset,
                Math.min(
                    offset + CHUNK_SIZE,
                    data.length
                )
            );


        await thermalWriter.write(
            chunk
        );


        await sleep(
            DELAY_MS
        );
    }
}


/*
|--------------------------------------------------------------------------
| Print Bluetooth / Web Serial
|--------------------------------------------------------------------------
*/

async function printBluetooth() {

    try {

        if (
            !thermalPort ||
            !thermalWriter ||
            !thermalPort.writable
        ) {

            throw new Error(
                'Printer connected नाही. आधी Connect Printer दाबा.'
            );
        }


        setStatus(
            'Thermal receipt तयार होत आहे...'
        );


        const data =
            await buildThermalReceipt();


        setStatus(
            'Printer कडे data पाठवत आहे...'
        );


        await writeInChunks(
            data
        );


        setStatus(
            'Print data पाठवला. Printer कडून receipt तपासा.'
        );


    } catch (error) {

        console.error(
            'Thermal print error:',
            error
        );


        setStatus(
            'Print error: ' +
            (
                error.message ||
                'Unknown error'
            )
        );


        alert(
            error.message ||
            'Thermal print करताना error आला.'
        );
    }
}


/*
|--------------------------------------------------------------------------
| Initial Page Setup
|--------------------------------------------------------------------------
*/

toggleReceiptOptions();

changePaperSize();

updatePrinterButtons(false);

</script>


</body>

</html>