<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Setup 2FA | Salon Management</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f1f3;
        }

        .setup-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }

        .setup-header {
            background: #2d2529;
            color: #fff;
            padding: 20px;
        }

        .qr-wrapper {
            display: inline-flex;
            padding: 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
        }

        .secret-box {
            background: #f8f9fa;
            border: 1px dashed #8b5e6d;
            border-radius: 8px;
            padding: 12px;
            word-break: break-all;
        }

        .btn-enable {
            background: #8b5e6d;
            border-color: #8b5e6d;
            color: #fff;
        }

        .btn-enable:hover {
            background: #744b5b;
            border-color: #744b5b;
            color: #fff;
        }

        .otp-input {
            font-size: 24px;
            letter-spacing: 8px;
            font-weight: 600;
        }

    </style>

</head>


<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div class="card shadow setup-card">

                {{-- HEADER --}}

                <div class="setup-header text-center">

                    <h4 class="mb-1">

                        <i class="bi bi-shield-lock"></i>

                        Setup Two-Factor Authentication

                    </h4>

                    <small>

                        Secure your Salon Management account

                    </small>

                </div>


                {{-- BODY --}}

                <div class="card-body p-4 text-center">


                    {{-- SUCCESS MESSAGE --}}

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif


                    {{-- ERROR MESSAGE --}}

                    @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                    @endif


                    {{-- VALIDATION ERRORS --}}

                    @if($errors->any())

                        <div class="alert alert-danger text-start">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <p class="mb-4">

                        Open
                        <strong>Google Authenticator</strong>
                        on your mobile and scan the QR code below.

                    </p>


                    {{-- QR CODE --}}

                    <div class="my-4">

                        <div class="qr-wrapper">

                            <img
                                src="https://quickchart.io/qr?text={{ urlencode($qrCodeUrl) }}&size=250"
                                alt="2FA QR Code"
                                width="250"
                                height="250"
                            >

                        </div>

                    </div>


                    {{-- SECRET --}}

                    <p class="text-muted mb-2">

                        Can't scan the QR code?

                    </p>

                    <p class="small text-muted">

                        Enter this secret manually in Google Authenticator.

                    </p>


                    <div class="secret-box mb-4">

                        <strong class="text-dark">

                            {{ $secret }}

                        </strong>

                    </div>


                    <hr class="my-4">


                    {{-- VERIFY --}}

                    <h5 class="mb-2">

                        Verify Your Setup

                    </h5>

                    <p class="text-muted">

                        Enter the 6-digit code currently shown
                        in Google Authenticator.

                    </p>


                    <form
                        method="POST"
                        action="{{ route('admin.2fa.enable') }}"
                    >

                        @csrf


                        <div class="mb-3">

                            <input
                                type="text"
                                name="otp"
                                class="form-control text-center otp-input"
                                maxlength="6"
                                minlength="6"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                autocomplete="one-time-code"
                                placeholder="000000"
                                required
                                autofocus
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-enable w-100 py-2"
                        >

                            Verify & Enable 2FA

                        </button>

                    </form>


                    {{-- BACK --}}

                    <a
                        href="{{ route('admin.2fa') }}"
                        class="btn btn-link mt-3"
                    >

                        ← Back to 2FA Settings

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | OTP Input - Numbers Only
    |--------------------------------------------------------------------------
    */

    const otpInput = document.querySelector('input[name="otp"]');

    if (otpInput) {

        otpInput.addEventListener('input', function () {

            this.value = this.value
                .replace(/\D/g, '')
                .slice(0, 6);

        });

    }

</script>

</body>

</html>