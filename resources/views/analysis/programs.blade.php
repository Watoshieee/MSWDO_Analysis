<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Beneficiaries Comparison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .highest-badge {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        .lowest-badge {
            background-color: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
        }
        .positive-diff {
            color: #28a745;
            font-weight: bold;
        }
        .negative-diff {
            color: #dc3545;
            font-weight: bold;
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
                        <a class="nav-link active" href="/analysis/programs">Programs</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4">Program Beneficiaries: Comparative Analysis</h1>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('analysis.programs') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Program Type</label>
                    <select name="program_type" class="form-select">
                        <option value="">All Programs</option>
                        @foreach($programTypes as $type)
                            <option value="{{ $type }}" {{ request('program_type') == $type ? 'selected' : '' }}>
                                {{ str_replace('_', ' ', $type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Year</label>
                    <select name="year" class="form-select">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Municipality</label>
                    <select name="municipality" class="form-select">
                        <option value="">All Municipalities</option>
                        @foreach($municipalities as $m)
                            <option value="{{ $m }}" {{ request('municipality') == $m ? 'selected' : '' }}>
                                {{ $m }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Beneficiaries</h5>
                        <h2>{{ number_format($summary['total_beneficiaries']) }}</h2>
                        <small>Across all programs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Magdalena</h5>
                        <h2>{{ number_format($municipalityTotals['Magdalena']) }}</h2>
                        <small>{{ $summary['total_beneficiaries'] > 0 ? round(($municipalityTotals['Magdalena']/$summary['total_beneficiaries'])*100,1) : 0 }}% of total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Liliw</h5>
                        <h2>{{ number_format($municipalityTotals['Liliw']) }}</h2>
                        <small>{{ $summary['total_beneficiaries'] > 0 ? round(($municipalityTotals['Liliw']/$summary['total_beneficiaries'])*100,1) : 0 }}% of total</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Majayjay</h5>
                        <h2>{{ number_format($municipalityTotals['Majayjay']) }}</h2>
                        <small>{{ $summary['total_beneficiaries'] > 0 ? round(($municipalityTotals['Majayjay']/$summary['total_beneficiaries'])*100,1) : 0 }}% of total</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Chart -->
        <div class="card stat-card mb-4">
            <div class="card-header">
                <h5>Program Beneficiaries Comparison Chart</h5>
            </div>
            <div class="card-body">
                <canvas id="comparisonChart" height="100"></canvas>
            </div>
        </div>

        <!-- Comparative Analysis Table -->
        <div class="card stat-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Detailed Program Comparison</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Program Type</th>
                                <th class="text-center">Magdalena</th>
                                <th class="text-center">Liliw</th>
                                <th class="text-center">Majayjay</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Highest</th>
                                <th class="text-center">Average</th>
                                <th>Comparison Insights</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comparativeData as $data)
                            <tr>
                                <td><strong>{{ str_replace('_', ' ', $data['program_type']) }}</strong></td>
                                <td class="text-center {{ $data['magdalena'] == $data['highest'] ? 'table-success' : '' }}">
                                    {{ number_format($data['magdalena']) }}
                                    @if($data['magdalena'] == $data['highest'] && $data['highest'] > 0)
                                        <span class="highest-badge">Highest</span>
                                    @endif
                                </td>
                                <td class="text-center {{ $data['liliw'] == $data['highest'] ? 'table-success' : '' }}">
                                    {{ number_format($data['liliw']) }}
                                    @if($data['liliw'] == $data['highest'] && $data['highest'] > 0)
                                        <span class="highest-badge">Highest</span>
                                    @endif
                                </td>
                                <td class="text-center {{ $data['majayjay'] == $data['highest'] ? 'table-success' : '' }}">
                                    {{ number_format($data['majayjay']) }}
                                    @if($data['majayjay'] == $data['highest'] && $data['highest'] > 0)
                                        <span class="highest-badge">Highest</span>
                                    @endif
                                </td>
                                <td class="text-center"><strong>{{ number_format($data['total']) }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $highestPerProgram[$data['program_type']] }}</span>
                                    <br>
                                    <small>{{ number_format($data['highest']) }} beneficiaries</small>
                                </td>
                                <td class="text-center">{{ number_format($data['average']) }}</td>
                                <td>
                                    @php
                                        $diff1 = $data['difference']['magdalena_vs_liliw'];
                                        $diff2 = $data['difference']['magdalena_vs_majayjay'];
                                        $diff3 = $data['difference']['liliw_vs_majayjay'];
                                    @endphp
                                    
                                    @if($data['magdalena'] == $data['liliw'] && $data['liliw'] == $data['majayjay'])
                                        <span class="text-muted">All municipalities equal</span>
                                    @else
                                        @if($diff1 != 0)
                                            <span class="{{ $diff1 > 0 ? 'positive-diff' : 'negative-diff' }}">
                                                Magdalena is {{ abs($diff1) }} {{ $diff1 > 0 ? 'higher' : 'lower' }} than Liliw
                                            </span><br>
                                        @endif
                                        
                                        @if($diff2 != 0)
                                            <span class="{{ $diff2 > 0 ? 'positive-diff' : 'negative-diff' }}">
                                                Magdalena is {{ abs($diff2) }} {{ $diff2 > 0 ? 'higher' : 'lower' }} than Majayjay
                                            </span><br>
                                        @endif
                                        
                                        @if($diff3 != 0)
                                            <span class="{{ $diff3 > 0 ? 'positive-diff' : 'negative-diff' }}">
                                                Liliw is {{ abs($diff3) }} {{ $diff3 > 0 ? 'higher' : 'lower' }} than Majayjay
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Insights -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card stat-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Key Insights</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Overall Comparison</h6>
                                <ul>
                                    @php
                                        $overallHighest = array_keys($municipalityTotals, max($municipalityTotals))[0];
                                        $overallLowest = array_keys($municipalityTotals, min($municipalityTotals))[0];
                                    @endphp
                                    <li><strong>{{ $overallHighest }}</strong> has the most beneficiaries overall with <strong>{{ number_format(max($municipalityTotals)) }}</strong> beneficiaries</li>
                                    <li><strong>{{ $overallLowest }}</strong> has the least beneficiaries with <strong>{{ number_format(min($municipalityTotals)) }}</strong> beneficiaries</li>
                                    <li>Total difference between highest and lowest: <strong>{{ number_format(max($municipalityTotals) - min($municipalityTotals)) }}</strong></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Program Highlights</h6>
                                <ul>
                                    @foreach($highestPerProgram as $program => $municipality)
                                        <li><strong>{{ str_replace('_', ' ', $program) }}</strong>: Mostly served by {{ $municipality }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare data for chart
            const programLabels = {!! json_encode(array_keys($comparativeData)) !!};
            const magdalenaData = {!! json_encode(array_column($comparativeData, 'magdalena')) !!};
            const liliwData = {!! json_encode(array_column($comparativeData, 'liliw')) !!};
            const majayjayData = {!! json_encode(array_column($comparativeData, 'majayjay')) !!};
            
            // Format labels
            const formattedLabels = programLabels.map(label => label.replace(/_/g, ' '));
            
            new Chart(document.getElementById('comparisonChart'), {
                type: 'bar',
                data: {
                    labels: formattedLabels,
                    datasets: [
                        {
                            label: 'Magdalena',
                            data: magdalenaData,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)',
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Liliw',
                            data: liliwData,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)',
                            borderColor: 'rgba(255, 193, 7, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Majayjay',
                            data: majayjayData,
                            backgroundColor: 'rgba(23, 162, 184, 0.7)',
                            borderColor: 'rgba(23, 162, 184, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Beneficiaries'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Program Beneficiaries by Municipality'
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>