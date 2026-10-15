@extends('admin.layout')

@section('title', 'Assigned Members | Salon Management')

@section('page-title', 'Assigned Members')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>

                    <h5 class="mb-1 fw-semibold">

                        <i class="bi bi-people me-2"></i>

                        Assigned Members

                    </h5>

                    <small class="text-muted">

                        View customers who have been assigned membership plans.

                    </small>

                </div>


                <a href="{{ route('admin.membership.customers.create') }}"
                   class="btn btn-primary">

                    <i class="bi bi-person-plus me-1"></i>

                    Assign Membership

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.membership.assigned-members') }}">

                <div class="row g-3">


                    {{-- SEARCH --}}

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Search Customer

                        </label>

                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="Customer name or phone">

                    </div>


                    {{-- PLAN --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Membership Plan

                        </label>

                        <select name="membership_plan_id"
                                class="form-select">

                            <option value="">

                                All Plans

                            </option>

                            @foreach($membershipPlans as $plan)

                                <option value="{{ $plan->id }}"
                                    {{ request('membership_plan_id') == $plan->id ? 'selected' : '' }}>

                                    {{ $plan->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">

                            Status

                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">

                                All Status

                            </option>

                            <option value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}>

                                Active

                            </option>

                            <option value="expired"
                                {{ request('status') == 'expired' ? 'selected' : '' }}>

                                Expired

                            </option>

                            <option value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}>

                                Pending

                            </option>

                            <option value="cancelled"
                                {{ request('status') == 'cancelled' ? 'selected' : '' }}>

                                Cancelled

                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-md-2 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search"></i>

                        </button>


                        <a href="{{ route('admin.membership.assigned-members') }}"
                           class="btn btn-outline-secondary">

                            <i class="bi bi-arrow-clockwise"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

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
        TABLE
    ========================================================== --}}

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom">

            <h6 class="mb-0 fw-semibold">

                <i class="bi bi-card-list me-2"></i>

                Assigned Memberships

            </h6>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Customer</th>

                            <th>Membership Plan</th>

                            <th>Price</th>

                            <th>Discount</th>

                            <th>Paid Amount</th>

                            <th>Start Date</th>

                            <th>Expiry Date</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($assignedMembers as $membership)

                            <tr>

                                {{-- NUMBER --}}

                                <td>

                                    {{ $assignedMembers->firstItem() + $loop->index }}

                                </td>


                                {{-- CUSTOMER --}}

                                <td>

                                    @if($membership->customer)

                                        <div class="fw-semibold">

                                            {{ $membership->customer->name }}

                                        </div>

                                        @if($membership->customer->phone)

                                            <small class="text-muted">

                                                {{ $membership->customer->phone }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">

                                            Customer deleted

                                        </span>

                                    @endif

                                </td>


                                {{-- PLAN --}}

                                <td>

                                    @if($membership->membershipPlan)

                                        <div class="fw-semibold">

                                            {{ $membership->membershipPlan->name }}

                                        </div>

                                        @if($membership->membershipPlan->code)

                                            <small class="text-muted">

                                                {{ $membership->membershipPlan->code }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">

                                            Plan deleted

                                        </span>

                                    @endif

                                </td>


                                {{-- PRICE --}}

                                <td>

                                    ₹{{ number_format((float) $membership->price, 2) }}

                                </td>


                                {{-- DISCOUNT --}}

                                <td>

                                    @if($membership->membershipPlan)

                                        @if($membership->membershipPlan->discount_type === 'percentage')

                                            {{ number_format((float) $membership->membershipPlan->discount_value, 2) }}%

                                        @elseif($membership->membershipPlan->discount_type === 'fixed')

                                            ₹{{ number_format((float) $membership->membershipPlan->discount_value, 2) }}

                                        @else

                                            0%

                                        @endif

                                    @else

                                        0%

                                    @endif

                                </td>


                                {{-- PAID AMOUNT --}}

                                <td>

                                    ₹{{ number_format((float) $membership->paid_amount, 2) }}

                                </td>


                                {{-- START DATE --}}

                                <td>

                                    {{ $membership->start_date
                                        ? \Carbon\Carbon::parse($membership->start_date)->format('d-m-Y')
                                        : '-' }}

                                </td>


                                {{-- EXPIRY DATE --}}

                                <td>

                                    {{ $membership->expiry_date
                                        ? \Carbon\Carbon::parse($membership->expiry_date)->format('d-m-Y')
                                        : '-' }}

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    @if($membership->status === 'active')

                                        <span class="badge bg-success">

                                            Active

                                        </span>

                                    @elseif($membership->status === 'expired')

                                        <span class="badge bg-danger">

                                            Expired

                                        </span>

                                    @elseif($membership->status === 'pending')

                                        <span class="badge bg-warning text-dark">

                                            Pending

                                        </span>

                                    @elseif($membership->status === 'cancelled')

                                        <span class="badge bg-secondary">

                                            Cancelled

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($membership->status) }}

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="bi bi-people fs-1 d-block mb-2"></i>

                                        No assigned memberships found.

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($assignedMembers->hasPages())

            <div class="card-footer bg-white">

                {{ $assignedMembers->links() }}

            </div>

        @endif

    </div>

</div>

@endsection