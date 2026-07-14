<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solo Parent -AICS Category | MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: #2C3E8F;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-yellow: #FDB913;
            --bg-light: #F0F4FF;
        }
        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; }

        /* TOP BAR */
        .top-bar{background:var(--primary-gradient);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.22);}
        .top-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
        .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.45rem;}
        .brand img{width:34px;height:34px;object-fit:contain;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;text-decoration:none;}
        .back-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}

        /* Language toggle */
        .lang-toggle{display:inline-flex;border-radius:30px;overflow:hidden;border:2px solid rgba(255,255,255,.4);background:rgba(255,255,255,.08);}
        .lang-btn{background:transparent;border:none;color:rgba(255,255,255,.7);font-weight:700;font-size:.82rem;padding:8px 20px;cursor:pointer;transition:all .2s;letter-spacing:.05em;}
        .lang-btn.active{background:var(--secondary-yellow);color:var(--primary-blue);}
        .lang-btn:hover:not(.active){background:rgba(255,255,255,.15);color:white;}

        /* HERO */
        .page-hero { background: var(--primary-gradient); border-radius: 20px; padding: 36px 40px; color: white; margin-bottom: 36px; position: relative; overflow: hidden; }
        .page-hero::before { content: ''; position: absolute; top: -60px; right: -60px; width: 260px; height: 260px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 10px; }
        .page-hero h1 { font-size: 1.85rem; font-weight: 900; margin-bottom: 6px; }
        .hero-divider { width: 44px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 8px; }
        .page-hero p { opacity: 0.82; font-size: 0.93rem; margin: 0; }

        /* BACK LINK */
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: var(--primary-blue); font-weight: 700; font-size: 0.88rem; margin-bottom: 22px; transition: gap 0.2s; }
        .back-link:hover { color: var(--primary-blue); gap: 12px; }

        /* SECTION HEADING */
        .section-label { font-size: 0.72rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: #6278b8; margin-bottom: 20px; }

        /* AICS CARDS */
        .aics-card { background: white; border-radius: 20px; border: 1px solid #C7D6F5; box-shadow: 0 8px 32px rgba(44,62,143,0.12); padding: 32px 28px; height: 100%; display: flex; flex-direction: column; transition: all 0.28s ease; cursor: pointer; position: relative; overflow: hidden; }
        .aics-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: var(--primary-gradient); border-radius: 20px 20px 0 0; }
        .aics-card:hover { transform: translateY(-5px); box-shadow: 0 18px 40px rgba(44,62,143,0.20); border-color: var(--primary-blue); }
        .aics-card.disabled { opacity: 0.55; cursor: not-allowed; pointer-events: none; }
        .aics-card.disabled::before { background: linear-gradient(135deg, #94a3b8, #64748b); }

        .card-icon { width: 60px; height: 60px; border-radius: 16px; background: #EEF2FF; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 18px; flex-shrink: 0; }
        .card-num { font-size: 0.62rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(253,185,19,0.9); display: block; margin-bottom: 6px; }
        .card-title { font-size: 1.15rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 10px; line-height: 1.2; }
        .card-desc { font-size: 0.84rem; color: #64748b; line-height: 1.65; flex: 1; }
        .card-badge-unavail { display: inline-block; background: #F1F5F9; color: #94a3b8; border-radius: 20px; padding: 3px 12px; font-size: 0.7rem; font-weight: 700; margin-top: 14px; border: 1px solid #E2E8F0; }
        .card-arrow { display: inline-flex; align-items: center; gap: 6px; margin-top: 20px; font-size: 0.85rem; font-weight: 700; color: var(--primary-blue); transition: gap 0.2s; }
        .aics-card:hover .card-arrow { gap: 12px; }
        .aics-card-link { display: block; height: 100%; text-decoration: none; color: inherit; }

        /* FOOTER */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.8); text-align: center; padding: 20px; font-size: 0.84rem; margin-top: auto; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>

    <!-- TOP BAR + HERO COMBINED -->
    <div style="background:var(--primary-gradient);box-shadow:0 4px 20px rgba(44,62,143,.22);position:relative;overflow:hidden;">
        <div style="content:'';position:absolute;top:-60px;right:-60px;width:260px;height:260px;border-radius:50%;background:rgba(253,185,19,0.09);"></div>
        <div class="container">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;padding:14px 0;position:relative;z-index:2;">
                <div class="brand">
                    <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD">
                    <span>MSWDO</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="lang-toggle">
                        <button class="lang-btn active" data-lang="en" onclick="setLang('en')">EN</button>
                        <button class="lang-btn" data-lang="tl" onclick="setLang('tl')">TL</button>
                    </div>
                    <a href="{{ route('user.programs') }}" class="back-btn">&#8592; <span data-en="Back to Programs" data-tl="Bumalik sa Programs">Back to Programs</span></a>
                </div>
            </div>
        </div>
        
        <!-- HERO -->
        <div class="container" style="padding:20px 0 44px;position:relative;z-index:2;">
            <div class="hero-badge" data-en="Assistance in Crisis (AICS)" data-tl="Tulong sa Krisis (AICS)">Assistance in Crisis (AICS)</div>
            <h1 style="font-size:2.5rem;font-weight:900;margin-bottom:8px;line-height:1.12;color:white;" data-en="AICS - Choose a Category" data-tl="AICS - Pumili ng Kategorya">AICS - Choose a Category</h1>
            <div class="hero-divider"></div>
            <p style="opacity:.85;font-size:.97rem;margin:0;max-width:900px;line-height:1.7;color:white;" data-en="Assistance to Individuals in Crisis Situation (AICS) provides targeted financial aid to individuals and families facing emergencies. Select the type of assistance you need to begin your application." data-tl="Ang Tulong sa mga Indibidwal sa Krisis na Sitwasyon (AICS) ay nagbibigay ng tulong pinansyal sa mga indibidwal at pamilyang nahaharap sa emerhensya. Pumili ng uri ng tulong na kailangan mo para magsimula ng iyong aplikasyon.">Assistance to Individuals in Crisis Situation (AICS) provides targeted financial aid to individuals and families facing emergencies. Select the type of assistance you need to begin your application.</p>
        </div>
    </div>

    <div style="flex:1;">
    <div class="container pb-5" style="padding-top:32px;">

        <p class="section-label" data-en="Available Categories" data-tl="Mga Available na Kategorya">Available Categories</p>

        <div class="row g-4">

            <!-- MEDICAL ASSISTANCE -->
            <div class="col-md-4">
                <a href="{{ route('user.aics-medical') }}" class="aics-card-link">
                    <div class="aics-card">
                        <span class="card-num">01 - AICS</span>
                        <div class="card-title" data-en="Medical Assistance" data-tl="Tulong Medikal">Medical Assistance</div>
                        <div class="card-desc" data-en="Financial support for medical needs including hospital bills, medicines, laboratory tests, and other health-related expenses for individuals in crisis situations." data-tl="Tulong pinansyal para sa pangangailangang medikal kabilang ang hospital bills, gamot, laboratory tests, at iba pang gastusin sa kalusugan para sa mga indibidwal sa krisis na sitwasyon.">Financial support for medical needs including hospital bills, medicines, laboratory tests, and other health-related expenses for individuals in crisis situations.</div>
                        <span class="card-arrow" data-en="View Requirements →" data-tl="Tingnan ang mga Kinakailangan →">View Requirements →</span>
                    </div>
                </a>
            </div>

            <!-- BURIAL ASSISTANCE -->
            <div class="col-md-4">
                <a href="{{ route('user.aics-burial') }}" class="aics-card-link">
                    <div class="aics-card">
                        <span class="card-num">02 - AICS</span>
                        <div class="card-title" data-en="Burial Assistance" data-tl="Tulong sa Libing">Burial Assistance</div>
                        <div class="card-desc" data-en="Financial aid to help individuals and families manage funeral and burial expenses for an immediate family member who has passed away." data-tl="Tulong pinansyal para sa mga indibidwal at pamilya sa paggastos sa libing at funeral para sa miyembro ng pamilyang pumanaw.">Financial aid to help individuals and families manage funeral and burial expenses for an immediate family member who has passed away.</div>
                        <span class="card-arrow" data-en="View Requirements →" data-tl="Tingnan ang mga Kinakailangan →">View Requirements →</span>
                    </div>
                </a>
            </div>

            <!-- EMERGENCY SHELTER (UNAVAILABLE) -->
            <div class="col-md-4">
                <div class="aics-card disabled">
                    <span class="card-num">03 - AICS</span>
                    <div class="card-title" data-en="Emergency Shelter Assistance" data-tl="Tulong sa Emergency Shelter">Emergency Shelter Assistance</div>
                    <div class="card-desc" data-en="Support for solo parents who have lost or are at risk of losing their shelter due to disaster, calamity, or emergency situations." data-tl="Tulong para sa mga solo parent na nawalan o nanganganib na mawalan ng tirahan dahil sa sakuna, kalamidad, o emergency na sitwasyon.">Support for solo parents who have lost or are at risk of losing their shelter due to disaster, calamity, or emergency situations.</div>
                    <span class="card-badge-unavail" data-en="⏳ Not yet available" data-tl="⏳ Hindi pa available">⏳ Not yet available</span>
                </div>
            </div>

        </div>

        <div class="mt-4" style="background:rgba(44,62,143,0.06);border-radius:14px;padding:16px 20px;border-left:4px solid var(--primary-blue);font-size:0.85rem;color:#475569;">
            <strong style="color:var(--primary-blue);" data-en="Note:" data-tl="Paalala:">Note:</strong> <span data-en="Emergency Shelter Assistance is currently unavailable while we prepare the necessary data and forms. Medical and Burial Assistance applications are open." data-tl="Ang Emergency Shelter Assistance ay kasalukuyang hindi available habang inihahanda namin ang kinakailangang data at forms. Bukas ang Medical at Burial Assistance applications.">Emergency Shelter Assistance is currently unavailable while we prepare the necessary data and forms. Medical and Burial Assistance applications are open.</span>
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.chat-modal')
    @include('components.chatbot-widget')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentLang = 'en';
        function setLang(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.lang === lang);
            });
            document.querySelectorAll('[data-en]').forEach(el => {
                const text = lang === 'tl' ? (el.dataset.tl || el.dataset.en) : el.dataset.en;
                if (text) el.textContent = text;
            });
        }
    </script>
</body>
</html>
