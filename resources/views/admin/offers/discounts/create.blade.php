@extends('admin.layout')

@section('title', 'Add Discount')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-percent"></i>
                Add Discount
            </h3>

            <p class="text-muted mb-0">
                Create a discount for services, packages or memberships.
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

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.offers.discounts.store') }}">

        @csrf


        <div class="row">


            {{-- LEFT COLUMN --}}
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
                                       value="{{ old('name') }}"
                                       placeholder="Example: Summer Special 20%"
                                       required>

                            </div>


                            {{-- Description --}}
                            <div class="col-md-12">

                                <label class="form-label">
                                    Description
                                </label>

                                <textarea name="description"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Enter discount description...">{{ old('description') }}</textarea>

                            </div>


                            {{-- Branch --}}
<div class="col-md-6">

    <label for="branch_id" class="form-label">
        Branch
    </label>

    <input type="text"
           class="form-control"
           value="All Branches"
           readonly>

    {{-- Backend ला branch_id = null जाईल --}}
    <input type="hidden" name="branch_id" value="">

    <small class="text-muted">
        This discount will apply to all branches.
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
                                        {{ old('applies_to', 'all') == 'all' ? 'selected' : '' }}>
                                        All
                                    </option>

                                    <option value="service"
                                        {{ old('applies_to') == 'service' ? 'selected' : '' }}>
                                        Services
                                    </option>

                                    <option value="package"
                                        {{ old('applies_to') == 'package' ? 'selected' : '' }}>
                                        Packages
                                    </option>

                                    <option value="membership"
                                        {{ old('applies_to') == 'membership' ? 'selected' : '' }}>
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


                            {{-- Discount Type --}}
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
                                        {{ old('discount_type', 'percentage') == 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)
                                    </option>

                                    <option value="fixed"
                                        {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount (₹)
                                    </option>

                                </select>

                            </div>


                            {{-- Discount Value --}}
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
                                           value="{{ old('discount_value') }}"
                                           min="0.01"
                                           step="0.01"
                                           placeholder="20"
                                           required>

                                    <span class="input-group-text"
                                          id="discount_symbol">
                                        %
                                    </span>

                                </div>

                                <small class="text-muted">
                                    Percentage cannot exceed 100%.
                                </small>

                            </div>


                            {{-- Minimum Bill --}}
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
                                           value="{{ old('minimum_bill_amount', 0) }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="500">

                                </div>

                                <small class="text-muted">
                                    Discount will apply only when bill reaches this amount.
                                </small>

                            </div>


                            {{-- Maximum Discount --}}
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
                                           value="{{ old('maximum_discount') }}"
                                           min="0"
                                           step="0.01"
                                           placeholder="1000">

                                </div>

                                <small class="text-muted">
                                    Optional maximum discount amount.
                                </small>

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


                            {{-- Start Date --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Start Date
                                </label>

                                <input type="date"
                                       name="start_date"
                                       class="form-control"
                                       value="{{ old('start_date') }}">

                            </div>


                            {{-- End Date --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    End Date
                                </label>

                                <input type="date"
                                       name="end_date"
                                       class="form-control"
                                       value="{{ old('end_date') }}">

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- RIGHT COLUMN --}}
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
                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        <small class="text-muted">
                            Only active discounts can be applied to bills.
                        </small>

                    </div>

                </div>



                {{-- Information --}}
                <div class="card shadow-sm">

                    <div class="card-header">

                        <h5 class="mb-0">
                            <i class="bi bi-lightbulb"></i>
                            Discount Rules
                        </h5>

                    </div>


                    <div class="card-body">

                        <ul class="mb-0 ps-3">

                            <li class="mb-2">
                                Percentage discount cannot exceed
                                <strong>100%</strong>.
                            </li>

                            <li class="mb-2">
                                Fixed discount cannot exceed the bill amount.
                            </li>

                            <li class="mb-2">
                                Minimum bill amount is optional.
                            </li>

                            <li class="mb-2">
                                Maximum discount cap is optional.
                            </li>

                            <li>
                                Leave branch empty for
                                <strong>all branches</strong>.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>



        {{-- Submit Buttons --}}
        <div class="d-flex justify-content-end gap-2 mt-2 mb-4">

            <a href="{{ route('admin.offers.discounts.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-x-lg"></i>
                Cancel

            </a>


            <button type="submit"
                    class="btn btn-primary">

                <i class="bi bi-check-lg"></i>
                Create Discount

            </button>

        </div>

    </form>

</div>


{{-- Percentage / Fixed Symbol --}}
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