<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Data – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue-light: #E5EEFF;
            --bg-light: #F8FAFC; --border-light: #E2E8F0;
        }
        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }
        body { background: var(--bg-light); display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,.88) !important; font-weight: 600; transition: all .25s; border-radius: 8px; padding: 10px 18px !important; font-size: .95rem; }
        .nav-link:hover { background: rgba(255,255,255,.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,.1); padding: 9px 22px; border-radius: 40px; font-size: .92rem; font-weight: 500; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all .3s; font-size: .88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 36px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -70px; right: -70px; width: 300px; height: 300px; border-radius: 50%; background: rgba(253,185,19,.10); }
        .hero-badge { display: inline-block; background: rgba(253,185,19,.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,.35); border-radius: 30px; padding: 5px 18px; font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 14px; }
        .hero-banner h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 8px; }
        .hero-divider { width: 55px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0; }
        .hero-banner p { font-size: .98rem; opacity: .85; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,.75); font-size: .85rem; font-weight: 600; text-decoration: none; margin-bottom: 14px; transition: color .2s; }
        .back-link:hover { color: var(--secondary-yellow); }
        .main-content { flex: 1; }
        .filter-card { background: white; border-radius: 16px; padding: 22px 28px; margin-bottom: 28px; box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid var(--border-light); }
        .form-label { font-weight: 600; color: var(--primary-blue); font-size: .88rem; }
        .form-control, .form-select { border: 1.5px solid var(--border-light); border-radius: 10px; padding: 10px 14px; font-size: .9rem; transition: border .2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,.1); outline: none; }
        .btn-filter { background: var(--primary-gradient); color: white; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 700; cursor: pointer; transition: all .3s; }
        .btn-filter:hover { opacity: .9; }
        .btn-clear { background: white; color: #64748b; border: 1.5px solid var(--border-light); border-radius: 10px; padding: 10px 24px; font-weight: 600; cursor: pointer; transition: all .2s; }
        .btn-clear:hover { border-color: #94a3b8; }
        .btn-clear-data { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 6px 14px; border-radius: 8px; font-size: .85rem; font-weight: 700; transition: all .2s; cursor: pointer; }
        .btn-clear-data:hover { background: #fecaca; color: #b91c1c; border-color: #f87171; box-shadow: 0 4px 12px rgba(220,38,38,.15); transform: translateY(-1px); }
        .panel-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid var(--border-light); overflow: hidden; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 20px 28px; display: flex; align-items: center; justify-content: space-between; }
        .panel-header h5 { font-weight: 700; margin: 0; font-size: 1.05rem; }
        .panel-header p { margin: 0; opacity: .75; font-size: .82rem; }
        .count-badge { background: rgba(253,185,19,.25); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,.4); border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 700; }
        table.premium-table { width: 100%; border-collapse: collapse; }
        .premium-table thead th { background: var(--bg-light); color: var(--primary-blue); font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: 12px 16px; border-bottom: 2px solid var(--border-light); white-space: nowrap; }
        .premium-table tbody td { padding: 10px 16px; font-size: .88rem; border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        .premium-table tbody tr:last-child td { border-bottom: none; }
        .premium-table tbody tr:hover td { background: var(--primary-blue-light); }
        .bgy-row.dirty td { background: #FFFBEB !important; }
        .inline-input { border: 1.5px solid var(--border-light); border-radius: 8px; padding: 6px 10px; font-size: .85rem; width: 90px; text-align: center; font-family: 'Inter', sans-serif; transition: border .2s; }
        .inline-input:focus { border-color: var(--primary-blue); outline: none; box-shadow: 0 0 0 2px rgba(44,62,143,.12); }
        .btn-save-row { background: var(--secondary-gradient); color: var(--primary-blue); border: none; border-radius: 8px; padding: 5px 14px; font-size: .79rem; font-weight: 700; cursor: pointer; transition: all .25s; }
        .btn-save-row:hover { transform: translateY(-1px); }
        .btn-update-all { background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 10px; padding: 8px 20px; font-size: .85rem; font-weight: 700; cursor: pointer; transition: all .3s; }
        .btn-update-all:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,185,129,.35); }
        .btn-add-bgy { background: var(--secondary-gradient); color: var(--primary-blue); border: none; border-radius: 30px; padding: 7px 20px; font-weight: 700; font-size: .82rem; cursor: pointer; transition: all .3s; }
        .btn-add-bgy:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(253,185,19,.4); }
        .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(44,62,143,.2); }
        .modal-header { background: var(--primary-gradient); color: white; border-radius: 16px 16px 0 0; }
        .modal-title { font-weight: 800; }
        .btn-close { filter: invert(1); }
        .btn-modal-submit { background: var(--primary-gradient); color: white; border: none; border-radius: 10px; padding: 10px 28px; font-weight: 700; cursor: pointer; transition: all .3s; }
        .btn-modal-submit:hover { opacity: .9; }
        .btn-modal-cancel { background: white; border: 1.5px solid var(--border-light); color: #64748b; border-radius: 10px; padding: 10px 28px; font-weight: 600; cursor: pointer; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,.75); text-align: center; padding: 20px 0; font-size: .85rem; margin-top: 48px; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>
    {{-- NAV --}}
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero-banner">
        <div class="container">
            <a href="{{ route('admin.data.dashboard') }}" class="back-link">&#8592; Data Management</a>
            <div class="hero-badge">{{ $municipality->name }}</div>
            <h1>Barangay Data Management</h1>
            <div class="hero-divider"></div>
            <p>Manage and update demographic records for all barangays in {{ $municipality->name }}.</p>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        {{-- TOP ACTION BAR --}}
        <div class="mb-3 d-flex justify-content-end align-items-center gap-3">
            <div class="d-inline-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm" style="border: 1px solid rgba(44,62,143,0.15);">
                <div style="width: 8px; height: 8px; background-color: #fbbf24; border-radius: 50%; margin-right: 10px;"></div>
                <span class="fw-bold text-muted me-2" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">Total Beneficiaries:</span>
                <span class="fs-5 fw-bolder" style="color: var(--primary-blue);" id="grand-total-beneficiaries">0</span>
            </div>
            <button class="btn-add-bgy" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Barangay Data</button>
        </div>

        {{-- TABLE --}}
        <div class="panel-card mb-4">
            <div class="panel-header">
                <div>
                    <h5>Barangay Records — {{ $municipality->name }}</h5>
                    <p>{{ $uniqueBarangayCount }} barangays · {{ $barangays->count() }} records
                        @if($selectedYear)
                            in {{ $selectedYear }}
                        @else
                            (across all years)
                        @endif
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Search Feature -->
                    <div class="position-relative">
                        <input type="text" id="brgySearchInput" list="brgyDatalist" class="form-control fw-bold" style="min-width:180px; font-size:.85rem; border-radius:8px; padding-left:32px; border:none; box-shadow:0 2px 5px rgba(0,0,0,0.1);" autocomplete="off" placeholder="Quick Search Brgy..." onchange="openBrgyModal(this.value)">
                        <span style="position:absolute; left:10px; top:6px; font-size:.9rem; opacity:0.6;">🔍</span>
                    </div>
                    <datalist id="brgyDatalist">
                        @foreach($barangays as $b)
                            <option value="{{ $b->name }}">
                        @endforeach
                    </datalist>

                    <button class="btn-clear-data" onclick="zeroOutInputs()">⊘ Clear data</button>
                    <button class="btn-update-all" id="updateAllBtn" onclick="updateAll()">⬆ Update All</button>
                    <form method="GET" action="{{ route('admin.data.barangays') }}" class="d-flex align-items-center gap-2">
                        <label for="yearFilter" class="form-label mb-0" style="color: rgba(255,255,255,.9); font-size: .82rem; font-weight: 600; white-space: nowrap;">Filter by Year:</label>
                        <select name="year" id="yearFilter" class="form-select" style="min-width:110px; font-size:.85rem;" onchange="this.form.submit()">
                            <option value="" {{ !$selectedYear ? 'selected' : '' }}>All Years</option>
                            @php
                                $dropdownYears = array_unique(array_merge(
                                    $years,
                                    [2015, 2020, 2024],
                                    range(date('Y') - 1, date('Y') + 1)
                                ));
                                rsort($dropdownYears);
                            @endphp
                            @foreach($dropdownYears as $yr)
                                <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        @if($selectedYear)
                            <a href="{{ route('admin.data.barangays') }}" class="btn-clear" style="background: rgba(255,255,255,.2); color: white; border: 1.5px solid rgba(255,255,255,.4); padding: 5px 14px; font-size: .8rem; text-decoration: none; border-radius: 8px; white-space: nowrap;">Clear Filter</a>
                        @endif
                    </form>
                </div>
            </div>
            <div class="table-responsive" style="max-height:520px;overflow-y:auto;">
                <table class="premium-table" style="position:relative;">
                    <thead style="position:sticky;top:0;z-index:3;">
                        <tr>
                            <th style="min-width:160px;">Barangay</th>
                            <th style="text-align:center;">Total Population</th>
                            <th style="text-align:center;">PWD</th>
                            <th style="text-align:center;">AICS</th>
                            <th style="text-align:center;">Solo Parent</th>
                            <th style="text-align:center;">Households</th>
                            <th style="text-align:center;">4PS</th>
                            <th style="text-align:center;">Senior</th>
                            <th style="min-width:140px; text-align:center; color:var(--primary-blue); font-weight:800;">Total Beneficiaries</th>
                            <th style="width:100px; position:sticky; right:0; background:var(--bg-light); z-index:4; border-left:1px solid var(--border-light);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangays as $barangay)
                        <tr class="bgy-row" data-id="{{ $barangay->id }}" data-year="{{ $barangay->year ?? date('Y') }}">
                            <td>
                                <strong>{{ $barangay->name }}</strong>
                                <div style="font-size:.7rem;color:#64748b;margin-top:2px;">Year: {{ $barangay->year ?? date('Y') }}</div>
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input" name="total_population"
                                    value="{{ $barangay->total_population ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input program-input" name="pwd_count"
                                    value="{{ $barangay->pwd_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input program-input" name="aics_count"
                                    value="{{ $barangay->aics_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input program-input" name="single_parent_count"
                                    value="{{ $barangay->single_parent_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input" name="total_households"
                                    value="{{ $barangay->total_households ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input program-input" name="four_ps_count"
                                    value="{{ $barangay->four_ps_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input program-input" name="senior_count"
                                    value="{{ $barangay->senior_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center; font-weight:800; color:var(--primary-blue); font-size:.95rem; background:rgba(44,62,143,0.05);" class="row-total-beneficiaries">
                                {{ ($barangay->pwd_count ?? 0) + ($barangay->aics_count ?? 0) + ($barangay->single_parent_count ?? 0) + ($barangay->four_ps_count ?? 0) + ($barangay->senior_count ?? 0) }}
                            </td>
                            <td style="text-align:center; position:sticky; right:0; background:white; border-left:1px solid var(--border-light); z-index:2; box-shadow:-4px 0 10px rgba(0,0,0,0.02);">
                                <button class="btn-save-row" onclick="saveRow(this)">💾 Save</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div style="font-size:2.8rem;opacity:.25;">📭</div>
                                @if(request('year'))
                                    <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No records for {{ $municipality->name }} in {{ request('year') }}</p>
                                    <p class="text-muted" style="font-size:.85rem;">Data for this year has not been added yet.</p>
                                    @if(!empty($availableYears))
                                        <p class="text-muted" style="font-size:.82rem;">Available years: {{ implode(', ', $availableYears) }}</p>
                                    @endif
                                @else
                                    <p class="mt-2 mb-1 fw-bold" style="color:#334155;">No barangay records yet</p>
                                    <p class="text-muted" style="font-size:.85rem;">Click <strong>+ Add Barangay Data</strong> to get started.</p>
                                @endif
                                <button class="btn-add-bgy mt-2" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Barangay Data</button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">+ Add Barangay Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Year</label>
                        <select class="form-select" id="addYear">
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ $yr == date('Y') ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barangay Name</label>
                        <select class="form-select" id="addBarangay">
                            <option value="">— Select —</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-modal-submit" id="addBtnSubmit" onclick="submitAdd()">Add Barangay Data</button>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK EDIT MODAL --}}
    <div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:14px; overflow:hidden;">
                <div class="modal-header bg-primary text-white p-4">
                    <h5 class="modal-title fw-bolder">Update: <span id="editMdl-name" class="text-warning"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3 bg-white p-3 rounded shadow-sm border mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem; letter-spacing:0.5px;">TOTAL POPULATION</label>
                            <input type="number" id="editMdl-pop" class="form-control text-center fw-bolder" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem; letter-spacing:0.5px;">HOUSEHOLDS</label>
                            <input type="number" id="editMdl-house" class="form-control text-center fw-bolder" min="0">
                        </div>
                    </div>
                    <h6 class="fw-bolder mb-3 text-secondary text-uppercase" style="font-size:0.8rem; letter-spacing:1px;">Program Beneficiaries</h6>
                    <div class="row g-3 bg-white p-3 rounded shadow-sm border">
                        <div class="col-6 col-md-4">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem;">PWD</label>
                            <input type="number" id="editMdl-pwd" class="form-control text-center q-prog" min="0" oninput="recalcEditModal()">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem;">AICS</label>
                            <input type="number" id="editMdl-aics" class="form-control text-center q-prog" min="0" oninput="recalcEditModal()">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem;">SOLO PARENT</label>
                            <input type="number" id="editMdl-solo" class="form-control text-center q-prog" min="0" oninput="recalcEditModal()">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem;">4PS</label>
                            <input type="number" id="editMdl-4ps" class="form-control text-center q-prog" min="0" oninput="recalcEditModal()">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label text-muted fw-bold" style="font-size:0.75rem;">SENIOR</label>
                            <input type="number" id="editMdl-senior" class="form-control text-center q-prog" min="0" oninput="recalcEditModal()">
                        </div>
                        <div class="col-12 col-md-4 d-flex">
                            <div class="w-100 p-2 rounded text-center d-flex flex-column justify-content-center align-items-center" style="background: rgba(44,62,143,0.08); border:1px solid rgba(44,62,143,0.15);">
                                <span class="d-block text-muted" style="font-size:0.6rem; font-weight:800; letter-spacing:1px;">TOTAL HERE</span>
                                <span id="editMdl-total" style="font-size:1.5rem; font-weight:900; color:var(--primary-blue); line-height:1;">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-white d-flex justify-content-between">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning rounded-pill px-4 fw-bolder shadow-sm" onclick="saveQuickEdit()">💾 Confirm & Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-strip"><strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF   = document.querySelector('meta[name="csrf-token"]').content;
        const MUN    = "{{ $municipality->name }}";
        const BULK_UPDATE_URL = '{{ route("admin.data.barangays.bulk-update") }}';
        const BULK_STORE_URL  = '{{ route("admin.data.barangays.bulk-store") }}';

        // Barangay master lists per municipality
        const barangayLists = {
            'Magdalena': [
                'Alipit','Balayhangin','Bañadero','Botocan','Cagsiay','Coralao','Ibabang Atingay',
                'Ibabang Butnong','Ilayang Atingay','Ilayang Butnong','Ilog','Kanlurang Talaonin',
                'Liyang','Maravilla','Pansol','Patimbao','Poblacion','Silangan Talaonin','Sildora'
            ],
            'Liliw': [
                'Bagong Anyo','Barangay I','Barangay II','Barangay III','Barangay IV','Barangay V',
                'Barangay VI','Barangay VII','Barangay VIII','Barangay IX','Barangay X',
                'Bukal','Bungkol','Buo','Burias','Caballero','Cambuja','Kangluangan',
                'Labayo','Laguio','Liyang','Malinao','Manaluco','Munting Ilog','Novaliches',
                'Oliva','Oobi','Operating','Palanca','Pook','Rizal','San Pedro'
            ],
            'Majayjay': [
                'Aldavoc','Alumbrado','Ambit','Angustia','Anos','Aso','Bakia','Balayong','Balian',
                'Baong','Batang','Bukal','Bunga','Buñga','Burgos','Halayhayin','Ibabang Kinalaglagan',
                'Ibabang Lalo','Ibabang Palina','Ilayang Kinalaglagan','Ilayang Lalo','Ilayang Palina',
                'Isabang','Malinao','Mataas Na Lupa','Munting Kawayan','Olla','Paciano Rizal',
                'Panalaban','Pangil','Panglan','Parang','Pook','Rizal','Saimba','San Diego',
                'San Francisco','San Miguel','San Pelayo','Santa Catalina','Santa Cruz','Santa Maria',
                'Santo Tomas','Silangan','Sumapa','Talao Talao','Talisay','Tanawan','Tipunan'
            ]
        };

        // ── Populate the Barangay dropdown when Add modal is shown ──────────
        function loadBarangayOptions() {
            const sel  = document.getElementById('addBarangay');
            const list = barangayLists[MUN] || [];
            sel.innerHTML = '<option value="">— Select —</option>';
            if (list.length) {
                const allOpt = document.createElement('option');
                allOpt.value = '__all__';
                allOpt.textContent = `✅ All Barangays (${list.length})`;
                sel.appendChild(allOpt);
                list.forEach(b => {
                    const o = document.createElement('option');
                    o.value = b; o.textContent = b;
                    sel.appendChild(o);
                });
            }
        }

        document.getElementById('addModal').addEventListener('show.bs.modal', loadBarangayOptions);

        // ── Submit Add (single or all) ──────────────────────────────────────
        function submitAdd() {
            const year = document.getElementById('addYear').value;
            const sel  = document.getElementById('addBarangay');
            const val  = sel.value;
            if (!val) { alert('Please select a barangay.'); return; }

            if (val === '__all__') {
                const btn = document.getElementById('addBtnSubmit');
                btn.disabled = true;
                btn.textContent = 'Adding…';
                const barangays = barangayLists[MUN] || [];

                fetch(BULK_STORE_URL, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                    body: JSON.stringify({ municipality: MUN, year: parseInt(year), barangays })
                })
                .then(r => r.json())
                .then(d => {
                    btn.disabled = false;
                    btn.textContent = 'Add Barangay Data';
                    if (d.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        showToast(d.message, 'success');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast(d.message || 'Error adding barangays.', 'danger');
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.textContent = 'Add Barangay Data';
                    showToast('Network error. Please try again.', 'danger');
                });

            } else {
                // Single barangay via bulk-store with 1 item
                fetch(BULK_STORE_URL, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                    body: JSON.stringify({ municipality: MUN, year: parseInt(year), barangays: [val] })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        bootstrap.Modal.getInstance(document.getElementById('addModal')).hide();
                        showToast(d.message, 'success');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        showToast(d.message || 'Error.', 'danger');
                    }
                })
                .catch(() => showToast('Network error.', 'danger'));
            }
        }

        // ── Mark row dirty on input change and recalculate total ────────────
        function calculateGrandTotalBeneficiaries() {
            let grandTotal = 0;
            document.querySelectorAll('.row-total-beneficiaries').forEach(el => {
                grandTotal += parseInt(el.textContent.trim()) || 0;
            });
            const grandTotalEl = document.getElementById('grand-total-beneficiaries');
            if (grandTotalEl) grandTotalEl.textContent = grandTotal.toLocaleString();
        }

        document.querySelectorAll('.bgy-row input').forEach(el => {
            el.addEventListener('input', () => {
                const tr = el.closest('tr');
                tr.classList.add('dirty');
                if (el.classList.contains('program-input')) {
                    let sum = 0;
                    tr.querySelectorAll('.program-input').forEach(inp => sum += parseInt(inp.value) || 0);
                    tr.querySelector('.row-total-beneficiaries').textContent = sum;
                    calculateGrandTotalBeneficiaries();
                }
            });
        });
        
        // Calculate initially
        document.addEventListener('DOMContentLoaded', calculateGrandTotalBeneficiaries);

        // ── Get row data ────────────────────────────────────────────────────
        function getRowData(tr) {
            return {
                id: parseInt(tr.dataset.id),
                year: parseInt(tr.dataset.year),
                total_population: parseInt(tr.querySelector('[name="total_population"]').value) || 0,
                pwd_count: parseInt(tr.querySelector('[name="pwd_count"]').value) || 0,
                aics_count: parseInt(tr.querySelector('[name="aics_count"]').value) || 0,
                single_parent_count: parseInt(tr.querySelector('[name="single_parent_count"]').value) || 0,
                total_households: parseInt(tr.querySelector('[name="total_households"]').value) || 0,
                four_ps_count: parseInt(tr.querySelector('[name="four_ps_count"]').value) || 0,
                senior_count: parseInt(tr.querySelector('[name="senior_count"]').value) || 0,
            };
        }

        // ── Save single row ─────────────────────────────────────────────────
        function saveRow(btn) {
            const tr = btn.closest('tr');
            const d  = getRowData(tr);
            
            if (d.total_households > d.total_population) {
                return showToast("Validation Error: Households cannot exceed Total Population.", "danger");
            }
            if (d.pwd_count > d.total_population || d.aics_count > d.total_population || d.single_parent_count > d.total_population || d.four_ps_count > d.total_population || d.senior_count > d.total_population) {
                return showToast("Validation Error: Program count cannot exceed Total Population.", "danger");
            }

            btn.textContent = '⏳';
            btn.disabled = true;

            fetch(`/admin/data/barangays/${d.id}/update`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                body: JSON.stringify(d)
            })
            .then(r => r.json())
            .then(res => {
                btn.textContent = '💾 Save';
                btn.disabled = false;
                if (res.success) {
                    tr.classList.remove('dirty');
                    showToast('Saved! Year: ' + d.year, 'success');
                    // Keep the current year filter when reloading
                    const currentYear = new URLSearchParams(window.location.search).get('year') || d.year;
                    setTimeout(() => window.location.href = window.location.pathname + '?year=' + currentYear, 800);
                } else {
                    showToast(res.message || 'Error saving row.', 'danger');
                }
            })
            .catch(() => { btn.textContent = '💾 Save'; btn.disabled = false; showToast('Network error.', 'danger'); });
        }

        // ── Zero Out Data ───────────────────────────────────────────────────
        function zeroOutInputs() {
            if(!confirm("Are you sure you want to zero out ALL inputs shown on this page? You must click Update All to save these changes.")) return;
            document.querySelectorAll('.bgy-row').forEach(tr => {
                tr.querySelectorAll('.inline-input').forEach(input => {
                    if (parseInt(input.value) !== 0) {
                        input.value = 0;
                        tr.classList.add('dirty');
                    }
                });
                const totalCell = tr.querySelector('.row-total-beneficiaries');
                if (totalCell) totalCell.textContent = "0";
            });
            calculateGrandTotalBeneficiaries();
            showToast('All inputs temporarily set to 0. Click Update All to save.', 'warning');
        }

        // ── Update All dirty rows ───────────────────────────────────────────
        function updateAll() {
            const dirtyRows = [...document.querySelectorAll('.bgy-row.dirty')];
            if (!dirtyRows.length) { showToast('No changes to save.', 'warning'); return; }

            const rows = dirtyRows.map(getRowData);
            
            // Client side validation
            for (let r of rows) {
                if (r.total_households > r.total_population) {
                    return showToast(`Validation Error: Households exceed population. Save aborted.`, "danger");
                }
                if (r.pwd_count > r.total_population || r.aics_count > r.total_population || r.single_parent_count > r.total_population || r.four_ps_count > r.total_population || r.senior_count > r.total_population) {
                    return showToast(`Validation Error: Programs exceed population. Save aborted.`, "danger");
                }
            }

            console.log('Updating rows:', rows);
            
            const btn  = document.getElementById('updateAllBtn');
            btn.disabled = true; btn.textContent = '⏳ Saving…';

            fetch(BULK_UPDATE_URL, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                body: JSON.stringify({ rows })
            })
            .then(r => r.json())
            .then(d => {
                console.log('Update response:', d);
                btn.disabled = false; btn.textContent = '⬆ Update All';
                if (d.success) {
                    dirtyRows.forEach(tr => tr.classList.remove('dirty'));
                    showToast(d.message, 'success');
                    // Keep the current year filter when reloading
                    const currentYear = new URLSearchParams(window.location.search).get('year');
                    if (currentYear) {
                        setTimeout(() => window.location.href = window.location.pathname + '?year=' + currentYear, 800);
                    } else {
                        setTimeout(() => location.reload(), 800);
                    }
                } else {
                    showToast(d.message || 'Error updating records.', 'danger');
                }
            })
            .catch(() => { btn.disabled = false; btn.textContent = '⬆ Update All'; showToast('Network error.', 'danger'); });
        }

        // ── Toast helper ────────────────────────────────────────────────────
        function showToast(message, type = 'success') {
            const colors = { success: '#2C3E8F', danger: '#C41E24', warning: '#E5A500' };
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:28px;right:28px;z-index:9999;background:${colors[type]||colors.success};color:white;padding:14px 22px;border-radius:12px;font-weight:600;font-size:.9rem;box-shadow:0 8px 24px rgba(0,0,0,.18);max-width:380px;`;
            t.textContent = message;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3500);
        }

        // ── Quick Edit Modal Logic ─────────────────────────────────────────
        let currentEditRow = null;

        function openBrgyModal(name) {
            if(!name) return;
            const rows = Array.from(document.querySelectorAll('.bgy-row'));
            currentEditRow = rows.find(r => r.querySelector('strong').textContent.trim() === name);
            if(!currentEditRow) {
                showToast("Barangay not found on current page.", "warning");
                return;
            }
            
            document.getElementById('editMdl-name').textContent = name;
            document.getElementById('editMdl-pop').value = currentEditRow.querySelector('input[name="total_population"]').value;
            document.getElementById('editMdl-pwd').value = currentEditRow.querySelector('input[name="pwd_count"]').value;
            document.getElementById('editMdl-aics').value = currentEditRow.querySelector('input[name="aics_count"]').value;
            document.getElementById('editMdl-solo').value = currentEditRow.querySelector('input[name="single_parent_count"]').value;
            document.getElementById('editMdl-house').value = currentEditRow.querySelector('input[name="total_households"]').value;
            document.getElementById('editMdl-4ps').value = currentEditRow.querySelector('input[name="four_ps_count"]').value;
            document.getElementById('editMdl-senior').value = currentEditRow.querySelector('input[name="senior_count"]').value;
            
            recalcEditModal();
            new bootstrap.Modal(document.getElementById('quickEditModal')).show();
            document.getElementById('brgySearchInput').value = ''; // clear search field
        }

        function recalcEditModal() {
            let total = 0;
            document.querySelectorAll('.q-prog').forEach(el => total += parseInt(el.value || 0));
            document.getElementById('editMdl-total').textContent = total.toLocaleString();
        }

        function saveQuickEdit() {
            if(!currentEditRow) return;
            
            // Validation: Programs cannot exceed total population
            let pop = parseInt(document.getElementById('editMdl-pop').value) || 0;
            let house = parseInt(document.getElementById('editMdl-house').value) || 0;
            let pwd = parseInt(document.getElementById('editMdl-pwd').value) || 0;
            let aics = parseInt(document.getElementById('editMdl-aics').value) || 0;
            let solo = parseInt(document.getElementById('editMdl-solo').value) || 0;
            let fps = parseInt(document.getElementById('editMdl-4ps').value) || 0;
            let senior = parseInt(document.getElementById('editMdl-senior').value) || 0;

            if (house > pop) return showToast("Households cannot exceed total population.", "danger");
            if (pwd > pop) return showToast("PWD count cannot exceed total population.", "danger");
            if (aics > pop) return showToast("AICS count cannot exceed total population.", "danger");
            if (solo > pop) return showToast("Solo Parent count cannot exceed total population.", "danger");
            if (fps > pop) return showToast("4Ps count cannot exceed total population.", "danger");
            if (senior > pop) return showToast("Senior count cannot exceed total population.", "danger");
            
            currentEditRow.querySelector('input[name="total_population"]').value = document.getElementById('editMdl-pop').value;
            currentEditRow.querySelector('input[name="pwd_count"]').value = document.getElementById('editMdl-pwd').value;
            currentEditRow.querySelector('input[name="aics_count"]').value = document.getElementById('editMdl-aics').value;
            currentEditRow.querySelector('input[name="single_parent_count"]').value = document.getElementById('editMdl-solo').value;
            currentEditRow.querySelector('input[name="total_households"]').value = document.getElementById('editMdl-house').value;
            currentEditRow.querySelector('input[name="four_ps_count"]').value = document.getElementById('editMdl-4ps').value;
            currentEditRow.querySelector('input[name="senior_count"]').value = document.getElementById('editMdl-senior').value;

            // Trigger the internal table calculation scripts mechanically
            currentEditRow.querySelector('input[name="pwd_count"]').dispatchEvent(new Event('input'));
            
            // Disparch save action mechanically
            const btn = currentEditRow.querySelector('.btn-save-row');
            saveRow(btn);
            
            bootstrap.Modal.getInstance(document.getElementById('quickEditModal')).hide();
        }
    </script>
@include('components.admin-settings-modal')
@include('components.admin-chat-modal')
</body>
</html>
