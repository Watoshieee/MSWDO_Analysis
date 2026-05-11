<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Data – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root { --bg-light:#F8FAFC; --bg-white:#FFFFFF; --bg-soft-blue:#F0F5FF; --border-light:#E2E8F0; --text-dark:#1E293B; }
        * { box-sizing:border-box; }
        body { background:var(--bg-light); font-family:'Inter',sans-serif; color:var(--text-dark); display:flex; flex-direction:column; min-height:100vh; margin:0; }
        a { text-decoration:none; }
        .navbar { background:var(--primary-gradient) !important; box-shadow:0 4px 24px rgba(44,62,143,0.18); padding:14px 0; }
        .navbar-brand { font-weight:800; font-size:1.55rem; color:white !important; display:flex; align-items:center; gap:12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color:rgba(255,255,255,0.88) !important; font-weight:600; transition:all 0.25s; border-radius:8px; padding:10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background:rgba(255,255,255,0.15); color:white !important; }
        .nav-link.active { background:var(--secondary-yellow); color:var(--primary-blue) !important; font-weight:700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.9rem; font-weight:600; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }
        .hero-banner { background:var(--primary-gradient); color:white; padding:36px 0 30px; position:relative; overflow:hidden; }
        .hero-banner::before { content:''; position:absolute; top:-60px; right:-60px; width:260px; height:260px; border-radius:50%; background:rgba(253,185,19,0.09); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:4px 16px; font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:8px; }
        .hero-banner h1 { font-size:1.75rem; font-weight:900; margin-bottom:4px; }
        .hero-divider { width:40px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:8px 0 6px; }
        .hero-banner p { opacity:0.82; font-size:0.9rem; margin:0; }
        .back-link { display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,0.75); font-size:0.82rem; font-weight:600; border:1px solid rgba(255,255,255,0.25); border-radius:20px; padding:5px 14px; transition:all 0.25s; margin-bottom:12px; }
        .back-link:hover { color:white; background:rgba(255,255,255,0.15); }
        .panel-card { background:var(--bg-white); border-radius:20px; border:1px solid var(--border-light); box-shadow:0 4px 15px rgba(0,0,0,0.04); overflow:hidden; margin-bottom:24px; }
        .panel-header { background:var(--primary-gradient); color:white; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; }
        .panel-header-title { font-size:1rem; font-weight:800; }
        .panel-header-sub { font-size:0.75rem; opacity:0.75; margin-top:2px; }
        .filter-body { padding:18px 24px; background:var(--bg-soft-blue); border-bottom:1px solid var(--border-light); }
        .f-label { font-size:0.76rem; font-weight:700; color:var(--primary-blue); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px; display:block; }
        .f-input { border:1.5px solid var(--border-light); border-radius:10px; padding:9px 13px; font-size:0.9rem; font-family:'Inter',sans-serif; transition:all 0.25s; width:100%; }
        .f-input:focus { border-color:var(--primary-blue); box-shadow:0 0 0 3px rgba(44,62,143,0.08); outline:none; }
        .btn-filter { background:var(--primary-gradient); color:white; border:none; border-radius:10px; padding:10px 22px; font-weight:700; font-size:0.88rem; cursor:pointer; transition:all 0.2s; width:100%; }
        .btn-filter:hover { box-shadow:0 6px 18px rgba(44,62,143,0.25); transform:translateY(-1px); }
        .btn-add-prog { background:var(--secondary-yellow); color:var(--primary-blue); border:none; border-radius:20px; padding:6px 20px; font-size:0.82rem; font-weight:800; cursor:pointer; transition:all 0.25s; }
        .btn-add-prog:hover { box-shadow:0 4px 12px rgba(253,185,19,0.35); transform:translateY(-1px); }
        .prog-table { width:100%; }
        .prog-table th { font-size:0.71rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; padding:12px 16px; background:var(--bg-soft-blue); border-bottom:2px solid var(--border-light); }
        .prog-table td { padding:13px 16px; font-size:0.87rem; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
        .prog-table tr:last-child td { border-bottom:none; }
        .prog-table tr:hover td { background:#FAFBFF; }
        .prog-name-pill { font-size:0.72rem; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; background:#E5EEFF; color:var(--primary-blue); border-radius:20px; padding:3px 11px; display:inline-block; }
        .btn-edit-sm { background:var(--secondary-yellow); color:var(--primary-blue); border:none; border-radius:20px; padding:4px 14px; font-size:0.76rem; font-weight:800; cursor:pointer; transition:all 0.2s; }
        .btn-edit-sm:hover { transform:translateY(-1px); box-shadow:0 3px 10px rgba(253,185,19,0.3); }
        .btn-del-sm { background:#fce8e8; color:#C41E24; border:none; border-radius:20px; padding:4px 14px; font-size:0.76rem; font-weight:800; cursor:pointer; transition:all 0.2s; margin-left:4px; }
        .btn-del-sm:hover { background:#C41E24; color:white; }
        .modal-content { border:none; border-radius:16px; overflow:hidden; }
        .modal-hdr { background:var(--primary-gradient); color:white; padding:18px 22px; }
        .btn-submit { background:var(--primary-gradient); color:white; border:none; border-radius:10px; padding:11px; font-weight:800; font-size: 0.85rem; cursor:pointer; transition:all 0.3s; width:100%; }
        .btn-submit:hover { box-shadow:0 8px 24px rgba(44,62,143,0.28); transform:translateY(-1px); }
        .btn-cncl { background:var(--bg-light); border:1.5px solid var(--border-light); color:#64748b; border-radius:10px; padding:10px; font-weight:700; font-size:0.88rem; cursor:pointer; width:100%; transition:all 0.2s; }
        .alert-s { border-radius:12px; font-size:0.88rem; padding:12px 16px; margin-bottom:16px; background:#d4edda; border-left:4px solid #28a745; color:#155724; }
        .main-content { flex:1; }
        .footer-strip { background:var(--primary-gradient); color:rgba(255,255,255,0.75); text-align:center; padding:20px 0; font-size:0.85rem; margin-top:48px; }
        .footer-strip strong { color:white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard"><img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                </ul>
                <div class="d-flex">@auth<div class="user-info"><span>{{ Auth::user()->full_name }}</span><form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button type="submit" class="logout-btn">Logout</button></form></div>@endauth</div>
            </div>
        </div>
    </nav>

    <section class="hero-banner">
        <div class="container"><div class="hero-inner">
            <a href="{{ route('admin.data.dashboard') }}#return" class="back-link">&#8592; Data Management</a>
            <div class="hero-badge">Programs</div>
            <h1>Social Program Management</h1>
            <div class="hero-divider"></div>
            <p>Manage social welfare program beneficiary counts and enrollment data for {{ $municipality->name }}.</p>
        </div></div>
    </section>

    <div class="main-content">
    <div class="container mt-4">
        @include('components.admin-notification')

        <!-- Filter + Add -->
        <div class="mb-3 text-end">
            <button class="btn-add-prog" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Program</button>
        </div>

        <!-- Programs Table -->
        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <div class="panel-header-title">Program Records — {{ $municipality->name }}</div>
                    <div class="panel-header-sub">{{ $programs->total() }} program records</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ route('admin.data.programs') }}" class="d-flex align-items-center gap-2">
                        <label class="form-label mb-0" style="color: rgba(255,255,255,.9); font-size: .82rem; font-weight: 600; white-space: nowrap;">Program Type:</label>
                        <select name="program_type" class="form-select" style="min-width:150px; font-size:.85rem;" onchange="this.form.submit()">
                            <option value="" {{ !request('program_type') ? 'selected' : '' }}>All Programs</option>
                            @foreach($programTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('program_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="form-label mb-0" style="color: rgba(255,255,255,.9); font-size: .82rem; font-weight: 600; white-space: nowrap;">Year:</label>
                        <select name="year" class="form-select" style="min-width:110px; font-size:.85rem;" onchange="this.form.submit()">
                            <option value="" {{ !request('year') ? 'selected' : '' }}>All Years</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        <label class="form-label mb-0" style="color: rgba(255,255,255,.9); font-size: .82rem; font-weight: 600; white-space: nowrap;">Month:</label>
                        <select name="month" class="form-select" style="min-width:110px; font-size:.85rem;" onchange="this.form.submit()">
                            <option value="" {{ !request('month') ? 'selected' : '' }}>All</option>
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if(request('program_type') || request('year') || request('month'))
                            <a href="{{ route('admin.data.programs') }}" class="btn-clear" style="background: rgba(255,255,255,.2); color: white; border: 1.5px solid rgba(255,255,255,.4); padding: 5px 14px; font-size: .8rem; text-decoration: none; border-radius: 8px; white-space: nowrap;">Clear</a>
                        @endif
                    </form>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="prog-table">
                    <thead><tr><th>#</th><th>Program</th><th>Beneficiaries</th><th>Period</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($programs as $i => $program)
                        <tr>
                            <td style="color:#94a3b8;font-size:0.78rem;font-weight:700;">{{ $programs->firstItem() + $i }}</td>
                            <td><span class="prog-name-pill">{{ str_replace('_', ' ', $program->program_type) }}</span></td>
                            <td><strong>{{ number_format($program->beneficiary_count) }}</strong></td>
                            <td>{{ $program->month ? $months[$program->month] . ' ' : '' }}{{ $program->year }}</td>
                            <td>
                                <button class="btn-edit-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $program->id }}">Edit</button>
                                <button class="btn-del-sm" onclick="deleteProgram({{ $program->id }})">Delete</button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $program->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content">
                                <div class="modal-hdr d-flex align-items-center justify-content-between">
                                    <span style="font-weight:800;">Edit Program - {{ str_replace('_', ' ', $program->program_type) }} ({{ $program->month ? $months[$program->month] . ' ' : '' }}{{ $program->year }})</span>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.data.programs.update', $program->id) }}" id="editForm{{ $program->id }}">
                                    @csrf
                                    <input type="hidden" name="year" value="{{ $program->year }}">
                                    <input type="hidden" name="month" value="{{ $program->month }}">
                                    <div class="modal-body p-4">
                                        <div class="mb-3" style="background:var(--primary-gradient);color:white;padding:16px;border-radius:12px;text-align:center;">
                                            <div style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;opacity:0.85;">Program</div>
                                            <div style="font-size:1.1rem;font-weight:800;margin-top:4px;">{{ str_replace('_', ' ', $program->program_type) }} &mdash; {{ $program->month ? $months[$program->month] . ' ' : '' }}{{ $program->year }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="f-label">Beneficiary Count</label>
                                            <input type="number" name="beneficiary_count"
                                                   value="{{ $program->beneficiary_count }}"
                                                   min="0" required
                                                   class="f-input"
                                                   style="font-size:1.4rem;font-weight:800;text-align:center;padding:14px;">
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                                        <button type="button" class="btn-cncl" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-submit">Save Changes</button>
                                    </div>
                                </form>
                            </div></div>
                        </div>
                        
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">No programs found. Add one above.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">{{ $programs->withQueryString()->links() }}</div>
        </div>
    </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-hdr d-flex align-items-center justify-content-between">
                <span style="font-weight:800;">Add New Program</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.data.programs.create') }}">@csrf
                <div class="modal-body p-4">
                    @if($errors->any())
                        <div style="background:#fee2e2;border-left:4px solid #C41E24;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:0.85rem;color:#7f1d1d;">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div class="mb-3"><label class="f-label">Program Type</label>
                        <select name="program_type" id="createProgramType" class="f-input" required onchange="checkAdminDuplicate()">
                            <option value="">Select Program</option>
                            @foreach($programTypes as $value => $label)<option value="{{ $value }}">{{ $label }}</option>@endforeach
                        </select>
                        <div id="adminDupWarning" style="display:none;margin-top:8px;font-size:0.8rem;color:#92400e;background:#fef3c7;border-radius:8px;padding:8px 12px;">⚠️ This program type already has a record for the selected year.</div>
                    </div>
                    <div class="mb-3"><label class="f-label">Beneficiary Count</label><input type="number" name="beneficiary_count" class="f-input" required min="0" value="{{ old('beneficiary_count') }}"></div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="f-label">Year</label>
                            <select name="year" id="createYear" class="f-input" required onchange="checkAdminDuplicate()">
                                <option value="">Select Year</option>
                                @foreach($years as $year)<option value="{{ $year }}">{{ $year }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="f-label">Month (Optional)</label>
                            <select name="month" class="f-input">
                                <option value="">Entire Year</option>
                                @foreach($months as $num => $name)<option value="{{ $num }}">{{ $name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 gap-2"><button type="button" class="btn-cncl" data-bs-dismiss="modal">Cancel</button><button type="submit" id="adminCreateSubmit" class="btn-submit">Save Program</button></div>
            </form>
        </div></div>
    </div>

    <div class="footer-strip"><strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Duplicate prevention
        const adminExistingCombos = @json($existingCombos);

        function checkAdminDuplicate() {
            const year = document.getElementById('createYear').value;
            const prog = document.getElementById('createProgramType').value;
            const warning = document.getElementById('adminDupWarning');
            const submitBtn = document.getElementById('adminCreateSubmit');
            const isDupe = year && prog && adminExistingCombos.includes(`${year}|${prog}`);
            warning.style.display = isDupe ? 'block' : 'none';
            submitBtn.disabled = isDupe;
            submitBtn.style.opacity = isDupe ? '0.5' : '1';
        }

        // Also disable already-used program types in the dropdown when year is selected
        document.getElementById('createYear').addEventListener('change', function() {
            const year = this.value;
            const select = document.getElementById('createProgramType');
            Array.from(select.options).forEach(opt => {
                if (!opt.value) return;
                const isDupe = year && adminExistingCombos.includes(`${year}|${opt.value}`);
                opt.disabled = isDupe;
                const baseLabel = opt.getAttribute('data-label') || opt.textContent.replace(' — Already Added', '');
                opt.setAttribute('data-label', baseLabel);
                opt.textContent = isDupe ? baseLabel + ' — Already Added' : baseLabel;
                if (isDupe && opt.selected) select.value = '';
            });
            checkAdminDuplicate();
        });

        // Auto-reopen modal on validation error
        @if($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('createModal')).show();
            });
        @endif

        function deleteProgram(id) {
            if (confirm('Delete this program record?')) {
                const form = document.createElement('form');
                form.method = 'POST'; form.action = '/admin/data/programs/' + id + '/delete';
                const csrf = document.createElement('input'); csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input'); method.name = '_method'; method.value = 'DELETE';
                form.appendChild(csrf); form.appendChild(method); document.body.appendChild(form); form.submit();
            }
        }
    </script>
@include('components.admin-settings-modal')
@include('components.admin-chat-modal')
</body>
</html>

