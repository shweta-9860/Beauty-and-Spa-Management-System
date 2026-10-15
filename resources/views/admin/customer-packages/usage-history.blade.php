@extends('admin.layout')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-clock-history"></i>
                Package Usage History
            </h3>

            <p class="text-muted mb-0">
                View all services used from customer packages.
            </p>
        </div>

        <a href="{{ route('admin.customer-packages.index') }}"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- Summary Cards --}}
    <div class="row mb-4">

        {{-- Total Customers --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Customers
                            </h6>

                            <h3 class="mb-0">
                                {{ $totalCustomers }}
                            </h3>
                        </div>

                        <div class="fs-1 text-primary">
                            <i class="bi bi-people"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Usage Records --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Usage Records
                            </h6>

                            <h3 class="mb-0">
                                {{ $totalUsageRecords }}
                            </h3>
                        </div>

                        <div class="fs-1 text-success">
                            <i class="bi bi-receipt"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Total Services Used --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <h6 class="text-muted mb-2">
                                Services Used
                            </h6>

                            <h3 class="mb-0">
                                {{ $totalServicesUsed }}
                            </h3>
                        </div>

                        <div class="fs-1 text-warning">
                            <i class="bi bi-scissors"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header">
            <strong>
                <i class="bi bi-funnel"></i>
                Filter Usage
            </strong>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.customer-package-usage.index') }}">

                <div class="row align-items-end">

                    {{-- Customer --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Customer
                        </label>

                        <select name="customer_id"
                                class="form-select">

                            <option value="">
                                All Customers
                            </option>

                            @foreach($customerPackages->pluck('customer')->filter()->unique('id') as $customer)

                                <option value="{{ $customer->id }}"
                                    {{ request('customer_id') == $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            From Date
                        </label>

                        <input type="date"
                               name="from_date"
                               class="form-control"
                               value="{{ request('from_date') }}">

                    </div>


                    {{-- To Date --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            To Date
                        </label>

                        <input type="date"
                               name="to_date"
                               class="form-control"
                               value="{{ request('to_date') }}">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-md-3 mb-3">

                        <button type="submit"
                                class="btn btn-primary">

                            <i class="bi bi-search"></i>
                            Filter

                        </button>

                        <a href="{{ route('admin.customer-package-usage.index') }}"
                           class="btn btn-secondary">

                            <i class="bi bi-arrow-clockwise"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Usage Records --}}
    <div class="card shadow-sm border-0">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>
                <i class="bi bi-list-ul"></i>
                Service Usage Records
            </strong>

            <span class="badge bg-primary">
                {{ $usages->count() }} Records
            </span>

        </div>


        <div class="card-body p-0">

            @if($usages->count())

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Package
                                </th>

                                <th>
                                    Service
                                </th>

                                <th>
                                    Variation
                                </th>

                                <th>
                                    Quantity
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($usages as $usage)

                                <tr>

                                    {{-- Number --}}
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        @if($usage->used_date)

                                            {{ \Carbon\Carbon::parse($usage->used_date)->format('d-m-Y') }}

                                            <br>

                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($usage->used_date)->format('h:i A') }}
                                            </small>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- Customer --}}
                                    <td>

                                        @if($usage->customer)

                                            <strong>
                                                {{ $usage->customer->name }}
                                            </strong>

                                            @if(!empty($usage->customer->phone))

                                                <br>

                                                <small class="text-muted">
                                                    {{ $usage->customer->phone }}
                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Package --}}
                                    <td>

                                        @if($usage->customerPackage &&
                                            $usage->customerPackage->package)

                                            <strong>
                                                {{ $usage->customerPackage->package->name }}
                                            </strong>

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Service --}}
                                    <td>

                                        @if($usage->packageService &&
                                            $usage->packageService->service)

                                            {{ $usage->packageService->service->name }}

                                        @else

                                            <span class="text-muted">
                                                Service not found
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Variation --}}
                                    <td>

                                        @if($usage->serviceVariation)

                                            {{ $usage->serviceVariation->name }}

                                        @else

                                            <span class="text-muted">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Quantity --}}
                                    <td>

                                        <span class="badge bg-success">
                                            {{ $usage->quantity ?? 1 }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="bi bi-clock-history"
                           style="font-size: 50px; opacity: .4;"></i>
                    </div>

                    <h5>
                        No Usage Records Found
                    </h5>

                    <p class="text-muted mb-0">
                        No package service usage has been recorded yet.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Package Wise Usage --}}
    <div class="card shadow-sm border-0 mt-4">

        <div class="card-header">

            <strong>
                <i class="bi bi-box-seam"></i>
                Customer Package Usage
            </strong>

        </div>


        <div class="card-body">

            @forelse($customerPackages as $customerPackage)

                <div class="card border mb-3">

                    <div class="card-header bg-light">

                        <div class="row align-items-center">

                            <div class="col-md-4">

                                <strong>
                                    {{ $customerPackage->customer->name ?? 'Unknown Customer' }}
                                </strong>

                            </div>

                            <div class="col-md-4">

                                <span class="text-muted">
                                    Package:
                                </span>

                                <strong>
                                    {{ $customerPackage->package->name ?? '-' }}
                                </strong>

                            </div>

                            <div class="col-md-4 text-md-end">

                                @if($customerPackage->usages->count())

                                    <span class="badge bg-success">
                                        {{ $customerPackage->usages->count() }}
                                        Usage Records
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        No Usage Yet
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    @if($customerPackage->usages->count())

                        <div class="table-responsive">

                            <table class="table table-sm table-bordered mb-0">

                                <thead>

                                    <tr>

                                        <th>
                                            Date
                                        </th>

                                        <th>
                                            Service
                                        </th>

                                        <th>
                                            Variation
                                        </th>

                                        <th>
                                            Quantity
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($customerPackage->usages as $usage)

                                        <tr>

                                            <td>

                                                {{ $usage->used_date
                                                    ? \Carbon\Carbon::parse($usage->used_date)->format('d-m-Y')
                                                    : '-' }}

                                            </td>


                                            <td>

                                                {{ $usage->packageService->service->name ?? '-' }}

                                            </td>


                                            <td>

                                                {{ $usage->serviceVariation->name ?? '-' }}

                                            </td>


                                            <td>

                                                {{ $usage->quantity ?? 1 }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="p-3 text-muted">

                            No service has been used from this package.

                        </div>

                    @endif

                </div>

            @empty

                <div class="alert alert-info mb-0">

                    No customer packages found.

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection