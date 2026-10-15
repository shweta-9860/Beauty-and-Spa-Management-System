
@extends('admin.layout')

@section('title', 'Printer Settings | Salon Management')

@section('page-title', 'Printer Settings')

@section('content')

<div class="container-fluid px-0">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- ERROR MESSAGE --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row">

        {{-- MAIN SETTINGS --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-printer me-2"></i>
                        Printer Configuration
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.settings.printer.update') }}">

                        @csrf


                        {{-- PRINTER NAME --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Printer Name
                            </label>

                            <input type="text"
                                   name="printer_name"
                                   class="form-control"
                                   value="{{ old('printer_name', $printerSetting->printer_name) }}"
                                   placeholder="Example: Blueprint Receipt Printer">

                            <small class="text-muted">
                                Enter a name to identify your receipt printer.
                            </small>

                        </div>


                        {{-- DEFAULT PRINT METHOD --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Default Print Method
                            </label>

                            <select name="default_print_method"
                                    class="form-select">

                                <option value="browser"
                                    {{ old('default_print_method', $printerSetting->default_print_method) == 'browser' ? 'selected' : '' }}>
                                    Browser Print
                                </option>

                                <option value="bluetooth"
                                    {{ old('default_print_method', $printerSetting->default_print_method) == 'bluetooth' ? 'selected' : '' }}>
                                    Bluetooth Printer
                                </option>

                                <option value="usb"
                                    {{ old('default_print_method', $printerSetting->default_print_method) == 'usb' ? 'selected' : '' }}>
                                    USB Printer
                                </option>

                            </select>

                        </div>


                        {{-- PAPER SIZE --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Paper Size
                            </label>

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-check border rounded p-3">

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="paper_size"
                                            id="paper58"
                                            value="58mm"
                                            {{ old('paper_size', $printerSetting->paper_size) == '58mm' ? 'checked' : '' }}
                                        >

                                        <label class="form-check-label"
                                               for="paper58">

                                            <strong>58mm</strong>

                                            <br>

                                            <small class="text-muted">
                                                Small thermal receipt
                                            </small>

                                        </label>

                                    </div>

                                </div>


                                <div class="col-md-6">

                                    <div class="form-check border rounded p-3">

                                        <input
                                            class="form-check-input"
                                            type="radio"
                                            name="paper_size"
                                            id="paper80"
                                            value="80mm"
                                            {{ old('paper_size', $printerSetting->paper_size) == '80mm' ? 'checked' : '' }}
                                        >

                                        <label class="form-check-label"
                                               for="paper80">

                                            <strong>80mm</strong>

                                            <br>

                                            <small class="text-muted">
                                                Standard thermal receipt
                                            </small>

                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- PRINT OPTIONS --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Print Options
                            </label>


                            <div class="form-check form-switch mb-3">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="print_logo"
                                    value="1"
                                    id="printLogo"
                                    {{ old('print_logo', $printerSetting->print_logo) ? 'checked' : '' }}
                                >

                                <label class="form-check-label"
                                       for="printLogo">

                                    Print Logo by Default

                                </label>

                            </div>


                            <div class="form-check form-switch mb-3">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="print_signature"
                                    value="1"
                                    id="printSignature"
                                    {{ old('print_signature', $printerSetting->print_signature) ? 'checked' : '' }}
                                >

                                <label class="form-check-label"
                                       for="printSignature">

                                    Print Authorized Signature by Default

                                </label>

                            </div>


                            <div class="form-check form-switch">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="auto_cut"
                                    value="1"
                                    id="autoCut"
                                    {{ old('auto_cut', $printerSetting->auto_cut) ? 'checked' : '' }}
                                >

                                <label class="form-check-label"
                                       for="autoCut">

                                    Auto Cut Paper

                                </label>

                            </div>

                        </div>


                        {{-- SAVE BUTTON --}}
                        <div class="d-flex justify-content-end">

                            <button type="submit"
                                    class="btn btn-primary px-4">

                                <i class="bi bi-save me-2"></i>
                                Save Printer Settings

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- CONNECTION PANEL --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white border-bottom py-3">

                    <h5 class="mb-0">
                        <i class="bi bi-bluetooth me-2"></i>
                        Printer Connection
                    </h5>

                </div>


                <div class="card-body">

                    <div id="printerStatus"
                         class="alert alert-secondary text-center">

                        <i class="bi bi-printer me-2"></i>

                        Printer Not Connected

                    </div>


                    <div class="d-grid gap-2">

                        <button type="button"
                                class="btn btn-primary"
                                onclick="connectBluetoothPrinter()">

                            <i class="bi bi-bluetooth me-2"></i>

                            Connect Bluetooth

                        </button>


                        <button type="button"
                                class="btn btn-dark"
                                onclick="connectUsbPrinter()">

                            <i class="bi bi-usb-drive me-2"></i>

                            Connect USB

                        </button>


                        <button type="button"
                                class="btn btn-outline-danger"
                                onclick="disconnectPrinter()">

                            <i class="bi bi-x-circle me-2"></i>

                            Disconnect

                        </button>

                    </div>


                    <hr>


                    <button type="button"
                            class="btn btn-outline-success w-100"
                            onclick="testPrint()">

                        <i class="bi bi-printer me-2"></i>

                        Test Print

                    </button>


                    <div class="mt-3">

                        <small class="text-muted">

                            <i class="bi bi-info-circle me-1"></i>

                            Bluetooth printing requires a browser/device
                            supporting Web Bluetooth. USB printing requires
                            Web Serial support.

                        </small>

                    </div>

                </div>

            </div>


            {{-- CURRENT SETTINGS --}}
            <div class="card shadow-sm border-0 mt-3">

                <div class="card-header bg-white">

                    <strong>
                        Current Configuration
                    </strong>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span>Print Method</span>

                        <strong>
                            {{ ucfirst($printerSetting->default_print_method) }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-2">

                        <span>Paper Size</span>

                        <strong>
                            {{ $printerSetting->paper_size }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span>Logo</span>

                        <strong>
                            {{ $printerSetting->print_logo ? 'Enabled' : 'Disabled' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

    .form-check.border {
        padding-left: 2.5rem !important;
        cursor: pointer;
        transition: 0.2s;
    }

    .form-check.border:hover {
        border-color: #8b5e6d !important;
        background: #faf7f8;
    }

    .form-switch .form-check-input {
        cursor: pointer;
    }

</style>


<script>

let bluetoothDevice = null;
let bluetoothServer = null;
let bluetoothCharacteristic = null;

let usbPort = null;


/* =========================================================
   STATUS
========================================================= */

function setPrinterStatus(message, type = 'secondary')
{
    const status = document.getElementById('printerStatus');

    status.className = 'alert alert-' + type + ' text-center';

    status.innerHTML =
        '<i class="bi bi-printer me-2"></i>' +
        message;
}


/* =========================================================
   BLUETOOTH CONNECT
========================================================= */

async function connectBluetoothPrinter()
{
    if (!navigator.bluetooth) {

        alert(
            'Web Bluetooth is not supported in this browser. ' +
            'Please use a supported browser/device.'
        );

        return;
    }


    try {

        setPrinterStatus(
            'Connecting to Bluetooth printer...',
            'warning'
        );


        bluetoothDevice = await navigator.bluetooth.requestDevice({

            acceptAllDevices: true,

            optionalServices: [
                '000018f0-0000-1000-8000-00805f9b34fb'
            ]

        });


        bluetoothServer =
            await bluetoothDevice.gatt.connect();


        const service =
            await bluetoothServer.getPrimaryService(
                '000018f0-0000-1000-8000-00805f9b34fb'
            );


        const characteristics =
            await service.getCharacteristics();


        bluetoothCharacteristic =
            characteristics.find(characteristic =>
                characteristic.properties.write ||
                characteristic.properties.writeWithoutResponse
            );


        if (!bluetoothCharacteristic) {

            throw new Error(
                'Writable Bluetooth characteristic was not found.'
            );
        }


        setPrinterStatus(
            'Bluetooth Printer Connected',
            'success'
        );


    } catch (error) {

        console.error(error);

        setPrinterStatus(
            'Bluetooth connection failed',
            'danger'
        );

        alert(error.message);

    }
}


/* =========================================================
   USB CONNECT
========================================================= */

async function connectUsbPrinter()
{
    if (!navigator.serial) {

        alert(
            'Web Serial is not supported in this browser.'
        );

        return;
    }


    try {

        setPrinterStatus(
            'Connecting to USB printer...',
            'warning'
        );


        usbPort =
            await navigator.serial.requestPort();


        await usbPort.open({

            baudRate: 9600,

            dataBits: 8,

            stopBits: 1,

            parity: 'none',

            flowControl: 'none'

        });


        setPrinterStatus(
            'USB Printer Connected',
            'success'
        );


    } catch (error) {

        console.error(error);

        setPrinterStatus(
            'USB connection failed',
            'danger'
        );

        alert(error.message);

    }
}


/* =========================================================
   DISCONNECT
========================================================= */

async function disconnectPrinter()
{
    try {

        if (bluetoothDevice &&
            bluetoothDevice.gatt.connected) {

            bluetoothDevice.gatt.disconnect();

        }

        bluetoothDevice = null;
        bluetoothServer = null;
        bluetoothCharacteristic = null;


        if (usbPort) {

            await usbPort.close();

        }

        usbPort = null;


        setPrinterStatus(
            'Printer Not Connected',
            'secondary'
        );


    } catch (error) {

        console.error(error);

    }
}


/* =========================================================
   TEST PRINT
========================================================= */

async function testPrint()
{
    const testText =
`
================================
       SALON TEST PRINT
================================

Printer: {{ $printerSetting->printer_name ?? 'Receipt Printer' }}

Paper Size: {{ $printerSetting->paper_size }}

--------------------------------

Bluetooth / USB printer test

Print successful!

================================

`;



    const encoder =
        new TextEncoder();


    const data =
        encoder.encode(testText);


    /* BLUETOOTH */

    if (bluetoothCharacteristic) {

        try {

            if (
                bluetoothCharacteristic.properties
                    .writeWithoutResponse
            ) {

                await bluetoothCharacteristic
                    .writeValueWithoutResponse(data);

            } else {

                await bluetoothCharacteristic
                    .writeValue(data);

            }


            alert('Bluetooth test print sent.');

            return;

        } catch (error) {

            console.error(error);

            alert(
                'Bluetooth test print failed: ' +
                error.message
            );

            return;
        }
    }


    /* USB */

    if (usbPort && usbPort.writable) {

        try {

            const writer =
                usbPort.writable.getWriter();

            await writer.write(data);

            writer.releaseLock();


            alert('USB test print sent.');

            return;

        } catch (error) {

            console.error(error);

            alert(
                'USB test print failed: ' +
                error.message
            );

            return;
        }
    }


    /* NO CONNECTION */

    alert(
        'Please connect a Bluetooth or USB printer first.'
    );
}

</script>

@endsection

