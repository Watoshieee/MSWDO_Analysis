<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Available Programs – MSWDO Member Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-dark: #1A2A5C;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F4F7FB;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; border-radius: 8px; padding: 10px 18px !important; font-size: 0.92rem; transition: all 0.2s; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.7); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.25s; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ── HERO ── */
        .hero {
            background: var(--primary-gradient);
            color: white;
            padding: 56px 0 52px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; top: -100px; right: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(253,185,19,0.12) 0%, transparent 70%);
        }
        .hero::after {
            content: '';
            position: absolute; bottom: -80px; left: -60px;
            width: 280px; height: 280px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px; padding: 5px 18px;
            font-size: 0.72rem; font-weight: 800;
            letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 14px;
        }
        .hero h1 { font-size: 2.5rem; font-weight: 900; line-height: 1.15; margin-bottom: 6px; }
        .hero-divider { width: 50px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 14px 0 16px; }
        .hero p { opacity: 0.85; font-size: 0.97rem; max-width: 520px; line-height: 1.7; margin-bottom: 28px; }

        /* Stats row in hero */
        .hero-stats { display: flex; gap: 28px; flex-wrap: wrap; }
        .hero-stat { background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.15); border-radius: 14px; padding: 12px 22px; text-align: center; }
        .hero-stat .stat-val { font-size: 1.5rem; font-weight: 900; color: var(--secondary-yellow); }
        .hero-stat .stat-lbl { font-size: 0.7rem; font-weight: 700; opacity: 0.8; text-transform: uppercase; letter-spacing: .06em; margin-top: 2px; }

        /* ── SEARCH & FILTER BAR ── */
        .filter-bar {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 14px 0;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(44,62,143,0.06);
        }
        .filter-inner { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .search-wrap { position: relative; flex: 1; min-width: 180px; max-width: 280px; }
        .search-wrap input {
            width: 100%; padding: 8px 14px 8px 38px;
            border: 1.5px solid var(--border-light); border-radius: 10px;
            font-family: 'Inter', sans-serif; font-size: 0.85rem; color: var(--text-dark);
            outline: none; transition: border-color .2s;
        }
        .search-wrap input:focus { border-color: var(--primary-blue); }
        .search-wrap .si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); font-size: 1rem; opacity: .45; }

        .filter-tabs { display: flex; gap: 6px; flex-wrap: wrap; }
        .ftab {
            padding: 7px 16px; border-radius: 30px; font-size: 0.8rem; font-weight: 700;
            border: 1.5px solid var(--border-light); background: white; color: var(--text-muted);
            cursor: pointer; transition: all .2s; white-space: nowrap;
        }
        .ftab.active, .ftab:hover { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
        .result-count { margin-left: auto; font-size: 0.78rem; color: var(--text-muted); font-weight: 600; white-space: nowrap; }

        /* ── PROGRAM CARDS ── */
        .prog-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin: 32px 0 48px; }
        @media (max-width: 900px) { .prog-grid { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 580px) { .prog-grid { grid-template-columns: 1fr; } }

        .prog-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            display: flex; flex-direction: column;
            transition: transform .28s, box-shadow .28s, border-color .28s;
            cursor: pointer;
        }
        .prog-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(44,62,143,0.13); border-color: rgba(44,62,143,0.25); }
        .prog-card.hidden { display: none !important; }

        /* Color accent bar at top */
        .prog-card .accent { height: 5px; }
        .acc-blue   { background: linear-gradient(90deg, #2C3E8F, #5578d9); }
        .acc-yellow { background: linear-gradient(90deg, #FDB913, #E5A500); }
        .acc-green  { background: linear-gradient(90deg, #16a34a, #22c55e); }
        .acc-purple { background: linear-gradient(90deg, #7c3aed, #a855f7); }
        .acc-red    { background: linear-gradient(90deg, #dc2626, #ef4444); }
        .acc-teal   { background: linear-gradient(90deg, #0891b2, #22d3ee); }
        .acc-orange { background: linear-gradient(90deg, #ea580c, #f97316); }

        .prog-body { padding: 20px 20px 18px; flex: 1; display: flex; flex-direction: column; }
        .prog-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px; }
        .prog-icon { font-size: 2rem; line-height: 1; flex-shrink: 0; }
        .prog-meta { flex: 1; min-width: 0; }
        .prog-cat {
            font-size: 0.65rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
            border-radius: 20px; padding: 2px 10px; display: inline-block; margin-bottom: 5px;
        }
        .cat-financial  { background: #EEF2FF; color: #2C3E8F; }
        .cat-social     { background: #d4edda; color: #155724; }
        .cat-livelihood { background: #d1f5f9; color: #0c6170; }
        .cat-education  { background: #fff0e0; color: #9a3b00; }

        .prog-title { font-size: 1rem; font-weight: 800; color: var(--text-dark); line-height: 1.25; }
        .prog-desc  { font-size: 0.8rem; color: var(--text-muted); line-height: 1.6; margin: 8px 0 10px; flex: 1; }

        /* Eligibility bullets */
        .prog-elig { margin-bottom: 14px; }
        .prog-elig-title { font-size: 0.68rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 5px; }
        .prog-elig ul { list-style: none; padding: 0; margin: 0; }
        .prog-elig li { font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: flex-start; gap: 6px; margin-bottom: 3px; }
        .prog-elig li::before { content: '✓'; color: #16a34a; font-weight: 800; flex-shrink: 0; font-size: .7rem; margin-top: 1px; }

        /* Apply button */
        .prog-btn {
            display: block; width: 100%; text-align: center;
            background: var(--primary-gradient); color: white;
            border: none; border-radius: 12px; padding: 11px;
            font-weight: 800; font-size: 0.84rem; letter-spacing: .02em;
            transition: all .25s; margin-top: auto;
        }
        .prog-btn:hover { box-shadow: 0 8px 22px rgba(44,62,143,0.28); transform: translateY(-1px); color: white; }
        .prog-btn.btn-yellow { background: linear-gradient(135deg, #FDB913, #E5A500); color: #1a2e8a; }
        .prog-btn.btn-yellow:hover { box-shadow: 0 8px 22px rgba(253,185,19,0.4); color: #1a2e8a; }

        /* No results */
        .no-results { display: none; text-align: center; padding: 60px 0; }
        .no-results .nr-icon { font-size: 3rem; margin-bottom: 12px; }
        .no-results p { color: var(--text-muted); font-size: 0.9rem; }

        /* ── BOTTOM CTA ── */
        .bottom-cta {
            background: var(--primary-gradient); color: white;
            border-radius: 20px; padding: 32px 36px;
            display: flex; align-items: center; justify-content: space-between; gap: 20px;
            margin-bottom: 48px; flex-wrap: wrap;
        }
        .bottom-cta h5 { font-weight: 800; font-size: 1.05rem; margin: 0 0 4px; }
        .bottom-cta p { font-size: 0.82rem; opacity: .8; margin: 0; }
        .cta-btn { background: var(--secondary-yellow); color: #1a2e8a; border: none; border-radius: 12px; padding: 11px 26px; font-weight: 900; font-size: 0.85rem; cursor: pointer; white-space: nowrap; flex-shrink: 0; transition: all .25s; }
        .cta-btn:hover { box-shadow: 0 6px 18px rgba(253,185,19,0.4); transform: translateY(-1px); }

        /* ── ALERTS ── */
        .alert { border-radius: 12px; border: none; font-size: 0.88rem; }
        .alert-success-c { background: #d4edda; border-left: 5px solid #28a745; color: #155724; }

        /* ── FOOTER ── */
        .main-content { flex: 1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.7); text-align: center; padding: 20px 0; font-size: 0.83rem; }
        .footer-strip strong { color: white; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/user/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/user/programs">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/announcements">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis">Public Analysis</a></li>
                </ul>
                <div class="d-flex">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Member Portal</div>
                <h1>Available Programs</h1>
                <div class="hero-divider"></div>
                <p>Choose a program below to view its requirements and submit your application for MSWDO assistance.</p>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="stat-val">4</div>
                        <div class="stat-lbl">Programs</div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-val">Free</div>
                        <div class="stat-lbl">No Fees</div>
                    </div>
                    <div class="hero-stat">
                        <div class="stat-val">Online</div>
                        <div class="stat-lbl">Application</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEARCH & FILTER BAR -->
    <div class="filter-bar">
        <div class="container">
            <div class="filter-inner">
                <div class="search-wrap">
                    <span class="si">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search programs…" oninput="filterPrograms()">
                </div>
                <div class="filter-tabs">
                    <div class="ftab active" data-cat="all" onclick="setCategory(this,'all')">All Programs</div>
                    <div class="ftab" data-cat="financial"  onclick="setCategory(this,'financial')"> Financial Aid</div>
                    <div class="ftab" data-cat="social"     onclick="setCategory(this,'social')"> Social Support</div>

                </div>
                <div class="result-count" id="resultCount">4 programs</div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success-c alert-dismissible fade show mt-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="prog-grid" id="progGrid">

                <!-- 1. Senior Citizen Pension -->
                <div class="prog-card" data-cat="financial" data-title="Senior Citizen Pension Social Pension">
                    <div class="accent acc-yellow"></div>
                    <div class="prog-body">
                        <div class="prog-header">
                        
                            <div class="prog-meta">
                                <div class="prog-cat cat-financial">Financial Aid</div>
                                <div class="prog-title">Senior Citizen Pension</div>
                            </div>
                        </div>
                        <div class="prog-desc">Monthly social pension for indigent senior citizens 60 years and above who are frail, sickly, or with disabilities, and have no regular source of income.</div>
                        <div class="prog-elig">
                            <div class="prog-elig-title">Key Eligibility</div>
                            <ul>
                                <li>Filipino citizen aged 60 or older</li>
                                <li>Indigent — no regular income or pension</li>
                                <li>Frail, sickly, or with disability</li>
                            </ul>
                        </div>
                        <a href="{{ route('user.apply', 'Senior_Citizen_Pension') }}" class="prog-btn btn-yellow">Apply Now →</a>
                    </div>
                </div>

                <!-- 2. PWD Assistance -->
                <div class="prog-card" data-cat="social" data-title="PWD Assistance Persons with Disability">
                    <div class="accent acc-green"></div>
                    <div class="prog-body">
                        <div class="prog-header">
                          
                            <div class="prog-meta">
                                <div class="prog-cat cat-social">Social Support</div>
                                <div class="prog-title">PWD Assistance</div>
                            </div>
                        </div>
                        <div class="prog-desc">Financial and social support services for persons with disability (PWD), including ID issuance for discounts, medical aid, and livelihood opportunities.</div>
                        <div class="prog-elig">
                            <div class="prog-elig-title">Key Eligibility</div>
                            <ul>
                                <li>Filipino citizen with recognized disability</li>
                                <li>Resident of Majayjay, Liliw, or Magdalena</li>
                                <li>With medical certificate of disability</li>
                            </ul>
                        </div>
                        <a href="{{ url('/user/pwd-application') }}" class="prog-btn">Apply Now →</a>
                    </div>
                </div>

                <!-- 3. Solo Parent Support -->
                <div class="prog-card" data-cat="social" data-title="Solo Parent Support Single Parent">
                    <div class="accent acc-purple"></div>
                    <div class="prog-body">
                        <div class="prog-header">
                       
                            <div class="prog-meta">
                                <div class="prog-cat cat-social">Social Support</div>
                                <div class="prog-title">Solo Parent Support</div>
                            </div>
                        </div>
                        <div class="prog-desc">Assistance and special privileges for solo parents raising children independently — including livelihood support, flexible work arrangements, and educational benefits.</div>
                        <div class="prog-elig">
                            <div class="prog-elig-title">Key Eligibility</div>
                            <ul>
                                <li>Solo parent with child/ren below 18</li>
                                <li>Annual income below ₱250,000</li>
                                <li>With valid Solo Parent ID</li>
                            </ul>
                        </div>
                        <a href="{{ url('/user/solo-parent-application') }}" class="prog-btn">Apply Now →</a>
                    </div>
                </div>

                <!-- 4. AICS -->
                <div class="prog-card" data-cat="financial" data-title="AICS Assistance in Crisis Situations Emergency Aid">
                    <div class="accent acc-red"></div>
                    <div class="prog-body">
                        <div class="prog-header">
                          
                            <div class="prog-meta">
                                <div class="prog-cat cat-financial">Financial Aid</div>
                                <div class="prog-title">Assistance in Crisis (AICS)</div>
                            </div>
                        </div>
                        <div class="prog-desc">Emergency financial aid for individuals and families facing crisis situations — covers medical, burial, food, transportation, and educational assistance.</div>
                        <div class="prog-elig">
                            <div class="prog-elig-title">Key Eligibility</div>
                            <ul>
                                <li>Filipino in crisis / emergency situation</li>
                                <li>Residing in the covered municipalities</li>
                                <li>Below poverty threshold income</li>
                            </ul>
                        </div>
                        <a href="{{ route('user.aics-category') }}" class="prog-btn">Apply Now →</a>
                    </div>
                </div>



            </div><!-- /prog-grid -->

            <!-- No results placeholder -->
            <div class="no-results" id="noResults">
               
                <p>No programs match your search. Try a different keyword or category.</p>
            </div>

            <!-- BOTTOM CTA -->
            <div class="bottom-cta">
                <div>
                    <h5>Can't find what you're looking for?</h5>
                    <p>Visit the MSWDO office or contact us directly for personalized assistance and guidance.</p>
                </div>
                <a href="{{ url('/user/announcements') }}" class="cta-btn">View Announcements →</a>
            </div>

        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let activeCategory = 'all';

        function setCategory(el, cat) {
            activeCategory = cat;
            document.querySelectorAll('.ftab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');
            filterPrograms();
        }

        function filterPrograms() {
            const q  = document.getElementById('searchInput').value.toLowerCase().trim();
            const cards = document.querySelectorAll('.prog-card');
            let visible = 0;

            cards.forEach(card => {
                const title = (card.dataset.title || '').toLowerCase();
                const cat   = card.dataset.cat || '';
                const matchSearch = !q || title.includes(q);
                const matchCat   = activeCategory === 'all' || cat === activeCategory;

                if (matchSearch && matchCat) {
                    card.classList.remove('hidden');
                    visible++;
                } else {
                    card.classList.add('hidden');
                }
            });

            document.getElementById('resultCount').textContent =
                visible === 0 ? 'No results' : `${visible} program${visible > 1 ? 's' : ''}`;
            document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
        }
    </script>
</body>
</html>