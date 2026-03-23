<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Analysis - {{ $municipality->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            --border-light: #E2E8F0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            padding-bottom: 30px;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 20px rgba(44, 62, 143, 0.15);
            margin-bottom: 30px;
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

        .back-btn {
            background: white;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .back-btn:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateX(-5px);
            border-color: transparent;
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

        .analysis-card {
            background: white;
            border-radius: 24px;
            padding: 28px;
            margin-bottom: 30px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .analysis-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .analysis-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .analysis-title i {
            color: var(--secondary-yellow);
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
            margin: 20px 0;
        }

        .stat-card-small {
            background: var(--bg-soft-blue);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }

        .stat-card-small .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .badge-pending {
            background: var(--secondary-yellow-light);
            color: var(--secondary-yellow);
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-approved {
            background: #E1F7E1;
            color: #28a745;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .badge-rejected {
            background: var(--accent-red-light);
            color: var(--accent-red);
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .table-container {
            max-height: 450px;
            overflow-y: auto;
            border-radius: 16px;
            border: 1px solid var(--border-light);
            background: white;
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            padding: 15px;
            position: sticky;
            top: 0;
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
            <div class="collapse navbar-collapse">
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

    <div class="container">
        <!-- Header with Back Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="/dashboard" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <span class="municipality-badge">
                <i class="bi bi-building"></i> {{ $municipality->name }} Municipality
            </span>
        </div>

        <!-- Summary Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card-small">
                    <div class="stat-value">{{ $totalApplications }}</div>
                    <div class="stat-label">Total Applications</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card-small" style="background: var(--secondary-yellow-light);">
                    <div class="stat-value">{{ $pendingApplications }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card-small" style="background: #E1F7E1;">
                    <div class="stat-value">{{ $approvedApplications }}</div>
                    <div class="stat-label">Approved</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card-small" style="background: var(--accent-red-light);">
                    <div class="stat-value">{{ $rejectedApplications }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
            </div>
        </div>

        <!-- Applications by Program Chart -->
        <div class="analysis-card">
            <div class="analysis-title">
                <i class="bi bi-pie-chart-fill"></i>
                Applications by Program
            </div>
            <div class="chart-container">
                <canvas id="programChart"></canvas>
            </div>
        </div>

        <!-- Applications by Status -->
        <div class="analysis-card">
            <div class="analysis-title">
                <i class="bi bi-bar-chart-fill"></i>
                Applications by Status
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card p-4">
                        <h6>Status Breakdown</h6>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span><span class="badge-pending me-2">●</span> Pending</span>
                                <strong>{{ $pendingApplications }} ({{ $totalApplications > 0 ? round(($pendingApplications/$totalApplications)*100, 1) : 0 }}%)</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><span class="badge-approved me-2">●</span> Approved</span>
                                <strong>{{ $approvedApplications }} ({{ $totalApplications > 0 ? round(($approvedApplications/$totalApplications)*100, 1) : 0 }}%)</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span><span class="badge-rejected me-2">●</span> Rejected</span>
                                <strong>{{ $rejectedApplications }} ({{ $totalApplications > 0 ? round(($rejectedApplications/$totalApplications)*100, 1) : 0 }}%)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barangay-level Statistics -->
        <div class="analysis-card">
            <div class="analysis-title">
                <i class="bi bi-grid-3x3-gap-fill"></i>
                Barangay-level Statistics
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Barangay</th>
                            <th>Population</th>
                            <th>Households</th>
                            <th>Total</th>
                            <th>Pending</th>
                            <th>Approved</th>
                            <th>Rejected</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangayStats as $barangayName => $stats)
                        <tr>
                            <td><strong>{{ $barangayName }}</strong></td>
                            <td>{{ number_format($stats['population']) }}</td>
                            <td>{{ number_format($stats['households']) }}</td>
                            <td>{{ $stats['total'] }}</td>
                            <td><span class="badge-pending">{{ $stats['pending'] }}</span></td>
                            <td><span class="badge-approved">{{ $stats['approved'] }}</span></td>
                            <td><span class="badge-rejected">{{ $stats['rejected'] }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No barangay data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        new Chart(document.getElementById('programChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($applicationsByProgram->keys()->map(function($key) { return str_replace('_', ' ', $key); })) !!},
                datasets: [
                    { label: 'Pending', data: {!! json_encode($applicationsByProgram->pluck('pending')) !!}, backgroundColor: '#FDB913' },
                    { label: 'Approved', data: {!! json_encode($applicationsByProgram->pluck('approved')) !!}, backgroundColor: '#2C3E8F' },
                    { label: 'Rejected', data: {!! json_encode($applicationsByProgram->pluck('rejected')) !!}, backgroundColor: '#C41E24' }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [{{ $pendingApplications }}, {{ $approvedApplications }}, {{ $rejectedApplications }}],
                    backgroundColor: ['#FDB913', '#2C3E8F', '#C41E24']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>
</body>
</html>