@extends('admin.layout')

@section('title', 'Edit Branch | Salon Management')

@section('page-title', 'Edit Branch')

@section('content')

<style>
    /* =========================================================
       EDIT BRANCH - WHITE 3D PREMIUM DESIGN
       Font-family / font-size intentionally not changed
    ========================================================= */

    .branch-edit-page {
        padding-bottom: 24px;
    }

    /* =========================
       WHITE HERO / BANNER
    ========================== */

    .branch-edit-hero {
        position: relative;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 20px;
        overflow: hidden;

        box-shadow:
            0 2px 4px rgba(0, 0, 0, 0.04),
            0 12px 28px rgba(0, 0, 0, 0.07),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    .branch-edit-hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78493,
            #8b5e6d
        );
    }

    .branch-edit-hero h4 {
        margin-bottom: 4px;
        font-weight: 700;
    }

    .branch-edit-hero p {
        margin-bottom: 0;
        color: #6c757d;
    }

    /* =========================
       ALERTS
    ========================== */

    .branch-edit-page .alert {
        border-radius: 13px;
        border-width: 1px;
        box-shadow:
            0 3px 8px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.7);
    }

    /* =========================
       MAIN CARD
    ========================== */

    .branch-edit-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 20px;
        overflow: hidden;

        box-shadow:
            0 3px 6px rgba(0, 0, 0, 0.035),
            0 15px 35px rgba(0, 0, 0, 0.075),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    /* =========================
       CARD HEADER
    ========================== */

    .branch-edit-card-header {
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #fcfcfc 100%
        );

        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding: 18px 22px;

        box-shadow:
            0 2px 8px rgba(0, 0, 0, 0.035);
    }

    .branch-edit-card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    /* =========================
       CARD BODY
    ========================== */

    .branch-edit-card-body {
        padding: 24px;
        background: #ffffff;
    }

    /* =========================
       FORM SECTIONS
    ========================== */

    .branch-form-section {
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #fdfdfd 100%
        );

        border: 1px solid rgba(0, 0, 0, 0.06);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 18px;

        box-shadow:
            0 2px 4px rgba(0, 0, 0, 0.025),
            0 8px 20px rgba(0, 0, 0, 0.045),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    .branch-form-section:last-child {
        margin-bottom: 0;
    }

    .branch-section-title {
        margin-bottom: 18px;
        padding-bottom: 12px;

        border-bottom: 1px dashed rgba(139, 94, 109, 0.18);

        font-weight: 600;
    }

    /* =========================
       FORM FIELDS
    ========================== */

    .branch-edit-page .form-label {
        margin-bottom: 7px;
    }

    .branch-edit-page .form-control,
    .branch-edit-page .form-select {
        border-radius: 11px;
        border: 1px solid #dee2e6;
        background: #ffffff;

        box-shadow:
            inset 0 1px 2px rgba(0, 0, 0, 0.025),
            0 2px 4px rgba(0, 0, 0, 0.025);

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }

    .branch-edit-page .form-control:hover,
    .branch-edit-page .form-select:hover {
        border-color: #c8c8c8;
        box-shadow:
            0 3px 7px rgba(0, 0, 0, 0.045),
            inset 0 1px 2px rgba(0, 0, 0, 0.025);
    }

    .branch-edit-page .form-control:focus,
    .branch-edit-page .form-select:focus {
        border-color: rgba(139, 94, 109, 0.55);

        box-shadow:
            0 0 0 0.2rem rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(0, 0, 0, 0.045),
            inset 0 1px 2px rgba(0, 0, 0, 0.025);

        transform: translateY(-1px);
    }

    .branch-edit-page textarea.form-control {
        resize: vertical;
        min-height: 90px;
    }

    .branch-edit-page .form-control.is-invalid,
    .branch-edit-page .form-select.is-invalid {
        box-shadow:
            0 3px 8px rgba(220, 53, 69, 0.08);
    }

    .branch-edit-page .text-muted {
        color: #7b8085 !important;
    }

    /* =========================
       ACTION AREA
    ========================== */

    .branch-edit-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;

        margin-top: 22px;
        padding-top: 20px;

        border-top: 1px solid rgba(0, 0, 0, 0.07);
    }

    .branch-action-btn {
        border-radius: 11px;

        box-shadow:
            0 3px 0 rgba(0, 0, 0, 0.07),
            0 7px 16px rgba(0, 0, 0, 0.06);

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .branch-action-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 5px 0 rgba(0, 0, 0, 0.07),
            0 10px 20px rgba(0, 0, 0, 0.09);
    }

    .branch-action-btn:active {
        transform: translateY(1px);

        box-shadow:
            0 2px 0 rgba(0, 0, 0, 0.07),
            0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* =========================
       SMALL RESPONSIVE FIX
    ========================== */

    @media (max-width: 767.98px) {

        .branch-edit-hero {
            padding: 18px;
            border-radius: 15px;
        }

        .branch-edit-card-body {
            padding: 16px;
        }

        .branch-form-section {
            padding: 16px;
        }

        .branch-edit-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .branch-edit-actions .btn {
            width: 100%;
        }
    }
</style>


<div class="container-fluid px-0 branch-edit-page">

    {{-- =========================================================
         WHITE 3D HERO
    ========================================================== --}}

    <div class="branch-edit-hero">

        <h4>
            Edit Branch
        </h4>

        <p>
            Update branch information and settings.
        </p>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
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

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         MAIN EDIT CARD
    ========================================================== --}}

    <div class="branch-edit-card">

        {{-- CARD HEADER --}}

        <div class="branch-edit-card-header">

            <h5>
                Branch Information
            </h5>

        </div>


        {{-- CARD BODY --}}

        <div class="branch-edit-card-body">

            <form
                method="POST"
                action="{{ route('admin.branches.update', $branch->id) }}"
                id="branchEditForm"
            >

                @csrf

                @method('PUT')


                {{-- =================================================
                     SECTION 1 - BASIC INFORMATION
                ================================================== --}}

                <div class="branch-form-section">

                    <div class="branch-section-title">
                        Basic Information
                    </div>


                    <div class="row g-3">

                        {{-- SALON --}}

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
                                        @selected(old('salon_id', $branch->salon_id) == $salon->id)
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


                        {{-- BRANCH NAME --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Branch Name

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $branch->name) }}"
                                placeholder="e.g. Main Branch"
                                maxlength="255"
                                required
                            >

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- BRANCH CODE --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Branch Code

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="code"
                                id="branchCode"
                                class="form-control text-uppercase @error('code') is-invalid @enderror"
                                value="{{ old('code', $branch->code) }}"
                                placeholder="e.g. BR001"
                                maxlength="50"
                                required
                            >

                            @error('code')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- MANAGER --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">

                                Branch Manager

                            </label>

                            <select
                                name="manager_id"
                                class="form-select @error('manager_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Manager
                                </option>

                                @foreach($managers as $manager)

                                    <option
                                        value="{{ $manager->id }}"
                                        @selected(old('manager_id', $branch->manager_id) == $manager->id)
                                    >

                                        {{ $manager->name }}

                                        @if($manager->email)
                                            - {{ $manager->email }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('manager_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PHONE --}}

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
                                value="{{ old('phone', $branch->phone) }}"
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


                        {{-- EMAIL --}}

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
                                value="{{ old('email', $branch->email) }}"
                                placeholder="branch@example.com"
                                maxlength="255"
                                autocomplete="email"
                            >

                            <small class="text-muted">
                                Example: branch@gmail.com
                            </small>

                            @error('email')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SECTION 2 - LOCATION
                ================================================== --}}

                <div class="branch-form-section">

                    <div class="branch-section-title">
                        Location Details
                    </div>


                    <div class="row g-3">

                        {{-- ADDRESS --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter complete branch address"
                            >{{ old('address', $branch->address) }}</textarea>

                            @error('address')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- CITY --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                City
                            </label>

                            <input
                                type="text"
                                name="city"
                                class="form-control @error('city') is-invalid @enderror"
                                value="{{ old('city', $branch->city) }}"
                                placeholder="City"
                                maxlength="100"
                            >

                            @error('city')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STATE --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                State
                            </label>

                            <input
                                type="text"
                                name="state"
                                class="form-control @error('state') is-invalid @enderror"
                                value="{{ old('state', $branch->state) }}"
                                placeholder="State"
                                maxlength="100"
                            >

                            @error('state')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PINCODE --}}

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Pincode
                            </label>

                            <input
                                type="text"
                                name="pincode"
                                class="form-control @error('pincode') is-invalid @enderror"
                                value="{{ old('pincode', $branch->pincode) }}"
                                placeholder="Pincode"
                                maxlength="10"
                            >

                            @error('pincode')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     SECTION 3 - BUSINESS HOURS & STATUS
                ================================================== --}}

                <div class="branch-form-section">

                    <div class="branch-section-title">
                        Business Hours & Status
                    </div>


                    <div class="row g-3">

                        {{-- OPENING TIME --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Opening Time
                            </label>

                            <input
                                type="time"
                                name="opening_time"
                                class="form-control @error('opening_time') is-invalid @enderror"
                                value="{{ old(
                                    'opening_time',
                                    $branch->opening_time
                                        ? substr($branch->opening_time, 0, 5)
                                        : ''
                                ) }}"
                            >

                            @error('opening_time')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- CLOSING TIME --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Closing Time
                            </label>

                            <input
                                type="time"
                                name="closing_time"
                                class="form-control @error('closing_time') is-invalid @enderror"
                                value="{{ old(
                                    'closing_time',
                                    $branch->closing_time
                                        ? substr($branch->closing_time, 0, 5)
                                        : ''
                                ) }}"
                            >

                            @error('closing_time')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STATUS --}}

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
                                    value="1"
                                    @selected(old('status', $branch->status) == '1')
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected(old('status', $branch->status) == '0')
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

                    </div>

                </div>


                {{-- =================================================
                     BUTTONS
                ================================================== --}}

                <div class="branch-edit-actions">

                    <a
                        href="{{ route('admin.branches.index') }}"
                        class="btn btn-outline-secondary branch-action-btn"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary branch-action-btn"
                    >
                        Update Branch
                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

@endsection


{{-- =============================================================
    BRANCH EDIT JAVASCRIPT
============================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       PHONE NUMBER VALIDATION
    ============================================================ */

    const phoneInput = document.getElementById('phone');

    if (phoneInput) {

        phoneInput.addEventListener('input', function () {

            // Allow numbers only
            this.value = this.value.replace(/[^0-9]/g, '');

            // Maximum 10 digits
            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }

            // Remove frontend error while typing
            this.classList.remove('is-invalid');

            const errorElement =
                this.parentElement.querySelector(
                    '.frontend-phone-error'
                );

            if (errorElement) {
                errorElement.remove();
            }

        });


        /* --------------------------------------------------------
           CLEAN PASTED PHONE NUMBER
        -------------------------------------------------------- */

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
       BRANCH CODE - UPPERCASE
    ============================================================ */

    const branchCode =
        document.getElementById('branchCode');

    if (branchCode) {

        branchCode.addEventListener('input', function () {

            this.value =
                this.value.toUpperCase();

        });

    }


    /* ============================================================
       EMAIL MAX LENGTH
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
       FORM SUBMIT VALIDATION
    ============================================================ */

    const branchEditForm =
        document.getElementById('branchEditForm');

    if (branchEditForm) {

        branchEditForm.addEventListener(
            'submit',
            function (event) {

                /* ================================================
                   PHONE
                ================================================= */

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


                /* ================================================
                   BRANCH EMAIL
                ================================================= */

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