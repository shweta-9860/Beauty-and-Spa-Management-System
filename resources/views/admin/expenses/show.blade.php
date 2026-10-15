@extends('admin.layout')

@section('title', 'Expense Details | Salon Management')
@section('page-title', 'Expense Details')

@section('content')

<div class="container-fluid">

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1 fw-bold">
            Expense Details
        </h4>

        <p class="text-muted mb-0">
            View complete expense information.
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('admin.expenses.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

        <a href="{{ route('admin.expenses.edit', $expense) }}"
           class="btn btn-primary">

            <i class="bi bi-pencil me-1"></i>
            Edit Expense

        </a>

    </div>

</div>


<div class="row g-4">

    {{-- =====================================================
         MAIN DETAILS
    ====================================================== --}}

    <div class="col-lg-8">

        {{-- Expense Information --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-receipt me-2"></i>
                    Expense Information
                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Title --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Expense Title
                        </div>

                        <div class="fw-semibold fs-5">
                            {{ $expense->title }}
                        </div>

                    </div>


                    {{-- Amount --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Amount
                        </div>

                        <div class="fw-bold fs-4 text-primary">
                            ₹{{ number_format($expense->amount, 2) }}
                        </div>

                    </div>


                    {{-- Category --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Category
                        </div>

                        @if($expense->category)

                            <span class="badge bg-light text-dark border">

                                {{ $expense->category->name }}

                            </span>

                        @else

                            <span class="text-muted">
                                —
                            </span>

                        @endif

                    </div>


                    {{-- Branch --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Branch
                        </div>

                        <div class="fw-semibold">

                            {{ $expense->branch?->name ?? 'All Branches / Salon Level' }}

                        </div>

                    </div>


                    {{-- Date --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Expense Date
                        </div>

                        <div class="fw-semibold">

                            {{ $expense->expense_date?->format('d M Y') ?? '—' }}

                        </div>

                    </div>


                    {{-- Payment --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Payment Method
                        </div>

                        @php

                            $paymentLabels = [
                                'cash' => 'Cash',
                                'upi' => 'UPI',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'online' => 'Online',
                            ];

                        @endphp

                        <span class="badge bg-light text-dark border">

                            {{ $paymentLabels[$expense->payment_method]
                                ?? ucwords(str_replace('_', ' ', $expense->payment_method)) }}

                        </span>

                    </div>


                    {{-- Paid To --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Paid To
                        </div>

                        <div class="fw-semibold">
                            {{ $expense->paid_to ?? '—' }}
                        </div>

                    </div>


                    {{-- Reference --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Reference No.
                        </div>

                        <div class="fw-semibold">

                            {{ $expense->reference_no ?? '—' }}

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-12">

                        <div class="text-muted small mb-1">
                            Description
                        </div>

                        @if($expense->description)

                            <div class="border rounded p-3 bg-light">

                                {!! nl2br(e($expense->description)) !!}

                            </div>

                        @else

                            <span class="text-muted">
                                No description provided.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Attachment --}}
        @if($expense->attachment)

            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-semibold">

                        <i class="bi bi-paperclip me-2"></i>
                        Attachment

                    </h5>

                </div>

                <div class="card-body">

                    @php

                        $attachmentUrl = asset(
                            'storage/' . $expense->attachment
                        );

                        $extension = strtolower(
                            pathinfo(
                                $expense->attachment,
                                PATHINFO_EXTENSION
                            )
                        );

                    @endphp


                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'webp']))

                        <div class="text-center">

                            <img src="{{ $attachmentUrl }}"
                                 alt="Expense Attachment"
                                 class="img-fluid rounded border"
                                 style="max-height: 500px;">

                        </div>

                        <div class="text-center mt-3">

                            <a href="{{ $attachmentUrl }}"
                               target="_blank"
                               class="btn btn-outline-primary">

                                <i class="bi bi-box-arrow-up-right me-1"></i>
                                Open Attachment

                            </a>

                        </div>

                    @else

                        <div class="d-flex align-items-center
                                    justify-content-between
                                    border rounded p-3">

                            <div class="d-flex align-items-center">

                                <i class="bi bi-file-earmark-pdf
                                          fs-2 text-danger me-3"></i>

                                <div>

                                    <div class="fw-semibold">
                                        Expense Attachment
                                    </div>

                                    <small class="text-muted">
                                        {{ strtoupper($extension) }} file
                                    </small>

                                </div>

                            </div>

                            <a href="{{ $attachmentUrl }}"
                               target="_blank"
                               class="btn btn-outline-primary">

                                <i class="bi bi-eye me-1"></i>
                                View

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        @endif

    </div>


    {{-- =====================================================
         SIDEBAR DETAILS
    ====================================================== --}}

    <div class="col-lg-4">

        {{-- Status --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-info-circle me-2"></i>
                    Status
                </h5>

            </div>

            <div class="card-body">

                @if($expense->status)

                    <span class="badge bg-success fs-6">
                        Active
                    </span>

                @else

                    <span class="badge bg-secondary fs-6">
                        Inactive
                    </span>

                @endif

            </div>

        </div>


        {{-- Created By --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-person me-2"></i>
                    Created By
                </h5>

            </div>

            <div class="card-body">

                @if($expense->creator)

                    <div class="fw-semibold">
                        {{ $expense->creator->name }}
                    </div>

                    @if($expense->creator->email)

                        <small class="text-muted">
                            {{ $expense->creator->email }}
                        </small>

                    @endif

                @else

                    <span class="text-muted">
                        —
                    </span>

                @endif

            </div>

        </div>


        {{-- Record Information --}}
        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-clock-history me-2"></i>
                    Record Information
                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <div class="text-muted small">
                        Created At
                    </div>

                    <div class="fw-semibold">
                        {{ $expense->created_at?->format('d M Y, h:i A') }}
                    </div>

                </div>

                <div>

                    <div class="text-muted small">
                        Last Updated
                    </div>

                    <div class="fw-semibold">
                        {{ $expense->updated_at?->format('d M Y, h:i A') }}
                    </div>

                </div>

            </div>

        </div>


        {{-- Delete --}}
        <div class="card shadow-sm border-0">

            <div class="card-body">

                <form action="{{ route(
                    'admin.expenses.destroy',
                    $expense
                ) }}"
                      method="POST"
                      onsubmit="return confirm(
                          'Are you sure you want to delete this expense?'
                      );">

                    @csrf

                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-outline-danger w-100">

                        <i class="bi bi-trash me-1"></i>
                        Delete Expense

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</div>

@endsection
