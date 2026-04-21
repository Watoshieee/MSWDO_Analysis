<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Barangays - {{ $municipality->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

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

        .navbar-brand {
            color: white !important;
            font-weight: 700;
        }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }

        .barangay-badge {
            background: var(--bg-light);
            color: var(--primary-blue);
            padding: 8px 15px;
            border-radius: 30px;
            border: 1px solid var(--border-light);
            display: inline-block;
            margin: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-grid-3x3"></i> Barangays of {{ $municipality->name }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">
                            <i class="bi bi-arrow-left"></i> Back to Municipalities
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 style="color: var(--primary-blue);">
                    <i class="bi bi-grid-3x3" style="color: var(--secondary-yellow);"></i>
                    Barangay Management for {{ $municipality->name }}
                </h2>
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

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add New Barangays</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('superadmin.municipalities.barangays.store', $municipality->id) }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">Enter Barangay Names</label>
                                    <textarea name="barangays" class="form-control" rows="8" 
                                              placeholder="Enter one barangay per line&#10;e.g.,&#10;Barangay 1&#10;Barangay 2&#10;Barangay 3" required></textarea>
                                    <small class="text-muted">Each barangay name on a new line</small>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save"></i> Save Barangays
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Existing Barangays ({{ count($barangays) }})</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @if(count($barangays) > 0)
                                <div class="row">
                                    @foreach($barangays as $barangay)
                                        <div class="col-md-6 mb-2">
                                            <span class="barangay-badge">
                                                <i class="bi bi-pin-map-fill" style="color: var(--secondary-yellow);"></i>
                                                {{ $barangay->name }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                    No barangays added yet.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('superadmin.municipalities.index') }}" class="btn btn-secondary">
                    <i class="bi bi-check-circle"></i> Done
                </a>
            </div>
        </div>
    </div>
</body>
</html>