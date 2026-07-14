<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Municipality Yearly Data – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @include('components.admin-colors')
    <style>
        html,
        body {
            overscroll-behavior: none;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-blue-light: #E5EEFF;
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }

        *,
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background: var(--bg-light);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        /* NAVBAR */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44, 62, 143, .18);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.55rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 12px;
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
            transition: all .25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: .85rem; white-space: nowrap;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .15);
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
            background: rgba(255, 255, 255, .1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: .9rem;
            font-weight: 600;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .8);
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

        /* HERO */
        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 44px 0 36px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(253, 185, 19, .10);
        }

        .hero-badge {
            display: inline-block;
            background: rgba(253, 185, 19, .18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, .35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .hero-banner h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .hero-divider {
            width: 55px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 10px 0;
        }

        .hero-banner p {
            font-size: .98rem;
            opacity: .85;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, .75);
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 14px;
            transition: all .25s;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 20px;
            padding: 5px 14px;
        }

        .back-link:hover {
            color: white;
            background: rgba(255, 255, 255, .15);
        }

        .main-content {
            flex: 1;
        }

        /* Tab Pills */
        .tab-pills {
            display: flex;
            gap: 10px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .tab-pill {
            background: white;
            border: 2px solid var(--border-light);
            border-radius: 30px;
            padding: 8px 22px;
            font-weight: 700;
            font-size: .88rem;
            color: #64748b;
            cursor: pointer;
            transition: all .25s;
        }

        .tab-pill.active {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        /* Panel */
        .panel-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
            border: 1px solid var(--border-light);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .panel-header {
            background: var(--primary-gradient);
            color: white;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .panel-header h5 {
            font-weight: 800;
            margin: 0;
            font-size: 1.05rem;
            margin-right: 8px;
        }

        .count-badge {
            background: rgba(253, 185, 19, .25);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, .4);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: .78rem;
            font-weight: 700;
        }

        /* Table */
        .premium-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premium-table thead th {
            background: var(--bg-light);
            color: var(--primary-blue);
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 12px 18px;
            border-bottom: 2px solid var(--border-light);
        }

        .premium-table tbody td {
            padding: 13px 18px;
            font-size: .88rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
            color: #334155;
        }

        .premium-table tbody tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr:hover {
            background: var(--primary-blue-light);
        }

        /* Buttons */
        .btn-add {
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            border: none;
            border-radius: 30px;
            padding: 9px 24px;
            font-weight: 800;
            font-size: .88rem;
            cursor: pointer;
            transition: all .3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(253, 185, 19, .4);
        }

        .btn-edit {
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 5px 14px;
            font-size: .79rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
        }

        .btn-edit:hover {
            transform: translateY(-1px);
        }

        .btn-del {
            background: rgba(196, 30, 36, .08);
            color: #C41E24;
            border: 1px solid rgba(196, 30, 36, .2);
            border-radius: 8px;
            padding: 5px 14px;
            font-size: .79rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
        }

        .btn-del:hover {
            background: #C41E24;
            color: white;
        }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(44, 62, 143, .2);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 16px 16px 0 0;
        }

        .modal-title {
            font-weight: 800;
        }

        .btn-close {
            filter: invert(1);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            font-size: .88rem;
        }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border-light);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: .9rem;
            transition: border .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(44, 62, 143, .1);
        }

        .btn-modal-submit {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 700;
            transition: all .3s;
        }

        .btn-modal-cancel {
            background: transparent;
            color: #64748b;
            border: 1.5px solid var(--border-light);
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
        }

        /* Chart */
        .chart-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
            border: 1px solid var(--border-light);
            padding: 24px;
            margin-bottom: 28px;
        }

        .chart-title {
            font-size: .85rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 16px;
        }

        /* Alerts */
        .alert-success-c {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: none;
            border-left: 4px solid var(--primary-blue);
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 20px;
        }

        .alert-danger-c {
            background: #fef2f2;
            color: #991b1b;
            border: none;
            border-left: 4px solid #C41E24;
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 20px;
        }

        .section-tab {
            display: none;
        }

        .section-tab.active {
            display: block;
        }

        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, .75);
            text-align: center;
            padding: 18px 0;
            font-size: .85rem;
            margin-top: auto;
        }

        .footer-strip strong {
            color: white;
        }

        /* Toast Notification */
        .toast-msg { position: fixed; top: 22px; right: 22px; padding: 14px 24px; border-radius: 12px; color: white; font-weight: 600; z-index: 9999; font-size: 0.88rem; animation: slideIn 0.3s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.18); display: flex; align-items: center; gap: 10px; overflow: hidden; }
        .toast-success { background: var(--primary-blue); }
        .toast-error   { background: #C41E24; }
        .toast-timer { height: 3px; width: 100%; background: var(--secondary-yellow); position: absolute; bottom: 0; left: 0; border-radius: 0 0 12px 12px; animation: timerShrink 3.5s linear; }
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes timerShrink { from { width: 100%; } to { width: 0%; } }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD"
                    style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data
                            Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                        <div class="user-info">
                            <span>{{ Auth::user()->full_name }}</span>
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
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('admin.data.dashboard') }}#return" class="back-link">&#8592; Data Management</a>
            <div class="hero-badge">Data Management</div>
            <h1>Municipality Yearly Data</h1>
            <div class="hero-divider"></div>
            <p>Manage population and household summary records per year for <strong>{{ $municipality->name }}</strong>.
            </p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">

            <div id="toast" class="toast-msg" style="display:none;"></div>

            @include('components.admin-notification')
            @if($errors->any())
                <div class="alert-danger-c">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Action Buttons Row -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Tab Pills -->
                <div class="tab-pills" style="margin-bottom:0;">
                    <button class="tab-pill active" onclick="switchTab('records', this)">📋 Yearly Records</button>
                    <button class="tab-pill" onclick="switchTab('analysis', this)">📊 Analysis</button>
                </div>
                
                <!-- Import/Export Buttons -->
                <div class="d-flex gap-2">
                    <button class="btn btn-sm" onclick="openCsvModal('import')" style="background:var(--primary-blue);color:white;border:none;border-radius:8px;padding:8px 16px;font-weight:700;font-size:0.85rem;">
                        <i class="bi bi-upload"></i> Import CSV
                    </button>
                    <button class="btn btn-sm" onclick="openCsvModal('export')" style="background:var(--secondary-yellow);color:#333;border:none;border-radius:8px;padding:8px 16px;font-weight:700;font-size:0.85rem;">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>
            </div>

            <!-- ===== RECORDS TAB ===== -->
            <div class="section-tab active" id="tab-records">
                <div class="panel-card">
                    <div class="panel-header">
                        <div class="d-flex align-items-center gap-2">
                            <h5>Yearly Summary Records — {{ $municipality->name }}</h5>
                            <span class="count-badge">{{ $summaries->count() }} year records</span>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Year
                                Data</button>
                            @if($archivedSummaries->count() > 0)
                                <button class="btn btn-sm" onclick="openArchivedYearlyModal()" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.4);border-radius:8px;padding:6px 16px;font-weight:700;font-size:0.82rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                                    Archived
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="table-responsive">
                        @if($summaries->isEmpty())
                            <div style="padding:36px;text-align:center;color:#94a3b8;font-size: .85rem;">
                                <div style="font-size:2.5rem;opacity:.3;margin-bottom:8px;"></div>
                                No records yet. Click <strong>"+ Add Year Data"</strong> to add your first yearly summary.
                            </div>
                        @else
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Population</th>
                                        <th>Male</th>
                                        <th>Female</th>
                                        <th style="text-align:center;">Age 0–19</th>
                                        <th style="text-align:center;">Age 20–59</th>
                                        <th style="text-align:center;">Age 60+</th>
                                        <th>Households</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summaries as $row)
                                        <tr>
                                            <td><strong>{{ $row->year }}</strong></td>
                                            <td>{{ number_format($row->total_population) }}</td>
                                            <td>{{ number_format($row->male_population ?? 0) }}</td>
                                            <td>{{ number_format($row->female_population ?? 0) }}</td>
                                            <td style="text-align:center;font-size:.82rem;color:#64748b;">
                                                {{ number_format($row->population_0_19 ?? 0) }}</td>
                                            <td style="text-align:center;font-size:.82rem;color:#64748b;">
                                                {{ number_format($row->population_20_59 ?? 0) }}</td>
                                            <td style="text-align:center;font-size:.82rem;color:#64748b;">
                                                {{ number_format($row->population_60_100 ?? 0) }}</td>
                                            <td>{{ number_format($row->total_households) }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn-edit" data-bs-toggle="modal"
                                                        data-bs-target="#editModal{{ $row->id }}">Edit</button>
                                                    <form method="POST"
                                                        action="{{ route('admin.data.yearly.archive', $row->id) }}"
                                                        onsubmit="return confirm('Archive the {{ $row->year }} record? You can restore it later.')">
                                                        @csrf
                                                        <button type="submit" class="btn-del"
                                                            style="background:rgba(245,158,11,.1);color:#b45309;border-color:rgba(245,158,11,.3);">Archive</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal for this row -->
                                        <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit {{ $municipality->name }} –
                                                            {{ $row->year }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.data.yearly.save') }}">
                                                        @csrf
                                                        <input type="hidden" name="year" value="{{ $row->year }}">
                                                        <div class="modal-body p-4">
                                                            <div class="row g-3">
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Total Population</label>
                                                                    <input type="number" name="total_population"
                                                                        class="form-control"
                                                                        value="{{ $row->total_population }}" required min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Male Population</label>
                                                                    <input type="number" name="male_population"
                                                                        class="form-control"
                                                                        value="{{ $row->male_population ?? 0 }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Female Population</label>
                                                                    <input type="number" name="female_population"
                                                                        class="form-control"
                                                                        value="{{ $row->female_population ?? 0 }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Age 0–19</label>
                                                                    <input type="number" name="population_0_19"
                                                                        class="form-control"
                                                                        value="{{ $row->population_0_19 ?? 0 }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Age 20–59</label>
                                                                    <input type="number" name="population_20_59"
                                                                        class="form-control"
                                                                        value="{{ $row->population_20_59 ?? 0 }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Age 60+</label>
                                                                    <input type="number" name="population_60_100"
                                                                        class="form-control"
                                                                        value="{{ $row->population_60_100 ?? 0 }}" min="0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Total Households</label>
                                                                    <input type="number" name="total_households"
                                                                        class="form-control"
                                                                        value="{{ $row->total_households }}" required min="0">
                                                                </div>
                                                                <input type="hidden" name="total_pwd"
                                                                    value="{{ $row->total_pwd }}">
                                                                <input type="hidden" name="total_aics"
                                                                    value="{{ $row->total_aics }}">
                                                                <input type="hidden" name="total_solo_parent"
                                                                    value="{{ $row->total_solo_parent }}">
                                                                <input type="hidden" name="total_4ps"
                                                                    value="{{ $row->total_4ps }}">
                                                                <input type="hidden" name="total_senior"
                                                                    value="{{ $row->total_senior }}">
                                                                <input type="hidden" name="total_slp"
                                                                    value="{{ $row->total_slp }}">
                                                                <input type="hidden" name="total_esa"
                                                                    value="{{ $row->total_esa }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 px-4 pb-4 gap-2">
                                                            <button type="button" class="btn-modal-cancel"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn-modal-submit">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <!-- Recent Import History -->
                <div class="panel-card mt-4">
                    <div class="panel-header">
                        <div>
                            <div class="panel-header-title"><i class="bi bi-clock-history"></i> Recent Import History</div>
                            <div class="panel-header-sub" style="font-size:0.75rem;opacity:0.75;margin-top:2px;">Last 5 CSV import operations</div>
                        </div>
                        <button class="btn btn-sm" onclick="openArchivedImportModal()" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.4);border-radius:8px;padding:6px 16px;font-weight:700;font-size:0.82rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                            Archived
                        </button>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        @if($importLogs->isEmpty())
                            <div style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">
                                <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.5;"></i>
                                No import history yet
                            </div>
                        @else
                            <div class="table-responsive">
                                <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                                    <thead>
                                        <tr style="background:#f8fafc;">
                                            <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Date/Time</th>
                                            <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">File Name</th>
                                            <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Type</th>
                                            <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Total</th>
                                            <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Success</th>
                                            <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Failed</th>
                                            <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Status</th>
                                            <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($importLogs as $log)
                                        <tr style="border-bottom:1px solid #E2E8F0;">
                                            <td style="padding:12px 16px;color:#334155;font-size:0.82rem;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                            <td style="padding:12px 16px;color:#334155;font-size:0.82rem;"><i class="bi bi-file-earmark-text" style="color:#64748b;margin-right:6px;"></i>{{ $log->file_name }}</td>
                                            <td style="padding:12px 16px;"><span style="background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ str_replace('_', ' ', $log->file_type) }}</span></td>
                                            <td style="padding:12px 16px;text-align:center;color:#334155;font-weight:600;">{{ $log->total_rows }}</td>
                                            <td style="padding:12px 16px;text-align:center;color:#15803d;font-weight:700;">{{ $log->successful_rows }}</td>
                                            <td style="padding:12px 16px;text-align:center;color:#dc2626;font-weight:700;">{{ $log->failed_rows }}</td>
                                            <td style="padding:12px 16px;text-align:center;">
                                                @if($log->status == 'completed')
                                                    <span style="background:#d4edda;color:#155724;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ $log->status }}</span>
                                                @elseif($log->status == 'failed')
                                                    <span style="background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ $log->status }}</span>
                                                @else
                                                    <span style="background:#fff3cd;color:#856404;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ $log->status }}</span>
                                                @endif
                                            </td>
                                            <td style="padding:12px 16px;text-align:center;">
                                                <button class="btn btn-sm" onclick="archiveImportLog({{ $log->id }})" style="background:#fce8e8;color:#C41E24;border:none;border-radius:20px;padding:4px 14px;font-size:0.76rem;font-weight:800;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#C41E24';this.style.color='white'" onmouseout="this.style.background='#fce8e8';this.style.color='#C41E24'">
                                                    <i class="bi bi-archive"></i> Archive
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ===== ANALYSIS TAB ===== -->
            <div class="section-tab" id="tab-analysis">
                @php $cd = $chartData[$municipality->name] ?? ['years' => [], 'population' => [], 'households' => []]; @endphp

                @if(empty($cd['years']))
                    <div class="panel-card" style="padding:36px;text-align:center;color:#94a3b8;">
                        <div style="font-size:2.5rem;opacity:.3;margin-bottom:8px;">📊</div>
                        No data yet to display charts. Add some yearly records first.
                    </div>
                @else
                    <div class="chart-card">
                        <div class="chart-title">{{ $municipality->name }} — Population &amp; Households per Year</div>
                        <canvas id="popChart" height="90"></canvas>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- ===== ADD MODAL ===== -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Year Data — {{ $municipality->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.data.yearly.save') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" required>
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Population</label>
                                <input type="number" name="total_population" class="form-control" required min="0"
                                    placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Male Population</label>
                                <input type="number" name="male_population" class="form-control" min="0"
                                    placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Female Population</label>
                                <input type="number" name="female_population" class="form-control" min="0"
                                    placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age 0–19</label>
                                <input type="number" name="population_0_19" class="form-control" min="0" placeholder="0"
                                    value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age 20–59</label>
                                <input type="number" name="population_20_59" class="form-control" min="0"
                                    placeholder="0" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age 60+</label>
                                <input type="number" name="population_60_100" class="form-control" min="0"
                                    placeholder="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Households</label>
                                <input type="number" name="total_households" class="form-control" required min="0"
                                    placeholder="0">
                            </div>
                            <input type="hidden" name="total_pwd" value="0">
                            <input type="hidden" name="total_aics" value="0">
                            <input type="hidden" name="total_solo_parent" value="0">
                            <input type="hidden" name="total_4ps" value="0">
                            <input type="hidden" name="total_senior" value="0">
                            <input type="hidden" name="total_slp" value="0">
                            <input type="hidden" name="total_esa" value="0">
                        </div>
                        <small class="text-muted mt-2 d-block">💡 If a record for this year already exists, it will be
                            updated automatically.</small>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== ARCHIVED RECORDS MODAL ===== -->
    <div class="modal fade" id="archiveModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" style="font-weight:800;"> Archived Year Records</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    @if($archivedSummaries->isEmpty())
                        <div style="padding:48px;text-align:center;color:#94a3b8;">
                            <div style="font-size:2.5rem;opacity:.3;margin-bottom:8px;"></div>
                            No archived records found.
                        </div>
                    @else
                    
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Year</th>
                                    <th>Population</th>
                                    <th>Households</th>
                                    <th>Archived On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($archivedSummaries as $archived)
                                    <tr>
                                        <td><strong>{{ $archived->year }}</strong></td>
                                        <td>{{ number_format($archived->total_population) }}</td>
                                        <td>{{ number_format($archived->total_households) }}</td>
                                        <td style="font-size:.8rem;color:#64748b;">{{ $archived->deleted_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                {{-- Restore --}}
                                                <form method="POST"
                                                    action="{{ route('admin.data.yearly.restore', $archived->id) }}">
                                                    @csrf
                                                    <button type="submit" class="btn-edit"
                                                        style="background:rgba(16,185,129,.12);color:#065f46;border:1px solid rgba(16,185,129,.3);">Restore</button>
                                                </form>
                                                {{-- Permanently Delete --}}
                                                <form method="POST"
                                                    action="{{ route('admin.data.yearly.forceDelete', $archived->id) }}"
                                                    onsubmit="return confirm('Permanently delete the {{ $archived->year }} record? This CANNOT be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-del">Delete Forever</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer border-0 px-4 pb-3">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <!-- CSV Import/Export Modal -->
    <div class="modal fade" id="csvModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" id="csvModalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:2rem;">
                    <!-- Import Section -->
                    <div id="importSection" style="display:none;">
                        <form action="{{ route('admin.csv.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                            @csrf
                            <input type="hidden" name="import_type" value="municipality_data">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Year (Optional)</label>
                                <select name="year" class="form-select" style="border:1.5px solid var(--border-light);border-radius:10px;">
                                    <option value="">All years in CSV file</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }} only</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave as "All years" to import all data from CSV, or select a specific year to filter</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Upload CSV File</label>
                                <input type="file" name="csv_file" class="form-control" accept=".csv" required style="border:1.5px solid var(--border-light);border-radius:10px;">
                                <small class="text-muted">Maximum file size: 10MB</small>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="importBtn" style="background:var(--primary-gradient);border:none;border-radius:10px;padding:12px;font-weight:700;">
                                    <i class="bi bi-upload"></i> Import Data
                                </button>
                            </div>
                        </form>
                        <div class="mt-4 pt-3" style="border-top:1px solid #e9ecef;">
                            <h6 style="font-weight:700;font-size:0.85rem;color:var(--primary-blue);"><i class="bi bi-file-earmark-arrow-down"></i> Download Template</h6>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                <a href="{{ route('admin.csv.template', 'municipality_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Municipality Template
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Export Section -->
                    <div id="exportSection" style="display:none;">
                        <form action="{{ route('admin.csv.export') }}" method="POST" id="exportForm">
                            @csrf
                            <input type="hidden" name="export_type" value="municipality_data">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Filter by Year (Optional)</label>
                                <select name="year" class="form-select" style="border:1.5px solid var(--border-light);border-radius:10px;">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="alert" style="background:#e0f2fe;border-left:4px solid #0369a1;color:#0c4a6e;font-size:0.85rem;">
                                <i class="bi bi-info-circle"></i> <strong>Municipality:</strong> {{ $municipality->name }}
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn" style="background:var(--secondary-yellow);color:#333;border:none;border-radius:10px;padding:12px;font-weight:700;">
                                    <i class="bi bi-download"></i> Export to CSV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Import Log Confirmation Modal -->
    <div class="modal fade" id="archiveImportLogModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-archive"></i> Confirm Archive</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2rem;">
                <div style="text-align:center;padding:20px;">
                    <div style="font-size:3rem;color:#C41E24;margin-bottom:16px;"></div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--primary-blue);margin-bottom:8px;">Archive this import log?</div>
                    <div style="font-size:0.88rem;color:#64748b;">This will remove the log from the list. This action cannot be undone.</div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 2rem 2rem;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-light);border:1.5px solid var(--border-light);color:#64748b;border-radius:10px;padding:10px;font-weight:700;font-size:0.88rem;flex:1;">Cancel</button>
                <button type="button" onclick="confirmArchiveImportLog()" style="background:#C41E24;color:white;border:none;border-radius:10px;padding:10px;font-weight:800;font-size:0.85rem;flex:1;">Archive</button>
            </div>
        </div></div>
    </div>

    <!-- Archived Import Logs Modal -->
    <div class="modal fade" id="archivedImportLogsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-archive"></i> Archived Import History</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:0;">
                <div class="table-responsive">
                    <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Date/Time</th>
                                <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">File Name</th>
                                <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Type</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Total</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Success</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Failed</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Status</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="archivedImportLogsBody">
                            <tr>
                                <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">
                                    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.5;"></i>
                                    No archived logs
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div></div>
    </div>

    <!-- Restore Import Log Confirmation Modal -->
    <div class="modal fade" id="restoreImportLogModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-arrow-counterclockwise"></i> Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2rem;">
                <div style="text-align:center;padding:20px;">
                    <div style="font-size:3rem;color:#15803d;margin-bottom:16px;"></div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--primary-blue);margin-bottom:8px;">Restore this import log?</div>
                    <div style="font-size:0.88rem;color:#64748b;">This will move the log back to the active list.</div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 2rem 2rem;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-light);border:1.5px solid var(--border-light);color:#64748b;border-radius:10px;padding:10px;font-weight:700;font-size:0.88rem;flex:1;">Cancel</button>
                <button type="button" onclick="confirmRestoreImportLog()" class="btn" style="background:#15803d;color:white;border:none;border-radius:10px;padding:10px;font-weight:800;font-size:0.85rem;flex:1;">Restore</button>
            </div>
        </div></div>
    </div>

    <!-- Delete Import Log Confirmation Modal -->
    <div class="modal fade" id="deleteImportLogModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-trash"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2rem;">
                <div style="text-align:center;padding:20px;">
                    <div style="font-size:3rem;color:#C41E24;margin-bottom:16px;"></div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--primary-blue);margin-bottom:8px;">Permanently delete this log?</div>
                    <div style="font-size:0.88rem;color:#64748b;">This action cannot be undone. The log will be permanently removed.</div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 2rem 2rem;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-light);border:1.5px solid var(--border-light);color:#64748b;border-radius:10px;padding:10px;font-weight:700;font-size:0.88rem;flex:1;">Cancel</button>
                <button type="button" onclick="confirmDeleteImportLog()" class="btn" style="background:#C41E24;color:white;border:none;border-radius:10px;padding:10px;font-weight:800;font-size:0.85rem;flex:1;">Delete</button>
            </div>
        </div></div>
    </div>

    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // Toast helper
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.innerHTML = `<div class="toast-timer"></div><span>${message}</span>`;
            toast.className = `toast-msg toast-${type}`;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3500);
        }

        // CSV Modal
        function openCsvModal(type) {
            const modal = new bootstrap.Modal(document.getElementById('csvModal'));
            const title = document.getElementById('csvModalTitle');
            const importSection = document.getElementById('importSection');
            const exportSection = document.getElementById('exportSection');
            
            if (type === 'import') {
                title.innerHTML = '<i class="bi bi-upload"></i> Import Municipality CSV Data';
                importSection.style.display = 'block';
                exportSection.style.display = 'none';
            } else {
                title.innerHTML = '<i class="bi bi-download"></i> Export Municipality CSV Data';
                importSection.style.display = 'none';
                exportSection.style.display = 'block';
            }
            
            modal.show();
        }

        // Handle import form submission
        document.getElementById('importForm').addEventListener('submit', function() {
            const btn = document.getElementById('importBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
        });

        // Import Log Archive
        let archiveLogId = null;

        function archiveImportLog(id) {
            archiveLogId = id;
            new bootstrap.Modal(document.getElementById('archiveImportLogModal')).show();
        }

        function confirmArchiveImportLog() {
            if (!archiveLogId) return;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('archiveImportLogModal'));
            modal.hide();
            
            fetch(`/admin/csv/import-log/${archiveLogId}/archive`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Import log archived successfully', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 3500);
                } else {
                    showToast(data.message || 'Failed to archive log', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error archiving log', 'error');
            })
            .finally(() => {
                archiveLogId = null;
            });
        }

        // Restore scroll position after page load
        window.addEventListener('load', function() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('scrollPosition');
            }
        });

        // Archived Import Logs Modal
        function openArchivedImportModal() {
            const modal = new bootstrap.Modal(document.getElementById('archivedImportLogsModal'));
            modal.show();
            
            const tbody = document.getElementById('archivedImportLogsBody');
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>`;
            
            fetch('{{ route("admin.csv.import-log.archived") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.logs.length > 0) {
                        let html = '';
                        data.logs.forEach(log => {
                            const date = new Date(log.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                            const statusBg = log.status === 'completed' ? '#d4edda' : (log.status === 'failed' ? '#f8d7da' : '#fff3cd');
                            const statusColor = log.status === 'completed' ? '#155724' : (log.status === 'failed' ? '#721c24' : '#856404');
                            
                            html += `<tr style="border-bottom:1px solid #E2E8F0;">
                                <td style="padding:12px 16px;color:#334155;font-size:0.82rem;">${date}</td>
                                <td style="padding:12px 16px;color:#334155;font-size:0.82rem;"><i class="bi bi-file-earmark-text" style="color:#64748b;margin-right:6px;"></i>${log.file_name}</td>
                                <td style="padding:12px 16px;"><span style="background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">${log.file_type.replace(/_/g, ' ')}</span></td>
                                <td style="padding:12px 16px;text-align:center;color:#334155;font-weight:600;">${log.total_rows}</td>
                                <td style="padding:12px 16px;text-align:center;color:#15803d;font-weight:700;">${log.successful_rows}</td>
                                <td style="padding:12px 16px;text-align:center;color:#dc2626;font-weight:700;">${log.failed_rows}</td>
                                <td style="padding:12px 16px;text-align:center;"><span style="background:${statusBg};color:${statusColor};padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">${log.status}</span></td>
                                <td style="padding:12px 16px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;">
                                        <button onclick="restoreImportLog(${log.id})" class="btn btn-sm" style="background:#d4edda;color:#155724;border:none;border-radius:6px;padding:3px 8px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#155724';this.style.color='white'" onmouseout="this.style.background='#d4edda';this.style.color='#155724'">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                        <button onclick="deleteImportLog(${log.id})" class="btn btn-sm" style="background:#f8d7da;color:#721c24;border:none;border-radius:6px;padding:3px 8px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#721c24';this.style.color='white'" onmouseout="this.style.background='#f8d7da';this.style.color='#721c24'">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        tbody.innerHTML = html;
                    } else {
                        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;"><i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.5;"></i>No archived logs</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#dc2626;font-size:0.88rem;">Error loading archived logs</td></tr>`;
                });
        }

        let pendingRestoreImportId = null;
        let pendingDeleteImportId = null;

        function restoreImportLog(id) {
            pendingRestoreImportId = id;
            const modal = new bootstrap.Modal(document.getElementById('restoreImportLogModal'));
            modal.show();
        }

        function confirmRestoreImportLog() {
            if (!pendingRestoreImportId) return;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('restoreImportLogModal'));
            modal.hide();
            
            fetch(`/admin/csv/import-log/${pendingRestoreImportId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const archivedModal = bootstrap.Modal.getInstance(document.getElementById('archivedImportLogsModal'));
                    if (archivedModal) {
                        archivedModal.hide();
                    }
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    
                    showToast('Import log restored successfully', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 3500);
                } else {
                    showToast(data.message || 'Failed to restore log', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error restoring log', 'error');
            })
            .finally(() => {
                pendingRestoreImportId = null;
            });
        }

        function deleteImportLog(id) {
            pendingDeleteImportId = id;
            const modal = new bootstrap.Modal(document.getElementById('deleteImportLogModal'));
            modal.show();
        }

        function confirmDeleteImportLog() {
            if (!pendingDeleteImportId) return;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteImportLogModal'));
            modal.hide();
            
            fetch(`/admin/csv/import-log/${pendingDeleteImportId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const archivedModal = bootstrap.Modal.getInstance(document.getElementById('archivedImportLogsModal'));
                    if (archivedModal) {
                        archivedModal.hide();
                    }
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    
                    showToast('Import log permanently deleted', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 3500);
                } else {
                    showToast(data.message || 'Failed to delete log', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error deleting log', 'error');
            })
            .finally(() => {
                pendingDeleteImportId = null;
            });
        }

        // Show notification on page load if exists
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        // Open Archived Yearly Records Modal
        function openArchivedYearlyModal() {
            const modal = new bootstrap.Modal(document.getElementById('archiveModal'));
            modal.show();
        }
        function switchTab(name, el) {
            document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pill').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            el.classList.add('active');
            if (name === 'analysis') initCharts();
        }

        const chartData = @json($chartData);
        const muniName = @json($municipality->name);
        let chartsInited = false;

        function initCharts() {
            if (chartsInited) return;
            chartsInited = true;

            const cd = chartData[muniName];
            if (!cd || !cd.years.length) return;

            // Get colors from CSS variables
            const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-blue').trim();
            const secondaryColor = getComputedStyle(document.documentElement).getPropertyValue('--secondary-yellow').trim();
            const accentColor = getComputedStyle(document.documentElement).getPropertyValue('--accent-red').trim();

            // Population & Households Chart only
            new Chart(document.getElementById('popChart'), {
                type: 'bar',
                data: {
                    labels: cd.years,
                    datasets: [
                        {
                            label: 'Population',
                            data: cd.population,
                            backgroundColor: primaryColor,
                            borderRadius: 6,
                            barPercentage: 0.7
                        },
                        {
                            label: 'Households',
                            data: cd.households,
                            backgroundColor: secondaryColor,
                            borderRadius: 6,
                            barPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } }
                    }
                }
            });
        }
    </script>
</body>

</html>