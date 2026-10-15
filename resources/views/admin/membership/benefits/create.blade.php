@extends('admin.layout')

@section('title', 'Add Membership Benefit | Salon Management')

@section('page-title', 'Add Membership Benefit')

@section('content')

<div class="container-fluid px-0">

    {{-- =====================================================
        HEADER
    ====================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-gift me-2"></i>
                Add Membership Benefit
            </h4>

            <p class="text-muted mb-0">
                Add a benefit to
                <strong>{{ $membershipPlan->name }}</strong>
            </p>
        </div>

        <a href="{{ route(
            'admin.membership.benefits.index',
            $membershipPlan
        ) }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Benefits

        </a>

    </div>


    {{-- =====================================================
        VALIDATION ERRORS
    ====================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">

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

        {{-- =================================================
            MAIN FORM
        ================================================== --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="bi bi-plus-circle me-2"></i>

                        Benefit Details

                    </h5>

                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.membership.benefits.store',
                              $membershipPlan
                          ) }}">

                        @csrf


                        {{-- =================================
                            BENEFIT TYPE
                        ================================== --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Benefit Type
                                <span class="text-danger">*</span>

                            </label>

                            <select name="benefit_type"
                                    id="benefit_type"
                                    class="form-select @error('benefit_type') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Benefit Type
                                </option>

                                <option value="discount"
                                    {{ old('benefit_type') === 'discount' ? 'selected' : '' }}>
                                    Discount
                                </option>

                                <option value="free_service"
                                    {{ old('benefit_type') === 'free_service' ? 'selected' : '' }}>
                                    Free Service
                                </option>

                                <option value="priority_booking"
                                    {{ old('benefit_type') === 'priority_booking' ? 'selected' : '' }}>
                                    Priority Booking
                                </option>

                                <option value="custom"
                                    {{ old('benefit_type') === 'custom' ? 'selected' : '' }}>
                                    Custom Benefit
                                </option>

                            </select>

                            @error('benefit_type')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================
                            SERVICE
                        ================================== --}}
                        <div class="mb-3"
                             id="service-wrapper">

                            <label class="form-label fw-semibold">

                                Service

                                <small class="text-muted">
                                    (Optional)
                                </small>

                            </label>

                            <select name="service_id"
                                    id="service_id"
                                    class="form-select @error('service_id') is-invalid @enderror">

                                <option value="">
                                    Select Service
                                </option>

                                @foreach($services as $service)

                                    <option value="{{ $service->id }}"
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>

                                        {{ $service->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('service_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Select a service if this benefit applies to a specific service.
                            </small>

                        </div>


                        {{-- =================================
                            DISCOUNT SECTION
                        ================================== --}}
                        <div id="discount-wrapper">

                            <div class="row g-3">

                                {{-- Discount Type --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Discount Type
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select name="discount_type"
                                            id="discount_type"
                                            class="form-select @error('discount_type') is-invalid @enderror">

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


                                {{-- Discount Value --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Discount Value

                                    </label>

                                    <div class="input-group">

                                        <input type="number"
                                               name="discount_value"
                                               id="discount_value"
                                               value="{{ old('discount_value', 0) }}"
                                               min="0"
                                               step="0.01"
                                               class="form-control @error('discount_value') is-invalid @enderror"
                                               placeholder="0">

                                        <span class="input-group-text"
                                              id="discount-symbol">

                                            %

                                        </span>

                                    </div>

                                    @error('discount_value')

                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                            </div>

                        </div>


                        {{-- =================================
                            FREE USES
                        ================================== --}}
                        <div class="mb-3 mt-3"
                             id="free-uses-wrapper">

                            <label class="form-label fw-semibold">

                                Free Uses

                            </label>

                            <input type="number"
                                   name="free_uses"
                                   value="{{ old('free_uses', 0) }}"
                                   min="0"
                                   class="form-control @error('free_uses') is-invalid @enderror"
                                   placeholder="Example: 1">

                            @error('free_uses')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">

                                Number of times the customer can use this free service.

                            </small>

                        </div>


                        {{-- =================================
                            TITLE
                        ================================== --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Benefit Title

                                <small class="text-muted">
                                    (Optional)
                                </small>

                            </label>

                            <input type="text"
                                   name="title"
                                   value="{{ old('title') }}"
                                   maxlength="255"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Example: 10% Off on Hair Services">

                            @error('title')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================
                            DESCRIPTION
                        ================================== --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Description

                                <small class="text-muted">
                                    (Optional)
                                </small>

                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Describe this membership benefit...">{{ old('description') }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- =================================
                            STATUS
                        ================================== --}}
                        <div class="mb-4">

                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       class="form-check-input"
                                       id="status"
                                       {{ old('status', true) ? 'checked' : '' }}>

                                <label class="form-check-label fw-semibold"
                                       for="status">

                                    Active Benefit

                                </label>

                            </div>

                            <small class="text-muted">
                                Inactive benefits will not be applied.
                            </small>

                        </div>


                        {{-- =================================
                            BUTTONS
                        ================================== --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>

                                Save Benefit

                            </button>


                            <a href="{{ route(
                                'admin.membership.benefits.index',
                                $membershipPlan
                            ) }}"
                               class="btn btn-outline-secondary">

                                Cancel

                            </a>

                        </div>


                    </form>

                </div>

            </div>

        </div>


        {{-- =================================================
            PLAN INFO
        ================================================== --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">

                        <i class="bi bi-person-vcard me-2"></i>

                        Membership Plan

                    </h5>

                </div>


                <div class="card-body">

                    <h5 class="mb-2">
                        {{ $membershipPlan->name }}
                    </h5>

                    @if($membershipPlan->code)

                        <div class="text-muted mb-3">

                            Code:
                            <strong>
                                {{ $membershipPlan->code }}
                            </strong>

                        </div>

                    @endif


                    <div class="mb-2">

                        <span class="text-muted">
                            Billing Cycle:
                        </span>

                        <strong>
                            {{ ucfirst($membershipPlan->billing_cycle) }}
                        </strong>

                    </div>


                    <div class="mb-2">

                        <span class="text-muted">
                            Price:
                        </span>

                        <strong>
                            ₹{{ number_format($membershipPlan->price, 2) }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <span class="text-muted">
                            Validity:
                        </span>

                        <strong>
                            {{ $membershipPlan->validity_days }} days
                        </strong>

                    </div>


                    <hr>


                    <div class="small text-muted">

                        <i class="bi bi-info-circle me-1"></i>

                        Benefits can provide discounts,
                        free services, priority booking,
                        or custom membership privileges.

                    </div>

                </div>

            </div>


            {{-- Preview --}}
            <div class="card shadow-sm border-0 mt-4">

                <div class="card-header bg-white py-3">

                    <h6 class="mb-0">

                        <i class="bi bi-eye me-2"></i>

                        Benefit Preview

                    </h6>

                </div>


                <div class="card-body">

                    <div id="benefit-preview">

                        <div class="text-muted small">
                            Select a benefit type to see the preview.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const benefitType = document.getElementById('benefit_type');

    const serviceWrapper =
        document.getElementById('service-wrapper');

    const discountWrapper =
        document.getElementById('discount-wrapper');

    const freeUsesWrapper =
        document.getElementById('free-uses-wrapper');

    const discountType =
        document.getElementById('discount_type');

    const discountSymbol =
        document.getElementById('discount-symbol');

    const preview =
        document.getElementById('benefit-preview');


    function updateDiscountSymbol() {

        if (discountType.value === 'percentage') {

            discountSymbol.innerText = '%';

        } else if (discountType.value === 'fixed') {

            discountSymbol.innerText = '₹';

        } else {

            discountSymbol.innerText = '—';

        }

    }


    function updateBenefitFields() {

        const type = benefitType.value;


        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        serviceWrapper.style.display = 'block';

        discountWrapper.style.display = 'block';

        freeUsesWrapper.style.display = 'block';


        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        if (type === 'discount') {

            serviceWrapper.style.display = 'block';

            discountWrapper.style.display = 'block';

            freeUsesWrapper.style.display = 'none';

            preview.innerHTML = `
                <div class="alert alert-success mb-0">

                    <i class="bi bi-percent me-2"></i>

                    <strong>Discount Benefit</strong>

                    <div class="small mt-1">
                        Member will receive a discount
                        on the selected service or applicable services.
                    </div>

                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Free Service
        |--------------------------------------------------------------------------
        */

        else if (type === 'free_service') {

            serviceWrapper.style.display = 'block';

            discountWrapper.style.display = 'none';

            freeUsesWrapper.style.display = 'block';

            preview.innerHTML = `
                <div class="alert alert-info mb-0">

                    <i class="bi bi-gift me-2"></i>

                    <strong>Free Service</strong>

                    <div class="small mt-1">
                        Customer can redeem the selected service
                        for free according to the allowed uses.
                    </div>

                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Priority Booking
        |--------------------------------------------------------------------------
        */

        else if (type === 'priority_booking') {

            serviceWrapper.style.display = 'none';

            discountWrapper.style.display = 'none';

            freeUsesWrapper.style.display = 'none';

            preview.innerHTML = `
                <div class="alert alert-warning mb-0">

                    <i class="bi bi-calendar-check me-2"></i>

                    <strong>Priority Booking</strong>

                    <div class="small mt-1">
                        Membership customers receive priority
                        when booking appointments.
                    </div>

                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Custom
        |--------------------------------------------------------------------------
        */

        else if (type === 'custom') {

            serviceWrapper.style.display = 'none';

            discountWrapper.style.display = 'none';

            freeUsesWrapper.style.display = 'none';

            preview.innerHTML = `
                <div class="alert alert-secondary mb-0">

                    <i class="bi bi-star me-2"></i>

                    <strong>Custom Benefit</strong>

                    <div class="small mt-1">
                        Define your own membership benefit
                        using the title and description fields.
                    </div>

                </div>
            `;

        }


        /*
        |--------------------------------------------------------------------------
        | Empty
        |--------------------------------------------------------------------------
        */

        else {

            preview.innerHTML = `
                <div class="text-muted small">
                    Select a benefit type to see the preview.
                </div>
            `;

        }

        updateDiscountSymbol();
    }


    benefitType.addEventListener(
        'change',
        updateBenefitFields
    );


    discountType.addEventListener(
        'change',
        updateDiscountSymbol
    );


    updateBenefitFields();

});

</script>

@endsection