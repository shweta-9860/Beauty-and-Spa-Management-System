@extends('admin.layout')

@section('title', 'Edit Appointment | Salon Management')

@section('page-title', 'Edit Appointment')

@section('content')

<div class="appointment-edit-3d">

    {{-- =========================================================
         HERO HEADER
    ========================================================== --}}
    <div class="appointment-hero-3d mb-4">

        <div class="hero-content-3d">

            <div class="hero-icon-3d">
                <i class="bi bi-calendar2-check"></i>
            </div>

            <div>
                <div class="hero-eyebrow">
                    APPOINTMENT MANAGEMENT
                </div>

                <h2 class="hero-title">
                    Edit Appointment
                </h2>

                <p class="hero-subtitle mb-0">
                    Update appointment details, schedule and status.
                </p>
            </div>

        </div>

        <a href="{{ route('admin.appointments.index') }}"
           class="back-button-3d">

            <i class="bi bi-arrow-left"></i>

            <span>Back to Appointments</span>

        </a>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="message-card-3d message-error-3d mb-4"
             role="alert">

            <div class="message-icon-3d">
                <i class="bi bi-exclamation-triangle"></i>
            </div>

            <div class="flex-grow-1">

                <div class="message-title">
                    Please fix the following errors
                </div>

                <ul class="message-list mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="message-card-3d message-success-3d mb-4"
             role="alert">

            <div class="message-icon-3d">
                <i class="bi bi-check-circle"></i>
            </div>

            <div class="flex-grow-1">

                <div class="message-title">
                    Success
                </div>

                <div class="message-text">
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
         FORM
    ========================================================== --}}
    <form action="{{ route('admin.appointments.update', $appointment->id) }}"
          method="POST">

        @csrf
        @method('PUT')


        <div class="row g-4 align-items-start">


            {{-- =================================================
                 LEFT SIDE
            ================================================== --}}
            <div class="col-xl-8">


                {{-- =============================================
                     APPOINTMENT DETAILS
                ============================================== --}}
                <div class="white-card-3d mb-4">

                    <div class="card-heading-3d">

                        <div class="heading-icon-3d">
                            <i class="bi bi-calendar-event"></i>
                        </div>

                        <div>
                            <h5 class="heading-title-3d">
                                Appointment Details
                            </h5>

                            <p class="heading-subtitle-3d">
                                Manage customer, service and appointment schedule.
                            </p>
                        </div>

                    </div>


                    <div class="card-content-3d">

                        <div class="row g-4">


                            {{-- CUSTOMER --}}
                            <div class="col-md-6">

                                <label class="field-label-3d">
                                    Customer
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <select name="customer_id"
                                            class="form-select input-3d"
                                            required>

                                        <option value="">
                                            Select Customer
                                        </option>

                                        @foreach($customers as $customer)

                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id', $appointment->customer_id) == $customer->id ? 'selected' : '' }}>

                                                {{ $customer->name }}

                                                @if($customer->phone)
                                                    - {{ $customer->phone }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('customer_id')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- SERVICE --}}
                            <div class="col-md-6">

                                <label class="field-label-3d">
                                    Service
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-scissors"></i>
                                    </div>

                                    <select name="service_id"
                                            id="serviceSelect"
                                            class="form-select input-3d"
                                            required>

                                        <option value="">
                                            Select Service
                                        </option>

                                        @foreach($services as $service)

                                            <option value="{{ $service->id }}"
                                                    data-duration="{{ $service->duration ?? 0 }}"
                                                    data-price="{{ $service->price ?? 0 }}"
                                                {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>

                                                {{ $service->name }}

                                                @if(isset($service->price))
                                                    - ₹{{ number_format($service->price, 2) }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('service_id')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- STAFF --}}
                            <div class="col-md-6">

                                <label class="field-label-3d">
                                    Staff / Beautician
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-person-badge"></i>
                                    </div>

                                    <select name="staff_id"
                                            class="form-select input-3d"
                                            required>

                                        <option value="">
                                            Select Staff
                                        </option>

                                        @foreach($staff as $member)

                                            <option value="{{ $member->id }}"
                                                {{ old('staff_id', $appointment->staff_id) == $member->id ? 'selected' : '' }}>

                                                {{ $member->name }}

                                                @if($member->role)
                                                    - {{ $member->role }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('staff_id')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- BRANCH --}}
                            <div class="col-md-6">

                                <label class="field-label-3d">
                                    Branch
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-building"></i>
                                    </div>

                                    <select name="branch_id"
                                            class="form-select input-3d"
                                            required>

                                        <option value="">
                                            Select Branch
                                        </option>

                                        @foreach($branches as $branch)

                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', $appointment->branch_id) == $branch->id ? 'selected' : '' }}>

                                                {{ $branch->name ?? 'Branch #' . $branch->id }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                                @error('branch_id')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- DATE --}}
                            <div class="col-md-4">

                                <label class="field-label-3d">
                                    Appointment Date
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-calendar3"></i>
                                    </div>

                                    <input type="date"
                                           name="appointment_date"
                                           class="form-control input-3d"
                                           value="{{ old('appointment_date', optional($appointment->appointment_date)->format('Y-m-d')) }}"
                                           required>

                                </div>

                                @error('appointment_date')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- START TIME --}}
                            <div class="col-md-4">

                                <label class="field-label-3d">
                                    Start Time
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-clock"></i>
                                    </div>

                                    <input type="time"
                                           name="start_time"
                                           id="startTime"
                                           class="form-control input-3d"
                                           value="{{ old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i')) }}"
                                           required>

                                </div>

                                @error('start_time')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- END TIME --}}
                            <div class="col-md-4">

                                <label class="field-label-3d">
                                    End Time
                                    <span>*</span>
                                </label>

                                <div class="input-wrapper-3d">

                                    <div class="input-icon-3d">
                                        <i class="bi bi-clock-history"></i>
                                    </div>

                                    <input type="time"
                                           name="end_time"
                                           id="endTime"
                                           class="form-control input-3d"
                                           value="{{ old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i')) }}"
                                           required>

                                </div>

                                @error('end_time')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- NOTES --}}
                            <div class="col-12">

                                <label class="field-label-3d">
                                    Notes
                                </label>

                                <div class="textarea-wrapper-3d">

                                    <div class="textarea-icon-3d">
                                        <i class="bi bi-pencil-square"></i>
                                    </div>

                                    <textarea name="notes"
                                              class="form-control textarea-3d"
                                              rows="4"
                                              placeholder="Add appointment notes...">{{ old('notes', $appointment->notes) }}</textarea>

                                </div>

                                @error('notes')

                                    <div class="field-error-3d">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                        </div>

                    </div>

                </div>


                {{-- =============================================
                     SERVICE INFORMATION
                ============================================== --}}
                <div class="white-card-3d">

                    <div class="card-heading-3d">

                        <div class="heading-icon-3d">
                            <i class="bi bi-scissors"></i>
                        </div>

                        <div>
                            <h5 class="heading-title-3d">
                                Service Information
                            </h5>

                            <p class="heading-subtitle-3d">
                                Quick overview of the selected service.
                            </p>
                        </div>

                    </div>


                    <div class="card-content-3d">

                        <div class="row g-3">


                            {{-- SERVICE --}}
                            <div class="col-md-4">

                                <div class="info-tile-3d">

                                    <div class="info-icon-3d">
                                        <i class="bi bi-scissors"></i>
                                    </div>

                                    <div class="info-content-3d">

                                        <small>
                                            Selected Service
                                        </small>

                                        <strong id="selectedService">
                                            {{ $appointment->service->name ?? 'Select Service' }}
                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- DURATION --}}
                            <div class="col-md-4">

                                <div class="info-tile-3d">

                                    <div class="info-icon-3d">
                                        <i class="bi bi-hourglass-split"></i>
                                    </div>

                                    <div class="info-content-3d">

                                        <small>
                                            Duration
                                        </small>

                                        <strong id="serviceDuration">

                                            @if($appointment->service && $appointment->service->duration)

                                                {{ $appointment->service->duration }} minutes

                                            @else

                                                -

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- PRICE --}}
                            <div class="col-md-4">

                                <div class="info-tile-3d">

                                    <div class="info-icon-3d">
                                        <i class="bi bi-currency-rupee"></i>
                                    </div>

                                    <div class="info-content-3d">

                                        <small>
                                            Service Price
                                        </small>

                                        <strong id="servicePrice">

                                            @if($appointment->service && $appointment->service->price)

                                                ₹{{ number_format($appointment->service->price, 2) }}

                                            @else

                                                -

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>


                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 RIGHT SIDE
            ================================================== --}}
            <div class="col-xl-4">


                {{-- =============================================
                     STATUS
                ============================================== --}}
                <div class="white-card-3d mb-4">

                    <div class="card-heading-3d">

                        <div class="heading-icon-3d">
                            <i class="bi bi-flag"></i>
                        </div>

                        <div>
                            <h5 class="heading-title-3d">
                                Appointment Status
                            </h5>

                            <p class="heading-subtitle-3d">
                                Update the current booking status.
                            </p>
                        </div>

                    </div>


                    <div class="card-content-3d">

                        <label class="field-label-3d">
                            Status
                        </label>

                        <div class="input-wrapper-3d">

                            <div class="input-icon-3d">
                                <i class="bi bi-activity"></i>
                            </div>

                            <select name="status"
                                    id="appointmentStatus"
                                    class="form-select input-3d"
                                    required>

                                <option value="pending"
                                    {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="confirmed"
                                    {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed
                                </option>

                                <option value="arrived"
                                    {{ old('status', $appointment->status) == 'arrived' ? 'selected' : '' }}>
                                    Arrived
                                </option>

                                <option value="in_service"
                                    {{ old('status', $appointment->status) == 'in_service' ? 'selected' : '' }}>
                                    In Service
                                </option>

                                <option value="completed"
                                    {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="cancelled"
                                    {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>

                                <option value="no_show"
                                    {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>
                                    No Show
                                </option>

                            </select>

                        </div>


                        @error('status')

                            <div class="field-error-3d">
                                {{ $message }}
                            </div>

                        @enderror


                        @php

                            $currentStatus = old(
                                'status',
                                $appointment->status
                            );

                            $statusClass = match($currentStatus) {

                                'pending' => 'status-pending',

                                'confirmed' => 'status-confirmed',

                                'arrived' => 'status-arrived',

                                'in_service' => 'status-service',

                                'completed' => 'status-completed',

                                'cancelled' => 'status-cancelled',

                                'no_show' => 'status-noshow',

                                default => 'status-default'

                            };

                        @endphp


                        <div class="status-display-3d mt-3"
                             id="statusAlert">

                            <div class="status-dot-3d"></div>

                            <div>

                                <small>
                                    Current Status
                                </small>

                                <strong id="statusText">

                                    {{ ucwords(str_replace('_', ' ', $currentStatus)) }}

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =============================================
                     APPOINTMENT SUMMARY
                ============================================== --}}
                <div class="white-card-3d mb-4">

                    <div class="card-heading-3d">

                        <div class="heading-icon-3d">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div>
                            <h5 class="heading-title-3d">
                                Appointment Summary
                            </h5>

                            <p class="heading-subtitle-3d">
                                Current appointment information.
                            </p>
                        </div>

                    </div>


                    <div class="card-content-3d">


                        {{-- ID --}}
                        <div class="summary-row-3d">

                            <div class="summary-icon-3d">
                                <i class="bi bi-hash"></i>
                            </div>

                            <div class="summary-data-3d">

                                <small>
                                    Appointment ID
                                </small>

                                <strong>
                                    #{{ $appointment->id }}
                                </strong>

                            </div>

                        </div>


                        {{-- CUSTOMER --}}
                        <div class="summary-row-3d">

                            <div class="summary-icon-3d">
                                <i class="bi bi-person"></i>
                            </div>

                            <div class="summary-data-3d">

                                <small>
                                    Customer
                                </small>

                                <strong>
                                    {{ $appointment->customer->name ?? 'N/A' }}
                                </strong>

                                @if($appointment->customer && $appointment->customer->phone)

                                    <span>
                                        {{ $appointment->customer->phone }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- SERVICE --}}
                        <div class="summary-row-3d">

                            <div class="summary-icon-3d">
                                <i class="bi bi-scissors"></i>
                            </div>

                            <div class="summary-data-3d">

                                <small>
                                    Service
                                </small>

                                <strong>
                                    {{ $appointment->service->name ?? 'N/A' }}
                                </strong>

                            </div>

                        </div>


                        {{-- STAFF --}}
                        <div class="summary-row-3d">

                            <div class="summary-icon-3d">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <div class="summary-data-3d">

                                <small>
                                    Staff
                                </small>

                                <strong>
                                    {{ $appointment->staff->name ?? 'N/A' }}
                                </strong>

                            </div>

                        </div>


                        {{-- BRANCH --}}
                        <div class="summary-row-3d">

                            <div class="summary-icon-3d">
                                <i class="bi bi-building"></i>
                            </div>

                            <div class="summary-data-3d">

                                <small>
                                    Branch
                                </small>

                                <strong>
                                    {{ $appointment->branch->name ?? 'N/A' }}
                                </strong>

                            </div>

                        </div>


                        {{-- CREATED --}}
                        <div class="summary-row-3d border-0 pb-0">

                            <div class="summary-icon-3d">
                                <i class="bi bi-clock-history"></i>
                            </div>

                            <div class="summary-data-3d">

                                <small>
                                    Created On
                                </small>

                                <strong>
                                    {{ $appointment->created_at?->format('d M Y, h:i A') }}
                                </strong>

                            </div>

                        </div>


                    </div>

                </div>


                {{-- =============================================
                     UPDATE BUTTONS
                ============================================== --}}
                <div class="action-card-3d">

                    <button type="submit"
                            class="update-button-3d w-100 mb-3">

                        <span class="button-icon-3d">
                            <i class="bi bi-check-lg"></i>
                        </span>

                        <span>
                            Update Appointment
                        </span>

                    </button>


                    <a href="{{ route('admin.appointments.index') }}"
                       class="cancel-button-3d w-100">

                        <i class="bi bi-x-lg"></i>

                        <span>
                            Cancel
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =============================================================
     3D WHITE THEME CSS
============================================================= --}}
@push('styles')

<style>

    /* =========================================================
       BASE
    ========================================================== */

    .appointment-edit-3d {

        --white: #ffffff;
        --soft-white: #fafafa;
        --surface: #f7f7f7;
        --border: #e9e9e9;
        --border-dark: #dddddd;

        --text: #454545;
        --muted: #8b8b8b;

        --shadow-1:
            0 8px 22px rgba(0, 0, 0, 0.055);

        --shadow-2:
            0 18px 45px rgba(0, 0, 0, 0.075);

        --shadow-3:
            inset 0 1px 0 rgba(255,255,255,.95),
            0 12px 28px rgba(0,0,0,.06);

        color: var(--text);
        padding: 4px 2px 35px;

    }


    /* =========================================================
       HERO
    ========================================================== */

    .appointment-hero-3d {

        position: relative;

        min-height: 145px;

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding: 28px 30px;

        background:
            linear-gradient(
                145deg,
                #ffffff 0%,
                #fbfbfb 55%,
                #f5f5f5 100%
            );

        border: 1px solid #ededed;

        border-radius: 28px;

        box-shadow:
            0 20px 45px rgba(0,0,0,.065),
            inset 0 1px 0 rgba(255,255,255,.98);

        overflow: hidden;

    }


    .appointment-hero-3d::before {

        content: "";

        position: absolute;

        width: 190px;
        height: 190px;

        right: -75px;
        top: -95px;

        border-radius: 50%;

        background: rgba(0,0,0,.025);

        box-shadow:
            inset -10px -10px 25px rgba(0,0,0,.025),
            inset 10px 10px 25px rgba(255,255,255,.9);

    }


    .appointment-hero-3d::after {

        content: "";

        position: absolute;

        width: 95px;
        height: 95px;

        right: 150px;
        bottom: -55px;

        border-radius: 50%;

        background: #ffffff;

        box-shadow:
            0 12px 30px rgba(0,0,0,.07);

    }


    .hero-content-3d {

        position: relative;

        z-index: 2;

        display: flex;

        align-items: center;

        gap: 18px;

    }


    .hero-icon-3d {

        width: 68px;
        height: 68px;

        flex: 0 0 68px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 20px;

        color: #555;

        background: #ffffff;

        border: 1px solid #ededed;

        font-size: 27px;

        box-shadow:
            8px 8px 18px rgba(0,0,0,.08),
            -5px -5px 15px rgba(255,255,255,.95),
            inset 0 1px 0 #ffffff;

    }


    .hero-eyebrow {

        font-size: 10px;

        font-weight: 800;

        letter-spacing: 1.7px;

        color: #999;

        margin-bottom: 5px;

    }


    .hero-title {

        margin: 0;

        font-size: 27px;

        font-weight: 800;

        color: #3f3f3f;

        letter-spacing: -.5px;

    }


    .hero-subtitle {

        margin-top: 5px;

        font-size: 13px;

        color: #8b8b8b;

    }


    .back-button-3d {

        position: relative;

        z-index: 5;

        display: inline-flex;

        align-items: center;

        gap: 9px;

        padding: 12px 17px;

        color: #555;

        background: #ffffff;

        border: 1px solid #e7e7e7;

        border-radius: 14px;

        text-decoration: none;

        font-size: 13px;

        font-weight: 700;

        box-shadow:
            5px 5px 12px rgba(0,0,0,.07),
            -4px -4px 10px rgba(255,255,255,.95);

        transition: .25s ease;

    }


    .back-button-3d:hover {

        color: #333;

        transform: translateY(-2px);

        box-shadow:
            8px 10px 20px rgba(0,0,0,.09),
            -4px -4px 12px rgba(255,255,255,.95);

    }


    /* =========================================================
       MESSAGES
    ========================================================== */

    .message-card-3d {

        display: flex;

        align-items: flex-start;

        gap: 14px;

        padding: 17px 19px;

        border-radius: 18px;

        background: #ffffff;

        border: 1px solid #e8e8e8;

        box-shadow: var(--shadow-1);

    }


    .message-error-3d {

        border-left: 4px solid #777;

    }


    .message-success-3d {

        border-left: 4px solid #999;

    }


    .message-icon-3d {

        width: 38px;
        height: 38px;

        flex: 0 0 38px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #f5f5f5;

        color: #555;

        box-shadow:
            inset 2px 2px 5px rgba(0,0,0,.05),
            inset -2px -2px 5px rgba(255,255,255,.9);

    }


    .message-title {

        font-weight: 800;

        font-size: 13px;

        margin-bottom: 3px;

    }


    .message-text,
    .message-list {

        color: #777;

        font-size: 13px;

    }


    .message-list {

        padding-left: 18px;

    }


    /* =========================================================
       MAIN CARD
    ========================================================== */

    .white-card-3d {

        background: #ffffff;

        border: 1px solid #ededed;

        border-radius: 24px;

        overflow: hidden;

        box-shadow:
            0 16px 38px rgba(0,0,0,.06),
            0 2px 5px rgba(0,0,0,.025),
            inset 0 1px 0 rgba(255,255,255,.98);

        transition:
            transform .25s ease,
            box-shadow .25s ease;

    }


    .white-card-3d:hover {

        box-shadow:
            0 22px 45px rgba(0,0,0,.075),
            inset 0 1px 0 rgba(255,255,255,.98);

    }


    /* =========================================================
       CARD HEADER
    ========================================================== */

    .card-heading-3d {

        display: flex;

        align-items: center;

        gap: 14px;

        padding: 20px 23px;

        border-bottom: 1px solid #eeeeee;

        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #fbfbfb 100%
            );

    }


    .heading-icon-3d {

        width: 44px;
        height: 44px;

        display: flex;

        align-items: center;
        justify-content: center;

        flex: 0 0 44px;

        border-radius: 14px;

        background: #f5f5f5;

        border: 1px solid #e9e9e9;

        color: #555;

        font-size: 18px;

        box-shadow:
            5px 5px 12px rgba(0,0,0,.07),
            -4px -4px 10px rgba(255,255,255,.95);

    }


    .heading-title-3d {

        margin: 0;

        color: #424242;

        font-size: 16px;

        font-weight: 800;

    }


    .heading-subtitle-3d {

        margin: 3px 0 0;

        color: #999;

        font-size: 11px;

    }


    .card-content-3d {

        padding: 24px;

    }


    /* =========================================================
       LABELS
    ========================================================== */

    .field-label-3d {

        display: block;

        margin-bottom: 8px;

        color: #555;

        font-size: 12px;

        font-weight: 800;

    }


    .field-label-3d span {

        color: #777;

        margin-left: 2px;

    }


    /* =========================================================
       INPUTS
    ========================================================== */

    .input-wrapper-3d {

        position: relative;

    }


    .input-icon-3d {

        position: absolute;

        left: 14px;

        top: 50%;

        transform: translateY(-50%);

        width: 27px;
        height: 27px;

        display: flex;

        align-items: center;
        justify-content: center;

        color: #8a8a8a;

        background: #f6f6f6;

        border-radius: 9px;

        font-size: 13px;

        pointer-events: none;

        z-index: 3;

        box-shadow:
            inset 1px 1px 3px rgba(0,0,0,.05),
            inset -1px -1px 3px rgba(255,255,255,.95);

    }


    .input-3d {

        min-height: 50px;

        padding-left: 52px;

        border-radius: 15px !important;

        border: 1px solid #e5e5e5 !important;

        background: #ffffff !important;

        color: #4a4a4a !important;

        font-size: 13px;

        font-weight: 600;

        box-shadow:
            inset 2px 2px 6px rgba(0,0,0,.035),
            0 3px 8px rgba(0,0,0,.025);

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            transform .2s ease;

    }


    .input-3d:hover {

        border-color: #d8d8d8 !important;

    }


    .input-3d:focus {

        border-color: #cfcfcf !important;

        box-shadow:
            0 0 0 4px rgba(0,0,0,.035),
            inset 2px 2px 6px rgba(0,0,0,.035) !important;

        outline: none;

    }


    .input-3d::placeholder {

        color: #b0b0b0;

        font-weight: 500;

    }


    select.input-3d {

        cursor: pointer;

    }


    /* =========================================================
       TEXTAREA
    ========================================================== */

    .textarea-wrapper-3d {

        position: relative;

    }


    .textarea-icon-3d {

        position: absolute;

        top: 15px;

        left: 15px;

        width: 28px;
        height: 28px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #f5f5f5;

        color: #888;

        z-index: 2;

    }


    .textarea-3d {

        min-height: 115px;

        padding: 15px 15px 15px 55px !important;

        border-radius: 16px !important;

        border: 1px solid #e5e5e5 !important;

        resize: vertical;

        font-size: 13px;

        color: #4a4a4a;

        box-shadow:
            inset 2px 2px 6px rgba(0,0,0,.035),
            0 3px 8px rgba(0,0,0,.025);

    }


    .textarea-3d:focus {

        border-color: #cfcfcf !important;

        box-shadow:
            0 0 0 4px rgba(0,0,0,.035),
            inset 2px 2px 6px rgba(0,0,0,.035) !important;

    }


    /* =========================================================
       ERROR
    ========================================================== */

    .field-error-3d {

        margin-top: 6px;

        color: #777;

        font-size: 11px;

        font-weight: 600;

    }


    /* =========================================================
       SERVICE INFORMATION
    ========================================================== */

    .info-tile-3d {

        min-height: 90px;

        display: flex;

        align-items: center;

        gap: 13px;

        padding: 15px;

        border-radius: 17px;

        background:
            linear-gradient(
                145deg,
                #ffffff,
                #f7f7f7
            );

        border: 1px solid #e8e8e8;

        box-shadow:
            6px 7px 15px rgba(0,0,0,.055),
            -4px -4px 12px rgba(255,255,255,.95);

    }


    .info-icon-3d {

        width: 40px;
        height: 40px;

        flex: 0 0 40px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 12px;

        background: #eeeeee;

        color: #555;

        box-shadow:
            inset 2px 2px 4px rgba(0,0,0,.05),
            inset -2px -2px 4px rgba(255,255,255,.95);

    }


    .info-content-3d {

        min-width: 0;

    }


    .info-content-3d small {

        display: block;

        color: #999;

        font-size: 10px;

        font-weight: 700;

        margin-bottom: 3px;

    }


    .info-content-3d strong {

        display: block;

        color: #444;

        font-size: 13px;

        font-weight: 800;

        word-break: break-word;

    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-display-3d {

        display: flex;

        align-items: center;

        gap: 12px;

        padding: 15px;

        border-radius: 16px;

        background: #f7f7f7;

        border: 1px solid #e8e8e8;

        box-shadow:
            inset 2px 2px 5px rgba(0,0,0,.035),
            inset -2px -2px 5px rgba(255,255,255,.95);

    }


    .status-dot-3d {

        width: 12px;
        height: 12px;

        flex: 0 0 12px;

        border-radius: 50%;

        background: #777;

        box-shadow:
            0 0 0 5px rgba(0,0,0,.035),
            0 3px 7px rgba(0,0,0,.15);

    }


    .status-display-3d small {

        display: block;

        color: #999;

        font-size: 10px;

        font-weight: 700;

        margin-bottom: 2px;

    }


    .status-display-3d strong {

        display: block;

        color: #444;

        font-size: 13px;

        font-weight: 800;

    }


    /* Status states - only grayscale */

    .status-pending .status-dot-3d {
        background: #999;
    }

    .status-confirmed .status-dot-3d {
        background: #666;
    }

    .status-arrived .status-dot-3d {
        background: #777;
    }

    .status-service .status-dot-3d {
        background: #555;
    }

    .status-completed .status-dot-3d {
        background: #444;
    }

    .status-cancelled .status-dot-3d {
        background: #999;
    }

    .status-noshow .status-dot-3d {
        background: #333;
    }

    .status-default .status-dot-3d {
        background: #888;
    }


    /* =========================================================
       SUMMARY
    ========================================================== */

    .summary-row-3d {

        display: flex;

        align-items: center;

        gap: 12px;

        padding: 13px 0;

        border-bottom: 1px solid #eeeeee;

    }


    .summary-icon-3d {

        width: 38px;
        height: 38px;

        flex: 0 0 38px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: #f5f5f5;

        color: #777;

        font-size: 14px;

        box-shadow:
            inset 2px 2px 4px rgba(0,0,0,.04),
            inset -2px -2px 4px rgba(255,255,255,.95);

    }


    .summary-data-3d {

        min-width: 0;

    }


    .summary-data-3d small {

        display: block;

        color: #999;

        font-size: 10px;

        font-weight: 700;

        margin-bottom: 2px;

    }


    .summary-data-3d strong {

        display: block;

        color: #484848;

        font-size: 12px;

        font-weight: 800;

        word-break: break-word;

    }


    .summary-data-3d span {

        display: block;

        margin-top: 2px;

        color: #999;

        font-size: 10px;

    }


    /* =========================================================
       ACTION CARD
    ========================================================== */

    .action-card-3d {

        padding: 19px;

        border-radius: 23px;

        background:
            linear-gradient(
                145deg,
                #ffffff,
                #f6f6f6
            );

        border: 1px solid #e7e7e7;

        box-shadow:
            0 17px 38px rgba(0,0,0,.075),
            inset 0 1px 0 rgba(255,255,255,.98);

    }


    .update-button-3d {

        min-height: 55px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 10px;

        border: 1px solid #3f3f3f;

        border-radius: 15px;

        background:
            linear-gradient(
                145deg,
                #4c4c4c,
                #2f2f2f
            );

        color: #ffffff;

        font-size: 13px;

        font-weight: 800;

        letter-spacing: .1px;

        box-shadow:
            0 9px 17px rgba(0,0,0,.18),
            inset 0 1px 0 rgba(255,255,255,.18);

        transition: .25s ease;

    }


    .update-button-3d:hover {

        color: #ffffff;

        transform: translateY(-2px);

        box-shadow:
            0 14px 24px rgba(0,0,0,.20),
            inset 0 1px 0 rgba(255,255,255,.18);

    }


    .update-button-3d:active {

        transform: translateY(1px);

        box-shadow:
            inset 3px 3px 7px rgba(0,0,0,.25);

    }


    .button-icon-3d {

        width: 28px;
        height: 28px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: rgba(255,255,255,.13);

    }


    .cancel-button-3d {

        min-height: 50px;

        display: flex;

        align-items: center;

        justify-content: center;

        gap: 9px;

        border: 1px solid #dedede;

        border-radius: 15px;

        background: #ffffff;

        color: #666;

        font-size: 13px;

        font-weight: 750;

        text-decoration: none;

        box-shadow:
            5px 6px 13px rgba(0,0,0,.055),
            -4px -4px 10px rgba(255,255,255,.95);

        transition: .25s ease;

    }


    .cancel-button-3d:hover {

        color: #333;

        transform: translateY(-2px);

        border-color: #cfcfcf;

        box-shadow:
            7px 9px 18px rgba(0,0,0,.075),
            -4px -4px 10px rgba(255,255,255,.95);

    }


    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 991.98px) {

        .appointment-hero-3d {

            align-items: flex-start;

            flex-direction: column;

        }


        .back-button-3d {

            width: 100%;

            justify-content: center;

        }

    }


    @media (max-width: 575.98px) {

        .appointment-edit-3d {

            padding-left: 0;
            padding-right: 0;

        }


        .appointment-hero-3d {

            padding: 22px 18px;

            border-radius: 21px;

        }


        .hero-content-3d {

            align-items: flex-start;

        }


        .hero-icon-3d {

            width: 54px;
            height: 54px;

            flex-basis: 54px;

            border-radius: 16px;

            font-size: 21px;

        }


        .hero-title {

            font-size: 22px;

        }


        .hero-subtitle {

            font-size: 12px;

        }


        .white-card-3d {

            border-radius: 20px;

        }


        .card-heading-3d {

            padding: 17px;

        }


        .card-content-3d {

            padding: 17px;

        }


        .heading-icon-3d {

            width: 40px;
            height: 40px;

            flex-basis: 40px;

        }


        .heading-title-3d {

            font-size: 14px;

        }


        .info-tile-3d {

            min-height: 78px;

        }

    }


    /* =========================================================
       REDUCED MOTION
    ========================================================== */

    @media (prefers-reduced-motion: reduce) {

        .appointment-edit-3d *,
        .appointment-edit-3d *::before,
        .appointment-edit-3d *::after {

            transition: none !important;

            animation: none !important;

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
       ELEMENTS
    ========================================================== */

    const serviceSelect =
        document.getElementById('serviceSelect');

    const startTime =
        document.getElementById('startTime');

    const endTime =
        document.getElementById('endTime');

    const selectedService =
        document.getElementById('selectedService');

    const serviceDuration =
        document.getElementById('serviceDuration');

    const servicePrice =
        document.getElementById('servicePrice');

    const statusSelect =
        document.getElementById('appointmentStatus');

    const statusAlert =
        document.getElementById('statusAlert');

    const statusText =
        document.getElementById('statusText');


    /* =========================================================
       SERVICE INFORMATION
    ========================================================== */

    function updateServiceInformation() {

        if (!serviceSelect) {
            return;
        }

        const option =
            serviceSelect.options[
                serviceSelect.selectedIndex
            ];


        if (!option || !option.value) {

            if (selectedService) {
                selectedService.textContent =
                    'Select Service';
            }

            if (serviceDuration) {
                serviceDuration.textContent =
                    '-';
            }

            if (servicePrice) {
                servicePrice.textContent =
                    '-';
            }

            return;
        }


        const serviceName =
            option.text
                .split(' - ₹')[0]
                .trim();


        const duration =
            parseInt(
                option.dataset.duration || 0
            );


        const price =
            parseFloat(
                option.dataset.price || 0
            );


        if (selectedService) {

            selectedService.textContent =
                serviceName;

        }


        if (serviceDuration) {

            serviceDuration.textContent =
                duration > 0
                    ? duration + ' minutes'
                    : '-';

        }


        if (servicePrice) {

            servicePrice.textContent =
                price > 0
                    ? '₹' + price.toFixed(2)
                    : '-';

        }

    }


    /* =========================================================
       AUTO CALCULATE END TIME
    ========================================================== */

    function calculateEndTime() {

        if (!serviceSelect || !startTime || !endTime) {
            return;
        }


        const option =
            serviceSelect.options[
                serviceSelect.selectedIndex
            ];


        if (!option || !option.value) {
            return;
        }


        const duration =
            parseInt(
                option.dataset.duration || 0
            );


        const startValue =
            startTime.value;


        if (!duration || !startValue) {
            return;
        }


        const parts =
            startValue.split(':');


        let hours =
            parseInt(parts[0]);


        let minutes =
            parseInt(parts[1]);


        minutes += duration;


        hours +=
            Math.floor(minutes / 60);


        minutes =
            minutes % 60;


        hours =
            hours % 24;


        const formattedHours =
            String(hours).padStart(2, '0');


        const formattedMinutes =
            String(minutes).padStart(2, '0');


        endTime.value =
            formattedHours +
            ':' +
            formattedMinutes;

    }


    /* =========================================================
       SERVICE EVENTS
    ========================================================== */

    if (serviceSelect) {

        serviceSelect.addEventListener(
            'change',
            function () {

                updateServiceInformation();

                calculateEndTime();

            }
        );

    }


    if (startTime) {

        startTime.addEventListener(
            'change',
            function () {

                calculateEndTime();

            }
        );

    }


    /* =========================================================
       STATUS
    ========================================================== */

    function updateStatus() {

        if (!statusSelect ||
            !statusAlert ||
            !statusText) {

            return;

        }


        const status =
            statusSelect.value;


        const statusClasses = [

            'status-pending',
            'status-confirmed',
            'status-arrived',
            'status-service',
            'status-completed',
            'status-cancelled',
            'status-noshow',
            'status-default'

        ];


        statusClasses.forEach(
            function (className) {

                statusAlert.classList.remove(
                    className
                );

            }
        );


        const classMap = {

            pending:
                'status-pending',

            confirmed:
                'status-confirmed',

            arrived:
                'status-arrived',

            in_service:
                'status-service',

            completed:
                'status-completed',

            cancelled:
                'status-cancelled',

            no_show:
                'status-noshow'

        };


        statusAlert.classList.add(
            classMap[status] ||
            'status-default'
        );


        statusText.textContent =
            status
                .replaceAll('_', ' ')
                .replace(/\b\w/g, function (letter) {

                    return letter.toUpperCase();

                });

    }


    if (statusSelect) {

        statusSelect.addEventListener(
            'change',
            updateStatus
        );

    }


    /* =========================================================
       INITIAL LOAD
    ========================================================== */

    updateServiceInformation();

    updateStatus();

});

</script>

@endpush

@endsection