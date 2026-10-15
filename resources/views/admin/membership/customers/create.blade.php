@extends('admin.layout')

@section('title', 'Assign Membership | Salon Management')

@section('page-title', 'Assign Membership')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please fix the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        MAIN CARD
    ========================================================== --}}
    <div class="card shadow-sm border-0">


        {{-- =====================================================
            HEADER
        ====================================================== --}}
        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="mb-1 fw-semibold">

                        <i class="bi bi-person-plus me-2"></i>

                        Assign Membership

                    </h5>

                    <small class="text-muted">

                        Assign an active membership plan to a customer.

                    </small>

                </div>


                <a href="{{ route('admin.membership.customers.index') }}"
                   class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- =====================================================
            FORM
        ====================================================== --}}
        <form method="POST"
              action="{{ route('admin.membership.customers.store') }}">

            @csrf

            {{-- Membership status --}}
            <input type="hidden"
                   name="status"
                   value="active">


            <div class="card-body">

                <div class="row g-4">


                    {{-- =================================================
                        CUSTOMER
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="customer_id"
                               class="form-label fw-semibold">

                            Customer

                            <span class="text-danger">*</span>

                        </label>


                        <select name="customer_id"
                                id="customer_id"
                                class="form-select @error('customer_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Customer --
                            </option>


                            @forelse($customers as $customer)

                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }}

                                    @if(!empty($customer->phone))

                                        - {{ $customer->phone }}

                                    @endif

                                </option>

                            @empty

                                <option value="" disabled>

                                    No customers available

                                </option>

                            @endforelse

                        </select>


                        @error('customer_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- =================================================
                        MEMBERSHIP PLAN
                    ================================================== --}}
                    <div class="col-md-6">

                        <label for="membership_plan_id"
                               class="form-label fw-semibold">

                            Membership Plan

                            <span class="text-danger">*</span>

                        </label>


                        <select name="membership_plan_id"
                                id="membership_plan_id"
                                class="form-select @error('membership_plan_id') is-invalid @enderror"
                                required>

                            <option value="">
                                -- Select Membership Plan --
                            </option>


                            @forelse($membershipPlans as $plan)

                                <option value="{{ $plan->id }}"

                                        {{-- Membership price --}}
                                        data-price="{{ $plan->price }}"

                                        {{-- Discount type --}}
                                        data-discount-type="{{ $plan->discount_type }}"

                                        {{-- Discount value --}}
                                        data-discount="{{ $plan->discount_value }}"

                                        {{-- Validity --}}
                                        data-validity="{{ $plan->validity_days }}"

                                    {{ old('membership_plan_id') == $plan->id ? 'selected' : '' }}>

                                    {{ $plan->name }}

                                    -

                                    ₹{{ number_format((float) $plan->price, 2) }}

                                </option>

                            @empty

                                <option value="" disabled>

                                    No membership plans available

                                </option>

                            @endforelse

                        </select>


                        @error('membership_plan_id')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- =================================================
                        SELECTED PLAN INFORMATION
                    ================================================== --}}
                    <div class="col-12">

                        <div id="planInfo"
                             class="alert alert-light border d-none">

                            <div class="row g-3">


                                {{-- PRICE --}}
                                <div class="col-md-4">

                                    <small class="text-muted d-block">

                                        Membership Price

                                    </small>

                                    <strong id="planPrice">

                                        ₹0.00

                                    </strong>

                                </div>


                                {{-- DISCOUNT --}}
                                <div class="col-md-4">

                                    <small class="text-muted d-block">

                                        Discount

                                    </small>

                                    <strong id="planDiscount">

                                        0%

                                    </strong>

                                </div>


                                {{-- VALIDITY --}}
                                <div class="col-md-4">

                                    <small class="text-muted d-block">

                                        Validity

                                    </small>

                                    <strong id="planValidity">

                                        0 days

                                    </strong>

                                </div>

                            </div>


                            {{-- =================================================
                                DISCOUNT AMOUNT + FINAL AMOUNT
                            ================================================== --}}
                            <hr class="my-3">


                            <div class="row g-3">

                                <div class="col-md-6">

                                    <small class="text-muted d-block">

                                        Discount Amount

                                    </small>

                                    <strong id="planDiscountAmount">

                                        ₹0.00

                                    </strong>

                                </div>


                                <div class="col-md-6">

                                    <small class="text-muted d-block">

                                        Final Membership Amount

                                    </small>

                                    <strong id="planFinalAmount"
                                            class="text-success">

                                        ₹0.00

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =================================================
                        START DATE
                    ================================================== --}}
                    <div class="col-md-4">

                        <label for="start_date"
                               class="form-label fw-semibold">

                            Start Date

                            <span class="text-danger">*</span>

                        </label>


                        <input type="date"
                               name="start_date"
                               id="start_date"
                               value="{{ old('start_date', now()->format('Y-m-d')) }}"
                               class="form-control @error('start_date') is-invalid @enderror"
                               required>


                        @error('start_date')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- =================================================
                        PAID AMOUNT
                    ================================================== --}}
                    <div class="col-md-4">

                        <label for="paid_amount"
                               class="form-label fw-semibold">

                            Paid Amount

                            <span class="text-danger">*</span>

                        </label>


                        <input type="number"
                               name="paid_amount"
                               id="paid_amount"
                               min="0"
                               step="0.01"
                               value="{{ old('paid_amount', 0) }}"
                               class="form-control @error('paid_amount') is-invalid @enderror"
                               placeholder="0.00"
                               required>


                        @error('paid_amount')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- =================================================
                        PAYMENT METHOD
                    ================================================== --}}
                    <div class="col-md-4">

                        <label for="payment_method"
                               class="form-label fw-semibold">

                            Payment Method

                        </label>


                        <select name="payment_method"
                                id="payment_method"
                                class="form-select @error('payment_method') is-invalid @enderror">

                            <option value="cash"
                                {{ old('payment_method', 'cash') == 'cash' ? 'selected' : '' }}>

                                Cash

                            </option>


                            <option value="upi"
                                {{ old('payment_method') == 'upi' ? 'selected' : '' }}>

                                UPI

                            </option>


                            <option value="card"
                                {{ old('payment_method') == 'card' ? 'selected' : '' }}>

                                Card

                            </option>


                            <option value="bank_transfer"
                                {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>

                                Bank Transfer

                            </option>


                            <option value="online"
                                {{ old('payment_method') == 'online' ? 'selected' : '' }}>

                                Online

                            </option>

                        </select>


                        @error('payment_method')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>



                    {{-- =================================================
                        NOTES
                    ================================================== --}}
                    <div class="col-12">

                        <label for="notes"
                               class="form-label fw-semibold">

                            Notes

                        </label>


                        <textarea name="notes"
                                  id="notes"
                                  rows="3"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Optional notes">{{ old('notes') }}</textarea>


                        @error('notes')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>

            </div>


            {{-- =====================================================
                FOOTER
            ====================================================== --}}
            <div class="card-footer bg-white border-top">

                <div class="d-flex justify-content-end gap-2">


                    <a href="{{ route('admin.membership.customers.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancel

                    </a>


                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>

                        Assign Membership

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================
    PLAN INFORMATION SCRIPT
========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const planSelect =
        document.getElementById('membership_plan_id');

    const planInfo =
        document.getElementById('planInfo');

    const planPrice =
        document.getElementById('planPrice');

    const planDiscount =
        document.getElementById('planDiscount');

    const planValidity =
        document.getElementById('planValidity');

    const planDiscountAmount =
        document.getElementById('planDiscountAmount');

    const planFinalAmount =
        document.getElementById('planFinalAmount');


    /* =========================================================
       CURRENCY FORMAT
    ========================================================== */

    function formatCurrency(amount) {

        return '₹' + Number(amount).toLocaleString('en-IN', {

            minimumFractionDigits: 2,

            maximumFractionDigits: 2

        });

    }


    /* =========================================================
       UPDATE PLAN INFORMATION
    ========================================================== */

    function updatePlanInfo() {

        const selectedOption =
            planSelect.options[
                planSelect.selectedIndex
            ];


        /* =====================================================
           NO PLAN SELECTED
        ====================================================== */

        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            planInfo.classList.add('d-none');

            planPrice.textContent =
                '₹0.00';

            planDiscount.textContent =
                '0%';

            planValidity.textContent =
                '0 days';

            planDiscountAmount.textContent =
                '₹0.00';

            planFinalAmount.textContent =
                '₹0.00';

            return;

        }


        /* =====================================================
           GET PLAN DATA
        ====================================================== */

        const price =
            parseFloat(
                selectedOption.dataset.price || 0
            );


        const discountType =
            selectedOption.dataset.discountType || 'none';


        const discount =
            parseFloat(
                selectedOption.dataset.discount || 0
            );


        const validity =
            parseInt(
                selectedOption.dataset.validity || 0
            );


        /* =====================================================
           CALCULATE DISCOUNT AMOUNT
        ====================================================== */

        let discountAmount = 0;


        if (discountType === 'percentage') {

            discountAmount =
                (price * discount) / 100;


            discountAmount =
                Math.min(
                    discountAmount,
                    price
                );


        } else if (discountType === 'fixed') {

            discountAmount =
                Math.min(
                    discount,
                    price
                );

        }


        discountAmount =
            Math.max(
                0,
                discountAmount
            );


        /* =====================================================
           FINAL AMOUNT
        ====================================================== */

        const finalAmount =
            Math.max(
                0,
                price - discountAmount
            );


        /* =====================================================
           DISPLAY PRICE
        ====================================================== */

        planPrice.textContent =
            formatCurrency(price);


        /* =====================================================
           DISPLAY DISCOUNT
        ====================================================== */

        if (discountType === 'percentage') {

            planDiscount.textContent =
                discount.toFixed(2) + '%';

        } else if (discountType === 'fixed') {

            planDiscount.textContent =
                formatCurrency(discount);

        } else {

            planDiscount.textContent =
                '0%';

        }


        /* =====================================================
           DISPLAY VALIDITY
        ====================================================== */

        planValidity.textContent =
            validity + ' days';


        /* =====================================================
           DISPLAY DISCOUNT AMOUNT
        ====================================================== */

        planDiscountAmount.textContent =
            formatCurrency(discountAmount);


        /* =====================================================
           DISPLAY FINAL AMOUNT
        ====================================================== */

        planFinalAmount.textContent =
            formatCurrency(finalAmount);


        /* =====================================================
           SHOW PLAN INFORMATION
        ====================================================== */

        planInfo.classList.remove('d-none');

    }


    /* =========================================================
       PLAN CHANGE EVENT
    ========================================================== */

    planSelect.addEventListener(
        'change',
        updatePlanInfo
    );


    /* =========================================================
       INITIAL LOAD
    ========================================================== */

    updatePlanInfo();

});

</script>

@endsection