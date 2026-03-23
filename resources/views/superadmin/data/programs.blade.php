<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Data - Super Admin</title>
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

        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
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

        .btn-add {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(253, 185, 19, 0.3);
        }

        .btn-edit {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            font-weight: 600;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 5px 15px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-heart"></i> Program Data Management
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">
                            <i class="bi bi-arrow-left"></i> Back to Data Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="color: var(--primary-blue);">
                <i class="bi bi-heart" style="color: var(--secondary-yellow);"></i>
                Program Data Management
            </h1>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-circle"></i> Add New Program Data
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Filter -->
        <div class="filter-card">
            <form method="GET" action="{{ route('superadmin.data.programs') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Municipality</label>
                        <select name="municipality" class="form-select">
                            <option value="">All</option>
                            @foreach($municipalities as $municipality)
                                <option value="{{ $municipality }}" {{ request('municipality') == $municipality ? 'selected' : '' }}>
                                    {{ $municipality }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Program Type</label>
                        <select name="program_type" class="form-select">
                            <option value="">All</option>
                            @foreach($programTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('program_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select">
                            <option value="">All</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Programs Table -->
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Municipality</th>
                        <th>Program</th>
                        <th>Beneficiaries</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programs as $program)
                    <tr>
                        <td>{{ $program->id }}</td>
                        <td>{{ $program->municipality }}</td>
                        <td>{{ str_replace('_', ' ', $program->program_type) }}</td>
                        <td>{{ number_format($program->beneficiary_count) }}</td>
                        <td>{{ $program->year }}</td>
                        <td>
                            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $program->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn-delete" onclick="deleteProgram({{ $program->id }})">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $program->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Edit Program Data</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('superadmin.data.programs.update', $program->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Municipality</label>
                                            <input type="text" class="form-control" value="{{ $program->municipality }}" readonly disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Program Type</label>
                                            <input type="text" class="form-control" value="{{ str_replace('_', ' ', $program->program_type) }}" readonly disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Beneficiary Count</label>
                                            <input type="number" name="beneficiary_count" class="form-control" 
                                                   value="{{ $program->beneficiary_count }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Year</label>
                                            <select name="year" class="form-select" required>
                                                @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                                    <option value="{{ $yearOption }}" {{ $program->year == $yearOption ? 'selected' : '' }}>
                                                        {{ $yearOption }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $programs->links() }}
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add New Program Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('superadmin.data.programs.create') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Municipality</label>
                            <select name="municipality" class="form-select" required>
                                <option value="">Select Municipality</option>
                                @foreach($municipalities as $municipality)
                                    <option value="{{ $municipality }}">{{ $municipality }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program Type</label>
                            <select name="program_type" class="form-select" required>
                                <option value="">Select Program</option>
                                @foreach($programTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Beneficiary Count</label>
                            <input type="number" name="beneficiary_count" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" required>
                                <option value="">Select Year</option>
                                @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                    <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProgram(id) {
            if (confirm('Are you sure you want to delete this program data?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/superadmin/data/programs/' + id;
                
                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                
                const method = document.createElement('input');
                method.name = '_method';
                method.value = 'DELETE';
                
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>