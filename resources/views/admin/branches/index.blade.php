@extends('admin.layout')

@section('title', 'Branches | Salon Management')

@section('page-title', 'Branches')

@section('content')

<style>
    /* =========================================================
       BRANCHES INDEX - WHITE 3D PREMIUM DESIGN
       Font-family / font-size intentionally not changed
    ========================================================= */

    .branches-page {
        padding-bottom: 24px;
    }

    /* =========================================================
       WHITE HERO / BANNER
    ========================================================= */

    .branches-hero {
        position: relative;
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 20px;
        overflow: hidden;

        box-shadow:
            0 2px 4px rgba(0, 0, 0, 0.04),
            0 12px 28px rgba(0, 0, 0, 0.07),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    .branches-hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;

        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78493,
            #8b5e6d
        );
    }

    .branches-hero-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .branches-hero h4 {
        margin-bottom: 4px;
        font-weight: 700;
    }

    .branches-hero p {
        margin-bottom: 0;
        color: #6c757d;
    }

    /* =========================================================
       ALERTS
    ========================================================= */

    .branches-page .alert {
        border-radius: 13px;
        box-shadow:
            0 3px 8px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.75);
    }

    /* =========================================================
       COMMON CARD
    ========================================================= */

    .branches-card {
        background: #ffffff;
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 18px;
        overflow: hidden;

        box-shadow:
            0 3px 6px rgba(0, 0, 0, 0.035),
            0 14px 32px rgba(0, 0, 0, 0.07),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    /* =========================================================
       FILTER CARD
    ========================================================= */

    .branches-filter-card {
        padding: 20px;
        margin-bottom: 20px;

        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #fcfcfc 100%
        );
    }

    .branches-filter-title {
        margin-bottom: 17px;
        padding-bottom: 12px;

        border-bottom: 1px dashed rgba(139, 94, 109, 0.18);

        font-weight: 600;
    }

    .branches-page .form-label {
        margin-bottom: 7px;
    }

    .branches-page .form-control,
    .branches-page .form-select {
        border-radius: 11px;
        border: 1px solid #dee2e6;
        background: #ffffff;

        box-shadow:
            inset 0 1px 2px rgba(0, 0, 0, 0.025),
            0 2px 4px rgba(0, 0, 0, 0.025);

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            transform 0.2s ease;
    }

    .branches-page .form-control:hover,
    .branches-page .form-select:hover {
        border-color: #c8c8c8;

        box-shadow:
            0 3px 7px rgba(0, 0, 0, 0.045),
            inset 0 1px 2px rgba(0, 0, 0, 0.025);
    }

    .branches-page .form-control:focus,
    .branches-page .form-select:focus {
        border-color: rgba(139, 94, 109, 0.55);

        box-shadow:
            0 0 0 0.2rem rgba(139, 94, 109, 0.10),
            0 4px 10px rgba(0, 0, 0, 0.045),
            inset 0 1px 2px rgba(0, 0, 0, 0.025);

        transform: translateY(-1px);
    }

    /* =========================================================
       BUTTONS
    ========================================================= */

    .branch-3d-btn {
        border-radius: 11px;

        box-shadow:
            0 3px 0 rgba(0, 0, 0, 0.07),
            0 7px 16px rgba(0, 0, 0, 0.06);

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .branch-3d-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 5px 0 rgba(0, 0, 0, 0.07),
            0 10px 20px rgba(0, 0, 0, 0.09);
    }

    .branch-3d-btn:active {
        transform: translateY(1px);

        box-shadow:
            0 2px 0 rgba(0, 0, 0, 0.07),
            0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .branches-table-card {
        overflow: hidden;
    }

    .branches-table-wrapper {
        overflow-x: auto;
    }

    .branches-table {
        margin-bottom: 0;
        min-width: 1100px;
    }

    .branches-table thead th {
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #f8f9fa 100%
        );

        border-bottom: 1px solid rgba(0, 0, 0, 0.08);

        white-space: nowrap;
        font-weight: 600;
    }

    .branches-table tbody tr {
        transition:
            background-color 0.18s ease,
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .branches-table tbody tr:hover {
        background: #fcfbfc;

        box-shadow:
            inset 4px 0 0 rgba(139, 94, 109, 0.55);
    }

    .branches-table td {
        border-color: rgba(0, 0, 0, 0.055);
        vertical-align: middle;
    }

    /* =========================================================
       BRANCH NAME
    ========================================================= */

    .branch-name {
        font-weight: 600;
    }

    .branch-location {
        color: #777d82;
    }

    /* =========================================================
       CODE BADGE
    ========================================================= */

    .branch-code-badge {
        display: inline-block;

        padding: 5px 9px;

        background: #ffffff;
        color: #343a40;

        border: 1px solid #dee2e6;
        border-radius: 8px;

        box-shadow:
            0 2px 5px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .branch-status {
        border-radius: 8px;

        box-shadow:
            0 2px 5px rgba(0, 0, 0, 0.08);
    }

    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .branch-action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .branch-action-btn {
        border-radius: 9px;

        box-shadow:
            0 2px 4px rgba(0, 0, 0, 0.05);

        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease;
    }

    .branch-action-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 5px 10px rgba(0, 0, 0, 0.08);
    }

    .branch-login-btn {
        width: 100%;
        margin-top: 1px;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .branches-empty-state {
        padding: 55px 20px;
        text-align: center;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #fcfcfc 100%
        );
    }

    .branches-empty-state h6 {
        margin-bottom: 7px;
    }

    .branches-empty-state p {
        color: #777d82;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .branches-pagination {
        padding: 18px 20px;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
        background: #ffffff;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .branches-hero {
            padding: 18px;
            border-radius: 15px;
        }

        .branches-hero-content {
            flex-direction: column;
            align-items: stretch;
        }

        .branches-hero-content .btn {
            width: 100%;
        }

        .branches-filter-card {
            padding: 16px;
        }

        .branches-table {
            min-width: 1000px;
        }
    }
</style>


<div class="container-fluid px-0 branches-page">

    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

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
         WHITE 3D HEADER / BANNER
    ========================================================== --}}

    <div class="branches-hero">

        <div class="branches-hero-content">

            <div>

                <h4>
                    Branches
                </h4>

                <p>
                    Manage salon branches, managers and operating hours.
                </p>

            </div>


            <div>

                <a
                    href="{{ route('admin.branches.create') }}"
                    class="btn btn-primary branch-3d-btn"
                >
                    Add Branch
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTERS
    ========================================================== --}}

    <div class="branches-card branches-filter-card">

        <div class="branches-filter-title">
            Search & Filters
        </div>


        <form
            method="GET"
            action="{{ route('admin.branches.index') }}"
        >

            <div class="row g-3">

                {{-- SEARCH --}}

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search branch name, code, phone or city..."
                        value="{{ request('search') }}"
                    >

                </div>


                {{-- STATUS --}}

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
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


                {{-- BUTTONS --}}

                <div class="col-md-3 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary branch-3d-btn"
                    >
                        Search
                    </button>

                    <a
                        href="{{ route('admin.branches.index') }}"
                        class="btn btn-outline-secondary branch-3d-btn"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================================================
         BRANCH TABLE
    ========================================================== --}}

    <div class="branches-card branches-table-card">

        <div class="branches-table-wrapper">

            <table class="table table-hover align-middle branches-table">

                <thead>

                    <tr>

                        <th class="px-4">
                            #
                        </th>

                        <th>
                            Branch
                        </th>

                        <th>
                            Code
                        </th>

                        <th>
                            Salon
                        </th>

                        <th>
                            Manager
                        </th>

                        <th>
                            Contact
                        </th>

                        <th>
                            Operating Hours
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($branches as $branch)

                        <tr>

                            {{-- NUMBER --}}

                            <td class="px-4">

                                {{ $branches->firstItem() + $loop->index }}

                            </td>


                            {{-- BRANCH --}}

                            <td>

                                <div class="branch-name">
                                    {{ $branch->name }}
                                </div>

                                @if($branch->city)

                                    <small class="branch-location">
                                        {{ $branch->city }}
                                    </small>

                                @endif

                            </td>


                            {{-- CODE --}}

                            <td>

                                <span class="branch-code-badge">
                                    {{ $branch->code }}
                                </span>

                            </td>


                            {{-- SALON --}}

                            <td>

                                {{ $branch->salon->name ?? '—' }}

                            </td>


                            {{-- MANAGER --}}

                            <td>

                                {{ $branch->manager->name ?? 'Not Assigned' }}

                            </td>


                            {{-- CONTACT --}}

                            <td>

                                @if($branch->phone)

                                    <div>
                                        {{ $branch->phone }}
                                    </div>

                                @endif


                                @if($branch->email)

                                    <small class="text-muted">
                                        {{ $branch->email }}
                                    </small>

                                @endif


                                @if(!$branch->phone && !$branch->email)

                                    —

                                @endif

                            </td>


                            {{-- OPERATING HOURS --}}

                            <td>

                                @if($branch->opening_time && $branch->closing_time)

                                    {{ \Carbon\Carbon::parse($branch->opening_time)->format('h:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse($branch->closing_time)->format('h:i A') }}

                                @else

                                    <span class="text-muted">
                                        Not Set
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($branch->status)

                                    <span class="badge bg-success branch-status">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary branch-status">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-center">

                                <div class="branch-action-buttons">

                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('admin.branches.edit', $branch->id) }}"
                                        class="btn btn-sm btn-outline-primary branch-action-btn"
                                        title="Edit"
                                    >
                                        Edit
                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        method="POST"
                                        action="{{ route('admin.branches.destroy', $branch->id) }}"
                                        class="m-0"
                                        onsubmit="return confirm('Are you sure you want to delete this branch?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger branch-action-btn"
                                            title="Delete"
                                        >
                                            Delete
                                        </button>

                                    </form>


                                    {{-- BRANCH LOGIN --}}

                                    <a
                                        href="{{ route('admin.branches.login', $branch->id) }}"
                                        class="btn btn-sm btn-outline-primary branch-action-btn branch-login-btn"
                                        title="Branch Login"
                                    >
                                        Branch Login
                                    </a>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="p-0"
                            >

                                <div class="branches-empty-state">

                                    <h6 class="fw-semibold">
                                        No branches found
                                    </h6>

                                    <p class="mb-3">
                                        Create your first salon branch.
                                    </p>

                                    <a
                                        href="{{ route('admin.branches.create') }}"
                                        class="btn btn-primary btn-sm branch-3d-btn"
                                    >
                                        Add Branch
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($branches->hasPages())

            <div class="branches-pagination">

                {{ $branches->links() }}

            </div>

        @endif

    </div>

</div>

@endsection