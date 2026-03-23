<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Municipality - {{ $municipality->name }}</title>
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

        .navbar-brand {
            color: white !important;
            font-weight: 700;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building"></i> Edit {{ $municipality->name }}
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="form-card">
            <h2 class="mb-4" style="color: var(--primary-blue);">
                <i class="bi bi-pencil-square" style="color: var(--secondary-yellow);"></i>
                Edit Municipality: {{ $municipality->name }}
            </h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('superadmin.municipalities.update', $municipality->id) }}">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Municipality Name</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name', $municipality->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select" required>
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $year)
                                <option value="{{ $year }}" {{ ($municipality->year ?? date('Y')) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Male Population</label>
                        <input type="number" name="male_population" class="form-control" 
                               value="{{ old('male_population', $municipality->male_population) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Female Population</label>
                        <input type="number" name="female_population" class="form-control" 
                               value="{{ old('female_population', $municipality->female_population) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Households</label>
                        <input type="number" name="total_households" class="form-control" 
                               value="{{ old('total_households', $municipality->total_households) }}" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 0-19</label>
                        <input type="number" name="population_0_19" class="form-control" 
                               value="{{ old('population_0_19', $municipality->population_0_19) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 20-59</label>
                        <input type="number" name="population_20_59" class="form-control" 
                               value="{{ old('population_20_59', $municipality->population_20_59) }}" min="0" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 60-100</label>
                        <input type="number" name="population_60_100" class="form-control" 
                               value="{{ old('population_60_100', $municipality->population_60_100) }}" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Single Parents</label>
                        <input type="number" name="single_parent_count" class="form-control" 
                               value="{{ old('single_parent_count', $municipality->single_parent_count) }}" min="0" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Municipality
                    </button>
                    <a href="{{ route('superadmin.municipalities.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>