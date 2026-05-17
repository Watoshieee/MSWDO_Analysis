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
            @include('components.admin-notification')
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <!-- Action Buttons Row -->
            <div class="d-flex justify-content-end gap-2 mb-3">
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
            const colors = { success: '#2C3E8F', danger: '#C41E24', warning: '#E5A500' };
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:28px;right:28px;z-index:9999;background:${colors[type] || colors.success};color:white;padding:14px 22px;border-radius:12px;font-weight:600;font-size:.9rem;box-shadow:0 8px 24px rgba(0,0,0,.18);max-width:380px;`;
            t.textContent = message;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3500);
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
            if (window.location.hash === '#return') {
                const el = document.getElementById('return');
                if (el) setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
            }
        });
    </script>
    @include('components.admin-notification-modal')
    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')
</body>

</html>