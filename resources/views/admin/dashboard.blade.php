<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Salon Management</title>

    <link rel="icon"
          type="image/jpeg"
          sizes="512x512"
          href="{{ asset('storage/salons/faviconn.jpg') }}">

    {{-- =========================================================
        ADMINLTE
    ========================================================== --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">

    {{-- =========================================================
        BOOTSTRAP ICONS
    ========================================================== --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- =========================================================
        CHART JS
    ========================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>

        /* =========================================================
           ROOT
        ========================================================== */

        :root {
            --primary: #8b5cf6;
            --primary-light: #ede9fe;
            --secondary: #ec4899;
            --blue: #3b82f6;
            --cyan: #06b6d4;
            --green: #10b981;
            --orange: #f59e0b;
            --red: #ef4444;

            --text: #25213a;
            --muted: #7b7890;

            --bg: #f7f8fc;
            --card: rgba(255,255,255,.88);

            --border: rgba(130,120,160,.13);

            --shadow:
                0 15px 45px rgba(91, 74, 130, .10);

            --shadow-hover:
                0 25px 55px rgba(91, 74, 130, .17);
        }


        /* =========================================================
           GLOBAL
        ========================================================== */

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(139,92,246,.08),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 90% 15%,
                    rgba(236,72,153,.07),
                    transparent 30%
                ),
                #f7f8fc;

            color: var(--text);

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }


        a {
            text-decoration: none;
        }


        /* =========================================================
           SIDEBAR
        ========================================================== */

        .app-sidebar {
            background:
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #faf9ff 100%
                ) !important;

            border-right:
                1px solid rgba(120,100,160,.10);

            box-shadow:
                10px 0 35px rgba(90,70,120,.06);
        }


        .sidebar-wrapper {
            background: transparent !important;
        }


        .sidebar-brand {
            background: transparent !important;

            border-bottom:
                1px solid rgba(120,100,160,.10) !important;
        }


        .brand-link {
            background: transparent !important;
            border: none !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }


        .brand-text {
            color: #302b48 !important;
            font-weight: 800 !important;
            letter-spacing: -.3px;
        }


        .brand-image {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #8b5cf6,
                    #ec4899
                );

            color: white;

            box-shadow:
                0 8px 18px rgba(139,92,246,.28);

            padding: 0;
        }


        /* =========================================================
           SIDEBAR MENU
        ========================================================== */

        .nav-sidebar .nav-link {
            color: #6f6b82 !important;

            border-radius: 12px;

            margin:
                3px 10px;

            padding:
                10px 12px;

            transition:
                all .25s ease;
        }


        .nav-sidebar .nav-link p {
            font-size: 13px;
            font-weight: 600;
        }


        .nav-sidebar .nav-icon {
            color: #8b84a2;
            transition: .25s ease;
        }


        .nav-sidebar .nav-link:hover {

            color: #6845c4 !important;

            background:
                linear-gradient(
                    90deg,
                    rgba(139,92,246,.10),
                    rgba(236,72,153,.06)
                ) !important;

            transform:
                translateX(3px);
        }


        .nav-sidebar .nav-link:hover .nav-icon {
            color: var(--primary);
        }


        .nav-sidebar .nav-link.active {

            color: white !important;

            background:
                linear-gradient(
                    135deg,
                    #8b5cf6,
                    #a855f7,
                    #ec4899
                ) !important;

            box-shadow:
                0 10px 22px rgba(139,92,246,.24);

            transform:
                translateX(3px);
        }


        .nav-sidebar .nav-link.active .nav-icon {
            color: white;
        }


        .nav-header {
            color: #aaa5ba !important;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 1px;

            padding:
                18px 18px 7px;
        }


        .nav-treeview .nav-link {
            margin-left: 18px;
            font-size: 12px;
        }


        .nav-treeview .nav-icon {
            font-size: 13px !important;
        }


        /* =========================================================
           NAVBAR
        ========================================================== */

        .app-header {
            background:
                rgba(255,255,255,.86) !important;

            backdrop-filter:
                blur(18px);

            -webkit-backdrop-filter:
                blur(18px);

            border-bottom:
                1px solid rgba(120,100,160,.10);

            box-shadow:
                0 5px 25px rgba(80,70,120,.05);
        }


        .navbar .nav-link {
            color: #656177;
        }


        .navbar .nav-link:hover {
            color: var(--primary);
        }


        .navbar-badge {
            border-radius: 20px;

            font-size: 9px;

            box-shadow:
                0 4px 10px rgba(239,68,68,.25);
        }


        /* =========================================================
           CONTENT
        ========================================================== */

        .app-main,
        .app-content,
        .content-wrapper {
            background: transparent !important;
        }


        .app-content-header {
            padding-bottom: 0;
        }


        .app-content-header h3 {
            font-weight: 800;
            letter-spacing: -.5px;
            color: #302b48;
        }


        .breadcrumb {
            background: transparent;
        }


        .breadcrumb-item {
            font-size: 12px;
            color: #8a8699;
        }


        /* =========================================================
           DASHBOARD HERO
        ========================================================== */

        .dashboard-hero {

            position: relative;

            overflow: hidden;

            border-radius: 26px;

            padding: 30px;

            background:
                linear-gradient(
                    135deg,
                    #ffffff 0%,
                    #f8f5ff 48%,
                    #fff5fa 100%
                );

            border:
                1px solid rgba(139,92,246,.10);

            box-shadow:
                0 20px 55px rgba(91,74,130,.10);

            margin-bottom: 25px;
        }


        .dashboard-hero::before {

            content: "";

            position: absolute;

            width: 220px;
            height: 220px;

            border-radius: 50%;

            right: -70px;
            top: -100px;

            background:
                radial-gradient(
                    circle,
                    rgba(139,92,246,.18),
                    transparent 68%
                );
        }


        .dashboard-hero::after {

            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border-radius: 50%;

            right: 130px;
            bottom: -110px;

            background:
                radial-gradient(
                    circle,
                    rgba(236,72,153,.13),
                    transparent 68%
                );
        }


        .hero-content {
            position: relative;
            z-index: 2;
        }


        .hero-title {

            font-size: 27px;

            font-weight: 800;

            margin-bottom: 7px;

            color: #2e2945;
        }


        .hero-subtitle {

            color: #858096;

            font-size: 14px;

            margin-bottom: 0;
        }


        .hero-icon {

            width: 74px;
            height: 74px;

            border-radius: 22px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 34px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #8b5cf6,
                    #ec4899
                );

            box-shadow:
                0 15px 30px rgba(139,92,246,.27);

            transform:
                perspective(500px)
                rotateY(-8deg)
                rotateX(5deg);
        }


        /* =========================================================
           3D STAT CARDS
        ========================================================== */

        .stat-card {

            position: relative;

            overflow: hidden;

            border: 1px solid var(--border);

            border-radius: 22px;

            background:
                rgba(255,255,255,.94);

            box-shadow:
                var(--shadow);

            min-height: 165px;

            transition:
                transform .3s ease,
                box-shadow .3s ease;
        }


        .stat-card:hover {

            transform:
                translateY(-7px)
                perspective(700px)
                rotateX(2deg);

            box-shadow:
                var(--shadow-hover);
        }


        .stat-card::before {

            content: "";

            position: absolute;

            width: 130px;
            height: 130px;

            right: -45px;
            top: -50px;

            border-radius: 50%;

            background:
                var(--card-gradient);

            opacity: .12;
        }


        .stat-inner {
            position: relative;
            z-index: 2;
            padding: 22px;
        }


        .stat-top {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 12px;
        }


        .stat-label {

            font-size: 12px;

            font-weight: 600;

            color: #8a8699;

            margin-bottom: 8px;
        }


        .stat-value {

            font-size: 29px;

            font-weight: 800;

            color: #302b48;

            letter-spacing: -.7px;

            margin: 0;
        }


        .stat-icon {

            width: 52px;
            height: 52px;

            flex-shrink: 0;

            border-radius: 16px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 22px;

            color: white;

            background:
                var(--card-gradient);

            box-shadow:
                0 10px 22px
                var(--icon-shadow);
        }


        .stat-bottom {

            display: flex;

            align-items: center;

            gap: 6px;

            margin-top: 15px;

            font-size: 11px;

            color: #8d899d;
        }


        .stat-progress {

            width: 45px;
            height: 5px;

            border-radius: 20px;

            background: #eeeef5;

            overflow: hidden;
        }


        .stat-progress span {

            display: block;

            width: 72%;
            height: 100%;

            border-radius: 20px;

            background:
                var(--card-gradient);
        }


        .stat-purple {

            --card-gradient:
                linear-gradient(
                    135deg,
                    #8b5cf6,
                    #a855f7
                );

            --icon-shadow:
                rgba(139,92,246,.24);
        }


        .stat-green {

            --card-gradient:
                linear-gradient(
                    135deg,
                    #10b981,
                    #34d399
                );

            --icon-shadow:
                rgba(16,185,129,.24);
        }


        .stat-orange {

            --card-gradient:
                linear-gradient(
                    135deg,
                    #f59e0b,
                    #fbbf24
                );

            --icon-shadow:
                rgba(245,158,11,.24);
        }


        .stat-blue {

            --card-gradient:
                linear-gradient(
                    135deg,
                    #3b82f6,
                    #06b6d4
                );

            --icon-shadow:
                rgba(59,130,246,.24);
        }


        /* =========================================================
           GENERAL CARD
        ========================================================== */

        .modern-card {

            border:
                1px solid var(--border) !important;

            border-radius:
                22px !important;

            background:
                rgba(255,255,255,.91) !important;

            box-shadow:
                var(--shadow) !important;

            overflow: hidden;

            transition:
                box-shadow .25s ease,
                transform .25s ease;
        }


        .modern-card:hover {

            box-shadow:
                var(--shadow-hover) !important;
        }


        .modern-card .card-header {

            background:
                transparent;

            border-bottom:
                1px solid rgba(120,100,160,.08);

            padding:
                18px 22px;
        }


        .modern-card .card-title {

            color: #37314f;

            font-weight: 750;

            font-size: 15px;
        }


        .modern-card .card-body {
            padding: 22px;
        }


        .section-icon {

            width: 34px;
            height: 34px;

            border-radius: 10px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            background:
                #f1edff;

            color:
                #8057e8;

            margin-right: 8px;
        }


        /* =========================================================
           ALERT CARDS
        ========================================================== */

        .alert-grid-card {

            border-radius: 18px;

            padding: 18px;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #faf9ff
                );

            border:
                1px solid rgba(120,100,160,.10);

            box-shadow:
                0 10px 28px rgba(90,70,120,.07);

            transition:
                transform .25s ease,
                box-shadow .25s ease;
        }


        .alert-grid-card:hover {

            transform:
                translateY(-5px);

            box-shadow:
                0 18px 38px rgba(90,70,120,.12);
        }


        .alert-icon {

            width: 48px;
            height: 48px;

            border-radius: 15px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 20px;
        }


        .alert-warning {
            background: #fff7df;
            color: #d99600;
        }


        .alert-primary {
            background: #eef3ff;
            color: #4777e8;
        }


        .alert-danger {
            background: #fff0f1;
            color: #e65362;
        }


        .alert-success {
            background: #eafbf4;
            color: #12a979;
        }


        .alert-label {

            color: #8c879b;

            font-size: 11px;

            font-weight: 600;
        }


        .alert-value {

            color: #302b48;

            font-size: 26px;

            font-weight: 800;

            line-height: 1.2;
        }


        /* =========================================================
           PROFIT CARDS
        ========================================================== */

        .profit-box {

            position: relative;

            overflow: hidden;

            border-radius: 19px;

            padding: 21px;

            min-height: 125px;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #fafaff
                );

            border:
                1px solid rgba(120,100,160,.10);

            box-shadow:
                0 10px 30px rgba(90,70,120,.07);

            transition:
                transform .25s ease;
        }


        .profit-box:hover {
            transform:
                translateY(-4px);
        }


        .profit-box::after {

            content: "";

            position: absolute;

            width: 100px;
            height: 100px;

            right: -35px;
            bottom: -50px;

            border-radius: 50%;

            background:
                var(--profit-color);

            opacity: .08;
        }


        .profit-label {

            color: #8c879b;

            font-size: 11px;

            font-weight: 650;
        }


        .profit-value {

            font-size: 25px;

            font-weight: 800;

            margin-top: 8px;
        }


        .profit-revenue {
            --profit-color: #10b981;
        }


        .profit-expense {
            --profit-color: #ef4444;
        }


        .profit-net {
            --profit-color: #8b5cf6;
        }


        .profit-formula {

            border-radius: 16px;

            padding: 17px;

            background:
                linear-gradient(
                    135deg,
                    #f8f5ff,
                    #fff6fa
                );

            border:
                1px solid rgba(139,92,246,.09);
        }


        /* =========================================================
           QUICK ACTIONS
        ========================================================== */

        .quick-action {

            display: block;

            position: relative;

            height: 100%;

            padding: 19px;

            border-radius: 18px;

            background:
                linear-gradient(
                    145deg,
                    #ffffff,
                    #fbfaff
                );

            border:
                1px solid rgba(120,100,160,.10);

            box-shadow:
                0 10px 28px rgba(90,70,120,.06);

            transition:
                transform .3s ease,
                box-shadow .3s ease;
        }


        .quick-action:hover {

            transform:
                translateY(-6px)
                scale(1.01);

            box-shadow:
                0 18px 40px rgba(90,70,120,.13);
        }


        .quick-action-icon {

            width: 48px;
            height: 48px;

            border-radius: 15px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 21px;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #8b5cf6,
                    #ec4899
                );

            box-shadow:
                0 10px 22px rgba(139,92,246,.20);

            margin-bottom: 13px;
        }


        .quick-action-title {

            color: #332e49;

            font-size: 14px;

            font-weight: 750;
        }


        .quick-action-text {

            color: #8c879b;

            font-size: 11px;

            margin-top: 4px;

            line-height: 1.5;
        }


        /* =========================================================
           CHART
        ========================================================== */

        .chart-container {

            position: relative;

            height: 335px;
        }


        .chart-pill {

            padding:
                6px 12px;

            border-radius: 20px;

            background:
                #f2efff;

            color:
                #7451c9;

            font-size: 10px;

            font-weight: 700;
        }


        /* =========================================================
           APPOINTMENTS
        ========================================================== */

        .appointment-row {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            padding:
                13px 0;

            border-bottom:
                1px solid rgba(120,100,160,.08);
        }


        .appointment-row:last-child {
            border-bottom: none;
        }


        .appointment-avatar {

            width: 39px;
            height: 39px;

            border-radius: 12px;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #ede9fe,
                    #fce7f3
                );

            color:
                #8057e8;

            font-weight: 800;

            font-size: 13px;
        }


        .appointment-time {

            color: #302b48;

            font-size: 12px;

            font-weight: 750;
        }


        .customer-name {

            color: #8c879b;

            font-size: 11px;

            margin-top: 3px;
        }


        /* =========================================================
           STATUS BADGES
        ========================================================== */

        .status-badge {

            display: inline-flex;

            align-items: center;

            padding:
                6px 10px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 700;

            white-space: nowrap;
        }


        .confirmed {
            background: #eafbf4;
            color: #11966d;
        }


        .pending {
            background: #fff7df;
            color: #b17a00;
        }


        .completed {
            background: #eef0f5;
            color: #656979;
        }


        .cancelled {
            background: #fff0f1;
            color: #dc4b5a;
        }


        .arrived {
            background: #e7f9fc;
            color: #07849a;
        }


        .in-service,
        .in_service {
            background: #f1ebff;
            color: #7650cb;
        }


        .no-show,
        .no_show {
            background: #fff0f1;
            color: #b52b3b;
        }


        /* =========================================================
           TABLE
        ========================================================== */

        .modern-table {
            margin: 0;
        }


        .modern-table thead th {

            background:
                #faf9fd;

            color:
                #898498;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: .5px;

            border-bottom:
                1px solid rgba(120,100,160,.08);

            padding:
                14px 18px;
        }


        .modern-table tbody td {

            color:
                #514c63;

            font-size:
                12px;

            padding:
                15px 18px;

            border-bottom:
                1px solid rgba(120,100,160,.07);

            vertical-align:
                middle;
        }


        .modern-table tbody tr {

            transition:
                background .2s ease;
        }


        .modern-table tbody tr:hover {

            background:
                #faf9ff;
        }


        .appointment-number {

            color:
                #7650cb;

            font-weight:
                800;
        }


        /* =========================================================
           NOTIFICATION
        ========================================================== */

        .notification-dropdown {

            width: 390px;

            max-width: 95vw;

            max-height: 560px;

            overflow-y: auto;

            border:
                1px solid rgba(120,100,160,.12);

            border-radius:
                16px;

            box-shadow:
                0 20px 60px rgba(60,50,90,.16);
        }


        .notification-icon {

            width: 40px;
            height: 40px;

            min-width: 40px;

            border-radius: 12px;

            display: flex;

            align-items: center;
            justify-content: center;
        }


        .notification-icon.warning {
            background: #fff7df;
            color: #c48a00;
        }


        .notification-icon.danger {
            background: #fff0f1;
            color: #dc4b5a;
        }


        .notification-icon.primary {
            background: #eef3ff;
            color: #4777e8;
        }


        .notification-icon.success {
            background: #eafbf4;
            color: #11966d;
        }


        .notification-bell-shake {

            animation:
                bellShake .6s ease-in-out;
        }


        @keyframes bellShake {

            0% {
                transform: rotate(0);
            }

            20% {
                transform: rotate(-15deg);
            }

            40% {
                transform: rotate(15deg);
            }

            60% {
                transform: rotate(-10deg);
            }

            80% {
                transform: rotate(10deg);
            }

            100% {
                transform: rotate(0);
            }
        }


        /* =========================================================
           TOAST
        ========================================================== */

        .notification-toast {

            position: fixed;

            top: 75px;
            right: 20px;

            width: 370px;

            max-width:
                calc(100vw - 30px);

            z-index: 99999;

            display: none;

            animation:
                toastIn .35s ease;
        }


        .notification-toast.show {
            display: block;
        }


        @keyframes toastIn {

            from {
                opacity: 0;
                transform:
                    translateY(-15px)
                    translateX(15px);
            }

            to {
                opacity: 1;
                transform:
                    translateY(0)
                    translateX(0);
            }
        }


        /* =========================================================
           EMPTY DATA
        ========================================================== */

        .no-data {

            padding: 35px 15px;

            text-align: center;

            color: #9994a8;
        }


        .no-data i {

            color: #c7c2d5;
        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .app-footer {

            background:
                rgba(255,255,255,.75);

            border-top:
                1px solid rgba(120,100,160,.08);

            color:
                #8b879a;

            font-size:
                11px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media(max-width: 991px) {

            .dashboard-hero {
                padding: 24px;
            }

            .hero-title {
                font-size: 23px;
            }

            .chart-container {
                height: 280px;
            }

        }


        @media(max-width: 768px) {

            .app-content-header {
                padding-top: 15px;
            }

            .dashboard-hero {
                border-radius: 20px;
            }

            .stat-card {
                min-height: 145px;
            }

            .stat-value {
                font-size: 24px;
            }

            .notification-dropdown {
                width: 350px;
                max-width: calc(100vw - 20px);
            }

        }


        @media(max-width: 575px) {

            .dashboard-hero {
                padding: 20px;
            }

            .hero-icon {
                width: 58px;
                height: 58px;
                font-size: 26px;
            }

            .hero-title {
                font-size: 20px;
            }

            .hero-subtitle {
                font-size: 12px;
            }

            .chart-container {
                height: 240px;
            }

        }

    </style>

</head>


<body class="layout-fixed sidebar-expand-lg">


<div class="app-wrapper">


{{-- =========================================================
    NAVBAR
========================================================= --}}

<nav class="app-header navbar navbar-expand">

    <div class="container-fluid">

        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link"
                   data-lte-toggle="sidebar"
                   href="#">

                    <i class="bi bi-list fs-5"></i>

                </a>

            </li>

        </ul>


        <ul class="navbar-nav ms-auto">


            {{-- NOTIFICATION --}}

            <li class="nav-item dropdown">

                <a class="nav-link position-relative"
                   id="notificationBell"
                   data-bs-toggle="dropdown"
                   href="#"
                   aria-expanded="false">

                    <i class="bi bi-bell fs-5"></i>

                    @if(($notificationCount ?? 0) > 0)

                        <span class="navbar-badge badge text-bg-danger notification-badge">

                            {{ $notificationCount }}

                        </span>

                    @endif

                </a>


                <div
                    id="notificationDropdownContainer"
                    class="dropdown-menu dropdown-menu-end p-0 notification-dropdown"
                >

                    <div class="text-center py-4">

                        <div class="spinner-border spinner-border-sm text-primary">
                        </div>

                        <div class="small text-muted mt-2">

                            Loading notifications...

                        </div>

                    </div>

                </div>

            </li>


            {{-- USER --}}

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle"
                   href="#"
                   data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle me-1"></i>

                    {{ auth()->user()->name ?? 'Admin' }}

                </a>


                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                    <li>

                        <h6 class="dropdown-header">

                            {{ auth()->user()->email ?? '' }}

                        </h6>

                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>

                        <a class="dropdown-item"
                           href="{{ route('admin.2fa') }}">

                            <i class="bi bi-shield-lock me-2"></i>

                            Two-Factor Authentication

                        </a>

                    </li>


                    <li>

                        <form method="POST"
                              action="{{ route('admin.logout') }}">

                            @csrf

                            <button type="submit"
                                    class="dropdown-item">

                                <i class="bi bi-box-arrow-right me-2"></i>

                                Logout

                            </button>

                        </form>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

</nav>


{{-- =========================================================
    SIDEBAR
========================================================= --}}

<aside class="app-sidebar"
       data-bs-theme="light">


    <div class="sidebar-brand">

        <a href="{{ route('admin.dashboard') }}"
           class="brand-link">

            <span class="brand-image">

                <i class="bi bi-scissors"></i>

            </span>

            <span class="brand-text">

                Salon Management

            </span>

        </a>

    </div>


    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">


                {{-- MAIN MENU --}}

                <li class="nav-header">
                    MAIN MENU
                </li>


                {{-- DASHBOARD --}}

                <li class="nav-item">

                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-grid-1x2-fill"></i>

                        <p>Dashboard</p>

                    </a>

                </li>


                {{-- APPOINTMENTS --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.appointments.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-calendar-heart"></i>

                        <p>
                            Appointments
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.appointments.calendar') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-calendar3"></i>

                                <p>Calendar</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.appointments.index', [
                                'appointment_date' => now()->format('Y-m-d')
                            ]) }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-calendar-day"></i>

                                <p>Today's Appointments</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.appointments.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-list-check"></i>

                                <p>All Appointments</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- CUSTOMERS --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-people"></i>

                        <p>
                            Customers
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.customers.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-list-ul"></i>

                                <p>All Customers</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.customers.create') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-person-plus"></i>

                                <p>Add Customer</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- SERVICES --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-stars"></i>

                        <p>
                            Services
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.categories.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-tags"></i>

                                <p>Categories</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.services.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-list-ul"></i>

                                <p>Services</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.services.create') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-plus-circle"></i>

                                <p>Add Service</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.service-variations.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-sliders"></i>

                                <p>Variations</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- STAFF --}}

                @php

                    $dashboardStaff = null;

                    if (
                        auth()->check() &&
                        auth()->user()->branch_id
                    ) {

                        $dashboardStaff =
                            \App\Models\Staff::where(
                                'branch_id',
                                auth()->user()->branch_id
                            )
                            ->orderBy('id')
                            ->first();

                    }

                @endphp


                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-person-badge"></i>

                        <p>
                            Staff
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.staff.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-people"></i>

                                <p>Staff</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            @if($dashboardStaff)

                                <a href="{{ route(
                                    'admin.staff.schedule.index',
                                    ['staff' => $dashboardStaff->id]
                                ) }}"
                                   class="nav-link">

                                    <i class="nav-icon bi bi-calendar-week"></i>

                                    <p>Schedule</p>

                                </a>

                            @else

                                <a href="{{ route('admin.staff.index') }}"
                                   class="nav-link">

                                    <i class="nav-icon bi bi-calendar-week"></i>

                                    <p>Schedule</p>

                                </a>

                            @endif

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.staff.attendance.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-calendar-check"></i>

                                <p>Attendance</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- BRANCHES --}}

                <li class="nav-item">

                    <a href="{{ route('admin.branches.index') }}"
                       class="nav-link">

                        <i class="nav-icon bi bi-diagram-3"></i>

                        <p>Branches</p>

                    </a>

                </li>


                {{-- PACKAGES --}}

                <li class="nav-item">

                    <a href="{{ route('admin.packages.index') }}"
                       class="nav-link">

                        <i class="nav-icon bi bi-box2-heart"></i>

                        <p>Packages</p>

                    </a>

                </li>


                <li class="nav-item">

                    <a href="{{ route('admin.customer-package-usage.index') }}"
                       class="nav-link">

                        <i class="nav-icon bi bi-clock-history"></i>

                        <p>Package Usage History</p>

                    </a>

                </li>


                {{-- MEMBERSHIP --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-person-vcard"></i>

                        <p>
                            Membership
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.membership.plans.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-card-list"></i>

                                <p>Membership Plans</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.membership.assigned-members') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-people"></i>

                                <p>Assigned Members</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- BILLING --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-receipt"></i>

                        <p>
                            POS / Billing
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.billing.all') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-receipt"></i>

                                <p>Billing</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.billing.create') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-cart-plus"></i>

                                <p>New Bill</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- INVENTORY --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-box-seam"></i>

                        <p>
                            Inventory
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.products.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-box"></i>

                                <p>Products</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.stock.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-boxes"></i>

                                <p>Stock</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.purchases.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-cart-plus"></i>

                                <p>Purchases</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.suppliers.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-truck"></i>

                                <p>Suppliers</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.wastage.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-trash"></i>

                                <p>Wastage</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- OFFERS --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-percent"></i>

                        <p>
                            Offers
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.offers.discounts.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-tag"></i>

                                <p>Discounts</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.offers.coupons.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-ticket-perforated"></i>

                                <p>Coupons</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- EXPENSES --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-wallet2"></i>

                        <p>
                            Expenses
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.expenses.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-list-ul"></i>

                                <p>All Expenses</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.expenses.create') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-plus-circle"></i>

                                <p>Add Expense</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.expenses.categories.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-tags"></i>

                                <p>Expense Categories</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- REPORTS --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-bar-chart-line"></i>

                        <p>
                            Reports
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.customers') }}" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Customer Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.appointments') }}" class="nav-link">
                                <i class="nav-icon bi bi-calendar-check"></i>
                                <p>Appointment Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.sales') }}" class="nav-link">
                                <i class="nav-icon bi bi-receipt"></i>
                                <p>Sales Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.services') }}" class="nav-link">
                                <i class="nav-icon bi bi-scissors"></i>
                                <p>Service Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.staff') }}" class="nav-link">
                                <i class="nav-icon bi bi-person-badge"></i>
                                <p>Staff & Commission</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.packages') }}" class="nav-link">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>Package Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.inventory') }}" class="nav-link">
                                <i class="nav-icon bi bi-boxes"></i>
                                <p>Inventory Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.expenses') }}" class="nav-link">
                                <i class="nav-icon bi bi-cash-stack"></i>
                                <p>Expense Report</p>
                            </a>
                        </li>

                    </ul>

                </li>


                {{-- SYSTEM --}}

                <li class="nav-header">
                    SYSTEM
                </li>


                {{-- SETTINGS --}}

                <li class="nav-item has-treeview">

                    <a href="#"
                       class="nav-link">

                        <i class="nav-icon bi bi-gear"></i>

                        <p>
                            Settings
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.settings.index') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-sliders"></i>

                                <p>General Settings</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.settings.printer') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-printer"></i>

                                <p>Printer Settings</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.2fa') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-shield-lock"></i>

                                <p>2FA</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.settings.backup') }}"
                               class="nav-link">

                                <i class="nav-icon bi bi-database"></i>

                                <p>Backup & Restore</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- LOGOUT --}}

                <li class="nav-item mt-2">

                    <form method="POST"
                          action="{{ route('admin.logout') }}">

                        @csrf

                        <button type="submit"
                                class="nav-link border-0 bg-transparent w-100 text-start">

                            <i class="nav-icon bi bi-box-arrow-right"></i>

                            <p>Logout</p>

                        </button>

                    </form>

                </li>

            </ul>

        </nav>

    </div>

</aside>


{{-- =========================================================
    MAIN
========================================================= --}}

<main class="app-main">


    {{-- PAGE HEADER --}}

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row align-items-center">

                <div class="col-sm-6">

                    <h3 class="mb-0">
                        Dashboard
                    </h3>

                </div>


                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item">
                            Admin
                        </li>

                        <li class="breadcrumb-item active">
                            Dashboard
                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    <div class="app-content">

        <div class="container-fluid">


            {{-- =================================================
                HERO
            ================================================== --}}

            <div class="dashboard-hero">

                <div class="hero-content">

                    <div class="row align-items-center">

                        <div class="col-md-9">

                            <h1 class="hero-title">

                                Welcome back,
                                {{ auth()->user()->name ?? 'Admin' }} 👋

                            </h1>

                            <p class="hero-subtitle">

                                Manage your salon, appointments,
                                customers and daily business performance
                                from one place.

                            </p>

                        </div>


                        <div class="col-md-3 d-none d-md-flex justify-content-end">

                            <div class="hero-icon">

                                <i class="bi bi-stars"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                STATISTICS
            ================================================== --}}

            <div class="row g-4">


                {{-- APPOINTMENTS --}}

                <div class="col-xl-3 col-md-6">

                    <div class="stat-card stat-purple">

                        <div class="stat-inner">

                            <div class="stat-top">

                                <div>

                                    <div class="stat-label">
                                        Today's Appointments
                                    </div>

                                    <h2 class="stat-value">

                                        {{ $todayAppointments }}

                                    </h2>

                                </div>


                                <div class="stat-icon">

                                    <i class="bi bi-calendar-heart"></i>

                                </div>

                            </div>


                            <div class="stat-bottom">

                                <div class="stat-progress">
                                    <span></span>
                                </div>

                                <span>
                                    Today's schedule
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- REVENUE --}}

                <div class="col-xl-3 col-md-6">

                    <div class="stat-card stat-green">

                        <div class="stat-inner">

                            <div class="stat-top">

                                <div>

                                    <div class="stat-label">
                                        Today's Revenue
                                    </div>

                                    <h2 class="stat-value">

                                        ₹{{ number_format($todayRevenue, 2) }}

                                    </h2>

                                </div>


                                <div class="stat-icon">

                                    <i class="bi bi-currency-rupee"></i>

                                </div>

                            </div>


                            <div class="stat-bottom">

                                <div class="stat-progress">
                                    <span></span>
                                </div>

                                <span>
                                    Sales performance
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- CUSTOMERS --}}

                <div class="col-xl-3 col-md-6">

                    <div class="stat-card stat-orange">

                        <div class="stat-inner">

                            <div class="stat-top">

                                <div>

                                    <div class="stat-label">
                                        Total Customers
                                    </div>

                                    <h2 class="stat-value">

                                        {{ $totalCustomers }}

                                    </h2>

                                </div>


                                <div class="stat-icon">

                                    <i class="bi bi-people"></i>

                                </div>

                            </div>


                            <div class="stat-bottom">

                                <div class="stat-progress">
                                    <span></span>
                                </div>

                                <span>
                                    Customer base
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- STAFF --}}

                <div class="col-xl-3 col-md-6">

                    <div class="stat-card stat-blue">

                        <div class="stat-inner">

                            <div class="stat-top">

                                <div>

                                    <div class="stat-label">
                                        Available Staff
                                    </div>

                                    <h2 class="stat-value">

                                        {{ $availableStaff }}

                                    </h2>

                                </div>


                                <div class="stat-icon">

                                    <i class="bi bi-person-check"></i>

                                </div>

                            </div>


                            <div class="stat-bottom">

                                <div class="stat-progress">
                                    <span></span>
                                </div>

                                <span>
                                    Staff availability
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                NOTIFICATION SUMMARY
            ================================================== --}}

            <div class="row g-4 mt-1">


                {{-- PACKAGE --}}

                <div class="col-xl-3 col-md-6">

                    <a href="{{ route('admin.customer-packages.index') }}">

                        <div class="alert-grid-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="alert-label">
                                        PACKAGE EXPIRY
                                    </div>

                                    <div class="alert-value mt-1">

                                        {{ $packageExpiryCount }}

                                    </div>

                                    <small class="text-muted">
                                        Next 7 days
                                    </small>

                                </div>


                                <div class="alert-icon alert-warning">

                                    <i class="bi bi-box-seam"></i>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                {{-- MEMBERSHIP --}}

                <div class="col-xl-3 col-md-6">

                    <a href="{{ route('admin.membership.assigned-members') }}">

                        <div class="alert-grid-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="alert-label">
                                        MEMBERSHIP EXPIRY
                                    </div>

                                    <div class="alert-value mt-1">

                                        {{ $membershipExpiryCount }}

                                    </div>

                                    <small class="text-muted">
                                        Next 7 days
                                    </small>

                                </div>


                                <div class="alert-icon alert-primary">

                                    <i class="bi bi-person-vcard"></i>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                {{-- PAYMENT --}}

                <div class="col-xl-3 col-md-6">

                    <a href="{{ route('admin.billing.all') }}">

                        <div class="alert-grid-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="alert-label">
                                        PENDING PAYMENTS
                                    </div>

                                    <div class="alert-value mt-1">

                                        {{ $pendingPaymentCount }}

                                    </div>

                                    <small class="text-muted">
                                        Payment pending
                                    </small>

                                </div>


                                <div class="alert-icon alert-danger">

                                    <i class="bi bi-credit-card"></i>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                {{-- TODAY --}}

                <div class="col-xl-3 col-md-6">

                    <a href="{{ route('admin.appointments.index', [
                        'appointment_date' => now()->format('Y-m-d')
                    ]) }}">

                        <div class="alert-grid-card">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <div class="alert-label">
                                        TODAY'S ALERTS
                                    </div>

                                    <div class="alert-value mt-1">

                                        {{ $todayAppointmentNotificationCount }}

                                    </div>

                                    <small class="text-muted">
                                        Today's appointments
                                    </small>

                                </div>


                                <div class="alert-icon alert-success">

                                    <i class="bi bi-calendar-check"></i>

                                </div>

                            </div>

                        </div>

                    </a>

                </div>

            </div>


            {{-- =================================================
                PROFIT SUMMARY
            ================================================== --}}

            <div class="modern-card card mt-4 mb-4">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <span class="section-icon">

                            <i class="bi bi-graph-up-arrow"></i>

                        </span>

                        <span class="card-title">

                            Profit Summary -
                            {{ now()->format('F Y') }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- REVENUE --}}

                        <div class="col-lg-4">

                            <div class="profit-box profit-revenue">

                                <div class="profit-label">
                                    MONTHLY REVENUE
                                </div>

                                <div class="profit-value text-success">

                                    ₹{{ number_format(
                                        $monthlyRevenue,
                                        2
                                    ) }}

                                </div>

                                <small class="text-muted">
                                    Total sales this month
                                </small>

                            </div>

                        </div>


                        {{-- EXPENSE --}}

                        <div class="col-lg-4">

                            <div class="profit-box profit-expense">

                                <div class="profit-label">
                                    TOTAL EXPENSES
                                </div>

                                <div class="profit-value text-danger">

                                    ₹{{ number_format(
                                        $monthlyExpenses,
                                        2
                                    ) }}

                                </div>

                                <small class="text-muted">
                                    Business expenses
                                </small>

                            </div>

                        </div>


                        {{-- NET --}}

                        <div class="col-lg-4">

                            <div class="profit-box profit-net">

                                <div class="profit-label">
                                    NET PROFIT
                                </div>

                                <div class="profit-value
                                    {{ $monthlyNetProfit >= 0
                                        ? 'text-primary'
                                        : 'text-danger' }}">

                                    ₹{{ number_format(
                                        $monthlyNetProfit,
                                        2
                                    ) }}

                                </div>

                                <small class="text-muted">
                                    Revenue minus expenses
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="profit-formula mt-4">

                        <div class="d-flex flex-wrap
                                    align-items-center
                                    gap-2">

                            <strong>
                                Profit Calculation
                            </strong>

                            <span class="text-success fw-bold">

                                ₹{{ number_format(
                                    $monthlyRevenue,
                                    2
                                ) }}

                            </span>

                            <span class="text-muted">
                                Revenue
                            </span>

                            <span class="fw-bold">
                                −
                            </span>

                            <span class="text-danger fw-bold">

                                ₹{{ number_format(
                                    $monthlyExpenses,
                                    2
                                ) }}

                            </span>

                            <span class="text-muted">
                                Expenses
                            </span>

                            <span class="fw-bold">
                                =
                            </span>

                            <span class="fw-bold
                                {{ $monthlyNetProfit >= 0
                                    ? 'text-primary'
                                    : 'text-danger' }}">

                                ₹{{ number_format(
                                    $monthlyNetProfit,
                                    2
                                ) }}

                            </span>

                            <span class="text-muted">
                                Net Profit
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                QUICK ACTIONS
            ================================================== --}}

            <div class="modern-card card mb-4">

                <div class="card-header">

                    <div class="d-flex align-items-center">

                        <span class="section-icon">

                            <i class="bi bi-lightning-charge-fill"></i>

                        </span>

                        <span class="card-title">
                            Quick Actions
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        <div class="col-xl-3 col-md-6">

                            <a href="{{ route('admin.appointments.create') }}"
                               class="quick-action">

                                <div class="quick-action-icon">

                                    <i class="bi bi-calendar-plus"></i>

                                </div>

                                <div class="quick-action-title">
                                    New Appointment
                                </div>

                                <div class="quick-action-text">
                                    Create a new customer appointment
                                </div>

                            </a>

                        </div>


                        <div class="col-xl-3 col-md-6">

                            <a href="{{ route('admin.customers.create') }}"
                               class="quick-action">

                                <div class="quick-action-icon">

                                    <i class="bi bi-person-plus"></i>

                                </div>

                                <div class="quick-action-title">
                                    Add Customer
                                </div>

                                <div class="quick-action-text">
                                    Register a new salon customer
                                </div>

                            </a>

                        </div>


                        <div class="col-xl-3 col-md-6">

                            <a href="{{ route('admin.billing.create') }}"
                               class="quick-action">

                                <div class="quick-action-icon">

                                    <i class="bi bi-receipt"></i>

                                </div>

                                <div class="quick-action-title">
                                    New Bill
                                </div>

                                <div class="quick-action-text">
                                    Create POS bill and payment
                                </div>

                            </a>

                        </div>


                        <div class="col-xl-3 col-md-6">

                            <a href="{{ route('admin.services.index') }}"
                               class="quick-action">

                                <div class="quick-action-icon">

                                    <i class="bi bi-stars"></i>

                                </div>

                                <div class="quick-action-title">
                                    Manage Services
                                </div>

                                <div class="quick-action-text">
                                    Manage salon services and pricing
                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                CHART + TODAY APPOINTMENTS
            ================================================== --}}

            <div class="row g-4">


                {{-- REVENUE CHART --}}

                <div class="col-xl-8">

                    <div class="modern-card card h-100">

                        <div class="card-header">

                            <div class="d-flex
                                        justify-content-between
                                        align-items-center">

                                <div class="d-flex align-items-center">

                                    <span class="section-icon">

                                        <i class="bi bi-bar-chart-line"></i>

                                    </span>

                                    <span class="card-title">
                                        Revenue Overview
                                    </span>

                                </div>

                                <span class="chart-pill">
                                    LAST 7 DAYS
                                </span>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="chart-container">

                                <canvas id="revenueChart"></canvas>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- TODAY APPOINTMENTS --}}

                <div class="col-xl-4">

                    <div class="modern-card card h-100">

                        <div class="card-header">

                            <div class="d-flex align-items-center">

                                <span class="section-icon">

                                    <i class="bi bi-calendar-heart"></i>

                                </span>

                                <span class="card-title">
                                    Today's Appointments
                                </span>

                            </div>

                        </div>


                        <div class="card-body">

                            @forelse($todayAppointmentList as $appointment)

                                <div class="appointment-row">

                                    <div class="d-flex
                                                align-items-center
                                                gap-2">

                                        <div class="appointment-avatar">

                                            {{ strtoupper(
                                                substr(
                                                    $appointment->customer->name ?? 'N',
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>


                                        <div>

                                            <div class="appointment-time">

                                                {{ \Carbon\Carbon::parse(
                                                    $appointment->start_time
                                                )->format('h:i A') }}

                                            </div>


                                            <div class="customer-name">

                                                {{ $appointment->customer->name ?? 'N/A' }}

                                                •

                                                {{ $appointment->service->name ?? 'N/A' }}

                                            </div>

                                        </div>

                                    </div>


                                    @php

                                        $statusClass = strtolower(
                                            str_replace(
                                                ' ',
                                                '-',
                                                $appointment->status ?? 'pending'
                                            )
                                        );

                                    @endphp


                                    <span class="status-badge {{ $statusClass }}">

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $appointment->status ?? 'Pending'
                                            )
                                        ) }}

                                    </span>

                                </div>

                            @empty

                                <div class="no-data">

                                    <i class="bi bi-calendar-x fs-2"></i>

                                    <div class="mt-2">

                                        No appointments for today.

                                    </div>

                                </div>

                            @endforelse

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                RECENT APPOINTMENTS
            ================================================== --}}

            <div class="modern-card card mt-4 mb-4">

                <div class="card-header">

                    <div class="d-flex
                                justify-content-between
                                align-items-center">

                        <div class="d-flex align-items-center">

                            <span class="section-icon">

                                <i class="bi bi-calendar2-week"></i>

                            </span>

                            <span class="card-title">
                                Recent Appointments
                            </span>

                        </div>


                        <a href="{{ route('admin.appointments.index') }}"
                           class="btn btn-sm btn-outline-primary rounded-pill px-3">

                            View All

                        </a>

                    </div>

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table modern-table mb-0">

                            <thead>

                                <tr>

                                    <th>APPOINTMENT</th>
                                    <th>CUSTOMER</th>
                                    <th>SERVICE</th>
                                    <th>STAFF</th>
                                    <th>AMOUNT</th>
                                    <th>STATUS</th>
                                    <th>DATE</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($recentAppointments as $appointment)

                                    <tr>

                                        <td>

                                            <span class="appointment-number">

                                                #A{{ $appointment->id }}

                                            </span>

                                        </td>


                                        <td>

                                            {{ $appointment->customer->name ?? 'N/A' }}

                                        </td>


                                        <td>

                                            {{ $appointment->service->name ?? 'N/A' }}

                                        </td>


                                        <td>

                                            {{ $appointment->staff->name ?? 'N/A' }}

                                        </td>


                                        <td>

                                            <strong>

                                                ₹{{ number_format(
                                                    $appointment->service->price ?? 0,
                                                    2
                                                ) }}

                                            </strong>

                                        </td>


                                        <td>

                                            @php

                                                $statusClass = strtolower(
                                                    str_replace(
                                                        ' ',
                                                        '-',
                                                        $appointment->status ?? 'pending'
                                                    )
                                                );

                                            @endphp


                                            <span class="status-badge {{ $statusClass }}">

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $appointment->status ?? 'Pending'
                                                    )
                                                ) }}

                                            </span>

                                        </td>


                                        <td>

                                            <strong>

                                                {{ \Carbon\Carbon::parse(
                                                    $appointment->appointment_date
                                                )->format('d M Y') }}

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                {{ \Carbon\Carbon::parse(
                                                    $appointment->start_time
                                                )->format('h:i A') }}

                                            </small>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="7"
                                            class="text-center py-5">

                                            <div class="no-data">

                                                <i class="bi bi-calendar-x fs-2"></i>

                                                <div class="mt-2">

                                                    No appointments found.

                                                </div>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>

    </div>

</main>


{{-- =========================================================
    FOOTER
========================================================= --}}

<footer class="app-footer">

    <strong>
        © {{ date('Y') }} Salon Management
    </strong>

    <span class="float-end d-none d-sm-inline">
        Admin Panel
    </span>

</footer>


</div>


{{-- =========================================================
    NOTIFICATION TOAST
========================================================= --}}

<div id="notificationToast"
     class="notification-toast">

    <div class="card border-0 shadow-lg"
         style="border-radius:18px;">

        <div class="card-body">

            <div class="d-flex align-items-start gap-3">

                <div class="notification-icon primary">

                    <i class="bi bi-bell-fill"></i>

                </div>


                <div class="flex-grow-1">

                    <div id="notificationToastTitle"
                         class="fw-bold">

                        New Notification

                    </div>

                    <div id="notificationToastMessage"
                         class="small text-muted mt-1">

                        You have a new notification.

                    </div>

                </div>


                <button type="button"
                        id="notificationToastClose"
                        class="btn-close">
                </button>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    NOTIFICATION SOUND
========================================================= --}}

<audio id="notificationSound"
       preload="auto">

    <source
        src="{{ asset('storage/notification/notification.mp3') }}"
        type="audio/mpeg">

</audio>


{{-- =========================================================
    BOOTSTRAP
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


{{-- =========================================================
    ADMINLTE
========================================================= --}}

<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /* =====================================================
           NOTIFICATION SYSTEM
        ====================================================== */

        const notificationSound =
            document.getElementById(
                'notificationSound'
            );

        const notificationBell =
            document.getElementById(
                'notificationBell'
            );

        const notificationToast =
            document.getElementById(
                'notificationToast'
            );

        const notificationToastTitle =
            document.getElementById(
                'notificationToastTitle'
            );

        const notificationToastMessage =
            document.getElementById(
                'notificationToastMessage'
            );

        const notificationToastClose =
            document.getElementById(
                'notificationToastClose'
            );


        let previousNotificationCount = null;

        let soundUnlocked = false;

        let browserPermissionRequested = false;

        let toastTimer = null;


        /* =====================================================
           SOUND UNLOCK
        ====================================================== */

        function unlockNotificationSound() {

            if (!notificationSound) {
                return;
            }

            notificationSound.muted = true;

            notificationSound.currentTime = 0;


            const promise =
                notificationSound.play();


            if (promise !== undefined) {

                promise.then(function () {

                    notificationSound.pause();

                    notificationSound.currentTime = 0;

                    notificationSound.muted = false;

                    soundUnlocked = true;

                    localStorage.setItem(
                        'notificationSoundEnabled',
                        '1'
                    );

                }).catch(function () {

                    notificationSound.muted = false;

                    soundUnlocked = false;

                });

            }

        }


        /* =====================================================
           FIRST USER INTERACTION
        ====================================================== */

        document.addEventListener(
            'click',
            function () {

                if (!soundUnlocked) {
                    unlockNotificationSound();
                }

            },
            {
                once: true
            }
        );


        document.addEventListener(
            'touchstart',
            function () {

                if (!soundUnlocked) {
                    unlockNotificationSound();
                }

            },
            {
                once: true
            }
        );


        document.addEventListener(
            'keydown',
            function () {

                if (!soundUnlocked) {
                    unlockNotificationSound();
                }

            },
            {
                once: true
            }
        );


        /* =====================================================
           PLAY SOUND
        ====================================================== */

        function playNotificationSound() {

            if (!notificationSound) {
                return;
            }


            notificationSound.currentTime = 0;

            notificationSound.muted = false;


            const promise =
                notificationSound.play();


            if (promise !== undefined) {

                promise.catch(function (error) {

                    console.log(
                        'Notification sound blocked:',
                        error
                    );

                });

            }

        }


        /* =====================================================
           BELL ANIMATION
        ====================================================== */

        function animateBell() {

            if (!notificationBell) {
                return;
            }


            const icon =
                notificationBell.querySelector('i');


            if (!icon) {
                return;
            }


            icon.classList.remove(
                'notification-bell-shake'
            );


            void icon.offsetWidth;


            icon.classList.add(
                'notification-bell-shake'
            );

        }


        /* =====================================================
           TOAST
        ====================================================== */

        function showNotificationToast(count) {

            if (!notificationToast) {
                return;
            }


            if (notificationToastTitle) {

                notificationToastTitle.innerText =
                    'New Notification';

            }


            if (notificationToastMessage) {

                notificationToastMessage.innerText =
                    count === 1
                        ? 'You have 1 new notification.'
                        : 'You have ' +
                          count +
                          ' new notifications.';

            }


            notificationToast.classList.add(
                'show'
            );


            if (toastTimer) {

                clearTimeout(
                    toastTimer
                );

            }


            toastTimer =
                setTimeout(
                    function () {

                        notificationToast.classList.remove(
                            'show'
                        );

                    },
                    5000
                );

        }


        if (notificationToastClose) {

            notificationToastClose.addEventListener(
                'click',
                function () {

                    notificationToast.classList.remove(
                        'show'
                    );

                    if (toastTimer) {

                        clearTimeout(
                            toastTimer
                        );

                    }

                }
            );

        }


        /* =====================================================
           BROWSER NOTIFICATION
        ====================================================== */

        function showBrowserNotification(count) {

            if (!('Notification' in window)) {
                return;
            }


            if (
                Notification.permission === 'default' &&
                !browserPermissionRequested
            ) {

                browserPermissionRequested = true;


                Notification.requestPermission()
                    .then(function (permission) {

                        if (
                            permission === 'granted'
                        ) {

                            new Notification(
                                'Salon Management',
                                {
                                    body:
                                        count === 1
                                            ? 'You have 1 new notification.'
                                            : 'You have ' +
                                              count +
                                              ' new notifications.',

                                    icon:
                                        "{{ asset('storage/salons/faviconn.jpg') }}"
                                }
                            );

                        }

                    })
                    .catch(function () {});


                return;
            }


            if (
                Notification.permission === 'granted'
            ) {

                try {

                    new Notification(
                        'Salon Management',
                        {
                            body:
                                count === 1
                                    ? 'You have 1 new notification.'
                                    : 'You have ' +
                                      count +
                                      ' new notifications.',

                            icon:
                                "{{ asset('storage/salons/faviconn.jpg') }}"
                        }
                    );

                } catch (error) {

                    console.log(
                        'Browser notification error:',
                        error
                    );

                }

            }

        }


        /* =====================================================
           UPDATE BADGE
        ====================================================== */

        function updateNotificationBadge(count) {

            if (!notificationBell) {
                return;
            }


            let badge =
                notificationBell.querySelector(
                    '.notification-badge'
                );


            if (count > 0) {

                if (!badge) {

                    badge =
                        document.createElement(
                            'span'
                        );

                    badge.className =
                        'navbar-badge badge text-bg-danger notification-badge';

                    notificationBell.appendChild(
                        badge
                    );

                }


                badge.textContent =
                    count;

            } else {

                if (badge) {

                    badge.remove();

                }

            }

        }


        /* =====================================================
           CHECK NOTIFICATIONS
        ====================================================== */

        function checkNotifications() {

            fetch(
                "{{ route('admin.notification.count') }}",
                {
                    method: 'GET',

                    headers: {
                        'X-Requested-With':
                            'XMLHttpRequest',

                        'Accept':
                            'application/json'
                    },

                    cache:
                        'no-store'
                }
            )

            .then(function (response) {

                if (!response.ok) {

                    throw new Error(
                        'Notification request failed'
                    );

                }

                return response.json();

            })

            .then(function (data) {

                if (
                    !data ||
                    data.success !== true
                ) {

                    return;

                }


                const currentCount =
                    parseInt(
                        data.notificationCount
                    ) || 0;


                updateNotificationBadge(
                    currentCount
                );


                /* FIRST LOAD */

                if (
                    previousNotificationCount === null
                ) {

                    previousNotificationCount =
                        currentCount;

                    return;

                }


                /* NEW NOTIFICATION */

                if (
                    currentCount >
                    previousNotificationCount
                ) {

                    const newNotifications =
                        currentCount -
                        previousNotificationCount;


                    playNotificationSound();

                    animateBell();

                    showNotificationToast(
                        newNotifications
                    );

                    showBrowserNotification(
                        newNotifications
                    );

                }


                previousNotificationCount =
                    currentCount;

            })

            .catch(function (error) {

                console.log(
                    'Notification polling error:',
                    error
                );

            });

        }


        checkNotifications();


        setInterval(
            checkNotifications,
            10000
        );

    }
);


/* =========================================================
   REVENUE CHART
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const chartElement =
            document.getElementById(
                'revenueChart'
            );


        if (!chartElement) {
            return;
        }


        const labels =
            @json($revenueLabels ?? []);


        const revenueData =
            @json($revenueData ?? []);


        const ctx =
            chartElement.getContext(
                '2d'
            );


        /* =====================================================
           GRADIENT
        ====================================================== */

        const gradient =
            ctx.createLinearGradient(
                0,
                0,
                0,
                330
            );


        gradient.addColorStop(
            0,
            'rgba(139,92,246,.22)'
        );


        gradient.addColorStop(
            1,
            'rgba(139,92,246,0)'
        );


        new Chart(
            ctx,
            {

                type: 'line',

                data: {

                    labels: labels,

                    datasets: [

                        {

                            label:
                                'Revenue',

                            data:
                                revenueData,

                            borderColor:
                                '#8b5cf6',

                            backgroundColor:
                                gradient,

                            borderWidth:
                                3,

                            tension:
                                .42,

                            fill:
                                true,

                            pointRadius:
                                4,

                            pointHoverRadius:
                                7,

                            pointBackgroundColor:
                                '#ffffff',

                            pointBorderColor:
                                '#8b5cf6',

                            pointBorderWidth:
                                3

                        }

                    ]

                },


                options: {

                    responsive:
                        true,

                    maintainAspectRatio:
                        false,


                    interaction: {

                        intersect:
                            false,

                        mode:
                            'index'

                    },


                    plugins: {

                        legend: {

                            display:
                                false

                        },


                        tooltip: {

                            backgroundColor:
                                '#ffffff',

                            titleColor:
                                '#302b48',

                            bodyColor:
                                '#6f6a7f',

                            borderColor:
                                'rgba(139,92,246,.15)',

                            borderWidth:
                                1,

                            padding:
                                12,

                            displayColors:
                                false,

                            callbacks: {

                                label:
                                    function (
                                        context
                                    ) {

                                        return (
                                            ' Revenue: ₹' +
                                            Number(
                                                context.parsed.y || 0
                                            ).toLocaleString(
                                                'en-IN',
                                                {
                                                    minimumFractionDigits:
                                                        2
                                                }
                                            )
                                        );

                                    }

                            }

                        }

                    },


                    scales: {

                        x: {

                            grid: {

                                display:
                                    false

                            },

                            ticks: {

                                color:
                                    '#9994a8',

                                font: {

                                    size:
                                        10

                                }

                            }

                        },


                        y: {

                            beginAtZero:
                                true,

                            grid: {

                                color:
                                    'rgba(120,100,160,.07)'

                            },

                            border: {

                                display:
                                    false

                            },

                            ticks: {

                                color:
                                    '#9994a8',

                                font: {

                                    size:
                                        10

                                },

                                callback:
                                    function (
                                        value
                                    ) {

                                        return (
                                            '₹' +
                                            Number(
                                                value
                                            ).toLocaleString(
                                                'en-IN'
                                            )
                                        );

                                    }

                            }

                        }

                    }

                }

            }
        );

    }
);


/* =========================================================
   DISABLE RIGHT CLICK / SHORTCUTS
========================================================= */

document.addEventListener(
    'contextmenu',
    function (e) {

        e.preventDefault();

    }
);


document.addEventListener(
    'keydown',
    function (e) {

        if (e.key === 'F12') {

            e.preventDefault();

            return false;

        }


        if (
            e.ctrlKey &&
            e.shiftKey &&
            e.key.toLowerCase() === 'i'
        ) {

            e.preventDefault();

            return false;

        }


        if (
            e.ctrlKey &&
            e.shiftKey &&
            e.key.toLowerCase() === 'j'
        ) {

            e.preventDefault();

            return false;

        }


        if (
            e.ctrlKey &&
            e.shiftKey &&
            e.key.toLowerCase() === 'c'
        ) {

            e.preventDefault();

            return false;

        }


        if (
            e.ctrlKey &&
            e.key.toLowerCase() === 'u'
        ) {

            e.preventDefault();

            return false;

        }

    }
);

</script>


@stack('scripts')


</body>

</html>