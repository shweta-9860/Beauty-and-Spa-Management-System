@extends('admin.layout')

@section('title', 'Create Membership Plan | Salon Management')

@section('page-title', 'Create Membership Plan')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">
                <i class="bi bi-person-vcard me-2"></i>
                Create Membership Plan
            </h4>

            <p class="text-muted mb-0">
                Create a new membership plan for your salon.
            </p>
        </div>

        <a href="{{ route('admin.membership.plans.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Plans
        </a>
    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <strong>
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please fix the following errors:
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


    <div class="row g-4">

        {{-- =====================================================
             MAIN FORM
        ====================================================== --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Membership Plan Details
                    </h5>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route('admin.membership.plans.store') }}">

                        @csrf


                        {{-- =================================================
                             PLAN NAME
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Plan Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Example: Gold Membership"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             PLAN CODE
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Plan Code

                                <small class="text-muted">
                                    (Optional)
                                </small>
                            </label>

                            <input type="text"
                                   name="code"
                                   value="{{ old('code') }}"
                                   class="form-control @error('code') is-invalid @enderror"
                                   placeholder="Example: GOLD001">

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             DESCRIPTION
                        ================================================== --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Describe this membership plan...">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             BILLING CYCLE + PRICE
                        ================================================== --}}
                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Billing Cycle
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="billing_cycle"
                                        id="billing_cycle"
                                        class="form-select @error('billing_cycle') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Billing Cycle
                                    </option>

                                    <option value="monthly"
                                        {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>
                                        Monthly
                                    </option>

                                    <option value="quarterly"
                                        {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>
                                        Quarterly
                                    </option>

                                    <option value="yearly"
                                        {{ old('billing_cycle') === 'yearly' ? 'selected' : '' }}>
                                        Yearly
                                    </option>

                                </select>

                                @error('billing_cycle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Membership Price
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>

                                    <input type="number"
                                           name="price"
                                           value="{{ old('price', 0) }}"
                                           min="0"
                                           step="0.01"
                                           class="form-control @error('price') is-invalid @enderror"
                                           required>

                                </div>

                                @error('price')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- =================================================
                             VALIDITY
                        ================================================== --}}
                        <div class="mb-3 mt-3">

                            <label class="form-label fw-semibold">
                                Validity Days
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="validity_days"
                                   value="{{ old('validity_days', 30) }}"
                                   min="1"
                                   class="form-control @error('validity_days') is-invalid @enderror"
                                   required>

                            <small class="text-muted">
                                Number of days the membership remains valid after activation.
                            </small>

                            @error('validity_days')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =================================================
                             DISCOUNT
                        ================================================== --}}
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Discount Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="discount_type"
                                        id="discount_type"
                                        class="form-select @error('discount_type') is-invalid @enderror"
                                        required>

                                    <option value="none"
                                        {{ old('discount_type', 'none') === 'none' ? 'selected' : '' }}>
                                        No Discount
                                    </option>

                                    <option value="percentage"
                                        {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount (₹)
                                    </option>

                                </select>

                                @error('discount_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Discount Value
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="discount_value"
                                           value="{{ old('discount_value', 0) }}"
                                           min="0"
                                           step="0.01"
                                           id="discount_value"
                                           class="form-control @error('discount_value') is-invalid @enderror">

                                    <span class="input-group-text"
                                          id="discount-symbol">
                                        —
                                    </span>

                                </div>

                                @error('discount_value')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>


                        {{-- =================================================
                             DISCOUNT SUMMARY
                        ================================================== --}}
                        <div class="mt-3">

                            <div class="alert alert-light border mb-0">

                                <div class="d-flex justify-content-between">

                                    <span>
                                        <i class="bi bi-calculator me-1"></i>
                                        Discount Amount
                                    </span>

                                    <strong id="discount-amount-preview">
                                        ₹0.00
                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mt-2">

                                    <span>
                                        Final Membership Amount
                                    </span>

                                    <strong class="text-success"
                                            id="final-amount-preview">
                                        ₹0.00
                                    </strong>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             STATUS
                        ================================================== --}}
                        <div class="mt-4 mb-4">

                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       id="status"
                                       class="form-check-input"
                                       {{ old('status', true) ? 'checked' : '' }}>

                                <label class="form-check-label fw-semibold"
                                       for="status">
                                    Active Membership Plan
                                </label>

                            </div>

                            <small class="text-muted">
                                Only active plans should be available for customer membership purchase.
                            </small>

                        </div>


                        {{-- =================================================
                             BUTTONS
                        ================================================== --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>
                                Save Membership Plan

                            </button>


                            <a href="{{ route('admin.membership.plans.index') }}"
                               class="btn btn-outline-secondary">

                                Cancel

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- =====================================================
             PREVIEW
        ====================================================== --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        <i class="bi bi-eye me-2"></i>
                        Plan Preview
                    </h5>

                </div>


                <div class="card-body">

                    <div class="text-center">

                        <div class="mb-3">

                            <i class="bi bi-person-vcard"
                               style="font-size:55px; opacity:.45;">
                            </i>

                        </div>


                        <h5 id="preview-name">
                            Membership Plan
                        </h5>


                        <div class="display-6 fw-bold mt-3"
                             id="preview-price">
                            ₹0.00
                        </div>


                        <div class="text-muted"
                             id="preview-cycle">
                            Monthly
                        </div>

                    </div>


                    <hr>


                    <div class="small">

                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Validity
                            </span>

                            <strong id="preview-validity">
                                30 days
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span class="text-muted">
                                Discount
                            </span>

                            <strong id="preview-discount">
                                No Discount
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Final Amount
                            </span>

                            <strong class="text-success"
                                    id="preview-final">
                                ₹0.00
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 INFO
            ====================================================== --}}
            <div class="alert alert-info mt-4">

                <i class="bi bi-info-circle me-2"></i>

                <strong>Tip:</strong>

                After creating the plan, you can add membership
                benefits such as discounts, free services and
                priority booking.

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT
========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const nameInput =
        document.querySelector('input[name="name"]');

    const priceInput =
        document.querySelector('input[name="price"]');

    const cycleInput =
        document.getElementById('billing_cycle');

    const validityInput =
        document.querySelector('input[name="validity_days"]');

    const discountType =
        document.getElementById('discount_type');

    const discountValue =
        document.getElementById('discount_value');

    const discountSymbol =
        document.getElementById('discount-symbol');

    const previewName =
        document.getElementById('preview-name');

    const previewPrice =
        document.getElementById('preview-price');

    const previewCycle =
        document.getElementById('preview-cycle');

    const previewValidity =
        document.getElementById('preview-validity');

    const previewDiscount =
        document.getElementById('preview-discount');

    const previewFinal =
        document.getElementById('preview-final');

    const discountAmountPreview =
        document.getElementById('discount-amount-preview');

    const finalAmountPreview =
        document.getElementById('final-amount-preview');


    function formatCurrency(amount) {

        return '₹' + Number(amount).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    }


    function updatePreview() {

        const name =
            nameInput.value.trim();

        const price =
            parseFloat(priceInput.value || 0);

        const cycle =
            cycleInput.value;

        const validity =
            validityInput.value || 0;

        const type =
            discountType.value;

        const value =
            parseFloat(discountValue.value || 0);


        /* =====================================================
           BASIC PREVIEW
        ====================================================== */

        previewName.innerText =
            name || 'Membership Plan';


        previewPrice.innerText =
            formatCurrency(price);


        if (cycle === 'quarterly') {

            previewCycle.innerText =
                'Quarterly';

        } else if (cycle === 'yearly') {

            previewCycle.innerText =
                'Yearly';

        } else {

            previewCycle.innerText =
                'Monthly';

        }


        previewValidity.innerText =
            validity + ' days';


        /* =====================================================
           DISCOUNT CALCULATION
        ====================================================== */

        let discountAmount = 0;


        if (type === 'percentage') {

            discountAmount =
                (price * value) / 100;

            discountAmount =
                Math.min(discountAmount, price);

            previewDiscount.innerText =
                value.toFixed(2) + '%';

            discountSymbol.innerText =
                '%';


        } else if (type === 'fixed') {

            discountAmount =
                Math.min(value, price);

            previewDiscount.innerText =
                formatCurrency(value);

            discountSymbol.innerText =
                '₹';


        } else {

            discountAmount =
                0;

            previewDiscount.innerText =
                'No Discount';

            discountSymbol.innerText =
                '—';

        }


        discountAmount =
            Math.max(0, discountAmount);


        const finalAmount =
            Math.max(0, price - discountAmount);


        /* =====================================================
           AMOUNT SUMMARY
        ====================================================== */

        discountAmountPreview.innerText =
            formatCurrency(discountAmount);

        finalAmountPreview.innerText =
            formatCurrency(finalAmount);

        previewFinal.innerText =
            formatCurrency(finalAmount);

    }


    /* =========================================================
       EVENTS
    ========================================================== */

    nameInput.addEventListener(
        'input',
        updatePreview
    );


    priceInput.addEventListener(
        'input',
        updatePreview
    );


    cycleInput.addEventListener(
        'change',
        updatePreview
    );


    validityInput.addEventListener(
        'input',
        updatePreview
    );


    discountType.addEventListener(
        'change',
        updatePreview
    );


    discountValue.addEventListener(
        'input',
        updatePreview
    );


    /* =========================================================
       INITIAL PREVIEW
    ========================================================== */

    updatePreview();

});

</script>

@endsection