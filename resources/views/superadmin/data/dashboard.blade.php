<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management � MSWDO Super Admin</title>
    <link rel="icon" type="image/jpeg" href="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
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
            background: #e2e8f0;
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

        .section-heading {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-heading::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--border-light);
            border-radius: 2px;
        }

        .menu-card {
            background: #f8fafc;
            border-radius: 18px;
            padding: 32px 26px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
            transition: all .3s ease;
            height: 100%;
            cursor: pointer;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 32px rgba(44, 62, 143, .12);
            border-color: var(--primary-blue-soft);
        }

        .menu-card-num {
            font-size: .75rem;
            font-weight: 800;
            color: rgba(44, 62, 143, .8);
            letter-spacing: .15em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .menu-card-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 6px;
        }

        .menu-card-desc {
            font-size: .85rem;
            color: #64748b;
            line-height: 1.6;
        }

        .menu-card-arrow {
            position: absolute;
            bottom: 22px;
            right: 22px;
            font-size: 1.2rem;
            color: var(--primary-blue);
            font-weight: 900;
            transition: transform .25s ease;
        }

        .menu-card:hover .menu-card-arrow {
            transform: translateX(4px);
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <img src="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
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
            <div class="hero-badge">Super Admin</div>
            <h1>Data Management</h1>
            <div class="hero-divider"></div>
            <p>Update population, household, and social program data across all municipalities.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="section-heading" style="margin-bottom:0;">Manage Data</div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"
                        style="background:var(--primary-blue);color:white;border:none;border-radius:8px;padding:9px 20px;font-weight:700;font-size:0.85rem;box-shadow:0 3px 10px rgba(44,62,143,0.22);">
                        <i class="bi bi-upload me-1"></i> Import CSV
                    </button>
                    <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal"
                        style="background:#FFFFFF;color:var(--primary-blue);border:1.5px solid var(--primary-blue);border-radius:8px;padding:9px 20px;font-weight:700;font-size:0.85rem;">
                        <i class="bi bi-download me-1"></i> Export CSV
                    </button>
                    <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exportModal"
                        style="background:var(--secondary-yellow);color:var(--primary-blue);border:none;border-radius:8px;padding:9px 20px;font-weight:700;font-size:0.85rem;">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <a href="{{ route('superadmin.data.municipalities') }}" class="menu-card">
                        <div class="menu-card-num">01</div>
                        <div class="menu-card-title">Municipality Data</div>
                        <div class="menu-card-desc">Update population by gender, age groups, households and demographics
                            for each municipality.</div>
                        <span class="menu-card-arrow">&#8594;</span>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('superadmin.data.programs') }}" class="menu-card">
                        <div class="menu-card-num">02</div>
                        <div class="menu-card-title">Social Programs</div>
                        <div class="menu-card-desc">Track beneficiary counts per social welfare program across each
                            municipality.</div>
                        <span class="menu-card-arrow">&#8594;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- EXPORT MODAL                                                 -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient);border:none;padding:20px 24px;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--secondary-yellow);margin-bottom:2px;">DATA MANAGEMENT &bull; EXPORT CENTER</div>
                        <h5 class="modal-title" id="exportModalLabel" style="font-weight:900;font-size:1.25rem;margin:0;color:white;">Export Data</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <!-- Filters -->
                    <div style="background:#F8FAFF;border:1px solid #E2E8F0;border-radius:12px;padding:16px 18px;margin-bottom:16px;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;color:#64748B;letter-spacing:0.06em;margin-bottom:10px;">Filter Options</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Municipality</label>
                                <select id="expMuni" class="form-select form-select-sm" onchange="syncExportFilters()">
                                    <option value="">All Municipalities</option>
                                    @foreach($municipalities ?? [] as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Year</label>
                                <select id="expYear" class="form-select form-select-sm" onchange="syncExportFilters()">
                                    <option value="">All Years</option>
                                    @foreach(range(date('Y'), 2015) as $yr)
                                        <option value="{{ $yr }}">{{ $yr }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Export Option Cards -->
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
                                    <p style="font-size:0.78rem;color:#64748B;margin-bottom:12px;line-height:1.5;">Downloads a UTF-8 CSV file with labeled sections for all categories. Compatible with Excel, R, Python, and SPSS.</p>
                                    <ul style="font-size:0.73rem;color:#475569;padding-left:18px;margin-bottom:16px;">
                                        <li>Section 1: Municipality Yearly Data</li>
                                        <li>Section 2: Barangay Data</li>
                                        <li>Section 3: Social Programs</li>
                                    </ul>
                                </div>
                                <form id="exportCsvForm" method="GET" action="{{ route('superadmin.data.export.csv') }}">
                                    <input type="hidden" name="municipality" id="csvMuni">
                                    <input type="hidden" name="year" id="csvYear">
                                    <button type="submit" id="btnExportCsv" class="btn w-100" style="background:#FFFFFF;color:var(--primary-blue);border:2px solid var(--primary-blue);font-weight:800;font-size:0.85rem;border-radius:10px;padding:9px 16px;">
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
                                    <p style="font-size:0.78rem;color:#64748B;margin-bottom:12px;line-height:1.5;">Generates a professional Excel workbook with 4 structured worksheets, MSWDO branding, auto-filters, and print-ready A4 layout.</p>
                                    <ul style="font-size:0.73rem;color:#475569;padding-left:18px;margin-bottom:16px;">
                                        <li>Sheet 1: Municipality Yearly Data</li>
                                        <li>Sheet 2: Barangay Data</li>
                                        <li>Sheet 3: Social Programs</li>
                                        <li>Sheet 4: Export Summary</li>
                                    </ul>
                                </div>
                                <form id="exportXlsxForm" method="GET" action="{{ route('superadmin.data.export.excel') }}">
                                    <input type="hidden" name="municipality" id="xlsxMuni">
                                    <input type="hidden" name="year" id="xlsxYear">
                                    <button type="submit" id="btnExportXlsx" class="btn w-100" style="background:var(--primary-gradient);color:white;border:none;font-weight:800;font-size:0.85rem;border-radius:10px;padding:10px 16px;box-shadow:0 4px 12px rgba(44,62,143,0.25);">
                                        <i class="bi bi-file-earmark-excel me-1" id="icoExportXlsx"></i> Download Excel (.xlsx)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top:14px;font-size:0.72rem;color:#64748B;font-style:italic;">
                        <i class="bi bi-info-circle me-1"></i>
                        Exports always reflect the current live database. Results depend on the applied municipality and year filters.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for syncing export filters -->
    <script>
    function syncExportFilters() {
        const muni = document.getElementById('expMuni').value;
        const year = document.getElementById('expYear').value;
        document.getElementById('csvMuni').value = muni;
        document.getElementById('csvYear').value = year;
        document.getElementById('xlsxMuni').value = muni;
        document.getElementById('xlsxYear').value = year;
    }
    </script>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- IMPORT MODAL                                                 -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient);border:none;padding:20px 24px;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--secondary-yellow);margin-bottom:2px;">
                            DATA MANAGEMENT &bull; IMPORT CENTER
                        </div>
                        <h5 class="modal-title" id="importModalLabel" style="font-weight:900;font-size:1.25rem;margin:0;color:white;">
                            Import CSV Data
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <!-- Step 1: Download template -->
                    <div style="background:#F8FAFF;border:1px solid #E2E8F0;border-radius:12px;padding:16px 18px;margin-bottom:16px;">
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

                    <!-- Step 2: Upload CSV -->
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
                                        <option value="program_data">Social Programs Data</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Duplicates</label>
                                    <select name="import_mode" class="form-select form-select-sm">
                                        <option value="update">Update existing records</option>
                                        <option value="skip">Skip duplicates</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">CSV File *</label>
                                    <input type="file" name="csv_file" id="csvFileInput" class="form-control form-control-sm" accept=".csv" required>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm" id="btnPreview" style="background:#E2E8F0;color:#475569;border:none;font-weight:700;border-radius:8px;padding:7px 18px;">
                                    <i class="bi bi-eye me-1"></i> Preview (first 20 rows)
                                </button>
                                <button type="submit" class="btn btn-sm" id="btnImport" style="background:var(--primary-gradient);color:white;border:none;font-weight:800;border-radius:8px;padding:7px 18px;box-shadow:0 3px 10px rgba(44,62,143,0.22);">
                                    <i class="bi bi-upload me-1"></i> Import File
                                </button>
                            </div>
                        </form>
                    </div>

                    <div style="margin-top:14px;font-size:0.72rem;color:#64748B;font-style:italic;">
                        <i class="bi bi-info-circle me-1"></i>
                        All imports are validated server-side. Municipality names are checked against the database. Invalid rows are skipped and reported in the import summary.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-strip"></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
