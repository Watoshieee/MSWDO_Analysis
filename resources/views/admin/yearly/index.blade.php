<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Comparison - {{ $municipality->name }}</title>
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
            --soft-green: #E1F7E1;
            --soft-red: #FCE8E8;
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

        .year-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .year-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(44, 62, 143, 0.1);
            border-color: var(--primary-blue);
        }

        .year-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
        }

        .year-badge {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border-light);
        }

        .trend-up {
            color: #28a745;
            font-weight: 600;
        }

        .trend-down {
            color: #dc3545;
            font-weight: 600;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin: 30px 0;
        }

        .action-btn {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-calendar-check"></i> Yearly Comparison - {{ $municipality->name }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.data.dashboard') }}">
                            <i class="bi bi-arrow-left"></i> Back to Data Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-calendar-check" style="color: var(--secondary-yellow);"></i>
                Yearly Comparison: {{ $municipality->name }}
            </h1>
            <p class="text-muted">View and compare historical data across different years</p>
        </div>

        @if(empty($years))
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No yearly data available yet. Start by updating municipality data for different years.
            </div>
        @else
            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <a href="{{ route('admin.yearly.compare') }}" class="action-btn w-100 text-center">
                        <i class="bi bi-bar-chart"></i> Compare Multiple Years
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('admin.data.municipality') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-pencil"></i> Update Current Year Data
                    </a>
                </div>
            </div>

            <!-- Population Trend Chart -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Population Trend</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="populationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Year Cards -->
            <h4 class="mb-3" style="color: var(--primary-blue);">Available Years</h4>
            <div class="row">
                @foreach($years as $year)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('admin.yearly.view', $year) }}" class="year-card">
                        <div class="year-badge">{{ $year }}</div>
                        
                        <div class="stat-row">
                            <span>Population</span>
                            <strong>{{ number_format($yearlyData[$year]['total_population']) }}</strong>
                        </div>
                        
                        <div class="stat-row">
                            <span>Households</span>
                            <strong>{{ number_format($yearlyData[$year]['total_households']) }}</strong>
                        </div>
                        
                        @if(isset($trends[$year]))
                        <div class="stat-row mt-2">
                            <span>vs Previous Year</span>
                            <span class="{{ $trends[$year]['population_change'] >= 0 ? 'trend-up' : 'trend-down' }}">
                                <i class="bi bi-arrow-{{ $trends[$year]['population_change'] >= 0 ? 'up' : 'down' }}"></i>
                                {{ $trends[$year]['population_percent'] }}%
                            </span>
                        </div>
                        @endif
                        
                        <div class="mt-3 text-center">
                            <span class="badge bg-primary">View Details</span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const years = {!! json_encode(array_keys($yearlyData)) !!};
            const populations = {!! json_encode(array_column($yearlyData, 'total_population')) !!};
            
            new Chart(document.getElementById('populationChart'), {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [{
                        label: 'Population',
                        data: populations,
                        borderColor: '#2C3E8F',
                        backgroundColor: 'rgba(44, 62, 143, 0.1)',
                        tension: 0.1,
                        fill: true
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