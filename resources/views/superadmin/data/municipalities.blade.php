<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipality Data - Super Admin</title>
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
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .data-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .municipality-title {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-yellow);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-label {
            background: #E5EEFF;
            color: var(--primary-blue);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            margin: 15px 0 10px 0;
            display: inline-block;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border-light);
            padding: 10px 15px;
        }

        .form-control:focus, .form-select:focus {
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
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        .total-display h4 {
            color: var(--primary-blue);
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .total-display p {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
            margin: 0;
        }

        .stat-badge {
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
                <i class="bi bi-building"></i> Municipality Data Management
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 style="color: var(--primary-blue);">
                <i class="bi bi-building" style="color: var(--secondary-yellow);"></i>
                Municipality Data
            </h1>
            <span class="stat-badge">{{ $municipalities->count() }} Municipalities</span>
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

        @foreach($municipalities as $municipality)
        <div class="data-card">
            <div class="municipality-title">
                <i class="bi bi-building"></i>
                <h3 class="mb-0">{{ $municipality->name }} Municipality</h3>
            </div>
            
            <form method="POST" action="{{ route('superadmin.data.municipalities.update', $municipality->id) }}">
                @csrf
                
                <div class="total-display">
                    <h4><i class="bi bi-people-fill"></i> Total Population</h4>
                    <p id="total-population-{{ $municipality->id }}">
                        {{ number_format($municipality->male_population + $municipality->female_population) }}
                    </p>
                </div>

                <div class="section-label">
                    <i class="bi bi-people"></i> Population by Gender
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Male Population</label>
                        <input type="number" name="male_population" class="form-control population-input" 
                               data-municipality="{{ $municipality->id }}"
                               value="{{ old('male_population', $municipality->male_population) }}" required min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Female Population</label>
                        <input type="number" name="female_population" class="form-control population-input" 
                               data-municipality="{{ $municipality->id }}"
                               value="{{ old('female_population', $municipality->female_population) }}" required min="0">
                    </div>
                </div>

                <div class="section-label">
                    <i class="bi bi-graph-up"></i> Population by Age Group
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Youth (0-19 years)</label>
                        <input type="number" name="population_0_19" class="form-control" 
                               value="{{ old('population_0_19', $municipality->population_0_19) }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Adult (20-59 years)</label>
                        <input type="number" name="population_20_59" class="form-control" 
                               value="{{ old('population_20_59', $municipality->population_20_59) }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Senior (60-100 years)</label>
                        <input type="number" name="population_60_100" class="form-control" 
                               value="{{ old('population_60_100', $municipality->population_60_100) }}" required min="0">
                    </div>
                </div>

                <div class="section-label">
                    <i class="bi bi-house-door"></i> Households & Demographics
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Total Households</label>
                        <input type="number" name="total_households" class="form-control" 
                               value="{{ old('total_households', $municipality->total_households) }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Single Parents</label>
                        <input type="number" name="single_parent_count" class="form-control" 
                               value="{{ old('single_parent_count', $municipality->single_parent_count) }}" required min="0">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select" required>
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                <option value="{{ $yearOption }}" {{ ($municipality->year ?? date('Y')) == $yearOption ? 'selected' : '' }}>
                                    {{ $yearOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn-update">
                    <i class="bi bi-save"></i> Update {{ $municipality->name }} Data
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <script>
        document.querySelectorAll('.population-input').forEach(input => {
            input.addEventListener('input', function() {
                const municipalityId = this.dataset.municipality;
                const male = document.querySelector(`input[name="male_population"][data-municipality="${municipalityId}"]`).value || 0;
                const female = document.querySelector(`input[name="female_population"][data-municipality="${municipalityId}"]`).value || 0;
                const total = parseInt(male) + parseInt(female);
                document.getElementById(`total-population-${municipalityId}`).innerText = total.toLocaleString();
            });
        });
    </script>
</body>
</html>