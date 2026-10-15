@extends('admin.layout')

@section('title', 'Staff Attendance | Salon Management')

@section('page-title', 'Staff Attendance')

@section('content')

<div class="container-fluid px-0 staff-attendance-page">

    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show attendance-alert attendance-alert-success"
             role="alert">

            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show attendance-alert attendance-alert-danger"
             role="alert">

            <div class="d-flex align-items-start gap-2">

                <i class="bi bi-exclamation-triangle"></i>

                <div>

                    <strong>Please check:</strong>

                    <ul class="mb-0 mt-1">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="attendance-page-header">

        <div class="attendance-page-header-inner">

            <div class="attendance-title-wrap">

                <div class="attendance-title-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div>

                    <h4 class="fw-bold mb-1">
                        Staff Attendance
                    </h4>

                    <p class="text-muted mb-0">
                        Manage staff check-in, check-out and working hours.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TODAY'S ATTENDANCE
    ========================================================== --}}

    <div class="attendance-card today-attendance-card">

        <div class="attendance-card-header">

            <div class="attendance-section-title">

                <div class="attendance-section-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <h5 class="fw-bold mb-1">
                        Today's Attendance
                    </h5>

                    <small class="text-muted">
                        {{ now()->format('d M Y') }}
                    </small>

                </div>

            </div>


            <div class="attendance-count-badge">

                <strong>
                    {{ $staff->count() }}
                </strong>

                <span>
                    Staff
                </span>

            </div>

        </div>


        <div class="attendance-table-wrapper">

            <div class="table-responsive">

                <table class="table align-middle mb-0 attendance-table">

                    <thead>

                        <tr>

                            <th class="px-3">#</th>

                            <th>Staff</th>

                            <th>Check In</th>

                            <th>Check Out</th>

                            <th>Working Hours</th>

                            <th>Status</th>

                            <th class="text-center">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($todayStaffAttendance as $index => $item)

                            @php

                                $staffMember = $item['staff'];

                                $todayAttendance = $item['attendance'];

                                $status = $item['status'];

                            @endphp


                            <tr>

                                {{-- =================================================
                                    #
                                ================================================== --}}

                                <td class="px-3">

                                    <span class="attendance-row-number">
                                        {{ $index + 1 }}
                                    </span>

                                </td>


                                {{-- =================================================
                                    STAFF
                                ================================================== --}}

                                <td>

                                    <div class="attendance-staff-info">

                                        <div class="attendance-avatar">
                                            <i class="bi bi-person"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold">
                                                {{ $staffMember->name }}
                                            </div>

                                            @if(!empty($staffMember->designation))

                                                <small class="text-muted">
                                                    {{ $staffMember->designation }}
                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================================
                                    CHECK IN
                                ================================================== --}}

                                <td>

                                    @if($todayAttendance && $todayAttendance->check_in)

                                        <span class="attendance-time attendance-time-in">

                                            <i class="bi bi-box-arrow-in-right"></i>

                                            {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}

                                        </span>

                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    CHECK OUT
                                ================================================== --}}

                                <td>

                                    @if($todayAttendance && $todayAttendance->check_out)

                                        <span class="attendance-time attendance-time-out">

                                            <i class="bi bi-box-arrow-right"></i>

                                            {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('h:i A') }}

                                        </span>

                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    WORKING HOURS
                                ================================================== --}}

                                <td>

                                    @if($todayAttendance && $todayAttendance->working_hours)

                                        <span class="attendance-working-hours">

                                            <i class="bi bi-hourglass-split"></i>

                                            {{ $todayAttendance->working_hours }}

                                        </span>

                                    @elseif($status === 'working')

                                        <span class="attendance-working-now">
                                            Working
                                        </span>

                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @if($status === 'present')

                                        <span class="attendance-status status-present">

                                            <i class="bi bi-check-circle"></i>
                                            Full Day

                                        </span>

                                    @elseif($status === 'half_day')

                                        <span class="attendance-status status-half-day">

                                            <i class="bi bi-clock"></i>
                                            Half Day

                                        </span>

                                    @elseif($status === 'working')

                                        <span class="attendance-status status-working">

                                            <i class="bi bi-hourglass-split"></i>
                                            Working

                                        </span>

                                    @elseif($status === 'absent')

                                        <span class="attendance-status status-absent">

                                            <i class="bi bi-x-circle"></i>
                                            Absent

                                        </span>

                                    @elseif($status === 'leave')

                                        <span class="attendance-status status-leave">

                                            <i class="bi bi-calendar-x"></i>
                                            Leave

                                        </span>

                                    @else

                                        <span class="attendance-status status-default">

                                            {{ ucfirst(str_replace('_', ' ', $status)) }}

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTION
                                ================================================== --}}

                                <td class="text-center">

                                    {{-- ABSENT / NO ATTENDANCE --}}

                                    @if($status === 'absent')

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.staff.attendance.check-in',
                                                $staffMember->id
                                            ) }}"
                                            class="d-inline">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="attendance-action-btn attendance-check-in-btn">

                                                <i class="bi bi-box-arrow-in-right"></i>

                                                <span>Check In</span>

                                            </button>

                                        </form>


                                    {{-- WORKING --}}

                                    @elseif($status === 'working')

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.staff.attendance.check-out',
                                                $staffMember->id
                                            ) }}"
                                            class="d-inline">

                                            @csrf

                                            <button
                                                type="submit"
                                                class="attendance-action-btn attendance-check-out-btn">

                                                <i class="bi bi-box-arrow-right"></i>

                                                <span>Check Out</span>

                                            </button>

                                        </form>


                                    {{-- FULL DAY / HALF DAY --}}

                                    @elseif(
                                        $status === 'present' ||
                                        $status === 'half_day'
                                    )

                                        <span class="attendance-completed-badge">

                                            <i class="bi bi-check-circle"></i>

                                            Completed

                                        </span>


                                    {{-- LEAVE --}}

                                    @elseif($status === 'leave')

                                        <span class="attendance-leave-badge">

                                            <i class="bi bi-calendar-check"></i>

                                            Leave

                                        </span>


                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center attendance-empty-row">

                                    <div class="attendance-empty-icon">
                                        <i class="bi bi-people"></i>
                                    </div>

                                    <div class="fw-semibold mt-3">
                                        No active staff found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}

    <div class="attendance-card attendance-filter-card">

        <div class="attendance-card-header">

            <div class="attendance-section-title">

                <div class="attendance-section-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>

                    <h6 class="fw-bold mb-0">
                        Attendance History Filters
                    </h6>

                </div>

            </div>

        </div>


        <div class="attendance-filter-body">

            <form
                method="GET"
                action="{{ route('admin.staff.attendance.index') }}">

                <div class="row g-3">


                    {{-- DATE --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Date
                        </label>

                        <input
                            type="date"
                            name="attendance_date"
                            class="form-control attendance-form-control"
                            value="{{ request('attendance_date') }}"
                        >

                    </div>


                    {{-- STAFF --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Staff
                        </label>

                        <select
                            name="staff_id"
                            class="form-select attendance-form-control">

                            <option value="">
                                All Staff
                            </option>

                            @foreach($staff as $staffMember)

                                <option
                                    value="{{ $staffMember->id }}"
                                    @selected(request('staff_id') == $staffMember->id)
                                >
                                    {{ $staffMember->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select attendance-form-control">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="present"
                                @selected(request('status') === 'present')
                            >
                                Present
                            </option>

                            <option
                                value="absent"
                                @selected(request('status') === 'absent')
                            >
                                Absent
                            </option>

                            <option
                                value="half_day"
                                @selected(request('status') === 'half_day')
                            >
                                Half Day
                            </option>

                            <option
                                value="leave"
                                @selected(request('status') === 'leave')
                            >
                                Leave
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-md-3 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="attendance-filter-btn attendance-filter-primary">

                            <i class="bi bi-search"></i>

                            <span>Filter</span>

                        </button>


                        <a
                            href="{{ route('admin.staff.attendance.index') }}"
                            class="attendance-filter-btn attendance-filter-reset">

                            <i class="bi bi-arrow-clockwise"></i>

                            <span>Reset</span>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        ATTENDANCE HISTORY
    ========================================================== --}}

    <div class="attendance-card attendance-history-card">

        <div class="attendance-card-header">

            <div class="attendance-section-title">

                <div class="attendance-section-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div>

                    <h5 class="fw-bold mb-0">
                        Attendance History
                    </h5>

                </div>

            </div>

        </div>


        <div class="attendance-table-wrapper">

            <div class="table-responsive">

                <table class="table align-middle mb-0 attendance-history-table">

                    <thead>

                        <tr>

                            <th class="px-3">#</th>

                            <th>Date</th>

                            <th>Staff</th>

                            <th>Branch</th>

                            <th>Status</th>

                            <th>Check In</th>

                            <th>Check Out</th>

                            <th>Working Hours</th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($attendances as $index => $attendance)

                            <tr>


                                {{-- # --}}

                                <td class="px-3">

                                    <span class="attendance-row-number">
                                        {{ $attendances->firstItem() + $index }}
                                    </span>

                                </td>


                                {{-- DATE --}}

                                <td>

                                    <span class="attendance-date">
                                        {{ optional($attendance->attendance_date)->format('d M Y') }}
                                    </span>

                                </td>


                                {{-- STAFF --}}

                                <td>

                                    <div class="attendance-history-staff">

                                        <div class="attendance-small-avatar">
                                            <i class="bi bi-person"></i>
                                        </div>

                                        <div class="fw-semibold">
                                            {{ $attendance->staff->name ?? 'N/A' }}
                                        </div>

                                    </div>

                                </td>


                                {{-- BRANCH --}}

                                <td>

                                    <span class="attendance-branch">
                                        {{ $attendance->branch->name ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- STATUS --}}

                                <td>

                                    {{-- Working --}}

                                    @if(
                                        $attendance->check_in &&
                                        !$attendance->check_out
                                    )

                                        <span class="attendance-status status-working">

                                            <i class="bi bi-hourglass-split"></i>
                                            Working

                                        </span>


                                    {{-- Leave --}}

                                    @elseif($attendance->status === 'leave')

                                        <span class="attendance-status status-leave">

                                            <i class="bi bi-calendar-x"></i>
                                            Leave

                                        </span>


                                    {{-- Absent --}}

                                    @elseif($attendance->status === 'absent')

                                        <span class="attendance-status status-absent">

                                            <i class="bi bi-x-circle"></i>
                                            Absent

                                        </span>


                                    {{-- Half Day --}}

                                    @elseif($attendance->status === 'half_day')

                                        <span class="attendance-status status-half-day">

                                            <i class="bi bi-clock"></i>
                                            Half Day

                                        </span>


                                    {{-- Full Day --}}

                                    @elseif(
                                        $attendance->status === 'present' &&
                                        $attendance->check_in &&
                                        $attendance->check_out
                                    )

                                        <span class="attendance-status status-present">

                                            <i class="bi bi-check-circle"></i>
                                            Full Day

                                        </span>


                                    {{-- Present without checkout --}}

                                    @elseif($attendance->status === 'present')

                                        <span class="attendance-status status-working">
                                            Present
                                        </span>


                                    @else

                                        <span class="attendance-status status-default">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $attendance->status
                                                )
                                            ) }}

                                        </span>

                                    @endif

                                </td>


                                {{-- CHECK IN --}}

                                <td>

                                    @if($attendance->check_in)

                                        <span class="attendance-time attendance-time-in">

                                            <i class="bi bi-box-arrow-in-right"></i>

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $attendance->check_in
                                                )->format('h:i A')
                                            }}

                                        </span>

                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- CHECK OUT --}}

                                <td>

                                    @if($attendance->check_out)

                                        <span class="attendance-time attendance-time-out">

                                            <i class="bi bi-box-arrow-right"></i>

                                            {{
                                                \Carbon\Carbon::parse(
                                                    $attendance->check_out
                                                )->format('h:i A')
                                            }}

                                        </span>

                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- WORKING HOURS --}}

                                <td>

                                    @if($attendance->working_hours)

                                        <span class="attendance-working-hours">

                                            <i class="bi bi-hourglass-split"></i>

                                            {{ $attendance->working_hours }}

                                        </span>

                                    @else

                                        <span class="attendance-empty-value">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- ACTIONS --}}

                                <td class="text-center">

                                    <div class="attendance-actions">


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route(
                                                'admin.staff.attendance.edit',
                                                $attendance->id
                                            ) }}"
                                            class="attendance-icon-btn attendance-edit-btn"
                                            title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- DELETE --}}

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.staff.attendance.destroy',
                                                $attendance->id
                                            ) }}"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this attendance record?'
                                            );">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="attendance-icon-btn attendance-delete-btn"
                                                title="Delete">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center attendance-empty-row">

                                    <div class="attendance-empty-icon">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>

                                    <div class="fw-semibold mt-3">
                                        No attendance records found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if($attendances->hasPages())

            <div class="attendance-pagination">

                {{ $attendances->links() }}

            </div>

        @endif

    </div>

</div>

@endsection


{{-- =========================================================
    PAGE CSS
========================================================== --}}

@push('styles')

<style>

    /* =========================================================
       PAGE
    ========================================================= */

    .staff-attendance-page {
        padding-bottom: 25px;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .attendance-alert {
        position: relative;
        border: 0;
        border-radius: 16px;
        padding: 15px 48px 15px 18px;
        box-shadow:
            0 4px 8px rgba(45, 37, 41, .03),
            0 12px 28px rgba(45, 37, 41, .06);
    }

    .attendance-alert-success {
        background: #f5fbf7;
        border-left: 4px solid #198754;
    }

    .attendance-alert-danger {
        background: #fff7f7;
        border-left: 4px solid #dc3545;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================= */

    .attendance-page-header {
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

    .attendance-page-header::before {
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

    .attendance-page-header-inner {
        padding: 22px 24px;
    }

    .attendance-title-wrap {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .attendance-title-icon {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
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


    /* =========================================================
       COMMON CARD
    ========================================================= */

    .attendance-card {
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

    .attendance-card::before {
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


    /* =========================================================
       CARD HEADER
    ========================================================= */

    .attendance-card-header {
        padding: 19px 22px;
        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfafb
        );
        border-bottom: 1px solid #eee9eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .attendance-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .attendance-section-icon {
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
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 5px 12px rgba(139,94,109,.08);
    }

    .attendance-count-badge {
        min-height: 35px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 13px;
        border-radius: 10px;
        background: #faf8f9;
        border: 1px solid #e8e1e5;
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 3px 8px rgba(45,37,41,.03);
    }

    .attendance-count-badge strong {
        color: #8b5e6d;
    }

    .attendance-count-badge span {
        color: #6f666b;
    }


    /* =========================================================
       TABLE
    ========================================================= */

    .attendance-table-wrapper {
        background: #ffffff;
    }

    .attendance-table,
    .attendance-history-table {
        min-width: 950px;
    }

    .attendance-table th,
    .attendance-history-table th {
        white-space: nowrap;
        padding: 13px 12px;
        background: #faf8f9;
        border-bottom: 1px solid #e5dfe2;
        color: #4f464b;
        font-weight: 600;
    }

    .attendance-table td,
    .attendance-history-table td {
        padding: 13px 12px;
        white-space: nowrap;
        border-bottom: 1px solid #eee9eb;
        border-right: 1px solid #f0ebed;
        background: #ffffff;
    }

    .attendance-table tbody tr:hover td,
    .attendance-history-table tbody tr:hover td {
        background: #fdfafb;
    }

    .attendance-table tbody tr:last-child td,
    .attendance-history-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .attendance-row-number {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #faf5f7;
        color: #8b5e6d;
        border: 1px solid rgba(139,94,109,.08);
    }


    /* =========================================================
       STAFF
    ========================================================= */

    .attendance-staff-info,
    .attendance-history-staff {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .attendance-avatar {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #8b5e6d;
        background: linear-gradient(
            145deg,
            #f8eef2,
            #ffffff
        );
        border: 1px solid rgba(139,94,109,.10);
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 4px 10px rgba(139,94,109,.08);
    }

    .attendance-small-avatar {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: #8b5e6d;
        background: #faf5f7;
        border: 1px solid rgba(139,94,109,.08);
    }


    /* =========================================================
       TIME / HOURS
    ========================================================= */

    .attendance-time {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
    }

    .attendance-time-in {
        color: #198754;
    }

    .attendance-time-out {
        color: #b04b5b;
    }

    .attendance-working-hours {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #3f373b;
        font-weight: 700;
    }

    .attendance-working-now {
        color: #9a7510;
        font-weight: 600;
    }

    .attendance-empty-value {
        color: #aaa2a7;
    }

    .attendance-date {
        color: #4f464b;
    }

    .attendance-branch {
        color: #6f666b;
    }


    /* =========================================================
       STATUS BADGES
    ========================================================= */

    .attendance-status {
        min-height: 30px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 9px;
        border: 1px solid transparent;
        font-weight: 500;
    }

    .status-present {
        color: #176b45;
        background: #eef9f3;
        border-color: #ccebdd;
    }

    .status-half-day {
        color: #866512;
        background: #fff8e5;
        border-color: #f2dfaa;
    }

    .status-working {
        color: #655074;
        background: #f4eff7;
        border-color: #ded2e5;
    }

    .status-absent {
        color: #a63e4a;
        background: #fff0f1;
        border-color: #f2cdd1;
    }

    .status-leave {
        color: #5f596d;
        background: #f1eff8;
        border-color: #dcd8ea;
    }

    .status-default {
        color: #625b5f;
        background: #f5f3f4;
        border-color: #e5e0e3;
    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .attendance-action-btn {
        min-height: 38px;
        padding: 7px 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 10px;
        font-weight: 500;
        border: 1px solid transparent;
        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .attendance-check-in-btn {
        color: #ffffff;
        background: linear-gradient(
            135deg,
            #198754,
            #146c43
        );
        border-color: #146c43;
        box-shadow:
            0 4px 0 #0f5132,
            0 8px 16px rgba(25,135,84,.14);
    }

    .attendance-check-in-btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 6px 0 #0f5132,
            0 12px 22px rgba(25,135,84,.18);
    }

    .attendance-check-out-btn {
        color: #ffffff;
        background: linear-gradient(
            135deg,
            #b04b5b,
            #913b49
        );
        border-color: #913b49;
        box-shadow:
            0 4px 0 #71303a,
            0 8px 16px rgba(176,75,91,.14);
    }

    .attendance-check-out-btn:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 6px 0 #71303a,
            0 12px 22px rgba(176,75,91,.18);
    }

    .attendance-completed-badge,
    .attendance-leave-badge {
        min-height: 32px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 9px;
        font-weight: 500;
    }

    .attendance-completed-badge {
        color: #5e585c;
        background: #f4f2f3;
        border: 1px solid #e3dfe1;
    }

    .attendance-leave-badge {
        color: #5f596d;
        background: #f1eff8;
        border: 1px solid #dcd8ea;
    }


    /* =========================================================
       FILTER
    ========================================================= */

    .attendance-filter-body {
        padding: 22px;
    }

    .attendance-form-control {
        min-height: 44px;
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

    .attendance-form-control:focus {
        border-color: #8b5e6d;
        box-shadow:
            0 0 0 3px rgba(139,94,109,.10),
            inset 0 2px 5px rgba(45,37,41,.025);
    }

    .attendance-filter-btn {
        min-height: 44px;
        flex: 1;
        padding: 8px 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border-radius: 11px;
        text-decoration: none;
        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .attendance-filter-primary {
        color: #ffffff;
        background: linear-gradient(
            135deg,
            #8b5e6d,
            #76505e
        );
        border: 1px solid #76505e;
        box-shadow:
            0 4px 0 #62414d,
            0 8px 18px rgba(139,94,109,.15);
    }

    .attendance-filter-primary:hover {
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow:
            0 6px 0 #62414d,
            0 12px 22px rgba(139,94,109,.18);
    }

    .attendance-filter-reset {
        color: #4f464b;
        background: #ffffff;
        border: 1px solid #ded7db;
        box-shadow:
            0 3px 7px rgba(45,37,41,.04),
            0 8px 16px rgba(45,37,41,.04);
    }

    .attendance-filter-reset:hover {
        color: #8b5e6d;
        background: #ffffff;
        border-color: rgba(139,94,109,.30);
        transform: translateY(-2px);
    }


    /* =========================================================
       HISTORY ACTIONS
    ========================================================= */

    .attendance-actions {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
    }

    .attendance-icon-btn {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        text-decoration: none;
        background: #ffffff;
        transition:
            transform .18s ease,
            box-shadow .18s ease;
    }

    .attendance-edit-btn {
        color: #8b5e6d;
        border: 1px solid rgba(139,94,109,.25);
        box-shadow:
            0 3px 7px rgba(139,94,109,.06);
    }

    .attendance-edit-btn:hover {
        color: #76505e;
        background: #faf5f7;
        transform: translateY(-2px);
        box-shadow:
            0 6px 12px rgba(139,94,109,.10);
    }

    .attendance-delete-btn {
        color: #b04b5b;
        border: 1px solid rgba(176,75,91,.22);
        box-shadow:
            0 3px 7px rgba(176,75,91,.05);
    }

    .attendance-delete-btn:hover {
        color: #913b49;
        background: #fff5f6;
        transform: translateY(-2px);
        box-shadow:
            0 6px 12px rgba(176,75,91,.09);
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .attendance-empty-row {
        padding: 50px 20px !important;
        color: #7d7479;
    }

    .attendance-empty-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        color: #8b5e6d;
        background: #faf5f7;
        border: 1px solid rgba(139,94,109,.10);
        box-shadow:
            inset 0 1px 2px rgba(255,255,255,.9),
            0 7px 16px rgba(139,94,109,.08);
    }


    /* =========================================================
       PAGINATION
    ========================================================= */

    .attendance-pagination {
        padding: 17px 22px;
        background: #ffffff;
        border-top: 1px solid #eee9eb;
    }

    .attendance-pagination .pagination {
        margin-bottom: 0;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .attendance-page-header-inner {
            padding: 20px;
        }

        .attendance-card-header {
            padding: 18px;
        }

        .attendance-filter-body {
            padding: 20px;
        }

        .attendance-filter-btn {
            width: 100%;
        }

        .attendance-actions {
            justify-content: flex-start;
        }

        .attendance-table,
        .attendance-history-table {
            min-width: 950px;
        }

    }


    @media (max-width: 576px) {

        .attendance-page-header,
        .attendance-card {
            border-radius: 16px;
        }

        .attendance-title-wrap {
            align-items: flex-start;
            gap: 11px;
        }

        .attendance-title-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
        }

        .attendance-card-header {
            align-items: flex-start;
        }

        .attendance-section-title {
            align-items: flex-start;
        }

        .attendance-count-badge {
            width: 100%;
            justify-content: center;
        }

    }

</style>

@endpush