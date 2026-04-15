<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs – MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --accent-red: #C41E24;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
        }

        body {
            background: #e2e8f0 !important;
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

        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
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
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
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
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
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
            padding: 64px 0 54px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: rgba(253, 185, 19, 0.10);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -90px;
            left: -60px;
            width: 260px;
            height: 260px;
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
            margin-bottom: 20px;
        }

        .hero-banner h1 {
            font-size: 2.7rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .hero-banner p {
            font-size: 1.05rem;
            opacity: 0.88;
            max-width: 680px;
            line-height: 1.75;
        }

        .hero-divider {
            width: 60px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 18px 0;
        }

        /* ===== SECTION TITLES ===== */
        .section-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 14px;
            margin-bottom: 30px;
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

        /* ===== VMG CARDS - NO HOVER ANIMATION ===== */
        .vmg-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 34px 28px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .vmg-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .vmg-label {
            display: inline-block;
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            border-radius: 8px;
            padding: 5px 14px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 18px;
            border-left: 3px solid var(--secondary-yellow);
        }

        .vmg-card h5 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 12px;
        }

        .vmg-card p {
            color: #475569;
            font-size: 0.94rem;
            line-height: 1.78;
            margin: 0;
        }

        /* ===== GOALS - NO HOVER ANIMATION ===== */
        .goal-item {
            background: #f8fafc;
            border-radius: 16px;
            padding: 22px 24px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .goal-number {
            min-width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: white;
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .goal-content h6 {
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 5px;
            font-size: 1rem;
        }

        .goal-content p {
            color: #475569;
            margin: 0;
            font-size: 0.92rem;
            line-height: 1.65;
        }

        /* ===== PROGRAM CARDS - NO HOVER ANIMATION ===== */
        .program-card {
            background: #f8fafc;
            border-radius: 20px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            height: 100%;
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .program-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(44, 62, 143, 0.15);
        }

        .card-hint {
            position: absolute;
            bottom: 15px;
            right: 15px;
            color: #3b82f6;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .program-card:hover .card-hint {
            color: #eab308;
            transform: scale(1.05);
        }

        .program-card-header {
            background: var(--primary-gradient);
            padding: 24px 26px 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .program-card-header::after {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
        }

        .program-abbr {
            display: inline-block;
            background: rgba(253, 185, 19, 0.25);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, 0.4);
            border-radius: 8px;
            padding: 3px 12px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .program-card-header h5 {
            font-weight: 700;
            margin: 0 0 4px 0;
            font-size: 1.05rem;
            line-height: 1.4;
        }

        .program-card-header small {
            opacity: 0.75;
            font-size: 0.82rem;
        }

        .program-card-body {
            padding: 22px 26px;
        }

        .program-card-body p {
            color: #475569;
            font-size: 0.93rem;
            line-height: 1.78;
            margin: 0;
        }

        .category-tag {
            display: inline-block;
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 0.78rem;
            font-weight: 600;
            margin-bottom: 14px;
        }

        /* ===== SECTIONS ===== */
        .section-wrapper {
            padding: 54px 0;
            background: #e2e8f0 !important;
        }

        .section-wrapper.alt {
            background: #f8fafc !important;
        }

        .section-wrapper .container,
        .section-wrapper.alt .container,
        .section-wrapper .row,
        .section-wrapper.alt .row,
        .section-wrapper [class*="col-"],
        .section-wrapper.alt [class*="col-"] {
            background: transparent !important;
        }

        /* ===== YEARLY ANALYSIS ===== */
        .yearly-section {
            padding: 60px 0;
            background: #e2e8f0 !important;
        }

        .yearly-section.alt {
            background: #f8fafc !important;
        }

        .yearly-section .container,
        .yearly-section.alt .container,
        .yearly-section .row,
        .yearly-section.alt .row,
        .yearly-section [class*="col-"],
        .yearly-section.alt [class*="col-"] {
            background: transparent !important;
        }

        /* Force all child divs in yearly sections to be transparent */
        .yearly-section div:not(.chart-card):not(.stat-pill):not(.goal-number):not(.vmg-label):not(.program-card):not(.program-card-header),
        .yearly-section.alt div:not(.chart-card):not(.stat-pill):not(.goal-number):not(.vmg-label):not(.program-card):not(.program-card-header) {
            background-color: transparent !important;
        }

        /* Ultra specific override for yearly-section alt */
        .yearly-section.alt * {
            background-color: inherit !important;
        }

        .yearly-section.alt .chart-card {
            background: #f8fafc !important;
        }

        .yearly-section.alt .stat-pill {
            background: #f8fafc !important;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 13px;
            margin-bottom: 8px;
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

        .section-sub {
            color: #94a3b8;
            font-size: .88rem;
            margin-bottom: 32px;
        }

        .chart-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .04);
            border: 1px solid #cbd5e1;
            position: relative;
            overflow: hidden;
            margin-bottom: 28px;
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
            margin-bottom: 4px;
            font-size: 1.02rem;
        }

        .chart-card p.csub {
            color: #94a3b8;
            font-size: .82rem;
            margin-bottom: 18px;
        }

        .chart-wrap {
            position: relative;
            height: 300px;
        }

        .year-pill {
            display: inline-block;
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: .78rem;
            font-weight: 700;
            margin: 2px;
            border: 1px solid rgba(44, 62, 143, .15);
        }

        .stat-pill {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 16px 22px;
            text-align: center;
        }

        .stat-pill .sp-val {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--primary-blue);
            line-height: 1.1;
        }

        .stat-pill .sp-lbl {
            font-size: .75rem;
            color: #64748b;
            font-weight: 600;
            margin-top: 3px;
        }

        /* ===== FOOTER ===== */
        .footer-strip {
            background: var(--primary-gradient);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 0.88rem;
            letter-spacing: 0.01em;
        }

        .border-light {
            border-color: var(--border-light) !important;
        }

        /* Force transparent backgrounds on all Bootstrap grid elements */
        .container,
        .row,
        [class*="col-"] {
            background-color: transparent !important;
        }

        @media (max-width: 768px) {
            .hero-banner h1 {
                font-size: 1.85rem;
            }

            .section-title {
                font-size: 1.3rem;
            }
        }
        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0;
            padding: 24px 28px;
        }

        .modal-header .modal-title {
            font-weight: 800;
            font-size: 1.3rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-body {
            padding: 28px;
        }

        .req-section {
            margin-bottom: 24px;
        }

        .req-section h6 {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .req-list, .elig-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .req-list li {
            padding: 10px 14px;
            background: #f8fafc;
            border-left: 3px solid var(--secondary-yellow);
            margin-bottom: 8px;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #475569;
        }

        .elig-list li {
            padding: 10px 14px;
            background: #EEF2FF;
            border-left: 3px solid var(--primary-blue);
            margin-bottom: 8px;
            border-radius: 6px;
            font-size: 0.9rem;
            color: #475569;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .elig-list li::before {
            content: '✓';
            color: #16a34a;
            font-weight: 800;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .program-badge {
            display: inline-block;
            background: rgba(253, 185, 19, 0.15);
            color: var(--secondary-yellow);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        /* AICS Tab Styles */
        .aics-tab-btn {
            flex: 1;
            padding: 8px 16px;
            border: 2px solid #E2E8F0;
            background: white;
            color: #64748b;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .aics-tab-btn.active {
            background: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .aics-tab-btn:hover:not(.active) {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        .aics-tab-content {
            display: none;
        }

        .aics-tab-content.active {
            display: block;
        }
    </style>
</head>

<body>

    <!-- ===== NAVBAR – role-aware ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
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
                            <li class="nav-item"><a class="nav-link active" href="/analysis">Programs</a></li>
                            <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
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
                        <li class="nav-item"><a class="nav-link active" href="/analysis">Programs</a></li>
                        <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
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
            <div class="hero-badge">About Us</div>
            <h1>Municipal Social Welfare<br>&amp; Development Office</h1>
            <div class="hero-divider"></div>
            <p>
                MSWDO is the local government office responsible for delivering social welfare services
                and programs to support individuals, families, and communities—especially those in need—through
                assistance, protection, and development initiatives.
            </p>
        </div>
    </section>

    <!-- ===== VISION / MISSION / GOALS ===== -->
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">Vision, Mission &amp; Goals</h2>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="vmg-card">
                        <div class="vmg-label">Vision</div>
                        <h5>What We Aspire To Be</h5>
                        <p>
                            A responsive and compassionate social welfare institution committed to empowering
                            individuals, strengthening families, and building resilient communities where all
                            citizens enjoy a better quality of life.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="vmg-card">
                        <div class="vmg-label">Mission</div>
                        <h5>How We Serve</h5>
                        <p>
                            To deliver inclusive, accessible, and quality social welfare and development
                            services through responsive programs, partnerships, and community participation,
                            ensuring the protection and empowerment of disadvantaged and vulnerable sectors.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="vmg-card">
                        <div class="vmg-label">Goals</div>
                        <h5>Our Strategic Direction</h5>
                        <p>
                            We are guided by six strategic goals: poverty reduction, social protection,
                            community empowerment, improved access to services, disaster preparedness &amp;
                            response, and good governance — all focused on the well-being of every citizen.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== STRATEGIC GOALS ===== -->
    <section class="section-wrapper alt" style="background: #e2e8f0 !important;">
        <div class="container" style="background: transparent !important;">
            <h2 class="section-title">Strategic Goals</h2>
            <div class="row" style="background: transparent !important;">
                <div class="col-lg-6" style="background: transparent !important;">
                    <div class="goal-item">
                        <div class="goal-number">01</div>
                        <div class="goal-content">
                            <h6>Poverty Reduction</h6>
                            <p>To alleviate poverty by providing sustainable livelihood opportunities and financial
                                assistance to disadvantaged individuals and families.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">02</div>
                        <div class="goal-content">
                            <h6>Social Protection</h6>
                            <p>To safeguard the rights and welfare of children, women, senior citizens, persons with
                                disabilities, and other vulnerable sectors.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">03</div>
                        <div class="goal-content">
                            <h6>Community Empowerment</h6>
                            <p>To strengthen community participation and promote self-reliance through
                                capability-building programs and social development initiatives.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" style="background: transparent !important;">
                    <div class="goal-item">
                        <div class="goal-number">04</div>
                        <div class="goal-content">
                            <h6>Improved Access to Services</h6>
                            <p>To ensure that social welfare programs and services are accessible, efficient, and
                                responsive to the needs of the public.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">05</div>
                        <div class="goal-content">
                            <h6>Disaster Preparedness &amp; Response</h6>
                            <p>To provide timely and effective assistance to individuals and families affected by
                                disasters and emergencies.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">06</div>
                        <div class="goal-content">
                            <h6>Good Governance</h6>
                            <p>To promote transparency, accountability, and professionalism in the delivery of social
                                welfare services.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROGRAM DESCRIPTIONS ===== -->
    <section class="section-wrapper">
        <div class="container">
            <h2 class="section-title">Program Descriptions</h2>
            <div class="row g-4">

                <!-- 4Ps -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card" onclick="showProgramModal('4ps')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">4Ps</div>
                            <h5>Pantawid Pamilyang Pilipino Program</h5>
                            <small>Conditional Cash Transfer</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Poverty Reduction</span>
                            <p>A national poverty reduction initiative that provides conditional cash grants to
                                qualified low-income households. The program improves health, nutrition, and education
                                of children aged 0–18 by requiring regular school attendance, health check-ups, and
                                family development sessions.</p>
                        </div>
                    </div>
                </div>

                <!-- Senior Citizen Pension -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card" onclick="showProgramModal('scp')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">SCP</div>
                            <h5>Senior Citizen Pension</h5>
                            <small>Financial Assistance Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Social Protection</span>
                            <p>Provides financial assistance to indigent senior citizens aged 60 and above with no
                                regular source of income or family support. The program supports daily subsistence and
                                improves quality of life through monthly cash assistance.</p>
                        </div>
                    </div>
                </div>

                <!-- PWD Assistance -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card" onclick="showProgramModal('pwd')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">PWD</div>
                            <h5>PWD Assistance</h5>
                            <small>Persons with Disabilities Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Inclusion</span>
                            <p>Offers support services and financial aid to individuals with disabilities including
                                medical assistance, provision of assistive devices, livelihood opportunities, and access
                                to social services to promote inclusion and improve overall well-being.</p>
                        </div>
                    </div>
                </div>

                <!-- Solo Parent -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card" onclick="showProgramModal('solo')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">SPA</div>
                            <h5>Solo Parent Assistance</h5>
                            <small>Solo Parents Welfare Act</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Family Support</span>
                            <p>Provides support to individuals solely responsible for the care and upbringing of their
                                children. Services include financial assistance, counseling, livelihood programs, and
                                access to benefits under the Solo Parents Welfare Act.</p>
                        </div>
                    </div>
                </div>

                <!-- AICS -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card" onclick="showProgramModal('aics')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">AICS</div>
                            <h5>Assistance to Individuals in Crisis Situation</h5>
                            <small>Crisis Response Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Crisis Response</span>
                            <p>Provides immediate financial and material support to individuals and families facing
                                emergencies. This includes assistance for medical needs, burial expenses, food,
                                transportation, and other urgent concerns.</p>
                        </div>
                    </div>
                </div>

                <!-- SLP -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card" onclick="showProgramModal('slp')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">SLP</div>
                            <h5>Sustainable Livelihood Program</h5>
                            <small>Capacity-Building Initiative</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Livelihood</span>
                            <p>A capacity-building initiative that improves the socio-economic status of poor and
                                vulnerable households. It provides skills training, capital assistance, and livelihood
                                opportunities to help beneficiaries establish sustainable sources of income.</p>
                        </div>
                    </div>
                </div>

                <!-- ESA -->
                <div class="col-md-6 col-xl-4 mx-auto">
                    <div class="program-card" onclick="showProgramModal('esa')">
                        <div class="card-hint">View Details →</div>
                        <div class="program-card-header">
                            <div class="program-abbr">ESA</div>
                            <h5>Emergency Shelter Assistance</h5>
                            <small>Disaster Relief Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Disaster Response</span>
                            <p>Provides financial aid to families whose homes were partially or totally damaged due to
                                natural or human-induced disasters. The program helps affected families repair or
                                rebuild their houses and restore safe living conditions.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if(isset($allYears) && count($allYears))
        <!-- ===== YEARLY ANALYSIS ===== -->
        <section class="yearly-section">
            <div class="container">
                <h2 class="section-title">Yearly Beneficiary Analysis</h2>
                <p class="section-sub">How total program beneficiaries changed year-over-year across all three
                    municipalities.</p>

                <!-- Quick year pills -->
                <div class="mb-4">
                    @foreach($allYears as $yr)
                        <span class="year-pill">{{ $yr }}</span>
                    @endforeach
                </div>

                <!-- Row: line chart + mini stat pills -->
                <div class="row g-4 mb-2">
                    <div class="col-lg-8">
                        <div class="chart-card">
                            <h5>Total Beneficiaries Over Time</h5>
                            <p class="csub">Combined beneficiary count per municipality per year</p>
                            <div class="chart-wrap"><canvas id="yearlyLineChart"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-card h-100" style="display:flex;flex-direction:column;justify-content:center;">
                            <h5>By Municipality Summary</h5>
                            <p class="csub">Total across all recorded years</p>
                            <div class="d-flex flex-column gap-3 mt-2">
                                @php
                                    $muniColors = ['Magdalena' => '#2C3E8F', 'Liliw' => '#FDB913', 'Majayjay' => '#28a745'];
                                @endphp
                                @foreach($coreNames as $mn)
                                    <div class="stat-pill">
                                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                                            <span
                                                style="width:10px;height:10px;border-radius:50%;background:{{ $muniColors[$mn] ?? '#333' }};display:inline-block;"></span>
                                            <span style="font-weight:700;font-size:.88rem;color:#334155;">{{ $mn }}</span>
                                        </div>
                                        <div class="sp-val">{{ number_format(array_sum($yearlyByMuni[$mn])) }}</div>
                                        <div class="sp-lbl">Total Beneficiaries</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="yearly-section alt" style="background: #e2e8f0 !important;">
            <div class="container" style="background: transparent !important;">
                <h2 class="section-title">Program Trends by Year</h2>
                <p class="section-sub">Breakdown of total beneficiaries per social welfare program across all years and
                    municipalities.</p>
                <div class="row g-4" style="background: transparent !important;">
                    <div class="col-lg-7" style="background: transparent !important;">
                        <div class="chart-card">
                            <h5>Beneficiaries per Program Type (Yearly)</h5>
                            <p class="csub">Stacked view of all program types per year</p>
                            <div class="chart-wrap" style="height:340px;"><canvas id="yearlyProgramChart"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-5" style="background: transparent !important;">
                        <div class="chart-card">
                            <h5>Per-Program Totals</h5>
                            <p class="csub">Aggregated across all years &amp; municipalities</p>
                            <div class="chart-wrap" style="height:340px;"><canvas id="programDonutChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @if(isset($summaryYears) && count($summaryYears))
            <section class="yearly-section">
                <div class="container">
                    <h2 class="section-title">Population Growth Trend</h2>
                    <p class="section-sub">Recorded population per municipality based on yearly summary data.</p>
                    <div class="chart-card">
                        <h5>Population Over the Years</h5>
                        <p class="csub">Municipality-level population tracked from official yearly summary records</p>
                        <div class="chart-wrap" style="height:320px;"><canvas id="yearlyPopChart"></canvas></div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- ===== FOOTER ===== -->
    <div class="footer-strip">
        MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <!-- Program Modal -->
    <div class="modal fade" id="programModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const programData = {
            '4ps': {
                title: 'Pantawid Pamilyang Pilipino Program (4Ps)',
                badge: 'Conditional Cash Transfer',
                description: 'A national poverty reduction initiative that provides conditional cash grants to qualified low-income households. The program improves health, nutrition, and education of children aged 0–18 by requiring regular school attendance, health check-ups, and family development sessions.',
                eligibility: [
                    'Filipino citizen aged 60 or older',
                    'Indigent — no regular income or pension',
                    'Frail, sickly, or with disability'
                ],
                requirements: [
                    'Birth Certificates (all family members)',
                    'School IDs / Report Cards',
                    'Barangay Certificate',
                    'Valid ID',
                    '1x1 Pictures',
                    'Health Records (0-5 yrs old)'
                ]
            },
            'scp': {
                title: 'Senior Citizen Pension',
                badge: 'Financial Assistance Program',
                description: 'Monthly social pension for indigent senior citizens 60 years and above who are frail, sickly, or with disabilities, and have no regular source of income.',
                eligibility: [
                    'Filipino citizen aged 60 or older',
                    'Indigent — no regular income or pension',
                    'Frail, sickly, or with disability'
                ],
                requirements: [
                    'OSCA Application Form',
                    'ID Photos',
                    'Birth Certificate / Valid ID',
                    'Barangay Certificate (if needed)',
                    "Voter's Certification (if needed)",
                    'Authorization Letter (if applicable)'
                ]
            },
            'pwd': {
                title: 'PWD Assistance',
                badge: 'Persons with Disabilities Program',
                description: 'Financial and social support services for persons with disability (PWD), including ID issuance for discounts, medical aid, and livelihood opportunities.',
                eligibility: [
                    'Filipino citizen with recognized disability',
                    'Resident of Majayjay, Liliw, or Magdalena',
                    'With medical certificate of disability'
                ],
                applicationSteps: [
                    '<strong>Visit Official PWD Website:</strong> pwd.doh.gov.ph',
                    '<strong>Download Application Form:</strong> Get the PRPWD form from the website',
                    '<strong>Fill Out & Submit:</strong> Complete form + Certificate of Disability',
                    '<strong>Submit ID Pictures:</strong> Two (2) 1×1 photos to MSWDO',
                    '<strong>Wait for ID:</strong> MSWDO will process your physical PWD ID'
                ],
                whatToBring: [
                    'Completed PRPWD Application Form',
                    'Certificate of Disability (original + 1 photocopy)',
                    'Two (2) recent 1×1 ID pictures',
                    'Valid government-issued ID'
                ],
                officeInfo: 'MSWDO Office, Municipal Hall Ground Floor · Mon-Fri 8AM-5PM'
            },
            'solo': {
                title: 'Solo Parent Assistance',
                badge: 'Solo Parents Welfare Act',
                description: 'Assistance and special privileges for solo parents raising children independently — including livelihood support, flexible work arrangements, and educational benefits.',
                eligibility: [
                    'Solo parent with child/ren below 18',
                    'Annual income below ₱250,000',
                    'With valid Solo Parent ID'
                ],
                applicationSteps: [
                    '<strong>Schedule Appointment:</strong> Apply for face-to-face or online interview',
                    '<strong>Wait for Confirmation:</strong> Admin will send notification via email',
                    '<strong>Attend Interview:</strong> At MSWDO office or online',
                    '<strong>Eligibility Notification:</strong> Receive result via email',
                    '<strong>Requirements Submission:</strong> List will be sent if approved',
                    '<strong>Submit Documents:</strong> Bring hard copies to MSWDO',
                    '<strong>ID Processing:</strong> Wait for notification when ready'
                ],
                whatToBring: [
                    'Application Form',
                    'Cedula',
                    "Voter's ID",
                    'Birth Certificate (minor)',
                    'Barangay Certification'
                ],
                officeInfo: 'MSWDO Office, Municipal Hall Ground Floor · Mon-Fri 8AM-5PM · Interview options: Face-to-face or Online'
            },
            'aics': {
                title: 'Assistance to Individuals in Crisis Situation (AICS)',
                badge: 'Crisis Response Program',
                description: 'Emergency financial aid for individuals and families facing crisis situations — covers medical, burial, food, transportation, and educational assistance.',
                eligibility: [
                    'Filipino in crisis / emergency situation',
                    'Residing in the covered municipalities',
                    'Below poverty threshold income'
                ],
                hasTabs: true,
                medicalRequirements: [
                    'Certificate of Indigency (Original)',
                    'Medical Certificate / Medical Abstract',
                    'Hospital Bills / Prescriptions',
                    'Photocopy of ID (patient & claimant)',
                    'Marriage Contract (if spouse)',
                    'Birth Certificate (if parent/children)',
                    'Authorization Letter (if applicable)'
                ],
                burialRequirements: [
                    'Certificate of Indigency (Original)',
                    'Death Certificate (Original)',
                    'Funeral Contract / Billing Statement',
                    'Marriage Contract (if spouse)',
                    'Birth Certificate (if parent/children)',
                    'Photocopy of ID (deceased & claimant)',
                    'Authorization Letter (if applicable)'
                ]
            },
            'slp': {
                title: 'Sustainable Livelihood Program (SLP)',
                badge: 'Capacity-Building Initiative',
                description: 'A capacity-building initiative that improves the socio-economic status of poor and vulnerable households. It provides skills training, capital assistance, and livelihood opportunities to help beneficiaries establish sustainable sources of income.',
                eligibility: [
                    'Filipino citizen',
                    'Indigent or low-income household',
                    'Willing to participate in training programs'
                ],
                requirements: [
                    'Valid ID (any government-issued ID)',
                    'Certificate of Indigency from Barangay',
                    'Barangay Clearance',
                    'Proof of residence',
                    'Business plan or livelihood proposal',
                    'Proof of income or ITR'
                ]
            },
            'esa': {
                title: 'Emergency Shelter Assistance (ESA)',
                badge: 'Disaster Relief Program',
                description: 'Provides financial aid to families whose homes were partially or totally damaged due to natural or human-induced disasters. The program helps affected families repair or rebuild their houses and restore safe living conditions.',
                eligibility: [
                    'Filipino citizen',
                    'Affected by natural or human-induced disaster',
                    'House partially or totally damaged'
                ],
                requirements: [
                    'Valid ID (any government-issued ID)',
                    'Barangay Certification of damage',
                    'Certificate of Indigency from Barangay',
                    'Photos of damaged house',
                    'Proof of ownership or occupancy',
                    'Incident report from Barangay or MDRRMO'
                ]
            }
        };

        function showProgramModal(programKey) {
            const data = programData[programKey];
            if (!data) return;

            document.getElementById('modalTitle').textContent = data.title;
            
            let reqHtml = '<div class="program-badge">' + data.badge + '</div>';
            reqHtml += '<p style="color: #475569; line-height: 1.7; margin-bottom: 24px;">' + data.description + '</p>';
            
            // Check if AICS with tabs
            if (data.hasTabs) {
                // AICS with tabs layout
                reqHtml += '<div class="row g-3">';
                reqHtml += '<div class="col-md-6">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>✓ Key Eligibility</h6>';
                reqHtml += '<ul class="elig-list">';
                data.eligibility.forEach(elig => {
                    reqHtml += '<li>' + elig + '</li>';
                });
                reqHtml += '</ul></div></div>';
                
                reqHtml += '<div class="col-md-6">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>📋 Required Documents</h6>';
                reqHtml += '<div style="display: flex; gap: 8px; margin-bottom: 12px;">';
                reqHtml += '<button class="aics-tab-btn active" onclick="switchAicsTab(event, \'medical\')">Medical Assistance</button>';
                reqHtml += '<button class="aics-tab-btn" onclick="switchAicsTab(event, \'burial\')">Burial Assistance</button>';
                reqHtml += '</div>';
                reqHtml += '<div id="aics-medical" class="aics-tab-content active">';
                reqHtml += '<ul class="req-list">';
                data.medicalRequirements.forEach(req => {
                    reqHtml += '<li>' + req + '</li>';
                });
                reqHtml += '</ul></div>';
                reqHtml += '<div id="aics-burial" class="aics-tab-content">';
                reqHtml += '<ul class="req-list">';
                data.burialRequirements.forEach(req => {
                    reqHtml += '<li>' + req + '</li>';
                });
                reqHtml += '</ul></div>';
                reqHtml += '</div></div></div>';
            } else if (data.applicationSteps) {
                // PWD & Solo Parent: Row 1 (Eligibility + What to Bring), Row 2 (Application Process full width)
                reqHtml += '<div class="row g-3">';
                
                // Left: Key Eligibility
                reqHtml += '<div class="col-md-6">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>✓ Key Eligibility</h6>';
                reqHtml += '<ul class="elig-list">';
                data.eligibility.forEach(elig => {
                    reqHtml += '<li>' + elig + '</li>';
                });
                reqHtml += '</ul></div></div>';
                
                // Right: What to Bring
                reqHtml += '<div class="col-md-6">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>📦 What to Bring</h6>';
                reqHtml += '<ul class="req-list">';
                data.whatToBring.forEach(item => {
                    reqHtml += '<li>' + item + '</li>';
                });
                reqHtml += '</ul></div></div>';
                
                reqHtml += '</div>'; // close row
                
                // Application Process - full width below
                reqHtml += '<div class="row g-3" style="margin-top: 20px; border-top: 1px solid #E2E8F0; padding-top: 20px;">';
                reqHtml += '<div class="col-12">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>📝 Application Process</h6>';
                reqHtml += '<ul class="req-list" style="font-size: 0.85rem;">';
                data.applicationSteps.forEach((step, index) => {
                    reqHtml += '<li style="padding: 8px 12px;">' + step + '</li>';
                });
                reqHtml += '</ul></div></div></div>';
            } else {
                // Regular programs (4Ps, SCP, SLP, ESA): 2 columns (Eligibility + Requirements)
                reqHtml += '<div class="row g-3">';
                
                // Left column: Key Eligibility
                reqHtml += '<div class="col-md-6">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>✓ Key Eligibility</h6>';
                reqHtml += '<ul class="elig-list">';
                data.eligibility.forEach(elig => {
                    reqHtml += '<li>' + elig + '</li>';
                });
                reqHtml += '</ul></div>';
                reqHtml += '</div>';
                
                // Right column: Required Documents
                reqHtml += '<div class="col-md-6">';
                reqHtml += '<div class="req-section">';
                reqHtml += '<h6>📋 Required Documents</h6>';
                reqHtml += '<ul class="req-list">';
                data.requirements.forEach(req => {
                    reqHtml += '<li>' + req + '</li>';
                });
                reqHtml += '</ul>';
                reqHtml += '</div>';
                reqHtml += '</div>';
                
                reqHtml += '</div>'; // close row
            }
            
            if (data.officeInfo) {
                reqHtml += '<div style="background: #FFF3D6; border-left: 3px solid var(--secondary-yellow); border-radius: 8px; padding: 12px 16px; margin-top: 16px; font-size: 0.88rem; color: #856404;">';
                reqHtml += '<strong>🏢 Office Info:</strong> ' + data.officeInfo;
                reqHtml += '</div>';
            }
            
            document.getElementById('modalBody').innerHTML = reqHtml;
            
            new bootstrap.Modal(document.getElementById('programModal')).show();
        }

        function switchAicsTab(event, tabType) {
            document.querySelectorAll('.aics-tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            document.querySelectorAll('.aics-tab-content').forEach(content => content.classList.remove('active'));
            document.getElementById('aics-' + tabType).classList.add('active');
        }
    </script>

    @if(isset($allYears) && count($allYears))
        <script>
            (function () {
                const COLORS = { Magdalena: '#2C3E8F', Liliw: '#FDB913', Majayjay: '#28a745' };
                const PROG_COLORS = ['#2C3E8F', '#FDB913', '#28a745', '#C41E24', '#0891b2', '#d97706', '#7c3aed'];
                const allYears = @json($allYears);
                const yearlyByMuni = @json($yearlyByMuni);
                const yearlyByProg = @json($yearlyByProgram);
                const summaryYears = @json($summaryYears ?? []);
                const yearlyPop = @json($yearlyPopulation ?? []);
                const coreNames = @json($coreNames);
                const programTypes = @json($programTypes);

                // ── 1. Total beneficiaries over time (line) ───────────────────
                new Chart(document.getElementById('yearlyLineChart'), {
                    type: 'line',
                    data: {
                        labels: allYears,
                        datasets: coreNames.map(mn => ({
                            label: mn,
                            data: allYears.map(yr => (yearlyByMuni[mn] && yearlyByMuni[mn][yr]) ? yearlyByMuni[mn][yr] : 0),
                            borderColor: COLORS[mn],
                            backgroundColor: COLORS[mn] + '22',
                            fill: true, tension: 0.4, pointRadius: 5,
                            borderWidth: 3, pointHoverRadius: 7
                        }))
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // ── 2. Per-program yearly bar chart ───────────────────────────
                new Chart(document.getElementById('yearlyProgramChart'), {
                    type: 'bar',
                    data: {
                        labels: allYears,
                        datasets: programTypes.map((pt, i) => ({
                            label: pt.replace(/_/g, ' '),
                            data: allYears.map(yr => (yearlyByProg[pt] && yearlyByProg[pt][yr]) ? yearlyByProg[pt][yr] : 0),
                            backgroundColor: PROG_COLORS[i % PROG_COLORS.length],
                            borderRadius: 4
                        }))
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            x: { stacked: true, grid: { display: false } },
                            y: { stacked: true, beginAtZero: true, ticks: { callback: v => v.toLocaleString() } }
                        }
                    }
                });

                // ── 3. Program totals donut ───────────────────────────────────
                const progTotals = programTypes.map(pt => Object.values(yearlyByProg[pt] || {}).reduce((a, b) => a + b, 0));
                new Chart(document.getElementById('programDonutChart'), {
                    type: 'doughnut',
                    data: {
                        labels: programTypes.map(p => p.replace(/_/g, ' ')),
                        datasets: [{ data: progTotals, backgroundColor: PROG_COLORS, borderWidth: 0, hoverOffset: 10 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'right', labels: { font: { size: 12 } } } },
                        cutout: '60%'
                    }
                });

                // ── 4. Population growth (only if summary data exists) ────────
                if (summaryYears.length && document.getElementById('yearlyPopChart')) {
                    new Chart(document.getElementById('yearlyPopChart'), {
                        type: 'line',
                        data: {
                            labels: summaryYears,
                            datasets: coreNames.map(mn => ({
                                label: mn,
                                data: summaryYears.map(yr => (yearlyPop[mn] && yearlyPop[mn][yr]) ? yearlyPop[mn][yr] : 0),
                                borderColor: COLORS[mn],
                                backgroundColor: COLORS[mn] + '18',
                                fill: true, tension: 0.4,
                                pointRadius: 5, pointHoverRadius: 8, borderWidth: 3
                            }))
                        },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: { legend: { position: 'top' } },
                            scales: {
                                y: { beginAtZero: false, ticks: { callback: v => v.toLocaleString() } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
            })();
        </script>
    @endif

    @auth
        <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

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
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

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
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

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

</body>

</html>