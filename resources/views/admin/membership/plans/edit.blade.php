
@extends('admin.layout')

@section('title', 'Edit Membership Plan | Salon Management')

@section('page-title', 'Edit Membership Plan')

@section('content')

<div class="container-fluid px-0">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Membership Plan
            </h4>

            <p class="text-muted mb-0">
                Update membership plan details.
            </p>
        </div>

        <a href="{{ route('admin.membership.plans.index') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Plans
        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

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

        {{-- Form --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-vcard me-2"></i>
                        Membership Plan Details
                    </h5>
                </div>


                <div class="card-body">

                    <form method="POST"
                          action="{{ route(
                              'admin.membership.plans.update',
                              $membershipPlan
                          ) }}">

                        @csrf
                        @method('PUT')


                        {{-- Plan Name --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Plan Name
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $membershipPlan->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Code --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Plan Code
                                <small class="text-muted">(Optional)</small>
                            </label>

                            <input type="text"
                                   name="code"
                                   value="{{ old('code', $membershipPlan->code) }}"
                                   class="form-control @error('code') is-invalid @enderror">

                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Description
                            </label>

                            <textarea name="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $membershipPlan->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Billing + Price --}}
                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Billing Cycle
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="billing_cycle"
                                        class="form-select @error('billing_cycle') is-invalid @enderror"
                                        required>

                                    <option value="monthly"
                                        {{ old('billing_cycle', $membershipPlan->billing_cycle) === 'monthly' ? 'selected' : '' }}>
                                        Monthly
                                    </option>

                                    <option value="quarterly"
                                        {{ old('billing_cycle', $membershipPlan->billing_cycle) === 'quarterly' ? 'selected' : '' }}>
                                        Quarterly
                                    </option>

                                    <option value="yearly"
                                        {{ old('billing_cycle', $membershipPlan->billing_cycle) === 'yearly' ? 'selected' : '' }}>
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

                                    <span class="input-group-text">₹</span>

                                    <input type="number"
                                           name="price"
                                           value="{{ old('price', $membershipPlan->price) }}"
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


                        {{-- Validity --}}
                        <div class="mb-3 mt-3">

                            <label class="form-label fw-semibold">
                                Validity Days
                                <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                   name="validity_days"
                                   value="{{ old('validity_days', $membershipPlan->validity_days) }}"
                                   min="1"
                                   class="form-control @error('validity_days') is-invalid @enderror"
                                   required>

                            <small class="text-muted">
                                Number of days the membership remains valid.
                            </small>

                            @error('validity_days')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Discount --}}
                        <div class="row g-3">

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
                                        {{ old('discount_type', $membershipPlan->discount_type) === 'none' ? 'selected' : '' }}>
                                        No Discount
                                    </option>

                                    <option value="percentage"
                                        {{ old('discount_type', $membershipPlan->discount_type) === 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('discount_type', $membershipPlan->discount_type) === 'fixed' ? 'selected' : '' }}>
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
                                           id="discount_value"
                                           value="{{ old('discount_value', $membershipPlan->discount_value) }}"
                                           min="0"
                                           step="0.01"
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


                        {{-- Status --}}
                        <div class="mt-4 mb-4">

                            <div class="form-check form-switch">

                                <input type="checkbox"
                                       name="status"
                                       value="1"
                                       id="status"
                                       class="form-check-input"
                                       {{ old('status', $membershipPlan->status) ? 'checked' : '' }}>

                                <label class="form-check-label fw-semibold"
                                       for="status">
                                    Active Membership Plan
                                </label>

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>
                                Update Membership Plan

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


        {{-- Information --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Current Plan
                    </h5>
                </div>

                <div class="card-body">

                    <h5>
                        {{ $membershipPlan->name }}
                    </h5>

                    <p class="text-muted mb-3">
                        {{ $membershipPlan->description ?: 'No description available.' }}
                    </p>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Price</span>
                        <strong>
                            ₹{{ number_format($membershipPlan->price, 2) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Validity</span>
                        <strong>
                            {{ $membershipPlan->validity_days }} days
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>

                        @if($membershipPlan->status)
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Inactive
                            </span>
                        @endif
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const discountType =
        document.getElementById('discount_type');

    const discountSymbol =
        document.getElementById('discount-symbol');

    function updateDiscountSymbol() {

        if (discountType.value === 'percentage') {

            discountSymbol.innerText = '%';

        } else if (discountType.value === 'fixed') {

            discountSymbol.innerText = '₹';

        } else {

            discountSymbol.innerText = '—';

        }

    }

    discountType.addEventListener(
        'change',
        updateDiscountSymbol
    );

    updateDiscountSymbol();

});

</script>

@endsection
