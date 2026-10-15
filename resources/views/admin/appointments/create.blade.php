@extends('admin.layout')

@section('title', 'Add Appointment | Salon Management')

@section('page-title', 'Add Appointment')

@section('content')

<div class="appointment-3d-page">

    {{-- =========================================================
        HERO HEADER
    ========================================================== --}}
    <div class="appointment-hero mb-4">

        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="hero-orb hero-orb-three"></div>

        <div class="hero-content">

            <div class="hero-icon">
                <i class="bi bi-calendar2-plus"></i>
            </div>

            <div class="hero-text">

                <div class="hero-badge">
                    <span class="live-dot"></span>
                    New Appointment
                </div>

                <h2>
                    Create New Appointment
                </h2>

                <p>
                    Schedule a seamless salon visit for your customer.
                </p>

            </div>

            <div class="hero-action">

                <a href="{{ route('admin.appointments.index') }}"
                   class="btn-3d btn-back">

                    <span class="btn-icon">
                        <i class="bi bi-arrow-left"></i>
                    </span>

                    <span>Back to Appointments</span>

                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="message-3d success-message">

            <div class="message-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <div class="message-content">
                <strong>Appointment Created</strong>
                <span>{{ session('success') }}</span>
            </div>

            <button type="button"
                    class="message-close"
                    data-bs-dismiss="alert">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="message-3d error-message">

            <div class="message-icon">
                <i class="bi bi-exclamation-lg"></i>
            </div>

            <div class="message-content">

                <strong>Please fix the following errors</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>

        </div>

    @endif


    {{-- =========================================================
        APPOINTMENT FORM
    ========================================================== --}}
    <form action="{{ route('admin.appointments.store') }}"
          method="POST"
          id="appointmentForm">

        @csrf

        <div class="row g-4">

            {{-- =====================================================
                MAIN FORM
            ====================================================== --}}
            <div class="col-xl-8">

                <div class="appointment-card main-form-card">

                    {{-- CARD HEADER --}}
                    <div class="card-3d-header">

                        <div class="header-icon purple-icon">
                            <i class="bi bi-calendar-plus"></i>
                        </div>

                        <div>
                            <h4>Appointment Details</h4>

                            <p>
                                Enter customer, service and scheduling information.
                            </p>
                        </div>

                        <div class="header-decoration">
                            <i class="bi bi-stars"></i>
                        </div>

                    </div>


                    <div class="card-3d-body">

                        <div class="row g-4">

                            {{-- =================================================
                                CUSTOMER
                            ================================================== --}}
                            <div class="col-md-6">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon customer-label">
                                            <i class="bi bi-person"></i>
                                        </span>

                                        <span>
                                            Customer
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <select name="customer_id"
                                                class="form-select input-3d"
                                                required>

                                            <option value="">
                                                Select Customer
                                            </option>

                                            @foreach($customers as $customer)

                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                                    {{ $customer->name }}

                                                    @if($customer->phone)
                                                        - {{ $customer->phone }}
                                                    @endif

                                                </option>

                                            @endforeach

                                        </select>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-chevron-down"></i>
                                        </span>

                                    </div>

                                    <small class="field-help">
                                        <i class="bi bi-info-circle"></i>
                                        Select the customer for this appointment.
                                    </small>

                                </div>

                            </div>


                            {{-- =================================================
                                SERVICE
                            ================================================== --}}
                            <div class="col-md-6">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon service-label">
                                            <i class="bi bi-scissors"></i>
                                        </span>

                                        <span>
                                            Service
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <select name="service_id"
                                                id="service_id"
                                                class="form-select input-3d"
                                                required>

                                            <option value="">
                                                Select Service
                                            </option>

                                            @foreach($services as $service)

                                                <option value="{{ $service->id }}"
                                                        data-duration="{{ $service->duration }}"
                                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>

                                                    {{ $service->name }}

                                                    @if(isset($service->price))
                                                        - ₹{{ number_format($service->price, 2) }}
                                                    @endif

                                                </option>

                                            @endforeach

                                        </select>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-chevron-down"></i>
                                        </span>

                                    </div>

                                    <small class="field-help">
                                        <i class="bi bi-stars"></i>
                                        Service duration will calculate automatically.
                                    </small>

                                </div>

                            </div>


                            {{-- =================================================
                                BRANCH
                            ================================================== --}}
                            <div class="col-md-6">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon branch-label">
                                            <i class="bi bi-shop"></i>
                                        </span>

                                        <span>
                                            Branch
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <select name="branch_id"
                                                id="branch_id"
                                                class="form-select input-3d"
                                                required>

                                            <option value="">
                                                Select Branch
                                            </option>

                                            @foreach($branches as $branch)

                                                <option value="{{ $branch->id }}"
                                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>

                                                    {{ $branch->name }}

                                                </option>

                                            @endforeach

                                        </select>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-chevron-down"></i>
                                        </span>

                                    </div>

                                    <small class="field-help">
                                        <i class="bi bi-geo-alt"></i>
                                        Select the branch for this booking.
                                    </small>

                                </div>

                            </div>


                            {{-- =================================================
                                STAFF
                            ================================================== --}}
                            <div class="col-md-6">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon staff-label">
                                            <i class="bi bi-person-badge"></i>
                                        </span>

                                        <span>
                                            Staff / Beautician
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <select name="staff_id"
                                                id="staff_id"
                                                class="form-select input-3d"
                                                required
                                                disabled>

                                            <option value="">
                                                Select Branch First
                                            </option>

                                            @foreach($staff as $member)

                                                <option value="{{ $member->id }}"
                                                        data-branch-id="{{ $member->branch_id }}">

                                                    {{ $member->name }}

                                                </option>

                                            @endforeach

                                        </select>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-chevron-down"></i>
                                        </span>

                                    </div>

                                    <small class="field-help">
                                        <i class="bi bi-diagram-3"></i>
                                        Staff will be shown according to selected branch.
                                    </small>


                                    {{-- STAFF LEAVE WARNING --}}
                                    <div id="staffLeaveWarning"
                                         class="status-message leave-message d-none">

                                        <div class="status-message-icon">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                        </div>

                                        <div>
                                            <strong>Staff Unavailable</strong>

                                            <p id="staffLeaveMessage">
                                                This staff member is on leave on the selected date.
                                                Please select another staff member.
                                            </p>
                                        </div>

                                    </div>


                                    {{-- CHECKING --}}
                                    <div id="staffLeaveChecking"
                                         class="status-message checking-message d-none">

                                        <div class="spinner-3d"></div>

                                        <div>
                                            <strong>Checking availability</strong>
                                            <p>Please wait...</p>
                                        </div>

                                    </div>


                                    {{-- AVAILABLE --}}
                                    <div id="staffAvailableMessage"
                                         class="status-message available-message d-none">

                                        <div class="status-message-icon">
                                            <i class="bi bi-check-lg"></i>
                                        </div>

                                        <div>
                                            <strong>Staff Available</strong>
                                            <p>
                                                Staff is available on the selected date.
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                DATE
                            ================================================== --}}
                            <div class="col-md-4">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon date-label">
                                            <i class="bi bi-calendar3"></i>
                                        </span>

                                        <span>
                                            Appointment Date
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <input type="date"
                                               name="appointment_date"
                                               id="appointment_date"
                                               class="form-control input-3d"
                                               value="{{ old('appointment_date', date('Y-m-d')) }}"
                                               min="{{ date('Y-m-d') }}"
                                               required>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-calendar-event"></i>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                START TIME
                            ================================================== --}}
                            <div class="col-md-4">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon time-label">
                                            <i class="bi bi-clock"></i>
                                        </span>

                                        <span>
                                            Start Time
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <input type="time"
                                               name="start_time"
                                               id="start_time"
                                               class="form-control input-3d"
                                               value="{{ old('start_time') }}"
                                               required>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-clock-history"></i>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                END TIME
                            ================================================== --}}
                            <div class="col-md-4">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon end-time-label">
                                            <i class="bi bi-clock-history"></i>
                                        </span>

                                        <span>
                                            End Time
                                            <b>*</b>
                                        </span>

                                    </label>

                                    <div class="input-3d-wrapper">

                                        <input type="time"
                                               name="end_time"
                                               id="end_time"
                                               class="form-control input-3d"
                                               value="{{ old('end_time') }}"
                                               required>

                                        <span class="input-floating-icon">
                                            <i class="bi bi-check2-circle"></i>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                NOTES
                            ================================================== --}}
                            <div class="col-12">

                                <div class="field-3d">

                                    <label class="field-label">

                                        <span class="label-icon notes-label">
                                            <i class="bi bi-journal-text"></i>
                                        </span>

                                        <span>
                                            Appointment Notes
                                        </span>

                                    </label>

                                    <textarea name="notes"
                                              class="form-control textarea-3d"
                                              rows="4"
                                              maxlength="1000"
                                              placeholder="Add appointment notes...">{{ old('notes') }}</textarea>

                                    <div class="notes-footer">

                                        <small>
                                            <i class="bi bi-shield-check"></i>
                                            Your notes are securely stored.
                                        </small>

                                        <small>
                                            Maximum 1000 characters
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                SIDE PANEL
            ====================================================== --}}
            <div class="col-xl-4">


                {{-- =================================================
                    STATUS CARD
                ================================================== --}}
                <div class="appointment-card side-card mb-4">

                    <div class="side-card-header">

                        <div class="side-icon status-icon">
                            <i class="bi bi-flag"></i>
                        </div>

                        <div>
                            <h5>Appointment Status</h5>
                            <span>Set the current booking status</span>
                        </div>

                    </div>

                    <div class="side-card-body">

                        <label class="field-label mb-3">

                            <span class="label-icon status-label">
                                <i class="bi bi-activity"></i>
                            </span>

                            <span>
                                Status
                                <b>*</b>
                            </span>

                        </label>


                        <div class="status-select-wrapper">

                            <select name="status"
                                    class="form-select input-3d status-select"
                                    required>

                                <option value="pending"
                                    {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="confirmed"
                                    {{ old('status') == 'confirmed' ? 'selected' : '' }}>
                                    Confirmed
                                </option>

                                <option value="arrived"
                                    {{ old('status') == 'arrived' ? 'selected' : '' }}>
                                    Arrived
                                </option>

                                <option value="in_service"
                                    {{ old('status') == 'in_service' ? 'selected' : '' }}>
                                    In Service
                                </option>

                                <option value="completed"
                                    {{ old('status') == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option value="cancelled"
                                    {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled
                                </option>

                                <option value="no_show"
                                    {{ old('status') == 'no_show' ? 'selected' : '' }}>
                                    No Show
                                </option>

                            </select>

                        </div>


                        <div class="status-mini-grid">

                            <div class="mini-status pending">
                                <span></span>
                                Pending
                            </div>

                            <div class="mini-status confirmed">
                                <span></span>
                                Confirmed
                            </div>

                            <div class="mini-status completed">
                                <span></span>
                                Completed
                            </div>

                            <div class="mini-status cancelled">
                                <span></span>
                                Cancelled
                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SERVICE INFORMATION
                ================================================== --}}
                <div class="appointment-card side-card mb-4">

                    <div class="side-card-header">

                        <div class="side-icon service-info-icon">
                            <i class="bi bi-scissors"></i>
                        </div>

                        <div>
                            <h5>Service Information</h5>
                            <span>Selected service details</span>
                        </div>

                    </div>


                    <div class="side-card-body">

                        <div class="service-preview">

                            <div class="service-preview-glow"></div>

                            <div class="service-big-icon">
                                <i class="bi bi-stars"></i>
                            </div>

                            <div class="service-preview-content">

                                <span>
                                    Selected Service
                                </span>

                                <strong id="selectedService">
                                    Not selected
                                </strong>

                            </div>

                        </div>


                        <div class="duration-box">

                            <div class="duration-icon">
                                <i class="bi bi-hourglass-split"></i>
                            </div>

                            <div>

                                <span>Estimated Duration</span>

                                <strong id="serviceDuration">
                                    Not available
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    BOOKING INFORMATION
                ================================================== --}}
                <div class="appointment-card side-card mb-4">

                    <div class="side-card-header">

                        <div class="side-icon info-icon">
                            <i class="bi bi-lightbulb"></i>
                        </div>

                        <div>
                            <h5>Booking Information</h5>
                            <span>Important appointment rules</span>
                        </div>

                    </div>


                    <div class="side-card-body">

                        <div class="booking-rules">

                            <div class="booking-rule">

                                <div class="rule-icon rule-green">
                                    <i class="bi bi-person-check"></i>
                                </div>

                                <div>
                                    <strong>Double Booking Protection</strong>
                                    <span>
                                        Staff double-booking will be prevented.
                                    </span>
                                </div>

                            </div>


                            <div class="booking-rule">

                                <div class="rule-icon rule-blue">
                                    <i class="bi bi-clock"></i>
                                </div>

                                <div>
                                    <strong>Time Validation</strong>
                                    <span>
                                        Start time must be before end time.
                                    </span>
                                </div>

                            </div>


                            <div class="booking-rule">

                                <div class="rule-icon rule-purple">
                                    <i class="bi bi-calendar-check"></i>
                                </div>

                                <div>
                                    <strong>Date Protection</strong>
                                    <span>
                                        Appointment date cannot be in the past.
                                    </span>
                                </div>

                            </div>


                            <div class="booking-rule">

                                <div class="rule-icon rule-orange">
                                    <i class="bi bi-person-badge"></i>
                                </div>

                                <div>
                                    <strong>Staff Leave Check</strong>
                                    <span>
                                        Staff leave will be checked automatically.
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    ACTION CARD
                ================================================== --}}
                <div class="appointment-card action-card">

                    <div class="action-card-content">

                        <div class="action-icon">

                            <i class="bi bi-calendar-heart"></i>

                        </div>

                        <div class="action-text">

                            <strong>Ready to book?</strong>

                            <span>
                                Review the details before creating the appointment.
                            </span>

                        </div>

                    </div>


                    <button type="submit"
                            id="bookAppointmentButton"
                            class="btn-book-3d w-100 mb-3">

                        <span class="book-icon">
                            <i class="bi bi-calendar-check"></i>
                        </span>

                        <span>
                            Book Appointment
                        </span>

                        <i class="bi bi-arrow-right book-arrow"></i>

                    </button>


                    <a href="{{ route('admin.appointments.index') }}"
                       class="btn-cancel-3d w-100">

                        <i class="bi bi-x-lg"></i>

                        Cancel

                    </a>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =========================================================
    3D APPOINTMENT DESIGN
========================================================= --}}
<style>

:root {
    --appointment-bg: #f7f5ff;
    --appointment-white: #ffffff;

    --appointment-heading: #4d4763;
    --appointment-text: #6e6882;
    --appointment-muted: #9690aa;

    --appointment-purple: #8b7cf6;
    --appointment-purple-light: #ebe8ff;

    --appointment-pink: #f29ab2;
    --appointment-pink-light: #ffe8ef;

    --appointment-blue: #73b7ff;
    --appointment-blue-light: #e7f3ff;

    --appointment-mint: #5bc9ab;
    --appointment-mint-light: #e3faf3;

    --appointment-yellow: #f2ca67;
    --appointment-yellow-light: #fff7dc;

    --appointment-orange: #ffb37d;
    --appointment-orange-light: #fff0e5;

    --appointment-cyan: #63c9df;
    --appointment-cyan-light: #e5faff;

    --appointment-border: #ece8fb;

    --appointment-shadow:
        0 20px 50px rgba(123, 103, 198, .11);

    --appointment-soft-shadow:
        0 8px 22px rgba(123, 103, 198, .10);

    --appointment-inner-shadow:
        inset 0 1px 0 rgba(255,255,255,.9),
        inset 0 -2px 6px rgba(123,103,198,.04);
}


/* =========================================================
   MAIN PAGE
========================================================= */

.appointment-3d-page {
    position: relative;
    padding: 4px 0 35px;
    color: var(--appointment-text);
}


/* =========================================================
   WHITE 3D HERO / BANNER
========================================================= */

.appointment-hero {
    position: relative;
    overflow: hidden;

    min-height: 165px;

    border-radius: 30px;

    /*
     * PURE WHITE BANNER
     */
    background: #ffffff;

    /*
     * Very soft border
     */
    border: 1px solid #ece8f7;

    /*
     * Soft 3D depth
     */
    box-shadow:
        0 24px 55px rgba(123,103,198,.10),
        0 8px 20px rgba(123,103,198,.05),
        inset 0 1px 0 rgba(255,255,255,.98);

    transform: translateZ(0);
}


/*
 * Soft purple 3D glow
 * No gradient on the banner itself.
 */
.appointment-hero::before {
    content: "";

    position: absolute;

    width: 280px;
    height: 280px;

    top: -155px;
    right: 7%;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(139,124,246,.09),
            rgba(139,124,246,0) 70%
        );

    pointer-events: none;
}


/*
 * Soft pink 3D glow
 */
.appointment-hero::after {
    content: "";

    position: absolute;

    width: 330px;
    height: 190px;

    right: -80px;
    bottom: -105px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(242,154,178,.10),
            rgba(242,154,178,0) 70%
        );

    pointer-events: none;
}


/* =========================================================
   HERO CONTENT
========================================================= */

.hero-content {
    position: relative;
    z-index: 3;

    min-height: 165px;

    display: flex;
    align-items: center;

    gap: 22px;

    padding: 28px 30px;
}


/* =========================================================
   HERO ICON
========================================================= */

.hero-icon {
    width: 72px;
    height: 72px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 24px;

    color: #7768e9;

    /*
     * White 3D icon box
     */
    background: #ffffff;

    border: 1px solid #e8e3f7;

    font-size: 30px;

    box-shadow:
        0 14px 28px rgba(123,103,198,.13),
        inset 0 2px 0 rgba(255,255,255,.98),
        inset 0 -5px 10px rgba(123,103,198,.035);

    transform:
        perspective(700px)
        rotateX(5deg)
        rotateY(-5deg);
}


/* =========================================================
   HERO TEXT
========================================================= */

.hero-text {
    flex: 1;
}


.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;

    padding: 6px 11px;

    border-radius: 50px;

    background: #ffffff;

    border: 1px solid #e8e3f5;

    color: #8175c4;

    font-size: 11px;
    font-weight: 800;

    text-transform: uppercase;
    letter-spacing: .7px;

    box-shadow:
        0 6px 15px rgba(123,103,198,.07),
        inset 0 1px 0 #ffffff;
}


.live-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: #5bc9ab;

    box-shadow:
        0 0 0 4px rgba(91,201,171,.13);
}


.hero-text h2 {
    margin: 9px 0 4px;

    color: var(--appointment-heading);

    font-size: 28px;
    font-weight: 800;

    letter-spacing: -.6px;
}


.hero-text p {
    margin: 0;

    color: var(--appointment-muted);

    font-size: 14px;
}


/* =========================================================
   HERO ACTION
========================================================= */

.hero-action {
    margin-left: auto;
}


.btn-3d {
    display: inline-flex;
    align-items: center;

    gap: 10px;

    padding: 11px 17px;

    border-radius: 15px;

    text-decoration: none;

    font-size: 13px;
    font-weight: 700;

    transition:
        transform .22s ease,
        box-shadow .22s ease;
}


.btn-back {
    color: #7266ae;

    background: #ffffff;

    border: 1px solid #e7e2f2;

    box-shadow:
        0 9px 20px rgba(123,103,198,.09),
        inset 0 1px 0 #ffffff;
}


.btn-back:hover {
    color: #6759a5;

    transform: translateY(-3px);

    box-shadow:
        0 14px 27px rgba(123,103,198,.14);
}


.btn-icon {
    width: 29px;
    height: 29px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #f0edff;

    color: #8174df;
}


/* =========================================================
   FLOATING 3D ORBS
========================================================= */

.hero-orb {
    position: absolute;

    border-radius: 50%;

    pointer-events: none;

    filter: blur(.2px);

    z-index: 1;
}


.hero-orb-one {
    width: 55px;
    height: 55px;

    right: 30%;
    top: 18px;

    background:
        radial-gradient(
            circle at 35% 30%,
            rgba(115,183,255,.20),
            rgba(115,183,255,.035)
        );

    box-shadow:
        8px 12px 20px rgba(115,183,255,.07);
}


.hero-orb-two {
    width: 32px;
    height: 32px;

    right: 14%;
    top: 90px;

    background:
        radial-gradient(
            circle at 35% 30%,
            rgba(242,154,178,.23),
            rgba(242,154,178,.035)
        );

    box-shadow:
        5px 8px 15px rgba(242,154,178,.07);
}


.hero-orb-three {
    width: 22px;
    height: 22px;

    left: 42%;
    bottom: 15px;

    background:
        radial-gradient(
            circle at 35% 30%,
            rgba(91,201,171,.22),
            rgba(91,201,171,.035)
        );

    box-shadow:
        4px 6px 12px rgba(91,201,171,.07);
}


/* =========================================================
   MESSAGE
========================================================= */

.message-3d {
    position: relative;

    display: flex;
    align-items: flex-start;

    gap: 13px;

    margin-bottom: 20px;

    padding: 15px 18px;

    border-radius: 18px;

    background: #ffffff;

    border: 1px solid var(--appointment-border);

    box-shadow: var(--appointment-soft-shadow);
}


.success-message {
    border-left: 4px solid var(--appointment-mint);
}


.error-message {
    border-left: 4px solid #ef829c;
}


.message-icon {
    width: 38px;
    height: 38px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: var(--appointment-mint-light);

    color: var(--appointment-mint);

    box-shadow:
        inset 0 1px 0 #ffffff,
        0 6px 14px rgba(91,201,171,.10);
}


.error-message .message-icon {
    background: var(--appointment-pink-light);

    color: #e77998;
}


.message-content {
    display: flex;
    flex-direction: column;

    gap: 3px;

    padding-top: 2px;
}


.message-content strong {
    color: var(--appointment-heading);

    font-size: 13px;
}


.message-content span,
.message-content li {
    color: var(--appointment-muted);

    font-size: 12px;
}


.message-content ul {
    margin: 4px 0 0;

    padding-left: 18px;
}


.message-close {
    margin-left: auto;

    border: 0;

    background: transparent;

    color: #aaa4bd;

    padding: 4px;

    cursor: pointer;
}


/* =========================================================
   MAIN CARD
========================================================= */

.appointment-card {
    position: relative;

    background:
        linear-gradient(
            145deg,
            rgba(255,255,255,.98),
            rgba(251,249,255,.98)
        );

    border: 1px solid rgba(236,232,251,.95);

    border-radius: 27px;

    box-shadow:
        var(--appointment-shadow),
        inset 0 1px 0 rgba(255,255,255,.95);

    overflow: hidden;

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}


.appointment-card::before {
    content: "";

    position: absolute;

    width: 180px;
    height: 180px;

    right: -100px;
    top: -100px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(139,124,246,.07),
            transparent 68%
        );

    pointer-events: none;
}


/* =========================================================
   CARD HEADER
========================================================= */

.card-3d-header {
    position: relative;

    display: flex;
    align-items: center;

    gap: 15px;

    padding: 22px 25px;

    border-bottom: 1px solid var(--appointment-border);

    background: #ffffff;
}


.header-icon {
    width: 48px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 16px;

    font-size: 20px;
}


.purple-icon {
    color: #7d70df;

    background: #f0edff;

    box-shadow:
        0 8px 17px rgba(139,124,246,.10),
        inset 0 2px 0 #ffffff;
}


.card-3d-header h4 {
    margin: 0 0 3px;

    color: var(--appointment-heading);

    font-size: 17px;
    font-weight: 800;
}


.card-3d-header p {
    margin: 0;

    color: var(--appointment-muted);

    font-size: 12px;
}


.header-decoration {
    margin-left: auto;

    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #fff5fa;

    color: #ef9bb2;

    transform: rotate(8deg);
}


/* =========================================================
   CARD BODY
========================================================= */

.card-3d-body {
    padding: 28px 25px 30px;
}


/* =========================================================
   FORM FIELDS
========================================================= */

.field-3d {
    position: relative;
}


.field-label {
    display: flex;
    align-items: center;

    gap: 9px;

    margin-bottom: 9px;

    color: #625b78;

    font-size: 12px;
    font-weight: 800;
}


.field-label b {
    color: #e784a1;

    margin-left: 2px;
}


.label-icon {
    width: 27px;
    height: 27px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 12px;
}


.customer-label {
    background: #eeeaff;
    color: #8275e6;
}


.service-label {
    background: #e5faff;
    color: #50b8d0;
}


.branch-label {
    background: #e9f7ff;
    color: #63a8e8;
}


.staff-label {
    background: #eaf9f4;
    color: #54b99d;
}


.date-label {
    background: #fff1f6;
    color: #e995ad;
}


.time-label {
    background: #fff7df;
    color: #d3a83c;
}


.end-time-label {
    background: #edf5ff;
    color: #6e9fe1;
}


.notes-label {
    background: #f1edff;
    color: #8679e8;
}


.status-label {
    background: #fff2e8;
    color: #ed9b68;
}


/* =========================================================
   INPUT WRAPPER
========================================================= */

.input-3d-wrapper {
    position: relative;
}


/* =========================================================
   INPUT / SELECT
========================================================= */

.input-3d {
    width: 100%;

    min-height: 48px;

    border-radius: 14px !important;

    border: 1px solid #e8e3f4 !important;

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #faf9fe
        ) !important;

    color: #5d5772 !important;

    font-size: 13px !important;

    font-weight: 600;

    padding: 11px 42px 11px 14px !important;

    box-shadow:
        inset 0 2px 5px rgba(123,103,198,.035),
        0 5px 12px rgba(123,103,198,.045) !important;

    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        transform .2s ease;
}


.input-3d:hover {
    border-color: #dcd5f3 !important;

    box-shadow:
        0 7px 17px rgba(123,103,198,.08),
        inset 0 1px 0 #ffffff !important;
}


.input-3d:focus {
    border-color: #b6acf4 !important;

    outline: none !important;

    box-shadow:
        0 0 0 4px rgba(139,124,246,.10),
        0 9px 20px rgba(123,103,198,.10),
        inset 0 1px 0 #ffffff !important;

    transform: translateY(-1px);
}


.input-3d:disabled {
    background:
        linear-gradient(
            145deg,
            #f4f2f9,
            #eeecf4
        ) !important;

    color: #aaa4b7 !important;

    cursor: not-allowed;
}


/* =========================================================
   INPUT ICON
========================================================= */

.input-floating-icon {
    position: absolute;

    right: 13px;
    top: 50%;

    transform: translateY(-50%);

    width: 25px;
    height: 25px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    background: #f2effc;

    color: #9186b9;

    font-size: 11px;

    pointer-events: none;
}


input[type="date"].input-3d,
input[type="time"].input-3d {
    padding-right: 43px !important;
}


input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
    opacity: 0;

    position: absolute;

    right: 10px;

    width: 25px;
    height: 25px;

    cursor: pointer;
}


/* =========================================================
   FIELD HELP
========================================================= */

.field-help {
    display: flex;
    align-items: center;

    gap: 5px;

    margin-top: 7px;

    color: #aaa4b7;

    font-size: 10px;
}


.field-help i {
    color: #9b91c8;
}


/* =========================================================
   TEXTAREA
========================================================= */

.textarea-3d {
    width: 100%;

    border-radius: 16px !important;

    border: 1px solid #e8e3f4 !important;

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #faf9fe
        ) !important;

    color: #5d5772 !important;

    padding: 14px !important;

    font-size: 13px !important;

    font-weight: 500;

    resize: vertical;

    box-shadow:
        inset 0 2px 6px rgba(123,103,198,.035),
        0 6px 15px rgba(123,103,198,.045) !important;
}


.textarea-3d:focus {
    outline: none !important;

    border-color: #b6acf4 !important;

    box-shadow:
        0 0 0 4px rgba(139,124,246,.10),
        0 10px 22px rgba(123,103,198,.10) !important;
}


.notes-footer {
    display: flex;

    justify-content: space-between;

    margin-top: 7px;

    color: #aaa4b7;

    font-size: 10px;
}


.notes-footer i {
    color: #66c8ab;

    margin-right: 3px;
}


/* =========================================================
   STAFF STATUS
========================================================= */

.status-message {
    display: flex;
    align-items: flex-start;

    gap: 10px;

    margin-top: 10px;

    padding: 11px 12px;

    border-radius: 14px;

    border: 1px solid transparent;

    box-shadow:
        0 6px 14px rgba(123,103,198,.07);
}


.status-message-icon {
    width: 29px;
    height: 29px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    font-size: 12px;
}


.status-message strong {
    display: block;

    font-size: 11px;

    font-weight: 800;
}


.status-message p {
    margin: 2px 0 0;

    font-size: 10px;

    line-height: 1.5;
}


.leave-message {
    background: #fff0f4;

    border-color: #ffd5df;

    color: #c96f88;
}


.leave-message .status-message-icon {
    background: #ffe0e8;

    color: #df7895;
}


.checking-message {
    background: #f1efff;

    border-color: #ddd7ff;

    color: #8175c9;
}


.available-message {
    background: #eafaf5;

    border-color: #d1f1e7;

    color: #4da88e;
}


.available-message .status-message-icon {
    background: #d8f5eb;

    color: #51b69b;
}


/* =========================================================
   LOADING SPINNER
========================================================= */

.spinner-3d {
    width: 26px;
    height: 26px;

    flex-shrink: 0;

    border-radius: 50%;

    border: 3px solid #ddd8fa;

    border-top-color: #897ce8;

    animation:
        spin3d .8s linear infinite;
}


@keyframes spin3d {

    to {
        transform: rotate(360deg);
    }

}


/* =========================================================
   SIDE CARD
========================================================= */

.side-card {
    border-radius: 25px;

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}


.side-card-header {
    display: flex;
    align-items: center;

    gap: 12px;

    padding: 18px 20px;

    border-bottom: 1px solid var(--appointment-border);

    background: #ffffff;
}


.side-icon {
    width: 43px;
    height: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    font-size: 17px;

    box-shadow:
        0 7px 15px rgba(123,103,198,.08),
        inset 0 1px 0 #ffffff;
}


.status-icon {
    background: #fff1e7;
    color: #ed9d6d;
}


.service-info-icon {
    background: #e7faff;
    color: #59b8d0;
}


.info-icon {
    background: #eeebff;
    color: #8175df;
}


.side-card-header h5 {
    margin: 0 0 3px;

    color: var(--appointment-heading);

    font-size: 14px;

    font-weight: 800;
}


.side-card-header span {
    color: #aaa4b7;

    font-size: 10px;
}


.side-card-body {
    padding: 20px;
}


/* =========================================================
   STATUS
========================================================= */

.status-select {
    padding-right: 14px !important;
}


.status-mini-grid {
    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 8px;

    margin-top: 14px;
}


.mini-status {
    display: flex;
    align-items: center;

    gap: 7px;

    padding: 8px 9px;

    border-radius: 11px;

    background: #faf9fd;

    border: 1px solid #eeeaf7;

    color: #817b91;

    font-size: 9px;

    font-weight: 700;
}


.mini-status span {
    width: 7px;
    height: 7px;

    border-radius: 50%;
}


.mini-status.pending span {
    background: #f2ca67;
}


.mini-status.confirmed span {
    background: #73b7ff;
}


.mini-status.completed span {
    background: #5bc9ab;
}


.mini-status.cancelled span {
    background: #f29ab2;
}


/* =========================================================
   SERVICE PREVIEW
========================================================= */

.service-preview {
    position: relative;

    display: flex;
    align-items: center;

    gap: 13px;

    overflow: hidden;

    padding: 15px;

    border-radius: 17px;

    background:
        linear-gradient(
            135deg,
            #f0fbff,
            #f7f4ff
        );

    border: 1px solid #e5e9fa;

    box-shadow:
        0 9px 20px rgba(99,201,223,.08),
        inset 0 1px 0 #ffffff;
}


.service-preview-glow {
    position: absolute;

    width: 100px;
    height: 100px;

    right: -45px;
    top: -45px;

    border-radius: 50%;

    background:
        radial-gradient(
            circle,
            rgba(99,201,223,.15),
            transparent 70%
        );
}


.service-big-icon {
    width: 43px;
    height: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 14px;

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #e9faff
        );

    color: #5bbbd2;

    box-shadow:
        0 8px 16px rgba(99,201,223,.12),
        inset 0 1px 0 #ffffff;
}


.service-preview-content {
    min-width: 0;

    display: flex;
    flex-direction: column;
}


.service-preview-content span {
    color: #aaa4b7;

    font-size: 9px;

    font-weight: 600;
}


.service-preview-content strong {
    margin-top: 3px;

    color: #625b78;

    font-size: 12px;

    font-weight: 800;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}


/* =========================================================
   DURATION
========================================================= */

.duration-box {
    display: flex;
    align-items: center;

    gap: 11px;

    margin-top: 11px;

    padding: 12px;

    border-radius: 14px;

    background: #fffaf0;

    border: 1px solid #f8edcf;
}


.duration-icon {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #fff1c9;

    color: #d4ad4a;

    font-size: 13px;
}


.duration-box span {
    display: block;

    color: #aaa4b7;

    font-size: 9px;
}


.duration-box strong {
    display: block;

    margin-top: 2px;

    color: #776b4d;

    font-size: 12px;
}


/* =========================================================
   BOOKING RULES
========================================================= */

.booking-rules {
    display: flex;

    flex-direction: column;

    gap: 11px;
}


.booking-rule {
    display: flex;
    align-items: flex-start;

    gap: 10px;

    padding: 10px;

    border-radius: 14px;

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #faf9fd
        );

    border: 1px solid #eeeaf6;

    box-shadow:
        0 5px 12px rgba(123,103,198,.045);
}


.rule-icon {
    width: 31px;
    height: 31px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    font-size: 11px;
}


.rule-green {
    background: #e5f9f1;
    color: #55b798;
}


.rule-blue {
    background: #e8f4ff;
    color: #65a7e6;
}


.rule-purple {
    background: #efecff;
    color: #8377df;
}


.rule-orange {
    background: #fff0e5;
    color: #e89c69;
}


.booking-rule strong {
    display: block;

    color: #696277;

    font-size: 10px;

    font-weight: 800;
}


.booking-rule span {
    display: block;

    margin-top: 2px;

    color: #aaa4b7;

    font-size: 9px;

    line-height: 1.45;
}


/* =========================================================
   ACTION CARD
========================================================= */

.action-card {
    padding: 19px;

    background:
        linear-gradient(
            145deg,
            #ffffff,
            #f8f6ff
        );
}


.action-card-content {
    display: flex;
    align-items: center;

    gap: 11px;

    margin-bottom: 15px;
}


.action-icon {
    width: 43px;
    height: 43px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;

    background:
        linear-gradient(
            145deg,
            #f0edff,
            #e8e2ff
        );

    color: #8174df;

    box-shadow:
        0 8px 17px rgba(139,124,246,.11),
        inset 0 1px 0 #ffffff;
}


.action-text {
    display: flex;

    flex-direction: column;
}


.action-text strong {
    color: var(--appointment-heading);

    font-size: 12px;

    font-weight: 800;
}


.action-text span {
    margin-top: 2px;

    color: #aaa4b7;

    font-size: 9px;
}


/* =========================================================
   BOOK BUTTON
========================================================= */

.btn-book-3d {
    position: relative;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 9px;

    min-height: 52px;

    border: 0;

    border-radius: 16px;

    color: #ffffff;

    background:
        linear-gradient(
            135deg,
            #9b8dfb 0%,
            #8274ed 50%,
            #7163df 100%
        );

    font-size: 13px;

    font-weight: 800;

    box-shadow:
        0 12px 24px rgba(139,124,246,.25),
        inset 0 2px 0 rgba(255,255,255,.28),
        inset 0 -5px 9px rgba(77,63,165,.14);

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        filter .2s ease;

    cursor: pointer;
}


.btn-book-3d:hover {
    color: #ffffff;

    transform: translateY(-3px);

    box-shadow:
        0 17px 29px rgba(139,124,246,.31),
        inset 0 2px 0 rgba(255,255,255,.32);
}


.btn-book-3d:active {
    transform: translateY(1px);
}


.btn-book-3d:disabled {
    opacity: .65;

    cursor: not-allowed;

    transform: none;

    box-shadow:
        0 7px 14px rgba(139,124,246,.12);
}


.book-icon {
    width: 29px;
    height: 29px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background: rgba(255,255,255,.18);

    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.25);
}


.book-arrow {
    position: absolute;

    right: 17px;

    font-size: 12px;

    opacity: .8;
}


/* =========================================================
   CANCEL BUTTON
========================================================= */

.btn-cancel-3d {
    display: flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    min-height: 45px;

    border-radius: 14px;

    text-decoration: none;

    color: #817a91;

    background: #ffffff;

    border: 1px solid #e8e3f2;

    font-size: 12px;

    font-weight: 700;

    box-shadow:
        0 6px 14px rgba(123,103,198,.06),
        inset 0 1px 0 #ffffff;

    transition:
        transform .2s ease,
        box-shadow .2s ease;
}


.btn-cancel-3d:hover {
    color: #6f687f;

    transform: translateY(-2px);

    box-shadow:
        0 10px 20px rgba(123,103,198,.10);
}


/* =========================================================
   SELECT ARROW
========================================================= */

select.input-3d {
    appearance: none;

    -webkit-appearance: none;

    background-image: none !important;
}


/* =========================================================
   CARD HOVER 3D
========================================================= */

.appointment-card:hover,
.side-card:hover,
.action-card:hover {
    box-shadow:
        0 25px 60px rgba(123,103,198,.14),
        inset 0 1px 0 rgba(255,255,255,.98);
}


.appointment-card:hover {
    transform: translateY(-2px);
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 1199.98px) {

    .hero-content {
        padding: 25px;
    }

}


@media (max-width: 767.98px) {

    .appointment-3d-page {
        padding-bottom: 20px;
    }


    .appointment-hero {
        min-height: auto;

        border-radius: 22px;
    }


    .hero-content {
        min-height: auto;

        flex-wrap: wrap;

        padding: 22px 19px;

        gap: 14px;
    }


    .hero-icon {
        width: 55px;
        height: 55px;

        border-radius: 18px;

        font-size: 23px;
    }


    .hero-text {
        min-width: calc(100% - 70px);
    }


    .hero-text h2 {
        font-size: 21px;
    }


    .hero-text p {
        font-size: 11px;
    }


    .hero-action {
        width: 100%;

        margin-left: 0;

        margin-top: 4px;
    }


    .btn-back {
        width: 100%;

        justify-content: center;
    }


    .card-3d-header {
        padding: 18px;
    }


    .card-3d-body {
        padding: 21px 17px;
    }


    .side-card-header {
        padding: 17px;
    }


    .side-card-body {
        padding: 17px;
    }


    .header-decoration {
        display: none;
    }


    .status-mini-grid {
        grid-template-columns: 1fr 1fr;
    }


    .notes-footer {
        flex-direction: column;

        gap: 4px;
    }

}


@media (max-width: 420px) {

    .hero-text h2 {
        font-size: 18px;
    }


    .field-label {
        font-size: 11px;
    }


    .input-3d {
        min-height: 46px;

        font-size: 12px !important;
    }


    .status-mini-grid {
        grid-template-columns: 1fr;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media (prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {
        animation-duration: .01ms !important;

        animation-iteration-count: 1 !important;

        transition-duration: .01ms !important;
    }

}

</style>
{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById('appointmentForm');

    const branchSelect =
        document.getElementById('branch_id');

    const staffSelect =
        document.getElementById('staff_id');

    const appointmentDate =
        document.getElementById('appointment_date');

    const bookButton =
        document.getElementById('bookAppointmentButton');

    const staffLeaveWarning =
        document.getElementById('staffLeaveWarning');

    const staffLeaveMessage =
        document.getElementById('staffLeaveMessage');

    const staffLeaveChecking =
        document.getElementById('staffLeaveChecking');

    const staffAvailableMessage =
        document.getElementById('staffAvailableMessage');

    const oldStaffId =
        "{{ old('staff_id') }}";


    /*
    |--------------------------------------------------------------------------
    | STORE ORIGINAL STAFF OPTIONS
    |--------------------------------------------------------------------------
    */

    const originalStaffOptions =
        Array.from(
            staffSelect.querySelectorAll('option[data-branch-id]')
        ).map(function (option) {

            return option.cloneNode(true);

        });


    /*
    |--------------------------------------------------------------------------
    | RESET LEAVE STATUS
    |--------------------------------------------------------------------------
    */

    function resetStaffLeaveStatus() {

        staffLeaveWarning.classList.add('d-none');

        staffAvailableMessage.classList.add('d-none');

        staffLeaveChecking.classList.add('d-none');

        staffLeaveMessage.textContent =
            'This staff member is on leave on the selected date. Please select another staff member.';

        bookButton.disabled = false;

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW CHECKING
    |--------------------------------------------------------------------------
    */

    function showChecking() {

        staffLeaveWarning.classList.add('d-none');

        staffAvailableMessage.classList.add('d-none');

        staffLeaveChecking.classList.remove('d-none');

        bookButton.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW STAFF LEAVE
    |--------------------------------------------------------------------------
    */

    function showStaffLeave(message) {

        staffLeaveChecking.classList.add('d-none');

        staffAvailableMessage.classList.add('d-none');

        staffLeaveMessage.textContent =
            message ||
            'This staff member is on leave on the selected date. Please select another staff member.';

        staffLeaveWarning.classList.remove('d-none');

        bookButton.disabled = true;

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW STAFF AVAILABLE
    |--------------------------------------------------------------------------
    */

    function showStaffAvailable() {

        staffLeaveChecking.classList.add('d-none');

        staffLeaveWarning.classList.add('d-none');

        staffAvailableMessage.classList.remove('d-none');

        bookButton.disabled = false;

    }


    /*
    |--------------------------------------------------------------------------
    | BRANCH → STAFF FILTER
    |--------------------------------------------------------------------------
    */

    function filterStaffByBranch() {

        const selectedBranchId =
            branchSelect.value;


        /*
        |--------------------------------------------------------------------------
        | NO BRANCH
        |--------------------------------------------------------------------------
        */

        if (!selectedBranchId) {

            staffSelect.innerHTML =
                '<option value="">Select Branch First</option>';

            staffSelect.disabled = true;

            resetStaffLeaveStatus();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | CLEAR STAFF
        |--------------------------------------------------------------------------
        */

        staffSelect.innerHTML =
            '<option value="">Select Staff</option>';

        let hasStaff = false;


        /*
        |--------------------------------------------------------------------------
        | ADD STAFF OF SELECTED BRANCH
        |--------------------------------------------------------------------------
        */

        originalStaffOptions.forEach(function (option) {

            const staffBranchId =
                option.getAttribute('data-branch-id');


            if (
                String(staffBranchId) ===
                String(selectedBranchId)
            ) {

                staffSelect.appendChild(
                    option.cloneNode(true)
                );

                hasStaff = true;

            }

        });


        /*
        |--------------------------------------------------------------------------
        | NO STAFF FOUND
        |--------------------------------------------------------------------------
        */

        if (!hasStaff) {

            staffSelect.innerHTML =
                '<option value="">No staff available for this branch</option>';

            staffSelect.disabled = true;

            resetStaffLeaveStatus();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | ENABLE STAFF
        |--------------------------------------------------------------------------
        */

        staffSelect.disabled = false;


        /*
        |--------------------------------------------------------------------------
        | RESTORE OLD STAFF AFTER VALIDATION ERROR
        |--------------------------------------------------------------------------
        */

        if (oldStaffId) {

            const oldOption =
                staffSelect.querySelector(
                    'option[value="' + oldStaffId + '"]'
                );


            if (oldOption) {

                oldOption.selected = true;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | CHECK LEAVE
        |--------------------------------------------------------------------------
        */

        checkStaffLeave();

    }


    /*
    |--------------------------------------------------------------------------
    | STAFF LEAVE AJAX CHECK
    |--------------------------------------------------------------------------
    */

    function checkStaffLeave() {

        const staffId =
            staffSelect.value;

        const branchId =
            branchSelect.value;

        const selectedDate =
            appointmentDate.value;


        /*
        |--------------------------------------------------------------------------
        | REQUIRED DATA MISSING
        |--------------------------------------------------------------------------
        */

        if (
            !staffId ||
            !branchId ||
            !selectedDate
        ) {

            resetStaffLeaveStatus();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | SHOW CHECKING
        |--------------------------------------------------------------------------
        */

        showChecking();


        /*
        |--------------------------------------------------------------------------
        | AJAX REQUEST
        |--------------------------------------------------------------------------
        */

        const url =
            "{{ route('admin.appointments.check-staff-leave') }}" +
            '?staff_id=' +
            encodeURIComponent(staffId) +
            '&branch_id=' +
            encodeURIComponent(branchId) +
            '&appointment_date=' +
            encodeURIComponent(selectedDate);


        fetch(url, {

            method: 'GET',

            headers: {

                'Accept': 'application/json',

                'X-Requested-With': 'XMLHttpRequest'

            }

        })

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Unable to check staff availability.'
                );

            }

            return response.json();

        })

        .then(function (data) {

            /*
            |--------------------------------------------------------------------------
            | STAFF ON LEAVE
            |--------------------------------------------------------------------------
            */

            if (data.on_leave) {

                showStaffLeave(
                    data.message
                );

                staffSelect.value = '';

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | STAFF AVAILABLE
            |--------------------------------------------------------------------------
            */

            showStaffAvailable();

        })

        .catch(function (error) {

            console.error(
                'Staff leave check error:',
                error
            );


            /*
            |--------------------------------------------------------------------------
            | DO NOT BLOCK COMPLETELY ON AJAX ERROR
            |--------------------------------------------------------------------------
            */

            staffLeaveChecking.classList.add('d-none');

            staffAvailableMessage.classList.add('d-none');

            bookButton.disabled = false;

        });

    }


    /*
    |--------------------------------------------------------------------------
    | BRANCH CHANGED
    |--------------------------------------------------------------------------
    */

    branchSelect.addEventListener(
        'change',
        function () {

            staffSelect.value = '';

            resetStaffLeaveStatus();

            filterStaffByBranch();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | STAFF CHANGED
    |--------------------------------------------------------------------------
    */

    staffSelect.addEventListener(
        'change',
        function () {

            checkStaffLeave();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | APPOINTMENT DATE CHANGED
    |--------------------------------------------------------------------------
    */

    appointmentDate.addEventListener(
        'change',
        function () {

            checkStaffLeave();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT PROTECTION
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {


            /*
            |--------------------------------------------------------------------------
            | STAFF LEAVE
            |--------------------------------------------------------------------------
            */

            if (
                !staffLeaveWarning.classList.contains('d-none')
            ) {

                event.preventDefault();

                alert(
                    'The selected staff member is on leave on the selected date. Please select another staff member.'
                );

                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | CHECKING
            |--------------------------------------------------------------------------
            */

            if (
                !staffLeaveChecking.classList.contains('d-none')
            ) {

                event.preventDefault();

                alert(
                    'Please wait while staff availability is being checked.'
                );

                return false;

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL BRANCH FILTER
    |--------------------------------------------------------------------------
    */

    filterStaffByBranch();


    /*
    |--------------------------------------------------------------------------
    | SERVICE DURATION + END TIME
    |--------------------------------------------------------------------------
    */

    const serviceSelect =
        document.getElementById('service_id');

    const startTimeInput =
        document.getElementById('start_time');

    const endTimeInput =
        document.getElementById('end_time');

    const selectedService =
        document.getElementById('selectedService');

    const serviceDuration =
        document.getElementById('serviceDuration');


    /*
    |--------------------------------------------------------------------------
    | CALCULATE END TIME
    |--------------------------------------------------------------------------
    */

    function calculateEndTime() {

        const selectedOption =
            serviceSelect.options[
                serviceSelect.selectedIndex
            ];


        /*
        |--------------------------------------------------------------------------
        | NO SERVICE
        |--------------------------------------------------------------------------
        */

        if (
            !selectedOption ||
            !selectedOption.value
        ) {

            selectedService.textContent =
                'Not selected';

            serviceDuration.textContent =
                'Not available';

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | SERVICE NAME
        |--------------------------------------------------------------------------
        */

        const serviceName =
            selectedOption.textContent.trim();


        /*
        |--------------------------------------------------------------------------
        | SERVICE DURATION
        |--------------------------------------------------------------------------
        */

        const duration =
            selectedOption.dataset.duration;


        selectedService.textContent =
            serviceName;


        /*
        |--------------------------------------------------------------------------
        | SHOW DURATION
        |--------------------------------------------------------------------------
        */

        if (duration) {

            serviceDuration.textContent =
                duration + ' minutes';

        } else {

            serviceDuration.textContent =
                'Not available';

        }


        /*
        |--------------------------------------------------------------------------
        | START TIME / DURATION MISSING
        |--------------------------------------------------------------------------
        */

        if (
            !startTimeInput.value ||
            !duration
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | CONVERT DURATION
        |--------------------------------------------------------------------------
        */

        let durationMinutes =
            parseInt(duration);


        /*
        |--------------------------------------------------------------------------
        | TEXT DURATION SUPPORT
        |--------------------------------------------------------------------------
        */

        if (isNaN(durationMinutes)) {

            const durationText =
                duration.toLowerCase();


            if (
                durationText.includes('1.5')
            ) {

                durationMinutes = 90;

            } else if (
                durationText.includes('2 hour') ||
                durationText.includes('2 hours')
            ) {

                durationMinutes = 120;

            } else if (
                durationText.includes('3 hour') ||
                durationText.includes('3 hours')
            ) {

                durationMinutes = 180;

            } else if (
                durationText.includes('1 hour') ||
                durationText.includes('1 hours')
            ) {

                durationMinutes = 60;

            } else if (
                durationText.includes('45')
            ) {

                durationMinutes = 45;

            } else if (
                durationText.includes('30')
            ) {

                durationMinutes = 30;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | INVALID DURATION
        |--------------------------------------------------------------------------
        */

        if (isNaN(durationMinutes)) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | START TIME
        |--------------------------------------------------------------------------
        */

        const parts =
            startTimeInput.value.split(':');


        let hours =
            parseInt(parts[0]);

        let minutes =
            parseInt(parts[1]);


        /*
        |--------------------------------------------------------------------------
        | TOTAL MINUTES
        |--------------------------------------------------------------------------
        */

        let totalMinutes =
            hours * 60 +
            minutes +
            durationMinutes;


        /*
        |--------------------------------------------------------------------------
        | PREVENT TIME BEYOND 23:59
        |--------------------------------------------------------------------------
        */

        if (
            totalMinutes >= 24 * 60
        ) {

            totalMinutes =
                23 * 60 + 59;

        }


        /*
        |--------------------------------------------------------------------------
        | END TIME
        |--------------------------------------------------------------------------
        */

        const endHours =
            Math.floor(
                totalMinutes / 60
            );

        const endMinutes =
            totalMinutes % 60;


        const formattedHours =
            String(endHours).padStart(2, '0');

        const formattedMinutes =
            String(endMinutes).padStart(2, '0');


        endTimeInput.value =
            `${formattedHours}:${formattedMinutes}`;

    }


    /*
    |--------------------------------------------------------------------------
    | SERVICE CHANGED
    |--------------------------------------------------------------------------
    */

    serviceSelect.addEventListener(
        'change',
        calculateEndTime
    );


    /*
    |--------------------------------------------------------------------------
    | START TIME CHANGED
    |--------------------------------------------------------------------------
    */

    startTimeInput.addEventListener(
        'change',
        calculateEndTime
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL SERVICE CALCULATION
    |--------------------------------------------------------------------------
    */

    calculateEndTime();

});

</script>

@endsection