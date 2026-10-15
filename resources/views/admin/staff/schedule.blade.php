@extends('admin.layout')

@section('title', 'Staff Schedule | Salon Management')

@section('page-title', 'Staff Schedule')

@section('content')

<div class="container-fluid px-0 staff-schedule-page">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show staff-alert staff-alert-success"
             role="alert">

            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-check-circle"></i>

                <div class="flex-grow-1">
                    {{ session('success') }}
                </div>
            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- =========================================================
        ERROR MESSAGES
    ========================================================== --}}

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show staff-alert staff-alert-danger"
             role="alert">

            <div class="fw-bold mb-2 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle"></i>
                <span>Please fix the following errors:</span>
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>
    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="staff-page-header">

        <div class="staff-page-header-content">

            <div class="staff-page-title-wrap">

                <div class="staff-page-icon">
                    <i class="bi bi-calendar-week"></i>
                </div>

                <div>
                    <h4 class="fw-bold mb-1">
                        Staff Weekly Schedule
                    </h4>

                    <p class="text-muted mb-0">
                        Manage weekly working hours, break time and leave
                        for all staff members.
                    </p>
                </div>

            </div>


            <div class="staff-header-action">

                <a href="{{ route('admin.staff.index') }}"
                   class="staff-btn staff-btn-secondary">

                    <i class="bi bi-arrow-left"></i>

                    <span>Back to Staff</span>

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        NO STAFF
    ========================================================== --}}

    @if($staffMembers->isEmpty())

        <div class="staff-empty-card">

            <div class="staff-empty-icon">
                <i class="bi bi-people"></i>
            </div>

            <h5 class="mt-3 mb-2">
                No Staff Found
            </h5>

            <p class="text-muted mb-3">
                There are no staff members available
                for this branch.
            </p>

            <a href="{{ route('admin.staff.create') }}"
               class="staff-btn staff-btn-primary">

                <i class="bi bi-person-plus"></i>

                <span>Add Staff</span>

            </a>

        </div>

    @else


        {{-- =====================================================
            STAFF SEARCH / FILTER
        ====================================================== --}}

        <div class="staff-filter-card">

            <div class="staff-filter-body">

                <div class="row g-3 align-items-end">

                    {{-- SEARCH --}}

                    <div class="col-md-6">

                        <label for="staffSearch"
                               class="form-label fw-semibold">

                            <i class="bi bi-search me-1"></i>
                            Search Staff by Name

                        </label>

                        <div class="staff-search-wrapper">

                            <i class="bi bi-search staff-search-icon"></i>

                            <input
                                type="text"
                                id="staffSearch"
                                class="form-control staff-search-input"
                                placeholder="Enter staff name..."
                                autocomplete="off"
                            >

                        </div>

                    </div>


                    {{-- CLEAR --}}

                    <div class="col-md-3">

                        <button
                            type="button"
                            id="clearStaffSearch"
                            class="staff-btn staff-btn-secondary staff-clear-btn"
                        >

                            <i class="bi bi-x-circle"></i>

                            <span>Clear</span>

                        </button>

                    </div>


                    {{-- COUNT --}}

                    <div class="col-md-3 text-md-end">

                        <div class="staff-count-box">

                            <span class="text-muted">
                                Showing
                            </span>

                            <strong id="visibleStaffCount">
                                {{ $staffMembers->count() }}
                            </strong>

                            <span class="text-muted">
                                staff
                            </span>

                        </div>

                    </div>

                </div>


                {{-- NO SEARCH RESULT --}}

                <div
                    id="staffSearchNoResult"
                    class="staff-no-result d-none"
                >

                    <i class="bi bi-exclamation-circle"></i>

                    <span>
                        No staff found with this name.
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ALL STAFF FORM
        ====================================================== --}}

        <form method="POST"
              action="{{ route('admin.staff.schedule.storeAll') }}">

            @csrf


            {{-- =================================================
                STAFF LOOP
            ================================================== --}}

            @foreach($staffMembers as $staff)

                @php

                    $staffSchedules =
                        $allSchedules
                            ->get($staff->id, collect())
                            ->keyBy('day');

                @endphp


                {{-- =================================================
                    STAFF CARD
                ================================================== --}}

                <div
                    class="staff-schedule-card staff-schedule-item"
                    data-staff-name="{{ strtolower($staff->name) }}"
                >


                    {{-- STAFF HEADER --}}

                    <div class="staff-card-header">

                        <div class="staff-profile-wrap">

                            <div class="staff-avatar">

                                <i class="bi bi-person-fill"></i>

                            </div>


                            <div class="staff-profile-info">

                                <h5 class="fw-bold mb-0">
                                    {{ $staff->name }}
                                </h5>

                                <small class="text-muted">

                                    {{ $staff->role ?? 'Staff' }}

                                    @if($staff->branch)
                                        <span class="staff-meta-separator">•</span>
                                        {{ $staff->branch->name }}
                                    @endif

                                </small>

                            </div>

                        </div>


                        {{-- STAFF ID --}}

                        <div class="staff-id-badge">

                            <span>
                                Staff ID:
                            </span>

                            <strong>
                                {{ $staff->id }}
                            </strong>

                        </div>

                    </div>


                    {{-- STAFF SCHEDULE TABLE --}}

                    <div class="staff-table-wrapper">

                        <div class="table-responsive">

                            <table class="table align-middle mb-0 staff-schedule-table">

                                <thead>

                                    <tr>

                                        <th style="width: 13%;">
                                            Day
                                        </th>

                                        <th style="width: 12%;">
                                            Status
                                        </th>

                                        <th style="width: 14%;">
                                            Start Time
                                        </th>

                                        <th style="width: 14%;">
                                            End Time
                                        </th>

                                        <th style="width: 14%;">
                                            Break Start
                                        </th>

                                        <th style="width: 14%;">
                                            Break End
                                        </th>

                                        <th style="width: 19%;">
                                            Leave Reason
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($days as $day)

                                        @php

                                            $schedule =
                                                $staffSchedules->get($day);

                                            $status =
                                                old(
                                                    "schedule.$staff->id.$day.status",
                                                    $schedule?->status ?? 'working'
                                                );


                                            $startTime =
                                                $schedule?->start_time
                                                    ? \Carbon\Carbon::parse(
                                                        $schedule->start_time
                                                    )->format('H:i')
                                                    : '';


                                            $endTime =
                                                $schedule?->end_time
                                                    ? \Carbon\Carbon::parse(
                                                        $schedule->end_time
                                                    )->format('H:i')
                                                    : '';


                                            $breakStart =
                                                $schedule?->break_start
                                                    ? \Carbon\Carbon::parse(
                                                        $schedule->break_start
                                                    )->format('H:i')
                                                    : '';


                                            $breakEnd =
                                                $schedule?->break_end
                                                    ? \Carbon\Carbon::parse(
                                                        $schedule->break_end
                                                    )->format('H:i')
                                                    : '';


                                            $leaveReason =
                                                $schedule?->leave_reason ?? '';

                                        @endphp


                                        <tr>

                                            {{-- DAY --}}

                                            <td>

                                                <div class="staff-day-cell">

                                                    <span class="staff-day-dot"></span>

                                                    <span class="fw-semibold">
                                                        {{ $day }}
                                                    </span>

                                                </div>

                                            </td>


                                            {{-- STATUS --}}

                                            <td>

                                                <select
                                                    name="schedule[{{ $staff->id }}][{{ $day }}][status]"
                                                    class="form-select schedule-status staff-form-control"
                                                    data-staff="{{ $staff->id }}"
                                                    data-day="{{ $day }}"
                                                    required
                                                >

                                                    <option
                                                        value="working"
                                                        {{ $status == 'working' ? 'selected' : '' }}
                                                    >
                                                        Working
                                                    </option>

                                                    <option
                                                        value="leave"
                                                        {{ $status == 'leave' ? 'selected' : '' }}
                                                    >
                                                        Leave
                                                    </option>

                                                </select>

                                            </td>


                                            {{-- START TIME --}}

                                            <td>

                                                <input
                                                    type="time"
                                                    name="schedule[{{ $staff->id }}][{{ $day }}][start_time]"
                                                    class="form-control time-field
                                                           time-{{ $staff->id }}-{{ $day }}
                                                           staff-form-control"
                                                    value="{{ old("schedule.$staff->id.$day.start_time", $startTime) }}"
                                                >

                                            </td>


                                            {{-- END TIME --}}

                                            <td>

                                                <input
                                                    type="time"
                                                    name="schedule[{{ $staff->id }}][{{ $day }}][end_time]"
                                                    class="form-control time-field
                                                           time-{{ $staff->id }}-{{ $day }}
                                                           staff-form-control"
                                                    value="{{ old("schedule.$staff->id.$day.end_time", $endTime) }}"
                                                >

                                            </td>


                                            {{-- BREAK START --}}

                                            <td>

                                                <input
                                                    type="time"
                                                    name="schedule[{{ $staff->id }}][{{ $day }}][break_start]"
                                                    class="form-control time-field
                                                           time-{{ $staff->id }}-{{ $day }}
                                                           staff-form-control"
                                                    value="{{ old("schedule.$staff->id.$day.break_start", $breakStart) }}"
                                                >

                                            </td>


                                            {{-- BREAK END --}}

                                            <td>

                                                <input
                                                    type="time"
                                                    name="schedule[{{ $staff->id }}][{{ $day }}][break_end]"
                                                    class="form-control time-field
                                                           time-{{ $staff->id }}-{{ $day }}
                                                           staff-form-control"
                                                    value="{{ old("schedule.$staff->id.$day.break_end", $breakEnd) }}"
                                                >

                                            </td>


                                            {{-- LEAVE REASON --}}

                                            <td>

                                                <input
                                                    type="text"
                                                    name="schedule[{{ $staff->id }}][{{ $day }}][leave_reason]"
                                                    class="form-control leave-reason-field
                                                           leave-reason-{{ $staff->id }}-{{ $day }}
                                                           staff-form-control"
                                                    value="{{ old("schedule.$staff->id.$day.leave_reason", $leaveReason) }}"
                                                    placeholder="Reason for leave"
                                                >

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            @endforeach


            {{-- =====================================================
                SAVE ALL
            ====================================================== --}}

            <div class="staff-save-card">

                <div class="staff-save-content">

                    <div class="staff-save-info">

                        <div class="staff-info-icon">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div>

                            <h6 class="fw-bold mb-1">
                                Schedule Information
                            </h6>

                            <small class="text-muted">
                                Working days require Start Time and End Time.
                                Leave days do not require working hours.
                            </small>

                        </div>

                    </div>


                    <div class="staff-action-buttons">

                        <a
                            href="{{ route('admin.staff.index') }}"
                            class="staff-btn staff-btn-secondary"
                        >

                            <i class="bi bi-x-circle"></i>

                            <span>Cancel</span>

                        </a>


                        <button
                            type="submit"
                            class="staff-btn staff-btn-primary"
                        >

                            <i class="bi bi-check-circle"></i>

                            <span>
                                Save All Staff Schedules
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </form>

    @endif

</div>

@endsection


{{-- =============================================================
    PAGE STYLES
============================================================= --}}

@push('styles')

<style>

    /* =========================================================
       PAGE BASE
    ========================================================= */

    .staff-schedule-page {
        padding-bottom: 25px;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .staff-alert {
        position: relative;
        border: 0;
        border-radius: 16px;
        padding: 15px 48px 15px 18px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .06);
    }

    .staff-alert-success {
        background: #f5fbf7;
        border-left: 4px solid #198754;
    }

    .staff-alert-danger {
        background: #fff7f7;
        border-left: 4px solid #dc3545;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .staff-page-header {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid rgba(45, 37, 41, .06);
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .07),
            0 25px 55px rgba(45, 37, 41, .05);
    }

    .staff-page-header::before {
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

    .staff-page-header-content {
        padding: 22px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .staff-page-title-wrap {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .staff-page-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(
            145deg,
            #f8eef2,
            #ffffff
        );
        color: #8b5e6d;
        border: 1px solid rgba(139, 94, 109, .10);
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 5px 12px rgba(139,94,109,.10);
    }


    /* =========================================================
       BUTTONS
    ========================================================= */

    .staff-btn {
        min-height: 43px;
        padding: 9px 16px;
        border-radius: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        transition:
            transform .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .staff-btn i {
        line-height: 1;
    }

    .staff-btn-primary {
        color: #ffffff;
        border: 1px solid #76505e;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        box-shadow:
            0 5px 0 #62414d,
            0 10px 22px rgba(139,94,109,.18);
    }

    .staff-btn-primary:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 7px 0 #62414d,
            0 14px 28px rgba(139,94,109,.20);
    }

    .staff-btn-primary:active {
        transform: translateY(3px);
        box-shadow:
            0 2px 0 #62414d,
            0 5px 12px rgba(139,94,109,.14);
    }

    .staff-btn-secondary {
        color: #2d2529;
        background: #ffffff;
        border: 1px solid #e2dce0;
        box-shadow:
            0 3px 7px rgba(45,37,41,.04),
            0 8px 18px rgba(45,37,41,.05);
    }

    .staff-btn-secondary:hover {
        color: #8b5e6d;
        background: #ffffff;
        border-color: rgba(139,94,109,.30);
        transform: translateY(-2px);
        box-shadow:
            0 5px 10px rgba(45,37,41,.05),
            0 12px 24px rgba(139,94,109,.08);
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .staff-empty-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid rgba(45,37,41,.06);
        border-radius: 20px;
        padding: 55px 25px;
        text-align: center;
        box-shadow:
            0 4px 8px rgba(45,37,41,.03),
            0 12px 28px rgba(45,37,41,.07),
            0 25px 55px rgba(45,37,41,.05);
    }

    .staff-empty-card::before {
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

    .staff-empty-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        color: #8b5e6d;
        background: #faf5f7;
        border: 1px solid rgba(139,94,109,.10);
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 8px 20px rgba(139,94,109,.10);
    }


    /* =========================================================
       SEARCH / FILTER CARD
    ========================================================= */

    .staff-filter-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid rgba(45,37,41,.06);
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow:
            0 4px 8px rgba(45,37,41,.03),
            0 12px 28px rgba(45,37,41,.07),
            0 25px 55px rgba(45,37,41,.05);
    }

    .staff-filter-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }

    .staff-filter-body {
        padding: 22px 24px;
    }

    .staff-schedule-page .form-label {
        margin-bottom: 7px;
    }

    .staff-search-wrapper {
        position: relative;
    }

    .staff-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        color: #8b5e6d;
        pointer-events: none;
    }

    #staffSearch {
        min-height: 44px;
        padding-left: 40px;
        border-radius: 11px;
        border: 1px solid #ddd5da;
        background: #ffffff;
        box-shadow:
            inset 0 2px 5px rgba(45,37,41,.025),
            0 3px 8px rgba(45,37,41,.025);
        transition:
            border-color .18s ease,
            box-shadow .18s ease;
    }

    #staffSearch:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139,94,109,.10),
            inset 0 2px 5px rgba(45,37,41,.025);
    }

    .staff-clear-btn {
        min-height: 44px;
        width: 100%;
    }

    .staff-count-box {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: flex-end;
        gap: 5px;
        padding: 0 14px;
        border-radius: 11px;
        background: #faf8f9;
        border: 1px solid #eee8eb;
    }

    #visibleStaffCount {
        color: #8b5e6d;
    }

    .staff-no-result {
        margin-top: 16px;
        margin-bottom: 0;
        padding: 12px 15px;
        border-radius: 12px;
        background: #fffaf0;
        border: 1px solid #f2dfb2;
        color: #7a5b16;
        display: flex;
        align-items: center;
        gap: 8px;
    }


    /* =========================================================
       STAFF CARD
    ========================================================= */

    .staff-schedule-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid rgba(45,37,41,.06);
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow:
            0 4px 8px rgba(45,37,41,.03),
            0 12px 28px rgba(45,37,41,.07),
            0 25px 55px rgba(45,37,41,.05);
        transition:
            transform .2s ease,
            box-shadow .2s ease;
    }

    .staff-schedule-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
        z-index: 2;
    }

    .staff-schedule-card:hover {
        transform: translateY(-2px);
        box-shadow:
            0 5px 10px rgba(45,37,41,.04),
            0 16px 34px rgba(45,37,41,.08),
            0 28px 60px rgba(45,37,41,.05);
    }


    /* =========================================================
       STAFF HEADER
    ========================================================= */

    .staff-card-header {
        padding: 19px 22px;
        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfafb
        );
        border-bottom: 1px solid #eee9eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .staff-profile-wrap {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .staff-avatar {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8b5e6d;
        background: linear-gradient(
            145deg,
            #f8eef2,
            #ffffff
        );
        border: 1px solid rgba(139,94,109,.10);
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 5px 12px rgba(139,94,109,.10);
    }

    .staff-profile-info small {
        display: block;
        margin-top: 4px;
    }

    .staff-meta-separator {
        margin: 0 5px;
        color: #aaa0a5;
    }

    .staff-id-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        min-height: 34px;
        padding: 6px 12px;
        border-radius: 10px;
        background: #faf8f9;
        border: 1px solid #e8e1e5;
        color: #6f666b;
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 3px 8px rgba(45,37,41,.03);
    }

    .staff-id-badge strong {
        color: #2d2529;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .staff-table-wrapper {
        background: #ffffff;
    }

    .staff-schedule-table {
        min-width: 1000px;
        margin-bottom: 0;
    }

    .staff-schedule-table th,
    .staff-schedule-table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .staff-schedule-table thead th {
        padding: 13px 12px;
        background: #faf8f9;
        border-bottom: 1px solid #e5dfe2;
        color: #4f464b;
        font-weight: 600;
    }

    .staff-schedule-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #eee9eb;
        border-right: 1px solid #f0ebed;
        background: #ffffff;
    }

    .staff-schedule-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .staff-schedule-table tbody tr:hover td {
        background: #fdfafb;
    }

    .staff-day-cell {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .staff-day-dot {
        width: 7px;
        height: 7px;
        flex: 0 0 7px;
        border-radius: 50%;
        background: #8b5e6d;
        box-shadow: 0 0 0 4px rgba(139,94,109,.08);
    }


    /* =========================================================
       FORM CONTROLS
    ========================================================= */

    .staff-form-control {
        min-height: 42px;
        min-width: 120px;
        border-radius: 10px;
        border: 1px solid #ddd5da;
        background: #ffffff;
        box-shadow:
            inset 0 2px 5px rgba(45,37,41,.025),
            0 3px 7px rgba(45,37,41,.025);
        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .staff-form-control:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139,94,109,.10),
            inset 0 2px 5px rgba(45,37,41,.025);
    }

    .staff-form-control:disabled {
        background: #f4f1f3;
        color: #aaa2a7;
        border-color: #e5dfe2;
        box-shadow: inset 0 2px 5px rgba(45,37,41,.03);
        cursor: not-allowed;
    }

    .staff-form-control::placeholder {
        color: #aaa2a7;
    }


    /* =========================================================
       SAVE CARD
    ========================================================= */

    .staff-save-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid rgba(45,37,41,.06);
        border-radius: 20px;
        margin-bottom: 24px;
        box-shadow:
            0 4px 8px rgba(45,37,41,.03),
            0 12px 28px rgba(45,37,41,.07),
            0 25px 55px rgba(45,37,41,.05);
    }

    .staff-save-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(
            90deg,
            #8b5e6d,
            #b78b99,
            #8b5e6d
        );
    }

    .staff-save-content {
        padding: 20px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .staff-save-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .staff-info-icon {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #8b5e6d;
        background: #faf5f7;
        border: 1px solid rgba(139,94,109,.10);
    }

    .staff-action-buttons {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .staff-page-header-content {
            padding: 20px;
        }

        .staff-page-title-wrap {
            align-items: flex-start;
        }

        .staff-header-action,
        .staff-header-action .staff-btn {
            width: 100%;
        }

        .staff-filter-body {
            padding: 20px;
        }

        .staff-count-box {
            justify-content: flex-start;
            width: 100%;
        }

        .staff-card-header {
            padding: 18px;
        }

        .staff-save-content {
            padding: 18px;
            align-items: stretch;
        }

        .staff-save-info {
            align-items: flex-start;
        }

        .staff-action-buttons {
            width: 100%;
            flex-direction: column;
        }

        .staff-action-buttons .staff-btn {
            width: 100%;
        }

        .staff-schedule-table {
            min-width: 1000px;
        }
    }


    @media (max-width: 576px) {

        .staff-page-header {
            border-radius: 16px;
        }

        .staff-filter-card,
        .staff-schedule-card,
        .staff-save-card,
        .staff-empty-card {
            border-radius: 16px;
        }

        .staff-page-header-content {
            padding: 18px;
        }

        .staff-page-title-wrap {
            gap: 11px;
        }

        .staff-page-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
        }

        .staff-card-header {
            align-items: flex-start;
        }

        .staff-id-badge {
            width: 100%;
            justify-content: center;
        }

        .staff-profile-wrap {
            width: 100%;
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


    /* =========================================================
       SCHEDULE STATUS TOGGLE
    ========================================================= */

    const statusSelects =
        document.querySelectorAll('.schedule-status');


    function toggleScheduleFields(select) {

        const staffId =
            select.dataset.staff;

        const day =
            select.dataset.day;

        const status =
            select.value;


        const timeFields =
            document.querySelectorAll(
                '.time-' + staffId + '-' + day
            );


        const leaveReason =
            document.querySelector(
                '.leave-reason-' + staffId + '-' + day
            );


        /*
         * LEAVE
         */

        if (status === 'leave') {

            timeFields.forEach(function (field) {

                field.disabled = true;

                field.value = '';

            });


            if (leaveReason) {

                leaveReason.disabled = false;

            }

        }


        /*
         * WORKING
         */

        else {

            timeFields.forEach(function (field) {

                field.disabled = false;

            });


            if (leaveReason) {

                leaveReason.disabled = true;

                leaveReason.value = '';

            }

        }

    }


    /*
     * INITIAL STATE
     */

    statusSelects.forEach(function (select) {

        toggleScheduleFields(select);


        select.addEventListener(
            'change',
            function () {

                toggleScheduleFields(this);

            }
        );

    });



    /* =========================================================
       STAFF NAME SEARCH
    ========================================================= */

    const searchInput =
        document.getElementById('staffSearch');


    const clearButton =
        document.getElementById('clearStaffSearch');


    const staffItems =
        document.querySelectorAll('.staff-schedule-item');


    const visibleCount =
        document.getElementById('visibleStaffCount');


    const noResult =
        document.getElementById('staffSearchNoResult');


    function filterStaff() {

        if (!searchInput) {
            return;
        }


        const searchValue =
            searchInput.value
                .trim()
                .toLowerCase();


        let count = 0;


        staffItems.forEach(function (item) {

            const staffName =
                (
                    item.dataset.staffName || ''
                ).toLowerCase();


            if (
                searchValue === '' ||
                staffName.includes(searchValue)
            ) {

                item.style.display = '';

                count++;

            }

            else {

                item.style.display = 'none';

            }

        });


        /*
         * UPDATE COUNT
         */

        if (visibleCount) {

            visibleCount.textContent = count;

        }


        /*
         * NO RESULT
         */

        if (noResult) {

            if (
                count === 0 &&
                searchValue !== ''
            ) {

                noResult.classList.remove('d-none');

            }

            else {

                noResult.classList.add('d-none');

            }

        }

    }


    /*
     * SEARCH WHILE TYPING
     */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            filterStaff
        );

    }


    /*
     * CLEAR SEARCH
     */

    if (clearButton) {

        clearButton.addEventListener(
            'click',
            function () {

                if (searchInput) {

                    searchInput.value = '';

                    filterStaff();

                    searchInput.focus();

                }

            }
        );

    }

});

</script>

@endpush