@extends('admin.layout')

@section('title', 'Customers | Salon Management')

@section('page-title', 'Customers')

@section('content')

<div class="container-fluid px-0 customer-index-page">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show customer-alert customer-alert-success"
             role="alert">

            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="customer-index-hero">

        <div class="customer-index-hero-content">

            <div class="customer-index-title-row">

                <div class="customer-index-title-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>

                    <h4 class="customer-index-title mb-1">
                        Customers
                    </h4>

                    <p class="customer-index-subtitle mb-0">
                        Manage salon customers
                    </p>

                </div>

            </div>

        </div>


        <a href="{{ route('admin.customers.create') }}"
           class="customer-primary-btn">

            <i class="bi bi-person-plus me-1"></i>
            Add Customer

        </a>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <div class="customer-filter-card">

        <div class="customer-filter-header">

            <div class="customer-filter-title">

                <i class="bi bi-funnel me-2"></i>
                Customer Filters

            </div>

            <span class="customer-filter-hint">
                Search and filter customers
            </span>

        </div>


        <div class="customer-filter-body">

            <form method="GET"
                  action="{{ route('admin.customers.index') }}">

                <div class="row g-3">


                    {{-- =================================================
                        SALON
                    ================================================== --}}
                    <div class="col-md-3">

                        <label class="customer-form-label">
                            Salon
                        </label>

                        @if(auth()->check() && !empty(auth()->user()->branch_id))

                            <input
                                type="text"
                                class="form-control customer-input"
                                value="{{ auth()->user()->salon?->name }}"
                                readonly
                            >

                        @else

                            <select
                                name="salon_id"
                                class="form-select customer-input"
                                id="salonFilter"
                            >

                                <option value="">
                                    All Salons
                                </option>

                                @foreach($salons as $salon)

                                    <option
                                        value="{{ $salon->id }}"
                                        {{ request('salon_id') == $salon->id ? 'selected' : '' }}
                                    >
                                        {{ $salon->name }}
                                    </option>

                                @endforeach

                            </select>

                        @endif

                    </div>


                    {{-- =================================================
                        BRANCH
                    ================================================== --}}
                    <div class="col-md-3">

                        <label class="customer-form-label">
                            Branch
                        </label>

                        @if(auth()->check() && !empty(auth()->user()->branch_id))

                            <input
                                type="text"
                                class="form-control customer-input"
                                value="{{ auth()->user()->branch?->name }}"
                                readonly
                            >

                        @else

                            <select
                                name="branch_id"
                                class="form-select customer-input"
                                id="branchFilter"
                            >

                                <option value="">
                                    All Branches
                                </option>

                                @foreach($branches as $branch)

                                    <option
                                        value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}
                                    >

                                        {{ $branch->name }}

                                        @if(!empty($branch->code))
                                            ({{ $branch->code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        @endif

                    </div>


                    {{-- =================================================
                        SEARCH
                    ================================================== --}}
                    <div class="col-md-3">

                        <label class="customer-form-label">
                            Search
                        </label>

                        <div class="customer-input-wrap">

                            <i class="bi bi-search customer-input-icon"></i>

                            <input
                                type="text"
                                name="search"
                                class="form-control customer-input customer-search-input"
                                value="{{ request('search') }}"
                                placeholder="Name, phone or email"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}
                    <div class="col-md-3">

                        <label class="customer-form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select customer-input"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        FILTER BUTTONS
                    ================================================== --}}
                    <div class="col-12">

                        <div class="customer-filter-actions">

                            <button
                                type="submit"
                                class="customer-primary-btn customer-search-btn"
                            >

                                <i class="bi bi-search me-1"></i>
                                Search

                            </button>


                            <a
                                href="{{ route('admin.customers.index') }}"
                                class="customer-secondary-btn"
                            >

                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Reset

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        CUSTOMER LIST
    ========================================================== --}}
    <div class="customer-list-card">

        {{-- Header --}}
        <div class="customer-list-header">

            <div class="customer-list-heading">

                <div class="customer-list-heading-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>

                    <div class="customer-list-title">
                        Customer List
                    </div>

                    <div class="customer-list-subtitle">
                        All registered salon customers
                    </div>

                </div>

            </div>


            <div class="customer-total-badge">
                {{ $customers->total() }} Customers
            </div>

        </div>


        {{-- Table --}}
        <div class="customer-list-body">

            <div class="table-responsive">

                <table class="table customer-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="65">
                                #
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Phone
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Salon
                            </th>

                            <th>
                                Branch
                            </th>

                            <th width="110">
                                Status
                            </th>

                            <th width="120" class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($customers as $customer)

                            <tr class="customer-table-row">


                                {{-- =================================================
                                    NUMBER
                                ================================================== --}}
                                <td>

                                    <span class="customer-number">
                                        {{ $customers->firstItem() + $loop->index }}
                                    </span>

                                </td>


                                {{-- =================================================
                                    CUSTOMER
                                ================================================== --}}
                                <td>

                                    <div class="customer-profile">

                                        <div class="customer-avatar">

                                            <i class="bi bi-person"></i>

                                        </div>


                                        <div class="customer-profile-info">

                                            <div class="customer-name">
                                                {{ $customer->name }}
                                            </div>

                                            @if($customer->gender)

                                                <div class="customer-gender">
                                                    {{ ucfirst($customer->gender) }}
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================================
                                    PHONE
                                ================================================== --}}
                                <td>

                                    @if($customer->phone)

                                        <span class="customer-contact">
                                            {{ $customer->phone }}
                                        </span>

                                    @else

                                        <span class="customer-empty">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    EMAIL
                                ================================================== --}}
                                <td>

                                    @if($customer->email)

                                        <span class="customer-contact">
                                            {{ $customer->email }}
                                        </span>

                                    @else

                                        <span class="customer-empty">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    SALON
                                ================================================== --}}
                                <td>

                                    @if($customer->salon)

                                        <span class="customer-location-name">
                                            {{ $customer->salon->name }}
                                        </span>

                                    @else

                                        <span class="customer-empty">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    BRANCH
                                ================================================== --}}
                                <td>

                                    @if($customer->branch)

                                        <div class="customer-branch">

                                            <span class="customer-location-name">
                                                {{ $customer->branch->name }}
                                            </span>

                                            @if($customer->branch->code)

                                                <span class="customer-branch-code">
                                                    {{ $customer->branch->code }}
                                                </span>

                                            @endif

                                        </div>

                                    @else

                                        <span class="customer-empty">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}
                                <td>

                                    @if($customer->status)

                                        <span class="customer-status customer-status-active">

                                            <i class="bi bi-check-circle me-1"></i>
                                            Active

                                        </span>

                                    @else

                                        <span class="customer-status customer-status-inactive">

                                            <i class="bi bi-x-circle me-1"></i>
                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}
                                <td class="text-center">

                                    <div class="customer-action-group">


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route(
                                                'admin.customers.edit',
                                                $customer->id
                                            ) }}"
                                            class="customer-action-btn customer-edit-btn"
                                            title="Edit Customer"
                                        >

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            action="{{ route(
                                                'admin.customers.destroy',
                                                $customer->id
                                            ) }}"
                                            method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this customer?'
                                            );"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="customer-action-btn customer-delete-btn"
                                                title="Delete Customer"
                                            >

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- =================================================
                                EMPTY STATE
                            ================================================== --}}
                            <tr>

                                <td
                                    colspan="8"
                                    class="customer-empty-cell"
                                >

                                    <div class="customer-empty-state">

                                        <div class="customer-empty-icon">

                                            <i class="bi bi-people"></i>

                                        </div>

                                        <h5>
                                            No Customers Found
                                        </h5>

                                        <p>
                                            Start by adding your first customer.
                                        </p>

                                        <a
                                            href="{{ route('admin.customers.create') }}"
                                            class="customer-primary-btn"
                                        >

                                            <i class="bi bi-person-plus me-1"></i>
                                            Add Customer

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}
        @if($customers->hasPages())

            <div class="customer-pagination">

                {{ $customers->links() }}

            </div>

        @endif

    </div>

</div>


@push('styles')

<style>

    /* =========================================================
       CUSTOMER INDEX PAGE
    ========================================================== */

    .customer-index-page {
        padding-bottom: 25px;
    }


    /* =========================================================
       ALERT
    ========================================================== */

    .customer-alert {
        border-radius: 14px;
        border: 1px solid #d9efdf;

        padding: 13px 16px;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 10px 24px rgba(45, 37, 41, 0.06);
    }

    .customer-alert-success {
        background: #f1faf4;
        color: #287a4a;
    }


    /* =========================================================
       HERO
    ========================================================== */

    .customer-index-hero {
        position: relative;
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #eadfe4;
        border-radius: 20px;

        padding: 22px 24px;
        margin-bottom: 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 12px 28px rgba(45, 37, 41, 0.07),
            0 25px 55px rgba(45, 37, 41, 0.05);
    }

    .customer-index-hero::before {
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

    .customer-index-title-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .customer-index-title-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: linear-gradient(
            145deg,
            #ffffff,
            #f5eef1
        );

        color: #8b5e6d;

        border: 1px solid #eadfe4;

        box-shadow:
            4px 4px 9px rgba(45, 37, 41, 0.08),
            -3px -3px 7px rgba(255, 255, 255, 0.95);

        font-size: 20px;
    }

    .customer-index-title {
        color: #4e4549;
        font-weight: 600;
    }

    .customer-index-subtitle {
        color: #8b8588;
    }


    /* =========================================================
       PRIMARY BUTTON
    ========================================================== */

    .customer-primary-btn {
        position: relative;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 42px;

        padding: 9px 17px;

        border: 0;
        border-radius: 11px;

        background: linear-gradient(
            145deg,
            #8b5e6d,
            #76505e
        );

        color: #ffffff;

        text-decoration: none;

        font-weight: 500;

        box-shadow:
            0 5px 0 #62414d,
            0 9px 18px rgba(139, 94, 109, 0.22);

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease;
    }

    .customer-primary-btn:hover {
        color: #ffffff;

        background: linear-gradient(
            145deg,
            #956879,
            #7b5361
        );

        transform: translateY(-2px);

        box-shadow:
            0 7px 0 #62414d,
            0 13px 24px rgba(139, 94, 109, 0.25);
    }

    .customer-primary-btn:active {
        transform: translateY(3px);

        box-shadow:
            0 2px 0 #62414d,
            0 5px 10px rgba(139, 94, 109, 0.18);
    }


    /* =========================================================
       FILTER CARD
    ========================================================== */

    .customer-filter-card {
        position: relative;

        background: #ffffff;

        border: 1px solid #eadfe4;
        border-radius: 18px;

        margin-bottom: 20px;

        overflow: hidden;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 12px 28px rgba(45, 37, 41, 0.06),
            0 25px 55px rgba(45, 37, 41, 0.04);
    }

    .customer-filter-header {
        padding: 16px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        border-bottom: 1px solid #eee5e8;

        background: #ffffff;
    }

    .customer-filter-title {
        color: #4e4549;
        font-weight: 600;
    }

    .customer-filter-title i {
        color: #8b5e6d;
    }

    .customer-filter-hint {
        color: #999195;
    }

    .customer-filter-body {
        padding: 20px;
    }


    /* =========================================================
       FORM
    ========================================================== */

    .customer-form-label {
        display: block;

        color: #4e4549;

        font-weight: 600;

        margin-bottom: 7px;
    }

    .customer-input-wrap {
        position: relative;
    }

    .customer-input-icon {
        position: absolute;

        left: 13px;
        top: 50%;

        transform: translateY(-50%);

        color: #8b8588;

        z-index: 2;
    }

    .customer-search-input {
        padding-left: 38px !important;
    }

    .customer-input {
        min-height: 43px;

        border: 1px solid #ded5d9;
        border-radius: 11px;

        background: #ffffff;

        color: #4e4549;

        box-shadow:
            inset 1px 1px 3px rgba(45, 37, 41, 0.025),
            0 2px 5px rgba(45, 37, 41, 0.025);

        transition:
            border-color 0.18s ease,
            box-shadow 0.18s ease,
            transform 0.18s ease;
    }

    .customer-input:hover {
        border-color: #cfc1c7;
    }

    .customer-input:focus {
        border-color: #8b5e6d;

        box-shadow:
            0 0 0 0.2rem rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(45, 37, 41, 0.05);

        outline: none;
    }

    .customer-input[readonly] {
        background: #faf7f8;
        color: #6d6569;
    }


    /* =========================================================
       FILTER ACTIONS
    ========================================================== */

    .customer-filter-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        gap: 10px;

        padding-top: 4px;
    }

    .customer-search-btn {
        min-width: 115px;
    }

    .customer-secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 42px;

        padding: 9px 17px;

        border: 1px solid #ded5d9;
        border-radius: 11px;

        background: #ffffff;

        color: #665d61;

        text-decoration: none;

        box-shadow:
            0 3px 7px rgba(45, 37, 41, 0.05);

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            border-color 0.18s ease,
            background 0.18s ease;
    }

    .customer-secondary-btn:hover {
        color: #4e4549;

        background: #faf7f8;

        border-color: #cfc1c7;

        transform: translateY(-1px);

        box-shadow:
            0 5px 11px rgba(45, 37, 41, 0.07);
    }


    /* =========================================================
       CUSTOMER LIST CARD
    ========================================================== */

    .customer-list-card {
        position: relative;

        background: #ffffff;

        border: 1px solid #eadfe4;
        border-radius: 20px;

        overflow: hidden;

        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 12px 28px rgba(45, 37, 41, 0.07),
            0 25px 55px rgba(45, 37, 41, 0.05);
    }

    .customer-list-header {
        min-height: 72px;

        padding: 15px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        background: #ffffff;

        border-bottom: 1px solid #eee5e8;
    }

    .customer-list-heading {
        display: flex;
        align-items: center;

        gap: 12px;
    }

    .customer-list-heading-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #f7edf1;
        color: #8b5e6d;

        border: 1px solid #eadfe4;

        box-shadow:
            2px 3px 7px rgba(45, 37, 41, 0.07);
    }

    .customer-list-title {
        color: #4e4549;
        font-weight: 600;
    }

    .customer-list-subtitle {
        color: #999195;
        margin-top: 2px;
    }

    .customer-total-badge {
        padding: 7px 12px;

        border-radius: 20px;

        background: #f7edf1;
        color: #8b5e6d;

        border: 1px solid #eadfe4;

        font-weight: 600;

        box-shadow:
            0 2px 5px rgba(45, 37, 41, 0.04);
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .customer-table {
        margin: 0;
    }

    .customer-table thead th {
        background: #faf7f8;

        color: #6d6569;

        font-weight: 600;

        padding: 14px 15px;

        border-bottom: 1px solid #eadfe4;

        white-space: nowrap;
    }

    .customer-table tbody td {
        padding: 15px;

        color: #5b5558;

        border-bottom: 1px solid #f0e9ec;

        vertical-align: middle;
    }

    .customer-table-row {
        transition:
            background 0.18s ease,
            box-shadow 0.18s ease;
    }

    .customer-table-row:hover {
        background: #fdfafb;

        box-shadow:
            inset 4px 0 0 #8b5e6d;
    }

    .customer-table tbody tr:last-child td {
        border-bottom: 0;
    }


    /* =========================================================
       NUMBER
    ========================================================== */

    .customer-number {
        display: inline-flex;

        width: 31px;
        height: 31px;

        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #faf7f8;

        color: #746b6f;

        border: 1px solid #eadfe4;

        font-weight: 600;

        box-shadow:
            2px 3px 6px rgba(45, 37, 41, 0.04);
    }


    /* =========================================================
       CUSTOMER PROFILE
    ========================================================== */

    .customer-profile {
        display: flex;
        align-items: center;

        gap: 11px;
    }

    .customer-avatar {
        width: 43px;
        height: 43px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 13px;

        background: linear-gradient(
            145deg,
            #8b5e6d,
            #76505e
        );

        color: #ffffff;

        border: 1px solid #76505e;

        box-shadow:
            3px 4px 9px rgba(45, 37, 41, 0.12),
            inset 1px 1px 2px rgba(255, 255, 255, 0.15);
    }

    .customer-profile-info {
        min-width: 0;
    }

    .customer-name {
        color: #4e4549;

        font-weight: 600;

        white-space: nowrap;
    }

    .customer-gender {
        color: #999195;

        margin-top: 2px;
    }


    /* =========================================================
       CONTACT
    ========================================================== */

    .customer-contact {
        color: #5f585c;
    }

    .customer-empty {
        color: #aaa3a6;
    }


    /* =========================================================
       LOCATION
    ========================================================== */

    .customer-location-name {
        color: #5b5558;

        font-weight: 500;
    }

    .customer-branch {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .customer-branch-code {
        color: #999195;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .customer-status {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        border-radius: 20px;

        font-weight: 600;

        border: 1px solid transparent;
    }

    .customer-status-active {
        background: #edf9f1;
        color: #287a4a;

        border-color: #d7efdf;
    }

    .customer-status-inactive {
        background: #f5f2f3;
        color: #746b6f;

        border-color: #e5dfe2;
    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .customer-action-group {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 6px;
    }

    .customer-action-btn {
        width: 35px;
        height: 35px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0;

        border-radius: 9px;

        text-decoration: none;

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease,
            color 0.18s ease;
    }

    .customer-action-btn:hover {
        transform: translateY(-2px);
    }

    .customer-edit-btn {
        color: #8b5e6d;

        background: #f7edf1;

        border: 1px solid #eadfe4;

        box-shadow:
            0 3px 6px rgba(45, 37, 41, 0.05);
    }

    .customer-edit-btn:hover {
        background: #8b5e6d;
        color: #ffffff;

        box-shadow:
            0 5px 10px rgba(139, 94, 109, 0.18);
    }

    .customer-delete-btn {
        color: #b54848;

        background: #faeeee;

        border: 1px solid #efdada;

        box-shadow:
            0 3px 6px rgba(45, 37, 41, 0.04);
    }

    .customer-delete-btn:hover {
        background: #b54848;
        color: #ffffff;

        box-shadow:
            0 5px 10px rgba(181, 72, 72, 0.16);
    }


    /* =========================================================
       PAGINATION
    ========================================================== */

    .customer-pagination {
        padding: 18px 20px;

        display: flex;
        justify-content: flex-end;

        border-top: 1px solid #f0e9ec;

        background: #ffffff;
    }

    .customer-pagination .pagination {
        margin-bottom: 0;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .customer-empty-cell {
        border-bottom: 0 !important;
    }

    .customer-empty-state {
        text-align: center;

        padding: 65px 20px;
    }

    .customer-empty-icon {
        width: 76px;
        height: 76px;

        margin: 0 auto 17px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 22px;

        background: linear-gradient(
            145deg,
            #ffffff,
            #f7edf1
        );

        color: #8b5e6d;

        border: 1px solid #eadfe4;

        box-shadow:
            5px 6px 12px rgba(45, 37, 41, 0.08),
            -3px -3px 8px rgba(255, 255, 255, 0.95);

        font-size: 28px;
    }

    .customer-empty-state h5 {
        color: #4e4549;
        font-weight: 600;
    }

    .customer-empty-state p {
        color: #8b8588;
        margin-bottom: 20px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 992px) {

        .customer-filter-hint {
            display: none;
        }

    }


    @media (max-width: 768px) {

        .customer-index-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .customer-index-hero .customer-primary-btn {
            width: 100%;
        }

        .customer-filter-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .customer-search-btn,
        .customer-secondary-btn {
            width: 100%;
        }

        .customer-list-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .customer-pagination {
            justify-content: center;
        }

    }


    @media (max-width: 576px) {

        .customer-index-hero {
            padding: 20px 16px;
        }

        .customer-filter-body {
            padding: 16px;
        }

        .customer-list-header {
            padding: 15px 16px;
        }

        .customer-table thead th,
        .customer-table tbody td {
            padding: 12px;
        }

        .customer-action-btn {
            width: 32px;
            height: 32px;
        }

    }

</style>

@endpush

@endsection