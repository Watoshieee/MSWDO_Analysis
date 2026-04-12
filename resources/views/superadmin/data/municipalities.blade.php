<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Municipality Data – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
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
        }

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
            gap: 10px;
        }

        .nav-link {
            color: rgba(255, 255, 255, .88) !important;
            font-weight: 600;
            transition: all .25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: .95rem;
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
            font-size: .92rem;
            font-weight: 500;
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
            gap: 6px;
            color: rgba(255, 255, 255, .75);
            font-size: .85rem;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 14px;
            transition: color .2s;
        }

        .back-link:hover {
            color: var(--secondary-yellow);
        }

        .main-content {
            flex: 1;
        }

        /* Tab nav */
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
        }

        .panel-header h5 {
            font-weight: 800;
            margin: 0;
            font-size: 1.05rem;
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

        .btn-archive-view {
            background: transparent;
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: .85rem;
            cursor: pointer;
            transition: all .3s;
        }

        .btn-archive-view:hover {
            background: var(--primary-blue);
            color: white;
        }

        .btn-restore {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 14px;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
        }

        .btn-restore:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, .4);
        }

        .btn-perm-del {
            background: rgba(196, 30, 36, .1);
            color: #C41E24;
            border: 1px solid rgba(196, 30, 36, .2);
            border-radius: 8px;
            padding: 5px 14px;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
        }

        .btn-perm-del:hover {
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
            font-size: .95rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 16px;
        }

        /* Muni badge */
        .muni-pill {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
        }

        .muni-Magdalena {
            background: #EFF6FF;
            color: #1D4ED8;
        }

        .muni-Liliw {
            background: #F0FDF4;
            color: #15803D;
        }

        .muni-Majayjay {
            background: #FFF7ED;
            color: #C2410C;
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

        .alert-success-custom {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: none;
            border-left: 4px solid var(--primary-blue);
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 20px;
        }

        .alert-danger-custom {
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('superadmin.data.dashboard') }}">Data
                            Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Public View</a></li>
                </ul>
                <div class="d-flex">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('superadmin.data.dashboard') }}" class="back-link">&#8592; Back to Data Management</a>
            <div class="hero-badge">Data Management</div>
            <h1>Municipality Yearly Data</h1>
            <div class="hero-divider"></div>
            <p>Manage and analyze population, household, and program data per municipality per year.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">

            @if(session('success'))
                <div class="alert-success-custom">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-danger-custom">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Tab Pills -->
            <div class="tab-pills">
                <button class="tab-pill active" onclick="switchTab('records')">📋 Records</button>
                <button class="tab-pill" onclick="switchTab('monthly')">📅 Monthly</button>
                <button class="tab-pill" onclick="switchTab('analysis')">📊 Analysis</button>
            </div>

            <!-- ===================== RECORDS TAB ===================== -->
            <div class="section-tab active" id="tab-records">
                <!-- Add Button + Panel -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold" style="color:var(--primary-blue);">Yearly Municipality Summary Records</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <button class="btn-archive-view" data-bs-toggle="modal" data-bs-target="#archivedSummaryModal">
                            🗂 Archived (<span id="archivedSummaryCount">...</span>)
                        </button>
                        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Year
                            Data</button>
                    </div>
                </div>

                @foreach($coreNames as $muniName)
                    @php $muniRows = $summaries->get($muniName, collect()); @endphp
                    <div class="panel-card">
                        <div class="panel-header">
                            <h5>{{ $muniName }}</h5>
                            <span class="count-badge">{{ $muniRows->count() }} year records</span>
                        </div>
                        <div class="table-responsive">
                            @if($muniRows->isEmpty())
                                <div style="padding:32px;text-align:center;color:#94a3b8;font-size:.9rem;">
                                    No records yet for {{ $muniName }}. Click "+ Add Year Data" to add one.
                                </div>
                            @else
                                <table class="premium-table">
                                    <thead>
                                        <tr>
                                            <th>Year</th>
                                            <th>Population</th>
                                            <th style="text-align:center;">Age 0–19</th>
                                            <th style="text-align:center;">Age 20–59</th>
                                            <th style="text-align:center;">Age 60+</th>
                                            <th>Households</th>
                                            <th>PWD</th>
                                            <th>AICS</th>
                                            <th>Solo Parent</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($muniRows as $row)
                                            <tr>
                                                <td><strong>{{ $row->year }}</strong></td>
                                                <td>{{ number_format($row->total_population) }}</td>
                                                <td style="text-align:center;font-size:.82rem;color:#64748b;">
                                                    {{ number_format($row->population_0_19 ?? 0) }}</td>
                                                <td style="text-align:center;font-size:.82rem;color:#64748b;">
                                                    {{ number_format($row->population_20_59 ?? 0) }}</td>
                                                <td style="text-align:center;font-size:.82rem;color:#64748b;">
                                                    {{ number_format($row->population_60_100 ?? 0) }}</td>
                                                <td>{{ number_format($row->total_households) }}</td>
                                                <td>{{ number_format($row->total_pwd) }}</td>
                                                <td>{{ number_format($row->total_aics) }}</td>
                                                <td>{{ number_format($row->total_solo_parent) }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn-edit" data-bs-toggle="modal"
                                                            data-bs-target="#editModal{{ $row->id }}">Edit</button>
                                                        <button class="btn-del"
                                                            onclick="archiveSummary({{ $row->id }}, '{{ $muniName }} {{ $row->year }}')">Archive</button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit {{ $muniName }} – {{ $row->year }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST"
                                                            action="{{ route('superadmin.data.municipalities.summary.save') }}">
                                                            @csrf
                                                            <input type="hidden" name="municipality"
                                                                value="{{ $row->municipality }}">
                                                            <input type="hidden" name="year" value="{{ $row->year }}">
                                                            <div class="modal-body p-4">
                                                                <div class="row g-3">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Total Population</label>
                                                                        <input type="number" name="total_population"
                                                                            class="form-control"
                                                                            value="{{ $row->total_population }}" required min="0">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Total Households</label>
                                                                        <input type="number" name="total_households"
                                                                            class="form-control"
                                                                            value="{{ $row->total_households }}" required min="0">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Population Age 0–19</label>
                                                                        <input type="number" name="population_0_19"
                                                                            class="form-control"
                                                                            value="{{ $row->population_0_19 ?? 0 }}" min="0">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Population Age 20–59</label>
                                                                        <input type="number" name="population_20_59"
                                                                            class="form-control"
                                                                            value="{{ $row->population_20_59 ?? 0 }}" min="0">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Population Age 60+</label>
                                                                        <input type="number" name="population_60_100"
                                                                            class="form-control"
                                                                            value="{{ $row->population_60_100 ?? 0 }}" min="0">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">PWD Assistance</label>
                                                                        <input type="number" name="total_pwd" class="form-control"
                                                                            value="{{ $row->total_pwd }}" min="0">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">AICS</label>
                                                                        <input type="number" name="total_aics" class="form-control"
                                                                            value="{{ $row->total_aics }}" min="0">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label">Solo Parent</label>
                                                                        <input type="number" name="total_solo_parent"
                                                                            class="form-control"
                                                                            value="{{ $row->total_solo_parent }}" min="0">
                                                                    </div>
                                                                    {{-- Preserve existing values silently --}}
                                                                    <input type="hidden" name="total_4ps"
                                                                        value="{{ $row->total_4ps }}">
                                                                    <input type="hidden" name="total_senior"
                                                                        value="{{ $row->total_senior }}">
                                                                    <input type="hidden" name="total_esa"
                                                                        value="{{ $row->total_esa }}">
                                                                    <input type="hidden" name="total_slp"
                                                                        value="{{ $row->total_slp }}">
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
                @endforeach
            </div>

            <!-- ===================== MONTHLY TAB ===================== -->
            <div class="section-tab" id="tab-monthly">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold" style="color:var(--primary-blue);">Monthly Beneficiary Records</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <button class="btn-archive-view" data-bs-toggle="modal"
                            data-bs-target="#archivedMonthlyModal">🗂 Archived Monthly</button>
                        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addMonthlyModal">+ Add Monthly
                            Data</button>
                    </div>
                </div>

                @foreach($coreNames as $muniName)
                    @php $mRows = $monthlySummaries->get($muniName, collect()); @endphp
                    <div class="panel-card mb-3">
                        <div class="panel-header">
                            <h5>{{ $muniName }}</h5>
                            <span class="count-badge">{{ $mRows->count() }} monthly records</span>
                        </div>
                        @if($mRows->isEmpty())
                            <div style="padding:28px;text-align:center;color:#94a3b8;font-size:.9rem;">No monthly records yet
                                for {{ $muniName }}. Click "+ Add Monthly Data" to start tracking.</div>
                        @else
                            <div class="table-responsive">
                                <table class="premium-table">
                                    <thead>
                                        <tr>
                                            <th>Year</th>
                                            <th>Month</th>
                                            <th>PWD</th>
                                            <th>AICS</th>
                                            <th>Solo Parent</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mRows as $mr)
                                            @php $monthLabel = \Carbon\Carbon::createFromDate(null, $mr->month, 1)->format('F'); @endphp
                                            <tr>
                                                <td><strong>{{ $mr->year }}</strong></td>
                                                <td>{{ $monthLabel }}</td>
                                                <td>{{ number_format($mr->total_pwd) }}</td>
                                                <td>{{ number_format($mr->total_aics) }}</td>
                                                <td>{{ number_format($mr->total_solo_parent) }}</td>
                                                <td style="font-size:.82rem;color:#64748b;">{{ $mr->notes ?: '—' }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button class="btn-edit"
                                                            onclick="openEditMonthly({{ $mr->id }}, {{ $mr->total_pwd }}, {{ $mr->total_aics }}, {{ $mr->total_solo_parent }})">Edit</button>
                                                        <button class="btn-del"
                                                            onclick="archiveMonthly({{ $mr->id }}, '{{ $muniName }} {{ $monthLabel }} {{ $mr->year }}')">Archive</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach

                {{-- Monthly trend chart for selected year --}}
                <div class="chart-card mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="chart-title mb-0">Monthly Program Beneficiaries</div>
                        <form method="GET" action="" class="d-flex gap-2 align-items-center">
                            <label class="form-label mb-0 fw-bold" style="font-size:.85rem;">Year:</label>
                            <select name="chart_year" class="form-select form-select-sm" style="width:auto;"
                                onchange="this.form.submit()">
                                @foreach($availableYears as $ay)
                                    <option value="{{ $ay }}" {{ $ay == $selectedYear ? 'selected' : '' }}>{{ $ay }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="#monthly" value="1">
                        </form>
                    </div>
                    <canvas id="monthlyTrendChart" height="100"></canvas>
                </div>
            </div>

            <!-- ===================== ANALYSIS TAB ===================== -->
            <div class="section-tab" id="tab-analysis">
                @foreach($coreNames as $muniName)
                    @php $cd = $chartData[$muniName]; @endphp
                    @if(!empty($cd['years']))
                        <div class="chart-card">
                            <div class="chart-title">{{ $muniName }} — Population & Households per Year</div>
                            <canvas id="chart-{{ $muniName }}" height="80"></canvas>
                        </div>
                    @endif
                @endforeach

                <!-- Combined comparison chart -->
                <div class="chart-card">
                    <div class="chart-title">All Municipalities — Population Comparison</div>
                    <canvas id="chart-comparison" height="90"></canvas>
                </div>
            </div>

        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Municipality Year Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('superadmin.data.municipalities.summary.save') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Municipality</label>
                                <select name="municipality" class="form-select" required>
                                    <option value="">Select Municipality</option>
                                    @foreach($coreNames as $n)<option value="{{ $n }}">{{ $n }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" required>
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)<option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Population</label>
                                <input type="number" name="total_population" class="form-control" required min="0"
                                    placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Households</label>
                                <input type="number" name="total_households" class="form-control" required min="0"
                                    placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Population Age 0–19</label>
                                <input type="number" name="population_0_19" class="form-control" min="0" placeholder="0"
                                    value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Population Age 20–59</label>
                                <input type="number" name="population_20_59" class="form-control" min="0"
                                    placeholder="0" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Population Age 60+</label>
                                <input type="number" name="population_60_100" class="form-control" min="0"
                                    placeholder="0" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PWD Assistance</label>
                                <input type="number" name="total_pwd" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">AICS</label>
                                <input type="number" name="total_aics" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Solo Parent</label>
                                <input type="number" name="total_solo_parent" class="form-control" min="0"
                                    placeholder="0">
                            </div>
                            {{-- Hidden defaults for removed fields --}}
                            <input type="hidden" name="total_4ps" value="0">
                            <input type="hidden" name="total_senior" value="0">
                            <input type="hidden" name="total_esa" value="0">
                            <input type="hidden" name="total_slp" value="0">
                        </div>
                        <small class="text-muted mt-2 d-block">💡 If a record for this municipality + year already
                            exists, it will be updated automatically.</small>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Monthly Modal -->
    <div class="modal fade" id="addMonthlyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Monthly Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('superadmin.data.municipalities.monthly.save') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Municipality</label>
                                <select name="municipality" class="form-select" required>
                                    <option value="">Select Municipality</option>
                                    @foreach($coreNames as $n)<option value="{{ $n }}">{{ $n }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" required>
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)<option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Month</label>
                                <select name="month" class="form-select" required>
                                    <option value="">Select Month</option>
                                    @php $mn = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']; @endphp
                                    @foreach($mn as $mi => $ml)<option value="{{ $mi + 1 }}" {{ ($mi + 1) == date('n') ? 'selected' : '' }}>{{ $ml }}</option>@endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PWD Assistance</label>
                                <input type="number" name="total_pwd" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">AICS</label>
                                <input type="number" name="total_aics" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Solo Parent</label>
                                <input type="number" name="total_solo_parent" class="form-control" min="0"
                                    placeholder="0">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes <span
                                        class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="notes" class="form-control" rows="2"
                                    placeholder="Any notes for this month..."></textarea>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">💡 If a record for this municipality + month + year
                            already exists, it will be updated.</small>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Save Monthly Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Monthly Modal -->
    <div class="modal fade" id="editMonthlyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Monthly Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">PWD Assistance</label>
                            <input type="number" id="em_pwd" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">AICS</label>
                            <input type="number" id="em_aics" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Solo Parent</label>
                            <input type="number" id="em_solo" class="form-control" min="0">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea id="em_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-modal-submit" onclick="saveEditMonthly()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archived Monthly Modal -->
    <div class="modal fade" id="archivedMonthlyModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🗂 Archived Monthly Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive" style="max-height:420px;">
                        <table class="premium-table" style="margin-bottom:0;">
                            <thead style="position:sticky;top:0;z-index:1;">
                                <tr>
                                    <th>Municipality</th>
                                    <th>Year</th>
                                    <th>Month</th>
                                    <th>PWD</th>
                                    <th>AICS</th>
                                    <th>Solo Parent</th>
                                    <th>Archived</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="archivedMonthlyList">
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ========== ARCHIVED SUMMARIES MODAL ========== -->
    <div class="modal fade" id="archivedSummaryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🗂 Archived Yearly Records</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive" style="max-height:460px;">
                        <table class="premium-table" style="margin-bottom:0;">
                            <thead style="position:sticky;top:0;z-index:1;">
                                <tr>
                                    <th>Municipality</th>
                                    <th>Year</th>
                                    <th>Population</th>
                                    <th>Archived Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="archivedSummaryList">
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>

    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Tab switching
        function switchTab(name) {
            document.querySelectorAll('.section-tab').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-pill').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            event.currentTarget.classList.add('active');
            if (name === 'analysis') initCharts();
            if (name === 'monthly') initMonthlyChart();
        }

        // Archive summary (AJAX)
        function archiveSummary(id, label) {
            if (!confirm(`Archive "${label}"?\n\nThis record will be hidden but can be restored later.`)) return;
            fetch('/superadmin/data/municipalities/summary/' + id + '/archive', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) location.reload(); else alert(d.message || 'Error.'); })
                .catch(() => alert('Network error.'));
        }

        // Load archived summaries
        function loadArchivedSummaries() {
            const tbody = document.getElementById('archivedSummaryList');
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>';
            fetch('/superadmin/data/municipalities/summary/archived', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('archivedSummaryCount').textContent = data.length;
                    if (!data.length) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted"><div style="font-size:2rem;opacity:.3;">🗂</div><p class="mt-2 mb-0">No archived records.</p></td></tr>';
                        return;
                    }
                    tbody.innerHTML = data.map(r => {
                        const date = r.deleted_at ? new Date(r.deleted_at).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) : 'N/A';
                        return `<tr>
                            <td style="font-weight:700;">${r.municipality}</td>
                            <td>${r.year}</td>
                            <td>${Number(r.total_population || 0).toLocaleString()}</td>
                            <td>${date}</td>
                            <td><div class="d-flex gap-2">
                                <button class="btn-restore" onclick="restoreSummary(${r.id})">Restore</button>
                                <button class="btn-perm-del" onclick="permDeleteSummary(${r.id})">Delete Forever</button>
                            </div></td>
                        </tr>`;
                    }).join('');
                })
                .catch(() => { tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-danger">Failed to load.</td></tr>'; });
        }

        function restoreSummary(id) {
            if (!confirm('Restore this record? It will appear in the active list again.')) return;
            fetch('/superadmin/data/municipalities/summary/' + id + '/restore', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) { loadArchivedSummaries(); location.reload(); } else alert(d.message); })
                .catch(() => alert('Network error.'));
        }

        function permDeleteSummary(id) {
            if (!confirm('⚠️ PERMANENTLY DELETE this record?\n\nThis CANNOT be undone!')) return;
            fetch('/superadmin/data/municipalities/summary/' + id + '/force-delete', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) loadArchivedSummaries(); else alert(d.message); })
                .catch(() => alert('Network error.'));
        }

        // Load count on page load
        fetch('/superadmin/data/municipalities/summary/archived', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(d => { document.getElementById('archivedSummaryCount').textContent = d.length; })
            .catch(() => { });

        document.getElementById('archivedSummaryModal').addEventListener('show.bs.modal', loadArchivedSummaries);

        let chartsInited = false;
        function initCharts() {
            if (chartsInited) return;
            chartsInited = true;

            const PALETTE = ['#2C3E8F', '#FDB913', '#C41E24', '#10B981', '#8B5CF6'];
            const chartData = @json($chartData);
            const coreNames = @json($coreNames);

            // Per-municipality charts
            coreNames.forEach((name, i) => {
                const cd = chartData[name];
                if (!cd || !cd.years.length) return;
                const ctx = document.getElementById('chart-' + name);
                if (!ctx) return;
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: cd.years,
                        datasets: [
                            {
                                label: 'Population',
                                data: cd.population,
                                backgroundColor: PALETTE[i % PALETTE.length],
                                borderRadius: 6,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Households',
                                data: cd.households,
                                backgroundColor: PALETTE[(i + 2) % PALETTE.length],
                                borderRadius: 6,
                                yAxisID: 'y'
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
            });

            // Comparison chart
            const allYears = [...new Set(Object.values(chartData).flatMap(d => d.years))].sort();
            const compCtx = document.getElementById('chart-comparison');
            if (compCtx) {
                new Chart(compCtx, {
                    type: 'line',
                    data: {
                        labels: allYears,
                        datasets: coreNames.map((name, i) => ({
                            label: name,
                            data: allYears.map(y => {
                                const idx = chartData[name].years.indexOf(y);
                                return idx >= 0 ? chartData[name].population[idx] : null;
                            }),
                            borderColor: PALETTE[i],
                            backgroundColor: PALETTE[i] + '22',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            borderWidth: 3
                        }))
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
        }

        function deleteSummary(id) {
            if (!confirm('Delete this year record? This cannot be undone.')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/superadmin/data/municipalities/summary/' + id;
            const csrf = document.createElement('input'); csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input'); method.name = '_method'; method.value = 'DELETE';
            form.appendChild(csrf); form.appendChild(method);
            document.body.appendChild(form); form.submit();
        }

        // ─── MONTHLY DATA ───────────────────────────────────────────────────
        const monthlyChartData = @json($monthlyChartData);
        const MONTH_NAMES = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const MUNI_COLORS = { 'Magdalena': '#2C3E8F', 'Liliw': '#FDB913', 'Majayjay': '#C41E24' };
        let monthlyChartInstance = null;

        function initMonthlyChart() {
            const ctx = document.getElementById('monthlyTrendChart');
            if (!ctx) return;
            if (monthlyChartInstance) { monthlyChartInstance.destroy(); monthlyChartInstance = null; }

            const datasets = [];
            const munis = Object.keys(monthlyChartData);
            const programKeys = [
                { key: 'pwd', label: 'PWD Assistance', dash: [] },
                { key: 'aics', label: 'AICS', dash: [6, 3] },
                { key: 'solo_parent', label: 'Solo Parent', dash: [2, 4] }
            ];
            const baseColors = ['#2C3E8F', '#FDB913', '#C41E24'];

            munis.forEach((muni, mi) => {
                programKeys.forEach(prog => {
                    datasets.push({
                        label: `${muni} – ${prog.label}`,
                        data: monthlyChartData[muni][prog.key],
                        borderColor: baseColors[mi],
                        backgroundColor: baseColors[mi] + '18',
                        borderDash: prog.dash,
                        borderWidth: 2,
                        pointRadius: 4,
                        tension: 0.35,
                        fill: false
                    });
                });
            });

            monthlyChartInstance = new Chart(ctx, {
                type: 'line',
                data: { labels: MONTH_NAMES, datasets },
                options: {
                    responsive: true,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { font: { size: 11 }, boxWidth: 24 } },
                        tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString()}` } }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } }
                    }
                }
            });
        }

        // Open Edit Monthly modal
        let editMonthlyId = null;
        function openEditMonthly(id, pwd, aics, solo) {
            editMonthlyId = id;
            document.getElementById('em_pwd').value = pwd;
            document.getElementById('em_aics').value = aics;
            document.getElementById('em_solo').value = solo;
            document.getElementById('em_notes').value = '';
            const modal = new bootstrap.Modal(document.getElementById('editMonthlyModal'));
            modal.show();
        }

        // Save edit via AJAX
        function saveEditMonthly() {
            if (!editMonthlyId) return;
            const payload = new FormData();
            payload.append('_token', CSRF);
            payload.append('total_pwd', document.getElementById('em_pwd').value);
            payload.append('total_aics', document.getElementById('em_aics').value);
            payload.append('total_solo_parent', document.getElementById('em_solo').value);
            payload.append('notes', document.getElementById('em_notes').value);

            fetch(`/superadmin/data/municipalities/monthly/${editMonthlyId}/edit`, {
                method: 'POST', body: payload
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        bootstrap.Modal.getInstance(document.getElementById('editMonthlyModal')).hide();
                        location.reload();
                    } else { alert(d.message || 'Error saving.'); }
                })
                .catch(() => alert('Network error.'));
        }

        // Archive monthly record
        function archiveMonthly(id, label) {
            if (!confirm(`Archive "${label}"?\n\nThis record will be hidden but can be restored later.`)) return;
            fetch(`/superadmin/data/municipalities/monthly/${id}/archive`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) location.reload(); else alert(d.message || 'Error.'); })
                .catch(() => alert('Network error.'));
        }

        // Load archived monthly records modal
        function loadArchivedMonthly() {
            const tbody = document.getElementById('archivedMonthlyList');
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>';
            fetch('/superadmin/data/municipalities/monthly/archived', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted"><div style="font-size:2rem;opacity:.3;">🗂</div><p class="mt-2 mb-0">No archived monthly records.</p></td></tr>';
                        return;
                    }
                    const mnames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    tbody.innerHTML = data.map(r => {
                        const date = r.deleted_at ? new Date(r.deleted_at).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) : 'N/A';
                        return `<tr>
                            <td><strong>${r.municipality}</strong></td>
                            <td>${r.year}</td>
                            <td>${mnames[r.month]}</td>
                            <td>${(r.total_pwd || 0).toLocaleString()}</td>
                            <td>${(r.total_aics || 0).toLocaleString()}</td>
                            <td>${(r.total_solo_parent || 0).toLocaleString()}</td>
                            <td style="font-size:.8rem;">${date}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn-restore" onclick="restoreMonthly(${r.id}, this)">Restore</button>
                                    <button class="btn-perm-del" onclick="permDeleteMonthly(${r.id}, this)">Delete</button>
                                </div>
                            </td>
                        </tr>`;
                    }).join('');
                });
        }

        function restoreMonthly(id, btn) {
            if (!confirm('Restore this monthly record?')) return;
            fetch(`/superadmin/data/municipalities/monthly/${id}/restore`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) { loadArchivedMonthly(); location.reload(); } else alert(d.message); });
        }

        function permDeleteMonthly(id, btn) {
            if (!confirm('⚠️ Permanently delete this monthly record? This cannot be undone.')) return;
            fetch(`/superadmin/data/municipalities/monthly/${id}/force-delete`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) loadArchivedMonthly(); else alert(d.message); });
        }

        // Auto-load archived monthly count on modal open
        document.getElementById('archivedMonthlyModal')?.addEventListener('show.bs.modal', loadArchivedMonthly);

        // Auto-open to monthly tab if page reloaded via chart_year selector
        if (window.location.hash === '#monthly' || new URLSearchParams(window.location.search).has('chart_year')) {
            document.querySelectorAll('.tab-pill').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.section-tab').forEach(s => s.classList.remove('active'));
            document.getElementById('tab-monthly')?.classList.add('active');
            document.querySelectorAll('.tab-pill')[1]?.classList.add('active');
            initMonthlyChart();
        }
    </script>
</body>

</html>
