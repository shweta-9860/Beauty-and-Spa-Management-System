@extends('admin.layout')

@section('title', 'Collect Remaining Payment | Salon Management')
@section('page-title', 'Collect Remaining Payment')

@section('content')

<div class="container-fluid px-0">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">

            <strong>
                <i class="bi bi-exclamation-circle me-2"></i>
                Please correct the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Page Header --}}
    <div class="payment-header mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                <i class="bi bi-credit-card-2-front me-2"></i>
                Collect Remaining Payment
            </h3>

            <p class="text-muted mb-0">
                Collect pending payment for an existing bill.
            </p>
        </div>

        <a href="{{ route('admin.billing.all') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to All Bills

        </a>

    </div>


    <div class="row g-4">


        {{-- ========================================================= --}}
        {{-- LEFT SIDE - BILL DETAILS --}}
        {{-- ========================================================= --}}

        <div class="col-lg-5">

            {{-- Bill Details Card --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-receipt me-2"></i>
                        Bill Details
                    </h5>

                </div>


                <div class="card-body">


                    {{-- Invoice --}}
                    <div class="detail-row">

                        <span>Invoice Number</span>

                        <strong>
                            {{ $bill->invoice_number }}
                        </strong>

                    </div>


                    {{-- Bill Date --}}
                    <div class="detail-row">

                        <span>Bill Date</span>

                        <strong>

                            @if($bill->bill_date)

                                {{ \Carbon\Carbon::parse($bill->bill_date)->format('d M Y') }}

                            @else

                                -

                            @endif

                        </strong>

                    </div>


                    {{-- Customer --}}
                    <div class="detail-row">

                        <span>Customer</span>

                        <strong>
                            {{ $bill->customer->name ?? 'Walk-in Customer' }}
                        </strong>

                    </div>


                    {{-- Phone --}}
                    @if($bill->customer?->phone)

                        <div class="detail-row">

                            <span>Phone</span>

                            <strong>
                                {{ $bill->customer->phone }}
                            </strong>

                        </div>

                    @endif


                    {{-- Branch --}}
                    <div class="detail-row">

                        <span>Branch</span>

                        <strong>
                            {{ $bill->branch->name ?? '-' }}
                        </strong>

                    </div>


                    <hr>


                    {{-- Total Amount --}}
                    <div class="amount-row">

                        <span>Total Amount</span>

                        <strong>
                            ₹{{ number_format((float) $bill->grand_total, 2) }}
                        </strong>

                    </div>


                    {{-- Already Paid --}}
                    <div class="amount-row paid-row">

                        <span>Already Paid</span>

                        <strong>
                            ₹{{ number_format((float) $paidAmount, 2) }}
                        </strong>

                    </div>


                    {{-- Remaining --}}
                    <div class="amount-row pending-row">

                        <span>Remaining Amount</span>

                        <strong>
                            ₹{{ number_format((float) $pendingAmount, 2) }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- PAYMENT HISTORY --}}
            {{-- ===================================================== --}}

            <div class="card border-0 shadow-sm mt-4">

                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-clock-history me-2"></i>

                        Payment History

                    </h5>

                </div>


                <div class="card-body p-0">

                    @if($bill->payments->count() > 0)

                        <div class="table-responsive">

                            <table class="table table-sm align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-3">
                                            #
                                        </th>

                                        <th>
                                            Method
                                        </th>

                                        <th>
                                            Amount
                                        </th>

                                        <th>
                                            Date
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($bill->payments as $index => $payment)

                                        <tr>

                                            <td class="ps-3">
                                                {{ $index + 1 }}
                                            </td>


                                            <td>

                                                @php
                                                    $method = strtolower(
                                                        $payment->payment_method ?? ''
                                                    );
                                                @endphp


                                                <span class="payment-method-badge">

                                                    @if($method === 'cash')

                                                        <i class="bi bi-cash me-1"></i>

                                                    @elseif($method === 'upi')

                                                        <i class="bi bi-phone me-1"></i>

                                                    @elseif($method === 'card')

                                                        <i class="bi bi-credit-card me-1"></i>

                                                    @elseif($method === 'bank_transfer')

                                                        <i class="bi bi-bank me-1"></i>

                                                    @elseif($method === 'online')

                                                        <i class="bi bi-globe me-1"></i>

                                                    @else

                                                        <i class="bi bi-wallet2 me-1"></i>

                                                    @endif


                                                    {{ ucwords(str_replace('_', ' ', $method)) }}

                                                </span>

                                            </td>


                                            <td class="fw-semibold">

                                                ₹{{ number_format((float) $payment->amount, 2) }}

                                            </td>


                                            <td class="text-muted">

                                                @if($payment->created_at)

                                                    {{ $payment->created_at->format('d M Y, h:i A') }}

                                                @else

                                                    -

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="p-4 text-center text-muted">

                            <i class="bi bi-wallet2 fs-2 d-block mb-2"></i>

                            No payment history found.

                        </div>

                    @endif

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- RIGHT SIDE - PAYMENT FORM --}}
        {{-- ========================================================= --}}

        <div class="col-lg-7">

            <div class="card border-0 shadow-sm">


                {{-- Card Header --}}
                <div class="card-header bg-white py-3">

                    <h5 class="fw-bold mb-0">

                        <i class="bi bi-wallet2 me-2"></i>

                        Collect Payment

                    </h5>

                </div>


                {{-- Form --}}
                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.billing.remaining-payment.collect', $bill->id) }}">

                        @csrf


                        {{-- ================================================= --}}
                        {{-- REMAINING AMOUNT BOX --}}
                        {{-- ================================================= --}}

                        <div class="remaining-box mb-4">

                            <div>

                                <small>
                                    Remaining Payment
                                </small>

                                <div class="remaining-value">

                                    ₹{{ number_format((float) $pendingAmount, 2) }}

                                </div>

                            </div>


                            <i class="bi bi-hourglass-split"></i>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT METHOD --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Payment Method

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <select name="payment_method"
                                    id="payment_method"
                                    class="form-select form-select-lg @error('payment_method') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Payment Method
                                </option>


                                <option value="cash"
                                    {{ old('payment_method') === 'cash' ? 'selected' : '' }}>

                                    Cash

                                </option>


                                <option value="upi"
                                    {{ old('payment_method') === 'upi' ? 'selected' : '' }}>

                                    UPI

                                </option>


                                <option value="card"
                                    {{ old('payment_method') === 'card' ? 'selected' : '' }}>

                                    Card

                                </option>


                                <option value="bank_transfer"
                                    {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>

                                    Bank Transfer

                                </option>


                                <option value="online"
                                    {{ old('payment_method') === 'online' ? 'selected' : '' }}>

                                    Online

                                </option>

                            </select>


                            @error('payment_method')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT AMOUNT --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Payment Amount

                                <span class="text-danger">
                                    *
                                </span>

                            </label>


                            <div class="input-group input-group-lg">

                                <span class="input-group-text">
                                    ₹
                                </span>


                                <input type="number"
                                       name="amount"
                                       id="payment_amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount', number_format((float) $pendingAmount, 2, '.', '')) }}"
                                       min="0.01"
                                       max="{{ $pendingAmount }}"
                                       step="0.01"
                                       required>


                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        id="payFullAmountBtn">

                                    Pay Full

                                </button>

                            </div>


                            <div class="form-text">

                                Maximum payment allowed:

                                <strong>
                                    ₹{{ number_format((float) $pendingAmount, 2) }}
                                </strong>

                            </div>


                            @error('amount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- TRANSACTION ID --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Transaction ID

                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>

                            </label>


                            <input type="text"
                                   name="transaction_id"
                                   id="transaction_id"
                                   class="form-control"
                                   value="{{ old('transaction_id') }}"
                                   placeholder="Enter UPI / Card / Bank transaction ID">

                        </div>


                        {{-- ================================================= --}}
                        {{-- NOTES --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Notes

                                <span class="text-muted fw-normal">
                                    (Optional)
                                </span>

                            </label>


                            <textarea name="notes"
                                      id="notes"
                                      class="form-control"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Enter payment notes...">{{ old('notes') }}</textarea>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT SUMMARY --}}
                        {{-- ================================================= --}}

                        <div class="payment-summary mb-4">

                            <div class="summary-line">

                                <span>
                                    Bill Total
                                </span>

                                <strong>
                                    ₹{{ number_format((float) $bill->grand_total, 2) }}
                                </strong>

                            </div>


                            <div class="summary-line">

                                <span>
                                    Already Paid
                                </span>

                                <strong class="text-success">
                                    ₹{{ number_format((float) $paidAmount, 2) }}
                                </strong>

                            </div>


                            <div class="summary-line final-line">

                                <span>
                                    Remaining After Payment
                                </span>

                                <strong id="remainingAfterPayment">

                                    ₹{{ number_format((float) $pendingAmount, 2) }}

                                </strong>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="d-flex justify-content-between align-items-center gap-2">


                            <a href="{{ route('admin.billing.all') }}"
                               class="btn btn-outline-secondary btn-lg">

                                <i class="bi bi-arrow-left me-1"></i>

                                Cancel

                            </a>


                            <button type="submit"
                                    class="btn btn-success btn-lg px-4"
                                    id="collectPaymentBtn">

                                <i class="bi bi-check-circle me-1"></i>

                                Collect Payment

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- ============================================================= --}}
{{-- STYLES --}}
{{-- ============================================================= --}}

<style>

    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }


    .card {
        border-radius: 14px;
        overflow: hidden;
    }


    .card-header {
        border-bottom: 1px solid #eeeeee;
    }


    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 11px 0;
        border-bottom: 1px solid #f1f1f1;
    }


    .detail-row:last-child {
        border-bottom: none;
    }


    .detail-row span {
        color: #777;
        font-size: 14px;
    }


    .detail-row strong {
        text-align: right;
        color: #333;
    }


    .amount-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }


    .amount-row span {
        color: #555;
    }


    .amount-row strong {
        font-size: 17px;
    }


    .paid-row strong {
        color: #198754;
    }


    .pending-row {
        margin-top: 5px;
        padding: 15px;
        border-radius: 10px;
        background: #fff4e5;
    }


    .pending-row span {
        font-weight: 600;
        color: #9a5b00;
    }


    .pending-row strong {
        color: #d97706;
        font-size: 20px;
    }


    .remaining-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 24px;
        border-radius: 14px;
        background: linear-gradient(
            135deg,
            #fff4e5,
            #fff9f0
        );
        border: 1px solid #ffe1b3;
    }


    .remaining-box small {
        display: block;
        color: #8a6200;
        font-weight: 600;
        margin-bottom: 5px;
    }


    .remaining-value {
        font-size: 30px;
        font-weight: 800;
        color: #d97706;
    }


    .remaining-box > i {
        font-size: 40px;
        color: #d97706;
        opacity: 0.8;
    }


    .payment-method-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 6px;
        background: #f5f5f5;
        font-size: 13px;
        font-weight: 600;
        text-transform: capitalize;
    }


    .payment-summary {
        padding: 18px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e9ecef;
    }


    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        color: #555;
    }


    .summary-line strong {
        color: #333;
    }


    .final-line {
        margin-top: 8px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
        font-weight: 700;
        color: #333;
    }


    .final-line strong {
        font-size: 18px;
        color: #d97706;
    }


    .table th {
        font-size: 13px;
        font-weight: 700;
        color: #555;
    }


    .table td {
        font-size: 13px;
    }


    .form-label {
        color: #333;
    }


    @media (max-width: 767px) {

        .payment-header {
            align-items: flex-start;
        }


        .payment-header h3 {
            font-size: 21px;
        }


        .remaining-value {
            font-size: 25px;
        }


        .remaining-box > i {
            font-size: 30px;
        }


        .detail-row,
        .amount-row {
            align-items: flex-start;
        }


        .detail-row strong,
        .amount-row strong {
            max-width: 55%;
            word-break: break-word;
        }

    }

</style>



{{-- ============================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const amountInput = document.getElementById('payment_amount');

    const remainingAfterPayment =
        document.getElementById('remainingAfterPayment');

    const payFullAmountBtn =
        document.getElementById('payFullAmountBtn');

    const collectPaymentBtn =
        document.getElementById('collectPaymentBtn');


    const pendingAmount =
        parseFloat(@json((float) $pendingAmount));


    /*
    |--------------------------------------------------------------------------
    | Update Remaining Amount
    |--------------------------------------------------------------------------
    */

    function updateRemainingAmount() {

        let paymentAmount =
            parseFloat(amountInput.value) || 0;


        if (paymentAmount < 0) {
            paymentAmount = 0;
        }


        if (paymentAmount > pendingAmount) {
            paymentAmount = pendingAmount;
            amountInput.value = pendingAmount.toFixed(2);
        }


        const remaining =
            Math.max(0, pendingAmount - paymentAmount);


        remainingAfterPayment.textContent =
            '₹' + remaining.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });


        if (remaining <= 0) {

            remainingAfterPayment.style.color =
                '#198754';

        } else {

            remainingAfterPayment.style.color =
                '#d97706';
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Payment Amount Change
    |--------------------------------------------------------------------------
    */

    amountInput.addEventListener(
        'input',
        updateRemainingAmount
    );


    /*
    |--------------------------------------------------------------------------
    | Pay Full Amount
    |--------------------------------------------------------------------------
    */

    payFullAmountBtn.addEventListener(
        'click',
        function () {

            amountInput.value =
                pendingAmount.toFixed(2);

            updateRemainingAmount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Prevent Invalid Amount
    |--------------------------------------------------------------------------
    */

    amountInput.addEventListener(
        'change',
        function () {

            let value =
                parseFloat(this.value) || 0;


            if (value > pendingAmount) {

                this.value =
                    pendingAmount.toFixed(2);

            }


            if (value < 0.01 && this.value !== '') {

                this.value = '0.01';

            }


            updateRemainingAmount();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Form Submit Protection
    |--------------------------------------------------------------------------
    */

    const form =
        collectPaymentBtn.closest('form');


    form.addEventListener(
        'submit',
        function (event) {

            const paymentMethod =
                document.getElementById('payment_method').value;


            const amount =
                parseFloat(amountInput.value) || 0;


            if (!paymentMethod) {

                event.preventDefault();

                alert('Please select a payment method.');

                return;

            }


            if (amount <= 0) {

                event.preventDefault();

                alert('Please enter a valid payment amount.');

                return;

            }


            if (amount > pendingAmount) {

                event.preventDefault();

                alert(
                    'Payment amount cannot be greater than the remaining amount of ₹' +
                    pendingAmount.toFixed(2)
                );

                return;

            }


            /*
            | Disable button to prevent double click
            */

            collectPaymentBtn.disabled = true;

            collectPaymentBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>' +
                'Processing...';

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    updateRemainingAmount();

});

</script>

@endsection