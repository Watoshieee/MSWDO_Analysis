<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Data - {{ $municipality->name }}</title>
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

        .btn-edit {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            font-weight: 600;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(253, 185, 19, 0.3);
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-grid-3x3"></i> Barangay Data - {{ $municipality->name }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.data.dashboard') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="mb-4" style="color: var(--primary-blue);">
            <i class="bi bi-grid-3x3" style="color: var(--secondary-yellow);"></i>
            Barangay Data Management
        </h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Barangay</th>
                        <th>Population</th>
                        <th>Households</th>
                        <th>Single Parents</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangays as $barangay)
                    <tr>
                        <td><strong>{{ $barangay->name }}</strong></td>
                        <td>{{ number_format($barangay->male_population + $barangay->female_population) }}</td>
                        <td>{{ number_format($barangay->total_households) }}</td>
                        <td>{{ $barangay->single_parent_count }}</td>
                        <td>{{ $barangay->year ?? 'N/A' }}</td>
                        <td>
                            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $barangay->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $barangay->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Barangay: {{ $barangay->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.data.barangays.update', $barangay->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Male Population</label>
                                                <input type="number" name="male_population" class="form-control" 
                                                       value="{{ $barangay->male_population }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Female Population</label>
                                                <input type="number" name="female_population" class="form-control" 
                                                       value="{{ $barangay->female_population }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Population 0-19</label>
                                                <input type="number" name="population_0_19" class="form-control" 
                                                       value="{{ $barangay->population_0_19 }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Population 20-59</label>
                                                <input type="number" name="population_20_59" class="form-control" 
                                                       value="{{ $barangay->population_20_59 }}" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Population 60-100</label>
                                                <input type="number" name="population_60_100" class="form-control" 
                                                       value="{{ $barangay->population_60_100 }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Total Households</label>
                                                <input type="number" name="total_households" class="form-control" 
                                                       value="{{ $barangay->total_households }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Single Parents</label>
                                                <input type="number" name="single_parent_count" class="form-control" 
                                                       value="{{ $barangay->single_parent_count }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Year</label>
                                                <select name="year" class="form-select" required>
                                                    @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                                        <option value="{{ $yearOption }}" {{ $barangay->year == $yearOption ? 'selected' : '' }}>
                                                            {{ $yearOption }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Barangay</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $barangays->links() }}
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>