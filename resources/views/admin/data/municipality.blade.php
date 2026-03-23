<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} Profile - Data Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --success-green: #28a745;
            --success-green-light: #E1F7E1;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-soft: #475569;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-dark);
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 2px 10px rgba(44, 62, 143, 0.15);
            padding: 8px 0;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.2rem;
            color: white !important;
        }

        .navbar-brand i {
            color: var(--secondary-yellow);
        }

        .nav-link {
            color: white !important;
            font-size: 0.9rem;
            padding: 5px 12px !important;
        }

        .back-btn {
            background: var(--bg-white);
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue);
            border-radius: 20px;
            padding: 5px 15px;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 15px;
        }

        .back-btn:hover {
            background: var(--primary-gradient);
            color: white;
        }

        .tab-navigation {
            background: white;
            border-radius: 12px;
            padding: 5px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            border: 1px solid var(--border-light);
            display: inline-flex;
            width: 100%;
            max-width: 500px;
        }

        .tab-btn {
            flex: 1;
            padding: 8px 15px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: var(--text-soft);
        }

        .tab-btn i {
            font-size: 1rem;
            color: var(--primary-blue);
        }

        .tab-btn:hover {
            background: var(--bg-light);
            color: var(--primary-blue);
        }

        .tab-btn.active {
            background: var(--primary-gradient);
            color: white;
        }

        .tab-btn.active i {
            color: var(--secondary-yellow);
        }

        .data-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid var(--border-light);
        }

        .section-title {
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 1.1rem;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid var(--secondary-yellow);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--secondary-yellow);
            font-size: 1.2rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 3px;
            font-size: 0.85rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--border-light);
            padding: 8px 12px;
            font-size: 0.9rem;
        }

        .btn-update {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 15px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 143, 0.3);
        }

        .total-display {
            background: var(--success-green-light);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-bottom: 15px;
        }

        .total-display h4 {
            color: var(--primary-blue);
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .total-display p {
            font-size: 2rem;
            font-weight: 700;
            color: var(--success-green);
            margin: 0;
        }

        .program-card {
            background: var(--bg-light);
            border-radius: 8px;
            padding: 10px;
            height: 100%;
            border: 1px solid var(--border-light);
        }

        .program-card label {
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 5px;
            display: block;
        }

        .barangay-year-selector {
            background: var(--bg-light);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid var(--border-light);
        }

        .year-selector-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .year-radio-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .year-radio {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .year-radio input[type="radio"] {
            accent-color: var(--primary-blue);
        }

        .barangay-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 15px;
            max-height: 600px;
            overflow-y: auto;
            padding: 10px;
        }

        .barangay-input-card {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 15px;
            border: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .barangay-input-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 4px 12px rgba(44, 62, 143, 0.1);
        }

        .barangay-input-header {
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }

        .barangay-input-header i {
            color: var(--secondary-yellow);
        }

        .barangay-input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .barangay-input-field {
            display: flex;
            flex-direction: column;
        }

        .barangay-input-field label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-soft);
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .barangay-input-field input {
            padding: 6px 10px;
            font-size: 0.9rem;
            border: 1px solid var(--border-light);
            border-radius: 6px;
            background: white;
        }

        .barangay-input-field input:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        .btn-save-barangay {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 10px;
            width: 100%;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-save-barangay:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 62, 143, 0.2);
        }

        .btn-save-barangay i {
            color: var(--secondary-yellow);
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            background: var(--bg-light);
            border-radius: 12px;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--primary-blue);
            opacity: 0.3;
        }

        .toast-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }

        .toast-success {
            background: var(--success-green);
        }

        .toast-error {
            background: #dc3545;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .barangay-grid {
                grid-template-columns: 1fr;
            }
            .tab-navigation {
                max-width: 100%;
            }
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

    <div class="container mt-3">
        <!-- Back Button -->
        <a href="{{ route('admin.data.dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Data Dashboard
        </a>

        <h1 class="mb-3" style="color: var(--primary-blue); font-size: 1.8rem;">
            <i class="bi bi-building" style="color: var(--secondary-yellow);"></i>
            {{ $municipality->name }} Municipality
        </h1>

        @if(session('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Toast Container -->
        <div id="toast" class="toast-message" style="display: none;"></div>

        <!-- Tabs Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" onclick="switchTab('current')">
                <i class="bi bi-pencil-square"></i> Current Year
            </button>
            <button class="tab-btn" onclick="switchTab('yearly')">
                <i class="bi bi-calendar-check"></i> Yearly Comparison
            </button>
            <button class="tab-btn" onclick="switchTab('barangay')">
                <i class="bi bi-grid-3x3"></i> Barangay Data
            </button>
        </div>

        <!-- Tab 1: Current Year Data -->
        <div id="current-tab" class="tab-content" style="display: block;">
            <div class="data-card">
                <div class="total-display">
                    <h4><i class="bi bi-people-fill"></i> Total Population</h4>
                    <p id="totalPopulation">{{ number_format($municipality->male_population + $municipality->female_population) }}</p>
                </div>

                <form method="POST" action="{{ route('admin.data.municipality.update') }}">
                    @csrf
                    
                    <div class="section-title">
                        <i class="bi bi-people"></i> Demographics
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Male Population</label>
                            <input type="number" name="male_population" class="form-control population-input" 
                                   value="{{ $municipality->male_population }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Female Population</label>
                            <input type="number" name="female_population" class="form-control population-input" 
                                   value="{{ $municipality->female_population }}" required min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Youth (0-19)</label>
                            <input type="number" name="population_0_19" class="form-control" 
                                   value="{{ $municipality->population_0_19 }}" required min="0">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Adult (20-59)</label>
                            <input type="number" name="population_20_59" class="form-control" 
                                   value="{{ $municipality->population_20_59 }}" required min="0">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="form-label">Senior (60-100)</label>
                            <input type="number" name="population_60_100" class="form-control" 
                                   value="{{ $municipality->population_60_100 }}" required min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Households</label>
                            <input type="number" name="total_households" class="form-control" 
                                   value="{{ $municipality->total_households }}" required min="0">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Single Parents</label>
                            <input type="number" name="single_parent_count" class="form-control" 
                                   value="{{ $municipality->single_parent_count }}" required min="0">
                        </div>
                    </div>

                    <div class="section-title">
                        <i class="bi bi-heart"></i> Programs
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>4Ps</label>
                                <input type="number" name="total_4ps" class="form-control" value="{{ $total4ps }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>PWD</label>
                                <input type="number" name="total_pwd" class="form-control" value="{{ $totalPwd }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>Senior</label>
                                <input type="number" name="total_senior" class="form-control" value="{{ $totalSenior }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>AICS</label>
                                <input type="number" name="total_aics" class="form-control" value="{{ $totalAics }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>ESA</label>
                                <input type="number" name="total_esa" class="form-control" value="{{ $totalEsa }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>SLP</label>
                                <input type="number" name="total_slp" class="form-control" value="{{ $totalSlp }}" required min="0">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="program-card">
                                <label>Solo Parent</label>
                                <input type="number" name="total_solo_parent" class="form-control" value="{{ $totalSoloParent }}" required min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-2">
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
                        <i class="bi bi-save"></i> Update Data
                    </button>
                </form>
            </div>
        </div>

        <!-- Tab 2: Yearly Comparison -->
        <div id="yearly-tab" class="tab-content" style="display: none;">
            <div class="data-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0" style="color: var(--primary-blue);">
                        <i class="bi bi-calendar-check"></i> Historical Data
                    </h4>
                    <a href="{{ route('admin.yearly.compare') }}" class="btn btn-sm btn-outline-primary">Compare Years</a>
                </div>

                @if(empty($years))
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <p class="mt-2">No historical data yet</p>
                    </div>
                @else
                    <div class="chart-container" style="height: 200px; position: relative;">
                        <canvas id="yearlyPopulationChart"></canvas>
                    </div>
                    <div class="row mt-3">
                        @foreach($years as $year)
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.yearly.view', $year) }}" class="btn btn-outline-primary w-100 btn-sm">
                                {{ $year }}
                            </a>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab 3: Barangay Data Management -->
        <div id="barangay-tab" class="tab-content" style="display: none;">
            <div class="data-card">
                <div class="section-title">
                    <i class="bi bi-grid-3x3"></i> Barangay Data Entry
                </div>

                <!-- Year Selection -->
                <div class="barangay-year-selector">
                    <div class="year-selector-title">
                        <i class="bi bi-calendar"></i> Select Year for Barangay Data
                    </div>
                    <div class="year-radio-group">
                        @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                            <div class="year-radio">
                                <input type="radio" name="barangay_year" id="year{{ $yearOption }}" 
                                       value="{{ $yearOption }}" {{ $yearOption == date('Y') ? 'checked' : '' }}
                                       onchange="loadBarangayData({{ $yearOption }})">
                                <label for="year{{ $yearOption }}">{{ $yearOption }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Barangay Input Grid -->
                <div id="barangay-data-container">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading barangay data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // CSRF Token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast-message toast-${type}`;
        toast.style.display = 'block';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    // Tab Switching
    function switchTab(tabName) {
        document.getElementById('current-tab').style.display = 'none';
        document.getElementById('yearly-tab').style.display = 'none';
        document.getElementById('barangay-tab').style.display = 'none';
        
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        if (tabName === 'current') {
            document.getElementById('current-tab').style.display = 'block';
            document.querySelectorAll('.tab-btn')[0].classList.add('active');
        } else if (tabName === 'yearly') {
            document.getElementById('yearly-tab').style.display = 'block';
            document.querySelectorAll('.tab-btn')[1].classList.add('active');
            
            // Initialize chart if not already done
            if (typeof yearlyChart === 'undefined' && document.getElementById('yearlyPopulationChart')) {
                initializeYearlyChart();
            }
        } else {
            document.getElementById('barangay-tab').style.display = 'block';
            document.querySelectorAll('.tab-btn')[2].classList.add('active');
            const selectedYear = document.querySelector('input[name="barangay_year"]:checked').value;
            loadBarangayData(selectedYear);
        }
    }

// Load Barangay Data for Selected Year (AUTO-CREATES if not exists)
function loadBarangayData(year) {
    const container = document.getElementById('barangay-data-container');
    
    container.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading barangay data for ${year}...</p>
        </div>
    `;

    // Add timeout to prevent infinite loading
    const timeoutId = setTimeout(() => {
        container.innerHTML = `
            <div class="empty-state">
                <i class="bi bi-exclamation-triangle"></i>
                <p class="mt-2">Request timed out. Please try again.</p>
                <button class="btn btn-primary btn-sm mt-2" onclick="loadBarangayData(${year})">
                    Retry
                </button>
            </div>
        `;
    }, 10000); // 10 second timeout

    fetch(`/api/barangays/{{ $municipality->name }}/${year}`)
        .then(response => {
            clearTimeout(timeoutId);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                renderBarangayInputs(year, data);
            } else {
                throw new Error('No data received');
            }
        })
        .catch(error => {
            clearTimeout(timeoutId);
            console.error('Error:', error);
            container.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-exclamation-triangle"></i>
                    <p class="mt-2">Error loading data: ${error.message}</p>
                    <p class="text-muted small">Please check your connection and try again.</p>
                    <button class="btn btn-primary btn-sm mt-2" onclick="loadBarangayData(${year})">
                        Retry
                    </button>
                </div>
            `;
        });
}

    // Render Barangay Input Forms
    function renderBarangayInputs(year, barangays) {
        const container = document.getElementById('barangay-data-container');
        
        let html = `
            <div class="alert alert-info py-2 mb-3">
                <i class="bi bi-info-circle"></i> 
                Editing data for year: <strong>${year}</strong>
                <small class="d-block text-muted mt-1">
                    ${barangays.length} barangays loaded. Fill in the data and click Save for each.
                </small>
            </div>
            <div class="barangay-grid">
        `;

        barangays.forEach(barangay => {
            // Use a unique ID for each barangay input group
            const uniqueId = `barangay_${barangay.name.replace(/\s+/g, '_')}_${year}`;
            
            html += `
                <div class="barangay-input-card" id="${uniqueId}">
                    <div class="barangay-input-header">
                        <i class="bi bi-pin-map-fill"></i>
                        ${barangay.name}
                    </div>
                    <div class="barangay-input-row">
                        <div class="barangay-input-field">
                            <label>Male Population</label>
                            <input type="number" class="form-control form-control-sm male-input" 
                                   data-barangay="${barangay.name}" value="${barangay.male_population || 0}" min="0">
                        </div>
                        <div class="barangay-input-field">
                            <label>Female Population</label>
                            <input type="number" class="form-control form-control-sm female-input" 
                                   data-barangay="${barangay.name}" value="${barangay.female_population || 0}" min="0">
                        </div>
                    </div>
                    <div class="barangay-input-row">
                        <div class="barangay-input-field">
                            <label>Age 0-19</label>
                            <input type="number" class="form-control form-control-sm" 
                                   id="age0_19_${barangay.name.replace(/\s+/g, '_')}" 
                                   value="${barangay.population_0_19 || 0}" min="0">
                        </div>
                        <div class="barangay-input-field">
                            <label>Age 20-59</label>
                            <input type="number" class="form-control form-control-sm" 
                                   id="age20_59_${barangay.name.replace(/\s+/g, '_')}" 
                                   value="${barangay.population_20_59 || 0}" min="0">
                        </div>
                        <div class="barangay-input-field">
                            <label>Age 60-100</label>
                            <input type="number" class="form-control form-control-sm" 
                                   id="age60_100_${barangay.name.replace(/\s+/g, '_')}" 
                                   value="${barangay.population_60_100 || 0}" min="0">
                        </div>
                    </div>
                    <div class="barangay-input-row">
                        <div class="barangay-input-field">
                            <label>Households</label>
                            <input type="number" class="form-control form-control-sm" 
                                   id="households_${barangay.name.replace(/\s+/g, '_')}" 
                                   value="${barangay.total_households || 0}" min="0">
                        </div>
                        <div class="barangay-input-field">
                            <label>Single Parents</label>
                            <input type="number" class="form-control form-control-sm" 
                                   id="single_parents_${barangay.name.replace(/\s+/g, '_')}" 
                                   value="${barangay.single_parent_count || 0}" min="0">
                        </div>
                    </div>
                    <button class="btn-save-barangay" onclick="saveBarangayData('${barangay.name}', ${year}, this)">
                        <i class="bi bi-save"></i> Save ${barangay.name}
                    </button>
                </div>
            `;
        });

        html += `</div>`;
        container.innerHTML = html;
    }

// Save Barangay Data
function saveBarangayData(barangayName, year, button) {
    // Generate a safe name for IDs
    const safeName = barangayName.replace(/\s+/g, '_').replace(/[()]/g, '');
    
    // Find the parent card
    const card = button.closest('.barangay-input-card');
    
    // Collect all input values
    const data = {
        male_population: card.querySelector('.male-input')?.value || 0,
        female_population: card.querySelector('.female-input')?.value || 0,
        population_0_19: card.querySelector('[id*="age0_19"]')?.value || 0,
        population_20_59: card.querySelector('[id*="age20_59"]')?.value || 0,
        population_60_100: card.querySelector('[id*="age60_100"]')?.value || 0,
        total_households: card.querySelector('[id*="households"]')?.value || 0,
        single_parent_count: card.querySelector('[id*="single_parents"]')?.value || 0,
        year: year,
        barangay_name: barangayName
    };

    // Show saving state
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';
    button.disabled = true;

    // First, find or create the barangay record
    fetch('/admin/data/barangays/find-or-create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            name: barangayName,
            municipality: '{{ $municipality->name }}',
            year: year
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success && result.barangay_id) {
            // Now save the data with the real barangay ID
            return fetch(`/admin/data/barangays/${result.barangay_id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
        } else {
            throw new Error(result.message || 'Could not find or create barangay');
        }
    })
    .then(async response => {
        const text = await response.text();
        try {
            const data = JSON.parse(text);
            if (!response.ok) {
                throw new Error(data.message || 'Server error');
            }
            return data;
        } catch (e) {
            console.error('Server response:', text);
            throw new Error('Invalid server response');
        }
    })
    .then(result => {
        if (result.success) {
            showToast(`✅ ${barangayName} data saved successfully for year ${year}!`, 'success');
            
            // Update the button to show success
            button.innerHTML = '✓ Saved';
            button.style.background = '#28a745';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
            }, 2000);
        } else {
            showToast('❌ Error: ' + (result.message || 'Failed to save'), 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('❌ Error: ' + error.message, 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

    // Add this new route to web.php for finding/creating barangays
    // You'll need to add this route separately

    // Auto-calculate total population
    document.querySelectorAll('.population-input').forEach(input => {
        input.addEventListener('input', function() {
            const male = document.querySelector('input[name="male_population"]').value || 0;
            const female = document.querySelector('input[name="female_population"]').value || 0;
            document.getElementById('totalPopulation').innerText = 
                (parseInt(male) + parseInt(female)).toLocaleString();
        });
    });

    // Initialize Yearly Chart
    function initializeYearlyChart() {
        const years = {!! json_encode(array_keys($yearlyData ?? [])) !!};
        const populations = {!! json_encode(array_column($yearlyData ?? [], 'total_population')) !!};
        
        if (years.length > 0) {
            new Chart(document.getElementById('yearlyPopulationChart'), {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [{
                        data: populations,
                        borderColor: '#2C3E8F',
                        backgroundColor: 'rgba(44, 62, 143, 0.1)',
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: '#FDB913',
                        pointBorderColor: '#2C3E8F'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    }

    // Load default barangay data on page load if barangay tab is active
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.tab-btn.active')?.innerText.includes('Barangay')) {
            const selectedYear = document.querySelector('input[name="barangay_year"]:checked')?.value;
            if (selectedYear) {
                loadBarangayData(selectedYear);
            }
        }
    });
</script>
</body>
</html>