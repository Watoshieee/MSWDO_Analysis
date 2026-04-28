<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Demographic Analysis – MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Leaflet.js for the map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        html,
        body {
            overscroll-behavior: none;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --accent-blue3: #6366f1;
            --accent-green: #28a745;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
        }

        body {
            background: #e2e8f0;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.55rem;
            color: white !important;
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
            color: rgba(255, 255, 255, 0.88) !important;
            font-weight: 600;
            transition: all 0.25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 700;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: 0.92rem;
            font-weight: 500;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 700;
            transition: all 0.3s;
            font-size: 0.88rem;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        .btn-login {
            background: white;
            color: var(--primary-blue);
            border: 2px solid white;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
            transform: translateY(-2px);
        }

        .btn-register {
            background: transparent;
            border: 2px solid white;
            color: white;
            border-radius: 30px;
            padding: 8px 25px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        /* ===== HERO ===== */
        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 58px 0 48px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(253, 185, 19, 0.1);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -50px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .hero-banner .hero-badge {
            display: inline-block;
            background: rgba(253, 185, 19, 0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, 0.35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .hero-banner h1 {
            font-size: 2.6rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .hero-divider {
            width: 55px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 16px 0;
        }

        .hero-banner p {
            font-size: 1.02rem;
            opacity: 0.87;
            max-width: 700px;
            line-height: 1.75;
        }

        /* ===== SECTION ===== */
        .section-wrapper {
            padding: 52px 0;
        }

        .section-wrapper.alt {
            background: #f8fafc;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 13px;
            margin-bottom: 28px;
            letter-spacing: -0.01em;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #cbd5e1;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stat-card.accent-yellow::before {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }

        .stat-card.accent-green::before {
            background: linear-gradient(135deg, #0891b2 0%, #0369a1 100%);
        }

        .stat-card.accent-red::before {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        }

        .stat-icon {
            min-width: 80px;
            width: auto;
            height: 52px;
            border-radius: 14px;
            background: var(--bg-soft-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            margin-bottom: 16px;
            font-weight: 800;
            color: var(--primary-blue);
            padding: 0 10px;
            letter-spacing: 0.03em;
        }

        .stat-card h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0 0 4px 0;
        }

        .stat-card .stat-label {
            color: #64748b;
            font-size: 0.88rem;
            font-weight: 500;
            margin: 0;
        }

        .stat-card .stat-sub {
            color: #94a3b8;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        /* ===== MAP ===== */
        #municipalityMap {
            height: 450px;
            border-radius: 16px;
            border: 2px solid #cbd5e1;
            z-index: 1;
        }

        .map-legend {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 16px;
            padding: 14px 18px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 7px;
        }

        .legend-item {
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
        }

        .map-info-card {
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            padding: 20px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .map-info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .map-info-card h6 {
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 14px;
            font-size: 1rem;
        }

        .muni-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .muni-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .muni-color {
            width: 10px;
            height: 38px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .muni-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.92rem;
        }

        .muni-pop {
            color: #64748b;
            font-size: 0.82rem;
        }

        /* ===== CHART CARDS ===== */
        .chart-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #cbd5e1;
            position: relative;
            overflow: hidden;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .chart-card h5 {
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 6px;
            font-size: 1.05rem;
        }

        .chart-card p.chart-sub {
            color: #94a3b8;
            font-size: 0.82rem;
            margin-bottom: 18px;
        }

        .chart-container {
            position: relative;
            height: 320px;
        }

        /* ===== AGE BREAKDOWN ===== */
        .age-bar-row {
            margin-bottom: 16px;
        }

        .age-bar-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
        }

        .age-bar-track {
            background: #F1F5F9;
            border-radius: 20px;
            height: 10px;
            overflow: hidden;
        }

        .age-bar-fill {
            height: 100%;
            border-radius: 20px;
            transition: width 1s ease;
        }

        /* ===== TABLE ===== */
        .data-table-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #cbd5e1;
            position: relative;
            overflow: hidden;
        }

        .data-table-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .data-table-card h5 {
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 6px;
        }

        .data-table-card p.table-sub {
            color: #94a3b8;
            font-size: 0.82rem;
            margin-bottom: 18px;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            border: none;
            padding: 12px 16px;
            font-size: 0.88rem;
        }

        .table thead th:first-child {
            border-radius: 8px 0 0 0;
        }

        .table thead th:last-child {
            border-radius: 0 8px 0 0;
        }

        .table tbody tr {
            /* No hover effect */
        }

        .table tbody td {
            padding: 12px 16px;
            font-size: 0.88rem;
            vertical-align: middle;
        }

        .muni-badge {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 7px;
        }

        /* ===== FOOTER ===== */
        .footer-strip {
            background: var(--primary-gradient);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 0.88rem;
        }

        @media (max-width: 768px) {
            .hero-banner h1 {
                font-size: 1.85rem;
            }

            .section-title {
                font-size: 1.2rem;
            }

            #municipalityMap {
                height: 300px;
            }
        }
    </style>
</head>

<body>

    <!-- ===== NAVBAR – role-aware ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD"
                    style="width:36px;height:36px;object-fit:contain;"> MSWDO
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
                            <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Public View</a></li>
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
                            <li class="nav-item"><a class="nav-link active" href="/analysis/demographic">Demographic</a></li>
                            <li class="nav-item"><a class="nav-link" href="/analysis/programs">Analysis</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="/analysis/demographic">Demographic</a></li>
                        <li class="nav-item"><a class="nav-link" href="/analysis/programs">Analysis</a></li>
                    </ul>
                    <div class="d-flex">
                        <a href="{{ route('login') }}" class="btn-login me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn-register">Register</a>
                    </div>
                @endauth

            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:1;">
            <div class="hero-badge">Demographic Data</div>
            <h1>Population Demographics</h1>
            <div class="hero-divider"></div>
            <p>
                Explore the population composition, gender distribution, and age structure
                of municipalities in Laguna province served by MSWDO.
                Understanding demographics helps design better-targeted social welfare programs.
            </p>
        </div>
    </section>

    @php
        $totalPop = array_sum(array_column($demographicData, 'total'));
        $totalHouseholds = array_sum(array_column($demographicData, 'households'));
        $totalBeneficiaries = array_sum(array_column($demographicData, 'beneficiaries'));
        $totalYouth = array_sum(array_column($demographicData, 'age_0_19'));
        $totalAdult = array_sum(array_column($demographicData, 'age_20_59'));
        $totalSenior = array_sum(array_column($demographicData, 'age_60_100'));
        $householdsPct = $totalPop > 0 ? round(($totalHouseholds / $totalPop) * 100, 1) : 0;
        $beneficiariesPct = $totalPop > 0 ? round(($totalBeneficiaries / $totalPop) * 100, 1) : 0;
        $youthPct = $totalPop > 0 ? round(($totalYouth / $totalPop) * 100, 1) : 0;
        $adultPct = $totalPop > 0 ? round(($totalAdult / $totalPop) * 100, 1) : 0;
        $seniorPct = $totalPop > 0 ? round(($totalSenior / $totalPop) * 100, 1) : 0;
    @endphp

    {{-- ===== YEAR FILTER BAR ===== --}}
    <div
        style="background:#fff;border-bottom:1px solid #e2e8f0;padding:14px 0;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(44,62,143,0.07);">
        <div class="container d-flex align-items-center gap-3 flex-wrap">
            <span style="font-weight:700;color:#2C3E8F;font-size:0.93rem;">📅 View Year:</span>
            <form method="GET" action="" id="yearFilterForm" class="d-flex gap-2 align-items-center flex-wrap">
                @foreach($allYears as $yr)
                    <a href="?year={{ $yr }}" style="padding:6px 18px;border-radius:20px;font-weight:600;font-size:0.85rem;text-decoration:none;
                                  background:{{ $yr == $selectedYear ? '#2C3E8F' : '#f1f5f9' }};
                                  color:{{ $yr == $selectedYear ? '#fff' : '#334155' }};
                                  border:1px solid {{ $yr == $selectedYear ? '#2C3E8F' : '#cbd5e1' }};
                                  transition:all 0.2s;" class="year-pill">{{ $yr }}</a>
                @endforeach
                <span style="color:#94a3b8;font-size:0.82rem;margin-left:4px;">Showing data for
                    <strong>{{ $selectedYear }}</strong></span>
            </form>
        </div>
    </div>

    <!-- ===== SUMMARY STAT CARDS ===== -->
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">Population Overview</h2>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">POPULATION</div>
                        <h2>{{ number_format($totalPop) }}</h2>
                        <p class="stat-label">Total Population</p>
                        <p class="stat-sub">Across {{ count($demographicData) }} municipalities</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card accent-yellow">
                        <div class="stat-icon" style="background:#E5EEFF;color:#1d4ed8;">HOUSEHOLD</div>
                        <h2 style="color:#2C3E8F;">{{ number_format($totalHouseholds) }}</h2>
                        <p class="stat-label">Total Households</p>
                        <p class="stat-sub">{{ $householdsPct }}% of population</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card accent-green">
                        <div class="stat-icon" style="background:#cce4f0;color:#0369a1;">BENEFICIARIES</div>
                        <h2 style="color:#2C3E8F;">{{ number_format($totalBeneficiaries) }}</h2>
                        <p class="stat-label">Total Beneficiaries</p>
                        <p class="stat-sub">{{ $beneficiariesPct }}% of population</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card accent-red">
                        <div class="stat-icon" style="background:#e0e7ff;color:#4f46e5;">60+</div>
                        <h2 style="color:#2C3E8F;">{{ number_format($totalSenior) }}</h2>
                        <p class="stat-label">Senior Citizens</p>
                        <p class="stat-sub">{{ $seniorPct }}% of total</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== MAP ===== -->
    <section class="section-wrapper alt" style="background: #e2e8f0 !important;">
        <div class="container" style="background: transparent !important;">
            <h2 class="section-title">Geographic Location</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">
                These municipalities are located in Laguna, Philippines.
                Click on a marker to see municipality details.
            </p>
            <div class="row g-4 align-items-stretch" style="background: transparent !important;">
                <div class="col-lg-8" style="background: transparent !important;">
                    <div id="municipalityMap"></div>
                    <div class="map-legend">
                        @foreach($demographicData as $mName => $_)
                            <div class="legend-item">
                                <span class="legend-dot" style="background:{{ $colors[$mName] ?? '#2C3E8F' }};"></span>
                                {{ $mName }}
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4" style="background: transparent !important;">
                    <div class="map-info-card">
                        <h6>Municipality Breakdown</h6>
                        @foreach($demographicData as $name => $data)
                            @php
                                $color = $colors[$name] ?? '#2C3E8F';
                            @endphp
                            <div class="muni-item">
                                <div class="muni-color" style="background:{{ $color }};"></div>
                                <div>
                                    <div class="muni-name">{{ $name }}</div>
                                    <div class="muni-pop">{{ number_format($data['total']) }} total population</div>
                                    <div class="muni-pop">{{ number_format($data['households']) }} households &bull;
                                        {{ number_format($data['beneficiaries']) }} beneficiaries
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div style="margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9;">
                            <p style="font-size:0.82rem;color:#64748b;margin:0;line-height:1.6;">
                                These municipalities are primary coverage areas
                                of the MSWDO social welfare programs in Laguna.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 3: GENDER DISTRIBUTION ===== --}}
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">⚤ Gender Distribution</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">Compare male vs. female population
                and identify any imbalance.</p>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="chart-card h-100">
                        <h5>Overall Male vs Female</h5>
                        <p class="chart-sub">Combined across all {{ count($demographicData) }} municipalities — {{ $selectedYear }}</p>
                        <div class="chart-container" style="height:280px;">
                            <canvas id="genderPieChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="chart-card h-100">
                        <h5>Male vs Female per Municipality</h5>
                        <p class="chart-sub">Side-by-side comparison — {{ $selectedYear }}</p>
                        <div class="chart-container" style="height:280px;">
                            <canvas id="genderBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 4: AGE GROUP STACKED BAR ===== --}}
    <section class="section-wrapper alt" style="background:#f0f5ff !important;">
        <div class="container">
            <h2 class="section-title">👥 Age Group Structure</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">Is the population youthful, mature,
                or aging? Compare age brackets per municipality.</p>
            <div class="chart-card">
                <h5>Population by Age Group per Municipality</h5>
                <p class="chart-sub">Stacked bars show Youth (0–19), Working Age (20–59), Senior (60+) —
                    {{ $selectedYear }}</p>
                <div class="chart-container" style="height:320px;">
                    <canvas id="ageStackedChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== AGE STRUCTURE ===== -->
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">Age Structure</h2>
            <div class="row g-4">
                <!-- Combined age breakdown bars -->
                <div class="col-lg-5">
                    <div class="chart-card h-100">
                        <h5>Age Group Breakdown</h5>
                        <p class="chart-sub">Combined across all municipalities</p>
                        <div class="age-bar-row">
                            <div class="age-bar-label">
                                <span>Youth (0–19 years)</span>
                                <span
                                    style="color:var(--primary-blue);font-weight:700;">{{ number_format($totalYouth) }}
                                    &nbsp;({{ $youthPct }}%)</span>
                            </div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill"
                                    style="width:{{ $youthPct }}%;background:var(--primary-blue);"></div>
                            </div>
                        </div>
                        <div class="age-bar-row">
                            <div class="age-bar-label">
                                <span>Adults (20–59 years)</span>
                                <span
                                    style="color:var(--secondary-yellow);font-weight:700;">{{ number_format($totalAdult) }}
                                    &nbsp;({{ $adultPct }}%)</span>
                            </div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill"
                                    style="width:{{ $adultPct }}%;background:var(--secondary-yellow);"></div>
                            </div>
                        </div>
                        <div class="age-bar-row">
                            <div class="age-bar-label">
                                <span>Seniors (60+ years)</span>
                                <span style="color:#28a745;font-weight:700;">{{ number_format($totalSenior) }}
                                    &nbsp;({{ $seniorPct }}%)</span>
                            </div>
                            <div class="age-bar-track">
                                <div class="age-bar-fill" style="width:{{ $seniorPct }}%;background:#28a745;"></div>
                            </div>
                        </div>
                        <hr style="margin:22px 0;">
                        @foreach($demographicData as $name => $data)
                            @php $colors2 = $colors; @endphp
                            <div style="margin-bottom:10px;">
                                <div
                                    style="font-size:0.85rem;font-weight:700;color:{{ $colors2[$name] ?? '#333' }};margin-bottom:6px;">
                                    {{ $name }}
                                </div>
                                <div style="display:flex;gap:6px;">
                                    <div
                                        style="flex:{{ $data['age_0_19'] }};background:#E5EEFF;border-radius:4px;height:8px;">
                                    </div>
                                    <div
                                        style="flex:{{ $data['age_20_59'] }};background:#FFF3D6;border-radius:4px;height:8px;">
                                    </div>
                                    <div
                                        style="flex:{{ $data['age_60_100'] }};background:#e8f5e9;border-radius:4px;height:8px;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div style="display:flex;gap:18px;margin-top:12px;font-size:0.76rem;color:#64748b;">
                            <span><span
                                    style="display:inline-block;width:10px;height:10px;background:#E5EEFF;border-radius:2px;margin-right:4px;border:1px solid #c7d7f0;"></span>Youth</span>
                            <span><span
                                    style="display:inline-block;width:10px;height:10px;background:#FFF3D6;border-radius:2px;margin-right:4px;border:1px solid #f0dfa0;"></span>Adult</span>
                            <span><span
                                    style="display:inline-block;width:10px;height:10px;background:#e8f5e9;border-radius:2px;margin-right:4px;border:1px solid #b2dfdb;"></span>Senior</span>
                        </div>
                    </div>
                </div>
                <!-- Age per municipality chart -->
                <div class="col-lg-7">
                    <div class="chart-card h-100">
                        <h5>Age Distribution by Municipality</h5>
                        <p class="chart-sub">Comparison of age groups per municipality</p>
                        <div class="chart-container">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 2: POPULATION TREND ===== --}}
    <section class="section-wrapper alt" style="background:#f0f5ff !important;">
        <div class="container">
            <h2 class="section-title">📈 Population Trend (Yearly)</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">Track population growth or decline
                over time for each municipality.</p>
            <div class="chart-card">
                <h5>Population Growth Over Time</h5>
                <p class="chart-sub">Lines represent Magdalena, Liliw, and Majayjay across all recorded years</p>
                <div class="chart-container" style="height:340px;">
                    <canvas id="popTrendChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 5: HOUSEHOLD ANALYSIS ===== --}}
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">🏠 Household Analysis</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">Compare total households and estimate
                average household size across municipalities.</p>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="chart-card h-100">
                        <h5>Population vs Households</h5>
                        <p class="chart-sub">Combo chart showing population (bars) and households (line) —
                            {{ $selectedYear }}</p>
                        <div class="chart-container" style="height:300px;">
                            <canvas id="hhComboChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="chart-card h-100">
                        <h5>Average Household Size</h5>
                        <p class="chart-sub">Estimated persons per household</p>
                        <div style="padding:8px 0;">
                            @foreach($demographicData as $name => $d)
                                @php $c = ['Magdalena' => '#2C3E8F', 'Liliw' => '#FDB913', 'Majayjay' => '#28a745'][$name] ?? '#666'; @endphp
                                <div style="margin-bottom:22px;">
                                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                                        <span style="font-weight:700;color:#1e293b;font-size:0.92rem;">
                                            <span
                                                style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $c }};margin-right:6px;"></span>
                                            {{ $name }}
                                        </span>
                                        <span
                                            style="font-weight:800;color:{{ $c }};font-size:1.05rem;">{{ $d['avg_hh_size'] }}
                                            <small
                                                style="font-weight:500;color:#64748b;font-size:0.75rem;">persons/hh</small></span>
                                    </div>
                                    <div style="background:#F1F5F9;border-radius:20px;height:10px;overflow:hidden;">
                                        <div
                                            style="height:100%;border-radius:20px;background:{{ $c }};width:{{ min($d['avg_hh_size'] / 10 * 100, 100) }}%;transition:width 1s ease;">
                                        </div>
                                    </div>
                                    <div style="font-size:0.78rem;color:#64748b;margin-top:4px;">
                                        {{ number_format($d['households']) }} households · {{ number_format($d['total']) }}
                                        total population</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== HOUSEHOLDS & BENEFICIARIES DISTRIBUTION ===== -->
    <section class="section-wrapper alt" style="background: #e2e8f0 !important;">
        <div class="container" style="background: transparent !important;">
            <h2 class="section-title">Households & Beneficiaries Distribution</h2>
            <div class="row g-4" style="background: transparent !important;">
                <div class="col-lg-7" style="background: transparent !important;">
                    <div class="chart-card">
                        <h5>Households vs. Beneficiaries</h5>
                        <p class="chart-sub">By municipality comparison</p>
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5" style="background: transparent !important;">
                    <div class="chart-card h-100">
                        <h5>Distribution Summary</h5>
                        <p class="chart-sub">Combined overall ratio</p>
                        <div class="chart-container" style="height:240px;">
                            <canvas id="hhBenefPieChart"></canvas>
                        </div>
                        <div style="margin-top:18px;">
                            @foreach($demographicData as $name => $data)
                                @php $c = ['Magdalena' => '#2C3E8F', 'Liliw' => '#FDB913', 'Majayjay' => '#28a745']; @endphp
                                <div
                                    style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #f1f5f9;">
                                    <span style="font-weight:600;font-size:0.88rem;color:#334155;">
                                        <span
                                            style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $c[$name] ?? '#333' }};margin-right:6px;"></span>
                                        {{ $name }}
                                    </span>
                                    <span style="font-size:0.82rem;color:#64748b;">{{ number_format($data['households']) }}
                                        HH &bull;
                                        {{ number_format($data['beneficiaries']) }} Ben</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 6: BENEFICIARIES DISTRIBUTION ===== --}}
    <section class="section-wrapper alt" style="background:#f0f5ff !important;">
        <div class="container">
            <h2 class="section-title">🤝 Beneficiaries Distribution</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">Breakdown of social welfare program
                beneficiaries per municipality.</p>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="chart-card h-100">
                        <h5>Programs per Municipality (Stacked)</h5>
                        <p class="chart-sub">PWD, AICS, Solo Parent, 4Ps, Senior — {{ $selectedYear }}</p>
                        <div class="chart-container" style="height:320px;">
                            <canvas id="benefStackedChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-card h-100">
                        <h5>Total Beneficiaries</h5>
                        <p class="chart-sub">Combined all programs — {{ $selectedYear }}</p>
                        <div class="chart-container" style="height:320px;">
                            <canvas id="benefTotalChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 7: BENEFICIARIES TREND ===== --}}
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">📊 Beneficiaries Trend (Yearly)</h2>
            <p class="text-muted mb-4" style="margin-top:-18px;font-size:0.93rem;">Analyze increase or decrease in total
                beneficiaries across all years.</p>
            <div class="chart-card">
                <h5>Total Beneficiaries Over Time</h5>
                <p class="chart-sub">Tracks combined PWD + AICS + Solo Parent + 4Ps + Senior across all recorded years
                </p>
                <div class="chart-container" style="height:320px;">
                    <canvas id="benefTrendChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 8: KEY INSIGHTS ===== --}}
    <section class="section-wrapper alt" style="background:#1e293b !important;padding:52px 0;">
        <div class="container">
            <h2 class="section-title" style="color:#FDB913;">💡 Key Insights</h2>
            <p style="color:rgba(255,255,255,0.7);margin-top:-18px;margin-bottom:32px;font-size:0.93rem;">Auto-generated
                insights based on {{ $selectedYear }} demographic data.</p>
            <div class="row g-4">
                @foreach($insights as $i => $insight)
                    <div class="col-md-6 col-lg-4">
                        <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:24px;height:100%;transition:transform 0.2s,background 0.2s;"
                            onmouseenter="this.style.background='rgba(255,255,255,0.1)'"
                            onmouseleave="this.style.background='rgba(255,255,255,0.06)'">
                            <div
                                style="font-size:0.72rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:#FDB913;margin-bottom:8px;">
                                Finding {{ $i + 1 }}</div>
                            <p style="color:rgba(255,255,255,0.88);font-size:0.9rem;line-height:1.65;margin:0;">
                                {{ $insight }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== DATA TABLE ===== -->
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">Detailed Data Table</h2>
            <div class="data-table-card">
                <h5>Complete Demographic Breakdown</h5>
                <p class="table-sub">Full population data across all {{ count($demographicData) }} municipalities</p>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Municipality</th>
                                <th class="text-center">Total Population</th>
                                <th class="text-center">Households</th>
                                <th class="text-center">Beneficiaries</th>
                                <th class="text-center">Youth (0–19)</th>
                                <th class="text-center">Adults (20–59)</th>
                                <th class="text-center">Seniors (60+)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demographicData as $municipality => $data)
                                @php /* use dynamic $colors from controller */ @endphp
                                <tr>
                                    <td>
                                        <span class="muni-badge"
                                            style="background:{{ $colors[$municipality] ?? '#333' }};"></span>
                                        <strong>{{ $municipality }}</strong>
                                    </td>
                                    <td class="text-center"><strong>{{ number_format($data['total']) }}</strong></td>
                                    <td class="text-center">{{ number_format($data['households']) }} <small
                                            class="text-muted">({{ $data['households_pct'] }}%)</small></td>
                                    <td class="text-center">{{ number_format($data['beneficiaries']) }} <small
                                            class="text-muted">({{ $data['beneficiaries_pct'] }}%)</small></td>
                                    <td class="text-center">{{ number_format($data['age_0_19']) }} <small
                                            class="text-muted">({{ $data['age_0_19_pct'] }}%)</small></td>
                                    <td class="text-center">{{ number_format($data['age_20_59']) }} <small
                                            class="text-muted">({{ $data['age_20_59_pct'] }}%)</small></td>
                                    <td class="text-center">{{ number_format($data['age_60_100']) }} <small
                                            class="text-muted">({{ $data['age_60_100_pct'] }}%)</small></td>
                                </tr>
                            @endforeach
                            <tr style="background:#f8fafc;font-weight:700;">
                                <td><strong>Total</strong></td>
                                <td class="text-center"><strong>{{ number_format($totalPop) }}</strong></td>
                                <td class="text-center">{{ number_format($totalHouseholds) }} <small
                                        class="text-muted">({{ $householdsPct }}%)</small></td>
                                <td class="text-center">{{ number_format($totalBeneficiaries) }} <small
                                        class="text-muted">({{ $beneficiariesPct }}%)</small></td>
                                <td class="text-center">{{ number_format($totalYouth) }} <small
                                        class="text-muted">({{ $youthPct }}%)</small></td>
                                <td class="text-center">{{ number_format($totalAdult) }} <small
                                        class="text-muted">({{ $adultPct }}%)</small></td>
                                <td class="text-center">{{ number_format($totalSenior) }} <small
                                        class="text-muted">({{ $seniorPct }}%)</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <div class="footer-strip">
        MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- ===== PHP → JS data bridge ===== --}}
    <script>
        const YEARS = @json($allYears);
        const MUNIS = @json($coreNames);
        const COLORS = @json($colors);
        const POP_TREND = @json($populationTrend);
        const HH_TREND = @json($householdsTrend);
        const BENEF_TREND = @json($benefTrend);
        const DEMO = @json($demographicData);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ===== LEAFLET MAP =====
            // Laguna province bounding box — user cannot pan/zoom outside this
            const laguanaBounds = L.latLngBounds(
                L.latLng(13.88, 121.10),   // SW corner
                L.latLng(14.42, 121.80)    // NE corner
            );

            const map = L.map('municipalityMap', {
                maxBounds: laguanaBounds,
                maxBoundsViscosity: 1.0,   // solid wall — no rubber-band drag past boundary
                minZoom: 10,               // can't zoom out past province level
                maxZoom: 17
            }).setView([14.085, 121.453], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 18
            }).addTo(map);

            @php
                // Known fallback coordinates for Laguna municipalities
                $lagCoords = [
                    'Magdalena'  => [14.1341, 121.4536],
                    'Liliw'      => [14.1291, 121.4250],
                    'Majayjay'   => [14.0337, 121.4714],
                    'Nagcarlan'  => [14.0736, 121.4133],
                    'Rizal'      => [14.1017, 121.5394],
                    'Los Baños'  => [14.1717, 121.2408],
                    'Bay'        => [14.1831, 121.2839],
                    'San Pablo'  => [14.0682, 121.3223],
                    'Alaminos'   => [14.0622, 121.2451],
                    'Calauan'    => [14.1333, 121.3167],
                    'Victoria'   => [14.2133, 121.2817],
                    'Pila'       => [14.2314, 121.3656],
                    'Santa Cruz' => [14.2817, 121.4167],
                    'Lumban'     => [14.2731, 121.4614],
                    'Pagsanjan'  => [14.2734, 121.4578],
                    'Cavinti'    => [14.2422, 121.5131],
                    'Luisiana'   => [14.1725, 121.5044],
                    'Siniloan'   => [14.4275, 121.4458],
                    'Famy'       => [14.4306, 121.4750],
                    'Kalayaan'   => [14.3072, 121.5083],
                    'Pakil'      => [14.3669, 121.4633],
                    'Pangil'     => [14.4022, 121.4628],
                    'Mabitac'    => [14.4289, 121.4236],
                    'Sta. Maria' => [14.4797, 121.4194],
                    'Cavite'     => [14.4791, 120.8980], // outside Laguna — still works
                ];
                $muniMapData = [];
                foreach ($demographicData as $mName => $mData) {
                    $coords = $lagCoords[$mName] ?? [14.17, 121.37]; // center of Laguna as default
                    $muniMapData[] = [
                        'name'          => $mName,
                        'searchQuery'   => $mName . ', Laguna, Philippines',
                        'lat'           => $coords[0],
                        'lng'           => $coords[1],
                        'color'         => $colors[$mName] ?? '#2C3E8F',
                        'pop'           => $mData['total'] ?? 0,
                        'households'    => $mData['households'] ?? 0,
                        'beneficiaries' => $mData['beneficiaries'] ?? 0,
                    ];
                }
            @endphp
            const municipalities = {!! json_encode($muniMapData) !!}.map(m => ({ ...m, polygonLayer: null, bounds: null }));


            // Dim all polygons except the active one
            function highlightMunicipality(active) {
                municipalities.forEach(m => {
                    if (!m.polygonLayer) return;
                    if (m.name === active.name) {
                        m.polygonLayer.setStyle({
                            fillOpacity: 0.30,
                            opacity: 1,
                            weight: 3
                        });
                    } else {
                        m.polygonLayer.setStyle({
                            fillOpacity: 0.06,
                            opacity: 0.4,
                            weight: 1.5
                        });
                    }
                });
            }

            // Fetch GeoJSON boundary from Nominatim and draw polygon
            async function loadBoundary(m) {
                try {
                    const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(m.searchQuery)}&format=json&polygon_geojson=1&limit=5`;
                    const res = await fetch(url, { headers: { 'Accept-Language': 'en' } });
                    const data = await res.json();

                    // Prefer results with a real polygon boundary (not just a Point)
                    const result = data.find(r => r.geojson && r.class === 'boundary' && r.type === 'administrative')
                        || data.find(r => r.geojson && r.geojson.type !== 'Point')
                        || data[0];

                    if (!result) return;

                    // ✅ Use Nominatim's own centroid for the marker (most accurate)
                    if (result.lat && result.lon) {
                        m.lat = parseFloat(result.lat);
                        m.lng = parseFloat(result.lon);
                    }

                    if (!result.geojson || result.geojson.type === 'Point') return;

                    m.polygonLayer = L.geoJSON(result.geojson, {
                        style: {
                            color: m.color,
                            weight: 2,
                            opacity: 0.6,
                            fillColor: m.color,
                            fillOpacity: 0.10,
                            dashArray: '4 4'
                        }
                    }).addTo(map);

                    m.bounds = m.polygonLayer.getBounds();

                    // ✅ Override with polygon centroid — even more precise than Nominatim's lat/lon
                    const center = m.bounds.getCenter();
                    m.lat = center.lat;
                    m.lng = center.lng;

                } catch (e) {
                    console.warn('Could not load boundary for', m.name, e);
                }
            }

            // Load all boundaries first, then add markers
            Promise.all(municipalities.map(m => loadBoundary(m))).then(() => {
                municipalities.forEach(m => {

                    // Larger, more polished marker
                    const icon = L.divIcon({
                        className: '',
                        html: `<div style="
                        width:22px;height:22px;border-radius:50%;
                        background:${m.color};border:3px solid white;
                        box-shadow:0 3px 10px rgba(0,0,0,0.35);
                        cursor:pointer;
                        transition:transform 0.2s;
                    " onmouseover="this.style.transform='scale(1.3)'" onmouseout="this.style.transform='scale(1)'"></div>`,
                        iconSize: [22, 22],
                        iconAnchor: [11, 11]
                    });

                    const marker = L.marker([m.lat, m.lng], { icon }).addTo(map);

                    marker.bindPopup(`
                    <div style="font-family:'Inter',sans-serif;min-width:175px;padding:4px 2px;">
                        <div style="font-weight:800;font-size:1rem;color:${m.color};margin-bottom:8px;padding-bottom:6px;border-bottom:2px solid ${m.color}20;">${m.name}</div>
                        <div style="font-size:0.84rem;color:#334155;margin-bottom:3px;"><b>Population:</b> ${m.pop.toLocaleString()}</div>
                        <div style="font-size:0.84rem;color:#334155;margin-bottom:3px;"><b>Households:</b> ${m.households.toLocaleString()}</div>
                        <div style="font-size:0.84rem;color:#334155;margin-bottom:6px;"><b>Beneficiaries:</b> ${m.beneficiaries.toLocaleString()}</div>
                        <div style="font-size:0.76rem;color:#94a3b8;">Laguna, Philippines</div>
                    </div>
                `, { maxWidth: 210 });

                    marker.on('click', () => {
                        highlightMunicipality(m);
                        if (m.bounds) {
                            map.flyToBounds(m.bounds, { padding: [40, 40], duration: 1.2, maxZoom: 14 });
                        } else {
                            map.flyTo([m.lat, m.lng], 13, { duration: 1.2 });
                        }
                    });
                });
            });

            // ===== HOUSEHOLDS & BENEFICIARIES BAR CHART =====
            new Chart(document.getElementById('genderChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($demographicData)) !!},
                    datasets: [
                        {
                            label: 'Households',
                            data: {!! json_encode(array_column($demographicData, 'households')) !!},
                            backgroundColor: '#2C3E8F',
                            borderRadius: 6
                        },
                        {
                            label: 'Beneficiaries',
                            data: {!! json_encode(array_column($demographicData, 'beneficiaries')) !!},
                            backgroundColor: '#FDB913',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
                }
            });

            // ===== HOUSEHOLDS & BENEFICIARIES PIE CHART =====
            new Chart(document.getElementById('hhBenefPieChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Households', 'Beneficiaries'],
                    datasets: [{
                        data: [{{ $totalHouseholds }}, {{ $totalBeneficiaries }}],
                        backgroundColor: ['#2C3E8F', '#FDB913'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                    return ` ${ctx.label}: ${ctx.parsed.toLocaleString()} (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            });

            // ===== AGE DISTRIBUTION CHART =====
            new Chart(document.getElementById('ageChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($demographicData)) !!},
                    datasets: [
                        {
                            label: 'Youth (0–19)',
                            data: {!! json_encode(array_column($demographicData, 'age_0_19')) !!},
                            backgroundColor: '#2C3E8F',
                            borderRadius: 5
                        },
                        {
                            label: 'Adults (20–59)',
                            data: {!! json_encode(array_column($demographicData, 'age_20_59')) !!},
                            backgroundColor: '#FDB913',
                            borderRadius: 5
                        },
                        {
                            label: 'Seniors (60+)',
                            data: {!! json_encode(array_column($demographicData, 'age_60_100')) !!},
                            backgroundColor: '#28a745',
                            borderRadius: 5
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: { beginAtZero: true, stacked: false, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // ===== POPULATION TREND LINE CHART =====
            const popDatasets = MUNIS.map(muni => ({
                label: muni,
                data: YEARS.map(yr => POP_TREND[muni]?.[yr] ?? 0),
                borderColor: COLORS[muni],
                backgroundColor: COLORS[muni] + '22',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 8,
            }));
            new Chart(document.getElementById('popTrendChart'), {
                type: 'line',
                data: { labels: YEARS, datasets: popDatasets },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'top' }, tooltip: { mode: 'index', intersect: false } },
                    scales: {
                        y: { beginAtZero: false, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // ===== GENDER PIE CHART =====
            const totalMale = MUNIS.reduce((s, m) => s + (DEMO[m]?.male ?? 0), 0);
            const totalFemale = MUNIS.reduce((s, m) => s + (DEMO[m]?.female ?? 0), 0);
            if (document.getElementById('genderPieChart')) {
                new Chart(document.getElementById('genderPieChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Male', 'Female'],
                        datasets: [{ data: [totalMale, totalFemale], backgroundColor: ['#2C3E8F', '#FDB913'], borderWidth: 0, hoverOffset: 8 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw.toLocaleString()}` } }
                        },
                        cutout: '60%'
                    }
                });
            }

            // ===== GENDER BAR CHART =====
            if (document.getElementById('genderBarChart')) {
                new Chart(document.getElementById('genderBarChart'), {
                    type: 'bar',
                    data: {
                        labels: MUNIS,
                        datasets: [
                            { label: 'Male', data: MUNIS.map(m => DEMO[m]?.male ?? 0), backgroundColor: '#2C3E8F', borderRadius: 6 },
                            { label: 'Female', data: MUNIS.map(m => DEMO[m]?.female ?? 0), backgroundColor: '#FDB913', borderRadius: 6 }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } }, x: { grid: { display: false } } }
                    }
                });
            }

            // ===== AGE GROUP STACKED BAR =====
            if (document.getElementById('ageStackedChart')) {
                new Chart(document.getElementById('ageStackedChart'), {
                    type: 'bar',
                    data: {
                        labels: MUNIS,
                        datasets: [
                            { label: 'Youth (0–19)', data: MUNIS.map(m => DEMO[m]?.age_0_19 ?? 0), backgroundColor: '#2C3E8F', borderRadius: 4 },
                            { label: 'Working Age (20–59)', data: MUNIS.map(m => DEMO[m]?.age_20_59 ?? 0), backgroundColor: '#FDB913', borderRadius: 4 },
                            { label: 'Senior (60+)', data: MUNIS.map(m => DEMO[m]?.age_60_100 ?? 0), backgroundColor: '#28a745', borderRadius: 4 }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { stacked: true, beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } }, x: { stacked: true, grid: { display: false } } }
                    }
                });
            }

            // ===== HOUSEHOLD COMBO CHART =====
            if (document.getElementById('hhComboChart')) {
                new Chart(document.getElementById('hhComboChart'), {
                    data: {
                        labels: MUNIS,
                        datasets: [
                            { type: 'bar', label: 'Total Population', data: MUNIS.map(m => DEMO[m]?.total ?? 0), backgroundColor: '#2C3E8F88', borderRadius: 6, yAxisID: 'y' },
                            { type: 'line', label: 'Households', data: MUNIS.map(m => DEMO[m]?.households ?? 0), borderColor: '#FDB913', backgroundColor: '#FDB91322', borderWidth: 3, tension: 0.4, pointRadius: 7, yAxisID: 'y2' }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, position: 'left', grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } },
                            y2: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, ticks: { callback: v => v.toLocaleString() } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            // ===== BENEFICIARIES STACKED BAR =====
            if (document.getElementById('benefStackedChart')) {
                new Chart(document.getElementById('benefStackedChart'), {
                    type: 'bar',
                    data: {
                        labels: MUNIS,
                        datasets: [
                            { label: 'PWD', data: MUNIS.map(m => DEMO[m]?.pwd ?? 0), backgroundColor: '#2C3E8F', borderRadius: 4 },
                            { label: 'AICS', data: MUNIS.map(m => DEMO[m]?.aics ?? 0), backgroundColor: '#FDB913', borderRadius: 4 },
                            { label: 'Solo Parent', data: MUNIS.map(m => DEMO[m]?.solo_parent ?? 0), backgroundColor: '#6366f1', borderRadius: 4 },
                            { label: '4Ps', data: MUNIS.map(m => DEMO[m]?.four_ps ?? 0), backgroundColor: '#28a745', borderRadius: 4 },
                            { label: 'Senior', data: MUNIS.map(m => DEMO[m]?.senior ?? 0), backgroundColor: '#8B5CF6', borderRadius: 4 }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { stacked: true, beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } }, x: { stacked: true, grid: { display: false } } }
                    }
                });
            }

            // ===== TOTAL BENEFICIARIES BAR =====
            if (document.getElementById('benefTotalChart')) {
                new Chart(document.getElementById('benefTotalChart'), {
                    type: 'bar',
                    data: {
                        labels: MUNIS,
                        datasets: [{ label: 'Total Beneficiaries', data: MUNIS.map(m => DEMO[m]?.beneficiaries ?? 0), backgroundColor: MUNIS.map(m => COLORS[m]), borderRadius: 8 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } }, x: { grid: { display: false } } }
                    }
                });
            }

            // ===== BENEFICIARIES TREND LINE =====
            if (document.getElementById('benefTrendChart')) {
                const bDatasets = MUNIS.map(muni => ({
                    label: muni,
                    data: YEARS.map(yr => BENEF_TREND[muni]?.[yr] ?? 0),
                    borderColor: COLORS[muni],
                    backgroundColor: COLORS[muni] + '22',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                }));
                new Chart(document.getElementById('benefTrendChart'), {
                    type: 'line',
                    data: { labels: YEARS, datasets: bDatasets },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' }, tooltip: { mode: 'index', intersect: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: v => v.toLocaleString() } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

        });
    </script>
    @auth
        <style>
            html,
            body {
                overscroll-behavior: none;
                margin: 0;
                padding: 0;
            }

            .back-dashboard-btn {
                position: fixed;
                bottom: 32px;
                left: 32px;
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 10px;
                background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
                color: white;
                border: none;
                border-radius: 50px;
                padding: 13px 22px 13px 18px;
                font-family: 'Inter', sans-serif;
                font-weight: 700;
                font-size: 0.88rem;
                box-shadow: 0 8px 28px rgba(44, 62, 143, 0.35);
                cursor: pointer;
                text-decoration: none;
                transition: all 0.3s ease;
                animation: slideInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
            }

            .back-dashboard-btn:hover {
                transform: translateY(-4px);
                box-shadow: 0 14px 36px rgba(44, 62, 143, 0.45);
                color: white;
            }

            .back-dashboard-btn .btn-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #FDB913;
                flex-shrink: 0;
                box-shadow: 0 0 0 3px rgba(253, 185, 19, 0.25);
            }

            .back-dashboard-btn .btn-label {
                letter-spacing: 0.02em;
            }

            .back-dashboard-btn .btn-arrow {
                width: 26px;
                height: 26px;
                border-radius: 50%;
                background: rgba(253, 185, 19, 0.22);
                color: #FDB913;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                font-weight: 900;
                flex-shrink: 0;
                transition: transform 0.25s ease;
            }

            .back-dashboard-btn:hover .btn-arrow {
                transform: translateX(-3px);
            }

            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
        @if(!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin())
            <a href="{{ route('user.dashboard') }}" class="back-dashboard-btn" title="Return to your dashboard">
                <span class="btn-arrow">&#8592;</span>
                <span class="btn-dot"></span>
                <span class="btn-label">My Dashboard</span>
            </a>
        @endif
    @endauth

    @auth
        @if(Auth::user()->isSuperAdmin())
            <style>
                html,
                body {
                    overscroll-behavior: none;
                    margin: 0;
                    padding: 0;
                }

                .admin-back-btn {
                    position: fixed;
                    bottom: 32px;
                    left: 32px;
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
                    color: #1A2A5C;
                    border: none;
                    border-radius: 50px;
                    padding: 13px 22px 13px 18px;
                    font-family: 'Inter', sans-serif;
                    font-weight: 800;
                    font-size: 0.88rem;
                    box-shadow: 0 8px 28px rgba(253, 185, 19, 0.45);
                    cursor: pointer;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    animation: adminSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
                }

                .admin-back-btn:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 14px 36px rgba(253, 185, 19, 0.55);
                    color: #1A2A5C;
                }

                .admin-back-btn .abtn-dot {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: #1A2A5C;
                    flex-shrink: 0;
                }

                .admin-back-btn .abtn-label {
                    letter-spacing: 0.02em;
                }

                .admin-back-btn .abtn-arrow {
                    width: 26px;
                    height: 26px;
                    border-radius: 50%;
                    background: rgba(26, 42, 92, 0.12);
                    color: #1A2A5C;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1rem;
                    font-weight: 900;
                    flex-shrink: 0;
                    transition: transform 0.25s ease;
                }

                .admin-back-btn:hover .abtn-arrow {
                    transform: translateX(-3px);
                }

                @keyframes adminSlideIn {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            </style>
            <a href="{{ route('superadmin.dashboard') }}" class="admin-back-btn" title="Return to Super Admin Dashboard">
                <span class="abtn-arrow">&#8592;</span>
                <span class="abtn-dot"></span>
                <span class="abtn-label">Super Admin Dashboard</span>
            </a>
        @elseif(Auth::user()->isAdmin())
            <style>
                html,
                body {
                    overscroll-behavior: none;
                    margin: 0;
                    padding: 0;
                }

                .admin-back-btn {
                    position: fixed;
                    bottom: 32px;
                    left: 32px;
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
                    color: #1A2A5C;
                    border: none;
                    border-radius: 50px;
                    padding: 13px 22px 13px 18px;
                    font-family: 'Inter', sans-serif;
                    font-weight: 800;
                    font-size: 0.88rem;
                    box-shadow: 0 8px 28px rgba(253, 185, 19, 0.45);
                    cursor: pointer;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    animation: adminSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
                }

                .admin-back-btn:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 14px 36px rgba(253, 185, 19, 0.55);
                    color: #1A2A5C;
                }

                .admin-back-btn .abtn-dot {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: #1A2A5C;
                    flex-shrink: 0;
                }

                .admin-back-btn .abtn-label {
                    letter-spacing: 0.02em;
                }

                .admin-back-btn .abtn-arrow {
                    width: 26px;
                    height: 26px;
                    border-radius: 50%;
                    background: rgba(26, 42, 92, 0.12);
                    color: #1A2A5C;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1rem;
                    font-weight: 900;
                    flex-shrink: 0;
                    transition: transform 0.25s ease;
                }

                .admin-back-btn:hover .abtn-arrow {
                    transform: translateX(-3px);
                }

                @keyframes adminSlideIn {
                    from {
                        opacity: 0;
                        transform: translateY(30px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            </style>
            <a href="{{ route('admin.dashboard') }}" class="admin-back-btn" title="Return to Admin Dashboard">
                <span class="abtn-arrow">&#8592;</span>
                <span class="abtn-dot"></span>
                <span class="abtn-label">Admin Dashboard</span>
            </a>
        @endif
    @endauth

    @include('components.chatbot-widget')

</body>

</html>