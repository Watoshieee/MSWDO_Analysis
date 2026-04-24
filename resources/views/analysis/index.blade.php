<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistical Analysis – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --blue:#2C3E8F; --blue-lt:#E5EEFF; --yellow:#FDB913; --green:#28a745;
            --red:#C41E24; --grad:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);
        }
        body { background:#e2e8f0; font-family:'Inter',sans-serif; margin:0; }
        .navbar { background:var(--grad)!important; box-shadow:0 4px 20px rgba(44,62,143,.18); padding:14px 0; }
        .navbar-brand { font-weight:800; font-size:1.5rem; color:#fff!important; display:flex; align-items:center; gap:10px; }
        .nav-link { color:rgba(255,255,255,.88)!important; font-weight:600; border-radius:8px; padding:10px 18px!important; }
        .nav-link:hover { background:rgba(255,255,255,.15); color:#fff!important; }
        .nav-link.active { background:var(--yellow); color:var(--blue)!important; font-weight:700; }
        .user-info { color:#fff; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,.1); padding:9px 22px; border-radius:40px; font-size:.92rem; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,.8); color:#fff; border-radius:30px; padding:6px 18px; font-weight:700; cursor:pointer; }
        .logout-btn:hover { background:var(--yellow); color:var(--blue); border-color:var(--yellow); }
        .btn-login { background:#fff; color:var(--blue); border:2px solid #fff; border-radius:30px; padding:8px 25px; font-weight:700; text-decoration:none; }
        .btn-login:hover { background:var(--yellow); color:var(--blue); border-color:var(--yellow); }
        .hero { background:var(--grad); color:#fff; padding:52px 0 42px; position:relative; overflow:hidden; }
        .hero::before { content:''; position:absolute; top:-60px; right:-60px; width:280px; height:280px; border-radius:50%; background:rgba(253,185,19,.1); }
        .hero h1 { font-size:2.4rem; font-weight:800; margin-bottom:10px; }
        .hero-divider { width:50px; height:4px; background:var(--yellow); border-radius:2px; margin:14px 0; }
        .hero p { opacity:.85; font-size:.98rem; line-height:1.7; max-width:680px; }
        .year-bar { background:#fff; border-bottom:1px solid #e2e8f0; padding:12px 0; position:sticky; top:0; z-index:100; box-shadow:0 2px 8px rgba(44,62,143,.06); }
        .year-pill { padding:5px 16px; border-radius:20px; font-weight:600; font-size:.83rem; text-decoration:none; transition:all .2s; }
        .section-wrap { padding:48px 0; }
        .section-wrap.alt { background:#f0f5ff; }
        .section-wrap.dark { background:#1e293b; }
        .sec-title { font-size:1.35rem; font-weight:800; color:var(--blue); padding-bottom:12px; margin-bottom:24px; position:relative; }
        .sec-title::after { content:''; position:absolute; bottom:0; left:0; width:44px; height:4px; background:var(--yellow); border-radius:2px; }
        .sec-title.light { color:#FDB913; }
        .card-base { background:#f8fafc; border:1px solid #cbd5e1; border-radius:18px; padding:24px; position:relative; overflow:hidden; }
        .card-base::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--grad); }
        .card-base.y::before { background:linear-gradient(135deg,#FDB913,#E5A500); }
        .card-base.g::before { background:linear-gradient(135deg,#28a745,#1e7e34); }
        .card-base.r::before { background:linear-gradient(135deg,#C41E24,#8B1A1A); }
        .stat-num { font-size:2rem; font-weight:800; color:var(--blue); }
        .stat-lbl { color:#64748b; font-size:.85rem; font-weight:500; }
        .chart-box { position:relative; height:300px; }
        .chart-box.tall { height:340px; }
        table thead th { background:var(--grad); color:#fff; font-weight:600; border:none; padding:11px 14px; font-size:.85rem; }
        table thead th:first-child { border-radius:8px 0 0 0; }
        table thead th:last-child { border-radius:0 8px 0 0; }
        table tbody td { padding:10px 14px; font-size:.85rem; vertical-align:middle; }
        .badge-sig { background:#dcfce7; color:#166534; border-radius:20px; padding:4px 12px; font-size:.78rem; font-weight:700; }
        .badge-nosig { background:#fef9c3; color:#854d0e; border-radius:20px; padding:4px 12px; font-size:.78rem; font-weight:700; }
        .badge-strong { background:#dbeafe; color:#1e40af; border-radius:20px; padding:4px 12px; font-size:.78rem; font-weight:700; }
        .badge-moderate { background:#e0f2fe; color:#0369a1; border-radius:20px; padding:4px 12px; font-size:.78rem; font-weight:700; }
        .badge-weak { background:#f1f5f9; color:#475569; border-radius:20px; padding:4px 12px; font-size:.78rem; font-weight:700; }
        .insight-card { background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.1); border-radius:14px; padding:20px; transition:background .2s; }
        .insight-card:hover { background:rgba(255,255,255,.11); }
        .rec-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:20px; border-left:4px solid var(--blue); }
        .footer-strip { background:var(--grad); color:#fff; text-align:center; padding:20px; font-size:.88rem; }
        @media(max-width:768px) { .hero h1 { font-size:1.8rem; } }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/analysis">
            <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nav">
            @auth
                @if(Auth::user()->isSuperAdmin())
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
                        <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                    </ul>
                    <div class="d-flex"><div class="user-info"><span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="logout-btn">Logout</button></form></div></div>
                @elseif(Auth::user()->isAdmin())
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                        <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                    </ul>
                    <div class="d-flex"><div class="user-info"><span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="logout-btn">Logout</button></form></div></div>
                @else
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="/analysis">Programs</a></li>
                        <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
                        <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                    </ul>
                    <div class="d-flex"><div class="user-info"><span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf
                            <button type="submit" class="logout-btn">Logout</button></form></div></div>
                @endif
            @else
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/analysis">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                </ul>
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn-login">Login</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="container" style="position:relative;z-index:1;">
        <div style="display:inline-block;background:rgba(253,185,19,.18);color:#FDB913;border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:4px 16px;font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:16px;">Statistical Analysis Dashboard</div>
        <h1>Comparative Socioeconomic Analysis</h1>
        <div class="hero-divider"></div>
        <p>Comprehensive statistical analysis of Magdalena, Liliw, and Majayjay — covering population, gender, age groups, households, social welfare programs, ANOVA, and Pearson correlation.</p>
    </div>
</section>

{{-- YEAR FILTER --}}
<div class="year-bar">
    <div class="container d-flex align-items-center gap-3 flex-wrap">
        <span style="font-weight:700;color:var(--blue);font-size:.9rem;">Year:</span>
        @foreach($allYears as $yr)
            <a href="?year={{ $yr }}" class="year-pill"
               style="background:{{ $yr==$selectedYear?'#2C3E8F':'#f1f5f9' }};color:{{ $yr==$selectedYear?'#fff':'#334155' }};border:1px solid {{ $yr==$selectedYear?'#2C3E8F':'#cbd5e1' }};">{{ $yr }}</a>
        @endforeach
        <span style="color:#94a3b8;font-size:.8rem;">Data for <strong>{{ $selectedYear }}</strong></span>
    </div>
</div>

@php
    /** @var string[] $coreNames */
    /** @var array<string,string> $colors */
    /** @var int[] $allYears */
    /** @var int $selectedYear */
    /** @var array<string,array<string,int|float>> $snapshot */
    /** @var array<string,array<int,int>> $populationTrend */
    /** @var array<string,array<int,int>> $householdsTrend */
    /** @var array<string,array<int,int>> $maleTrend */
    /** @var array<string,array<int,int>> $femaleTrend */
    /** @var array<string,array<int,int>> $benefTrend */
    /** @var array<string,array<int,float|null>> $growthRates */
    /** @var array|null $anovaPopResult */
    /** @var array|null $anovaBenefResult */
    /** @var array $correlations */
    /** @var array $insights */
    /** @var array $recommendations */
    /** @var string $fastest */
    /** @var string $domAge */
    /** @var string $topProgram */
    /** @var array $progTotals */
    $muniColors = ['Magdalena'=>'#2C3E8F','Liliw'=>'#FDB913','Majayjay'=>'#28a745'];
    $totalPop   = array_sum(array_map(fn($n)=>$snapshot[$n]['population'],   $coreNames));
    $totalHH    = array_sum(array_map(fn($n)=>$snapshot[$n]['households'],   $coreNames));
    $totalBenef = array_sum(array_map(fn($n)=>$snapshot[$n]['beneficiaries'],$coreNames));
    $popArr = array_map(fn($n)=>$snapshot[$n]['population'],   $coreNames); arsort($popArr); $highPop=array_keys($popArr)[0]??'';
    $benArr = array_map(fn($n)=>$snapshot[$n]['beneficiaries'],$coreNames); arsort($benArr); $highBen=array_keys($benArr)[0]??'';
    $hhArr  = array_map(fn($n)=>$snapshot[$n]['households'],   $coreNames); arsort($hhArr);  $highHH=array_keys($hhArr)[0]??'';
@endphp

{{-- SECTION 1: DESCRIPTIVE ANALYSIS --}}
<section class="section-wrap">
    <div class="container">
        <h2 class="sec-title">1. Descriptive Analysis</h2>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-base text-center">
                    <div class="stat-num">{{ number_format($totalPop) }}</div>
                    <div class="stat-lbl">Total Population ({{ $selectedYear }})</div>
                    <div style="font-size:.78rem;color:#22c55e;margin-top:4px;">Highest: {{ $highPop }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-base y text-center">
                    <div class="stat-num">{{ number_format($totalHH) }}</div>
                    <div class="stat-lbl">Total Households</div>
                    <div style="font-size:.78rem;color:#22c55e;margin-top:4px;">Highest: {{ $highHH }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-base g text-center">
                    <div class="stat-num">{{ number_format($totalBenef) }}</div>
                    <div class="stat-lbl">Total Beneficiaries</div>
                    <div style="font-size:.78rem;color:#22c55e;margin-top:4px;">Highest: {{ $highBen }}</div>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Population, Households & Beneficiaries per Municipality</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Grouped bar chart — {{ $selectedYear }}</p>
                    <div class="chart-box"><canvas id="descBar"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-base h-100">
                    <h6 style="font-weight:700;color:var(--blue);">Key Differences</h6>
                    <div class="table-responsive mt-2">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Municipality</th><th class="text-end">Population</th><th class="text-end">Benef. %</th></tr></thead>
                            <tbody>
                                @foreach($coreNames as $n)
                                <tr>
                                    <td><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}</td>
                                    <td class="text-end fw-bold">{{ number_format($snapshot[$n]['population']) }}</td>
                                    <td class="text-end"><span style="color:var(--blue);font-weight:600;">{{ $snapshot[$n]['benef_pct'] }}%</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid #f1f5f9;">
                        <p style="font-size:.8rem;color:#64748b;margin:0;">
                            <strong>{{ $highPop }}</strong> leads in population.
                            <strong>{{ $highBen }}</strong> has the most welfare beneficiaries.
                            Beneficiary rates range from {{ min(array_map(fn($n)=>$snapshot[$n]['benef_pct'],$coreNames)) }}% to {{ max(array_map(fn($n)=>$snapshot[$n]['benef_pct'],$coreNames)) }}%.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 2: POPULATION GROWTH --}}
<section class="section-wrap alt">
    <div class="container">
        <h2 class="sec-title">2. Population Growth Analysis</h2>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Population per Year per Municipality</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Line chart — all recorded years</p>
                    <div class="chart-box tall"><canvas id="popTrend"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-base h-100">
                    <h6 style="font-weight:700;color:var(--blue);">Growth Rates</h6>
                    @foreach($coreNames as $n)
                        @php
                            $rates = array_filter($growthRates[$n], fn($v) => $v !== null);
                            $avg   = count($rates) > 0 ? round(array_sum($rates)/count($rates),2) : 0;
                        @endphp
                        <div style="margin-bottom:18px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                <span style="font-weight:600;font-size:.88rem;color:#1e293b;">
                                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}
                                </span>
                                <span style="font-weight:700;color:{{ $avg>=0?'#16a34a':'#dc2626' }};font-size:.9rem;">{{ $avg >= 0 ? '+' : '' }}{{ $avg }}%</span>
                            </div>
                            <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                <div style="height:100%;background:{{ $muniColors[$n] }};border-radius:20px;width:{{ min(abs($avg)/5*100,100) }}%;"></div>
                            </div>
                            <div style="font-size:.75rem;color:#94a3b8;margin-top:3px;">Avg. yearly growth</div>
                        </div>
                    @endforeach
                    <p style="font-size:.8rem;color:#64748b;margin:0;padding-top:10px;border-top:1px solid #f1f5f9;">
                        <strong>{{ $fastest }}</strong> shows the fastest average population growth among the three municipalities.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 3: GENDER TREND --}}
<section class="section-wrap">
    <div class="container">
        <h2 class="sec-title">3. Gender Trend Analysis</h2>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Male vs Female Trend Over Years</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Combined across all municipalities</p>
                    <div class="chart-box tall"><canvas id="genderTrend"></canvas></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-base h-100">
                    <h6 style="font-weight:700;color:var(--blue);">Male vs Female per Municipality</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">{{ $selectedYear }}</p>
                    <div class="chart-box"><canvas id="genderBar"></canvas></div>
                    @php
                        $totalM = array_sum(array_map(fn($n)=>$snapshot[$n]['male'],  $coreNames));
                        $totalF = array_sum(array_map(fn($n)=>$snapshot[$n]['female'],$coreNames));
                    @endphp
                    <div style="margin-top:14px;padding-top:12px;border-top:1px solid #f1f5f9;font-size:.8rem;color:#64748b;">
                        <strong>Total Male:</strong> {{ number_format($totalM) }} &nbsp;|&nbsp;
                        <strong>Total Female:</strong> {{ number_format($totalF) }}<br>
                        {{ $totalM > $totalF ? 'Male-dominant across municipalities.' : ($totalF > $totalM ? 'Female-dominant across municipalities.' : 'Balanced gender distribution.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 4: AGE GROUP --}}
<section class="section-wrap alt">
    <div class="container">
        <h2 class="sec-title">4. Age Group Analysis</h2>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Age Groups per Municipality (Stacked)</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Youth (0–19), Working Age (20–59), Senior (60+) — {{ $selectedYear }}</p>
                    <div class="chart-box tall"><canvas id="ageStacked"></canvas></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-base h-100">
                    <h6 style="font-weight:700;color:var(--blue);">Dependency Ratios</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">(Youth + Senior) / Working Age × 100</p>
                    @foreach($coreNames as $n)
                        <div style="margin-bottom:18px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                <span style="font-weight:600;font-size:.88rem;">
                                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}
                                </span>
                                <span style="font-weight:700;color:var(--blue);">{{ $snapshot[$n]['dependency_ratio'] }}%</span>
                            </div>
                            <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                <div style="height:100%;border-radius:20px;background:{{ $muniColors[$n] }};width:{{ min($snapshot[$n]['dependency_ratio'],100) }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                    <div style="padding-top:12px;border-top:1px solid #f1f5f9;">
                        <p style="font-size:.8rem;color:#64748b;margin:0;line-height:1.6;">
                            Dominant age group: <strong>{{ $domAge }}</strong>.<br>
                            {{ $domAge === 'Youth (0–19)' ? 'Population is youthful — invest in education and livelihood programs.' : ($domAge === 'Working Age (20–59)' ? 'Productive population — strong labor force, moderate dependency.' : 'Aging population — prioritize elder care and pension services.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 5: HOUSEHOLD VS POPULATION --}}
<section class="section-wrap">
    <div class="container">
        <h2 class="sec-title">5. Household vs Population Analysis</h2>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Population vs Households Combo Chart</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Bars = Population, Line = Households — {{ $selectedYear }}</p>
                    <div class="chart-box tall"><canvas id="hhCombo"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card-base h-100">
                    <h6 style="font-weight:700;color:var(--blue);">Average Household Size</h6>
                    @foreach($coreNames as $n)
                        <div style="margin-bottom:20px;">
                            <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                <span style="font-weight:600;font-size:.88rem;">
                                    <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $muniColors[$n] }};margin-right:5px;"></span>{{ $n }}
                                </span>
                                <span style="font-weight:800;color:{{ $muniColors[$n] }};">{{ $snapshot[$n]['avg_hh_size'] }} <small style="color:#94a3b8;font-weight:500;">persons/hh</small></span>
                            </div>
                            <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                <div style="height:100%;border-radius:20px;background:{{ $muniColors[$n] }};width:{{ min($snapshot[$n]['avg_hh_size']/10*100,100) }}%;"></div>
                            </div>
                            <div style="font-size:.75rem;color:#94a3b8;margin-top:3px;">{{ number_format($snapshot[$n]['households']) }} households · {{ number_format($snapshot[$n]['population']) }} pop.</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION 6: PROGRAM BENEFICIARIES --}}
<section class="section-wrap alt">
    <div class="container">
        <h2 class="sec-title">6. Program Beneficiaries Analysis</h2>
        <div class="row g-4 mb-4">
            <div class="col-md-4"><div class="card-base text-center">
                <div class="stat-num" style="font-size:1.5rem;">{{ $topProgram }}</div>
                <div class="stat-lbl">Highest Demand Program</div>
                <div style="font-size:.78rem;color:#22c55e;margin-top:4px;">{{ number_format($progTotals[$topProgram]) }} total beneficiaries</div>
            </div></div>
            @foreach($coreNames as $n)
            <div class="col-md-{{ count($coreNames) == 3 ? '2-2' : '4' }}" style="flex:1;">
                <div class="card-base text-center" style="border-top:4px solid {{ $muniColors[$n] }};">
                    <div style="font-weight:700;color:var(--blue);margin-bottom:8px;">{{ $n }}</div>
                    <div style="font-size:1.4rem;font-weight:800;color:var(--blue);">{{ number_format($snapshot[$n]['beneficiaries']) }}</div>
                    <div class="stat-lbl">Total Beneficiaries</div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Programs per Municipality (Stacked)</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">PWD, AICS, Solo Parent, 4Ps, Senior — {{ $selectedYear }}</p>
                    <div class="chart-box tall"><canvas id="benefStacked"></canvas></div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card-base">
                    <h6 style="font-weight:700;color:var(--blue);">Beneficiaries Trend (Yearly)</h6>
                    <p style="color:#94a3b8;font-size:.8rem;margin-bottom:16px;">Total beneficiaries over all years</p>
                    <div class="chart-box tall"><canvas id="benefTrend"></canvas></div>
                </div>
            </div>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-hover mb-0" style="background:#f8fafc;border-radius:12px;overflow:hidden;">
                <thead><tr>
                    <th>Program</th>
                    @foreach($coreNames as $n)<th class="text-center">{{ $n }}</th>@endforeach
                    <th class="text-center">Total</th>
                    <th>Highest</th>
                </tr></thead>
                <tbody>
                    @php
                        $programs6 = [
                            'PWD'        => array_map(fn($n) => $snapshot[$n]['pwd'],        $coreNames),
                            'AICS'       => array_map(fn($n) => $snapshot[$n]['aics'],       $coreNames),
                            'Solo Parent'=> array_map(fn($n) => $snapshot[$n]['solo_parent'],$coreNames),
                            '4Ps'        => array_map(fn($n) => $snapshot[$n]['four_ps'],    $coreNames),
                            'Senior'     => array_map(fn($n) => $snapshot[$n]['senior'],     $coreNames),
                        ];
                    @endphp
                    @foreach($programs6 as $prog => $vals)
                        @php $tot6 = array_sum($vals); $maxV = !empty($vals) ? max($vals) : 0; $maxIdx = (int) array_search($maxV, $vals); @endphp
                        <tr>
                            <td><strong>{{ $prog }}</strong></td>
                            @foreach($vals as $i => $v)
                                <td class="text-center" style="{{ $v == $maxV ? 'font-weight:700;color:var(--blue);' : '' }}">{{ number_format($v) }}</td>
                            @endforeach
                            <td class="text-center fw-bold">{{ number_format($tot6) }}</td>
                            <td><span style="background:var(--blue-lt);color:var(--blue);border-radius:10px;padding:2px 10px;font-size:.78rem;font-weight:700;">{{ $coreNames[$maxIdx] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- SECTION 7: ANOVA --}}
<section class="section-wrap">
    <div class="container">
        <h2 class="sec-title">7. ANOVA Analysis</h2>
        <p style="color:#64748b;font-size:.9rem;margin-top:-16px;margin-bottom:24px;">One-way ANOVA tests whether significant differences exist among municipalities (α = 0.05).</p>
        <div class="row g-4">
            @foreach([['Population','anovaPopResult'],['Beneficiaries','anovaBenefResult']] as [$label,$var])
                @php $res = $$var; @endphp
                <div class="col-lg-6">
                    <div class="card-base">
                        <h6 style="font-weight:700;color:var(--blue);">ANOVA — {{ $label }}</h6>
                        @if($res)
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div>
                                    <div style="font-size:.75rem;color:#94a3b8;">F-Statistic</div>
                                    <div style="font-size:1.6rem;font-weight:800;color:var(--blue);">{{ $res['F'] }}</div>
                                </div>
                                <div>
                                    <div style="font-size:.75rem;color:#94a3b8;">df (between / within)</div>
                                    <div style="font-size:1.1rem;font-weight:700;color:#334155;">{{ $res['dfBetween'] }} / {{ $res['dfWithin'] }}</div>
                                </div>
                                <div class="ms-auto">
                                    @if($res['significant'])
                                        <span class="badge-sig">Significant (p &lt; 0.05)</span>
                                    @else
                                        <span class="badge-nosig">Not Significant (p &gt; 0.05)</span>
                                    @endif
                                </div>
                            </div>
                            <p style="font-size:.83rem;color:#475569;margin-bottom:16px;">
                                @if($res['significant'])
                                    There are statistically significant differences in {{ strtolower($label) }} among the three municipalities. The observed differences are unlikely due to chance.
                                @else
                                    No statistically significant difference in {{ strtolower($label) }} was detected. The municipalities perform similarly relative to the variability in data.
                                @endif
                            </p>
                            <div style="margin-top:8px;">
                                <div style="font-size:.75rem;color:#94a3b8;margin-bottom:6px;">Group Means</div>
                                @foreach($coreNames as $i => $n)
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                                        <span style="width:70px;font-size:.82rem;font-weight:600;">{{ $n }}</span>
                                        <div style="flex:1;background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                            @php $maxMean = max($res['groupMeans']); @endphp
                                            <div style="height:100%;background:{{ $muniColors[$n] }};border-radius:20px;width:{{ $maxMean>0?round($res['groupMeans'][$i]/$maxMean*100):0 }}%;"></div>
                                        </div>
                                        <span style="font-size:.82rem;font-weight:700;color:var(--blue);width:70px;text-align:right;">{{ number_format($res['groupMeans'][$i]) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="color:#94a3b8;font-size:.85rem;">Insufficient data to perform ANOVA. At least 2 data points per group required.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 8: CORRELATION --}}
<section class="section-wrap alt">
    <div class="container">
        <h2 class="sec-title">8. Correlation Analysis</h2>
        <p style="color:#64748b;font-size:.9rem;margin-top:-16px;margin-bottom:24px;">Pearson correlation (r) measures strength and direction of linear relationships.</p>
        <div class="row g-4">
            @foreach($correlations as $i => $corr)
                @php
                    $r = $corr['r'];
                    $abs = $r !== null ? abs($r) : 0;
                    $badge = $abs>=0.7 ? 'badge-strong' : ($abs>=0.4 ? 'badge-moderate' : 'badge-weak');
                    $color = $abs>=0.7 ? '#1e40af' : ($abs>=0.4 ? '#0369a1' : '#475569');
                @endphp
                <div class="col-lg-4">
                    <div class="card-base h-100">
                        <h6 style="font-weight:700;color:var(--blue);font-size:.92rem;">{{ $corr['label'] }}</h6>
                        <div class="d-flex align-items-center gap-3 my-3">
                            <div style="font-size:2rem;font-weight:800;color:{{ $color }};">{{ $r !== null ? $r : 'N/A' }}</div>
                            <div>
                                <span class="{{ $badge }}">{{ $corr['strength'] }}</span>
                                <div style="font-size:.75rem;color:#94a3b8;margin-top:3px;">Pearson r</div>
                            </div>
                        </div>
                        <div style="background:#f1f5f9;border-radius:20px;height:10px;overflow:hidden;margin-bottom:12px;">
                            <div style="height:100%;border-radius:20px;background:{{ $abs>=0.7?'#2C3E8F':($abs>=0.4?'#FDB913':'#94a3b8') }};width:{{ round($abs*100) }}%;"></div>
                        </div>
                        <p style="font-size:.8rem;color:#64748b;margin:0;">
                            @if($r === null) Insufficient data for correlation.
                            @elseif($abs >= 0.7) <strong>Strong {{ $r >= 0 ? 'positive' : 'negative' }}</strong> relationship — as {{ $corr['xLabel'] }} {{ $r>=0?'increases':'decreases' }}, {{ $corr['yLabel'] }} {{ $r>=0?'increases':'decreases' }} significantly.
                            @elseif($abs >= 0.4) <strong>Moderate {{ $r >= 0 ? 'positive' : 'negative' }}</strong> relationship detected between {{ $corr['xLabel'] }} and {{ $corr['yLabel'] }}.
                            @else <strong>Weak</strong> relationship — {{ $corr['xLabel'] }} and {{ $corr['yLabel'] }} show little linear dependency.
                            @endif
                        </p>
                        <div style="margin-top:14px;">
                            <canvas id="corrChart{{ $i }}" height="120"></canvas>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 9: KEY INSIGHTS --}}
<section class="section-wrap dark">
    <div class="container">
        <h2 class="sec-title light">9. Key Insights</h2>
        <div class="row g-3">
            @foreach($insights as $i => $insight)
                <div class="col-md-6">
                    <div class="insight-card">
                        <div style="font-size:.7rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#FDB913;margin-bottom:6px;">Finding {{ $i+1 }}</div>
                        <p style="color:rgba(255,255,255,.88);font-size:.88rem;line-height:1.65;margin:0;">{{ $insight }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 10: RECOMMENDATIONS --}}
<section class="section-wrap">
    <div class="container">
        <h2 class="sec-title">10. Recommendations</h2>
        <div class="row g-3">
            @foreach($recommendations as $rec)
                <div class="col-md-6">
                    <div class="rec-card">
                        <div style="font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase;color:var(--blue);margin-bottom:6px;">{{ $rec['label'] }}</div>
                        <p style="font-size:.88rem;color:#475569;margin:0;line-height:1.65;">{{ $rec['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="footer-strip">MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const MUNIS  = @json($coreNames);
    const COLORS = @json($colors);
    const YEARS  = @json($allYears);
    const SNAP   = @json($snapshot);
    const POP    = @json($populationTrend);
    const HH     = @json($householdsTrend);
    const MALE   = @json($maleTrend);
    const FEMALE = @json($femaleTrend);
    const BENEF  = @json($benefTrend);
    const PWD    = @json($pwdTrend);
    const AICS   = @json($aicsTrend);
    const SOLO   = @json($soloTrend);
    const FPS    = @json($fpsTrend);
    const SENIOR = @json($seniorTrend);
    const CORRS  = @json($correlations);

    const opts = (extra={}) => ({responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>v.toLocaleString()}},x:{grid:{display:false}}},...extra});

    // Section 1 – Descriptive Bar
    new Chart(document.getElementById('descBar'),{type:'bar',data:{labels:MUNIS,datasets:[
        {label:'Population',data:MUNIS.map(m=>SNAP[m]?.population??0),backgroundColor:'#2C3E8F',borderRadius:6},
        {label:'Households',data:MUNIS.map(m=>SNAP[m]?.households??0),backgroundColor:'#FDB913',borderRadius:6},
        {label:'Beneficiaries',data:MUNIS.map(m=>SNAP[m]?.beneficiaries??0),backgroundColor:'#28a745',borderRadius:6},
    ]},options:opts()});

    // Section 2 – Population Trend
    new Chart(document.getElementById('popTrend'),{type:'line',data:{labels:YEARS,datasets:MUNIS.map(m=>({label:m,data:YEARS.map(y=>POP[m]?.[y]??0),borderColor:COLORS[m],backgroundColor:COLORS[m]+'22',fill:true,tension:.4,borderWidth:3,pointRadius:5}))},options:opts({plugins:{legend:{position:'top'},tooltip:{mode:'index',intersect:false}}})});

    // Section 3 – Gender Trend
    const maleTot  = YEARS.map(y=>MUNIS.reduce((s,m)=>s+(MALE[m]?.[y]??0),0));
    const femTot   = YEARS.map(y=>MUNIS.reduce((s,m)=>s+(FEMALE[m]?.[y]??0),0));
    new Chart(document.getElementById('genderTrend'),{type:'line',data:{labels:YEARS,datasets:[
        {label:'Male (All)',  data:maleTot, borderColor:'#2C3E8F',backgroundColor:'#2C3E8F22',fill:true,tension:.4,borderWidth:3,pointRadius:5},
        {label:'Female (All)',data:femTot, borderColor:'#FDB913', backgroundColor:'#FDB91322',fill:true,tension:.4,borderWidth:3,pointRadius:5},
    ]},options:opts({plugins:{legend:{position:'top'},tooltip:{mode:'index',intersect:false}}})});

    new Chart(document.getElementById('genderBar'),{type:'bar',data:{labels:MUNIS,datasets:[
        {label:'Male',  data:MUNIS.map(m=>SNAP[m]?.male??0),  backgroundColor:'#2C3E8F',borderRadius:6},
        {label:'Female',data:MUNIS.map(m=>SNAP[m]?.female??0),backgroundColor:'#FDB913',borderRadius:6},
    ]},options:opts()});

    // Section 4 – Age Stacked
    new Chart(document.getElementById('ageStacked'),{type:'bar',data:{labels:MUNIS,datasets:[
        {label:'Youth (0-19)',      data:MUNIS.map(m=>SNAP[m]?.age_0_19??0),  backgroundColor:'#2C3E8F',borderRadius:4},
        {label:'Working Age (20-59)',data:MUNIS.map(m=>SNAP[m]?.age_20_59??0),backgroundColor:'#FDB913',borderRadius:4},
        {label:'Senior (60+)',      data:MUNIS.map(m=>SNAP[m]?.age_60_100??0),backgroundColor:'#28a745',borderRadius:4},
    ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top'}},scales:{y:{stacked:true,beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>v.toLocaleString()}},x:{stacked:true,grid:{display:false}}}}});

    // Section 5 – HH Combo
    new Chart(document.getElementById('hhCombo'),{data:{labels:MUNIS,datasets:[
        {type:'bar', label:'Population',data:MUNIS.map(m=>SNAP[m]?.population??0),backgroundColor:'#2C3E8F88',borderRadius:6,yAxisID:'y'},
        {type:'line',label:'Households', data:MUNIS.map(m=>SNAP[m]?.households??0),borderColor:'#FDB913',backgroundColor:'#FDB91322',borderWidth:3,tension:.4,pointRadius:7,yAxisID:'y2'},
    ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true,position:'left',grid:{color:'#f1f5f9'},ticks:{callback:v=>v.toLocaleString()}},y2:{beginAtZero:true,position:'right',grid:{drawOnChartArea:false},ticks:{callback:v=>v.toLocaleString()}},x:{grid:{display:false}}}}});

    // Section 6 – Benef Stacked
    new Chart(document.getElementById('benefStacked'),{type:'bar',data:{labels:MUNIS,datasets:[
        {label:'PWD',         data:MUNIS.map(m=>SNAP[m]?.pwd??0),        backgroundColor:'#2C3E8F',borderRadius:4},
        {label:'AICS',        data:MUNIS.map(m=>SNAP[m]?.aics??0),       backgroundColor:'#FDB913',borderRadius:4},
        {label:'Solo Parent', data:MUNIS.map(m=>SNAP[m]?.solo_parent??0),backgroundColor:'#C41E24',borderRadius:4},
        {label:'4Ps',         data:MUNIS.map(m=>SNAP[m]?.four_ps??0),    backgroundColor:'#28a745',borderRadius:4},
        {label:'Senior',      data:MUNIS.map(m=>SNAP[m]?.senior??0),     backgroundColor:'#8B5CF6',borderRadius:4},
    ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'top'}},scales:{y:{stacked:true,beginAtZero:true,grid:{color:'#f1f5f9'},ticks:{callback:v=>v.toLocaleString()}},x:{stacked:true,grid:{display:false}}}}});

    // Section 6 – Benef Trend
    new Chart(document.getElementById('benefTrend'),{type:'line',data:{labels:YEARS,datasets:MUNIS.map(m=>({label:m,data:YEARS.map(y=>BENEF[m]?.[y]??0),borderColor:COLORS[m],backgroundColor:COLORS[m]+'22',fill:true,tension:.4,borderWidth:3,pointRadius:5}))},options:opts({plugins:{legend:{position:'top'},tooltip:{mode:'index',intersect:false}}})});

    // Section 8 – Correlation scatter/bar
    CORRS.forEach((c,i) => {
        const ctx = document.getElementById('corrChart'+i);
        if (!ctx) return;
        new Chart(ctx,{type:'bar',data:{labels:c.xData.map((_,j)=>'P'+(j+1)),datasets:[
            {label:c.xLabel,data:c.xData,backgroundColor:'#2C3E8F55',borderRadius:3},
            {label:c.yLabel,data:c.yData,backgroundColor:'#FDB91388',borderRadius:3},
        ]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{size:10}}}},scales:{y:{beginAtZero:true,ticks:{font:{size:9},callback:v=>v.toLocaleString()}},x:{ticks:{font:{size:9}},grid:{display:false}}}}});
    });
</script>

@auth
@if(Auth::user()->isSuperAdmin())
<style>
    .admin-back-btn{position:fixed;bottom:28px;left:28px;z-index:9999;display:flex;align-items:center;gap:10px;background:var(--grad);color:#fff;border:none;border-radius:50px;padding:12px 22px 12px 16px;font-family:'Inter',sans-serif;font-weight:800;font-size:.85rem;box-shadow:0 8px 28px rgba(44,62,143,.4);cursor:pointer;text-decoration:none;transition:all .3s;}
    .admin-back-btn:hover{transform:translateY(-4px);color:#fff;}
</style>
<a href="{{ route('superadmin.dashboard') }}" class="admin-back-btn">&#8592; Super Admin Dashboard</a>
@elseif(Auth::user()->isAdmin())
<style>
    .admin-back-btn{position:fixed;bottom:28px;left:28px;z-index:9999;display:flex;align-items:center;gap:10px;background:linear-gradient(135deg,#FDB913,#E5A500);color:#1A2A5C;border:none;border-radius:50px;padding:12px 22px 12px 16px;font-family:'Inter',sans-serif;font-weight:800;font-size:.85rem;box-shadow:0 8px 28px rgba(253,185,19,.45);cursor:pointer;text-decoration:none;transition:all .3s;}
    .admin-back-btn:hover{transform:translateY(-4px);color:#1A2A5C;}
</style>
<a href="{{ route('admin.dashboard') }}" class="admin-back-btn">&#8592; Admin Dashboard</a>
@endif
@endauth

</body>
</html>
