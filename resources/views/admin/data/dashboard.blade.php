<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} Data Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
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

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(44, 62, 143, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--secondary-yellow);
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .menu-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            color: inherit;
            height: 100%;
        }

        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(44, 62, 143, 0.1);
            border-color: var(--primary-blue);
        }

        .menu-icon {
            font-size: 2rem;
            color: var(--secondary-yellow);
            margin-bottom: 10px;
        }

        .menu-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .menu-desc {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .municipality-badge {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-database"></i> {{ $municipality->name }} Data Management
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="color: var(--primary-blue);">
                <i class="bi bi-database" style="color: var(--secondary-yellow);"></i>
                Data Management
            </h1>
            <span class="municipality-badge">
                <i class="bi bi-building"></i> {{ $municipality->name }}
            </span>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-building"></i></div>
                    <div class="stat-value">{{ number_format($municipality->male_population + $municipality->female_population) }}</div>
                    <div class="stat-label">Total Population</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-grid-3x3"></i></div>
                    <div class="stat-value">{{ number_format($barangays) }}</div>
                    <div class="stat-label">Barangays</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-heart"></i></div>
                    <div class="stat-value">{{ number_format($beneficiaries) }}</div>
                    <div class="stat-label">Total Beneficiaries</div>
                </div>
            </div>
        </div>

        <!-- Data Management Menu -->
        <h4 class="mb-3" style="color: var(--primary-blue);">Manage Your Municipality Data</h4>
        
        <div class="row">
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.data.municipality') }}" class="menu-card">
                    <div class="menu-icon"><i class="bi bi-building"></i></div>
                    <div class="menu-title">Municipality Profile</div>
                    <div class="menu-desc">Update population, households, and demographic data</div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.data.barangays') }}" class="menu-card">
                    <div class="menu-icon"><i class="bi bi-grid-3x3"></i></div>
                    <div class="menu-title">Barangays</div>
                    <div class="menu-desc">Manage data for all barangays in {{ $municipality->name }}</div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="{{ route('admin.data.programs') }}" class="menu-card">
                    <div class="menu-icon"><i class="bi bi-heart"></i></div>
                    <div class="menu-title">Programs</div>
                    <div class="menu-desc">Manage social welfare program beneficiaries</div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>