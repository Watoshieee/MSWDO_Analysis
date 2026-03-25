<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs – MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
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
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18);
            padding: 14px 0;
        }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:10px; }
        .nav-link {
            color: rgba(255,255,255,0.88) !important;
            font-weight: 600;
            transition: all 0.25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: 0.95rem;
        }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 700;
        }
        .user-info {
            color: white; display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size:0.92rem; font-weight:500;
        }
        .logout-btn {
            background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white;
            border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size:0.88rem; cursor:pointer;
        }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }
        .btn-login {
            background: white; color: var(--primary-blue); border: 2px solid white;
            border-radius: 30px; padding: 8px 25px; font-weight: 600;
            text-decoration: none; transition: all 0.3s ease;
        }
        .btn-login:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); transform: translateY(-2px); }
        .btn-register {
            background: transparent; border: 2px solid white; color: white;
            border-radius: 30px; padding: 8px 25px; font-weight: 600;
            text-decoration: none; transition: all 0.3s ease;
        }
        .btn-register:hover { background: var(--secondary-yellow); color: var(--primary-blue); transform: translateY(-2px); }

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
            position: absolute; top: -80px; right: -80px;
            width: 340px; height: 340px; border-radius: 50%;
            background: rgba(253,185,19,0.10);
        }
        .hero-banner::after {
            content: '';
            position: absolute; bottom: -90px; left: -60px;
            width: 260px; height: 260px; border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .hero-banner .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,0.35);
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
            width: 60px; height: 4px;
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
            position: absolute; bottom: 0; left: 0;
            width: 50px; height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
        }

        /* ===== VMG CARDS ===== */
        .vmg-card {
            background: white;
            border-radius: 20px;
            padding: 34px 28px;
            border: 1px solid var(--border-light);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .vmg-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }
        .vmg-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(44,62,143,0.12); border-color: var(--primary-blue-soft); }

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
        .vmg-card p { color: #475569; font-size: 0.94rem; line-height: 1.78; margin: 0; }

        /* ===== GOALS ===== */
        .goal-item {
            background: white;
            border-radius: 16px;
            padding: 22px 24px;
            border: 1px solid var(--border-light);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 16px;
            display: flex; align-items: flex-start; gap: 18px;
            transition: all 0.3s ease;
        }
        .goal-item:hover { transform: translateX(6px); box-shadow: 0 8px 25px rgba(44,62,143,0.1); border-color: var(--primary-blue-soft); }
        .goal-number {
            min-width: 44px; height: 44px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: white;
            font-weight: 800;
            font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .goal-content h6 { font-weight: 700; color: var(--primary-blue); margin-bottom: 5px; font-size: 1rem; }
        .goal-content p { color: #475569; margin: 0; font-size: 0.92rem; line-height: 1.65; }

        /* ===== PROGRAM CARDS ===== */
        .program-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-light);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            height: 100%;
            transition: all 0.35s ease;
            overflow: hidden;
        }
        .program-card:hover { transform: translateY(-6px); box-shadow: 0 18px 44px rgba(44,62,143,0.13); border-color: var(--primary-blue-soft); }
        .program-card-header {
            background: var(--primary-gradient);
            padding: 24px 26px 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .program-card-header::after {
            content: '';
            position: absolute; top: -30px; right: -30px;
            width: 100px; height: 100px; border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .program-abbr {
            display: inline-block;
            background: rgba(253,185,19,0.25);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,0.4);
            border-radius: 8px;
            padding: 3px 12px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .program-card-header h5 { font-weight: 700; margin: 0 0 4px 0; font-size: 1.05rem; line-height: 1.4; }
        .program-card-header small { opacity: 0.75; font-size: 0.82rem; }
        .program-card-body { padding: 22px 26px; }
        .program-card-body p { color: #475569; font-size: 0.93rem; line-height: 1.78; margin: 0; }

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
        .section-wrapper { padding: 54px 0; }
        .section-wrapper.alt { background: white; }

        /* ===== FOOTER ===== */
        .footer-strip {
            background: var(--primary-gradient);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 0.88rem;
            letter-spacing: 0.01em;
        }

        @media (max-width: 768px) {
            .hero-banner h1 { font-size: 1.85rem; }
            .section-title { font-size: 1.3rem; }
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
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
    <section class="section-wrapper alt">
        <div class="container">
            <h2 class="section-title">Strategic Goals</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="goal-item">
                        <div class="goal-number">01</div>
                        <div class="goal-content">
                            <h6>Poverty Reduction</h6>
                            <p>To alleviate poverty by providing sustainable livelihood opportunities and financial assistance to disadvantaged individuals and families.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">02</div>
                        <div class="goal-content">
                            <h6>Social Protection</h6>
                            <p>To safeguard the rights and welfare of children, women, senior citizens, persons with disabilities, and other vulnerable sectors.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">03</div>
                        <div class="goal-content">
                            <h6>Community Empowerment</h6>
                            <p>To strengthen community participation and promote self-reliance through capability-building programs and social development initiatives.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="goal-item">
                        <div class="goal-number">04</div>
                        <div class="goal-content">
                            <h6>Improved Access to Services</h6>
                            <p>To ensure that social welfare programs and services are accessible, efficient, and responsive to the needs of the public.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">05</div>
                        <div class="goal-content">
                            <h6>Disaster Preparedness &amp; Response</h6>
                            <p>To provide timely and effective assistance to individuals and families affected by disasters and emergencies.</p>
                        </div>
                    </div>
                    <div class="goal-item">
                        <div class="goal-number">06</div>
                        <div class="goal-content">
                            <h6>Good Governance</h6>
                            <p>To promote transparency, accountability, and professionalism in the delivery of social welfare services.</p>
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
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">4Ps</div>
                            <h5>Pantawid Pamilyang Pilipino Program</h5>
                            <small>Conditional Cash Transfer</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Poverty Reduction</span>
                            <p>A national poverty reduction initiative that provides conditional cash grants to qualified low-income households. The program improves health, nutrition, and education of children aged 0–18 by requiring regular school attendance, health check-ups, and family development sessions.</p>
                        </div>
                    </div>
                </div>

                <!-- Senior Citizen Pension -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">SCP</div>
                            <h5>Senior Citizen Pension</h5>
                            <small>Financial Assistance Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Social Protection</span>
                            <p>Provides financial assistance to indigent senior citizens aged 60 and above with no regular source of income or family support. The program supports daily subsistence and improves quality of life through monthly cash assistance.</p>
                        </div>
                    </div>
                </div>

                <!-- PWD Assistance -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">PWD</div>
                            <h5>PWD Assistance</h5>
                            <small>Persons with Disabilities Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Inclusion</span>
                            <p>Offers support services and financial aid to individuals with disabilities including medical assistance, provision of assistive devices, livelihood opportunities, and access to social services to promote inclusion and improve overall well-being.</p>
                        </div>
                    </div>
                </div>

                <!-- Solo Parent -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">SPA</div>
                            <h5>Solo Parent Assistance</h5>
                            <small>Solo Parents Welfare Act</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Family Support</span>
                            <p>Provides support to individuals solely responsible for the care and upbringing of their children. Services include financial assistance, counseling, livelihood programs, and access to benefits under the Solo Parents Welfare Act.</p>
                        </div>
                    </div>
                </div>

                <!-- AICS -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">AICS</div>
                            <h5>Assistance to Individuals in Crisis Situation</h5>
                            <small>Crisis Response Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Crisis Response</span>
                            <p>Provides immediate financial and material support to individuals and families facing emergencies. This includes assistance for medical needs, burial expenses, food, transportation, and other urgent concerns.</p>
                        </div>
                    </div>
                </div>

                <!-- SLP -->
                <div class="col-md-6 col-xl-4">
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">SLP</div>
                            <h5>Sustainable Livelihood Program</h5>
                            <small>Capacity-Building Initiative</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Livelihood</span>
                            <p>A capacity-building initiative that improves the socio-economic status of poor and vulnerable households. It provides skills training, capital assistance, and livelihood opportunities to help beneficiaries establish sustainable sources of income.</p>
                        </div>
                    </div>
                </div>

                <!-- ESA -->
                <div class="col-md-6 col-xl-4 mx-auto">
                    <div class="program-card">
                        <div class="program-card-header">
                            <div class="program-abbr">ESA</div>
                            <h5>Emergency Shelter Assistance</h5>
                            <small>Disaster Relief Program</small>
                        </div>
                        <div class="program-card-body">
                            <span class="category-tag">Disaster Response</span>
                            <p>Provides financial aid to families whose homes were partially or totally damaged due to natural or human-induced disasters. The program helps affected families repair or rebuild their houses and restore safe living conditions.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <div class="footer-strip">
        MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    @auth
    <style>
        .back-dashboard-btn {
            position: fixed; bottom: 32px; left: 32px; z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            color: white; border: none; border-radius: 50px;
            padding: 13px 22px 13px 18px;
            font-family: 'Inter', sans-serif; font-weight: 700; font-size: 0.88rem;
            box-shadow: 0 8px 28px rgba(44,62,143,0.35);
            cursor: pointer; text-decoration: none;
            transition: all 0.3s ease;
            animation: slideInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        .back-dashboard-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 36px rgba(44,62,143,0.45);
            color: white;
        }
        .back-dashboard-btn .btn-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #FDB913; flex-shrink: 0;
            box-shadow: 0 0 0 3px rgba(253,185,19,0.25);
        }
        .back-dashboard-btn .btn-label { letter-spacing: 0.02em; }
        .back-dashboard-btn .btn-arrow {
            width: 26px; height: 26px; border-radius: 50%;
            background: rgba(253,185,19,0.22); color: #FDB913;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; font-weight: 900; flex-shrink: 0;
            transition: transform 0.25s ease;
        }
        .back-dashboard-btn:hover .btn-arrow { transform: translateX(-3px); }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
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
        .admin-back-btn {
            position: fixed; bottom: 32px; left: 32px; z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            color: #1A2A5C; border: none; border-radius: 50px;
            padding: 13px 22px 13px 18px;
            font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.88rem;
            box-shadow: 0 8px 28px rgba(253,185,19,0.45);
            cursor: pointer; text-decoration: none;
            transition: all 0.3s ease;
            animation: adminSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        .admin-back-btn:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(253,185,19,0.55); color: #1A2A5C; }
        .admin-back-btn .abtn-dot { width: 8px; height: 8px; border-radius: 50%; background: #1A2A5C; flex-shrink: 0; }
        .admin-back-btn .abtn-label { letter-spacing: 0.02em; }
        .admin-back-btn .abtn-arrow { width: 26px; height: 26px; border-radius: 50%; background: rgba(26,42,92,0.12); color: #1A2A5C; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 900; flex-shrink: 0; transition: transform 0.25s ease; }
        .admin-back-btn:hover .abtn-arrow { transform: translateX(-3px); }
        @keyframes adminSlideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <a href="{{ route('superadmin.dashboard') }}" class="admin-back-btn" title="Return to Super Admin Dashboard">
        <span class="abtn-arrow">&#8592;</span>
        <span class="abtn-dot"></span>
        <span class="abtn-label">Super Admin Dashboard</span>
    </a>
    @elseif(Auth::user()->isAdmin())
    <style>
        .admin-back-btn {
            position: fixed; bottom: 32px; left: 32px; z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            color: #1A2A5C; border: none; border-radius: 50px;
            padding: 13px 22px 13px 18px;
            font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.88rem;
            box-shadow: 0 8px 28px rgba(253,185,19,0.45);
            cursor: pointer; text-decoration: none;
            transition: all 0.3s ease;
            animation: adminSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        .admin-back-btn:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(253,185,19,0.55); color: #1A2A5C; }
        .admin-back-btn .abtn-dot { width: 8px; height: 8px; border-radius: 50%; background: #1A2A5C; flex-shrink: 0; }
        .admin-back-btn .abtn-label { letter-spacing: 0.02em; }
        .admin-back-btn .abtn-arrow { width: 26px; height: 26px; border-radius: 50%; background: rgba(26,42,92,0.12); color: #1A2A5C; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 900; flex-shrink: 0; transition: transform 0.25s ease; }
        .admin-back-btn:hover .abtn-arrow { transform: translateX(-3px); }
        @keyframes adminSlideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
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