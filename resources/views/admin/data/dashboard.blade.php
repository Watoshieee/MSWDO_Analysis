@php
    use App\Models\Municipality;
    use App\Models\Barangay;
    use App\Models\SocialWelfareProgram;
    use App\Models\MunicipalityYearlySummary;

    $yearsList = $allYears ?? [2024];
    $cYear = (int)($currentYear ?? (in_array(2024, $yearsList) ? 2024 : ($yearsList[0] ?? date('Y'))));
    $idx = array_search($cYear, $yearsList);
    $prevYear = ($idx !== false && isset($yearsList[$idx + 1])) ? $yearsList[$idx + 1] : null;

    $currSum = $yearlySummaries[$cYear] ?? ($currentSummary ?? null);
    $prevSum = $prevYear ? ($yearlySummaries[$prevYear] ?? null) : null;

    $popCurr = (int)($currSum->total_population ?? $totalPopulation ?? 0);
    $popPrev = $prevSum ? (int)$prevSum->total_population : null;
    $popDiff = ($popPrev !== null) ? ($popCurr - $popPrev) : null;

    $hhCurr = (int)($currSum->total_households ?? $totalHouseholds ?? 0);
    $hhPrev = $prevSum ? (int)$prevSum->total_households : null;
    $hhDiff = ($hhPrev !== null) ? ($hhCurr - $hhPrev) : null;

    $progCount = (int)($programs ?? 0);
    $beneCount = (int)($beneficiaries ?? 0);

    $revYears = array_reverse($yearsList);
    $popHistory = [];
    $hhHistory = [];
    foreach ($revYears as $y) {
        $s = $yearlySummaries[$y] ?? null;
        $popHistory[] = $s ? (int)$s->total_population : 0;
        $hhHistory[] = $s ? (int)$s->total_households : 0;
    }

    $minPop = !empty($popHistory) ? min($popHistory) : 0;
    $maxPop = !empty($popHistory) ? max($popHistory) : 1;
    $rangePop = max(1, $maxPop - $minPop);

    $minHh = !empty($hhHistory) ? min($hhHistory) : 0;
    $maxHh = !empty($hhHistory) ? max($hhHistory) : 1;
    $rangeHh = max(1, $maxHh - $minHh);

    $totalPoints = count($revYears);
    $popSparkPoints = [];
    $hhSparkPoints = [];
    foreach ($revYears as $i => $y) {
        $x = 4 + ($totalPoints > 1 ? ($i / ($totalPoints - 1)) * 76 : 38);
        $yPop = 24 - (($popHistory[$i] - $minPop) / $rangePop) * 20;
        $yHh = 24 - (($hhHistory[$i] - $minHh) / $rangeHh) * 20;
        $popSparkPoints[] = [$x, $yPop, $y === $cYear];
        $hhSparkPoints[] = [$x, $yHh, $y === $cYear];
    }

    $maxBarangayPop = ($barangays && $barangays->isNotEmpty()) ? ($barangays->max('total_population') ?: ($barangays->max('population') ?: 1)) : 1;
    if ($maxBarangayPop <= 0) $maxBarangayPop = 1;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management — MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.admin-colors')
    <style>
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            overscroll-behavior: none;
        }
/* Tokens taken from the existing MSWDO dashboard: navy, gold, Inter. Add your Inter font link when moving this into the project. */
:root{--navy:#1e2a78;--navy-d:#1a2470;--navy-l:#243088;--gold:#f5b82e;--gold-h:#ffc94d;--ink:#1b2559;--muted:#4d5878;--bg:#f4f6fb;--line:#e1e6f2;--soft:#eef1f8;--tint:#eef1fb}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.5 Inter,system-ui,-apple-system,'Segoe UI',sans-serif}
a{color:var(--navy);text-decoration:none}
button,input{font:inherit;color:inherit}
h1,h2,h3,p{margin:0}
:focus-visible{outline:2px solid var(--gold);outline-offset:2px}
.in{max-width:1280px;margin:0 auto;padding:0 32px}


/* page band */
.band{background:linear-gradient(135deg,var(--navy-d),var(--navy-l));color:#fff}
.band .in{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;padding-top:22px;padding-bottom:22px}
.band h1{font-size:24px;font-weight:800;letter-spacing:-.01em}
.band p{margin-top:6px;color:#d3d9f5;font-size:13.5px;max-width:560px}
.muni{border:1px solid rgba(245,184,46,.45);background:rgba(245,184,46,.1);border-radius:10px;padding:8px 18px;text-align:center;color:var(--gold)}
.muni b{display:block;font-size:17px;font-weight:800}
.muni span{font-size:11px;font-weight:600}

main.in{padding-top:32px;padding-bottom:40px}
section+section,.cols{margin-top:36px}
.sh{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:16px}
.sh h2{font-size:17px;font-weight:800;letter-spacing:-.005em}
.sh h2::after{content:"";display:block;width:28px;height:3px;border-radius:3px;background:var(--gold);margin-top:6px}
.sh p{color:var(--muted);font-size:12.5px;margin-top:5px}
.tools{display:flex;gap:8px;flex-wrap:wrap;align-items:center}

/* buttons */
.btn{display:inline-flex;align-items:center;gap:6px;height:36px;white-space:nowrap;padding:0 14px;border-radius:9px;border:1px solid var(--navy);background:var(--navy);color:#fff;font-size:12.5px;font-weight:700;cursor:pointer;transition:background .15s,color .15s,transform .15s,box-shadow .15s}
.btn:hover{background:var(--navy-d);box-shadow:0 3px 10px rgba(30,42,120,.25)}
.btn:active{transform:translateY(1px)}
.btn.ghost{background:#fff;color:var(--navy);border-color:#cfd6ec}
.btn.ghost:hover{background:var(--tint);box-shadow:none}
.btn.gold{background:var(--gold);border-color:var(--gold);color:#172066}
.btn.gold:hover{background:var(--gold-h)}
.seg{display:inline-flex;box-shadow:inset 0 1px 2px rgba(23,32,102,.12);background:#e6eaf6;border-radius:10px;padding:3px;gap:2px}
.seg button{border:0;background:transparent;height:30px;padding:0 14px;border-radius:8px;font-size:12.5px;font-weight:700;color:var(--muted);cursor:pointer;transition:background .15s,color .15s}
.seg button:hover{color:var(--navy)}
.seg{position:relative}
.seg .thumb{position:absolute;top:3px;bottom:3px;left:0;width:0;border-radius:8px;background:linear-gradient(135deg,var(--navy-d),var(--navy-l));box-shadow:0 2px 6px rgba(23,32,102,.35);transition:transform .28s cubic-bezier(.2,.8,.2,1),width .28s cubic-bezier(.2,.8,.2,1)}
.seg button{position:relative;z-index:1}
.seg button[aria-pressed="true"]{color:#fff}
.stat{animation:rise .35s ease-out both}
.stat:nth-child(2){animation-delay:.04s}.stat:nth-child(3){animation-delay:.08s}.stat:nth-child(4){animation-delay:.12s}
.exm{position:relative}
.exm .btn svg{flex:none}
.exm .btn .chev{transition:transform .15s}
.exm .btn[aria-expanded="true"] .chev{transform:rotate(180deg)}
.exm-menu{position:absolute;right:0;top:calc(100% + 8px);z-index:30;width:270px;background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:0 12px 28px rgba(20,27,77,.22);padding:6px}
.exm-menu[hidden]{display:none}
.exm-menu button{display:flex;align-items:center;gap:12px;width:100%;border:0;background:none;text-align:left;padding:10px;border-radius:8px;cursor:pointer;transition:background .12s}
.exm-menu button:hover,.exm-menu button:focus-visible{background:var(--bg)}
.exm-menu .mi{flex:none;width:34px;height:34px;border-radius:9px;background:var(--tint);color:var(--navy);display:grid;place-items:center}
.exm-menu b{display:block;font-size:13px;font-weight:700;color:var(--ink);line-height:1.3}
.exm-menu small{display:block;font-size:12px;color:var(--muted)}
@keyframes rise{from{opacity:.35;transform:translateY(5px)}to{opacity:1;transform:none}}

/* overview */
.stats{display:grid;grid-template-columns:repeat(4,1fr);background:#fff;border:1px solid #dbe1f1;border-top:3px solid var(--navy);border-radius:14px;overflow:hidden;box-shadow:0 1px 2px rgba(23,32,102,.06),0 8px 20px -10px rgba(23,32,102,.18)}
.stat{padding:18px 22px 16px;border-left:1px solid var(--soft)}
.stat:first-child{border-left:0;background:#f6f8fe}
.stat span{display:block;font-size:12.5px;font-weight:600;color:var(--muted)}
.stat b{display:block;font-size:30px;line-height:1.15;font-weight:800;color:var(--navy);margin:4px 0 9px;letter-spacing:-.015em;font-variant-numeric:tabular-nums}
.stat small{display:inline-block;font-size:11.5px;font-weight:600;color:var(--muted);background:var(--soft);padding:2px 9px;border-radius:99px}
.stat small.up{background:#dcf5e6;color:#14663a}
.search input{box-shadow:0 1px 2px rgba(23,32,102,.05)}

/* search */
.search{position:relative;width:min(100%,340px)}
.search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none}
.search input{width:100%;height:40px;padding:0 12px 0 36px;border:1px solid #d5dcee;border-radius:10px;background:#fff;transition:border-color .15s,box-shadow .15s}
.search input:hover{border-color:#aab4d8}
.search input:focus{outline:0;border-color:var(--navy);box-shadow:0 0 0 3px rgba(30,42,120,.15)}
.search input::placeholder{color:#7b86a8}

/* options */
.opts{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.opt{position:relative;display:flex;flex-direction:column;gap:8px;background:#fff;border:1px solid #dbe1f1;border-radius:14px;padding:20px 22px 16px 26px;color:var(--ink);cursor:pointer;box-shadow:0 1px 2px rgba(23,32,102,.06),0 6px 16px -8px rgba(23,32,102,.14);transition:border-color .18s,box-shadow .18s,transform .18s}
.opt::before{content:"";position:absolute;left:0;top:18px;bottom:18px;width:4px;border-radius:0 4px 4px 0;background:#d5dcee;transition:background .18s,top .18s,bottom .18s}
.opt:hover{border-color:#aab6e0;transform:translateY(-2px);box-shadow:0 2px 4px rgba(23,32,102,.06),0 16px 30px -14px rgba(30,42,120,.4)}
.opt:hover::before{background:var(--gold);top:10px;bottom:10px}
.opt:active{transform:translateY(0);box-shadow:0 1px 2px rgba(23,32,102,.08)}
.top-r{display:flex;align-items:center;justify-content:space-between;gap:12px}
.ttl{display:flex;align-items:center;gap:12px;min-width:0}
.ico{flex:none;width:40px;height:40px;border-radius:11px;display:grid;place-items:center;background:linear-gradient(135deg,var(--navy-d),var(--navy-l));color:#fff;box-shadow:0 4px 10px -4px rgba(30,42,120,.55);transition:background .18s,color .18s,transform .18s}
.opt:hover .ico{background:var(--gold);color:#172066;transform:scale(1.06)}
.opt h3{font-size:18.5px;line-height:1.25;font-weight:800;color:var(--navy);letter-spacing:-.01em;padding:0;background:linear-gradient(var(--gold),var(--gold)) 0 100%/0 3px no-repeat;padding-bottom:3px;transition:background-size .22s}
.opt:hover h3{background-size:100% 3px}
.opt::before{background:#8f9ddb}
.arw{flex:none;width:30px;height:30px;border-radius:50%;border:1px solid #cfd6ec;background:#fff;color:var(--navy);display:grid;place-items:center;transition:background .18s,color .18s,border-color .18s,transform .18s}
.opt:hover .arw{background:var(--navy);border-color:var(--navy);color:#fff;transform:translateX(2px)}
.opt p{color:var(--muted);font-size:13px;line-height:1.5;max-width:54ch}
.opt .ft{display:flex;align-items:center;gap:8px;margin-top:6px;padding-top:12px;border-top:1px solid var(--soft);font-size:12.5px;color:var(--muted)}
.empty{text-align:center;padding:36px 16px;background:#fff;border:1px dashed #c5cde6;border-radius:12px;color:var(--muted)}
.empty b{display:block;color:var(--ink);font-size:14px;margin-bottom:2px}

.stat{position:relative}
.spark{position:absolute;right:18px;top:20px}
.spark polyline{fill:none;stroke:#aab6e0;stroke-width:1.6;stroke-linejoin:round;stroke-linecap:round}
.spark circle{fill:#fff;stroke:#aab6e0;stroke-width:1.4}
.spark circle.s{fill:var(--gold);stroke:var(--navy);stroke-width:1.6}
.crumb{font-size:12.5px;color:#b9c2ee;margin-bottom:8px}
.crumb a{color:#d3d9f5}.crumb a:hover{color:#fff;text-decoration:underline}
.viz{margin:2px 0 0;padding:12px 14px;background:#f6f8fe;border:1px solid #e6ebf8;border-radius:10px;transition:background .18s}
.opt:hover .viz{background:#f0f3fd}
.kv{display:flex;gap:28px}
.kv b{display:block;font-size:20px;line-height:1.2;font-weight:800;color:var(--navy);font-variant-numeric:tabular-nums}
.kv span{font-size:12px;color:var(--muted)}
.pg{display:flex;align-items:center;gap:12px;font-size:12px;color:var(--muted)}
.pg .bar{flex:1;height:8px;background:#dfe5f6;border-radius:9px;overflow:hidden}
.pg .bar i{display:block;height:100%;background:var(--navy);border-radius:9px}
.pg b{color:var(--navy);font-size:13px;white-space:nowrap}
.seg4{display:flex;height:8px;gap:2px;border-radius:9px;overflow:hidden}
.seg4 i{display:block;height:100%}
.lg{display:flex;flex-wrap:wrap;gap:4px 14px;margin-top:9px;font-size:12px;color:var(--muted)}
.lg span{display:inline-flex;align-items:center;gap:6px}
.lg em{width:8px;height:8px;border-radius:2px}
.lg b{color:var(--ink);font-weight:700}
.tl{position:relative;display:flex;justify-content:space-between;padding-top:2px}
.tl::before{content:"";position:absolute;left:14px;right:14px;top:8px;height:2px;background:#d5dcee}
.tl span{position:relative;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:12px;font-weight:600;color:var(--muted)}
.tl span::before{content:"";width:12px;height:12px;border-radius:50%;background:#fff;border:2px solid var(--navy)}
.tl span.s{color:var(--navy);font-weight:800}
.tl span.s::before{background:var(--gold)}
.mb{display:block;width:90px;height:3px;margin:5px 0 0 auto;background:#e8ecf7;border-radius:3px}
.mb i{display:block;height:100%;background:var(--navy);border-radius:3px;opacity:.75}
@media (max-width:520px){.spark{display:none}.kv{gap:20px}}
/* table + activity */
.cols{display:grid;grid-template-columns:minmax(0,8fr) minmax(0,4fr);gap:16px;align-items:start}
.card{background:#fff;border:1px solid var(--line);border-radius:12px;box-shadow:0 1px 3px rgba(23,32,102,.06);overflow:hidden}
.card>.sh{padding:16px 18px 0;margin-bottom:12px}
.tb{display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:space-between;padding:0 18px 12px}
.tb .search{width:220px}
.tb .search input{height:34px;font-size:13px}
.sc{overflow-x:auto}
table{width:100%;border-collapse:collapse;font-size:13px;min-width:560px}
th{text-align:left;font-size:12px;font-weight:700;color:var(--muted);padding:8px 18px;background:var(--bg);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
td{padding:11px 18px;border-bottom:1px solid var(--soft)}
th.r,td.r{text-align:right}
tbody tr{transition:background .12s}
tbody tr:hover{background:#f8f9fd}
.lnk{border:0;background:none;color:var(--navy);font-weight:700;cursor:pointer;padding:4px 8px;border-radius:6px}
.lnk:hover{background:var(--tint)}
.act-l{list-style:none;margin:0;padding:0 18px 8px}
.act-l li{padding:11px 0;border-top:1px solid var(--soft);font-size:13px}
.act-l li:first-child{border-top:0}
.act-l span{display:block;font-size:12px;color:var(--muted)}
.act-l li.new{animation:fl 1.6s ease-out}
@keyframes fl{from{background:#fff6dc}to{background:transparent}}

/* dialog + toast */
dialog{border:0;border-radius:14px;padding:0;width:min(420px,calc(100vw - 32px));box-shadow:0 20px 50px rgba(20,27,77,.35);color:var(--ink)}
dialog::backdrop{background:rgba(20,27,77,.5)}
dialog form{padding:20px}
dialog h2{font-size:17px;font-weight:800}
dialog p{color:var(--muted);font-size:12.5px;margin:2px 0 14px}
dialog label{display:block;font-size:12.5px;font-weight:700;margin-bottom:12px}
dialog input{display:block;width:100%;height:40px;margin-top:5px;padding:0 12px;border:1px solid #d5dcee;border-radius:10px;background:var(--bg)}
dialog input:focus{outline:0;border-color:var(--navy);background:#fff;box-shadow:0 0 0 3px rgba(30,42,120,.15)}
.dbtn{display:flex;justify-content:flex-end;gap:8px;margin-top:6px}
.toast{position:fixed;left:50%;bottom:22px;transform:translate(-50%,20px);background:#141b4d;color:#fff;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;box-shadow:0 8px 22px rgba(20,27,77,.35);opacity:0;pointer-events:none;transition:opacity .2s,transform .2s;z-index:200;max-width:calc(100vw - 32px)}
.toast.on{opacity:1;transform:translate(-50%,0)}

/* tablet + mobile */
@media (max-width:1000px){
.stats{grid-template-columns:1fr 1fr}
.stat:nth-child(3){border-left:0}
.stat:nth-child(n+3){border-top:1px solid var(--soft)}
.cols{grid-template-columns:minmax(0,1fr)}
}
@media (max-width:700px){
.in{padding:0 16px}
.opts{grid-template-columns:minmax(0,1fr)}
.stat{padding:14px 16px}
.stat b{font-size:23px}
.tools,.tools .seg,.exm{width:100%}
.tools .seg{display:grid;grid-template-columns:repeat(4,1fr)}
.exm .btn{width:100%;justify-content:center}
.exm-menu{left:0;right:0;width:auto}
.tb .btn{width:100%;justify-content:center}
.search,.tb .search{width:100%}
}
@media (max-width:520px){.band .in{padding-top:18px;padding-bottom:18px}}
@media (prefers-reduced-motion:reduce){*{transition:none!important;animation:none!important}}


.modal-backdrop { z-index: 1040 !important; }
.modal { z-index: 1050 !important; }
.seg a.seg-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 30px;
    padding: 0 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 700;
    color: var(--muted);
    text-decoration: none;
    transition: all .15s ease;
}
.seg a.seg-btn:hover { color: var(--navy); text-decoration: none; }
.seg a.seg-btn.active {
    background: linear-gradient(135deg, var(--navy-d), var(--navy-l));
    color: #fff;
    box-shadow: 0 2px 6px rgba(23,32,102,.35);
}
.opt { text-decoration: none !important; color: var(--ink); }
.opt:hover { text-decoration: none !important; color: var(--ink); }
    </style>
</head>
<body>
    @include('components.admin-navbar', ['active' => 'data'])

    <!-- Hero Band -->
    <div class="band">
        <div class="in">
            <div>
                <nav class="crumb" aria-label="Breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a> / Data Management
                </nav>
                <h1>Data Management</h1>
                <p>Update municipality profiles, barangay records, and social welfare program data for {{ $municipality->name ?? Auth::user()->municipality ?? 'Majayjay' }}.</p>
            </div>
            <div class="muni">
                <b>{{ $municipality->name ?? Auth::user()->municipality ?? 'Majayjay' }}</b>
                <span>Municipality</span>
            </div>
        </div>
    </div>

    <main class="in">
        <!-- Overview Section -->
        <section aria-labelledby="ovT">
            <div class="sh">
                <div>
                    <h2 id="ovT">Dashboard overview</h2>
                    <p id="ovNote" aria-live="polite">Showing {{ $cYear }} data (latest available records)</p>
                </div>
                <div class="tools">
                    <div class="seg" role="group" aria-label="Select year">
                        @foreach($yearsList as $yr)
                            <a href="{{ route('admin.data.dashboard', ['year' => $yr]) }}" class="seg-btn {{ $yr == $cYear ? 'active' : '' }}" title="View data for {{ $yr }}">
                                {{ $yr }}
                            </a>
                        @endforeach
                    </div>
                    <!-- Import CSV Button -->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#importModal"
                        style="background:var(--primary-blue);color:white;border:none;border-radius:8px;padding:7px 15px;font-weight:700;font-size:0.83rem;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:all 0.2s;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Import CSV
                    </button>
                    <div class="exm" id="exm">
                        <button class="btn ghost" id="expBtn" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="expMenu">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export
                            <svg class="chev" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="exm-menu" id="expMenu" role="menu" hidden>
                            <button id="expCsvBtn" type="button" role="menuitem">
                                <span class="mi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="3" x2="9" y2="21"/></svg></span>
                                <span><b>Yearly figures</b><small>CSV data package</small></span>
                            </button>
                            <button id="expReportBtn" type="button" role="menuitem">
                                <span class="mi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg></span>
                                <span><b>Analysis report</b><small>Excel (.xlsx) workbook</small></span>
                            </button>
                            <button id="expMoreBtn" type="button" role="menuitem">
                                <span class="mi"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>
                                <span><b>More export options</b><small>Comparative &amp; DSS packages</small></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats" id="stats">
                <!-- Total Population -->
                <div class="stat">
                    <span>Total Population</span>
                    <b>{{ number_format($popCurr) }}</b>
                    @if($popDiff !== null)
                        <small class="{{ $popDiff >= 0 ? 'up' : '' }}">
                            {{ $popDiff >= 0 ? '+' : '' }}{{ number_format($popDiff) }} vs {{ $prevYear }}
                        </small>
                    @else
                        <small>Official records</small>
                    @endif
                    @if(count($popSparkPoints) > 1)
                        <svg class="spark" width="84" height="28" viewBox="0 0 84 28" aria-hidden="true">
                            <polyline points="@foreach($popSparkPoints as $pt){{ $pt[0] }},{{ $pt[1] }} @endforeach"></polyline>
                            @foreach($popSparkPoints as $pt)
                                <circle class="{{ $pt[2] ? 's' : '' }}" cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="2.5"></circle>
                            @endforeach
                        </svg>
                    @endif
                </div>

                <!-- Total Households -->
                <div class="stat">
                    <span>Total Households</span>
                    <b>{{ number_format($hhCurr) }}</b>
                    @if($hhDiff !== null)
                        <small class="{{ $hhDiff >= 0 ? 'up' : '' }}">
                            {{ $hhDiff >= 0 ? '+' : '' }}{{ number_format($hhDiff) }} vs {{ $prevYear }}
                        </small>
                    @else
                        <small>Municipal summary</small>
                    @endif
                    @if(count($hhSparkPoints) > 1)
                        <svg class="spark" width="84" height="28" viewBox="0 0 84 28" aria-hidden="true">
                            <polyline points="@foreach($hhSparkPoints as $pt){{ $pt[0] }},{{ $pt[1] }} @endforeach"></polyline>
                            @foreach($hhSparkPoints as $pt)
                                <circle class="{{ $pt[2] ? 's' : '' }}" cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="2.5"></circle>
                            @endforeach
                        </svg>
                    @endif
                </div>

                <!-- Active Programs -->
                <div class="stat">
                    <span>Active Programs</span>
                    <b>{{ number_format($progCount) }}</b>
                    <small>Social services tracked</small>
                </div>

                <!-- Program Beneficiaries -->
                <div class="stat">
                    <span>Program Beneficiaries</span>
                    <b>{{ number_format($beneCount) }}</b>
                    <small>{{ $cYear }} enrolled recipients</small>
                </div>
            </div>
        </section>
        <!-- Manage Data Section / Task Cards -->
        <section id="manage-data-section" aria-labelledby="mgT">
            <div class="sh">
                <div>
                    <h2 id="mgT">What do you want to update?</h2>
                    <p id="optNote" aria-live="polite">Pick a task to open its editor.</p>
                </div>
                <label class="search">
                    <span style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">Search tasks</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5"/></svg>
                    <input id="qOpt" type="search" placeholder="Search tasks, e.g. enrollment or households" autocomplete="off">
                </label>
            </div>

            <div class="opts" id="opts">
                <!-- Card 1: Municipality Profile -->
                <a href="{{ route('admin.data.municipality') }}#return" class="opt" id="municipality-card" data-tags="municipality profile population households classification land area geography">
                    <div class="top-r">
                        <div class="ttl">
                            <span class="ico">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </span>
                            <h3>Municipality profile</h3>
                        </div>
                        <span class="arw">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </div>
                    <p>Update population, households, income classification and institutional profile for {{ $municipality->name ?? 'Majayjay' }}.</p>
                    <div class="viz">
                        <div class="kv">
                            <div><b>{{ number_format($popCurr) }}</b><span>Population</span></div>
                            <div><b>{{ number_format($hhCurr) }}</b><span>Households</span></div>
                        </div>
                    </div>
                    <div class="ft">
                        <span>Classification: <strong>{{ $municipality->income_class ?? '4th Class' }}</strong></span>
                    </div>
                </a>

                <!-- Card 2: Barangay Data -->
                <a href="{{ route('admin.data.barangays') }}#return" class="opt" id="barangay-card" data-tags="barangay data population households counts demographics records purok">
                    <div class="top-r">
                        <div class="ttl">
                            <span class="ico">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            </span>
                            <h3>Barangay data</h3>
                        </div>
                        <span class="arw">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </div>
                    <p>Manage population, households and demographic distributions for each barangay in the municipality.</p>
                    <div class="viz">
                        <div class="pg">
                            <span>Barangay records:</span>
                            <div class="bar"><i style="width: 100%;"></i></div>
                            <b>{{ $barangaysCount }} barangays</b>
                        </div>
                    </div>
                    <div class="ft">
                        <span>{{ $barangaysCount }} barangays recorded for {{ $cYear }}</span>
                    </div>
                </a>

                <!-- Card 3: Social Programs -->
                <a href="{{ route('admin.data.programs') }}#return" class="opt" id="programs-card" data-tags="programs beneficiaries enrollment pwd solo parent aics social welfare services">
                    <div class="top-r">
                        <div class="ttl">
                            <span class="ico">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </span>
                            <h3>Social programs</h3>
                        </div>
                        <span class="arw">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </div>
                    <p>Manage beneficiaries, enrollment data and annual records for each MSWDO social welfare program.</p>
                    <div class="viz">
                        @php
                            $colors = ['#1e2a78', '#f5b82e', '#2e9e6b', '#8f9ddb', '#e26d5c'];
                            $ci = 0;
                            $progTotal = max(1, array_sum($programBreakdown ?? []));
                        @endphp
                        @if(!empty($programBreakdown))
                            <div class="seg4">
                                @foreach($programBreakdown as $ptype => $pcount)
                                    <i style="width:{{ ($pcount / $progTotal) * 100 }}%; background:{{ $colors[$ci % count($colors)] }};"></i>
                                    @php $ci++; @endphp
                                @endforeach
                            </div>
                            <div class="lg">
                                @php $ci = 0; @endphp
                                @foreach(array_slice($programBreakdown, 0, 4, true) as $ptype => $pcount)
                                    <span><em style="background:{{ $colors[$ci % count($colors)] }}"></em>{{ $ptype }} <b>{{ number_format($pcount) }}</b></span>
                                    @php $ci++; @endphp
                                @endforeach
                            </div>
                        @else
                            <div class="kv">
                                <div><b>{{ number_format($progCount) }}</b><span>Programs</span></div>
                                <div><b>{{ number_format($beneCount) }}</b><span>Beneficiaries</span></div>
                            </div>
                        @endif
                    </div>
                    <div class="ft">
                        <span>{{ $progCount }} programs &bull; {{ number_format($beneCount) }} total beneficiaries</span>
                    </div>
                </a>

                <!-- Card 4: Municipality Yearly Data -->
                <a href="{{ route('admin.data.yearly') }}#return" class="opt" id="yearly-card" data-tags="yearly data annual summary kpi historical budget indicators comparison">
                    <div class="top-r">
                        <div class="ttl">
                            <span class="ico">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            </span>
                            <h3>Municipality yearly data</h3>
                        </div>
                        <span class="arw">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </span>
                    </div>
                    <p>Add and manage yearly population, household, PWD, AICS and Solo Parent annual summary records.</p>
                    <div class="viz">
                        <div class="tl">
                            @foreach(array_slice($revYears, -4) as $y)
                                <span class="{{ $y === $cYear ? 's' : '' }}">{{ $y }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="ft">
                        <span>{{ count($yearsList) }} annual historical records tracked</span>
                    </div>
                </a>
            </div>

            <div class="empty" id="none" hidden>
                <b>No matching task</b>
                Try a different search word, such as "population", "barangay" or "program".
            </div>
        </section>
        <!-- Two Columns: Barangay Records & Recent Updates -->
        <div class="cols">
            <!-- Left Column: Barangay Table -->
            <section class="card" aria-labelledby="tbT">
                <div class="sh">
                    <div>
                        <h2 id="tbT">Barangay records</h2>
                        <p>Review and edit population and household figures for each barangay.</p>
                    </div>
                </div>
                <div class="tb">
                    <label class="search">
                        <span style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">Search barangays</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.5" y2="16.5"/></svg>
                        <input id="qRow" type="search" placeholder="Search barangay..." autocomplete="off">
                    </label>
                    <button class="btn ghost" id="csvB" type="button" title="Download the barangay records shown">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export CSV
                    </button>
                </div>
                <div class="sc">
                    <table>
                        <thead>
                            <tr>
                                <th>Barangay</th>
                                <th class="r">Population</th>
                                <th class="r">Households</th>
                                <th>Year</th>
                                <th class="r"><span style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">Action</span></th>
                            </tr>
                        </thead>
                        <tbody id="rows">
                            @forelse($barangays as $b)
                                <tr data-bname="{{ strtolower($b->name) }}">
                                    <td>
                                        <strong>{{ $b->name }}</strong>
                                    </td>
                                    <td class="r">
                                        <span class="b-pop">{{ number_format($b->total_population ?? $b->population ?? 0) }}</span>
                                        <span class="mb"><i style="width: {{ min(100, round((($b->population ?? 0) / $maxBarangayPop) * 100)) }}%;"></i></span>
                                    </td>
                                    <td class="r">
                                        <span class="b-hh">{{ number_format($b->total_households ?? $b->households ?? 0) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $b->year ?? $cYear }}</span>
                                    </td>
                                    <td class="r">
                                        <button class="lnk edit-btn" type="button" 
                                            data-id="{{ $b->id }}"
                                            data-name="{{ $b->name }}"
                                            data-pop="{{ $b->total_population ?? $b->population ?? 0 }}"
                                            data-hh="{{ $b->total_households ?? $b->households ?? 0 }}"
                                            data-year="{{ $b->year ?? $cYear }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center; padding: 28px; color: var(--muted);">
                                        No barangay records available for this year.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="empty" id="noRow" hidden style="border:0;border-radius:0;margin:0;">No barangay matches this search filter.</p>
            </section>

            <!-- Right Column: Recent Activity -->
            <aside class="card" aria-labelledby="acT">
                <div class="sh">
                    <div>
                        <h2 id="acT">Recent updates</h2>
                        <p>Latest changes by your office.</p>
                    </div>
                </div>
                <ul class="act-l" id="acts">
                    @forelse($importLogs as $log)
                        <li>
                            <b>{{ ucfirst($log->file_type ?? 'CSV Data') }} Update</b>
                            <span>Processed {{ number_format($log->successful_rows ?? 0) }} rows &bull; {{ $log->created_at ? $log->created_at->diffForHumans() : 'Recently' }}</span>
                        </li>
                    @empty
                        <li>
                            <b>Municipal Data Synchronized</b>
                            <span>{{ $cYear }} baseline indicators active &bull; Today</span>
                        </li>
                    @endforelse
                    <li>
                        <b>Annual Summary Verified</b>
                        <span>Official records for {{ $municipality->name ?? 'Majayjay' }} ({{ $cYear }})</span>
                    </li>
                </ul>
            </aside>
        </div>
    </main>

    <!-- Dialog for Editing Barangay Data -->
    <dialog id="dlg">
        <form id="editForm">
            @csrf
            <h2 id="dT">Update Barangay</h2>
            <p id="dSub">Enter updated figures for this barangay.</p>
            <input type="hidden" id="fId" name="id">
            <input type="hidden" id="fYear" name="year" value="{{ $cYear }}">
            <label>
                Population
                <input id="fP" name="total_population" type="number" min="0" required inputmode="numeric">
            </label>
            <label>
                Households
                <input id="fH" name="total_households" type="number" min="0" required inputmode="numeric">
            </label>
            <div class="dbtn">
                <button class="btn ghost" type="button" id="dlgCancel">Cancel</button>
                <button class="btn gold" type="submit" id="dlgSave">Save changes</button>
            </div>
        </form>
    </dialog>

    <!-- Toast Notification -->
    <div class="toast" id="toast" role="status" aria-live="polite"></div>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- IMPORT LOADING OVERLAY                                        -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <div id="importOverlay" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(30,41,79,0.7);backdrop-filter:blur(4px);display:none;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:18px;padding:40px 48px;text-align:center;box-shadow:0 20px 60px rgba(44,62,143,0.3);max-width:380px;width:90%;">
            <div style="width:56px;height:56px;border:4px solid #E2E8F0;border-top-color:var(--primary-blue);border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 20px;"></div>
            <div style="font-weight:800;font-size:1.05rem;color:var(--primary-blue);" id="overlayTitle">Importing Data</div>
            <div id="overlayDetail" style="font-size:0.82rem;color:#64748b;margin-top:8px;">Please wait...</div>
            <div id="overlayResult" style="display:none;margin-top:20px;text-align:left;"></div>
            <button onclick="hideImportOverlay()" id="overlayDoneBtn" style="display:none;margin-top:20px;background:var(--primary-gradient);color:#fff;border:none;border-radius:10px;padding:9px 28px;font-weight:800;font-size:0.9rem;cursor:pointer;">Done</button>
        </div>
    </div>
    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>

    <!-- ══════════════════════════════════════════════════════════════ -->
    <!-- IMPORT MODAL                                                   -->
    <!-- ══════════════════════════════════════════════════════════════ -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;padding:18px 24px;">
                    <div>
                        <div style="font-size:0.72rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;color:var(--secondary-yellow);margin-bottom:2px;">DATA MANAGEMENT &bull; IMPORT CENTER</div>
                        <h5 class="modal-title" id="importModalLabel" style="font-weight:900;font-size:1.25rem;margin:0;">Import CSV Data</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="background:#F8FAFC;padding:24px;">
                    <!-- Scope notice -->
                    <div style="background:#EEF2FF;border:1px solid #C7D6F5;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:0.78rem;color:var(--primary-blue);font-weight:600;">
                        <i class="bi bi-shield-lock me-1"></i>
                        Imports are restricted to <strong>{{ $municipality->name }}</strong> &mdash; only records matching your municipality will be saved.
                    </div>

                    <!-- Step 1: Templates -->
                    <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:14px 18px;margin-bottom:18px;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;color:#64748B;letter-spacing:0.06em;margin-bottom:10px;">Step 1 &mdash; Download a Template</div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.data.import.template', 'municipality_data') }}" class="btn btn-sm" style="background:#EEF2FF;color:var(--primary-blue);border:1px solid #C7D6F5;font-weight:700;border-radius:8px;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Municipality Template
                            </a>
                            <a href="{{ route('admin.data.import.template', 'barangay_data') }}" class="btn btn-sm" style="background:#EEF2FF;color:var(--primary-blue);border:1px solid #C7D6F5;font-weight:700;border-radius:8px;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Barangay Template
                            </a>
                            <a href="{{ route('admin.data.import.template', 'program_data') }}" class="btn btn-sm" style="background:#EEF2FF;color:var(--primary-blue);border:1px solid #C7D6F5;font-weight:700;border-radius:8px;">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> Social Programs Template
                            </a>
                        </div>
                    </div>

                    <!-- Step 2: Upload -->
                    <div style="background:#FFFFFF;border:1px solid #E2E8F0;border-radius:12px;padding:16px 18px;">
                        <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;color:#64748B;letter-spacing:0.06em;margin-bottom:12px;">Step 2 &mdash; Upload Your CSV</div>
                        <form id="importForm" method="POST" action="{{ route('admin.data.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Data Type *</label>
                                    <select name="import_type" id="impType" class="form-select form-select-sm" required>
                                        <option value="">&mdash; Select Type &mdash;</option>
                                        <option value="municipality_data">Municipality Yearly Data</option>
                                        <option value="barangay_data">Barangay Data</option>
                                        <option value="program_data">Social Programs</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Year Filter <span style="font-weight:400;color:#94a3b8;">(optional)</span></label>
                                    <select name="year" id="impYear" class="form-select form-select-sm">
                                        <option value="">All Years in File</option>
                                        @foreach (range(2030, 2021, -1) as $yr)
                                            <option value="{{ $yr }}">{{ $yr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">Duplicates</label>
                                    <select name="import_mode" class="form-select form-select-sm">
                                        <option value="update">Update existing records</option>
                                        <option value="skip">Skip duplicates</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.78rem;font-weight:700;color:#475569;">CSV File * <span style="font-weight:400;color:#94a3b8;">(max 10 MB)</span></label>
                                <input type="file" name="csv_file" id="impFile" accept=".csv,text/csv" class="form-control form-control-sm" required>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm" id="btnPreview" style="background:#F1F5F9;color:#334155;border:1px solid #CBD5E1;font-weight:700;border-radius:8px;padding:7px 16px;">
                                    <i class="bi bi-eye me-1"></i> Preview (first 20 rows)
                                </button>
                                <button type="submit" class="btn btn-sm" id="btnImport" style="background:var(--primary-gradient);color:white;border:none;font-weight:800;border-radius:8px;padding:7px 18px;box-shadow:0 3px 10px rgba(44,62,143,0.22);">
                                    <i class="bi bi-upload me-1"></i> Import File
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="previewArea" style="display:none;margin-top:16px;">
                        <div id="previewMsg" style="font-size:0.78rem;color:#475569;margin-bottom:8px;font-weight:600;"></div>
                        <div style="overflow-x:auto;max-height:220px;overflow-y:auto;border-radius:8px;border:1px solid #E2E8F0;">
                            <table class="table table-sm" id="previewTable" style="font-size:0.75rem;margin:0;">
                                <thead id="previewHead"></thead>
                                <tbody id="previewBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div style="margin-top:14px;font-size:0.72rem;color:#64748B;font-style:italic;">
                        <i class="bi bi-shield-check me-1"></i>
                        All imports are validated server-side. Only data for <strong>{{ $municipality->name }}</strong> will be accepted.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Export Forms -->
    <form id="exportCsvForm" method="GET" action="{{ route('admin.data.export.data-csv') }}" style="display:none;">
        <input type="hidden" name="year" value="{{ $cYear }}">
        <input type="hidden" name="category" value="all">
    </form>
    <form id="exportReportForm" method="GET" action="{{ route('admin.data.export.data-excel') }}" style="display:none;">
        <input type="hidden" name="year" value="{{ $cYear }}">
        <input type="hidden" name="category" value="all">
    </form>
<div class="modal fade" id="exportOptionsModal" tabindex="-1" aria-labelledby="exportOptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none; border-radius:18px; overflow:hidden; box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient); color:white; padding:18px 24px;">
                    <div>
                        <div style="font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; color:var(--secondary-yellow); margin-bottom:2px;">
                            DATA MANAGEMENT &bull; EXPORT CENTER
                        </div>
                        <h5 class="modal-title" id="exportOptionsModalLabel" style="font-weight:900; font-size:1.25rem; margin:0;">
                            Export Data &amp; Analysis Reports
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background:#F8FAFC; padding:24px;">
                    <!-- Municipality & Scope Info -->
                    <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; color:#64748B; letter-spacing:0.06em; display:block;">Active Municipality</span>
                            <strong style="color:var(--primary-blue); font-size:1.1rem;">{{ $municipality->name }}</strong>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label for="exportYearSelect" style="font-size:0.8rem; font-weight:700; color:#475569;">Target Year:</label>
                            <select id="exportYearSelect" class="form-select form-select-sm" style="width:auto; font-weight:700; border-radius:8px; border-color:#CBD5E1;" onchange="updateExportForms(this.value)">
                                <option value="{{ request('year', $currentYear) }}" selected>Selected Year ({{ request('year', $currentYear) }})</option>
                                <option value="all">All Available Years</option>
                                @foreach($allYears as $y)
                                    @if($y != request('year', $currentYear))
                                    <option value="{{ $y }}">Year {{ $y }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Export Option Cards -->
                    <div class="row g-3">
                        <!-- Option A: CSV / Raw Data -->
                        <div class="col-md-6">
                            <div style="background:#FFFFFF; border:1px solid #C7D6F5; border-radius:14px; padding:20px; height:100%; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 4px 16px rgba(44,62,143,0.06);">
                                <div>
                                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                        <div style="width:38px; height:38px; border-radius:10px; background:#EEF2FF; color:var(--primary-blue); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                                            <i class="bi bi-file-earmark-zip"></i>
                                        </div>
                                        <div>
                                            <span style="font-size:0.65rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:var(--primary-blue);">Option A</span>
                                            <h6 style="font-weight:800; color:var(--text-dark); margin:0; font-size:0.95rem;">Export CSV / Raw Data</h6>
                                        </div>
                                    </div>
                                    <p style="font-size:0.78rem; color:#64748B; margin-bottom:12px; line-height:1.5;">
                                        Downloads a complete ZIP package containing UTF-8 CSV raw data tables, DSS &amp; WSM calculations, 7 high-res PNG visualization charts, and methodology README.
                                    </p>
                                    <ul style="font-size:0.73rem; color:#475569; padding-left:18px; margin-bottom:16px;">
                                        <li>01_Municipality_Yearly_Data.csv</li>
                                        <li>02_Barangay_Data.csv</li>
                                        <li>03_Social_Programs.csv</li>
                                        <li>04_DSS_WSM.csv</li>
                                        <li>05_Graphs/ (7 PNG charts)</li>
                                        <li>README.txt (WSM formulas &amp; weights)</li>
                                    </ul>
                                </div>
                                <form id="modalCsvForm" method="GET" action="{{ route('admin.data.export.data-csv') }}">
                                    @csrf
                                    <input type="hidden" name="year" id="modalCsvYear" value="{{ request('year', $currentYear) }}">
                                    <button type="submit" class="btn w-100" style="background:#FFFFFF; color:var(--primary-blue); border:2px solid var(--primary-blue); font-weight:800; font-size:0.85rem; border-radius:10px; padding:9px 16px;">
                                        <i class="bi bi-download me-1"></i> Download ZIP Package
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option B: Analysis Report Excel -->
                        <div class="col-md-6">
                            <div style="background:#FFFFFF; border:1px solid #FCD34D; border-radius:14px; padding:20px; height:100%; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 4px 16px rgba(253,185,19,0.12);">
                                <div>
                                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                        <div style="width:38px; height:38px; border-radius:10px; background:#FEF3C7; color:#B45309; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>
                                        <div>
                                            <span style="font-size:0.65rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:#B45309;">Option B</span>
                                            <h6 style="font-weight:800; color:var(--text-dark); margin:0; font-size:0.95rem;">Export Analysis Report</h6>
                                        </div>
                                    </div>
                                    <p style="font-size:0.78rem; color:#64748B; margin-bottom:12px; line-height:1.5;">
                                        Generates a professional Excel workbook (.xlsx) organized into 10 structured worksheets with formatted KPI cards, YoY analysis, DSS prevalence rates, WSM priority scores, and embedded charts.
                                    </p>
                                    <ul style="font-size:0.73rem; color:#475569; padding-left:18px; margin-bottom:16px;">
                                        <li>10 Structured Worksheets</li>
                                        <li>Executive Summary &amp; YoY Trends</li>
                                        <li>DSS &amp; Transparent WSM Calculations</li>
                                        <li>Embedded High-Resolution Charts</li>
                                        <li>Methodological Framework</li>
                                    </ul>
                                </div>
                                <form id="modalReportForm" method="GET" action="{{ route('admin.data.export.data-excel') }}">
                                    @csrf
                                    <input type="hidden" name="year" id="modalReportYear" value="{{ request('year', $currentYear) }}">
                                    <button type="submit" class="btn w-100" style="background:var(--primary-gradient); color:white; border:none; font-weight:800; font-size:0.85rem; border-radius:10px; padding:10px 16px; box-shadow:0 4px 12px rgba(44,62,143,0.25);">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Download Excel (.xlsx)
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option C: Comparative Analysis Export -->
                        <div class="col-12">
                            <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:14px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                                <div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="badge" style="background:#E0E7FF; color:var(--primary-blue); font-weight:800; font-size:0.68rem;">COMPARATIVE</span>
                                        <h6 style="font-weight:800; margin:0; font-size:0.92rem; color:var(--primary-blue);">Municipal Comparative Export</h6>
                                    </div>
                                    <p style="font-size:0.75rem; color:#64748B; margin:4px 0 0;">
                                        Compares demographic scale, beneficiary concentration, and calculated DSS/WSM indicators among <strong>Magdalena, Liliw, and Majayjay</strong>.
                                    </p>
                                </div>
                                <form method="GET" action="{{ route('admin.data.export.data-csv') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="year" id="modalCompYear" value="{{ request('year', $currentYear) }}">
                                    <button type="submit" class="btn btn-sm" style="background:#F1F5F9; color:#334155; border:1px solid #CBD5E1; font-weight:700; border-radius:8px; padding:7px 16px;">
                                        <i class="bi bi-bar-chart me-1"></i> Export Comparative CSV
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div style="margin-top:16px; font-size:0.72rem; color:#64748B; font-style:italic;">
                        <i class="bi bi-info-circle me-1"></i> The DSS/WSM result is a decision-support indicator calculated from the available MSWDO data and configured criteria. It does not replace professional assessment, field validation, or official MSWDO decision-making.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts & Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toast notification helper
            var toastTimer = null;
            function showToast(msg) {
                var t = document.getElementById('toast');
                if (!t) return;
                t.textContent = msg;
                t.classList.add('on');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(function () {
                    t.classList.remove('on');
                }, 3200);
            }

            // ── Export year select → sync all hidden year inputs ──────────
            function updateExportForms(val) {
                // If 'all' selected, send empty string so controller treats it as null (all years)
                var yearVal = (val === 'all') ? '' : val;
                var ids = ['modalCsvYear', 'modalReportYear', 'modalCompYear'];
                ids.forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el) el.value = yearVal;
                });
                // Also update the hidden quick-export forms
                var csvHidden = document.querySelector('#exportCsvForm input[name="year"]');
                var xlsHidden = document.querySelector('#exportReportForm input[name="year"]');
                if (csvHidden) csvHidden.value = yearVal;
                if (xlsHidden) xlsHidden.value = yearVal;
            }

            // Export dropdown toggle
            var expBtn = document.getElementById('expBtn');
            var expMenu = document.getElementById('expMenu');
            if (expBtn && expMenu) {
                expBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var isHidden = expMenu.hidden;
                    expMenu.hidden = !isHidden;
                    expBtn.setAttribute('aria-expanded', String(isHidden));
                });
                document.addEventListener('click', function (e) {
                    if (!expMenu.hidden && !e.target.closest('#exm')) {
                        expMenu.hidden = true;
                        expBtn.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // Export Actions
            var expCsvBtn = document.getElementById('expCsvBtn');
            if (expCsvBtn) {
                expCsvBtn.addEventListener('click', function () {
                    if (expMenu) expMenu.hidden = true;
                    showToast('Preparing yearly CSV figures package...');
                    var form = document.getElementById('exportCsvForm');
                    if (form) form.submit();
                });
            }

            var expReportBtn = document.getElementById('expReportBtn');
            if (expReportBtn) {
                expReportBtn.addEventListener('click', function () {
                    if (expMenu) expMenu.hidden = true;
                    showToast('Generating analysis report Excel workbook...');
                    var form = document.getElementById('exportReportForm');
                    if (form) form.submit();
                });
            }

            var expMoreBtn = document.getElementById('expMoreBtn');
            if (expMoreBtn) {
                expMoreBtn.addEventListener('click', function () {
                    if (expMenu) expMenu.hidden = true;
                    var modalEl = document.getElementById('exportOptionsModal');
                    if (modalEl && window.bootstrap) {
                        var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                });
            }

            // Filter Task Cards
            var qOpt = document.getElementById('qOpt');
            var optCards = document.querySelectorAll('#opts .opt');
            var noneBox = document.getElementById('none');
            var optNote = document.getElementById('optNote');
            if (qOpt) {
                qOpt.addEventListener('input', function () {
                    var val = qOpt.value.trim().toLowerCase();
                    var visibleCount = 0;
                    optCards.forEach(function (card) {
                        var text = (card.textContent + ' ' + (card.dataset.tags || '')).toLowerCase();
                        var match = !val || text.indexOf(val) !== -1;
                        card.style.display = match ? 'flex' : 'none';
                        if (match) visibleCount++;
                    });
                    if (noneBox) {
                        noneBox.hidden = (visibleCount > 0);
                    }
                    if (optNote) {
                        optNote.textContent = val ? (visibleCount + ' of ' + optCards.length + ' tasks match') : 'Pick a task to open its editor.';
                    }
                });
            }

            // Filter Barangay Table Rows
            var qRow = document.getElementById('qRow');
            var tableRows = document.querySelectorAll('#rows tr[data-bname]');
            var noRow = document.getElementById('noRow');
            if (qRow) {
                qRow.addEventListener('input', function () {
                    var val = qRow.value.trim().toLowerCase();
                    var matchCount = 0;
                    tableRows.forEach(function (tr) {
                        var bName = tr.getAttribute('data-bname') || '';
                        var match = !val || bName.indexOf(val) !== -1;
                        tr.style.display = match ? '' : 'none';
                        if (match) matchCount++;
                    });
                    if (noRow) {
                        noRow.hidden = (matchCount > 0);
                    }
                });
            }

            // Export Visible Barangay Rows as CSV
            var csvB = document.getElementById('csvB');
            if (csvB) {
                csvB.addEventListener('click', function () {
                    var visibleRows = [];
                    tableRows.forEach(function (tr) {
                        if (tr.style.display !== 'none') {
                            var nameEl = tr.querySelector('strong');
                            var popEl = tr.querySelector('.b-pop');
                            var hhEl = tr.querySelector('.b-hh');
                            var yrEl = tr.querySelector('.badge');
                            if (nameEl) {
                                var name = nameEl.textContent.trim().replace(/"/g, '""');
                                var pop = popEl ? popEl.textContent.trim().replace(/,/g, '') : '0';
                                var hh = hhEl ? hhEl.textContent.trim().replace(/,/g, '') : '0';
                                var yr = yrEl ? yrEl.textContent.trim() : '{{ $cYear }}';
                                visibleRows.push('"' + name + '",' + pop + ',' + hh + ',' + yr);
                            }
                        }
                    });

                    if (visibleRows.length === 0) {
                        showToast('No barangay records to export.');
                        return;
                    }

                    var csvContent = "data:text/csv;charset=utf-8,Barangay,Population,Households,Year\n" + visibleRows.join("\n");
                    var encodedUri = encodeURI(csvContent);
                    var link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "barangay_records_{{ $cYear }}.csv");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    showToast('Exported ' + visibleRows.length + ' barangay records as CSV.');
                });
            }

            // Edit Barangay Dialog
            var dlg = document.getElementById('dlg');
            var dlgCancel = document.getElementById('dlgCancel');
            var editForm = document.getElementById('editForm');
            var currentEditingTr = null;

            document.querySelectorAll('.edit-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    if (!dlg) return;
                    currentEditingTr = btn.closest('tr');
                    document.getElementById('fId').value = btn.dataset.id;
                    document.getElementById('fYear').value = btn.dataset.year;
                    document.getElementById('fP').value = btn.dataset.pop;
                    document.getElementById('fH').value = btn.dataset.hh;
                    document.getElementById('dT').textContent = 'Update ' + btn.dataset.name;
                    dlg.showModal();
                    document.getElementById('fP').focus();
                });
            });

            if (dlgCancel && dlg) {
                dlgCancel.addEventListener('click', function () {
                    dlg.close();
                });
                dlg.addEventListener('click', function (e) {
                    if (e.target === dlg) dlg.close();
                });
            }

            if (editForm && dlg) {
                editForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    var bId = document.getElementById('fId').value;
                    var pop = document.getElementById('fP').value;
                    var hh = document.getElementById('fH').value;
                    var yr = document.getElementById('fYear').value;

                    var saveBtn = document.getElementById('dlgSave');
                    if (saveBtn) {
                        saveBtn.disabled = true;
                        saveBtn.textContent = 'Saving...';
                    }

                    fetch('/admin/data/barangays/' + bId + '/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            total_population: pop,
                            total_households: hh,
                            population: pop,
                            households: hh,
                            year: yr
                        })
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (saveBtn) {
                            saveBtn.disabled = false;
                            saveBtn.textContent = 'Save changes';
                        }
                        if (data.success) {
                            dlg.close();
                            if (currentEditingTr) {
                                var popEl = currentEditingTr.querySelector('.b-pop');
                                var hhEl = currentEditingTr.querySelector('.b-hh');
                                var btn = currentEditingTr.querySelector('.edit-btn');
                                if (popEl) popEl.textContent = Number(pop).toLocaleString();
                                if (hhEl) hhEl.textContent = Number(hh).toLocaleString();
                                if (btn) {
                                    btn.dataset.pop = pop;
                                    btn.dataset.hh = hh;
                                }
                            }
                            showToast(data.message || 'Barangay updated successfully.');
                        } else {
                            alert(data.message || 'Failed to update barangay.');
                        }
                    })
                    .catch(function (err) {
                        if (saveBtn) {
                            saveBtn.disabled = false;
                            saveBtn.textContent = 'Save changes';
                        }
                        console.error('Update error:', err);
                        showToast('Error saving changes. Please try again.');
                    });
                });
            }
        });
    </script>

    @include('components.admin-notification-modal')
    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')
</body>
</html>