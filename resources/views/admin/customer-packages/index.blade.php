@extends('admin.layout')

@section('title', 'Customer Packages | Salon Management')

@section('page-title', 'Customer Packages')

@section('content')

<div class="container-fluid px-0">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- Error Message --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Please fix the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="bi bi-person-vcard me-2"></i>

                Customer Packages

            </h4>

            <p class="text-muted mb-0">

                Manage customer package purchases and usage balances.

            </p>

        </div>


        <a
            href="{{ route('admin.customer-packages.create') }}"
            class="btn text-white"
            style="background:#8b5e6d;"
        >

            <i class="bi bi-plus-circle me-1"></i>

            Purchase Package

        </a>

    </div>


    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">


        {{-- Total Purchases --}}
        <div class="col-md-3">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Total Purchases
                            </div>

                            <h4 class="mb-0">

                                {{ $customerPackages->count() }}

                            </h4>

                        </div>

                        <div class="fs-3 text-primary">

                            <i class="bi bi-box-seam"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active Packages --}}
        <div class="col-md-3">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Active Packages
                            </div>

                            <h4 class="mb-0">

                                {{ $customerPackages->where('status', 'active')->count() }}

                            </h4>

                        </div>

                        <div class="fs-3 text-success">

                            <i class="bi bi-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Expired --}}
        <div class="col-md-3">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Expired
                            </div>

                            <h4 class="mb-0">

                                {{ $customerPackages->where('status', 'expired')->count() }}

                            </h4>

                        </div>

                        <div class="fs-3 text-danger">

                            <i class="bi bi-calendar-x"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Remaining Uses --}}
        <div class="col-md-3">

            <div class="card dashboard-card h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="text-muted small">
                                Remaining Uses
                            </div>

                            <h4 class="mb-0">

                                {{ $customerPackages->sum('remaining_uses') }}

                            </h4>

                        </div>

                        <div class="fs-3 text-warning">

                            <i class="bi bi-arrow-repeat"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Customer Packages Table --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="bi bi-list-ul me-2"></i>

                    Package Purchases

                </h5>

                <span class="badge text-bg-light border">

                    {{ $customerPackages->count() }} Records

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($customerPackages->count() > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Purchase No.</th>

                                <th>Customer</th>

                                <th>Package</th>

                                <th>Amount</th>

                                <th style="min-width:280px;">
                                    Usage
                                </th>

                                <th>Purchase Date</th>

                                <th>Expiry</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($customerPackages as $index => $customerPackage)

                                <tr>

                                    {{-- Number --}}
                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- Purchase Number --}}
                                    <td>

                                        <span class="fw-semibold">

                                            {{ $customerPackage->purchase_number }}

                                        </span>

                                    </td>


                                    {{-- Customer --}}
                                    <td>

                                        @if($customerPackage->customer)

                                            <div class="fw-semibold">

                                                {{ $customerPackage->customer->name }}

                                            </div>

                                            @if(!empty($customerPackage->customer->phone))

                                                <small class="text-muted">

                                                    {{ $customerPackage->customer->phone }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">

                                                Customer deleted

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Package --}}
                                    <td>

                                        @if($customerPackage->package)

                                            <div class="fw-semibold">

                                                {{ $customerPackage->package->name }}

                                            </div>

                                            @if(!empty($customerPackage->package->code))

                                                <small class="text-muted">

                                                    {{ $customerPackage->package->code }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">

                                                Package deleted

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}
                                    <td>

                                        <div class="fw-semibold">

                                            ₹{{ number_format($customerPackage->price, 2) }}

                                        </div>

                                        @if($customerPackage->discount > 0)

                                            <small class="text-success">

                                                Discount:

                                                ₹{{ number_format(
                                                    $customerPackage->discount,
                                                    2
                                                ) }}

                                            </small>

                                        @endif

                                        <br>

                                        <small class="text-muted">

                                            Paid:

                                            ₹{{ number_format(
                                                $customerPackage->paid_amount,
                                                2
                                            ) }}

                                        </small>

                                    </td>


                                    {{-- =====================================================
                                         USAGE + USAGE HISTORY
                                    ====================================================== --}}
                                    <td>

                                        {{-- Usage Count --}}
                                        <div class="fw-semibold">

                                            {{ $customerPackage->used_uses }}

                                            /

                                            {{ $customerPackage->total_uses }}

                                        </div>


                                        {{-- Remaining --}}
                                        <small class="text-success d-block mb-2">

                                            {{ $customerPackage->remaining_uses }}

                                            remaining

                                        </small>


                                        {{-- Usage History --}}
                                        @if($customerPackage->usages && $customerPackage->usages->count() > 0)

                                            <div class="border rounded p-2 bg-light">

                                                <div class="small fw-semibold mb-2">

                                                    <i class="bi bi-clock-history me-1"></i>

                                                    Usage History

                                                </div>


                                                @foreach($customerPackage->usages->sortByDesc('used_date') as $usage)

                                                    <div
                                                        class="border-bottom pb-2 mb-2"
                                                        @if($loop->last)
                                                            style="border-bottom:0 !important;margin-bottom:0 !important;padding-bottom:0 !important;"
                                                        @endif
                                                    >

                                                        {{-- Service --}}
                                                        <div class="fw-semibold small">

                                                            {{ $usage->service->name ?? 'N/A' }}

                                                            @if($usage->serviceVariation)

                                                                <span class="text-muted">

                                                                    -
                                                                    {{ $usage->serviceVariation->name }}

                                                                </span>

                                                            @endif

                                                        </div>


                                                        {{-- Date + Quantity --}}
                                                        <div class="small text-muted">

                                                            <i class="bi bi-calendar3 me-1"></i>

                                                            @if($usage->used_date)

                                                                {{ $usage->used_date->format('d M Y') }}

                                                            @else

                                                                -

                                                            @endif


                                                            <span class="mx-1">
                                                                •
                                                            </span>


                                                            <i class="bi bi-repeat me-1"></i>

                                                            {{ $usage->quantity }}

                                                            use(s)

                                                        </div>


                                                        {{-- Notes --}}
                                                        @if($usage->notes)

                                                            <div class="small text-muted mt-1">

                                                                <i class="bi bi-chat-left-text me-1"></i>

                                                                {{ $usage->notes }}

                                                            </div>

                                                        @endif

                                                    </div>

                                                @endforeach

                                            </div>

                                        @else

                                            <div class="small text-muted">

                                                <i class="bi bi-clock me-1"></i>

                                                No usage yet

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Purchase Date --}}
                                    <td>

                                        {{ $customerPackage->purchase_date
                                            ? $customerPackage->purchase_date->format('d M Y')
                                            : '-' }}

                                    </td>


                                    {{-- Expiry --}}
                                    <td>

                                        {{ $customerPackage->expiry_date
                                            ? $customerPackage->expiry_date->format('d M Y')
                                            : '-' }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($customerPackage->status === 'active')

                                            <span class="badge text-bg-success">

                                                Active

                                            </span>

                                        @elseif($customerPackage->status === 'expired')

                                            <span class="badge text-bg-danger">

                                                Expired

                                            </span>

                                        @elseif($customerPackage->status === 'exhausted')

                                            <span class="badge text-bg-warning">

                                                Exhausted

                                            </span>

                                        @elseif($customerPackage->status === 'cancelled')

                                            <span class="badge text-bg-secondary">

                                                Cancelled

                                            </span>

                                        @else

                                            <span class="badge text-bg-light">

                                                {{ ucfirst($customerPackage->status) }}

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-box-seam"
                            style="font-size:48px;color:#8b5e6d;">
                        </i>

                    </div>

                    <h5>

                        No Customer Packages Found

                    </h5>

                    <p class="text-muted mb-3">

                        No package has been purchased for any customer yet.

                    </p>

                    <a
                        href="{{ route('admin.customer-packages.create') }}"
                        class="btn text-white"
                        style="background:#8b5e6d;"
                    >

                        <i class="bi bi-plus-circle me-1"></i>

                        Purchase First Package

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
