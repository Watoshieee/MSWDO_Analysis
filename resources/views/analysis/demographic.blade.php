<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demographic Analysis - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
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

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            border-radius: 8px;
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

        .page-header {
            margin: 30px 0;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 15px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            height: 100%;
        }

        .chart-container {
            position: relative;
            height: 350px;
            margin: 20px 0;
        }

        .demographic-table {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
            border: none;
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
                            <a class="nav-link" href="/dashboard">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('applications.index') }}">
                                <i class="bi bi-folder-check"></i> Applications
                            </a>
                        </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis">
                            <i class="bi bi-bar-chart"></i> Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/analysis/demographic">
                            <i class="bi bi-people"></i> Demographic
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis/programs">
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
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-light">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-people" style="color: var(--secondary-yellow);"></i>
                Demographic Analysis
            </h1>
            <p class="text-muted">Population distribution and demographics across municipalities</p>
        </div>

        <!-- Gender Distribution Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <h5 class="mb-3">Gender Distribution</h5>
                    <div class="chart-container">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <h5 class="mb-3">Age Group Distribution</h5>
                    <div class="chart-container">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demographic Table -->
        <div class="demographic-table">
            <h5 class="mb-3">Detailed Demographics</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Municipality</th>
                            <th>Total Population</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>0-19</th>
                            <th>20-59</th>
                            <th>60-100</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demographicData as $municipality => $data)
                        <tr>
                            <td><strong>{{ $municipality }}</strong></td>
                            <td>{{ number_format($data['total']) }}</td>
                            <td>{{ number_format($data['male']) }} ({{ $data['male_pct'] }}%)</td>
                            <td>{{ number_format($data['female']) }} ({{ $data['female_pct'] }}%)</td>
                            <td>{{ number_format($data['age_0_19']) }} ({{ $data['age_0_19_pct'] }}%)</td>
                            <td>{{ number_format($data['age_20_59']) }} ({{ $data['age_20_59_pct'] }}%)</td>
                            <td>{{ number_format($data['age_60_100']) }} ({{ $data['age_60_100_pct'] }}%)</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gender Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($demographicData)) !!},
                    datasets: [
                        {
                            label: 'Male',
                            data: {!! json_encode(array_column($demographicData, 'male')) !!},
                            backgroundColor: '#2C3E8F',
                            borderRadius: 6
                        },
                        {
                            label: 'Female',
                            data: {!! json_encode(array_column($demographicData, 'female')) !!},
                            backgroundColor: '#FDB913',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Age Chart
            new Chart(document.getElementById('ageChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($demographicData)) !!},
                    datasets: [
                        {
                            label: '0-19 years',
                            data: {!! json_encode(array_column($demographicData, 'age_0_19')) !!},
                            borderColor: '#2C3E8F',
                            backgroundColor: 'rgba(44, 62, 143, 0.1)',
                            tension: 0.1,
                            fill: true
                        },
                        {
                            label: '20-59 years',
                            data: {!! json_encode(array_column($demographicData, 'age_20_59')) !!},
                            borderColor: '#FDB913',
                            backgroundColor: 'rgba(253, 185, 19, 0.1)',
                            tension: 0.1,
                            fill: true
                        },
                        {
                            label: '60-100 years',
                            data: {!! json_encode(array_column($demographicData, 'age_60_100')) !!},
                            borderColor: '#C41E24',
                            backgroundColor: 'rgba(196, 30, 36, 0.1)',
                            tension: 0.1,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</body>
</html>