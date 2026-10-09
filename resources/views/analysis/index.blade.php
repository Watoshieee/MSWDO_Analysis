<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Statistical Analysis - MSWDO</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo_mswdo.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --blue: #2C3E8F;
            --blue-lt: #E5EEFF;
            --yellow: #FDB913;
            --green: #28a745;
            --blue3: #6366f1;
            --grad: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f0f4f8;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .navbar {
            background: var(--grad) !important;
            box-shadow: 0 4px 20px rgba(44, 62, 143, .18);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-toggler {
            order: -1;
        }

        .navbar-brand {
            order: 0;
            margin-left: auto !important;
            margin-right: 0 !important;
        }

        @media (min-width: 992px) {
            .navbar-toggler {
                order: 0;
            }

            .navbar-brand {
                order: 0;
                margin-left: 0 !important;
                margin-right: auto !important;
            }
        }

        .nav-link {
            color: rgba(255, 255, 255, .88) !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 10px 18px !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .15);
            color: #fff !important;
        }

        .nav-link.active {
            background: var(--yellow);
            color: var(--blue) !important;
            font-weight: 700;
        }

        .user-info {
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, .1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: .92rem;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .8);
            color: #fff;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: var(--yellow);
            color: var(--blue);
            border-color: var(--yellow);
        }

        .btn-login {
            background: #fff;
            color: var(--blue);
            border: 2px solid #fff;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-login:hover {
            background: var(--yellow);
            color: var(--blue);
            border-color: var(--yellow);
        }

        .btn-register {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,.8);
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 700;
            text-decoration: none;
            transition: all .3s;
        }

        .btn-register:hover {
            background: var(--yellow);
            color: var(--blue);
            border-color: var(--yellow);
        }

        .hero {
            background: var(--grad);
            color: #fff;
            padding: 52px 0 42px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(253, 185, 19, .1);
        }

        .hero h1 {
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .hero-divider {
            width: 50px;
            height: 4px;
            background: var(--yellow);
            border-radius: 2px;
            margin: 14px 0;
        }

        .hero p {
            opacity: .85;
            font-size: .98rem;
            line-height: 1.7;
            max-width: 680px;
        }

        .year-bar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(44, 62, 143, .06);
        }

        .year-pill {
            padding: 5px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: .83rem;
            text-decoration: none;
            transition: all .2s;
        }

        .section-wrap { padding: 40px 0; }

        .section-wrap.alt { background: #fff; }

        .section-wrap.dark { background: linear-gradient(135deg, #1A2A5C 0%, #2C3E8F 100%); }

        .sec-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1A2A5C;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -.01em;
        }

        .sec-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 44px;
            height: 4px;
            background: var(--yellow);
            border-radius: 2px;
        }

        .sec-title.light {
            color: #FDB913;
        }

        .card-base {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 18px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .card-base::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--grad);
        }

        .card-base.y::before {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        .card-base.g::before {
            background: linear-gradient(135deg, #0891b2, #0369a1);
        }

        .card-base.r::before {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .card-base.card-plain::before {
            display: none;
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            color: var(--blue);
        }

        .stat-lbl {
            color: #64748b;
            font-size: .85rem;
            font-weight: 500;
        }

        .chart-box {
            position: relative;
            height: 300px;
        }

        .chart-box.tall {
            height: 340px;
        }

        table thead th {
            background: var(--grad);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 11px 14px;
            font-size: .85rem;
        }

        table thead th:first-child {
            border-radius: 8px 0 0 0;
        }

        table thead th:last-child {
            border-radius: 0 8px 0 0;
        }

        table tbody td {
            padding: 10px 14px;
            font-size: .85rem;
            vertical-align: middle;
        }

        .badge-sig {
            background: #dcfce7;
            color: #166534;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 700;
        }

        .badge-nosig {
            background: #fef9c3;
            color: #854d0e;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 700;
        }

        .badge-strong {
            background: #dbeafe;
            color: #1e40af;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 700;
        }

        .badge-moderate {
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 700;
        }

        .badge-weak {
            background: #f1f5f9;
            color: #475569;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .78rem;
            font-weight: 700;
        }

        .insight-card {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 14px;
            padding: 20px;
            transition: background .2s;
        }

        .insight-card:hover {
            background: rgba(255, 255, 255, .11);
        }

        .rec-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 20px;
            border-left: 4px solid var(--blue);
        }

        .footer-strip {
            background: var(--grad);
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: .88rem;
        }

        @media(max-width:768px) {
            .hero h1 {
                font-size: 1.8rem;
            }
        }


        }

        /* ── Reference-style redesign ───────────────────────────── */
        .sec-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 22px;
            background: var(--yellow);
            border-radius: 3px;
            flex-shrink: 0;
        }
        .sec-title::after { display: none; }
        .sec-title.light { color: #fff; }
        .sec-title.light::before { background: var(--yellow); }

        /* Card */
        .card-base {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(44,62,143,.07), 0 4px 16px rgba(44,62,143,.06);
            padding: 22px 24px;
            border: 1px solid #eaecf5;
            height: 100%;
        }

        /* Stat cards */
        .stat-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 1px 4px rgba(44,62,143,.07), 0 4px 16px rgba(44,62,143,.06);
            border: 1px solid #eaecf5;
            padding: 22px 24px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }
        .stat-card-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-card-icon.blue { background: #EEF2FF; }
        .stat-card-icon.gold { background: #FFF8E5; }
        .stat-card-icon.green { background: #EDFDF6; }
        .stat-card-body { flex: 1; min-width: 0; }
        .stat-card-label {
            font-size: .76rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 4px;
        }
        .stat-card-value {
            font-size: 1.85rem;
            font-weight: 800;
            color: #1A2A5C;
            line-height: 1.1;
            margin-bottom: 4px;
        }
        .stat-card-sub {
            font-size: .76rem;
            color: #94a3b8;
            margin-top: 2px;
        }
        .stat-card-change {
            font-size: .78rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .stat-card-change.up { color: #16a34a; }
        .stat-card-change.na { color: #94a3b8; }

        /* Filter strip */
        .filter-strip {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(44,62,143,.06);
        }
        .filter-strip-inner {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .filter-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .filter-row-top {
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .filter-row-bottom {
            padding-top: 7px;
            border-top: 1px solid #f1f5f9;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .filter-group-label {
            font-size: .74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
            white-space: nowrap;
        }

        /* Segmented dataset control */
        .ds-control {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 3px;
            gap: 2px;
        }
        .ds-control a {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: .78rem;
            font-weight: 700;
            text-decoration: none;
            color: #64748b;
            cursor: pointer;
            border: 1.5px solid transparent;
            transition: background .18s, color .18s, border-color .18s, transform .15s, box-shadow .18s;
            white-space: nowrap;
        }
        .ds-control a:hover:not(.ds-active-demo):not(.ds-active-prog):not(.ds-active-all) {
            background: #dde3f5;
            color: #2C3E8F;
            border-color: #b8c4e8;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(44,62,143,0.14);
        }
        .ds-control a.ds-active-demo,
        .ds-control a.ds-active-prog,
        .ds-control a.ds-active-all {
            background: #2C3E8F;
            color: #fff;
            border-color: #2C3E8F;
            box-shadow: 0 2px 8px rgba(44,62,143,0.32);
        }

        /* Year pills */
        .year-pills-wrap {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }
        .yr-pill {
            display: inline-flex;
            align-items: center;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
            text-decoration: none;
            border: 1.5px solid #d0d8ec;
            color: #475569;
            background: #f1f4fb;
            cursor: pointer;
            transition: all .18s;
        }
        .yr-pill:hover:not(.active) {
            border-color: #2C3E8F;
            color: #2C3E8F;
            background: #E8EDFF;
            transform: translateY(-1px);
            box-shadow: 0 2px 7px rgba(44,62,143,0.15);
        }
        .yr-pill.active {
            background: #2C3E8F;
            color: #fff;
            border-color: #2C3E8F;
            box-shadow: 0 2px 6px rgba(44,62,143,0.28);
        }
        @media (max-width: 768px) {
            .filter-row-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            .filter-viewing-group {
                width: 100%;
            }
            .viewing-pill {
                width: 100%;
                justify-content: center;
                text-align: center;
            }
            .year-pills-wrap {
                overflow-x: auto;
                flex-wrap: nowrap;
                max-width: 100%;
                padding-bottom: 2px;
            }
        }

        /* Nav pills for jump links */
        .nav-pills-bar {
            background: #f8fafc;
            border-bottom: 1px solid #e9ecf5;
            padding: 0;
        }
        .nav-pills-inner {
            display: flex;
            align-items: center;
            gap: 2px;
            overflow-x: auto;
            padding: 8px 0;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .nav-pills-inner::-webkit-scrollbar { display: none; }
        .nav-pill-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 7px;
            font-size: .78rem;
            font-weight: 700;
            text-decoration: none;
            color: #64748b;
            cursor: pointer;
            border: 1.5px solid transparent;
            transition: background .18s, color .18s, border-color .18s, transform .15s, box-shadow .18s;
            white-space: nowrap;
        }
        .nav-pill-link:hover {
            background: #E8EDFF;
            color: #2C3E8F;
            border-color: #c7d0ea;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(44,62,143,0.10);
        }
        .nav-pill-link.active {
            background: #EEF2FF;
            color: #2C3E8F;
            border-color: #c7d0ea;
        }

        /* ===== HERO (matches demographic & programs pages) ===== */
        .analysis-hero {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            color: white;
            padding: 58px 0 48px;
            position: relative;
            overflow: hidden;
        }
        .analysis-hero::before {
            content: '';
            position: absolute;
            top: -70px; right: -70px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: rgba(253,185,19,0.10);
        }
        .analysis-hero::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -50px;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .hero-inner { position: relative; z-index: 1; }
        .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,0.18);
            color: #FDB913;
            border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        .hero-title {
            font-size: 2.6rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 0;
        }
        .hero-divider {
            width: 55px;
            height: 4px;
            background: #FDB913;
            border-radius: 2px;
            margin: 16px 0;
        }
        .hero-sub {
            font-size: 1.02rem;
            color: rgba(255,255,255,0.87);
            max-width: 680px;
            line-height: 1.75;
            margin: 0;
        }
        .hero-church-wrap {
            display: flex; flex-direction: column;
            align-items: flex-end; gap: 6px;
            position: relative; z-index: 1;
        }
        .hero-muni-list {
            display: flex; align-items: center;
            gap: 0; flex-wrap: wrap; justify-content: flex-end;
        }
        .hero-muni-tag { font-size: 0.9rem; font-weight: 700; color: rgba(255,255,255,0.9); }
        .hero-muni-sep { color: rgba(255,255,255,0.35); margin: 0 10px; }
        .hero-province { font-size: 0.72rem; color: rgba(255,255,255,0.45); font-weight: 500; text-align: right; }
        .hero-church-svg { opacity: 0.22; flex-shrink: 0; margin-top: 8px; }
        @media (max-width: 768px) {
            .analysis-hero { padding: 40px 0 32px; }
            .hero-title { font-size: 1.8rem !important; }
            .hero-church-wrap { align-items: flex-start; margin-top: 20px; }
        }

        .rec-card {
            background: #fff;
            border: 1px solid #eaecf5;
            border-left: 3px solid var(--yellow);
            border-radius: 12px;
            padding: 16px 20px;
            height: 100%;
        }

        /* Chart containers */
        .chart-box { position: relative; height: 240px; }
        .chart-box.tall { height: 300px; }

        /* Viewing summary pill */
        .viewing-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #EEF2FF;
            border: 1px solid #c7d7f5;
            border-radius: 999px;
            padding: 5px 14px;
            font-size: .76rem;
            font-weight: 600;
            color: #2C3E8F;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 1.4rem; }
            .filter-group { padding: 8px 12px; border-right: none; border-bottom: 1px solid #f1f5f9; }
            .ds-control a { padding: 5px 9px; font-size:.72rem; }
            .stat-card-value { font-size: 1.5rem; }
            .hero-munis { align-items: flex-start; margin-top: 16px; }
            .hero-muni-list { justify-content: flex-start; }
            .growth-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <img src="{{ asset('images/logo_mswdo.jpg') }}" alt="MSWDO"
                    style="width:36px;height:36px;object-fit:contain;border-radius:4px;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                @auth
                    @if(Auth::user()->isSuperAdmin())
                        {{-- Super Admin nav --}}
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data
                                    Management</a></li>
                            <li class="nav-item"><a class="nav-link active" href="/analysis">Public View</a></li>
                        </ul>
                        <div class="d-flex">
                            <div class="user-info">
                                <span>{{ Auth::user()->full_name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                                    <button type="submit" class="logout-btn">Logout</button>
                                </form>
                            </div>
                        </div>
                    @elseif(Auth::user()->isAdmin())
                        {{-- Admin nav --}}
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data
                                    Management</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                            </li>
                            <li class="nav-item"><a class="nav-link active" href="/analysis">Public View</a></li>
                        </ul>
                        <div class="d-flex">
                            <div class="user-info">
                                <span>{{ Auth::user()->full_name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                                    <button type="submit" class="logout-btn">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Logged-in user nav --}}
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item"><a class="nav-link" href="/analysis">Programs</a></li>
                            <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
                            <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                        </ul>
                        <div class="d-flex">
                            <div class="user-info">
                                <span>{{ Auth::user()->full_name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                                    <button type="submit" class="logout-btn">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @else
                    {{-- Guest nav --}}
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="/analysis">Programs</a></li>
                        <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
                        <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                    </ul>
                    <div class="d-flex">
                        <a href="{{ route('login') }}" class="btn-login me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn-register">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="analysis-hero">
        <div class="container" style="position:relative;z-index:1;">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-4">
                <div>
                    <div class="hero-badge">Statistical Analysis Dashboard</div>
                    <h1 class="hero-title">Comparative Socioeconomic Analysis</h1>
                    <div class="hero-divider"></div>
                    <p class="hero-sub">
                        Comparative statistical analysis of municipalities in Laguna Province
                        covering population, households, and social welfare programs.
                        Supporting MSWDO planning, assessment, and decision-making.
                    </p>
                </div>
                <div class="hero-church-wrap">
                    <div class="hero-muni-list">
                        @foreach($coreNames as $i => $mn)
                            @if($i > 0)<span class="hero-muni-sep">&bull;</span>@endif
                            <span class="hero-muni-tag">{{ $mn }}</span>
                        @endforeach
                    </div>
                    <span class="hero-province">Laguna Province</span>
                    <svg class="hero-church-svg" width="68" height="80" viewBox="0 0 72 84" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="35" y="0" width="2.5" height="8" fill="white"/>
                        <rect x="31.5" y="3" width="9" height="2" fill="white"/>
                        <polygon points="36,8 42,20 30,20" fill="white"/>
                        <rect x="28" y="20" width="16" height="5" fill="white"/>
                        <path d="M30,25 L30,36 Q30,40 36,40 Q42,40 42,36 L42,25 Z" fill="white"/>
                        <ellipse cx="36" cy="33" rx="4" ry="5" fill="rgba(13,27,62,0.4)"/>
                        <rect x="26" y="40" width="20" height="3" fill="white"/>
                        <circle cx="36" cy="48" r="4" fill="white"/>
                        <circle cx="36" cy="48" r="2.5" fill="rgba(13,27,62,0.35)"/>
                        <rect x="23" y="43" width="26" height="26" fill="white"/>
                        <path d="M28,51 L28,60 Q28,63 31,63 Q34,63 34,60 L34,51 Z" fill="rgba(13,27,62,0.28)"/>
                        <path d="M38,51 L38,60 Q38,63 41,63 Q44,63 44,60 L44,51 Z" fill="rgba(13,27,62,0.28)"/>
                        <path d="M32,69 L32,79 Q32,82 36,82 Q40,82 40,79 L40,69 Z" fill="rgba(13,27,62,0.28)"/>
                        <rect x="18" y="67" width="36" height="3" rx="1" fill="white"/>
                        <rect x="14" y="70" width="44" height="3" rx="1" fill="white"/>
                        <rect x="10" y="73" width="52" height="3" rx="1" fill="white"/>
                        <rect x="8" y="55" width="15" height="18" fill="white"/>
                        <rect x="49" y="55" width="15" height="18" fill="white"/>
                        <path d="M11,59 L11,67 Q11,70 15.5,70 Q20,70 20,67 L20,59 Z" fill="rgba(13,27,62,0.22)"/>
                        <path d="M52,59 L52,67 Q52,70 56.5,70 Q61,70 61,67 L61,59 Z" fill="rgba(13,27,62,0.22)"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- FILTER STRIP ─────────────────────────────────────────── --}}
    <div class="filter-strip">
        <div class="container">
            <div class="filter-strip-inner">
                <div class="filter-row filter-row-top">
                    <div class="filter-group">
                        <span class="filter-group-label">1. Dataset</span>
                        <div class="ds-control" role="group" aria-label="Select dataset">
                            <a href="?category=demography" class="{{ $selectedCategory === 'demography' ? 'ds-active-demo' : '' }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Population &amp; Households
                            </a>
                            <a href="?category=programs" class="{{ $selectedCategory === 'programs' ? 'ds-active-prog' : '' }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Social Welfare Programs
                            </a>
                            <a href="?category=all" class="{{ $selectedCategory === 'all' ? 'ds-active-all' : '' }}">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                                All Data
                            </a>
                        </div>
                    </div>
                    <div class="filter-group filter-viewing-group">
                        <span class="filter-group-label">Viewing:</span>
                        <span class="viewing-pill">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M2 12s3.27-7 10-7 10 7 10 7-3.27 7-10 7-10-7-10-7z"/></svg>
                            @if($selectedCategory === 'demography')Population &amp; Households
                            @elseif($selectedCategory === 'programs')Social Welfare Programs
                            @else All Data
                            @endif
                            &middot; {{ $selectedYear }}
                            &middot; @foreach($coreNames as $i => $mn)@if($i > 0), @endif{{ $mn }}@endforeach
                        </span>
                    </div>
                </div>
                <div class="filter-row filter-row-bottom">
                    <div class="filter-group">
                        <span class="filter-group-label">2. Year</span>
                        <div class="year-pills-wrap">
                            @forelse($categoryYears as $yr)
                                <a href="?category={{ $selectedCategory }}&year={{ $yr }}"
                                   class="yr-pill {{ $yr == $selectedYear ? 'active' : '' }}">{{ $yr }}</a>
                            @empty
                                <span style="font-size:.78rem;color:#94a3b8;font-style:italic;">No data</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- NAV PILLS ────────────────────────────────────────────────── --}}
    <div class="nav-pills-bar">
        <div class="container">
            <div class="nav-pills-inner">
                <a href="#descriptive-analysis" class="nav-pill-link jump-link">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Descriptive Analysis
                </a>
                @if($selectedCategory === 'demography' || $selectedCategory === 'all')
                    <a href="#population-growth-analysis" class="nav-pill-link jump-link">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        Population Growth
                    </a>
                    <a href="#household-vs-population-analysis" class="nav-pill-link jump-link">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                        Household vs Population
                    </a>
                @endif
                @if($selectedCategory === 'programs' || $selectedCategory === 'all')
                    <a href="#program-beneficiaries-analysis" class="nav-pill-link jump-link">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        Program Beneficiaries
                    </a>
                    <a href="#program-report-analysis" class="nav-pill-link jump-link">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Report &amp; Download
                    </a>
                @endif
                <a href="#key-insights-analysis" class="nav-pill-link jump-link">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Key Insights
                </a>
            </div>
        </div>
    </div>

    @php
        /** @var string[] $coreNames */
        /** @var array<string,string> $colors */
        /** @var int[] $allYears */
        /** @var int $selectedYear */
        /** @var array<string,array<string,int|float>> $snapshot */
        /** @var array<string,array<int,int>> $populationTrend */
        /** @var array<string,array<int,int>> $householdsTrend */
        /** @var array<string,array<int,int>> $maleTrend */
        /** @var array<string,array<int,int>> $femaleTrend */
        /** @var array<string,array<int,int>> $benefTrend */
        /** @var array<string,array<int,float|null>> $growthRates */
        /** @var array|null $anovaPopResult */
        /** @var array|null $anovaBenefResult */
        /** @var array $correlations */
        /** @var array $insights */
        /** @var string|null $fastest */
        /** @var string $domAge */
        /** @var string|null $topProgram */
        /** @var array $progTotals */
        /** @var array $programTypes */
        /** @var array $programLabels */
        $muniColors = $colors; // dynamic set in AnalysisController from DB
        // Sum of KNOWN (non-null) values only. Returns null when nothing is known,
        // so missing data is never shown as 0 and an actual total of 0 stays 0.
        $sumKnown = function (array $values) {
            $known = array_filter($values, fn($v) => $v !== null);
            return count($known) > 0 ? array_sum($known) : null;
        };
        $totalPop = $sumKnown(array_map(fn($n) => $snapshot[$n]['population'] ?? null, $coreNames));
        $totalHH = $sumKnown(array_map(fn($n) => $snapshot[$n]['households'] ?? null, $coreNames));
        $totalBenef = $sumKnown(array_map(fn($n) => $snapshot[$n]['beneficiaries'] ?? null, $coreNames));
        // Known values only (municipalities with missing data are left out, never counted as 0)
        $popArr = [];
        $benArr = [];
        $hhArr = [];
        foreach ($coreNames as $n) {
            if (($snapshot[$n]['population'] ?? null) !== null) {
                $popArr[$n] = (int) $snapshot[$n]['population'];
            }
            if (($snapshot[$n]['beneficiaries'] ?? null) !== null) {
                $benArr[$n] = (int) $snapshot[$n]['beneficiaries'];
            }
            if (($snapshot[$n]['households'] ?? null) !== null) {
                $hhArr[$n] = (int) $snapshot[$n]['households'];
            }
        }
        arsort($popArr);
        arsort($benArr);
        arsort($hhArr);
        $highPop = array_key_first($popArr) ?? ($highestPop ?? '');
        $highBen = array_key_first($benArr) ?? ($highestBenef ?? '');
        $highHH = array_key_first($hhArr) ?? '';
        // Beneficiary rates that actually exist (null = not calculable)
        $benefPcts = array_filter(array_map(fn($n) => $snapshot[$n]['benef_pct'] ?? null, $coreNames), fn($v) => $v !== null);
        // Average yearly growth per municipality, taken from the controller's calculated $growthRates.
        // null = no valid growth data (displayed as N/A, never 0%).
        $avgGrowthByMuni = [];
        foreach ($coreNames as $n) {
            $validRates = array_filter($growthRates[$n] ?? [], fn($v) => $v !== null);
            $avgGrowthByMuni[$n] = count($validRates) > 0 ? round(array_sum($validRates) / count($validRates), 2) : null;
        }
        $fastestGrowth = ($fastest ?? null) !== null ? ($avgGrowthByMuni[$fastest] ?? null) : null;
    @endphp

    {{-- SECTION 1: DESCRIPTIVE ANALYSIS --}}
    <section class="section-wrap" id="descriptive-analysis" style="scroll-margin-top:110px;">
        <div class="container">
            <h2 class="sec-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Descriptive Analysis</h2>
            <div class="row g-3 mb-4">
                @if($selectedCategory === 'demography' || $selectedCategory === 'all')
                <div class="col-md-6 col-lg-4">
                    <div class="stat-card">
                        <div class="stat-card-icon blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2C3E8F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-card-label">Total Population ({{ $selectedYear }})</div>
                            <div class="stat-card-value">{{ $totalPop !== null ? number_format($totalPop) : 'N/A' }}</div>
                            <div class="stat-card-sub {{ ($totalPop !== null && $totalPop > 0) ? 'stat-card-change up' : 'stat-card-change na' }}">
                                @if($totalPop === null)No population data for {{ $selectedYear }}@elseif($totalPop > 0)<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg> Highest: {{ $highPop }}@else Recorded population is 0 for {{ $selectedYear }}@endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="stat-card">
                        <div class="stat-card-icon" style="background:#EEF7FF;width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2C3E8F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-card-label">Total Households ({{ $selectedYear }})</div>
                            <div class="stat-card-value">{{ $totalHH !== null ? number_format($totalHH) : 'N/A' }}</div>
                            <div class="stat-card-sub {{ ($totalHH !== null && $totalHH > 0) ? 'stat-card-change up' : 'stat-card-change na' }}">
                                @if($totalHH === null)No household data for {{ $selectedYear }}@elseif($totalHH > 0)<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg> Highest: {{ $highHH }}@else Recorded households total is 0 for {{ $selectedYear }}@endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if($selectedCategory === 'programs' || $selectedCategory === 'all')
                <div class="col-md-6 col-lg-4">
                    <div class="stat-card">
                        <div class="stat-card-icon green">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </div>
                        <div class="stat-card-body">
                            <div class="stat-card-label">Total Beneficiaries ({{ $selectedYear }})</div>
                            <div class="stat-card-value">{{ $totalBenef !== null ? number_format($totalBenef) : 'N/A' }}</div>
                            <div class="stat-card-sub {{ ($totalBenef !== null && $totalBenef > 0) ? 'stat-card-change up' : 'stat-card-change na' }}">
                                @if($totalBenef === null)No program data for {{ $selectedYear }}@elseif($totalBenef > 0)<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg> Highest: {{ $highBen }}@else Recorded beneficiaries total is 0 for {{ $selectedYear }}@endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-base">
                        <h6 style="font-weight:700;color:var(--blue);">Population, Households & Beneficiaries per
                            Municipality</h6>
                        <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Grouped bar chart 
                            {{ $selectedYear }}</p>
                        <div class="chart-box"><canvas id="descBar"></canvas></div>
                    </div>
                </div>
                @if($selectedCategory === 'demography' || $selectedCategory === 'all')
                <div class="col-lg-4">
                    <div class="card-base h-100">
                        <h6 style="font-weight:700;color:var(--blue);">Key Differences</h6>
                        <div class="table-responsive mt-2">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Municipality</th>
                                        <th class="text-end">Population</th>
                                        <th class="text-end">Benef. %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($coreNames as $n)
                                        <tr>
                                            <td><span
                                                    style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}
                                            </td>
                                            <td class="text-end fw-bold">
                                                @if(($snapshot[$n]['population'] ?? null) !== null)
                                                    {{ number_format($snapshot[$n]['population']) }}
                                                @else
                                                    <span style="color:#94a3b8;font-weight:500;">N/A</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if(($snapshot[$n]['benef_pct'] ?? null) !== null)
                                                    <span style="color:var(--blue);font-weight:600;">{{ $snapshot[$n]['benef_pct'] }}%</span>
                                                @else
                                                    <span style="color:#94a3b8;font-weight:500;">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9;">
                            <p style="font-size:.8rem;color:#64748b;margin:0;">
                                @if($totalPop === null)
                                    Official population census records are not available for {{ $selectedYear }}.
                                @elseif($totalPop > 0)
                                    <strong>{{ $highPop }}</strong> leads in population.
                                @else
                                    Recorded population is 0 for {{ $selectedYear }}.
                                @endif
                                @if($totalBenef === null)
                                    Program beneficiary records are not collected for {{ $selectedYear }}.
                                @elseif($totalBenef > 0)
                                    <strong>{{ $highBen }}</strong> has the most welfare beneficiaries.
                                    @if($totalPop !== null && count($benefPcts) > 0)
                                        Beneficiary rates range from
                                        {{ min($benefPcts) }}% to
                                        {{ max($benefPcts) }}%.
                                    @endif
                                @else
                                    Recorded beneficiaries total is 0 for {{ $selectedYear }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if($selectedCategory === 'demography' || $selectedCategory === 'all')
    {{-- SECTION 2: POPULATION GROWTH --}}
    <section class="section-wrap alt" id="population-growth-analysis" style="scroll-margin-top:110px;">
        <div class="container">
            <h2 class="sec-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> Population Growth Analysis</h2>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-base">
                        <h6 style="font-weight:700;color:var(--blue);">Population per Year per Municipality</h6>
                        <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Line chart  all recorded years</p>
                        <div class="chart-box tall"><canvas id="popTrend"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-base h-100">
                        <h6 style="font-weight:700;color:var(--blue);">Growth Rates</h6>
                        @foreach($coreNames as $n)
                            @php
                                $avg = $avgGrowthByMuni[$n] ?? null;
                            @endphp
                            <div style="margin-bottom:18px;">
                                <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                    <span style="font-weight:600;font-size:.88rem;color:#1e293b;">
                                        <span
                                            style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}
                                    </span>
                                    @if($avg !== null)
                                        <span
                                            style="font-weight:700;color:{{ $avg >= 0 ? '#16a34a' : '#dc2626' }};font-size:.9rem;">{{ $avg >= 0 ? '+' : '' }}{{ $avg }}%</span>
                                    @else
                                        <span style="font-weight:700;color:#94a3b8;font-size:.9rem;">N/A</span>
                                    @endif
                                </div>
                                <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                    <div
                                        style="height:100%;background:{{ $muniColors[$n] }};border-radius:20px;width:{{ $avg !== null ? min(abs($avg) / 5 * 100, 100) : 0 }}%;">
                                    </div>
                                </div>
                                <div style="font-size:.75rem;color:#94a3b8;margin-top:3px;">Avg. yearly growth</div>
                            </div>
                        @endforeach
                        <p
                            style="font-size:.8rem;color:#64748b;margin:0;padding-top:10px;border-top:1px solid #f1f5f9;">
                            @if(($fastest ?? null) !== null)
                                <strong>{{ $fastest }}</strong> shows the fastest average population growth among the
                                municipalities.
                            @else
                                No valid population growth data is available for comparison.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- POPULATION GROWTH KEY FINDING (from calculated growth data) --}}
    <section class="section-wrap" style="padding-top:0;padding-bottom:40px;">
        <div class="container">
            <div class="card-base" style="border-top:4px solid #FDB913;padding:0;overflow:hidden;">

                {{-- KEY FINDING --}}
                <div style="background:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);padding:22px 28px 18px;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:#FDB913;flex-shrink:0;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1A2A5C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        </span>
                        <span style="font-size:.68rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:#FDB913;">Key Finding</span>
                    </div>
                    <p style="color:rgba(255,255,255,.93);font-size:.93rem;line-height:1.7;margin:0;">
                        @if(($fastest ?? null) !== null && $fastestGrowth !== null)
                            <strong style="color:#FDB913;">{{ $fastest }}</strong> shows the highest average yearly population growth rate at
                            <strong style="color:#FDB913;">{{ $fastestGrowth >= 0 ? '+' : '' }}{{ $fastestGrowth }}%</strong>, based on the available population records.
                        @else
                            <strong style="color:#FDB913;">N/A</strong> &mdash; no valid population growth data is available.
                        @endif
                    </p>
                </div>

                <div style="padding:28px;display:grid;grid-template-columns:1fr 1fr;gap:28px;" class="growth-detail-grid">

                    {{-- LEFT COL: EXPLANATION --}}
                    <div>
                        <div style="display:flex;align-items:center;margin-bottom:16px;">
                            <span style="display:inline-block;width:4px;height:20px;background:#FDB913;border-radius:2px;margin-right:10px;flex-shrink:0;"></span>
                            <span style="font-weight:800;font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;color:#2C3E8F;">Explanation of Growth Trend</span>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:14px;">
                            <div style="display:flex;gap:14px;align-items:flex-start;background:#F0F5FF;border-radius:12px;padding:14px 16px;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;background:#2C3E8F;flex-shrink:0;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </span>
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:#1e293b;margin-bottom:3px;">Young Population Structure</div>
                                    <div style="font-size:.81rem;color:#64748b;line-height:1.6;">Higher birth rates due to lower median age contribute to sustained natural population increase.</div>
                                </div>
                            </div>

                            <div style="display:flex;gap:14px;align-items:flex-start;background:#F0F5FF;border-radius:12px;padding:14px 16px;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;background:#2C3E8F;flex-shrink:0;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                </span>
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:#1e293b;margin-bottom:3px;">Sustained Growth Trend</div>
                                    <div style="font-size:.81rem;color:#64748b;line-height:1.6;">Continuous increase based on census data reflects a long-term upward demographic trajectory.</div>
                                </div>
                            </div>

                            <div style="display:flex;gap:14px;align-items:flex-start;background:#F0F5FF;border-radius:12px;padding:14px 16px;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;background:#2C3E8F;flex-shrink:0;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </span>
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:#1e293b;margin-bottom:3px;">Resource Availability & Settlement Expansion</div>
                                    <div style="font-size:.81rem;color:#64748b;line-height:1.6;">Access to water and land in the Santa Cruz watershed supports population growth, leading to expansion of settlements and built-up areas.</div>
                                </div>
                            </div>

                            <div style="display:flex;gap:14px;align-items:flex-start;background:#F0F5FF;border-radius:12px;padding:14px 16px;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:10px;background:#2C3E8F;flex-shrink:0;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                </span>
                                <div>
                                    <div style="font-weight:700;font-size:.85rem;color:#1e293b;margin-bottom:3px;">Increasing Human Activities & Environmental Interaction</div>
                                    <div style="font-size:.81rem;color:#64748b;line-height:1.6;">Rising population leads to increased land use, water consumption, and environmental changes, reflecting strong interaction between people and natural resources.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COL: EVIDENCE + CONCLUSION --}}
                    <div style="display:flex;flex-direction:column;gap:20px;">

                        {{-- Supporting Evidence --}}
                        <div>
                            <div style="display:flex;align-items:center;margin-bottom:14px;">
                                <span style="display:inline-block;width:4px;height:20px;background:#2C3E8F;border-radius:2px;margin-right:10px;flex-shrink:0;"></span>
                                <span style="font-weight:800;font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;color:#2C3E8F;">Supporting Evidence</span>
                            </div>
                            <div style="display:flex;flex-direction:column;gap:10px;">
                                @php    
                                $growthMuniSlug = ($fastest ?? null) ? strtolower(str_replace(' ', '-', $fastest)) : '';
                                $growthRefs = [
                                    [
                                        'authors' => 'PhilAtlas (2020)',
                                        'url' => ($fastest ?? null) ? 'https://www.philatlas.com/luzon/r04a/laguna/' . $growthMuniSlug . '.html' : 'https://www.philatlas.com/luzon/r04a/laguna.html',
                                        'label' => (($fastest ?? null) ?: 'Municipal') . ', Laguna Population Data'
                                    ],
                                    [
                                        'authors' => 'Magpantay & Sanchez (2023)',
                                        'url' => 'https://journals.uplb.edu.ph/index.php/JESAM/article/download/1030/853',
                                        'label' => 'JESAM Environmental & Socio-demographic Study'
                                    ],
                                    [
                                        'authors' => 'Sandoval et al. (2023)',
                                        'url' => 'https://www.researchgate.net/profile/Ryan-Labana/publication/371812110_Water_Quality_Assessment_of_Santa_Cruz_River_in_2011_and_2022_in_the_Vicinity_of_Liliw_and_Nagcarlan_Laguna_Philippines/links/669b155b02e9686cd11091b5/Water-Quality-Assessment-of-Santa-Cruz-River-in-2011-and-2022-in-the-Vicinity-of-Liliw-and-Nagcarlan-Laguna-Philippines.pdf',
                                        'label' => 'Water Quality Assessment Santa Cruz River, Laguna'
                                    ],
                                ];
                                @endphp
                                @foreach($growthRefs as $idx => $ref)
                                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:11px 14px;display:flex;gap:12px;align-items:flex-start;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:6px;background:#2C3E8F;color:#fff;font-size:.65rem;font-weight:800;flex-shrink:0;margin-top:1px;">{{ $idx + 1 }}</span>
                                    <div>
                                        <div style="font-weight:700;font-size:.8rem;color:#1e293b;margin-bottom:2px;">{{ $ref['authors'] }}</div>
                                        <div style="font-size:.75rem;color:#64748b;margin-bottom:4px;">{{ $ref['label'] }}</div>
                                        <a href="{{ $ref['url'] }}" target="_blank" rel="noopener noreferrer"
                                           style="font-size:.72rem;color:#2C3E8F;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                            View Source
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Conclusion --}}
                        <div style="background:linear-gradient(135deg,#F0F5FF 0%,#E5EEFF 100%);border:1px solid #c7d7f5;border-radius:14px;padding:18px 20px;">
                            <div style="display:flex;align-items:center;margin-bottom:12px;">
                                <span style="display:inline-block;width:4px;height:20px;background:#FDB913;border-radius:2px;margin-right:10px;flex-shrink:0;"></span>
                                <span style="font-weight:800;font-size:.85rem;text-transform:uppercase;letter-spacing:.08em;color:#2C3E8F;">Conclusion</span>
                            </div>
                            <p style="font-size:.83rem;color:#334155;line-height:1.75;margin:0;">
                                The rapid population increase in {{ ($fastest ?? null) ?: 'the leading municipality' }} is driven by a combination of <strong>demographic factors</strong> and
                                <strong>environmental-resource dynamics</strong>. The availability of water and land supports continuous settlement expansion,
                                while increasing human activities further accelerate growth.
                            </p>
                            <p style="font-size:.83rem;color:#334155;line-height:1.75;margin:12px 0 0;">
                                However, this growth also places pressure on natural resources, particularly <strong>water systems and land use</strong>.
                                As population increases, environmental impacts such as changes in land cover and water quality become more evident.
                                Therefore, <strong>sustainable resource management</strong> is essential to balance population growth with environmental protection.
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media (max-width: 768px) {
            .growth-detail-grid { grid-template-columns: 1fr !important; }
            .gender-detail-grid { grid-template-columns: 1fr !important; }
            .age-detail-grid { grid-template-columns: 1fr !important; }
        }
    </style>

    {{-- SECTION 3: HOUSEHOLD VS POPULATION --}}
    <section class="section-wrap" id="household-vs-population-analysis" style="scroll-margin-top:110px;">
        <div class="container">
            <h2 class="sec-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> Household vs Population Analysis</h2>
            @if($totalPop === null && $totalHH === null)
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #2C3E8F;border-radius:10px;padding:14px 18px;margin-bottom:24px;">
                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;margin-bottom:2px;">
                        No population or household data available for {{ $selectedYear }}
                    </div>
                    <div style="font-size:.8rem;color:#64748b;">
                        Census population and household records are available for: {{ implode(', ', $summaryYears) ?: 'N/A' }}. Select one of those years to view comparative population and household statistics.
                    </div>
                </div>
            @endif
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-base">
                        <h6 style="font-weight:700;color:var(--blue);">Population vs Households Combo Chart</h6>
                        <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Bars = Population, Line =
                            Households  {{ $selectedYear }}</p>
                        <div class="chart-box tall"><canvas id="hhCombo"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-base h-100">
                        <h6 style="font-weight:700;color:var(--blue);">Average Household Size</h6>
                        @foreach($coreNames as $n)
                            <div style="margin-bottom:20px;">
                                <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                    <span style="font-weight:600;font-size:.88rem;">
                                        <span
                                            style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}
                                    </span>
                                    @if(($snapshot[$n]['avg_hh_size'] ?? null) !== null)
                                        <span
                                            style="font-weight:800;color:{{ $muniColors[$n] }};">{{ $snapshot[$n]['avg_hh_size'] }}
                                            <small style="color:#94a3b8;font-weight:500;">persons/hh</small></span>
                                    @else
                                        <span style="font-weight:700;color:#94a3b8;">N/A</span>
                                    @endif
                                </div>
                                <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                    <div
                                        style="height:100%;border-radius:20px;background:{{ $muniColors[$n] }};width:{{ ($snapshot[$n]['avg_hh_size'] ?? null) !== null ? min($snapshot[$n]['avg_hh_size'] / 10 * 100, 100) : 0 }}%;">
                                    </div>
                                </div>
                                <div style="font-size:.75rem;color:#94a3b8;margin-top:3px;">
                                    @if(($snapshot[$n]['households'] ?? null) !== null)
                                        {{ number_format($snapshot[$n]['households']) }} households &middot;
                                        {{ ($snapshot[$n]['population'] ?? null) !== null ? number_format($snapshot[$n]['population']) : 'N/A' }} pop.
                                    @else
                                        No household data for {{ $selectedYear }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endif {{-- end demography sections --}}

    @if($selectedCategory === 'programs' || $selectedCategory === 'all')
    {{-- SECTION 4: PROGRAM BENEFICIARIES --}}
    <section class="section-wrap alt" id="program-beneficiaries-analysis" style="scroll-margin-top:110px;">
        <div class="container">
            <h2 class="sec-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Program Beneficiaries Analysis</h2>
            @php
                $anyProgramDataThisYear = $totalBenef !== null;
            @endphp
            @if(!$anyProgramDataThisYear)
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #2C3E8F;border-radius:10px;padding:14px 18px;margin-bottom:24px;">
                    <div style="font-weight:700;font-size:.88rem;color:#1e293b;margin-bottom:2px;">
                        No program beneficiary data available for {{ $selectedYear }}
                    </div>
                    <div style="font-size:.8rem;color:#64748b;">
                        @if(!empty($programYears))
                        Program beneficiary records cover: <strong>{{ min($programYears) }}{{ count($programYears) > 1 ? chr(8211) . max($programYears) : '' }}</strong>.
                    @else
                        No program beneficiary records are available yet.
                    @endif
                    </div>
                </div>
            @endif
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card-base text-center">
                        @php $hasTopProgram = $topProgram !== null && isset($progTotals[$topProgram]); @endphp
                        <div class="stat-num" style="font-size:1.5rem;{{ $hasTopProgram ? '' : 'color:#94a3b8;' }}">{{ $hasTopProgram ? $topProgram : 'N/A' }}</div>
                        <div class="stat-lbl">Highest Demand Program</div>
                        <div style="font-size:.78rem;color:{{ $hasTopProgram ? '#22c55e' : '#94a3b8' }};margin-top:4px;">
                            @if($hasTopProgram)
                                {{ number_format($progTotals[$topProgram]) }} total beneficiaries
                            @elseif(!empty($progTotals))
                                All recorded program totals are 0 for {{ $selectedYear }}
                            @else
                                No program data for {{ $selectedYear }}
                            @endif
                        </div>
                    </div>
                </div>
                @foreach($coreNames as $n)
                    <div class="col-md-{{ count($coreNames) == 3 ? '2-2' : '4' }}" style="flex:1;">
                        <div class="card-base card-plain text-center" style="border-top:4px solid #2C3E8F;">
                            <div style="font-weight:700;color:var(--blue);margin-bottom:8px;">{{ $n }}</div>
                            @if(($snapshot[$n]['beneficiaries'] ?? null) !== null)
                                <div style="font-size:1.4rem;font-weight:800;color:var(--blue);">
                                    {{ number_format($snapshot[$n]['beneficiaries']) }}</div>
                                <div class="stat-lbl">Total Beneficiaries</div>
                            @else
                                <div style="font-size:1.4rem;font-weight:700;color:#94a3b8;">N/A</div>
                                <div class="stat-lbl">No data for {{ $selectedYear }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card-base">
                        <h6 style="font-weight:700;color:var(--blue);">Programs per Municipality (Stacked)</h6>
                        <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">{{ !empty($programLabels) ? implode(', ', $programLabels) : 'No program types recorded' }}
                             {{ $selectedYear }}</p>
                        <div class="chart-box tall"><canvas id="benefStacked"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card-base d-flex flex-column">
                        <h6 style="font-weight:700;color:var(--blue);">Beneficiaries Trend (Yearly)</h6>
                        <p style="color:#94a3b8;font-size:.8rem;margin-bottom:12px;">Total beneficiaries over all years</p>
                        <div class="chart-box" style="height:265px;"><canvas id="benefTrend"></canvas></div>
                        <div style="margin-top:auto;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;align-items:center;gap:6px;font-size:.74rem;color:#94a3b8;line-height:1.4;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:3px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>Note: Only recorded beneficiary data are shown. Missing years are treated as N/A, not zero.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-hover mb-0" style="background:#f8fafc;border-radius:12px;overflow:hidden;">
                    <thead>
                        <tr>
                            <th>Program</th>
                            @foreach($coreNames as $n)<th class="text-center">{{ $n }}</th>@endforeach
                            <th class="text-center">Total</th>
                            <th>Highest</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programTypes as $type)
                            @php
                                $vals = array_map(fn($n) => $snapshot[$n]['programs'][$type] ?? null, $coreNames);
                                $knownVals = array_filter($vals, fn($v) => $v !== null);
                                $tot6 = count($knownVals) > 0 ? array_sum($knownVals) : null;
                                $maxV = count($knownVals) > 0 ? max($knownVals) : null;
                                $maxIdx = ($maxV !== null && $maxV > 0) ? array_search($maxV, $knownVals, true) : null;
                            @endphp
                            <tr>
                                <td><strong>{{ $programLabels[$type] ?? $type }}</strong></td>
                                @foreach($vals as $i => $v)
                                    <td class="text-center"
                                        style="{{ ($v !== null && $maxV > 0 && $v == $maxV) ? 'font-weight:700;color:var(--blue);' : '' }}">
                                        @if($v !== null){{ number_format($v) }}@else<span style="color:#94a3b8;font-weight:500;">N/A</span>@endif</td>
                                @endforeach
                                <td class="text-center fw-bold">@if($tot6 !== null){{ number_format($tot6) }}@else<span style="color:#94a3b8;font-weight:500;">N/A</span>@endif</td>
                                <td>
                                    @if($maxIdx !== null)
                                        <span
                                            style="background:var(--blue-lt);color:var(--blue);border-radius:10px;padding:2px 10px;font-size:.78rem;font-weight:700;">{{ $coreNames[$maxIdx] }}</span>
                                    @else
                                        <span style="color:#94a3b8;font-weight:500;">{!! $tot6 === null ? 'N/A' : '&mdash;' !!}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($coreNames) + 3 }}" class="text-center" style="color:#94a3b8;">No program types found in the data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    @endif {{-- end programs section --}}

    @if($selectedCategory === 'programs' || $selectedCategory === 'all')
    {{-- SECTION: ANALYSIS REPORT, MODAL VIEW & DOWNLOADS --}}
    <style>
        .rep-meta { display: grid; grid-template-columns: max-content 1fr; gap: 6px 18px; margin: 0; font-size: .88rem; }
        .rep-meta dt { font-weight: 700; color: #1e293b; }
        .rep-meta dd { margin: 0; color: #334155; }
        .rep-list { margin: 8px 0 0; padding-left: 20px; font-size: .86rem; color: #334155; line-height: 1.6; }
        .rep-list li { margin-bottom: 6px; }
        .rep-badge { display: inline-block; border: 1px solid #cbd5e1; background: #f1f5f9; color: #334155; border-radius: 999px; padding: 3px 12px; font-weight: 700; font-size: .78rem; margin: 0 8px 8px 0; }
        .rep-sub { font-weight: 700; font-size: .86rem; color: var(--blue); margin: 16px 0 0; }
        .rep-details summary { cursor: pointer; font-weight: 700; font-size: .86rem; color: var(--blue); margin-top: 14px; }

        /* Action card: overflow must be visible so the format menu is not clipped by .card-base */
        .card-base.rep-actions { overflow: visible; }
        .rep-actions::before { border-radius: 14px 14px 0 0; }
        .rep-actions-title { font-weight: 800; font-size: 1rem; color: var(--blue); margin: 0 0 4px; }
        .rep-actions-sub { font-size: .8rem; color: #64748b; line-height: 1.5; margin: 0 0 16px; }

        .rep-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 12px 20px; border-radius: 12px; border: 2px solid transparent; font-weight: 800; font-size: .92rem; line-height: 1.2; text-decoration: none; cursor: pointer; transition: transform .2s, box-shadow .2s, background .2s, color .2s; }
        .rep-btn-primary { background: var(--grad); color: #fff; box-shadow: 0 8px 22px rgba(44, 62, 143, .28); }
        .rep-btn-primary:hover { color: #fff; transform: translateY(-2px); box-shadow: 0 12px 28px rgba(44, 62, 143, .36); }
        .rep-btn-outline { background: #fff; color: var(--blue); border-color: var(--blue); }
        .rep-btn-outline:hover { background: var(--blue-lt); color: var(--blue); transform: translateY(-2px); }
        .rep-btn-ghost { background: #fff; color: #334155; border-color: #cbd5e1; padding: 10px 16px; }
        .rep-btn-ghost:hover { background: #f1f5f9; color: #1e293b; }
        .rep-btn:focus-visible { outline: 3px solid var(--yellow); outline-offset: 3px; }
        .rep-btn .rep-caret { margin-left: auto; transition: transform .2s; }
        .rep-btn[aria-expanded="true"] .rep-caret { transform: rotate(180deg); }

        .rep-menu { min-width: 100%; width: 330px; max-width: 92vw; padding: 8px; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 16px 40px rgba(26, 42, 92, .18); }
        .rep-menu-head { font-size: .7rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #94a3b8; padding: 6px 10px 8px; }
        .rep-menu .dropdown-item { display: flex; align-items: flex-start; gap: 12px; padding: 10px; border-radius: 10px; white-space: normal; }
        .rep-menu .dropdown-item:hover, .rep-menu .dropdown-item:focus { background: var(--blue-lt); }
        .rep-fmt-icon { flex: 0 0 38px; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fff; }
        .rep-fmt-docx { background: #2B579A; }
        .rep-fmt-csv { background: #217346; }
        .rep-fmt-html { background: #C2410C; }
        .rep-fmt-name { display: block; font-weight: 700; font-size: .9rem; color: #1e293b; }
        .rep-fmt-ext { display: inline-block; margin-left: 6px; padding: 0 7px; border-radius: 6px; background: #f1f5f9; color: #475569; font-size: .68rem; font-weight: 800; letter-spacing: .04em; vertical-align: 1px; }
        .rep-fmt-desc { display: block; font-size: .76rem; color: #64748b; line-height: 1.4; margin-top: 2px; }

        /* Modal */
        body.rep-modal-open .modal-backdrop { z-index: 1000000; }
        body.rep-modal-open .admin-back-btn { display: none !important; }
        #reportModal { z-index: 1000001; }
        .rep-modal .modal-content { border: 0; border-radius: 18px; overflow: hidden; box-shadow: 0 30px 80px rgba(13, 27, 62, .35); }
        .rep-modal-head { background: var(--grad); color: #fff; border-bottom: 4px solid var(--yellow); align-items: center; gap: 14px; padding: 16px 22px; }
        .rep-modal-icon { flex: 0 0 44px; width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 255, 255, .15); display: flex; align-items: center; justify-content: center; }
        .rep-modal-head .modal-title { font-weight: 800; font-size: 1.1rem; margin: 0; }
        .rep-modal-sub { font-size: .8rem; opacity: .85; margin-top: 2px; }
        .rep-modal .modal-body { padding: 0; background: #f0f4f8; }
        .rep-frame { display: block; width: 100%; height: calc(100vh - 250px); min-height: 340px; border: 0; background: #f0f4f8; }
        .rep-modal-foot { background: #f8fafc; border-top: 1px solid #e2e8f0; gap: 10px; flex-wrap: wrap; padding: 12px 22px; }
        .rep-foot-note { margin-right: auto; font-size: .76rem; color: #64748b; }
        .rep-modal-foot .rep-btn { padding: 10px 18px; font-size: .86rem; }
        @media (max-width: 575.98px) {
            .rep-modal-foot .rep-btn, .rep-modal-foot .dropup { flex: 1 1 100%; }
            .rep-modal-foot .dropup .rep-btn { width: 100%; }
            .rep-foot-note { flex: 1 0 100%; }
            .rep-frame { height: calc(100vh - 330px); }
        }
        @media (prefers-reduced-motion: reduce) { .rep-btn, .rep-btn .rep-caret { transition: none; } }
    </style>
    @php
        $rep = $programsReport;
        $repMeta = $rep['meta'];
        $repSum = $rep['summary'];
        $repMcdm = $rep['mcdm'];
        $repFileIcon = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';
        $repFormats = [
            ['key' => 'docx', 'label' => 'Word document', 'ext' => '.docx', 'desc' => 'Editable report with tables, methodology and notes'],
            ['key' => 'csv', 'label' => 'CSV spreadsheet', 'ext' => '.csv', 'desc' => 'Raw tables for Excel or Google Sheets'],
            ['key' => 'html', 'label' => 'Web page', 'ext' => '.html', 'desc' => 'Formatted report that opens in any browser'],
        ];
        foreach ($repFormats as $fi => $fmt) {
            $repFormats[$fi]['url'] = request()->fullUrlWithQuery(['download' => 'report', 'format' => $fmt['key'], 'category' => $selectedCategory, 'year' => $selectedYear]);
        }
    @endphp
    <section class="section-wrap" id="program-report-analysis" style="scroll-margin-top:110px;">
        <div class="container">
            <h2 class="sec-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> Analysis Report &amp; Download</h2>
            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="card-base h-100">
                        <h6 style="font-weight:700;color:var(--blue);">{{ $repMeta['title'] }}</h6>
                        <dl class="rep-meta mt-3">
                            <dt>Reporting period</dt><dd>{{ $repMeta['period'] }}</dd>
                            <dt>Dataset</dt><dd>{{ $repMeta['category'] }}</dd>
                            <dt>Municipalities</dt><dd>{{ implode(', ', $repMeta['municipalities']) }}</dd>
                            <dt>Recorded beneficiaries</dt>
                            <dd>
                                @if($repSum['total_beneficiaries'] !== null)
                                    {{ number_format($repSum['total_beneficiaries']) }}
                                    ({{ $repSum['municipalities_with_data'] }} of {{ $repSum['municipalities_total'] }} municipalities have records)
                                @else
                                    N/A &mdash; no program records for {{ $selectedYear }}
                                @endif
                            </dd>
                        </dl>
                        <div class="rep-sub">The report contains</div>
                        <ul class="rep-list">
                            <li>Title, reporting period, dataset, municipalities and date generated</li>
                            <li>Data summary and tables: beneficiaries by program, municipality summary, yearly totals</li>
                            <li>Methodology, AHP and WSM status, and limitations / missing-data notes</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-base rep-actions">
                        <p class="rep-actions-title">Report for {{ $selectedYear }}</p>
                        <p class="rep-actions-sub">Read it here, or download a copy. Every format matches the selected dataset and year.</p>
                        <div class="d-grid gap-3">
                            <button type="button" class="rep-btn rep-btn-outline" data-bs-toggle="modal" data-bs-target="#reportModal"
                                aria-label="View the programs analysis report for {{ $selectedYear }}">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                View Report
                            </button>
                            <div class="dropdown">
                                <button type="button" class="rep-btn rep-btn-primary w-100" id="reportDownloadToggle" data-bs-toggle="dropdown" aria-expanded="false"
                                    aria-label="Download the programs analysis report for {{ $selectedYear }}. Choose a format: Word, CSV or HTML">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Download Report
                                    <svg class="rep-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <ul class="dropdown-menu rep-menu" aria-labelledby="reportDownloadToggle">
                                    <li class="rep-menu-head" aria-hidden="true">Choose a format</li>
                                    @foreach($repFormats as $fmt)
                                        <li>
                                            <a class="dropdown-item" href="{{ $fmt['url'] }}" rel="nofollow"
                                                aria-label="Download as {{ $fmt['label'] }} ({{ $fmt['ext'] }})">
                                                <span class="rep-fmt-icon rep-fmt-{{ $fmt['key'] }}">{!! $repFileIcon !!}</span>
                                                <span>
                                                    <span class="rep-fmt-name">{{ $fmt['label'] }}<span class="rep-fmt-ext">{{ $fmt['ext'] }}</span></span>
                                                    <span class="rep-fmt-desc">{{ $fmt['desc'] }}</span>
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card-base h-100">
                        <h6 style="font-weight:700;color:var(--blue);">Data Notes &amp; Limitations</h6>
                        <ul class="rep-list">
                            @foreach($rep['notes'] as $note)
                                <li>{{ $note }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-base h-100" style="border-top:4px solid #FDB913;">
                        <h6 style="font-weight:700;color:var(--blue);">AHP &amp; WSM Results</h6>
                        <div>
                            <span class="rep-badge">AHP: {{ $repMcdm['status'] }}</span>
                            <span class="rep-badge">WSM: {{ $repMcdm['status'] }}</span>
                        </div>
                        <p style="font-size:.86rem;color:#334155;line-height:1.6;margin:0;">{{ $repMcdm['reason'] }}</p>
                        <div class="rep-sub">Data available now</div>
                        <ul class="rep-list">
                            @foreach($repMcdm['available'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <div class="rep-sub">Needed before AHP and WSM can be calculated</div>
                        <ol class="rep-list">
                            @foreach($repMcdm['missing'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ol>
                        <details class="rep-details">
                            <summary>How the calculations will work once inputs are supplied</summary>
                            <div class="rep-sub">AHP</div>
                            <ol class="rep-list">
                                @foreach($repMcdm['ahp_steps'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                            <div class="rep-sub">WSM</div>
                            <ol class="rep-list">
                                @foreach($repMcdm['wsm_steps'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ol>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Report modal: the report HTML is the same document as the HTML download --}}
    <div class="modal fade rep-modal" id="reportModal" tabindex="-1" aria-labelledby="reportModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
            <div class="modal-content">
                <div class="modal-header rep-modal-head">
                    <div class="rep-modal-icon">{!! str_replace('width="18" height="18"', 'width="22" height="22"', $repFileIcon) !!}</div>
                    <div class="flex-grow-1">
                        <h5 class="modal-title" id="reportModalTitle">{{ $repMeta['title'] }}</h5>
                        <div class="rep-modal-sub">{{ $repMeta['period'] }} &middot; {{ $repMeta['category'] }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close report"></button>
                </div>
                <div class="modal-body">
                    <iframe id="reportFrame" class="rep-frame" title="Programs analysis report preview"
                        sandbox="allow-same-origin allow-modals" srcdoc="{{ $reportHtml }}"></iframe>
                </div>
                <div class="modal-footer rep-modal-foot">
                    <span class="rep-foot-note">Generated {{ $repMeta['generated'] }}</span>
                    <button type="button" class="rep-btn rep-btn-ghost" id="reportPrintBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        Print
                    </button>
                    <div class="dropup">
                        <button type="button" class="rep-btn rep-btn-primary" id="reportModalDownloadToggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download
                            <svg class="rep-caret" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rep-menu" aria-labelledby="reportModalDownloadToggle">
                            <li class="rep-menu-head" aria-hidden="true">Choose a format</li>
                            @foreach($repFormats as $fmt)
                                <li>
                                    <a class="dropdown-item" href="{{ $fmt['url'] }}" rel="nofollow"
                                        aria-label="Download as {{ $fmt['label'] }} ({{ $fmt['ext'] }})">
                                        <span class="rep-fmt-icon rep-fmt-{{ $fmt['key'] }}">{!! $repFileIcon !!}</span>
                                        <span>
                                            <span class="rep-fmt-name">{{ $fmt['label'] }}<span class="rep-fmt-ext">{{ $fmt['ext'] }}</span></span>
                                            <span class="rep-fmt-desc">{{ $fmt['desc'] }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="rep-btn rep-btn-ghost" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var modal = document.getElementById('reportModal');
            if (!modal) { return; }
            // Move to <body> so no ancestor can affect the fixed positioning, and lift it above fixed page widgets.
            document.body.appendChild(modal);
            modal.addEventListener('show.bs.modal', function () { document.body.classList.add('rep-modal-open'); });
            modal.addEventListener('hidden.bs.modal', function () { document.body.classList.remove('rep-modal-open'); });
            var printBtn = document.getElementById('reportPrintBtn');
            if (printBtn) {
                printBtn.addEventListener('click', function () {
                    var frame = document.getElementById('reportFrame');
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.print();
                    } catch (e) {
                        alert('Printing is not available here. Use Download and print the file instead.');
                    }
                });
            }
        })();
    </script>
    @endif {{-- end report section --}}

    {{-- SECTION 5: KEY INSIGHTS --}}
    <section class="section-wrap dark" id="key-insights-analysis" style="scroll-margin-top:110px;">
        <div class="container">
            <h2 class="sec-title light"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Key Insights</h2>
            <div class="row g-3">
                @foreach($insights as $i => $insight)
                    <div class="col-md-6">
                        <div class="insight-card">
                            <div
                                style="font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#FDB913;margin-bottom:6px;">
                                Finding {{ $i + 1 }}</div>
                            <p style="color:rgba(255,255,255,.88);font-size:.88rem;line-height:1.65;margin:0;">
                                {{ preg_replace('/[\x00-\x1F\x7F]|â€[^\s]*|â€|â€”|â€“/', '', $insight) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @auth
        @if(Auth::user()->isSuperAdmin())
            <style>.admin-back-btn{position:fixed;bottom:28px;left:28px;z-index:9999;display:flex;align-items:center;gap:10px;background:var(--grad);color:#fff;border:none;border-radius:50px;padding:12px 22px 12px 16px;font-family:'Inter',sans-serif;font-weight:800;font-size:.85rem;box-shadow:0 8px 28px rgba(44,62,143,.4);cursor:pointer;text-decoration:none;transition:all .3s;}.admin-back-btn:hover{transform:translateY(-4px);color:#fff;}</style>
            <a href="{{ route('superadmin.dashboard') }}" class="admin-back-btn">&#8592; Super Admin Dashboard</a>
        @elseif(Auth::user()->isAdmin())
            <style>.admin-back-btn{position:fixed;bottom:28px;left:28px;z-index:9999;display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#FDB913,#E5A500);color:#1A2A5C;border:none;border-radius:50px;padding:12px 22px 12px 16px;font-family:'Inter',sans-serif;font-weight:800;font-size:.85rem;box-shadow:0 8px 28px rgba(253,185,19,.45);cursor:pointer;text-decoration:none;transition:all .3s;}.admin-back-btn:hover{transform:translateY(-4px);color:#1A2A5C;}</style>
            <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">&#8592; Admin Dashboard</a>
        @endif
    @endauth

    @include('components.chatbot-widget')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Jump link active state
        document.addEventListener('DOMContentLoaded', function() {
            const jumpLinks = document.querySelectorAll('.jump-link');
            const sections = document.querySelectorAll('[id$="-analysis"]');
            
            function setActiveLink() {
                let current = '';
                const scrollPos = window.scrollY || window.pageYOffset;
                const windowHeight = window.innerHeight;
                const documentHeight = document.documentElement.scrollHeight;
                
                // Check if we're at the bottom of the page
                if (scrollPos + windowHeight >= documentHeight - 50) {
                    // Highlight the last section
                    current = sections[sections.length - 1].getAttribute('id');
                } else {
                    sections.forEach(section => {
                        const sectionTop = section.offsetTop - 150;
                        const sectionBottom = sectionTop + section.offsetHeight;
                        
                        if (scrollPos >= sectionTop && scrollPos < sectionBottom) {
                            current = section.getAttribute('id');
                        }
                    });
                }
                
                jumpLinks.forEach(link => {
                    link.style.background = '#f1f5f9';
                    link.style.color = '#334155';
                    link.style.borderColor = '#cbd5e1';
                    if (link.getAttribute('href') === '#' + current) {
                        link.style.background = '#2C3E8F';
                        link.style.color = '#fff';
                        link.style.borderColor = '#2C3E8F';
                    }
                });
            }
            
            window.addEventListener('scroll', setActiveLink);
            setActiveLink();
            
            jumpLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    setTimeout(() => setActiveLink(), 100);
                });
            });
        });
    </script>
    <script>
        const MUNIS  = @json($coreNames);
        const COLORS = @json($colors);
        const YEARS        = @json($allYears);
        const SUMMARY_YEARS = @json($summaryYears);   // census only: population & households
        const PROG_YEARS    = @json($programYears);   // program only: social welfare
        const SNAP   = @json($snapshot);
        const POP    = @json($populationTrend);
        const HH     = @json($householdsTrend);
        const MALE   = @json($maleTrend);
        const FEMALE = @json($femaleTrend);
        const BENEF  = @json($benefTrend);
        const PROGRAM_TYPES  = @json($programTypes);    // dynamic: discovered from the program data
        const PROGRAM_LABELS = @json($programLabels);   // dynamic: {program_type: label}
        const PROGRAM_COLORS = ['#2C3E8F','#FDB913','#6366f1','#28a745','#8B5CF6','#0891b2','#ea580c','#db2777','#65a30d','#d97706'];
        const CORRS  = @json($correlations);

        const opts = (extra={}) => ({
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{ position:'top' } },
            scales:{ y:{ beginAtZero:true, grid:{ color:'#f1f5f9' }, ticks:{ callback: v=>v.toLocaleString() } }, x:{ grid:{ display:false } } },
            ...extra
        });

        // §1 Descriptive Bar
        const descBarEl = document.getElementById('descBar');
        if (descBarEl) {
            new Chart(descBarEl, {
                type:'bar', data:{ labels:MUNIS, datasets:[
                    { label:'Population',    data:MUNIS.map(m=>SNAP[m]?.population??null),    backgroundColor:'#2C3E8F', borderRadius:6 },
                    { label:'Households',    data:MUNIS.map(m=>SNAP[m]?.households??null),    backgroundColor:'#FDB913', borderRadius:6 },
                    { label:'Beneficiaries', data:MUNIS.map(m=>SNAP[m]?.beneficiaries??null), backgroundColor:'#28a745', borderRadius:6 },
                ]}, options:opts()
            });
        }

        // §2 Population Trend
        const popTrendEl = document.getElementById('popTrend');
        if (popTrendEl) {
            new Chart(popTrendEl, {
                type:'line', data:{ labels:SUMMARY_YEARS.length ? SUMMARY_YEARS : YEARS, datasets:MUNIS.map(m=>({
                    label:m, data:(SUMMARY_YEARS.length ? SUMMARY_YEARS : YEARS).map(y=>POP[m]?.[y]??null),
                    borderColor:COLORS[m], backgroundColor:COLORS[m]+'22', fill:true, tension:.4, borderWidth:3, pointRadius:5
                }))}, options:opts({ plugins:{ legend:{ position:'top' }, tooltip:{ mode:'index', intersect:false } } })
            });
        }

        // §3 HH Combo
        const hhComboEl = document.getElementById('hhCombo');
        if (hhComboEl) {
            new Chart(hhComboEl, {
                data:{ labels:MUNIS, datasets:[
                    { type:'bar',  label:'Population', data:MUNIS.map(m=>SNAP[m]?.population??null), backgroundColor:'#2C3E8F88', borderRadius:6, yAxisID:'y' },
                    { type:'line', label:'Households', data:MUNIS.map(m=>SNAP[m]?.households??null), borderColor:'#FDB913', backgroundColor:'#FDB91322', borderWidth:3, tension:.4, pointRadius:7, yAxisID:'y2' },
                ]}, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top' } },
                    scales:{ y:{ beginAtZero:true, position:'left', grid:{ color:'#f1f5f9' }, ticks:{ callback:v=>v.toLocaleString() } },
                        y2:{ beginAtZero:true, position:'right', grid:{ drawOnChartArea:false }, ticks:{ callback:v=>v.toLocaleString() } }, x:{ grid:{ display:false } } } }
            });
        }

        // §4 Beneficiaries Stacked
        const benefStackedEl = document.getElementById('benefStacked');
        if (benefStackedEl) {
            new Chart(benefStackedEl, {
                type:'bar', data:{ labels:MUNIS, datasets:PROGRAM_TYPES.map((t,i)=>({
                    label:PROGRAM_LABELS[t]??t,
                    data:MUNIS.map(m=>SNAP[m]?.programs?.[t]??null),
                    backgroundColor:PROGRAM_COLORS[i%PROGRAM_COLORS.length], borderRadius:4
                })) }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top' } },
                    scales:{ y:{ stacked:true, beginAtZero:true, grid:{ color:'#f1f5f9' }, ticks:{ callback:v=>v.toLocaleString() } }, x:{ stacked:true, grid:{ display:false } } } }
            });
        }

        // §4 Beneficiaries Trend
        const benefTrendEl = document.getElementById('benefTrend');
        if (benefTrendEl) {
            new Chart(benefTrendEl, {
                type:'line', data:{ labels:PROG_YEARS.length ? PROG_YEARS : YEARS, datasets:MUNIS.map(m=>({
                    label:m, data:(PROG_YEARS.length ? PROG_YEARS : YEARS).map(y=>BENEF[m]?.[y]??null),
                    borderColor:COLORS[m], backgroundColor:COLORS[m]+'22', fill:true, tension:.4, borderWidth:3, pointRadius:5
                }))}, options:opts({ plugins:{ legend:{ position:'top' }, tooltip:{ mode:'index', intersect:false } } })
            });
        }
    </script>
</body>

</html>