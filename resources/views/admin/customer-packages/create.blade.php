@extends('admin.layout')

@section('title', 'Purchase Package | Salon Management')
@section('page-title', 'Purchase Package')

@section('content')

<div class="container-fluid px-0">

    {{-- Validation Errors --}}
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
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-person-vcard me-2"></i>
                Purchase Package
            </h4>

            <p class="text-muted mb-0">
                Assign a service package to a customer.
            </p>
        </div>

        <a href="{{ route('admin.customer-packages.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Customer Packages

        </a>

    </div>


    {{-- Purchase Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>
                Package Purchase Details
            </h5>

        </div>


        <div class="card-body">

            <form action="{{ route('admin.customer-packages.store') }}"
                  method="POST"
                  id="customerPackageForm">

                @csrf


                {{-- Customer & Package --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-person me-2"></i>
                        Customer & Package
                    </h6>


                    <div class="row g-3">

                        {{-- Customer --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Customer
                                <span class="text-danger">*</span>
                            </label>

                            <select name="customer_id"
                                    id="customer_id"
                                    class="form-select @error('customer_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Customer
                                </option>

                                @foreach($customers as $customer)

                                    <option value="{{ $customer->id }}"
                                        {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                        {{ $customer->name }}

                                        @if(!empty($customer->phone))
                                            - {{ $customer->phone }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('customer_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Package --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Package
                                <span class="text-danger">*</span>
                            </label>

                            <select name="package_id"
                                    id="package_id"
                                    class="form-select @error('package_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Package
                                </option>

                                @foreach($packages as $package)

                                    <option value="{{ $package->id }}"
                                        {{ old('package_id') == $package->id ? 'selected' : '' }}>

                                        {{ $package->name }}
                                        — ₹{{ number_format($package->price, 2) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('package_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- Package Information --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Package Information
                    </h6>


                    <div class="row g-3">

                        {{-- Package Price --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Package Price
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="text"
                                       id="package_price"
                                       class="form-control"
                                       value="0.00"
                                       readonly>

                            </div>

                        </div>


                        {{-- Total Uses --}}
<div class="col-md-4">

    <label class="form-label">
        Total Uses
    </label>

    <input type="text"
           name="total_uses"
           id="total_uses"
           class="form-control"
           value="{{ old('total_uses', 0) }}"
           readonly>

</div>

                        {{-- Validity --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Validity
                            </label>

                            <div class="input-group">

                                <input type="text"
                                       id="validity_days"
                                       class="form-control"
                                       value="0"
                                       readonly>

                                <span class="input-group-text">
                                    Days
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- Financial Details --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-cash-stack me-2"></i>
                        Payment Details
                    </h6>


                    <div class="row g-3">

                        {{-- Discount --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Discount
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="discount"
                                       id="discount"
                                       class="form-control @error('discount') is-invalid @enderror"
                                       value="{{ old('discount', 0) }}"
                                       min="0"
                                       step="0.01">

                            </div>

                            @error('discount')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Final Amount --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Final Amount
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="text"
                                       id="final_amount"
                                       class="form-control fw-semibold"
                                       value="0.00"
                                       readonly>

                            </div>

                        </div>


                        {{-- Paid Amount --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Paid Amount
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="paid_amount"
                                       id="paid_amount"
                                       class="form-control @error('paid_amount') is-invalid @enderror"
                                       value="{{ old('paid_amount', 0) }}"
                                       min="0"
                                       step="0.01"
                                       required>

                            </div>

                            @error('paid_amount')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>


                    {{-- Payment Summary --}}
                    <div class="row mt-3">

                        <div class="col-md-4">

                            <div class="border rounded p-3 bg-light">

                                <small class="text-muted">
                                    Final Amount
                                </small>

                                <div class="fs-5 fw-bold"
                                     id="summary_final">
                                    ₹0.00
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3 bg-light">

                                <small class="text-muted">
                                    Paid Amount
                                </small>

                                <div class="fs-5 fw-bold"
                                     id="summary_paid">
                                    ₹0.00
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="border rounded p-3 bg-light">

                                <small class="text-muted">
                                    Pending Amount
                                </small>

                                <div class="fs-5 fw-bold"
                                     id="summary_pending">
                                    ₹0.00
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <hr class="my-4">


                {{-- Dates --}}
                <div class="mb-4">

                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-calendar-event me-2"></i>
                        Package Validity
                    </h6>


                    <div class="row g-3">

                        {{-- Purchase Date --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Purchase Date
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="purchase_date"
                                   id="purchase_date"
                                   class="form-control @error('purchase_date') is-invalid @enderror"
                                   value="{{ old('purchase_date', now()->format('Y-m-d')) }}"
                                   required>

                            @error('purchase_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Expiry Date --}}
                        <div class="col-md-6">

                            <label class="form-label">
                                Expiry Date
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                   name="expiry_date"
                                   id="expiry_date"
                                   class="form-control @error('expiry_date') is-invalid @enderror"
                                   value="{{ old('expiry_date') }}"
                                   required>

                            @error('expiry_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- Notes --}}
                <div class="mb-4">

                    <label class="form-label">
                        Notes
                    </label>

                    <textarea name="notes"
                              rows="3"
                              class="form-control"
                              placeholder="Optional notes...">{{ old('notes') }}</textarea>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('admin.customer-packages.index') }}"
                       class="btn btn-light border">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn text-white"
                            style="background:#8b5e6d;">

                        <i class="bi bi-check-circle me-1"></i>
                        Purchase Package

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const packages = @json($packages);

    const packageSelect = document.getElementById('package_id');

    const packagePrice = document.getElementById('package_price');
    const totalUses = document.getElementById('total_uses');
    const validityDays = document.getElementById('validity_days');

    const discount = document.getElementById('discount');
    const finalAmount = document.getElementById('final_amount');

    const paidAmount = document.getElementById('paid_amount');

    const summaryFinal = document.getElementById('summary_final');
    const summaryPaid = document.getElementById('summary_paid');
    const summaryPending = document.getElementById('summary_pending');

    const purchaseDate = document.getElementById('purchase_date');
    const expiryDate = document.getElementById('expiry_date');


    function updatePackageDetails() {

        const packageId = packageSelect.value;

        const selectedPackage = packages.find(function (item) {

            return String(item.id) === String(packageId);

        });


        if (!selectedPackage) {

            packagePrice.value = '0.00';
            totalUses.value = '0';
            validityDays.value = '0';

            calculateAmount();

            return;
        }


        const price = parseFloat(selectedPackage.price) || 0;

        const uses = parseInt(selectedPackage.total_uses) || 0;

        const validity = parseInt(selectedPackage.validity_days) || 0;


        packagePrice.value = price.toFixed(2);

        totalUses.value = uses;

        validityDays.value = validity;


        calculateAmount();

        calculateExpiry();

    }


    function calculateAmount() {

        const price = parseFloat(
            packagePrice.value
        ) || 0;

        const discountValue = parseFloat(
            discount.value
        ) || 0;

        let final = price - discountValue;

        if (final < 0) {
            final = 0;
        }


        finalAmount.value = final.toFixed(2);

        summaryFinal.textContent =
            '₹' + final.toFixed(2);


        const paid = parseFloat(
            paidAmount.value
        ) || 0;

        let pending = final - paid;

        if (pending < 0) {
            pending = 0;
        }


        summaryPaid.textContent =
            '₹' + paid.toFixed(2);

        summaryPending.textContent =
            '₹' + pending.toFixed(2);

    }


    function calculateExpiry() {

        const selectedPackage = packages.find(function (item) {

            return String(item.id) === String(packageSelect.value);

        });


        if (!selectedPackage) {
            return;
        }


        const validity = parseInt(
            selectedPackage.validity_days
        ) || 0;


        const dateValue = purchaseDate.value;

        if (!dateValue || validity <= 0) {
            return;
        }


        const date = new Date(dateValue + 'T00:00:00');

        date.setDate(
            date.getDate() + validity
        );


        const year = date.getFullYear();

        const month = String(
            date.getMonth() + 1
        ).padStart(2, '0');

        const day = String(
            date.getDate()
        ).padStart(2, '0');


        expiryDate.value =
            `${year}-${month}-${day}`;

    }


    packageSelect.addEventListener(
        'change',
        updatePackageDetails
    );


    discount.addEventListener(
        'input',
        calculateAmount
    );


    paidAmount.addEventListener(
        'input',
        calculateAmount
    );


    purchaseDate.addEventListener(
        'change',
        calculateExpiry
    );


    /*
     * Prevent invalid discount/payment
     */
    document.getElementById('customerPackageForm')
        .addEventListener('submit', function (event) {

            const price =
                parseFloat(packagePrice.value) || 0;

            const discountValue =
                parseFloat(discount.value) || 0;

            const final =
                price - discountValue;

            const paid =
                parseFloat(paidAmount.value) || 0;


            if (discountValue > price) {

                event.preventDefault();

                alert(
                    'Discount cannot be greater than package price.'
                );

                discount.focus();

                return;
            }


            if (paid > final) {

                event.preventDefault();

                alert(
                    'Paid amount cannot be greater than final amount.'
                );

                paidAmount.focus();

                return;
            }

        });


    /*
     * Load old package values
     */
    if (packageSelect.value) {
        updatePackageDetails();
    }

    calculateAmount();

});

</script>

@endpush