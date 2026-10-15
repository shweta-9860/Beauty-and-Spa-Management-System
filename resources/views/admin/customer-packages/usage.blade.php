@extends('admin.layout')

@section('title', 'Package Usage History | Salon Management')

@section('page-title', 'Package Usage History')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
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


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                Error

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


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">

                <i class="bi bi-clock-history me-2"></i>

                Package Usage History

            </h4>

            <p class="text-muted mb-0">

                View customers and their package service usage.

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('admin.customer-packages.index') }}"
                class="btn btn-outline-secondary"
            >

                <i class="bi bi-box-seam me-1"></i>

                Customer Packages

            </a>

        </div>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white py-3">

            <h5 class="mb-0">

                <i class="bi bi-funnel me-2"></i>

                Filter Usage

            </h5>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.customer-package-usage.index') }}"
            >

                <div class="row g-3 align-items-end">


                    {{-- From Date --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            From Date

                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}"
                        >

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-3">

                        <label class="form-label">

                            To Date

                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}"
                        >

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3">

                        <button
                            type="submit"
                            class="btn text-white"
                            style="background:#8b5e6d;"
                        >

                            <i class="bi bi-search me-1"></i>

                            Filter

                        </button>


                        <a
                            href="{{ route('admin.customer-package-usage.index') }}"
                            class="btn btn-light border"
                        >

                            <i class="bi bi-arrow-clockwise me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-4">


        {{-- Total Usage --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">

                        Total Usage Records

                    </div>

                    <div class="fs-3 fw-bold">

                        {{ $totalUsageRecords }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Services Used --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">

                        Total Services Used

                    </div>

                    <div class="fs-3 fw-bold text-success">

                        {{ $totalServicesUsed }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Customers --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="text-muted small">

                        Customers

                    </div>

                    <div class="fs-3 fw-bold text-primary">

                        {{ $totalCustomers }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CUSTOMER PACKAGE TABLE
    ========================================================== --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="bi bi-list-check me-2"></i>

                    Customer Packages

                </h5>


                <span class="badge bg-secondary">

                    {{ $customerPackages->count() }} Customers

                </span>

            </div>

        </div>


        <div class="card-body p-0">


            @if($customerPackages->count())


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">


                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Customer</th>

                                <th>Package</th>

                                <th>Usage Status</th>

                                <th>Services Used</th>

                                <th>Last Used Date</th>

                            </tr>

                        </thead>


                        <tbody>


                            @foreach($customerPackages as $customerPackage)


                                @php

                                    $packageUsages =
                                        $customerPackage->usages ?? collect();

                                    $usedQuantity =
                                        $packageUsages->sum('quantity');

                                    $lastUsage =
                                        $packageUsages->first();

                                @endphp


                                <tr>


                                    {{-- Number --}}
                                    <td>

                                        {{ $loop->iteration }}

                                    </td>


                                    {{-- Customer --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $customerPackage->customer->name ?? 'N/A' }}

                                        </div>


                                        @if(!empty($customerPackage->customer?->phone))

                                            <small class="text-muted">

                                                {{ $customerPackage->customer->phone }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Package --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $customerPackage->package->name ?? 'N/A' }}

                                        </div>


                                        @if(!empty($customerPackage->purchase_number))

                                            <small class="text-muted">

                                                Purchase No:
                                                {{ $customerPackage->purchase_number }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Usage Status --}}
                                    <td>


                                        @if($packageUsages->count())

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Used

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                <i class="bi bi-clock me-1"></i>

                                                Not Used

                                            </span>

                                        @endif


                                    </td>


                                    {{-- Services Used --}}
                                    <td>


                                        @if($packageUsages->count())

                                            <span class="badge bg-success">

                                                {{ $usedQuantity }}

                                            </span>


                                            <div class="mt-1">

                                                @foreach($packageUsages as $usage)

                                                    <small class="d-block text-muted">

                                                        {{ $usage->service->name
                                                            ?? $usage->packageService->service->name
                                                            ?? 'Service' }}

                                                        ×

                                                        {{ $usage->quantity }}

                                                    </small>

                                                @endforeach

                                            </div>

                                        @else

                                            <span class="text-muted">

                                                No services used yet

                                            </span>

                                        @endif


                                    </td>


                                    {{-- Last Used Date --}}
                                    <td>


                                        @if($lastUsage && $lastUsage->used_date)

                                            {{ $lastUsage->used_date->format('d M Y') }}

                                        @else

                                            <span class="text-muted">

                                                Not used yet

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


                    <i
                        class="bi bi-people text-muted"
                        style="font-size:56px;"
                    ></i>


                    <h5 class="mt-3">

                        No Customer Packages Found

                    </h5>


                    <p class="text-muted mb-3">

                        No customer packages are available for this salon.

                    </p>


                    <a
                        href="{{ route('admin.customer-packages.index') }}"
                        class="btn text-white"
                        style="background:#8b5e6d;"
                    >

                        <i class="bi bi-box-seam me-1"></i>

                        Customer Packages

                    </a>


                </div>


            @endif


        </div>

    </div>

</div>

@endsection