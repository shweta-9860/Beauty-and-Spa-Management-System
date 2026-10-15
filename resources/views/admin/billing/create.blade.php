@extends('admin.layout')

@section('title', 'Create Bill | Salon Management')
@section('page-title', 'Create Bill')

@section('content')

<style>
    /* =========================================================
       PREMIUM 3D WHITE BILLING UI
       Font + font-size intentionally unchanged
    ========================================================== */

    .billing-page {
        --primary: #8b5e6d;
        --primary-dark: #704856;
        --primary-soft: #f8f1f4;
        --border: #e8e1e4;
        --text: #29252a;
        --muted: #777178;
        --success: #238b63;
        --danger: #d9534f;
        --warning: #c88719;
        --surface: #ffffff;
        --surface-soft: #fafafa;

        color: var(--text);
    }

    /* =========================================================
       MAIN 3D CARDS
    ========================================================== */

    .billing-page .premium-card {
        background: #fff;
        border: 1px solid rgba(45, 37, 41, .08);
        border-radius: 18px;
        box-shadow:
            0 2px 3px rgba(45, 37, 41, .03),
            0 8px 20px rgba(45, 37, 41, .06),
            0 20px 45px rgba(45, 37, 41, .05);
        transition:
            transform .25s ease,
            box-shadow .25s ease,
            border-color .25s ease;
    }

    .billing-page .premium-card:hover {
        transform: translateY(-2px);
        box-shadow:
            0 4px 8px rgba(45, 37, 41, .04),
            0 14px 30px rgba(45, 37, 41, .08),
            0 25px 55px rgba(45, 37, 41, .06);
        border-color: rgba(139, 94, 109, .16);
    }

    /* =========================================================
       TOP INFORMATION CARD
    ========================================================== */

    .billing-page .bill-info-card {
        position: relative;
        overflow: visible;
        padding: 22px;
        margin-bottom: 18px;
    }

    .billing-page .bill-info-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 20px;
        right: 20px;
        height: 3px;
        border-radius: 0 0 10px 10px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #c39aa7,
            #8b5e6d
        );
        box-shadow: 0 3px 10px rgba(139, 94, 109, .20);
    }

    .billing-page .field-label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #4d474b;
    }

    .billing-page .premium-input,
    .billing-page .premium-select {
        width: 100%;
        min-height: 43px;
        border: 1px solid #e5dfe2;
        border-radius: 11px;
        background: #fff;
        color: var(--text);
        padding: 9px 12px;
        box-shadow:
            inset 0 1px 2px rgba(0, 0, 0, .025),
            0 2px 5px rgba(0, 0, 0, .025);
        transition: all .2s ease;
    }

    .billing-page .premium-input:hover,
    .billing-page .premium-select:hover {
        border-color: #d4c6cb;
    }

    .billing-page .premium-input:focus,
    .billing-page .premium-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow:
            0 0 0 3px rgba(139, 94, 109, .09),
            0 5px 12px rgba(139, 94, 109, .08);
    }

    /* =========================================================
       SEARCH RESULT BOXES
    ========================================================== */

    .billing-page .search-results-box {
        position: absolute;
        z-index: 1050;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        background: #fff;
        border: 1px solid #e5dfe2;
        border-radius: 13px;
        overflow: hidden;
        box-shadow:
            0 5px 10px rgba(0, 0, 0, .04),
            0 15px 35px rgba(0, 0, 0, .10);
    }

    .billing-page .search-result-item {
        padding: 11px 13px;
        cursor: pointer;
        border-bottom: 1px solid #f0ebed;
        transition: background .15s ease, padding-left .15s ease;
    }

    .billing-page .search-result-item:last-child {
        border-bottom: 0;
    }

    .billing-page .search-result-item:hover {
        background: #fbf7f9;
        padding-left: 17px;
    }

    .billing-page .search-result-name {
        font-weight: 600;
        color: #302a2e;
    }

    .billing-page .search-result-info {
        color: #817a7e;
    }

    .billing-page .search-no-result {
        padding: 13px;
        color: #888;
        text-align: center;
    }

    .billing-page .search-selected {
        border-color: var(--primary);
        background: #fcf8fa;
    }

    /* =========================================================
       SECTION HEADER
    ========================================================== */

    .billing-page .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #eee8eb;
        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fcfafb 100%
            );
        border-radius: 18px 18px 0 0;
    }

    .billing-page .section-title {
        margin: 0;
        font-weight: 700;
        color: #302a2e;
    }

    .billing-page .section-subtitle {
        margin-top: 3px;
        color: var(--muted);
    }

    /* =========================================================
       SERVICE SEARCH
    ========================================================== */

    .billing-page .service-search-wrapper {
        padding: 16px 20px;
        background: #fff;
        border-bottom: 1px solid #f0ebed;
    }

    .billing-page #serviceSearch {
        border-radius: 12px;
        min-height: 44px;
        padding-left: 13px;
    }

    /* =========================================================
       SERVICE CATEGORY
    ========================================================== */

    .billing-page .service-category {
        padding: 18px 20px 4px;
    }

    .billing-page .service-category-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f0ebed;
        font-weight: 700;
        color: #51494e;
    }

    /* =========================================================
       SERVICE CARD - 3D
    ========================================================== */

    .billing-page .service-card {
        width: 100%;
        height: 100%;
        padding: 0;
        border: 0;
        background: transparent;
        text-align: left;
        color: inherit;
    }

    .billing-page .service-card-inner {
        position: relative;
        height: 100%;
        min-height: 142px;
        padding: 17px;
        border: 1px solid #e9e2e5;
        border-radius: 15px;
        background:
            linear-gradient(
                145deg,
                #ffffff 0%,
                #ffffff 65%,
                #fbf7f9 100%
            );
        box-shadow:
            0 2px 2px rgba(45, 37, 41, .025),
            0 7px 16px rgba(45, 37, 41, .055),
            inset 0 1px 0 rgba(255,255,255,.9);
        transition:
            transform .22s ease,
            box-shadow .22s ease,
            border-color .22s ease;
    }

    .billing-page .service-card-inner::after {
        content: "";
        position: absolute;
        left: 14px;
        right: 14px;
        bottom: -5px;
        height: 8px;
        border-radius: 50%;
        background: rgba(45, 37, 41, .06);
        filter: blur(5px);
        z-index: -1;
    }

    .billing-page .service-card:hover .service-card-inner {
        transform: translateY(-4px);
        border-color: rgba(139, 94, 109, .35);
        box-shadow:
            0 3px 4px rgba(45, 37, 41, .03),
            0 12px 25px rgba(45, 37, 41, .09),
            0 22px 40px rgba(139, 94, 109, .07);
    }

    .billing-page .service-card:active .service-card-inner {
        transform: translateY(-1px) scale(.99);
    }

    .billing-page .service-name {
        font-weight: 700;
        color: #302a2e;
        margin-bottom: 7px;
    }

    .billing-page .service-description {
        color: #817a7e;
        line-height: 1.45;
        min-height: 39px;
    }

    .billing-page .service-price {
        margin-top: 12px;
        font-weight: 700;
        color: var(--primary);
    }

    .billing-page .service-duration,
    .billing-page .variation-count {
        color: #8b8387;
    }

    /* =========================================================
       BILL / CART SIDE
    ========================================================== */

    .billing-page .bill-card {
        position: sticky;
        top: 20px;
        overflow: hidden;
    }

    .billing-page .bill-card-header {
        padding: 18px 20px;
        background:
            linear-gradient(
                145deg,
                #ffffff,
                #fbf8fa
            );
        border-bottom: 1px solid #eee8eb;
    }

    .billing-page .bill-card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .billing-page .bill-card-title {
        margin: 0;
        font-weight: 700;
        color: #302a2e;
    }

    .billing-page .cart-count {
        min-width: 29px;
        height: 29px;
        padding: 0 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        background: #f5e9ee;
        color: var(--primary-dark);
        font-weight: 700;
        box-shadow:
            inset 0 1px 2px rgba(0,0,0,.04),
            0 2px 6px rgba(139,94,109,.10);
    }

    .billing-page .clear-cart-btn {
        border: 1px solid #eadfe3;
        border-radius: 9px;
        background: #fff;
        color: #777;
        padding: 6px 10px;
        transition: all .2s ease;
    }

    .billing-page .clear-cart-btn:hover {
        color: var(--danger);
        border-color: #e8c9c8;
        background: #fff8f8;
    }

    /* =========================================================
       CART
    ========================================================== */

    .billing-page #cartContainer {
        padding: 12px;
        max-height: 430px;
        overflow-y: auto;
        background: #fcfbfb;
    }

    .billing-page #cartContainer::-webkit-scrollbar {
        width: 5px;
    }

    .billing-page #cartContainer::-webkit-scrollbar-thumb {
        background: #d9cbd0;
        border-radius: 20px;
    }

    .billing-page .empty-cart {
        padding: 38px 20px;
        text-align: center;
        color: #938c90;
    }

    .billing-page .cart-item {
        position: relative;
        margin-bottom: 10px;
        padding: 13px;
        border: 1px solid #e9e2e5;
        border-radius: 13px;
        background: #fff;
        box-shadow:
            0 2px 4px rgba(0,0,0,.025),
            0 6px 13px rgba(45,37,41,.045);
        transition: all .2s ease;
    }

    .billing-page .cart-item:hover {
        border-color: #dccbd1;
        box-shadow:
            0 3px 6px rgba(0,0,0,.03),
            0 9px 20px rgba(45,37,41,.07);
    }

    .billing-page .cart-item-title {
        font-weight: 700;
        color: #342e32;
    }

    .billing-page .cart-item-meta {
        color: #817a7e;
    }

    .billing-page .cart-item input,
    .billing-page .cart-item select {
        border-radius: 8px;
        border: 1px solid #e4dde0;
        min-height: 34px;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.025);
    }

    /* =========================================================
       TOTALS
    ========================================================== */

    .billing-page .totals-box {
        padding: 16px 20px;
        border-top: 1px solid #eee8eb;
        background: #fff;
    }

    .billing-page .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 7px 0;
        color: #625b5f;
    }

    .billing-page .total-row strong {
        color: #302a2e;
    }

    .billing-page .grand-total-row {
        margin-top: 9px;
        padding: 15px 0 4px;
        border-top: 1px dashed #d9cdd1;
        font-weight: 700;
    }

    .billing-page .grand-total-row strong {
        color: var(--primary-dark);
        font-size: inherit;
    }

    /* =========================================================
       COUPON
    ========================================================== */

    .billing-page .coupon-box {
        margin: 10px 0;
        padding: 11px;
        border: 1px solid #eadfe3;
        border-radius: 12px;
        background: #fcfafb;
    }

    .billing-page .coupon-box select {
        border-radius: 9px;
    }

    .billing-page .coupon-action-btn {
        border: 1px solid var(--primary);
        border-radius: 9px;
        background: var(--primary);
        color: #fff;
        padding: 8px 12px;
        transition: all .2s ease;
    }

    .billing-page .coupon-action-btn:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(139,94,109,.18);
    }

    .billing-page #appliedCouponBox {
        border: 1px solid #d8eadf;
        border-radius: 10px;
        background: #f6fbf8;
        padding: 9px 11px;
    }

    /* =========================================================
       TAX
    ========================================================== */

    .billing-page .tax-box {
        margin-top: 10px;
        padding: 11px;
        border: 1px solid #eee6e9;
        border-radius: 12px;
        background: #fcfbfb;
    }

    .billing-page .tax-option {
        cursor: pointer;
        padding: 5px 8px;
        border-radius: 8px;
        transition: background .15s ease;
    }

    .billing-page .tax-option:hover {
        background: #f8f1f4;
    }

    /* =========================================================
       PAYMENT
    ========================================================== */

    .billing-page .payment-section {
        border-top: 1px solid #eee8eb;
        background: #fff;
    }

    .billing-page .payment-header {
        padding: 15px 20px 9px;
        font-weight: 700;
        color: #40383d;
    }

    .billing-page .payment-row {
        margin: 0 20px 9px;
        padding: 11px;
        border: 1px solid #e8e0e4;
        border-radius: 11px;
        background: #fff;
        box-shadow:
            0 2px 4px rgba(0,0,0,.025),
            0 5px 12px rgba(0,0,0,.035);
    }

    .billing-page .payment-row input,
    .billing-page .payment-row select {
        border-radius: 8px;
    }

    .billing-page .payment-summary {
        margin: 12px 20px 0;
        padding: 13px;
        border-radius: 12px;
        background:
            linear-gradient(
                145deg,
                #fcfafb,
                #f7f1f4
            );
        border: 1px solid #eadfe3;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,.9),
            0 5px 14px rgba(45,37,41,.045);
    }

    .billing-page .payment-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 5px 0;
    }

    .billing-page .payment-status {
        font-weight: 700;
    }

    /* =========================================================
       FOOTER ACTIONS
    ========================================================== */

    .billing-page .billing-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 9px;
        padding: 18px 20px 20px;
        border-top: 1px solid #eee8eb;
        background: #fff;
    }

    .billing-page .billing-action-btn {
        min-height: 42px;
        border-radius: 10px;
        border: 1px solid #e4dce0;
        background: #fff;
        color: #51494e;
        font-weight: 600;
        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .billing-page .billing-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 15px rgba(45,37,41,.08);
    }

    .billing-page .billing-action-btn.primary {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        box-shadow:
            0 4px 8px rgba(139,94,109,.18),
            0 9px 18px rgba(139,94,109,.10);
    }

    .billing-page .billing-action-btn.primary:hover {
        background: var(--primary-dark);
        border-color: var(--primary-dark);
    }

    /* =========================================================
       VARIATION MODAL
    ========================================================== */

    .billing-page .variation-option {
        position: relative;
        padding: 14px 15px;
        margin-bottom: 9px;
        border: 1px solid #e5dfe2;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        box-shadow:
            0 2px 4px rgba(0,0,0,.025),
            0 6px 13px rgba(45,37,41,.045);
        transition: all .2s ease;
    }

    .billing-page .variation-option:hover {
        transform: translateY(-2px);
        border-color: var(--primary);
        box-shadow:
            0 4px 7px rgba(0,0,0,.03),
            0 10px 22px rgba(139,94,109,.10);
    }

    .billing-page .variation-option-name {
        font-weight: 700;
        color: #3a3337;
    }

    .billing-page .variation-option-price {
        font-weight: 700;
        color: var(--primary);
    }

    /* =========================================================
       ALERTS
    ========================================================== */

    .billing-page .premium-alert {
        border: 0;
        border-radius: 13px;
        box-shadow:
            0 4px 12px rgba(0,0,0,.045);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 1199.98px) {
        .billing-page .bill-card {
            position: relative;
            top: auto;
        }
    }

    @media (max-width: 767.98px) {

        .billing-page .bill-info-card,
        .billing-page .section-header,
        .billing-page .service-search-wrapper,
        .billing-page .totals-box {
            padding-left: 14px;
            padding-right: 14px;
        }

        .billing-page .service-category {
            padding-left: 14px;
            padding-right: 14px;
        }

        .billing-page .billing-actions {
            padding-left: 14px;
            padding-right: 14px;
        }

        .billing-page .service-card-inner {
            min-height: 125px;
        }
    }
</style>


<div class="billing-page">

    <div class="container-fluid px-0">

        {{-- =====================================================
             VALIDATION ERRORS
        ====================================================== --}}
        @if ($errors->any())
            <div class="alert alert-danger premium-alert alert-dismissible fade show mb-3">
                <strong>Please check the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- =====================================================
             SUCCESS
        ====================================================== --}}
        @if (session('success'))
            <div class="alert alert-success premium-alert alert-dismissible fade show mb-3">
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- =====================================================
             RESUME BILL
        ====================================================== --}}
        @if ($resumeBill)
            <div class="alert alert-warning premium-alert mb-3">
                You are currently editing a held bill.
            </div>
        @endif


        {{-- =====================================================
             BILL INFORMATION
        ====================================================== --}}
        <div class="premium-card bill-info-card">

            <div class="row g-3">

                {{-- BRANCH --}}
                <div class="col-xl-4 col-md-6">

                    <label for="branch_id" class="field-label">
                        Branch
                    </label>

                    <select
                        name="branch_id"
                        id="branch_id"
                        class="premium-select"
                    >

                        @foreach ($branches as $branch)

                            <option
                                value="{{ $branch->id }}"
                                @selected(
                                    (int)($selectedBranchId ?? 0) ===
                                    (int)$branch->id
                                )
                            >
                                {{ $branch->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


<div class="col-xl-4 col-md-6">

    <label for="customerSearch" class="field-label">
        Customer
    </label>

    <div class="position-relative">

        <input
            type="text"
            id="customerSearch"
            class="premium-input"
            placeholder="Search customer by name or mobile..."
            autocomplete="off"
        >

        <input
            type="hidden"
            id="customer_id"
            name="customer_id"
        >

        <div
            id="customerSearchResults"
            class="search-results-box d-none"
        ></div>

    </div>

</div>

                {{-- APPOINTMENT --}}
                <div class="col-xl-4 col-md-6">

                    <label for="appointmentSearch" class="field-label">
                        Appointment
                    </label>

                    <div class="position-relative">

                        <input
                            type="text"
                            id="appointmentSearch"
                            class="premium-input"
                            placeholder="Search appointment..."
                            autocomplete="off"
                        >

                        <input
                            type="hidden"
                            id="appointment_id"
                            name="appointment_id"
                        >

                        <div
                            id="appointmentSearchResults"
                            class="search-results-box d-none"
                        ></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             BILLING FORM
        ====================================================== --}}
        <form
            id="billingForm"
            method="POST"
            action="{{ route('admin.billing.store') }}"
        >

            @csrf

            {{-- HIDDEN FORM VALUES --}}
            <input type="hidden"
                   name="branch_id"
                   id="form_branch_id">

            <input type="hidden"
                   name="customer_id"
                   id="form_customer_id">

            <input type="hidden"
                   name="appointment_id"
                   id="form_appointment_id">

            <input type="hidden"
                   name="resume_bill_id"
                   id="form_resume_bill_id">

            <input type="hidden"
                   name="discount"
                   id="form_discount">

            <input type="hidden"
                   name="tax_percentage"
                   id="form_tax_percentage">

            <input type="hidden"
                   name="tax_mode"
                   id="form_tax_mode">

            <input type="hidden"
                   name="notes"
                   id="form_notes">

            <input type="hidden"
                   name="coupon_code"
                   id="form_coupon_code">

            <input type="hidden"
                   name="coupon_discount"
                   id="form_coupon_discount">

            <div id="hiddenItems"></div>

            <div id="hiddenPayments"></div>


            <div class="row g-3">

                {{-- =================================================
                     SERVICES
                ================================================== --}}
                <div class="col-xl-8">

                    <div class="premium-card overflow-hidden">

                        {{-- HEADER --}}
                        <div class="section-header">

                            <div>
                                <h5 class="section-title">
                                    Services
                                </h5>

                                <div class="section-subtitle">
                                    Select services to add to the bill
                                </div>
                            </div>

                        </div>


                        {{-- SEARCH --}}
                        <div class="service-search-wrapper">

                            <input
                                type="text"
                                id="serviceSearch"
                                class="premium-input"
                                placeholder="Search services..."
                                autocomplete="off"
                            >

                        </div>


                        {{-- SERVICES --}}
                        <div id="servicesContainer">

                            @foreach ($services->groupBy('category') as $category => $categoryServices)

                                <div
                                    class="service-category"
                                    data-category="{{ strtolower($category ?? '') }}"
                                >

                                    <div class="service-category-title">

                                        <span>
                                            {{ $category ?: 'Other Services' }}
                                        </span>

                                    </div>


                                    <div class="row g-3">

                                        @foreach ($categoryServices as $service)

                                            <div
                                                class="col-md-6 col-lg-4 service-item"
                                                data-name="{{ strtolower($service->name) }}"
                                                data-category="{{ strtolower($category ?? '') }}"
                                            >

                                                <button
                                                    type="button"
                                                    class="service-card"
                                                    onclick="addService({{ $service->id }})"
                                                >

                                                    <div class="service-card-inner">

                                                        <div class="service-name">
                                                            {{ $service->name }}
                                                        </div>


                                                        @if (!empty($service->description))
                                                            <div class="service-description">
                                                                {{ \Illuminate\Support\Str::limit($service->description, 70) }}
                                                            </div>
                                                        @else
                                                            <div class="service-description">
                                                                Professional salon service
                                                            </div>
                                                        @endif


                                                        <div class="service-price">
                                                            ₹{{ number_format((float)($service->price ?? 0), 2) }}
                                                        </div>


                                                        <div class="d-flex justify-content-between mt-2">

                                                            @if (!empty($service->duration))
                                                                <small class="service-duration">
                                                                    {{ $service->duration }} min
                                                                </small>
                                                            @endif


                                                            @if (
                                                                isset($service->variations) &&
                                                                $service->variations->count()
                                                            )
                                                                <small class="variation-count">
                                                                    {{ $service->variations->count() }} options
                                                                </small>
                                                            @endif

                                                        </div>

                                                    </div>

                                                </button>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            @endforeach


                            <div
                                id="noServicesFound"
                                class="text-center text-muted py-5 d-none"
                            >
                                No services found.
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     CURRENT BILL
                ================================================== --}}
                <div class="col-xl-4">

                    <div class="premium-card bill-card">

                        {{-- BILL HEADER --}}
                        <div class="bill-card-header">

                            <div class="bill-card-header-row">

                                <div>

                                    <h5 class="bill-card-title">
                                        Current Bill
                                    </h5>

                                </div>


                                <div class="d-flex align-items-center gap-2">

                                    <span
                                        id="cartCount"
                                        class="cart-count"
                                    >
                                        0
                                    </span>

                                    <button
                                        type="button"
                                        class="clear-cart-btn"
                                        onclick="clearCart()"
                                    >
                                        Clear
                                    </button>

                                </div>

                            </div>

                        </div>


                        {{-- CART --}}
                        <div id="cartContainer">

                            <div id="emptyCart" class="empty-cart">

                                <div>
                                    No services added yet.
                                </div>

                                <small>
                                    Select a service to start billing.
                                </small>

                            </div>


                            <div
                                id="cartItems"
                                class="d-none"
                            ></div>

                        </div>


                        {{-- TOTALS --}}
                        <div class="totals-box">

                            <div class="total-row">

                                <span>
                                    Service Total
                                </span>

                                <strong>
                                    ₹<span id="serviceTotal">0.00</span>
                                </strong>

                            </div>


                            <div class="total-row">

                                <span>
                                    Item Discount
                                </span>

                                <strong>
                                    ₹<span id="itemDiscountTotal">0.00</span>
                                </strong>

                            </div>


                            {{-- COUPON --}}
                            <div class="coupon-box">

                                <div class="d-flex gap-2">

                                    <select
                                        id="couponCode"
                                        class="premium-select"
                                    >

                                        <option value="">
                                            Select coupon
                                        </option>

                                        @foreach (($coupons ?? collect()) as $coupon)

                                            <option
                                                value="{{ $coupon->code }}"
                                                data-discount-type="{{ $coupon->discount_type ?? '' }}"
                                                data-discount-value="{{ $coupon->discount_value ?? 0 }}"
                                            >
                                                {{ strtoupper($coupon->code) }}
                                            </option>

                                        @endforeach

                                    </select>


                                    <button
                                        type="button"
                                        id="applyCouponBtn"
                                        class="coupon-action-btn"
                                        onclick="applyCoupon()"
                                    >
                                        Apply
                                    </button>

                                </div>


                                <div
                                    id="appliedCouponBox"
                                    class="mt-2 d-none"
                                >

                                    <div class="d-flex justify-content-between align-items-center">

                                        <span>
                                            <span id="appliedCouponCode"></span>
                                        </span>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-link text-danger p-0"
                                            onclick="removeCoupon()"
                                        >
                                            Remove
                                        </button>

                                    </div>

                                </div>


                                <div
                                    id="couponDiscountText"
                                    class="text-success mt-2 d-none"
                                >
                                    Coupon Discount:
                                    ₹<span>0.00</span>
                                </div>

                            </div>


                            {{-- OVERALL DISCOUNT --}}
                            <div class="total-row">

                                <span>
                                    Overall Discount
                                </span>

                                <input
                                    type="number"
                                    id="overallDiscount"
                                    class="premium-input"
                                    value="0"
                                    min="0"
                                    step="0.01"
                                    style="max-width:120px;"
                                >

                            </div>


                            {{-- TAX --}}
                            <div class="tax-box">

                                <div class="mb-2 fw-semibold">
                                    Tax
                                </div>

                                <div class="d-flex gap-3 flex-wrap">

                                    <label class="tax-option">

                                        <input
                                            type="radio"
                                            name="tax_display_mode"
                                            value="with_tax"
                                            id="taxWith"
                                            checked
                                        >

                                        With Tax

                                    </label>


                                    <label class="tax-option">

                                        <input
                                            type="radio"
                                            name="tax_display_mode"
                                            value="without_tax"
                                            id="taxWithout"
                                        >

                                        Without Tax

                                    </label>

                                </div>

                            </div>


                            <div class="total-row">

                                <span>
                                    Tax
                                    (<span id="taxPercentageLabel">
                                        {{ $salon->tax_percentage ?? 0 }}
                                    </span>%)
                                </span>

                                <strong>
                                    ₹<span id="taxTotal">0.00</span>
                                </strong>

                            </div>


                            {{-- GRAND TOTAL --}}
                            <div class="total-row grand-total-row">

                                <span>
                                    Grand Total
                                </span>

                                <strong>
                                    ₹<span id="grandTotal">0.00</span>
                                </strong>

                            </div>

                        </div>


                        {{-- =================================================
                             PAYMENT
                        ================================================== --}}
                        <div class="payment-section">

                            <div class="payment-header">
                                Payment
                            </div>


                            <div id="paymentsContainer">

                                <div class="payment-row">

                                    <div class="row g-2 align-items-center">

                                        <div class="col-5">

                                            <select
                                                class="form-select payment-method"
                                            >

                                                <option value="cash">
                                                    Cash
                                                </option>

                                                <option value="upi">
                                                    UPI
                                                </option>

                                                <option value="card">
                                                    Card
                                                </option>

                                                <option value="bank_transfer">
                                                    Bank Transfer
                                                </option>

                                                <option value="online">
                                                    Online
                                                </option>

                                            </select>

                                        </div>


                                        <div class="col-5">

                                            <input
                                                type="number"
                                                class="form-control payment-amount"
                                                value="0"
                                                min="0"
                                                step="0.01"
                                                placeholder="Amount"
                                            >

                                        </div>


                                        <div class="col-2">

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light w-100"
                                                onclick="removePaymentRow(this)"
                                            >
                                                ×
                                            </button>

                                        </div>


                                        <div class="col-12">

                                            <input
                                                type="text"
                                                class="form-control payment-transaction"
                                                placeholder="Transaction / reference"
                                            >

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="px-3 pb-2">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    onclick="addPaymentRow()"
                                >
                                    Add Payment
                                </button>

                            </div>


                            {{-- PAYMENT SUMMARY --}}
                            <div class="payment-summary">

                                <div class="payment-summary-row">

                                    <span>
                                        Paid Amount
                                    </span>

                                    <strong>
                                        ₹<span id="paidAmount">0.00</span>
                                    </strong>

                                </div>


                                <div class="payment-summary-row">

                                    <span>
                                        Balance
                                    </span>

                                    <strong>
                                        ₹<span id="balanceAmount">0.00</span>
                                    </strong>

                                </div>


                                <div class="payment-summary-row">

                                    <span>
                                        Status
                                    </span>

                                    <span
                                        id="paymentStatus"
                                        class="payment-status"
                                    >
                                        Pending
                                    </span>

                                </div>

                            </div>


                            {{-- ACTION BUTTONS --}}
                            <div class="billing-actions">

                                <button
                                    type="button"
                                    class="billing-action-btn"
                                    onclick="submitBill('hold')"
                                >
                                    Hold
                                </button>


                                <button
                                    type="button"
                                    class="billing-action-btn"
                                    onclick="focusPayment()"
                                >
                                    Payment
                                </button>


                                <button
                                    type="button"
                                    class="billing-action-btn"
                                    onclick="submitBill('invoice')"
                                >
                                    Invoice
                                </button>


                                <button
                                    type="button"
                                    class="billing-action-btn primary"
                                    onclick="submitBill('print')"
                                >
                                    Save & Print
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =============================================================
     VARIATION MODAL
============================================================= --}}
<div
    class="modal fade"
    id="variationModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header border-0">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="variationModalTitle"
                    >
                        Select Variation
                    </h5>

                    <div
                        id="variationModalSubtitle"
                        class="text-muted"
                    ></div>

                </div>


                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <div class="modal-body pt-0">

                <div id="variationOptions"></div>

            </div>

        </div>

    </div>

</div>


{{-- =============================================================
     IMPORTANT:
     KEEP YOUR EXISTING FULL JAVASCRIPT BELOW THIS LINE.
     IDs/classes/functions have intentionally been preserved.
============================================================= --}}




<script>

    /* ============================================================
       SERVER DATA
    ============================================================ */

    const services = @js($serviceData ?? []);
    const staff = @js($staffData ?? []);
    const appointments = @js($appointmentData ?? []);
    const customers = @js($customerData ?? []);
    const resumeBill = @js($resumeBill ?? null);

    const taxPercentage = Number(
        @js((float)($salon->tax_percentage ?? 0))
    );


    /* ============================================================
       CART
    ============================================================ */

    let cart = [];
    let paymentRowCounter = 1;

    let appliedCoupon = null;
    let couponDiscount = 0;


    /* ============================================================
       FORMAT MONEY
    ============================================================ */

    function formatMoney(value) {

        const number = Number(value) || 0;

        return '₹' + number.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    }


    /* ============================================================
       ESCAPE
    ============================================================ */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    function escapeAttribute(value) {
        return escapeHtml(value);
    }


    /* ============================================================
       GET SERVICE
    ============================================================ */

    function getServiceById(serviceId) {

        const id = String(serviceId);

        if (!services) {
            return null;
        }

        if (Array.isArray(services)) {

            return services.find(function(service) {
                return Number(service.id) === Number(serviceId);
            }) || null;

        }

        return services[id] ||
               services[serviceId] ||
               null;
    }


    /* ============================================================
       GET VARIATION
    ============================================================ */

    function getVariationById(service, variationId) {

        if (!service) {
            return null;
        }

        const variations = Array.isArray(service.variations)
            ? service.variations
            : [];

        return variations.find(function(variation) {
            return Number(variation.id) === Number(variationId);
        }) || null;
    }


    /* ============================================================
       ADD SERVICE
    ============================================================ */

    function addService(serviceId, variationId = null, staffId = null) {

        const service = getServiceById(serviceId);

        if (!service) {

            console.error('Service not found:', serviceId);

            alert(
                'Service data not found. Please refresh the billing page.'
            );

            return;
        }


        const variations = Array.isArray(service.variations)
            ? service.variations
            : [];


        /* ONE VARIATION */

        if (
            variations.length === 1 &&
            variationId === null
        ) {

            addService(
                serviceId,
                variations[0].id,
                staffId
            );

            return;
        }


        /* MULTIPLE VARIATIONS */

        if (
            variations.length > 1 &&
            variationId === null
        ) {

            showVariationModal(
                service,
                staffId
            );

            return;
        }


        let variation = null;

        if (variationId !== null) {

            variation = getVariationById(
                service,
                variationId
            );

        }


        let finalPrice = variation
            ? Number(variation.price)
            : Number(service.price);


        if (!Number.isFinite(finalPrice) || finalPrice < 0) {
            finalPrice = 0;
        }


        const existingItem = cart.find(function(item) {

            return Number(item.service_id) === Number(serviceId) &&
                   Number(item.variation_id || 0) ===
                   Number(variationId || 0);

        });


        if (existingItem) {

            existingItem.quantity =
                Number(existingItem.quantity || 0) + 1;

            if (staffId) {
                existingItem.staff_id = Number(staffId);
            }

        } else {

            cart.push({

                uid:
                    Date.now().toString() +
                    Math.random().toString(36).substring(2),

                service_id:
                    Number(service.id),

                service_name:
                    service.name,

                variation_id:
                    variation
                        ? Number(variation.id)
                        : null,

                variation_name:
                    variation
                        ? variation.name
                        : null,

                staff_id:
                    staffId
                        ? Number(staffId)
                        : (
                            staff.length > 0
                                ? Number(staff[0].id)
                                : null
                        ),

                quantity: 1,

                price: finalPrice,

                discount: 0

            });

        }


        renderCart();

    }


    /* ============================================================
       VARIATION MODAL
    ============================================================ */

    function showVariationModal(service, appointmentStaffId = null) {

        const modalElement =
            document.getElementById('variationModal');

        const titleElement =
            document.getElementById('variationModalTitle');

        const subtitleElement =
            document.getElementById('variationModalSubtitle');

        const optionsElement =
            document.getElementById('variationOptions');


        if (!modalElement || !optionsElement) {
            return;
        }


        if (titleElement) {
            titleElement.textContent = 'Select Variation';
        }


        if (subtitleElement) {
            subtitleElement.textContent =
                service.name || '';
        }


        const variations =
            Array.isArray(service.variations)
                ? service.variations
                : [];


        optionsElement.innerHTML = '';


        variations.forEach(function(variation) {

            const option =
                document.createElement('div');

            option.className =
                'variation-option';


            option.innerHTML = `

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <div class="variation-option-name">
                            ${escapeHtml(
                                variation.name || 'Variation'
                            )}
                        </div>

                        <div class="small text-muted mt-1">

                            ${
                                variation.duration
                                    ? escapeHtml(
                                        String(variation.duration)
                                      ) + ' min'
                                    : ''
                            }

                        </div>

                    </div>

                    <div class="variation-option-price">

                        ${formatMoney(variation.price)}

                    </div>

                </div>

            `;


            option.addEventListener(
                'click',
                function() {

                    addService(
                        service.id,
                        variation.id,
                        appointmentStaffId
                    );


                    const modal =
                        bootstrap.Modal.getInstance(
                            modalElement
                        );


                    if (modal) {
                        modal.hide();
                    }

                }
            );


            optionsElement.appendChild(option);

        });


        const modal =
            new bootstrap.Modal(modalElement);

        modal.show();

    }


    /* ============================================================
       RENDER CART
    ============================================================ */

    function renderCart() {

        const cartItems =
            document.getElementById('cartItems');

        const emptyCart =
            document.getElementById('emptyCart');

        const cartCount =
            document.getElementById('cartCount');


        if (!cartItems) {
            return;
        }


        cartItems.innerHTML = '';


        if (cart.length === 0) {

            if (emptyCart) {
                emptyCart.classList.remove('d-none');
            }

            if (cartCount) {
                cartCount.textContent = '0';
            }

            calculateTotals();

            return;
        }


        if (emptyCart) {
            emptyCart.classList.add('d-none');
        }


        let totalQuantity = 0;


        cart.forEach(function(item) {

            totalQuantity +=
                Number(item.quantity || 0);


            const service =
                getServiceById(item.service_id);


            const variationName =
                item.variation_name
                    ? item.variation_name
                    : '';


            const itemTotal =
                calculateItemTotal(item);


            const wrapper =
                document.createElement('div');


            wrapper.className =
                'cart-item';


            wrapper.innerHTML = `

                <div class="d-flex justify-content-between gap-2">

                    <div class="flex-grow-1">

                        <div class="cart-item-title">

                            ${escapeHtml(
                                item.service_name ||
                                (
                                    service
                                        ? service.name
                                        : 'Service'
                                )
                            )}

                        </div>


                        ${
                            variationName
                                ? `
                                    <div class="cart-item-meta">

                                        Variation:
                                        ${escapeHtml(variationName)}

                                    </div>
                                  `
                                : ''
                        }

                    </div>


                    <button type="button"
                            class="btn btn-sm btn-link text-danger p-0"
                            onclick="removeItem('${escapeAttribute(item.uid)}')">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>


                <div class="row g-2 mt-2">

                    <div class="col-5">

                        <label class="small text-muted">
                            Staff
                        </label>

                        <select class="form-select form-select-sm"
                                onchange="changeStaff('${escapeAttribute(item.uid)}', this.value)">

                            <option value="">
                                Select Staff
                            </option>

                            ${
                                staff.map(function(member) {

                                    return `

                                        <option value="${member.id}"
                                            ${
                                                Number(item.staff_id) ===
                                                Number(member.id)
                                                    ? 'selected'
                                                    : ''
                                            }>

                                            ${escapeHtml(member.name)}

                                        </option>

                                    `;

                                }).join('')
                            }

                        </select>

                    </div>


                    <div class="col-3">

                        <label class="small text-muted">
                            Qty
                        </label>

                        <input type="number"
                               class="form-control form-control-sm"
                               min="1"
                               value="${Number(item.quantity || 1)}"
                               onchange="changeQuantity('${escapeAttribute(item.uid)}', this.value)">

                    </div>


                    <div class="col-4">

                        <label class="small text-muted">
                            Price
                        </label>

                        <input type="number"
                               class="form-control form-control-sm"
                               min="0"
                               step="0.01"
                               value="${Number(item.price || 0)}"
                               onchange="changePrice('${escapeAttribute(item.uid)}', this.value)">

                    </div>


                    ${
                        service &&
                        Array.isArray(service.variations) &&
                        service.variations.length > 1

                            ? `

                                <div class="col-7">

                                    <label class="small text-muted">
                                        Variation
                                    </label>

                                    <select class="form-select form-select-sm"
                                            onchange="changeVariation('${escapeAttribute(item.uid)}', this.value)">

                                        ${service.variations.map(function(variation) {

                                            return `

                                                <option value="${variation.id}"
                                                    ${
                                                        Number(item.variation_id) ===
                                                        Number(variation.id)
                                                            ? 'selected'
                                                            : ''
                                                    }>

                                                    ${escapeHtml(variation.name)}
                                                    -
                                                    ${formatMoney(variation.price)}

                                                </option>

                                            `;

                                        }).join('')}

                                    </select>

                                </div>

                              `

                            : ''
                    }


                    <div class="col-5">

                        <label class="small text-muted">
                            Discount
                        </label>

                        <input type="number"
                               class="form-control form-control-sm"
                               min="0"
                               step="0.01"
                               value="${Number(item.discount || 0)}"
                               onchange="changeDiscount('${escapeAttribute(item.uid)}', this.value)">

                    </div>

                </div>


                <div class="d-flex justify-content-between align-items-center mt-3">

                    <span class="small text-muted">
                        Item Total
                    </span>

                    <strong>
                        ${formatMoney(itemTotal)}
                    </strong>

                </div>

            `;


            cartItems.appendChild(wrapper);

        });


        if (cartCount) {
            cartCount.textContent =
                totalQuantity;
        }


        calculateTotals();

    }


    /* ============================================================
       CART CHANGE FUNCTIONS
    ============================================================ */

    function changeStaff(uid, staffId) {

        const item =
            cart.find(function(item) {
                return item.uid === uid;
            });


        if (!item) {
            return;
        }


        item.staff_id =
            staffId
                ? Number(staffId)
                : null;

    }


    function changeQuantity(uid, quantity) {

        const item =
            cart.find(function(item) {
                return item.uid === uid;
            });


        if (!item) {
            return;
        }


        let qty =
            parseInt(quantity, 10);


        if (!qty || qty < 1) {
            qty = 1;
        }


        item.quantity = qty;

        renderCart();

    }


    function changePrice(uid, price) {

        const item =
            cart.find(function(item) {
                return item.uid === uid;
            });


        if (!item) {
            return;
        }


        let value =
            Number(price);


        if (!Number.isFinite(value) || value < 0) {
            value = 0;
        }


        item.price = value;

        renderCart();

    }


    function changeDiscount(uid, discount) {

        const item =
            cart.find(function(item) {
                return item.uid === uid;
            });


        if (!item) {
            return;
        }


        let value =
            Number(discount);


        if (!Number.isFinite(value) || value < 0) {
            value = 0;
        }


        const maxDiscount =
            Number(item.quantity || 1) *
            Number(item.price || 0);


        item.discount =
            Math.min(value, maxDiscount);

        renderCart();

    }


    function changeVariation(uid, variationId) {

        const item =
            cart.find(function(item) {
                return item.uid === uid;
            });


        if (!item) {
            return;
        }


        const service =
            getServiceById(item.service_id);


        if (!service) {
            return;
        }


        const variation =
            getVariationById(
                service,
                variationId
            );


        if (!variation) {
            return;
        }


        item.variation_id =
            Number(variation.id);


        item.variation_name =
            variation.name;


        let price =
            Number(variation.price);


        if (!Number.isFinite(price) || price < 0) {
            price = 0;
        }


        item.price = price;

        renderCart();

    }


    function calculateItemTotal(item) {

        const quantity =
            Number(item.quantity || 0);

        const price =
            Number(item.price || 0);

        const discount =
            Number(item.discount || 0);


        const gross =
            quantity * price;


        return Math.max(
            0,
            gross - discount
        );

    }


    function removeItem(uid) {

        cart =
            cart.filter(function(item) {
                return item.uid !== uid;
            });

        renderCart();

    }


    function clearCart() {

        if (cart.length === 0) {
            return;
        }


        if (!confirm('Clear all services from the bill?')) {
            return;
        }


        cart = [];

        removeCoupon(false);

        renderCart();

    }


    /* ============================================================
       TAX MODE
    ============================================================ */

    function getTaxMode() {

        const checkedRadio =
            document.querySelector(
                'input[name="tax_mode"]:checked'
            );


        if (checkedRadio && checkedRadio.value) {

            return String(
                checkedRadio.value
            ).toLowerCase().trim();

        }


        return 'with_tax';

    }


    /* ============================================================
       CALCULATE TOTALS
    ============================================================ */

    function calculateTotals() {

        let serviceTotal = 0;
        let itemDiscountTotal = 0;


        cart.forEach(function(item) {

            const quantity =
                Number(item.quantity || 0);

            const price =
                Number(item.price || 0);

            const gross =
                quantity * price;

            const discount =
                Math.min(
                    Number(item.discount || 0),
                    gross
                );


            serviceTotal += gross;
            itemDiscountTotal += discount;

        });


        const afterItemDiscount =
            Math.max(
                0,
                serviceTotal - itemDiscountTotal
            );


        let currentCouponDiscount =
            Number(couponDiscount || 0);


        if (
            !Number.isFinite(currentCouponDiscount) ||
            currentCouponDiscount < 0
        ) {
            currentCouponDiscount = 0;
        }


        currentCouponDiscount =
            Math.min(
                currentCouponDiscount,
                afterItemDiscount
            );


        const discountElement =
            document.getElementById('overallDiscount');


        let manualOverallDiscount =
            discountElement
                ? Number(discountElement.value || 0)
                : 0;


        if (
            !Number.isFinite(manualOverallDiscount) ||
            manualOverallDiscount < 0
        ) {
            manualOverallDiscount = 0;
        }


        const remainingAfterCoupon =
            Math.max(
                0,
                afterItemDiscount -
                currentCouponDiscount
            );


        manualOverallDiscount =
            Math.min(
                manualOverallDiscount,
                remainingAfterCoupon
            );


        const totalOverallDiscount =
            currentCouponDiscount +
            manualOverallDiscount;


        const taxableAmount =
            Math.max(
                0,
                afterItemDiscount -
                totalOverallDiscount
            );


        const taxMode =
            getTaxMode();


        let tax = 0;
        let grandTotal = taxableAmount;


        if (taxMode === 'with_tax') {

            tax =
                taxableAmount *
                taxPercentage /
                100;

            grandTotal =
                taxableAmount +
                tax;

        } else {

            tax = 0;

            grandTotal =
                taxableAmount;

        }


        tax =
            Math.max(0, Number(tax) || 0);

        grandTotal =
            Math.max(0, Number(grandTotal) || 0);


        const serviceTotalElement =
            document.getElementById('serviceTotal');


        if (serviceTotalElement) {
            serviceTotalElement.textContent =
                formatMoney(serviceTotal);
        }


        const itemDiscountElement =
            document.getElementById('itemDiscountTotal');


        if (itemDiscountElement) {
            itemDiscountElement.textContent =
                formatMoney(itemDiscountTotal);
        }


        const couponDiscountElement =
            document.getElementById('couponDiscountText');


        if (couponDiscountElement) {

            couponDiscountElement.textContent =
                '- ' +
                formatMoney(currentCouponDiscount);

        }


        const taxElement =
            document.getElementById('taxTotal');


        if (taxElement) {
            taxElement.textContent =
                formatMoney(tax);
        }


        const grandTotalElement =
            document.getElementById('grandTotal');


        if (grandTotalElement) {
            grandTotalElement.textContent =
                formatMoney(grandTotal);
        }


        const formTaxPercentage =
            document.getElementById('form_tax_percentage');


        if (formTaxPercentage) {
            formTaxPercentage.value =
                taxPercentage;
        }


        const formTaxMode =
            document.getElementById('form_tax_mode');


        if (formTaxMode) {
            formTaxMode.value =
                taxMode;
        }


        const formDiscount =
    document.getElementById('form_discount');

if (formDiscount) {

    /*
     * IMPORTANT:
     * form_discount = ONLY manual/overall discount.
     * Coupon discount must NEVER be added here.
     *
     * Item discount is already sent separately
     * inside items[index][discount].
     */

    formDiscount.value =
        manualOverallDiscount.toFixed(2);

}


        const formCouponCode =
            document.getElementById('form_coupon_code');


        const formCouponDiscount =
            document.getElementById('form_coupon_discount');


        if (formCouponCode) {

            formCouponCode.value =
                appliedCoupon
                    ? appliedCoupon.code
                    : '';

        }


        if (formCouponDiscount) {

            formCouponDiscount.value =
                appliedCoupon
                    ? currentCouponDiscount.toFixed(2)
                    : '0';

        }


        calculatePayment();

    }


    /* ============================================================
       COUPON
    ============================================================ */

   async function applyCoupon() {

    const couponInput =
        document.getElementById('couponCode');

    const button =
        document.getElementById('applyCouponBtn');


    if (!couponInput || !button) {
        return;
    }


    // Selected coupon
    const couponCode =
        couponInput.value
            .trim()
            .toUpperCase();


    if (!couponCode) {

        alert('Please select a coupon.');

        couponInput.focus();

        return;
    }


    // Prevent multiple coupon apply
    if (appliedCoupon) {

        alert(
            'A coupon is already applied. Remove it first.'
        );

        return;
    }


    // Calculate bill amount
    let serviceTotal = 0;
    let itemDiscountTotal = 0;


    cart.forEach(function(item) {

        const quantity =
            Number(item.quantity || 0);

        const price =
            Number(item.price || 0);

        const gross =
            quantity * price;

        const discount =
            Math.min(
                Number(item.discount || 0),
                gross
            );


        serviceTotal += gross;

        itemDiscountTotal += discount;

    });


    const billAmount =
        Math.max(
            0,
            serviceTotal - itemDiscountTotal
        );


    if (billAmount <= 0) {

        alert(
            'Please add services before applying a coupon.'
        );

        return;
    }


    // Branch
    const branchElement =
        document.getElementById('branch_id');


    const branchId =
        branchElement
            ? branchElement.value
            : '';


    if (!branchId) {

        alert(
            'Please select a branch first.'
        );

        return;
    }


    // Customer
    const customerElement =
        document.getElementById('customer_id');


    const customerId =
        customerElement
            ? customerElement.value
            : '';


    // Button loading
    const originalButtonText =
        button.innerHTML;


    button.disabled = true;

    button.innerHTML =
        '<span class="spinner-border spinner-border-sm me-1"></span> Checking...';


    try {

        // CSRF
        const csrfElement =
            document.querySelector(
                'meta[name="csrf-token"]'
            );


        const csrfToken =
            csrfElement
                ? csrfElement.getAttribute('content')
                : '{{ csrf_token() }}';


        // Apply coupon request
        const response =
            await fetch(
                '{{ route("admin.billing.apply-coupon") }}',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },

                    body: JSON.stringify({

                        coupon_code:
                            couponCode,

                        branch_id:
                            branchId,

                        customer_id:
                            customerId
                                ? customerId
                                : null,

                        bill_amount:
                            billAmount

                    })
                }
            );


        const data =
            await response.json();


        // Backend error
        if (
            !response.ok ||
            !data.success
        ) {

            throw new Error(
                data.message ||
                'Unable to apply coupon.'
            );

        }


        // Save applied coupon
        appliedCoupon =
            data.coupon;


        couponDiscount =
            Number(
                data.discount || 0
            );


        // Applied coupon code
        const appliedCouponCode =
            document.getElementById(
                'appliedCouponCode'
            );


        if (appliedCouponCode) {

            appliedCouponCode.textContent =
                data.coupon.code;

        }


        // Show applied coupon box
        const appliedCouponBox =
            document.getElementById(
                'appliedCouponBox'
            );


        if (appliedCouponBox) {

            appliedCouponBox.classList.remove(
                'd-none'
            );

        }


        // Keep selected coupon
        couponInput.value =
            data.coupon.code;


        // Disable coupon dropdown
        couponInput.disabled = true;


        // Hide Apply button
        button.classList.add(
            'd-none'
        );


        // Recalculate totals
        calculateTotals();


        alert(
            'Coupon applied successfully.\n\n' +
            'Coupon: ' +
            data.coupon.code +
            '\n' +
            'Discount: ' +
            formatMoney(
                couponDiscount
            )
        );


    } catch (error) {

        alert(
            error.message ||
            'Unable to apply coupon.'
        );

    } finally {

        button.disabled = false;

        button.innerHTML =
            originalButtonText;

    }

}


    function removeCoupon(showMessage = true) {

        appliedCoupon = null;

        couponDiscount = 0;


        const couponInput =
            document.getElementById('couponCode');

        const formCouponCode =
            document.getElementById('form_coupon_code');

        const formCouponDiscount =
            document.getElementById('form_coupon_discount');

        const appliedBox =
            document.getElementById('appliedCouponBox');

        const applyButton =
            document.getElementById('applyCouponBtn');


        if (couponInput) {

            couponInput.value = '';

            couponInput.disabled = false;

        }


        if (formCouponCode) {
            formCouponCode.value = '';
        }


        if (formCouponDiscount) {
            formCouponDiscount.value = '0';
        }


        if (appliedBox) {
            appliedBox.classList.add('d-none');
        }


        if (applyButton) {

            applyButton.classList.remove('d-none');

            applyButton.disabled = false;

        }


        calculateTotals();


        if (showMessage) {
            alert('Coupon removed.');
        }

    }


    /* ============================================================
       GRAND TOTAL
    ============================================================ */

    function getGrandTotal() {

        const element =
            document.getElementById('grandTotal');


        if (!element) {
            return 0;
        }


        const text =
            element.textContent
                .replace(/[₹,\s]/g, '');


        return Number(text) || 0;

    }


    /* ============================================================
       PAYMENT
    ============================================================ */

    function addPaymentRow() {

        const container =
            document.getElementById('paymentsContainer');


        if (!container) {
            return;
        }


        const index =
            paymentRowCounter++;


        const row =
            document.createElement('div');


        row.className =
            'payment-row border rounded p-2 mb-2';


        row.dataset.paymentIndex =
            index;


        row.innerHTML = `

            <div class="row g-2">

                <div class="col-5">

                    <select class="form-select form-select-sm payment-method">

                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="online">Online</option>

                    </select>

                </div>


                <div class="col-5">

                    <input type="number"
                           class="form-control form-control-sm payment-amount"
                           min="0"
                           step="0.01"
                           value="0"
                           placeholder="Amount">

                </div>


                <div class="col-2">

                    <button type="button"
                            class="btn btn-sm btn-outline-danger w-100"
                            onclick="removePaymentRow(this)">

                        <i class="bi bi-x"></i>

                    </button>

                </div>


                <div class="col-12">

                    <input type="text"
                           class="form-control form-control-sm payment-transaction"
                           placeholder="Transaction ID / Reference (optional)">

                </div>

            </div>

        `;


        container.appendChild(row);

        bindPaymentInputs();

        calculatePayment();

    }


    function removePaymentRow(button) {

        const rows =
            document.querySelectorAll(
                '#paymentsContainer .payment-row'
            );


        if (rows.length <= 1) {

            if (!rows[0]) {
                return;
            }


            const amount =
                rows[0].querySelector(
                    '.payment-amount'
                );


            const transaction =
                rows[0].querySelector(
                    '.payment-transaction'
                );


            if (amount) {
                amount.value = 0;
            }


            if (transaction) {
                transaction.value = '';
            }


            calculatePayment();

            return;

        }


        button
            .closest('.payment-row')
            .remove();


        calculatePayment();

    }


    function calculatePayment() {

        let paid = 0;


        document
            .querySelectorAll('.payment-amount')
            .forEach(function(input) {

                paid +=
                    Number(input.value) || 0;

            });


        const total =
            getGrandTotal();


        const balance =
            Math.max(
                0,
                total - paid
            );


        const paidAmount =
            document.getElementById('paidAmount');


        const balanceAmount =
            document.getElementById('balanceAmount');


        if (paidAmount) {
            paidAmount.textContent =
                formatMoney(paid);
        }


        if (balanceAmount) {
            balanceAmount.textContent =
                formatMoney(balance);
        }


        const status =
            document.getElementById('paymentStatus');


        if (!status) {
            return;
        }


        if (total <= 0) {

            status.value = 'pending';

        } else if (paid >= total) {

            status.value = 'paid';

        } else if (paid > 0) {

            status.value = 'partial';

        } else {

            status.value = 'pending';

        }

    }


    function bindPaymentInputs() {

        document
            .querySelectorAll('.payment-amount')
            .forEach(function(input) {

                input.removeEventListener(
                    'input',
                    calculatePayment
                );

                input.addEventListener(
                    'input',
                    calculatePayment
                );

            });

    }


    function focusPayment() {

        const paymentInput =
            document.querySelector(
                '.payment-amount'
            );


        if (paymentInput) {

            paymentInput.focus();

            paymentInput.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

        }

    }


    /* ============================================================
       HIDDEN INPUT
    ============================================================ */

    function createHiddenInput(name, value) {

        const input =
            document.createElement('input');


        input.type = 'hidden';

        input.name = name;

        input.value =
            value ?? '';


        return input;

    }


/* ============================================================
   PREPARE FORM
============================================================ */

function prepareForm() {

    /* ============================================================
       BRANCH
    ============================================================ */

    const branch =
        document.getElementById('branch_id');

    const formBranch =
        document.getElementById('form_branch_id');

    if (formBranch) {

        formBranch.value =
            branch
                ? branch.value
                : '';

    }


    /* ============================================================
       CUSTOMER
    ============================================================ */

    const customer =
        document.getElementById('customer_id');

    const formCustomer =
        document.getElementById('form_customer_id');

    if (formCustomer) {

        formCustomer.value =
            customer
                ? customer.value
                : '';

    }


    /* ============================================================
       APPOINTMENT
    ============================================================ */

    const appointment =
        document.getElementById('appointment_id');

    const formAppointment =
        document.getElementById('form_appointment_id');

    if (formAppointment) {

        formAppointment.value =
            appointment
                ? appointment.value
                : '';

    }


    /* ============================================================
       RESUME BILL
    ============================================================ */

    const resumeInput =
        document.getElementById('form_resume_bill_id');

    if (resumeInput) {

        resumeInput.value =
            resumeBill && resumeBill.id
                ? resumeBill.id
                : '';

    }


    /* ============================================================
       TAX
    ============================================================ */

    const formTaxPercentage =
        document.getElementById('form_tax_percentage');

    if (formTaxPercentage) {

        const selectedTaxMode =
            getTaxMode();

        formTaxPercentage.value =
            selectedTaxMode === 'without_tax'
                ? '0'
                : Number(
                    taxPercentage || 0
                ).toFixed(2);

    }


    const formTaxMode =
        document.getElementById('form_tax_mode');

    if (formTaxMode) {

        formTaxMode.value =
            getTaxMode();

    }


    /* ============================================================
       HIDDEN ITEMS
    ============================================================ */

    const hiddenItems =
        document.getElementById('hiddenItems');

    if (!hiddenItems) {

        return false;

    }

    hiddenItems.innerHTML = '';


    if (
        !Array.isArray(cart) ||
        cart.length === 0
    ) {

        return false;

    }


    /* ============================================================
       CREATE BILLING ITEMS
    ============================================================ */

    cart.forEach(function(item, index) {

        /* ========================================================
           SERVICE ID
        ======================================================== */

        hiddenItems.appendChild(
            createHiddenInput(
                `items[${index}][service_id]`,
                item.service_id
                    ? Number(item.service_id)
                    : ''
            )
        );


        /* ========================================================
           VARIATION ID
           Backend expects: variation_id
        ======================================================== */

        hiddenItems.appendChild(
            createHiddenInput(
                `items[${index}][variation_id]`,
                item.variation_id
                    ? Number(item.variation_id)
                    : ''
            )
        );


        /* ========================================================
           STAFF ID
        ======================================================== */

        hiddenItems.appendChild(
            createHiddenInput(
                `items[${index}][staff_id]`,
                item.staff_id
                    ? Number(item.staff_id)
                    : ''
            )
        );


        /* ========================================================
           QUANTITY
        ======================================================== */

        let quantity =
            Number(item.quantity);

        if (
            !Number.isFinite(quantity) ||
            quantity < 1
        ) {

            quantity = 1;

        }

        hiddenItems.appendChild(
            createHiddenInput(
                `items[${index}][quantity]`,
                quantity
            )
        );


        /* ========================================================
           PRICE
           Backend expects: price
        ======================================================== */

        let unitPrice =
            Number(
                item.price ??
                item.unit_price ??
                item.service_price ??
                0
            );

        if (
            !Number.isFinite(unitPrice) ||
            unitPrice < 0
        ) {

            unitPrice = 0;

        }

        unitPrice =
            Number(
                unitPrice.toFixed(2)
            );


        hiddenItems.appendChild(
            createHiddenInput(
                `items[${index}][price]`,
                unitPrice.toFixed(2)
            )
        );


        /* ========================================================
           ITEM DISCOUNT
        ======================================================== */

        let itemDiscount =
            Number(
                item.discount ?? 0
            );

        if (
            !Number.isFinite(itemDiscount) ||
            itemDiscount < 0
        ) {

            itemDiscount = 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Maximum Item Discount
        |--------------------------------------------------------------------------
        */

        const maxDiscount =
            quantity * unitPrice;


        itemDiscount =
            Math.min(
                itemDiscount,
                maxDiscount
            );


        itemDiscount =
            Number(
                itemDiscount.toFixed(2)
            );


        hiddenItems.appendChild(
            createHiddenInput(
                `items[${index}][discount]`,
                itemDiscount.toFixed(2)
            )
        );

    });


    /* ============================================================
       PAYMENTS
    ============================================================ */

    const hiddenPayments =
        document.getElementById(
            'hiddenPayments'
        );

    if (hiddenPayments) {

        hiddenPayments.innerHTML = '';

        let paymentIndex = 0;

        /*
        |--------------------------------------------------------------------------
        | FINAL GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        const finalGrandTotal =
            Number(
                getGrandTotal().toFixed(2)
            );

        let remainingPayment =
            finalGrandTotal;


        /*
        |--------------------------------------------------------------------------
        | READ PAYMENT ROWS
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '#paymentsContainer .payment-row'
            )
            .forEach(function(row) {

                if (remainingPayment <= 0) {

                    return;

                }


                const methodElement =
                    row.querySelector(
                        '.payment-method'
                    );


                const amountElement =
                    row.querySelector(
                        '.payment-amount'
                    );


                const transactionElement =
                    row.querySelector(
                        '.payment-transaction'
                    );


                const method =
                    methodElement
                        ? methodElement.value
                        : 'cash';


                let amount =
                    amountElement
                        ? Number(
                            amountElement.value || 0
                        )
                        : 0;


                /*
                |--------------------------------------------------------------------------
                | SANITIZE PAYMENT
                |--------------------------------------------------------------------------
                */

                if (
                    !Number.isFinite(amount) ||
                    amount < 0
                ) {

                    amount = 0;

                }


                amount =
                    Number(
                        amount.toFixed(2)
                    );


                /*
                |--------------------------------------------------------------------------
                | PAYMENT CAN NEVER BE GREATER THAN
                | REMAINING GRAND TOTAL
                |--------------------------------------------------------------------------
                */

                amount =
                    Math.min(
                        amount,
                        remainingPayment
                    );


                amount =
                    Number(
                        amount.toFixed(2)
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE VISIBLE PAYMENT INPUT
                |--------------------------------------------------------------------------
                */

                if (amountElement) {

                    amountElement.value =
                        amount.toFixed(2);

                }


                const transaction =
                    transactionElement
                        ? transactionElement.value.trim()
                        : '';


                /*
                |--------------------------------------------------------------------------
                | CREATE HIDDEN PAYMENT
                |--------------------------------------------------------------------------
                */

                if (amount > 0) {

                    hiddenPayments.appendChild(
                        createHiddenInput(
                            `payments[${paymentIndex}][payment_method]`,
                            method
                        )
                    );


                    hiddenPayments.appendChild(
                        createHiddenInput(
                            `payments[${paymentIndex}][amount]`,
                            amount.toFixed(2)
                        )
                    );


                    hiddenPayments.appendChild(
                        createHiddenInput(
                            `payments[${paymentIndex}][transaction_id]`,
                            transaction
                        )
                    );


                    paymentIndex++;


                    remainingPayment =
                        Number(
                            (
                                remainingPayment -
                                amount
                            ).toFixed(2)
                        );

                }

            });

    }


    /* ============================================================
       MANUAL / OVERALL DISCOUNT
    ============================================================ */

    const discountElement =
        document.getElementById(
            'overallDiscount'
        );

    const formDiscount =
        document.getElementById(
            'form_discount'
        );


    let overallDiscount = 0;


    if (discountElement) {

        overallDiscount =
            Number(
                discountElement.value || 0
            );


        if (
            !Number.isFinite(overallDiscount) ||
            overallDiscount < 0
        ) {

            overallDiscount = 0;

        }


        overallDiscount =
            Number(
                overallDiscount.toFixed(2)
            );


        discountElement.value =
            overallDiscount.toFixed(2);

    }


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | form_discount contains ONLY manual overall discount.
    | Item discounts are already sent individually.
    | Coupon discount is sent separately.
    |--------------------------------------------------------------------------
    */

    if (formDiscount) {

        formDiscount.value =
            overallDiscount.toFixed(2);

    }


    /* ============================================================
       COUPON
    ============================================================ */

    const formCouponCode =
        document.getElementById(
            'form_coupon_code'
        );


    const formCouponDiscount =
        document.getElementById(
            'form_coupon_discount'
        );


    if (formCouponCode) {

        formCouponCode.value =
            appliedCoupon
                ? appliedCoupon.code
                : '';

    }


    if (formCouponDiscount) {

        formCouponDiscount.value =
            appliedCoupon
                ? Number(
                    couponDiscount || 0
                ).toFixed(2)
                : '0.00';

    }


    /* ============================================================
       FINAL PAYMENT CALCULATION
    ============================================================ */

    calculatePayment();


    return true;

}


    /* ============================================================
       SUBMIT
    ============================================================ */

    function submitBill(action) {

    /* ============================================================
       CART VALIDATION
    ============================================================ */

    if (
        !Array.isArray(cart) ||
        cart.length === 0
    ) {

        alert(
            'Please add at least one service to the bill.'
        );

        return;
    }


    /* ============================================================
       BRANCH VALIDATION
    ============================================================ */

    const branchElement =
        document.getElementById('branch_id');

    const branchId =
        branchElement
            ? branchElement.value
            : '';


    if (!branchId) {

        alert(
            'Please select a branch.'
        );

        return;
    }


    /* ============================================================
       STAFF VALIDATION
    ============================================================ */

    const missingStaff =
        cart.some(function(item) {

            return !item.staff_id;

        });


    if (missingStaff) {

        alert(
            'Please select staff for every service.'
        );

        return;
    }


    /* ============================================================
       HOLD BILL
    ============================================================ */

    if (action === 'hold') {

        if (!confirm(
            'Do you want to hold this bill?\n\n' +
            'You can complete the payment later.'
        )) {

            return;

        }

    }


    /* ============================================================
       FINAL TOTAL RECALCULATION
       IMPORTANT:
       Coupon / tax / discount must be recalculated
       immediately before submit.
    ============================================================ */

    calculateTotals();


    /* ============================================================
       PAYMENT VALIDATION
    ============================================================ */

    if (action !== 'hold') {

        const total =
            Number(
                getGrandTotal().toFixed(2)
            );


        const paymentInputs =
            document.querySelectorAll(
                '.payment-amount'
            );


        let paid = 0;


        paymentInputs.forEach(function(input) {

            let amount =
                Number(input.value || 0);


            if (
                !Number.isFinite(amount) ||
                amount < 0
            ) {

                amount = 0;

            }


            amount =
                Number(
                    amount.toFixed(2)
                );


            input.value =
                amount.toFixed(2);


            paid += amount;

        });


        paid =
            Number(
                paid.toFixed(2)
            );


        /* ========================================================
           IMPORTANT FIX

           If coupon reduced Grand Total and old payment amount
           is now greater than Grand Total, automatically reduce
           the payment amount.
        ======================================================== */

        if (paid > total) {

            let remaining =
                total;


            paymentInputs.forEach(function(input) {

                let amount =
                    Number(input.value || 0);


                if (remaining <= 0) {

                    input.value =
                        '0.00';

                    return;

                }


                const allowed =
                    Math.min(
                        amount,
                        remaining
                    );


                input.value =
                    Number(
                        allowed.toFixed(2)
                    ).toFixed(2);


                remaining =
                    Number(
                        (
                            remaining -
                            allowed
                        ).toFixed(2)
                    );

            });


            /* Recalculate paid amount */

            paid = 0;


            paymentInputs.forEach(function(input) {

                paid +=
                    Number(input.value || 0);

            });


            paid =
                Number(
                    paid.toFixed(2)
                );

        }


        /* ========================================================
           FINAL SAFETY CHECK
        ======================================================== */

        if (paid > total) {

            alert(
                'Payment amount cannot be greater than Grand Total.\n\n' +
                'Grand Total: ₹' +
                total.toFixed(2) +
                '\n' +
                'Payment: ₹' +
                paid.toFixed(2)
            );

            return;

        }


        /* ========================================================
           PARTIAL PAYMENT
        ======================================================== */

        if (paid < total) {

            const continuePartial =
                confirm(
                    'The payment is less than the Grand Total.\n\n' +
                    'Grand Total: ₹' +
                    total.toFixed(2) +
                    '\n' +
                    'Paid: ₹' +
                    paid.toFixed(2) +
                    '\n\n' +
                    'Do you want to save this bill as Partial/Pending?'
                );


            if (!continuePartial) {

                return;

            }

        }

    }


    /* ============================================================
       PREPARE FORM
    ============================================================ */

    const prepared =
        prepareForm();


    if (!prepared) {

        alert(
            'Unable to prepare billing items.'
        );

        return;
    }


    /* ============================================================
       FORM
    ============================================================ */

    const form =
        document.getElementById('billingForm');


    if (!form) {

        alert(
            'Billing form not found.'
        );

        return;
    }


    /* ============================================================
       FORM ACTION
    ============================================================ */

    if (action === 'hold') {

        form.action =
            "{{ route('admin.billing.hold') }}";

    } else {

        form.action =
            "{{ route('admin.billing.store') }}";

    }


    /* ============================================================
       ACTION INPUT
    ============================================================ */

    let actionInput =
        document.getElementById('bill_action');


    if (!actionInput) {

        actionInput =
            document.createElement('input');


        actionInput.type =
            'hidden';

        actionInput.name =
            'action';

        actionInput.id =
            'bill_action';


        form.appendChild(
            actionInput
        );

    }


    actionInput.value =
        action;


    /* ============================================================
       DISABLE BUTTONS
    ============================================================ */

    const buttons =
        form.querySelectorAll(
            'button[type="button"]'
        );


    buttons.forEach(function(button) {

        button.disabled = true;

    });


    /* ============================================================
       SUBMIT
    ============================================================ */

    form.submit();

}

    /* =========================================================
   CUSTOMER SEARCH
========================================================= */

const customerSearch = document.getElementById('customerSearch');
const customerIdInput = document.getElementById('customer_id');
const customerSearchResults = document.getElementById('customerSearchResults');

if (customerSearch) {

    customerSearch.addEventListener('input', function () {

        const search = this.value.trim().toLowerCase();

        customerSearchResults.innerHTML = '';

        if (!search) {
            customerSearchResults.classList.add('d-none');
            customerIdInput.value = '';
            return;
        }

        const results = customers.filter(customer => {

            const name = String(customer.name ?? '').toLowerCase();
            const mobile = String(
                customer.phone ??
                customer.mobile ??
                customer.phone_number ??
                ''
            ).toLowerCase();

            return (
                name.includes(search) ||
                mobile.includes(search)
            );
        });

        if (!results.length) {

            customerSearchResults.innerHTML = `
                <div class="search-no-result">
                    No customer found
                </div>
            `;

            customerSearchResults.classList.remove('d-none');
            return;
        }

        results.slice(0, 10).forEach(customer => {

            const name = escapeHtml(customer.name ?? 'Unnamed Customer');

            const mobile = escapeHtml(
                customer.phone ??
                customer.mobile ??
                customer.phone_number ??
                ''
            );

            const item = document.createElement('div');

            item.className = 'search-result-item';

            item.innerHTML = `
                <div class="search-result-name">
                    ${name}
                </div>

                <div class="search-result-info">
                    ${mobile || 'No mobile number'}
                </div>
            `;

            item.addEventListener('click', function () {

                customerIdInput.value = customer.id;

                customerSearch.value =
                    customer.name ?? '';

                customerSearchResults.innerHTML = '';

                customerSearchResults.classList.add('d-none');

                customerSearch.classList.add('search-selected');

                /*
                 * Keep hidden form customer ID synced
                 */
                const formCustomerId =
                    document.getElementById('form_customer_id');

                if (formCustomerId) {
                    formCustomerId.value = customer.id;
                }

                /*
                 * Clear appointment if customer changes
                 */
                const appointmentId =
                    document.getElementById('appointment_id');

                const appointmentSearch =
                    document.getElementById('appointmentSearch');

                if (appointmentId) {
                    appointmentId.value = '';
                }

                if (appointmentSearch) {
                    appointmentSearch.value = '';
                    appointmentSearch.classList.remove('search-selected');
                }

            });

            customerSearchResults.appendChild(item);
        });

        customerSearchResults.classList.remove('d-none');
    });


    /* Close result box when clicking outside */

    document.addEventListener('click', function (event) {

        if (
            !customerSearch.contains(event.target) &&
            !customerSearchResults.contains(event.target)
        ) {
            customerSearchResults.classList.add('d-none');
        }

    });


    /* Reopen results when focusing selected/search field */

    customerSearch.addEventListener('focus', function () {

        if (this.value.trim() !== '') {
            this.dispatchEvent(new Event('input'));
        }

    });

}

    /* ============================================================
       SELECT CUSTOMER
    ============================================================ */

    function selectCustomer(customerId) {

        const customer =
            (Array.isArray(customers) ? customers : [])
                .find(function(item) {

                    return Number(item.id) ===
                           Number(customerId);

                });


        if (!customer) {
            return;
        }


        const searchInput =
            document.getElementById(
                'customerSearch'
            );


        const hiddenInput =
            document.getElementById(
                'customer_id'
            );


        const resultsBox =
            document.getElementById(
                'customerSearchResults'
            );


        if (searchInput) {

            let display =
                customer.name || 'Customer';


            if (customer.phone) {

                display +=
                    ' - ' +
                    customer.phone;

            }


            searchInput.value =
                display;

            searchInput.classList.add(
                'search-selected'
            );

        }


        if (hiddenInput) {

            hiddenInput.value =
                customer.id;

        }


        if (resultsBox) {

            resultsBox.innerHTML = '';

            resultsBox.style.display = 'none';

        }

    }


    /* ============================================================
       CLEAR CUSTOMER
    ============================================================ */

    function clearCustomer() {

        const searchInput =
            document.getElementById(
                'customerSearch'
            );

        const hiddenInput =
            document.getElementById(
                'customer_id'
            );


        if (searchInput) {

            searchInput.value = '';

            searchInput.classList.remove(
                'search-selected'
            );

        }


        if (hiddenInput) {
            hiddenInput.value = '';
        }

    }


    /* ============================================================
       APPOINTMENT SEARCH
    ============================================================ */

    function searchAppointments(query) {

        const resultsBox =
            document.getElementById(
                'appointmentSearchResults'
            );


        if (!resultsBox) {
            return;
        }


        query =
            String(query || '')
                .trim()
                .toLowerCase();


        if (!query) {

            resultsBox.innerHTML = '';

            resultsBox.style.display = 'none';

            return;
        }


        const matches =
            (Array.isArray(appointments) ? appointments : [])
                .filter(function(appointment) {

                    const id =
                        String(appointment.id || '')
                            .toLowerCase();

                    const customerName =
                        String(
                            appointment.customer_name || ''
                        ).toLowerCase();

                    const phone =
                        String(
                            appointment.phone || ''
                        ).toLowerCase();

                    const serviceName =
                        String(
                            appointment.service_name || ''
                        ).toLowerCase();

                    const date =
                        String(
                            appointment.appointment_date || ''
                        ).toLowerCase();

                    const status =
                        String(
                            appointment.status || ''
                        ).toLowerCase();


                    return (
                        id.includes(query) ||
                        customerName.includes(query) ||
                        phone.includes(query) ||
                        serviceName.includes(query) ||
                        date.includes(query) ||
                        status.includes(query)
                    );

                })
                .slice(0, 30);


        if (matches.length === 0) {

            resultsBox.innerHTML = `

                <div class="search-no-result">

                    <i class="bi bi-calendar-x me-1"></i>

                    No appointment found

                </div>

            `;

            resultsBox.style.display = 'block';

            return;
        }


        resultsBox.innerHTML =
            matches.map(function(appointment) {

                const date =
                    appointment.appointment_date
                        ? appointment.appointment_date
                        : '';


                const time =
                    appointment.start_time
                        ? appointment.start_time
                        : '';


                return `

                    <div class="search-result-item"
                         data-appointment-id="${escapeAttribute(appointment.id)}">

                        <div class="search-result-name">

                            #${escapeHtml(appointment.id)}

                            -
                            ${escapeHtml(
                                appointment.customer_name ||
                                'Walk-in'
                            )}

                        </div>


                        <div class="search-result-info">

                            ${
                                appointment.phone
                                    ? `<i class="bi bi-telephone me-1"></i>${escapeHtml(appointment.phone)}`
                                    : ''
                            }

                            ${
                                appointment.service_name
                                    ? `<span class="ms-2"><i class="bi bi-scissors me-1"></i>${escapeHtml(appointment.service_name)}</span>`
                                    : ''
                            }

                        </div>


                        <div class="search-result-info">

                            ${
                                date
                                    ? `<i class="bi bi-calendar3 me-1"></i>${escapeHtml(date)}`
                                    : ''
                            }

                            ${
                                time
                                    ? `<span class="ms-2"><i class="bi bi-clock me-1"></i>${escapeHtml(time)}</span>`
                                    : ''
                            }

                            ${
                                appointment.status
                                    ? `<span class="ms-2 badge bg-secondary">${escapeHtml(appointment.status)}</span>`
                                    : ''
                            }

                        </div>

                    </div>

                `;

            }).join('');


        resultsBox.style.display = 'block';


        resultsBox
            .querySelectorAll('.search-result-item')
            .forEach(function(item) {

                item.addEventListener(
                    'click',
                    function() {

                        const appointmentId =
                            this.dataset.appointmentId;


                        selectAppointment(
                            appointmentId
                        );

                    }
                );

            });

    }


    /* ============================================================
       SELECT APPOINTMENT
    ============================================================ */

    function selectAppointment(appointmentId) {

        const appointment =
            (Array.isArray(appointments) ? appointments : [])
                .find(function(item) {

                    return Number(item.id) ===
                           Number(appointmentId);

                });


        if (!appointment) {

            console.error(
                'Appointment not found:',
                appointmentId
            );

            return;
        }


        const searchInput =
            document.getElementById(
                'appointmentSearch'
            );


        const hiddenInput =
            document.getElementById(
                'appointment_id'
            );


        const resultsBox =
            document.getElementById(
                'appointmentSearchResults'
            );


        if (searchInput) {

            let display =
                '#' +
                appointment.id +
                ' - ' +
                (
                    appointment.customer_name ||
                    'Walk-in'
                );


            if (appointment.service_name) {

                display +=
                    ' - ' +
                    appointment.service_name;

            }


            searchInput.value =
                display;

            searchInput.classList.add(
                'search-selected'
            );

        }


        if (hiddenInput) {

            hiddenInput.value =
                appointment.id;

        }


        if (resultsBox) {

            resultsBox.innerHTML = '';

            resultsBox.style.display = 'none';

        }


        /* ========================================================
           AUTO SELECT CUSTOMER
        ======================================================== */

        if (appointment.customer_id) {

            selectCustomer(
                appointment.customer_id
            );

        } else {

            clearCustomer();

        }


        /* ========================================================
           AUTO ADD APPOINTMENT SERVICE
        ======================================================== */

        if (appointment.service_id) {

            console.log(
                'APPOINTMENT SERVICE ADD:',
                appointment.service_id
            );


            addService(
                appointment.service_id,
                null,
                appointment.staff_id || null
            );

        }

    }


    /* ============================================================
       CLEAR APPOINTMENT
    ============================================================ */

    function clearAppointment() {

        const searchInput =
            document.getElementById(
                'appointmentSearch'
            );

        const hiddenInput =
            document.getElementById(
                'appointment_id'
            );


        if (searchInput) {

            searchInput.value = '';

            searchInput.classList.remove(
                'search-selected'
            );

        }


        if (hiddenInput) {
            hiddenInput.value = '';
        }

    }


    /* ============================================================
       RESUME BILL
    ============================================================ */

    function loadResumeBill() {

        if (
            !resumeBill ||
            !resumeBill.items
        ) {

            renderCart();

            return;
        }


        /* CUSTOMER */

        if (resumeBill.customer_id) {

            selectCustomer(
                resumeBill.customer_id
            );

        }


        /* APPOINTMENT */

        if (resumeBill.appointment_id) {

            const appointment =
                (Array.isArray(appointments)
                    ? appointments
                    : []
                ).find(function(item) {

                    return Number(item.id) ===
                           Number(resumeBill.appointment_id);

                });


            if (appointment) {

                selectAppointment(
                    appointment.id
                );

            } else {

                const appointmentInput =
                    document.getElementById(
                        'appointment_id'
                    );


                if (appointmentInput) {

                    appointmentInput.value =
                        resumeBill.appointment_id;

                }

            }

        }


        const overallDiscount =
            document.getElementById(
                'overallDiscount'
            );


        if (overallDiscount) {
            overallDiscount.value = 0;
        }


        removeCoupon(false);


        cart = [];


        resumeBill.items.forEach(function(item) {

            const service =
                getServiceById(
                    item.service_id
                );


            if (!service) {
                return;
            }


            const variation =
                item.service_variation_id
                    ? getVariationById(
                        service,
                        item.service_variation_id
                    )
                    : null;


            cart.push({

                uid:
                    Date.now().toString() +
                    Math.random().toString(36).substring(2),

                service_id:
                    Number(item.service_id),

                service_name:
                    service.name,

                variation_id:
                    item.service_variation_id
                        ? Number(item.service_variation_id)
                        : null,

                variation_name:
                    variation
                        ? variation.name
                        : '',

                staff_id:
                    item.staff_id
                        ? Number(item.staff_id)
                        : null,

                quantity:
                    Number(item.quantity || 1),

                price:
                    Number(item.unit_price || 0),

                discount:
                    Number(item.discount || 0)

            });

        });


        renderCart();

        calculateTotals();

    }


    /* ============================================================
       DOM READY
    ============================================================ */

    document.addEventListener(
        'DOMContentLoaded',
        function() {

            /* =====================================================
               CUSTOMER SEARCH
            ===================================================== */

            const customerSearch =
                document.getElementById(
                    'customerSearch'
                );


            if (customerSearch) {

                customerSearch.addEventListener(
                    'input',
                    function() {

                        /*
                         * If user edits selected customer,
                         * clear old customer ID.
                         */

                        const hiddenCustomer =
                            document.getElementById(
                                'customer_id'
                            );


                        if (
                            hiddenCustomer &&
                            customerSearch.classList.contains(
                                'search-selected'
                            )
                        ) {

                            hiddenCustomer.value = '';

                            customerSearch.classList.remove(
                                'search-selected'
                            );

                        }


                        searchCustomers(
                            this.value
                        );

                    }
                );


                customerSearch.addEventListener(
                    'focus',
                    function() {

                        if (this.value.trim()) {

                            searchCustomers(
                                this.value
                            );

                        }

                    }
                );

            }


            /* =====================================================
               APPOINTMENT SEARCH
            ===================================================== */

            const appointmentSearch =
                document.getElementById(
                    'appointmentSearch'
                );


            if (appointmentSearch) {

                appointmentSearch.addEventListener(
                    'input',
                    function() {

                        const hiddenAppointment =
                            document.getElementById(
                                'appointment_id'
                            );


                        if (
                            hiddenAppointment &&
                            appointmentSearch.classList.contains(
                                'search-selected'
                            )
                        ) {

                            hiddenAppointment.value = '';

                            appointmentSearch.classList.remove(
                                'search-selected'
                            );

                        }


                        searchAppointments(
                            this.value
                        );

                    }
                );


                appointmentSearch.addEventListener(
                    'focus',
                    function() {

                        if (this.value.trim()) {

                            searchAppointments(
                                this.value
                            );

                        }

                    }
                );

            }


            /* =====================================================
               OVERALL DISCOUNT
            ===================================================== */

            const overallDiscount =
                document.getElementById(
                    'overallDiscount'
                );


            if (overallDiscount) {

                overallDiscount.addEventListener(
                    'input',
                    calculateTotals
                );

            }


            /* =====================================================
               TAX
            ===================================================== */

            document
                .querySelectorAll(
                    'input[name="tax_mode"]'
                )
                .forEach(function(radio) {

                    radio.addEventListener(
                        'change',
                        calculateTotals
                    );

                });


            /* =====================================================
               PAYMENT
            ===================================================== */

            bindPaymentInputs();


            /* =====================================================
               SERVICE SEARCH
            ===================================================== */

            const serviceSearch =
                document.getElementById(
                    'serviceSearch'
                );


            if (serviceSearch) {

                serviceSearch.addEventListener(
                    'input',
                    function() {

                        const query =
                            this.value
                                .trim()
                                .toLowerCase();


                        let visibleCount = 0;


                        document
                            .querySelectorAll(
                                '.service-item'
                            )
                            .forEach(function(item) {

                                const name =
                                    item.dataset.serviceName || '';


                                const category =
                                    item.dataset.serviceCategory || '';


                                const match =
                                    name.includes(query) ||
                                    category.includes(query);


                                if (match) {

                                    item.classList.remove(
                                        'd-none'
                                    );

                                    visibleCount++;

                                } else {

                                    item.classList.add(
                                        'd-none'
                                    );

                                }

                            });


                        document
                            .querySelectorAll(
                                '.service-category'
                            )
                            .forEach(function(categoryElement) {

                                const visibleItems =
                                    categoryElement.querySelectorAll(
                                        '.service-item:not(.d-none)'
                                    );


                                if (
                                    visibleItems.length === 0
                                ) {

                                    categoryElement.classList.add(
                                        'd-none'
                                    );

                                } else {

                                    categoryElement.classList.remove(
                                        'd-none'
                                    );

                                }

                            });


                        const noServices =
                            document.getElementById(
                                'noServicesFound'
                            );


                        if (noServices) {

                            if (visibleCount === 0) {

                                noServices.classList.remove(
                                    'd-none'
                                );

                            } else {

                                noServices.classList.add(
                                    'd-none'
                                );

                            }

                        }

                    }
                );

            }


            /* =====================================================
               OUTSIDE CLICK
            ===================================================== */

            document.addEventListener(
                'click',
                function(event) {

                    const customerBox =
                        document.getElementById(
                            'customerSearchResults'
                        );

                    const customerInput =
                        document.getElementById(
                            'customerSearch'
                        );


                    if (
                        customerBox &&
                        customerInput &&
                        !customerBox.contains(event.target) &&
                        !customerInput.contains(event.target)
                    ) {

                        customerBox.style.display =
                            'none';

                    }


                    const appointmentBox =
                        document.getElementById(
                            'appointmentSearchResults'
                        );

                    const appointmentInput =
                        document.getElementById(
                            'appointmentSearch'
                        );


                    if (
                        appointmentBox &&
                        appointmentInput &&
                        !appointmentBox.contains(event.target) &&
                        !appointmentInput.contains(event.target)
                    ) {

                        appointmentBox.style.display =
                            'none';

                    }

                }
            );


            /* =====================================================
               INITIAL
            ===================================================== */

            loadResumeBill();

        }
    );


    /* ============================================================
       BRANCH CHANGE
    ============================================================ */

    document.addEventListener(
        'DOMContentLoaded',
        function() {

            const branchSelect =
                document.getElementById(
                    'branch_id'
                );


            if (!branchSelect) {
                return;
            }


            branchSelect.addEventListener(
                'change',
                function() {

                    const branchId =
                        this.value;


                    if (!branchId) {
                        return;
                    }


                    const url =
                        new URL(
                            window.location.href
                        );


                    url.searchParams.set(
                        'branch_id',
                        branchId
                    );


                    url.searchParams.delete(
                        'resume_bill_id'
                    );


                    window.location.href =
                        url.toString();

                }
            );

        }
    );

</script>

@endsection
