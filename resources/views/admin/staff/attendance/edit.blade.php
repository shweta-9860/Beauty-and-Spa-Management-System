@extends('admin.layout')

@section('title', 'Edit Attendance | Salon Management')

@section('page-title', 'Edit Attendance')

@section('content')

<div class="container-fluid px-0">

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Staff Attendance
            </h4>

            <p class="text-muted mb-0">
                Update attendance record
            </p>
        </div>

        <a href="{{ route('admin.staff.attendance.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back

        </a>

    </div>


    {{-- Branch Information --}}
    @if(auth()->check() && !empty(auth()->user()->branch_id))

        <div class="alert alert-info border-0 shadow-sm mb-4">

            <div class="d-flex align-items-center">

                <i class="bi bi-diagram-3 fs-4 me-3"></i>

                <div>

                    <strong>Current Branch</strong>

                    <div class="small mt-1">
                        {{ auth()->user()->branch?->name ?? 'Current Branch' }}
                    </div>

                </div>

            </div>

        </div>

    @endif


    {{-- Form --}}
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white py-3">

            <h6 class="mb-0 fw-bold">
                <i class="bi bi-calendar-check me-2"></i>
                Attendance Details
            </h6>

        </div>

        <div class="card-body">

            <form method="POST"
                  action="{{ route('admin.staff.attendance.update', $attendance->id) }}">

                @csrf
                @method('PUT')

                <div class="row g-4">

                    {{-- Staff --}}
                    <div class="col-md-6">

                        <label for="staff_id"
                               class="form-label fw-semibold">

                            Staff
                            <span class="text-danger">*</span>

                        </label>

                        <select name="staff_id"
                                id="staff_id"
                                class="form-select @error('staff_id') is-invalid @enderror"
                                required>

                            <option value="">
                                Select Staff
                            </option>

                            @foreach($staff as $member)

                                <option value="{{ $member->id }}"
                                    {{ old('staff_id', $attendance->staff_id) == $member->id ? 'selected' : '' }}>

                                    {{ $member->name }}

                                    @if($member->role)
                                        — {{ $member->role }}
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('staff_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Date --}}
                    <div class="col-md-6">

                        <label for="attendance_date"
                               class="form-label fw-semibold">

                            Attendance Date
                            <span class="text-danger">*</span>

                        </label>

                        <input type="date"
                               name="attendance_date"
                               id="attendance_date"
                               class="form-control @error('attendance_date') is-invalid @enderror"
                               value="{{ old('attendance_date', $attendance->attendance_date?->format('Y-m-d')) }}"
                               required>

                        @error('attendance_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label for="status"
                               class="form-label fw-semibold">

                            Attendance Status
                            <span class="text-danger">*</span>

                        </label>

                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                            <option value="present"
                                {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>
                                Present
                            </option>

                            <option value="absent"
                                {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>
                                Absent
                            </option>

                            <option value="half_day"
                                {{ old('status', $attendance->status) === 'half_day' ? 'selected' : '' }}>
                                Half Day
                            </option>

                            <option value="leave"
                                {{ old('status', $attendance->status) === 'leave' ? 'selected' : '' }}>
                                Leave
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Check In --}}
                    <div class="col-md-3">

                        <label for="check_in"
                               class="form-label fw-semibold">

                            Check In

                        </label>

                        <input type="time"
                               name="check_in"
                               id="check_in"
                               class="form-control @error('check_in') is-invalid @enderror"
                               value="{{ old('check_in', $attendance->check_in) }}">

                        @error('check_in')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Check Out --}}
                    <div class="col-md-3">

                        <label for="check_out"
                               class="form-label fw-semibold">

                            Check Out

                        </label>

                        <input type="time"
                               name="check_out"
                               id="check_out"
                               class="form-control @error('check_out') is-invalid @enderror"
                               value="{{ old('check_out', $attendance->check_out) }}">

                        @error('check_out')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Notes --}}
                    <div class="col-12">

                        <label for="notes"
                               class="form-label fw-semibold">

                            Notes

                        </label>

                        <textarea name="notes"
                                  id="notes"
                                  rows="4"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Enter attendance notes...">{{ old('notes', $attendance->notes) }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">

                    <a href="{{ route('admin.staff.attendance.index') }}"
                       class="btn btn-outline-secondary">

                        <i class="bi bi-x-circle me-1"></i>
                        Cancel

                    </a>

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-check-circle me-1"></i>
                        Update Attendance

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- Status JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const status = document.getElementById('status');
    const checkIn = document.getElementById('check_in');
    const checkOut = document.getElementById('check_out');

    function updateTimeFields() {

        if (!status || !checkIn || !checkOut) {
            return;
        }

        const disabled =
            status.value === 'absent' ||
            status.value === 'leave';

        checkIn.disabled = disabled;
        checkOut.disabled = disabled;

        if (disabled) {
            checkIn.value = '';
            checkOut.value = '';
        }
    }

    if (status) {
        status.addEventListener('change', updateTimeFields);
        updateTimeFields();
    }

});
</script>

@endsection