@extends('admin.layout')

@section('title', 'Appointments | Salon Management')

@section('page-title', 'Appointments')

@section('content')

<div class="appointments-page">

    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="alert appointment-alert alert-success alert-dismissible fade show"
             role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
         HEADER / WHITE 3D BANNER
    ========================================================== --}}
    <div class="appointment-banner">

        <div class="banner-decoration banner-decoration-one"></div>
        <div class="banner-decoration banner-decoration-two"></div>
        <div class="banner-decoration banner-decoration-three"></div>

        <div class="appointment-banner-content">

            <div class="appointment-heading">

                <div class="heading-accent"></div>

                <div>
                    <h4 class="appointment-title mb-1">
                        All Appointments
                    </h4>

                    <p class="appointment-subtitle mb-0">
                        Manage salon appointments and booking status.
                    </p>
                </div>

            </div>


            <div class="appointment-header-actions">

                {{-- Calendar --}}
                <a href="{{ route('admin.appointments.calendar') }}"
                   class="appointment-btn appointment-btn-calendar">

                    <i class="bi bi-calendar3"></i>
                    <span>Calendar</span>

                </a>


                {{-- Add Appointment --}}
                <a href="{{ route('admin.appointments.create') }}"
                   class="appointment-btn appointment-btn-primary">

                    <i class="bi bi-plus-lg"></i>
                    <span>Add Appointment</span>

                </a>

            </div>

        </div>

    </div>



    {{-- =========================================================
         FILTER CARD
    ========================================================== --}}
    <div class="appointment-card appointment-filter-card">

        <div class="appointment-card-header">

            <div class="card-heading-left">

                <div class="card-heading-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>
                    <h6 class="mb-0">
                        Filter Appointments
                    </h6>

                    <span>
                        Search and filter appointment records
                    </span>
                </div>

            </div>

        </div>


        <div class="appointment-card-body">

            <form method="GET"
                  action="{{ route('admin.appointments.index') }}">

                <div class="row g-3">


                    {{-- =================================================
                         SEARCH
                    ================================================== --}}
                    <div class="col-lg-3 col-md-6">

                        <label class="appointment-label">
                            Search Customer
                        </label>

                        <div class="appointment-input-wrap">

                            <input type="text"
                                   name="search"
                                   class="appointment-input"
                                   placeholder="Name or phone"
                                   value="{{ request('search') }}">

                        </div>

                    </div>



                    {{-- =================================================
                         BRANCH
                    ================================================== --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="appointment-label">
                            Branch
                        </label>

                        <select name="branch_id"
                                class="appointment-input appointment-select">

                            <option value="">
                                All Branches
                            </option>

                            @foreach($branches as $branch)

                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>

                                    {{ $branch->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- =================================================
                         DATE
                    ================================================== --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="appointment-label">
                            Appointment Date
                        </label>

                        <input type="date"
                               name="appointment_date"
                               class="appointment-input"
                               value="{{ request('appointment_date') }}">

                    </div>



                    {{-- =================================================
                         STATUS
                    ================================================== --}}
                    <div class="col-lg-2 col-md-6">

                        <label class="appointment-label">
                            Status
                        </label>

                        <select name="status"
                                class="appointment-input appointment-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="confirmed"
                                {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>

                            <option value="arrived"
                                {{ request('status') == 'arrived' ? 'selected' : '' }}>
                                Arrived
                            </option>

                            <option value="in_service"
                                {{ request('status') == 'in_service' ? 'selected' : '' }}>
                                In Service
                            </option>

                            <option value="completed"
                                {{ request('status') == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="cancelled"
                                {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                            <option value="no_show"
                                {{ request('status') == 'no_show' ? 'selected' : '' }}>
                                No Show
                            </option>

                        </select>

                    </div>



                    {{-- =================================================
                         BUTTONS
                    ================================================== --}}
                    <div class="col-lg-3 col-md-6 d-flex align-items-end gap-2">

                        <button type="submit"
                                class="appointment-filter-btn">

                            <i class="bi bi-funnel"></i>
                            <span>Filter</span>

                        </button>


                        <a href="{{ route('admin.appointments.index') }}"
                           class="appointment-reset-btn"
                           title="Reset">

                            <i class="bi bi-arrow-counterclockwise"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- =========================================================
         APPOINTMENTS TABLE
    ========================================================== --}}
    <div class="appointment-card appointment-table-card">

        <div class="appointment-table-header">

            <div class="table-heading-left">

                <div class="table-heading-icon">
                    <i class="bi bi-calendar-check"></i>
                </div>

                <div>

                    <h5 class="mb-0">
                        Appointments
                    </h5>

                    <span>
                        Appointment records
                    </span>

                </div>

            </div>


            <div class="appointment-total">

                {{ $appointments->total() }} Total

            </div>

        </div>



        <div class="appointment-table-wrapper">

            <div class="table-responsive">

                <table class="table appointment-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Customer</th>

                            <th>Service</th>

                            <th>Staff</th>

                            <th>Branch</th>

                            <th>Date</th>

                            <th>Time</th>

                            <th>Status</th>

                            <th class="text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($appointments as $appointment)

                            <tr>

                                {{-- =================================================
                                     NUMBER
                                ================================================== --}}
                                <td class="appointment-number">

                                    {{ $loop->iteration + (($appointments->currentPage() - 1) * $appointments->perPage()) }}

                                </td>



                                {{-- =================================================
                                     CUSTOMER
                                ================================================== --}}
                                <td>

                                    <div class="customer-name">

                                        {{ $appointment->customer->name ?? 'N/A' }}

                                    </div>


                                    @if($appointment->customer?->phone)

                                        <div class="customer-secondary">

                                            {{ $appointment->customer->phone }}

                                        </div>

                                    @endif

                                </td>



                                {{-- =================================================
                                     SERVICE
                                ================================================== --}}
                                <td>

                                    <div class="appointment-main-text">

                                        {{ $appointment->service->name ?? 'N/A' }}

                                    </div>


                                    @if($appointment->service?->category)

                                        <div class="appointment-secondary-text">

                                            {{ $appointment->service->category }}

                                        </div>

                                    @endif

                                </td>



                                {{-- =================================================
                                     STAFF
                                ================================================== --}}
                                <td>

                                    @if($appointment->staff)

                                        <div class="appointment-main-text">

                                            {{ $appointment->staff->name }}

                                        </div>


                                        @if($appointment->staff->role)

                                            <div class="appointment-secondary-text">

                                                {{ $appointment->staff->role }}

                                            </div>

                                        @endif

                                    @else

                                        <span class="appointment-na">
                                            N/A
                                        </span>

                                    @endif

                                </td>



                                {{-- =================================================
                                     BRANCH
                                ================================================== --}}
                                <td>

                                    @if($appointment->branch)

                                        <span class="branch-badge">

                                            {{ $appointment->branch->name }}

                                        </span>

                                    @else

                                        <span class="appointment-na">
                                            N/A
                                        </span>

                                    @endif

                                </td>



                                {{-- =================================================
                                     DATE
                                ================================================== --}}
                                <td>

                                    @if($appointment->appointment_date)

                                        <span class="appointment-date">

                                            {{ $appointment->appointment_date->format('d M Y') }}

                                        </span>

                                    @else

                                        <span class="appointment-na">
                                            N/A
                                        </span>

                                    @endif

                                </td>



                                {{-- =================================================
                                     TIME
                                ================================================== --}}
                                <td>

                                    @if($appointment->start_time && $appointment->end_time)

                                        <div class="appointment-time">

                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}

                                            <span>-</span>

                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}

                                        </div>

                                    @else

                                        <span class="appointment-na">
                                            N/A
                                        </span>

                                    @endif

                                </td>



                                {{-- =================================================
                                     STATUS
                                ================================================== --}}
                                <td>

                                    @php

                                        $statusClasses = [

                                            'pending' =>
                                                'status-pending',

                                            'confirmed' =>
                                                'status-confirmed',

                                            'arrived' =>
                                                'status-arrived',

                                            'in_service' =>
                                                'status-in-service',

                                            'completed' =>
                                                'status-completed',

                                            'cancelled' =>
                                                'status-cancelled',

                                            'no_show' =>
                                                'status-no-show',

                                        ];


                                        $statusLabels = [

                                            'pending' =>
                                                'Pending',

                                            'confirmed' =>
                                                'Confirmed',

                                            'arrived' =>
                                                'Arrived',

                                            'in_service' =>
                                                'In Service',

                                            'completed' =>
                                                'Completed',

                                            'cancelled' =>
                                                'Cancelled',

                                            'no_show' =>
                                                'No Show',

                                        ];

                                    @endphp


                                    <span class="appointment-status {{ $statusClasses[$appointment->status] ?? 'status-no-show' }}">

                                        {{ $statusLabels[$appointment->status] ?? ucfirst(str_replace('_', ' ', $appointment->status)) }}

                                    </span>

                                </td>



                                {{-- =================================================
                                     ACTIONS
                                ================================================== --}}
                                <td class="text-center">

                                    <div class="appointment-actions">

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                           class="appointment-action-edit"
                                           title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        {{-- Delete --}}
                                        <form action="{{ route('admin.appointments.destroy', $appointment->id) }}"
                                              method="POST"
                                              class="delete-appointment-form d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="appointment-action-delete"
                                                    title="Delete">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9">

                                    <div class="appointment-empty">

                                        <div class="empty-circle">
                                            <i class="bi bi-calendar-x"></i>
                                        </div>

                                        <h5>
                                            No Appointments Found
                                        </h5>

                                        <p>
                                            There are no appointments matching your filters.
                                        </p>

                                        <a href="{{ route('admin.appointments.create') }}"
                                           class="appointment-btn appointment-btn-primary">

                                            <i class="bi bi-plus-lg"></i>
                                            <span>Add Appointment</span>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        @if($appointments->hasPages())

            <div class="appointment-pagination-wrapper">

                <div class="appointment-pagination">

                    {{ $appointments->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection



{{-- =========================================================
     3D APPOINTMENT PAGE STYLES
========================================================= --}}
@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Main Page
    |--------------------------------------------------------------------------
    */

    .appointments-page {
        position: relative;
        width: 100%;
        padding: 4px 2px 30px;
    }



    /*
    |--------------------------------------------------------------------------
    | Success Alert
    |--------------------------------------------------------------------------
    */

    .appointment-alert {
        border: 0;
        border-radius: 16px;
        padding: 13px 18px;
        margin-bottom: 18px;

        box-shadow:
            0 12px 28px rgba(55, 45, 90, 0.08),
            inset 0 1px 0 rgba(255, 255, 255, 0.85);
    }



    /*
    |--------------------------------------------------------------------------
    | White Hero Banner
    |--------------------------------------------------------------------------
    */

    .appointment-banner {
        position: relative;
        overflow: hidden;

        background: #ffffff;

        border: 1px solid #eeeaf7;

        border-radius: 26px;

        padding: 27px 30px;

        margin-bottom: 22px;

        box-shadow:
            0 20px 48px rgba(75, 60, 120, 0.09),
            0 7px 18px rgba(75, 60, 120, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }


    .appointment-banner::before {
        content: "";

        position: absolute;

        top: 0;
        left: 0;

        width: 42%;
        height: 4px;

        background: linear-gradient(
            90deg,
            #7c5cff,
            #b28cff,
            #ff8fbd
        );

        border-radius: 0 0 10px 0;
    }


    .appointment-banner::after {
        content: "";

        position: absolute;

        right: -90px;
        bottom: -110px;

        width: 260px;
        height: 260px;

        border-radius: 50%;

        background: radial-gradient(
            circle,
            rgba(124, 92, 255, 0.10),
            rgba(124, 92, 255, 0)
        );

        pointer-events: none;
    }



    /*
    |--------------------------------------------------------------------------
    | Banner Decorations
    |--------------------------------------------------------------------------
    */

    .banner-decoration {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
    }


    .banner-decoration-one {
        width: 75px;
        height: 75px;

        right: 23%;
        top: -35px;

        background: rgba(255, 126, 182, 0.08);
    }


    .banner-decoration-two {
        width: 42px;
        height: 42px;

        right: 11%;
        bottom: 17px;

        background: rgba(110, 168, 254, 0.10);
    }


    .banner-decoration-three {
        width: 22px;
        height: 22px;

        right: 30%;
        bottom: 20px;

        background: rgba(83, 184, 137, 0.12);
    }



    /*
    |--------------------------------------------------------------------------
    | Banner Content
    |--------------------------------------------------------------------------
    */

    .appointment-banner-content {
        position: relative;
        z-index: 2;

        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;
    }


    .appointment-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }


    .heading-accent {
        width: 6px;
        height: 48px;

        border-radius: 10px;

        background: linear-gradient(
            180deg,
            #7c5cff,
            #ff82b8
        );

        box-shadow:
            0 5px 15px rgba(124, 92, 255, 0.20);
    }


    .appointment-title {
        color: #27243a;
        letter-spacing: -0.2px;
    }


    .appointment-subtitle {
        color: #878394;
    }



    /*
    |--------------------------------------------------------------------------
    | Header Buttons
    |--------------------------------------------------------------------------
    */

    .appointment-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }


    .appointment-btn {
        position: relative;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 8px;

        min-height: 42px;

        padding: 9px 17px;

        border-radius: 13px;

        text-decoration: none;

        border: 1px solid transparent;

        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease,
            border-color 0.22s ease;
    }


    .appointment-btn:hover {
        transform: translateY(-2px);
    }


    .appointment-btn-calendar {
        color: #6657a6;

        background: linear-gradient(
            145deg,
            #ffffff,
            #f4f0ff
        );

        border-color: #e6e0fb;

        box-shadow:
            0 7px 16px rgba(89, 70, 150, 0.08),
            inset 0 1px 0 #ffffff;
    }


    .appointment-btn-calendar:hover {
        color: #5a4a9d;

        box-shadow:
            0 12px 24px rgba(89, 70, 150, 0.13);
    }


    .appointment-btn-primary {
        color: #ffffff;

        background: linear-gradient(
            135deg,
            #7c5cff,
            #9477ff
        );

        box-shadow:
            0 10px 22px rgba(124, 92, 255, 0.25),
            inset 0 1px 0 rgba(255, 255, 255, 0.30);
    }


    .appointment-btn-primary:hover {
        color: #ffffff;

        box-shadow:
            0 15px 28px rgba(124, 92, 255, 0.31);
    }



    /*
    |--------------------------------------------------------------------------
    | Cards
    |--------------------------------------------------------------------------
    */

    .appointment-card {
        background: #ffffff;

        border: 1px solid #eeeaf6;

        border-radius: 23px;

        margin-bottom: 22px;

        overflow: hidden;

        box-shadow:
            0 18px 45px rgba(75, 60, 120, 0.08),
            0 6px 15px rgba(75, 60, 120, 0.045),
            inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }



    /*
    |--------------------------------------------------------------------------
    | Filter Header
    |--------------------------------------------------------------------------
    */

    .appointment-card-header {
        padding: 18px 22px;

        border-bottom: 1px solid #f0edf7;

        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfbff
        );
    }


    .card-heading-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }


    .card-heading-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        color: #715de0;

        background: linear-gradient(
            145deg,
            #f4f0ff,
            #ebe5ff
        );

        box-shadow:
            0 7px 15px rgba(104, 81, 187, 0.10),
            inset 0 1px 0 #ffffff;
    }


    .card-heading-left h6 {
        color: #302c40;
    }


    .card-heading-left span {
        display: block;

        margin-top: 2px;

        color: #9691a2;
    }



    /*
    |--------------------------------------------------------------------------
    | Filter Body
    |--------------------------------------------------------------------------
    */

    .appointment-card-body {
        padding: 22px;
    }


    .appointment-label {
        display: block;

        margin-bottom: 7px;

        color: #514c61;
    }


    .appointment-input {
        width: 100%;

        min-height: 43px;

        padding: 9px 13px;

        border: 1px solid #e6e1f1;

        border-radius: 12px;

        background: #fbfaff;

        color: #3c384b;

        outline: none;

        box-shadow:
            inset 0 2px 5px rgba(74, 58, 120, 0.035),
            0 2px 5px rgba(74, 58, 120, 0.025);

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }


    .appointment-input:focus {
        border-color: #a997f3;

        background: #ffffff;

        box-shadow:
            0 0 0 3px rgba(124, 92, 255, 0.08),
            0 8px 18px rgba(78, 61, 133, 0.07);
    }


    .appointment-input::placeholder {
        color: #aaa6b5;
    }


    .appointment-select {
        cursor: pointer;
    }



    /*
    |--------------------------------------------------------------------------
    | Filter Buttons
    |--------------------------------------------------------------------------
    */

    .appointment-filter-btn {
        width: 100%;

        min-height: 43px;

        border: 0;

        border-radius: 12px;

        color: #ffffff;

        background: linear-gradient(
            135deg,
            #7c5cff,
            #967cff
        );

        box-shadow:
            0 9px 19px rgba(124, 92, 255, 0.22),
            inset 0 1px 0 rgba(255, 255, 255, 0.25);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .appointment-filter-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 13px 25px rgba(124, 92, 255, 0.28);
    }


    .appointment-reset-btn {
        width: 43px;
        min-width: 43px;
        height: 43px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        color: #716b80;

        text-decoration: none;

        border: 1px solid #e5e0ef;

        border-radius: 12px;

        background: #ffffff;

        box-shadow:
            0 5px 12px rgba(72, 59, 105, 0.06),
            inset 0 1px 0 #ffffff;

        transition: 0.2s ease;
    }


    .appointment-reset-btn:hover {
        color: #6653c9;

        border-color: #d8d0f4;

        transform: translateY(-2px);

        box-shadow:
            0 9px 17px rgba(72, 59, 105, 0.10);
    }



    /*
    |--------------------------------------------------------------------------
    | Table Header
    |--------------------------------------------------------------------------
    */

    .appointment-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 18px 22px;

        background: linear-gradient(
            180deg,
            #ffffff,
            #fcfbff
        );

        border-bottom: 1px solid #eeeaf6;
    }


    .table-heading-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }


    .table-heading-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 12px;

        color: #6b58cf;

        background: linear-gradient(
            145deg,
            #f2eeff,
            #eae4ff
        );

        box-shadow:
            0 8px 17px rgba(101, 78, 182, 0.10),
            inset 0 1px 0 #ffffff;
    }


    .table-heading-left h5 {
        color: #302c40;
    }


    .table-heading-left span {
        display: block;

        margin-top: 2px;

        color: #9691a2;
    }


    .appointment-total {
        padding: 7px 13px;

        border-radius: 10px;

        color: #6a55ca;

        background: #f1edff;

        border: 1px solid #e4dcff;

        box-shadow:
            0 5px 12px rgba(102, 81, 181, 0.07);
    }



    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    .appointment-table-wrapper {
        padding: 0;
    }


    .appointment-table {
        min-width: 1050px;
    }


    .appointment-table thead th {
        padding: 14px 16px;

        color: #696477;

        background: #faf8ff;

        border-bottom: 1px solid #ece8f5;

        white-space: nowrap;
    }


    .appointment-table tbody tr {
        position: relative;

        transition:
            background 0.2s ease,
            transform 0.2s ease;
    }


    .appointment-table tbody tr:hover {
        background: linear-gradient(
            90deg,
            #fcfaff,
            #ffffff,
            #fffafd
        );
    }


    .appointment-table tbody td {
        padding: 16px;

        border-color: #f1eef7;

        color: #454052;

        vertical-align: middle;
    }


    .appointment-number {
        color: #9a95a6 !important;
    }


    .customer-name,
    .appointment-main-text {
        color: #312d3e;
    }


    /*
    |--------------------------------------------------------------------------
    | Customer Phone
    |--------------------------------------------------------------------------
    |
    | Existing font-family and font-size are NOT changed.
    |
    */

    .customer-secondary {
        margin-top: 4px;

        color: #8a8497;

        font-weight: 500;
    }


    .appointment-secondary-text {
        margin-top: 3px;

        color: #9892a4;
    }


    .appointment-na {
        color: #aaa5b3;
    }


    .branch-badge {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        border-radius: 9px;

        color: #625b76;

        background: #f7f4ff;

        border: 1px solid #e9e3fa;

        box-shadow:
            0 4px 9px rgba(74, 60, 112, 0.045);
    }


    .appointment-date {
        color: #514c5e;
    }


    .appointment-time {
        color: #514c5e;

        white-space: nowrap;
    }


    .appointment-time span {
        color: #aaa5b3;

        margin: 0 3px;
    }



    /*
    |--------------------------------------------------------------------------
    | Status Pills
    |--------------------------------------------------------------------------
    */

    .appointment-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-width: 82px;

        padding: 6px 10px;

        border-radius: 20px;

        border: 1px solid transparent;

        box-shadow:
            0 5px 10px rgba(70, 58, 105, 0.055);
    }


    .status-pending {
        color: #9a6c17;

        background: #fff6df;

        border-color: #f6e4b7;
    }


    .status-confirmed {
        color: #6752bf;

        background: #f0ecff;

        border-color: #dfd6ff;
    }


    .status-arrived {
        color: #3877b8;

        background: #edf6ff;

        border-color: #d9ebff;
    }


    .status-in-service {
        color: #7650ae;

        background: #f5edff;

        border-color: #e8d9ff;
    }


    .status-completed {
        color: #34865f;

        background: #eaf9f1;

        border-color: #d5efdf;
    }


    .status-cancelled {
        color: #bd6374;

        background: #fff0f3;

        border-color: #f8dce2;
    }


    .status-no-show {
        color: #77717e;

        background: #f3f2f5;

        border-color: #e5e3e8;
    }



    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    .appointment-actions {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }


    .appointment-action-edit,
    .appointment-action-delete {
        width: 34px;
        height: 34px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        text-decoration: none;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    .appointment-action-edit {
        color: #6c58d1;

        background: #f2eeff;

        border: 1px solid #e3dcff;

        box-shadow:
            0 5px 10px rgba(91, 71, 167, 0.07);
    }


    .appointment-action-delete {
        color: #d36d7e;

        background: #fff0f3;

        border: 1px solid #f7dce2;

        box-shadow:
            0 5px 10px rgba(184, 86, 105, 0.07);
    }


    .appointment-action-edit:hover,
    .appointment-action-delete:hover {
        transform: translateY(-2px);
    }


    .appointment-action-edit:hover {
        color: #5844bf;

        box-shadow:
            0 9px 16px rgba(91, 71, 167, 0.13);
    }


    .appointment-action-delete:hover {
        color: #c45368;

        box-shadow:
            0 9px 16px rgba(184, 86, 105, 0.13);
    }



    /*
    |--------------------------------------------------------------------------
    | Empty State
    |--------------------------------------------------------------------------
    */

    .appointment-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        min-height: 330px;

        padding: 45px 20px;

        text-align: center;
    }


    .empty-circle {
        width: 72px;
        height: 72px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 16px;

        border-radius: 50%;

        color: #765fe0;

        background: linear-gradient(
            145deg,
            #f4f0ff,
            #ebe5ff
        );

        border: 1px solid #e3dcff;

        box-shadow:
            0 15px 30px rgba(100, 78, 184, 0.12),
            inset 0 1px 0 #ffffff;
    }


    .appointment-empty h5 {
        color: #393444;
    }


    .appointment-empty p {
        color: #9892a2;

        margin-bottom: 20px;
    }



    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    .appointment-pagination-wrapper {
        padding: 16px 20px;

        border-top: 1px solid #eeeaf6;

        background: #ffffff;
    }


    .appointment-pagination {
        display: flex;
        justify-content: flex-end;
    }


    .appointment-pagination nav {
        margin: 0;
    }


    .appointment-pagination svg {
        width: 12px !important;
        height: 12px !important;
    }


    .appointment-pagination a,
    .appointment-pagination span {
        padding: 6px 10px !important;

        border-radius: 8px !important;
    }



    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    @media (max-width: 991.98px) {

        .appointment-banner-content {
            align-items: flex-start;
            flex-direction: column;
        }


        .appointment-header-actions {
            width: 100%;
        }


        .appointment-header-actions .appointment-btn {
            flex: 1;
        }


        .appointment-pagination {
            justify-content: center;
        }

    }


    @media (max-width: 575.98px) {

        .appointments-page {
            padding-left: 0;
            padding-right: 0;
        }


        .appointment-banner {
            padding: 22px 18px;
            border-radius: 20px;
        }


        .appointment-card {
            border-radius: 19px;
        }


        .appointment-card-body {
            padding: 17px;
        }


        .appointment-table-header {
            padding: 16px;
        }


        .appointment-header-actions {
            flex-direction: column;
        }


        .appointment-header-actions .appointment-btn {
            width: 100%;
        }


        .appointment-total {
            display: none;
        }

    }

</style>

@endpush



{{-- =========================================================
     DELETE CONFIRMATION
========================================================= --}}
@push('scripts')

<script>

    document.querySelectorAll('.delete-appointment-form')
        .forEach(function(form) {

            form.addEventListener('submit', function(e) {

                if (!confirm(
                    'Are you sure you want to delete this appointment?'
                )) {

                    e.preventDefault();

                }

            });

        });

</script>

@endpush