@extends('admin.layout')

@section('title', 'Services | Salon Management')

@section('page-title', 'Services')

@section('content')

<div class="services-page">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="services-alert services-alert-success" role="alert">
            <div class="services-alert-content">
                <i class="bi bi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>

            <button
                type="button"
                class="services-alert-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
                &times;
            </button>
        </div>
    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))
        <div class="services-alert services-alert-danger" role="alert">
            <div class="services-alert-content">
                <i class="bi bi-exclamation-triangle"></i>
                <span>{{ session('error') }}</span>
            </div>

            <button
                type="button"
                class="services-alert-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
                &times;
            </button>
        </div>
    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())
        <div class="services-alert services-alert-danger" role="alert">

            <div class="services-alert-content align-items-start">

                <i class="bi bi-exclamation-circle mt-1"></i>

                <div>
                    <strong>Please fix the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <button
                type="button"
                class="services-alert-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
                &times;
            </button>

        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="services-hero">

        <div class="services-hero-left">

            <div class="services-title-icon">
                <i class="bi bi-scissors"></i>
            </div>

            <div>
                <h2>Services</h2>

                <p>
                    Manage salon services and their variations
                </p>
            </div>

        </div>


        <a
            href="{{ route('admin.services.create') }}"
            class="services-primary-btn"
        >
            <i class="bi bi-plus-lg"></i>
            <span>Add New Service</span>
        </a>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <div class="services-filter-card">

        <div class="services-filter-header">

            <div>
                <h5>Find Services</h5>
                <span>Search and filter your salon services</span>
            </div>

        </div>


        <form
            action="{{ route('admin.services.index') }}"
            method="GET"
        >

            <div class="row g-3 align-items-end">

                {{-- Search --}}
                <div class="col-lg-4 col-md-6">

                    <label class="services-label">
                        Search Service
                    </label>

                    <div class="services-input-wrap">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control services-input services-search-input"
                            placeholder="Search service name or description..."
                        >

                    </div>

                </div>


                {{-- Category --}}
                <div class="col-lg-3 col-md-6">

                    <label class="services-label">
                        Category
                    </label>

                    <select
                        name="category"
                        class="form-select services-input"
                    >

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                            <option
                                value="{{ $category->name }}"
                                {{ request('category') == $category->name ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div class="col-lg-3 col-md-6">

                    <label class="services-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select services-input"
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


                {{-- Buttons --}}
                <div class="col-lg-2 col-md-6">

                    <div class="services-filter-buttons">

                        <button
                            type="submit"
                            class="services-filter-btn"
                        >
                            <i class="bi bi-funnel"></i>
                            <span>Filter</span>
                        </button>

                        <a
                            href="{{ route('admin.services.index') }}"
                            class="services-reset-btn"
                            title="Reset Filters"
                        >
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================================================
        SERVICES LIST CARD
    ========================================================== --}}
    <div class="services-list-card">

        {{-- Table Header --}}
        <div class="services-list-header">

            <div class="services-list-heading">

                <div class="services-list-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div>
                    <h5>All Services</h5>

                    <span>
                        {{ $services->total() }} services found
                    </span>
                </div>

            </div>

            <div class="services-count-box">
                {{ $services->total() }}
            </div>

        </div>


        {{-- Table --}}
        <div class="table-responsive">

            <table class="table services-table">

                <thead>

                    <tr>

                        <th width="60">
                            #
                        </th>

                        <th>
                            Service
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Price
                        </th>

                        <th>
                            Duration
                        </th>

                        <th>
                            Variations
                        </th>

                        <th>
                            Status
                        </th>

                        <th width="150">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($services as $service)

                        <tr>

                            {{-- Number --}}
                            <td class="service-number">

                                {{ $services->firstItem() + $loop->index }}

                            </td>


                            {{-- Service --}}
                            <td>

                                <div class="service-info">

                                    <div class="service-icon">
                                        <i class="bi bi-scissors"></i>
                                    </div>

                                    <div class="service-details">

                                        <div class="service-name">
                                            {{ $service->name }}
                                        </div>

                                        @if($service->description)

                                            <div class="service-description">

                                                {{ \Illuminate\Support\Str::limit($service->description, 55) }}

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Category --}}
                            <td>

                                <span class="category-badge">

                                    <i class="bi bi-tag"></i>

                                    {{ $service->category }}

                                </span>

                            </td>


                            {{-- Price --}}
                            <td>

                                <span class="price-text">

                                    ₹{{ number_format((float) $service->price, 2) }}

                                </span>

                            </td>


                            {{-- Duration --}}
                            <td>

                                <span class="duration-badge">

                                    <i class="bi bi-clock"></i>

                                    @if(is_numeric($service->duration))
                                        {{ $service->duration }} min
                                    @else
                                        {{ $service->duration }}
                                    @endif

                                </span>

                            </td>


                            {{-- Variations --}}
                            <td>

                                <a
                                    href="{{ route('admin.service-variations.index', ['service_id' => $service->id]) }}"
                                    class="variation-count"
                                    title="View Service Variations"
                                >

                                    <i class="bi bi-diagram-3"></i>

                                    {{ $service->variations_count }}

                                </a>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($service->status)

                                    <span class="status-badge status-active">

                                        <span class="status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="status-badge status-inactive">

                                        <span class="status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="action-buttons">

                                    {{-- Manage Variations --}}
                                    <a
                                        href="{{ route('admin.service-variations.index', ['service_id' => $service->id]) }}"
                                        class="btn-action btn-variations"
                                        title="Manage Variations"
                                    >
                                        <i class="bi bi-diagram-3"></i>
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.services.edit', $service->id) }}"
                                        class="btn-action btn-edit"
                                        title="Edit Service"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.services.destroy', $service->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this service?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-action btn-delete"
                                            title="Delete Service"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty-state"
                            >

                                <div class="empty-icon">

                                    <i class="bi bi-scissors"></i>

                                </div>

                                <h5>
                                    No Services Found
                                </h5>

                                <p>
                                    No services match your current filters.
                                </p>

                                <a
                                    href="{{ route('admin.services.create') }}"
                                    class="services-primary-btn services-empty-btn"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Add New Service</span>
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if($services->hasPages())

            <div class="services-pagination-wrapper">

                <div class="pagination-info">

                    Showing

                    <strong>
                        {{ $services->firstItem() }}
                    </strong>

                    to

                    <strong>
                        {{ $services->lastItem() }}
                    </strong>

                    of

                    <strong>
                        {{ $services->total() }}
                    </strong>

                    services

                </div>

                <div class="services-pagination">

                    {{ $services->withQueryString()->links() }}

                </div>

            </div>

        @endif

    </div>

</div>


<style>

/* =========================================================
   SERVICES PAGE
========================================================= */

.services-page {
    padding: 10px 0 30px;
}


/* =========================================================
   ALERTS
========================================================= */

.services-alert {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 13px 16px;
    margin-bottom: 18px;
    border-radius: 14px;
    border: 1px solid transparent;
    box-shadow:
        0 3px 8px rgba(45, 37, 41, 0.03),
        0 10px 22px rgba(45, 37, 41, 0.04);
}

.services-alert-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.services-alert-content i {
    font-size: 15px;
}

.services-alert-success {
    background: #f2faf5;
    color: #277448;
    border-color: #dcefe3;
}

.services-alert-danger {
    background: #fff5f5;
    color: #a84d4d;
    border-color: #f1dddd;
}

.services-alert ul {
    padding-left: 18px;
}

.services-alert li {
    margin-bottom: 2px;
}

.services-alert-close {
    width: 28px;
    height: 28px;
    border: none;
    background: transparent;
    color: currentColor;
    font-size: 20px;
    line-height: 1;
    opacity: .65;
    cursor: pointer;
}

.services-alert-close:hover {
    opacity: 1;
}


/* =========================================================
   HERO HEADER
========================================================= */

.services-hero {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 24px;
    margin-bottom: 22px;

    background: #ffffff;

    border: 1px solid #eee5e8;
    border-radius: 18px;

    box-shadow:
        0 4px 8px rgba(45, 37, 41, 0.03),
        0 12px 28px rgba(45, 37, 41, 0.07),
        0 25px 55px rgba(45, 37, 41, 0.05);

    overflow: hidden;
}

.services-hero::before {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    height: 4px;
    background: linear-gradient(
        90deg,
        #8b5e6d,
        #b78b99,
        #8b5e6d
    );
}

.services-hero-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.services-title-icon {
    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background: linear-gradient(
        145deg,
        #ffffff,
        #f4ecef
    );

    color: #8b5e6d;

    border: 1px solid #eadde2;

    box-shadow:
        4px 4px 9px rgba(45, 37, 41, 0.08),
        -3px -3px 8px rgba(255, 255, 255, 0.95);
}

.services-title-icon i {
    font-size: 20px;
}

.services-hero h2 {
    margin: 0;
    color: #2d2529;
    font-size: 25px;
    font-weight: 700;
}

.services-hero p {
    margin: 5px 0 0;
    color: #8b7d82;
    font-size: 14px;
}


/* =========================================================
   PRIMARY BUTTON
========================================================= */

.services-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    min-height: 43px;
    padding: 10px 17px;

    border: none;
    border-radius: 11px;

    background: linear-gradient(
        145deg,
        #8b5e6d,
        #76505e
    );

    color: #ffffff;

    text-decoration: none;

    font-size: 14px;
    font-weight: 600;

    box-shadow:
        0 5px 0 #62414d,
        0 9px 18px rgba(139, 94, 109, 0.18);

    transition: all .2s ease;
}

.services-primary-btn:hover {
    color: #ffffff;
    transform: translateY(-2px);

    box-shadow:
        0 7px 0 #62414d,
        0 13px 24px rgba(139, 94, 109, 0.22);
}

.services-primary-btn:active {
    transform: translateY(2px);

    box-shadow:
        0 2px 0 #62414d,
        0 5px 12px rgba(139, 94, 109, 0.15);
}

.services-primary-btn i {
    font-size: 14px;
}


/* =========================================================
   FILTER CARD
========================================================= */

.services-filter-card {
    position: relative;

    background: #ffffff;

    border: 1px solid #eee5e8;
    border-radius: 18px;

    padding: 20px;

    margin-bottom: 22px;

    box-shadow:
        0 4px 8px rgba(45, 37, 41, 0.03),
        0 12px 26px rgba(45, 37, 41, 0.06),
        0 22px 45px rgba(45, 37, 41, 0.04);
}

.services-filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding-bottom: 16px;
    margin-bottom: 17px;

    border-bottom: 1px solid #f0e9ec;
}

.services-filter-header h5 {
    margin: 0;

    color: #332a2e;
    font-size: 15px;
    font-weight: 700;
}

.services-filter-header span {
    display: block;

    margin-top: 4px;

    color: #9a8c91;
    font-size: 12px;
}


/* =========================================================
   FORM INPUTS
========================================================= */

.services-label {
    display: block;

    margin-bottom: 7px;

    color: #5e5055;
    font-size: 13px;
    font-weight: 600;
}

.services-input-wrap {
    position: relative;
}

.services-input-wrap > i {
    position: absolute;

    left: 14px;
    top: 50%;

    transform: translateY(-50%);

    color: #9b8b91;

    z-index: 2;

    font-size: 13px;
}

.services-input {
    height: 43px;

    border-radius: 10px;

    border: 1px solid #ddd2d7;

    background: #ffffff;

    color: #40373b;

    font-size: 13px;

    box-shadow:
        inset 0 1px 2px rgba(45, 37, 41, 0.025),
        0 2px 5px rgba(45, 37, 41, 0.025);

    transition: all .2s ease;
}

.services-input:hover {
    border-color: #cfc0c6;
}

.services-input:focus {
    border-color: #8b5e6d;

    box-shadow:
        0 0 0 3px rgba(139, 94, 109, 0.09),
        inset 0 1px 2px rgba(45, 37, 41, 0.02);

    outline: none;
}

.services-search-input {
    padding-left: 39px;
}


/* =========================================================
   FILTER BUTTONS
========================================================= */

.services-filter-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
}

.services-filter-btn {
    flex: 1;

    height: 43px;

    border: none;
    border-radius: 10px;

    background: linear-gradient(
        145deg,
        #8b5e6d,
        #76505e
    );

    color: #ffffff;

    font-size: 13px;
    font-weight: 600;

    box-shadow:
        0 4px 0 #62414d,
        0 7px 14px rgba(139, 94, 109, 0.15);

    transition: all .2s ease;
}

.services-filter-btn:hover {
    transform: translateY(-1px);

    box-shadow:
        0 5px 0 #62414d,
        0 9px 17px rgba(139, 94, 109, 0.18);
}

.services-filter-btn:active {
    transform: translateY(2px);

    box-shadow:
        0 2px 0 #62414d,
        0 4px 8px rgba(139, 94, 109, 0.12);
}

.services-filter-btn i {
    margin-right: 4px;
}

.services-reset-btn {
    width: 43px;
    height: 43px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #f7f1f3;
    border: 1px solid #eadde2;

    color: #8b5e6d;

    text-decoration: none;

    box-shadow:
        0 3px 7px rgba(45, 37, 41, 0.04);

    transition: all .2s ease;
}

.services-reset-btn:hover {
    background: #8b5e6d;
    border-color: #8b5e6d;
    color: #ffffff;

    transform: translateY(-1px);
}


/* =========================================================
   LIST CARD
========================================================= */

.services-list-card {
    background: #ffffff;

    border: 1px solid #eee5e8;
    border-radius: 18px;

    overflow: hidden;

    box-shadow:
        0 4px 8px rgba(45, 37, 41, 0.03),
        0 12px 28px rgba(45, 37, 41, 0.07),
        0 25px 55px rgba(45, 37, 41, 0.05);
}


/* =========================================================
   LIST HEADER
========================================================= */

.services-list-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 18px 20px;

    border-bottom: 1px solid #eee5e8;

    background: #ffffff;
}

.services-list-heading {
    display: flex;
    align-items: center;
    gap: 11px;
}

.services-list-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #f7edf1;
    color: #8b5e6d;
}

.services-list-icon i {
    font-size: 15px;
}

.services-list-header h5 {
    margin: 0;

    color: #2d2529;
    font-size: 16px;
    font-weight: 700;
}

.services-list-header span {
    display: block;

    margin-top: 3px;

    color: #9a8c91;
    font-size: 12px;
}

.services-count-box {
    min-width: 42px;
    height: 32px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 10px;

    border-radius: 9px;

    background: #f7edf1;
    color: #8b5e6d;

    font-size: 12px;
    font-weight: 700;
}


/* =========================================================
   TABLE
========================================================= */

.services-table {
    margin: 0;

    min-width: 1050px;
}

.services-table thead th {
    padding: 13px 14px;

    background: #faf7f8;

    color: #76666c;

    border-bottom: 1px solid #eee5e8;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .4px;

    white-space: nowrap;
}

.services-table tbody td {
    padding: 14px;

    border-bottom: 1px solid #f1ebed;

    vertical-align: middle;

    color: #40373b;

    font-size: 13px;
}

.services-table tbody tr {
    transition: all .2s ease;
}

.services-table tbody tr:hover {
    background: #fdfbfc;

    box-shadow:
        inset 3px 0 0 #8b5e6d;
}

.services-table tbody tr:last-child td {
    border-bottom: none;
}


/* =========================================================
   SERVICE NUMBER
========================================================= */

.service-number {
    color: #9a8c91 !important;

    font-size: 12px !important;

    font-weight: 600;
}


/* =========================================================
   SERVICE INFO
========================================================= */

.service-info {
    display: flex;
    align-items: center;

    gap: 11px;
}

.service-icon {
    width: 40px;
    height: 40px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: linear-gradient(
        145deg,
        #ffffff,
        #f4ecef
    );

    color: #8b5e6d;

    border: 1px solid #eadde2;

    box-shadow:
        3px 3px 7px rgba(45, 37, 41, 0.07),
        -2px -2px 6px rgba(255, 255, 255, 0.95);
}

.service-icon i {
    font-size: 15px;
}

.service-name {
    color: #2d2529;

    font-size: 13px;

    font-weight: 700;
}

.service-description {
    max-width: 260px;

    margin-top: 3px;

    color: #9a8c91;

    font-size: 11px;

    line-height: 1.4;
}


/* =========================================================
   CATEGORY
========================================================= */

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 6px 10px;

    border-radius: 20px;

    background: #f6f1f3;
    color: #745965;

    font-size: 11px;
    font-weight: 600;

    white-space: nowrap;
}

.category-badge i {
    font-size: 10px;
}


/* =========================================================
   PRICE
========================================================= */

.price-text {
    color: #2d2529;

    font-size: 13px;

    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   DURATION
========================================================= */

.duration-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 6px 9px;

    border-radius: 8px;

    background: #f7f4f5;
    color: #75676c;

    font-size: 11px;
    font-weight: 600;

    white-space: nowrap;
}

.duration-badge i {
    font-size: 10px;
}


/* =========================================================
   VARIATION COUNT
========================================================= */

.variation-count {
    display: inline-flex;
    align-items: center;
    gap: 5px;

    padding: 6px 10px;

    border-radius: 20px;

    background: #f7edf1;
    color: #8b5e6d;

    font-size: 12px;
    font-weight: 700;

    text-decoration: none;

    transition: all .2s ease;
}

.variation-count:hover {
    background: #8b5e6d;
    color: #ffffff;

    transform: translateY(-1px);
}

.variation-count i {
    font-size: 11px;
}


/* =========================================================
   STATUS
========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 6px 10px;

    border-radius: 20px;

    font-size: 11px;
    font-weight: 700;

    white-space: nowrap;
}

.status-active {
    background: #edf8f1;
    color: #27824a;
}

.status-inactive {
    background: #f5f0f1;
    color: #8c7c82;
}

.status-dot {
    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}


/* =========================================================
   ACTIONS
========================================================= */

.action-buttons {
    display: flex;
    align-items: center;
    gap: 7px;
}

.btn-action {
    width: 35px;
    height: 35px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border: 1px solid transparent;
    border-radius: 9px;

    text-decoration: none;

    cursor: pointer;

    transition: all .2s ease;

    box-shadow:
        0 2px 5px rgba(45, 37, 41, 0.04);
}

.btn-action i {
    font-size: 13px;
}


/* Variations */
.btn-variations {
    background: #f7edf1;
    color: #8b5e6d;
    border-color: #eadde2;
}

.btn-variations:hover {
    background: #8b5e6d;
    border-color: #8b5e6d;
    color: #ffffff;

    transform: translateY(-2px);

    box-shadow:
        0 5px 10px rgba(139, 94, 109, 0.18);
}


/* Edit */
.btn-edit {
    background: #eef4fb;
    color: #4777a9;
    border-color: #dce8f4;
}

.btn-edit:hover {
    background: #4777a9;
    border-color: #4777a9;
    color: #ffffff;

    transform: translateY(-2px);

    box-shadow:
        0 5px 10px rgba(71, 119, 169, 0.18);
}


/* Delete */
.btn-delete {
    background: #fdf0f0;
    color: #c75b5b;
    border-color: #f4dede;
}

.btn-delete:hover {
    background: #c75b5b;
    border-color: #c75b5b;
    color: #ffffff;

    transform: translateY(-2px);

    box-shadow:
        0 5px 10px rgba(199, 91, 91, 0.18);
}


/* =========================================================
   EMPTY STATE
========================================================= */

.empty-state {
    text-align: center;

    padding: 70px 20px !important;
}

.empty-icon {
    width: 68px;
    height: 68px;

    margin: 0 auto 16px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 18px;

    background: linear-gradient(
        145deg,
        #ffffff,
        #f4ecef
    );

    color: #8b5e6d;

    border: 1px solid #eadde2;

    box-shadow:
        5px 5px 12px rgba(45, 37, 41, 0.08),
        -4px -4px 10px rgba(255, 255, 255, 0.95);
}

.empty-icon i {
    font-size: 27px;
}

.empty-state h5 {
    margin-bottom: 6px;

    color: #3a3034;

    font-size: 16px;
    font-weight: 700;
}

.empty-state p {
    margin-bottom: 20px;

    color: #9a8c91;

    font-size: 13px;
}

.services-empty-btn {
    font-size: 13px;

    padding: 9px 15px;

    display: inline-flex;
}


/* =========================================================
   PAGINATION
========================================================= */

.services-pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 17px 20px;

    border-top: 1px solid #eee5e8;

    background: #ffffff;
}

.pagination-info {
    color: #8b7d82;

    font-size: 12px;
}

.pagination-info strong {
    color: #4d4146;
}


/* Bootstrap pagination refinement */
.services-pagination .pagination {
    margin: 0;
    gap: 5px;
}

.services-pagination .page-link {
    min-width: 34px;
    height: 34px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 9px;

    border-radius: 8px !important;

    border: 1px solid #e6dce0;

    color: #6f6066;

    background: #ffffff;

    font-size: 12px;

    box-shadow:
        0 2px 5px rgba(45, 37, 41, 0.03);

    transition: all .2s ease;
}

.services-pagination .page-link:hover {
    background: #f7edf1;
    border-color: #d9c8cf;
    color: #8b5e6d;
}

.services-pagination .page-item.active .page-link {
    background: #8b5e6d;
    border-color: #8b5e6d;

    color: #ffffff;

    box-shadow:
        0 4px 9px rgba(139, 94, 109, 0.18);
}

.services-pagination .page-item.disabled .page-link {
    background: #faf8f9;
    color: #b3a8ac;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 991px) {

    .services-hero {
        align-items: flex-start;
    }

    .services-table {
        min-width: 1050px;
    }

}


@media (max-width: 767px) {

    .services-hero {
        flex-direction: column;
        align-items: stretch;
    }

    .services-primary-btn {
        width: 100%;
    }

    .services-filter-card {
        padding: 16px;
    }

    .services-list-header {
        align-items: flex-start;
    }

    .services-pagination-wrapper {
        flex-direction: column;
        align-items: flex-start;
    }

    .services-pagination {
        width: 100%;
        overflow-x: auto;
    }

}


@media (max-width: 575px) {

    .services-page {
        padding-top: 5px;
    }

    .services-hero {
        padding: 20px 17px;
    }

    .services-hero h2 {
        font-size: 21px;
    }

    .services-title-icon {
        width: 43px;
        height: 43px;
    }

    .services-filter-header {
        align-items: flex-start;
    }

    .services-list-header {
        padding: 16px;
    }

    .services-list-icon {
        width: 35px;
        height: 35px;
    }

    .services-count-box {
        min-width: 38px;
    }

}

</style>

@endsection