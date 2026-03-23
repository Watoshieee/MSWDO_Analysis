<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} Admin Dashboard - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --accent-red: #C41E24;
            --accent-red-light: #FCE8E8;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
            --text-dark: #1E293B;
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
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
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 8px 16px !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 600;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.1);
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

        .welcome-section {
            background: var(--bg-white);
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .municipality-badge {
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            padding: 10px 24px;
            border-radius: 40px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            border: 1px solid var(--primary-blue-soft);
        }

        .stat-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            background: var(--bg-white);
            height: 100%;
            border: 1px solid var(--border-light);
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(44, 62, 143, 0.1);
        }

        .stat-card .card-body {
            padding: 24px;
            position: relative;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2.5rem;
            opacity: 0.2;
            color: var(--primary-blue);
        }

        .stat-card .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .quick-actions-card {
            background: var(--bg-white);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .quick-actions-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .quick-actions-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quick-actions-title i {
            color: var(--secondary-yellow);
        }

        .detailed-analysis-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            width: 100%;
            text-align: left;
            transition: all 0.3s ease;
        }

        .detailed-analysis-btn:hover {
            transform: translateX(6px);
            box-shadow: 0 10px 25px rgba(44, 62, 143, 0.3);
        }

        .detailed-analysis-btn i {
            font-size: 2rem;
            color: var(--secondary-yellow);
        }

        .action-btn {
            background: var(--bg-light);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
            text-align: left;
            width: 100%;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateX(6px);
            background: var(--bg-soft-blue);
            border-color: var(--primary-blue-soft);
        }

        .info-card {
            background: var(--bg-white);
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .info-highlight {
            background: var(--bg-soft-blue);
            border-radius: 16px;
            padding: 16px;
            margin-top: 20px;
            border-left: 4px solid var(--primary-blue);
        }

        .public-view-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            padding: 16px 32px;
            border-radius: 50px;
            box-shadow: 0 8px 25px rgba(44, 62, 143, 0.2);
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .public-view-btn:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <i class="bi bi-heart-fill"></i> MSWDO Analysis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}" href="{{ route('applications.index') }}">
                                <i class="bi bi-folder-check"></i> Applications
                            </a>
                        </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('analysis') ? 'active' : '' }}" href="/analysis">
                            <i class="bi bi-bar-chart"></i> Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('analysis/demographic') ? 'active' : '' }}" href="/analysis/demographic">
                            <i class="bi bi-people"></i> Demographic
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('analysis/programs') ? 'active' : '' }}" href="/analysis/programs">
                            <i class="bi bi-heart"></i> Programs
                        </a>
                    </li>
                </ul>
                <div class="d-flex">
                    @auth
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
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="background: var(--bg-soft-blue); color: var(--primary-blue); border-left: 5px solid var(--primary-blue);">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="welcome-title">
                        <i class="bi bi-house-heart-fill" style="color: var(--secondary-yellow);"></i>
                        Welcome back, {{ Auth::user()->full_name }}!
                    </h1>
                    <p class="text-muted mb-0">Here's what's happening in {{ $municipality->name }} today.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="municipality-badge">
                        <i class="bi bi-building"></i> {{ $municipality->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="card-body">
                        <i class="bi bi-folder-check stat-icon"></i>
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-value">{{ number_format($totalApplications) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: var(--secondary-yellow-light);">
                    <div class="card-body">
                        <i class="bi bi-clock-history stat-icon"></i>
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ number_format($pendingApplications) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: var(--bg-soft-blue);">
                    <div class="card-body">
                        <i class="bi bi-check-circle-fill stat-icon"></i>
                        <div class="stat-label">Approved</div>
                        <div class="stat-value">{{ number_format($approvedApplications) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card" style="background: var(--accent-red-light);">
                    <div class="card-body">
                        <i class="bi bi-x-circle-fill stat-icon"></i>
                        <div class="stat-label">Rejected</div>
                        <div class="stat-value">{{ number_format($rejectedApplications) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Municipality Info -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="quick-actions-card">
                    <div class="quick-actions-title">
                        <i class="bi bi-lightning-charge-fill"></i> Quick Actions
                    </div>
                    
                    <div class="detailed-analysis-btn" onclick="window.location.href='{{ route('admin.detailed-analysis') }}'">
                        <i class="bi bi-bar-chart-steps"></i>
                        <div>
                            <h6>📊 View Complete Analysis</h6>
                            <p>Applications by Program, Status & Barangay Statistics</p>
                        </div>
                    </div>

                    <!-- Data Management Button -->
                     <div class="detailed-analysis-btn" onclick="window.location.href='{{ route('admin.data.dashboard') }}'">
                        <i class="bi bi-database"></i>
                          <div>
                             <h6>📊 Data Management</h6>
                           <p>Update municipality, barangay, and program data</p>
                         </div>
                    </div>
                    
                    <div class="action-btn" onclick="window.location.href='{{ route('applications.index') }}'">
                        <i class="bi bi-list-check"></i>
                        <div>
                            <h6>Manage Applications</h6>
                            <p>View, approve, or reject pending applications</p>
                        </div>
                    </div>
                    
                    <div class="action-btn" onclick="window.location.href='{{ route('applications.create') }}'">
                        <i class="bi bi-plus-circle-fill"></i>
                        <div>
                            <h6>Create New Application</h6>
                            <p>Add a new beneficiary application</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="info-card">
                    <div class="quick-actions-title">
                        <i class="bi bi-building"></i> Municipality Information
                    </div>
                    
                    <table class="table">
                        <tr><td>Municipality</td><td><strong>{{ $municipality->name }}</strong></td></tr>
                        <tr><td>Total Barangays</td><td><strong>{{ $totalBarangays ?? 0 }}</strong></td></tr>
                        <tr><td>Social Programs</td><td><strong>{{ $totalPrograms ?? 0 }}</strong></td></tr>
                        <tr><td>Population</td><td><strong>{{ number_format($municipality->male_population + $municipality->female_population) }}</strong></td></tr>
                        <tr><td>Households</td><td><strong>{{ number_format($municipality->total_households) }}</strong></td></tr>
                    </table>
                    
                    <div class="info-highlight">
                        <i class="bi bi-info-circle-fill" style="color: var(--secondary-yellow);"></i>
                        <strong>{{ $municipality->name }}</strong> has 
                        <strong style="color: var(--secondary-yellow);">{{ $pendingApplications }} pending</strong> and 
                        <strong style="color: var(--primary-blue);">{{ $approvedApplications }} approved</strong> applications.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Public View Button -->
    <a href="/analysis" class="public-view-btn">
        <i class="bi bi-eye"></i>
        Public View <small>(View Analysis)</small>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>