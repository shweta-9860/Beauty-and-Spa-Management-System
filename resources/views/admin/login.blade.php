
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | Salon Management</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
        }

        body {
            min-height: 100vh;

            font-family:
                Inter,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: #f8f5f7;

            color: #2c2529;
        }


        /* =====================================================
           MAIN PAGE
        ====================================================== */

        .login-page {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 25px;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(214, 51, 132, 0.10),
                    transparent 30%
                ),
                #f8f5f7;
        }


        /* =====================================================
           LOGIN CONTAINER
        ====================================================== */

        .login-container {

            width: 100%;
            max-width: 1180px;

            min-height: 690px;

            display: grid;

            grid-template-columns: 1.15fr 0.85fr;

            background: #ffffff;

            border-radius: 28px;

            overflow: hidden;

            box-shadow:
                0 30px 80px rgba(48, 34, 42, 0.14),
                0 10px 30px rgba(214, 51, 132, 0.06);

            border: 1px solid #eee4e9;
        }


        /* =====================================================
           LEFT IMAGE SECTION
        ====================================================== */

        .image-section {

            position: relative;

            min-height: 690px;

            background-image:
                linear-gradient(
                    135deg,
                    rgba(36, 24, 30, 0.35),
                    rgba(214, 51, 132, 0.28)
                ),
                url("https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=90");

            background-size: cover;

            background-position: center;

            display: flex;

            align-items: flex-end;

            padding: 48px;
        }


        /* Image overlay */

        .image-overlay {

            position: absolute;

            inset: 0;

            background:
                linear-gradient(
                    to top,
                    rgba(30, 20, 25, 0.78),
                    rgba(30, 20, 25, 0.12) 65%,
                    rgba(214, 51, 132, 0.08)
                );
        }


        /* =====================================================
           IMAGE CONTENT
        ====================================================== */

        .image-content {

            position: relative;

            z-index: 2;

            color: #ffffff;

            max-width: 500px;
        }


        .brand-small {

            display: inline-flex;

            align-items: center;

            gap: 9px;

            padding: 8px 14px;

            border-radius: 50px;

            background:
                rgba(255, 255, 255, 0.13);

            border:
                1px solid rgba(255, 255, 255, 0.22);

            backdrop-filter: blur(8px);

            font-size: 12px;

            font-weight: 600;

            letter-spacing: 0.6px;

            margin-bottom: 20px;
        }


        .brand-small i {

            color: #ffb5d5;

            font-size: 15px;
        }


        .image-content h1 {

            margin: 0;

            font-size: 42px;

            line-height: 1.15;

            font-weight: 800;

            letter-spacing: -1px;
        }


        .image-content h1 span {

            color: #ff9fc8;
        }


        .image-content p {

            margin: 18px 0 0;

            max-width: 430px;

            font-size: 15px;

            line-height: 1.7;

            color: rgba(255, 255, 255, 0.86);
        }


        /* =====================================================
           FEATURE BADGES
        ====================================================== */

        .features {

            display: flex;

            flex-wrap: wrap;

            gap: 10px;

            margin-top: 25px;
        }


        .feature {

            display: flex;

            align-items: center;

            gap: 7px;

            padding: 8px 12px;

            border-radius: 8px;

            background:
                rgba(255, 255, 255, 0.10);

            border:
                1px solid rgba(255, 255, 255, 0.16);

            backdrop-filter: blur(6px);

            font-size: 11px;

            color: rgba(255, 255, 255, 0.92);
        }


        .feature i {

            color: #ffacd0;
        }


        /* =====================================================
           RIGHT LOGIN SECTION
        ====================================================== */

        .form-section {

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 55px 60px;

            background: #ffffff;
        }


        .login-form-wrapper {

            width: 100%;

            max-width: 390px;
        }


        /* =====================================================
           FORM BRAND
        ====================================================== */

        .form-brand {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 35px;
        }


        .form-brand-icon {

            width: 46px;

            height: 46px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 13px;

            background:
                linear-gradient(
                    135deg,
                    #d63384,
                    #b82167
                );

            color: #ffffff;

            font-size: 21px;

            box-shadow:
                0 8px 20px rgba(214, 51, 132, 0.20);
        }


        .form-brand-text strong {

            display: block;

            font-size: 16px;

            color: #30282c;

            font-weight: 800;
        }


        .form-brand-text small {

            display: block;

            margin-top: 2px;

            color: #9b8f95;

            font-size: 10px;

            letter-spacing: 0.4px;
        }


        /* =====================================================
           HEADING
        ====================================================== */

        .login-heading {

            margin-bottom: 28px;
        }


        .login-heading h2 {

            margin: 0;

            font-size: 30px;

            font-weight: 800;

            color: #2c2529;

            letter-spacing: -0.7px;
        }


        .login-heading p {

            margin: 8px 0 0;

            color: #91868c;

            font-size: 13px;

            line-height: 1.6;
        }


        /* =====================================================
           ALERTS
        ====================================================== */

        .alert {

            border: 0;

            border-radius: 11px;

            font-size: 12px;

            margin-bottom: 20px;
        }


        .alert-danger {

            background: #fff0f2;

            color: #a32943;
        }


        .alert-success {

            background: #edf9f1;

            color: #26733f;
        }


        /* =====================================================
           FORM GROUP
        ====================================================== */

        .form-group {

            margin-bottom: 20px;
        }


        .form-label {

            display: block;

            margin-bottom: 8px;

            font-size: 12px;

            font-weight: 700;

            color: #40363b;
        }


        .input-wrapper {

            position: relative;
        }


        .input-icon {

            position: absolute;

            left: 15px;

            top: 50%;

            transform: translateY(-50%);

            color: #aaa0a6;

            font-size: 16px;

            z-index: 2;

            transition: 0.2s;
        }


        .form-control {

            width: 100%;

            height: 52px;

            padding:
                10px 45px;

            border:
                1px solid #e2dce0;

            border-radius: 11px;

            background: #ffffff;

            color: #2e272b;

            font-size: 13px;

            transition:
                0.2s ease;

            box-shadow: none;
        }


        .form-control::placeholder {

            color: #aaa1a6;
        }


        .form-control:hover {

            border-color: #d7cdd2;
        }


        .form-control:focus {

            border-color: #d63384;

            box-shadow:
                0 0 0 4px rgba(214, 51, 132, 0.08);
        }


        .input-wrapper:focus-within .input-icon {

            color: #d63384;
        }


        /* =====================================================
           PASSWORD TOGGLE
        ====================================================== */

        .password-toggle {

            position: absolute;

            right: 10px;

            top: 50%;

            transform: translateY(-50%);

            width: 34px;

            height: 34px;

            display: flex;

            align-items: center;

            justify-content: center;

            border: 0;

            border-radius: 7px;

            background: transparent;

            color: #9c9297;

            cursor: pointer;
        }


        .password-toggle:hover {

            color: #d63384;

            background: #fff1f7;
        }


        /* =====================================================
           REMEMBER
        ====================================================== */

        .remember-row {

            display: flex;

            align-items: center;

            margin-bottom: 25px;
        }


        .remember-box {

            display: flex;

            align-items: center;

            gap: 8px;
        }


        .remember-box input {

            width: 16px;

            height: 16px;

            cursor: pointer;
        }


        .remember-box input:checked {

            background-color: #d63384;

            border-color: #d63384;
        }


        .remember-box label {

            color: #776c72;

            font-size: 12px;

            cursor: pointer;
        }


        /* =====================================================
           LOGIN BUTTON
        ====================================================== */

        .login-btn {

            width: 100%;

            height: 52px;

            border: 0;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    #d63384,
                    #bd2168
                );

            color: #ffffff;

            font-size: 13px;

            font-weight: 700;

            letter-spacing: 0.15px;

            box-shadow:
                0 10px 25px rgba(214, 51, 132, 0.22);

            cursor: pointer;

            transition:
                0.2s ease;
        }


        .login-btn:hover {

            transform: translateY(-1px);

            background:
                linear-gradient(
                    135deg,
                    #c22570,
                    #a91d5b
                );

            box-shadow:
                0 14px 30px rgba(214, 51, 132, 0.28);
        }


        .login-btn:active {

            transform: translateY(0);
        }


        /* =====================================================
           SECURITY
        ====================================================== */

        .security-note {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            margin-top: 23px;

            padding: 11px;

            border-radius: 9px;

            background: #faf8f9;

            border: 1px solid #eee7ea;

            color: #81767b;

            font-size: 10.5px;
        }


        .security-note i {

            color: #d63384;

            font-size: 15px;
        }


        /* =====================================================
           COPYRIGHT
        ====================================================== */

        .copyright {

            text-align: center;

            margin-top: 22px;

            color: #b0a6ab;

            font-size: 10px;

            line-height: 1.7;
        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 900px) {

            .login-container {

                grid-template-columns: 1fr;

                max-width: 500px;

                min-height: auto;
            }


            .image-section {

                min-height: 350px;

                padding: 35px;
            }


            .image-content h1 {

                font-size: 32px;
            }


            .form-section {

                padding: 45px 35px;
            }

        }


        @media (max-width: 576px) {

            .login-page {

                padding: 0;
            }


            .login-container {

                min-height: 100vh;

                border-radius: 0;

                border: 0;

                box-shadow: none;
            }


            .image-section {

                min-height: 300px;

                padding: 28px 24px;
            }


            .image-content h1 {

                font-size: 28px;
            }


            .image-content p {

                font-size: 12px;
            }


            .features {

                display: none;
            }


            .form-section {

                padding: 38px 24px;
            }


            .login-heading h2 {

                font-size: 26px;
            }

        }

    </style>

</head>


<body>


<div class="login-page">


    <div class="login-container">


        <!-- =================================================
             LEFT IMAGE
        ================================================== -->

        <div class="image-section">

            <div class="image-overlay"></div>


            <div class="image-content">

                <div class="brand-small">

                    
                </div>


                <h1>

                    Manage your salon.

                    <br>

                    <span>Elevate your business.</span>

                </h1>


                <p>

                    Everything you need to manage appointments,
                    customers, billing, staff, inventory and
                    daily salon operations in one place.

                </p>


                <div class="features">

                    <div class="feature">

                        <i class="bi bi-calendar-check"></i>

                        Appointments

                    </div>


                    <div class="feature">

                        <i class="bi bi-receipt"></i>

                        Smart Billing

                    </div>


                    <div class="feature">

                        <i class="bi bi-people"></i>

                        Customers

                    </div>


                    <div class="feature">

                        <i class="bi bi-bar-chart"></i>

                        Reports

                    </div>

                </div>

            </div>

        </div>


        <!-- =================================================
             RIGHT LOGIN
        ================================================== -->

        <div class="form-section">


            <div class="login-form-wrapper">


                <!-- Brand -->

                <div class="form-brand">

                    <div class="form-brand-icon">

                        <i class="bi bi-scissors"></i>

                    </div>


                    <div class="form-brand-text">

                        <strong>
                            Salon Management
                        </strong>

                        <small>
                            ADMINISTRATION PORTAL
                        </small>

                    </div>

                </div>


                <!-- Heading -->

                <div class="login-heading">

                    <h2>
                        Welcome back
                    </h2>

                    <p>
                        Sign in to access your salon administration panel.
                    </p>

                </div>


                <!-- =================================================
                     ALERTS
                ================================================== -->

                @if(session('error'))

                    <div
                        class="alert alert-danger alert-dismissible fade show"
                        role="alert"
                    >

                        <i class="bi bi-exclamation-circle me-2"></i>

                        {{ session('error') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                        ></button>

                    </div>

                @endif


                @if(session('success'))

                    <div
                        class="alert alert-success alert-dismissible fade show"
                        role="alert"
                    >

                        <i class="bi bi-check-circle me-2"></i>

                        {{ session('success') }}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                        ></button>

                    </div>

                @endif


                @if($errors->any())

                    <div class="alert alert-danger">

                        <div class="fw-semibold">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            Please check your login details.

                        </div>


                        <ul class="mb-0 ps-4">

                            @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <!-- =================================================
                     LOGIN FORM
                ================================================== -->

                <form
                    method="POST"
                    action="{{ route('admin.login.submit') }}"
                    id="loginForm"
                >

                    @csrf


                    <!-- Email -->

                    <div class="form-group">

                        <label
                            for="email"
                            class="form-label"
                        >

                            Email Address

                        </label>


                        <div class="input-wrapper">

                            <i
                                class="bi bi-envelope input-icon"
                            ></i>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="Enter your email address"
                                autocomplete="email"
                                required
                                autofocus
                            >

                        </div>

                    </div>


                    <!-- Password -->

                    <div class="form-group">

                        <label
                            for="password"
                            class="form-label"
                        >

                            Password

                        </label>


                        <div class="input-wrapper">

                            <i
                                class="bi bi-lock input-icon"
                            ></i>


                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="password-toggle"
                                id="togglePassword"
                                aria-label="Show password"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>


                    <!-- Remember -->

                    <div class="remember-row">

                        <div class="remember-box">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                id="remember"
                            >

                            <label for="remember">

                                Remember me

                            </label>

                        </div>

                    </div>


                    <!-- Login -->

                    <button
                        type="submit"
                        class="login-btn"
                        id="loginButton"
                    >

                        <i class="bi bi-box-arrow-in-right me-2"></i>

                        Sign In to Admin Panel

                    </button>

                </form>


                <!-- Security -->

                <div class="security-note">

                    <i class="bi bi-shield-check"></i>

                    Secure access for authorized salon users only.

                </div>


                <!-- Copyright -->

                <div class="copyright">

                    © {{ date('Y') }} Salon Management System

                    <br>

                    All rights reserved.

                </div>


            </div>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /* ============================================
           PASSWORD TOGGLE
        ============================================= */

        const password =
            document.getElementById('password');

        const toggle =
            document.getElementById('togglePassword');


        if (password && toggle) {

            toggle.addEventListener(
                'click',
                function () {

                    const icon =
                        this.querySelector('i');


                    if (
                        password.type === 'password'
                    ) {

                        password.type = 'text';

                        icon.classList.remove(
                            'bi-eye'
                        );

                        icon.classList.add(
                            'bi-eye-slash'
                        );

                        this.setAttribute(
                            'aria-label',
                            'Hide password'
                        );

                    } else {

                        password.type = 'password';

                        icon.classList.remove(
                            'bi-eye-slash'
                        );

                        icon.classList.add(
                            'bi-eye'
                        );

                        this.setAttribute(
                            'aria-label',
                            'Show password'
                        );

                    }

                }
            );

        }


        /* ============================================
           LOGIN BUTTON LOADING
        ============================================= */

        const form =
            document.getElementById('loginForm');

        const button =
            document.getElementById('loginButton');


        if (form && button) {

            form.addEventListener(
                'submit',
                function () {

                    if (!form.checkValidity()) {

                        return;

                    }


                    button.innerHTML = `

                        <span
                            class="spinner-border spinner-border-sm me-2"
                            role="status"
                            aria-hidden="true"
                        ></span>

                        Signing in...

                    `;

                    button.style.pointerEvents =
                        'none';

                    button.style.opacity =
                        '0.85';

                }
            );

        }

    }
);

</script>


</body>

</html>
