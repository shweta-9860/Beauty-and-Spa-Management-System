@extends('admin.layout')

@section('title', 'Packages | Salon Management')
@section('page-title', 'Packages')

@section('content')

<div class="container-fluid px-0 packages-page">

    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show packages-alert packages-alert-success"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- =========================================================
         ERROR MESSAGES
    ========================================================== --}}
    @if($errors->any())
        <div class="alert alert-danger packages-alert packages-alert-danger">

            <div class="fw-semibold mb-2">
                Please check the following:
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="packages-page-header mb-4">

        <div class="packages-header-content">

            <div>
                <div class="packages-title-row">

                    <div class="packages-title-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div>
                        <h4 class="mb-1">
                            Packages
                        </h4>

                        <p class="text-muted mb-0">
                            Manage salon packages and package offers.
                        </p>
                    </div>

                </div>
            </div>


            <a href="{{ route('admin.packages.create') }}"
               class="btn packages-primary-btn">

                <i class="bi bi-plus-circle me-1"></i>
                Add Package

            </a>

        </div>

    </div>


    {{-- =========================================================
         PACKAGE LIST CARD
    ========================================================== --}}
    <div class="card packages-card border-0">

        {{-- CARD HEADER --}}
        <div class="card-header packages-card-header">

            <div class="packages-list-heading">

                <div>
                    <h5 class="mb-1">
                        Package List
                    </h5>

                    <span class="packages-subheading">
                        Manage all your salon packages
                    </span>
                </div>

                <span class="packages-count-badge">
                    {{ $packages->count() }} Package(s)
                </span>

            </div>

        </div>


        {{-- CARD BODY --}}
        <div class="card-body p-0">

            @if($packages->count() > 0)

                <div class="table-responsive packages-table-wrapper">

                    <table class="table packages-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="px-3">
                                    #
                                </th>

                                <th>
                                    Package
                                </th>

                                <th>
                                    Code
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Validity
                                </th>

                                <th>
                                    Expiry Date
                                </th>

                                <th>
                                    Uses
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end px-3">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($packages as $package)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Calculate Created-Based Expiry
                                    |--------------------------------------------------------------------------
                                    */
                                    $createdExpiryDate = $package->created_at
                                        ? \Carbon\Carbon::parse($package->created_at)
                                            ->addDays((int) $package->validity_days)
                                        : null;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Check Created-Based Expiry
                                    |--------------------------------------------------------------------------
                                    */
                                    $isExpired = $createdExpiryDate
                                        ? now()->greaterThanOrEqualTo($createdExpiryDate)
                                        : false;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Final Active State
                                    |--------------------------------------------------------------------------
                                    */
                                    $isActive = !$isExpired && (bool) $package->status;

                                @endphp


                                <tr>

                                    {{-- =====================================================
                                         ID
                                    ====================================================== --}}
                                    <td class="px-3">

                                        <span class="packages-row-number">
                                            {{ $loop->iteration }}
                                        </span>

                                    </td>


                                    {{-- =====================================================
                                         PACKAGE NAME
                                    ====================================================== --}}
                                    <td>

                                        <div class="packages-name">
                                            {{ $package->name }}
                                        </div>

                                        @if($package->description)

                                            <div class="packages-description">

                                                {{ Str::limit($package->description, 60) }}

                                            </div>

                                        @endif

                                    </td>


                                    {{-- =====================================================
                                         CODE
                                    ====================================================== --}}
                                    <td>

                                        @if($package->code)

                                            <span class="packages-code">
                                                {{ $package->code }}
                                            </span>

                                        @else

                                            <span class="packages-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =====================================================
                                         PRICE
                                    ====================================================== --}}
                                    <td>

                                        <span class="packages-price">
                                            ₹{{ number_format($package->price, 2) }}
                                        </span>

                                    </td>


                                    {{-- =====================================================
                                         VALIDITY
                                    ====================================================== --}}
                                    <td>

                                        <span class="packages-validity-number">
                                            {{ $package->validity_days ?? 0 }}
                                        </span>

                                        <span class="packages-validity-text">
                                            days
                                        </span>

                                    </td>


                                    {{-- =====================================================
                                         EXPIRY DATE
                                    ====================================================== --}}
                                    <td>

                                        @if($package->expiry_date)

                                            @php

                                                $expiryDate = \Carbon\Carbon::parse(
                                                    $package->expiry_date
                                                )->startOfDay();

                                                $today = now()->startOfDay();

                                                $daysLeft = (int) $today->diffInDays(
                                                    $expiryDate,
                                                    false
                                                );

                                            @endphp


                                            <div class="packages-expiry-date">
                                                {{ $expiryDate->format('d M Y') }}
                                            </div>


                                            @if($daysLeft > 0)

                                                <div class="packages-expiry-left packages-expiry-active">
                                                    {{ $daysLeft }} days left
                                                </div>

                                            @elseif($daysLeft === 0)

                                                <div class="packages-expiry-left packages-expiry-today">
                                                    Expires today
                                                </div>

                                            @else

                                                <div class="packages-expiry-left packages-expiry-expired">
                                                    Expired
                                                </div>

                                            @endif

                                        @else

                                            <span class="packages-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =====================================================
                                         TOTAL USES
                                    ====================================================== --}}
                                    <td>

                                        <span class="packages-uses">
                                            {{ $package->total_uses ?? 0 }}
                                        </span>

                                    </td>


                                    {{-- =====================================================
                                         STATUS
                                    ====================================================== --}}
                                    <td>

                                        @if($package->expiry_date)

                                            @php

                                                $expiryDate = \Carbon\Carbon::parse(
                                                    $package->expiry_date
                                                )->startOfDay();

                                                $today = now()->startOfDay();

                                            @endphp


                                            @if($expiryDate->gte($today))

                                                <span class="packages-status packages-status-active">
                                                    Active
                                                </span>

                                            @else

                                                <span class="packages-status packages-status-inactive">
                                                    Inactive
                                                </span>

                                            @endif

                                        @else

                                            <span class="packages-status packages-status-inactive">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =====================================================
                                         ACTIONS
                                    ====================================================== --}}
                                    <td class="text-end px-3">

                                        <div class="packages-actions">

                                            {{-- VIEW --}}
                                            <a href="{{ route('admin.packages.show', $package->id) }}"
                                               class="packages-action-btn packages-view-btn"
                                               title="View">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            {{-- EDIT --}}
                                            <a href="{{ route('admin.packages.edit', $package->id) }}"
                                               class="packages-action-btn packages-edit-btn"
                                               title="Edit">

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            {{-- DELETE --}}
                                            <form action="{{ route('admin.packages.destroy', $package->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this package?');">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="packages-action-btn packages-delete-btn"
                                                        title="Delete">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- =====================================================
                     EMPTY STATE
                ====================================================== --}}
                <div class="packages-empty-state">

                    <div class="packages-empty-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <h5>
                        No Packages Found
                    </h5>

                    <p>
                        You haven't created any package yet.
                    </p>

                    <a href="{{ route('admin.packages.create') }}"
                       class="btn packages-primary-btn">

                        <i class="bi bi-plus-circle me-1"></i>
                        Create First Package

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>

    /* =========================================================
       PACKAGES PAGE
    ========================================================== */

    .packages-page {
        width: 100%;
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .packages-alert {
        border: 0;
        border-radius: 15px;
        padding: 14px 18px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .06);
    }

    .packages-alert-success {
        border-left: 4px solid #198754;
    }

    .packages-alert-danger {
        border-left: 4px solid #dc3545;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .packages-page-header {
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

    .packages-page-header::before {
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

    .packages-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .packages-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .packages-title-icon {
        width: 44px;
        height: 44px;
        border-radius: 13px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(
            145deg,
            #f8f1f4,
            #eee2e7
        );

        color: #8b5e6d;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 5px 12px rgba(139,94,109,.12);
    }

    .packages-title-icon i {
        font-size: 20px;
    }


    /* =========================================================
       PRIMARY BUTTON
    ========================================================== */

    .packages-primary-btn {
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

    .packages-primary-btn:hover {
        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
            0 7px 0 #62414d,
            0 15px 25px rgba(139,94,109,.20);
    }

    .packages-primary-btn:active {
        transform: translateY(2px);

        box-shadow:
            0 2px 0 #62414d,
            0 6px 12px rgba(139,94,109,.12);
    }


    /* =========================================================
       MAIN CARD
    ========================================================== */

    .packages-card {
        position: relative;
        overflow: hidden;

        background: #ffffff;

        border-radius: 19px !important;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .07),
            0 25px 55px rgba(45, 37, 41, .05);
    }

    .packages-card::before {
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

    .packages-card-header {
        background: #ffffff !important;
        border-bottom: 1px solid #eee8eb !important;
        padding: 18px 20px !important;
    }

    .packages-list-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .packages-list-heading h5 {
        font-weight: 600;
    }

    .packages-subheading {
        color: #8a8085;
    }

    .packages-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 7px 12px;

        border-radius: 10px;

        background: #f5eef1;
        color: #76505e;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 3px 8px rgba(139,94,109,.08);
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .packages-table-wrapper {
        overflow-x: auto;
    }

    .packages-table {
        min-width: 1050px;
        background: #ffffff;
    }

    .packages-table th {
        white-space: nowrap;

        background: #f8f5f6;

        border-bottom: 1px solid #e9e1e5;

        font-weight: 600;
        font-size: 13px;

        color: #62575d;

        padding-top: 14px;
        padding-bottom: 14px;
    }

    .packages-table td {
        vertical-align: middle;

        border-color: #f0eaed;

        font-size: 14px;

        padding-top: 15px;
        padding-bottom: 15px;
    }

    .packages-table tbody tr {
        transition:
            background .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .packages-table tbody tr:hover {
        background: #fcf9fa;
        box-shadow: inset 0 1px 0 rgba(139,94,109,.04);
    }


    /* =========================================================
       ROW NUMBER
    ========================================================== */

    .packages-row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 28px;
        height: 28px;

        padding: 0 7px;

        border-radius: 8px;

        background: #f6f1f3;
        color: #76505e;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9);
    }


    /* =========================================================
       PACKAGE DETAILS
    ========================================================== */

    .packages-name {
        font-weight: 600;
        color: #332d31;
    }

    .packages-description {
        margin-top: 3px;
        color: #8a8085;
    }

    .packages-code {
        display: inline-block;

        padding: 5px 9px;

        border-radius: 8px;

        background: #f7f3f5;
        color: #76505e;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.8);
    }

    .packages-price {
        font-weight: 700;
        color: #76505e;
    }

    .packages-validity-number {
        font-weight: 600;
        color: #3f363b;
    }

    .packages-validity-text {
        color: #8a8085;
    }

    .packages-muted {
        color: #a29a9e;
    }


    /* =========================================================
       EXPIRY
    ========================================================== */

    .packages-expiry-date {
        font-weight: 600;
        color: #3d3439;
    }

    .packages-expiry-left {
        margin-top: 3px;
    }

    .packages-expiry-active {
        color: #198754;
    }

    .packages-expiry-today {
        color: #c58a00;
    }

    .packages-expiry-expired {
        color: #dc3545;
    }


    /* =========================================================
       USES
    ========================================================== */

    .packages-uses {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 34px;
        height: 30px;

        padding: 0 9px;

        border-radius: 9px;

        background: #f6f1f3;
        color: #76505e;

        font-weight: 600;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .packages-status {
        display: inline-flex;
        align-items: center;

        padding: 6px 11px;

        border-radius: 9px;

        font-weight: 600;
    }

    .packages-status-active {
        background: #eaf7ef;
        color: #198754;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.8),
            0 3px 8px rgba(25,135,84,.06);
    }

    .packages-status-inactive {
        background: #fcebec;
        color: #c0394b;

        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.8),
            0 3px 8px rgba(192,57,75,.06);
    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================== */

    .packages-actions {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .packages-action-btn {
        width: 35px;
        height: 35px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #ffffff;

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .packages-action-btn:hover {
        transform: translateY(-2px);
    }

    .packages-view-btn {
        border: 1px solid #d9c4cc;
        color: #76505e;

        box-shadow:
            0 3px 8px rgba(139,94,109,.08);
    }

    .packages-view-btn:hover {
        background: #f7f0f3;
        color: #76505e;

        box-shadow:
            0 7px 15px rgba(139,94,109,.12);
    }

    .packages-edit-btn {
        border: 1px solid #d5c1ca;
        color: #8b5e6d;

        box-shadow:
            0 3px 8px rgba(139,94,109,.08);
    }

    .packages-edit-btn:hover {
        background: #f7f0f3;
        color: #76505e;

        box-shadow:
            0 7px 15px rgba(139,94,109,.12);
    }

    .packages-delete-btn {
        border: 1px solid #edc9cf;
        color: #c0394b;

        box-shadow:
            0 3px 8px rgba(192,57,75,.06);
    }

    .packages-delete-btn:hover {
        background: #fff3f4;
        color: #b02a3b;

        box-shadow:
            0 7px 15px rgba(192,57,75,.10);
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .packages-empty-state {
        text-align: center;
        padding: 70px 20px;
    }

    .packages-empty-icon {
        width: 78px;
        height: 78px;

        margin: 0 auto 18px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 22px;

        background: linear-gradient(
            145deg,
            #f8f1f4,
            #eee2e7
        );

        color: #8b5e6d;

        box-shadow:
            inset 0 2px 4px rgba(255,255,255,.9),
            0 10px 25px rgba(139,94,109,.12);
    }

    .packages-empty-icon i {
        font-size: 32px;
    }

    .packages-empty-state h5 {
        font-weight: 600;
        margin-bottom: 7px;
    }

    .packages-empty-state p {
        color: #8a8085;
        margin-bottom: 20px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 768px) {

        .packages-page-header {
            padding: 19px;
        }

        .packages-header-content {
            align-items: flex-start;
            flex-direction: column;
        }

        .packages-primary-btn {
            width: 100%;
            text-align: center;
        }

        .packages-list-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .packages-card-header {
            padding: 16px !important;
        }

        .packages-table {
            min-width: 1000px;
        }

    }

</style>

@endpush