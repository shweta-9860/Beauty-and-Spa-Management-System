@extends('admin.layout')

@section('title', $branch->name . ' Login | Salon Management')

@section('page-title', 'Branch Login')

@section('content')

<div class="container-fluid branch-login-page">

    <div class="row justify-content-center">

        <div class="col-md-6 col-lg-5 col-xl-4">

            {{-- =====================================================
                LOGIN CARD
            ====================================================== --}}
            <div class="branch-login-card">

                {{-- Top Accent --}}
                <div class="branch-login-accent"></div>

                <div class="branch-login-body">

                    {{-- =================================================
                        HEADER
                    ================================================== --}}
                    <div class="branch-login-header">

                        <div class="branch-login-icon">
                            <i class="bi bi-building-lock"></i>
                        </div>

                        <h4 class="fw-bold mb-1">
                            {{ $branch->name }}
                        </h4>

                        <p class="text-muted mb-0">
                            Branch Login
                        </p>

                    </div>


                    {{-- =================================================
                        SESSION ERROR
                    ================================================== --}}
                    @if(session('error'))

                        <div class="branch-alert branch-alert-danger">

                            <div class="branch-alert-icon">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>

                            <div>
                                {{ session('error') }}
                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                        VALIDATION ERRORS
                    ================================================== --}}
                    @if($errors->any())

                        <div class="branch-alert branch-alert-danger">

                            <div class="branch-alert-icon">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>

                            <div>

                                @foreach($errors->all() as $error)

                                    <div class="mb-1">
                                        {{ $error }}
                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    {{-- =================================================
                        LOGIN FORM
                    ================================================== --}}
                    <form
                        method="POST"
                        action="{{ route('admin.branches.login.submit', $branch->id) }}"
                        id="branchLoginForm"
                    >

                        @csrf


                        {{-- =================================================
                            EMAIL
                        ================================================== --}}
                        <div class="branch-form-group">

                            <label
                                for="branchLoginEmail"
                                class="form-label fw-semibold"
                            >
                                Email
                            </label>

                            <div class="branch-input-wrap">

                                <span class="branch-input-icon">
                                    <i class="bi bi-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    id="branchLoginEmail"
                                    class="form-control branch-input"
                                    value="{{ old('email') }}"
                                    placeholder="Enter branch login email"
                                    required
                                    autofocus
                                    autocomplete="email"
                                >

                            </div>

                        </div>


                        {{-- =================================================
                            PASSWORD
                        ================================================== --}}
                        <div class="branch-form-group">

                            <label
                                for="branchLoginPassword"
                                class="form-label fw-semibold"
                            >
                                Password
                            </label>

                            <div class="branch-input-wrap">

                                <span class="branch-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    name="password"
                                    id="branchLoginPassword"
                                    class="form-control branch-input branch-password-input"
                                    placeholder="Enter password"
                                    required
                                    autocomplete="current-password"
                                >

                                <button
                                    type="button"
                                    class="branch-password-toggle"
                                    id="toggleBranchPassword"
                                    aria-label="Show password"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                            </div>

                        </div>


                        {{-- =================================================
                            REMEMBER ME
                        ================================================== --}}
                        <div class="branch-remember-row">

                            <div class="form-check">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="form-check-input"
                                    id="remember"
                                >

                                <label
                                    class="form-check-label"
                                    for="remember"
                                >
                                    Remember me
                                </label>

                            </div>

                        </div>


                        {{-- =================================================
                            LOGIN BUTTON
                        ================================================== --}}
                        <button
                            type="submit"
                            class="branch-login-btn"
                            id="branchLoginButton"
                        >

                            <i class="bi bi-box-arrow-in-right me-1"></i>

                            <span>Login to Branch</span>

                        </button>

                    </form>


                    {{-- =================================================
                        BACK TO DASHBOARD
                    ================================================== --}}
                    <div class="branch-back-wrapper">

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="branch-back-link"
                        >

                            <i class="bi bi-arrow-left"></i>

                            <span>Back to Dashboard</span>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    PAGE STYLE
========================================================= --}}
<style>

    .branch-login-page {
        padding-top: 45px;
        padding-bottom: 45px;
    }


    /* =========================================================
       LOGIN CARD
    ========================================================= */

    .branch-login-card {
        position: relative;
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;

        border: 1px solid rgba(139, 94, 109, 0.10);

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.04),
            0 12px 25px rgba(45, 37, 41, 0.08),
            0 25px 55px rgba(45, 37, 41, 0.08);

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }


    .branch-login-card:hover {

        transform: translateY(-3px);

        box-shadow:
            0 7px 12px rgba(45, 37, 41, 0.05),
            0 18px 35px rgba(45, 37, 41, 0.10),
            0 30px 65px rgba(45, 37, 41, 0.09);
    }


    /* =========================================================
       TOP ACCENT
    ========================================================= */

    .branch-login-accent {
        height: 5px;

        background: linear-gradient(
            90deg,
            #8b5e6d 0%,
            #b78b99 50%,
            #8b5e6d 100%
        );
    }


    /* =========================================================
       BODY
    ========================================================= */

    .branch-login-body {
        padding: 32px;
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .branch-login-header {
        text-align: center;
        margin-bottom: 28px;
    }


    .branch-login-icon {

        width: 72px;
        height: 72px;

        margin: 0 auto 17px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 20px;

        color: #8b5e6d;

        background: #ffffff;

        border: 1px solid rgba(139, 94, 109, 0.12);

        box-shadow:
            0 5px 10px rgba(45, 37, 41, 0.05),
            0 12px 22px rgba(45, 37, 41, 0.08);

        transform: perspective(500px) rotateX(2deg);
    }


    .branch-login-icon i {
        font-size: 32px;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .branch-alert {

        display: flex;
        align-items: flex-start;
        gap: 10px;

        padding: 12px 14px;

        margin-bottom: 20px;

        border-radius: 12px;

        font-size: inherit;

        border: 1px solid rgba(220, 53, 69, 0.15);

        background: #fff7f8;

        color: #842029;

        box-shadow:
            0 3px 8px rgba(45, 37, 41, 0.04);
    }


    .branch-alert-icon {
        flex-shrink: 0;
        margin-top: 1px;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .branch-form-group {
        margin-bottom: 21px;
    }


    .branch-form-group .form-label {
        margin-bottom: 8px;
    }


    /* =========================================================
       INPUT WRAPPER
    ========================================================= */

    .branch-input-wrap {
        position: relative;
    }


    .branch-input {

        min-height: 46px;

        padding-left: 43px;
        padding-right: 42px;

        border-radius: 12px;

        border: 1px solid #e4e1e3;

        background: #ffffff;

        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.02),
            0 2px 5px rgba(45, 37, 41, 0.03);

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }


    .branch-input:hover {
        border-color: #d3c4ca;
    }


    .branch-input:focus {

        border-color: #8b5e6d;

        box-shadow:
            0 0 0 3px rgba(139, 94, 109, 0.10),
            0 5px 12px rgba(45, 37, 41, 0.06);

        transform: translateY(-1px);
    }


    .branch-input-icon {

        position: absolute;

        left: 14px;
        top: 50%;

        transform: translateY(-50%);

        color: #8b5e6d;

        z-index: 2;

        pointer-events: none;
    }


    /* =========================================================
       PASSWORD TOGGLE
    ========================================================= */

    .branch-password-toggle {

        position: absolute;

        right: 8px;
        top: 50%;

        transform: translateY(-50%);

        width: 34px;
        height: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: 0;
        border-radius: 9px;

        background: transparent;

        color: #777;

        cursor: pointer;

        transition:
            background 0.2s ease,
            color 0.2s ease;
    }


    .branch-password-toggle:hover {

        background: #f5f1f3;

        color: #8b5e6d;
    }


    /* =========================================================
       REMEMBER ME
    ========================================================= */

    .branch-remember-row {
        margin-bottom: 22px;
    }


    .branch-remember-row .form-check-input {

        cursor: pointer;

        border-color: #cfc8cb;
    }


    .branch-remember-row .form-check-input:checked {

        background-color: #8b5e6d;
        border-color: #8b5e6d;
    }


    .branch-remember-row .form-check-label {
        cursor: pointer;
    }


    /* =========================================================
       LOGIN BUTTON
    ========================================================= */

    .branch-login-btn {

        width: 100%;

        min-height: 48px;

        border: 0;

        border-radius: 12px;

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );

        box-shadow:
            0 5px 0 #62414d,
            0 10px 20px rgba(139, 94, 109, 0.20);

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        transition:
            transform 0.15s ease,
            box-shadow 0.15s ease,
            background 0.2s ease;
    }


    .branch-login-btn:hover {

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #966778,
            #7d5362
        );

        transform: translateY(-2px);

        box-shadow:
            0 7px 0 #62414d,
            0 14px 25px rgba(139, 94, 109, 0.22);
    }


    .branch-login-btn:active {

        transform: translateY(3px);

        box-shadow:
            0 2px 0 #62414d,
            0 5px 10px rgba(139, 94, 109, 0.15);
    }


    .branch-login-btn:disabled {

        opacity: 0.75;

        cursor: not-allowed;

        transform: none;

        box-shadow:
            0 3px 0 #62414d,
            0 6px 12px rgba(139, 94, 109, 0.12);
    }


    /* =========================================================
       BACK LINK
    ========================================================= */

    .branch-back-wrapper {

        text-align: center;

        margin-top: 23px;

        padding-top: 20px;

        border-top: 1px solid #f0edef;
    }


    .branch-back-link {

        display: inline-flex;

        align-items: center;

        gap: 7px;

        color: #6f6267;

        text-decoration: none;

        transition:
            color 0.2s ease,
            transform 0.2s ease;
    }


    .branch-back-link:hover {

        color: #8b5e6d;

        transform: translateX(-3px);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 575.98px) {

        .branch-login-page {
            padding-top: 20px;
            padding-bottom: 25px;
        }

        .branch-login-body {
            padding: 24px 20px;
        }

        .branch-login-card {
            border-radius: 18px;
        }

        .branch-login-icon {
            width: 64px;
            height: 64px;
        }

    }

</style>


{{-- =========================================================
    PASSWORD TOGGLE + FORM UX
========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const passwordInput = document.getElementById('branchLoginPassword');
    const toggleButton = document.getElementById('toggleBranchPassword');
    const loginForm = document.getElementById('branchLoginForm');
    const loginButton = document.getElementById('branchLoginButton');


    /* =========================================================
       PASSWORD SHOW / HIDE
    ========================================================= */

    if (passwordInput && toggleButton) {

        toggleButton.addEventListener('click', function () {

            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

                this.setAttribute(
                    'aria-label',
                    'Hide password'
                );

            } else {

                passwordInput.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

                this.setAttribute(
                    'aria-label',
                    'Show password'
                );
            }

        });

    }


    /* =========================================================
       LOGIN BUTTON LOADING
    ========================================================= */

    if (loginForm && loginButton) {

        loginForm.addEventListener('submit', function () {

            if (!loginForm.checkValidity()) {
                return;
            }

            loginButton.disabled = true;

            loginButton.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-2"
                    role="status"
                    aria-hidden="true"
                ></span>
                <span>Logging in...</span>
            `;

        });

    }

});

</script>

@endpush

@endsection