<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demographic Analysis - {{ $municipality->name }}</title>
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
        }

        .navbar-brand, .nav-link {
            color: white !important;
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

        .municipality-badge {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
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
            height: 300px;
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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-people"></i> Demographic Analysis
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-arrow-left"></i> Back to Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">
                    <i class="bi bi-people" style="color: var(--secondary-yellow);"></i>
                    Demographic Analysis
                </h1>
                <span class="municipality-badge">
                    <i class="bi bi-building"></i> {{ $municipality->name }}
                </span>
            </div>
            <p class="text-muted">Population demographics for {{ $municipality->name }}</p>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Total Population</h6>
                    <h2 class="text-primary">{{ number_format($demographicData['total_population']) }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Male</h6>
                    <h2 class="text-primary">{{ number_format($demographicData['male']) }}</h2>
                    <small>{{ $demographicData['male_pct'] }}%</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Female</h6>
                    <h2 class="text-primary">{{ number_format($demographicData['female']) }}</h2>
                    <small>{{ $demographicData['female_pct'] }}%</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h6 class="text-muted">Total Barangays</h6>
                    <h2 class="text-primary">{{ count($barangayData) }}</h2>
                </div>
            </div>
        </div>

        <!-- Charts -->
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
                    <h5 class="mb-3">Age Distribution</h5>
                    <div class="chart-container">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barangay Table -->
        <div class="demographic-table">
            <h5 class="mb-3">Barangay Demographics</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Barangay</th>
                            <th>Population</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Households</th>
                            <th>Single Parents</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangayData as $barangay)
                        <tr>
                            <td><strong>{{ $barangay['name'] }}</strong></td>
                            <td>{{ number_format($barangay['population']) }}</td>
                            <td>{{ number_format($barangay['male']) }}</td>
                            <td>{{ number_format($barangay['female']) }}</td>
                            <td>{{ number_format($barangay['households']) }}</td>
                            <td>{{ number_format($barangay['single_parents']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gender Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [{{ $demographicData['male'] }}, {{ $demographicData['female'] }}],
                        backgroundColor: ['#2C3E8F', '#FDB913'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Age Chart
            new Chart(document.getElementById('ageChart'), {
                type: 'bar',
                data: {
                    labels: ['0-19', '20-59', '60-100'],
                    datasets: [{
                        data: [
                            {{ $demographicData['age_0_19'] }},
                            {{ $demographicData['age_20_59'] }},
                            {{ $demographicData['age_60_100'] }}
                        ],
                        backgroundColor: ['#2C3E8F', '#FDB913', '#C41E24'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
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