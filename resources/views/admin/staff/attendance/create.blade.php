@extends('admin.layout')

@section('title', 'Staff Attendance | Salon Management')

@section('page-title', 'Staff Attendance')

@section('content')

<div class="container-fluid px-0">

```
{{-- =========================================================
     SUCCESS MESSAGE
========================================================== --}}

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}

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
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <i class="bi bi-exclamation-triangle me-2"></i>

        <strong>Please check:</strong>

        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
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
     PAGE HEADER
========================================================== --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-calendar-check me-2"></i>
            Staff Attendance
        </h4>

        <p class="text-muted mb-0">
            Manage staff check-in, check-out and working hours.
        </p>
    </div>

    

</div>


{{-- =========================================================
     TODAY'S ATTENDANCE
========================================================== --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-clock-history me-2"></i>
                    Today's Attendance
                </h5>

                <small class="text-muted">
                    {{ now()->format('d M Y') }}
                </small>

            </div>

            <span class="badge bg-light text-dark border">
                {{ $staff->count() }} Staff
            </span>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-3">#</th>

                        <th>Staff</th>

                        <th>Check In</th>

                        <th>Check Out</th>

                        <th>Working Hours</th>

                        <th>Attendance</th>

                        <th class="text-center">Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($staff as $index => $staffMember)

                        @php
                            $todayAttendance =
                                $todayAttendances->get($staffMember->id);
                        @endphp

                        <tr>

                            {{-- # --}}

                            <td class="px-3">
                                {{ $index + 1 }}
                            </td>


                            {{-- STAFF --}}

                            <td>

                                <div class="d-flex align-items-center">

                                    <div
                                        class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2"
                                        style="width:40px;height:40px;">

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


                            {{-- CHECK IN --}}

                            <td>

                                @if($todayAttendance?->check_in)

                                    <span class="text-success fw-semibold">

                                        <i class="bi bi-box-arrow-in-right me-1"></i>

                                        {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('h:i A') }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- CHECK OUT --}}

                            <td>

                                @if($todayAttendance?->check_out)

                                    <span class="text-danger fw-semibold">

                                        <i class="bi bi-box-arrow-right me-1"></i>

                                        {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('h:i A') }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- WORKING HOURS --}}

                            <td>

                                @if($todayAttendance?->working_hours)

                                    <span class="badge bg-light text-dark border fw-semibold">

                                        <i class="bi bi-hourglass-split me-1"></i>

                                        {{ $todayAttendance->working_hours }}

                                    </span>

                                @elseif($todayAttendance?->check_in)

                                    <span class="text-warning fw-semibold">

                                        <i class="bi bi-hourglass-split me-1"></i>

                                        Working

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ATTENDANCE --}}

                            <td>

                                @if(!$todayAttendance)

                                    <span class="badge bg-secondary">
                                        Not Checked In
                                    </span>

                                @elseif(
                                    $todayAttendance->check_in &&
                                    !$todayAttendance->check_out
                                )

                                    <span class="badge bg-warning text-dark">

                                        <i class="bi bi-hourglass-split me-1"></i>

                                        Working

                                    </span>

                                @elseif($todayAttendance->status === 'absent')

                                    <span class="badge bg-danger">

                                        <i class="bi bi-x-circle me-1"></i>

                                        Absent

                                    </span>

                                @elseif($todayAttendance->status === 'leave')

                                    <span class="badge bg-info">

                                        <i class="bi bi-calendar-event me-1"></i>

                                        Leave

                                    </span>

                                @elseif($todayAttendance->status === 'half_day')

                                    <span class="badge bg-warning text-dark">

                                        <i class="bi bi-clock me-1"></i>

                                        Half Day

                                    </span>

                                @elseif(
                                    $todayAttendance->status === 'present' &&
                                    $todayAttendance->check_in &&
                                    $todayAttendance->check_out
                                )

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Full Day

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        {{ ucfirst($todayAttendance->status) }}

                                    </span>

                                @endif

                            </td>


                            {{-- ACTION --}}

                            <td class="text-center">

                                {{-- NO ATTENDANCE --}}

                                @if(!$todayAttendance)

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
                                            class="btn btn-success btn-sm px-3">

                                            <i class="bi bi-box-arrow-in-right me-1"></i>

                                            Check In

                                        </button>

                                    </form>


                                {{-- WORKING --}}

                                @elseif(
                                    $todayAttendance->check_in &&
                                    !$todayAttendance->check_out
                                )

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
                                            class="btn btn-danger btn-sm px-3">

                                            <i class="bi bi-box-arrow-right me-1"></i>

                                            Check Out

                                        </button>

                                    </form>


                                {{-- COMPLETED --}}

                                @elseif(
                                    $todayAttendance->check_in &&
                                    $todayAttendance->check_out
                                )

                                    <span class="badge bg-success px-3 py-2">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Completed

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-people fs-1 d-block mb-2"></i>

                                No active staff found.

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

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 py-3">

        <h6 class="fw-bold mb-0">

            <i class="bi bi-funnel me-2"></i>

            Attendance History Filters

        </h6>

    </div>


    <div class="card-body">

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
                        class="form-control"
                        value="{{ request('attendance_date') }}">

                </div>


                {{-- STAFF --}}

                <div class="col-md-3">

                    <label class="form-label fw-semibold">
                        Staff
                    </label>

                    <select
                        name="staff_id"
                        class="form-select">

                        <option value="">
                            All Staff
                        </option>

                        @foreach($staff as $staffMember)

                            <option
                                value="{{ $staffMember->id }}"
                                @selected(request('staff_id') == $staffMember->id)>

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
                        class="form-select">

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="present"
                            @selected(request('status') === 'present')>

                            Full Day

                        </option>

                        <option
                            value="absent"
                            @selected(request('status') === 'absent')>

                            Absent

                        </option>

                        <option
                            value="half_day"
                            @selected(request('status') === 'half_day')>

                            Half Day

                        </option>

                        <option
                            value="leave"
                            @selected(request('status') === 'leave')>

                            Leave

                        </option>

                    </select>

                </div>


                {{-- BUTTONS --}}

                <div class="col-md-3 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="bi bi-search me-1"></i>

                        Filter

                    </button>


                    <a
                        href="{{ route('admin.staff.attendance.index') }}"
                        class="btn btn-light border">

                        <i class="bi bi-arrow-clockwise"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     ATTENDANCE HISTORY
========================================================== --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <h5 class="fw-bold mb-0">

            <i class="bi bi-clock-history me-2"></i>

            Attendance History

        </h5>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-3">#</th>

                        <th>Date</th>

                        <th>Staff</th>

                        <th>Branch</th>

                        <th>Attendance</th>

                        <th>Check In</th>

                        <th>Check Out</th>

                        <th>Working Hours</th>

                        <th>Notes</th>

                        <th class="text-center">Actions</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($attendances as $index => $attendance)

                        <tr>

                            {{-- # --}}

                            <td class="px-3">

                                {{
                                    $attendances->firstItem() + $index
                                }}

                            </td>


                            {{-- DATE --}}

                            <td>

                                {{
                                    optional(
                                        $attendance->attendance_date
                                    )->format('d M Y')
                                }}

                            </td>


                            {{-- STAFF --}}

                            <td>

                                <div class="fw-semibold">

                                    {{
                                        $attendance->staff->name
                                        ?? 'N/A'
                                    }}

                                </div>

                            </td>


                            {{-- BRANCH --}}

                            <td>

                                {{
                                    $attendance->branch->name
                                    ?? 'N/A'
                                }}

                            </td>


                            {{-- ATTENDANCE --}}

                            <td>

                                @if(
                                    $attendance->check_in &&
                                    !$attendance->check_out
                                )

                                    <span class="badge bg-warning text-dark">

                                        <i class="bi bi-hourglass-split me-1"></i>

                                        Working

                                    </span>

                                @elseif($attendance->status === 'absent')

                                    <span class="badge bg-danger">

                                        <i class="bi bi-x-circle me-1"></i>

                                        Absent

                                    </span>

                                @elseif($attendance->status === 'leave')

                                    <span class="badge bg-info">

                                        <i class="bi bi-calendar-event me-1"></i>

                                        Leave

                                    </span>

                                @elseif($attendance->status === 'half_day')

                                    <span class="badge bg-warning text-dark">

                                        <i class="bi bi-clock me-1"></i>

                                        Half Day

                                    </span>

                                @elseif(
                                    $attendance->status === 'present' &&
                                    $attendance->check_in &&
                                    $attendance->check_out
                                )

                                    <span class="badge bg-success">

                                        <i class="bi bi-check-circle me-1"></i>

                                        Full Day

                                    </span>

                                @elseif($attendance->status === 'present')

                                    <span class="badge bg-primary">

                                        Present

                                    </span>

                                @else

                                    <span class="badge bg-secondary">

                                        {{ ucfirst($attendance->status) }}

                                    </span>

                                @endif

                            </td>


                            {{-- CHECK IN --}}

                            <td>

                                @if($attendance->check_in)

                                    <span class="text-success fw-semibold">

                                        {{
                                            \Carbon\Carbon::parse(
                                                $attendance->check_in
                                            )->format('h:i A')
                                        }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- CHECK OUT --}}

                            <td>

                                @if($attendance->check_out)

                                    <span class="text-danger fw-semibold">

                                        {{
                                            \Carbon\Carbon::parse(
                                                $attendance->check_out
                                            )->format('h:i A')
                                        }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- WORKING HOURS --}}

                            <td>

                                @if($attendance->working_hours)

                                    <span class="badge bg-light text-dark border fw-bold">

                                        <i class="bi bi-hourglass-split me-1"></i>

                                        {{ $attendance->working_hours }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- NOTES --}}

                            <td>

                                @if($attendance->notes)

                                    <span title="{{ $attendance->notes }}">

                                        {{
                                            \Illuminate\Support\Str::limit(
                                                $attendance->notes,
                                                30
                                            )
                                        }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- ACTIONS --}}

                            <td class="text-center">

                                <div class="d-flex justify-content-center gap-1">

                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route(
                                            'admin.staff.attendance.edit',
                                            $attendance->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
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
                                            class="btn btn-sm btn-outline-danger"
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
                                colspan="10"
                                class="text-center py-5 text-muted">

                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>

                                No attendance records found.

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

        <div class="card-footer bg-white border-0">

            {{ $attendances->links() }}

        </div>

    @endif

</div>
```

</div>

{{-- =========================================================
PAGE CSS
========================================================== --}}

<style>

    .table th {
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
    }

    .table td {
        font-size: 14px;
        vertical-align: middle;
    }

    .badge {
        font-weight: 500;
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 7px;
    }

</style>

@endsection
