<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Profile – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: {{ $primaryColor ?? '#2C3E8F' }};
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: {{ $secondaryColor ?? '#FDB913' }};
            --secondary-yellow-light: #FFF3D6;
            --accent-green: #28a745;
            --accent-red: {{ $accentColor ?? '#C41E24' }};
            --accent-red-light: #FCE8E8;
            --primary-gradient: linear-gradient(135deg, {{ $primaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, {{ $secondaryColor ?? '#FDB913' }} 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-mid: #475569;
            --text-light: #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { margin: 0; padding: 0; overscroll-behavior: none; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); }
        a { text-decoration: none; color: inherit; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto; margin-right: 0; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0; margin-right: auto; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.92rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ── HERO BANNER ── */
        .hero-banner {
            background: var(--primary-gradient);
            color: white; padding: 56px 0 50px;
            position: relative; overflow: hidden;
        }
        .hero-banner::before { content: ''; position: absolute; top: -90px; right: -90px; width: 360px; height: 360px; border-radius: 50%; background: rgba(253,185,19,0.10); }
        .hero-banner::after  { content: ''; position: absolute; bottom: -60px; left: -50px; width: 240px; height: 240px; border-radius: 50%; background: rgba(255,255,255,0.04); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge {
            display: inline-block; background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px; padding: 5px 18px; font-size: 0.75rem; font-weight: 800;
            letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 18px;
        }
        .hero-banner h1 { font-size: 2.5rem; font-weight: 900; margin-bottom: 6px; line-height: 1.15; }
        .hero-divider { width: 50px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 16px 0; }
        .hero-banner p { opacity: 0.84; font-size: 0.97rem; margin: 0; max-width: 580px; line-height: 1.7; }

        /* ── PANEL CARDS ── */
        .panel-card { background: white; border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 26px; font-size: 0.95rem; font-weight: 800; letter-spacing: 0.02em; display: flex; align-items: center; justify-content: space-between; }
        .panel-header .ph-badge { background: rgba(253,185,19,0.25); color: var(--secondary-yellow); border-radius: 20px; padding: 3px 12px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; }
        .panel-body { padding: 28px 32px; }

        /* Form styles */
        .form-label { font-weight: 700; color: var(--primary-blue); font-size: 0.88rem; margin-bottom: 8px; }
        .form-control, .form-select { border: 2px solid var(--border-light); border-radius: 10px; padding: 12px 16px; font-size: 0.9rem; transition: all 0.25s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,0.1); }
        .form-control:disabled, .form-select:disabled { background: #f1f5f9; cursor: not-allowed; }

        .btn-primary-custom { background: var(--primary-gradient); color: white; border: none; border-radius: 10px; padding: 12px 28px; font-weight: 700; font-size: 0.9rem; transition: all 0.25s; cursor: pointer; }
        .btn-primary-custom:hover { box-shadow: 0 6px 20px rgba(44,62,143,0.32); transform: translateY(-1px); }
        .btn-secondary-custom { background: #e2e8f0; color: var(--text-mid); border: none; border-radius: 10px; padding: 12px 28px; font-weight: 700; font-size: 0.9rem; transition: all 0.25s; cursor: pointer; }
        .btn-secondary-custom:hover { background: #cbd5e1; }

        /* Quick Actions */
        .quick-link {
            display: flex; align-items: center; gap: 16px;
            padding: 14px 18px; border-radius: 14px; border: 1px solid var(--border-light);
            background: white; transition: all 0.25s; margin-bottom: 10px; color: var(--text-dark);
        }
        .quick-link:hover { background: var(--bg-soft-blue); border-color: var(--primary-blue-soft); padding-left: 22px; }
        .ql-num { width: 36px; height: 36px; border-radius: 10px; background: var(--bg-soft-blue); color: var(--primary-blue); font-weight: 900; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; letter-spacing: 0.05em; }
        .ql-text strong { display: block; font-size: 0.9rem; font-weight: 700; color: var(--primary-blue); }
        .ql-text span   { font-size: 0.76rem; color: var(--text-light); }
        .ql-arrow { margin-left: auto; color: var(--text-light); font-size: 1.2rem; font-weight: 300; }

        /* Alerts */
        .dash-alert { border-radius: 14px; border: none; padding: 16px 20px; font-size: 0.9rem; margin-bottom: 16px; border-left: 5px solid transparent; display: flex; align-items: flex-start; gap: 14px; }
        .dash-alert-label { font-weight: 800; font-size: 0.68rem; letter-spacing: 0.1em; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .alert-success-c { background: #d4edda; border-left-color: var(--accent-green); color: #155724; }
        .alert-danger-c  { background: var(--accent-red-light); border-left-color: var(--accent-red); color: #721c24; }
        .alert-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem; flex-shrink: 0; }

        /* Footer */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.7); text-align: center; padding: 20px 0; font-size: 0.84rem; margin-top: 60px; }
        .footer-strip strong { color: white; }

        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border-light); }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: 600; color: var(--text-mid); font-size: 0.88rem; }
        .info-value { font-weight: 700; color: var(--primary-blue); font-size: 0.88rem; }
    </style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/user/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/programs">Programs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('user.profile') }}">User Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.announcements') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis">Public Analysis</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ===== HERO BANNER ===== -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Account Settings</div>
                <h1>User Profile</h1>
                <div class="hero-divider"></div>
                <p>Manage your personal information and account settings.</p>
            </div>
        </div>
    </section>

    <div class="container mt-4 mb-5">

        {{-- Session Alerts --}}
        @php
            $topNotice = session('success') ?: session('error');
        @endphp
        @if($topNotice)
            <div style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
                {{ $topNotice }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Information -->
            <div class="col-lg-8">
                <div class="panel-card">
                    <div class="panel-header">
                        Profile Information
                        <span class="ph-badge">Edit</span>
                    </div>
                    <div class="panel-body">
                        <form method="POST" action="{{ route('user.profile.update') }}" id="profileForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', Auth::user()->first_name ?? explode(' ', Auth::user()->full_name)[0] ?? '') }}" required>
                                    @error('first_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', Auth::user()->last_name ?? (count(explode(' ', Auth::user()->full_name)) > 1 ? explode(' ', Auth::user()->full_name)[count(explode(' ', Auth::user()->full_name)) - 1] : '')) }}" required>
                                    @error('last_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" class="form-control" value="{{ old('middle_name', Auth::user()->middle_name ?? (count(explode(' ', Auth::user()->full_name)) > 2 ? explode(' ', Auth::user()->full_name)[1] : '')) }}">
                                    @error('middle_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <small class="text-muted">(Auto-generated)</small></label>
                                    <input type="text" id="full_name_display" class="form-control" disabled style="background:#f8fafc;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" disabled style="background:#f8fafc;">
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', Auth::user()->phone_number ?? Auth::user()->mobile_number) }}" required>
                                    @error('phone_number')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', Auth::user()->date_of_birth ?? Auth::user()->birthdate) }}" required>
                                    @error('date_of_birth')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->gender }}" disabled style="background:#f8fafc;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Municipality</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->municipality }}" disabled style="background:#f8fafc;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Barangay</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->barangay }}" disabled style="background:#f8fafc;">
                                </div>
                            </div>

                            <div class="mt-4 d-flex gap-2">
                                <button type="submit" class="btn-primary-custom">
                                    <i class="bi bi-check-circle me-2"></i>Save Changes
                                </button>
                                <a href="{{ route('user.dashboard') }}" class="btn-secondary-custom">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Current Profile Information -->
            <div class="col-lg-4">
                <div class="panel-card mb-4">
                    <div class="panel-header">
                        Current Profile
                        <span class="ph-badge">Info</span>
                    </div>
                    <div class="panel-body">
                        <div class="info-row">
                            <span class="info-label">Full Name</span>
                            <span class="info-value">{{ Auth::user()->full_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email</span>
                            <span class="info-value" style="font-size:0.8rem;word-break:break-all;">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone</span>
                            <span class="info-value">{{ Auth::user()->phone_number }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Birth</span>
                            <span class="info-value">{{ Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('M d, Y') : 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Gender</span>
                            <span class="info-value">{{ Auth::user()->gender }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Municipality</span>
                            <span class="info-value">{{ Auth::user()->municipality }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Barangay</span>
                            <span class="info-value">{{ Auth::user()->barangay }}</span>
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="panel-card">
                    <div class="panel-header">
                        Account Information
                        <span class="ph-badge">Status</span>
                    </div>
                    <div class="panel-body">
                        <div class="info-row">
                            <span class="info-label">Account Type</span>
                            <span class="info-value">{{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Member Since</span>
                            <span class="info-value">{{ Auth::user()->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email Verified</span>
                            <span class="info-value">
                                @if(Auth::user()->email_verified_at)
                                    <i class="bi bi-check-circle-fill text-success"></i> Yes
                                @else
                                    <i class="bi bi-x-circle-fill text-danger"></i> No
                                @endif
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:12px;font-size:0.75rem;font-weight:800;">Active</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.chat-modal')
    @include('components.chatbot-widget')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-generate Full Name
        function updateFullName() {
            const firstName = document.getElementById('first_name').value.trim();
            const middleName = document.getElementById('middle_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            
            let fullName = firstName;
            if (middleName) {
                fullName += ' ' + middleName;
            }
            if (lastName) {
                fullName += ' ' + lastName;
            }
            
            document.getElementById('full_name_display').value = fullName.trim();
        }
        
        // Update on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateFullName();
            
            // Update on input change
            document.getElementById('first_name').addEventListener('input', updateFullName);
            document.getElementById('middle_name').addEventListener('input', updateFullName);
            document.getElementById('last_name').addEventListener('input', updateFullName);
        });
    </script>
</body>
</html>
