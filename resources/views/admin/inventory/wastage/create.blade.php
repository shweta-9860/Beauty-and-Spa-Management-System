@extends('admin.layout')

@section('title', 'Add Wastage')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-trash3 me-2"></i>
                Add Wastage
            </h3>

            <p class="text-muted mb-0">
                Report damaged, expired or wasted inventory.
            </p>
        </div>

        <a href="{{ route('admin.inventory.wastage.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
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
          action="{{ route('admin.inventory.wastage.store') }}">

        @csrf


        <div class="row">

            {{-- Main Form --}}
            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Wastage Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="row g-3">


                            {{-- Branch --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Branch
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="branch_id"
                                        class="form-select @error('branch_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Branch
                                    </option>

                                    @foreach($branches as $branch)

                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>

                                            {{ $branch->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('branch_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Product --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Product
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="product_id"
                                        id="product_id"
                                        class="form-select @error('product_id') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Product
                                    </option>

                                    @foreach($products as $product)

                                        <option value="{{ $product->id }}"
                                                data-stock="{{ $product->stock ?? 0 }}"
                                            {{ old('product_id') == $product->id ? 'selected' : '' }}>

                                            {{ $product->name }}

                                            @if(isset($product->stock))
                                                — Stock: {{ $product->stock }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('product_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Available Stock --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Available Stock
                                </label>

                                <input type="text"
                                       id="available_stock"
                                       class="form-control"
                                       value="0"
                                       readonly>

                                <small class="text-muted">
                                    Stock will be deducted only after approval.
                                </small>

                            </div>


                            {{-- Staff --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Reported By
                                </label>

                                <select name="staff_id"
                                        class="form-select @error('staff_id') is-invalid @enderror">

                                    <option value="">
                                        Select Staff
                                    </option>

                                    @foreach($staff as $member)

                                        <option value="{{ $member->id }}"
                                            {{ old('staff_id') == $member->id ? 'selected' : '' }}>

                                            {{ $member->name }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('staff_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Date --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Wastage Date
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="date"
                                       name="wastage_date"
                                       value="{{ old('wastage_date', date('Y-m-d')) }}"
                                       class="form-control @error('wastage_date') is-invalid @enderror"
                                       required>

                                @error('wastage_date')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Quantity --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Quantity
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="quantity"
                                       id="quantity"
                                       value="{{ old('quantity') }}"
                                       min="0.01"
                                       step="0.01"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       placeholder="Enter quantity"
                                       required>

                                @error('quantity')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Unit Cost --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Unit Cost (₹)
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number"
                                       name="unit_cost"
                                       id="unit_cost"
                                       value="{{ old('unit_cost', 0) }}"
                                       min="0"
                                       step="0.01"
                                       class="form-control @error('unit_cost') is-invalid @enderror"
                                       placeholder="Enter unit cost"
                                       required>

                                @error('unit_cost')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Reason --}}
                            <div class="col-md-6">

                                <label class="form-label">
                                    Reason
                                    <span class="text-danger">*</span>
                                </label>

                                <select name="reason"
                                        class="form-select @error('reason') is-invalid @enderror"
                                        required>

                                    <option value="">
                                        Select Reason
                                    </option>

                                    <option value="damaged"
                                        {{ old('reason') == 'damaged' ? 'selected' : '' }}>
                                        Damaged
                                    </option>

                                    <option value="expired"
                                        {{ old('reason') == 'expired' ? 'selected' : '' }}>
                                        Expired
                                    </option>

                                    <option value="spillage"
                                        {{ old('reason') == 'spillage' ? 'selected' : '' }}>
                                        Spillage
                                    </option>

                                    <option value="leakage"
                                        {{ old('reason') == 'leakage' ? 'selected' : '' }}>
                                        Leakage
                                    </option>

                                    <option value="broken"
                                        {{ old('reason') == 'broken' ? 'selected' : '' }}>
                                        Broken
                                    </option>

                                    <option value="contaminated"
                                        {{ old('reason') == 'contaminated' ? 'selected' : '' }}>
                                        Contaminated
                                    </option>

                                    <option value="lost"
                                        {{ old('reason') == 'lost' ? 'selected' : '' }}>
                                        Lost
                                    </option>

                                    <option value="other"
                                        {{ old('reason') == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                </select>

                                @error('reason')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Notes --}}
                            <div class="col-12">

                                <label class="form-label">
                                    Notes
                                </label>

                                <textarea name="notes"
                                          rows="4"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          placeholder="Enter additional details...">{{ old('notes') }}</textarea>

                                @error('notes')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Summary --}}
            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">

                        <h5 class="mb-0">
                            <i class="bi bi-calculator me-2"></i>
                            Wastage Summary
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="mb-3">

                            <label class="text-muted">
                                Quantity
                            </label>

                            <h5 id="summary_quantity">
                                0.00
                            </h5>

                        </div>


                        <div class="mb-3">

                            <label class="text-muted">
                                Unit Cost
                            </label>

                            <h5>
                                ₹<span id="summary_unit_cost">0.00</span>
                            </h5>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Total Wastage Cost
                            </strong>

                            <strong class="text-danger fs-5">

                                ₹<span id="summary_total">
                                    0.00
                                </span>

                            </strong>

                        </div>


                        <div class="alert alert-warning mt-4 mb-0">

                            <i class="bi bi-exclamation-triangle me-1"></i>

                            <small>
                                This wastage will remain
                                <strong>Pending</strong>
                                until it is approved.
                                Stock will be deducted only after approval.
                            </small>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="card">

                    <div class="card-body">

                        <button type="submit"
                                class="btn btn-primary w-100 mb-2">

                            <i class="bi bi-save me-1"></i>
                            Report Wastage

                        </button>


                        <a href="{{ route('admin.inventory.wastage.index') }}"
                           class="btn btn-outline-secondary w-100">

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- Calculation Script --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const productSelect = document.getElementById('product_id');

    const stockInput = document.getElementById('available_stock');

    const quantityInput = document.getElementById('quantity');

    const unitCostInput = document.getElementById('unit_cost');

    const summaryQuantity =
        document.getElementById('summary_quantity');

    const summaryUnitCost =
        document.getElementById('summary_unit_cost');

    const summaryTotal =
        document.getElementById('summary_total');


    function updateSummary() {

        const quantity =
            parseFloat(quantityInput.value) || 0;

        const unitCost =
            parseFloat(unitCostInput.value) || 0;

        const total =
            quantity * unitCost;


        summaryQuantity.textContent =
            quantity.toFixed(2);

        summaryUnitCost.textContent =
            unitCost.toFixed(2);

        summaryTotal.textContent =
            total.toFixed(2);
    }


    productSelect.addEventListener('change', function () {

        const selected =
            this.options[this.selectedIndex];

        const stock =
            selected.getAttribute('data-stock') || 0;

        stockInput.value = stock;
    });


    quantityInput.addEventListener(
        'input',
        updateSummary
    );


    unitCostInput.addEventListener(
        'input',
        updateSummary
    );


    // Load selected product stock after validation error
    if (productSelect.value) {

        productSelect.dispatchEvent(
            new Event('change')
        );

    }


    updateSummary();

});

</script>

@endsection