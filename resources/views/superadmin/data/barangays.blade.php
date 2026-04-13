<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Data – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .btn-update-all {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 20px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all .3s;
            white-space: nowrap;
        }

        .btn-update-all:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-update-all:disabled {
            opacity: .55;
            transform: none;
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
            <h1>Barangay Data</h1>
            <div class="hero-divider"></div>
            <p>Edit barangay-level population, household, and demographic statistics per municipality.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <div class="filter-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold" style="color:var(--primary-blue);">Filter Barangay Records</h6>
                    <div class="d-flex gap-2">
                        <button class="btn-action-delete" onclick="bulkArchiveByYear()" style="padding:8px 20px;">🗑
                            Archive by Year</button>
                        <button class="btn-action-edit" onclick="openArchivedModal()"
                            style="background:rgba(44,62,143,.12);color:var(--primary-blue);border:1.5px solid rgba(44,62,143,.25);border-radius:8px;padding:8px 20px;font-size:.8rem;font-weight:700;cursor:pointer;">📂
                            Archived Barangays</button>
                        <button class="btn-action-edit" data-bs-toggle="modal" data-bs-target="#addBarangayModal"
                            style="background:var(--secondary-gradient);padding:8px 20px;">+ Add Barangay Data</button>
                    </div>
                </div>
                <form method="GET" action="{{ route('superadmin.data.barangays') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Municipality</label>
                            <select name="municipality" id="filterMunicipality" class="form-select">
                                <option value="">All Municipalities</option>
                                @foreach($municipalities as $municipality)
                                    <option value="{{ $municipality }}" {{ request('municipality') == $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select name="year" id="filterYear" class="form-select">
                                <option value="">All Years</option>
                                @foreach($years as $yr)
                                    <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-filter w-100">Apply Filter</button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('superadmin.data.barangays') }}" class="btn btn-outline-secondary w-100"
                                style="padding:10px;">Clear Filters</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Inline-Edit Table -->
            <div class="panel-card">
                <div class="panel-header">
                    <h5>Barangay Records</h5>
                    <span class="count-badge">{{ $barangays->count() }} records</span>
                </div>
                <div class="table-responsive" style="max-height:620px;overflow-y:auto;">
                    <table class="premium-table" id="barangayTable">
                        <thead style="position:sticky;top:0;z-index:2;">
                            <tr>
                                <th>Year</th>
                                <th>Municipality</th>
                                <th>Barangay</th>
                                <th style="text-align:center;">Total Population</th>
                                <th style="text-align:center;">PWD</th>
                                <th style="text-align:center;">AICS</th>
                                <th style="text-align:center;">Solo Parent</th>
                                <th style="text-align:center;">Households</th>
                                <th style="text-align:center;">Approved Apps</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangays as $barangay)
                                <tr class="bgy-row" data-id="{{ $barangay->id }}">
                                    <td><span style="font-weight:700;color:var(--primary-blue);">{{ $barangay->year ?? date('Y') }}</span></td>
                                    <td><span style="font-size:.83rem;color:#64748b;">{{ $barangay->municipality }}</span></td>
                                    <td style="font-weight:700;color:var(--primary-blue);font-size:.88rem;">{{ $barangay->name }}</td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="male_population" value="{{ $barangay->male_population }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="pwd_count" value="{{ $barangay->pwd_count }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="aics_count" value="{{ $barangay->aics_count }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="single_parent_count" value="{{ $barangay->single_parent_count }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="total_households" value="{{ $barangay->total_households }}" min="0"></td>
                                    <td style="text-align:center;"><input type="number" class="inline-input" data-field="total_approved_applications" value="{{ $barangay->total_approved_applications }}" min="0"></td>
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
                                    <td colspan="5" class="text-center py-5">
                                        <div style="font-size:2.8rem;opacity:.25;">📭</div>
                                        @if(request('municipality') && request('year'))
                                            <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No records for <span
                                                    style="color:var(--primary-blue);">{{ request('municipality') }}</span> in
                                                <span style="color:var(--primary-blue);">{{ request('year') }}</span>
                                            </p>
                                            <p class="mb-3" style="color:#94a3b8;font-size:.88rem;">Data for this municipality
                                                &amp; year combination has not been added yet.</p>
                                            @if(isset($availableYears[request('municipality')]) && count($availableYears[request('municipality')]) > 0)
                                                <p style="color:#64748b;font-size:.85rem;">Available years for
                                                    {{ request('municipality') }}:
                                                    <strong>{{ implode(', ', $availableYears[request('municipality')]) }}</strong>
                                                </p>
                                            @endif
                                        @elseif(request('municipality'))
                                            <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No records found for <span
                                                    style="color:var(--primary-blue);">{{ request('municipality') }}</span></p>
                                            <p class="mb-3" style="color:#94a3b8;font-size:.88rem;">No barangay data has been
                                                entered for this municipality yet.</p>
                                        @elseif(request('year'))
                                            <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No records found for year <span
                                                    style="color:var(--primary-blue);">{{ request('year') }}</span></p>
                                            <p class="mb-3" style="color:#94a3b8;font-size:.88rem;">No barangay data has been
                                                entered for this year yet.</p>
                                        @else
                                            <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No barangay records found</p>
                                            <p class="mb-3" style="color:#94a3b8;font-size:.88rem;">Click "+ Add Barangay Data"
                                                to start adding records.</p>
                                        @endif
                                        <button data-bs-toggle="modal" data-bs-target="#addBarangayModal"
                                            class="btn-modal-submit" style="font-size:.88rem;padding:10px 24px;">+ Add
                                            Barangay Data</button>
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
                    <h5 class="modal-title">📂 Archived Barangay Records</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="archivedBarangaysList" style="min-height:200px;">
                        <div class="text-center py-5 text-muted">
                            <div style="font-size:2rem;">⏳</div>
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
                        <div style="font-size:2rem;line-height:1;">⚠️</div>
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
                <form method="POST" action="{{ route('superadmin.data.barangays.store') }}" id="addBarangayForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Municipality</label>
                                <select name="municipality" id="addMunicipality" class="form-select" required
                                    onchange="loadBarangayOptions(this.value)">
                                    <option value="">Select Municipality</option>
                                    @foreach($municipalities as $municipality)
                                        <option value="{{ $municipality }}">{{ $municipality }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Barangay Name</label>
                                <select name="name" id="addBarangayName" class="form-select" required disabled>
                                    <option value="">Select municipality first</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" required>
                                    @foreach($years as $yr)
                                        <option value="{{ $yr }}" {{ $yr == date('Y') ? 'selected' : '' }}>{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Population</label>
                                <input type="number" name="total_population" class="form-control" required min="0"
                                    value="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">PWD Count</label>
                                <input type="number" name="pwd_count" class="form-control" min="0" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">AICS Count</label>
                                <input type="number" name="aics_count" class="form-control" min="0" value="0">
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
        let dirtyRows = new Set();

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
                // ── Select All option ──
                const allOpt = document.createElement('option');
                allOpt.value = '__ALL__';
                allOpt.textContent = '✅ All Barangays (' + barangayLists[municipality].length + ')';
                allOpt.style.cssText = 'font-weight:700;color:#1e7e34;background:#f0fff4;';
                select.appendChild(allOpt);
                // divider
                const divider = document.createElement('option');
                divider.disabled = true;
                divider.textContent = '──────────────';
                select.appendChild(divider);
                // individual barangays
                barangayLists[municipality].forEach(barangay => {
                    const option = document.createElement('option');
                    option.value = barangay;
                    option.textContent = barangay;
                    select.appendChild(option);
                });
            } else {
                select.disabled = true;
                select.innerHTML = '<option value="">Select municipality first</option>';
            }
        }

        // Intercept Add form — if "All Barangays" selected, bulk-store via AJAX
        document.getElementById('addBarangayForm').addEventListener('submit', function (e) {
            const bgySelect = document.getElementById('addBarangayName');
            if (bgySelect.value !== '__ALL__') return; // normal submit for single barangay
            e.preventDefault();

            const municipality = document.getElementById('addMunicipality').value;
            const year = parseInt(document.querySelector('#addBarangayForm select[name="year"]').value);
            const allBgys = barangayLists[municipality] || [];

            if (!municipality || !allBgys.length) {
                showToast('Please select a valid municipality.', 'warning');
                return;
            }

            const btn = document.querySelector('#addBarangayForm button[type="submit"]');
            const orig = btn.textContent;
            btn.textContent = '⏳ Adding all...'; btn.disabled = true;

            fetch('{{ route("superadmin.data.barangays.bulk-store") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ municipality, year, barangays: allBgys })
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

        // ── Filter form: always reset to page 1 when filters change ─────────
        document.getElementById('filterForm').addEventListener('submit', function (e) {
            // Remove 'page' from form values so we always go to page 1 on new filters
            const existing = this.querySelector('input[name="page"]');
            if (existing) existing.remove();
        });

        // Auto-submit when municipality dropdown changes
        document.getElementById('filterMunicipality').addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });

        // Auto-submit when year dropdown changes
        document.getElementById('filterYear').addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });

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
                    fetch('/superadmin/data/barangays/' + id + '/archive', {
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
            const municipality = document.getElementById('filterMunicipality').value;
            const year = document.getElementById('filterYear').value;

            if (!municipality) { showToast('Please select a municipality using the filter first.', 'warning'); return; }
            if (!year) { showToast('Please select a year using the filter first.', 'warning'); return; }

            showConfirmModal(
                '📂 Archive All Records by Year',
                `This will archive <strong>ALL</strong> barangay records for:<br>
                 <span style="color:var(--primary-blue);font-weight:700;">${municipality} — ${year}</span><br><br>
                 <span style="color:#64748b;font-size:.88rem;">Records can be restored later from the <strong>Archived Barangays</strong> panel.</span>`,
                function () {
                    fetch('/superadmin/data/barangays/bulk-delete', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ municipality, year })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast(`Archived ${data.count} barangay records for ${municipality} (${year}).`, 'success');
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
            container.innerHTML = '<div class="text-center py-5 text-muted"><div style="font-size:2rem;">⏳</div><p>Loading...</p></div>';
            deleteAllBtn.style.display = 'none'; // hide while loading

            fetch('/superadmin/data/barangays/archived', {
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    if (!data.length) {
                        container.innerHTML = '<div class="text-center py-5" style="color:#94a3b8;"><div style="font-size:2.5rem;opacity:.4;">📭</div><p class="fw-semibold mt-2">No archived barangay records.</p></div>';
                        deleteAllBtn.style.display = 'none';
                        return;
                    }

                    // Show the Delete All button now that we have records
                    deleteAllBtn.style.display = 'inline-block';
                    deleteAllBtn.textContent = `🗑 Delete All Permanently (${data.length})`;

                    let html = '<div class="table-responsive"><table class="premium-table"><thead><tr>' +
                        '<th>Year</th><th>Municipality</th><th>Barangay</th><th>PWD</th><th>Solo Parent</th><th>AICS</th><th>Archived On</th><th>Actions</th>' +
                        '</tr></thead><tbody>';
                    data.forEach(b => {
                        const archivedOn = b.deleted_at ? new Date(b.deleted_at).toLocaleDateString('en-PH', { year: 'numeric', month: 'short', day: 'numeric' }) : '—';
                        html += `<tr>
                        <td><strong style="color:var(--primary-blue);">${b.year ?? 'N/A'}</strong></td>
                        <td style="color:#64748b;font-size:.85rem;">${b.municipality}</td>
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
                    fetch('/superadmin/data/barangays/' + id + '/restore', {
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
                '⚠️ Permanently Delete Record',
                `Permanently delete barangay data for <strong>${name}</strong> (${year})?<br>
                 <span style="color:#C41E24;font-size:.88rem;">This action <u>cannot</u> be undone!</span>`,
                function () {
                    fetch('/superadmin/data/barangays/' + id + '/force-delete', {
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
                '⚠️ Delete All Archived Records',
                `<strong>Permanently delete ALL archived barangay records?</strong><br><br>
                 <span style="color:#C41E24;font-size:.88rem;">
                     This will remove <u>every</u> record in the archive — this action
                     <u>cannot</u> be undone!
                 </span>`,
                function () {
                    fetch('/superadmin/data/barangays/archived/delete-all', {
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
                    const id = row.dataset.id;
                    dirtyRows.add(id);
                    row.classList.add('dirty');
                }
            }
        });

        function getRowData(id) {
            const row = document.querySelector(`.bgy-row[data-id="${id}"]`);
            if (!row) return null;
            const data = { id };
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
            btn.textContent = '⏳'; btn.disabled = true;

            fetch('/superadmin/data/barangays/' + id, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showToast(res.message || 'Saved!', 'success');
                        dirtyRows.delete(id);
                        const row = document.querySelector(`.bgy-row[data-id="${id}"]`);
                        if (row) row.classList.remove('dirty');
                    } else {
                        showToast(res.message || 'Error saving.', 'danger');
                    }
                    btn.textContent = orig; btn.disabled = false;
                })
                .catch(() => { showToast('Network error.', 'danger'); btn.textContent = orig; btn.disabled = false; });
        }

        function updateAll() {
            if (!dirtyRows.size) { showToast('No changes to save.', 'warning'); return; }
            const allData = [];
            dirtyRows.forEach(id => {
                const d = getRowData(id);
                if (d) allData.push(d);
            });
            if (!allData.length) return;

            const btn = document.getElementById('updateAllBtn');
            const orig = btn.textContent;
            btn.textContent = '⏳ Saving...'; btn.disabled = true;

            fetch('/superadmin/data/barangays/bulk-update', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ records: allData })
            })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        showToast(res.message || 'All changes saved!', 'success');
                        dirtyRows.clear();
                        document.querySelectorAll('.bgy-row.dirty').forEach(r => r.classList.remove('dirty'));
                    } else {
                        showToast(res.message || 'Error saving.', 'danger');
                    }
                    btn.textContent = orig; btn.disabled = false;
                })
                .catch(() => { showToast('Network error.', 'danger'); btn.textContent = orig; btn.disabled = false; });
        }
    </script>
</body>

</html>