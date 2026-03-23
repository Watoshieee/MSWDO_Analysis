<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} - Barangay Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }
        .barangay-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .barangay-row:hover {
            background-color: #f5f5f5;
        }
        .progress {
            height: 8px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-bar-chart-line"></i> MSWDO Analysis
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis/demographic">Demographic</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/analysis/programs">Programs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h1>
                    <i class="bi bi-building"></i> 
                    {{ $municipality->name }} - Barangay Level Analysis
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/analysis">Home</a></li>
                        <li class="breadcrumb-item active">{{ $municipality->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Municipality Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <h6>Total Population</h6>
                        <h3>{{ number_format($municipality->male_population + $municipality->female_population) }}</h3>
                        <small>{{ $barangays->count() }} Barangays</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <h6>Total Households</h6>
                        <h3>{{ number_format($municipality->total_households) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <h6>Single Parents</h6>
                        <h3>{{ number_format($municipality->single_parent_count) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <h6>Approved Apps</h6>
                        <h3>{{ number_format($barangays->sum('total_approved_applications')) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-header">
                        <h5>Top 10 Barangays by Population</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="topBarangaysChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-header">
                        <h5>Population Distribution by Age Group</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="ageDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-header">
                        <h5>Gender Distribution per Barangay</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stat-card">
                    <div class="card-header">
                        <h5>Single Parents per Barangay</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="singleParentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barangay List with Details -->
        <div class="card stat-card">
            <div class="card-header">
                <h5>Detailed Barangay Information</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Barangay</th>
                                <th>Population</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Households</th>
                                <th>Single Parents</th>
                                <th>Approved Apps</th>
                                <th>Age Distribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangayData as $barangay => $data)
                            <tr class="barangay-row" onclick="showBarangayDetails('{{ $barangay }}')">
                                <td><strong>{{ $barangay }}</strong></td>
                                <td>{{ number_format($data['population']) }}</td>
                                <td>{{ number_format($data['male']) }}</td>
                                <td>{{ number_format($data['female']) }}</td>
                                <td>{{ number_format($data['households']) }}</td>
                                <td>{{ number_format($data['single_parents']) }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $data['approved_apps'] }}</span>
                                </td>
                                <td style="min-width: 200px;">
                                    <div class="progress">
                                        @php
                                            $total = $data['age_0_19'] + $data['age_20_59'] + $data['age_60_100'];
                                            $pct0_19 = $total > 0 ? ($data['age_0_19'] / $total) * 100 : 0;
                                            $pct20_59 = $total > 0 ? ($data['age_20_59'] / $total) * 100 : 0;
                                            $pct60_100 = $total > 0 ? ($data['age_60_100'] / $total) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-info" style="width: {{ $pct0_19 }}%" 
                                             title="0-19: {{ number_format($data['age_0_19']) }}"></div>
                                        <div class="progress-bar bg-success" style="width: {{ $pct20_59 }}%"
                                             title="20-59: {{ number_format($data['age_20_59']) }}"></div>
                                        <div class="progress-bar bg-warning" style="width: {{ $pct60_100 }}%"
                                             title="60-100: {{ number_format($data['age_60_100']) }}"></div>
                                    </div>
                                    <small class="text-muted">
                                        0-19: {{ number_format($data['age_0_19']) }} | 
                                        20-59: {{ number_format($data['age_20_59']) }} | 
                                        60+: {{ number_format($data['age_60_100']) }}
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Program Distribution -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card stat-card">
                    <div class="card-header">
                        <h5>Social Welfare Programs in {{ $municipality->name }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($programs->groupBy('program_type') as $type => $programsByType)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>{{ str_replace('_', ' ', $type) }}</h6>
                                        <h4>{{ number_format($programsByType->sum('beneficiary_count')) }}</h4>
                                        <small class="text-muted">Beneficiaries</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barangay Details Modal -->
    <div class="modal fade" id="barangayModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Barangay Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare data for charts
            const barangayNames = {!! json_encode(array_keys($barangayData)) !!};
            const populations = {!! json_encode(array_column($barangayData, 'population')) !!};
            const males = {!! json_encode(array_column($barangayData, 'male')) !!};
            const females = {!! json_encode(array_column($barangayData, 'female')) !!};
            const singleParents = {!! json_encode(array_column($barangayData, 'single_parents')) !!};
            const age0_19 = {!! json_encode(array_column($barangayData, 'age_0_19')) !!};
            const age20_59 = {!! json_encode(array_column($barangayData, 'age_20_59')) !!};
            const age60_100 = {!! json_encode(array_column($barangayData, 'age_60_100')) !!};

            // Top 10 Barangays Chart
            const top10Data = barangayNames.slice(0, 10).map((name, index) => ({
                name,
                population: populations[index]
            }));

            new Chart(document.getElementById('topBarangaysChart'), {
                type: 'bar',
                data: {
                    labels: top10Data.map(d => d.name),
                    datasets: [{
                        label: 'Population',
                        data: top10Data.map(d => d.population),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true }
                    }
                }
            });

            // Age Distribution Chart (Pie)
            const totalAge0_19 = age0_19.reduce((a, b) => a + b, 0);
            const totalAge20_59 = age20_59.reduce((a, b) => a + b, 0);
            const totalAge60_100 = age60_100.reduce((a, b) => a + b, 0);

            new Chart(document.getElementById('ageDistributionChart'), {
                type: 'pie',
                data: {
                    labels: ['0-19 years', '20-59 years', '60-100 years'],
                    datasets: [{
                        data: [totalAge0_19, totalAge20_59, totalAge60_100],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 206, 86, 0.7)'
                        ]
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

            // Gender Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'bar',
                data: {
                    labels: barangayNames.slice(0, 8),
                    datasets: [{
                        label: 'Male',
                        data: males.slice(0, 8),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }, {
                        label: 'Female',
                        data: females.slice(0, 8),
                        backgroundColor: 'rgba(255, 99, 132, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Single Parents Chart
            new Chart(document.getElementById('singleParentsChart'), {
                type: 'line',
                data: {
                    labels: barangayNames.slice(0, 8),
                    datasets: [{
                        label: 'Single Parents',
                        data: singleParents.slice(0, 8),
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        });

        function showBarangayDetails(barangayName) {
            const data = {!! json_encode($barangayData) !!}[barangayName];
            const modal = new bootstrap.Modal(document.getElementById('barangayModal'));
            
            document.getElementById('modalTitle').textContent = `${barangayName} - Detailed Analysis`;
            
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6>Demographics</h6>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Total Population:</th>
                                        <td>${data.population.toLocaleString()}</td>
                                    </tr>
                                    <tr>
                                        <th>Male:</th>
                                        <td>${data.male.toLocaleString()} (${((data.male/data.population)*100).toFixed(1)}%)</td>
                                    </tr>
                                    <tr>
                                        <th>Female:</th>
                                        <td>${data.female.toLocaleString()} (${((data.female/data.population)*100).toFixed(1)}%)</td>
                                    </tr>
                                    <tr>
                                        <th>Total Households:</th>
                                        <td>${data.households.toLocaleString()}</td>
                                    </tr>
                                    <tr>
                                        <th>Single Parents:</th>
                                        <td>${data.single_parents.toLocaleString()}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-success text-white">
                                <h6>Age Distribution</h6>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>0-19 years:</th>
                                        <td>${data.age_0_19.toLocaleString()} (${((data.age_0_19/data.population)*100).toFixed(1)}%)</td>
                                    </tr>
                                    <tr>
                                        <th>20-59 years:</th>
                                        <td>${data.age_20_59.toLocaleString()} (${((data.age_20_59/data.population)*100).toFixed(1)}%)</td>
                                    </tr>
                                    <tr>
                                        <th>60-100 years:</th>
                                        <td>${data.age_60_100.toLocaleString()} (${((data.age_60_100/data.population)*100).toFixed(1)}%)</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6>Applications</h6>
                            </div>
                            <div class="card-body">
                                <h4 class="text-center">${data.approved_apps} Approved Applications</h4>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: ${(data.approved_apps/data.households)*100}%">
                                        ${((data.approved_apps/data.households)*100).toFixed(1)}% of households
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('modalBody').innerHTML = html;
            modal.show();
        }
    </script>
</body>
</html>