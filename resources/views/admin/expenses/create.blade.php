@extends('admin.layout')

@section('title', 'Add Expense | Salon Management')
@section('page-title', 'Add Expense')

@section('content')

<div class="container-fluid">

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1 fw-bold">
            Add Expense
        </h4>

        <p class="text-muted mb-0">
            Record a new salon expense.
        </p>
    </div>

    <a href="{{ route('admin.expenses.index') }}"
       class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>
        Back to Expenses

    </a>

</div>


{{-- Validation Errors --}}
@if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-semibold mb-2">
            Please fix the following errors:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<form action="{{ route('admin.expenses.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="row g-4">

        {{-- =====================================================
             LEFT SIDE
        ====================================================== --}}

        <div class="col-lg-8">

            {{-- Basic Details --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-receipt me-2"></i>
                        Expense Details
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Category --}}
                        <div class="col-md-6">

                            <label for="expense_category_id"
                                   class="form-label fw-semibold">

                                Expense Category
                                <span class="text-danger">*</span>

                            </label>

                            <select name="expense_category_id"
                                    id="expense_category_id"
                                    class="form-select @error('expense_category_id') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Category
                                </option>

                                @foreach($categories as $category)

                                    <option value="{{ $category->id }}"
                                        {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>

                                        {{ $category->name }}

                                    </option>

                                @endforeach

                            </select>

                            @error('expense_category_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Branch --}}
                        <div class="col-md-6">

                            <label for="branch_id"
                                   class="form-label fw-semibold">

                                Branch

                            </label>

                            <select name="branch_id"
                                    id="branch_id"
                                    class="form-select @error('branch_id') is-invalid @enderror">

                                <option value="">
                                    All Branches / Salon Level
                                </option>

                                @foreach($branches as $branch)

                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>

                                        {{ $branch->name }}

                                        @if($branch->code)
                                            ({{ $branch->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('branch_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Select a branch if this expense belongs to a specific branch.
                            </small>

                        </div>


                        {{-- Expense Date --}}
                        <div class="col-md-6">

                            <label for="expense_date"
                                   class="form-label fw-semibold">

                                Expense Date
                                <span class="text-danger">*</span>

                            </label>

                            <input type="date"
                                   name="expense_date"
                                   id="expense_date"
                                   class="form-control @error('expense_date') is-invalid @enderror"
                                   value="{{ old('expense_date', now()->format('Y-m-d')) }}"
                                   required>

                            @error('expense_date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Title --}}
                        <div class="col-md-6">

                            <label for="title"
                                   class="form-label fw-semibold">

                                Expense Title
                                <span class="text-danger">*</span>

                            </label>

                            <input type="text"
                                   name="title"
                                   id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Monthly Electricity Bill"
                                   maxlength="255"
                                   required>

                            @error('title')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Amount --}}
                        <div class="col-md-6">

                            <label for="amount"
                                   class="form-label fw-semibold">

                                Amount
                                <span class="text-danger">*</span>

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    ₹
                                </span>

                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}"
                                       placeholder="0.00"
                                       min="0"
                                       step="0.01"
                                       required>

                            </div>

                            @error('amount')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Payment Method --}}
                        <div class="col-md-6">

                            <label for="payment_method"
                                   class="form-label fw-semibold">

                                Payment Method
                                <span class="text-danger">*</span>

                            </label>

                            <select name="payment_method"
                                    id="payment_method"
                                    class="form-select @error('payment_method') is-invalid @enderror"
                                    required>

                                <option value="">
                                    Select Payment Method
                                </option>

                                <option value="cash"
                                    {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                                    Cash
                                </option>

                                <option value="upi"
                                    {{ old('payment_method') === 'upi' ? 'selected' : '' }}>
                                    UPI
                                </option>

                                <option value="card"
                                    {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                    Card
                                </option>

                                <option value="bank_transfer"
                                    {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                    Bank Transfer
                                </option>

                                <option value="online"
                                    {{ old('payment_method') === 'online' ? 'selected' : '' }}>
                                    Online
                                </option>

                            </select>

                            @error('payment_method')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Paid To --}}
                        <div class="col-md-6">

                            <label for="paid_to"
                                   class="form-label fw-semibold">

                                Paid To

                            </label>

                            <input type="text"
                                   name="paid_to"
                                   id="paid_to"
                                   class="form-control @error('paid_to') is-invalid @enderror"
                                   value="{{ old('paid_to') }}"
                                   placeholder="e.g. MSEB, Landlord, Supplier"
                                   maxlength="255">

                            @error('paid_to')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Reference --}}
                        <div class="col-md-6">

                            <label for="reference_no"
                                   class="form-label fw-semibold">

                                Reference No.

                            </label>

                            <input type="text"
                                   name="reference_no"
                                   id="reference_no"
                                   class="form-control @error('reference_no') is-invalid @enderror"
                                   value="{{ old('reference_no') }}"
                                   placeholder="Invoice / Receipt / Transaction No."
                                   maxlength="255">

                            @error('reference_no')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <label for="description"
                                   class="form-label fw-semibold">

                                Description

                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter additional details about this expense...">{{ old('description') }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- Attachment --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-paperclip me-2"></i>
                        Attachment
                    </h5>

                </div>

                <div class="card-body">

                    <label for="attachment"
                           class="form-label fw-semibold">

                        Upload Receipt / Invoice

                    </label>

                    <input type="file"
                           name="attachment"
                           id="attachment"
                           class="form-control @error('attachment') is-invalid @enderror"
                           accept=".jpg,.jpeg,.png,.pdf,.webp">

                    @error('attachment')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    <small class="text-muted">
                        Allowed: JPG, JPEG, PNG, WEBP and PDF. Maximum size: 5 MB.
                    </small>

                </div>

            </div>

        </div>


        {{-- =====================================================
             RIGHT SIDE
        ====================================================== --}}

        <div class="col-lg-4">

            {{-- Status --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-toggle-on me-2"></i>
                        Status
                    </h5>

                </div>

                <div class="card-body">

                    <div class="form-check form-switch">

                        <input type="hidden"
                               name="status"
                               value="0">

                        <input type="checkbox"
                               name="status"
                               id="status"
                               value="1"
                               class="form-check-input"
                               {{ old('status', true) ? 'checked' : '' }}>

                        <label for="status"
                               class="form-check-label fw-semibold">

                            Active Expense

                        </label>

                    </div>

                    <small class="text-muted d-block mt-2">
                        Inactive expenses can still remain in the system but
                        will be marked as inactive.
                    </small>

                </div>

            </div>


            {{-- Information --}}
            <div class="card shadow-sm border-0 mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>
                        Expense Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex mb-3">

                        <i class="bi bi-check-circle text-success me-2"></i>

                        <span class="text-muted small">
                            Expense will be linked to your salon automatically.
                        </span>

                    </div>

                    <div class="d-flex mb-3">

                        <i class="bi bi-person text-primary me-2"></i>

                        <span class="text-muted small">
                            Current logged-in user will be recorded as creator.
                        </span>

                    </div>

                    <div class="d-flex">

                        <i class="bi bi-shield-check text-warning me-2"></i>

                        <span class="text-muted small">
                            Branch and category selection is restricted to your salon.
                        </span>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="card shadow-sm border-0">

                <div class="card-body">

                    <button type="submit"
                            class="btn btn-primary w-100 mb-2">

                        <i class="bi bi-check-lg me-1"></i>
                        Save Expense

                    </button>

                    <a href="{{ route('admin.expenses.index') }}"
                       class="btn btn-light border w-100">

                        Cancel

                    </a>

                </div>

            </div>

        </div>

    </div>

</form>


</div>

@endsection
