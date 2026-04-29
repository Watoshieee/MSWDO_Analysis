<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} Profile - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
            --soft-green: #E1F7E1;
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

        .data-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }

        .section-title {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid var(--secondary-yellow);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid var(--border-light);
            padding: 10px 15px;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(44, 62, 143, 0.1);
        }

        .btn-update {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 62, 143, 0.3);
            color: white;
        }

        .total-display {
            background: var(--soft-green);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }

        .total-display h4 {
            color: var(--primary-blue);
            font-size: 1.1rem;
        }

        .total-display p {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
            margin: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building"></i> {{ $municipality->name }} Profile
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
            <i class="bi bi-building" style="color: var(--secondary-yellow);"></i>
            {{ $municipality->name }} Municipality Profile
        </h1>

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

        <div class="data-card">
            <div class="total-display">
                <h4><i class="bi bi-people-fill"></i> Total Population</h4>
                <p id="totalPopulation">{{ number_format($municipality->male_population + $municipality->female_population) }}</p>
            </div>

            <form method="POST" action="{{ route('admin.data.municipality.update') }}">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Male Population</label>
                        <input type="number" name="male_population" class="form-control population-input" 
                               value="{{ $municipality->male_population }}" required min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Female Population</label>
                        <input type="number" name="female_population" class="form-control population-input" 
                               value="{{ $municipality->female_population }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 0-19</label>
                        <input type="number" name="population_0_19" class="form-control" 
                               value="{{ $municipality->population_0_19 }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 20-50</label>
                        <input type="number" name="population_20_50" class="form-control" 
                               value="{{ $municipality->population_20_50 }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Population 51-100</label>
                        <input type="number" name="population_51_100" class="form-control" 
                               value="{{ $municipality->population_51_100 }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Households</label>
                        <input type="number" name="total_households" class="form-control" 
                               value="{{ $municipality->total_households }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Single Parents</label>
                        <input type="number" name="single_parent_count" class="form-control" 
                               value="{{ $municipality->single_parent_count }}" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
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

                <button type="submit" class="btn-update">
                    <i class="bi bi-save"></i> Update Municipality Data
                </button>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.population-input').forEach(input => {
            input.addEventListener('input', function() {
                const male = document.querySelector('input[name="male_population"]').value || 0;
                const female = document.querySelector('input[name="female_population"]').value || 0;
                document.getElementById('totalPopulation').innerText = (parseInt(male) + parseInt(female)).toLocaleString();
            });
        });
    </script>
</body>
</html>