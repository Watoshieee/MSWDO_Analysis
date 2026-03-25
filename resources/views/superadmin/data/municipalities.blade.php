<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipality Data – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--primary-blue:#2C3E8F;--primary-blue-light:#E5EEFF;--primary-blue-soft:#5D7BB9;--secondary-yellow:#FDB913;--secondary-yellow-light:#FFF3D6;--primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);--secondary-gradient:linear-gradient(135deg,#FDB913 0%,#E5A500 100%);--bg-light:#F8FAFC;--border-light:#E2E8F0;}
        *,body{font-family:'Inter','Segoe UI',sans-serif;}
        body{background:var(--bg-light);display:flex;flex-direction:column;min-height:100vh;}
        .navbar{background:var(--primary-gradient)!important;box-shadow:0 4px 24px rgba(44,62,143,.18);padding:14px 0;}
        .navbar-brand{font-weight:800;font-size:1.55rem;color:white!important;display:flex;align-items:center;gap:10px;}
        .nav-link{color:rgba(255,255,255,.88)!important;font-weight:600;transition:all .25s;border-radius:8px;padding:10px 18px!important;font-size:.95rem;}
        .nav-link:hover{background:rgba(255,255,255,.15);color:white!important;}
        .nav-link.active{background:var(--secondary-yellow);color:var(--primary-blue)!important;font-weight:700;}
        .user-info{color:white;display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.1);padding:9px 22px;border-radius:40px;font-size:.92rem;font-weight:500;}
        .logout-btn{background:transparent;border:2px solid rgba(255,255,255,.8);color:white;border-radius:30px;padding:6px 18px;font-weight:700;transition:all .3s;font-size:.88rem;cursor:pointer;}
        .logout-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}
        .hero-banner{background:var(--primary-gradient);color:white;padding:52px 0 42px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-70px;right:-70px;width:320px;height:320px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-banner::after{content:'';position:absolute;bottom:-80px;left:-50px;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,.05);}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:18px;}
        .hero-banner h1{font-size:2.4rem;font-weight:800;line-height:1.2;margin-bottom:10px;}
        .hero-divider{width:55px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:14px 0;}
        .hero-banner p{font-size:1rem;opacity:.87;max-width:580px;}
        .back-link{display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.75);font-size:.85rem;font-weight:600;text-decoration:none;margin-bottom:14px;transition:color .2s;}
        .back-link:hover{color:var(--secondary-yellow);}
        .main-content{flex:1;}

        /* Municipality Form Card */
        .muni-card{background:white;border-radius:20px;margin-bottom:32px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid var(--border-light);overflow:hidden;}
        .muni-card-header{background:var(--primary-gradient);color:white;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;}
        .muni-card-header h4{font-weight:800;margin:0;font-size:1.15rem;}
        .muni-id-badge{background:rgba(253,185,19,.25);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.4);border-radius:20px;padding:3px 14px;font-size:.78rem;font-weight:700;}
        .muni-card-body{padding:28px;}
        .section-label{display:inline-block;background:var(--primary-blue-light);color:var(--primary-blue);border-radius:8px;padding:5px 16px;font-size:.8rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin:16px 0 14px;border-left:3px solid var(--secondary-yellow);}
        .form-label{font-weight:600;color:var(--primary-blue);font-size:.88rem;margin-bottom:5px;}
        .form-control,.form-select{border:1.5px solid var(--border-light);border-radius:10px;padding:10px 14px;font-size:.9rem;transition:border .2s;}
        .form-control:focus,.form-select:focus{border-color:var(--primary-blue);box-shadow:0 0 0 3px rgba(44,62,143,.1);outline:none;}
        .info-text{font-size:.8rem;color:#94a3b8;margin-top:4px;}
        .total-display{background:linear-gradient(135deg,#e6f9f0,#d1f5e5);border-radius:14px;padding:18px 22px;text-align:center;margin-bottom:20px;border:1px solid rgba(26,122,74,.15);}
        .total-display .total-label{font-size:.78rem;font-weight:700;color:#1a7a4a;letter-spacing:.08em;text-transform:uppercase;}
        .total-display .total-value{font-size:2rem;font-weight:800;color:#1a7a4a;line-height:1.1;}
        .validation-note{background:var(--primary-blue-light);border-left:4px solid var(--primary-blue);border-radius:10px;padding:12px 16px;font-size:.85rem;color:var(--primary-blue);margin-bottom:18px;}
        .btn-update{background:var(--primary-gradient);color:white;border:none;border-radius:30px;padding:12px 36px;font-weight:700;font-size:.95rem;width:100%;transition:all .3s;cursor:pointer;}
        .btn-update:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 8px 20px rgba(44,62,143,.3);}

        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.75);text-align:center;padding:18px 0;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}
        .alert-success{background:var(--primary-blue-light);color:var(--primary-blue);border:none;border-left:4px solid var(--primary-blue);border-radius:12px;}
        .alert-danger{background:#fef2f2;color:#991b1b;border:none;border-left:4px solid #C41E24;border-radius:12px;}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Public View</a></li>
                </ul>
                <div class="d-flex">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('superadmin.data.dashboard') }}" class="back-link">&#8592; Back to Data Management</a>
            <div class="hero-badge">Data Management</div>
            <h1>Municipality Data</h1>
            <div class="hero-divider"></div>
            <p>Update population, age groups, households, and demographic data for each municipality.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            @foreach($municipalities as $municipality)
            <div class="muni-card">
                <div class="muni-card-header">
                    <h4>{{ $municipality->name }} Municipality</h4>
                    <span class="muni-id-badge">ID: {{ $municipality->id }}</span>
                </div>
                <div class="muni-card-body">
                    <form method="POST" action="{{ route('superadmin.data.municipalities.update', $municipality->id) }}">
                        @csrf

                        <!-- Total Population Live Display -->
                        <div class="total-display">
                            <div class="total-label">Total Population</div>
                            <div class="total-value" id="total-pop-{{ $municipality->id }}">
                                {{ number_format($municipality->male_population + $municipality->female_population) }}
                            </div>
                            <small style="color:#1a7a4a;font-size:.78rem;">Auto-calculated from Male + Female</small>
                        </div>

                        <!-- Population by Gender -->
                        <div class="section-label">Population by Gender</div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Male Population</label>
                                <input type="number" name="male_population" class="form-control population-input"
                                       data-municipality="{{ $municipality->id }}"
                                       value="{{ old('male_population', $municipality->male_population) }}" required min="0">
                                <div class="info-text">Number of male residents</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Female Population</label>
                                <input type="number" name="female_population" class="form-control population-input"
                                       data-municipality="{{ $municipality->id }}"
                                       value="{{ old('female_population', $municipality->female_population) }}" required min="0">
                                <div class="info-text">Number of female residents</div>
                            </div>
                        </div>

                        <!-- Population by Age Group -->
                        <div class="section-label">Population by Age Group</div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Youth (0–19 years)</label>
                                <input type="number" name="population_0_19" class="form-control"
                                       value="{{ old('population_0_19', $municipality->population_0_19) }}" required min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Adult (20–50 years)</label>
                                <input type="number" name="population_20_50" class="form-control"
                                       value="{{ old('population_20_50', $municipality->population_20_50) }}" required min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Senior (51–100 years)</label>
                                <input type="number" name="population_51_100" class="form-control"
                                       value="{{ old('population_51_100', $municipality->population_51_100) }}" required min="0">
                            </div>
                        </div>

                        <!-- Households & Demographics -->
                        <div class="section-label">Households &amp; Demographics</div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Total Households</label>
                                <input type="number" name="total_households" class="form-control"
                                       value="{{ old('total_households', $municipality->total_households) }}" required min="0">
                                <div class="info-text">Number of households</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Single Parents</label>
                                <input type="number" name="single_parent_count" class="form-control"
                                       value="{{ old('single_parent_count', $municipality->single_parent_count) }}" required min="0">
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

                        <div class="validation-note" id="validation-msg-{{ $municipality->id }}">
                            Age groups should sum to equal total population.
                        </div>

                        <button type="submit" class="btn-update">Save {{ $municipality->name }} Data</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.population-input').forEach(input => {
            input.addEventListener('input', function() {
                const id = this.dataset.municipality;
                const male = parseInt(document.querySelector(`input[name="male_population"][data-municipality="${id}"]`).value) || 0;
                const female = parseInt(document.querySelector(`input[name="female_population"][data-municipality="${id}"]`).value) || 0;
                const total = male + female;
                document.getElementById('total-pop-' + id).textContent = total.toLocaleString();
            });
        });
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const idEl = this.querySelector('.population-input');
                if (!idEl) return;
                const id = idEl.dataset.municipality;
                const male = parseInt(this.querySelector('[name="male_population"]').value) || 0;
                const female = parseInt(this.querySelector('[name="female_population"]').value) || 0;
                const a = parseInt(this.querySelector('[name="population_0_19"]').value) || 0;
                const b = parseInt(this.querySelector('[name="population_20_50"]').value) || 0;
                const c = parseInt(this.querySelector('[name="population_51_100"]').value) || 0;
                if ((male + female) !== (a + b + c)) {
                    e.preventDefault();
                    alert(`Age group sum (${(a+b+c).toLocaleString()}) ≠ total population (${(male+female).toLocaleString()}). Please verify your numbers.`);
                }
            });
        });
    </script>
</body>
</html>