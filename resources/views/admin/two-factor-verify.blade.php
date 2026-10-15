
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>2FA Verification</title>
<link rel="icon" type="image/png" href="{{ asset('images/faviconn.png') }}">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-body p-4">

                    <div class="text-center">

                        <h3>
                            🔐 2FA Verification
                        </h3>

                        <p class="text-muted">

                            Enter the 6-digit code
                            from Google Authenticator.

                        </p>

                    </div>


                    @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('admin.2fa.verify.submit') }}"
                    >

                        @csrf


                        <div class="mb-3">

                            <label class="form-label">
                                Authentication Code
                            </label>

                            <input
                                type="text"
                                name="otp"
                                class="form-control text-center"
                                maxlength="6"
                                minlength="6"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                placeholder="000000"
                                required
                                autofocus
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            Verify & Continue

                        </button>

                    </form>


                    <div class="text-center mt-3">

                        <form
                            method="POST"
                            action="{{ route('admin.logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-link text-danger"
                            >

                                Logout

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>

