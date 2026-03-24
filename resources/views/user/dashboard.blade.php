<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard - MSWDO</title>
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
            color: #6c757d;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .program-card {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .program-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(44, 62, 143, 0.1);
            border-color: var(--primary-blue);
        }

        .program-icon {
            font-size: 2rem;
            color: var(--secondary-yellow);
            margin-bottom: 15px;
        }

        .program-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .apply-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .apply-btn:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
        }

        .application-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: var(--secondary-yellow-light);
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: var(--accent-red-light);
            color: #721c24;
        }

        .announcement-card {
            background: var(--bg-soft-blue);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            border-left: 4px solid var(--secondary-yellow);
        }
        
        a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <i class="bi bi-heart-fill"></i> MSWDO Analysis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/user/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/programs">
                            <i class="bi bi-list-check"></i> Programs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/my-applications">
                            <i class="bi bi-folder-check"></i> My Applications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/announcements">
                            <i class="bi bi-megaphone"></i> Announcements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis">
                            <i class="bi bi-bar-chart"></i> Public Analysis
                        </a>
                    </li>
                </ul>
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
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="background: var(--bg-soft-blue); color: var(--primary-blue); border-left: 5px solid var(--primary-blue);">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="welcome-title">
                        <i class="bi bi-house-heart-fill" style="color: var(--secondary-yellow);"></i>
                        Welcome, {{ Auth::user()->full_name }}!
                    </h1>
                    <p class="text-muted mb-0">Apply for MSWDO programs and track your applications.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-primary p-3">
                        <i class="bi bi-calendar-check"></i> 
                        {{ date('F d, Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="card-body">
                        <i class="bi bi-folder-check stat-icon"></i>
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-value">{{ $totalApplications ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card" style="background: var(--secondary-yellow-light);">
                    <div class="card-body">
                        <i class="bi bi-clock-history stat-icon"></i>
                        <div class="stat-label">Pending</div>
                        <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card" style="background: var(--bg-soft-blue);">
                    <div class="card-body">
                        <i class="bi bi-check-circle-fill stat-icon"></i>
                        <div class="stat-label">Approved</div>
                        <div class="stat-value">{{ $approvedCount ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Programs Section -->
        <h4 class="mb-3" style="color: var(--primary-blue);">
            <i class="bi bi-grid-3x3-gap-fill" style="color: var(--secondary-yellow);"></i>
            Available Programs
        </h4>

        <div class="row mb-4">
            <!-- 4Ps Card -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('user.apply', '4Ps') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-people"></i></div>
                        <div class="program-title">4Ps</div>
                        <small class="text-muted">Pantawid Pamilyang Pilipino Program</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Senior Citizen Card -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('user.apply', 'Senior_Citizen_Pension') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-person-standing"></i></div>
                        <div class="program-title">Senior Citizen</div>
                        <small class="text-muted">Social pension for senior citizens</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- PWD Card -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('user.apply', 'PWD_Assistance') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-handicap"></i></div>
                        <div class="program-title">PWD Assistance</div>
                        <small class="text-muted">Assistance for persons with disability</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Solo Parent Card - FIXED: Uses solo-parent.apply route -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('solo-parent.apply') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-person-hearts"></i></div>
                        <div class="program-title">Solo Parent</div>
                        <small class="text-muted">Support for solo parents</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- AICS Card -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('user.apply', 'AICS') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="program-title">AICS</div>
                        <small class="text-muted">Assistance to Individuals in Crisis Situation</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- SLP Card -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('user.apply', 'SLP') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-briefcase"></i></div>
                        <div class="program-title">SLP</div>
                        <small class="text-muted">Sustainable Livelihood Program</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>

            <!-- ESA Card -->
            <div class="col-md-3 col-sm-6 mb-3">
                <a href="{{ route('user.apply', 'ESA') }}">
                    <div class="program-card">
                        <div class="program-icon"><i class="bi bi-mortarboard"></i></div>
                        <div class="program-title">ESA</div>
                        <small class="text-muted">Educational Assistance</small>
                        <div class="mt-3">
                            <button class="apply-btn btn-sm">Apply Now <i class="bi bi-arrow-right"></i></button>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Recent Applications -->
            <div class="col-md-7 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Applications</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($recentApplications) && count($recentApplications) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Program</th>
                                            <th>Barangay</th>
                                            <th>Date Applied</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentApplications as $app)
                                        <tr>
                                            <td>
                                                <strong>{{ str_replace('_', ' ', $app->program_type) }}</strong>
                                            </td>
                                            <td>{{ $app->barangay }}</td>
                                            <td>{{ $app->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <span class="application-status status-{{ $app->status }}">
                                                    {{ ucfirst($app->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('user.my-applications') }}" class="btn btn-outline-primary">
                                    View All Applications <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No applications yet.</p>
                                <a href="{{ route('user.programs') }}" class="btn btn-primary">
                                    Apply for a Program
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Announcements -->
            <div class="col-md-5 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-megaphone"></i> Latest Announcements</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($announcements) && count($announcements) > 0)
                            @foreach($announcements as $announcement)
                            <div class="announcement-card">
                                <h6 class="mb-1">
                                    <i class="bi bi-bullhorn" style="color: var(--secondary-yellow);"></i>
                                    {{ $announcement->title }}
                                </h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    {{ $announcement->created_at->format('F d, Y') }}
                                </small>
                                <p class="mb-0 mt-2 small">{{ Str::limit($announcement->content, 100) }}</p>
                            </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="{{ route('user.announcements') }}" class="btn btn-sm btn-link">
                                    View All Announcements <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-bell-slash" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No announcements yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>