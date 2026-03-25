<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue:#2C3E8F; --primary-blue-light:#E5EEFF; --primary-blue-soft:#5D7BB9;
            --secondary-yellow:#FDB913; --secondary-yellow-light:#FFF3D6; --accent-red:#C41E24;
            --primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);
            --secondary-gradient:linear-gradient(135deg,#FDB913 0%,#E5A500 100%);
            --bg-light:#F8FAFC; --border-light:#E2E8F0;
        }
        *, body { font-family:'Inter','Segoe UI',sans-serif; }
        body { background:var(--bg-light); display:flex; flex-direction:column; min-height:100vh; }
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

        /* Panel */
        .panel-card{background:white;border-radius:20px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid var(--border-light);overflow:hidden;}
        .panel-header{background:var(--primary-gradient);color:white;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;}
        .panel-header h5{font-weight:700;margin:0;font-size:1.05rem;}
        .panel-header .count-badge{background:rgba(253,185,19,.25);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.4);border-radius:20px;padding:3px 12px;font-size:.78rem;font-weight:700;}
        table.premium-table{width:100%;border-collapse:collapse;}
        .premium-table thead th{background:var(--bg-light);color:var(--primary-blue);font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:12px 20px;border-bottom:2px solid var(--border-light);}
        .premium-table tbody td{padding:14px 20px;font-size:.88rem;border-bottom:1px solid var(--border-light);vertical-align:middle;color:#334155;}
        .premium-table tbody tr:last-child td{border-bottom:none;}
        .premium-table tbody tr:hover{background:var(--primary-blue-light);}

        /* Pills */
        .role-pill{display:inline-block;padding:3px 12px;border-radius:20px;font-size:.75rem;font-weight:700;letter-spacing:.04em;}
        .role-pill.super-admin{background:rgba(196,30,36,.12);color:#C41E24;border:1px solid rgba(196,30,36,.2);}
        .role-pill.admin{background:var(--secondary-yellow-light);color:#92600a;border:1px solid rgba(253,185,19,.3);}
        .role-pill.user{background:var(--primary-blue-light);color:var(--primary-blue);border:1px solid rgba(44,62,143,.2);}
        .status-pill{display:inline-block;padding:3px 12px;border-radius:20px;font-size:.75rem;font-weight:700;}
        .status-pill.active{background:#e6f9f0;color:#1a7a4a;}
        .status-pill.inactive{background:#fef2f2;color:#991b1b;}
        .verified-pill{display:inline-block;padding:3px 12px;border-radius:20px;font-size:.75rem;font-weight:700;}
        .verified-pill.yes{background:#e6f9f0;color:#1a7a4a;}
        .verified-pill.no{background:#FFF3D6;color:#92600a;}

        /* Action Buttons */
        .btn-action-edit{display:inline-flex;align-items:center;justify-content:center;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:8px;padding:6px 14px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .25s;text-decoration:none;}
        .btn-action-edit:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(253,185,19,.4);color:var(--primary-blue);}
        .btn-action-delete{display:inline-flex;align-items:center;justify-content:center;background:rgba(196,30,36,.1);color:#C41E24;border:1px solid rgba(196,30,36,.2);border-radius:8px;padding:6px 14px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .25s;}
        .btn-action-delete:hover{background:#C41E24;color:white;transform:translateY(-2px);}
        .btn-add{background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:30px;padding:10px 28px;font-weight:800;font-size:.9rem;cursor:pointer;transition:all .3s;text-decoration:none;display:inline-block;}
        .btn-add:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(253,185,19,.4);color:var(--primary-blue);}

        /* Modal */
        .modal-header{background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;}
        .modal-title{font-weight:800;}
        .btn-close{filter:invert(1);}
        .modal-content{border-radius:16px;border:none;box-shadow:0 20px 60px rgba(44,62,143,.2);}
        .form-label{font-weight:600;color:var(--primary-blue);font-size:.88rem;}
        .form-control,.form-select{border:1.5px solid var(--border-light);border-radius:10px;padding:10px 14px;font-size:.9rem;transition:border .2s;}
        .form-control:focus,.form-select:focus{border-color:var(--primary-blue);box-shadow:0 0 0 3px rgba(44,62,143,.1);outline:none;}
        .btn-modal-submit{background:var(--primary-gradient);color:white;border:none;border-radius:30px;padding:10px 28px;font-weight:700;width:100%;transition:all .3s;}
        .btn-modal-submit:hover{opacity:.9;transform:translateY(-1px);}
        .btn-modal-cancel{background:transparent;color:#64748b;border:1.5px solid var(--border-light);border-radius:30px;padding:10px 28px;font-weight:600;width:100%;}

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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('superadmin.users') }}">User Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
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
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('superadmin.dashboard') }}" class="back-link">&#8592; Back to Dashboard</a>
            <div class="hero-badge">Super Admin</div>
            <h1>User Management</h1>
            <div class="hero-divider"></div>
            <p>Create, update, and manage all system users, roles, and account status.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div></div>
                <a href="#" class="btn-add" data-bs-toggle="modal" data-bs-target="#createUserModal">+ Create New User</a>
            </div>

            <div class="panel-card">
                <div class="panel-header">
                    <h5>All Users</h5>
                    <span class="count-badge">{{ $users->count() }} users</span>
                </div>
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Municipality</th>
                                <th>Status</th>
                                <th>Verified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td style="color:#94a3b8;font-size:.8rem;">#{{ $user->id }}</td>
                                <td style="font-weight:600;">{{ $user->username }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td style="color:#64748b;">{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'super_admin')
                                        <span class="role-pill super-admin">Super Admin</span>
                                    @elseif($user->role == 'admin')
                                        <span class="role-pill admin">Admin</span>
                                    @else
                                        <span class="role-pill user">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->municipality ?? 'N/A' }}</td>
                                <td><span class="status-pill {{ $user->status == 'active' ? 'active' : 'inactive' }}">{{ ucfirst($user->status) }}</span></td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="verified-pill yes">Verified</span>
                                    @else
                                        <span class="verified-pill no">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn-action-edit" onclick="editUser({{ $user }})">Edit</button>
                                        @if($user->id !== Auth::id())
                                        <form action="{{ route('superadmin.users.delete', $user->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-action-delete" onclick="return confirm('Delete this user?')">Del</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <!-- CREATE USER MODAL -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('superadmin.users.create') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="e.g. juan_dela_cruz">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required placeholder="Juan Dela Cruz">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="user@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required id="createRole">
                                @foreach($roles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="createMunicipalityField">
                            <label class="form-label">Municipality</label>
                            <select name="municipality" class="form-select">
                                <option value="">Select Municipality</option>
                                @foreach($municipalities as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('createRole').addEventListener('change', function() {
            document.getElementById('createMunicipalityField').style.display = this.value === 'admin' ? 'block' : 'none';
        });
        function editUser(user) { alert('Edit functionality — User: ' + user.full_name); }
    </script>
</body>
</html>