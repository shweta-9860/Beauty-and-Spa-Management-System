@extends('admin.layout')

@section('title', 'Edit Coupon')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Edit Coupon</h3>
            <p class="text-muted mb-0">Update coupon details</p>
        </div>

        <a href="{{ route('admin.offers.coupons.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-ticket-perforated me-2"></i>
                Coupon Details
            </h5>
        </div>

        <div class="card-body">

            <form
                action="{{ route('admin.offers.coupons.update', $coupon->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Coupon Code --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Coupon Code <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="code"
                            class="form-control"
                            value="{{ old('code', $coupon->code) }}"
                            maxlength="50"
                            required
                        >
                    </div>

                    {{-- Branch --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Branch
                        </label>

                        <select name="branch_id" class="form-select">

                            <option value="">
                                All Branches
                            </option>

                            @foreach ($branches as $branch)

                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id', $coupon->branch_id) == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="3"
                        >{{ old('description', $coupon->description) }}</textarea>
                    </div>

                    {{-- Discount Type --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Discount Type <span class="text-danger">*</span>
                        </label>

                        <select
                            name="discount_type"
                            id="discount_type"
                            class="form-select"
                            required
                        >

                            <option
                                value="percentage"
                                {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}
                            >
                                Percentage (%)
                            </option>

                            <option
                                value="fixed"
                                {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}
                            >
                                Fixed Amount (₹)
                            </option>

                        </select>
                    </div>

                    {{-- Discount Value --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Discount Value <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="discount_value"
                            class="form-control"
                            value="{{ old('discount_value', $coupon->discount_value) }}"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>

                    {{-- Minimum Bill --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Minimum Bill Amount
                        </label>

                        <input
                            type="number"
                            name="minimum_bill_amount"
                            class="form-control"
                            value="{{ old('minimum_bill_amount', $coupon->minimum_bill_amount) }}"
                            min="0"
                            step="0.01"
                        >
                    </div>

                    {{-- Maximum Discount --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Maximum Discount
                        </label>

                        <input
                            type="number"
                            name="maximum_discount"
                            class="form-control"
                            value="{{ old('maximum_discount', $coupon->maximum_discount) }}"
                            min="0"
                            step="0.01"
                        >

                        <small class="text-muted">
                            Leave blank for no maximum limit.
                        </small>
                    </div>

                    {{-- Valid From --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Valid From
                        </label>

                        <input
                            type="datetime-local"
                            name="valid_from"
                            class="form-control"
                            value="{{ old('valid_from', $coupon->valid_from ? \Carbon\Carbon::parse($coupon->valid_from)->format('Y-m-d\TH:i') : '') }}"
                        >
                    </div>

                    {{-- Valid Until --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Valid Until
                        </label>

                        <input
                            type="datetime-local"
                            name="valid_until"
                            class="form-control"
                            value="{{ old('valid_until', $coupon->valid_until ? \Carbon\Carbon::parse($coupon->valid_until)->format('Y-m-d\TH:i') : '') }}"
                        >
                    </div>

                    {{-- Usage Limit --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Usage Limit
                        </label>

                        <input
                            type="number"
                            name="usage_limit"
                            class="form-control"
                            value="{{ old('usage_limit', $coupon->usage_limit) }}"
                            min="1"
                        >

                        <small class="text-muted">
                            Leave blank for unlimited usage.
                        </small>
                    </div>

                    {{-- Per Customer Limit --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Per Customer Limit
                        </label>

                        <input
                            type="number"
                            name="per_customer_limit"
                            class="form-control"
                            value="{{ old('per_customer_limit', $coupon->per_customer_limit) }}"
                            min="1"
                        >

                        <small class="text-muted">
                            Leave blank for unlimited usage per customer.
                        </small>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="active"
                                {{ old('status', $coupon->status) == 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ old('status', $coupon->status) == 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>
                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('admin.offers.coupons.index') }}"
                        class="btn btn-light border"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-lg me-1"></i>
                        Update Coupon
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection