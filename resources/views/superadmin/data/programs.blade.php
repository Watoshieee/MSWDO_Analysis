<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Data – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--primary-blue:#2C3E8F;--primary-blue-light:#E5EEFF;--primary-blue-soft:#5D7BB9;--secondary-yellow:#FDB913;--secondary-yellow-light:#FFF3D6;--accent-red:#C41E24;--primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);--secondary-gradient:linear-gradient(135deg,#FDB913 0%,#E5A500 100%);--bg-light:#F8FAFC;--border-light:#E2E8F0;}
        *,body{font-family:'Inter','Segoe UI',sans-serif;}
        body{background:var(--bg-light);display:flex;flex-direction:column;min-height:100vh;}
        .navbar{background:var(--primary-gradient)!important;box-shadow:0 4px 24px rgba(44,62,143,.18);padding:14px 0;}
        .navbar-brand{font-weight:800;font-size:1.55rem;color:white!important;display:flex;align-items:center;gap:10px;}
        .nav-link{color:rgba(255,255,255,.88)!important;font-weight:600;transition:all .25s;border-radius:8px;padding:10px 18px!important;font-size:.95rem;}
        .nav-link:hover{background:rgba(255,255,255,.15);color:white!important;}
        .nav-link.active{background:var(--secondary-yellow);color:var(--primary-blue)!important;font-weight:700;}
        .user-info{color:white;display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.1);padding:9px 22px;border-radius:40px;font-size:.92rem;font-weight:500;}
        .logout-btn{background:transparent;border:2px solid rgba(255,255,255,.8);color:white;border-radius:30px;padding:6px 18px;font-weight:700;transition:all .3s;font-size:.88rem;cursor:pointer;}
        .logout-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}
        .hero-banner{background:var(--primary-gradient);color:white;padding:52px 0 42px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-70px;right:-70px;width:320px;height:320px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-banner::after{content:'';position:absolute;bottom:-80px;left:-50px;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,.05);}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:18px;}
        .hero-banner h1{font-size:2.4rem;font-weight:800;line-height:1.2;margin-bottom:10px;}
        .hero-divider{width:55px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:14px 0;}
        .hero-banner p{font-size:1rem;opacity:.87;max-width:580px;}
        .back-link{display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.75);font-size:.85rem;font-weight:600;text-decoration:none;margin-bottom:14px;transition:color .2s;}
        .back-link:hover{color:var(--secondary-yellow);}
        .main-content{flex:1;}
        .filter-card{background:white;border-radius:16px;padding:22px 28px;margin-bottom:28px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid var(--border-light);}
        .form-label{font-weight:600;color:var(--primary-blue);font-size:.88rem;}
        .form-control,.form-select{border:1.5px solid var(--border-light);border-radius:10px;padding:10px 14px;font-size:.9rem;transition:border .2s;}
        .form-control:focus,.form-select:focus{border-color:var(--primary-blue);box-shadow:0 0 0 3px rgba(44,62,143,.1);outline:none;}
        .btn-filter{background:var(--primary-gradient);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:700;cursor:pointer;}
        .btn-add{background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:30px;padding:10px 28px;font-weight:800;font-size:.9rem;cursor:pointer;transition:all .3s;text-decoration:none;display:inline-block;}
        .btn-add:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(253,185,19,.4);color:var(--primary-blue);}
        .panel-card{background:white;border-radius:20px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid var(--border-light);overflow:hidden;}
        .panel-header{background:var(--primary-gradient);color:white;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;}
        .panel-header h5{font-weight:700;margin:0;font-size:1.05rem;}
        .count-badge{background:rgba(253,185,19,.25);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.4);border-radius:20px;padding:3px 12px;font-size:.78rem;font-weight:700;}
        table.premium-table{width:100%;border-collapse:collapse;}
        .premium-table thead th{background:var(--bg-light);color:var(--primary-blue);font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:12px 20px;border-bottom:2px solid var(--border-light);}
        .premium-table tbody td{padding:14px 20px;font-size:.88rem;border-bottom:1px solid var(--border-light);vertical-align:middle;color:#334155;}
        .premium-table tbody tr:last-child td{border-bottom:none;}
        .premium-table tbody tr:hover{background:var(--primary-blue-light);}
        .program-pill{display:inline-block;padding:3px 12px;border-radius:20px;font-size:.75rem;font-weight:700;background:var(--primary-blue-light);color:var(--primary-blue);border:1px solid rgba(44,62,143,.2);}
        .btn-action-edit{display:inline-flex;align-items:center;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:8px;padding:6px 14px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .25s;}
        .btn-action-edit:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(253,185,19,.4);}
        .btn-action-delete{display:inline-flex;align-items:center;background:rgba(196,30,36,.1);color:#C41E24;border:1px solid rgba(196,30,36,.2);border-radius:8px;padding:6px 14px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .25s;}
        .btn-action-delete:hover{background:#C41E24;color:white;}
        .modal-header{background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;}
        .modal-title{font-weight:800;}
        .btn-close{filter:invert(1);}
        .modal-content{border-radius:16px;border:none;box-shadow:0 20px 60px rgba(44,62,143,.2);}
        .btn-modal-submit{background:var(--primary-gradient);color:white;border:none;border-radius:30px;padding:10px 28px;font-weight:700;transition:all .3s;}
        .btn-modal-cancel{background:transparent;color:#64748b;border:1.5px solid var(--border-light);border-radius:30px;padding:10px 28px;font-weight:600;}
        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.75);text-align:center;padding:18px 0;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}
        .alert-success{background:var(--primary-blue-light);color:var(--primary-blue);border:none;border-left:4px solid var(--primary-blue);border-radius:12px;}
        .alert-danger{background:#fef2f2;color:#991b1b;border:none;border-left:4px solid #C41E24;border-radius:12px;}
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

    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('superadmin.data.dashboard') }}" class="back-link">&#8592; Back to Data Management</a>
            <div class="hero-badge">Data Management</div>
            <h1>Social Program Data</h1>
            <div class="hero-divider"></div>
            <p>Track beneficiary counts per social welfare program across all MSWDO municipalities.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
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
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-filter w-100">Apply Filter</button>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#createModal">+ Add Data</a>
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
                            @foreach($programs as $program)
                            <tr>
                                <td style="color:#94a3b8;font-size:.8rem;">#{{ $program->id }}</td>
                                <td style="font-weight:600;">{{ $program->municipality }}</td>
                                <td><span class="program-pill">{{ str_replace('_', ' ', $program->program_type) }}</span></td>
                                <td style="font-weight:700;color:var(--primary-blue);">{{ number_format($program->beneficiary_count) }}</td>
                                <td>{{ $program->year }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn-action-edit" data-bs-toggle="modal" data-bs-target="#editProg{{ $program->id }}">Edit</button>
                                        <button class="btn-action-delete" onclick="deleteProgram({{ $program->id }})">Del</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editProg{{ $program->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Program Data</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('superadmin.data.programs.update', $program->id) }}">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Municipality</label>
                                                    <input type="text" class="form-control" value="{{ $program->municipality }}" readonly disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Program Type</label>
                                                    <input type="text" class="form-control" value="{{ str_replace('_', ' ', $program->program_type) }}" readonly disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Beneficiary Count</label>
                                                    <input type="number" name="beneficiary_count" class="form-control" value="{{ $program->beneficiary_count }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Year</label>
                                                    <select name="year" class="form-select" required>
                                                        @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                                            <option value="{{ $yearOption }}" {{ $program->year == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 px-4 pb-4 gap-2">
                                                <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn-modal-submit">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 d-flex justify-content-center">{{ $programs->links() }}</div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Program Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('superadmin.data.programs.create') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Municipality</label>
                            <select name="municipality" class="form-select" required>
                                <option value="">Select Municipality</option>
                                @foreach($municipalities as $municipality)
                                    <option value="{{ $municipality }}">{{ $municipality }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program Type</label>
                            <select name="program_type" class="form-select" required>
                                <option value="">Select Program</option>
                                @foreach($programTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Beneficiary Count</label>
                            <input type="number" name="beneficiary_count" class="form-control" required placeholder="e.g. 450">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                    <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProgram(id) {
            if (confirm('Delete this program data?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/superadmin/data/programs/' + id;
                const csrf = document.createElement('input'); csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input'); method.name = '_method'; method.value = 'DELETE';
                form.appendChild(csrf); form.appendChild(method);
                document.body.appendChild(form); form.submit();
            }
        }
    </script>
</body>
</html>