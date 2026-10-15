@extends('admin.layout')

@section('title', 'Edit Supplier | Salon Management')

@section('page-title', 'Edit Supplier')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Edit Supplier
        </h4>

        <p class="text-muted mb-0">
            Update supplier information.
        </p>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>Please fix the following errors:</strong>

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


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

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



    {{-- =========================================================
         SUPPLIER FORM
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0 fw-semibold">

                <i class="bi bi-pencil-square me-2"></i>

                Supplier Information

            </h5>

        </div>


        <div class="card-body">

            <form
                method="POST"
                action="{{ route('admin.inventory.suppliers.update', $supplier->id) }}"
                id="supplierEditForm"
            >

                @csrf

                @method('PUT')


                <div class="row g-3">


                    {{-- =====================================================
                         SALON
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Salon
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="salon_id"
                            class="form-select @error('salon_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Salon
                            </option>

                            @foreach($salons as $salon)

                                <option
                                    value="{{ $salon->id }}"
                                    {{ old('salon_id', $supplier->salon_id) == $salon->id ? 'selected' : '' }}
                                >

                                    {{ $salon->name }}

                                </option>

                            @endforeach

                        </select>

                        @error('salon_id')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         SUPPLIER NAME
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Supplier Name
                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $supplier->name) }}"
                            placeholder="Enter supplier name"
                            maxlength="255"
                            required
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         COMPANY NAME
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Company Name

                        </label>

                        <input
                            type="text"
                            name="company_name"
                            class="form-control @error('company_name') is-invalid @enderror"
                            value="{{ old('company_name', $supplier->company_name) }}"
                            placeholder="Enter company name"
                            maxlength="255"
                        >

                        @error('company_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         PHONE
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="phone"
                            class="form-label fw-semibold"
                        >

                            Phone

                        </label>

                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $supplier->phone) }}"
                            placeholder="Enter 10-digit mobile number"
                            maxlength="10"
                            minlength="10"
                            pattern="[6-9][0-9]{9}"
                            inputmode="numeric"
                            autocomplete="tel"
                        >

                        <small class="text-muted">

                            Enter a valid 10-digit mobile number starting with
                            6, 7, 8 or 9.

                        </small>

                        @error('phone')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         EMAIL
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="email"
                            class="form-label fw-semibold"
                        >

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $supplier->email) }}"
                            placeholder="supplier@example.com"
                            maxlength="255"
                            autocomplete="email"
                        >

                        <small class="text-muted">

                            Example: supplier@gmail.com

                        </small>

                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         ADDRESS
                    ====================================================== --}}

                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Address

                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter complete supplier address"
                        >{{ old('address', $supplier->address) }}</textarea>

                        @error('address')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         CITY
                    ====================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            City

                        </label>

                        <input
                            type="text"
                            name="city"
                            class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city', $supplier->city) }}"
                            placeholder="City"
                            maxlength="100"
                        >

                        @error('city')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         STATE
                    ====================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            State

                        </label>

                        <input
                            type="text"
                            name="state"
                            class="form-control @error('state') is-invalid @enderror"
                            value="{{ old('state', $supplier->state) }}"
                            placeholder="State"
                            maxlength="100"
                        >

                        @error('state')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         PINCODE
                    ====================================================== --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Pincode

                        </label>

                        <input
                            type="text"
                            name="pincode"
                            class="form-control @error('pincode') is-invalid @enderror"
                            value="{{ old('pincode', $supplier->pincode) }}"
                            placeholder="Pincode"
                            maxlength="10"
                            inputmode="numeric"
                        >

                        @error('pincode')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         GST NUMBER
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            GST Number

                        </label>

                        <input
                            type="text"
                            name="gst_number"
                            class="form-control text-uppercase @error('gst_number') is-invalid @enderror"
                            value="{{ old('gst_number', $supplier->gst_number) }}"
                            placeholder="e.g. 27ABCDE1234F1Z5"
                            maxlength="15"
                        >

                        @error('gst_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         STATUS
                    ====================================================== --}}

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Status
                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="active"
                                {{ old('status', $supplier->status) === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ old('status', $supplier->status) === 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        @error('status')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>



                    {{-- =====================================================
                         NOTES
                    ====================================================== --}}

                    <div class="col-12">

                        <label class="form-label fw-semibold">

                            Notes

                        </label>

                        <textarea
                            name="notes"
                            rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Enter additional notes"
                        >{{ old('notes', $supplier->notes) }}</textarea>

                        @error('notes')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>



                {{-- =====================================================
                     BUTTONS
                ====================================================== --}}

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('admin.inventory.suppliers.index') }}"
                        class="btn btn-outline-secondary"
                    >

                        <i class="bi bi-arrow-left me-1"></i>

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Update Supplier

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection



{{-- =============================================================
     JAVASCRIPT
============================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ============================================================
       PHONE NUMBER
    ============================================================ */

    const phoneInput =
        document.getElementById('phone');


    if (phoneInput) {

        phoneInput.addEventListener('input', function () {

            this.value =
                this.value.replace(/[^0-9]/g, '');

            if (this.value.length > 10) {

                this.value =
                    this.value.substring(0, 10);

            }

            this.classList.remove('is-invalid');

            const errorElement =
                this.parentElement.querySelector(
                    '.frontend-phone-error'
                );

            if (errorElement) {
                errorElement.remove();
            }

        });


        phoneInput.addEventListener('paste', function (event) {

            event.preventDefault();

            const pastedText =
                (event.clipboardData || window.clipboardData)
                .getData('text');

            const numbersOnly =
                pastedText.replace(/[^0-9]/g, '');

            this.value =
                numbersOnly.substring(0, 10);

            this.dispatchEvent(
                new Event('input')
            );

        });

    }



    /* ============================================================
       EMAIL
    ============================================================ */

    const emailInput =
        document.getElementById('email');


    if (emailInput) {

        emailInput.addEventListener('input', function () {

            if (this.value.length > 255) {

                this.value =
                    this.value.substring(0, 255);

            }

            this.classList.remove('is-invalid');

        });

    }



    /* ============================================================
       GST NUMBER - UPPERCASE
    ============================================================ */

    const gstInput =
        document.querySelector('input[name="gst_number"]');


    if (gstInput) {

        gstInput.addEventListener('input', function () {

            this.value =
                this.value.toUpperCase();

        });

    }



    /* ============================================================
       PINCODE - NUMBERS ONLY
    ============================================================ */

    const pincodeInput =
        document.querySelector('input[name="pincode"]');


    if (pincodeInput) {

        pincodeInput.addEventListener('input', function () {

            this.value =
                this.value.replace(/[^0-9]/g, '');

            if (this.value.length > 10) {

                this.value =
                    this.value.substring(0, 10);

            }

        });

    }



    /* ============================================================
       FORM VALIDATION
    ============================================================ */

    const supplierForm =
        document.getElementById('supplierEditForm');


    if (supplierForm) {

        supplierForm.addEventListener(
            'submit',
            function (event) {


                /* ------------------------------------------------
                   PHONE
                ------------------------------------------------ */

                const phone =
                    phoneInput
                        ? phoneInput.value.trim()
                        : '';


                if (phone !== '') {

                    const phonePattern =
                        /^[6-9][0-9]{9}$/;


                    if (!phonePattern.test(phone)) {

                        event.preventDefault();

                        phoneInput.classList.add(
                            'is-invalid'
                        );


                        let errorElement =
                            phoneInput.parentElement.querySelector(
                                '.frontend-phone-error'
                            );


                        if (!errorElement) {

                            errorElement =
                                document.createElement('div');

                            errorElement.className =
                                'invalid-feedback d-block frontend-phone-error';

                            phoneInput.parentElement.appendChild(
                                errorElement
                            );

                        }


                        errorElement.textContent =
                            'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.';


                        phoneInput.focus();

                        return false;

                    }

                }



                /* ------------------------------------------------
                   EMAIL
                ------------------------------------------------ */

                if (
                    emailInput &&
                    emailInput.value.trim() !== ''
                ) {

                    if (!emailInput.checkValidity()) {

                        event.preventDefault();

                        emailInput.classList.add(
                            'is-invalid'
                        );

                        emailInput.focus();

                        return false;

                    }

                }

            }
        );

    }

});

</script>

@endpush