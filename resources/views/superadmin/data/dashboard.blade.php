<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management – MSWDO Super Admin</title>
    <meta name="description" content="MSWDO Super Admin Data Management — manage, import, and export municipality, barangay, and social program data.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --accent-red: #C41E24;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        a { text-decoration: none; }

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* HERO */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 38px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -80px; right: -80px; width: 340px; height: 340px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-banner::after  { content: ''; position: absolute; bottom: -60px; left: -40px; width: 230px; height: 230px; border-radius: 50%; background: rgba(255,255,255,0.05); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 10px; }
        .hero-banner h1 { font-size: 2rem; font-weight: 900; margin-bottom: 4px; }
        .hero-divider { width: 44px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 8px; }
        .hero-banner p { opacity: 0.82; font-size: 0.85rem; margin: 0; }

        /* SECTION HEADING */
        .section-heading { font-size: 1.05rem; font-weight: 800; color: var(--primary-blue); position: relative; padding-bottom: 10px; margin-bottom: 20px; }
        .section-heading::after { content: ''; position: absolute; bottom: 0; left: 0; width: 36px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; }

        /* MENU CARDS */
        .menu-card { display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 14px; background: var(--primary-gradient); color: white; border: none; transition: all 0.25s ease; text-decoration: none; box-shadow: 0 3px 14px rgba(44,62,143,0.18); height: 100%; }
        .menu-card:hover { box-shadow: 0 10px 28px rgba(44,62,143,0.32); transform: translateY(-3px); color: white; }
        .menu-text { flex: 1; }
        .menu-num   { font-size: 0.6rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(253,185,19,0.9); display: block; margin-bottom: 4px; }
        .menu-title { font-size: 1rem; font-weight: 800; color: white; display: block; margin-bottom: 4px; line-height: 1.25; }
        .menu-desc  { font-size: 0.76rem; color: rgba(255,255,255,0.72); display: block; line-height: 1.5; }
        .menu-arrow { font-size: 1.4rem; color: rgba(255,255,255,0.55); flex-shrink: 0; transition: color 0.2s; }
        .menu-card:hover .menu-arrow { color: var(--secondary-yellow); }

        /* EXPORT BUTTONS */
        .export-btn { border-radius: 8px; font-weight: 700; font-size: 0.83rem; transition: all 0.2s ease; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap; line-height: 1.4; }
        .export-btn-csv    { background: #FFFFFF; color: var(--primary-blue); border: 1.5px solid var(--primary-blue); padding: 7px 15px; box-shadow: 0 2px 6px rgba(44,62,143,0.08); }
        .export-btn-csv:hover    { background: var(--primary-blue); color: #FFFFFF; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,62,143,0.18); }
        .export-btn-report { background: var(--primary-gradient); color: #FFFFFF; border: none; padding: 8.5px 16px; box-shadow: 0 3px 12px rgba(44,62,143,0.22); }
        .export-btn-report:hover { filter: brightness(1.08); transform: translateY(-1px); color: #FFFFFF; box-shadow: 0 6px 16px rgba(44,62,143,0.30); }
        .export-btn-import { background: #FFFFFF; color: var(--primary-blue); border: 1.5px solid var(--primary-blue); padding: 7px 15px; box-shadow: 0 2px 6px rgba(44,62,143,0.08); }
        .export-btn-import:hover { background: var(--primary-blue); color: #FFFFFF; transform: translateY(-1px); }
        .export-btn-options { background: #E2E8F0; color: #475569; border: none; padding: 8.5px 12px; }
        .export-btn-options:hover { background: #CBD5E1; color: #1E293B; transform: translateY(-1px); }

        /* MAIN */
        .main-content { flex: 1; }

        /* FOOTER */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 48px; }
        .footer-strip strong { color: white; }

        /* Preview table */
        #previewTableWrap { overflow-x: auto; max-height: 220px; overflow-y: auto; border-radius: 8px; border: 1px solid var(--border-light); }
        #previewTable { font-size: 0.75rem; margin: 0; }
        #previewTable th { background: var(--primary-blue); color: white; padding: 6px 10px; white-space: nowrap; position: sticky; top: 0; }
        #previewTable td { padding: 5px 10px; white-space: nowrap; }
        #previewTable tr:nth-child(even) { background: #f1f5f9; }

        /* loading spinner inline */
        .btn-spinner { display: none; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.5); border-top-color: #fff; border-radius: 50%; animation: spin 0.65s linear infinite; margin-right: 6px; }
        .btn-spinner-dark { border-color: rgba(44,62,143,0.3); border-top-color: var(--primary-blue); }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Full-page loading overlay — two states: loading & result */
        .ui-loading-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26,42,92,0.72);
            backdrop-filter: blur(3px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .ui-loading-backdrop.active { display: flex; }
        .ui-loading-box {
            background: #fff;
            border-radius: 20px;
            padding: 40px 48px;
            text-align: center;
            box-shadow: 0 24px 64px rgba(44,62,143,0.28);
            min-width: 320px;
            max-width: 480px;
            width: 90%;
        }
        .ui-loading-spinner {
            width: 52px; height: 52px;
            border: 5px solid #E5EEFF;
            border-top-color: var(--primary-blue);
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
            margin: 0 auto 18px;
        }
        .ui-result-icon {
            font-size: 3rem;
            margin-bottom: 14px;
            line-height: 1;
        }
        .ui-loading-title  { font-weight: 800; font-size: 1.05rem; color: var(--primary-blue); }
        .ui-loading-sub    { margin-top: 6px; font-size: 0.83rem; color: #475569; opacity: 0.85; }
        .ui-result-stats {
            display: flex; gap: 10px; flex-wrap: wrap;
            justify-content: center;
            margin: 16px 0 0;
        }
        .ui-result-pill {
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .ui-result-pill.green  { background:#DCFCE7; color:#166534; }
        .ui-result-pill.blue   { background:#DBEAFE; color:#1E40AF; }
        .ui-result-pill.yellow { background:#FEF9C3; color:#854D0E; }
        .ui-result-pill.red    { background:#FEE2E2; color:#991B1B; }
        .ui-result-errors {
            margin: 12px 0 0;
            text-align: left;
            max-height: 140px;
            overflow-y: auto;
            background: #FFF1F2;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.75rem;
            color: #991B1B;
        }
        .ui-result-errors li { margin-bottom: 3px; }
        .ui-close-btn {
            margin-top: 20px;
            background: var(--primary-gradient);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 9px 28px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .ui-close-btn:hover { opacity: 0.88; }
    </style>
</head>
<body>

    <!-- Full-page loading/result overlay -->
    <div id="uiLoadingBackdrop" class="ui-loading-backdrop" aria-hidden="true">
        <div class="ui-loading-box" role="status" aria-live="polite">
            <!-- Loading state -->
            <div id="overlayLoading">
                <div class="ui-loading-spinner"></div>
                <div class="ui-loading-title" id="overlayTitle">Importing Data</div>
                <div class="ui-loading-sub" id="overlaySub">Validating and saving records &mdash; please wait&hellip;</div>
            </div>
            <!-- Result state (shown after AJAX returns) -->
            <div id="overlayResult" style="display:none;">
                <div class="ui-result-icon" id="overlayResultIcon"></div>
                <div class="ui-loading-title" id="overlayResultTitle"></div>
                <div class="ui-loading-sub" id="overlayResultSub"></div>
                <div class="ui-result-stats" id="overlayStats"></div>
                <ul class="ui-result-errors" id="overlayErrors" style="display:none;list-style:disc;padding-left:18px;"></ul>
                <button class="ui-close-btn" onclick="hideImportOverlay()">Done</button>
            </div>
        </div>
    </div>


    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <img src="{{ asset('images/logo_mswdo.jpg') }}" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
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

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Super Admin</div>
                <h1>Data Management</h1>
                <div class="hero-divider"></div>
                <p>Update, import, and export population, household, and social program data across all municipalities.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">


        <!-- Header row: section title + action buttons -->
        <div style="display:flex;justify-content:space-between;align-items:flex-end;flex-wrap:wrap;gap:12px;margin-bottom:8px;">
            <div>
                <div class="section-heading" style="margin-bottom:0;padding-bottom:8px;">Manage Data</div>
                <div style="font-size:0.82rem;color:#64748b;">Select a category below to add, edit, or review records.</div>
            </div>
            <div class="d-flex align-items-center gap-2" style="margin-bottom:10px;">
                <!-- Import button -->
                <button type="button" class="export-btn export-btn-import" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-upload me-1"></i> Import CSV
                </button>
                <!-- Export CSV button -->
                <button type="button" class="export-btn export-btn-csv" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="bi bi-download me-1"></i> Export Data
                </button>
                <!-- Export Excel -->
                <button type="button" class="export-btn export-btn-report" data-bs-toggle="modal" data-bs-target="#exportModal" onclick="document.getElementById('modalExportTarget').value='excel'">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
                </button>
            </div>
        </div>

        <!-- Menu Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="{{ route('superadmin.data.municipalities') }}" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">01 &mdash; Municipality</span>
                        <span class="menu-title">Municipality Data</span>
                        <span class="menu-desc">Update population by gender, age groups, households and demographics for each municipality.</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('superadmin.data.programs') }}" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">02 &mdash; Programs</span>
                        <span class="menu-title">Social Programs</span>
                        <span class="menu-desc">Track beneficiary counts per social welfare program across each municipality.</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('superadmin.data.barangays') }}" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">03 &mdash; Barangay</span>
                        <span class="menu-title">Barangay Data</span>
                        <span class="menu-desc">Manage barangay-level statistics and demographics across all municipalities.</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
        </div>

    </div>
    </div>

    <div class="footer-strip"><strong>MSWDO</strong> Analysis System &mdash; Super Admin</div>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- EXPORT MODAL                                                   -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;padding:18px 24px;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--secondary-yellow);margin-bottom:2px;">
                            DATA MANAGEMENT &bull; EXPORT CENTER
                        </div>
                        <h5 class="modal-title" id="exportModalLabel" style="font-weight:900;font-size:1.25rem;margin:0;">
                            Export Data Records
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="background:#F8FAFC;padding:24px;">

                    <!-- Filters row -->
                    <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:16px 18px;margin-bottom:20px;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;color:#64748B;letter-spacing:0.06em;margin-bottom:12px;">Filter Export</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Municipality</label>
                                <select id="expMunicipality" class="form-select form-select-sm">
                                    <option value="">All Municipalities</option>
                                    @foreach ($municipalities as $muni)
                                        <option value="{{ $muni->name }}">{{ $muni->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Year</label>
                                <select id="expYear" class="form-select form-select-sm">
                                    <option value="">All Years</option>
                                    @foreach (range(date('Y'), 2015, -1) as $yr)
                                        <option value="{{ $yr }}">{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Category</label>
                                <select id="expCategory" class="form-select form-select-sm">
                                    <option value="all">All Categories</option>
                                    <option value="municipality">Municipality Yearly Data</option>
                                    <option value="barangay">Barangay Data</option>
                                    <option value="programs">Social Programs</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Option cards -->
                    <div class="row g-3">
                        <!-- Option A: CSV -->
                        <div class="col-md-6">
                            <div style="background:#FFFFFF;border:1px solid #C7D6F5;border-radius:14px;padding:20px;height:100%;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 4px 16px rgba(44,62,143,0.06);">
                                <div>
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                        <div style="width:38px;height:38px;border-radius:10px;background:#EEF2FF;color:var(--primary-blue);display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                                            <i class="bi bi-file-earmark-text"></i>
                                        </div>
                                        <div>
                                            <span style="font-size:0.65rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:var(--primary-blue);">Option A</span>
                                            <h6 style="font-weight:800;color:var(--text-dark);margin:0;font-size:0.95rem;">Export CSV</h6>
                                        </div>
                                    </div>
                                    <p style="font-size:0.78rem;color:#64748B;margin-bottom:12px;line-height:1.5;">
                                        Downloads a UTF-8 CSV file with labeled sections for all selected categories. Compatible with Excel, R, Python, and SPSS.
                                    </p>
                                    <ul style="font-size:0.73rem;color:#475569;padding-left:18px;margin-bottom:16px;">
                                        <li>Section 1: Municipality Yearly Data</li>
                                        <li>Section 2: Barangay Data</li>
                                        <li>Section 3: Social Programs</li>
                                    </ul>
                                </div>
                                <form id="exportCsvForm" method="GET" action="{{ route('superadmin.data.export.csv') }}">
                                    <input type="hidden" name="municipality" id="csvMuni">
                                    <input type="hidden" name="year"         id="csvYear">
                                    <input type="hidden" name="category"     id="csvCat" value="all">
                                    <button type="submit" id="btnExportCsv" class="btn w-100" style="background:#FFFFFF;color:var(--primary-blue);border:2px solid var(--primary-blue);font-weight:800;font-size:0.85rem;border-radius:10px;padding:9px 16px;">
                                        <span class="btn-spinner btn-spinner-dark" id="spinCsv"></span>
                                        <i class="bi bi-download me-1" id="icoExportCsv"></i> Download CSV
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option B: Excel -->
                        <div class="col-md-6">
                            <div style="background:#FFFFFF;border:1px solid #FCD34D;border-radius:14px;padding:20px;height:100%;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 4px 16px rgba(253,185,19,0.12);">
                                <div>
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                                        <div style="width:38px;height:38px;border-radius:10px;background:#FEF3C7;color:#B45309;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>
                                        <div>
                                            <span style="font-size:0.65rem;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#B45309;">Option B</span>
                                            <h6 style="font-weight:800;color:var(--text-dark);margin:0;font-size:0.95rem;">Export Excel (.xlsx)</h6>
                                        </div>
                                    </div>
                                    <p style="font-size:0.78rem;color:#64748B;margin-bottom:12px;line-height:1.5;">
                                        Generates a professional Excel workbook with 4 structured worksheets, MSWDO branding, alternating rows, auto-filters, and print-ready A4 layout.
                                    </p>
                                    <ul style="font-size:0.73rem;color:#475569;padding-left:18px;margin-bottom:16px;">
                                        <li>Sheet 1: Municipality Yearly Data</li>
                                        <li>Sheet 2: Barangay Data</li>
                                        <li>Sheet 3: Social Programs</li>
                                        <li>Sheet 4: Export Summary</li>
                                    </ul>
                                </div>
                                <form id="exportXlsxForm" method="GET" action="{{ route('superadmin.data.export.excel') }}">
                                    <input type="hidden" name="municipality" id="xlsxMuni">
                                    <input type="hidden" name="year"         id="xlsxYear">
                                    <input type="hidden" name="category"     id="xlsxCat" value="all">
                                    <button type="submit" id="btnExportXlsx" class="btn w-100" style="background:var(--primary-gradient);color:white;border:none;font-weight:800;font-size:0.85rem;border-radius:10px;padding:10px 16px;box-shadow:0 4px 12px rgba(44,62,143,0.25);">
                                        <span class="btn-spinner" id="spinXlsx"></span>
                                        <i class="bi bi-file-earmark-excel me-1" id="icoExportXlsx"></i> Download Excel (.xlsx)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:14px;font-size:0.72rem;color:#64748B;font-style:italic;">
                        <i class="bi bi-info-circle me-1"></i>
                        Exports always reflect the current live database. Results depend on the applied municipality, year, and category filters.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- IMPORT MODAL                                                   -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;padding:18px 24px;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--secondary-yellow);margin-bottom:2px;">
                            DATA MANAGEMENT &bull; IMPORT CENTER
                        </div>
                        <h5 class="modal-title" id="importModalLabel" style="font-weight:900;font-size:1.25rem;margin:0;">
                            Import CSV Data
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="background:#F8FAFC;padding:24px;">

                    <!-- Template downloads -->
                    <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:14px 18px;margin-bottom:18px;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;color:#64748B;letter-spacing:0.06em;margin-bottom:10px;">Step 1 — Download a Template</div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('superadmin.data.import.template', 'municipality_data') }}" class="btn btn-sm" style="background:#EEF2FF;color:var(--primary-blue);border:1px solid #C7D6F5;font-weight:700;border-radius:8px;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Municipality Template
                            </a>
                            <a href="{{ route('superadmin.data.import.template', 'barangay_data') }}" class="btn btn-sm" style="background:#EEF2FF;color:var(--primary-blue);border:1px solid #C7D6F5;font-weight:700;border-radius:8px;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Barangay Template
                            </a>
                            <a href="{{ route('superadmin.data.import.template', 'program_data') }}" class="btn btn-sm" style="background:#EEF2FF;color:var(--primary-blue);border:1px solid #C7D6F5;font-weight:700;border-radius:8px;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Social Programs Template
                            </a>
                        </div>
                    </div>

                    <!-- Import form -->
                    <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:16px 18px;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;color:#64748B;letter-spacing:0.06em;margin-bottom:12px;">Step 2 — Upload Your CSV</div>
                        <form id="importForm" method="POST" action="{{ route('superadmin.data.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Data Type *</label>
                                    <select name="import_type" id="impType" class="form-select form-select-sm" required>
                                        <option value="">— Select Type —</option>
                                        <option value="municipality_data">Municipality Yearly Data</option>
                                        <option value="barangay_data">Barangay Data</option>
                                        <option value="program_data">Social Programs</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Year Filter <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                                    <select name="year" id="impYear" class="form-select form-select-sm">
                                        <option value="">All Years in File</option>
                                        @foreach (range(2030, 2021, -1) as $yr)
                                            <option value="{{ $yr }}">{{ $yr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Duplicates</label>
                                    <select name="import_mode" class="form-select form-select-sm">
                                        <option value="update">Update existing records</option>
                                        <option value="skip">Skip duplicates</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">CSV File * <span style="font-weight:400;color:#94a3b8;">(max 10 MB)</span></label>
                                <input type="file" name="csv_file" id="impFile" accept=".csv,text/csv" class="form-control form-control-sm" required>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm" id="btnPreview" style="background:#F1F5F9;color:#334155;border:1px solid #CBD5E1;font-weight:700;border-radius:8px;padding:7px 16px;" onclick="previewCsv()">
                                    <i class="bi bi-eye me-1"></i> Preview (first 20 rows)
                                </button>
                                <button type="submit" class="btn btn-sm" id="btnImport" style="background:var(--primary-gradient);color:white;border:none;font-weight:800;border-radius:8px;padding:7px 18px;box-shadow:0 3px 10px rgba(44,62,143,0.22);">
                                    <i class="bi bi-upload me-1"></i> Import File
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Preview area -->
                    <div id="previewArea" style="display:none;margin-top:16px;">
                        <div id="previewMsg" style="font-size:0.78rem;color:#475569;margin-bottom:8px;font-weight:600;"></div>
                        <div id="previewTableWrap">
                            <table class="table table-sm" id="previewTable">
                                <thead id="previewHead"></thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div style="margin-top:14px;font-size:0.72rem;color:#64748B;font-style:italic;">
                        <i class="bi bi-shield-check me-1"></i>
                        All imports are validated server-side. Municipality names are checked against the database. Invalid rows are skipped and reported in the import summary.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // ── Sync export filter values to hidden inputs ───────────────────
    function syncExportFilters() {
        const muni = document.getElementById('expMunicipality').value;
        const year = document.getElementById('expYear').value;
        const cat  = document.getElementById('expCategory').value;

        document.getElementById('csvMuni').value  = muni;
        document.getElementById('csvYear').value  = year;
        document.getElementById('csvCat').value   = cat;
        document.getElementById('xlsxMuni').value = muni;
        document.getElementById('xlsxYear').value = year;
        document.getElementById('xlsxCat').value  = cat;
    }
    ['expMunicipality','expYear','expCategory'].forEach(id =>
        document.getElementById(id).addEventListener('change', syncExportFilters)
    );

    // ── Export loading states ────────────────────────────────────────
    document.getElementById('exportCsvForm').addEventListener('submit', function() {
        syncExportFilters();
        document.getElementById('spinCsv').style.display = 'inline-block';
        document.getElementById('icoExportCsv').style.display = 'none';
        setTimeout(() => {
            document.getElementById('spinCsv').style.display = 'none';
            document.getElementById('icoExportCsv').style.display = '';
        }, 8000);
    });
    document.getElementById('exportXlsxForm').addEventListener('submit', function() {
        syncExportFilters();
        document.getElementById('spinXlsx').style.display = 'inline-block';
        document.getElementById('icoExportXlsx').style.display = 'none';
        setTimeout(() => {
            document.getElementById('spinXlsx').style.display = 'none';
            document.getElementById('icoExportXlsx').style.display = '';
        }, 10000);
    });

    // ── Import via AJAX \u2014 shows loading then result in overlay ───────
    const TYPE_LABELS = {
        'municipality_data': 'Municipality Yearly Data',
        'barangay_data':     'Barangay Data',
        'program_data':      'Social Programs',
    };

    document.getElementById('importForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // always intercept \u2014 use AJAX instead of redirect

        const type = document.getElementById('impType').value;
        const file = document.getElementById('impFile').files[0];

        if (!type) { alert('Please select a data type.'); return; }
        if (!file) { alert('Please select a CSV file.'); return; }

        // Show loading state
        const overlay = document.getElementById('uiLoadingBackdrop');
        document.getElementById('overlayLoading').style.display = '';
        document.getElementById('overlayResult').style.display  = 'none';
        document.getElementById('overlayTitle').textContent = 'Importing ' + (TYPE_LABELS[type] || 'Data');
        document.getElementById('overlaySub').textContent   = 'Validating rows and saving to database \u2014 please wait\u2026';
        overlay.classList.add('active');
        overlay.setAttribute('aria-hidden', 'false');
        document.getElementById('btnImport').disabled = true;

        try {
            const formData = new FormData(this);
            formData.set('_token', document.querySelector('meta[name="csrf-token"]').content);

            const resp = await fetch('{{ route("superadmin.data.import") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData,
            });
            const json = await resp.json();
            showImportResult(json, TYPE_LABELS[type] || 'Data');

        } catch (err) {
            showImportResult({ success: false, message: 'Network error: ' + err.message, data: {} }, 'Import');
        }
    });

    function showImportResult(json, typeLabel) {
        const d    = json.data || {};
        const ok   = json.success && (d.failed === 0 || d.failed === undefined);
        const hasFail = (d.failed ?? 0) > 0;

        // Transition overlay to result state
        document.getElementById('overlayLoading').style.display = 'none';
        document.getElementById('overlayResult').style.display  = '';

        document.getElementById('overlayResultIcon').textContent  = ok ? '\u2705' : (hasFail ? '\u26a0\ufe0f' : '\u274c');
        document.getElementById('overlayResultTitle').textContent = ok
            ? 'Import Successful!'
            : (hasFail ? 'Import Completed with Errors' : 'Import Failed');
        document.getElementById('overlayResultTitle').style.color = ok ? '#166534' : (hasFail ? '#854D0E' : '#991B1B');

        document.getElementById('overlayResultSub').textContent = typeLabel + ' \u2014 ' + (json.message || '');

        // Stats pills
        const statsEl = document.getElementById('overlayStats');
        statsEl.innerHTML = '';
        if (json.data && json.success) {
            const pills = [
                { label: (d.imported ?? 0) + ' Imported', cls: 'green' },
                { label: (d.updated  ?? 0) + ' Updated',  cls: 'blue'  },
                { label: (d.skipped  ?? 0) + ' Skipped',  cls: 'yellow' },
                { label: (d.failed   ?? 0) + ' Failed',   cls: 'red'   },
            ];
            pills.forEach(p => {
                const span = document.createElement('span');
                span.className = 'ui-result-pill ' + p.cls;
                span.textContent = p.label;
                statsEl.appendChild(span);
            });
        }

        // Row errors
        const errEl = document.getElementById('overlayErrors');
        errEl.innerHTML = '';
        const errors = d.errors || [];
        if (errors.length > 0) {
            errors.forEach(e => {
                const li = document.createElement('li');
                li.textContent = e;
                errEl.appendChild(li);
            });
            errEl.style.display = '';
        } else {
            errEl.style.display = 'none';
        }
    }

    function hideImportOverlay() {
        const overlay = document.getElementById('uiLoadingBackdrop');
        overlay.classList.remove('active');
        overlay.setAttribute('aria-hidden', 'true');
        // Reset to loading state for next use
        document.getElementById('overlayLoading').style.display = '';
        document.getElementById('overlayResult').style.display  = 'none';
        document.getElementById('btnImport').disabled = false;
        // Close the import modal too
        const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
        if (modal) modal.hide();
    }

    // ── CSV Preview ──────────────────────────────────────────────────
    async function previewCsv() {
        const file = document.getElementById('impFile').files[0];
        const type = document.getElementById('impType').value;

        if (!file) { alert('Please select a CSV file first.'); return; }
        if (!type) { alert('Please select a data type first.'); return; }

        const btn = document.getElementById('btnPreview');
        btn.disabled = true;
        btn.innerHTML = '<span class="btn-spinner btn-spinner-dark" style="display:inline-block;margin-right:6px;"></span>Loading…';

        try {
            const formData = new FormData();
            formData.append('csv_file', file);
            formData.append('import_type', type);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            const resp = await fetch('{{ route("superadmin.data.import.preview") }}', {
                method: 'POST',
                body: formData,
            });
            const data = await resp.json();

            if (!data.success) {
                alert('Preview failed: ' + (data.message || JSON.stringify(data.errors)));
                return;
            }

            const head = document.getElementById('previewHead');
            const body = document.getElementById('previewBody');
            const msg  = document.getElementById('previewMsg');

            head.innerHTML = '<tr>' + data.headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
            body.innerHTML = data.preview.map(row =>
                '<tr>' + data.headers.map(h => `<td>${row[h] ?? ''}</td>`).join('') + '</tr>'
            ).join('');

            msg.innerHTML = `<i class="bi bi-eye me-1"></i>Showing <strong>${data.preview.length}</strong> of <strong>${data.total_rows}</strong> data rows. Review then click <em>Import File</em> to proceed.`;
            document.getElementById('previewArea').style.display = 'block';
        } catch (err) {
            alert('Preview error: ' + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-eye me-1"></i>Preview (first 20 rows)';
        }
    }

    // Reset preview when file changes
    document.getElementById('impFile').addEventListener('change', function() {
        document.getElementById('previewArea').style.display = 'none';
    });
    </script>
</body>
</html>
