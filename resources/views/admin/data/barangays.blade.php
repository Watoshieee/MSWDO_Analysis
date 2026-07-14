<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Data – {{ $municipality->name }} – MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }};
            --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }};
            --accent-red: {{ $adminAccentColor ?? '#C41E24' }};
            --primary-gradient: linear-gradient(135deg, var(--primary-blue) 0%, color-mix(in srgb, var(--primary-blue) 80%, black) 100%);
            --secondary-gradient: linear-gradient(135deg, var(--secondary-yellow) 0%, color-mix(in srgb, var(--secondary-yellow) 90%, black) 100%);
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow-light: #FFF3D6;
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
            font-size: 0.85rem;
            white-space: nowrap;
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
            font-size: 0.9rem;
            font-weight: 600;
            max-width: 100%;
        }
        .user-info span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px; }

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
            padding: 52px 0 42px;
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
            background: rgba(253, 185, 19, .10);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -50px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
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
            margin-bottom: 18px;
        }

        .hero-banner h1 {
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .hero-divider {
            width: 55px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 14px 0;
        }

        .hero-banner p {
            font-size: 1rem;
            opacity: .87;
            max-width: 580px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, .75);
            font-size: .82rem;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 20px;
            padding: 5px 14px;
            transition: all .25s;
            margin-bottom: 12px;
            text-decoration: none;
        }

        .back-link:hover {
            color: white;
            background: rgba(255, 255, 255, .15);
        }

        .main-content {
            flex: 1;
        }

        /* Filter Card */
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
            transition: all .3s;
            cursor: pointer;
        }

        .btn-filter:hover {
            opacity: .9;
        }

        /* Panel */
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
            background: var(--primary-blue-light);
        }

        .btn-action-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
            justify-content: center;
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
            transform: translateY(-1px);
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

        .alert-success {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: none;
            border-left: 4px solid var(--primary-blue);
            border-radius: 12px;
        }

        .inline-input {
            width: 85px;
            border: 1.5px solid var(--border-light);
            border-radius: 8px;
            padding: 5px 7px;
            font-size: .82rem;
            font-family: 'Inter', sans-serif;
            transition: border .2s;
            background: #f8fafc;
            text-align: center;
        }

        .inline-input:focus {
            border-color: var(--primary-blue);
            outline: none;
            background: white;
            box-shadow: 0 0 0 3px rgba(44, 62, 143, .08);
        }

        .inline-select {
            border: 1.5px solid var(--border-light);
            border-radius: 8px;
            padding: 5px 6px;
            font-size: .82rem;
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            transition: border .2s;
            width: 76px;
        }

        .inline-select:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        .btn-save-row {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: .79rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .25s;
            white-space: nowrap;
        }

        .btn-save-row:hover {
            opacity: .85;
            transform: translateY(-1px);
        }

        .btn-save-row:disabled {
            opacity: .55;
            transform: none;
        }

        .bgy-row.dirty {
            background: #fffbeb !important;
        }

        .bgy-row.dirty td {
            background: transparent;
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
    <script>
        // Force scroll to top immediately
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
        window.scrollTo(0, 0);
        
        // Prevent scroll restoration
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        
        // Lock scroll position during page load
        window.addEventListener('DOMContentLoaded', function() {
            window.scrollTo(0, 0);
        });
        
        window.addEventListener('load', function() {
            window.scrollTo(0, 0);
        });
    </script>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}"
                           href="{{ route('admin.requirements') }}">Applications</a>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.data.*') ? 'active' : '' }}"
                           href="{{ route('admin.data.dashboard') }}">Data Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}"
                           href="{{ route('admin.announcements.index') }}">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.detailed*') ? 'active' : '' }}"
                           href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                    <button type="button" class="btn" onclick="openAdminNotifModal()"
                        style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;transition:all 0.3s;position:relative;"
                        title="Application Notifications">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($adminNotifCount) && $adminNotifCount > 0)
                        <span class="admin-bell-badge" style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
                        @endif
                    </button>
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

    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('admin.data.dashboard') }}#return" class="back-link">&#8592; Data Management</a>
            <div class="hero-badge">{{ $municipality->name }}</div>
            <h1>Barangay Data</h1>
            <div class="hero-divider"></div>
            <p>Edit barangay-level population, household, and demographic statistics for {{ $municipality->name }}.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            <div id="toast" class="toast-msg" style="display:none;"></div>

            <!-- Action Buttons Row -->
            <div class="d-flex justify-content-end gap-2 mb-3">
                <button class="btn btn-sm" onclick="openCsvModal('import')" style="background:var(--primary-blue);color:white;border:none;border-radius:8px;padding:8px 16px;font-weight:700;font-size:0.85rem;">
                    <i class="bi bi-upload"></i> Import CSV
                </button>
                <button class="btn btn-sm" onclick="openCsvModal('export')" style="background:var(--secondary-yellow);color:#333;border:none;border-radius:8px;padding:8px 16px;font-weight:700;font-size:0.85rem;">
                    <i class="bi bi-download"></i> Export CSV
                </button>
                <button onclick="bulkArchiveByYear()" style="background:rgba(196,30,36,0.1);border:1px solid rgba(196,30,36,0.25);color:#C41E24;border-radius:10px;padding:10px 20px;font-size:0.85rem;font-weight:700;cursor:pointer;white-space:nowrap;transition:all 0.25s;">
                     Archive by Year
                </button>
                <button onclick="openArchivedModal()" style="background:rgba(44,62,143,0.1);border:1px solid rgba(44,62,143,0.25);color:var(--primary-blue);border-radius:10px;padding:10px 20px;font-size:0.85rem;font-weight:700;cursor:pointer;white-space:nowrap;transition:all 0.25s;">
                     Archived
                </button>
                <button data-bs-toggle="modal" data-bs-target="#addBarangayModal" style="background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:10px;padding:10px 20px;font-size:0.85rem;font-weight:700;cursor:pointer;white-space:nowrap;transition:all 0.25s;">
                    + Add Data
                </button>
            </div>

            <!-- Inline-Edit Table -->
            <div class="panel-card" id="return">
                <div class="panel-header" style="flex-wrap:wrap;gap:12px;">
                    <div class="d-flex align-items-center gap-3">
                        <h5>Barangay Records</h5>
                        <span class="count-badge">{{ $barangays->count() }} records</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="flex-wrap:wrap;">
                        <!-- Search Bar -->
                        <input type="text" id="searchInput" placeholder="🔍 Search barangay..." style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;font-size:0.85rem;padding:6px 14px;border-radius:8px;min-width:200px;" oninput="searchTable()">
                        
                        <div style="width:1px;height:24px;background:rgba(255,255,255,0.2);margin:0 4px;"></div>
                        
                        <!-- Year Filter (admin: data is always scoped to your municipality — no municipality filter) -->
                        <select id="filterYear" class="form-select form-select-sm" style="width:auto;min-width:120px;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.3);color:white;font-size:0.85rem;padding:6px 12px;border-radius:8px;" onchange="applyFilters()">
                            <option value="" style="color:#334155;">All Years</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }} style="color:#334155;">{{ $yr }}</option>
                            @endforeach
                        </select>
                        
                        @if(request('year'))
                        <button type="button" onclick="clearFilters()" style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);color:white;border-radius:8px;padding:6px 14px;font-size:0.8rem;font-weight:600;cursor:pointer;white-space:nowrap;">Clear</button>
                        @endif
                        <button type="button" id="saveAllBtn" onclick="saveAllRows()" style="background:linear-gradient(135deg,#10B981,#059669);color:white;border:none;border-radius:8px;padding:6px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;white-space:nowrap;">Save All</button>
                    </div>
                </div>
                <div class="table-responsive" style="max-height:620px;overflow-y:auto;">
                    <table class="premium-table" id="barangayTable">
                        <thead style="position:sticky;top:0;z-index:2;">
                            <tr>
                                <th>Year</th>
                                <th>Barangay</th>
                                <th style="text-align:center;">Total Population</th>
                                <th style="text-align:center;">PWD</th>
                                <th style="text-align:center;">AICS</th>
                                <th style="text-align:center;">Solo Parent</th>
                                <th style="text-align:center;">Households</th>
                                <th style="text-align:center;">4PS</th>
                                <th style="text-align:center;">Senior</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangays as $barangay)
                                <tr class="bgy-row" data-id="{{ $barangay->id }}" data-year="{{ $barangay->year ?? date('Y') }}">
                                    <td><span style="font-weight:700;color:var(--primary-blue);">{{ $barangay->year ?? date('Y') }}</span></td>
                                    <td style="font-weight:700;color:var(--primary-blue);font-size:.88rem;">{{ $barangay->name }}</td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="total_population" value="{{ $barangay->total_population ?? 0 }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="pwd_count" value="{{ $barangay->pwd_count }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="aics_count" value="{{ $barangay->aics_count }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="single_parent_count" value="{{ $barangay->single_parent_count }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="total_households" value="{{ $barangay->total_households }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="four_ps_count" value="{{ $barangay->four_ps_count ?? 0 }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="senior_count" value="{{ $barangay->senior_count ?? 0 }}" min="0"></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn-save-row" onclick="saveRow({{ $barangay->id }})">Save</button>
                                            <button class="btn-action-delete btn-archive-row" data-id="{{ $barangay->id }}"
                                                data-name="{{ $barangay->name }}"
                                                data-year="{{ $barangay->year }}">Archive</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div style="font-size:2.8rem;opacity:.25;"></div>
                                        @if(request('year'))
                                            <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No records for <span style="color:var(--primary-blue);">{{ $municipality->name }}</span> in <span style="color:var(--primary-blue);">{{ request('year') }}</span></p>
                                            <p class="mb-3" style="color:#94a3b8;font-size:.88rem;">Data for this year has not been added yet.</p>
                                            @if(!empty($years))
                                                <p style="color:#64748b;font-size:.85rem;">Available years with data: <strong>{{ implode(', ', $years) }}</strong></p>
                                            @endif
                                        @else
                                            <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No barangay records for {{ $municipality->name }}</p>
                                            <p class="mb-3" style="color:#94a3b8;font-size:.88rem;">Click <strong>+ Add Data</strong> to start adding records.</p>
                                        @endif
                                        <button data-bs-toggle="modal" data-bs-target="#addBarangayModal"
                                            class="btn-modal-submit" style="font-size:.88rem;padding:10px 24px;">+ Add Barangay Data</button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Import History -->
            <div class="panel-card mt-4">
                <div class="panel-header">
                    <div>
                        <div class="panel-header-title"><i class="bi bi-clock-history"></i> Recent Import History</div>
                        <div class="panel-header-sub">Last 5 CSV import operations</div>
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
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <!-- Archived Barangays Modal -->
    <div class="modal fade" id="archivedBarangaysModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);border-radius:16px 16px 0 0;">
                    <h5 class="modal-title"> Archived Barangay Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="archivedBarangaysList" style="min-height:200px;">
                        <div class="text-center py-5 text-muted">
                            <div style="font-size:2rem;">❳</div>
                            <p>Loading archived records...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 d-flex justify-content-between align-items-center">
                    <button type="button" id="deleteAllArchivedBtn" onclick="forceDeleteAllArchived()"
                        style="background:rgba(196,30,36,.1);color:#C41E24;border:1.5px solid rgba(196,30,36,.25);border-radius:30px;padding:9px 22px;font-size:.85rem;font-weight:700;cursor:pointer;display:none;">
                        🗑 Delete All Permanently
                    </button>
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(196,30,36,.15);">
                <div class="modal-header"
                    style="background:linear-gradient(135deg,#C41E24 0%,#8B1018 100%);border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" id="confirmDeleteTitle">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div style="font-size:2rem;line-height:1;">⚠︝</div>
                        <div>
                            <p id="confirmDeleteBody" class="mb-0" style="color:#334155;"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn-modal-submit"
                        style="background:linear-gradient(135deg,#C41E24 0%,#8B1018 100%);">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Barangay Modal -->
    <div class="modal fade" id="addBarangayModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Barangay Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.data.barangays.store') }}" id="addBarangayForm">
                    @csrf
                    <input type="hidden" name="municipality" value="{{ $municipality->name }}">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Municipality</label>
                                <input type="text" class="form-control" value="{{ $municipality->name }}" readonly style="background:#f1f5f9;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Barangay Name</label>
                                <select name="name" id="addBarangayName" class="form-select" required>
                                    <option value="">Loading…</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" required>
                                    @foreach($yearsForAddModal as $yr)
                                        <option value="{{ $yr }}" {{ $yr == (int) date('Y') ? 'selected' : '' }}>{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Population</label>
                                <input type="number" name="total_population" class="form-control" required min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Households</label>
                                <input type="number" name="total_households" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">PWD Count</label>
                                <input type="number" name="pwd_count" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">AICS Count</label>
                                <input type="number" name="aics_count" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Solo Parent Count</label>
                                <input type="number" name="single_parent_count" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">4PS Count</label>
                                <input type="number" name="four_ps_count" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Senior Count</label>
                                <input type="number" name="senior_count" class="form-control" min="0" value="0">
                            </div>
                        </div>
                        <small class="text-muted mt-3 d-block">💡 If a record for this barangay + year already exists,
                            it will be updated.</small>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Add Barangay Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const ADMIN_MUN = @json($municipality->name);
        const ADMIN_BARANGAYS_URL = @json(route('admin.data.barangays'));

        // ── Toast helper ─────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.innerHTML = `<div class="toast-timer"></div><span>${message}</span>`;
            toast.className = `toast-msg toast-${type}`;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3500);
        }

        // ── Import Log Archive ──────────────────────────────────────
        let archiveLogId = null;

        function archiveImportLog(id) {
            archiveLogId = id;
            new bootstrap.Modal(document.getElementById('archiveImportLogModal')).show();
        }

        function confirmArchiveImportLog() {
            if (archiveLogId) {
                sessionStorage.setItem('scrollPosition', window.scrollY);
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/csv/import-log/' + archiveLogId + '/archive';
                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = CSRF;
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Restore scroll position after page load
        window.addEventListener('load', function() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('scrollPosition');
            }
        });

        // ── Archived Import Logs Modal ──────────────────────────────
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

        const barangayLists = {
            'Magdalena': [
                'Alipit', 'Malaking Ambling', 'Munting Ambling', 'Baanan', 'Balanac',
                'Bucal', 'Buenavista', 'Bungkol', 'Buo', 'Burlungan', 'Cigaras',
                'Ibabang Atingay', 'Ibabang Butnong', 'Ilayang Atingay', 'Ilayang Butnong',
                'Ilog', 'Malinao', 'Maravilla', 'Poblacion', 'Sabang', 'Salasad',
                'Tanawan', 'Tipunan', 'Halayhayin'
            ],
            'Liliw': [
                'Bagong Anyo (Poblacion)', 'Bayate', 'Bongkol', 'Bubukal', 'Cabuyew',
                'Calumpang', 'San Isidro Culoy', 'Dagatan', 'Daniw', 'Dita',
                'Ibabang Palina', 'Ibabang San Roque', 'Ibabang Sungi', 'Ibabang Taykin',
                'Ilayang Palina', 'Ilayang San Roque', 'Ilayang Sungi', 'Ilayang Taykin',
                'Kanlurang Bukal', 'Laguan', 'Luquin', 'Malabo-Kalantukan',
                'Masikap (Poblacion)', 'Maslun (Poblacion)', 'Mojon', 'Novaliches',
                'Oples', 'Pag-asa (Poblacion)', 'Palayan', 'Rizal (Poblacion)',
                'Silangang Bukal', 'Tuy-Baanan'
            ],
            'Majayjay': [
                'Amonoy', 'Bakia', 'Balanac', 'Balayong', 'Banilad', 'Banti',
                'Bitaoy', 'Botocan', 'Bukal', 'Burgos', 'Burol', 'Coralao',
                'Gagalot', 'Ibabang Banga', 'Ibabang Bayucain', 'Ilayang Banga',
                'Ilayang Bayucain', 'Isabang', 'Malinao', 'May-It', 'Munting Kawayan',
                'Olla', 'Oobi', 'Origuel (Poblacion)', 'Panalaban', 'Pangil',
                'Panglan', 'Piit', 'Pook', 'Rizal', 'San Francisco (Poblacion)',
                'San Isidro', 'San Miguel (Poblacion)', 'San Roque',
                'Santa Catalina (Poblacion)', 'Suba', 'Talortor', 'Tanawan',
                'Taytay', 'Villa Nogales'
            ]
        };

        function loadBarangayOptions(municipality) {
            const select = document.getElementById('addBarangayName');

            if (municipality && barangayLists[municipality]) {
                select.disabled = false;
                select.innerHTML = '<option value="">Select barangay</option>';
                const allOpt = document.createElement('option');
                allOpt.value = '__ALL__';
                allOpt.textContent = '✅ All Barangays (' + barangayLists[municipality].length + ')';
                allOpt.style.cssText = 'font-weight:700;color:#1e7e34;background:#f0fff4;';
                select.appendChild(allOpt);
                const divider = document.createElement('option');
                divider.disabled = true;
                divider.textContent = '──────────────';
                select.appendChild(divider);
                barangayLists[municipality].forEach(barangay => {
                    const option = document.createElement('option');
                    option.value = barangay;
                    option.textContent = barangay;
                    select.appendChild(option);
                });
            } else {
                select.disabled = false;
                select.innerHTML = '<option value="">No preset list for this municipality — type a name via single add or contact super admin</option>';
            }
        }

        document.getElementById('addBarangayModal').addEventListener('show.bs.modal', function () {
            loadBarangayOptions(ADMIN_MUN);
        });

        // Intercept Add form — if "All Barangays" selected, bulk-store via AJAX
        document.getElementById('addBarangayForm').addEventListener('submit', function (e) {
            const bgySelect = document.getElementById('addBarangayName');
            if (bgySelect.value !== '__ALL__') return;
            e.preventDefault();

            const municipality = ADMIN_MUN;
            const year = parseInt(document.querySelector('#addBarangayForm select[name="year"]').value, 10);
            const allBgys = barangayLists[municipality] || [];

            if (!municipality || !allBgys.length) {
                showToast('No barangay master list is configured for your municipality.', 'warning');
                return;
            }

            const btn = document.querySelector('#addBarangayForm button[type="submit"]');
            const orig = btn.textContent;
            btn.textContent = '⏳ Adding all...'; btn.disabled = true;

            fetch('{{ route("admin.data.barangays.bulk-store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ year, barangays: allBgys })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        showToast(d.message, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('addBarangayModal')).hide();
                        setTimeout(() => location.reload(), 1400);
                    } else {
                        showToast(d.message || 'Error adding barangays.', 'danger');
                        btn.textContent = orig; btn.disabled = false;
                    }
                })
                .catch(() => { showToast('Network error.', 'danger'); btn.textContent = orig; btn.disabled = false; });
        });

        // ── Filter functions ─────────────────────────────────────────
        function applyFilters() {
            const year = document.getElementById('filterYear').value;
            const url = new URL(ADMIN_BARANGAYS_URL);
            if (year) url.searchParams.set('year', year);
            else url.searchParams.delete('year');
            window.location.href = url.toString();
        }
        
        function clearFilters() {
            window.location.href = ADMIN_BARANGAYS_URL;
        }

        // ── Custom confirmation modal logic ──────────────────────────
        let _pendingAction = null;

        function showConfirmModal(title, body, onConfirm) {
            document.getElementById('confirmDeleteTitle').textContent = title;
            document.getElementById('confirmDeleteBody').innerHTML = body;
            _pendingAction = onConfirm;
            // Reuse existing instance to avoid Bootstrap double-init error
            let modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
            if (!modal) modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
            modal.hide();
            if (_pendingAction) { _pendingAction(); _pendingAction = null; }
        });

        // ── Delegated listener for Archive buttons (safe: avoids JS parse issues with special chars in names)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.btn-archive-row');
            if (!btn) return;
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const year = btn.dataset.year;
            archiveBarangay(id, name, year);
        });

        // ── Per-row Archive (soft delete) ────────────────────────────
        function archiveBarangay(id, name, year) {
            showConfirmModal(
                'Archive Barangay Record',
                `Archive barangay data for <strong>${name}</strong> (${year})?<br>
                 <span style="color:#64748b;font-size:.88rem;">The record will be moved to the archive and can be restored later.</span>`,
                function () {
                    fetch('{{ url('/admin/data/barangays') }}/' + id + '/archive', {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) { showToast(data.message, 'success'); setTimeout(() => location.reload(), 1200); }
                            else showToast(data.message || 'Error archiving record.', 'danger');
                        })
                        .catch(() => showToast('Network error. Please try again.', 'danger'));
                }
            );
        }

        // ── Archive by Year (bulk soft delete) ───────────────────────
        function bulkArchiveByYear() {
            const year = document.getElementById('filterYear').value;

            if (!year) { showToast('Please select a year using the filter first.', 'warning'); return; }

            showConfirmModal(
                ' Archive All Records by Year',
                `This will archive <strong>ALL</strong> barangay records for:<br>
                 <span style="color:var(--primary-blue);font-weight:700;">${ADMIN_MUN} — ${year}</span><br><br>
                 <span style="color:#64748b;font-size:.88rem;">Records can be restored later from the <strong>Archived Barangays</strong> panel.</span>`,
                function () {
                    fetch('{{ route("admin.data.barangays.bulk-delete") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ year: parseInt(year, 10) })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast(`Archived ${data.count} barangay records for ${ADMIN_MUN} (${year}).`, 'success');
                                setTimeout(() => location.reload(), 1200);
                            } else {
                                showToast(data.message || 'Error archiving records.', 'danger');
                            }
                        })
                        .catch(() => showToast('Network error. Please try again.', 'danger'));
                }
            );
        }

        // ── Archived Barangays Modal ─────────────────────────────────
        function openArchivedModal() {
            const modal = new bootstrap.Modal(document.getElementById('archivedBarangaysModal'));
            modal.show();
            loadArchivedBarangays();
        }

        function loadArchivedBarangays() {
            const container = document.getElementById('archivedBarangaysList');
            const deleteAllBtn = document.getElementById('deleteAllArchivedBtn');
            container.innerHTML = '<div class="text-center py-5 text-muted"><div style="font-size:2rem;">❳</div><p>Loading...</p></div>';
            deleteAllBtn.style.display = 'none'; // hide while loading

            fetch('{{ route("admin.data.barangays.archived") }}', {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        container.innerHTML = '<div class="text-center py-5" style="color:#94a3b8;"><div style="font-size:2.5rem;opacity:.4;"></div><p class="fw-semibold mt-2">No archived barangay records.</p></div>';
                        deleteAllBtn.style.display = 'none';
                        return;
                    }

                    // Show the Delete All button now that we have records
                    deleteAllBtn.style.display = 'inline-block';
                    deleteAllBtn.textContent = `🗑 Delete All Permanently (${data.length})`;

                    let html = '<div class="table-responsive"><table class="premium-table"><thead><tr>' +
                        '<th>Year</th><th>Barangay</th><th>PWD</th><th>Solo Parent</th><th>AICS</th><th>Archived On</th><th>Actions</th>' +
                        '</tr></thead><tbody>';
                    data.forEach(b => {
                        const archivedOn = b.deleted_at ? new Date(b.deleted_at).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' }) : '—';
                        html += `<tr>
                        <td><strong style="color:var(--primary-blue);">${b.year ?? 'N/A'}</strong></td>
                        <td style="font-weight:700;">${b.name}</td>
                        <td>${(b.pwd_count ?? 0).toLocaleString()}</td>
                        <td>${(b.single_parent_count ?? 0).toLocaleString()}</td>
                        <td>${(b.aics_count ?? 0).toLocaleString()}</td>
                        <td style="font-size:.8rem;color:#94a3b8;">${archivedOn}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button onclick="restoreBarangay(${b.id},'${b.name}',${b.year})" style="background:rgba(40,167,69,.12);color:#28a745;border:1px solid rgba(40,167,69,.25);border-radius:8px;padding:5px 12px;font-size:.78rem;font-weight:700;cursor:pointer;">↩ Restore</button>
                                <button onclick="forceDeleteBarangay(${b.id},'${b.name}',${b.year})" style="background:rgba(196,30,36,.1);color:#C41E24;border:1px solid rgba(196,30,36,.2);border-radius:8px;padding:5px 12px;font-size:.78rem;font-weight:700;cursor:pointer;">🗑 Delete</button>
                            </div>
                        </td>
                    </tr>`;
                    });
                    html += '</tbody></table></div>';
                    container.innerHTML = html;
                })
                .catch(() => {
                    container.innerHTML = '<div class="text-center py-5 text-danger">Failed to load archived records.</div>';
                    deleteAllBtn.style.display = 'none';
                });
        }

        function restoreBarangay(id, name, year) {
            showConfirmModal(
                '↩ Restore Barangay Record',
                `Restore barangay data for <strong>${name}</strong> (${year}) back to active records?`,
                function () {
                    fetch('{{ url('/admin/data/barangays') }}/' + id + '/restore', {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) { showToast(data.message, 'success'); loadArchivedBarangays(); }
                            else showToast(data.message || 'Error restoring.', 'danger');
                        })
                        .catch(() => showToast('Network error.', 'danger'));
                }
            );
        }

        function forceDeleteBarangay(id, name, year) {
            showConfirmModal(
                '⚠︝ Permanently Delete Record',
                `Permanently delete barangay data for <strong>${name}</strong> (${year})?<br>
                 <span style="color:#C41E24;font-size:.88rem;">This action <u>cannot</u> be undone!</span>`,
                function () {
                    fetch('{{ url('/admin/data/barangays') }}/' + id + '/force-delete', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) { showToast(data.message, 'success'); loadArchivedBarangays(); }
                            else showToast(data.message || 'Error deleting.', 'danger');
                        })
                        .catch(() => showToast('Network error.', 'danger'));
                }
            );
        }

        // ── Delete ALL archived records permanently ───────────────────
        function forceDeleteAllArchived() {
            showConfirmModal(
                '⚠︝ Delete All Archived Records',
                `<strong>Permanently delete ALL archived barangay records for ${ADMIN_MUN}?</strong><br><br>
                 <span style="color:#C41E24;font-size:.88rem;">
                     This will remove <u>every</u> archived record for your municipality — this action
                     <u>cannot</u> be undone!
                 </span>`,
                function () {
                    fetch('{{ route("admin.data.barangays.archived.delete-all") }}', {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast(data.message, 'success');
                                document.getElementById('deleteAllArchivedBtn').style.display = 'none';
                                loadArchivedBarangays();
                            } else {
                                showToast(data.message || 'Error deleting records.', 'danger');
                            }
                        })
                        .catch(() => showToast('Network error.', 'danger'));
                }
            );
        }

        function saveAllRows() {
            const rowEls = document.querySelectorAll('.bgy-row');
            if (!rowEls.length) {
                showToast('No rows to save.', 'warning');
                return;
            }
            const rows = [...rowEls].map(tr => getRowData(tr.dataset.id)).filter(Boolean);

            for (let r of rows) {
                if (r.total_households > r.total_population) {
                    return showToast('Validation: households cannot exceed total population in one or more rows.', 'danger');
                }
                const checks = [
                    ['Solo Parent', r.single_parent_count],
                    ['PWD', r.pwd_count],
                    ['AICS', r.aics_count],
                    ['4Ps', r.four_ps_count],
                    ['Senior', r.senior_count]
                ];
                for (let [label, count] of checks) {
                    if (count > r.total_population) {
                        return showToast(`Validation: ${label} count cannot exceed total population.`, 'danger');
                    }
                }
            }

            const btn = document.getElementById('saveAllBtn');
            const orig = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving…';

            fetch('{{ route("admin.data.barangays.bulk-update") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ rows })
            })
                .then(r => r.json())
                .then(d => {
                    btn.disabled = false;
                    btn.textContent = orig;
                    if (d.success) {
                        showToast(d.message || 'All rows saved.', 'success');
                        document.querySelectorAll('.bgy-row.dirty').forEach(tr => tr.classList.remove('dirty'));
                        const y = new URLSearchParams(window.location.search).get('year');
                        setTimeout(() => {
                            window.location.href = y ? (ADMIN_BARANGAYS_URL + '?year=' + encodeURIComponent(y)) : ADMIN_BARANGAYS_URL;
                        }, 650);
                    } else {
                        showToast(d.message || 'Save failed.', 'danger');
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.textContent = orig;
                    showToast('Network error.', 'danger');
                });
        }

        // ── Toast helper ─────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.innerHTML = `<div class="toast-timer"></div><span>${message}</span>`;
            toast.className = `toast-msg toast-${type}`;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3500);
        }

        // ── Inline Editing ───────────────────────────────────────────
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('inline-input') || e.target.classList.contains('inline-select')) {
                const row = e.target.closest('.bgy-row');
                if (row) {
                    row.classList.add('dirty');
                }
            }
        });

        function getRowData(id) {
            const row = document.querySelector(`.bgy-row[data-id="${id}"]`);
            if (!row) return null;
            const data = { id: parseInt(id, 10), year: parseInt(row.dataset.year, 10) };
            row.querySelectorAll('.inline-input, .inline-select').forEach(inp => {
                const field = inp.dataset.field;
                data[field] = inp.type === 'number' ? parseInt(inp.value) || 0 : inp.value;
            });
            return data;
        }

        function saveRow(id) {
            const data = getRowData(id);
            if (!data) return;
            const btn = document.querySelector(`.bgy-row[data-id="${id}"] .btn-save-row`);
            const orig = btn.textContent;
            btn.textContent = '❳'; btn.disabled = true;

            fetch('{{ url('/admin/data/barangays') }}/' + id + '/update', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showToast(res.message || 'Saved!', 'success');
                        const row = document.querySelector(`.bgy-row[data-id="${id}"]`);
                        if (row) row.classList.remove('dirty');
                    } else {
                        showToast(res.message || 'Error saving.', 'danger');
                    }
                    btn.textContent = orig; btn.disabled = false;
                })
                .catch(() => { showToast('Network error.', 'danger'); btn.textContent = orig; btn.disabled = false; });
        }

        window.addEventListener('DOMContentLoaded', function () {
            // Check if URL has #return hash
            if (window.location.hash === '#return') {
                // Wait for page to fully load
                setTimeout(() => {
                    const el = document.getElementById('return');
                    if (el) {
                        // Scroll to the table with smooth animation
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        // Add a small offset to account for any fixed headers
                        window.scrollBy(0, -20);
                    }
                }, 300);
            }
        });

        // Also handle when coming back from another page
        window.addEventListener('load', function() {
            if (window.location.hash === '#return') {
                setTimeout(() => {
                    const el = document.getElementById('return');
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        window.scrollBy(0, -20);
                    }
                }, 100);
            }
        });

        // ── Search function ──────────────────────────────────────────
        function searchTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#barangayTable tbody tr');
            
            rows.forEach(row => {
                const barangayName = row.cells[1]?.textContent.toLowerCase() || '';
                
                if (barangayName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Show notification on page load if exists
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    </script>

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
                            <input type="hidden" name="import_type" value="barangay_data">
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
                                <a href="{{ route('admin.csv.template', 'barangay_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Barangay Template
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Export Section -->
                    <div id="exportSection" style="display:none;">
                        <form action="{{ route('admin.csv.export') }}" method="POST" id="exportForm">
                            @csrf
                            <input type="hidden" name="export_type" value="barangay_data">
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

    <script>
        // CSV Modal
        function openCsvModal(type) {
            const modal = new bootstrap.Modal(document.getElementById('csvModal'));
            const title = document.getElementById('csvModalTitle');
            const importSection = document.getElementById('importSection');
            const exportSection = document.getElementById('exportSection');
            
            if (type === 'import') {
                title.innerHTML = '<i class="bi bi-upload"></i> Import Barangay CSV Data';
                importSection.style.display = 'block';
                exportSection.style.display = 'none';
            } else {
                title.innerHTML = '<i class="bi bi-download"></i> Export Barangay CSV Data';
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
    </script>
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @include('components.admin-notification-modal')
    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')
</body>

</html>