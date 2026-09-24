<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="Minor Traveling Abroad (MTA) - MSWDO" data-tl="Minor Traveling Abroad (MTA) - MSWDO">Minor Traveling Abroad (MTA) - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: {{ $primaryColor ?? '#2C3E8F' }};
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: {{ $secondaryColor ?? '#FDB913' }};
            --secondary-yellow-light: #FFF3D6;
            --primary-gradient: linear-gradient(135deg, {{ $primaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, {{ $secondaryColor ?? '#FDB913' }} 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }

        body {
            background: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        a { text-decoration: none; }

        .top-bar {
            background: var(--primary-gradient);
            padding: 14px 0;
            box-shadow: 0 4px 20px rgba(44, 62, 143, .2);
        }

        .top-bar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-weight: 800;
            font-size: 1.45rem;
        }

        .brand img { width: 34px; height: 34px; object-fit: contain; }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .12);
            border: 2px solid rgba(255, 255, 255, .4);
            color: white;
            border-radius: 30px;
            padding: 8px 22px;
            font-weight: 700;
            font-size: .88rem;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
        }

        .back-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 24px 0 20px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -90px;
            right: -90px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(253, 185, 19, .10);
        }

        .hero-inner { position: relative; z-index: 2; }

        .hero-badge {
            display: inline-block;
            background: rgba(253, 185, 19, .18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, .35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .hero-banner h1 {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 6px;
            line-height: 1.15;
        }

        .hero-divider {
            width: 50px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 12px 0;
        }

        .hero-banner p.hero-sub {
            opacity: .85;
            font-size: .92rem;
            margin: 0;
            max-width: 900px;
            line-height: 1.65;
        }

        .lang-toggle {
            display: inline-flex;
            border-radius: 30px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, .4);
        }

        .lang-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, .7);
            font-weight: 700;
            font-size: .82rem;
            padding: 8px 20px;
            cursor: pointer;
            transition: all .2s;
            letter-spacing: .05em;
        }

        .lang-btn.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
        }

        .lang-btn:hover:not(.active) {
            background: rgba(255, 255, 255, .15);
            color: white;
        }

        #mta-wizard-view { width: 100%; }

        .wizard-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-light);
            box-shadow: 0 8px 32px rgba(44, 62, 143, .08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .wizard-top {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 32px 16px;
        }

        .wizard-top h2 { font-size: 1.3rem; font-weight: 900; margin: 0 0 4px; }
        .wizard-top p { margin: 0; font-size: .86rem; opacity: .82; }

        .wizard-progress-track {
            height: 6px;
            background: rgba(255, 255, 255, .18);
            border-radius: 3px;
            margin-top: 14px;
            overflow: hidden;
        }

        .wizard-progress-fill {
            height: 100%;
            background: var(--secondary-yellow);
            border-radius: 3px;
            transition: width .4s ease;
            width: 33.33%;
        }

        .wizard-steps {
            display: flex;
            align-items: flex-start;
            justify-content: stretch;
            gap: 8px;
            padding: 18px 32px 14px;
            overflow-x: auto;
            scrollbar-width: none;
            background: #fafbfc;
            border-bottom: 1px solid var(--border-light);
        }

        .wizard-steps::-webkit-scrollbar { display: none; }

        .wiz-step {
            flex: 1;
            min-width: 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            background: transparent;
            padding: 0 6px;
        }

        .wiz-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--border-light);
            background: white;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: .88rem;
            transition: all .3s;
            flex-shrink: 0;
        }

        .wiz-step.active .wiz-step-circle {
            background: var(--primary-gradient);
            border-color: var(--primary-blue);
            color: white;
            box-shadow: 0 4px 14px rgba(44, 62, 143, .28);
        }

        .wiz-step.done .wiz-step-circle {
            background: #dcfce7;
            border-color: #22c55e;
            color: #15803d;
        }

        .wiz-step-label {
            font-size: .72rem;
            font-weight: 700;
            color: #64748b;
            text-align: center;
            line-height: 1.3;
            max-width: 140px;
        }

        .wiz-step.active .wiz-step-label { color: var(--primary-blue); }
        .wiz-step.done .wiz-step-label { color: #15803d; }

        .wizard-body { padding: 28px 32px 24px; min-height: 380px; }

        .wizard-panel { display: none; animation: fadeSlideIn .35s ease; }
        .wizard-panel.active { display: block; }

        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .wizard-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 32px 22px;
            border-top: 1px solid var(--border-light);
            background: #fafbfc;
        }

        .wiz-btn-prev, .wiz-btn-next {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 12px;
            padding: 11px 24px;
            font-weight: 700;
            font-size: .88rem;
            cursor: pointer;
            transition: all .25s;
            border: none;
        }

        .wiz-btn-prev {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--border-light);
        }

        .wiz-btn-prev:hover:not(:disabled) {
            border-color: var(--primary-blue);
            background: var(--primary-blue-light);
        }

        .wiz-btn-prev:disabled { opacity: .4; cursor: not-allowed; }

        .wiz-btn-next {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 14px rgba(44, 62, 143, .25);
        }

        .wiz-btn-next:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(44, 62, 143, .35);
        }

        .wiz-btn-finish {
            display: inline-flex;
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            box-shadow: 0 4px 14px rgba(253, 185, 19, .35);
        }

        .wiz-btn-finish:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(253, 185, 19, .45);
        }

        .wiz-btn-finish:disabled {
            opacity: .45;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .wiz-step-counter {
            font-size: .78rem;
            color: #64748b;
            font-weight: 600;
        }

        .step-item { display: flex; gap: 18px; margin-bottom: 24px; align-items: flex-start; }
        .step-item:last-child { margin-bottom: 0; }

        .step-num {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1rem;
            box-shadow: 0 4px 14px rgba(44, 62, 143, .28);
        }

        .step-content { flex: 1; }
        .step-title { font-weight: 700; color: var(--primary-blue); font-size: .97rem; margin-bottom: 4px; }
        .step-desc { font-size: .87rem; color: #475569; line-height: 1.65; }

        .step-note {
            background: var(--secondary-yellow-light);
            border-left: 3px solid var(--secondary-yellow);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: .82rem;
            color: #856404;
            margin-top: 10px;
        }

        .step-link {
            color: var(--primary-blue);
            font-weight: 600;
            font-size: .84rem;
            word-break: break-all;
        }

        .connector {
            margin-left: 19px;
            border-left: 2px dashed var(--border-light);
            height: 16px;
        }

        .info-card {
            background: var(--primary-blue-light);
            border: 1px solid rgba(44, 62, 143, .12);
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 16px;
        }

        .info-card.yellow {
            background: var(--secondary-yellow-light);
            border-color: rgba(253, 185, 19, .3);
        }

        .info-card .ic-title { font-weight: 700; color: var(--primary-blue); font-size: .88rem; margin-bottom: 6px; }
        .info-card .ic-body { font-size: .85rem; color: #475569; line-height: 1.65; }

        .btn-yellow, .btn-blue {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            border-radius: 12px;
            padding: 13px 28px;
            font-weight: 800;
            font-size: .92rem;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
            justify-content: center;
        }

        .btn-yellow {
            background: var(--secondary-gradient);
            color: var(--primary-blue);
        }

        .btn-yellow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(253, 185, 19, .45);
            color: var(--primary-blue);
        }

        .btn-blue {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-blue:hover {
            opacity: .9;
            transform: translateY(-2px);
            color: white;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 8px;
        }

        .overview-tile {
            background: var(--primary-blue-light);
            border-radius: 14px;
            padding: 20px 18px;
            border: 1px solid rgba(44, 62, 143, .1);
            text-align: center;
        }

        .overview-tile .ot-num { font-size: 2rem; font-weight: 900; color: var(--primary-blue); line-height: 1; }
        .overview-tile .ot-label { font-size: .8rem; font-weight: 700; color: #475569; margin-top: 8px; line-height: 1.4; }

        .wiz-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }
        .guide-steps-grid { display: grid; grid-template-columns: 1fr; gap: 0; }

        .panel-heading { font-weight: 800; color: var(--primary-blue); font-size: 1.1rem; margin-bottom: 6px; }
        .panel-sub { font-size: .86rem; color: #64748b; margin-bottom: 22px; line-height: 1.6; }

        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, .85);
            text-align: center;
            padding: 18px;
            font-size: .85rem;
            margin-top: auto;
        }

        .pwd-req {
            padding: 10px 14px;
            border-radius: 10px;
            background: #f8fafc;
            border-left: 4px solid #dee2e6;
        }
        .pwd-req.uploaded {
            border-left-color: #28a745;
            background: #f0fff8;
        }
        .pwd-req-name {
            font-weight: 600;
            font-size: .88rem;
            color: #1e293b;
            line-height: 1.3;
        }
        .pwd-thumb {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            vertical-align: middle;
        }
        .pwd-upload-box {
            background: #FFF3D6;
            border-radius: 8px;
            padding: 10px 12px;
            margin-top: 8px;
        }
        .mta-cat-block { margin-bottom: 22px; }
        .mta-cat-title {
            font-weight: 800;
            color: var(--primary-blue);
            font-size: .95rem;
            margin-bottom: 12px;
        }
        .toast-notice {
            position: fixed;
            top: 84px;
            right: 18px;
            z-index: 1080;
            max-width: 420px;
            background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
            color: white;
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: 0 10px 28px rgba(26,42,92,.35);
            font-size: .84rem;
            font-weight: 700;
        }
        .ui-loading-backdrop {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(1.5px);
            z-index: 12050; display: none;
            align-items: center; justify-content: center;
        }
        .ui-loading-box {
            width: 100%; max-width: 340px; border-radius: 16px;
            background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
            color: #fff; box-shadow: 0 16px 44px rgba(15,23,42,.35);
            border: 1px solid rgba(255,255,255,.15);
            padding: 20px 18px; text-align: center;
        }
        .ui-loading-spinner {
            width: 44px; height: 44px; margin: 0 auto 10px;
            border-radius: 50%; border: 3px solid rgba(255,255,255,.25);
            border-top-color: #FDB913; animation: uiSpin .8s linear infinite;
        }
        @keyframes uiSpin { to { transform: rotate(360deg); } }
        .ui-loading-title { font-weight: 800; font-size: .98rem; }
        .ui-loading-sub { margin-top: 4px; opacity: .85; font-size: .8rem; }
        @keyframes slideInRight  { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
        @keyframes flashTimerShrink { from { width: 100%; } to { width: 0%; } }

        html { background: #1A2A5C; }
        body { padding-bottom: 0 !important; }

        @media (min-width: 992px) {
            .guide-steps-grid { grid-template-columns: 1fr 1fr; column-gap: 32px; }
            .guide-steps-grid .connector { display: none; }
        }

        @media (max-width: 991px) {
            .wiz-2col { grid-template-columns: 1fr; }
            .overview-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 576px) {
            .hero-banner h1 { font-size: 1.45rem; }
            .wizard-body { padding: 20px 16px 16px; }
            .wizard-nav { padding: 14px 16px 20px; flex-wrap: wrap; }
            .wizard-top, .wizard-steps { padding-left: 16px; padding-right: 16px; }
            .wiz-btn-prev, .wiz-btn-next, .wiz-btn-finish { width: 100%; justify-content: center; }
            .overview-grid { grid-template-columns: 1fr; }
            .wiz-step-label { max-width: 90px; font-size: .68rem; }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="brand">
                    <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD">
                    <span>MSWDO</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="lang-toggle">
                        <button class="lang-btn active" data-lang="en" onclick="setLang('en')">EN</button>
                        <button class="lang-btn" data-lang="tl" onclick="setLang('tl')">TL</button>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="back-btn">&#8592; <span data-en="Back to Programs" data-tl="Bumalik sa Programs">Back to Programs</span></a>
                </div>
            </div>
        </div>
    </div>

    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge" data-en="Liliw MSWDO Service" data-tl="Serbisyo ng Liliw MSWDO">Liliw MSWDO Service</div>
                <h1 data-en="Minor Traveling Abroad (MTA)" data-tl="Minor Traveling Abroad (MTA)">Minor Traveling Abroad (MTA)</h1>
                <div class="hero-divider"></div>
                <p class="hero-sub"
                    data-en="Follow each step below to apply for Minor Traveling Abroad clearance through the official DSWD MTS portal."
                    data-tl="Sundin ang bawat hakbang sa ibaba para mag-apply ng Minor Traveling Abroad clearance sa opisyal na DSWD MTS portal.">
                    Follow each step below to apply for Minor Traveling Abroad clearance through the official DSWD MTS portal.
                </p>
            </div>
        </div>
    </section>

    @if(session('upload_success') || session('error'))
        <div class="toast-notice">{{ session('upload_success') ?: session('error') }}</div>
    @endif

    <div class="flex-grow-1 py-3 pb-4">
        <div class="container-fluid px-3 px-lg-4">
            <div id="mta-wizard-view">
                <div class="wizard-card">
                    <div class="wizard-top">
                        <h2 id="wizard-step-title" data-en="Step 1 — Overview" data-tl="Hakbang 1 — Pangkalahatang-ideya">Step 1 — Overview</h2>
                        <p id="wizard-step-desc" data-en="Learn what you need and how this wizard works." data-tl="Alamin ang mga kailangan mo at kung paano gumagana ang wizard na ito.">Learn what you need and how this wizard works.</p>
                        <div class="wizard-progress-track">
                            <div class="wizard-progress-fill" id="wizard-progress-fill"></div>
                        </div>
                    </div>

                    <div class="wizard-steps" role="tablist">
                        <button type="button" class="wiz-step active" data-step="0">
                            <span class="wiz-step-circle">1</span>
                            <span class="wiz-step-label" data-en="Overview" data-tl="Pangkalahatan">Overview</span>
                        </button>
                        <button type="button" class="wiz-step" data-step="1">
                            <span class="wiz-step-circle">2</span>
                            <span class="wiz-step-label" data-en="How to Apply" data-tl="Paano Mag-apply">How to Apply</span>
                        </button>
                        <button type="button" class="wiz-step" data-step="2">
                            <span class="wiz-step-circle">3</span>
                            <span class="wiz-step-label" data-en="Document Upload Requirements" data-tl="Mga Kinakailangang Dokumento">Document Upload Requirements</span>
                        </button>
                    </div>

                    <div class="wizard-body">
                        <div class="wizard-panel active" data-panel="overview">
                            <div class="panel-heading" data-en="Welcome to the MTA Application Process" data-tl="Maligayang Pagdating sa Proseso ng MTA Application">Welcome to the MTA Application Process</div>
                            <div class="panel-sub"
                                data-en="This wizard walks you through applying for Minor Traveling Abroad clearance using the official DSWD MTS website."
                                data-tl="Gagabayan ka ng wizard na ito sa pag-apply ng Minor Traveling Abroad clearance gamit ang opisyal na DSWD MTS website.">
                                This wizard walks you through applying for Minor Traveling Abroad clearance using the official DSWD MTS website.
                            </div>

                            <div class="overview-grid mb-4">
                                <div class="overview-tile">
                                    <div class="ot-num">4</div>
                                    <div class="ot-label" data-en="Official steps to complete your MTA application" data-tl="Opisyal na hakbang para makumpleto ang MTA application">Official steps to complete your MTA application</div>
                                </div>
                                <div class="overview-tile">
                                    <div class="ot-num">1</div>
                                    <div class="ot-label" data-en="Official DSWD MTS portal" data-tl="Opisyal na DSWD MTS portal">Official DSWD MTS portal</div>
                                </div>
                                <div class="overview-tile">
                                    <div class="ot-num">Liliw</div>
                                    <div class="ot-label" data-en="Available to Liliw residents only" data-tl="Para sa mga residente ng Liliw lamang">Available to Liliw residents only</div>
                                </div>
                            </div>

                            <div class="wiz-2col">
                                <div class="info-card yellow mb-0">
                                    <div class="ic-title" data-en="What you'll need" data-tl="Mga kailangan mo">What you'll need</div>
                                    <div class="ic-body">
                                        <ul style="margin:0;padding-left:18px;line-height:2;">
                                            <li>Affidavit of Guardianship</li>
                                            <li>Certificate from the Barangay</li>
                                            <li>Certificate of Indigency from the Barangay</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="info-card mb-0">
                                    <div class="ic-title" data-en="Getting Started" data-tl="Pagsisimula">Getting Started</div>
                                    <div class="ic-body"
                                        data-en="Use the Next button below to begin. Upload all required documents in Step 3, then submit your MTA application."
                                        data-tl="Gamitin ang Next button sa ibaba para magsimula. I-upload ang lahat ng required documents sa Hakbang 3, tapos i-submit ang MTA application.">
                                        Use the Next button below to begin. Upload all required documents in Step 3, then submit your MTA application.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-panel" data-panel="guide">
                            <div class="panel-heading" data-en="How to Apply for MTA" data-tl="Paano Mag-apply ng MTA">How to Apply for MTA</div>
                            <div class="panel-sub"
                                data-en="Follow these official steps to complete your Minor Traveling Abroad application."
                                data-tl="Sundin ang mga opisyal na hakbang na ito para makumpleto ang iyong Minor Traveling Abroad application.">
                                Follow these official steps to complete your Minor Traveling Abroad application.
                            </div>

                            <div class="guide-steps-grid">
                                <div class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-content">
                                        <div class="step-title">Visit mts.dswd.gov.ph</div>
                                        <div class="step-desc"
                                            data-en="Go to the official DSWD MTS portal to start your Minor Traveling Abroad application."
                                            data-tl="Pumunta sa opisyal na DSWD MTS portal para simulan ang iyong Minor Traveling Abroad application.">
                                            Go to the official DSWD MTS portal to start your Minor Traveling Abroad application.
                                        </div>
                                        <a href="https://mts.dswd.gov.ph" target="_blank" rel="noopener noreferrer" class="step-link mt-2 d-block">https://mts.dswd.gov.ph</a>
                                    </div>
                                </div>
                                <div class="connector"></div>
                                <div class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-content">
                                        <div class="step-title">Create an account</div>
                                        <div class="step-desc"
                                            data-en="Register a new account on the DSWD MTS portal if you do not have one yet."
                                            data-tl="Magrehistro ng bagong account sa DSWD MTS portal kung wala ka pa.">
                                            Register a new account on the DSWD MTS portal if you do not have one yet.
                                        </div>
                                    </div>
                                </div>
                                <div class="connector"></div>
                                <div class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-content">
                                        <div class="step-title">Login your registered account</div>
                                        <div class="step-desc"
                                            data-en="Sign in using your registered MTS account to continue the application."
                                            data-tl="Mag-login gamit ang iyong rehistradong MTS account para magpatuloy sa aplikasyon.">
                                            Sign in using your registered MTS account to continue the application.
                                        </div>
                                    </div>
                                </div>
                                <div class="connector"></div>
                                <div class="step-item">
                                    <div class="step-num">4</div>
                                    <div class="step-content">
                                        <div class="step-title">Prepare necessary documents required</div>
                                        <div class="step-desc"
                                            data-en="Prepare all documents required by the DSWD MTS portal before submitting your application."
                                            data-tl="Ihanda ang lahat ng dokumentong hinihingi ng DSWD MTS portal bago isumite ang iyong aplikasyon.">
                                            Prepare all documents required by the DSWD MTS portal before submitting your application.
                                        </div>
                                        <div class="step-note">
                                            <strong>Note:</strong>
                                            <span data-en="Document requirements are listed on the official MTS portal after you log in." data-tl="Ang mga dokumentong kailangan ay nakalista sa opisyal na MTS portal pagkatapos mong mag-login.">
                                                Document requirements are listed on the official MTS portal after you log in.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-panel" data-panel="resources">
                            <div class="panel-heading" data-en="Document Upload Requirements" data-tl="Mga Kinakailangang Dokumento para sa Pag-upload">Document Upload Requirements</div>
                            <div class="panel-sub"
                                data-en="Upload every required document below. All files are required before you can submit your MTA application. There is no admin review — each successful upload is marked as uploaded immediately."
                                data-tl="I-upload ang bawat required document sa ibaba. Kailangan ang lahat ng files bago i-submit ang MTA application. Walang admin review — agad itong mamarkahan bilang uploaded.">
                                Upload every required document below. All files are required before you can submit your MTA application. There is no admin review — each successful upload is marked as uploaded immediately.
                            </div>

                            @if(!empty($application) && ($application->status ?? '') === 'submitted')
                                <div class="info-card mb-4">
                                    <div class="ic-title" data-en="Application submitted" data-tl="Naisumite na ang aplikasyon">Application submitted</div>
                                    <div class="ic-body" data-en="Your MTA documents have been submitted. You may still replace a file if needed." data-tl="Naisumite na ang iyong mga MTA dokumento. Maaari mo pa ring palitan ang file kung kailangan.">
                                        Your MTA documents have been submitted. You may still replace a file if needed.
                                    </div>
                                </div>
                            @endif

                            @include('user.partials.mta-upload-requirements')
                        </div>
                    </div>

                    <div class="wizard-nav">
                        <button type="button" class="wiz-btn-prev" id="wiz-btn-prev" disabled onclick="wizardPrev()">
                            &#8592; <span data-en="Previous" data-tl="Nakaraan">Previous</span>
                        </button>
                        <span class="wiz-step-counter" id="wiz-step-counter">Step 1 of 3</span>
                        <button type="button" class="wiz-btn-next" id="wiz-btn-next" onclick="wizardNext()">
                            <span data-en="Next" data-tl="Susunod">Next</span> &#8594;
                        </button>
                        <form action="{{ route('user.mta-submit-application') }}" method="POST" id="mta-submit-form" style="display:none;margin:0;">
                            @csrf
                            <button type="submit" class="wiz-btn-next wiz-btn-finish" id="wiz-btn-finish" {{ empty($allMtaUploaded) ? 'disabled' : '' }}>
                                <span data-en="Submit MTA Application" data-tl="I-submit ang MTA Application">Submit MTA Application</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center py-2">
                    <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.92rem;padding:12px 28px;background:var(--primary-gradient);">
                        <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-strip"></div>

    <div id="uiLoadingBackdrop" class="ui-loading-backdrop" aria-hidden="true">
        <div class="ui-loading-box">
            <div class="ui-loading-spinner"></div>
            <div class="ui-loading-title">Uploading Document</div>
            <div class="ui-loading-sub">Please wait while we process your file.</div>
        </div>
    </div>

    <div class="modal fade" id="fileViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:20px 24px;">
                    <h5 class="modal-title" id="fileViewerModalLabel" style="font-weight:800;font-size:1.2rem;">Document Viewer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div id="fileViewerContainer" style="text-align:center;background:var(--bg-light);border-radius:12px;padding:24px;min-height:400px;display:flex;align-items:center;justify-content:center;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const wizardMeta = [
            { enTitle: 'Step 1 — Overview', tlTitle: 'Hakbang 1 — Pangkalahatang-ideya', enDesc: 'Learn what you need and how this wizard works.', tlDesc: 'Alamin ang mga kailangan mo at kung paano gumagana ang wizard na ito.' },
            { enTitle: 'Step 2 — How to Apply', tlTitle: 'Hakbang 2 — Paano Mag-apply', enDesc: 'Follow the official MTA steps on the DSWD MTS portal.', tlDesc: 'Sundin ang opisyal na mga hakbang ng MTA sa DSWD MTS portal.' },
            { enTitle: 'Step 3 — Document Upload Requirements', tlTitle: 'Hakbang 3 — Mga Kinakailangang Dokumento', enDesc: 'Upload all required MTA documents, then submit.', tlDesc: 'I-upload ang lahat ng kinakailangang MTA dokumento, tapos i-submit.' }
        ];
        let currentStep = 0;
        let currentLang = 'en';
        const totalSteps = wizardMeta.length;
        const totalRequired = {{ count($mtaRequirements ?? []) }};
        let allUploaded = @json(!empty($allMtaUploaded));
        const alreadySubmitted = @json(!empty($application) && ($application->status ?? '') === 'submitted');

        function setLang(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.lang === lang);
            });
            document.querySelectorAll('[data-en][data-tl]').forEach(el => {
                if (el.querySelector('input,button,select,textarea')) return;
                el.textContent = lang === 'tl' ? el.dataset.tl : el.dataset.en;
            });
            updateWizardChrome();
        }

        function goToStep(index) {
            currentStep = Math.max(0, Math.min(index, totalSteps - 1));
            document.querySelectorAll('.wizard-panel').forEach((panel, i) => {
                panel.classList.toggle('active', i === currentStep);
            });
            document.querySelectorAll('.wiz-step').forEach((btn, i) => {
                btn.classList.toggle('active', i === currentStep);
                btn.classList.toggle('done', i < currentStep);
            });
            document.getElementById('wiz-btn-prev').disabled = currentStep === 0;
            document.getElementById('wiz-btn-next').style.display = currentStep === totalSteps - 1 ? 'none' : 'inline-flex';
            document.getElementById('mta-submit-form').style.display = currentStep === totalSteps - 1 ? 'block' : 'none';
            document.getElementById('wizard-progress-fill').style.width = (((currentStep + 1) / totalSteps) * 100) + '%';
            document.getElementById('wiz-step-counter').textContent = 'Step ' + (currentStep + 1) + ' of ' + totalSteps;
            updateWizardChrome();
            syncSubmitButton();
        }

        function updateWizardChrome() {
            const meta = wizardMeta[currentStep];
            const title = document.getElementById('wizard-step-title');
            const desc = document.getElementById('wizard-step-desc');
            title.textContent = currentLang === 'tl' ? meta.tlTitle : meta.enTitle;
            desc.textContent = currentLang === 'tl' ? meta.tlDesc : meta.enDesc;
        }

        function wizardNext() { goToStep(currentStep + 1); }
        function wizardPrev() { goToStep(currentStep - 1); }

        function syncSubmitButton() {
            const btn = document.getElementById('wiz-btn-finish');
            if (!btn) return;
            btn.disabled = !allUploaded;
            const label = btn.querySelector('span');
            if (alreadySubmitted && label) {
                label.dataset.en = 'Already submitted';
                label.dataset.tl = 'Naisumite na';
                label.textContent = currentLang === 'tl' ? 'Naisumite na' : 'Already submitted';
                btn.disabled = true;
            }
        }

        document.querySelectorAll('.wiz-step').forEach(btn => {
            btn.addEventListener('click', () => goToStep(parseInt(btn.dataset.step, 10)));
        });

        document.getElementById('mta-submit-form').addEventListener('submit', function (e) {
            if (!allUploaded) {
                e.preventDefault();
                showMtaUploadNotification('Upload all required documents before submitting.');
            }
        });

        function showReqLoading() {
            const el = document.getElementById('uiLoadingBackdrop');
            if (el) { el.style.display = 'flex'; el.setAttribute('aria-hidden','false'); }
        }
        function hideReqLoading() {
            const el = document.getElementById('uiLoadingBackdrop');
            if (el) { el.style.display = 'none'; el.setAttribute('aria-hidden','true'); }
        }

        function showMtaUploadNotification(message) {
            const existing = document.getElementById('mtaUploadNotif');
            if (existing) existing.remove();
            const notif = document.createElement('div');
            notif.id = 'mtaUploadNotif';
            notif.style.cssText = 'position:fixed;top:84px;right:18px;z-index:1081;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;animation:slideInRight .4s ease;';
            notif.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <span>${message}</span>
                    <button onclick="this.closest('#mtaUploadNotif').remove()" style="background:transparent;border:none;color:rgba(255,255,255,.7);font-size:1.2rem;cursor:pointer;line-height:1;">&times;</button>
                </div>
                <div style="position:absolute;bottom:0;left:0;height:3px;background:rgba(253,185,19,.9);width:100%;border-radius:0 0 12px 12px;animation:flashTimerShrink 5s linear forwards;"></div>
            `;
            document.body.appendChild(notif);
            setTimeout(() => { if (notif.parentNode) { notif.style.animation = 'slideOutRight .4s ease forwards'; setTimeout(() => notif.remove(), 400); } }, 5000);
        }

        function markRowUploaded(row, data) {
            row.classList.add('uploaded');
            const badge = row.querySelector('.mta-status-badge');
            if (badge) {
                badge.style.background = '#d4edda';
                badge.style.color = '#155724';
                badge.textContent = 'Uploaded';
            }
            const dateEl = row.querySelector('.mta-uploaded-date');
            if (dateEl && data.uploaded_at) {
                dateEl.style.display = '';
                dateEl.textContent = data.uploaded_at;
            }
            const btn = row.querySelector('button[type="submit"]');
            if (btn) btn.textContent = 'Replace';
            const hint = row.querySelector('.pwd-upload-box > div');
            if (hint) hint.textContent = 'Replace document';
            if (data.file_url && ['jpg','jpeg','png','webp'].includes((data.file_ext || '').toLowerCase())) {
                let thumb = row.querySelector('.pwd-thumb');
                if (!thumb) {
                    thumb = document.createElement('img');
                    thumb.className = 'pwd-thumb';
                    thumb.alt = '';
                    const badgeWrap = row.querySelector('.mta-status-badge')?.parentNode;
                    if (badgeWrap) badgeWrap.appendChild(thumb);
                }
                thumb.src = data.file_url;
                thumb.onclick = function () { openFileModal(data.file_url, data.requirement_name, data.file_ext); };
            }
        }

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form.classList.contains('js-mta-ajax-upload')) return;
            e.preventDefault();
            var fileInput = form.querySelector('input[type="file"][name="file"]');
            if (!fileInput || !fileInput.files.length) return;
            var file = fileInput.files[0];
            var isImage = ['image/jpeg','image/jpg','image/png'].includes(file.type);
            var maxSize = isImage ? 5 * 1024 * 1024 : 25 * 1024 * 1024;
            if (file.size > maxSize) {
                showMtaUploadNotification('"' + file.name + '" exceeds the ' + (isImage ? '5MB' : '25MB') + ' limit.');
                return;
            }
            var btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            showReqLoading();
            var fd = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                body: fd
            })
            .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, data: data }; }); })
            .then(function (result) {
                hideReqLoading();
                btn.disabled = false;
                if (result.ok && result.data && result.data.success) {
                    var row = form.closest('.pwd-req');
                    if (row) markRowUploaded(row, result.data);
                    fileInput.value = '';
                    allUploaded = !!result.data.all_uploaded;
                    var progress = document.getElementById('mta-upload-progress');
                    var progressText = document.getElementById('mta-upload-progress-text');
                    var pct = result.data.total_required ? Math.round((result.data.uploaded_count / result.data.total_required) * 100) : 0;
                    if (progress) progress.style.width = pct + '%';
                    if (progressText) progressText.textContent = result.data.uploaded_count + '/' + result.data.total_required + ' uploaded — ' + pct + '% complete';
                    syncSubmitButton();
                    showMtaUploadNotification(result.data.message || 'File uploaded successfully.');
                } else {
                    showMtaUploadNotification((result.data && result.data.message) || 'Upload failed. Please try again.');
                }
            })
            .catch(function () {
                hideReqLoading();
                btn.disabled = false;
                showMtaUploadNotification('Upload failed. Please try again.');
            });
        });

        function openFileModal(url, title, ext) {
            const label = document.getElementById('fileViewerModalLabel');
            const container = document.getElementById('fileViewerContainer');
            if (label) label.textContent = title || 'Document Viewer';
            if (container) {
                const lower = (ext || '').toLowerCase();
                if (['jpg','jpeg','png','webp','gif'].includes(lower)) {
                    container.innerHTML = '<img src="' + url + '" style="max-width:100%;max-height:70vh;border-radius:8px;">';
                } else {
                    container.innerHTML = '<iframe src="' + url + '" style="width:100%;height:70vh;border:0;border-radius:8px;"></iframe>';
                }
            }
            const modal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
            modal.show();
        }

        goToStep({{ !empty($uploadedCount) ? 2 : 0 }});
    </script>
</body>
</html>
