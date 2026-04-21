<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Municipality – {{ $municipality->name }} | MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --border-light: #E2E8F0;
        }

        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }

        body {
            background: var(--bg-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44,62,143,.18);
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
            color: rgba(255,255,255,.88) !important;
            font-weight: 600;
            transition: all .25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: .95rem;
        }
        .nav-link:hover { background: rgba(255,255,255,.15); color: white !important; }
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
            background: rgba(255,255,255,.1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: .92rem;
            font-weight: 500;
        }
        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255,255,255,.8);
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 700;
            transition: all .3s;
            font-size: .88rem;
            cursor: pointer;
        }
        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        /* ── HERO ── */
        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 42px 0 34px;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -70px; right: -70px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(253,185,19,.08);
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }
        .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,.35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .hero-banner h1 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 8px;
        }
        .hero-divider {
            width: 48px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 12px 0;
        }
        .hero-banner p {
            font-size: .95rem;
            opacity: .82;
            max-width: 520px;
            margin: 0;
        }
        .hero-muni-badge {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 16px;
            padding: 18px 26px;
            text-align: center;
        }
        .hero-muni-badge .label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--secondary-yellow);
            opacity: .9;
        }
        .hero-muni-badge .name {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            margin-top: 4px;
        }
        .hero-muni-badge .id-chip {
            display: inline-block;
            background: rgba(253,185,19,.18);
            border-radius: 20px;
            padding: 2px 12px;
            font-size: .75rem;
            font-weight: 600;
            color: var(--secondary-yellow);
            margin-top: 6px;
        }

        /* ── BREADCRUMB ── */
        .breadcrumb-bar {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 12px 0;
        }
        .breadcrumb { margin: 0; font-size: .84rem; }
        .breadcrumb-item a { color: var(--primary-blue); font-weight: 600; text-decoration: none; }
        .breadcrumb-item a:hover { text-decoration: underline; }
        .breadcrumb-item.active { color: #64748b; font-weight: 500; }

        /* ── FORM SECTIONS ── */
        .section-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,.04);
            border: 1px solid var(--border-light);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .section-card-header {
            background: var(--bg-light);
            border-bottom: 2px solid var(--border-light);
            padding: 18px 28px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-card-header .sh-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .section-card-header .sh-title {
            font-size: .95rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin: 0;
        }
        .section-card-header .sh-sub {
            font-size: .78rem;
            color: #94a3b8;
            margin: 2px 0 0;
        }
        .section-card-body { padding: 24px 28px; }

        /* ── FORM CONTROLS ── */
        .form-label {
            font-size: .82rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: .06em;
            margin-bottom: 7px;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--border-light);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .92rem;
            color: #1e293b;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(44,62,143,.12);
            outline: none;
        }
        .form-control.readonly-total {
            background: #F0F5FF;
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            font-weight: 800;
            font-size: 1rem;
            cursor: default;
        }
        .input-hint {
            font-size: .73rem;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 4px;
        }

        /* ── ACTION BUTTONS ── */
        .action-bar {
            background: white;
            border-radius: 20px;
            padding: 24px 28px;
            box-shadow: 0 4px 18px rgba(0,0,0,.04);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 32px;
        }
        .action-bar .action-hint {
            font-size: .84rem;
            color: #64748b;
        }
        .action-bar .action-hint strong {
            color: var(--primary-blue);
        }
        .btn-save {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 32px;
            font-weight: 700;
            font-size: .92rem;
            cursor: pointer;
            transition: all .3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 18px rgba(44,62,143,.25);
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(44,62,143,.35);
        }
        .btn-cancel {
            background: white;
            color: #64748b;
            border: 1.5px solid var(--border-light);
            border-radius: 30px;
            padding: 11px 28px;
            font-weight: 600;
            font-size: .92rem;
            text-decoration: none;
            transition: all .25s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-cancel:hover {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            background: var(--primary-blue-light);
        }

        /* ── FOOTER ── */
        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255,255,255,.75);
            text-align: center;
            padding: 18px 0;
            font-size: .85rem;
            margin-top: auto;
        }
        .footer-strip strong { color: white; }
    </style>
</head>

<body>
    <!-- ── NAVBAR ── -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
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
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Public View</a></li>
                </ul>
                <div class="d-flex">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ── HERO ── -->
    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-badge">Edit Record</div>
                    <h1><i class="bi bi-pencil-square" style="color:var(--secondary-yellow);font-size:1.7rem;"></i>
                        Edit Municipality</h1>
                    <div class="hero-divider"></div>
                    <p>Update population statistics, demographic data, and household information for this municipality.</p>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <div class="hero-muni-badge">
                        <div class="label">Editing</div>
                        <div class="name">{{ $municipality->name }}</div>
                        <div class="id-chip">ID #{{ $municipality->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── BREADCRUMB ── -->
    <div class="breadcrumb-bar">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                <li class="breadcrumb-item active">Edit – {{ $municipality->name }}</li>
            </ol>
        </div>
    </div>

    <!-- ── MAIN CONTENT ── -->
    <div style="flex:1;">
        <div class="container py-4">

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-3 mb-4" style="border-left:4px solid #dc2626 !important;">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following:</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.municipalities.update', $municipality->id) }}"
                  id="editMuniForm">
                @csrf
                @method('PUT')

                {{-- ── SECTION 1: Basic Info ── --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <div class="sh-icon"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="sh-title">Basic Information</div>
                            <div class="sh-sub">Municipality name and record year</div>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Municipality Name</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $municipality->name) }}" required
                                       placeholder="e.g. Magdalena">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Year <span style="color:#C41E24;">*</span></label>
                                <select name="year" class="form-select" required>
                                    @foreach(range(2015, date('Y') + 1) as $yr)
                                        <option value="{{ $yr }}"
                                            {{ old('year', $municipality->year ?? date('Y')) == $yr ? 'selected' : '' }}>
                                            {{ $yr }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-hint">Select the data reference year for this record.</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 2: Population ── --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <div class="sh-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="sh-title">Population Data</div>
                            <div class="sh-sub">Total population broken down by gender</div>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Male Population</label>
                                <input type="number" name="male_population" id="male_population"
                                       class="form-control"
                                       value="{{ old('male_population', $municipality->male_population) }}"
                                       min="0" required oninput="calcTotal()"
                                       placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Female Population</label>
                                <input type="number" name="female_population" id="female_population"
                                       class="form-control"
                                       value="{{ old('female_population', $municipality->female_population) }}"
                                       min="0" required oninput="calcTotal()"
                                       placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">
                                    Total Population
                                    <span style="font-size:.7rem;font-weight:500;color:#94a3b8;text-transform:none;letter-spacing:0;">
                                        (auto-calculated)
                                    </span>
                                </label>
                                <input type="number" id="total_population_display"
                                       class="form-control readonly-total"
                                       value="{{ $municipality->male_population + $municipality->female_population }}"
                                       readonly tabindex="-1">
                                <div class="input-hint"><i class="bi bi-info-circle"></i> Male + Female</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 3: Age Groups ── --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <div class="sh-icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <div>
                            <div class="sh-title">Age Group Distribution</div>
                            <div class="sh-sub">Population grouped by age brackets</div>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Youth (0–19 yrs)</label>
                                <input type="number" name="population_0_19" class="form-control"
                                       value="{{ old('population_0_19', $municipality->population_0_19) }}"
                                       min="0" required placeholder="0">
                                <div class="input-hint">Children & youth population</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Adults (20–59 yrs)</label>
                                <input type="number" name="population_20_59" class="form-control"
                                       value="{{ old('population_20_59', $municipality->population_20_59) }}"
                                       min="0" required placeholder="0">
                                <div class="input-hint">Working-age population</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Seniors (60–100 yrs)</label>
                                <input type="number" name="population_60_100" class="form-control"
                                       value="{{ old('population_60_100', $municipality->population_60_100) }}"
                                       min="0" required placeholder="0">
                                <div class="input-hint">Senior citizens</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 4: Household & Social ── --}}
                <div class="section-card">
                    <div class="section-card-header">
                        <div class="sh-icon"><i class="bi bi-house-heart-fill"></i></div>
                        <div>
                            <div class="sh-title">Household & Social Data</div>
                            <div class="sh-sub">Household count and solo parent households</div>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Total Households</label>
                                <input type="number" name="total_households" class="form-control"
                                       value="{{ old('total_households', $municipality->total_households) }}"
                                       min="0" required placeholder="0">
                                <div class="input-hint">Number of registered households</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Single Parents</label>
                                <input type="number" name="single_parent_count" class="form-control"
                                       value="{{ old('single_parent_count', $municipality->single_parent_count) }}"
                                       min="0" required placeholder="0">
                                <div class="input-hint">Solo parent households</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── ACTION BAR ── --}}
                <div class="action-bar">
                    <div class="action-hint">
                        Editing <strong>{{ $municipality->name }}</strong> — all fields marked
                        <span style="color:#C41E24;">*</span> are required.
                    </div>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('superadmin.municipalities.index') }}" class="btn-cancel">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                        <button type="submit" class="btn-save">
                            <i class="bi bi-check2-circle"></i> Save Changes
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- ── FOOTER ── -->
    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function calcTotal() {
            const male   = parseInt(document.getElementById('male_population').value)   || 0;
            const female = parseInt(document.getElementById('female_population').value) || 0;
            document.getElementById('total_population_display').value = male + female;
        }
    </script>
</body>

</html>