@extends('admin.layout')

@section('title', 'Edit Customer | Salon Management')

@section('page-title', 'Edit Customer')

@section('content')

<div class="container-fluid px-0 customer-edit-page">

    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())
        <div class="alert customer-alert customer-alert-danger">
            <div class="customer-alert-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div>
                <div class="customer-alert-title">
                    Please correct the following errors
                </div>

                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if (session('success'))
        <div class="alert customer-alert customer-alert-success">
            <div class="customer-alert-icon">
                <i class="bi bi-check-circle"></i>
            </div>

            <div>
                {{ session('success') }}
            </div>
        </div>
    @endif


    {{-- =========================================================
        MAIN CARD
    ========================================================== --}}
    <div class="customer-edit-card">

        <div class="customer-card-accent"></div>


        {{-- =====================================================
            CARD HEADER
        ====================================================== --}}
        <div class="customer-card-header">

            <div class="customer-heading-row">

                <div class="customer-heading-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h5 class="customer-card-title mb-1">
                        Edit Customer
                    </h5>

                    <p class="customer-card-subtitle mb-0">
                        Update customer information, branch assignment and profile details.
                    </p>
                </div>

            </div>


            <a
                href="{{ route('admin.customers.index') }}"
                class="customer-back-btn"
            >
                <i class="bi bi-arrow-left"></i>
                Back
            </a>

        </div>


        {{-- =====================================================
            FORM BODY
        ====================================================== --}}
        <div class="customer-card-body">

            <form
                action="{{ route('admin.customers.update', $customer->id) }}"
                method="POST"
                id="customerEditForm"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                    BUSINESS LOCATION
                ================================================== --}}
                <div class="customer-section">

                    <div class="customer-section-heading">

                        <div class="customer-section-line"></div>

                        <div>
                            <div class="customer-section-title">
                                Business Location
                            </div>

                            <div class="customer-section-subtitle">
                                Manage the salon and branch associated with this customer.
                            </div>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- =========================================
                            SALON
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="salon_id"
                                class="form-label customer-label"
                            >
                                Salon
                                <span class="text-danger">*</span>
                            </label>

                            @if(auth()->check() && !empty(auth()->user()->branch_id))

                                <input
                                    type="hidden"
                                    name="salon_id"
                                    value="{{ auth()->user()->salon_id }}"
                                >

                                <div class="customer-input-wrap">
                                    <input
                                        type="text"
                                        class="form-control customer-input"
                                        value="{{ auth()->user()->salon?->name }}"
                                        readonly
                                    >
                                </div>

                            @else

                                <div class="customer-input-wrap">
                                    <select
                                        name="salon_id"
                                        id="salon_id"
                                        class="form-select customer-input @error('salon_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">
                                            Select Salon
                                        </option>

                                        @foreach($salons as $salon)

                                            <option
                                                value="{{ $salon->id }}"
                                                {{ old('salon_id', $customer->salon_id) == $salon->id ? 'selected' : '' }}
                                            >
                                                {{ $salon->name }}
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                            @endif

                            @error('salon_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            BRANCH
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="branch_id"
                                class="form-label customer-label"
                            >
                                Branch
                                <span class="text-danger">*</span>
                            </label>

                            @if(auth()->check() && !empty(auth()->user()->branch_id))

                                <input
                                    type="hidden"
                                    name="branch_id"
                                    value="{{ auth()->user()->branch_id }}"
                                >

                                <div class="customer-input-wrap">
                                    <input
                                        type="text"
                                        class="form-control customer-input"
                                        value="{{ auth()->user()->branch?->name }}"
                                        readonly
                                    >
                                </div>

                                <small class="customer-help-text">
                                    Customer belongs to your logged-in branch.
                                </small>

                            @else

                                <div class="customer-input-wrap">
                                    <select
                                        name="branch_id"
                                        id="branch_id"
                                        class="form-select customer-input @error('branch_id') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">
                                            Select Branch
                                        </option>

                                        @foreach($branches as $branch)

                                            <option
                                                value="{{ $branch->id }}"
                                                data-salon="{{ $branch->salon_id }}"
                                                {{ old('branch_id', $customer->branch_id) == $branch->id ? 'selected' : '' }}
                                            >
                                                {{ $branch->name }}

                                                @if(!empty($branch->code))
                                                    ({{ $branch->code }})
                                                @endif
                                            </option>

                                        @endforeach

                                    </select>
                                </div>

                                <small
                                    id="branchHelp"
                                    class="customer-help-text"
                                >
                                    Select a branch.
                                </small>

                            @endif

                            @error('branch_id')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    CUSTOMER INFORMATION
                ================================================== --}}
                <div class="customer-section">

                    <div class="customer-section-heading">

                        <div class="customer-section-line"></div>

                        <div>
                            <div class="customer-section-title">
                                Customer Information
                            </div>

                            <div class="customer-section-subtitle">
                                Update the customer's contact and personal information.
                            </div>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- =========================================
                            CUSTOMER NAME
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="name"
                                class="form-label customer-label"
                            >
                                Customer Name
                                <span class="text-danger">*</span>
                            </label>

                            <div class="customer-input-wrap">
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control customer-input @error('name') is-invalid @enderror"
                                    value="{{ old('name', $customer->name) }}"
                                    placeholder="Enter customer name"
                                    maxlength="255"
                                    required
                                >
                            </div>

                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            PHONE
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="phone"
                                class="form-label customer-label"
                            >
                                Phone
                            </label>

                            <div class="customer-input-wrap">
                                <input
                                    type="tel"
                                    name="phone"
                                    id="phone"
                                    class="form-control customer-input @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $customer->phone) }}"
                                    placeholder="Enter 10-digit mobile number"
                                    maxlength="10"
                                    minlength="10"
                                    pattern="[6-9][0-9]{9}"
                                    inputmode="numeric"
                                >
                            </div>

                            <small class="customer-help-text">
                                Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9.
                            </small>

                            @error('phone')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            EMAIL
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="email"
                                class="form-label customer-label"
                            >
                                Email
                            </label>

                            <div class="customer-input-wrap">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="form-control customer-input @error('email') is-invalid @enderror"
                                    value="{{ old('email', $customer->email) }}"
                                    placeholder="Enter valid email address"
                                    maxlength="255"
                                >
                            </div>

                            <small class="customer-help-text">
                                Example: customer@gmail.com
                            </small>

                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            GENDER
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="gender"
                                class="form-label customer-label"
                            >
                                Gender
                            </label>

                            <div class="customer-input-wrap">
                                <select
                                    name="gender"
                                    id="gender"
                                    class="form-select customer-input @error('gender') is-invalid @enderror"
                                >
                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option
                                        value="male"
                                        {{ old('gender', $customer->gender) == 'male' ? 'selected' : '' }}
                                    >
                                        Male
                                    </option>

                                    <option
                                        value="female"
                                        {{ old('gender', $customer->gender) == 'female' ? 'selected' : '' }}
                                    >
                                        Female
                                    </option>

                                    <option
                                        value="other"
                                        {{ old('gender', $customer->gender) == 'other' ? 'selected' : '' }}
                                    >
                                        Other
                                    </option>
                                </select>
                            </div>

                            @error('gender')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ADDITIONAL INFORMATION
                ================================================== --}}
                <div class="customer-section">

                    <div class="customer-section-heading">

                        <div class="customer-section-line"></div>

                        <div>
                            <div class="customer-section-title">
                                Additional Information
                            </div>

                            <div class="customer-section-subtitle">
                                Manage date of birth, address, notes and account status.
                            </div>
                        </div>

                    </div>


                    <div class="row g-3">

                        {{-- =========================================
                            DATE OF BIRTH
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="date_of_birth"
                                class="form-label customer-label"
                            >
                                Date of Birth
                            </label>

                            <div class="customer-input-wrap">
                                <input
                                    type="date"
                                    name="date_of_birth"
                                    id="date_of_birth"
                                    class="form-control customer-input @error('date_of_birth') is-invalid @enderror"
                                    value="{{ old(
                                        'date_of_birth',
                                        optional($customer->date_of_birth)->format('Y-m-d')
                                    ) }}"
                                >
                            </div>

                            @error('date_of_birth')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            STATUS
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="status"
                                class="form-label customer-label"
                            >
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <div class="customer-input-wrap">
                                <select
                                    name="status"
                                    id="status"
                                    class="form-select customer-input @error('status') is-invalid @enderror"
                                    required
                                >
                                    <option
                                        value="1"
                                        {{ old('status', $customer->status) == 1 ? 'selected' : '' }}
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="0"
                                        {{ old('status', $customer->status) == 0 ? 'selected' : '' }}
                                    >
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            @error('status')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            ADDRESS
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="address"
                                class="form-label customer-label"
                            >
                                Address
                            </label>

                            <textarea
                                name="address"
                                id="address"
                                class="form-control customer-textarea @error('address') is-invalid @enderror"
                                rows="4"
                                placeholder="Enter address"
                            >{{ old('address', $customer->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- =========================================
                            NOTES
                        ========================================== --}}
                        <div class="col-md-6">

                            <label
                                for="notes"
                                class="form-label customer-label"
                            >
                                Notes
                            </label>

                            <textarea
                                name="notes"
                                id="notes"
                                class="form-control customer-textarea @error('notes') is-invalid @enderror"
                                rows="4"
                                placeholder="Enter notes"
                            >{{ old('notes', $customer->notes) }}</textarea>

                            @error('notes')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTION BUTTONS
                ================================================== --}}
                <div class="customer-form-actions">

                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="customer-cancel-btn"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="customer-save-btn"
                        id="updateCustomerBtn"
                    >
                        <i class="bi bi-check-circle"></i>

                        <span id="updateCustomerText">
                            Update Customer
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
    PAGE STYLES
========================================================= --}}
@push('styles')

<style>

    /* =========================================================
       MAIN PAGE
    ========================================================== */

    .customer-edit-page {
        padding-bottom: 25px;
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .customer-alert {
        border: 0;
        border-radius: 15px;
        padding: 15px 18px;
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 18px;
        box-shadow:
            0 4px 10px rgba(45, 37, 41, 0.04),
            0 12px 28px rgba(45, 37, 41, 0.06);
    }

    .customer-alert-danger {
        background: #fff7f8;
        color: #842029;
        border-left: 4px solid #dc3545;
    }

    .customer-alert-success {
        background: #f6fbf8;
        color: #276749;
        border-left: 4px solid #4f9d69;
    }

    .customer-alert-icon {
        width: 34px;
        height: 34px;
        min-width: 34px;
        border-radius: 10px;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.08);
    }

    .customer-alert-title {
        font-weight: 600;
        margin-bottom: 4px;
    }


    /* =========================================================
       MAIN CARD
    ========================================================== */

    .customer-edit-card {
        position: relative;
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(45, 37, 41, 0.07);
        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 12px 28px rgba(45, 37, 41, 0.07),
            0 25px 55px rgba(45, 37, 41, 0.05);
    }

    .customer-card-accent {
        height: 4px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }


    /* =========================================================
       HEADER
    ========================================================== */

    .customer-card-header {
        min-height: 82px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid rgba(45, 37, 41, 0.07);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .customer-heading-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .customer-heading-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(
            145deg,
            #ffffff,
            #f2edef
        );
        color: #8b5e6d;
        border: 1px solid rgba(139, 94, 109, 0.12);
        box-shadow:
            0 4px 7px rgba(45, 37, 41, 0.07),
            inset 0 1px 1px rgba(255, 255, 255, 0.9);
        font-size: inherit;
    }

    .customer-card-title {
        font-weight: 600;
        color: #2d2529;
    }

    .customer-card-subtitle {
        color: #80757a;
    }

    .customer-back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 8px 15px;
        border-radius: 10px;
        text-decoration: none;
        color: #5d5156;
        background: #ffffff;
        border: 1px solid rgba(45, 37, 41, 0.10);
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.06);
        transition: all .2s ease;
    }

    .customer-back-btn:hover {
        color: #8b5e6d;
        transform: translateY(-2px);
        box-shadow:
            0 6px 13px rgba(45, 37, 41, 0.10);
    }


    /* =========================================================
       CARD BODY
    ========================================================== */

    .customer-card-body {
        padding: 25px;
        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fdfcfd 100%
            );
    }


    /* =========================================================
       FORM SECTION
    ========================================================== */

    .customer-section {
        padding: 21px;
        margin-bottom: 20px;
        border-radius: 16px;
        background: #ffffff;
        border: 1px solid rgba(45, 37, 41, 0.07);
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.025),
            0 9px 22px rgba(45, 37, 41, 0.045);
    }

    .customer-section:last-of-type {
        margin-bottom: 0;
    }

    .customer-section-heading {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 19px;
    }

    .customer-section-line {
        width: 4px;
        min-width: 4px;
        height: 35px;
        border-radius: 10px;
        background: linear-gradient(
            180deg,
            #8b5e6d,
            #b78b99
        );
        box-shadow:
            0 3px 6px rgba(139, 94, 109, 0.20);
    }

    .customer-section-title {
        color: #332b2f;
        font-weight: 600;
    }

    .customer-section-subtitle {
        color: #8a7d82;
        margin-top: 2px;
    }


    /* =========================================================
       LABELS
    ========================================================== */

    .customer-label {
        color: #443a3f;
        font-weight: 500;
        margin-bottom: 7px;
    }


    /* =========================================================
       INPUTS
    ========================================================== */

    .customer-input-wrap {
        position: relative;
    }

    .customer-input {
        min-height: 44px;
        border-radius: 11px;
        border: 1px solid rgba(45, 37, 41, 0.12);
        background: #ffffff;
        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.025),
            0 2px 5px rgba(45, 37, 41, 0.035);
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            transform .2s ease;
    }

    .customer-input:hover {
        border-color: rgba(139, 94, 109, 0.28);
    }

    .customer-input:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(45, 37, 41, 0.05);
    }

    .customer-input[readonly] {
        background: #f8f6f7;
        color: #665b60;
        cursor: not-allowed;
    }

    .customer-textarea {
        min-height: 110px;
        resize: vertical;
        border-radius: 11px;
        border: 1px solid rgba(45, 37, 41, 0.12);
        background: #ffffff;
        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.025),
            0 2px 5px rgba(45, 37, 41, 0.035);
        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .customer-textarea:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(45, 37, 41, 0.05);
    }


    /* =========================================================
       HELP TEXT
    ========================================================== */

    .customer-help-text {
        display: block;
        margin-top: 6px;
        color: #8b7f84;
    }


    /* =========================================================
       VALIDATION
    ========================================================== */

    .customer-input.is-invalid,
    .customer-textarea.is-invalid {
        border-color: #dc3545;
    }

    .customer-input.is-invalid:focus,
    .customer-textarea.is-invalid:focus {
        box-shadow:
            0 0 0 3px rgba(220, 53, 69, 0.09);
    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .customer-form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 11px;
        padding-top: 22px;
        margin-top: 20px;
        border-top: 1px solid rgba(45, 37, 41, 0.07);
    }

    .customer-cancel-btn,
    .customer-save-btn {
        min-height: 43px;
        padding: 9px 18px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        text-decoration: none;
        transition:
            transform .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .customer-cancel-btn {
        color: #5d5156;
        background: #ffffff;
        border: 1px solid rgba(45, 37, 41, 0.11);
        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.06);
    }

    .customer-cancel-btn:hover {
        color: #8b5e6d;
        transform: translateY(-2px);
        box-shadow:
            0 7px 14px rgba(45, 37, 41, 0.09);
    }

    .customer-save-btn {
        border: 0;
        color: #ffffff;
        background: linear-gradient(
            145deg,
            #8b5e6d,
            #76505e
        );
        box-shadow:
            0 5px 0 #62414d,
            0 9px 18px rgba(139, 94, 109, 0.20);
        cursor: pointer;
    }

    .customer-save-btn:hover {
        transform: translateY(-2px);
        box-shadow:
            0 7px 0 #62414d,
            0 13px 23px rgba(139, 94, 109, 0.24);
    }

    .customer-save-btn:active {
        transform: translateY(3px);
        box-shadow:
            0 2px 0 #62414d,
            0 5px 10px rgba(139, 94, 109, 0.15);
    }

    .customer-save-btn:disabled {
        opacity: .75;
        cursor: not-allowed;
        transform: none;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 767.98px) {

        .customer-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .customer-back-btn {
            width: 100%;
        }

        .customer-card-body {
            padding: 15px;
        }

        .customer-section {
            padding: 16px;
        }

        .customer-form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .customer-cancel-btn,
        .customer-save-btn {
            width: 100%;
        }

    }

</style>

@endpush


{{-- =========================================================
    BRANCH FILTER + PHONE VALIDATION SCRIPT
========================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       PHONE VALIDATION
    ========================================================== */

    const phoneInput =
        document.getElementById('phone');

    if (phoneInput) {

        phoneInput.addEventListener(
            'input',
            function () {

                // Allow numbers only
                this.value =
                    this.value.replace(/\D/g, '');

                // Maximum 10 digits
                if (this.value.length > 10) {

                    this.value =
                        this.value.substring(0, 10);

                }

            }
        );

    }


    /* =========================================================
       SALON / BRANCH ELEMENTS
    ========================================================== */

    const salonSelect =
        document.getElementById('salon_id');

    const branchSelect =
        document.getElementById('branch_id');

    const branchHelp =
        document.getElementById('branchHelp');


    /* =========================================================
       STOP IF ELEMENTS NOT FOUND
    ========================================================== */

    if (!salonSelect || !branchSelect) {
        return;
    }


    /* =========================================================
       FILTER BRANCHES
    ========================================================== */

    function filterBranches() {

        const salonId =
            salonSelect.value;

        let visibleBranches = 0;


        Array.from(
            branchSelect.options
        ).forEach(function (option) {

            /* Default option */
            if (option.value === '') {

                option.hidden = false;

                return;
            }


            /* Get salon assigned to branch */
            const branchSalonId =
                option.getAttribute('data-salon');


            /* Show only matching branches */
            if (
                String(branchSalonId) ===
                String(salonId)
            ) {

                option.hidden = false;

                visibleBranches++;

            } else {

                option.hidden = true;

            }

        });


        /* =====================================================
           CHECK BRANCH AVAILABILITY
        ====================================================== */

        if (visibleBranches === 0) {

            branchSelect.value = '';

            if (branchHelp) {

                branchHelp.textContent =
                    'No branches available for this salon.';

            }

        } else {

            if (branchHelp) {

                branchHelp.textContent =
                    'Select a branch.';

            }

        }


        /* =====================================================
           RESTORE CUSTOMER BRANCH
        ====================================================== */

        const currentBranch =
            "{{ old('branch_id', $customer->branch_id) }}";

        if (currentBranch) {

            const branchOption =
                branchSelect.querySelector(
                    'option[value="' +
                    currentBranch +
                    '"]'
                );

            if (
                branchOption &&
                !branchOption.hidden
            ) {

                branchSelect.value =
                    currentBranch;

            }

        }

    }


    /* =========================================================
       SALON CHANGE
    ========================================================== */

    salonSelect.addEventListener(
        'change',
        function () {

            branchSelect.value = '';

            filterBranches();

        }
    );


    /* =========================================================
       INITIAL LOAD
    ========================================================== */

    filterBranches();


    /* =========================================================
       FORM SUBMIT LOADING STATE
    ========================================================== */

    const customerEditForm =
        document.getElementById('customerEditForm');

    const updateButton =
        document.getElementById('updateCustomerBtn');

    const updateText =
        document.getElementById('updateCustomerText');


    if (
        customerEditForm &&
        updateButton
    ) {

        customerEditForm.addEventListener(
            'submit',
            function () {

                updateButton.disabled = true;

                if (updateText) {

                    updateText.textContent =
                        'Updating Customer...';

                }

            }
        );

    }

});

</script>

@endpush

@endsection