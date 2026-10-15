@extends('admin.layout')

@section('title', 'View Package | Salon Management')
@section('page-title', 'View Package')

@section('content')

<div class="container-fluid px-0 package-view-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="package-view-header mb-4">

        <div class="package-view-header-content">

            <div class="package-view-title">

                <div class="package-view-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <h4 class="mb-1">
                        {{ $package->name }}
                    </h4>

                    <p class="text-muted mb-0">
                        Package details
                    </p>
                </div>

            </div>


            <div class="package-view-actions">

                <a href="{{ route('admin.packages.edit', $package->id) }}"
                   class="btn package-edit-main-btn">

                    <i class="bi bi-pencil me-1"></i>
                    Edit

                </a>


                <a href="{{ route('admin.packages.index') }}"
                   class="btn package-back-btn">

                    <i class="bi bi-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PACKAGE INFORMATION CARD
    ========================================================== --}}
    <div class="card package-info-card border-0">

        <div class="card-header package-info-header">

            <div>

                <h5 class="mb-1">
                    Package Information
                </h5>

                <span class="package-info-subtitle">
                    Complete information about this package
                </span>

            </div>

        </div>


        <div class="card-body package-info-body">

            <div class="row g-4">


                {{-- =================================================
                     PACKAGE NAME
                ================================================== --}}
                <div class="col-md-6">

                    <div class="package-detail-box">

                        <label>
                            Package Name
                        </label>

                        <div class="package-detail-value">
                            {{ $package->name }}
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PACKAGE CODE
                ================================================== --}}
                <div class="col-md-6">

                    <div class="package-detail-box">

                        <label>
                            Package Code
                        </label>

                        <div class="package-code-value">

                            @if($package->code)

                                {{ $package->code }}

                            @else

                                -

                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     PRICE
                ================================================== --}}
                <div class="col-md-6">

                    <div class="package-detail-box package-price-box">

                        <label>
                            Price
                        </label>

                        <div class="package-price-value">
                            ₹{{ number_format($package->price, 2) }}
                        </div>

                    </div>

                </div>


                {{-- =================================================
                     VALIDITY
                ================================================== --}}
                <div class="col-md-3">

                    <div class="package-detail-box">

                        <label>
                            Validity
                        </label>

                        <div class="package-detail-value">

                            {{ $package->validity_days }}

                            <span class="package-unit">
                                Days
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     TOTAL USES
                ================================================== --}}
                <div class="col-md-3">

                    <div class="package-detail-box">

                        <label>
                            Total Uses
                        </label>

                        <div class="package-detail-value">

                            {{ $package->total_uses }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DESCRIPTION
                ================================================== --}}
                <div class="col-12">

                    <div class="package-detail-box package-description-box">

                        <label>
                            Description
                        </label>

                        <div class="package-description">

                            {{ $package->description ?? 'No description available.' }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}
                <div class="col-12">

                    <div class="package-detail-box">

                        <label>
                            Status
                        </label>

                        <div>

                            @if($package->status)

                                <span class="package-status package-status-active">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Active

                                </span>

                            @else

                                <span class="package-status package-status-inactive">

                                    <i class="bi bi-x-circle me-1"></i>

                                    Inactive

                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CREATED AT
                ================================================== --}}
                <div class="col-md-6">

                    <div class="package-detail-box">

                        <label>
                            Created At
                        </label>

                        <div class="package-date-value">

                            {{ $package->created_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     LAST UPDATED
                ================================================== --}}
                <div class="col-md-6">

                    <div class="package-detail-box">

                        <label>
                            Last Updated
                        </label>

                        <div class="package-date-value">

                            {{ $package->updated_at?->format('d M Y, h:i A') }}

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

    /* =========================================================
       PAGE
    ========================================================== */

    .package-view-page {
        width: 100%;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .package-view-header {
        position: relative;
        overflow: hidden;

        background: #ffffff;

        border-radius: 19px;

        padding: 22px 24px;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .07),
            0 25px 55px rgba(45, 37, 41, .05);
    }

    .package-view-header::before {
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

    .package-view-header-content {
        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;
    }


    /* =========================================================
       TITLE
    ========================================================== */

    .package-view-title {
        display: flex;

        align-items: center;

        gap: 13px;
    }

    .package-view-icon {
        width: 46px;
        height: 46px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: linear-gradient(
            145deg,
            #f8f1f4,
            #eee2e7
        );

        color: #8b5e6d;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 6px 14px rgba(139,94,109,.12);
    }

    .package-view-icon i {
        font-size: 20px;
    }


    /* =========================================================
       HEADER BUTTONS
    ========================================================== */

    .package-view-actions {
        display: flex;

        align-items: center;

        gap: 10px;
    }

    .package-edit-main-btn {
        border: 0;

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );

        border-radius: 11px;

        padding: 10px 17px;

        box-shadow:
            0 5px 0 #62414d,
            0 10px 20px rgba(139,94,109,.16);

        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .package-edit-main-btn:hover {
        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
            0 7px 0 #62414d,
            0 15px 25px rgba(139,94,109,.20);
    }

    .package-edit-main-btn:active {
        transform: translateY(2px);

        box-shadow:
            0 2px 0 #62414d,
            0 6px 12px rgba(139,94,109,.12);
    }


    .package-back-btn {
        background: #ffffff;

        color: #62575d;

        border: 1px solid #ddd4d8;

        border-radius: 11px;

        padding: 10px 17px;

        box-shadow:
            0 4px 10px rgba(45,37,41,.06);

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .package-back-btn:hover {
        background: #faf7f8;

        color: #76505e;

        transform: translateY(-2px);

        box-shadow:
            0 8px 18px rgba(45,37,41,.09);
    }


    /* =========================================================
       INFORMATION CARD
    ========================================================== */

    .package-info-card {
        position: relative;

        overflow: hidden;

        background: #ffffff;

        border-radius: 19px !important;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .07),
            0 25px 55px rgba(45, 37, 41, .05);
    }

    .package-info-card::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;
        right: 0;

        height: 3px;

        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }


    /* =========================================================
       CARD HEADER
    ========================================================== */

    .package-info-header {
        background: #ffffff !important;

        border-bottom: 1px solid #eee8eb !important;

        padding: 18px 22px !important;
    }

    .package-info-header h5 {
        font-weight: 600;
    }

    .package-info-subtitle {
        color: #8a8085;
    }


    /* =========================================================
       CARD BODY
    ========================================================== */

    .package-info-body {
        padding: 24px !important;
    }


    /* =========================================================
       DETAIL BOX
    ========================================================== */

    .package-detail-box {
        min-height: 82px;

        padding: 16px 17px;

        background: #ffffff;

        border: 1px solid #eee7ea;

        border-radius: 13px;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 4px 10px rgba(45,37,41,.035);

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }

    .package-detail-box:hover {
        transform: translateY(-2px);

        border-color: #e0d1d7;

        box-shadow:
            0 7px 18px rgba(45,37,41,.07);
    }

    .package-detail-box label {
        display: block;

        margin-bottom: 7px;

        color: #8a8085;
    }


    /* =========================================================
       VALUES
    ========================================================== */

    .package-detail-value {
        font-weight: 600;

        color: #342d31;
    }

    .package-code-value {
        display: inline-block;

        padding: 5px 9px;

        border-radius: 8px;

        background: #f7f3f5;

        color: #76505e;

        font-weight: 600;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9);
    }

    .package-price-box {
        background: linear-gradient(
            145deg,
            #ffffff,
            #fcf8fa
        );
    }

    .package-price-value {
        font-weight: 700;

        color: #76505e;

        font-size: 18px;
    }

    .package-unit {
        color: #8a8085;

        font-weight: 500;
    }


    /* =========================================================
       DESCRIPTION
    ========================================================== */

    .package-description-box {
        min-height: 105px;
    }

    .package-description {
        color: #4d4549;

        line-height: 1.6;

        white-space: pre-line;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .package-status {
        display: inline-flex;

        align-items: center;

        padding: 7px 12px;

        border-radius: 9px;

        font-weight: 600;
    }

    .package-status-active {
        background: #eaf7ef;

        color: #198754;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.8),
            0 3px 8px rgba(25,135,84,.06);
    }

    .package-status-inactive {
        background: #fcebec;

        color: #c0394b;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.8),
            0 3px 8px rgba(192,57,75,.06);
    }


    /* =========================================================
       DATES
    ========================================================== */

    .package-date-value {
        font-weight: 500;

        color: #41383d;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 768px) {

        .package-view-header {
            padding: 19px;
        }

        .package-view-header-content {
            align-items: flex-start;

            flex-direction: column;
        }

        .package-view-actions {
            width: 100%;
        }

        .package-view-actions .btn {
            flex: 1;

            text-align: center;
        }

        .package-info-body {
            padding: 17px !important;
        }

    }


    @media (max-width: 480px) {

        .package-view-title {
            align-items: flex-start;
        }

        .package-view-actions {
            flex-direction: column;
        }

        .package-view-actions .btn {
            width: 100%;
        }

    }

</style>

@endpush