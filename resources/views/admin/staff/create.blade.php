@extends('admin.layout')

@section('title', 'Add Staff | Salon Management')

@section('page-title', 'Add Staff')

@section('content')

<div class="container-fluid px-0 staff-create-page">

    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())
        <div class="staff-alert staff-alert-danger alert-dismissible fade show" role="alert">
            <div class="staff-alert-content">
                <div class="staff-alert-icon">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>

                <div>
                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="staff-page-header">

        <div class="staff-header-content">

            <div class="staff-header-icon">
                <i class="bi bi-person-plus"></i>
            </div>

            <div>
                <h4 class="fw-bold mb-1">
                    Add Staff / Beautician
                </h4>

                <p class="text-muted mb-0">
                    Add a new staff member to your salon.
                </p>
            </div>

        </div>


        <a
            href="{{ route('admin.staff.index') }}"
            class="staff-secondary-btn">

            <i class="bi bi-arrow-left"></i>

            <span>Back to Staff</span>

        </a>

    </div>


    {{-- =========================================================
        STAFF FORM CARD
    ========================================================== --}}
    <div class="staff-form-card">

        <div class="staff-form-card-header">

            <div>
                <h5 class="mb-1 fw-semibold">
                    Staff Information
                </h5>

                <p class="mb-0 text-muted">
                    Enter the staff member's personal and professional details.
                </p>
            </div>

            <div class="staff-required-note">
                <span>*</span>
                Required fields
            </div>

        </div>


        <div class="staff-form-body">

            <form
                action="{{ route('admin.staff.store') }}"
                method="POST"
                id="staffCreateForm">

                @csrf


                {{-- =================================================
                    SALON & BRANCH
                ================================================== --}}
                <div class="staff-section">

                    <div class="staff-section-heading">

                        <div class="staff-section-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <div>
                            <div class="staff-section-title">
                                Salon & Branch
                            </div>

                            <div class="staff-section-subtitle">
                                Select where this staff member will work.
                            </div>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Salon --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Salon
                                <span class="text-danger">*</span>
                            </label>

                            @if(auth()->check() && !empty(auth()->user()->branch_id))

                                {{-- Branch User --}}
                                <input
                                    type="hidden"
                                    name="salon_id"
                                    value="{{ auth()->user()->salon_id }}"
                                >

                                <div class="staff-readonly-field">

                                    <span>
                                        {{ auth()->user()->salon?->name }}
                                    </span>

                                    <i class="bi bi-lock"></i>

                                </div>

                                <small class="staff-help-text">
                                    Staff will be added to your current salon.
                                </small>

                            @else

                                {{-- Admin / Non Branch User --}}
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

                            @endif

                            @error('salon_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Branch --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Branch
                                <span class="text-danger">*</span>
                            </label>

                            @if(auth()->check() && !empty(auth()->user()->branch_id))

                                {{-- Branch User --}}
                                <input
                                    type="hidden"
                                    name="branch_id"
                                    value="{{ auth()->user()->branch_id }}"
                                >

                                <div class="staff-readonly-field">

                                    <span>
                                        {{ auth()->user()->branch?->name }}
                                    </span>

                                    <i class="bi bi-lock"></i>

                                </div>

                                <small class="staff-help-text">
                                    Staff will be added to your logged-in branch.
                                </small>

                            @else

                                {{-- Admin / Non Branch User --}}
                                <select
                                    name="branch_id"
                                    id="branch_id"
                                    class="form-select @error('branch_id') is-invalid @enderror"
                                    required
                                    disabled
                                >

                                    <option value="">
                                        Select Branch
                                    </option>

                                    @foreach($branches as $branch)

                                        <option
                                            value="{{ $branch->id }}"
                                            data-salon="{{ $branch->salon_id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                        >
                                            {{ $branch->name }}

                                            @if(!empty($branch->code))
                                                ({{ $branch->code }})
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                                <small
                                    class="staff-help-text"
                                    id="branchHelp">
                                    Select a salon first to view its branches.
                                </small>

                            @endif

                            @error('branch_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PERSONAL DETAILS
                ================================================== --}}
                <div class="staff-section">

                    <div class="staff-section-heading">

                        <div class="staff-section-icon">
                            <i class="bi bi-person"></i>
                        </div>

                        <div>
                            <div class="staff-section-title">
                                Personal Details
                            </div>

                            <div class="staff-section-subtitle">
                                Basic contact and personal information.
                            </div>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <label
                                for="name"
                                class="form-label fw-semibold">

                                Full Name
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter full name"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="col-md-6">

                            <label
                                for="email"
                                class="form-label fw-semibold">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="example@email.com"
                                maxlength="255"
                                autocomplete="email"
                            >

                            <small class="staff-help-text">
                                Example: staff@gmail.com
                            </small>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Phone --}}
                        <div class="col-md-6">

                            <label
                                for="phone"
                                class="form-label fw-semibold">

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

                            <small class="staff-help-text">
                                Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.
                            </small>

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Gender --}}
                        <div class="col-md-3">

                            <label
                                for="gender"
                                class="form-label fw-semibold">

                                Gender

                            </label>

                            <select
                                name="gender"
                                id="gender"
                                class="form-select @error('gender') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Gender
                                </option>

                                <option
                                    value="male"
                                    {{ old('gender') == 'male' ? 'selected' : '' }}
                                >
                                    Male
                                </option>

                                <option
                                    value="female"
                                    {{ old('gender') == 'female' ? 'selected' : '' }}
                                >
                                    Female
                                </option>

                                <option
                                    value="other"
                                    {{ old('gender') == 'other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>

                            @error('gender')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Date of Birth --}}
                        <div class="col-md-3">

                            <label
                                for="date_of_birth"
                                class="form-label fw-semibold">

                                Date of Birth

                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                value="{{ old('date_of_birth') }}"
                            >

                            @error('date_of_birth')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Address --}}
                        <div class="col-12">

                            <label
                                for="address"
                                class="form-label fw-semibold">

                                Address

                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Enter staff address"
                            >{{ old('address') }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PROFESSIONAL DETAILS
                ================================================== --}}
                <div class="staff-section">

                    <div class="staff-section-heading">

                        <div class="staff-section-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>

                        <div>
                            <div class="staff-section-title">
                                Professional Details
                            </div>

                            <div class="staff-section-subtitle">
                                Define the staff member's role and expertise.
                            </div>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Role --}}
                        <div class="col-md-6">

                            <label
                                for="role"
                                class="form-label fw-semibold">

                                Role
                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="role"
                                id="role"
                                class="form-control @error('role') is-invalid @enderror"
                                value="{{ old('role') }}"
                                placeholder="e.g. Beautician, Hair Stylist, Manager"
                                required
                            >

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Specialization --}}
                        <div class="col-md-6">

                            <label
                                for="specialization"
                                class="form-label fw-semibold">

                                Specialization

                            </label>

                            <input
                                type="text"
                                name="specialization"
                                id="specialization"
                                class="form-control @error('specialization') is-invalid @enderror"
                                value="{{ old('specialization') }}"
                                placeholder="e.g. Hair, Makeup, Skin Care"
                            >

                            @error('specialization')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SALARY & COMMISSION
                ================================================== --}}
                <div class="staff-section">

                    <div class="staff-section-heading">

                        <div class="staff-section-icon">
                            <i class="bi bi-currency-rupee"></i>
                        </div>

                        <div>
                            <div class="staff-section-title">
                                Salary & Commission
                            </div>

                            <div class="staff-section-subtitle">
                                Configure salary and commission structure.
                            </div>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Salary --}}
                        <div class="col-md-4">

                            <label
                                for="salary"
                                class="form-label fw-semibold">

                                Salary
                                <span class="text-danger">*</span>

                            </label>

                            <div class="staff-input-group">

                                <span class="staff-input-prefix">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="salary"
                                    id="salary"
                                    class="form-control @error('salary') is-invalid @enderror"
                                    value="{{ old('salary', 0) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                    required
                                >

                            </div>

                            @error('salary')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Commission Type --}}
                        <div class="col-md-4">

                            <label
                                for="commission_type"
                                class="form-label fw-semibold">

                                Commission Type
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="commission_type"
                                id="commission_type"
                                class="form-select @error('commission_type') is-invalid @enderror"
                                required
                            >

                                <option
                                    value="percentage"
                                    {{ old('commission_type', 'percentage') == 'percentage' ? 'selected' : '' }}
                                >
                                    Percentage (%)
                                </option>

                                <option
                                    value="fixed"
                                    {{ old('commission_type') == 'fixed' ? 'selected' : '' }}
                                >
                                    Fixed Amount (₹)
                                </option>

                            </select>

                            @error('commission_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Commission Value --}}
                        <div class="col-md-4">

                            <label
                                for="commission_value"
                                class="form-label fw-semibold">

                                Commission Value
                                <span class="text-danger">*</span>

                            </label>

                            <div class="staff-input-group">

                                <span
                                    class="staff-input-prefix"
                                    id="commission-symbol">
                                    %
                                </span>

                                <input
                                    type="number"
                                    name="commission_value"
                                    id="commission_value"
                                    class="form-control @error('commission_value') is-invalid @enderror"
                                    value="{{ old('commission_value', 0) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="0.00"
                                    required
                                >

                            </div>

                            @error('commission_value')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    EMPLOYMENT
                ================================================== --}}
                <div class="staff-section staff-section-last">

                    <div class="staff-section-heading">

                        <div class="staff-section-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>
                            <div class="staff-section-title">
                                Employment Details
                            </div>

                            <div class="staff-section-subtitle">
                                Set joining date and current employment status.
                            </div>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Joining Date --}}
                        <div class="col-md-6">

                            <label
                                for="joining_date"
                                class="form-label fw-semibold">

                                Joining Date

                            </label>

                            <input
                                type="date"
                                name="joining_date"
                                id="joining_date"
                                class="form-control @error('joining_date') is-invalid @enderror"
                                value="{{ old('joining_date') }}"
                            >

                            @error('joining_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Status --}}
                        <div class="col-md-6">

                            <label
                                for="status"
                                class="form-label fw-semibold">

                                Status
                                <span class="text-danger">*</span>

                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >

                                <option
                                    value="1"
                                    {{ old('status', '1') == '1' ? 'selected' : '' }}
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    {{ old('status') == '0' ? 'selected' : '' }}
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
                    FORM ACTIONS
                ================================================== --}}
                <div class="staff-form-actions">

                    <a
                        href="{{ route('admin.staff.index') }}"
                        class="staff-cancel-btn">

                        <i class="bi bi-x-circle"></i>

                        <span>Cancel</span>

                    </a>


                    <button
                        type="submit"
                        class="staff-save-btn"
                        id="saveStaffBtn">

                        <i class="bi bi-check-circle"></i>

                        <span>Save Staff</span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    STYLES
========================================================= --}}
@push('styles')

<style>

    /* =========================================================
       STAFF CREATE PAGE
    ========================================================== */

    .staff-create-page {
        padding-bottom: 30px;
    }


    /* =========================================================
       ALERT
    ========================================================== */

    .staff-alert {
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        padding: 17px 20px;
        margin-bottom: 22px;
        border-radius: 16px;
        border: 1px solid transparent;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .07);
    }

    .staff-alert-danger {
        background: #fff8f8;
        border-color: #f1d5d5;
        color: #842029;
    }

    .staff-alert-content {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }

    .staff-alert-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #fff0f0;
        color: #b02a37;
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.8),
            0 3px 8px rgba(176,42,55,.08);
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .staff-page-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 24px;
        margin-bottom: 22px;
        background: #ffffff;
        border: 1px solid #eadfe4;
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 4px 8px rgba(45,37,41,.03),
            0 12px 28px rgba(45,37,41,.07),
            0 25px 55px rgba(45,37,41,.05);
    }

    .staff-page-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }

    .staff-header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .staff-header-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(
            145deg,
            #ffffff,
            #f4edf0
        );
        color: #8b5e6d;
        border: 1px solid #eadfe4;
        box-shadow:
            5px 5px 12px rgba(45,37,41,.08),
            -4px -4px 10px rgba(255,255,255,.95);
    }

    .staff-header-icon i {
        font-size: 20px;
    }

    .staff-page-header h4 {
        color: #2d2529;
    }


    /* =========================================================
       BUTTONS
    ========================================================== */

    .staff-secondary-btn,
    .staff-cancel-btn,
    .staff-save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        border-radius: 11px;
        min-height: 42px;
        padding: 9px 17px;
        font-weight: 600;
        transition:
            transform .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .staff-secondary-btn {
        color: #5c4c53;
        background: #ffffff;
        border: 1px solid #ded2d8;
        box-shadow:
            0 4px 9px rgba(45,37,41,.06),
            inset 0 1px 1px rgba(255,255,255,.9);
    }

    .staff-secondary-btn:hover {
        color: #8b5e6d;
        border-color: #cdb8c1;
        transform: translateY(-2px);
        box-shadow:
            0 7px 16px rgba(45,37,41,.09);
    }


    /* =========================================================
       FORM CARD
    ========================================================== */

    .staff-form-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #eadfe4;
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 4px 8px rgba(45,37,41,.03),
            0 12px 28px rgba(45,37,41,.07),
            0 25px 55px rgba(45,37,41,.05);
    }

    .staff-form-card::before {
        content: "";
        display: block;
        height: 3px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }

    .staff-form-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 21px 26px;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #fcfafb 100%
        );
        border-bottom: 1px solid #eee5e9;
    }

    .staff-form-card-header h5 {
        color: #2d2529;
    }

    .staff-required-note {
        white-space: nowrap;
        color: #776970;
    }

    .staff-required-note span {
        color: #dc3545;
        font-weight: 700;
    }

    .staff-form-body {
        padding: 28px;
    }


    /* =========================================================
       SECTIONS
    ========================================================== */

    .staff-section {
        padding-bottom: 31px;
        margin-bottom: 31px;
        border-bottom: 1px solid #eee6ea;
    }

    .staff-section-last {
        padding-bottom: 0;
        margin-bottom: 0;
        border-bottom: 0;
    }

    .staff-section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 21px;
    }

    .staff-section-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: linear-gradient(
            145deg,
            #ffffff,
            #f2eaee
        );
        color: #8b5e6d;
        border: 1px solid #e7dbe0;
        box-shadow:
            4px 4px 9px rgba(45,37,41,.07),
            -3px -3px 8px rgba(255,255,255,.95);
    }

    .staff-section-title {
        color: #2d2529;
        font-weight: 700;
    }

    .staff-section-subtitle {
        color: #8a7c82;
        margin-top: 2px;
    }


    /* =========================================================
       FORM CONTROLS
    ========================================================== */

    .staff-create-page .form-label {
        color: #40363b;
        margin-bottom: 8px;
    }

    .staff-create-page .form-control,
    .staff-create-page .form-select {
        min-height: 44px;
        border-radius: 11px;
        border: 1px solid #ded3d8;
        background-color: #ffffff;
        color: #332b2f;
        box-shadow:
            inset 0 1px 2px rgba(45,37,41,.025),
            0 2px 5px rgba(45,37,41,.025);
        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            transform .18s ease;
    }

    .staff-create-page .form-control:hover,
    .staff-create-page .form-select:hover {
        border-color: #cbb9c1;
    }

    .staff-create-page .form-control:focus,
    .staff-create-page .form-select:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139,94,109,.10),
            0 5px 12px rgba(45,37,41,.06);
        outline: none;
    }

    .staff-create-page .form-control::placeholder {
        color: #aaa0a5;
    }

    .staff-create-page textarea.form-control {
        min-height: 92px;
        resize: vertical;
    }

    .staff-create-page .form-select:disabled {
        background-color: #f8f6f7;
        color: #9a9095;
        cursor: not-allowed;
    }

    .staff-create-page .is-invalid {
        border-color: #dc3545 !important;
    }

    .staff-create-page .invalid-feedback {
        margin-top: 6px;
    }


    /* =========================================================
       READONLY FIELDS
    ========================================================== */

    .staff-readonly-field {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 8px 13px;
        border: 1px solid #ded3d8;
        border-radius: 11px;
        background: linear-gradient(
            180deg,
            #ffffff,
            #f8f5f6
        );
        color: #51464c;
        box-shadow:
            inset 0 1px 2px rgba(45,37,41,.025),
            0 2px 5px rgba(45,37,41,.025);
    }

    .staff-readonly-field i {
        color: #a08e96;
    }

    .staff-help-text {
        display: block;
        margin-top: 6px;
        color: #8a7d83;
    }


    /* =========================================================
       INPUT GROUP
    ========================================================== */

    .staff-input-group {
        display: flex;
        align-items: stretch;
    }

    .staff-input-prefix {
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 12px;
        border: 1px solid #ded3d8;
        border-right: 0;
        border-radius: 11px 0 0 11px;
        background: linear-gradient(
            180deg,
            #ffffff,
            #f5f1f3
        );
        color: #705864;
        font-weight: 700;
        box-shadow:
            inset 0 1px 2px rgba(45,37,41,.025);
    }

    .staff-input-group .form-control {
        border-radius: 0 11px 11px 0;
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================== */

    .staff-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 30px;
        padding-top: 23px;
        border-top: 1px solid #eee6ea;
    }

    .staff-cancel-btn {
        color: #62565c;
        background: #ffffff;
        border: 1px solid #ded3d8;
        box-shadow:
            0 4px 9px rgba(45,37,41,.05);
    }

    .staff-cancel-btn:hover {
        color: #8b5e6d;
        transform: translateY(-2px);
        box-shadow:
            0 7px 16px rgba(45,37,41,.08);
    }

    .staff-save-btn {
        color: #ffffff;
        border: 0;
        min-width: 135px;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        box-shadow:
            0 5px 0 #62414d,
            0 9px 18px rgba(98,65,77,.18);
    }

    .staff-save-btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 7px 0 #62414d,
            0 13px 23px rgba(98,65,77,.22);
    }

    .staff-save-btn:active {
        transform: translateY(3px);
        box-shadow:
            0 2px 0 #62414d,
            0 5px 12px rgba(98,65,77,.16);
    }

    .staff-save-btn.loading {
        pointer-events: none;
        opacity: .8;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 768px) {

        .staff-page-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }

        .staff-secondary-btn {
            width: 100%;
        }

        .staff-form-card-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
        }

        .staff-form-body {
            padding: 20px;
        }

        .staff-form-actions {
            flex-direction: column-reverse;
        }

        .staff-cancel-btn,
        .staff-save-btn {
            width: 100%;
        }

    }


    @media (max-width: 576px) {

        .staff-header-content {
            align-items: flex-start;
        }

        .staff-header-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
        }

        .staff-form-body {
            padding: 17px;
        }

        .staff-section {
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

    }

</style>

@endpush


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       PHONE VALIDATION
    ========================================================== */

    const phoneInput = document.getElementById('phone');

    if (phoneInput) {

        phoneInput.addEventListener('input', function () {

            this.value = this.value.replace(/\D/g, '');

            if (this.value.length > 10) {
                this.value = this.value.substring(0, 10);
            }

        });


        phoneInput.addEventListener('paste', function () {

            setTimeout(() => {

                this.value = this.value.replace(/\D/g, '');

                if (this.value.length > 10) {
                    this.value = this.value.substring(0, 10);
                }

            }, 0);

        });

    }


    /* =========================================================
       SALON → BRANCH
    ========================================================== */

    const salonSelect =
        document.getElementById('salon_id');

    const branchSelect =
        document.getElementById('branch_id');

    const branchHelp =
        document.getElementById('branchHelp');


    if (salonSelect && branchSelect) {

        function filterBranches() {

            const salonId = salonSelect.value;

            let hasBranch = false;


            Array.from(branchSelect.options).forEach(function (option) {

                if (option.value === '') {

                    option.hidden = false;

                    return;
                }


                if (option.dataset.salon === salonId) {

                    option.hidden = false;

                    hasBranch = true;

                } else {

                    option.hidden = true;

                }

            });


            if (salonId && hasBranch) {

                branchSelect.disabled = false;

                if (branchHelp) {
                    branchHelp.textContent =
                        'Select a branch.';
                }

            } else {

                branchSelect.disabled = true;

                branchSelect.value = '';

                if (branchHelp) {

                    branchHelp.textContent =
                        salonId
                            ? 'No active branches available for this salon.'
                            : 'Select a salon first to view its branches.';

                }

            }

        }


        salonSelect.addEventListener('change', function () {

            branchSelect.value = '';

            filterBranches();

        });


        filterBranches();

    }


    /* =========================================================
       COMMISSION TYPE → SYMBOL
    ========================================================== */

    const commissionType =
        document.getElementById('commission_type');

    const commissionSymbol =
        document.getElementById('commission-symbol');


    if (commissionType && commissionSymbol) {

        function updateCommissionSymbol() {

            if (commissionType.value === 'fixed') {

                commissionSymbol.textContent = '₹';

            } else {

                commissionSymbol.textContent = '%';

            }

        }


        commissionType.addEventListener(
            'change',
            updateCommissionSymbol
        );


        updateCommissionSymbol();

    }


    /* =========================================================
       FORM SUBMIT LOADING STATE
    ========================================================== */

    const staffForm =
        document.getElementById('staffCreateForm');

    const saveButton =
        document.getElementById('saveStaffBtn');


    if (staffForm && saveButton) {

        staffForm.addEventListener('submit', function () {

            if (!staffForm.checkValidity()) {
                return;
            }

            saveButton.classList.add('loading');

            saveButton.innerHTML =
                '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>' +
                '<span>Saving...</span>';

        });

    }

});

</script>

@endpush

@endsection