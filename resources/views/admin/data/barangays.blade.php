<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Barangay Data – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F; --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC; --border-light: #E2E8F0;
        }
        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }
        body { background: var(--bg-light); display: flex; flex-direction: column; min-height: 100vh; }
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 10px; }
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
        .panel-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid var(--border-light); overflow: hidden; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 20px 28px; display: flex; align-items: center; justify-content: space-between; }
        .panel-header h5 { font-weight: 700; margin: 0; font-size: 1.05rem; }
        .panel-header p { margin: 0; opacity: .75; font-size: .82rem; }
        .count-badge { background: rgba(253,185,19,.25); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,.4); border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 700; }
        table.premium-table { width: 100%; border-collapse: collapse; }
        .premium-table thead th { background: var(--bg-light); color: var(--primary-blue); font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: 12px 20px; border-bottom: 2px solid var(--border-light); white-space: nowrap; }
        .premium-table tbody td { padding: 10px 20px; font-size: .88rem; border-bottom: 1px solid var(--border-light); vertical-align: middle; }
        .premium-table tbody tr:last-child td { border-bottom: none; }
        .premium-table tbody tr:hover td { background: var(--primary-blue-light); }
        .bgy-row.dirty td { background: #FFFBEB !important; }
        .inline-input { border: 1.5px solid var(--border-light); border-radius: 8px; padding: 6px 10px; font-size: .85rem; width: 90px; text-align: center; font-family: 'Inter', sans-serif; transition: border .2s; }
        .inline-input:focus { border-color: var(--primary-blue); outline: none; box-shadow: 0 0 0 2px rgba(44,62,143,.12); }
        .btn-save-row { background: var(--secondary-gradient); color: var(--primary-blue); border: none; border-radius: 8px; padding: 5px 14px; font-size: .79rem; font-weight: 700; cursor: pointer; transition: all .25s; }
        .btn-save-row:hover { transform: translateY(-1px); }
        .btn-update-all { background: linear-gradient(135deg, #10B981, #059669); color: white; border: none; border-radius: 10px; padding: 8px 20px; font-size: .85rem; font-weight: 700; cursor: pointer; transition: all .3s; }
        .btn-update-all:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,185,129,.35); }
        .btn-add-bgy { background: var(--secondary-gradient); color: var(--primary-blue); border: none; border-radius: 30px; padding: 9px 24px; font-weight: 800; font-size: .88rem; cursor: pointer; transition: all .3s; }
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
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
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

        {{-- FILTER --}}
        <div class="filter-card">
            <div class="d-flex align-items-end gap-3 flex-wrap justify-content-between">
                <form method="GET" action="{{ route('admin.data.barangays') }}" class="d-flex align-items-end gap-3 flex-wrap">
                    <div>
                        <label class="form-label mb-1">Year</label>
                        <select name="year" class="form-select" style="min-width:130px;">
                            <option value="">All Years</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-filter">Apply Filter</button>
                    <a href="{{ route('admin.data.barangays') }}" class="btn-clear">Clear</a>
                </form>
                <div class="d-flex gap-2">
                    <button class="btn-update-all" id="updateAllBtn" onclick="updateAll()">⬆ Update All</button>
                    <button class="btn-add-bgy" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Barangay Data</button>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="panel-card mb-4">
            <div class="panel-header">
                <div>
                    <h5>Barangay Records — {{ $municipality->name }}</h5>
                    <p>Edit values directly in the table — click Save or Update All when done.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="count-badge" id="recordCount">{{ $barangays->count() }} records</span>
                    <button class="btn-update-all" onclick="updateAll()">⬆ Update All</button>
                </div>
            </div>
            <div class="table-responsive" style="max-height:520px;overflow-y:auto;">
                <table class="premium-table" style="position:relative;">
                    <thead style="position:sticky;top:0;z-index:2;">
                        <tr>
                            <th>Year</th>
                            <th>Barangay</th>
                            <th style="text-align:center;">Total Population</th>
                            <th style="text-align:center;">PWD</th>
                            <th style="text-align:center;">AICS</th>
                            <th style="text-align:center;">Solo Parent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangays as $barangay)
                        <tr class="bgy-row" data-id="{{ $barangay->id }}">
                            <td>
                                <input type="number" class="inline-input" name="year"
                                    value="{{ $barangay->year ?? date('Y') }}" min="2000" max="{{ date('Y') + 1 }}" style="width:72px;">
                            </td>
                            <td><strong>{{ $barangay->name }}</strong></td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input" name="total_population"
                                    value="{{ $barangay->male_population }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input" name="pwd_count"
                                    value="{{ $barangay->pwd_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input" name="aics_count"
                                    value="{{ $barangay->aics_count ?? 0 }}" min="0">
                            </td>
                            <td style="text-align:center;">
                                <input type="number" class="inline-input" name="single_parent_count"
                                    value="{{ $barangay->single_parent_count ?? 0 }}" min="0">
                            </td>
                            <td>
                                <button class="btn-save-row" onclick="saveRow(this)">💾 Save</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
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

        // ── Mark row dirty on input change ──────────────────────────────────
        document.querySelectorAll('.bgy-row input').forEach(el => {
            el.addEventListener('input', () => el.closest('tr').classList.add('dirty'));
        });

        // ── Get row data ────────────────────────────────────────────────────
        function getRowData(tr) {
            return {
                id: parseInt(tr.dataset.id),
                year: parseInt(tr.querySelector('[name="year"]').value),
                total_population: parseInt(tr.querySelector('[name="total_population"]').value) || 0,
                pwd_count: parseInt(tr.querySelector('[name="pwd_count"]').value) || 0,
                aics_count: parseInt(tr.querySelector('[name="aics_count"]').value) || 0,
                single_parent_count: parseInt(tr.querySelector('[name="single_parent_count"]').value) || 0,
            };
        }

        // ── Save single row ─────────────────────────────────────────────────
        function saveRow(btn) {
            const tr = btn.closest('tr');
            const d  = getRowData(tr);
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
                    showToast('Saved!', 'success');
                } else {
                    showToast(res.message || 'Error saving row.', 'danger');
                }
            })
            .catch(() => { btn.textContent = '💾 Save'; btn.disabled = false; showToast('Network error.', 'danger'); });
        }

        // ── Update All dirty rows ───────────────────────────────────────────
        function updateAll() {
            const dirtyRows = [...document.querySelectorAll('.bgy-row.dirty')];
            if (!dirtyRows.length) { showToast('No changes to save.', 'warning'); return; }

            const rows = dirtyRows.map(getRowData);
            const btn  = document.getElementById('updateAllBtn');
            btn.disabled = true; btn.textContent = '⏳ Saving…';

            fetch(BULK_UPDATE_URL, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF},
                body: JSON.stringify({ rows })
            })
            .then(r => r.json())
            .then(d => {
                btn.disabled = false; btn.textContent = '⬆ Update All';
                if (d.success) {
                    dirtyRows.forEach(tr => tr.classList.remove('dirty'));
                    showToast(d.message, 'success');
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
    </script>
</body>
</html>