<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Program Data – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

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
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
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

        .section-tab {
            display: none;
        }

        .section-tab.active {
            display: block;
        }

        /* Filter */
        .filter-card {
            background: white;
            border-radius: 16px;
            padding: 22px 28px;
            margin-bottom: 28px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
            border: 1px solid var(--border-light);
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
            outline: none;
        }

        .btn-filter {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-add {
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            border: none;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 800;
            font-size: .9rem;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(253, 185, 19, .4);
            color: var(--primary-blue);
        }

        .btn-archive-view {
            background: transparent;
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            border-radius: 30px;
            padding: 9px 22px;
            font-weight: 700;
            transition: all .3s;
            cursor: pointer;
            font-size: .88rem;
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

        /* Table */
        .panel-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .panel-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-header h5 {
            font-weight: 700;
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

        table.premium-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premium-table thead th {
            background: var(--bg-light);
            color: var(--primary-blue);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 12px 20px;
            border-bottom: 2px solid var(--border-light);
        }

        .premium-table tbody td {
            padding: 14px 20px;
            font-size: .88rem;
            border-bottom: 1px solid var(--border-light);
            vertical-align: middle;
            color: #334155;
        }

        .premium-table tbody tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr:hover {
            background: #FAFBFF;
        }

        .program-pill {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: 1px solid rgba(44, 62, 143, .2);
        }

        .btn-action-edit {
            display: inline-flex;
            align-items: center;
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
        }

        .btn-action-edit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(253, 185, 19, .4);
        }

        .btn-action-delete {
            display: inline-flex;
            align-items: center;
            background: rgba(196, 30, 36, .1);
            color: #C41E24;
            border: 1px solid rgba(196, 30, 36, .2);
            border-radius: 8px;
            padding: 6px 14px;
            font-size: .8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
        }

        .btn-action-delete:hover {
            background: #C41E24;
            color: white;
        }

        /* Modal */
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

        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(44, 62, 143, .2);
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

        /* Barangay sync panel */
        .sync-panel {
            background: white;
            border-radius: 16px;
            border: 2px solid #10B981;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .sync-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .sync-title {
            font-size: .95rem;
            font-weight: 800;
            color: #065F46;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sync-badge {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #6EE7B7;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: .75rem;
            font-weight: 700;
        }

        .btn-sync {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 9px 22px;
            font-weight: 700;
            font-size: .87rem;
            cursor: pointer;
            transition: all .3s;
        }

        .btn-sync:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, .35);
        }

        .sync-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .83rem;
        }

        .sync-table th {
            background: #F0FDF4;
            color: #065F46;
            padding: 8px 14px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: .73rem;
            letter-spacing: .06em;
            border-bottom: 2px solid #6EE7B7;
        }

        .sync-table td {
            padding: 8px 14px;
            border-bottom: 1px solid #D1FAE5;
            color: #334155;
        }

        .sync-table tr:last-child td {
            border-bottom: none;
        }

        .bgy-badge {
            display: inline-block;
            background: #D1FAE5;
            color: #065F46;
            border-radius: 6px;
            padding: 1px 8px;
            font-size: .7rem;
            font-weight: 700;
            margin-left: 6px;
            vertical-align: middle;
        }

        .warn-badge {
            display: inline-block;
            background: #FEF3C7;
            color: #92400E;
            border-radius: 6px;
            padding: 1px 8px;
            font-size: .7rem;
            font-weight: 700;
            margin-left: 6px;
            vertical-align: middle;
        }

        /* Charts */
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
            margin-bottom: 4px;
        }

        .chart-subtitle {
            font-size: .82rem;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        .chart-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        @media(max-width:768px) {
            .chart-row {
                grid-template-columns: 1fr;
            }
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
            background: #EFF6FF;
            color: #1D4ED8;
            border: none;
            border-left: 4px solid #1D4ED8;
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
            <h1>Social Program Data</h1>
            <div class="hero-divider"></div>
            <p>Track, add, and analyze social welfare program beneficiary counts across municipalities and years.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">

            @if(session('success'))
                <div class="alert-success-custom">? {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-danger-custom">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Tab Pills -->
            <div class="tab-pills">
                <button class="tab-pill active" onclick="switchTab('records', this)">?? Records</button>
                <button class="tab-pill" onclick="switchTab('analysis', this)">?? Analysis</button>
            </div>

            <!-- ===================== RECORDS TAB ===================== -->
            <div class="section-tab active" id="tab-records">
                <!-- Barangay Aggregates Panel -->
                @if($barangayAggregates->isNotEmpty())
                    <div class="sync-panel">
                        <div class="sync-panel-header">
                            <div class="sync-title">
                                ?? Barangay-Computed Totals
                                <span class="sync-badge">Auto-calculated from Barangay Data</span>
                            </div>
                            <form method="POST" action="{{ route('superadmin.data.programs.sync-barangays') }}">
                                @csrf
                                <button type="submit" class="btn-sync"
                                    onclick="return confirm('Sync barangay totals to programs table? Existing AICS, PWD, and Solo Parent records will be updated.')">
                                    ?? Sync to Programs Table
                                </button>
                            </form>
                        </div>
                        <p style="font-size:.82rem;color:#6B7280;margin-bottom:14px;">These are the <strong>live
                                sums</strong> computed from all barangay records. Click <em>Sync</em> to save them as the
                            official beneficiary counts in the programs table.</p>
                        <div class="table-responsive">
                            <table class="sync-table">
                                <thead>
                                    <tr>
                                        <th>Municipality</th>
                                        <th>Year</th>
                                        <th>AICS (from Barangays)</th>
                                        <th>PWD (from Barangays)</th>
                                        <th>Solo Parent (from Barangays)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangayAggregates as $agg)
                                        @php
                                            $key = "{$agg->municipality}|{$agg->year}";
                                            $stored = [
                                                'AICS' => \App\Models\SocialWelfareProgram::where('municipality', $agg->municipality)->where('year', $agg->year)->where('program_type', 'AICS')->value('beneficiary_count'),
                                                'PWD' => \App\Models\SocialWelfareProgram::where('municipality', $agg->municipality)->where('year', $agg->year)->where('program_type', 'PWD_Assistance')->value('beneficiary_count'),
                                                'Solo' => \App\Models\SocialWelfareProgram::where('municipality', $agg->municipality)->where('year', $agg->year)->where('program_type', 'Solo_Parent')->value('beneficiary_count'),
                                            ];
                                        @endphp
                                        <tr>
                                            <td style="font-weight:700;">{{ $agg->municipality }}</td>
                                            <td>{{ $agg->year }}</td>
                                            <td>
                                                <strong>{{ number_format($agg->total_aics) }}</strong>
                                                @if($stored['AICS'] !== null && $stored['AICS'] != $agg->total_aics)
                                                    <span class="warn-badge">DB: {{ number_format($stored['AICS']) }}</span>
                                                @elseif($stored['AICS'] == $agg->total_aics)
                                                    <span class="bgy-badge">? Synced</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ number_format($agg->total_pwd) }}</strong>
                                                @if($stored['PWD'] !== null && $stored['PWD'] != $agg->total_pwd)
                                                    <span class="warn-badge">DB: {{ number_format($stored['PWD']) }}</span>
                                                @elseif($stored['PWD'] == $agg->total_pwd)
                                                    <span class="bgy-badge">? Synced</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ number_format($agg->total_solo_parent) }}</strong>
                                                @if($stored['Solo'] !== null && $stored['Solo'] != $agg->total_solo_parent)
                                                    <span class="warn-badge">DB: {{ number_format($stored['Solo']) }}</span>
                                                @elseif($stored['Solo'] == $agg->total_solo_parent)
                                                    <span class="bgy-badge">? Synced</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Filter + Add -->
                <div class="filter-card">
                    <form method="GET" action="{{ route('superadmin.data.programs') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">Municipality</label>
                                <select name="municipality" class="form-select">
                                    <option value="">All Municipalities</option>
                                    @foreach($municipalities as $municipality)
                                        <option value="{{ $municipality }}" {{ request('municipality') == $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Program Type</label>
                                <select name="program_type" class="form-select">
                                    <option value="">All Programs</option>
                                    @foreach($programTypes as $value => $label)
                                        <option value="{{ $value }}" {{ request('program_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn-filter w-100">Apply Filter</button>
                            </div>
                            <div class="col-md-2 text-end d-flex gap-2 justify-content-end">
                                <button class="btn-archive-view" data-bs-toggle="modal"
                                    data-bs-target="#archivedProgModal">
                                    ?? Archived (<span id="archivedProgCount">...</span>)
                                </button>
                                <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#createModal">+ Add
                                    Data</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="panel-card">
                    <div class="panel-header">
                        <h5>Program Records</h5>
                        <span class="count-badge">{{ $programs->total() }} records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Municipality</th>
                                    <th>Program</th>
                                    <th>Beneficiaries</th>
                                    <th>Year</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($programs as $program)
                                    <tr>
                                        <td style="color:#94a3b8;font-size:.8rem;">#{{ $program->id }}</td>
                                        <td style="font-weight:600;">{{ $program->municipality }}</td>
                                        <td><span
                                                class="program-pill">{{ str_replace('_', ' ', $program->program_type) }}</span>
                                        </td>
                                        <td style="font-weight:700;color:var(--primary-blue);">
                                            {{ number_format($program->beneficiary_count) }}
                                            @if(in_array($program->program_type, ['AICS', 'PWD_Assistance', 'Solo_Parent']))
                                                @php $bKey = "{$program->municipality}|{$program->year}"; @endphp
                                                @if(isset($barangayLookup[$bKey]))
                                                    @php
                                                        $bMap = ['AICS' => 'AICS', 'PWD_Assistance' => 'PWD', 'Solo_Parent' => 'Solo_Parent'];
                                                        $bField = $bMap[$program->program_type];
                                                        $bTotal = $barangayLookup[$bKey][$bField] ?? null;
                                                    @endphp
                                                    @if($bTotal !== null && $bTotal == $program->beneficiary_count)
                                                        <span class="bgy-badge">?? Barangay</span>
                                                    @elseif($bTotal !== null && $bTotal != $program->beneficiary_count)
                                                        <span class="warn-badge" title="Barangay total: {{ number_format($bTotal) }}">?
                                                            Bgy: {{ number_format($bTotal) }}</span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $program->year }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button class="btn-action-edit" data-bs-toggle="modal"
                                                    data-bs-target="#editProg{{ $program->id }}">Edit</button>
                                                <button class="btn-action-delete"
                                                    onclick="archiveProgram({{ $program->id }}, '{{ addslashes(str_replace('_', ' ', $program->program_type)) }}', '{{ $program->municipality }}')">Archive</button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editProg{{ $program->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Program Data</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('superadmin.data.programs.update', $program->id) }}">
                                                    @csrf
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Municipality</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $program->municipality }}" readonly disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Program Type</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ str_replace('_', ' ', $program->program_type) }}"
                                                                readonly disabled>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Beneficiary Count</label>
                                                            <input type="number" name="beneficiary_count"
                                                                class="form-control"
                                                                value="{{ $program->beneficiary_count }}" required min="0">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Year</label>
                                                            <select name="year" class="form-select" required>
                                                                @foreach(range(date('Y') - 3, date('Y') + 1) as $yearOption)
                                                                    <option value="{{ $yearOption }}" {{ $program->year == $yearOption ? 'selected' : '' }}>
                                                                        {{ $yearOption }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                                                        <button type="button" class="btn-modal-cancel"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn-modal-submit">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center;padding:48px;color:#94a3b8;">No program
                                            records found. Add one using the button above.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 d-flex justify-content-center">{{ $programs->withQueryString()->links() }}</div>
                </div>
            </div>

            <!-- ===================== ANALYSIS TAB ===================== -->
            <div class="section-tab" id="tab-analysis">
                @if(empty($allYears))
                    <div style="background:white;border-radius:20px;padding:40px;text-align:center;color:#94a3b8;">
                        No program data available yet. Add records first.
                    </div>
                @else
                    <!-- Total Beneficiaries per Municipality per Year -->
                    <div class="chart-card">
                        <div class="chart-title">Total Beneficiaries per Municipality per Year</div>
                        <div class="chart-subtitle">Combined across all programs</div>
                        <canvas id="chartMuniYear" height="80"></canvas>
                    </div>

                    <!-- Per-Program Charts -->
                    <div class="chart-row">
                        <div class="chart-card" style="margin-bottom:0">
                            <div class="chart-title">Beneficiaries by Program Type per Year</div>
                            <div class="chart-subtitle">All municipalities combined</div>
                            <canvas id="chartProgYear" height="140"></canvas>
                        </div>
                        <div class="chart-card" style="margin-bottom:0">
                            <div class="chart-title">Program Distribution (Latest Year: {{ end($allYears) }})</div>
                            <div class="chart-subtitle">Percentage breakdown by program</div>
                            <canvas id="chartPie" height="140"></canvas>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- ========== ARCHIVED PROGRAMS MODAL ========== -->
    <div class="modal fade" id="archivedProgModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">?? Archived Program Records</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive" style="max-height:460px;">
                        <table class="premium-table" style="margin-bottom:0;">
                            <thead style="position:sticky;top:0;z-index:1;">
                                <tr>
                                    <th>Municipality</th>
                                    <th>Program</th>
                                    <th>Beneficiaries</th>
                                    <th>Year</th>
                                    <th>Archived Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="archivedProgList">
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function switchTab(name, btn) {
            document.querySelectorAll('.section-tab').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-pill').forEach(el => el.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            btn.classList.add('active');
            if (name === 'analysis') initCharts();
        }

        // Archive program (AJAX)
        function archiveProgram(id, type, muni) {
            if (!confirm(`Archive "${type}" record for ${muni}?\n\nThis can be restored later from the archive.`)) return;
            fetch('/superadmin/data/programs/' + id + '/archive', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) location.reload(); else alert(d.message || 'Error.'); })
                .catch(() => alert('Network error. Please try again.'));
        }

        // Load archived programs
        function loadArchivedPrograms() {
            const tbody = document.getElementById('archivedProgList');
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>';
            fetch('/superadmin/data/programs/archived', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('archivedProgCount').textContent = data.length;
                    if (!data.length) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted"><div style="font-size:2rem;opacity:.3;">??</div><p class="mt-2 mb-0">No archived program records.</p></td></tr>';
                        return;
                    }
                    tbody.innerHTML = data.map(p => {
                        const date = p.deleted_at ? new Date(p.deleted_at).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) : 'N/A';
                        const prog = (p.program_type || '').replace(/_/g, ' ');
                        return `<tr>
                            <td style="font-weight:600;">${p.municipality}</td>
                            <td><span style="background:#E5EEFF;color:#2C3E8F;padding:2px 10px;border-radius:20px;font-size:.75rem;font-weight:700;">${prog}</span></td>
                            <td style="font-weight:700;">${Number(p.beneficiary_count || 0).toLocaleString()}</td>
                            <td>${p.year}</td>
                            <td>${date}</td>
                            <td><div class="d-flex gap-2">
                                <button class="btn-restore" onclick="restoreProgram(${p.id})">Restore</button>
                                <button class="btn-perm-del" onclick="permDeleteProgram(${p.id})">Delete Forever</button>
                            </div></td>
                        </tr>`;
                    }).join('');
                })
                .catch(() => { tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger">Failed to load.</td></tr>'; });
        }

        function restoreProgram(id) {
            if (!confirm('Restore this program record? It will appear in the active list again.')) return;
            fetch('/superadmin/data/programs/' + id + '/restore', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) { loadArchivedPrograms(); location.reload(); } else alert(d.message); })
                .catch(() => alert('Network error.'));
        }

        function permDeleteProgram(id) {
            if (!confirm('?? PERMANENTLY DELETE this program record?\n\nThis CANNOT be undone!')) return;
            fetch('/superadmin/data/programs/' + id + '/force-delete', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            })
                .then(r => r.json())
                .then(d => { if (d.success) loadArchivedPrograms(); else alert(d.message); })
                .catch(() => alert('Network error.'));
        }

        // Load archived count on page load
        fetch('/superadmin/data/programs/archived', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(d => { document.getElementById('archivedProgCount').textContent = d.length; })
            .catch(() => { });

        document.getElementById('archivedProgModal').addEventListener('show.bs.modal', loadArchivedPrograms);

        let chartsInited = false;
        function initCharts() {
            if (chartsInited) return;
            chartsInited = true;

            const allYears = @json($allYears);
            const chartDatasets = @json($chartDatasets);
            const programChartData = @json($programChartData);

            const MUNICOLORS = ['#2C3E8F', '#FDB913', '#C41E24'];
            const PROGCOLORS = ['#2C3E8F', '#FDB913', '#C41E24', '#10B981', '#8B5CF6', '#F97316', '#06B6D4'];

            // Chart 1: Total per Municipality per Year
            const ctx1 = document.getElementById('chartMuniYear');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: allYears,
                        datasets: chartDatasets.map((ds, i) => ({
                            label: ds.label,
                            data: ds.data,
                            backgroundColor: MUNICOLORS[i % MUNICOLORS.length],
                            borderRadius: 6
                        }))
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } },
                            x: { stacked: false }
                        }
                    }
                });
            }

            // Chart 2: Per Program Type per Year
            const ctx2 = document.getElementById('chartProgYear');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: allYears,
                        datasets: programChartData.map((pd, i) => ({
                            label: pd.label,
                            data: pd.data,
                            backgroundColor: PROGCOLORS[i % PROGCOLORS.length],
                            borderRadius: 4
                        }))
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'right', labels: { font: { size: 11 } } } },
                        scales: {
                            y: { beginAtZero: true, stacked: true, ticks: { callback: v => v.toLocaleString() } },
                            x: { stacked: true }
                        }
                    }
                });
            }

            // Chart 3: Pie for latest year
            const ctx3 = document.getElementById('chartPie');
            if (ctx3 && allYears.length > 0) {
                const latestIdx = allYears.length - 1;
                const pieData = programChartData.map(pd => pd.data[latestIdx] || 0);
                const pieLabels = programChartData.map(pd => pd.label);
                new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieData,
                            backgroundColor: PROGCOLORS,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'right', labels: { font: { size: 11 } } },
                            tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.parsed.toLocaleString()}` } }
                        }
                    }
                });
            }
        }

        function deleteProgram(id) {
            if (!confirm('Delete this program record?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/superadmin/data/programs/' + id;
            const csrf = document.createElement('input'); csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input'); method.name = '_method'; method.value = 'DELETE';
            form.appendChild(csrf); form.appendChild(method);
            document.body.appendChild(form); form.submit();
        }
    </script>
</body>

</html>
