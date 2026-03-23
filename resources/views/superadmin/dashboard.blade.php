<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 20px rgba(44, 62, 143, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .navbar-brand i {
            color: var(--secondary-yellow);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255,255,255,0.1);
            padding: 8px 20px;
            border-radius: 40px;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid white;
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        /* Sidebar Styles */
        .wrapper {
            display: flex;
            width: 100%;
        }

        .sidebar {
            width: 280px;
            background: white;
            border-right: 1px solid var(--border-light);
            height: calc(100vh - 72px);
            position: sticky;
            top: 72px;
            overflow-y: auto;
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: var(--text-dark) !important;
            padding: 12px 25px;
            margin: 5px 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar .nav-link i {
            color: var(--primary-blue);
            font-size: 1.3rem;
            width: 24px;
        }

        .sidebar .nav-link:hover {
            background: var(--primary-blue-light);
            color: var(--primary-blue) !important;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: white !important;
        }

        .sidebar .nav-link.active i {
            color: var(--secondary-yellow);
        }

        .sidebar .nav-link .badge {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            margin-left: auto;
        }

        .sidebar .section-title {
            padding: 15px 25px 5px;
            color: #6c757d;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(44, 62, 143, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--secondary-yellow);
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .data-menu-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .data-menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(44, 62, 143, 0.1);
            border-color: var(--primary-blue);
        }

        .data-menu-icon {
            font-size: 2rem;
            color: var(--secondary-yellow);
            margin-bottom: 10px;
        }

        .data-menu-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .data-menu-desc {
            color: #6c757d;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/superadmin/dashboard">
                <i class="bi bi-shield-lock-fill"></i> Super Admin Panel
            </a>
            <div class="d-flex">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ Auth::user()->full_name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="section-title">Main Menu</div>
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link active">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('superadmin.users') }}" class="nav-link">
                <i class="bi bi-people"></i> User Management
                <span class="badge">{{ $totalUsers }}</span>
            </a>
            <div class="section-title mt-3">Municipality Management</div>
              <a href="{{ route('superadmin.municipalities.index') }}" class="nav-link">
                 <i class="bi bi-building"></i> Municipalities
            </a>
            <div class="section-title mt-3">Data Management</div>
            <a href="{{ route('superadmin.data.dashboard') }}" class="nav-link">
                <i class="bi bi-database"></i> Data Dashboard
            </a>
            
            <a href="{{ route('superadmin.data.municipalities') }}" class="nav-link">
                <i class="bi bi-building"></i> Municipalities
            </a>
            <a href="{{ route('superadmin.data.barangays') }}" class="nav-link">
                <i class="bi bi-grid-3x3"></i> Barangays
            </a>
            <a href="{{ route('superadmin.data.programs') }}" class="nav-link">
                <i class="bi bi-heart"></i> Programs
            </a>

            <div class="section-title mt-3">Quick Links</div>
            <a href="/analysis" class="nav-link" target="_blank">
                <i class="bi bi-bar-chart"></i> View Analysis
            </a>
            <a href="/applications" class="nav-link" target="_blank">
                <i class="bi bi-folder-check"></i> Applications
            </a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Welcome Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 style="color: var(--primary-blue);">
                    <i class="bi bi-speedometer2" style="color: var(--secondary-yellow);"></i>
                    Super Admin Dashboard
                </h1>
                <span class="badge bg-primary p-3">Welcome back, {{ Auth::user()->full_name }}!</span>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div class="stat-value">{{ $totalUsers }}</div>
                        <div class="stat-label">Total Users</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-shield-fill"></i></div>
                        <div class="stat-value">{{ $totalSuperAdmins }}</div>
                        <div class="stat-label">Super Admins</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
                        <div class="stat-value">{{ $totalAdmins }}</div>
                        <div class="stat-label">Admins</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-person"></i></div>
                        <div class="stat-value">{{ $totalRegularUsers }}</div>
                        <div class="stat-label">Regular Users</div>
                    </div>
                </div>
            </div>

            <!-- Data Management Quick Access -->
            <h4 class="mb-3" style="color: var(--primary-blue);">
                <i class="bi bi-database" style="color: var(--secondary-yellow);"></i>
                Quick Data Management
            </h4>

            <div class="row mb-4">
                <div class="col-md-4">
                    <a href="{{ route('superadmin.data.municipalities') }}" style="text-decoration: none;">
                        <div class="data-menu-card">
                            <div class="data-menu-icon"><i class="bi bi-building"></i></div>
                            <div class="data-menu-title">Municipality Data</div>
                            <div class="data-menu-desc">Update population, households, demographics</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('superadmin.data.barangays') }}" style="text-decoration: none;">
                        <div class="data-menu-card">
                            <div class="data-menu-icon"><i class="bi bi-grid-3x3"></i></div>
                            <div class="data-menu-title">Barangay Data</div>
                            <div class="data-menu-desc">Manage barangay-level statistics</div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('superadmin.data.programs') }}" style="text-decoration: none;">
                        <div class="data-menu-card">
                            <div class="data-menu-icon"><i class="bi bi-heart"></i></div>
                            <div class="data-menu-title">Program Data</div>
                            <div class="data-menu-desc">Track beneficiaries per program</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Users -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-people"></i> Recent Users</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'super_admin' ? 'danger' : ($user->role == 'admin' ? 'warning' : 'info') }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $user->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Municipalities Overview -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Municipalities Overview</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Municipality</th>
                                    <th>Population</th>
                                    <th>Households</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($municipalities as $m)
                                <tr>
                                    <td>{{ $m->name }}</td>
                                    <td>{{ number_format($m->male_population + $m->female_population) }}</td>
                                    <td>{{ number_format($m->total_households) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>