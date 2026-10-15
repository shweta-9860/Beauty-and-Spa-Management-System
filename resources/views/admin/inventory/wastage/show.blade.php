@extends('admin.layout')

@section('title', 'Wastage Details')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                <i class="bi bi-trash3 me-2"></i>
                Wastage Details
            </h3>

            <p class="text-muted mb-0">
                View wastage information and approval status.
            </p>
        </div>

        <a href="{{ route('admin.inventory.wastage.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Wastage

        </a>

    </div>


    {{-- Success Message --}}
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


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please check:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="row">

        {{-- Main Details --}}
        <div class="col-lg-8">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Wastage Information
                    </h5>


                    {{-- Status --}}
                    @if($wastage->status === 'pending')

                        <span class="badge bg-warning text-dark px-3 py-2">
                            Pending
                        </span>

                    @elseif($wastage->status === 'approved')

                        <span class="badge bg-success px-3 py-2">
                            Approved
                        </span>

                    @else

                        <span class="badge bg-danger px-3 py-2">
                            Rejected
                        </span>

                    @endif

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Wastage Number --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Wastage Number
                            </label>

                            <div class="fw-bold">
                                {{ $wastage->wastage_number }}
                            </div>

                        </div>


                        {{-- Date --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Wastage Date
                            </label>

                            <div class="fw-bold">

                                {{ $wastage->wastage_date
                                    ? $wastage->wastage_date->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>


                        {{-- Product --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Product
                            </label>

                            <div class="fw-bold">

                                {{ $wastage->product->name ?? 'N/A' }}

                            </div>

                        </div>


                        {{-- Branch --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Branch
                            </label>

                            <div class="fw-bold">

                                {{ $wastage->branch->name ?? 'N/A' }}

                            </div>

                        </div>


                        {{-- Staff --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Reported By
                            </label>

                            <div class="fw-bold">

                                {{ $wastage->staff->name ?? 'Not Assigned' }}

                            </div>

                        </div>


                        {{-- Reason --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Reason
                            </label>

                            <div class="fw-bold">

                                {{ ucfirst($wastage->reason) }}

                            </div>

                        </div>


                        {{-- Quantity --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Wasted Quantity
                            </label>

                            <div class="fw-bold fs-5">

                                {{ number_format((float) $wastage->quantity, 2) }}

                            </div>

                        </div>


                        {{-- Unit Cost --}}
                        <div class="col-md-6">

                            <label class="text-muted small">
                                Unit Cost
                            </label>

                            <div class="fw-bold fs-5">

                                ₹{{ number_format((float) $wastage->unit_cost, 2) }}

                            </div>

                        </div>


                        {{-- Total Cost --}}
                        <div class="col-md-12">

                            <div class="p-3 bg-light rounded">

                                <div class="d-flex justify-content-between align-items-center">

                                    <span class="fw-semibold">
                                        Total Wastage Cost
                                    </span>

                                    <span class="text-danger fw-bold fs-4">

                                        ₹{{ number_format((float) $wastage->total_cost, 2) }}

                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Notes --}}
                        <div class="col-md-12">

                            <label class="text-muted small">
                                Notes
                            </label>

                            <div class="border rounded p-3 bg-light">

                                {{ $wastage->notes ?: 'No notes added.' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Product Stock --}}
            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>
                        Stock Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <span class="text-muted">
                                Product
                            </span>

                            <div class="fw-bold">
                                {{ $wastage->product->name ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <span class="text-muted">
                                Current Stock
                            </span>

                            <div class="fw-bold fs-4">

                                {{ number_format(
                                    (float) ($wastage->product->stock ?? 0),
                                    2
                                ) }}

                            </div>

                        </div>

                    </div>


                    @if($wastage->status === 'pending')

                        <div class="alert alert-warning mt-4 mb-0">

                            <i class="bi bi-info-circle me-2"></i>

                            Stock has <strong>not been deducted</strong>
                            yet. It will be deducted after approval.

                        </div>

                    @elseif($wastage->status === 'approved')

                        <div class="alert alert-success mt-4 mb-0">

                            <i class="bi bi-check-circle me-2"></i>

                            This wastage has been approved and the
                            stock has been deducted.

                        </div>

                    @else

                        <div class="alert alert-danger mt-4 mb-0">

                            <i class="bi bi-x-circle me-2"></i>

                            This wastage was rejected.
                            Stock was not deducted.

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- Action Panel --}}
        <div class="col-lg-4">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        <i class="bi bi-check2-square me-2"></i>
                        Approval
                    </h5>

                </div>


                <div class="card-body">


                    @if($wastage->status === 'pending')

                        <div class="alert alert-warning">

                            <strong>
                                Pending Approval
                            </strong>

                            <br>

                            <small>
                                Review the wastage details before
                                approving or rejecting it.
                            </small>

                        </div>


                        {{-- Approve --}}
                        <form method="POST"
                              action="{{ route(
                                  'admin.inventory.wastage.approve',
                                  $wastage->id
                              ) }}"
                              class="mb-2"
                              onsubmit="return confirm(
                                  'Approve this wastage? Stock will be deducted.'
                              );">

                            @csrf

                            <button type="submit"
                                    class="btn btn-success w-100">

                                <i class="bi bi-check-circle me-1"></i>
                                Approve Wastage

                            </button>

                        </form>


                        {{-- Reject --}}
                        <form method="POST"
                              action="{{ route(
                                  'admin.inventory.wastage.reject',
                                  $wastage->id
                              ) }}"
                              onsubmit="return confirm(
                                  'Reject this wastage?'
                              );">

                            @csrf

                            <button type="submit"
                                    class="btn btn-danger w-100">

                                <i class="bi bi-x-circle me-1"></i>
                                Reject Wastage

                            </button>

                        </form>


                    @elseif($wastage->status === 'approved')

                        <div class="text-center">

                            <div class="mb-3">

                                <i class="bi bi-check-circle-fill text-success"
                                   style="font-size: 60px;">
                                </i>

                            </div>

                            <h5 class="text-success">
                                Wastage Approved
                            </h5>

                            <p class="text-muted mb-0">

                                Stock has been deducted successfully.

                            </p>

                        </div>


                    @else

                        <div class="text-center">

                            <div class="mb-3">

                                <i class="bi bi-x-circle-fill text-danger"
                                   style="font-size: 60px;">
                                </i>

                            </div>

                            <h5 class="text-danger">
                                Wastage Rejected
                            </h5>

                            <p class="text-muted mb-0">

                                Stock was not deducted.

                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- Record Information --}}
            <div class="card mt-4">

                <div class="card-header">

                    <h6 class="mb-0">
                        Record Information
                    </h6>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="text-muted">
                            Created
                        </span>

                        <span>
                            {{ $wastage->created_at?->format('d M Y, h:i A') }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Last Updated
                        </span>

                        <span>
                            {{ $wastage->updated_at?->format('d M Y, h:i A') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection