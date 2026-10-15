@extends('admin.layout')

@section('title', 'Add Service | Salon Management')

@section('page-title', 'Add Service')

@section('content')

@push('styles')
<style>
    /* =========================================================
       ADD SERVICE - PREMIUM WHITE 3D UI
    ========================================================= */

    .service-create-page {
        width: 100%;
        padding: 4px 0 30px;
    }

    /* =========================================================
       MAIN CARD
    ========================================================= */

    .service-create-card {
        position: relative;
        max-width: 1050px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #eee5e9;
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 12px 28px rgba(45, 37, 41, 0.07),
            0 25px 55px rgba(45, 37, 41, 0.05);
    }

    .service-create-card::before {
        content: "";
        display: block;
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
    ========================================================= */

    .service-create-header {
        padding: 26px 30px 23px;
        border-bottom: 1px solid #eee6ea;
        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fcfafb 100%
            );
    }

    .service-create-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .service-create-icon {
        width: 46px;
        height: 46px;
        min-width: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: linear-gradient(
            145deg,
            #f8eef2,
            #ffffff
        );
        border: 1px solid #eadce2;
        color: #8b5e6d;
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }

    .service-create-title {
        margin: 0;
        font-weight: 700;
        color: #4e4549;
    }

    .service-create-subtitle {
        margin: 4px 0 0;
        color: #7b7176;
    }

    /* =========================================================
       BACK BUTTON
    ========================================================= */

    .service-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #ffffff;
        color: #5c5156;
        border: 1px solid #ddd3d8;
        border-radius: 11px;
        padding: 9px 15px;
        font-weight: 600;
        text-decoration: none;
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.04);
        transition: all 0.2s ease;
    }

    .service-back-btn:hover {
        color: #8b5e6d;
        border-color: #cdbac2;
        transform: translateY(-1px);
        box-shadow:
            0 6px 14px rgba(45, 37, 41, 0.08);
    }

    /* =========================================================
       FORM AREA
    ========================================================= */

    .service-create-body {
        padding: 30px;
        background: #ffffff;
    }

    .service-section {
        padding: 22px;
        border: 1px solid #eee5e9;
        border-radius: 17px;
        background: linear-gradient(
            145deg,
            #ffffff,
            #fcfafb
        );
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.025),
            0 10px 22px rgba(45, 37, 41, 0.045);
    }

    .service-section + .service-section {
        margin-top: 22px;
    }

    .service-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 20px;
        padding-bottom: 13px;
        border-bottom: 1px solid #eee6ea;
        color: #4e4549;
        font-weight: 700;
    }

    .service-section-title::before {
        content: "";
        width: 4px;
        height: 20px;
        border-radius: 4px;
        background: linear-gradient(
            180deg,
            #8b5e6d,
            #b78b99
        );
    }

    /* =========================================================
       LABELS
    ========================================================= */

    .service-create-page .form-label {
        display: block;
        margin-bottom: 8px;
        color: #4e4549;
        font-weight: 600;
    }

    .service-required {
        color: #dc3545;
        margin-left: 2px;
    }

    /* =========================================================
       INPUTS
    ========================================================= */

    .service-create-page .form-control,
    .service-create-page .form-select {
        min-height: 45px;
        padding: 10px 13px;
        border: 1px solid #ddd5d9;
        border-radius: 11px;
        background-color: #ffffff;
        color: #40383c;
        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.025),
            0 2px 5px rgba(45, 37, 41, 0.025);
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }

    .service-create-page .form-control:hover,
    .service-create-page .form-select:hover {
        border-color: #cdbbc3;
    }

    .service-create-page .form-control:focus,
    .service-create-page .form-select:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(45, 37, 41, 0.05);
        outline: none;
    }

    .service-create-page textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    /* =========================================================
       PRICE FIELD
    ========================================================= */

    .service-price-group {
        display: flex;
        align-items: stretch;
    }

    .service-price-prefix {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 48px;
        padding: 0 13px;
        border: 1px solid #ddd5d9;
        border-right: 0;
        border-radius: 11px 0 0 11px;
        background: #faf7f8;
        color: #8b5e6d;
        font-weight: 700;
        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.025);
    }

    .service-price-group .form-control {
        border-radius: 0 11px 11px 0;
    }

    .service-price-group:focus-within .service-price-prefix {
        border-color: #8b5e6d;
        color: #8b5e6d;
    }

    /* =========================================================
       FILE UPLOAD
    ========================================================= */

    .service-file-wrap {
        position: relative;
    }

    .service-create-page input[type="file"].form-control {
        padding-top: 9px;
        padding-bottom: 9px;
    }

    .service-help-text {
        display: block;
        margin-top: 7px;
        color: #81777c;
    }

    .service-image-preview {
        display: none;
        margin-top: 13px;
        padding: 10px;
        width: 125px;
        height: 105px;
        border-radius: 13px;
        border: 1px solid #e5dce1;
        background: #ffffff;
        box-shadow:
            0 5px 14px rgba(45, 37, 41, 0.07);
    }

    .service-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 9px;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .service-status-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 17px;
        border: 1px solid #e8dfe3;
        border-radius: 13px;
        background: #ffffff;
        box-shadow:
            0 3px 8px rgba(45, 37, 41, 0.035);
    }

    .service-status-info strong {
        display: block;
        color: #4e4549;
        font-weight: 600;
    }

    .service-status-info small {
        color: #81777c;
    }

    .service-create-page .form-switch {
        padding-left: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .service-create-page .form-switch .form-check-input {
        float: none;
        margin-left: 0;
        width: 2.65em;
        height: 1.4em;
        cursor: pointer;
        border-color: #cfc4c9;
        box-shadow: inset 0 1px 2px rgba(45, 37, 41, 0.08);
    }

    .service-create-page .form-switch .form-check-input:checked {
        background-color: #8b5e6d;
        border-color: #8b5e6d;
    }

    .service-create-page .form-check-label {
        margin: 0;
        color: #4e4549;
        font-weight: 600;
        cursor: pointer;
    }

    /* =========================================================
       ERROR ALERT
    ========================================================= */

    .service-error-alert {
        margin-bottom: 22px;
        padding: 17px 19px;
        border: 1px solid #f0cfd5;
        border-radius: 14px;
        background: linear-gradient(
            145deg,
            #fffafa,
            #fff5f6
        );
        color: #842029;
        box-shadow:
            0 5px 14px rgba(132, 32, 41, 0.06);
    }

    .service-error-alert strong {
        color: #842029;
    }

    .service-error-alert ul {
        padding-left: 20px;
    }

    /* =========================================================
       ACTION BAR
    ========================================================= */

    .service-action-bar {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 24px;
        padding-top: 22px;
        border-top: 1px solid #eee6ea;
    }

    .service-cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 43px;
        padding: 9px 17px;
        border: 1px solid #d8cfd4;
        border-radius: 11px;
        background: #ffffff;
        color: #5d5358;
        text-decoration: none;
        font-weight: 600;
        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.035);
        transition: all 0.2s ease;
    }

    .service-cancel-btn:hover {
        color: #4e4549;
        border-color: #c7bac0;
        background: #faf8f9;
        transform: translateY(-1px);
    }

    .service-save-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 43px;
        padding: 9px 20px;
        border: none;
        border-radius: 11px;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        color: #ffffff;
        font-weight: 600;
        box-shadow:
            0 5px 0 #62414d,
            0 10px 20px rgba(139, 94, 109, 0.18);
        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease;
    }

    .service-save-btn:hover {
        background: linear-gradient(
            135deg,
            #956879,
            #7d5664
        );
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 7px 0 #62414d,
            0 13px 24px rgba(139, 94, 109, 0.22);
    }

    .service-save-btn:active {
        transform: translateY(3px);
        box-shadow:
            0 2px 0 #62414d,
            0 5px 12px rgba(139, 94, 109, 0.15);
    }

    .service-save-btn:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .service-create-page {
            padding-bottom: 20px;
        }

        .service-create-header {
            padding: 22px 20px;
        }

        .service-create-body {
            padding: 20px;
        }

        .service-section {
            padding: 18px;
        }

        .service-create-heading {
            align-items: flex-start;
        }

        .service-back-btn {
            width: 100%;
            justify-content: center;
        }

        .service-status-box {
            align-items: flex-start;
            flex-direction: column;
        }

        .service-action-bar {
            flex-direction: column-reverse;
        }

        .service-action-bar a,
        .service-action-bar button {
            width: 100%;
        }
    }
</style>
@endpush


<div class="service-create-page">

    <div class="service-create-card">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="service-create-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="service-create-heading">

                    <div class="service-create-icon">
                        <i class="bi bi-stars"></i>
                    </div>

                    <div>
                        <h3 class="service-create-title">
                            Add New Service
                        </h3>

                        <p class="service-create-subtitle">
                            Add a new service to your salon.
                        </p>
                    </div>

                </div>

                <a
                    href="{{ route('admin.services.index') }}"
                    class="service-back-btn"
                >
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>

            </div>

        </div>


        {{-- =====================================================
             BODY
        ====================================================== --}}
        <div class="service-create-body">

            {{-- =================================================
                 VALIDATION ERRORS
            ================================================== --}}
            @if($errors->any())

                <div class="service-error-alert">

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

                </div>

            @endif


            {{-- =================================================
                 FORM
            ================================================== --}}
            <form
                method="POST"
                action="{{ route('admin.services.store') }}"
                enctype="multipart/form-data"
                id="serviceCreateForm"
            >

                @csrf


                {{-- =================================================
                     BASIC SERVICE DETAILS
                ================================================== --}}
                <div class="service-section">

                    <div class="service-section-title">
                        Service Details
                    </div>

                    <div class="row g-4">

                        {{-- SALON --}}
                        <div class="col-md-6">

                            <label
                                for="salon_id"
                                class="form-label"
                            >
                                Salon
                                <span class="service-required">*</span>
                            </label>

                            <select
                                name="salon_id"
                                id="salon_id"
                                class="form-select"
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

                        </div>


                        {{-- SERVICE NAME --}}
                        <div class="col-md-6">

                            <label
                                for="name"
                                class="form-label"
                            >
                                Service Name
                                <span class="service-required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="e.g. Haircut & Styling"
                                required
                            >

                        </div>


                        {{-- CATEGORY --}}
                        <div class="col-md-6">

                            <label
                                for="category"
                                class="form-label"
                            >
                                Category
                                <span class="service-required">*</span>
                            </label>

                            <select
                                name="category"
                                id="category"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Category
                                </option>

                                <option
                                    value="Hair"
                                    {{ old('category') == 'Hair' ? 'selected' : '' }}
                                >
                                    Hair
                                </option>

                                <option
                                    value="Facial"
                                    {{ old('category') == 'Facial' ? 'selected' : '' }}
                                >
                                    Facial
                                </option>

                                <option
                                    value="Skin Care"
                                    {{ old('category') == 'Skin Care' ? 'selected' : '' }}
                                >
                                    Skin Care
                                </option>

                                <option
                                    value="Makeup"
                                    {{ old('category') == 'Makeup' ? 'selected' : '' }}
                                >
                                    Makeup
                                </option>

                                <option
                                    value="Nails"
                                    {{ old('category') == 'Nails' ? 'selected' : '' }}
                                >
                                    Nails
                                </option>

                                <option
                                    value="Spa"
                                    {{ old('category') == 'Spa' ? 'selected' : '' }}
                                >
                                    Spa
                                </option>

                                <option
                                    value="Other"
                                    {{ old('category') == 'Other' ? 'selected' : '' }}
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        {{-- PRICE --}}
                        <div class="col-md-6">

                            <label
                                for="price"
                                class="form-label"
                            >
                                Price (₹)
                                <span class="service-required">*</span>
                            </label>

                            <div class="service-price-group">

                                <span class="service-price-prefix">
                                    ₹
                                </span>

                                <input
                                    type="number"
                                    name="price"
                                    id="price"
                                    class="form-control"
                                    value="{{ old('price') }}"
                                    placeholder="e.g. 650"
                                    min="0"
                                    step="0.01"
                                    required
                                >

                            </div>

                        </div>


                        {{-- DURATION --}}
                        <div class="col-md-6">

                            <label
                                for="duration"
                                class="form-label"
                            >
                                Duration
                                <span class="service-required">*</span>
                            </label>

                            <select
                                name="duration"
                                id="duration"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Select Duration
                                </option>

                                <option
                                    value="30"
                                    {{ old('duration') == '30' ? 'selected' : '' }}
                                >
                                    30 Minutes
                                </option>

                                <option
                                    value="45"
                                    {{ old('duration') == '45' ? 'selected' : '' }}
                                >
                                    45 Minutes
                                </option>

                                <option
                                    value="60"
                                    {{ old('duration') == '60' ? 'selected' : '' }}
                                >
                                    1 Hour
                                </option>

                                <option
                                    value="90"
                                    {{ old('duration') == '90' ? 'selected' : '' }}
                                >
                                    1.5 Hours
                                </option>

                                <option
                                    value="120"
                                    {{ old('duration') == '120' ? 'selected' : '' }}
                                >
                                    2 Hours
                                </option>

                                <option
                                    value="180"
                                    {{ old('duration') == '180' ? 'selected' : '' }}
                                >
                                    3 Hours
                                </option>

                            </select>

                        </div>


                        {{-- SERVICE IMAGE --}}
                        <div class="col-md-6">

                            <label
                                for="image"
                                class="form-label"
                            >
                                Service Image
                            </label>

                            <div class="service-file-wrap">

                                <input
                                    type="file"
                                    name="image"
                                    id="image"
                                    class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp"
                                >

                                <small class="service-help-text">
                                    JPG, JPEG, PNG or WEBP. Maximum 2MB.
                                </small>

                                <div
                                    class="service-image-preview"
                                    id="serviceImagePreview"
                                >
                                    <img
                                        src=""
                                        alt="Service Preview"
                                        id="servicePreviewImage"
                                    >
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DESCRIPTION & STATUS
                ================================================== --}}
                <div class="service-section">

                    <div class="service-section-title">
                        Additional Information
                    </div>

                    <div class="row g-4">

                        {{-- DESCRIPTION --}}
                        <div class="col-12">

                            <label
                                for="description"
                                class="form-label"
                            >
                                Description
                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="form-control"
                                placeholder="Enter service description..."
                            >{{ old('description') }}</textarea>

                        </div>


                        {{-- STATUS --}}
                        <div class="col-12">

                            <label class="form-label">
                                Status
                            </label>

                            <div class="service-status-box">

                                <div class="service-status-info">

                                    <strong>
                                        Service Availability
                                    </strong>

                                    <small>
                                        Active services can be selected while creating appointments and bills.
                                    </small>

                                </div>

                                <div class="form-check form-switch">

                                    {{-- Hidden value so inactive sends 0 --}}
                                    <input
                                        type="hidden"
                                        name="status"
                                        value="0"
                                    >

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="status"
                                        value="1"
                                        id="status"
                                        {{ old('status', 1) ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label"
                                        for="status"
                                    >
                                        Active
                                    </label>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     BUTTONS
                ================================================== --}}
                <div class="service-action-bar">

                    <a
                        href="{{ route('admin.services.index') }}"
                        class="service-cancel-btn"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="service-save-btn"
                        id="saveServiceBtn"
                    >
                        <i class="bi bi-check-lg"></i>
                        <span id="saveServiceText">
                            Save Service
                        </span>
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* =====================================================
           SERVICE IMAGE LIVE PREVIEW
        ====================================================== */

        const imageInput = document.getElementById('image');
        const previewBox = document.getElementById('serviceImagePreview');
        const previewImage = document.getElementById('servicePreviewImage');

        if (imageInput) {

            imageInput.addEventListener('change', function (event) {

                const file = event.target.files[0];

                if (!file) {
                    previewBox.style.display = 'none';
                    previewImage.removeAttribute('src');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    previewBox.style.display = 'none';
                    previewImage.removeAttribute('src');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewBox.style.display = 'block';
                };

                reader.readAsDataURL(file);
            });
        }


        /* =====================================================
           SUBMIT LOADING STATE
        ====================================================== */

        const form = document.getElementById('serviceCreateForm');
        const saveButton = document.getElementById('saveServiceBtn');
        const saveText = document.getElementById('saveServiceText');

        if (form && saveButton) {

            form.addEventListener('submit', function () {

                if (!form.checkValidity()) {
                    return;
                }

                saveButton.disabled = true;

                if (saveText) {
                    saveText.textContent = 'Saving Service...';
                }

            });
        }

    });
</script>
@endpush

@endsection