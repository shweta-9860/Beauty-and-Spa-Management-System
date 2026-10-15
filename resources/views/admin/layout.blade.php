<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin | Salon Management')
    </title>

    {{-- Favicon --}}
    <link rel="icon"
          type="image/jpeg"
          sizes="512x512"
          href="{{ asset('storage/salons/faviconn.jpg') }}">

    {{-- AdminLTE 4 --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        /* =========================================================
           3D LIGHT SALON ADMIN THEME
        ========================================================= */

        :root {

            --primary: #8875f5;
            --primary-light: #eeeaff;
            --primary-soft: #f6f3ff;

            --pink: #ef91b8;
            --pink-light: #fff0f6;

            --blue: #6bb8f7;
            --blue-light: #edf8ff;

            --mint: #62cdb1;
            --mint-light: #edfcf7;

            --orange: #f5b477;
            --orange-light: #fff6ec;

            --yellow: #efca6d;
            --yellow-light: #fffbea;

            --danger: #ed8d9b;
            --danger-light: #fff0f2;

            --text: #56516b;
            --heading: #464158;
            --muted: #89849a;

            --page: #f7f5fc;
            --white: #ffffff;

            --border: #ebe7f4;

            --shadow-sm:
                0 4px 15px rgba(106, 93, 145, .07);

            --shadow-md:
                0 12px 30px rgba(106, 93, 145, .11);

            --shadow-lg:
                0 20px 50px rgba(106, 93, 145, .15);

            --shadow-3d:
                0 18px 35px rgba(116, 103, 161, .14),
                0 5px 10px rgba(116, 103, 161, .08);

            --radius:
                20px;
        }


        /* =========================================================
           GLOBAL
        ========================================================= */

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
                    rgba(136,117,245,.08),
                    transparent 25%
                ),
                radial-gradient(
                    circle at 90% 20%,
                    rgba(239,145,184,.08),
                    transparent 25%
                ),
                radial-gradient(
                    circle at 50% 90%,
                    rgba(107,184,247,.07),
                    transparent 28%
                ),
                var(--page);

            color: var(--text);

            font-family:
                Inter,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            overflow-x: hidden;
        }


        a {
            text-decoration: none;
        }


        /* =========================================================
           APP WRAPPER
        ========================================================= */

        .app-wrapper {

            min-height: 100vh;

            background:
                linear-gradient(
                    135deg,
                    #faf9ff 0%,
                    #f7f5fc 50%,
                    #fff8fb 100%
                );
        }


        /* =========================================================
           NAVBAR
        ========================================================= */

        .app-header {

            background: rgba(255,255,255,.92) !important;

            backdrop-filter: blur(18px);

            -webkit-backdrop-filter: blur(18px);

            border-bottom:
                1px solid rgba(229,224,242,.85) !important;

            box-shadow:
                0 5px 25px rgba(96,83,135,.07);

            min-height: 64px;

            position: relative;

            z-index: 1030;
        }


        .app-header .nav-link {

            color: var(--text) !important;

            border-radius: 14px;

            margin: 7px 4px;

            transition:
                all .25s ease;

            position: relative;
        }


        .app-header .nav-link:hover {

            background:
                var(--primary-light);

            color:
                var(--primary) !important;

            transform:
                translateY(-2px);
        }


        .app-header .bi-list {

            font-size: 22px;
        }


        /* =========================================================
           NAVBAR NOTIFICATION
        ========================================================= */

        #notificationBell {

            width: 44px;
            height: 44px;

            display: flex;
            align-items: center;
            justify-content: center;
        }


        #notificationBell > i {

            font-size: 20px;

            transition:
                transform .25s ease;
        }


        #notificationBell:hover > i {

            transform:
                rotate(-12deg)
                scale(1.08);
        }


        .navbar-badge {

            position: absolute !important;

            top: 1px !important;
            right: 0 !important;

            min-width: 20px;
            height: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50% !important;

            background:
                linear-gradient(
                    135deg,
                    #ef91b8,
                    #e879a8
                ) !important;

            border:
                3px solid #ffffff;

            font-size: 10px;

            box-shadow:
                0 5px 12px rgba(232,121,168,.25);
        }


        /* =========================================================
           NOTIFICATION DROPDOWN
        ========================================================= */

        .notification-dropdown {

            border: 0 !important;

            border-radius: 20px !important;

            background:
                rgba(255,255,255,.97);

            box-shadow:
                var(--shadow-lg);

            overflow: hidden;

            padding: 8px !important;

            backdrop-filter: blur(20px);
        }


        .notification-dropdown .dropdown-header {

            padding: 13px 12px;

            color: var(--heading);

            font-size: 14px;

            background:
                linear-gradient(
                    135deg,
                    #f7f4ff,
                    #fff4f8
                );

            border-radius: 14px;
        }


        .notification-dropdown .dropdown-item {

            border-radius: 13px;

            padding: 11px 12px;

            margin: 2px 0;

            color: var(--text);

            transition:
                all .2s ease;
        }


        .notification-dropdown .dropdown-item:hover {

            background:
                var(--primary-light);

            transform:
                translateX(3px);
        }


        .notification-icon {

            width: 40px;
            height: 40px;

            min-width: 40px;

            border-radius: 14px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin-right: 11px;

            font-size: 17px;

            box-shadow:
                0 5px 12px rgba(100,90,140,.08);
        }


        /* =========================================================
           USER DROPDOWN
        ========================================================= */

        .app-header .dropdown-menu {

            border: 0;

            border-radius: 18px;

            box-shadow:
                var(--shadow-lg);

            padding: 8px;

            background:
                rgba(255,255,255,.97);
        }


        .app-header .dropdown-menu .dropdown-item {

            border-radius: 11px;

            color: var(--text);

            padding: 10px 12px;
        }


        .app-header .dropdown-menu .dropdown-item:hover {

            background:
                var(--primary-light);

            color:
                var(--primary);
        }


        /* =========================================================
           SIDEBAR - LIGHT 3D
        ========================================================= */

        .app-sidebar {

            background:
                linear-gradient(
                    180deg,
                    #ffffff 0%,
                    #fbfaff 55%,
                    #fffafd 100%
                ) !important;

            border-right:
                1px solid #ece8f5 !important;

            box-shadow:
                8px 0 35px rgba(98,87,138,.07) !important;

            color:
                var(--text) !important;

            overflow: hidden;
        }


        .sidebar-wrapper {

            background:
                transparent !important;

            padding-bottom: 20px;
        }


        /* =========================================================
           SIDEBAR BRAND
        ========================================================= */

        .sidebar-brand {

            height: 72px;

            padding: 10px 12px;

            background:
                linear-gradient(
                    135deg,
                    #ffffff,
                    #f8f5ff
                ) !important;

            border-bottom:
                1px solid #eeeaf6;
        }


        .sidebar-brand .brand-link {

            height: 100%;

            display: flex;

            align-items: center;

            gap: 11px;

            border: 0 !important;

            background: transparent !important;

            color: var(--heading) !important;
        }


        .brand-image {

            width: 43px !important;
            height: 43px !important;

            min-width: 43px;

            border-radius: 15px !important;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #a594ff,
                    #ef91b8
                ) !important;

            color:
                #ffffff !important;

            padding: 0 !important;

            font-size: 20px;

            box-shadow:
                0 10px 20px rgba(136,117,245,.25);

            transform:
                perspective(400px)
                rotateY(-7deg);
        }


        .brand-text {

            color:
                var(--heading) !important;

            font-size: 15px;

            font-weight: 800 !important;

            letter-spacing: -.2px;
        }


        /* =========================================================
           SIDEBAR NAV
        ========================================================= */

        .nav-sidebar {

            padding:
                10px 8px;
        }


        .nav-sidebar .nav-header {

            color:
                #aaa5b8 !important;

            font-size: 10px;

            font-weight: 800;

            letter-spacing: 1.2px;

            padding:
                15px 12px 7px;
        }


        .nav-sidebar .nav-item {

            margin-bottom: 3px;
        }


        .nav-sidebar .nav-link {

            color:
                #777186 !important;

            border-radius: 14px;

            margin: 3px 4px;

            padding:
                10px 11px;

            font-size: 13px;

            min-height: 43px;

            display: flex;

            align-items: center;

            transition:
                all .25s cubic-bezier(.2,.8,.2,1);

            position: relative;
        }


        .nav-sidebar .nav-link::before {

            content: "";

            position: absolute;

            left: 0;

            top: 9px;
            bottom: 9px;

            width: 3px;

            border-radius: 0 5px 5px 0;

            background:
                var(--primary);

            opacity: 0;

            transition:
                opacity .2s ease;
        }


        .nav-sidebar .nav-link:hover {

            background:
                linear-gradient(
                    135deg,
                    #f4f1ff,
                    #fff2f7
                ) !important;

            color:
                var(--primary) !important;

            transform:
                translateX(3px)
                translateZ(5px);

            box-shadow:
                0 7px 18px rgba(110,95,160,.08);
        }


        .nav-sidebar .nav-link.active {

            background:
                linear-gradient(
                    135deg,
                    #eeeaff,
                    #fff0f7
                ) !important;

            color:
                var(--primary) !important;

            font-weight: 700;

            box-shadow:
                0 8px 20px rgba(110,95,160,.10);
        }


        .nav-sidebar .nav-link.active::before {

            opacity: 1;
        }


        .nav-sidebar .nav-icon {

            width: 28px;

            margin-right: 4px;

            color:
                #9389ae !important;

            font-size: 17px;

            transition:
                all .25s ease;
        }


        .nav-sidebar .nav-link:hover .nav-icon,
        .nav-sidebar .nav-link.active .nav-icon {

            color:
                var(--primary) !important;

            transform:
                translateZ(8px)
                scale(1.08);
        }


        .nav-sidebar p {

            margin: 0;

            line-height: 1.3;
        }


        /* =========================================================
           TREEVIEW
        ========================================================= */

        .nav-treeview {

            padding:
                3px 0 5px;
        }


        .nav-treeview .nav-link {

            padding-left:
                39px !important;

            min-height: 38px;

            font-size: 12px;

            background:
                transparent !important;

            box-shadow: none !important;
        }


        .nav-treeview .nav-link:hover {

            background:
                #f7f4ff !important;

            transform:
                translateX(4px);
        }


        .nav-treeview .nav-link.active {

            background:
                #f2eeff !important;

            color:
                var(--primary) !important;
        }


        .nav-treeview .nav-icon {

            font-size: 13px !important;

            width: 23px;
        }


        .nav-arrow {

            margin-left: auto;

            color:
                #aaa4b8;

            transition:
                transform .25s ease;
        }


        .nav-item.menu-open >
        .nav-link .nav-arrow {

            transform:
                rotate(90deg);

            color:
                var(--primary);
        }


        /* =========================================================
           LOGOUT
        ========================================================= */

        .nav-sidebar form .nav-link {

            width: calc(100% - 8px);

            color:
                #b17c91 !important;
        }


        .nav-sidebar form .nav-link:hover {

            background:
                #fff1f5 !important;

            color:
                #df7799 !important;
        }


        /* =========================================================
           MAIN AREA
        ========================================================= */

        .app-main {

            background:
                transparent !important;

            min-height:
                calc(100vh - 64px);
        }


        .app-content {

            background:
                transparent !important;

            padding-bottom: 30px;
        }


        /* =========================================================
           PAGE HEADER
        ========================================================= */

        .app-content-header {

            padding:
                22px 0 12px;
        }


        .app-content-header h3 {

            color:
                var(--heading);

            font-size: 24px;

            font-weight: 800;

            letter-spacing: -.5px;
        }


        .breadcrumb {

            margin-bottom: 0;
        }


        .breadcrumb-item {

            color:
                #9a95a9;

            font-size: 12px;
        }


        .breadcrumb-item.active {

            color:
                var(--primary);

            font-weight: 700;
        }


        /* =========================================================
           GENERAL 3D CARD
        ========================================================= */

        .dashboard-card {

            background:
                rgba(255,255,255,.96);

            border:
                1px solid rgba(234,230,244,.95);

            border-radius:
                var(--radius);

            box-shadow:
                var(--shadow-3d);

            transition:
                transform .3s cubic-bezier(.2,.8,.2,1),
                box-shadow .3s ease;

            position: relative;

            overflow: hidden;
        }


        .dashboard-card:hover {

            transform:
                translateY(-5px)
                perspective(900px)
                rotateX(1deg);

            box-shadow:
                0 25px 55px rgba(110,98,150,.17);
        }


        /* =========================================================
           CONTENT CARD
        ========================================================= */

        .card {

            border: 0 !important;

            border-radius:
                20px !important;

            box-shadow:
                var(--shadow-sm);

            overflow: hidden;

            background:
                rgba(255,255,255,.96);
        }


        .card-header {

            background:
                rgba(255,255,255,.7) !important;

            border-bottom:
                1px solid #f0edf6 !important;

            padding:
                15px 18px;

            color:
                var(--heading);
        }


        .card-title {

            color:
                var(--heading) !important;

            font-weight:
                750 !important;
        }


        /* =========================================================
           TABLE
        ========================================================= */

        .table {

            color:
                var(--text);
        }


        .table th {

            font-size: 11px;

            color:
                #918b9f;

            white-space: nowrap;

            font-weight: 750;

            text-transform:
                uppercase;

            letter-spacing: .4px;

            border-bottom:
                1px solid #f0edf5;
        }


        .table td {

            font-size: 13px;

            vertical-align:
                middle;

            border-color:
                #f3f0f7;
        }


        .table tbody tr {

            transition:
                all .2s ease;
        }


        .table tbody tr:hover {

            background:
                #faf8ff;

            transform:
                scale(1.003);
        }


        /* =========================================================
           BUTTONS
        ========================================================= */

        .btn {

            border-radius:
                11px;

            font-weight:
                650;

            transition:
                all .25s ease;
        }


        .btn:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 8px 18px rgba(100,90,140,.12);
        }


        .btn-primary {

            border: 0 !important;

            background:
                linear-gradient(
                    135deg,
                    #8d7af7,
                    #a491ff
                ) !important;

            box-shadow:
                0 8px 18px rgba(136,117,245,.22);
        }


        .btn-success {

            border: 0 !important;

            background:
                linear-gradient(
                    135deg,
                    #62cdb1,
                    #79d8c1
                ) !important;
        }


        /* =========================================================
           BADGES
        ========================================================= */

        .badge {

            border-radius:
                9px;

            padding:
                5px 8px;

            font-weight:
                650;
        }


        /* =========================================================
           NOTIFICATION TOAST
        ========================================================= */

        #notificationToastContainer {

            position:
                fixed;

            top:
                76px;

            right:
                20px;

            width:
                min(390px, calc(100vw - 30px));

            z-index:
                99999;

            pointer-events:
                none;
        }


        .salon-notification-toast {

            display:
                flex;

            align-items:
                flex-start;

            gap:
                12px;

            background:
                rgba(255,255,255,.97);

            border-radius:
                18px;

            padding:
                14px;

            margin-bottom:
                12px;

            box-shadow:
                0 18px 45px rgba(100,90,140,.18);

            border:
                1px solid #ebe6f5;

            pointer-events:
                auto;

            cursor:
                pointer;

            animation:
                salonNotificationIn .35s ease forwards;

            overflow:
                hidden;

            backdrop-filter:
                blur(15px);
        }


        .salon-notification-toast.hide {

            animation:
                salonNotificationOut .3s ease forwards;
        }


        .salon-notification-icon {

            width:
                44px;

            height:
                44px;

            min-width:
                44px;

            border-radius:
                14px;

            background:
                linear-gradient(
                    135deg,
                    #8d7af7,
                    #ef91b8
                );

            color:
                #fff;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            font-size:
                19px;

            box-shadow:
                0 9px 20px rgba(136,117,245,.22);
        }


        .salon-notification-content {

            flex:
                1;

            min-width:
                0;
        }


        .salon-notification-title {

            font-size:
                14px;

            font-weight:
                750;

            color:
                var(--heading);

            margin-bottom:
                3px;
        }


        .salon-notification-message {

            font-size:
                13px;

            color:
                #858094;

            line-height:
                1.4;
        }


        .salon-notification-time {

            font-size:
                10px;

            color:
                #aaa5b5;

            margin-top:
                5px;
        }


        .salon-notification-close {

            border:
                0;

            background:
                transparent;

            color:
                #aaa5b5;

            width:
                26px;

            height:
                26px;

            border-radius:
                50%;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            flex-shrink:
                0;
        }


        .salon-notification-close:hover {

            background:
                #f3effb;

            color:
                var(--primary);
        }


        @keyframes salonNotificationIn {

            from {
                opacity: 0;
                transform:
                    translateX(120%)
                    rotateY(-8deg);
            }

            to {
                opacity: 1;
                transform:
                    translateX(0)
                    rotateY(0);
            }
        }


        @keyframes salonNotificationOut {

            from {
                opacity: 1;
                transform:
                    translateX(0);
            }

            to {
                opacity: 0;
                transform:
                    translateX(120%);
            }
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .app-footer {

            background:
                rgba(255,255,255,.8) !important;

            border-top:
                1px solid #ece8f4;

            color:
                #858094;

            padding:
                13px 18px;

            font-size:
                12px;

            box-shadow:
                0 -5px 20px rgba(100,90,140,.04);
        }


        .app-footer strong {

            color:
                var(--heading);
        }


        /* =========================================================
           FORM ELEMENTS
        ========================================================= */

        .form-control,
        .form-select {

            border:
                1px solid #e5e0ef;

            border-radius:
                11px;

            color:
                var(--text);

            background:
                #ffffff;

            min-height:
                40px;

            transition:
                all .2s ease;
        }


        .form-control:focus,
        .form-select:focus {

            border-color:
                #afa1f9;

            box-shadow:
                0 0 0 4px rgba(136,117,245,.09);

            transform:
                translateY(-1px);
        }


        /* =========================================================
           3D FLOATING DECORATION
        ========================================================= */

        .app-main::before {

            content: "";

            position:
                fixed;

            width:
                280px;

            height:
                280px;

            border-radius:
                50%;

            background:
                radial-gradient(
                    circle,
                    rgba(136,117,245,.07),
                    transparent 70%
                );

            top:
                130px;

            right:
                20px;

            pointer-events:
                none;

            z-index:
                -1;
        }


        .app-main::after {

            content: "";

            position:
                fixed;

            width:
                240px;

            height:
                240px;

            border-radius:
                50%;

            background:
                radial-gradient(
                    circle,
                    rgba(239,145,184,.06),
                    transparent 70%
                );

            bottom:
                80px;

            left:
                240px;

            pointer-events:
                none;

            z-index:
                -1;
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 768px) {

            .app-content-header {

                padding:
                    16px 0 8px;
            }


            .app-content-header h3 {

                font-size:
                    20px;
            }


            .breadcrumb {

                margin-top:
                    5px;
            }


            .app-sidebar {

                box-shadow:
                    10px 0 35px rgba(80,70,120,.15) !important;
            }


            #notificationToastContainer {

                top:
                    68px;

                left:
                    10px;

                right:
                    10px;

                width:
                    auto;
            }


            .notification-dropdown {

                min-width:
                    300px !important;

                max-width:
                    calc(100vw - 20px);
            }
        }


        @media (max-width: 576px) {

            .brand-text {

                font-size:
                    13px;
            }


            .app-footer {

                text-align:
                    center;
            }


            .app-footer .float-end {

                float:
                    none !important;

                display:
                    block !important;

                margin-top:
                    4px;
            }
        }


        /* =========================================================
           REDUCE MOTION
        ========================================================= */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {

                animation-duration:
                    .01ms !important;

                animation-iteration-count:
                    1 !important;

                transition-duration:
                    .01ms !important;
            }
        }

    </style>

    @stack('styles')

</head>


<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">


{{-- =========================================================
    INVENTORY STOCK NOTIFICATIONS
========================================================= --}}

@php

    $lowStockProducts = collect();

    $outOfStockProducts = collect();

    if (auth()->check() && auth()->user()->salon_id) {

        $inventoryProducts = \App\Models\Product::where(
            'salon_id',
            auth()->user()->salon_id
        )->get();


        $outOfStockProducts = $inventoryProducts->filter(function ($product) {

            return (float) $product->stock <= 0;

        });


        $lowStockProducts = $inventoryProducts->filter(function ($product) {

            return (float) $product->stock > 0
                && (float) $product->stock <= (float) $product->minimum_stock;

        });

    }


    $inventoryNotificationCount =
        $lowStockProducts->count()
        + $outOfStockProducts->count();

@endphp


<div class="app-wrapper">


{{-- =========================================================
    NAVBAR
========================================================= --}}

<nav class="app-header navbar navbar-expand">

    <div class="container-fluid">


        {{-- SIDEBAR TOGGLE --}}

        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link"
                   data-lte-toggle="sidebar"
                   href="#"
                   aria-label="Toggle Sidebar">

                    <i class="bi bi-list"></i>

                </a>

            </li>

        </ul>


        {{-- RIGHT NAVBAR --}}

        <ul class="navbar-nav ms-auto">


            {{-- =================================================
                NOTIFICATION
            ================================================== --}}

            <li class="nav-item dropdown">

                <a class="nav-link"
                   id="notificationBell"
                   data-bs-toggle="dropdown"
                   href="#"
                   aria-label="Notifications">

                    <i class="bi bi-bell"></i>

                    <span id="notificationBadge"
                          class="navbar-badge badge"
                          style="{{ (($todayAppointments ?? 0) + $inventoryNotificationCount) > 0 ? '' : 'display:none;' }}">

                        {{ ($todayAppointments ?? 0) + $inventoryNotificationCount }}

                    </span>

                </a>


                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end notification-dropdown"
                     style="min-width:360px;">


                    <div class="dropdown-header fw-bold">

                        <i class="bi bi-bell-fill me-1"
                           style="color:var(--primary);"></i>

                        Notifications


                        <span id="dropdownNotificationCount"
                              class="badge float-end"
                              style="background:var(--pink-light);color:#d9799e;">

                            {{ ($todayAppointments ?? 0) + $inventoryNotificationCount }}

                        </span>


                        <button type="button"
                                id="enableNotificationSound"
                                class="btn btn-sm btn-outline-secondary ms-2"
                                style="font-size:11px;border-radius:9px;">

                            🔊 Enable Sound

                        </button>

                    </div>


                    <div class="dropdown-divider"></div>


                    {{-- TODAY'S APPOINTMENTS --}}

                    @if(($todayAppointments ?? 0) > 0)

                        <a href="{{ route('admin.appointments.index', ['appointment_date' => now()->format('Y-m-d')]) }}"
                           class="dropdown-item">

                            <div class="d-flex align-items-center">

                                <div class="notification-icon"
                                     style="background:var(--blue-light);color:#5da9e8;">

                                    <i class="bi bi-calendar-check"></i>

                                </div>

                                <div>

                                    <div class="fw-semibold">

                                        {{ $todayAppointments }}

                                        Today's Appointment{{ $todayAppointments == 1 ? '' : 's' }}

                                    </div>

                                    <small class="text-muted">
                                        Check today's appointments
                                    </small>

                                </div>

                            </div>

                        </a>

                    @endif


                    {{-- OUT OF STOCK --}}

                    @foreach($outOfStockProducts as $product)

                        <a href="{{ route('admin.inventory.products.index') }}"
                           class="dropdown-item">

                            <div class="d-flex align-items-center">

                                <div class="notification-icon"
                                     style="background:var(--danger-light);color:#df8190;">

                                    <i class="bi bi-x-circle-fill"></i>

                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        {{ $product->name }}
                                    </div>

                                    <small style="color:#df8190;">
                                        Out of Stock
                                    </small>

                                </div>

                            </div>

                        </a>

                    @endforeach


                    {{-- LOW STOCK --}}

                    @foreach($lowStockProducts as $product)

                        <a href="{{ route('admin.inventory.products.index') }}"
                           class="dropdown-item">

                            <div class="d-flex align-items-center">

                                <div class="notification-icon"
                                     style="background:var(--yellow-light);color:#d8ad48;">

                                    <i class="bi bi-exclamation-triangle-fill"></i>

                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        {{ $product->name }}
                                    </div>

                                    <small style="color:#c49d3e;">

                                        Stock:
                                        {{ rtrim(rtrim(number_format((float)$product->stock, 2), '0'), '.') }}

                                        /

                                        Minimum:
                                        {{ rtrim(rtrim(number_format((float)$product->minimum_stock, 2), '0'), '.') }}

                                    </small>

                                </div>

                            </div>

                        </a>

                    @endforeach


                    @if(
                        ($todayAppointments ?? 0) === 0 &&
                        $outOfStockProducts->count() === 0 &&
                        $lowStockProducts->count() === 0
                    )

                        <div id="noNotificationMessage"
                             class="dropdown-item text-center py-4">

                            <i class="bi bi-check-circle fs-3 d-block mb-1"
                               style="color:var(--mint);"></i>

                            <span style="color:var(--muted);">
                                No new notifications
                            </span>

                        </div>

                    @endif


                    <div class="dropdown-divider"></div>


                    <a href="{{ route('admin.appointments.index', ['appointment_date' => now()->format('Y-m-d')]) }}"
                       class="dropdown-item text-center fw-semibold">

                        <i class="bi bi-calendar-check me-1"
                           style="color:var(--primary);"></i>

                        Today's Appointments

                    </a>


                    <a href="{{ route('admin.customers.index') }}"
                       class="dropdown-item text-center fw-semibold">

                        <i class="bi bi-people me-1"
                           style="color:var(--pink);"></i>

                        Customer Management

                    </a>


                    @if(isset($todayRevenue))

                        <div class="dropdown-divider"></div>

                        <div class="dropdown-item">

                            <i class="bi bi-currency-rupee me-2"
                               style="color:var(--mint);"></i>

                            Today's Revenue:

                            <strong>
                                ₹{{ number_format($todayRevenue, 2) }}
                            </strong>

                        </div>

                    @endif

                </div>

            </li>


            {{-- =================================================
                USER
            ================================================== --}}

            <li class="nav-item dropdown">

                <a class="nav-link dropdown-toggle"
                   href="#"
                   data-bs-toggle="dropdown">

                    <i class="bi bi-person-circle me-1"
                       style="color:var(--primary);"></i>

                    {{ auth()->user()->name ?? 'Admin' }}

                </a>


                <ul class="dropdown-menu dropdown-menu-end">


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

                            <i class="bi bi-shield-lock me-2"
                               style="color:var(--primary);"></i>

                            Two-Factor Authentication

                        </a>

                    </li>


                    <li>

                        <form method="POST"
                              action="{{ route('admin.logout') }}">

                            @csrf

                            <button type="submit"
                                    class="dropdown-item">

                                <i class="bi bi-box-arrow-right me-2"
                                   style="color:var(--pink);"></i>

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

<aside class="app-sidebar shadow"
       data-bs-theme="light">


    {{-- BRAND --}}

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


    {{-- SIDEBAR MENU --}}

    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="menu"
                data-accordion="false">


                {{-- =================================================
                    MAIN MENU
                ================================================== --}}

                <li class="nav-header">
                    MAIN MENU
                </li>


                {{-- DASHBOARD --}}

                <li class="nav-item">

                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-grid-1x2"></i>

                        <p>Dashboard</p>

                    </a>

                </li>


                {{-- =================================================
                    APPOINTMENTS
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.appointments.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-calendar-heart"></i>

                        <p>
                            Appointments
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.appointments.calendar') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.appointments.calendar') ? 'active' : '' }}">

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


                {{-- =================================================
                    CUSTOMERS
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.customers.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">

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


                {{-- =================================================
                    SERVICES
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.categories.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.services.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">

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


                {{-- =================================================
                    STAFF
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.staff.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">

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


                        @php

                            $scheduleStaff = null;

                            if (auth()->check() && auth()->user()->branch_id) {

                                $scheduleStaff =
                                    \App\Models\Staff::where(
                                        'branch_id',
                                        auth()->user()->branch_id
                                    )->first();

                            }

                        @endphp


                       

                        <li class="nav-item">

                            <a href="{{ route('admin.staff.attendance.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.staff.attendance.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-calendar-check"></i>

                                <p>Attendance</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- BRANCHES --}}

                <li class="nav-item">

                    <a href="{{ route('admin.branches.index') }}"
                       class="nav-link
                       {{ request()->routeIs('admin.branches.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-diagram-3"></i>

                        <p>Branches</p>

                    </a>

                </li>


                {{-- PACKAGES --}}

                <li class="nav-item">

                    <a href="{{ route('admin.packages.index') }}"
                       class="nav-link
                       {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-box2-heart"></i>

                        <p>Packages</p>

                    </a>

                </li>


                {{-- CUSTOMER PACKAGES --}}

                <li class="nav-item">

                    <a href="{{ route('admin.customer-packages.index') }}"
                       class="nav-link
                       {{ request()->routeIs('admin.customer-packages.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-box-seam"></i>

                        <p>Customer Packages</p>

                    </a>

                </li>


                {{-- =================================================
                    MEMBERSHIP
                ================================================== --}}

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
                               class="nav-link
                               {{ request()->routeIs('admin.membership.assigned-members') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-people"></i>

                                <p>Assigned Members</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- =================================================
                    POS / BILLING
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.billing.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}">

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


                {{-- =================================================
                    INVENTORY
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.inventory.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-box-seam"></i>

                        <p>
                            Inventory
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.products.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.inventory.products.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-box"></i>

                                <p>Products</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.stock.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.inventory.stock.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-boxes"></i>

                                <p>Stock</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.purchases.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.inventory.purchases.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-cart-plus"></i>

                                <p>Purchases</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.suppliers.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.inventory.suppliers.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-truck"></i>

                                <p>Suppliers</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.inventory.wastage.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.inventory.wastage.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-trash"></i>

                                <p>Wastage</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- =================================================
                    OFFERS
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.offers.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.offers.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-percent"></i>

                        <p>
                            Offers
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{ route('admin.offers.discounts.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.offers.discounts.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-tag"></i>

                                <p>Discounts</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.offers.coupons.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.offers.coupons.*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-ticket-perforated"></i>

                                <p>Coupons</p>

                            </a>

                        </li>

                    </ul>

                </li>


                {{-- =================================================
                    EXPENSES
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.expenses.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">

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

                                <i class="nav-icon bi bi-receipt"></i>

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


                {{-- =================================================
                    REPORTS
                ================================================== --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.reports.*') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-bar-chart-line"></i>

                        <p>
                            Reports
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.customers') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Customer Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.appointments') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-calendar-check"></i>
                                <p>Appointment Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.sales') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-receipt"></i>
                                <p>Sales Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.services') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-scissors"></i>
                                <p>Service Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.staff') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-person-badge"></i>
                                <p>Staff & Commission</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.packages') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <p>Package Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.inventory') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-boxes"></i>
                                <p>Inventory Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.reports.expenses') }}"
                               class="nav-link">
                                <i class="nav-icon bi bi-cash-stack"></i>
                                <p>Expense Report</p>
                            </a>
                        </li>

                    </ul>

                </li>


                {{-- =================================================
                    SYSTEM
                ================================================== --}}

                <li class="nav-header mt-2">
                    SYSTEM
                </li>


                {{-- SETTINGS --}}

                <li class="nav-item has-treeview
                    {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.2fa') ? 'menu-open' : '' }}">

                    <a href="#"
                       class="nav-link
                       {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.2fa') ? 'active' : '' }}">

                        <i class="nav-icon bi bi-gear"></i>

                        <p>
                            Settings
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>

                    </a>


                    <ul class="nav nav-treeview">


                        <li class="nav-item">

                            <a href="{{ route('admin.settings.index') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-sliders"></i>

                                <p>General Settings</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.settings.printer') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.settings.printer*') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-printer"></i>

                                <p>Printer Settings</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.2fa') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.2fa') ? 'active' : '' }}">

                                <i class="nav-icon bi bi-shield-lock"></i>

                                <p>2FA</p>

                            </a>

                        </li>


                        <li class="nav-item">

                            <a href="{{ route('admin.settings.backup') }}"
                               class="nav-link
                               {{ request()->routeIs('admin.settings.backup*') ? 'active' : '' }}">

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
    MAIN CONTENT
========================================================= --}}

<main class="app-main">


    {{-- PAGE HEADER --}}

    <div class="app-content-header">

        <div class="container-fluid">

            <div class="row align-items-center">

                <div class="col-sm-6">

                    <h3 class="mb-0">

                        @yield('page-title', 'Dashboard')

                    </h3>

                </div>


                <div class="col-sm-6">

                    <ol class="breadcrumb float-sm-end">

                        <li class="breadcrumb-item">
                            Admin
                        </li>

                        <li class="breadcrumb-item active">

                            @yield('page-title', 'Dashboard')

                        </li>

                    </ol>

                </div>

            </div>

        </div>

    </div>


    {{-- PAGE CONTENT --}}

    <div class="app-content">

        <div class="container-fluid">

            @yield('content')

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

<div id="notificationToastContainer"
     aria-live="polite"
     aria-atomic="true"></div>


{{-- =========================================================
    NOTIFICATION SOUND
========================================================= --}}

<audio id="notificationSound"
       preload="auto">

    <source src="{{ asset('storage/notification/notification.mp3') }}"
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


@stack('scripts')


{{-- =========================================================
    DISABLE RIGHT CLICK / DEVTOOLS SHORTCUTS
========================================================= --}}

<script>

document.addEventListener('contextmenu', function (e) {

    e.preventDefault();

});


document.addEventListener('keydown', function (e) {

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

});

</script>


{{-- =========================================================
    NOTIFICATION SOUND + AJAX POLLING
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    const notificationSound =
        document.getElementById('notificationSound');


    const toastContainer =
        document.getElementById('notificationToastContainer');


    const badgeSelector =
        '.navbar-badge, #notificationBell .badge, .notification-badge';


    let previousNotificationCount = null;

    let soundUnlocked = false;

    let browserPermissionRequested = false;


    /* =========================================================
       SOUND UNLOCK
    ========================================================= */

    function unlockNotificationSound() {

        if (
            soundUnlocked ||
            !notificationSound
        ) {

            return;
        }


        notificationSound.muted = true;

        notificationSound.currentTime = 0;


        const playPromise =
            notificationSound.play();


        if (playPromise !== undefined) {

            playPromise
                .then(function () {

                    notificationSound.pause();

                    notificationSound.currentTime = 0;

                    notificationSound.muted = false;

                    soundUnlocked = true;

                    localStorage.setItem(
                        'notificationSoundEnabled',
                        '1'
                    );


                    const enableButton =
                        document.getElementById(
                            'enableNotificationSound'
                        );


                    if (enableButton) {

                        enableButton.innerHTML =
                            '🔊 Sound Enabled';

                        enableButton.disabled =
                            true;
                    }

                })
                .catch(function () {

                    notificationSound.muted =
                        false;

                });

        }

    }


    /* =========================================================
       ENABLE SOUND BUTTON
    ========================================================= */

    const enableSoundButton =
        document.getElementById(
            'enableNotificationSound'
        );


    if (
        enableSoundButton &&
        notificationSound
    ) {

        enableSoundButton.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                event.stopPropagation();


                notificationSound.muted =
                    true;

                notificationSound.currentTime =
                    0;


                const playPromise =
                    notificationSound.play();


                if (
                    playPromise !== undefined
                ) {

                    playPromise
                        .then(function () {

                            notificationSound.pause();

                            notificationSound.currentTime =
                                0;

                            notificationSound.muted =
                                false;

                            soundUnlocked =
                                true;


                            localStorage.setItem(
                                'notificationSoundEnabled',
                                '1'
                            );


                            enableSoundButton.innerHTML =
                                '🔊 Sound Enabled';


                            enableSoundButton.disabled =
                                true;

                        })
                        .catch(function (error) {

                            notificationSound.muted =
                                false;

                            console.log(
                                'Unable to enable notification sound:',
                                error
                            );

                        });

                }

            }
        );

    }


    /* =========================================================
       REMEMBER SOUND
    ========================================================= */

    if (
        localStorage.getItem(
            'notificationSoundEnabled'
        ) === '1'
    ) {

        soundUnlocked = true;


        if (enableSoundButton) {

            enableSoundButton.innerHTML =
                '🔊 Sound Enabled';

            enableSoundButton.disabled =
                true;
        }

    }


    /* =========================================================
       FIRST USER INTERACTION
    ========================================================= */

    [
        'click',
        'touchstart',
        'keydown'
    ].forEach(function (eventName) {

        document.addEventListener(
            eventName,
            unlockNotificationSound,
            {
                once: true,
                passive: true
            }
        );

    });


    /* =========================================================
       BROWSER NOTIFICATION
    ========================================================= */

    function requestBrowserNotificationPermission() {

        if (

            browserPermissionRequested ||

            !('Notification' in window) ||

            Notification.permission !== 'default'

        ) {

            return;
        }


        browserPermissionRequested =
            true;


        Notification
            .requestPermission()
            .catch(function () {});

    }


    document.addEventListener(
        'click',
        requestBrowserNotificationPermission,
        {
            once: true
        }
    );


    /* =========================================================
       PLAY SOUND
    ========================================================= */

    function playNotificationSound() {

        if (!notificationSound) {

            return;
        }


        notificationSound.pause();

        notificationSound.currentTime =
            0;

        notificationSound.muted =
            false;


        const promise =
            notificationSound.play();


        if (promise !== undefined) {

            promise
                .then(function () {

                    soundUnlocked = true;

                })
                .catch(function (error) {

                    console.log(
                        'Notification sound blocked:',
                        error
                    );


                    const enableButton =
                        document.getElementById(
                            'enableNotificationSound'
                        );


                    if (enableButton) {

                        enableButton.disabled =
                            false;

                        enableButton.innerHTML =
                            '🔊 Enable Sound';

                    }

                });

        }

    }


    /* =========================================================
       SHOW TOAST
    ========================================================= */

    function showNotificationToast(count) {

        if (!toastContainer) {

            return;
        }


        const toast =
            document.createElement('div');


        toast.className =
            'salon-notification-toast';


        toast.innerHTML = `

            <div class="salon-notification-icon">

                <i class="bi bi-bell-fill"></i>

            </div>


            <div class="salon-notification-content">

                <div class="salon-notification-title">

                    New Notification

                </div>


                <div class="salon-notification-message">

                    You have ${count}
                    active notification${count === 1 ? '' : 's'}
                    in your Salon Management panel.

                </div>


                <div class="salon-notification-time">

                    Just now

                </div>

            </div>


            <button
                type="button"
                class="salon-notification-close"
                aria-label="Close">

                <i class="bi bi-x-lg"></i>

            </button>

        `;


        toast.addEventListener(
            'click',
            function (event) {

                if (
                    event.target.closest(
                        '.salon-notification-close'
                    )
                ) {

                    return;
                }


                window.location.href =
                    "{{ route('admin.dashboard') }}";

            }
        );


        const closeButton =
            toast.querySelector(
                '.salon-notification-close'
            );


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function (event) {

                    event.stopPropagation();

                    removeToast(toast);

                }
            );

        }


        toastContainer.prepend(toast);


        setTimeout(function () {

            removeToast(toast);

        }, 7000);

    }


    /* =========================================================
       REMOVE TOAST
    ========================================================= */

    function removeToast(toast) {

        if (
            !toast ||
            toast.classList.contains('hide')
        ) {

            return;
        }


        toast.classList.add('hide');


        setTimeout(function () {

            if (toast.parentNode) {

                toast.parentNode.removeChild(
                    toast
                );

            }

        }, 300);

    }


    /* =========================================================
       BROWSER / WINDOWS NOTIFICATION
    ========================================================= */

    function showBrowserNotification(count) {

        if (

            !('Notification' in window) ||

            Notification.permission !== 'granted'

        ) {

            return;
        }


        try {

            const browserNotification =
                new Notification(
                    'Salon Management',
                    {
                        body:
                            'You have ' +
                            count +
                            ' new notification' +
                            (count === 1 ? '' : 's') +
                            '.',

                        icon:
                            "{{ asset('storage/salons/faviconn.jpg') }}",

                        tag:
                            'salon-management-notification'
                    }
                );


            browserNotification.onclick =
                function () {

                    window.focus();

                    window.location.href =
                        "{{ route('admin.dashboard') }}";

                    browserNotification.close();

                };


            setTimeout(function () {

                browserNotification.close();

            }, 7000);


        } catch (error) {

            console.log(
                'Browser notification error:',
                error
            );

        }

    }


    /* =========================================================
       UPDATE BADGE
    ========================================================= */

    function updateNotificationBadge(count) {

        let badge =
            document.querySelector(
                badgeSelector
            );


        if (!badge && count > 0) {

            const bellLink =
                document.querySelector(
                    '#notificationBell'
                );


            if (bellLink) {

                badge =
                    document.createElement('span');


                badge.className =
                    'navbar-badge badge';


                bellLink.appendChild(badge);

            }

        }


        if (!badge) {

            return;
        }


        badge.textContent =
            count;


        badge.style.display =
            count > 0
                ? ''
                : 'none';


        const dropdownCount =
            document.getElementById(
                'dropdownNotificationCount'
            );


        if (dropdownCount) {

            dropdownCount.textContent =
                count;

            dropdownCount.style.display =
                count > 0
                    ? ''
                    : 'none';

        }

    }


    /* =========================================================
       AJAX NOTIFICATION CHECK
    ========================================================= */

    function checkNotifications() {

        fetch(
            "{{ route('admin.notification.count') }}",
            {
                method:
                    'GET',

                headers:
                    {
                        'Accept':
                            'application/json',

                        'X-Requested-With':
                            'XMLHttpRequest'
                    },

                credentials:
                    'same-origin',

                cache:
                    'no-store'
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Notification request failed: ' +
                    response.status
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
                    data.notificationCount,
                    10
                ) || 0;


            updateNotificationBadge(
                currentCount
            );


            /* First load */

            if (
                previousNotificationCount ===
                null
            ) {

                previousNotificationCount =
                    currentCount;

                return;
            }


            /* New notification */

            if (
                currentCount >
                previousNotificationCount
            ) {

                const newCount =
                    currentCount -
                    previousNotificationCount;


                showNotificationToast(
                    newCount
                );


                playNotificationSound();


                showBrowserNotification(
                    newCount
                );


                const bell =
                    document.getElementById(
                        'notificationBell'
                    );


                if (bell) {

                    bell.classList.add(
                        'notification-pulse'
                    );


                    setTimeout(function () {

                        bell.classList.remove(
                            'notification-pulse'
                        );

                    }, 3000);

                }

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


    /* =========================================================
       START POLLING
    ========================================================= */

    checkNotifications();


    setInterval(
        checkNotifications,
        10000
    );

});

</script>


</body>

</html>