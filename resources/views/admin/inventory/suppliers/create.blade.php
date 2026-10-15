@extends('admin.layout')

@section('title', 'Add Supplier | Salon Management')
@section('page-title', 'Add Supplier')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
         VALIDATION / SUCCESS MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>
                <i class="bi bi-exclamation-triangle me-2"></i>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
         SUPPLIER FORM
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="bi bi-truck me-2"></i>
                Supplier Information
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.inventory.suppliers.store') }}"
                  id="supplierForm">

                @csrf

                <div class="row g-3">

                    {{-- =================================================
                         SALON
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="salon_id" class="form-label">
                            Salon <span class="text-danger">*</span>
                        </label>

                        <select
                            name="salon_id"
                            id="salon_id"
                            class="form-select @error('salon_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Salon
                            </option>

                            @foreach($salons as $salon)

                                <option
                                    value="{{ $salon->id }}"
                                    {{ old('salon_id') == $salon->id ? 'selected' : '' }}
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


                    {{-- =================================================
                         SUPPLIER NAME
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="name" class="form-label">
                            Supplier Name <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
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


                    {{-- =================================================
                         COMPANY NAME
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="company_name" class="form-label">
                            Company Name
                        </label>

                        <input
                            type="text"
                            name="company_name"
                            id="company_name"
                            class="form-control @error('company_name') is-invalid @enderror"
                            value="{{ old('company_name') }}"
                            placeholder="Enter company name"
                            maxlength="255"
                        >

                        @error('company_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         PHONE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="phone" class="form-label">
                            Phone Number
                        </label>

                        <input
                            type="tel"
                            name="phone"
                            id="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}"
                            placeholder="Enter 10-digit mobile number"
                            maxlength="10"
                            minlength="10"
                            pattern="[6-9][0-9]{9}"
                            inputmode="numeric"
                            autocomplete="tel"
                        >

                        <small class="text-muted">
                            Enter 10-digit Indian mobile number starting with 6, 7, 8 or 9.
                        </small>

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         EMAIL
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="supplier@example.com"
                            maxlength="255"
                            autocomplete="email"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         ADDRESS
                    ================================================== --}}

                    <div class="col-md-12">

                        <label for="address" class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter supplier address"
                            maxlength="1000"
                        >{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         CITY
                    ================================================== --}}

                    <div class="col-md-4">

                        <label for="city" class="form-label">
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            id="city"
                            class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city') }}"
                            placeholder="Enter city"
                            maxlength="100"
                        >

                        @error('city')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         STATE
                    ================================================== --}}

                    <div class="col-md-4">

                        <label for="state" class="form-label">
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            id="state"
                            class="form-control @error('state') is-invalid @enderror"
                            value="{{ old('state') }}"
                            placeholder="Enter state"
                            maxlength="100"
                        >

                        @error('state')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         PINCODE
                    ================================================== --}}

                    <div class="col-md-4">

                        <label for="pincode" class="form-label">
                            Pincode
                        </label>

                        <input
                            type="text"
                            name="pincode"
                            id="pincode"
                            class="form-control @error('pincode') is-invalid @enderror"
                            value="{{ old('pincode') }}"
                            placeholder="Enter pincode"
                            maxlength="10"
                            inputmode="numeric"
                        >

                        @error('pincode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         GST NUMBER
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="gst_number" class="form-label">
                            GST Number
                        </label>

                        <input
                            type="text"
                            name="gst_number"
                            id="gst_number"
                            class="form-control @error('gst_number') is-invalid @enderror"
                            value="{{ old('gst_number') }}"
                            placeholder="Enter GST number"
                            maxlength="15"
                            style="text-transform: uppercase;"
                        >

                        <small class="text-muted">
                            GST number should contain maximum 15 characters.
                        </small>

                        @error('gst_number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="col-md-6">

                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}
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


                    {{-- =================================================
                         NOTES
                    ================================================== --}}

                    <div class="col-md-12">

                        <label for="notes" class="form-label">
                            Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Enter any additional notes"
                            maxlength="2000"
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =================================================
                     ACTION BUTTONS
                ================================================== --}}

                <div class="d-flex justify-content-end gap-2 mt-4">

                    <a
                        href="{{ route('admin.inventory.suppliers.index') }}"
                        class="btn btn-secondary"
                    >
                        <i class="bi bi-arrow-left me-1"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-check-circle me-1"></i>
                        Save Supplier
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
     JAVASCRIPT VALIDATION
============================================================== --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const phone = document.getElementById('phone');
    const email = document.getElementById('email');
    const pincode = document.getElementById('pincode');
    const gstNumber = document.getElementById('gst_number');
    const form = document.getElementById('supplierForm');


    /* =========================================================
       PHONE - ONLY NUMBERS + MAX 10 DIGITS
    ========================================================== */

    if (phone) {

        phone.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 10);

        });


        phone.addEventListener('paste', function () {

            setTimeout(() => {

                this.value = this.value
                    .replace(/\D/g, '')
                    .slice(0, 10);

            }, 0);

        });

    }


    /* =========================================================
       EMAIL - MAX 255 CHARACTERS
    ========================================================== */

    if (email) {

        email.addEventListener('input', function () {

            this.value = this.value.slice(0, 255);

        });

    }


    /* =========================================================
       PINCODE - ONLY NUMBERS
    ========================================================== */

    if (pincode) {

        pincode.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 10);

        });

    }


    /* =========================================================
       GST NUMBER - UPPERCASE + MAX 15
    ========================================================== */

    if (gstNumber) {

        gstNumber.addEventListener('input', function () {

            this.value = this.value
                .toUpperCase()
                .replace(/\s/g, '')
                .slice(0, 15);

        });

    }


    /* =========================================================
       FORM SUBMIT VALIDATION
    ========================================================== */

    if (form) {

        form.addEventListener('submit', function (event) {

            /* PHONE VALIDATION */

            if (phone && phone.value.trim() !== '') {

                const phonePattern = /^[6-9][0-9]{9}$/;

                if (!phonePattern.test(phone.value.trim())) {

                    event.preventDefault();

                    phone.focus();

                    alert(
                        'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.'
                    );

                    return false;
                }
            }


            /* EMAIL VALIDATION */

            if (email && email.value.trim() !== '') {

                if (!email.checkValidity()) {

                    event.preventDefault();

                    email.focus();

                    alert(
                        'Please enter a valid email address.'
                    );

                    return false;
                }
            }

        });

    }

});

</script>

@endpush

@endsection