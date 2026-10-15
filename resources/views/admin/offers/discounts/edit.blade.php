@extends('admin.layout')

@section('title', 'Edit Discount')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-pencil-square"></i>
                Edit Discount
            </h3>

            <p class="text-muted mb-0">
                Update discount details and settings.
            </p>
        </div>

        <a href="{{ route('admin.offers.discounts.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Back

        </a>

    </div>


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.offers.discounts.update', $discount) }}">

        @csrf
        @method('PUT')


        <div class="row">

            {{-- LEFT --}}
            <div class="col-lg-8">

                {{-- Basic Information --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle"></i>
                            Basic Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Name --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    Discount Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name', $discount->name) }}"
                                       required>

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          class="form-control"
                                          rows="3">{{ old('description', $discount->description) }}</textarea>

                            </div>


                            {{-- Branch --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Branch
                                </label>

                                <select name="branch_id"
                                        class="form-select">

                                    <option value="">
                                        All Branches
                                    </option>

                                    @foreach($branches as $branch)

                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $discount->branch_id) == $branch->id ? 'selected' : '' }}>

                                            {{ $branch->name }}

                                            @if($branch->code)
                                                ({{ $branch->code }})
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                <small class="text-muted">
                                    Leave blank for all branches.
                                </small>

                            </div>


                            {{-- Applies To --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Applies To
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="applies_to"
                                        class="form-select"
                                        required>

                                    <option value="all"
                                        {{ old('applies_to', $discount->applies_to) == 'all' ? 'selected' : '' }}>
                                        All
                                    </option>

                                    <option value="service"
                                        {{ old('applies_to', $discount->applies_to) == 'service' ? 'selected' : '' }}>
                                        Services
                                    </option>

                                    <option value="package"
                                        {{ old('applies_to', $discount->applies_to) == 'package' ? 'selected' : '' }}>
                                        Packages
                                    </option>

                                    <option value="membership"
                                        {{ old('applies_to', $discount->applies_to) == 'membership' ? 'selected' : '' }}>
                                        Memberships
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Discount Configuration --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            <i class="bi bi-percent"></i>
                            Discount Configuration
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            {{-- Type --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Discount Type
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="discount_type"
                                        id="discount_type"
                                        class="form-select"
                                        required>

                                    <option value="percentage"
                                        {{ old('discount_type', $discount->discount_type) == 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('discount_type', $discount->discount_type) == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount (₹)
                                    </option>

                                </select>

                            </div>


                            {{-- Value --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Discount Value
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="input-group">

                                    <input type="number"
                                           name="discount_value"
                                           id="discount_value"
                                           class="form-control"
                                           value="{{ old('discount_value', $discount->discount_value) }}"
                                           min="0.01"
                                           step="0.01"
                                           required>

                                    <span class="input-group-text"
                                          id="discount_symbol">
                                        %
                                    </span>

                                </div>

                            </div>


                            {{-- Minimum --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Minimum Bill Amount
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>

                                    <input type="number"
                                           name="minimum_bill_amount"
                                           class="form-control"
                                           value="{{ old('minimum_bill_amount', $discount->minimum_bill_amount) }}"
                                           min="0"
                                           step="0.01">

                                </div>

                            </div>


                            {{-- Maximum --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Maximum Discount Cap
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ₹
                                    </span>

                                    <input type="number"
                                           name="maximum_discount"
                                           class="form-control"
                                           value="{{ old('maximum_discount', $discount->maximum_discount) }}"
                                           min="0"
                                           step="0.01">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Validity --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            <i class="bi bi-calendar-event"></i>
                            Validity
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    Start Date
                                </label>

                                <input type="date"
                                       name="start_date"
                                       class="form-control"
                                       value="{{ old('start_date', $discount->start_date ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    End Date
                                </label>

                                <input type="date"
                                       name="end_date"
                                       class="form-control"
                                       value="{{ old('end_date', $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- RIGHT --}}
            <div class="col-lg-4">

                {{-- Status --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">

                        <h5 class="mb-0">
                            <i class="bi bi-toggle-on"></i>
                            Status
                        </h5>

                    </div>

                    <div class="card-body">

                        <label class="form-label">
                            Discount Status
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="active"
                                {{ old('status', $discount->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status', $discount->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                </div>


                {{-- Current Discount --}}
                <div class="card shadow-sm mb-4">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-eye"></i>
                            Current Discount
                        </h5>
                    </div>

                    <div class="card-body">

                        <p class="mb-2">
                            <strong>Name:</strong>
                            {{ $discount->name }}
                        </p>

                        <p class="mb-2">
                            <strong>Type:</strong>
                            {{ ucfirst($discount->discount_type) }}
                        </p>

                        <p class="mb-2">
                            <strong>Value:</strong>

                            @if($discount->discount_type === 'percentage')
                                {{ $discount->discount_value }}%
                            @else
                                ₹{{ number_format($discount->discount_value, 2) }}
                            @endif

                        </p>

                        <p class="mb-0">
                            <strong>Status:</strong>

                            @if($discount->status === 'active')
                                <span class="badge bg-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    Inactive
                                </span>
                            @endif

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- Buttons --}}
        <div class="d-flex justify-content-end gap-2 mb-4">

            <a href="{{ route('admin.offers.discounts.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-x-lg"></i>
                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg"></i>
                Update Discount

            </button>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const type = document.getElementById('discount_type');
    const symbol = document.getElementById('discount_symbol');
    const value = document.getElementById('discount_value');

    function updateDiscountType() {

        if (type.value === 'percentage') {

            symbol.textContent = '%';
            value.max = 100;

        } else {

            symbol.textContent = '₹';
            value.removeAttribute('max');

        }

    }

    type.addEventListener('change', updateDiscountType);

    updateDiscountType();

});

</script>

@endsection