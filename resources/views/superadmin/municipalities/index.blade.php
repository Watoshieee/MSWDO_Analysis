<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipalities Management - Super Admin</title>
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

        .table-container {
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

        .btn-action {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 8px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building"></i> Super Admin Panel
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
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
                    <i class="bi bi-building" style="color: var(--secondary-yellow);"></i>
                    Municipalities Management
                </h1>
                <a href="{{ route('superadmin.municipalities.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Municipality
                </a>
            </div>
            <p class="text-muted mt-2">Manage all municipalities in Laguna province</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Municipality</th>
                        <th>Population</th>
                        <th>Households</th>
                        <th>Single Parents</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($municipalities as $municipality)
                    <tr>
                        <td>{{ $municipality->id }}</td>
                        <td><strong>{{ $municipality->name }}</strong></td>
                        <td>{{ number_format($municipality->male_population + $municipality->female_population) }}</td>
                        <td>{{ number_format($municipality->total_households) }}</td>
                        <td>{{ number_format($municipality->single_parent_count) }}</td>
                        <td>{{ $municipality->year ?? date('Y') }}</td>
                        <td>
                            <a href="{{ route('superadmin.municipalities.barangays', $municipality->id) }}" 
                               class="btn btn-info btn-action" title="Manage Barangays">
                                <i class="bi bi-grid-3x3"></i>
                            </a>
                            <a href="{{ route('superadmin.municipalities.edit', $municipality->id) }}" 
                               class="btn btn-warning btn-action" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('superadmin.municipalities.delete', $municipality->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-action" 
                                        onclick="return confirm('Are you sure you want to delete this municipality?')"
                                        title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $municipalities->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>