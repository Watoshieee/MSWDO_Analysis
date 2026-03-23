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
            --soft-red: #FCE8E8;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 20px rgba(44, 62, 143, 0.15);
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .page-header {
            margin-bottom: 30px;
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

        .page-title i {
            color: var(--secondary-yellow);
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

        .municipality-title i {
            color: var(--secondary-yellow);
            font-size: 1.5rem;
        }

        .section-label {
            background: var(--primary-blue-light);
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
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(44, 62, 143, 0.1);
        }

        .stat-badge {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            padding: 8px 20px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-block;
        }

        .btn-update {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
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
            margin-top: 20px;
        }

        .total-display h4 {
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 5px;
        }

        .total-display p {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
            margin: 0;
        }

        .info-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
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
                            <i class="bi bi-arrow-left"></i> Back to Data Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('superadmin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Main Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <h1 class="page-title">
                <i class="bi bi-building"></i>
                Municipality Data Management
            </h1>
            <p class="text-muted">Update population and demographic data for each municipality</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:
                <ul class="mt-2 mb-0">
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
                <span class="stat-badge ms-auto">ID: {{ $municipality->id }}</span>
            </div>
            
            <form method="POST" action="{{ route('superadmin.data.municipalities.update', $municipality->id) }}">
                @csrf
                
                <!-- Total Population Summary -->
                <div class="total-display mb-4">
                    <h4><i class="bi bi-people-fill"></i> Total Population</h4>
                    <p id="total-population-{{ $municipality->id }}">
                        {{ number_format($municipality->male_population + $municipality->female_population) }}
                    </p>
                    <small class="text-muted">Auto-calculated from Male + Female population</small>
                </div>

                <!-- Population by Gender -->
                <div class="section-label">
                    <i class="bi bi-people"></i> Population by Gender
                </div>
                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Male Population</label>
                        <input type="number" name="male_population" class="form-control population-input" 
                               data-municipality="{{ $municipality->id }}"
                               value="{{ old('male_population', $municipality->male_population) }}" 
                               required min="0" step="1">
                        <div class="info-text">Number of male residents</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Female Population</label>
                        <input type="number" name="female_population" class="form-control population-input" 
                               data-municipality="{{ $municipality->id }}"
                               value="{{ old('female_population', $municipality->female_population) }}" 
                               required min="0" step="1">
                        <div class="info-text">Number of female residents</div>
                    </div>
                </div>

                <!-- Population by Age Group -->
                <div class="section-label">
                    <i class="bi bi-graph-up"></i> Population by Age Group
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Youth (0-19 years)</label>
                        <input type="number" name="population_0_19" class="form-control" 
                               value="{{ old('population_0_19', $municipality->population_0_19) }}" 
                               required min="0" step="1">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Adult (20-50 years)</label>
                        <input type="number" name="population_20_50" class="form-control" 
                               value="{{ old('population_20_50', $municipality->population_20_50) }}" 
                               required min="0" step="1">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Senior (51-100 years)</label>
                        <input type="number" name="population_51_100" class="form-control" 
                               value="{{ old('population_51_100', $municipality->population_51_100) }}" 
                               required min="0" step="1">
                    </div>
                </div>

                <!-- Households and Demographics -->
                <div class="section-label">
                    <i class="bi bi-house-door"></i> Households & Demographics
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Households</label>
                        <input type="number" name="total_households" class="form-control" 
                               value="{{ old('total_households', $municipality->total_households) }}" 
                               required min="0" step="1">
                        <div class="info-text">Number of households in the municipality</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Single Parents</label>
                        <input type="number" name="single_parent_count" class="form-control" 
                               value="{{ old('single_parent_count', $municipality->single_parent_count) }}" 
                               required min="0" step="1">
                        <div class="info-text">Number of single parents</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Data Year</label>
                        <select name="year" class="form-select" required>
                            @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                <option value="{{ $yearOption }}" {{ ($municipality->year ?? date('Y')) == $yearOption ? 'selected' : '' }}>
                                    {{ $yearOption }}
                                </option>
                            @endforeach
                        </select>
                        <div class="info-text">Year of data collection</div>
                    </div>
                </div>

                <!-- Validation Summary -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill"></i>
                            <strong>Data Validation:</strong> 
                            <span id="validation-message-{{ $municipality->id }}">
                                Age groups sum should equal total population
                            </span>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-update">
                    <i class="bi bi-save"></i> Update {{ $municipality->name }} Data
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-calculate total population when male/female inputs change
        document.querySelectorAll('.population-input').forEach(input => {
            input.addEventListener('input', function() {
                const municipalityId = this.dataset.municipality;
                const maleInput = document.querySelector(`input[name="male_population"][data-municipality="${municipalityId}"]`);
                const femaleInput = document.querySelector(`input[name="female_population"][data-municipality="${municipalityId}"]`);
                const totalDisplay = document.getElementById(`total-population-${municipalityId}`);
                
                const male = parseInt(maleInput.value) || 0;
                const female = parseInt(femaleInput.value) || 0;
                const total = male + female;
                
                totalDisplay.textContent = total.toLocaleString();
            });
        });

        // Validate age groups sum
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const municipalityId = this.querySelector('.population-input')?.dataset.municipality;
                if (!municipalityId) return;
                
                const male = parseInt(this.querySelector('input[name="male_population"]').value) || 0;
                const female = parseInt(this.querySelector('input[name="female_population"]').value) || 0;
                const age0_19 = parseInt(this.querySelector('input[name="population_0_19"]').value) || 0;
                const age20_50 = parseInt(this.querySelector('input[name="population_20_50"]').value) || 0;
                const age51_100 = parseInt(this.querySelector('input[name="population_51_100"]').value) || 0;
                
                const totalPop = male + female;
                const ageSum = age0_19 + age20_50 + age51_100;
                
                if (totalPop !== ageSum) {
                    e.preventDefault();
                    alert(`Warning: Age group sum (${ageSum.toLocaleString()}) does not equal total population (${totalPop.toLocaleString()}). Please check your numbers.`);
                }
            });
        });
    </script>
</body>
</html>