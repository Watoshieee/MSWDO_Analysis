<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Municipality Yearly Data – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }
        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }
        body { background: var(--bg-light); display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        a { text-decoration: none; }

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .nav-link { color: rgba(255,255,255,.88) !important; font-weight: 600; transition: all .25s; border-radius: 8px; padding: 10px 18px !important; font-size: .93rem; }
        .nav-link:hover { background: rgba(255,255,255,.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,.1); padding: 9px 22px; border-radius: 40px; font-size: .9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all .3s; font-size: .88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* HERO */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 36px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -70px; right: -70px; width: 300px; height: 300px; border-radius: 50%; background: rgba(253,185,19,.10); }
        .hero-badge { display: inline-block; background: rgba(253,185,19,.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,.35); border-radius: 30px; padding: 5px 18px; font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 14px; }
        .hero-banner h1 { font-size: 2.2rem; font-weight: 800; margin-bottom: 8px; }
        .hero-divider { width: 55px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0; }
        .hero-banner p { font-size: .98rem; opacity: .85; }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,.75); font-size: .85rem; font-weight: 600; text-decoration: none; margin-bottom: 14px; transition: color .2s; }
        .back-link:hover { color: var(--secondary-yellow); }
        .main-content { flex: 1; }

        /* Tab Pills */
        .tab-pills { display: flex; gap: 10px; margin-bottom: 28px; flex-wrap: wrap; }
        .tab-pill { background: white; border: 2px solid var(--border-light); border-radius: 30px; padding: 8px 22px; font-weight: 700; font-size: .88rem; color: #64748b; cursor: pointer; transition: all .25s; }
        .tab-pill.active { background: var(--primary-gradient); color: white; border-color: transparent; }

        /* Panel */
        .panel-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid var(--border-light); overflow: hidden; margin-bottom: 28px; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
        .panel-header h5 { font-weight: 800; margin: 0; font-size: 1.05rem; }
        .count-badge { background: rgba(253,185,19,.25); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,.4); border-radius: 20px; padding: 3px 12px; font-size: .78rem; font-weight: 700; }

        /* Table */
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table thead th { background: var(--bg-light); color: var(--primary-blue); font-size: .76rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; padding: 12px 18px; border-bottom: 2px solid var(--border-light); }
        .premium-table tbody td { padding: 13px 18px; font-size: .88rem; border-bottom: 1px solid var(--border-light); vertical-align: middle; color: #334155; }
        .premium-table tbody tr:last-child td { border-bottom: none; }
        .premium-table tbody tr:hover { background: var(--primary-blue-light); }

        /* Buttons */
        .btn-add { background: var(--secondary-gradient); color: var(--primary-blue); border: none; border-radius: 30px; padding: 9px 24px; font-weight: 800; font-size: .88rem; cursor: pointer; transition: all .3s; }
        .btn-add:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(253,185,19,.4); }
        .btn-edit { background: var(--secondary-gradient); color: var(--primary-blue); border: none; border-radius: 8px; padding: 5px 14px; font-size: .79rem; font-weight: 700; cursor: pointer; transition: all .25s; }
        .btn-edit:hover { transform: translateY(-1px); }
        .btn-del { background: rgba(196,30,36,.08); color: #C41E24; border: 1px solid rgba(196,30,36,.2); border-radius: 8px; padding: 5px 14px; font-size: .79rem; font-weight: 700; cursor: pointer; transition: all .25s; }
        .btn-del:hover { background: #C41E24; color: white; }

        /* Modal */
        .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(44,62,143,.2); }
        .modal-header { background: var(--primary-gradient); color: white; border-radius: 16px 16px 0 0; }
        .modal-title { font-weight: 800; }
        .btn-close { filter: invert(1); }
        .form-label { font-weight: 600; color: var(--primary-blue); font-size: .88rem; }
        .form-control, .form-select { border: 1.5px solid var(--border-light); border-radius: 10px; padding: 10px 14px; font-size: .9rem; transition: border .2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,.1); }
        .btn-modal-submit { background: var(--primary-gradient); color: white; border: none; border-radius: 30px; padding: 10px 28px; font-weight: 700; transition: all .3s; }
        .btn-modal-cancel { background: transparent; color: #64748b; border: 1.5px solid var(--border-light); border-radius: 30px; padding: 10px 28px; font-weight: 600; }

        /* Chart */
        .chart-card { background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid var(--border-light); padding: 24px; margin-bottom: 28px; }
        .chart-title { font-size: .95rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 16px; }

        /* Alerts */
        .alert-success-c { background: var(--primary-blue-light); color: var(--primary-blue); border: none; border-left: 4px solid var(--primary-blue); border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; }
        .alert-danger-c { background: #fef2f2; color: #991b1b; border: none; border-left: 4px solid #C41E24; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; }

        .section-tab { display: none; }
        .section-tab.active { display: block; }

        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,.75); text-align: center; padding: 18px 0; font-size: .85rem; margin-top: auto; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <a href="{{ route('admin.data.dashboard') }}" class="back-link">&#8592; Back to Data Management</a>
            <div class="hero-badge">Data Management</div>
            <h1>Municipality Yearly Data</h1>
            <div class="hero-divider"></div>
            <p>Manage population, household, and program beneficiary summary records per year for <strong>{{ $municipality->name }}</strong>.</p>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">

            @if(session('success'))
                <div class="alert-success-c">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-danger-c"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <!-- Tab Pills -->
            <div class="tab-pills">
                <button class="tab-pill active" onclick="switchTab('records', this)">📋 Records</button>
                <button class="tab-pill" onclick="switchTab('analysis', this)">📊 Analysis</button>
            </div>

            <!-- ===== RECORDS TAB ===== -->
            <div class="section-tab active" id="tab-records">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold" style="color:var(--primary-blue);">Yearly Summary Records — {{ $municipality->name }}</h6>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">+ Add Year Data</button>
                </div>

                <div class="panel-card">
                    <div class="panel-header">
                        <h5>{{ $municipality->name }}</h5>
                        <span class="count-badge">{{ $summaries->count() }} year records</span>
                    </div>
                    <div class="table-responsive">
                        @if($summaries->isEmpty())
                            <div style="padding:36px;text-align:center;color:#94a3b8;font-size:.95rem;">
                                <div style="font-size:2.5rem;opacity:.3;margin-bottom:8px;">📋</div>
                                No records yet. Click <strong>"+ Add Year Data"</strong> to add your first yearly summary.
                            </div>
                        @else
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Population</th>
                                        <th>Households</th>
                                        <th>PWD</th>
                                        <th>AICS</th>
                                        <th>Solo Parent</th>
                                        <th>4Ps</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summaries as $row)
                                        <tr>
                                            <td><strong>{{ $row->year }}</strong></td>
                                            <td>{{ number_format($row->total_population) }}</td>
                                            <td>{{ number_format($row->total_households) }}</td>
                                            <td>{{ number_format($row->total_pwd) }}</td>
                                            <td>{{ number_format($row->total_aics) }}</td>
                                            <td>{{ number_format($row->total_solo_parent) }}</td>
                                            <td>{{ number_format($row->total_4ps) }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">Edit</button>
                                                    <form method="POST" action="{{ route('admin.data.yearly.delete', $row->id) }}" onsubmit="return confirm('Delete this {{ $row->year }} record? This cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-del">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal for this row -->
                                        <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit {{ $municipality->name }} – {{ $row->year }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.data.yearly.save') }}">
                                                        @csrf
                                                        <input type="hidden" name="year" value="{{ $row->year }}">
                                                        <div class="modal-body p-4">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Total Population</label>
                                                                    <input type="number" name="total_population" class="form-control" value="{{ $row->total_population }}" required min="0">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Total Households</label>
                                                                    <input type="number" name="total_households" class="form-control" value="{{ $row->total_households }}" required min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">PWD Assistance</label>
                                                                    <input type="number" name="total_pwd" class="form-control" value="{{ $row->total_pwd }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">AICS</label>
                                                                    <input type="number" name="total_aics" class="form-control" value="{{ $row->total_aics }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Solo Parent</label>
                                                                    <input type="number" name="total_solo_parent" class="form-control" value="{{ $row->total_solo_parent }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">4Ps</label>
                                                                    <input type="number" name="total_4ps" class="form-control" value="{{ $row->total_4ps }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">Senior Citizen</label>
                                                                    <input type="number" name="total_senior" class="form-control" value="{{ $row->total_senior }}" min="0">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">SLP</label>
                                                                    <input type="number" name="total_slp" class="form-control" value="{{ $row->total_slp }}" min="0">
                                                                </div>
                                                                <input type="hidden" name="total_esa" value="{{ $row->total_esa }}">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 px-4 pb-4 gap-2">
                                                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn-modal-submit">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ===== ANALYSIS TAB ===== -->
            <div class="section-tab" id="tab-analysis">
                @php $cd = $chartData[$municipality->name] ?? ['years'=>[],'population'=>[],'households'=>[]]; @endphp

                @if(empty($cd['years']))
                    <div class="panel-card" style="padding:36px;text-align:center;color:#94a3b8;">
                        <div style="font-size:2.5rem;opacity:.3;margin-bottom:8px;">📊</div>
                        No data yet to display charts. Add some yearly records first.
                    </div>
                @else
                    <div class="chart-card">
                        <div class="chart-title">{{ $municipality->name }} — Population & Households per Year</div>
                        <canvas id="popChart" height="90"></canvas>
                    </div>
                    <div class="chart-card">
                        <div class="chart-title">{{ $municipality->name }} — PWD, AICS & Solo Parent per Year</div>
                        <canvas id="progChart" height="90"></canvas>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- ===== ADD MODAL ===== -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Year Data — {{ $municipality->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.data.yearly.save') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Year</label>
                                <select name="year" class="form-select" required>
                                    <option value="">Select Year</option>
                                    @foreach($years as $y)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Population</label>
                                <input type="number" name="total_population" class="form-control" required min="0" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total Households</label>
                                <input type="number" name="total_households" class="form-control" required min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PWD Assistance</label>
                                <input type="number" name="total_pwd" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">AICS</label>
                                <input type="number" name="total_aics" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Solo Parent</label>
                                <input type="number" name="total_solo_parent" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">4Ps</label>
                                <input type="number" name="total_4ps" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Senior Citizen</label>
                                <input type="number" name="total_senior" class="form-control" min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">SLP</label>
                                <input type="number" name="total_slp" class="form-control" min="0" placeholder="0">
                            </div>
                            <input type="hidden" name="total_esa" value="0">
                        </div>
                        <small class="text-muted mt-2 d-block">💡 If a record for this year already exists, it will be updated automatically.</small>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-submit">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function switchTab(name, el) {
            document.querySelectorAll('.section-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-pill').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            el.classList.add('active');
            if (name === 'analysis') initCharts();
        }

        const chartData = @json($chartData);
        const muniName  = @json($municipality->name);
        let chartsInited = false;

        function initCharts() {
            if (chartsInited) return;
            chartsInited = true;

            const cd = chartData[muniName];
            if (!cd || !cd.years.length) return;

            // Population & Households Chart
            new Chart(document.getElementById('popChart'), {
                type: 'bar',
                data: {
                    labels: cd.years,
                    datasets: [
                        {
                            label: 'Population',
                            data: cd.population,
                            backgroundColor: '#2C3E8F',
                            borderRadius: 6,
                            barPercentage: 0.7
                        },
                        {
                            label: 'Households',
                            data: cd.households,
                            backgroundColor: '#FDB913',
                            borderRadius: 6,
                            barPercentage: 0.7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } }
                    }
                }
            });

            // Program Summary Chart (PWD, AICS, Solo Parent) from summaries JSON
            const summaries = @json($summaries);
            const years     = summaries.map(r => r.year).reverse();
            const pwd       = summaries.map(r => r.total_pwd).reverse();
            const aics      = summaries.map(r => r.total_aics).reverse();
            const solo      = summaries.map(r => r.total_solo_parent).reverse();

            new Chart(document.getElementById('progChart'), {
                type: 'line',
                data: {
                    labels: years,
                    datasets: [
                        { label: 'PWD Assistance', data: pwd,  borderColor: '#2C3E8F', backgroundColor: 'rgba(44,62,143,.1)', fill: true, tension: 0.4, pointRadius: 5, borderWidth: 3 },
                        { label: 'AICS',            data: aics, borderColor: '#FDB913', backgroundColor: 'rgba(253,185,19,.1)', fill: true, tension: 0.4, pointRadius: 5, borderWidth: 3 },
                        { label: 'Solo Parent',     data: solo, borderColor: '#C41E24', backgroundColor: 'rgba(196,30,36,.1)',  fill: true, tension: 0.4, pointRadius: 5, borderWidth: 3 }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString() } }
                    }
                }
            });
        }
    </script>
</body>
</html>
