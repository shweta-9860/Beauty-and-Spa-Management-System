@extends('admin.layout')

@section('title', 'Edit Service | Salon Management')

@section('page-title', 'Edit Service')

@section('content')

<div class="service-edit-page">

    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())
        <div class="service-edit-alert service-edit-alert-danger">

            <div class="service-edit-alert-content align-items-start">

                <i class="bi bi-exclamation-circle mt-1"></i>

                <div>
                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <button
                type="button"
                class="service-edit-alert-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
                &times;
            </button>

        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="service-edit-hero">

        <div class="service-edit-hero-left">

            <div class="service-edit-title-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h2>Edit Salon Service</h2>

                <p>
                    Update the details of this salon service.
                </p>
            </div>

        </div>


        <a
            href="{{ route('admin.services.index') }}"
            class="service-edit-back-btn"
        >
            <i class="bi bi-arrow-left"></i>
            <span>Back to Services</span>
        </a>

    </div>


    {{-- =========================================================
        SERVICE INFORMATION
    ========================================================== --}}
    <div class="service-info-card">

        <div class="service-info-heading">

            <div class="service-info-icon">
                <i class="bi bi-info-circle"></i>
            </div>

            <div>
                <h5>Service Information</h5>

                <span>
                    Basic information about this service
                </span>
            </div>

        </div>


        <div class="row g-3">

            <div class="col-md-4">

                <div class="service-meta-box">

                    <span class="service-meta-label">
                        Service ID
                    </span>

                    <strong>
                        #{{ $service->id }}
                    </strong>

                </div>

            </div>


            <div class="col-md-4">

                <div class="service-meta-box">

                    <span class="service-meta-label">
                        Created
                    </span>

                    <strong>
                        {{ $service->created_at?->format('d M Y') }}
                    </strong>

                </div>

            </div>


            <div class="col-md-4">

                <div class="service-meta-box">

                    <span class="service-meta-label">
                        Last Updated
                    </span>

                    <strong>
                        {{ $service->updated_at?->format('d M Y') }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FORM CARD
    ========================================================== --}}
    <div class="service-edit-card">

        <div class="service-edit-card-accent"></div>


        <div class="service-edit-card-header">

            <div>

                <h5>
                    Edit Service Details
                </h5>

                <span>
                    Update the service information below
                </span>

            </div>

        </div>


        <div class="service-edit-card-body">

            <form
                method="POST"
                action="{{ route('admin.services.update', $service->id) }}"
                enctype="multipart/form-data"
                id="serviceEditForm"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                    BASIC INFORMATION
                ================================================== --}}
                <div class="service-form-section">

                    <div class="service-section-heading">

                        <span class="service-section-line"></span>

                        <div>
                            <h6>Basic Information</h6>
                            <small>Salon, name and category</small>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Salon --}}
                        <div class="col-md-6">

                            <label
                                for="salon_id"
                                class="service-form-label"
                            >
                                Salon
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="salon_id"
                                id="salon_id"
                                class="form-select service-form-control"
                                required
                            >

                                <option value="">
                                    Select Salon
                                </option>

                                @foreach($salons as $salon)

                                    <option
                                        value="{{ $salon->id }}"
                                        {{ old('salon_id', $service->salon_id) == $salon->id ? 'selected' : '' }}
                                    >
                                        {{ $salon->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- Service Name --}}
                        <div class="col-md-6">

                            <label
                                for="name"
                                class="service-form-label"
                            >
                                Service Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control service-form-control"
                                value="{{ old('name', $service->name) }}"
                                placeholder="Enter service name"
                                required
                            >

                        </div>


                        {{-- Category --}}
                        <div class="col-md-6">

                            <label
                                for="category"
                                class="service-form-label"
                            >
                                Category
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="category"
                                id="category"
                                class="form-select service-form-control"
                                required
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <option
                                    value="Hair"
                                    {{ old('category', $service->category) == 'Hair' ? 'selected' : '' }}
                                >
                                    Hair
                                </option>

                                <option
                                    value="Facial"
                                    {{ old('category', $service->category) == 'Facial' ? 'selected' : '' }}
                                >
                                    Facial
                                </option>

                                <option
                                    value="Skin Care"
                                    {{ old('category', $service->category) == 'Skin Care' ? 'selected' : '' }}
                                >
                                    Skin Care
                                </option>

                                <option
                                    value="Makeup"
                                    {{ old('category', $service->category) == 'Makeup' ? 'selected' : '' }}
                                >
                                    Makeup
                                </option>

                                <option
                                    value="Nails"
                                    {{ old('category', $service->category) == 'Nails' ? 'selected' : '' }}
                                >
                                    Nails
                                </option>

                                <option
                                    value="Spa"
                                    {{ old('category', $service->category) == 'Spa' ? 'selected' : '' }}
                                >
                                    Spa
                                </option>

                                <option
                                    value="Other"
                                    {{ old('category', $service->category) == 'Other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    PRICING & DURATION
                ================================================== --}}
                <div class="service-form-section">

                    <div class="service-section-heading">

                        <span class="service-section-line"></span>

                        <div>
                            <h6>Pricing & Duration</h6>
                            <small>Set service price and appointment duration</small>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Price --}}
                        <div class="col-md-6">

                            <label
                                for="price"
                                class="service-form-label"
                            >
                                Price (₹)
                                <span class="text-danger">*</span>
                            </label>

                            <div class="service-input-group">

                                <span class="service-input-prefix">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="price"
                                    id="price"
                                    class="form-control service-form-control service-price-input"
                                    value="{{ old('price', $service->price) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="Enter price"
                                    required
                                >

                            </div>

                        </div>


                        {{-- Duration --}}
                        <div class="col-md-6">

                            <label
                                for="duration"
                                class="service-form-label"
                            >
                                Duration
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="duration"
                                id="duration"
                                class="form-select service-form-control"
                                required
                            >

                                <option value="">
                                    Select Duration
                                </option>

                                <option
                                    value="30"
                                    {{ old('duration', $service->duration) == 30 ? 'selected' : '' }}
                                >
                                    30 Minutes
                                </option>

                                <option
                                    value="45"
                                    {{ old('duration', $service->duration) == 45 ? 'selected' : '' }}
                                >
                                    45 Minutes
                                </option>

                                <option
                                    value="60"
                                    {{ old('duration', $service->duration) == 60 ? 'selected' : '' }}
                                >
                                    1 Hour
                                </option>

                                <option
                                    value="90"
                                    {{ old('duration', $service->duration) == 90 ? 'selected' : '' }}
                                >
                                    1.5 Hours
                                </option>

                                <option
                                    value="120"
                                    {{ old('duration', $service->duration) == 120 ? 'selected' : '' }}
                                >
                                    2 Hours
                                </option>

                                <option
                                    value="180"
                                    {{ old('duration', $service->duration) == 180 ? 'selected' : '' }}
                                >
                                    3 Hours
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    IMAGE & DESCRIPTION
                ================================================== --}}
                <div class="service-form-section">

                    <div class="service-section-heading">

                        <span class="service-section-line"></span>

                        <div>
                            <h6>Service Presentation</h6>
                            <small>Image and service description</small>
                        </div>

                    </div>


                    <div class="row g-4">

                        {{-- Service Image --}}
                        <div class="col-md-6">

                            <label
                                for="image"
                                class="service-form-label"
                            >
                                Service Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                id="image"
                                class="form-control service-form-control"
                                accept=".jpg,.jpeg,.png,.webp"
                            >

                            <div class="service-help-text">
                                JPG, JPEG, PNG or WEBP. Maximum 2MB.
                            </div>


                            @if($service->image)

                                <div class="current-image-box">

                                    <div class="current-image-heading">
                                        Current Image
                                    </div>

                                    <img
                                        src="{{ asset($service->image) }}"
                                        alt="{{ $service->name }}"
                                        class="service-image-preview"
                                    >

                                </div>

                            @endif


                            <div
                                id="newImagePreviewWrapper"
                                class="new-image-preview-wrapper"
                                style="display:none;"
                            >

                                <div class="current-image-heading">
                                    New Image Preview
                                </div>

                                <img
                                    src=""
                                    id="newImagePreview"
                                    class="service-image-preview"
                                    alt="New Image Preview"
                                >

                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-md-6">

                            <label
                                for="description"
                                class="service-form-label"
                            >
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="7"
                                class="form-control service-form-control service-textarea"
                                placeholder="Enter service description"
                            >{{ old('description', $service->description) }}</textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    STATUS
                ================================================== --}}
                <div class="service-form-section">

                    <div class="service-section-heading">

                        <span class="service-section-line"></span>

                        <div>
                            <h6>Service Status</h6>
                            <small>Control whether this service is available</small>
                        </div>

                    </div>


                    <div class="row">

                        <div class="col-md-6">

                            <label
                                for="status"
                                class="service-form-label"
                            >
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-select service-form-control"
                                required
                            >

                                <option
                                    value="1"
                                    {{ old('status', $service->status) == 1 ? 'selected' : '' }}
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    {{ old('status', $service->status) == 0 ? 'selected' : '' }}
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTIONS
                ================================================== --}}
                <div class="service-form-actions">

                    <a
                        href="{{ route('admin.services.index') }}"
                        class="service-cancel-btn"
                    >
                        <i class="bi bi-x-lg"></i>
                        <span>Cancel</span>
                    </a>


                    <button
                        type="submit"
                        class="service-update-btn"
                        id="updateServiceBtn"
                    >
                        <i class="bi bi-check-lg"></i>
                        <span id="updateServiceBtnText">
                            Update Service
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('styles')
<style>

/* =========================================================
   MAIN PAGE
========================================================= */

.service-edit-page {
    padding: 10px 0 30px;
}


/* =========================================================
   ALERT
========================================================= */

.service-edit-alert {
    position: relative;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 13px 16px;

    margin-bottom: 18px;

    border-radius: 14px;

    border: 1px solid #f1dddd;

    background: #fff5f5;

    color: #a84d4d;

    box-shadow:
        0 3px 8px rgba(45, 37, 41, .03),
        0 10px 22px rgba(45, 37, 41, .04);
}

.service-edit-alert-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.service-edit-alert-content i {
    font-size: 15px;
}

.service-edit-alert ul {
    padding-left: 18px;
}

.service-edit-alert li {
    margin-bottom: 2px;
}

.service-edit-alert-close {
    width: 28px;
    height: 28px;

    border: none;

    background: transparent;

    color: currentColor;

    font-size: 20px;
    line-height: 1;

    opacity: .65;

    cursor: pointer;
}

.service-edit-alert-close:hover {
    opacity: 1;
}


/* =========================================================
   HERO
========================================================= */

.service-edit-hero {
    position: relative;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 22px 24px;

    margin-bottom: 20px;

    background: #ffffff;

    border: 1px solid #eee5e8;

    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 4px 8px rgba(45, 37, 41, .03),
        0 12px 28px rgba(45, 37, 41, .07),
        0 25px 55px rgba(45, 37, 41, .05);
}

.service-edit-hero::before {
    content: "";

    position: absolute;

    left: 0;
    right: 0;
    top: 0;

    height: 4px;

    background: linear-gradient(
        90deg,
        #8b5e6d,
        #b78b99,
        #8b5e6d
    );
}

.service-edit-hero-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.service-edit-title-icon {
    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background: linear-gradient(
        145deg,
        #ffffff,
        #f4ecef
    );

    color: #8b5e6d;

    border: 1px solid #eadde2;

    box-shadow:
        4px 4px 9px rgba(45, 37, 41, .08),
        -3px -3px 8px rgba(255, 255, 255, .95);
}

.service-edit-title-icon i {
    font-size: 19px;
}

.service-edit-hero h2 {
    margin: 0;

    color: #2d2529;

    font-size: 25px;
    font-weight: 700;
}

.service-edit-hero p {
    margin: 5px 0 0;

    color: #8b7d82;

    font-size: 14px;
}


/* =========================================================
   BACK BUTTON
========================================================= */

.service-edit-back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    min-height: 42px;

    padding: 9px 15px;

    border-radius: 10px;

    background: #ffffff;

    border: 1px solid #ded3d8;

    color: #64575c;

    text-decoration: none;

    font-size: 13px;
    font-weight: 600;

    box-shadow:
        0 3px 7px rgba(45, 37, 41, .05);

    transition: all .2s ease;
}

.service-edit-back-btn:hover {
    background: #f7edf1;

    border-color: #d7c4cb;

    color: #8b5e6d;

    transform: translateY(-1px);
}


/* =========================================================
   SERVICE INFO CARD
========================================================= */

.service-info-card {
    position: relative;

    padding: 20px;

    margin-bottom: 20px;

    background: #ffffff;

    border: 1px solid #eee5e8;

    border-radius: 18px;

    box-shadow:
        0 4px 8px rgba(45, 37, 41, .03),
        0 12px 25px rgba(45, 37, 41, .06),
        0 20px 40px rgba(45, 37, 41, .04);
}

.service-info-heading {
    display: flex;
    align-items: center;

    gap: 11px;

    margin-bottom: 17px;

    padding-bottom: 15px;

    border-bottom: 1px solid #f0e9ec;
}

.service-info-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #f7edf1;

    color: #8b5e6d;
}

.service-info-icon i {
    font-size: 15px;
}

.service-info-heading h5 {
    margin: 0;

    color: #332a2e;

    font-size: 15px;
    font-weight: 700;
}

.service-info-heading span {
    display: block;

    margin-top: 3px;

    color: #9a8c91;

    font-size: 12px;
}


/* =========================================================
   META BOX
========================================================= */

.service-meta-box {
    min-height: 68px;

    display: flex;
    flex-direction: column;
    justify-content: center;

    padding: 11px 14px;

    border-radius: 11px;

    background: #faf7f8;

    border: 1px solid #eee5e8;

    box-shadow:
        inset 0 1px 2px rgba(45, 37, 41, .02);
}

.service-meta-label {
    display: block;

    margin-bottom: 4px;

    color: #95868c;

    font-size: 11px;

    text-transform: uppercase;

    letter-spacing: .35px;

    font-weight: 600;
}

.service-meta-box strong {
    color: #40373b;

    font-size: 13px;

    font-weight: 700;
}


/* =========================================================
   FORM CARD
========================================================= */

.service-edit-card {
    position: relative;

    background: #ffffff;

    border: 1px solid #eee5e8;

    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 4px 8px rgba(45, 37, 41, .03),
        0 12px 28px rgba(45, 37, 41, .07),
        0 25px 55px rgba(45, 37, 41, .05);
}

.service-edit-card-accent {
    height: 4px;

    background: linear-gradient(
        90deg,
        #8b5e6d,
        #b78b99,
        #8b5e6d
    );
}

.service-edit-card-header {
    padding: 19px 22px;

    border-bottom: 1px solid #eee5e8;
}

.service-edit-card-header h5 {
    margin: 0;

    color: #2d2529;

    font-size: 16px;
    font-weight: 700;
}

.service-edit-card-header span {
    display: block;

    margin-top: 4px;

    color: #9a8c91;

    font-size: 12px;
}

.service-edit-card-body {
    padding: 22px;
}


/* =========================================================
   FORM SECTION
========================================================= */

.service-form-section {
    padding-bottom: 24px;

    margin-bottom: 24px;

    border-bottom: 1px solid #eee7ea;
}

.service-form-section:last-of-type {
    border-bottom: none;

    margin-bottom: 0;
}

.service-section-heading {
    display: flex;
    align-items: center;

    gap: 10px;

    margin-bottom: 18px;
}

.service-section-line {
    width: 4px;
    height: 30px;

    flex-shrink: 0;

    border-radius: 10px;

    background: linear-gradient(
        180deg,
        #8b5e6d,
        #b78b99
    );
}

.service-section-heading h6 {
    margin: 0;

    color: #3b3035;

    font-size: 14px;
    font-weight: 700;
}

.service-section-heading small {
    display: block;

    margin-top: 3px;

    color: #9a8c91;

    font-size: 11px;
}


/* =========================================================
   LABELS
========================================================= */

.service-form-label {
    display: block;

    margin-bottom: 7px;

    color: #55484e;

    font-size: 13px;

    font-weight: 600;
}


/* =========================================================
   FORM CONTROLS
========================================================= */

.service-form-control {
    min-height: 44px;

    border-radius: 10px;

    border: 1px solid #ddd2d7;

    background: #ffffff;

    color: #40373b;

    font-size: 13px;

    box-shadow:
        inset 0 1px 2px rgba(45, 37, 41, .025),
        0 2px 5px rgba(45, 37, 41, .025);

    transition: all .2s ease;
}

.service-form-control:hover {
    border-color: #cfc0c6;
}

.service-form-control:focus {
    border-color: #8b5e6d;

    box-shadow:
        0 0 0 3px rgba(139, 94, 109, .09),
        inset 0 1px 2px rgba(45, 37, 41, .02);

    outline: none;
}

textarea.service-form-control {
    resize: vertical;
}

.service-textarea {
    min-height: 155px;
}


/* =========================================================
   PRICE INPUT
========================================================= */

.service-input-group {
    display: flex;
    align-items: stretch;
}

.service-input-prefix {
    min-width: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #ddd2d7;

    border-right: none;

    border-radius: 10px 0 0 10px;

    background: #f8f3f5;

    color: #8b5e6d;

    font-weight: 700;

    box-shadow:
        inset 0 1px 2px rgba(45, 37, 41, .02);
}

.service-price-input {
    border-radius: 0 10px 10px 0;
}


/* =========================================================
   HELP TEXT
========================================================= */

.service-help-text {
    margin-top: 7px;

    color: #9a8c91;

    font-size: 11px;
}


/* =========================================================
   CURRENT IMAGE
========================================================= */

.current-image-box,
.new-image-preview-wrapper {
    margin-top: 15px;

    padding: 12px;

    border-radius: 12px;

    background: #faf7f8;

    border: 1px solid #eee5e8;

    box-shadow:
        inset 0 1px 2px rgba(45, 37, 41, .02);
}

.current-image-heading {
    margin-bottom: 9px;

    color: #76666c;

    font-size: 11px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .3px;
}

.service-image-preview {
    width: 105px;
    height: 105px;

    object-fit: cover;

    display: block;

    border-radius: 12px;

    border: 1px solid #e4d9de;

    background: #ffffff;

    box-shadow:
        4px 4px 9px rgba(45, 37, 41, .08),
        -3px -3px 8px rgba(255, 255, 255, .95);
}


/* =========================================================
   ACTIONS
========================================================= */

.service-form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;

    gap: 10px;

    padding-top: 20px;

    border-top: 1px solid #eee7ea;
}

.service-cancel-btn {
    min-height: 43px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 9px 17px;

    border-radius: 10px;

    background: #ffffff;

    border: 1px solid #ddd2d7;

    color: #65575d;

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

    box-shadow:
        0 3px 7px rgba(45, 37, 41, .04);

    transition: all .2s ease;
}

.service-cancel-btn:hover {
    background: #f7f2f4;

    color: #55474d;

    border-color: #cfc0c6;

    transform: translateY(-1px);
}

.service-update-btn {
    min-height: 43px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 9px 18px;

    border: none;

    border-radius: 10px;

    background: linear-gradient(
        145deg,
        #8b5e6d,
        #76505e
    );

    color: #ffffff;

    font-size: 13px;

    font-weight: 600;

    box-shadow:
        0 5px 0 #62414d,
        0 9px 18px rgba(139, 94, 109, .18);

    transition: all .2s ease;
}

.service-update-btn:hover {
    color: #ffffff;

    transform: translateY(-2px);

    box-shadow:
        0 7px 0 #62414d,
        0 13px 24px rgba(139, 94, 109, .22);
}

.service-update-btn:active {
    transform: translateY(2px);

    box-shadow:
        0 2px 0 #62414d,
        0 5px 12px rgba(139, 94, 109, .15);
}

.service-update-btn:disabled {
    opacity: .75;

    cursor: not-allowed;

    transform: none;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 767px) {

    .service-edit-page {
        padding-top: 5px;
    }

    .service-edit-hero {
        flex-direction: column;
        align-items: stretch;

        padding: 20px 17px;
    }

    .service-edit-back-btn {
        width: 100%;
    }

    .service-info-card,
    .service-edit-card-body {
        padding: 16px;
    }

    .service-edit-card-header {
        padding: 17px 16px;
    }

    .service-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .service-cancel-btn,
    .service-update-btn {
        width: 100%;
    }

}


@media (max-width: 575px) {

    .service-edit-hero h2 {
        font-size: 21px;
    }

    .service-edit-title-icon {
        width: 43px;
        height: 43px;
    }

    .service-edit-title-icon i {
        font-size: 17px;
    }

    .service-info-heading {
        align-items: flex-start;
    }

}

</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       IMAGE PREVIEW
    ====================================================== */

    const imageInput = document.getElementById('image');
    const previewWrapper = document.getElementById('newImagePreviewWrapper');
    const previewImage = document.getElementById('newImagePreview');

    if (imageInput) {

        imageInput.addEventListener('change', function () {

            const file = this.files && this.files[0];

            if (!file) {
                previewWrapper.style.display = 'none';
                previewImage.removeAttribute('src');
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {

                alert('Please select a JPG, JPEG, PNG or WEBP image.');

                this.value = '';

                previewWrapper.style.display = 'none';
                previewImage.removeAttribute('src');

                return;
            }

            if (file.size > 2 * 1024 * 1024) {

                alert('Image size must not exceed 2MB.');

                this.value = '';

                previewWrapper.style.display = 'none';
                previewImage.removeAttribute('src');

                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                previewImage.src = event.target.result;

                previewWrapper.style.display = 'block';

            };

            reader.readAsDataURL(file);

        });

    }


    /* =====================================================
       FORM SUBMIT LOADING
    ====================================================== */

    const form = document.getElementById('serviceEditForm');
    const updateButton = document.getElementById('updateServiceBtn');
    const updateButtonText = document.getElementById('updateServiceBtnText');

    if (form) {

        form.addEventListener('submit', function (event) {

            if (!form.checkValidity()) {
                return;
            }

            if (updateButton) {
                updateButton.disabled = true;
            }

            if (updateButtonText) {
                updateButtonText.textContent = 'Updating Service...';
            }

        });

    }

});
</script>
@endpush

@endsection