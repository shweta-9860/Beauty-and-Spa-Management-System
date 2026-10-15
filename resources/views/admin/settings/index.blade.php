@extends('admin.layout')

@section('title', 'Settings | Salon Management')

@section('page-title', 'Settings')

@section('content')

<div class="container-fluid settings-page">

    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <strong>
                <i class="bi bi-exclamation-circle me-1"></i>
                Please fix the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row g-4">

        {{-- =====================================================
            LEFT SETTINGS MENU
        ====================================================== --}}

        <div class="col-lg-3">

            <div class="card shadow-sm settings-menu-card">

                <div class="card-body p-2">

                    <div
                        class="nav flex-column nav-pills"
                        id="settingsTabs"
                        role="tablist">

                        {{-- PROFILE --}}

                        <button
                            class="nav-link active text-start"
                            data-bs-toggle="pill"
                            data-bs-target="#profileTab"
                            type="button">

                            <i class="bi bi-building me-2"></i>

                            Profile

                        </button>


                        {{-- PASSWORD --}}

                        <button
                            class="nav-link text-start"
                            data-bs-toggle="pill"
                            data-bs-target="#passwordTab"
                            type="button">

                            <i class="bi bi-key me-2"></i>

                            Change Password

                        </button>


                        {{-- SECURITY --}}

                        <button
                            class="nav-link text-start"
                            data-bs-toggle="pill"
                            data-bs-target="#securityTab"
                            type="button">

                            <i class="bi bi-shield-lock me-2"></i>

                            Security

                        </button>

                    </div>

                </div>

            </div>


            {{-- =================================================
                ACCOUNT INFORMATION
            ================================================== --}}

            <div class="card shadow-sm mt-3">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">

                        <i class="bi bi-person-badge me-2"></i>

                        Account Information

                    </h6>


                    <div class="account-info-row">

                        <span>Account Name</span>

                        <strong>
                            {{ $user->name }}
                        </strong>

                    </div>


                    <div class="account-info-row">

                        <span>Email</span>

                        <strong class="text-break">
                            {{ $user->email }}
                        </strong>

                    </div>


                    @if(isset($user->role))

                        <div class="account-info-row">

                            <span>Role</span>

                            <strong>
                                {{ ucfirst($user->role) }}
                            </strong>

                        </div>

                    @endif


                    <div class="account-info-row">

                        <span>2FA</span>

                        @if($user->two_factor_enabled)

                            <span class="badge bg-success">
                                Enabled
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Disabled
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT CONTENT
        ====================================================== --}}

        <div class="col-lg-9">

            <div class="tab-content">


                {{-- =================================================
                    PROFILE TAB
                ================================================== --}}

                <div
                    class="tab-pane fade show active"
                    id="profileTab">

                    <form
                        method="POST"
                        action="{{ route('admin.settings.profile.update') }}"
                        enctype="multipart/form-data">

                        @csrf
                        @method('PUT')


                        {{-- =================================================
                            SALON HEADER
                        ================================================== --}}

                        <div class="card shadow-sm mb-4">

                            <div class="card-body">

                                <div class="row g-4 align-items-center">


                                    {{-- ==========================
                                        LOGO
                                    =========================== --}}

                                    <div class="col-md-6">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="salon-logo-wrapper">

                                                @if($salon->logo)

                                                    <img
                                                        id="salonLogoPreview"
                                                        src="{{ asset('storage/' . $salon->logo) }}"
                                                        alt="Salon Logo"
                                                        class="salon-logo">

                                                @else

                                                    <div
                                                        id="salonLogoPlaceholder"
                                                        class="salon-logo-placeholder">

                                                        <i class="bi bi-scissors"></i>

                                                    </div>

                                                @endif

                                            </div>


                                            <div>

                                                <h4 class="mb-1 fw-bold">
                                                    {{ $salon->name }}
                                                </h4>

                                                <p class="text-muted mb-2">
                                                    Manage your salon and account profile.
                                                </p>


                                                <label
                                                    for="logo"
                                                    class="btn btn-sm btn-outline-primary">

                                                    <i class="bi bi-upload me-1"></i>

                                                    Change Logo

                                                </label>


                                                <input
                                                    type="file"
                                                    name="logo"
                                                    id="logo"
                                                    class="d-none"
                                                    accept=".jpg,.jpeg,.png,.webp">


                                                <div class="small text-muted mt-1">
                                                    JPG, PNG, WEBP · Max 2 MB
                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    {{-- ==========================
                                        AUTHORIZED SIGNATURE
                                    =========================== --}}

                                    <div class="col-md-6">

                                        <div class="signature-upload-box">

                                            <div class="signature-preview-wrapper">

                                                @if($salon->authorized_signature)

                                                    <img
                                                        id="signaturePreview"
                                                        src="{{ asset('storage/' . $salon->authorized_signature) }}"
                                                        alt="Authorized Signature"
                                                        class="signature-preview">

                                                @else

                                                    <div
                                                        id="signaturePlaceholder"
                                                        class="signature-placeholder">

                                                        <i class="bi bi-pen"></i>

                                                        <span>
                                                            No signature uploaded
                                                        </span>

                                                    </div>

                                                    <img
                                                        id="signaturePreview"
                                                        src=""
                                                        alt="Signature Preview"
                                                        class="signature-preview"
                                                        style="display:none;">

                                                @endif

                                            </div>


                                            <div class="signature-upload-content">

                                                <h6 class="fw-bold mb-1">

                                                    <i class="bi bi-pen me-1"></i>

                                                    Authorized Signature

                                                </h6>

                                                <p class="text-muted small mb-2">
                                                    Upload signature for invoices and receipts.
                                                </p>


                                                <label
                                                    for="authorized_signature"
                                                    class="btn btn-sm btn-outline-primary">

                                                    <i class="bi bi-upload me-1"></i>

                                                    {{ $salon->authorized_signature
                                                        ? 'Change Signature'
                                                        : 'Upload Signature' }}

                                                </label>


                                                <input
                                                    type="file"
                                                    name="authorized_signature"
                                                    id="authorized_signature"
                                                    class="d-none"
                                                    accept=".jpg,.jpeg,.png,.webp">


                                                <div class="small text-muted mt-1">
                                                    JPG, PNG, WEBP · Max 2 MB
                                                </div>


                                                @error('authorized_signature')

                                                    <div class="text-danger small mt-1">
                                                        {{ $message }}
                                                    </div>

                                                @enderror

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            PERSONAL INFORMATION
                        ================================================== --}}

                        <div class="card shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-1">

                                    <i class="bi bi-person me-2"></i>

                                    Account Profile

                                </h5>

                                <small class="text-muted">
                                    Your login and contact information.
                                </small>

                            </div>


                            <div class="card-body">

                                <div class="row g-3">


                                    {{-- NAME --}}

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Owner / Admin Name
                                        </label>

                                        <input
                                            type="text"
                                            name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $user->name) }}"
                                            maxlength="255"
                                            required>

                                        @error('name')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    {{-- LOGIN EMAIL --}}

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Login Email
                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}"
                                            maxlength="255"
                                            placeholder="example@email.com"
                                            required>

                                        @error('email')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    {{-- PHONE --}}

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Phone
                                        </label>

                                        <input
                                            type="tel"
                                            name="phone"
                                            id="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $salon->phone ?? $user->phone ?? '') }}"
                                            maxlength="10"
                                            minlength="10"
                                            pattern="[6-9][0-9]{9}"
                                            inputmode="numeric"
                                            placeholder="Enter 10-digit mobile number"
                                            required>

                                        @error('phone')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                        <small class="text-muted">
                                            Enter a valid 10-digit Indian mobile number.
                                        </small>

                                    </div>


                                    {{-- SALON EMAIL --}}

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Salon Email
                                        </label>

                                        <input
                                            type="email"
                                            name="salon_email"
                                            id="salon_email"
                                            class="form-control @error('salon_email') is-invalid @enderror"
                                            value="{{ old('salon_email', $salon->email) }}"
                                            maxlength="255"
                                            placeholder="salon@example.com">

                                        @error('salon_email')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            SALON INFORMATION
                        ================================================== --}}

                        <div class="card shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-1">

                                    <i class="bi bi-building me-2"></i>

                                    Salon Information

                                </h5>

                                <small class="text-muted">
                                    Basic information about your salon.
                                </small>

                            </div>


                            <div class="card-body">

                                <div class="row g-3">


                                    {{-- SALON NAME --}}

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Salon Name
                                        </label>

                                        <input
                                            type="text"
                                            name="salon_name"
                                            class="form-control @error('salon_name') is-invalid @enderror"
                                            value="{{ old('salon_name', $salon->name) }}"
                                            maxlength="255"
                                            required>

                                        @error('salon_name')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    {{-- GST --}}

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            GST Number
                                        </label>

                                        <input
                                            type="text"
                                            name="gst_number"
                                            class="form-control @error('gst_number') is-invalid @enderror"
                                            value="{{ old('gst_number', $salon->gst_number) }}">

                                        @error('gst_number')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>


                                    {{-- ADDRESS --}}

                                    <div class="col-12">

                                        <label class="form-label fw-semibold">
                                            Address
                                        </label>

                                        <textarea
                                            name="address"
                                            rows="3"
                                            class="form-control @error('address') is-invalid @enderror">{{ old('address', $salon->address) }}</textarea>

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
                                            value="{{ old('city', $salon->city) }}"
                                            maxlength="100">

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
                                            value="{{ old('state', $salon->state) }}"
                                            maxlength="100">

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
                                            id="pincode"
                                            class="form-control @error('pincode') is-invalid @enderror"
                                            value="{{ old('pincode', $salon->pincode) }}"
                                            maxlength="6"
                                            minlength="6"
                                            pattern="[0-9]{6}"
                                            inputmode="numeric"
                                            placeholder="6-digit pincode">

                                        @error('pincode')

                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            BUSINESS SETTINGS
                        ================================================== --}}

                        <div class="card shadow-sm mb-4">

                            <div class="card-header bg-white">

                                <h5 class="mb-1">

                                    <i class="bi bi-sliders me-2"></i>

                                    Business Settings

                                </h5>

                                <small class="text-muted">
                                    Configure tax, invoice and working hours.
                                </small>

                            </div>


                            <div class="card-body">

                                <div class="row g-3">


                                    {{-- CURRENCY --}}

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Currency
                                        </label>

                                        @php
                                            $currency = old(
                                                'currency',
                                                $salon->currency ?? 'INR'
                                            );
                                        @endphp

                                        <select
                                            name="currency"
                                            class="form-select"
                                            required>

                                            <option
                                                value="INR"
                                                {{ $currency == 'INR' ? 'selected' : '' }}>
                                                INR - ₹ Indian Rupee
                                            </option>

                                            <option
                                                value="USD"
                                                {{ $currency == 'USD' ? 'selected' : '' }}>
                                                USD - $ US Dollar
                                            </option>

                                            <option
                                                value="EUR"
                                                {{ $currency == 'EUR' ? 'selected' : '' }}>
                                                EUR - € Euro
                                            </option>

                                            <option
                                                value="GBP"
                                                {{ $currency == 'GBP' ? 'selected' : '' }}>
                                                GBP - £ British Pound
                                            </option>

                                        </select>

                                    </div>


                                    {{-- TAX --}}

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Default Tax (%)
                                        </label>

                                        <div class="input-group">

                                            <input
                                                type="number"
                                                name="tax_percentage"
                                                class="form-control"
                                                min="0"
                                                max="100"
                                                step="0.01"
                                                value="{{ old('tax_percentage', $salon->tax_percentage ?? 0) }}"
                                                required>

                                            <span class="input-group-text">
                                                %
                                            </span>

                                        </div>

                                    </div>


                                    {{-- INVOICE PREFIX --}}

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Invoice Prefix
                                        </label>

                                        <input
                                            type="text"
                                            name="invoice_prefix"
                                            class="form-control"
                                            value="{{ old('invoice_prefix', $salon->invoice_prefix ?? 'INV') }}"
                                            maxlength="20"
                                            required>

                                    </div>


                                    {{-- OPENING TIME --}}

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Opening Time
                                        </label>

                                        <input
                                            type="time"
                                            name="opening_time"
                                            class="form-control"
                                            value="{{ old(
                                                'opening_time',
                                                $salon->opening_time
                                                    ? \Carbon\Carbon::parse($salon->opening_time)->format('H:i')
                                                    : ''
                                            ) }}">

                                    </div>


                                    {{-- CLOSING TIME --}}

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Closing Time
                                        </label>

                                        <input
                                            type="time"
                                            name="closing_time"
                                            class="form-control"
                                            value="{{ old(
                                                'closing_time',
                                                $salon->closing_time
                                                    ? \Carbon\Carbon::parse($salon->closing_time)->format('H:i')
                                                    : ''
                                            ) }}">

                                    </div>


                                    {{-- APPOINTMENT DURATION --}}

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Default Appointment Duration
                                        </label>

                                        <div class="input-group">

                                            <input
                                                type="number"
                                                name="default_appointment_duration"
                                                class="form-control"
                                                min="5"
                                                max="1440"
                                                value="{{ old(
                                                    'default_appointment_duration',
                                                    $salon->default_appointment_duration ?? 30
                                                ) }}"
                                                required>

                                            <span class="input-group-text">
                                                minutes
                                            </span>

                                        </div>

                                    </div>


                                    {{-- RECEIPT FOOTER --}}

                                    <div class="col-12">

                                        <label class="form-label fw-semibold">
                                            Receipt Footer
                                        </label>

                                        <textarea
                                            name="receipt_footer"
                                            rows="3"
                                            class="form-control"
                                            placeholder="Thank you for visiting us!">{{ old(
                                                'receipt_footer',
                                                $salon->receipt_footer
                                            ) }}</textarea>

                                        <small class="text-muted">
                                            This text can be displayed at the bottom of invoices and receipts.
                                        </small>

                                    </div>


                                    {{-- STATUS --}}

                                    <div class="col-12">

                                        <div class="form-check form-switch">

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="0">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="status"
                                                value="1"
                                                id="salonStatus"
                                                {{ $salon->status ? 'checked' : '' }}>

                                            <label
                                                class="form-check-label fw-semibold"
                                                for="salonStatus">

                                                Salon Active

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                            SAVE PROFILE
                        ================================================== --}}

                        <div class="card shadow-sm">

                            <div class="card-footer bg-white text-end">

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4">

                                    <i class="bi bi-save me-1"></i>

                                    Save Profile Changes

                                </button>

                            </div>

                        </div>

                    </form>

                </div>


                {{-- =================================================
                    PASSWORD TAB
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="passwordTab">

                    <div class="card shadow-sm">

                        <div class="card-header bg-white">

                            <h5 class="mb-1">

                                <i class="bi bi-key me-2"></i>

                                Change Password

                            </h5>

                            <small class="text-muted">
                                Keep your account secure with a strong password.
                            </small>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('admin.settings.password.update') }}">

                            @csrf
                            @method('PUT')


                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-8">


                                        {{-- CURRENT PASSWORD --}}

                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                Current Password
                                            </label>

                                            <input
                                                type="password"
                                                name="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                required>

                                            @error('current_password')

                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>

                                            @enderror

                                        </div>


                                        {{-- NEW PASSWORD --}}

                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                New Password
                                            </label>

                                            <input
                                                type="password"
                                                name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                required>

                                            @error('password')

                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>

                                            @enderror

                                            <small class="text-muted">
                                                Minimum 8 characters.
                                            </small>

                                        </div>


                                        {{-- CONFIRM PASSWORD --}}

                                        <div class="mb-3">

                                            <label class="form-label fw-semibold">
                                                Confirm New Password
                                            </label>

                                            <input
                                                type="password"
                                                name="password_confirmation"
                                                class="form-control"
                                                required>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="card-footer bg-white text-end">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="bi bi-shield-check me-1"></i>

                                    Change Password

                                </button>

                            </div>

                        </form>

                    </div>

                </div>


                {{-- =================================================
                    SECURITY TAB
                ================================================== --}}

                <div
                    class="tab-pane fade"
                    id="securityTab">

                    <div class="card shadow-sm">

                        <div class="card-header bg-white">

                            <h5 class="mb-1">

                                <i class="bi bi-shield-lock me-2"></i>

                                Security Settings

                            </h5>

                            <small class="text-muted">
                                Manage your account security.
                            </small>

                        </div>


                        <form
                            method="POST"
                            action="{{ route('admin.settings.security.update') }}">

                            @csrf
                            @method('PUT')


                            <div class="card-body">

                                <div class="security-box">

                                    <div>

                                        <h6 class="fw-bold mb-1">
                                            Two-Factor Authentication
                                        </h6>

                                        <p class="text-muted mb-0">
                                            Protect your account with Google
                                            Authenticator verification.
                                        </p>

                                    </div>


                                    <div class="text-end">

                                        @if($user->two_factor_enabled)

                                            <span class="badge bg-success px-3 py-2 mb-2">

                                                <i class="bi bi-shield-check me-1"></i>

                                                Enabled

                                            </span>

                                        @else

                                            <span class="badge bg-secondary px-3 py-2 mb-2">

                                                <i class="bi bi-shield me-1"></i>

                                                Disabled

                                            </span>

                                        @endif


                                        <div class="form-check form-switch">

                                            <input
                                                type="hidden"
                                                name="two_factor_enabled"
                                                value="0">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="two_factor_enabled"
                                                value="1"
                                                id="twoFactorSwitch"
                                                {{ $user->two_factor_enabled ? 'checked' : '' }}>

                                            <label
                                                class="form-check-label"
                                                for="twoFactorSwitch">

                                                Enable 2FA

                                            </label>

                                        </div>

                                    </div>

                                </div>


                                <div class="alert alert-info mt-4 mb-0">

                                    <i class="bi bi-info-circle me-2"></i>

                                    Your Google Authenticator setup and QR
                                    verification are already handled by the
                                    existing 2FA system.

                                </div>

                            </div>


                            <div class="card-footer bg-white text-end">

                                <button
                                    type="submit"
                                    class="btn btn-primary">

                                    <i class="bi bi-save me-1"></i>

                                    Save Security Settings

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>

/* =========================================================
   SETTINGS PAGE
========================================================= */

.settings-page {
    width: 100%;
}


/* =========================================================
   SETTINGS MENU
========================================================= */

#settingsTabs .nav-link {
    color: #495057;
    border-radius: 8px;
    margin-bottom: 5px;
    padding: 11px 14px;
    transition: 0.2s ease;
}

#settingsTabs .nav-link:hover {
    background: #f5f5f5;
}

#settingsTabs .nav-link.active {
    background: #8b5e6d;
    color: #fff;
}


/* =========================================================
   CARDS
========================================================= */

.settings-page .card {
    border-radius: 10px;
    border: 1px solid #e9ecef;
}


/* =========================================================
   LOGO
========================================================= */

.salon-logo-wrapper {
    width: 90px;
    height: 90px;
    flex-shrink: 0;
}

.salon-logo {
    width: 90px;
    height: 90px;
    object-fit: contain;
    border-radius: 12px;
    border: 1px solid #dee2e6;
    background: #fff;
    padding: 5px;
}

.salon-logo-placeholder {
    width: 90px;
    height: 90px;
    border-radius: 12px;
    border: 1px solid #dee2e6;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.salon-logo-placeholder i {
    font-size: 38px;
    color: #8b5e6d;
}


/* =========================================================
   AUTHORIZED SIGNATURE
========================================================= */

.signature-upload-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    background: #fafafa;
    min-height: 120px;
}

.signature-preview-wrapper {
    width: 150px;
    height: 90px;
    flex-shrink: 0;
    border: 1px dashed #ced4da;
    border-radius: 8px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    overflow: hidden;
}

.signature-preview {
    max-width: 135px;
    max-height: 75px;
    width: auto;
    height: auto;
    object-fit: contain;
}

.signature-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #adb5bd;
    text-align: center;
    font-size: 12px;
    gap: 5px;
}

.signature-placeholder i {
    font-size: 28px;
    color: #8b5e6d;
}

.signature-upload-content {
    min-width: 0;
}


/* =========================================================
   ACCOUNT INFORMATION
========================================================= */

.account-info-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    padding: 9px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13px;
}

.account-info-row:last-child {
    border-bottom: none;
}

.account-info-row span:first-child {
    color: #6c757d;
}


/* =========================================================
   FORM VALIDATION
========================================================= */

.settings-page .form-control.is-invalid,
.settings-page .form-select.is-invalid {
    border-color: #dc3545;
}

.settings-page .invalid-feedback {
    display: block;
    font-size: 13px;
}


/* =========================================================
   SECURITY
========================================================= */

.security-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    background: #fafafa;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991px) {

    .security-box {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

}


@media (max-width: 767px) {

    .signature-upload-box {
        flex-direction: column;
        align-items: flex-start;
    }

    .signature-preview-wrapper {
        width: 100%;
    }

    .signature-preview {
        max-width: 220px;
    }

}

</style>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =========================================================
       LOGO PREVIEW
    ========================================================= */

    const logoInput =
        document.getElementById('logo');

    if (logoInput) {

        logoInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                return;
            }


            /* File type validation */

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {

                alert(
                    'Please select a JPG, PNG or WEBP image.'
                );

                this.value = '';

                return;
            }


            /* File size validation - 2 MB */

            if (file.size > 2 * 1024 * 1024) {

                alert(
                    'Logo size must not exceed 2 MB.'
                );

                this.value = '';

                return;
            }


            const reader = new FileReader();

            reader.onload = function (event) {

                const wrapper =
                    document.querySelector(
                        '.salon-logo-wrapper'
                    );

                if (!wrapper) {
                    return;
                }


                wrapper.innerHTML = `

                    <img
                        id="salonLogoPreview"
                        src="${event.target.result}"
                        class="salon-logo"
                        alt="Salon Logo Preview">

                `;
            };

            reader.readAsDataURL(file);

        });

    }


    /* =========================================================
       AUTHORIZED SIGNATURE PREVIEW
    ========================================================= */

    const signatureInput =
        document.getElementById(
            'authorized_signature'
        );

    if (signatureInput) {

        signatureInput.addEventListener(
            'change',
            function () {

                const file = this.files[0];

                if (!file) {
                    return;
                }


                /* File type validation */

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type)) {

                    alert(
                        'Please select a JPG, PNG or WEBP signature image.'
                    );

                    this.value = '';

                    return;
                }


                /* File size validation - 2 MB */

                if (file.size > 2 * 1024 * 1024) {

                    alert(
                        'Signature size must not exceed 2 MB.'
                    );

                    this.value = '';

                    return;
                }


                const reader = new FileReader();

                reader.onload = function (event) {

                    const preview =
                        document.getElementById(
                            'signaturePreview'
                        );

                    const placeholder =
                        document.getElementById(
                            'signaturePlaceholder'
                        );


                    if (preview) {

                        preview.src =
                            event.target.result;

                        preview.style.display =
                            'block';
                    }


                    if (placeholder) {

                        placeholder.style.display =
                            'none';
                    }

                };

                reader.readAsDataURL(file);

            }
        );

    }


    /* =========================================================
       PHONE NUMBER VALIDATION
    ========================================================= */

    const phoneInput =
        document.getElementById('phone');

    if (phoneInput) {

        phoneInput.addEventListener(
            'input',
            function () {

                this.value =
                    this.value.replace(/\D/g, '');

                if (this.value.length > 10) {

                    this.value =
                        this.value.substring(0, 10);

                }

            }
        );


        phoneInput.addEventListener(
            'blur',
            function () {

                const value =
                    this.value.trim();

                if (
                    value !== '' &&
                    !/^[6-9][0-9]{9}$/.test(value)
                ) {

                    this.setCustomValidity(
                        'Please enter a valid 10-digit Indian mobile number.'
                    );

                } else {

                    this.setCustomValidity('');

                }

            }
        );


        phoneInput.addEventListener(
            'input',
            function () {

                this.setCustomValidity('');

            }
        );

    }


    /* =========================================================
       PINCODE VALIDATION
    ========================================================= */

    const pincodeInput =
        document.getElementById('pincode');

    if (pincodeInput) {

        pincodeInput.addEventListener(
            'input',
            function () {

                this.value =
                    this.value.replace(/\D/g, '');

                if (this.value.length > 6) {

                    this.value =
                        this.value.substring(0, 6);

                }

            }
        );

    }


    /* =========================================================
       LOGIN EMAIL VALIDATION
    ========================================================= */

    const emailInput =
        document.getElementById('email');

    if (emailInput) {

        emailInput.addEventListener(
            'blur',
            function () {

                if (
                    this.value.trim() !== '' &&
                    !this.checkValidity()
                ) {

                    this.classList.add(
                        'is-invalid'
                    );

                } else {

                    this.classList.remove(
                        'is-invalid'
                    );

                }

            }
        );

    }


    /* =========================================================
       SALON EMAIL VALIDATION
    ========================================================= */

    const salonEmailInput =
        document.getElementById('salon_email');

    if (salonEmailInput) {

        salonEmailInput.addEventListener(
            'blur',
            function () {

                if (
                    this.value.trim() !== '' &&
                    !this.checkValidity()
                ) {

                    this.classList.add(
                        'is-invalid'
                    );

                } else {

                    this.classList.remove(
                        'is-invalid'
                    );

                }

            }
        );

    }

});

</script>

@endsection