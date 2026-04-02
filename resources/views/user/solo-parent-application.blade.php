<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="Solo Parent ID Guide – MSWDO" data-tl="Gabay sa Solo Parent ID – MSWDO">Solo Parent ID Guide – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue:#2C3E8F; --primary-blue-light:#E5EEFF;
            --secondary-yellow:#FDB913; --secondary-yellow-light:#FFF3D6;
            --primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);
            --secondary-gradient:linear-gradient(135deg,#FDB913 0%,#E5A500 100%);
            --bg-light:#F8FAFC; --border-light:#E2E8F0; --text-dark:#1E293B;
        }
        *,body{font-family:'Inter','Segoe UI',sans-serif;}
        body{background:var(--bg-light);color:var(--text-dark);display:flex;flex-direction:column;min-height:100vh;margin:0;}
        a{text-decoration:none;}

        /* TOP BAR */
        .top-bar{background:var(--primary-gradient);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.22);}
        .top-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
        .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.45rem;}
        .brand img{width:34px;height:34px;object-fit:contain;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;text-decoration:none;}
        .back-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}

        /* HERO */
        .hero-banner{background:var(--primary-gradient);color:white;padding:56px 0 44px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-90px;right:-90px;width:360px;height:360px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-banner::after{content:'';position:absolute;bottom:-60px;left:-50px;width:240px;height:240px;border-radius:50%;background:rgba(255,255,255,.04);}
        .hero-inner{position:relative;z-index:2;}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;margin-bottom:16px;}
        .hero-banner h1{font-size:2.5rem;font-weight:900;margin-bottom:8px;line-height:1.12;}
        .hero-divider{width:50px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:16px 0;}
        .hero-banner p.hero-sub{opacity:.85;font-size:.97rem;margin:0;max-width:600px;line-height:1.7;}

        /* Language toggle */
        .lang-toggle{display:inline-flex;border-radius:30px;overflow:hidden;border:2px solid rgba(255,255,255,.4);}
        .lang-btn{background:transparent;border:none;color:rgba(255,255,255,.7);font-weight:700;font-size:.82rem;padding:8px 20px;cursor:pointer;transition:all .2s;letter-spacing:.05em;}
        .lang-btn.active{background:var(--secondary-yellow);color:var(--primary-blue);}
        .lang-btn:hover:not(.active){background:rgba(255,255,255,.15);color:white;}

        /* SECTION CARD */
        .section-card{background:white;border-radius:20px;border:1px solid var(--border-light);box-shadow:0 4px 16px rgba(0,0,0,.04);overflow:hidden;margin-bottom:28px;}
        .section-header{background:var(--primary-gradient);color:white;padding:20px 26px;display:flex;align-items:center;gap:16px;}
        .sec-icon{width:44px;height:44px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;}
        .section-header h4{font-weight:800;margin:0;font-size:1.05rem;}
        .section-header p{margin:0;opacity:.82;font-size:.84rem;margin-top:2px;}
        .section-body{padding:28px;}

        /* Steps */
        .step-item{display:flex;gap:18px;margin-bottom:24px;align-items:flex-start;}
        .step-item:last-child{margin-bottom:0;}
        .step-num{width:40px;height:40px;min-width:40px;border-radius:50%;background:var(--primary-gradient);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;box-shadow:0 4px 14px rgba(44,62,143,.28);}
        .step-content{flex:1;}
        .step-title{font-weight:700;color:var(--primary-blue);font-size:.97rem;margin-bottom:4px;}
        .step-desc{font-size:.87rem;color:#475569;line-height:1.65;}
        .step-note{background:var(--secondary-yellow-light);border-left:3px solid var(--secondary-yellow);border-radius:8px;padding:10px 14px;font-size:.82rem;color:#856404;margin-top:10px;}
        .connector{margin-left:19px;border-left:2px dashed var(--border-light);height:16px;}

        /* Info cards */
        .info-card{background:var(--primary-blue-light);border:1px solid rgba(44,62,143,.12);border-radius:14px;padding:18px 22px;margin-bottom:16px;}
        .info-card.yellow{background:var(--secondary-yellow-light);border-color:rgba(253,185,19,.3);}
        .info-card.placeholder{background:#f8f9fa;border:2px dashed #dee2e6;text-align:center;}
        .info-card .ic-title{font-weight:700;color:var(--primary-blue);font-size:.88rem;margin-bottom:6px;}
        .info-card .ic-body{font-size:.85rem;color:#475569;line-height:1.65;}

        /* Action buttons */
        .btn-yellow{display:inline-flex;align-items:center;gap:10px;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:12px;padding:13px 28px;font-weight:800;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-yellow:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(253,185,19,.45);color:var(--primary-blue);}
        .btn-purple{display:inline-flex;align-items:center;gap:8px;background:var(--primary-gradient);color:white;border:none;border-radius:12px;padding:13px 28px;font-weight:700;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-purple:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 8px 22px rgba(44,62,143,.35);color:white;}

        /* CTA strip */
        .cta-strip{background:var(--primary-gradient);border-radius:16px;padding:22px 30px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
        .cta-strip .cta-text-main{color:white;font-weight:800;font-size:1rem;margin-bottom:3px;}
        .cta-strip .cta-text-sub{color:rgba(255,255,255,.75);font-size:.85rem;}

        /* Footer */
        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.85);text-align:center;padding:18px;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}
    </style>
</head>
<body>

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="brand">
                    <img src="/images/mswd-logo.png" alt="MSWD">
                    <span>MSWDO</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="lang-toggle">
                        <button class="lang-btn active" data-lang="en" onclick="setLang('en')">EN</button>
                        <button class="lang-btn"        data-lang="tl" onclick="setLang('tl')">TL</button>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="back-btn">
                        ← <span data-en="Back to Dashboard" data-tl="Bumalik sa Dashboard">Back to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge" data-en="Solo Parent Services" data-tl="Mga Serbisyo para sa Solo Parent">Solo Parent Services</div>
                <h1 data-en="Solo Parent ID Application Guide" data-tl="Gabay sa Pag-apply ng Solo Parent ID">Solo Parent ID Application Guide</h1>
                <div class="hero-divider"></div>
                <p class="hero-sub"
                   data-en="A complete, step-by-step guide to applying for a Solo Parent ID through the MSWDO — from scheduling an appointment to receiving your ID."
                   data-tl="Isang kumpletong hakbang-hakbang na gabay sa pag-apply ng Solo Parent ID sa MSWDO — mula sa pag-schedule ng appointment hanggang sa pagtanggap ng iyong ID.">
                    A complete, step-by-step guide to applying for a Solo Parent ID through the MSWDO — from scheduling an appointment to receiving your ID.
                </p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 py-5">
        <div class="container">
            <div class="row g-4">

                <!-- LEFT: APPLICATION STEPS -->
                <div class="col-lg-7">

                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">📋</div>
                            <div>
                                <h4 data-en="How to Apply for a Solo Parent ID" data-tl="Paano Mag-apply ng Solo Parent ID">How to Apply for a Solo Parent ID</h4>
                                <p data-en="Follow these 7 steps to complete your application" data-tl="Sundin ang 7 hakbang na ito para makumpleto ang iyong aplikasyon">Follow these 7 steps to complete your application</p>
                            </div>
                        </div>
                        <div class="section-body">

                            <!-- Step 1 -->
                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Schedule an Appointment" data-tl="Mag-schedule ng Appointment">Schedule an Appointment</div>
                                    <div class="step-desc" data-en="Apply for an appointment for a face-to-face or online interview with the MSWDO." data-tl="Mag-apply ng appointment para sa harapan o online na panayam sa MSWDO.">
                                        Apply for an appointment for a face-to-face or online interview with the MSWDO.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 2 -->
                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Wait for Confirmation" data-tl="Hintayin ang Kumpirmasyon">Wait for Confirmation</div>
                                    <div class="step-desc" data-en="The admin will review your request and send a confirmation notification through the website and your registered email." data-tl="Susuriin ng admin ang iyong kahilingan at magpapadala ng kumpirmasyon sa pamamagitan ng website at iyong nakarehistrong email.">
                                        The admin will review your request and send a confirmation notification through the website and your registered email.
                                    </div>
                                    <div class="step-note">
                                        📧 <span data-en="Make sure your registered email is active and accessible." data-tl="Siguraduhing aktibo at naa-access ang iyong nakarehistrong email.">Make sure your registered email is active and accessible.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 3 -->
                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Attend the Interview" data-tl="Dumalo sa Panayam">Attend the Interview</div>
                                    <div class="step-desc" data-en="Go to the MSWDO office for a face-to-face interview if you are nearby. Otherwise, attend the scheduled online interview." data-tl="Pumunta sa opisina ng MSWDO para sa harapang panayam kung malapit ka. Kung hindi, dumalo sa nakatakdang online na panayam.">
                                        Go to the MSWDO office for a face-to-face interview if you are nearby. Otherwise, attend the scheduled online interview.
                                    </div>
                                    <div class="step-note">
                                        📍 <span data-en="MSWDO Office — Municipal Hall, Ground Floor, Monday–Friday 8:00 AM–5:00 PM." data-tl="Opisina ng MSWDO — Municipal Hall, Ground Floor, Lunes–Biyernes 8:00 AM–5:00 PM.">MSWDO Office — Municipal Hall, Ground Floor, Monday–Friday 8:00 AM–5:00 PM.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 4 -->
                            <div class="step-item">
                                <div class="step-num">4</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Eligibility Notification" data-tl="Abiso sa Pagiging Karapat-dapat">Eligibility Notification</div>
                                    <div class="step-desc" data-en="You will receive a message via your registered email informing you if you are eligible for the Solo Parent program." data-tl="Makatanggap ka ng mensahe sa iyong nakarehistrong email na nagpapaalam kung ikaw ay karapat-dapat para sa programang Solo Parent.">
                                        You will receive a message via your registered email informing you if you are eligible for the program.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 5 -->
                            <div class="step-item">
                                <div class="step-num">5</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Requirements Submission" data-tl="Pagsusumite ng mga Kinakailangan">Requirements Submission</div>
                                    <div class="step-desc" data-en="If approved, a list of required documents will be sent to you via email. Prepare all the necessary documents before proceeding." data-tl="Kung naaprubahan, isang listahan ng mga kinakailangang dokumento ang ipapadala sa iyo sa pamamagitan ng email. Ihanda ang lahat ng kinakailangang dokumento bago magpatuloy.">
                                        If approved, a list of required documents will be sent to you via email. Prepare all the necessary documents before proceeding.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 6 -->
                            <div class="step-item">
                                <div class="step-num">6</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Submit Documents" data-tl="Isumite ang mga Dokumento">Submit Documents</div>
                                    <div class="step-desc" data-en="Prepare and submit the hard copies of your required documents to the MSWDO office." data-tl="Ihanda at isumite ang mga pisikal na kopya ng iyong mga kinakailangang dokumento sa opisina ng MSWDO.">
                                        Prepare and submit the hard copies of your required documents to the MSWDO office.
                                    </div>
                                    <div class="step-note">
                                        💡 <span data-en="Bring original copies and at least one photocopy of each document for verification." data-tl="Magdala ng mga orihinal na kopya at hindi bababa sa isang photocopy ng bawat dokumento para sa pag-verify.">Bring original copies and at least one photocopy of each document for verification.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 7 -->
                            <div class="step-item">
                                <div class="step-num">7</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="ID Processing and Release" data-tl="Pagpoproseso at Paglalabas ng ID">ID Processing and Release</div>
                                    <div class="step-desc" data-en="Your Solo Parent ID will be processed by the MSWDO. You will be notified once it is ready for release." data-tl="Ipoproseso ng MSWDO ang iyong Solo Parent ID. Maabisuhan ka kapag handa na itong makuha.">
                                        Your Solo Parent ID will be processed by the MSWDO. You will be notified once it is ready for release.
                                    </div>
                                    <div class="step-note">
                                        🎉 <span data-en="You will receive a notification via email and the website when your ID is ready for pickup." data-tl="Makakatanggap ka ng abiso sa pamamagitan ng email at website kapag handa na ang iyong ID para makuha.">You will receive a notification via email and the website when your ID is ready for pickup.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- RIGHT: SIDEBAR -->
                <div class="col-lg-5">

                    <!-- Requirements (Placeholder) -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">📄</div>
                            <div>
                                <h4 data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</h4>
                                <p data-en="Documents needed for your Solo Parent ID application" data-tl="Mga dokumentong kailangan para sa iyong aplikasyon">Documents needed for your Solo Parent ID application</p>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="info-card placeholder" style="padding:32px 22px;">
                                <div style="font-size:2.5rem;margin-bottom:12px;">📋</div>
                                <div style="font-weight:700;color:#6c757d;font-size:.95rem;margin-bottom:8px;"
                                     data-en="Requirements List Coming Soon"
                                     data-tl="Listahan ng mga Kinakailangan — Malapit na">
                                    Requirements List Coming Soon
                                </div>
                                <div style="font-size:.83rem;color:#94a3b8;line-height:1.6;"
                                     data-en="The list of required documents for the Solo Parent ID is being finalized. Once available, it will be displayed here and sent to eligible applicants via email."
                                     data-tl="Ang listahan ng mga kinakailangang dokumento para sa Solo Parent ID ay kasalukuyang pinipinish. Kapag mayroon na, ito ay ipapakita dito at ipapadala sa mga karapat-dapat na aplikante sa pamamagitan ng email.">
                                    The list of required documents is being finalized. Once available, it will be displayed here and sent to eligible applicants via email after the interview.
                                </div>
                            </div>
                            <div class="info-card yellow" style="margin-top:0;">
                                <div class="ic-title">📧 <span data-en="How will I know?" data-tl="Paano ako malalaman?">How will I know?</span></div>
                                <div class="ic-body" data-en="After your eligibility interview, the MSWDO will send the complete list of required documents directly to your registered email address." data-tl="Pagkatapos ng iyong eligibility interview, ipapadala ng MSWDO ang kumpletong listahan ng mga kinakailangang dokumento sa iyong nakarehistrong email address.">
                                    After your eligibility interview, the MSWDO will send the complete list of required documents directly to your registered email address.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MSWDO Office Details -->
                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">🏢</div>
                            <div>
                                <h4 data-en="MSWDO Office Details" data-tl="Detalye ng Opisina ng MSWDO">MSWDO Office Details</h4>
                                <p data-en="Where to personally submit your requirements" data-tl="Saan personal na magsumite ng mga kinakailangan">Where to personally submit your requirements</p>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="info-card">
                                <div class="ic-title">📍 <span data-en="Location" data-tl="Lokasyon">Location</span></div>
                                <div class="ic-body" data-en="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor" data-tl="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor">
                                    Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="ic-title">🕐 <span data-en="Office Hours" data-tl="Oras ng Opisina">Office Hours</span></div>
                                <div class="ic-body" data-en="Monday – Friday · 8:00 AM – 5:00 PM (Closed on Holidays)" data-tl="Lunes – Biyernes · 8:00 AM – 5:00 PM (Sarado sa mga Pista Opisyal)">
                                    Monday – Friday · 8:00 AM – 5:00 PM <br><small style="color:#94a3b8;">(Closed on Holidays)</small>
                                </div>
                            </div>
                            <div class="info-card yellow">
                                <div class="ic-title">💬 <span data-en="Interview Options" data-tl="Mga Opsyon sa Panayam">Interview Options</span></div>
                                <div class="ic-body">
                                    <ul style="margin:0;padding-left:18px;line-height:2;">
                                        <li data-en="Face-to-face at the MSWDO office" data-tl="Harapan sa opisina ng MSWDO">Face-to-face at the MSWDO office</li>
                                        <li data-en="Online interview (if you cannot visit in person)" data-tl="Online na panayam (kung hindi ka makabisita)">Online interview (if you cannot visit in person)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bottom CTA strip -->
            <div class="cta-strip mt-2 mb-4">
                <div>
                    <div class="cta-text-main" data-en="Ready to apply for your Solo Parent ID?" data-tl="Handa ka na bang mag-apply para sa iyong Solo Parent ID?">Ready to apply for your Solo Parent ID?</div>
                    <div class="cta-text-sub" data-en="Schedule an appointment through this portal or visit the MSWDO office directly." data-tl="Mag-schedule ng appointment sa pamamagitan ng portal na ito o direktang bisitahin ang opisina ng MSWDO.">Schedule an appointment through this portal or visit the MSWDO office directly.</div>
                </div>
                <a href="{{ route('user.dashboard') }}"
                   style="background:var(--secondary-yellow);color:var(--purple);border-radius:12px;padding:13px 30px;font-weight:800;font-size:.92rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;transition:all .3s;"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(253,185,19,.5)'"
                   onmouseout="this.style.transform='';this.style.boxShadow=''">
                    🏠 <span data-en="Go to Dashboard" data-tl="Pumunta sa Dashboard">Go to Dashboard</span>
                </a>
            </div>

            <!-- Back link -->
            <div class="text-center py-2">
                <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.92rem;padding:12px 28px;background:transparent;border-color:rgba(124,58,237,.4);color:var(--purple);">
                    ← <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                </a>
            </div>

        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

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
            document.querySelectorAll('li[data-en]').forEach(li => {
                const text = lang === 'tl' ? (li.dataset.tl || li.dataset.en) : li.dataset.en;
                if (text) li.textContent = text;
            });
            const t = document.querySelector('title');
            if (t) t.textContent = lang === 'tl' ? t.dataset.tl : t.dataset.en;
        }
    </script>
</body>
</html>
