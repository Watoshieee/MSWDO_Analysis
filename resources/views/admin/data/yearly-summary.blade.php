<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Summary - {{ $municipality->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
        }
        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .navbar {
            background: var(--primary-gradient) !important;
        }
        .summary-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-calendar-check"></i> Yearly Summary - {{ $municipality->name }}
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4" style="color: var(--primary-blue);">
            <i class="bi bi-calendar-check" style="color: var(--secondary-yellow);"></i>
            Yearly Summary Records
        </h1>

        <div class="summary-card">
            <table class="table">
                <thead>
                    <tr>
                        <th>Year</th>
                        <th>Total Population</th>
                        <th>Total Households</th>
                        <th>4Ps</th>
                        <th>PWD</th>
                        <th>Senior</th>
                        <th>AICS</th>
                        <th>Solo Parent</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summaries as $summary)
                    <tr>
                        <td>{{ $summary->year }}</td>
                        <td>{{ number_format($summary->total_population) }}</td>
                        <td>{{ number_format($summary->total_households) }}</td>
                        <td>{{ number_format($summary->total_4ps) }}</td>
                        <td>{{ number_format($summary->total_pwd) }}</td>
                        <td>{{ number_format($summary->total_senior) }}</td>
                        <td>{{ number_format($summary->total_aics) }}</td>
                        <td>{{ number_format($summary->total_solo_parent) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>