@extends('admin.layout')

@section('title', 'Staff | Salon Management')

@section('page-title', 'Staff')

@section('content')

<div class="staff-page">

    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="staff-alert staff-alert-success alert-dismissible fade show" role="alert">

            <div class="staff-alert-content">
                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>
            </div>

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

        <div class="staff-alert staff-alert-danger">

            <div class="staff-alert-content">

                <i class="bi bi-exclamation-circle-fill"></i>

                <div>
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
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="staff-page-header">

        <div class="staff-page-heading">

            <div class="staff-page-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>

                <h4>
                    Staff / Beauticians
                </h4>

                <p>
                    Manage salon staff, beauticians and their professional details.
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.staff.create') }}"
            class="staff-add-btn"
        >
            <i class="bi bi-plus-lg"></i>
            Add Staff
        </a>

    </div>


    {{-- =========================================================
         FILTER CARD
    ========================================================== --}}
    <div class="staff-filter-card">

        <div class="staff-filter-header">

            <div class="staff-filter-title">
                <span class="staff-filter-icon">
                    <i class="bi bi-funnel"></i>
                </span>

                <div>
                    <h5>Filters</h5>
                    <small>Search and filter staff records</small>
                </div>
            </div>

        </div>


        <div class="staff-filter-body">

            <form
                method="GET"
                action="{{ route('admin.staff.index') }}"
            >

                <div class="row g-3 align-items-end">

                    {{-- =================================================
                         SEARCH
                    ================================================== --}}
                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <label class="staff-form-label">
                            Search
                        </label>

                        <div class="staff-search-box">

                            <i class="bi bi-search"></i>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Name, email, phone..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         SALON
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="staff-form-label">
                            Salon
                        </label>

                        @if(auth()->check() && !empty(auth()->user()->branch_id))

                            <input
                                type="hidden"
                                name="salon_id"
                                value="{{ auth()->user()->salon_id }}"
                            >

                            <input
                                type="text"
                                class="form-control"
                                value="{{ auth()->user()->salon?->name }}"
                                readonly
                            >

                        @else

                            <select
                                name="salon_id"
                                id="filter_salon_id"
                                class="form-select"
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
                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="staff-form-label">
                            Branch
                        </label>

                        @if(auth()->check() && !empty(auth()->user()->branch_id))

                            <input
                                type="hidden"
                                name="branch_id"
                                value="{{ auth()->user()->branch_id }}"
                            >

                            <input
                                type="text"
                                class="form-control"
                                value="{{ auth()->user()->branch?->name }}"
                                readonly
                            >

                        @else

                            <select
                                name="branch_id"
                                id="filter_branch_id"
                                class="form-select"
                            >

                                <option value="">
                                    All Branches
                                </option>

                                @foreach($branches as $branch)

                                    <option
                                        value="{{ $branch->id }}"
                                        data-salon="{{ $branch->salon_id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}
                                    >
                                        {{ $branch->name }}
                                    </option>

                                @endforeach

                            </select>

                        @endif

                    </div>


                    {{-- =================================================
                         ROLE
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="staff-form-label">
                            Role
                        </label>

                        <select
                            name="role"
                            class="form-select"
                        >

                            <option value="">
                                All Roles
                            </option>

                            @foreach($roles as $role)

                                <option
                                    value="{{ $role }}"
                                    {{ request('role') == $role ? 'selected' : '' }}
                                >
                                    {{ ucfirst($role) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- =================================================
                         STATUS
                    ================================================== --}}
                    <div class="col-xl-2 col-lg-4 col-md-6">

                        <label class="staff-form-label">
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
                                value="1"
                                {{ request('status') === '1' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ request('status') === '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                         FILTER BUTTONS
                    ================================================== --}}
                    <div class="col-xl-1 col-lg-4 col-md-6">

                        <div class="staff-filter-actions">

                            <button
                                type="submit"
                                class="staff-filter-btn"
                                title="Filter"
                            >
                                <i class="bi bi-funnel"></i>
                            </button>

                            <a
                                href="{{ route('admin.staff.index') }}"
                                class="staff-reset-btn"
                                title="Reset"
                            >
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         STAFF TABLE CARD
    ========================================================== --}}
    <div class="staff-table-card">

        <div class="staff-table-top">

            <div>

                <h5>
                    Staff Members
                </h5>

                <span>
                    Manage your salon team and professional information
                </span>

            </div>

            @if(method_exists($staff, 'total'))

                <div class="staff-count-badge">
                    {{ $staff->total() }} Staff
                </div>

            @endif

        </div>


        <div class="table-responsive">

            <table class="table staff-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Staff</th>
                        <th>Salon</th>
                        <th>Branch</th>
                        <th>Contact</th>
                        <th>Role</th>
                        <th>Specialization</th>
                        <th>Salary</th>
                        <th>Commission</th>
                        <th>Status</th>

                        <th class="text-center">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($staff as $member)

                        <tr>

                            {{-- =================================================
                                 NUMBER
                            ================================================== --}}
                            <td class="staff-number">

                                {{ $staff->firstItem() + $loop->index }}

                            </td>


                            {{-- =================================================
                                 STAFF
                            ================================================== --}}
                            <td>

                                <div class="staff-info">

                                    <div class="staff-avatar">

                                        {{ strtoupper(substr($member->name, 0, 1)) }}

                                    </div>


                                    <div class="staff-name-area">

                                        <div class="staff-name">
                                            {{ $member->name }}
                                        </div>

                                        @if($member->joining_date)

                                            <small>
                                                Joined
                                                {{ $member->joining_date->format('d M Y') }}
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 SALON
                            ================================================== --}}
                            <td>

                                @if($member->salon)

                                    <div class="staff-location">

                                        <span class="staff-location-icon">
                                            <i class="bi bi-building"></i>
                                        </span>

                                        <span>
                                            {{ $member->salon->name }}
                                        </span>

                                    </div>

                                @else

                                    <span class="staff-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 BRANCH
                            ================================================== --}}
                            <td>

                                @if($member->branch)

                                    <div class="staff-location">

                                        <span class="staff-location-icon">
                                            <i class="bi bi-geo-alt"></i>
                                        </span>

                                        <span>
                                            {{ $member->branch->name }}
                                        </span>

                                    </div>

                                    <small class="staff-branch-code">
                                        {{ $member->branch->code }}
                                    </small>

                                @else

                                    <span class="staff-muted">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 CONTACT
                            ================================================== --}}
                            <td>

                                @if($member->phone)

                                    <div class="staff-contact-line">

                                        <i class="bi bi-telephone"></i>

                                        {{ $member->phone }}

                                    </div>

                                @endif


                                @if($member->email)

                                    <div class="staff-contact-line">

                                        <i class="bi bi-envelope"></i>

                                        {{ $member->email }}

                                    </div>

                                @endif


                                @if(!$member->phone && !$member->email)

                                    <span class="staff-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ROLE
                            ================================================== --}}
                            <td>

                                <span class="staff-role-badge">
                                    {{ $member->role }}
                                </span>

                            </td>


                            {{-- =================================================
                                 SPECIALIZATION
                            ================================================== --}}
                            <td>

                                @if($member->specialization)

                                    <span class="staff-specialization">
                                        {{ $member->specialization }}
                                    </span>

                                @else

                                    <span class="staff-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 SALARY
                            ================================================== --}}
                            <td>

                                <span class="staff-money">
                                    ₹{{ number_format((float) $member->salary, 2) }}
                                </span>

                            </td>


                            {{-- =================================================
                                 COMMISSION
                            ================================================== --}}
                            <td>

                                <span class="staff-money">

                                    @if($member->commission_type === 'percentage')

                                        {{ number_format((float) $member->commission_value, 2) }}%

                                    @else

                                        ₹{{ number_format((float) $member->commission_value, 2) }}

                                    @endif

                                </span>

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td>

                                @if($member->status)

                                    <span class="staff-status-badge staff-status-active">

                                        <span class="staff-status-dot"></span>

                                        Active

                                    </span>

                                @else

                                    <span class="staff-status-badge staff-status-inactive">

                                        <span class="staff-status-dot"></span>

                                        Inactive

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}
                            <td>

                                <div class="staff-action-buttons">

                                    {{-- Schedule --}}
                                    <a
                                        href="{{ route('admin.staff.schedule.index', $member->id) }}"
                                        class="staff-action-btn staff-action-schedule"
                                        title="Weekly Schedule"
                                    >
                                        <i class="bi bi-calendar-week"></i>
                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.staff.edit', $member->id) }}"
                                        class="staff-action-btn staff-action-edit"
                                        title="Edit Staff"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- Toggle Status --}}
                                    <form
                                        action="{{ route('admin.staff.toggle-status', $member->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="staff-action-btn staff-action-status"
                                            title="{{ $member->status ? 'Deactivate' : 'Activate' }}"
                                        >

                                            @if($member->status)

                                                <i class="bi bi-toggle-on"></i>

                                            @else

                                                <i class="bi bi-toggle-off"></i>

                                            @endif

                                        </button>

                                    </form>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.staff.destroy', $member->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this staff member?');"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="staff-action-btn staff-action-delete"
                                            title="Delete Staff"
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
                                colspan="11"
                                class="staff-empty-state"
                            >

                                <div class="staff-empty-icon">
                                    <i class="bi bi-people"></i>
                                </div>

                                <h5>
                                    No Staff Found
                                </h5>

                                <p>
                                    No staff members match your current filters.
                                </p>

                                <a
                                    href="{{ route('admin.staff.create') }}"
                                    class="staff-empty-btn"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    Add Staff
                                </a>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($staff->hasPages())

            <div class="staff-pagination">

                {{ $staff->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     STYLES
============================================================= --}}
@push('styles')

<style>

    /* =========================================================
       STAFF PAGE
    ========================================================= */

    .staff-page {
        width: 100%;
        padding: 3px 0 30px;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .staff-alert {
        position: relative;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 18px;
        margin-bottom: 18px;
        border-radius: 14px;
        border: 1px solid;
        box-shadow:
            0 4px 9px rgba(45, 37, 41, 0.04),
            0 10px 22px rgba(45, 37, 41, 0.04);
    }

    .staff-alert-content {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .staff-alert-content > i {
        margin-top: 2px;
    }

    .staff-alert-success {
        background: #f8fffb;
        border-color: #ccebd9;
        color: #176b3b;
    }

    .staff-alert-success .staff-alert-content > i {
        color: #198754;
    }

    .staff-alert-danger {
        background: #fff9fa;
        border-color: #f1d1d5;
        color: #842029;
    }

    .staff-alert-danger .staff-alert-content > i {
        color: #dc3545;
    }

    .staff-alert-danger ul {
        padding-left: 19px;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .staff-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 22px;
        padding: 20px 22px;
        background: #ffffff;
        border: 1px solid #eee5e9;
        border-radius: 18px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.025),
            0 12px 28px rgba(45, 37, 41, 0.055);
    }

    .staff-page-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .staff-page-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(
            145deg,
            #f8eef2,
            #ffffff
        );
        border: 1px solid #eadce2;
        color: #8b5e6d;
        box-shadow:
            0 4px 9px rgba(45, 37, 41, 0.06),
            inset 0 1px 0 rgba(255,255,255,.9);
    }

    .staff-page-heading h4 {
        margin: 0;
        font-weight: 700;
        color: #2d2529;
    }

    .staff-page-heading p {
        margin: 4px 0 0;
        color: #7b7176;
    }


    /* =========================================================
       ADD STAFF BUTTON
    ========================================================= */

    .staff-add-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 43px;
        padding: 9px 18px;
        border-radius: 11px;
        border: none;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        box-shadow:
            0 5px 0 #62414d,
            0 10px 20px rgba(139, 94, 109, 0.17);
        transition: all .2s ease;
    }

    .staff-add-btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
        background: linear-gradient(
            135deg,
            #956879,
            #7d5664
        );
        box-shadow:
            0 7px 0 #62414d,
            0 13px 24px rgba(139, 94, 109, 0.21);
    }

    .staff-add-btn:active {
        transform: translateY(3px);
        box-shadow:
            0 2px 0 #62414d,
            0 5px 12px rgba(139, 94, 109, .14);
    }


    /* =========================================================
       FILTER CARD
    ========================================================= */

    .staff-filter-card {
        position: relative;
        overflow: hidden;
        margin-bottom: 22px;
        background: #ffffff;
        border: 1px solid #eee5e9;
        border-radius: 18px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, 0.025),
            0 12px 28px rgba(45, 37, 41, 0.06),
            0 25px 50px rgba(45, 37, 41, 0.035);
    }

    .staff-filter-card::before {
        content: "";
        display: block;
        height: 3px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }

    .staff-filter-header {
        padding: 17px 20px;
        border-bottom: 1px solid #eee6ea;
        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfafb
        );
    }

    .staff-filter-title {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .staff-filter-icon {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f8eef2;
        color: #8b5e6d;
        border: 1px solid #eadce2;
    }

    .staff-filter-title h5 {
        margin: 0;
        color: #4e4549;
        font-weight: 700;
    }

    .staff-filter-title small {
        color: #8a7f84;
    }

    .staff-filter-body {
        padding: 20px;
    }


    /* =========================================================
       FORM CONTROLS
    ========================================================= */

    .staff-form-label {
        display: block;
        margin-bottom: 7px;
        color: #4e4549;
        font-weight: 600;
    }

    .staff-page .form-control,
    .staff-page .form-select {
        min-height: 44px;
        border-radius: 11px;
        border: 1px solid #ddd5d9;
        background: #ffffff;
        color: #40383c;
        box-shadow:
            inset 0 1px 2px rgba(45,37,41,.025),
            0 2px 5px rgba(45,37,41,.025);
        transition: all .2s ease;
    }

    .staff-page .form-control:hover,
    .staff-page .form-select:hover {
        border-color: #cdbbc3;
    }

    .staff-page .form-control:focus,
    .staff-page .form-select:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139,94,109,.10),
            0 4px 10px rgba(45,37,41,.05);
        outline: none;
    }

    .staff-search-box {
        position: relative;
    }

    .staff-search-box > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #9a8e94;
        z-index: 2;
    }

    .staff-search-box .form-control {
        padding-left: 38px;
    }


    /* =========================================================
       FILTER BUTTONS
    ========================================================= */

    .staff-filter-actions {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .staff-filter-btn,
    .staff-reset-btn {
        width: 43px;
        height: 43px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        transition: all .2s ease;
    }

    .staff-filter-btn {
        border: none;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        color: #ffffff;
        box-shadow:
            0 4px 0 #62414d,
            0 7px 14px rgba(139,94,109,.15);
    }

    .staff-filter-btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 6px 0 #62414d,
            0 10px 18px rgba(139,94,109,.19);
    }

    .staff-reset-btn {
        border: 1px solid #ddd4d9;
        background: #ffffff;
        color: #655a60;
        text-decoration: none;
        box-shadow:
            0 3px 7px rgba(45,37,41,.035);
    }

    .staff-reset-btn:hover {
        color: #8b5e6d;
        border-color: #cdbbc3;
        transform: translateY(-1px);
    }


    /* =========================================================
       TABLE CARD
    ========================================================= */

    .staff-table-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #eee5e9;
        border-radius: 18px;
        box-shadow:
            0 4px 8px rgba(45,37,41,.025),
            0 12px 28px rgba(45,37,41,.065),
            0 25px 55px rgba(45,37,41,.04);
    }

    .staff-table-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 19px 21px;
        border-bottom: 1px solid #eee6ea;
        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfafb
        );
    }

    .staff-table-top h5 {
        margin: 0;
        color: #4e4549;
        font-weight: 700;
    }

    .staff-table-top span {
        display: block;
        margin-top: 3px;
        color: #8a7f84;
    }

    .staff-count-badge {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-height: 32px;
        padding: 5px 12px;
        border-radius: 20px;
        background: #f8eef2;
        border: 1px solid #eadce2;
        color: #8b5e6d !important;
        font-weight: 700;
        white-space: nowrap;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .staff-table {
        min-width: 1500px;
        margin: 0;
    }

    .staff-table thead th {
        padding: 14px 14px;
        background: #faf8f9;
        color: #655b60;
        border-bottom: 1px solid #eae2e6;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .35px;
        white-space: nowrap;
    }

    .staff-table tbody td {
        padding: 15px 14px;
        border-bottom: 1px solid #f0eaed;
        color: #494145;
        vertical-align: middle;
    }

    .staff-table tbody tr {
        transition: background .2s ease;
    }

    .staff-table tbody tr:hover {
        background: #fffafb;
    }

    .staff-table tbody tr:last-child td {
        border-bottom: none;
    }

    .staff-number {
        color: #887d83 !important;
        font-weight: 600;
    }


    /* =========================================================
       STAFF INFO
    ========================================================= */

    .staff-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 190px;
    }

    .staff-avatar {
        width: 43px;
        height: 43px;
        min-width: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: linear-gradient(
            145deg,
            #f8eaf0,
            #ffffff
        );
        border: 1px solid #ead9e0;
        color: #8b5e6d;
        font-weight: 700;
        box-shadow:
            0 3px 7px rgba(45,37,41,.05);
    }

    .staff-name-area {
        min-width: 0;
    }

    .staff-name {
        color: #30282c;
        font-weight: 700;
        white-space: nowrap;
    }

    .staff-name-area small {
        display: block;
        margin-top: 3px;
        color: #94898f;
    }


    /* =========================================================
       LOCATION
    ========================================================= */

    .staff-location {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #51484d;
        font-weight: 600;
        white-space: nowrap;
    }

    .staff-location-icon {
        width: 25px;
        height: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #faf1f5;
        color: #8b5e6d;
    }

    .staff-branch-code {
        display: block;
        margin-top: 4px;
        color: #978c92;
    }

    .staff-muted {
        color: #9b9196;
    }


    /* =========================================================
       CONTACT
    ========================================================= */

    .staff-contact-line {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        color: #655b60;
        white-space: nowrap;
    }

    .staff-contact-line:last-child {
        margin-bottom: 0;
    }

    .staff-contact-line i {
        width: 16px;
        color: #8b5e6d;
    }


    /* =========================================================
       ROLE / SPECIALIZATION / MONEY
    ========================================================= */

    .staff-role-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 11px;
        border-radius: 20px;
        background: #f7f1f4;
        border: 1px solid #eadfe4;
        color: #62565c;
        font-weight: 600;
        white-space: nowrap;
    }

    .staff-specialization {
        color: #5c5257;
        white-space: nowrap;
    }

    .staff-money {
        color: #4b4247;
        font-weight: 600;
        white-space: nowrap;
    }


    /* =========================================================
       STATUS
    ========================================================= */

    .staff-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 11px;
        border-radius: 20px;
        font-weight: 600;
        white-space: nowrap;
    }

    .staff-status-active {
        background: #edf9f2;
        border: 1px solid #d3efdf;
        color: #198754;
    }

    .staff-status-inactive {
        background: #f5f3f4;
        border: 1px solid #e6e1e3;
        color: #777075;
    }

    .staff-status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .staff-action-buttons {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 160px;
    }

    .staff-action-buttons form {
        margin: 0;
    }

    .staff-action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5dfe2;
        border-radius: 9px;
        background: #ffffff;
        text-decoration: none;
        cursor: pointer;
        box-shadow:
            0 2px 5px rgba(45,37,41,.035);
        transition: all .2s ease;
    }

    .staff-action-btn:hover {
        transform: translateY(-2px);
        box-shadow:
            0 5px 11px rgba(45,37,41,.08);
    }

    .staff-action-schedule {
        color: #6f42c1;
    }

    .staff-action-schedule:hover {
        color: #6f42c1;
        background: #f6f1ff;
        border-color: #d9c9f3;
    }

    .staff-action-edit {
        color: #0d6efd;
    }

    .staff-action-edit:hover {
        color: #0d6efd;
        background: #eef5ff;
        border-color: #c8dcff;
    }

    .staff-action-status {
        color: #8b5e6d;
    }

    .staff-action-status:hover {
        color: #8b5e6d;
        background: #fbf0f4;
        border-color: #e4c4cf;
    }

    .staff-action-delete {
        color: #dc3545;
    }

    .staff-action-delete:hover {
        color: #dc3545;
        background: #fff2f3;
        border-color: #efc3c7;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .staff-empty-state {
        padding: 70px 20px !important;
        text-align: center;
    }

    .staff-empty-icon {
        width: 68px;
        height: 68px;
        margin: 0 auto 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(
            145deg,
            #f8eaf0,
            #ffffff
        );
        border: 1px solid #ead9e0;
        color: #8b5e6d;
        box-shadow:
            0 5px 14px rgba(45,37,41,.07);
    }

    .staff-empty-icon i {
        font-size: 27px;
    }

    .staff-empty-state h5 {
        margin-bottom: 5px;
        color: #40373b;
        font-weight: 700;
    }

    .staff-empty-state p {
        margin-bottom: 18px;
        color: #91868c;
    }

    .staff-empty-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 10px;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        color: #ffffff;
        text-decoration: none;
        font-weight: 600;
        box-shadow:
            0 4px 0 #62414d,
            0 8px 16px rgba(139,94,109,.15);
        transition: all .2s ease;
    }

    .staff-empty-btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .staff-pagination {
        display: flex;
        justify-content: flex-end;
        padding: 16px 20px;
        border-top: 1px solid #eee6ea;
        background: #ffffff;
    }

    .staff-pagination .pagination {
        margin: 0;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 992px) {

        .staff-page-header {
            align-items: flex-start;
        }

        .staff-filter-actions {
            width: 100%;
        }

        .staff-filter-btn {
            flex: 1;
        }

        .staff-reset-btn {
            width: 43px;
        }

    }


    @media (max-width: 768px) {

        .staff-page {
            padding-bottom: 20px;
        }

        .staff-page-header {
            flex-direction: column;
            padding: 18px;
        }

        .staff-page-heading {
            align-items: flex-start;
        }

        .staff-add-btn {
            width: 100%;
        }

        .staff-filter-header,
        .staff-filter-body {
            padding: 16px;
        }

        .staff-table-top {
            align-items: flex-start;
            flex-direction: column;
        }

        .staff-pagination {
            justify-content: center;
        }

    }

</style>

@endpush


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const salonSelect = document.getElementById('filter_salon_id');
    const branchSelect = document.getElementById('filter_branch_id');


    /* =========================================================
       SALON → BRANCH FILTER
    ========================================================= */

    function filterBranches() {

        if (!salonSelect || !branchSelect) {
            return;
        }

        const salonId = salonSelect.value;

        Array.from(branchSelect.options).forEach(function (option) {

            if (option.value === '') {

                option.hidden = false;

                return;
            }


            if (!salonId || option.dataset.salon === salonId) {

                option.hidden = false;

            } else {

                option.hidden = true;

                if (option.selected) {
                    option.selected = false;
                }

            }

        });

    }


    if (salonSelect && branchSelect) {

        salonSelect.addEventListener('change', function () {

            branchSelect.value = '';

            filterBranches();

        });

        filterBranches();

    }

});

</script>

@endpush

@endsection