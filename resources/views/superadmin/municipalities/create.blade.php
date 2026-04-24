<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Add Municipality &mdash; MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-dark: #1A2A5C;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }
        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }
        html, body { margin: 0; padding: 0; background: var(--bg-light); overscroll-behavior: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .nav-link { color: rgba(255,255,255,.88) !important; font-weight: 600; transition: all .25s; border-radius: 8px; padding: 10px 18px !important; font-size: .95rem; }
        .nav-link:hover { background: rgba(255,255,255,.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; font-size: .9rem; font-weight: 600; }
        .logout-btn { background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.4); color: white; border-radius: 8px; padding: 6px 16px; font-weight: 600; font-size: .85rem; cursor: pointer; transition: all .2s; }
        .logout-btn:hover { background: rgba(255,255,255,.28); }

        /* ── HERO ── */
        .hero-banner { background: var(--primary-gradient); padding: 44px 0 36px; position: relative; overflow: hidden; }
        .hero-banner::after { content: ''; position: absolute; right: -80px; top: -80px; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,.05); }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,.75); text-decoration: none; font-size: .85rem; font-weight: 600; margin-bottom: 14px; transition: color .2s; }
        .back-link:hover { color: white; }
        .hero-badge { display: inline-block; background: var(--secondary-yellow); color: var(--primary-blue); font-size: .72rem; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; padding: 4px 12px; border-radius: 20px; margin-bottom: 10px; }
        .hero-banner h1 { color: white; font-weight: 800; font-size: 2rem; margin: 0 0 6px; }
        .hero-divider { width: 48px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 10px; }
        .hero-banner p { color: rgba(255,255,255,.78); font-size: .95rem; margin: 0; }

        /* ── FORM CARD ── */
        .form-card { background: white; border-radius: 20px; padding: 36px; box-shadow: 0 4px 24px rgba(44,62,143,.07); border: 1px solid var(--border-light); margin-bottom: 40px; }
        .section-label { font-size: .72rem; font-weight: 800; color: var(--primary-blue); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 18px; padding-bottom: 10px; border-bottom: 2px solid var(--secondary-yellow); }
        .f-label { font-size: .8rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .04em; display: block; margin-bottom: 6px; }
        .f-input { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border-light); border-radius: 10px; font-size: .92rem; font-family: 'Inter', sans-serif; color: #1e293b; background: #f8fafc; transition: border-color .2s, box-shadow .2s; }
        .f-input:focus { outline: none; border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,.1); background: white; }
        .f-hint { font-size: .73rem; color: #94a3b8; margin-top: 4px; }

        /* ── POPULATION DISPLAY ── */
        .pop-display { background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); border: 2px solid #C7D2FE; border-radius: 12px; padding: 16px 20px; display: flex; align-items: center; gap: 12px; }
        .pop-display .pop-value { font-size: 1.6rem; font-weight: 800; color: var(--primary-blue); }
        .pop-display .pop-label { font-size: .75rem; color: #6366f1; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }

        /* ── BARANGAY PREVIEW ── */
        .bgy-preview { background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: 14px; padding: 20px; margin-top: 18px; display: none; }
        .bgy-preview h6 { color: var(--primary-blue); font-weight: 700; margin-bottom: 12px; font-size: .88rem; }
        .bgy-badge { display: inline-block; background: white; border: 1px solid #bae6fd; color: #0369a1; border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 600; margin: 3px; }

        /* ── BUTTONS ── */
        .btn-submit { background: var(--primary-gradient); color: white; border: none; border-radius: 12px; padding: 13px 36px; font-weight: 700; font-size: 1rem; cursor: pointer; transition: all .2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(44,62,143,.3); }
        .btn-cancel { background: white; color: #64748b; border: 1.5px solid var(--border-light); border-radius: 12px; padding: 13px 28px; font-weight: 600; font-size: .95rem; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all .2s; }
        .btn-cancel:hover { background: #f1f5f9; color: #475569; }

        /* ── ALERTS ── */
        .alert-e { background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 12px; padding: 14px 18px; margin-bottom: 24px; color: #b91c1c; font-size: .88rem; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/superadmin/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name ?? 'Super Admin' }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container">
            <a href="{{ route('superadmin.municipalities.index') }}" class="back-link">&#8592; Municipalities</a>
            <div class="hero-badge">New Municipality</div>
            <h1>Add Municipality</h1>
            <div class="hero-divider"></div>
            <p>Create a new municipality record with demographic data.</p>
        </div>
    </section>

    <!-- MAIN -->
    <div class="container mt-4">

        @if($errors->any())
            <div class="alert-e">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.municipalities.store') }}" id="municipalityForm">
            @csrf

            <!-- Basic Info -->
            <div class="form-card">
                <div class="section-label">Basic Information</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="f-label">Municipality Name</label>
                        <select name="name" id="municipalitySelect" class="f-input" required>
                            <option value="">-- Select Municipality --</option>
                            @foreach(['Alaminos','Bay','Biñan','Cabuyao','Calamba','Calauan','Cavinti','Famy','Kalayaan','Liliw','Los Baños','Luisiana','Lumban','Mabitac','Magdalena','Majayjay','Nagcarlan','Paete','Pagsanjan','Pakil','Pangil','Pila','Rizal','San Pablo','San Pedro','Santa Cruz','Santa Maria','Santa Rosa','Siniloan','Victoria'] as $mun)
                                <option value="{{ $mun }}" {{ old('name') == $mun ? 'selected' : '' }}>{{ $mun }}</option>
                            @endforeach
                        </select>
                        <div class="f-hint">Select a municipality from Laguna</div>
                    </div>
                    <div class="col-md-3">
                        <label class="f-label">Data Year</label>
                        <select name="year" class="f-input" required>
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $yr)
                                <option value="{{ $yr }}" {{ (old('year', date('Y')) == $yr) ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="f-label">Total Households</label>
                        <input type="number" name="total_households" class="f-input" value="{{ old('total_households', 0) }}" min="0" required>
                    </div>
                </div>
            </div>

            <!-- Demographics -->
            <div class="form-card">
                <div class="section-label">Population &amp; Demographics</div>

                <!-- Auto-calculated total -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="f-label">Total Population</label>
                        <input type="number" name="total_population" id="totalPop" class="f-input" value="{{ old('total_population', 0) }}" min="0" required>
                        <div class="f-hint">Or leave at 0 to auto-sum from Male + Female</div>
                    </div>
                    <div class="col-md-4">
                        <label class="f-label">Male Population</label>
                        <input type="number" name="male_population" id="malePop" class="f-input" value="{{ old('male_population', 0) }}" min="0" required oninput="autoSum()">
                    </div>
                    <div class="col-md-4">
                        <label class="f-label">Female Population</label>
                        <input type="number" name="female_population" id="femalePop" class="f-input" value="{{ old('female_population', 0) }}" min="0" required oninput="autoSum()">
                    </div>
                </div>

                <div class="section-label" style="margin-top:0;">Age Breakdown</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="f-label">Age 0&ndash;19</label>
                        <input type="number" name="population_0_19" class="f-input" value="{{ old('population_0_19', 0) }}" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="f-label">Age 20&ndash;59</label>
                        <input type="number" name="population_20_59" class="f-input" value="{{ old('population_20_59', 0) }}" min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="f-label">Age 60&ndash;100</label>
                        <input type="number" name="population_60_100" class="f-input" value="{{ old('population_60_100', 0) }}" min="0">
                    </div>
                </div>
            </div>

            <!-- hidden required by controller -->
            <input type="hidden" name="single_parent_count" value="0">

            <!-- Actions -->
            <div class="d-flex gap-3 mb-5">
                <button type="submit" class="btn-submit">&#10003; Create Municipality</button>
                <a href="{{ route('superadmin.municipalities.index') }}" class="btn-cancel">Cancel</a>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function autoSum() {
            const m = parseInt(document.getElementById('malePop').value) || 0;
            const f = parseInt(document.getElementById('femalePop').value) || 0;
            const tp = document.getElementById('totalPop');
            if (parseInt(tp.value) === 0 || tp.dataset.manual !== '1') {
                tp.value = m + f;
            }
        }

        document.getElementById('totalPop').addEventListener('input', function () {
            this.dataset.manual = '1';
        });
    </script>
</body>
</html>