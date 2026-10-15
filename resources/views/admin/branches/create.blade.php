@extends('admin.layout')

@section('title', 'Add Branch | Salon Management')

@section('page-title', 'Add Branch')

@section('content')

<style>
    /* =========================================================
       PREMIUM 3D WHITE BRANCH FORM
       Font family and font sizes are NOT changed.
    ========================================================== */

    .branch-page {
        color: #29252a;
    }

    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .branch-page .page-intro {
        position: relative;
        margin-bottom: 22px;
        padding: 4px 2px;
    }

    .branch-page .page-intro h4 {
        color: #2e292d;
        letter-spacing: -0.2px;
    }

    .branch-page .page-intro p {
        color: #777178;
    }

    /* =========================================================
       MAIN CARD - 3D
    ========================================================== */

    .branch-page .branch-card {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(45, 37, 41, 0.08);
        border-radius: 20px;
        background: #ffffff;
        box-shadow:
            0 2px 3px rgba(45, 37, 41, 0.025),
            0 8px 18px rgba(45, 37, 41, 0.055),
            0 20px 45px rgba(45, 37, 41, 0.055);
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }

    .branch-page .branch-card:hover {
        box-shadow:
            0 3px 5px rgba(45, 37, 41, 0.03),
            0 12px 25px rgba(45, 37, 41, 0.075),
            0 25px 55px rgba(45, 37, 41, 0.06);
    }

    /* Top depth line */
    .branch-page .branch-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 25px;
        right: 25px;
        height: 3px;
        border-radius: 0 0 10px 10px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #c8aab4,
            #8b5e6d
        );
        box-shadow:
            0 3px 10px rgba(139, 94, 109, 0.18);
    }

    /* =========================================================
       CARD HEADER
    ========================================================== */

    .branch-page .branch-card-header {
        padding: 20px 24px;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #fcfafb 100%
        );
        border-bottom: 1px solid #eee8eb;
    }

    .branch-page .branch-card-header h5 {
        color: #302a2e;
        margin-bottom: 3px;
    }

    .branch-page .branch-card-header p {
        color: #817a7e;
        margin-bottom: 0;
    }

    /* =========================================================
       FORM BODY
    ========================================================== */

    .branch-page .branch-card-body {
        padding: 25px;
        background: #ffffff;
    }

    /* =========================================================
       FIELD GROUP
    ========================================================== */

    .branch-page .form-label {
        color: #40393e;
        margin-bottom: 7px;
    }

    .branch-page .required-mark {
        color: #d9534f;
    }

    /* =========================================================
       INPUTS / SELECT / TEXTAREA
    ========================================================== */

    .branch-page .form-control,
    .branch-page .form-select {
        border: 1px solid #e4dde0;
        border-radius: 11px;
        background: #ffffff;
        color: #29252a;
        box-shadow:
            inset 0 1px 2px rgba(0, 0, 0, 0.025),
            0 2px 5px rgba(45, 37, 41, 0.025);
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }

    .branch-page .form-control,
    .branch-page .form-select {
        min-height: 43px;
    }

    .branch-page textarea.form-control {
        min-height: auto;
    }

    .branch-page .form-control:hover,
    .branch-page .form-select:hover {
        border-color: #d4c7cc;
    }

    .branch-page .form-control:focus,
    .branch-page .form-select:focus {
        border-color: #8b5e6d;
        background: #ffffff;
        box-shadow:
            0 0 0 3px rgba(139, 94, 109, 0.08),
            0 6px 14px rgba(139, 94, 109, 0.08);
    }

    .branch-page .form-control.is-invalid,
    .branch-page .form-select.is-invalid {
        border-color: #dc3545;
    }

    .branch-page .form-control.is-valid {
        border-color: #198754;
    }

    .branch-page small.text-muted {
        display: block;
        margin-top: 6px;
        color: #8a8387 !important;
        line-height: 1.45;
    }

    /* =========================================================
       SECTION DIVIDER
    ========================================================== */

    .branch-page .credentials-section {
        position: relative;
        margin-top: 12px;
        padding: 22px 0 5px;
        border-top: 1px solid #eee8eb;
    }

    .branch-page .credentials-section::before {
        content: "";
        position: absolute;
        top: -1px;
        left: 0;
        width: 90px;
        height: 2px;
        border-radius: 10px;
        background: #8b5e6d;
    }

    .branch-page .credentials-title {
        color: #302a2e;
        margin-bottom: 4px;
    }

    .branch-page .credentials-description {
        color: #817a7e;
        margin-bottom: 0;
    }

    /* =========================================================
       PASSWORD MESSAGE
    ========================================================== */

    .branch-page #passwordMatchMessage {
        min-height: 18px;
    }

    /* =========================================================
       ALERTS
    ========================================================== */

    .branch-page .premium-alert {
        border: 1px solid transparent;
        border-radius: 13px;
        box-shadow:
            0 4px 12px rgba(0, 0, 0, 0.045);
    }

    .branch-page .alert-danger {
        border-color: #f1cccc;
    }

    /* =========================================================
       BUTTON AREA
    ========================================================== */

    .branch-page .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #eee8eb;
    }

    .branch-page .form-actions .btn {
        min-height: 42px;
        border-radius: 10px;
        padding-left: 18px;
        padding-right: 18px;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .branch-page .form-actions .btn:hover {
        transform: translateY(-2px);
    }

    .branch-page .btn-outline-secondary {
        border-color: #dcd4d8;
        color: #5f585c;
        background: #ffffff;
    }

    .branch-page .btn-outline-secondary:hover {
        background: #faf7f8;
        border-color: #cfc3c8;
        color: #40393e;
        box-shadow:
            0 6px 14px rgba(45, 37, 41, 0.07);
    }

    .branch-page .btn-primary {
        background: #8b5e6d;
        border-color: #8b5e6d;
        box-shadow:
            0 4px 8px rgba(139, 94, 109, 0.17),
            0 9px 18px rgba(139, 94, 109, 0.09);
    }

    .branch-page .btn-primary:hover,
    .branch-page .btn-primary:focus {
        background: #704856;
        border-color: #704856;
        box-shadow:
            0 6px 12px rgba(139, 94, 109, 0.20),
            0 12px 24px rgba(139, 94, 109, 0.11);
    }

    /* =========================================================
       SELECT ARROW / INPUT POLISH
    ========================================================== */

    .branch-page input::placeholder,
    .branch-page textarea::placeholder {
        color: #aaa2a6;
    }

    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 767.98px) {

        .branch-page .branch-card-header {
            padding: 18px;
        }

        .branch-page .branch-card-body {
            padding: 18px;
        }

        .branch-page .form-actions {
            flex-direction: column-reverse;
        }

        .branch-page .form-actions .btn {
            width: 100%;
        }
    }
</style>


<div class="container-fluid px-0 branch-page">

    {{-- =========================================================
         PAGE INTRO
    ========================================================== --}}

    <div class="page-intro">

        <h4 class="fw-bold mb-1">
            Add Branch
        </h4>

        <p class="text-muted mb-0">
            Create a new branch for your salon.
        </p>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show premium-alert">

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
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
         MAIN CARD
    ========================================================== --}}

    <div class="branch-card">

        {{-- CARD HEADER --}}

        <div class="branch-card-header">

            <h5 class="fw-semibold">
                Branch Information
            </h5>

            <p>
                Enter the branch details and manager login information.
            </p>

        </div>


        {{-- CARD BODY --}}

        <div class="branch-card-body">

            <form
                method="POST"
                action="{{ route('admin.branches.store') }}"
                id="branchForm"
            >

                @csrf


                <div class="row g-3">


                    {{-- =================================================
                         SALON
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="salon_id"
                            class="form-label fw-semibold"
                        >
                            Salon
                            <span class="required-mark">*</span>
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
                                    @selected(old('salon_id') == $salon->id)
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
                         BRANCH NAME
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="name"
                            class="form-label fw-semibold"
                        >
                            Branch Name
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="e.g. Main Branch"
                            value="{{ old('name') }}"
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
                         BRANCH CODE
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="code"
                            class="form-label fw-semibold"
                        >
                            Branch Code
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="text"
                            name="code"
                            id="code"
                            class="form-control text-uppercase @error('code') is-invalid @enderror"
                            placeholder="e.g. BR001"
                            value="{{ old('code') }}"
                            maxlength="50"
                            required
                        >

                        @error('code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         BRANCH MANAGER NAME
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="manager_name"
                            class="form-label fw-semibold"
                        >
                            Branch Manager Name
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="text"
                            name="manager_name"
                            id="manager_name"
                            class="form-control @error('manager_name') is-invalid @enderror"
                            placeholder="Enter branch manager name"
                            value="{{ old('manager_name') }}"
                            maxlength="255"
                            autocomplete="name"
                            required
                        >

                        <small class="text-muted">
                            Enter the name of the manager who will manage this branch.
                        </small>

                        @error('manager_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         PHONE
                    ================================================== --}}

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
                            value="{{ old('phone') }}"
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


                    {{-- =================================================
                         BRANCH EMAIL
                    ================================================== --}}

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
                            value="{{ old('email') }}"
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


                    {{-- =================================================
                         ADDRESS
                    ================================================== --}}

                    <div class="col-12">

                        <label
                            for="address"
                            class="form-label fw-semibold"
                        >
                            Address
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            rows="3"
                            class="form-control @error('address') is-invalid @enderror"
                            placeholder="Enter complete branch address"
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

                        <label
                            for="city"
                            class="form-label fw-semibold"
                        >
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            id="city"
                            class="form-control @error('city') is-invalid @enderror"
                            placeholder="City"
                            value="{{ old('city') }}"
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

                        <label
                            for="state"
                            class="form-label fw-semibold"
                        >
                            State
                        </label>

                        <input
                            type="text"
                            name="state"
                            id="state"
                            class="form-control @error('state') is-invalid @enderror"
                            placeholder="State"
                            value="{{ old('state') }}"
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

                        <label
                            for="pincode"
                            class="form-label fw-semibold"
                        >
                            Pincode
                        </label>

                        <input
                            type="text"
                            name="pincode"
                            id="pincode"
                            class="form-control @error('pincode') is-invalid @enderror"
                            placeholder="Pincode"
                            value="{{ old('pincode') }}"
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
                         OPENING TIME
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="opening_time"
                            class="form-label fw-semibold"
                        >
                            Opening Time
                        </label>

                        <input
                            type="time"
                            name="opening_time"
                            id="opening_time"
                            class="form-control @error('opening_time') is-invalid @enderror"
                            value="{{ old('opening_time') }}"
                        >

                        @error('opening_time')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CLOSING TIME
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="closing_time"
                            class="form-label fw-semibold"
                        >
                            Closing Time
                        </label>

                        <input
                            type="time"
                            name="closing_time"
                            id="closing_time"
                            class="form-control @error('closing_time') is-invalid @enderror"
                            value="{{ old('closing_time') }}"
                        >

                        @error('closing_time')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}

                    <div class="col-md-6">

                        <label
                            for="status"
                            class="form-label fw-semibold"
                        >
                            Status
                            <span class="required-mark">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="1"
                                @selected(old('status', '1') == '1')
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                @selected(old('status') === '0')
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
                         MANAGER LOGIN SECTION
                    ================================================== --}}

                    <div class="col-12 mt-3">

                        <div class="credentials-section">

                            <h5 class="credentials-title fw-bold">
                                Branch Manager Login Credentials
                            </h5>

                            <p class="credentials-description small">
                                These credentials will be used by the branch
                                manager to login to the admin panel.
                            </p>

                        </div>

                    </div>


                    {{-- =================================================
                         LOGIN EMAIL
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="login_email"
                            class="form-label fw-semibold"
                        >
                            Login Email
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="email"
                            name="login_email"
                            id="login_email"
                            class="form-control @error('login_email') is-invalid @enderror"
                            value="{{ old('login_email') }}"
                            placeholder="manager@gmail.com"
                            maxlength="255"
                            autocomplete="email"
                            required
                        >

                        <small class="text-muted">
                            This email will be used for manager login.
                        </small>

                        @error('login_email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         LOGIN PASSWORD
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="login_password"
                            class="form-label fw-semibold"
                        >
                            Login Password
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="password"
                            name="login_password"
                            id="login_password"
                            class="form-control @error('login_password') is-invalid @enderror"
                            placeholder="Minimum 8 characters"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        @error('login_password')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         CONFIRM PASSWORD
                    ================================================== --}}

                    <div class="col-md-4">

                        <label
                            for="login_password_confirmation"
                            class="form-label fw-semibold"
                        >
                            Confirm Password
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="password"
                            name="login_password_confirmation"
                            id="login_password_confirmation"
                            class="form-control"
                            placeholder="Confirm password"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >

                        <div
                            id="passwordMatchMessage"
                            class="small mt-1"
                        ></div>

                    </div>

                </div>


                {{-- =================================================
                     FORM ACTIONS
                ================================================== --}}

                <div class="form-actions">

                    <a
                        href="{{ route('admin.branches.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Create Branch
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
    BRANCH FORM JAVASCRIPT
============================================================= --}}

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       PHONE NUMBER VALIDATION
    ========================================================== */

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


    /* =========================================================
       BRANCH CODE - UPPERCASE
    ========================================================== */

    const codeInput =
        document.querySelector('input[name="code"]');

    if (codeInput) {

        codeInput.addEventListener('input', function () {

            this.value =
                this.value.toUpperCase();

        });

    }


    /* =========================================================
       EMAIL LENGTH
    ========================================================== */

    const emailInput =
        document.getElementById('email');

    if (emailInput) {

        emailInput.addEventListener('input', function () {

            if (this.value.length > 255) {

                this.value =
                    this.value.substring(0, 255);
            }

        });

    }


    /* =========================================================
       LOGIN EMAIL LENGTH
    ========================================================== */

    const loginEmailInput =
        document.getElementById('login_email');

    if (loginEmailInput) {

        loginEmailInput.addEventListener('input', function () {

            if (this.value.length > 255) {

                this.value =
                    this.value.substring(0, 255);
            }

        });

    }


    /* =========================================================
       MANAGER NAME
    ========================================================== */

    const managerNameInput =
        document.getElementById('manager_name');

    if (managerNameInput) {

        managerNameInput.addEventListener('input', function () {

            if (this.value.length > 255) {

                this.value =
                    this.value.substring(0, 255);
            }

        });

    }


    /* =========================================================
       PASSWORD MATCH
    ========================================================== */

    const password =
        document.getElementById('login_password');

    const confirmation =
        document.getElementById(
            'login_password_confirmation'
        );

    const passwordMessage =
        document.getElementById(
            'passwordMatchMessage'
        );


    function checkPasswordMatch() {

        if (
            !password ||
            !confirmation ||
            !passwordMessage
        ) {
            return;
        }


        if (confirmation.value === '') {

            passwordMessage.textContent = '';

            confirmation.classList.remove(
                'is-invalid',
                'is-valid'
            );

            return;
        }


        if (
            password.value ===
            confirmation.value
        ) {

            confirmation.classList.remove(
                'is-invalid'
            );

            confirmation.classList.add(
                'is-valid'
            );

            passwordMessage.className =
                'small mt-1 text-success';

            passwordMessage.textContent =
                'Passwords match.';

        } else {

            confirmation.classList.remove(
                'is-valid'
            );

            confirmation.classList.add(
                'is-invalid'
            );

            passwordMessage.className =
                'small mt-1 text-danger';

            passwordMessage.textContent =
                'Passwords do not match.';

        }

    }


    if (password) {

        password.addEventListener(
            'input',
            checkPasswordMatch
        );

    }


    if (confirmation) {

        confirmation.addEventListener(
            'input',
            checkPasswordMatch
        );

    }


    /* =========================================================
       FORM SUBMIT VALIDATION
    ========================================================== */

    const branchForm =
        document.getElementById('branchForm');


    if (branchForm) {

        branchForm.addEventListener(
            'submit',
            function (event) {


                /* =================================================
                   PHONE
                ================================================== */

                const phone =
                    phoneInput
                        ? phoneInput.value.trim()
                        : '';


                if (phone !== '') {

                    const phonePattern =
                        /^[6-9][0-9]{9}$/;


                    if (
                        !phonePattern.test(phone)
                    ) {

                        event.preventDefault();

                        phoneInput.classList.add(
                            'is-invalid'
                        );


                        let errorElement =
                            phoneInput.parentElement
                                .querySelector(
                                    '.frontend-phone-error'
                                );


                        if (!errorElement) {

                            errorElement =
                                document.createElement(
                                    'div'
                                );

                            errorElement.className =
                                'invalid-feedback d-block frontend-phone-error';

                            phoneInput.parentElement
                                .appendChild(
                                    errorElement
                                );

                        }


                        errorElement.textContent =
                            'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8 or 9.';


                        phoneInput.focus();

                        return false;

                    } else {

                        phoneInput.classList.remove(
                            'is-invalid'
                        );


                        const errorElement =
                            phoneInput.parentElement
                                .querySelector(
                                    '.frontend-phone-error'
                                );


                        if (errorElement) {
                            errorElement.remove();
                        }

                    }

                }


                /* =================================================
                   BRANCH EMAIL
                ================================================== */

                if (
                    emailInput &&
                    emailInput.value.trim() !== ''
                ) {

                    if (
                        !emailInput.checkValidity()
                    ) {

                        event.preventDefault();

                        emailInput.classList.add(
                            'is-invalid'
                        );

                        emailInput.focus();

                        return false;

                    }

                }


                /* =================================================
                   LOGIN EMAIL
                ================================================== */

                if (loginEmailInput) {

                    if (
                        !loginEmailInput.checkValidity()
                    ) {

                        event.preventDefault();

                        loginEmailInput.classList.add(
                            'is-invalid'
                        );

                        loginEmailInput.focus();

                        return false;

                    }

                }


                /* =================================================
                   PASSWORD MATCH
                ================================================== */

                if (
                    password &&
                    confirmation &&
                    password.value !==
                    confirmation.value
                ) {

                    event.preventDefault();

                    confirmation.classList.add(
                        'is-invalid'
                    );


                    if (passwordMessage) {

                        passwordMessage.className =
                            'small mt-1 text-danger';

                        passwordMessage.textContent =
                            'Password and confirm password must match.';

                    }


                    confirmation.focus();

                    return false;

                }


                /* =================================================
                   PASSWORD MINIMUM LENGTH
                ================================================== */

                if (
                    password &&
                    password.value.length < 8
                ) {

                    event.preventDefault();

                    password.classList.add(
                        'is-invalid'
                    );

                    password.focus();

                    return false;

                }

            }
        );

    }

});
</script>

@endpush

@endsection