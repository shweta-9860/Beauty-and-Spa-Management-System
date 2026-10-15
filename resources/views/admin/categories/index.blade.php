@extends('admin.layout')

@section('title', 'Categories | Salon Management')

@section('page-title', 'Categories')

@section('content')

<div class="container-fluid px-0 category-index-page">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show category-alert category-alert-success"
             role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show category-alert category-alert-danger"
             role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="category-index-hero">

        <div class="category-index-hero-content">

            <div class="category-index-title-row">

                <div class="category-index-title-icon">
                    <i class="bi bi-tags"></i>
                </div>

                <div>
                    <h4 class="category-index-title mb-1">
                        Service Categories
                    </h4>

                    <p class="category-index-subtitle mb-0">
                        Manage salon service categories
                    </p>
                </div>

            </div>

        </div>


        <a href="{{ route('admin.categories.create') }}"
           class="category-primary-btn">

            <i class="bi bi-plus-lg me-1"></i>
            Add Category

        </a>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}
    <div class="category-filter-card">

        <div class="category-filter-header">

            <div class="category-filter-title">
                <i class="bi bi-funnel me-2"></i>
                Category Filters
            </div>

            <span class="category-filter-hint">
                Refine your category list
            </span>

        </div>


        <div class="category-filter-body">

            <form method="GET"
                  action="{{ route('admin.categories.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-md-5">

                        <label class="category-form-label">
                            Search Category
                        </label>

                        <div class="category-input-wrap">

                            <i class="bi bi-search category-input-icon"></i>

                            <input
                                type="text"
                                name="search"
                                class="form-control category-input category-search-input"
                                placeholder="Search by name or description..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3">

                        <label class="category-form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-select category-input">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Sort --}}
                    <div class="col-md-2">

                        <label class="category-form-label">
                            Sort By
                        </label>

                        <select name="sort"
                                class="form-select category-input">

                            <option value="sort_order"
                                {{ request('sort', 'sort_order') == 'sort_order' ? 'selected' : '' }}>
                                Sort Order
                            </option>

                            <option value="name"
                                {{ request('sort') == 'name' ? 'selected' : '' }}>
                                Name
                            </option>

                            <option value="status"
                                {{ request('sort') == 'status' ? 'selected' : '' }}>
                                Status
                            </option>

                            <option value="created_at"
                                {{ request('sort') == 'created_at' ? 'selected' : '' }}>
                                Created Date
                            </option>

                        </select>

                    </div>


                    {{-- Direction --}}
                    <div class="col-md-2">

                        <label class="category-form-label">
                            Order
                        </label>

                        <select name="direction"
                                class="form-select category-input">

                            <option value="asc"
                                {{ request('direction', 'asc') == 'asc' ? 'selected' : '' }}>
                                Ascending
                            </option>

                            <option value="desc"
                                {{ request('direction') == 'desc' ? 'selected' : '' }}>
                                Descending
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-12">

                        <div class="category-filter-actions">

                            <button type="submit"
                                    class="category-primary-btn category-filter-btn">

                                <i class="bi bi-funnel me-1"></i>
                                Apply Filters

                            </button>


                            <a href="{{ route('admin.categories.index') }}"
                               class="category-secondary-btn">

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
        CATEGORY LIST CARD
    ========================================================== --}}
    <div class="category-list-card">

        {{-- Card Header --}}
        <div class="category-list-header">

            <div class="category-list-heading">

                <div class="category-list-heading-icon">
                    <i class="bi bi-list-ul"></i>
                </div>

                <div>

                    <div class="category-list-title">
                        Category List
                    </div>

                    <div class="category-list-subtitle">
                        All service categories
                    </div>

                </div>

            </div>


            <div class="category-total-badge">
                {{ $categories->total() }} Categories
            </div>

        </div>


        {{-- Card Body --}}
        <div class="category-list-body">

            @if($categories->count() > 0)

                <div class="table-responsive">

                    <table class="table category-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th width="70">
                                    #
                                </th>

                                <th width="90">
                                    Image
                                </th>

                                <th>
                                    Category
                                </th>

                                <th>
                                    Description
                                </th>

                                <th width="120">
                                    Services
                                </th>

                                <th width="110">
                                    Sort Order
                                </th>

                                <th width="120">
                                    Status
                                </th>

                                <th width="150">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($categories as $category)

                                <tr class="category-table-row">

                                    {{-- Number --}}
                                    <td>

                                        <span class="category-number">
                                            {{ $categories->firstItem() + $loop->index }}
                                        </span>

                                    </td>


                                    {{-- Image --}}
                                    <td>

                                        @if($category->image)

                                            <a href="{{ $category->image }}"
                                               target="_blank"
                                               class="category-image-link">

                                                <img
                                                    src="{{ $category->image }}"
                                                    alt="{{ $category->name }}"
                                                    class="category-image"
                                                >

                                            </a>

                                        @else

                                            <div class="category-image-placeholder">

                                                <i class="bi bi-image"></i>

                                            </div>

                                        @endif

                                    </td>


                                    {{-- Name --}}
                                    <td>

                                        <div class="category-name">
                                            {{ $category->name }}
                                        </div>

                                    </td>


                                    {{-- Description --}}
                                    <td>

                                        <span class="description-text">

                                            {{ $category->description
                                                ? \Illuminate\Support\Str::limit(
                                                    $category->description,
                                                    60
                                                )
                                                : 'No description'
                                            }}

                                        </span>

                                    </td>


                                    {{-- Services --}}
                                    <td>

                                        <span class="service-count">

                                            <i class="bi bi-scissors me-1"></i>

                                            {{ $category->services_count }}

                                        </span>

                                    </td>


                                    {{-- Sort Order --}}
                                    <td>

                                        <span class="sort-badge">
                                            {{ $category->sort_order }}
                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($category->status === 'active')

                                            <span class="status-badge status-active">

                                                <i class="bi bi-check-circle me-1"></i>
                                                Active

                                            </span>

                                        @else

                                            <span class="status-badge status-inactive">

                                                <i class="bi bi-x-circle me-1"></i>
                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="category-action-group">

                                            {{-- Edit --}}
                                            <a
                                                href="{{ route(
                                                    'admin.categories.edit',
                                                    $category
                                                ) }}"
                                                class="category-action-btn category-edit-btn"
                                                title="Edit Category"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            {{-- Toggle Status --}}
                                            <form
                                                action="{{ route(
                                                    'admin.categories.toggle-status',
                                                    $category
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="category-action-btn category-status-btn"
                                                    title="Toggle Status"
                                                >

                                                    @if($category->status === 'active')

                                                        <i class="bi bi-toggle-on"></i>

                                                    @else

                                                        <i class="bi bi-toggle-off"></i>

                                                    @endif

                                                </button>

                                            </form>


                                            {{-- Delete --}}
                                            <form
                                                action="{{ route(
                                                    'admin.categories.destroy',
                                                    $category
                                                ) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to delete this category?'
                                                );"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="category-action-btn category-delete-btn"
                                                    title="Delete Category"
                                                >

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
                <div class="category-pagination">

                    {{ $categories->links() }}

                </div>

            @else

                {{-- Empty State --}}
                <div class="category-empty-state">

                    <div class="category-empty-icon">

                        <i class="bi bi-tags"></i>

                    </div>

                    <h5>
                        No Categories Found
                    </h5>

                    <p>
                        No categories match your current filters.
                    </p>

                    <a
                        href="{{ route('admin.categories.create') }}"
                        class="category-primary-btn"
                    >

                        <i class="bi bi-plus-lg me-1"></i>
                        Add First Category

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


@push('styles')

<style>

    /* =========================================================
       CATEGORY INDEX PAGE
    ========================================================== */

    .category-index-page {
        padding-bottom: 25px;
    }


    /* =========================================================
       ALERTS
    ========================================================== */

    .category-alert {
        border-radius: 14px;
        border: 1px solid transparent;
        padding: 13px 16px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.03),
            0 10px 24px rgba(45, 37, 41, 0.06);
    }

    .category-alert-success {
        background: #f1faf4;
        border-color: #d9efdf;
        color: #287a4a;
    }

    .category-alert-danger {
        background: #fff4f4;
        border-color: #f0dada;
        color: #a13d3d;
    }


    /* =========================================================
       HERO
    ========================================================== */

    .category-index-hero {
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

    .category-index-hero::before {
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

    .category-index-hero-content {
        position: relative;
        z-index: 1;
    }

    .category-index-title-row {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .category-index-title-icon {
        width: 48px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: linear-gradient(
            145deg,
            #ffffff,
            #f5eef1
        );

        color: #8b5e6d;

        border: 1px solid #eadfe4;
        border-radius: 14px;

        box-shadow:
            4px 4px 9px rgba(45, 37, 41, 0.08),
            -3px -3px 7px rgba(255, 255, 255, 0.95);

        font-size: 20px;
    }

    .category-index-title {
        color: #4e4549;
        font-weight: 600;
    }

    .category-index-subtitle {
        color: #8b8588;
    }


    /* =========================================================
       PRIMARY BUTTON
    ========================================================== */

    .category-primary-btn {
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

    .category-primary-btn:hover {
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

    .category-primary-btn:active {
        transform: translateY(3px);

        box-shadow:
            0 2px 0 #62414d,
            0 5px 10px rgba(139, 94, 109, 0.18);
    }


    /* =========================================================
       FILTER CARD
    ========================================================== */

    .category-filter-card {
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

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .category-filter-card:hover {
        transform: translateY(-1px);

        box-shadow:
            0 5px 10px rgba(45, 37, 41, 0.04),
            0 15px 32px rgba(45, 37, 41, 0.08),
            0 28px 60px rgba(45, 37, 41, 0.05);
    }

    .category-filter-header {
        padding: 16px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        background: #ffffff;

        border-bottom: 1px solid #eee5e8;
    }

    .category-filter-title {
        color: #4e4549;
        font-weight: 600;
    }

    .category-filter-title i {
        color: #8b5e6d;
    }

    .category-filter-hint {
        color: #999195;
        font-size: 12px;
    }

    .category-filter-body {
        padding: 20px;
    }


    /* =========================================================
       FORM ELEMENTS
    ========================================================== */

    .category-form-label {
        display: block;

        color: #4e4549;

        font-weight: 600;

        margin-bottom: 7px;
    }

    .category-input-wrap {
        position: relative;
    }

    .category-input-icon {
        position: absolute;

        left: 13px;
        top: 50%;

        transform: translateY(-50%);

        color: #8b8588;

        z-index: 2;
    }

    .category-search-input {
        padding-left: 38px !important;
    }

    .category-input {
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

    .category-input:hover {
        border-color: #cfc1c7;
    }

    .category-input:focus {
        border-color: #8b5e6d;

        box-shadow:
            0 0 0 0.2rem rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(45, 37, 41, 0.05);

        outline: none;
    }

    .category-filter-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 4px;
    }

    .category-filter-btn {
        min-width: 140px;
    }

    .category-secondary-btn {
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

    .category-secondary-btn:hover {
        color: #4e4549;

        background: #faf7f8;

        border-color: #cfc1c7;

        transform: translateY(-1px);

        box-shadow:
            0 5px 11px rgba(45, 37, 41, 0.07);
    }


    /* =========================================================
       LIST CARD
    ========================================================== */

    .category-list-card {
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

    .category-list-header {
        min-height: 72px;

        padding: 15px 20px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        background: #ffffff;

        border-bottom: 1px solid #eee5e8;
    }

    .category-list-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .category-list-heading-icon {
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

    .category-list-title {
        color: #4e4549;
        font-weight: 600;
    }

    .category-list-subtitle {
        color: #999195;
        font-size: 12px;
        margin-top: 2px;
    }

    .category-total-badge {
        padding: 7px 12px;

        border-radius: 20px;

        background: #f7edf1;
        color: #8b5e6d;

        border: 1px solid #eadfe4;

        font-size: 12px;
        font-weight: 600;

        box-shadow:
            0 2px 5px rgba(45, 37, 41, 0.04);
    }

    .category-list-body {
        background: #ffffff;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .category-table {
        margin: 0;
    }

    .category-table thead th {
        background: #faf7f8;

        color: #6d6569;

        font-weight: 600;

        padding: 14px 15px;

        border-bottom: 1px solid #eadfe4;

        white-space: nowrap;
    }

    .category-table tbody td {
        padding: 15px;

        color: #5b5558;

        border-bottom: 1px solid #f0e9ec;

        vertical-align: middle;
    }

    .category-table-row {
        transition:
            background 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .category-table-row:hover {
        background: #fdfafb;

        box-shadow:
            inset 4px 0 0 #8b5e6d;
    }

    .category-table tbody tr:last-child td {
        border-bottom: 0;
    }


    /* =========================================================
       NUMBER
    ========================================================== */

    .category-number {
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
       IMAGE
    ========================================================== */

    .category-image-link {
        display: inline-block;

        border-radius: 12px;

        transition:
            transform 0.18s ease;
    }

    .category-image-link:hover {
        transform: translateY(-2px);
    }

    .category-image,
    .category-image-placeholder {
        width: 54px;
        height: 54px;

        border-radius: 12px;

        object-fit: cover;

        border: 1px solid #eadfe4;

        display: block;

        box-shadow:
            3px 4px 9px rgba(45, 37, 41, 0.08);
    }

    .category-image-placeholder {
        background: linear-gradient(
            145deg,
            #ffffff,
            #f7edf1
        );

        color: #8b5e6d;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 19px;
    }


    /* =========================================================
       CATEGORY NAME
    ========================================================== */

    .category-name {
        color: #4e4549;

        font-weight: 600;
    }


    /* =========================================================
       DESCRIPTION
    ========================================================== */

    .description-text {
        color: #8b8588;
    }


    /* =========================================================
       SERVICE COUNT
    ========================================================== */

    .service-count {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        border-radius: 20px;

        background: #f7edf1;
        color: #8b5e6d;

        border: 1px solid #eadfe4;

        font-weight: 600;

        box-shadow:
            0 2px 5px rgba(45, 37, 41, 0.04);
    }


    /* =========================================================
       SORT BADGE
    ========================================================== */

    .sort-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 36px;

        padding: 6px 10px;

        border-radius: 8px;

        background: #f5f2f3;

        color: #6d6569;

        border: 1px solid #e7e0e3;

        font-weight: 600;

        box-shadow:
            0 2px 5px rgba(45, 37, 41, 0.035);
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        border-radius: 20px;

        font-weight: 600;

        border: 1px solid transparent;
    }

    .status-active {
        background: #edf9f1;
        color: #287a4a;

        border-color: #d7efdf;
    }

    .status-inactive {
        background: #fff1f1;
        color: #a13d3d;

        border-color: #efdada;
    }


    /* =========================================================
       ACTIONS
    ========================================================== */

    .category-action-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .category-action-btn {
        width: 34px;
        height: 34px;

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

    .category-action-btn:hover {
        transform: translateY(-2px);
    }

    .category-edit-btn {
        color: #8b5e6d;

        background: #f7edf1;

        border: 1px solid #eadfe4;

        box-shadow:
            0 3px 6px rgba(45, 37, 41, 0.05);
    }

    .category-edit-btn:hover {
        background: #8b5e6d;
        color: #ffffff;

        box-shadow:
            0 5px 10px rgba(139, 94, 109, 0.18);
    }

    .category-status-btn {
        color: #65727d;

        background: #f2f4f5;

        border: 1px solid #e0e4e7;

        box-shadow:
            0 3px 6px rgba(45, 37, 41, 0.04);
    }

    .category-status-btn:hover {
        background: #65727d;
        color: #ffffff;

        box-shadow:
            0 5px 10px rgba(101, 114, 125, 0.16);
    }

    .category-delete-btn {
        color: #b54848;

        background: #faeeee;

        border: 1px solid #efdada;

        box-shadow:
            0 3px 6px rgba(45, 37, 41, 0.04);
    }

    .category-delete-btn:hover {
        background: #b54848;
        color: #ffffff;

        box-shadow:
            0 5px 10px rgba(181, 72, 72, 0.16);
    }


    /* =========================================================
       PAGINATION
    ========================================================== */

    .category-pagination {
        padding: 18px 20px;

        display: flex;
        justify-content: flex-end;

        border-top: 1px solid #f0e9ec;

        background: #ffffff;
    }

    .category-pagination .pagination {
        margin-bottom: 0;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .category-empty-state {
        text-align: center;

        padding: 65px 20px;
    }

    .category-empty-icon {
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

    .category-empty-state h5 {
        color: #4e4549;
        font-weight: 600;
    }

    .category-empty-state p {
        color: #8b8588;

        margin-bottom: 20px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 992px) {

        .category-filter-hint {
            display: none;
        }

        .category-list-header {
            gap: 12px;
        }

    }


    @media (max-width: 768px) {

        .category-index-hero {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .category-index-hero .category-primary-btn {
            width: 100%;
        }

        .category-filter-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .category-filter-btn,
        .category-secondary-btn {
            width: 100%;
        }

        .category-list-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .category-pagination {
            justify-content: center;
        }

    }


    @media (max-width: 576px) {

        .category-index-hero {
            padding: 20px 16px;
        }

        .category-filter-body {
            padding: 16px;
        }

        .category-list-header {
            padding: 15px 16px;
        }

        .category-table thead th,
        .category-table tbody td {
            padding: 12px;
        }

        .category-action-group {
            gap: 4px;
        }

        .category-action-btn {
            width: 32px;
            height: 32px;
        }

    }

</style>

@endpush

@endsection