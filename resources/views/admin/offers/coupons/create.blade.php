@extends('admin.layout')

@section('title', 'Add Coupon')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="bi bi-ticket-perforated me-2"></i>
                Add Coupon
            </h3>
            <p class="text-muted mb-0">
                Create a coupon code for customers.
            </p>
        </div>

        <a href="{{ route('admin.offers.coupons.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>
    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('admin.offers.coupons.store') }}"
          method="POST">

        @csrf

        <div class="row">

            {{-- LEFT COLUMN --}}
            <div class="col-lg-8">

                {{-- Basic Information --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Coupon Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Coupon Code --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Coupon Code
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="code"
                                       id="couponCode"
                                       class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}"
                                       placeholder="e.g. WELCOME20"
                                       maxlength="50"
                                       required>

                                <small class="text-muted">
                                    Only letters, numbers, hyphen and underscore are allowed.
                                </small>

                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Branch --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Branch
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="branch_id"
                                        class="form-select @error('branch_id') is-invalid @enderror">

                                    <option value="">
                                        All Branches
                                    </option>

                                    @foreach($branches as $branch)

                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>

                                            {{ $branch->name }}

                                            @if($branch->code)
                                                ({{ $branch->code }})
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">
                                    Select All Branches to use this coupon everywhere.
                                </small>

                                @error('branch_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Description --}}
                            <div class="col-12 mb-3">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          placeholder="Example: Get 20% off on your first salon visit">{{ old('description') }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Discount Settings --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-percent me-2"></i>
                            Discount Settings
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Discount Type --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Discount Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="discount_type"
                                        id="discountType"
                                        class="form-select @error('discount_type') is-invalid @enderror"
                                        required>

                                    <option value="percentage"
                                        {{ old('discount_type', 'percentage') == 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
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
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Discount Value
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text"
                                          id="discountSymbol">
                                        %
                                    </span>

                                    <input type="number"
                                           name="discount_value"
                                           id="discountValue"
                                           class="form-control @error('discount_value') is-invalid @enderror"
                                           value="{{ old('discount_value') }}"
                                           min="0.01"
                                           step="0.01"
                                           placeholder="20"
                                           required>

                                </div>

                                @error('discount_value')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Minimum Bill --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Minimum Bill Amount
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">₹</span>

                                    <input type="number"
                                           name="minimum_bill_amount"
                                           class="form-control @error('minimum_bill_amount') is-invalid @enderror"
                                           value="{{ old('minimum_bill_amount', 0) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="500">

                                </div>

                                <small class="text-muted">
                                    Coupon will work only when bill reaches this amount.
                                </small>

                                @error('minimum_bill_amount')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Maximum Discount --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Maximum Discount Cap
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">₹</span>

                                    <input type="number"
                                           name="maximum_discount"
                                           class="form-control @error('maximum_discount') is-invalid @enderror"
                                           value="{{ old('maximum_discount') }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="500">

                                </div>

                                <small class="text-muted">
                                    Leave empty for no maximum discount.
                                </small>

                                @error('maximum_discount')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Validity --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event me-2"></i>
                            Coupon Validity
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            {{-- Valid From --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Valid From
                                </label>

                                <input type="datetime-local"
                                       name="valid_from"
                                       class="form-control @error('valid_from') is-invalid @enderror"
                                       value="{{ old('valid_from') }}">

                                @error('valid_from')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            {{-- Valid Until --}}
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Valid Until
                                </label>

                                <input type="datetime-local"
                                       name="valid_until"
                                       class="form-control @error('valid_until') is-invalid @enderror"
                                       value="{{ old('valid_until') }}">

                                @error('valid_until')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4">

                {{-- Usage Limits --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-people me-2"></i>
                            Usage Limits
                        </h5>
                    </div>

                    <div class="card-body">

                        {{-- Usage Limit --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Total Usage Limit
                            </label>

                            <input type="number"
                                   name="usage_limit"
                                   class="form-control @error('usage_limit') is-invalid @enderror"
                                   value="{{ old('usage_limit') }}"
                                   min="1"
                                   placeholder="100">

                            <small class="text-muted">
                                Total number of times this coupon can be used.
                            </small>

                            @error('usage_limit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Per Customer --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Per Customer Limit
                            </label>

                            <input type="number"
                                   name="per_customer_limit"
                                   class="form-control @error('per_customer_limit') is-invalid @enderror"
                                   value="{{ old('per_customer_limit') }}"
                                   min="1"
                                   placeholder="1">

                            <small class="text-muted">
                                Maximum uses allowed for one customer.
                            </small>

                            @error('per_customer_limit')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Status --}}
                <div class="card mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-toggle-on me-2"></i>
                            Status
                        </h5>
                    </div>

                    <div class="card-body">

                        <select name="status"
                                class="form-select @error('status') is-invalid @enderror">

                            <option value="active"
                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Info Box --}}
                <div class="alert alert-info">

                    <strong>
                        <i class="bi bi-lightbulb me-1"></i>
                        Example
                    </strong>

                    <hr>

                    <div class="small">

                        <strong>WELCOME20</strong><br>

                        20% discount<br>

                        Minimum bill: ₹500<br>

                        Maximum discount: ₹500<br>

                        Usage limit: 100<br>

                        Per customer: 1

                    </div>

                </div>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="card">

            <div class="card-body d-flex justify-content-end gap-2">

                <a href="{{ route('admin.offers.coupons.index') }}"
                   class="btn btn-secondary">

                    <i class="bi bi-x-circle me-1"></i>
                    Cancel

                </a>

                <button type="submit"
                        class="btn btn-primary">

                    <i class="bi bi-check-circle me-1"></i>
                    Create Coupon

                </button>

            </div>

        </div>

    </form>

</div>


{{-- JavaScript --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const discountType = document.getElementById('discountType');
    const discountSymbol = document.getElementById('discountSymbol');
    const discountValue = document.getElementById('discountValue');

    function updateDiscountType() {

        if (discountType.value === 'percentage') {

            discountSymbol.textContent = '%';

            discountValue.max = '100';

            discountValue.placeholder = '20';

        } else {

            discountSymbol.textContent = '₹';

            discountValue.removeAttribute('max');

            discountValue.placeholder = '500';

        }

    }

    discountType.addEventListener('change', updateDiscountType);

    updateDiscountType();


    // Automatically convert coupon code to uppercase
    const couponCode = document.getElementById('couponCode');

    couponCode.addEventListener('input', function () {

        this.value = this.value
            .toUpperCase()
            .replace(/[^A-Z0-9_-]/g, '');

    });

});

</script>

@endsection