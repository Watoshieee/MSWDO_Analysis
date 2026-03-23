<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Years - {{ $municipality->name }}</title>
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

        .compare-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin: 30px 0;
        }

        .comparison-table th {
            background: var(--primary-gradient);
            color: white;
            font-weight: 600;
        }

        .year-checkbox {
            margin-right: 15px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-bar-chart"></i> Compare Years - {{ $municipality->name }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.yearly.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4" style="color: var(--primary-blue);">
            <i class="bi bi-bar-chart" style="color: var(--secondary-yellow);"></i>
            Compare Years
        </h1>

        <div class="compare-card">
            <form method="GET" action="{{ route('admin.yearly.compare') }}" class="mb-4">
                <h5 class="mb-3">Select Years to Compare:</h5>
                <div class="row">
                    @foreach($allYears as $year)
                    <div class="col-md-2">
                        <div class="form-check year-checkbox">
                            <input class="form-check-input" type="checkbox" name="years[]" 
                                   value="{{ $year }}" id="year{{ $year }}"
                                   {{ in_array($year, $selectedYears) ? 'checked' : '' }}>
                            <label class="form-check-label" for="year{{ $year }}">
                                {{ $year }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bi bi-filter"></i> Compare Selected Years
                </button>
            </form>

            @if(!empty($comparisonData))
                <!-- Comparison Chart -->
                <div class="chart-container">
                    <canvas id="comparisonChart"></canvas>
                </div>

                <!-- Comparison Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-bordered comparison-table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                @foreach(array_keys($comparisonData) as $year)
                                    <th class="text-center">{{ $year }}</th>
                                @endforeach
                                <th class="text-center">Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Population</strong></td>
                                @php $first = null; @endphp
                                @foreach($comparisonData as $year => $data)
                                    <td class="text-center">{{ number_format($data['total_population']) }}</td>
                                    @if($first === null) @php $first = $data['total_population']; @endphp @endif
                                @endforeach
                                <td class="text-center">
                                    @php $last = end($comparisonData)['total_population']; @endphp
                                    <span class="{{ $last >= $first ? 'text-success' : 'text-danger' }}">
                                        {{ $last >= $first ? '+' : '' }}{{ number_format($last - $first) }}
                                        ({{ $first > 0 ? round(($last - $first) / $first * 100, 1) : 0 }}%)
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Households</strong></td>
                                @php $first = null; @endphp
                                @foreach($comparisonData as $year => $data)
                                    <td class="text-center">{{ number_format($data['total_households']) }}</td>
                                    @if($first === null) @php $first = $data['total_households']; @endphp @endif
                                @endforeach
                                <td class="text-center">
                                    @php $last = end($comparisonData)['total_households']; @endphp
                                    <span class="{{ $last >= $first ? 'text-success' : 'text-danger' }}">
                                        {{ $last >= $first ? '+' : '' }}{{ number_format($last - $first) }}
                                        ({{ $first > 0 ? round(($last - $first) / $first * 100, 1) : 0 }}%)
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>4Ps</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_4ps']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>PWD</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_pwd']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>Senior</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_senior']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>AICS</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_aics']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>ESA</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_esa']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>SLP</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_slp']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>Solo Parent</strong></td>
                                @foreach($comparisonData as $data)
                                    <td class="text-center">{{ number_format($data['total_solo_parent']) }}</td>
                                @endforeach
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const years = {!! json_encode(array_keys($comparisonData)) !!};
            const datasets = [
                {
                    label: 'Population',
                    data: {!! json_encode(array_column($comparisonData, 'total_population')) !!},
                    backgroundColor: '#2C3E8F',
                },
                {
                    label: 'Households',
                    data: {!! json_encode(array_column($comparisonData, 'total_households')) !!},
                    backgroundColor: '#FDB913',
                }
            ];

            new Chart(document.getElementById('comparisonChart'), {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
    </script>
</body>
</html>