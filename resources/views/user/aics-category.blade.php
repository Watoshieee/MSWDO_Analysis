<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solo Parent – AICS Category | MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-yellow: #FDB913;
            --bg-light: #F0F4FF;
        }
        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; }

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.45rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; border-radius: 8px; padding: 10px 18px !important; font-size: 0.93rem; transition: all 0.25s; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-pill { color: white; background: rgba(255,255,255,0.1); padding: 8px 22px; border-radius: 40px; font-size: 0.88rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 5px 16px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* HERO */
        .page-hero { background: var(--primary-gradient); border-radius: 20px; padding: 36px 40px; color: white; margin-bottom: 36px; position: relative; overflow: hidden; }
        .page-hero::before { content: ''; position: absolute; top: -60px; right: -60px; width: 260px; height: 260px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 10px; }
        .page-hero h1 { font-size: 1.85rem; font-weight: 900; margin-bottom: 6px; }
        .hero-divider { width: 44px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 8px; }
        .page-hero p { opacity: 0.82; font-size: 0.93rem; margin: 0; }

        /* BACK LINK */
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-blue); font-weight: 700; font-size: 0.88rem; margin-bottom: 22px; transition: gap 0.2s; }
        .back-link:hover { color: var(--primary-blue); gap: 12px; }

        /* SECTION HEADING */
        .section-label { font-size: 0.72rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #6278b8; margin-bottom: 20px; }

        /* AICS CARDS */
        .aics-card { background: white; border-radius: 20px; border: 1px solid #C7D6F5; box-shadow: 0 8px 32px rgba(44,62,143,0.12); padding: 32px 28px; height: 100%; display: flex; flex-direction: column; transition: all 0.28s ease; cursor: pointer; position: relative; overflow: hidden; }
        .aics-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: var(--primary-gradient); border-radius: 20px 20px 0 0; }
        .aics-card:hover { transform: translateY(-5px); box-shadow: 0 18px 40px rgba(44,62,143,0.20); border-color: var(--primary-blue); }
        .aics-card.disabled { opacity: 0.55; cursor: not-allowed; pointer-events: none; }
        .aics-card.disabled::before { background: linear-gradient(135deg, #94a3b8, #64748b); }

        .card-icon { width: 60px; height: 60px; border-radius: 16px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 18px; flex-shrink: 0; }
        .card-num { font-size: 0.62rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(253,185,19,0.9); display: block; margin-bottom: 6px; }
        .card-title { font-size: 1.15rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 10px; line-height: 1.2; }
        .card-desc { font-size: 0.84rem; color: #64748b; line-height: 1.65; flex: 1; }
        .card-badge-unavail { display: inline-block; background: #F1F5F9; color: #94a3b8; border-radius: 20px; padding: 3px 12px; font-size: 0.7rem; font-weight: 700; margin-top: 14px; border: 1px solid #E2E8F0; }
        .card-arrow { display: inline-flex; align-items: center; gap: 6px; margin-top: 20px; font-size: 0.85rem; font-weight: 700; color: var(--primary-blue); transition: gap 0.2s; }
        .aics-card:hover .card-arrow { gap: 12px; }
        .aics-card-link { display: block; height: 100%; text-decoration: none; color: inherit; }

        /* FOOTER */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.8); text-align: center; padding: 20px; font-size: 0.84rem; margin-top: auto; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('user.programs') }}">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.announcements') }}">Announcements</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-pill">{{ $user->full_name }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div style="flex:1;">
    <div class="container mt-4 pb-5">

        <a href="{{ route('user.programs') }}" class="back-link">
            ← Back to Programs
        </a>

        <!-- HERO -->
        <div class="page-hero">
            <div class="hero-badge">Assistance in Crisis (AICS)</div>
            <h1>AICS – Choose a Category</h1>
            <div class="hero-divider"></div>
            <p>Assistance to Individuals in Crisis Situation (AICS) provides targeted financial aid to individuals and families facing emergencies. Select the type of assistance you need to begin your application.</p>
        </div>

        <p class="section-label">Available Categories</p>

        <div class="row g-4">

            <!-- MEDICAL ASSISTANCE -->
            <div class="col-md-4">
                <a href="{{ route('user.aics-medical') }}" class="aics-card-link">
                    <div class="aics-card">
                        <div class="card-icon">🏥</div>
                        <span class="card-num">01 — AICS</span>
                        <div class="card-title">Medical Assistance</div>
                        <div class="card-desc">Financial support for medical needs including hospital bills, medicines, laboratory tests, and other health-related expenses for individuals in crisis situations.</div>
                        <span class="card-arrow">View Requirements →</span>
                    </div>
                </a>
            </div>

            <!-- BURIAL ASSISTANCE -->
            <div class="col-md-4">
                <a href="{{ route('user.aics-burial') }}" class="aics-card-link">
                    <div class="aics-card">
                        <div class="card-icon">🕯️</div>
                        <span class="card-num">02 — AICS</span>
                        <div class="card-title">Burial Assistance</div>
                        <div class="card-desc">Financial aid to help individuals and families manage funeral and burial expenses for an immediate family member who has passed away.</div>
                        <span class="card-arrow">View Requirements →</span>
                    </div>
                </a>
            </div>

            <!-- EMERGENCY SHELTER (UNAVAILABLE) -->
            <div class="col-md-4">
                <div class="aics-card disabled">
                    <div class="card-icon">🏠</div>
                    <span class="card-num">03 — AICS</span>
                    <div class="card-title">Emergency Shelter Assistance</div>
                    <div class="card-desc">Support for solo parents who have lost or are at risk of losing their shelter due to disaster, calamity, or emergency situations.</div>
                    <span class="card-badge-unavail">⏳ Not yet available</span>
                </div>
            </div>

        </div>

        <div class="mt-4" style="background:rgba(44,62,143,0.06);border-radius:14px;padding:16px 20px;border-left:4px solid var(--primary-blue);font-size:0.85rem;color:#475569;">
            <strong style="color:var(--primary-blue);">Note:</strong> Emergency Shelter Assistance is currently unavailable while we prepare the necessary data and forms. Medical and Burial Assistance applications are open.
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
