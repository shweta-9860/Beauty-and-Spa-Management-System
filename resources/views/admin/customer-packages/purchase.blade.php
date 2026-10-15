@extends('admin.layout')

@section('title', 'Purchase Package')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-box-seam me-2"></i>
                Purchase Package
            </h4>

            <p class="text-muted mb-0">
                Sell a package to a customer
            </p>
        </div>

        <a href="{{ route('admin.customer-packages.index') }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <form method="POST"
          action="{{ route('admin.customer-packages.store-purchase') }}">

        @csrf


        <div class="row">

            {{-- Customer --}}
            <div class="col-md-6 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-header">

                        <strong>
                            <i class="bi bi-person me-1"></i>
                            Customer
                        </strong>

                    </div>

                    <div class="card-body">

                        <label class="form-label">
                            Select Customer
                            <span class="text-danger">*</span>
                        </label>

                        <select name="customer_id"
                                id="customer_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Customer --
                            </option>

                            @foreach($customers as $customer)

                                <option value="{{ $customer->id }}"
                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }}

                                    @if($customer->phone)
                                        - {{ $customer->phone }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- Package --}}
            <div class="col-md-6 mb-3">

                <div class="card shadow-sm h-100">

                    <div class="card-header">

                        <strong>
                            <i class="bi bi-box me-1"></i>
                            Package
                        </strong>

                    </div>

                    <div class="card-body">

                        <label class="form-label">
                            Select Package
                            <span class="text-danger">*</span>
                        </label>

                        <select name="package_id"
                                id="package_id"
                                class="form-select"
                                required>

                            <option value="">
                                -- Select Package --
                            </option>

                            @foreach($packages as $package)

                                <option value="{{ $package->id }}"
                                    data-price="{{ $package->price ?? 0 }}"
                                    data-uses="{{ $package->total_uses ?? ($package->uses ?? 0) }}"
                                    data-validity="{{ $package->validity_days ?? 0 }}">

                                    {{ $package->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- Package Details --}}
            <div class="col-12 mb-3">

                <div id="packageDetails"
                     class="card shadow-sm"
                     style="display:none;">

                    <div class="card-header">

                        <strong>
                            Package Details
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="row text-center">

                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Package Price
                                </div>

                                <h4 class="mt-1">
                                    ₹<span id="packagePrice">0</span>
                                </h4>

                            </div>


                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Total Uses
                                </div>

                                <h4 class="mt-1"
                                    id="packageUses">
                                    0
                                </h4>

                            </div>


                            <div class="col-md-4">

                                <div class="text-muted small">
                                    Validity
                                </div>

                                <h4 class="mt-1">

                                    <span id="packageValidity">
                                        0
                                    </span>

                                    Days

                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Purchase Date --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Purchase Date
                    <span class="text-danger">*</span>
                </label>

                <input type="date"
                       name="purchase_date"
                       class="form-control"
                       value="{{ old('purchase_date', date('Y-m-d')) }}"
                       required>

            </div>


            {{-- Payment Method --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Payment Method
                    <span class="text-danger">*</span>
                </label>

                <select name="payment_method"
                        class="form-select"
                        required>

                    <option value="cash">
                        Cash
                    </option>

                    <option value="upi">
                        UPI
                    </option>

                    <option value="card">
                        Card
                    </option>

                    <option value="bank_transfer">
                        Bank Transfer
                    </option>

                    <option value="online">
                        Online
                    </option>

                </select>

            </div>


            {{-- Notes --}}
            <div class="col-md-4 mb-3">

                <label class="form-label">
                    Notes
                </label>

                <textarea name="notes"
                          class="form-control"
                          rows="1"
                          placeholder="Optional notes">{{ old('notes') }}</textarea>

            </div>


            {{-- Submit --}}
            <div class="col-12">

                <div class="card shadow-sm">

                    <div class="card-body text-end">

                        <button type="submit"
                                class="btn btn-primary btn-lg">

                            <i class="bi bi-cart-check me-1"></i>

                            Purchase Package

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const packageSelect =
        document.getElementById('package_id');

    const details =
        document.getElementById('packageDetails');

    const price =
        document.getElementById('packagePrice');

    const uses =
        document.getElementById('packageUses');

    const validity =
        document.getElementById('packageValidity');


    packageSelect.addEventListener('change', function () {

        const option =
            this.options[this.selectedIndex];

        if (!this.value) {

            details.style.display = 'none';

            return;
        }


        price.textContent =
            Number(option.dataset.price || 0)
                .toLocaleString('en-IN');


        uses.textContent =
            option.dataset.uses || 0;


        validity.textContent =
            option.dataset.validity || 0;


        details.style.display = 'block';

    });

});

</script>

@endsection