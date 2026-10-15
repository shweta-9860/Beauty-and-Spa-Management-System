
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
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-plus-circle me-2"></i>
                Create Membership Plan
            </h4>

            <p class="text-muted mb-0">
                Create a recurring membership plan for your salon customers.
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
        FORM
    ========================================================== --}}
    <form action="{{ route('admin.membership.plans.store') }}"
          method="POST">

        @csrf

        <div class="row g-4">

            {{-- =================================================
                LEFT SIDE
            ================================================== --}}
            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i class="bi bi-card-checklist me-2"></i>

                            Plan Information

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- PLAN NAME --}}
                            <div class="col-md-8">

                                <label class="form-label fw-semibold">

                                    Plan Name
                                    <span class="text-danger">*</span>

                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}"
                                       placeholder="Example: Gold Membership"
                                       required>

                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- PLAN CODE --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Plan Code
                                </label>

                                <input type="text"
                                       name="code"
                                       class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}"
                                       placeholder="Example: GOLD">

                                @error('code')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- DESCRIPTION --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="4"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Describe the membership plan benefits...">{{ old('description') }}</textarea>

                                @error('description')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    BILLING DETAILS
                ================================================== --}}
                <div class="card shadow-sm border-0 mt-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i class="bi bi-currency-rupee me-2"></i>

                            Billing & Validity

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- BILLING CYCLE --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Billing Cycle
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="billing_cycle"
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


                            {{-- PRICE --}}
                            <div class="col-md-4">

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
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('price') is-invalid @enderror"
                                           value="{{ old('price', 0) }}"
                                           placeholder="999"
                                           required>

                                </div>

                                @error('price')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- VALIDITY --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">

                                    Validity
                                    <span class="text-danger">*</span>

                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="validity_days"
                                           min="1"
                                           class="form-control @error('validity_days') is-invalid @enderror"
                                           value="{{ old('validity_days', 30) }}"
                                           required>

                                    <span class="input-group-text">
                                        Days
                                    </span>

                                </div>

                                @error('validity_days')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    DISCOUNT
                ================================================== --}}
                <div class="card shadow-sm border-0 mt-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i class="bi bi-percent me-2"></i>

                            Membership Discount

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">

                            {{-- DISCOUNT TYPE --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Discount Type
                                    <span class="text-danger">*</span>

                                </label>

                                <select name="discount_type"
                                        id="discountType"
                                        class="form-select @error('discount_type') is-invalid @enderror"
                                        required>

                                    <option value="none"
                                        {{ old('discount_type', 'percentage') === 'none' ? 'selected' : '' }}>
                                        No Discount
                                    </option>

                                    <option value="percentage"
                                        {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>
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


                            {{-- DISCOUNT VALUE --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Discount Value
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text"
                                          id="discountSymbol">
                                        %
                                    </span>

                                    <input type="number"
                                           name="discount_value"
                                           id="discountValue"
                                           step="0.01"
                                           min="0"
                                           class="form-control @error('discount_value') is-invalid @enderror"
                                           value="{{ old('discount_value', 0) }}"
                                           placeholder="10">

                                </div>

                                <small class="text-muted"
                                       id="discountHelp">
                                    Enter percentage discount.
                                </small>

                                @error('discount_value')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                RIGHT SIDE
            ================================================== --}}
            <div class="col-lg-4">

                <div class="card shadow-sm border-0">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i class="bi bi-toggle-on me-2"></i>

                            Plan Status

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="form-check form-switch">

                            <input type="checkbox"
                                   name="status"
                                   value="1"
                                   class="form-check-input"
                                   id="status"
                                   {{ old('status', true) ? 'checked' : '' }}>

                            <label class="form-check-label fw-semibold"
                                   for="status">

                                Active Membership Plan

                            </label>

                        </div>

                        <div class="text-muted small mt-2">

                            Active plans can be assigned to customers.

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PLAN PREVIEW
                ================================================== --}}
                <div class="card shadow-sm border-0 mt-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0 fw-semibold">

                            <i class="bi bi-eye me-2"></i>

                            Plan Preview

                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="text-center">

                            <div class="mb-3">

                                <i class="bi bi-gem"
                                   style="font-size: 45px;">
                                </i>

                            </div>

                            <h5 id="previewName"
                                class="fw-bold">

                                Membership Plan

                            </h5>

                            <div class="text-muted"
                                 id="previewCycle">

                                Monthly

                            </div>

                            <div class="mt-3">

                                <span class="fs-3 fw-bold">
                                    ₹<span id="previewPrice">0.00</span>
                                </span>

                            </div>

                            <div class="mt-2">

                                <span class="badge bg-success"
                                      id="previewDiscount">

                                    0% Discount

                                </span>

                            </div>

                            <div class="text-muted small mt-2">

                                Valid for
                                <span id="previewValidity">30</span>
                                days

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
            FORM ACTIONS
        ========================================================== --}}
        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('admin.membership.plans.index') }}"
               class="btn btn-outline-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg me-1"></i>

                Save Membership Plan

            </button>

        </div>

    </form>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const discountType =
        document.getElementById('discountType');

    const discountValue =
        document.getElementById('discountValue');

    const discountSymbol =
        document.getElementById('discountSymbol');

    const discountHelp =
        document.getElementById('discountHelp');

    const nameInput =
        document.querySelector('input[name="name"]');

    const priceInput =
        document.querySelector('input[name="price"]');

    const validityInput =
        document.querySelector('input[name="validity_days"]');

    const cycleInput =
        document.querySelector('select[name="billing_cycle"]');


    function updateDiscountUI() {

        const type = discountType.value;

        if (type === 'percentage') {

            discountSymbol.textContent = '%';

            discountHelp.textContent =
                'Enter percentage discount.';

            discountValue.max = 100;

        } else if (type === 'fixed') {

            discountSymbol.textContent = '₹';

            discountHelp.textContent =
                'Enter fixed discount amount.';

            discountValue.removeAttribute('max');

        } else {

            discountSymbol.textContent = '₹';

            discountHelp.textContent =
                'No discount will be applied.';

            discountValue.value = 0;

            discountValue.removeAttribute('max');

        }

        updatePreview();
    }


    function updatePreview() {

        const name =
            nameInput.value.trim() || 'Membership Plan';

        const price =
            parseFloat(priceInput.value || 0);

        const validity =
            validityInput.value || 30;

        const cycle =
            cycleInput.value || 'monthly';

        const discount =
            parseFloat(discountValue.value || 0);

        let cycleText =
            cycle.charAt(0).toUpperCase() +
            cycle.slice(1);

        document.getElementById('previewName')
            .textContent = name;

        document.getElementById('previewPrice')
            .textContent = price.toFixed(2);

        document.getElementById('previewValidity')
            .textContent = validity;

        document.getElementById('previewCycle')
            .textContent = cycleText;


        let discountText = 'No Discount';

        if (discountType.value === 'percentage') {

            discountText =
                discount.toFixed(2) + '% Discount';

        } else if (discountType.value === 'fixed') {

            discountText =
                '₹' + discount.toFixed(2) + ' Discount';

        }

        document.getElementById('previewDiscount')
            .textContent = discountText;
    }


    discountType.addEventListener(
        'change',
        updateDiscountUI
    );

    discountValue.addEventListener(
        'input',
        updatePreview
    );

    nameInput.addEventListener(
        'input',
        updatePreview
    );

    priceInput.addEventListener(
        'input',
        updatePreview
    );

    validityInput.addEventListener(
        'input',
        updatePreview
    );

    cycleInput.addEventListener(
        'change',
        updatePreview
    );


    updateDiscountUI();

});

</script>

@endsection

