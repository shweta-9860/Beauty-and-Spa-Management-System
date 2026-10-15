@extends('admin.layout')

@section('title', 'Appointment Calendar | Salon Management')
@section('page-title', 'Appointment Calendar')

@section('content')

<div class="calendar-3d-page">

    {{-- =========================================================
         3D HERO BANNER
         ========================================================= --}}
    <div class="calendar-hero mb-4">

        <div class="hero-orb hero-orb-one"></div>
        <div class="hero-orb hero-orb-two"></div>
        <div class="hero-orb hero-orb-three"></div>

        <div class="hero-content">

            <div class="d-flex flex-column flex-lg-row
                        justify-content-between
                        align-items-lg-center gap-4">

                {{-- Left --}}
                <div>

                    <div class="live-pill mb-3">
                        <span class="live-dot"></span>
                        Live Calendar
                    </div>

                    <h2 class="hero-title mb-2">
                        <span class="hero-icon">
                            <i class="bi bi-calendar3"></i>
                        </span>

                        Appointment Calendar
                    </h2>

                    <p class="hero-subtitle mb-0">
                        View and manage your appointments by day, week and month.
                    </p>

                    <div class="calendar-hint mt-3">
                        <i class="bi bi-hand-index-thumb me-1"></i>
                        Click any date to create an appointment
                    </div>

                </div>


                {{-- Right Actions --}}
                <div class="hero-actions d-flex flex-wrap gap-2">

                    <a href="{{ route('admin.appointments.index') }}"
                       class="calendar-btn calendar-btn-light">

                        <span class="btn-icon">
                            <i class="bi bi-list"></i>
                        </span>

                        <span>
                            All Appointments
                        </span>

                    </a>


                    <a href="{{ route('admin.appointments.create') }}"
                       class="calendar-btn calendar-btn-primary">

                        <span class="btn-icon">
                            <i class="bi bi-plus-lg"></i>
                        </span>

                        <span>
                            Add Appointment
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         STATUS LEGEND
         ========================================================= --}}
    <div class="calendar-legend mb-4">

        <div class="legend-title">
            <i class="bi bi-palette2"></i>
            Appointment Status
        </div>

        <div class="legend-items">

            <span class="legend-item">
                <span class="legend-dot confirmed"></span>
                Confirmed
            </span>

            <span class="legend-item">
                <span class="legend-dot pending"></span>
                Pending
            </span>

            <span class="legend-item">
                <span class="legend-dot completed"></span>
                Completed
            </span>

            <span class="legend-item">
                <span class="legend-dot cancelled"></span>
                Cancelled
            </span>

        </div>

    </div>


    {{-- =========================================================
         CALENDAR CARD
         ========================================================= --}}
    <div class="calendar-card">

        <div class="calendar-card-glow"></div>

        <div class="calendar-card-inner">

            <div id="appointmentCalendar"></div>

        </div>

    </div>

</div>

@endsection


{{-- =============================================================
     STYLES
     ============================================================= --}}
@push('styles')

<link
    href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css"
    rel="stylesheet"
>

<style>

    /* =========================================================
       PAGE
       ========================================================= */

    .calendar-3d-page {

        --calendar-bg: #f7f5ff;
        --calendar-surface: #ffffff;

        --calendar-text: #5b5670;
        --calendar-heading: #51496f;

        --calendar-primary: #8878f4;
        --calendar-primary-dark: #7564df;

        --calendar-pink: #f29ab2;
        --calendar-blue: #73b7ff;
        --calendar-mint: #5bc9ab;
        --calendar-peach: #ffb37d;
        --calendar-yellow: #f2ca67;
        --calendar-cyan: #63c9df;

        --calendar-border: #e9e4fa;

        width: 100%;
        position: relative;
    }


    /* =========================================================
       HERO BANNER - PURE WHITE 3D
       ========================================================= */

    .calendar-hero {

        position: relative;
        overflow: hidden;

        min-height: 190px;

        padding: 30px 32px;

        border-radius: 28px;

        /* PURE WHITE BANNER */
        background: #ffffff !important;

        /* Soft lavender border */
        border: 1px solid #e5def8;

        /* 3D shadow */
        box-shadow:

            0 25px 55px rgba(91, 74, 165, 0.12),

            0 10px 24px rgba(91, 74, 165, 0.07),

            inset 0 1px 0 rgba(255,255,255,1),

            inset 0 -10px 25px rgba(96, 75, 173, 0.025);

        isolation: isolate;

        transition:
            transform .3s ease,
            box-shadow .3s ease;
    }


    .calendar-hero:hover {

        transform: translateY(-2px);

        box-shadow:

            0 30px 65px rgba(91, 74, 165, 0.15),

            0 12px 26px rgba(91, 74, 165, 0.08),

            inset 0 1px 0 rgba(255,255,255,1);
    }


    /* =========================================================
       HERO DECORATIVE ORBS
       ========================================================= */

    .hero-orb {

        position: absolute;

        border-radius: 50%;

        pointer-events: none;

        z-index: 0;

        filter: blur(.2px);
    }


    /* Top right - subtle lavender glass orb */

    .hero-orb-one {

        width: 210px;
        height: 210px;

        top: -120px;
        right: 8%;

        background:
            radial-gradient(
                circle at 35% 35%,
                rgba(139,124,246,.12),
                rgba(139,124,246,.035) 55%,
                transparent 72%
            );

        box-shadow:
            0 20px 55px rgba(104, 84, 190, .06);
    }


    /* Bottom right - subtle pink orb */

    .hero-orb-two {

        width: 145px;
        height: 145px;

        right: -45px;
        bottom: -70px;

        background:
            radial-gradient(
                circle at 35% 35%,
                rgba(242,154,178,.16),
                rgba(242,154,178,.035) 65%,
                transparent 75%
            );
    }


    /* Bottom center - subtle lavender orb */

    .hero-orb-three {

        width: 95px;
        height: 95px;

        left: 42%;
        bottom: -58px;

        background:
            radial-gradient(
                circle at 35% 35%,
                rgba(139,124,246,.08),
                rgba(139,124,246,.02) 65%,
                transparent 75%
            );
    }


    .hero-content {

        position: relative;

        z-index: 5;
    }


    /* =========================================================
       LIVE PILL
       ========================================================= */

    .live-pill {

        display: inline-flex;

        align-items: center;

        gap: 8px;

        padding: 7px 14px;

        border-radius: 50px;

        background: #f7f5ff;

        border: 1px solid #e6e0f8;

        color: #625783;

        font-size: 12px;

        font-weight: 800;

        letter-spacing: .2px;

        box-shadow:

            0 8px 20px rgba(87, 69, 145, .08),

            inset 0 1px 0 rgba(255,255,255,1);

        backdrop-filter: blur(10px);

        -webkit-backdrop-filter: blur(10px);
    }


    .live-dot {

        width: 8px;
        height: 8px;

        border-radius: 50%;

        background: #5bc9ab;

        box-shadow:

            0 0 0 4px rgba(91,201,171,.16),

            0 3px 8px rgba(91,201,171,.35);

        animation: livePulse 2s infinite;
    }


    @keyframes livePulse {

        0%,
        100% {

            transform: scale(1);
        }

        50% {

            transform: scale(1.22);
        }
    }


    /* =========================================================
       HERO TITLE
       ========================================================= */

    .hero-title {

        display: flex;

        align-items: center;

        gap: 12px;

        color: #51496f !important;

        font-size: 29px;

        font-weight: 850;

        letter-spacing: -.6px;

        text-shadow:

            0 2px 0 rgba(255,255,255,.95),

            0 6px 16px rgba(87,69,145,.10);
    }


    .hero-icon {

        width: 48px;

        height: 48px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 15px;

        background:
            linear-gradient(
                145deg,
                #f8f6ff,
                #ece8ff
            );

        color: #7767dc;

        font-size: 21px;

        border: 1px solid #e5def8;

        box-shadow:

            0 10px 22px rgba(85,67,160,.12),

            inset 0 1px 0 rgba(255,255,255,1),

            inset 0 -5px 10px rgba(116,96,190,.05);
    }


    .hero-subtitle {

        color: #665f80 !important;

        font-size: 14px;

        font-weight: 550;

        max-width: 600px;
    }


    .calendar-hint {

        display: inline-flex;

        align-items: center;

        padding: 6px 11px;

        border-radius: 10px;

        background: #faf9ff;

        border: 1px solid #e8e2f7;

        color: #716987;

        font-size: 11px;

        font-weight: 650;

        box-shadow:

            0 4px 10px rgba(104,87,164,.05);
    }


    /* =========================================================
       HERO BUTTONS
       ========================================================= */

    .hero-actions {

        position: relative;

        z-index: 10;
    }


    .calendar-btn {

        display: inline-flex;

        align-items: center;

        gap: 9px;

        min-height: 48px;

        padding: 9px 15px;

        border-radius: 15px;

        text-decoration: none !important;

        font-size: 13px;

        font-weight: 750;

        border: 1px solid rgba(255,255,255,.75);

        transition:

            transform .25s ease,

            box-shadow .25s ease;
    }


    .calendar-btn .btn-icon {

        width: 29px;

        height: 29px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 9px;
    }


    .calendar-btn-light {

        color: #5c5575;

        background: #ffffff;

        border-color: #e5def7;

        box-shadow:

            0 9px 20px rgba(84,68,143,.10),

            inset 0 1px 0 rgba(255,255,255,1);
    }


    .calendar-btn-light .btn-icon {

        background: #f0edff;

        color: #7565dc;
    }


    .calendar-btn-light:hover {

        color: #51496f;

        transform: translateY(-4px);

        box-shadow:

            0 15px 28px rgba(84,68,143,.15),

            inset 0 1px 0 rgba(255,255,255,1);
    }


    .calendar-btn-primary {

        color: #ffffff;

        background:
            linear-gradient(
                135deg,
                #8878f4,
                #a18ef7
            );

        border-color: rgba(255,255,255,.20);

        box-shadow:

            0 11px 25px rgba(105,86,205,.31),

            inset 0 1px 0 rgba(255,255,255,.38);
    }


    .calendar-btn-primary .btn-icon {

        background: rgba(255,255,255,.18);

        color: #fff;
    }


    .calendar-btn-primary:hover {

        color: #fff;

        transform: translateY(-4px);

        box-shadow:

            0 17px 32px rgba(105,86,205,.38),

            inset 0 1px 0 rgba(255,255,255,.42);
    }


    /* =========================================================
       LEGEND
       ========================================================= */

    .calendar-legend {

        display: flex;

        align-items: center;

        justify-content: space-between;

        flex-wrap: wrap;

        gap: 15px;

        padding: 14px 18px;

        border-radius: 18px;

        background: rgba(255,255,255,.82);

        border: 1px solid #ebe7f8;

        box-shadow:

            0 10px 25px rgba(104,87,164,.08),

            inset 0 1px 0 rgba(255,255,255,.95);
    }


    .legend-title {

        display: flex;

        align-items: center;

        gap: 8px;

        color: #625b78;

        font-size: 12px;

        font-weight: 800;
    }


    .legend-title i {

        width: 27px;

        height: 27px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 8px;

        background: #f0edff;

        color: #8071e8;
    }


    .legend-items {

        display: flex;

        align-items: center;

        flex-wrap: wrap;

        gap: 15px;
    }


    .legend-item {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        color: #77708b;

        font-size: 11px;

        font-weight: 650;
    }


    .legend-dot {

        width: 9px;

        height: 9px;

        border-radius: 50%;

        box-shadow: 0 3px 7px rgba(0,0,0,.12);
    }


    .legend-dot.confirmed {

        background: #73b7ff;
    }


    .legend-dot.pending {

        background: #f2ca67;
    }


    .legend-dot.completed {

        background: #5bc9ab;
    }


    .legend-dot.cancelled {

        background: #f29ab2;
    }


    /* =========================================================
       CALENDAR CARD
       ========================================================= */

    .calendar-card {

        position: relative;

        border-radius: 28px;

        background:
            linear-gradient(
                145deg,
                #ffffff,
                #fbfaff
            );

        border: 1px solid #ebe7f8;

        box-shadow:

            0 24px 55px rgba(104,87,164,.13),

            0 8px 18px rgba(104,87,164,.08),

            inset 0 1px 0 rgba(255,255,255,.95);

        overflow: hidden;
    }


    .calendar-card-glow {

        position: absolute;

        width: 280px;

        height: 280px;

        right: -150px;

        top: -160px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(139,124,246,.12),
                transparent 68%
            );

        pointer-events: none;
    }


    .calendar-card-inner {

        position: relative;

        z-index: 2;

        padding: 22px;
    }


    #appointmentCalendar {

        min-height: 700px;
    }


    /* =========================================================
       FULLCALENDAR BASE
       ========================================================= */

    .calendar-3d-page .fc {

        --fc-border-color: #ece8f7;

        --fc-page-bg-color: transparent;

        --fc-neutral-bg-color: #faf9ff;

        --fc-list-event-hover-bg-color: #f7f4ff;

        color: #69627d;

        font-family: inherit;
    }


    /* =========================================================
       TOOLBAR
       ========================================================= */

    .calendar-3d-page .fc .fc-toolbar {

        margin-bottom: 22px;

        gap: 15px;

        flex-wrap: wrap;
    }


    .calendar-3d-page .fc .fc-toolbar-title {

        color: #51496f;

        font-size: 23px;

        font-weight: 800;

        letter-spacing: -.4px;
    }


    .calendar-3d-page .fc .fc-toolbar-title::before {

        content: "\f214";

        font-family: "bootstrap-icons";

        margin-right: 8px;

        color: #8878f4;

        font-size: 19px;
    }


    /* =========================================================
       FULLCALENDAR BUTTONS
       ========================================================= */

    .calendar-3d-page .fc .fc-button {

        border: 1px solid #e6e1f5 !important;

        background: #ffffff !important;

        color: #6b6480 !important;

        border-radius: 11px !important;

        min-height: 38px;

        padding: 6px 12px;

        font-size: 12px;

        font-weight: 750;

        box-shadow:

            0 5px 12px rgba(104,87,164,.07),

            inset 0 1px 0 rgba(255,255,255,.9);

        transition:

            transform .2s ease,

            box-shadow .2s ease,

            background .2s ease;
    }


    .calendar-3d-page .fc .fc-button:hover {

        background: #f8f6ff !important;

        color: #5c5290 !important;

        transform: translateY(-2px);

        box-shadow:

            0 8px 17px rgba(104,87,164,.12);
    }


    .calendar-3d-page .fc .fc-button:focus {

        box-shadow:

            0 0 0 3px rgba(139,124,246,.12) !important;
    }


    .calendar-3d-page .fc .fc-button-primary:not(:disabled).fc-button-active,

    .calendar-3d-page .fc .fc-button-primary:not(:disabled):active {

        background:
            linear-gradient(
                135deg,
                #8878f4,
                #a08df6
            ) !important;

        border-color: #8878f4 !important;

        color: #ffffff !important;

        box-shadow:

            0 8px 18px rgba(105,86,205,.25),

            inset 0 1px 0 rgba(255,255,255,.30);
    }


    .calendar-3d-page .fc .fc-button-primary {

        background: #ffffff !important;

        border-color: #e6e1f5 !important;

        color: #6b6480 !important;
    }


    /* Today */

    .calendar-3d-page .fc .fc-today-button {

        background:
            linear-gradient(
                135deg,
                #f0edff,
                #e8e3ff
            ) !important;

        color: #7565dc !important;

        border-color: #dcd5fa !important;
    }


    /* Previous / Next */

    .calendar-3d-page .fc .fc-prev-button,

    .calendar-3d-page .fc .fc-next-button {

        width: 38px;

        height: 38px;

        padding: 0 !important;

        border-radius: 50% !important;

        display: inline-flex;

        align-items: center;

        justify-content: center;
    }


    /* =========================================================
       CALENDAR GRID
       ========================================================= */

    .calendar-3d-page .fc .fc-scrollgrid {

        border: 0 !important;

        border-radius: 20px;

        overflow: hidden;

        background: #ffffff;

        box-shadow:

            inset 0 0 0 1px #eeeaf8;
    }


    .calendar-3d-page .fc th {

        background:
            linear-gradient(
                180deg,
                #faf9ff,
                #f6f3ff
            );

        border-color: #ece8f7 !important;

        color: #77708a;

        font-size: 11px;

        font-weight: 800;

        text-transform: uppercase;

        letter-spacing: .5px;

        padding: 12px 5px;
    }


    .calendar-3d-page .fc td {

        border-color: #eeeaf8 !important;
    }


    /* =========================================================
       DAY CELLS
       ========================================================= */

    .calendar-3d-page .fc .fc-daygrid-day {

        background: #ffffff;

        transition:

            background .2s ease,

            box-shadow .2s ease;
    }


    .calendar-3d-page .fc .fc-daygrid-day:hover {

        background:
            linear-gradient(
                145deg,
                #ffffff,
                #faf8ff
            );

        box-shadow:

            inset 0 0 0 2px rgba(139,124,246,.07);
    }


    .calendar-3d-page .fc .fc-daygrid-day-frame {

        min-height: 115px;

        padding: 6px;

        transition: transform .2s ease;
    }


    .calendar-3d-page .fc .fc-daygrid-day:hover
    .fc-daygrid-day-frame {

        transform: translateY(-1px);
    }


    /* =========================================================
       DATE NUMBER
       ========================================================= */

    .calendar-3d-page .fc .fc-daygrid-day-number {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-width: 29px;

        height: 29px;

        padding: 0 7px;

        border-radius: 9px;

        color: #77708a;

        font-size: 12px;

        font-weight: 750;

        transition:

            background .2s ease,

            color .2s ease;
    }


    .calendar-3d-page .fc .fc-daygrid-day:hover
    .fc-daygrid-day-number {

        background: #f0edff;

        color: #7666dc;
    }


    /* Other month */

    .calendar-3d-page .fc .fc-day-other {

        background: #fbfaff !important;
    }


    .calendar-3d-page .fc .fc-day-other
    .fc-daygrid-day-number {

        opacity: .42;
    }


    /* =========================================================
       TODAY
       ========================================================= */

    .calendar-3d-page .fc .fc-day-today {

        background:
            linear-gradient(
                145deg,
                rgba(139,124,246,.07),
                rgba(242,154,178,.05)
            ) !important;
    }


    .calendar-3d-page .fc .fc-day-today
    .fc-daygrid-day-number {

        background:
            linear-gradient(
                135deg,
                #8878f4,
                #a28ff6
            );

        color: #ffffff;

        box-shadow:

            0 6px 13px rgba(105,86,205,.24);
    }


    /* =========================================================
       EVENTS
       ========================================================= */

    .calendar-3d-page .fc-event {

        cursor: pointer;

        border: 0 !important;

        border-radius: 9px !important;

        padding: 4px 7px !important;

        margin: 2px 3px !important;

        min-height: 27px;

        overflow: hidden;

        font-size: 11px;

        font-weight: 700;

        box-shadow:

            0 4px 9px rgba(80,65,130,.12),

            inset 0 1px 0 rgba(255,255,255,.38);

        transition:

            transform .18s ease,

            box-shadow .18s ease;
    }


    .calendar-3d-page .fc-event:hover {

        transform: translateY(-2px) scale(1.01);

        box-shadow:

            0 8px 16px rgba(80,65,130,.17),

            inset 0 1px 0 rgba(255,255,255,.45);

        z-index: 5;
    }


    .calendar-3d-page .fc-daygrid-event {

        white-space: nowrap;

        overflow: hidden;

        text-overflow: ellipsis;
    }


    /* =========================================================
       EVENT CONTENT
       ========================================================= */

    .calendar-3d-page .fc-custom-event {

        display: flex;

        align-items: center;

        gap: 5px;

        width: 100%;

        overflow: hidden;
    }


    .calendar-3d-page .fc-event-dot {

        flex: 0 0 auto;

        width: 6px;

        height: 6px;

        border-radius: 50%;

        background: rgba(255,255,255,.90);

        box-shadow:

            0 1px 4px rgba(0,0,0,.15);
    }


    .calendar-3d-page .fc-event-time {

        flex: 0 0 auto;

        font-weight: 800;
    }


    .calendar-3d-page .fc-event-customer {

        min-width: 0;

        overflow: hidden;

        text-overflow: ellipsis;

        white-space: nowrap;
    }


    /* =========================================================
       STATUS COLORS
       ========================================================= */

    .calendar-3d-page .fc-event.status-completed {

        background:
            linear-gradient(
                135deg,
                #72d5b8,
                #55c4a5
            ) !important;

        color: #ffffff !important;
    }


    .calendar-3d-page .fc-event.status-confirmed {

        background:
            linear-gradient(
                135deg,
                #83c1ff,
                #68aef5
            ) !important;

        color: #ffffff !important;
    }


    .calendar-3d-page .fc-event.status-pending {

        background:
            linear-gradient(
                135deg,
                #f5d27c,
                #eec45f
            ) !important;

        color: #6d5a26 !important;
    }


    .calendar-3d-page .fc-event.status-cancelled {

        background:
            linear-gradient(
                135deg,
                #f5a6bc,
                #ee8faa
            ) !important;

        color: #ffffff !important;
    }


    .calendar-3d-page .fc-event.status-arrived {

        background:
            linear-gradient(
                135deg,
                #aa99f4,
                #8f7ce8
            ) !important;

        color: #ffffff !important;
    }


    .calendar-3d-page .fc-event.status-in_service {

        background:
            linear-gradient(
                135deg,
                #78d5e3,
                #59bfd0
            ) !important;

        color: #ffffff !important;
    }


    .calendar-3d-page .fc-event.status-no_show {

        background:
            linear-gradient(
                135deg,
                #ffc18f,
                #f4a96e
            ) !important;

        color: #ffffff !important;
    }


    .calendar-3d-page .fc-event.status-default {

        background:
            linear-gradient(
                135deg,
                #aaa0f5,
                #8e80e5
            ) !important;

        color: #ffffff !important;
    }


    /* =========================================================
       MORE LINK
       ========================================================= */

    .calendar-3d-page .fc-daygrid-more-link {

        display: inline-flex;

        align-items: center;

        margin: 4px 4px;

        padding: 4px 8px;

        border-radius: 8px;

        background: #f0edff;

        color: #7665dc !important;

        font-size: 10px;

        font-weight: 800;

        text-decoration: none;

        transition:

            transform .2s ease,

            background .2s ease;
    }


    .calendar-3d-page .fc-daygrid-more-link:hover {

        background: #e7e2ff;

        transform: translateY(-1px);
    }


    /* =========================================================
       WEEK / DAY VIEW
       ========================================================= */

    .calendar-3d-page .fc .fc-timegrid-slot {

        height: 44px;

        border-color: #eeeaf8 !important;
    }


    .calendar-3d-page .fc .fc-timegrid-slot-label {

        color: #918aa3;

        font-size: 10px;

        font-weight: 650;
    }


    .calendar-3d-page .fc .fc-timegrid-axis {

        background: #fbfaff;
    }


    .calendar-3d-page .fc .fc-timegrid-col {

        background: #ffffff;
    }


    .calendar-3d-page .fc .fc-timegrid-col.fc-day-today {

        background:
            linear-gradient(
                180deg,
                rgba(139,124,246,.055),
                rgba(139,124,246,.015)
            ) !important;
    }


    .calendar-3d-page .fc .fc-timegrid-now-indicator-line {

        border-color: #f29ab2;
    }


    .calendar-3d-page .fc .fc-timegrid-now-indicator-arrow {

        border-color: #f29ab2;
    }


    /* =========================================================
       POPOVER
       ========================================================= */

    .calendar-3d-page .fc .fc-popover {

        border: 1px solid #e7e2f5;

        border-radius: 16px;

        overflow: hidden;

        background: #ffffff;

        box-shadow:

            0 22px 45px rgba(70,55,125,.18),

            0 7px 18px rgba(70,55,125,.10);
    }


    .calendar-3d-page .fc .fc-popover-header {

        padding: 10px 13px;

        background:
            linear-gradient(
                135deg,
                #f5f2ff,
                #eeeaff
            );

        color: #5f5777;

        font-size: 12px;

        font-weight: 800;

        border-bottom: 1px solid #e9e4f7;
    }


    .calendar-3d-page .fc .fc-popover-close {

        color: #817995;

        transition:

            transform .2s ease,

            color .2s ease;
    }


    .calendar-3d-page .fc .fc-popover-close:hover {

        color: #7565dc;

        transform: rotate(90deg);
    }


    /* =========================================================
       SCROLLBARS
       ========================================================= */

    .calendar-3d-page .fc-scroller::-webkit-scrollbar {

        width: 7px;

        height: 7px;
    }


    .calendar-3d-page .fc-scroller::-webkit-scrollbar-track {

        background: #f7f5ff;
    }


    .calendar-3d-page .fc-scroller::-webkit-scrollbar-thumb {

        background: #d8d1f3;

        border-radius: 20px;
    }


    .calendar-3d-page .fc-scroller::-webkit-scrollbar-thumb:hover {

        background: #bcb1eb;
    }


    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 991px) {

        .calendar-hero {

            padding: 25px;

            border-radius: 23px;
        }


        .hero-title {

            font-size: 24px;
        }


        .hero-actions {

            width: 100%;
        }


        .calendar-btn {

            flex: 1;

            justify-content: center;
        }


        .calendar-card-inner {

            padding: 14px;
        }
    }


    @media (max-width: 768px) {

        .calendar-hero {

            min-height: auto;

            padding: 22px;

            border-radius: 20px;
        }


        .hero-title {

            font-size: 21px;

            gap: 9px;
        }


        .hero-icon {

            width: 42px;

            height: 42px;

            border-radius: 12px;

            font-size: 18px;
        }


        .hero-subtitle {

            font-size: 12px;
        }


        .calendar-hint {

            font-size: 10px;
        }


        .calendar-legend {

            align-items: flex-start;

            flex-direction: column;

            padding: 13px;
        }


        .legend-items {

            gap: 10px;
        }


        .calendar-card {

            border-radius: 20px;
        }


        .calendar-card-inner {

            padding: 10px;
        }


        #appointmentCalendar {

            min-height: 600px;
        }


        .calendar-3d-page .fc .fc-toolbar {

            flex-direction: column;

            align-items: stretch;

            gap: 10px;
        }


        .calendar-3d-page .fc .fc-toolbar-chunk {

            display: flex;

            justify-content: center;

            flex-wrap: wrap;

            gap: 5px;
        }


        .calendar-3d-page .fc .fc-toolbar-title {

            font-size: 18px;

            text-align: center;
        }


        .calendar-3d-page .fc .fc-toolbar-title::before {

            font-size: 15px;
        }


        .calendar-3d-page .fc .fc-button {

            min-height: 34px;

            padding: 5px 8px;

            font-size: 10px;
        }


        .calendar-3d-page .fc .fc-prev-button,

        .calendar-3d-page .fc .fc-next-button {

            width: 34px;

            height: 34px;
        }


        .calendar-3d-page .fc .fc-daygrid-day-frame {

            min-height: 90px;

            padding: 4px;
        }


        .calendar-3d-page .fc .fc-daygrid-day-number {

            min-width: 25px;

            height: 25px;

            font-size: 10px;
        }


        .calendar-3d-page .fc-event {

            font-size: 9px;

            padding: 3px 5px !important;

            border-radius: 7px !important;
        }


        .calendar-3d-page .fc-event-dot {

            width: 5px;

            height: 5px;
        }


        .calendar-3d-page .fc th {

            font-size: 9px;

            padding: 9px 3px;
        }
    }


    @media (max-width: 480px) {

        .hero-actions {

            flex-direction: column;
        }


        .calendar-btn {

            width: 100%;
        }


        .calendar-3d-page .fc .fc-button {

            font-size: 9px;

            padding: 4px 7px;
        }


        .calendar-3d-page .fc .fc-daygrid-day-frame {

            min-height: 75px;
        }


        .calendar-3d-page .fc-event {

            font-size: 8px;

            padding: 2px 4px !important;
        }
    }


    /* =========================================================
       REDUCED MOTION
       ========================================================= */

    @media (prefers-reduced-motion: reduce) {

        .calendar-hero,

        .calendar-btn,

        .calendar-3d-page .fc-event,

        .live-dot {

            animation: none !important;

            transition: none !important;
        }
    }

</style>

@endpush


{{-- =============================================================
     SCRIPTS
     ============================================================= --}}
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const calendarElement =
        document.getElementById('appointmentCalendar');

    if (!calendarElement) {
        return;
    }


    /* =========================================================
       HTML ESCAPE
       ========================================================= */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }


    /* =========================================================
       STATUS CLASS
       ========================================================= */

    function getStatusClass(status) {

        if (!status) {
            return 'status-default';
        }

        return String(status)
            .toLowerCase()
            .trim()
            .replace(/[\s-]+/g, '_')
            .replace(/[^a-z0-9_]/g, '');
    }


    /* =========================================================
       CALENDAR
       ========================================================= */

    const calendar = new FullCalendar.Calendar(
        calendarElement,
        {

            initialView: 'dayGridMonth',

            height: 'auto',

            expandRows: true,

            stickyHeaderDates: true,

            nowIndicator: true,


            headerToolbar: {

                left: 'prev,next today',

                center: 'title',

                right:
                    'dayGridMonth,timeGridWeek,timeGridDay'
            },


            buttonText: {

                today: 'Today',

                month: 'Month',

                week: 'Week',

                day: 'Day'
            },


            events:
                "{{ route('admin.appointments.calendar-events') }}",


            dayMaxEvents: 3,


            moreLinkText: function (num) {

                return '+' + num + ' more';
            },


            moreLinkClick: function () {

                return 'popover';
            },


            eventDisplay: 'block',


            /* =====================================================
               EVENT CLICK
               ===================================================== */

            eventClick: function (info) {

                if (info.event.url) {

                    info.jsEvent.preventDefault();

                    window.location.href =
                        info.event.url;
                }
            },


            /* =====================================================
               EVENT MOUNT
               ===================================================== */

            eventDidMount: function (info) {

                const props =
                    info.event.extendedProps || {};

                const status =
                    getStatusClass(props.status);

                info.el.classList.add(status);


                const tooltip =
                    'Customer: ' +
                    (props.customer || 'N/A') +

                    '\nService: ' +
                    (props.service || 'N/A') +

                    '\nStaff: ' +
                    (props.staff || 'N/A') +

                    '\nStatus: ' +
                    (props.status || 'N/A');


                info.el.setAttribute(
                    'title',
                    tooltip
                );
            },


            /* =====================================================
               CUSTOM EVENT UI
               ===================================================== */

            eventContent: function (arg) {

                const event =
                    arg.event;

                const props =
                    event.extendedProps || {};


                let time = '';

                if (event.start) {

                    time =
                        event.start.toLocaleTimeString(
                            'en-IN',
                            {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            }
                        );
                }


                const customer =
                    props.customer ||
                    event.title ||
                    'Appointment';


                return {

                    html:

                        '<div class="fc-custom-event">' +

                            '<span class="fc-event-dot"></span>' +

                            '<span class="fc-event-time">' +
                                escapeHtml(time) +
                            '</span>' +

                            '<span class="fc-event-customer">' +
                                escapeHtml(customer) +
                            '</span>' +

                        '</div>'
                };
            },


            /* =====================================================
               DATE CLICK
               ===================================================== */

            dateClick: function (info) {

                const createUrl =
                    "{{ route('admin.appointments.create') }}";


                window.location.href =
                    createUrl +
                    '?appointment_date=' +
                    encodeURIComponent(
                        info.dateStr
                    );
            }

        }
    );


    calendar.render();

});

</script>

@endpush