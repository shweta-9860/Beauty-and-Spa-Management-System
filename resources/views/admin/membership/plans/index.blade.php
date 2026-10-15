
@extends('admin.layout')

@section('title', 'Membership Plans | Salon Management')

@section('page-title', 'Membership Plans')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="bi bi-card-checklist me-2"></i>
                Membership Plans
            </h4>

            <p class="text-muted mb-0">
                Manage recurring membership plans for your salon.
            </p>
        </div>

        <a href="{{ route('admin.membership.plans.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Add Membership Plan

        </a>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        PLANS TABLE
    ========================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-semibold">
                    All Membership Plans
                </h5>

                <span class="badge bg-secondary">
                    {{ $plans->count() }} Plans
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($plans->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">#</th>

                                <th>Plan</th>

                                <th>Billing Cycle</th>

                                <th>Total Amount</th>

                                <th>Discount</th>

                                <th>Final Amount</th>

                                <th>Validity</th>

                                <th>Expiry Date</th>

                                <th>Status</th>

                                <th width="150">Action</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($plans as $plan)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | AMOUNT CALCULATION
                                    |--------------------------------------------------------------------------
                                    */

                                    $totalAmount = (float) $plan->price;

                                    $discountAmount = (float) $plan->discount_amount;

                                    $finalAmount = (float) $plan->final_amount;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | EXPIRY
                                    |--------------------------------------------------------------------------
                                    */

                                    $expiryDate = $plan->expiry_date;

                                    $isExpired = $plan->is_expired;

                                    /*
                                    |--------------------------------------------------------------------------
                                    | EFFECTIVE STATUS
                                    |--------------------------------------------------------------------------
                                    */

                                    $isActive = $plan->is_active;

                                @endphp


                                <tr>

                                    {{-- =================================================
                                        ID
                                    ================================================== --}}
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- =================================================
                                        PLAN
                                    ================================================== --}}
                                    <td>

                                        <div class="fw-semibold">
                                            {{ $plan->name }}
                                        </div>

                                        @if($plan->code)

                                            <small class="text-muted">
                                                Code: {{ $plan->code }}
                                            </small>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        BILLING CYCLE
                                    ================================================== --}}
                                    <td>

                                        @if($plan->billing_cycle === 'monthly')

                                            <span class="badge bg-info">
                                                Monthly
                                            </span>

                                        @elseif($plan->billing_cycle === 'quarterly')

                                            <span class="badge bg-primary">
                                                Quarterly
                                            </span>

                                        @else

                                            <span class="badge bg-dark">
                                                Yearly
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        TOTAL AMOUNT
                                    ================================================== --}}
                                    <td>

                                        <strong class="text-dark">

                                            ₹{{ number_format(
                                                $totalAmount,
                                                2
                                            ) }}

                                        </strong>

                                    </td>


                                    {{-- =================================================
                                        DISCOUNT
                                    ================================================== --}}
                                    <td>

                                        @if($discountAmount > 0)

                                            <div class="text-danger fw-semibold">

                                                - ₹{{ number_format(
                                                    $discountAmount,
                                                    2
                                                ) }}

                                            </div>


                                            @if($plan->discount_type === 'percentage')

                                                <small class="text-muted">

                                                    {{ number_format(
                                                        (float) $plan->discount_value,
                                                        2
                                                    ) }}%

                                                </small>

                                            @elseif($plan->discount_type === 'fixed')

                                                <small class="text-muted">
                                                    Fixed Discount
                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                ₹0.00
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        FINAL AMOUNT
                                    ================================================== --}}
                                    <td>

                                        <strong class="text-success">

                                            ₹{{ number_format(
                                                $finalAmount,
                                                2
                                            ) }}

                                        </strong>

                                    </td>


                                    {{-- =================================================
                                        VALIDITY
                                    ================================================== --}}
                                    <td>

                                        {{ $plan->validity_days }}

                                        {{ $plan->validity_days == 1
                                            ? 'Day'
                                            : 'Days'
                                        }}

                                    </td>


                                    {{-- =================================================
                                        EXPIRY DATE
                                    ================================================== --}}
                                    <td>

                                        @if($isExpired)

                                            <span class="text-danger fw-semibold">

                                                <i class="bi bi-calendar-x me-1"></i>

                                                {{ $expiryDate->format('d M Y') }}

                                            </span>

                                            <br>

                                            <small class="text-danger">
                                                Expired
                                            </small>

                                        @else

                                            <span class="text-dark">

                                                <i class="bi bi-calendar-check text-success me-1"></i>

                                                {{ $expiryDate->format('d M Y') }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        STATUS
                                    ================================================== --}}
                                    <td>

                                        @if($isActive)

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                <i class="bi bi-x-circle me-1"></i>

                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        ACTION
                                    ================================================== --}}
                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- EDIT --}}
                                            <a href="{{ route(
                                                'admin.membership.plans.edit',
                                                $plan
                                            ) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit">

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            {{-- DELETE --}}
                                            <form action="{{ route(
                                                'admin.membership.plans.destroy',
                                                $plan
                                            ) }}"
                                                  method="POST"
                                                  onsubmit="return confirm(
                                                      'Are you sure you want to delete this membership plan?'
                                                  );">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
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

                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-card-checklist"
                           style="font-size: 55px; opacity: .35;">
                        </i>

                    </div>

                    <h5 class="fw-semibold">
                        No Membership Plans Found
                    </h5>

                    <p class="text-muted mb-4">
                        Create your first membership plan to get started.
                    </p>

                    <a href="{{ route('admin.membership.plans.create') }}"
                       class="btn btn-primary">

                        <i class="bi bi-plus-lg me-1"></i>

                        Create Membership Plan

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

