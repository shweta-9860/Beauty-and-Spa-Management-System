<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Two-Factor Authentication | Salon Management</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {
            background: #f5f1f3;
        }

        .twofa-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .twofa-header {
            background: #2d2529;
            color: #fff;
            padding: 20px;
        }

        .status-icon {
            font-size: 45px;
            margin-bottom: 10px;
        }

        .setup-btn {
            background: #8b5e6d;
            border-color: #8b5e6d;
            color: #fff;
        }

        .setup-btn:hover {
            background: #744b5b;
            border-color: #744b5b;
            color: #fff;
        }

    </style>

</head>


<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7 col-md-9">

            <div class="card shadow twofa-card">


                {{-- HEADER --}}

                <div class="twofa-header text-center">

                    <h4 class="mb-1">

                        <i class="bi bi-shield-lock me-2"></i>

                        Two-Factor Authentication

                    </h4>

                    <small>

                        Secure your Salon Management account

                    </small>

                </div>


                {{-- BODY --}}

                <div class="card-body p-4">


                    {{-- SUCCESS MESSAGE --}}

                    @if(session('success'))

                        <div class="alert alert-success">

                            <i class="bi bi-check-circle me-2"></i>

                            {{ session('success') }}

                        </div>

                    @endif


                    {{-- ERROR MESSAGE --}}

                    @if(session('error'))

                        <div class="alert alert-danger">

                            <i class="bi bi-exclamation-triangle me-2"></i>

                            {{ session('error') }}

                        </div>

                    @endif


                    {{-- VALIDATION ERRORS --}}

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- =====================================================
                        2FA ENABLED
                    ====================================================== --}}

                    @if($user->two_factor_enabled)

                        <div class="text-center py-3">

                            <div class="status-icon text-success">

                                <i class="bi bi-shield-check"></i>

                            </div>

                            <h4 class="text-success">

                                2FA is Enabled

                            </h4>

                            <p class="text-muted mb-4">

                                Your account is protected with
                                Google Authenticator.

                            </p>


                            {{-- DISABLE BUTTON --}}

                            <form
                                method="POST"
                                action="{{ route('admin.2fa.disable') }}"
                                class="d-inline"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-danger px-4"
                                    onclick="return confirm('Are you sure you want to disable 2FA? Your current authenticator secret will be removed.')"
                                >

                                    <i class="bi bi-shield-x me-1"></i>

                                    Disable 2FA

                                </button>

                            </form>

                        </div>


                    {{-- =====================================================
                        2FA DISABLED
                    ====================================================== --}}

                    @else

                        <div class="text-center py-3">

                            <div class="status-icon text-warning">

                                <i class="bi bi-shield-exclamation"></i>

                            </div>

                            <h4 class="text-warning">

                                2FA is Disabled

                            </h4>

                            <p class="text-muted">

                                Two-Factor Authentication is currently
                                disabled for your account.

                            </p>

                        </div>


                        <div class="alert alert-info">

                            <i class="bi bi-info-circle me-2"></i>

                            <strong>Setup 2FA again</strong>

                            <br>

                            Click the button below to generate a
                            <strong>new QR code</strong>.

                            You can scan the new QR code using
                            Google Authenticator.

                        </div>


                        {{-- SETUP BUTTON --}}

                        <div class="text-center mt-4">

                            <a
                                href="{{ route('admin.2fa.setup') }}"
                                class="btn setup-btn px-4 py-2"
                            >

                                <i class="bi bi-qr-code-scan me-1"></i>

                                Setup 2FA & Generate QR Code

                            </a>

                        </div>

                    @endif


                    {{-- =====================================================
                        DASHBOARD
                    ====================================================== --}}

                    <div class="text-center mt-4 pt-3 border-top">

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-speedometer2 me-1"></i>

                            Go to Dashboard

                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>