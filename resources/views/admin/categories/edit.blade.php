@extends('admin.layout')

@section('title', 'Edit Category | Salon Management')

@section('page-title', 'Edit Category')

@section('content')

<div class="container-fluid category-edit-page">

    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if ($errors->any())

        <div class="category-alert">

            <div class="category-alert-icon">
                <i class="bi bi-exclamation-circle"></i>
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

    @endif


    {{-- =========================================================
        MAIN CARD
    ========================================================== --}}
    <div class="category-card">

        {{-- Top Accent --}}
        <div class="category-card-accent"></div>


        {{-- =====================================================
            CARD HEADER
        ====================================================== --}}
        <div class="category-card-header">

            <div class="category-heading-row">

                <div class="category-heading-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>

                    <h5 class="mb-1">
                        Edit Category
                    </h5>

                    <small>
                        Update category information.
                    </small>

                </div>

            </div>


            <a
                href="{{ route('admin.categories.index') }}"
                class="category-back-btn"
            >

                <i class="bi bi-arrow-left"></i>

                <span>Back</span>

            </a>

        </div>


        {{-- =====================================================
            CARD BODY
        ====================================================== --}}
        <div class="category-card-body">

            <form
                action="{{ route('admin.categories.update', $category) }}"
                method="POST"
                enctype="multipart/form-data"
                id="categoryForm"
            >

                @csrf

                @method('PUT')


                <div class="row g-4">


                    {{-- =================================================
                        CATEGORY NAME
                    ================================================== --}}
                    <div class="col-md-8">

                        <label
                            for="name"
                            class="form-label"
                        >

                            Category Name

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control category-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $category->name) }}"
                            required
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        SORT ORDER
                    ================================================== --}}
                    <div class="col-md-4">

                        <label
                            for="sort_order"
                            class="form-label"
                        >
                            Sort Order
                        </label>

                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            class="form-control category-input @error('sort_order') is-invalid @enderror"
                            value="{{ old('sort_order', $category->sort_order) }}"
                            min="0"
                        >

                        @error('sort_order')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="category-help-text">
                            Lower number appears first.
                        </small>

                    </div>


                    {{-- =================================================
                        DESCRIPTION
                    ================================================== --}}
                    <div class="col-12">

                        <label
                            for="description"
                            class="form-label"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="form-control category-input category-textarea @error('description') is-invalid @enderror"
                            placeholder="Enter category description..."
                        >{{ old('description', $category->description) }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        CURRENT IMAGE
                    ================================================== --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Current Image
                        </label>


                        @if ($category->image)

                            <div class="current-image-box">

                                <img
                                    src="{{ $category->image }}"
                                    alt="{{ $category->name }}"
                                    class="category-preview"
                                >

                            </div>

                        @else

                            <div class="no-image">

                                <i class="bi bi-image"></i>

                                <span>No Image</span>

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                        NEW IMAGE
                    ================================================== --}}
                    <div class="col-md-4">

                        <label
                            for="image"
                            class="form-label"
                        >
                            Change Image
                        </label>


                        <input
                            type="file"
                            id="image"
                            name="image"
                            class="form-control category-input @error('image') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >


                        @error('image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        <small class="category-help-text">
                            Leave empty to keep current image.
                        </small>


                        {{-- =================================================
                            NEW IMAGE PREVIEW
                        ================================================== --}}
                        <div
                            id="imagePreviewWrapper"
                            class="category-preview-wrapper mt-3 d-none"
                        >

                            <div class="category-preview-title">
                                New Image Preview
                            </div>

                            <div class="category-preview-box">

                                <img
                                    id="imagePreview"
                                    src="#"
                                    alt="New Category Preview"
                                    class="category-preview"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-md-4">

                        <label
                            for="status"
                            class="form-label"
                        >

                            Status

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            id="status"
                            name="status"
                            class="form-select category-input @error('status') is-invalid @enderror"
                            required
                        >

                            <option
                                value="active"
                                {{ old('status', $category->status) === 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}
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


                {{-- =================================================
                    FORM ACTIONS
                ================================================== --}}
                <div class="category-form-actions">

                    <a
                        href="{{ route('admin.categories.index') }}"
                        class="category-cancel-btn"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="category-save-btn"
                        id="updateCategoryBtn"
                    >

                        <i class="bi bi-check-circle"></i>

                        <span>Update Category</span>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
    PAGE STYLE
============================================================= --}}
@push('styles')

<style>

    /* =========================================================
       MAIN PAGE
    ========================================================= */

    .category-edit-page {
        padding-bottom: 35px;
    }


    /* =========================================================
       ERROR ALERT
    ========================================================= */

    .category-alert {

        display: flex;
        align-items: flex-start;

        gap: 12px;

        margin-bottom: 20px;
        padding: 15px 17px;

        background: #fff7f8;

        color: #842029;

        border: 1px solid rgba(220, 53, 69, 0.15);

        border-radius: 14px;

        box-shadow:
            0 4px 10px rgba(45, 37, 41, 0.04),
            0 10px 20px rgba(45, 37, 41, 0.04);
    }


    .category-alert-icon {

        flex-shrink: 0;

        width: 32px;
        height: 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #ffffff;

        color: #dc3545;

        box-shadow:
            0 3px 8px rgba(45, 37, 41, 0.06);
    }


    .category-alert ul {
        padding-left: 18px;
    }


    /* =========================================================
       MAIN CARD
    ========================================================= */

    .category-card {

        position: relative;

        background: #ffffff;

        border-radius: 20px;

        border: 1px solid rgba(139, 94, 109, 0.10);

        overflow: hidden;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 12px 28px rgba(45, 37, 41, 0.07),
            0 25px 55px rgba(45, 37, 41, 0.06);

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;
    }


    .category-card:hover {

        transform: translateY(-2px);

        box-shadow:
            0 6px 12px rgba(45, 37, 41, 0.04),
            0 18px 35px rgba(45, 37, 41, 0.09),
            0 30px 65px rgba(45, 37, 41, 0.07);
    }


    /* =========================================================
       TOP ACCENT
    ========================================================= */

    .category-card-accent {

        height: 5px;

        background: linear-gradient(
            90deg,
            #8b5e6d 0%,
            #b78b99 50%,
            #8b5e6d 100%
        );
    }


    /* =========================================================
       HEADER
    ========================================================= */

    .category-card-header {

        padding: 22px 25px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        background: #ffffff;

        border-bottom: 1px solid #f0edef;
    }


    .category-heading-row {

        display: flex;

        align-items: center;

        gap: 13px;
    }


    .category-heading-icon {

        width: 44px;
        height: 44px;

        flex-shrink: 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #ffffff;

        color: #8b5e6d;

        border: 1px solid rgba(139, 94, 109, 0.10);

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.04),
            0 9px 16px rgba(45, 37, 41, 0.06);
    }


    .category-heading-icon i {
        font-size: 20px;
    }


    .category-card-header h5 {

        color: #2d2529;

        font-weight: 700;
    }


    .category-card-header small {
        color: #777;
    }


    /* =========================================================
       BACK BUTTON
    ========================================================= */

    .category-back-btn {

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 7px;

        min-height: 40px;

        padding: 8px 15px;

        color: #5f5559;

        background: #ffffff;

        border: 1px solid #ded9dc;

        border-radius: 10px;

        text-decoration: none;

        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.04);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            color 0.2s ease,
            border-color 0.2s ease;
    }


    .category-back-btn:hover {

        color: #8b5e6d;

        border-color: #cdbbc2;

        transform: translateY(-2px);

        box-shadow:
            0 6px 12px rgba(45, 37, 41, 0.08);
    }


    /* =========================================================
       CARD BODY
    ========================================================= */

    .category-card-body {
        padding: 28px 25px;
    }


    /* =========================================================
       LABEL
    ========================================================= */

    .category-edit-page .form-label {

        font-weight: 600;

        color: #2d2529;

        margin-bottom: 8px;
    }


    /* =========================================================
       INPUT
    ========================================================= */

    .category-input {

        min-height: 45px;

        border-radius: 11px;

        border: 1px solid #e1dde0;

        background: #ffffff;

        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.02),
            0 2px 5px rgba(45, 37, 41, 0.025);

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }


    .category-input:hover {
        border-color: #d2c6cb;
    }


    .category-input:focus {

        border-color: #8b5e6d;

        box-shadow:
            0 0 0 3px rgba(139, 94, 109, 0.10),
            0 5px 12px rgba(45, 37, 41, 0.06);

        transform: translateY(-1px);
    }


    .category-input.is-invalid:focus {

        border-color: #dc3545;

        box-shadow:
            0 0 0 3px rgba(220, 53, 69, 0.08);
    }


    .category-textarea {

        min-height: 110px;

        resize: vertical;
    }


    /* =========================================================
       HELP TEXT
    ========================================================= */

    .category-help-text {

        display: block;

        margin-top: 7px;

        color: #777;
    }


    /* =========================================================
       CURRENT IMAGE
    ========================================================= */

    .current-image-box {

        display: inline-flex;

        padding: 6px;

        background: #ffffff;

        border-radius: 13px;

        border: 1px solid #e6e1e4;

        box-shadow:
            0 4px 9px rgba(45, 37, 41, 0.06),
            0 10px 18px rgba(45, 37, 41, 0.05);
    }


    .category-preview {

        width: 180px;

        height: 130px;

        object-fit: cover;

        border-radius: 9px;

        display: block;
    }


    /* =========================================================
       NO IMAGE
    ========================================================= */

    .no-image {

        width: 192px;

        height: 142px;

        border: 1px dashed #cfc7cb;

        border-radius: 13px;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        color: #999;

        gap: 6px;

        background: #faf8f9;

        box-shadow:
            inset 0 1px 3px rgba(45, 37, 41, 0.02);
    }


    .no-image i {
        font-size: 30px;
    }


    /* =========================================================
       NEW IMAGE PREVIEW
    ========================================================= */

    .category-preview-wrapper {

        padding: 15px;

        border-radius: 14px;

        background: #faf8f9;

        border: 1px solid #eee8eb;

        box-shadow:
            inset 0 1px 2px rgba(45, 37, 41, 0.02);
    }


    .category-preview-title {

        margin-bottom: 10px;

        font-weight: 600;

        color: #2d2529;
    }


    .category-preview-box {

        display: inline-flex;

        padding: 6px;

        background: #ffffff;

        border-radius: 13px;

        border: 1px solid #e6e1e4;

        box-shadow:
            0 4px 9px rgba(45, 37, 41, 0.06),
            0 10px 18px rgba(45, 37, 41, 0.05);
    }


    /* =========================================================
       FORM ACTIONS
    ========================================================= */

    .category-form-actions {

        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 11px;

        margin-top: 28px;

        padding-top: 22px;

        border-top: 1px solid #eee9eb;
    }


    /* =========================================================
       CANCEL
    ========================================================= */

    .category-cancel-btn {

        min-height: 43px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 9px 19px;

        border-radius: 10px;

        background: #ffffff;

        border: 1px solid #ddd8db;

        color: #62595d;

        text-decoration: none;

        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.04);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            border-color 0.2s ease;
    }


    .category-cancel-btn:hover {

        color: #4f474a;

        border-color: #c9c0c4;

        transform: translateY(-2px);

        box-shadow:
            0 6px 12px rgba(45, 37, 41, 0.07);
    }


    /* =========================================================
       UPDATE BUTTON
    ========================================================= */

    .category-save-btn {

        min-height: 43px;

        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 6px;

        padding: 9px 20px;

        border: 0;

        border-radius: 10px;

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );

        box-shadow:
            0 5px 0 #62414d,
            0 10px 20px rgba(139, 94, 109, 0.18);

        cursor: pointer;

        transition:
            transform 0.15s ease,
            box-shadow 0.15s ease,
            background 0.2s ease;
    }


    .category-save-btn:hover {

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #966778,
            #7d5362
        );

        transform: translateY(-2px);

        box-shadow:
            0 7px 0 #62414d,
            0 14px 24px rgba(139, 94, 109, 0.21);
    }


    .category-save-btn:active {

        transform: translateY(3px);

        box-shadow:
            0 2px 0 #62414d,
            0 5px 10px rgba(139, 94, 109, 0.14);
    }


    .category-save-btn:disabled {

        opacity: 0.75;

        cursor: not-allowed;

        transform: none;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .category-card-header {

            align-items: flex-start;

            flex-direction: column;
        }


        .category-back-btn {
            width: 100%;
        }


        .category-card-body {
            padding: 22px 18px;
        }


        .category-form-actions {

            flex-direction: column-reverse;
        }


        .category-cancel-btn,
        .category-save-btn {

            width: 100%;
        }

    }


    @media (max-width: 575.98px) {

        .category-card {
            border-radius: 16px;
        }


        .category-card-header {
            padding: 19px;
        }


        .category-card-body {
            padding: 20px 15px;
        }


        .category-heading-row {
            align-items: flex-start;
        }


        .category-heading-icon {

            width: 40px;
            height: 40px;
        }


        .category-preview {

            width: 150px;
            height: 110px;
        }


        .no-image {

            width: 162px;
            height: 122px;
        }

    }

</style>

@endpush


{{-- =============================================================
    IMAGE PREVIEW + FORM UX
============================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const imageInput =
        document.getElementById('image');

    const previewWrapper =
        document.getElementById('imagePreviewWrapper');

    const preview =
        document.getElementById('imagePreview');

    const categoryForm =
        document.getElementById('categoryForm');

    const updateButton =
        document.getElementById('updateCategoryBtn');


    /* =========================================================
       NEW IMAGE PREVIEW
    ========================================================= */

    if (
        imageInput &&
        previewWrapper &&
        preview
    ) {

        imageInput.addEventListener(
            'change',
            function (event) {

                const file =
                    event.target.files[0];


                if (!file) {

                    previewWrapper.classList.add('d-none');

                    preview.src = '#';

                    return;
                }


                /* ---------------------------------------------
                   FILE SIZE VALIDATION
                --------------------------------------------- */

                const maxSize =
                    2 * 1024 * 1024;


                if (file.size > maxSize) {

                    alert(
                        'Category image must not exceed 2 MB.'
                    );

                    imageInput.value = '';

                    previewWrapper.classList.add('d-none');

                    preview.src = '#';

                    return;
                }


                /* ---------------------------------------------
                   FILE TYPE VALIDATION
                --------------------------------------------- */

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                if (!allowedTypes.includes(file.type)) {

                    alert(
                        'Please select a JPG, JPEG, PNG or WEBP image.'
                    );

                    imageInput.value = '';

                    previewWrapper.classList.add('d-none');

                    preview.src = '#';

                    return;
                }


                /* ---------------------------------------------
                   FILE READER
                --------------------------------------------- */

                const reader =
                    new FileReader();


                reader.onload =
                    function (e) {

                        preview.src =
                            e.target.result;

                        previewWrapper
                            .classList
                            .remove('d-none');

                    };


                reader.readAsDataURL(file);

            }
        );

    }


    /* =========================================================
       FORM SUBMIT LOADING
    ========================================================= */

    if (
        categoryForm &&
        updateButton
    ) {

        categoryForm.addEventListener(
            'submit',
            function () {

                if (!categoryForm.checkValidity()) {
                    return;
                }


                updateButton.disabled = true;


                updateButton.innerHTML = `
                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                        aria-hidden="true"
                    ></span>

                    <span>Updating...</span>
                `;

            }
        );

    }

});

</script>

@endpush

@endsection