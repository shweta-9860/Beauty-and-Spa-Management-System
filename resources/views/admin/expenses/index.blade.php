@extends('admin.layout')

@section('title', 'Expenses | Salon Management')
@section('page-title', 'Expenses')

@section('content')

<div class="container-fluid">


{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1 fw-bold">
            Expenses
        </h4>

        <p class="text-muted mb-0">
            Manage and track salon expenses.
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('admin.expenses.categories.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-folder2-open me-1"></i>
            Categories

        </a>

        <a href="{{ route('admin.expenses.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>
            Add Expense

        </a>

    </div>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show"
         role="alert">

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

    <div class="alert alert-danger alert-dismissible fade show"
         role="alert">

        <i class="bi bi-exclamation-triangle me-2"></i>

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

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- Summary Card --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="rounded-circle bg-primary-subtle
                                text-primary p-3 me-3">

                        <i class="bi bi-cash-stack fs-4"></i>

                    </div>

                    <div>

                        <div class="text-muted small">
                            Filtered Expenses
                        </div>

                        <div class="fs-4 fw-bold">

                            ₹{{ number_format($totalExpenses, 2) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="rounded-circle bg-success-subtle
                                text-success p-3 me-3">

                        <i class="bi bi-receipt fs-4"></i>

                    </div>

                    <div>

                        <div class="text-muted small">
                            Total Records
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $expenses->total() }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card shadow-sm border-0 h-100">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="rounded-circle bg-warning-subtle
                                text-warning p-3 me-3">

                        <i class="bi bi-tags fs-4"></i>

                    </div>

                    <div>

                        <div class="text-muted small">
                            Categories
                        </div>

                        <div class="fs-4 fw-bold">
                            {{ $categories->count() }}
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Filters --}}
<div class="card shadow-sm border-0 mb-4">

    <div class="card-header bg-white py-3">

        <div class="d-flex align-items-center">

            <i class="bi bi-funnel me-2"></i>

            <h5 class="mb-0 fw-semibold">
                Filters
            </h5>

        </div>

    </div>

    <div class="card-body">

        <form method="GET"
              action="{{ route('admin.expenses.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-lg-3 col-md-6">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <input type="text"
                           name="search"
                           class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Title, paid to, reference...">

                </div>


                {{-- Category --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Category
                    </label>

                    <select name="category_id"
                            class="form-select">

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>

                                {{ $category->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Branch --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Branch
                    </label>

                    <select name="branch_id"
                            class="form-select">

                        <option value="">
                            All Branches
                        </option>

                        @foreach($branches as $branch)

                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>

                                {{ $branch->name }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Payment Method --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label fw-semibold">
                        Payment Method
                    </label>

                    <select name="payment_method"
                            class="form-select">

                        <option value="">
                            All Methods
                        </option>

                        <option value="cash"
                            {{ request('payment_method') === 'cash' ? 'selected' : '' }}>
                            Cash
                        </option>

                        <option value="upi"
                            {{ request('payment_method') === 'upi' ? 'selected' : '' }}>
                            UPI
                        </option>

                        <option value="card"
                            {{ request('payment_method') === 'card' ? 'selected' : '' }}>
                            Card
                        </option>

                        <option value="bank_transfer"
                            {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                            Bank Transfer
                        </option>

                        <option value="online"
                            {{ request('payment_method') === 'online' ? 'selected' : '' }}>
                            Online
                        </option>

                    </select>

                </div>


                {{-- Date From --}}
                <div class="col-lg-1 col-md-6">

                    <label class="form-label fw-semibold">
                        From
                    </label>

                    <input type="date"
                           name="date_from"
                           class="form-control"
                           value="{{ request('date_from') }}">

                </div>


                {{-- Date To --}}
                <div class="col-lg-1 col-md-6">

                    <label class="form-label fw-semibold">
                        To
                    </label>

                    <input type="date"
                           name="date_to"
                           class="form-control"
                           value="{{ request('date_to') }}">

                </div>


                {{-- Buttons --}}
                <div class="col-lg-1 col-md-6 d-flex
                            align-items-end gap-1">

                    <button type="submit"
                            class="btn btn-primary"
                            title="Apply Filters">

                        <i class="bi bi-search"></i>

                    </button>

                    <a href="{{ route('admin.expenses.index') }}"
                       class="btn btn-light border"
                       title="Clear Filters">

                        <i class="bi bi-x-lg"></i>

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- Expenses Table --}}
<div class="card shadow-sm border-0">

    <div class="card-header bg-white py-3">

        <div class="d-flex justify-content-between
                    align-items-center">

            <h5 class="mb-0 fw-semibold">
                Expense Records
            </h5>

            <span class="badge bg-secondary">
                {{ $expenses->total() }} Records
            </span>

        </div>

    </div>


    <div class="card-body p-0">

        @if($expenses->count())

            <div class="table-responsive">

                <table class="table table-hover
                              align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                #
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Expense
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Branch
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Paid To
                            </th>

                            <th class="text-end">
                                Amount
                            </th>

                            <th width="110"
                                class="text-center">

                                Status

                            </th>

                            <th width="150"
                                class="text-center">

                                Actions

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($expenses as $expense)

                            <tr>

                                <td>
                                    {{ $expenses->firstItem() + $loop->index }}
                                </td>


                                {{-- Date --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $expense->expense_date?->format('d M Y') }}
                                    </div>

                                </td>


                                {{-- Expense --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $expense->title }}
                                    </div>

                                    @if($expense->reference_no)

                                        <small class="text-muted">

                                            Ref:
                                            {{ $expense->reference_no }}

                                        </small>

                                    @endif

                                </td>


                                {{-- Category --}}
                                <td>

                                    @if($expense->category)

                                        <span class="badge bg-light
                                                     text-dark border">

                                            {{ $expense->category->name }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Branch --}}
                                <td>

                                    {{ $expense->branch?->name ?? 'All Branches' }}

                                </td>


                                {{-- Payment --}}
                                <td>

                                    @php

                                        $paymentLabels = [
                                            'cash' => 'Cash',
                                            'upi' => 'UPI',
                                            'card' => 'Card',
                                            'bank_transfer' => 'Bank Transfer',
                                            'online' => 'Online',
                                        ];

                                    @endphp

                                    <span class="text-capitalize">

                                        {{ $paymentLabels[$expense->payment_method]
                                            ?? str_replace('_', ' ', $expense->payment_method) }}

                                    </span>

                                </td>


                                {{-- Paid To --}}
                                <td>

                                    {{ $expense->paid_to ?? '—' }}

                                </td>


                                {{-- Amount --}}
                                <td class="text-end">

                                    <span class="fw-bold">

                                        ₹{{ number_format($expense->amount, 2) }}

                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="text-center">

                                    @if($expense->status)

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-center">

                                    <div class="d-inline-flex gap-1">

                                        <a href="{{ route(
                                            'admin.expenses.show',
                                            $expense
                                        ) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="View">

                                            <i class="bi bi-eye"></i>

                                        </a>


                                        <a href="{{ route(
                                            'admin.expenses.edit',
                                            $expense
                                        ) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <form action="{{ route(
                                            'admin.expenses.destroy',
                                            $expense
                                        ) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm(
                                                  'Are you sure you want to delete this expense?'
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


            {{-- Pagination --}}
            @if($expenses->hasPages())

                <div class="card-footer bg-white">

                    {{ $expenses->links() }}

                </div>

            @endif

        @else

            <div class="text-center py-5">

                <div class="mb-3">

                    <i class="bi bi-wallet2 fs-1 text-muted"></i>

                </div>

                <h5 class="fw-semibold">
                    No Expenses Found
                </h5>

                <p class="text-muted mb-3">
                    No expense records match your current filters.
                </p>

                <a href="{{ route('admin.expenses.create') }}"
                   class="btn btn-primary">

                    <i class="bi bi-plus-lg me-1"></i>
                    Add Expense

                </a>

            </div>

        @endif

    </div>

</div>


</div>

@endsection
