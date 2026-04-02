<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="PWD Application Guide – MSWDO" data-tl="Gabay sa PWD Application – MSWDO">PWD Application Guide – MSWDO</title>
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

        /* ── TOP BAR (not a full nav — just a slim identity bar) ── */
        .top-bar{background:var(--primary-gradient);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.2);}
        .top-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
        .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.45rem;}
        .brand img{width:34px;height:34px;object-fit:contain;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;text-decoration:none;}
        .back-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}

        /* ── HERO ── */
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

        /* ── SECTION CARD ── */
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
        .step-link{color:var(--primary-blue);font-weight:600;font-size:.84rem;word-break:break-all;}
        .connector{margin-left:19px;border-left:2px dashed var(--border-light);height:16px;}

        /* Info cards */
        .info-card{background:var(--primary-blue-light);border:1px solid rgba(44,62,143,.12);border-radius:14px;padding:18px 22px;margin-bottom:16px;}
        .info-card.yellow{background:var(--secondary-yellow-light);border-color:rgba(253,185,19,.3);}
        .info-card .ic-title{font-weight:700;color:var(--primary-blue);font-size:.88rem;margin-bottom:6px;}
        .info-card .ic-body{font-size:.85rem;color:#475569;line-height:1.65;}

        /* Action buttons */
        .btn-yellow{display:inline-flex;align-items:center;gap:10px;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:12px;padding:13px 28px;font-weight:800;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-yellow:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(253,185,19,.45);color:var(--primary-blue);}
        .btn-blue{display:inline-flex;align-items:center;gap:8px;background:var(--primary-gradient);color:white;border:none;border-radius:12px;padding:13px 28px;font-weight:700;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-blue:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 8px 22px rgba(44,62,143,.35);color:white;}

        /* CTA strip */
        .cta-strip{background:var(--primary-gradient);border-radius:16px;padding:22px 30px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
        .cta-strip .cta-text-main{color:white;font-weight:800;font-size:1rem;margin-bottom:3px;}
        .cta-strip .cta-text-sub{color:rgba(255,255,255,.75);font-size:.85rem;}

        /* PWD upload requirement compact boxes */
        .pwd-req{padding:10px 14px;border-radius:10px;background:#f8fafc;border-left:4px solid #dee2e6;margin-bottom:8px;}
        .pwd-req.approved{border-left-color:#28a745;background:#f0fff8;}
        .pwd-req.rejected{border-left-color:#dc3545;background:#fff5f5;}
        .pwd-req.in_review{border-left-color:#17a2b8;background:#f0faff;}
        .pwd-req.pending{border-left-color:#FDB913;background:#fffbea;}
        .pwd-req-name{font-weight:600;font-size:.85rem;color:#1e293b;}
        .pwd-thumb{width:36px;height:36px;object-fit:cover;border-radius:6px;cursor:pointer;vertical-align:middle;}
        .pwd-view{background:#2C3E8F;color:white;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600;text-decoration:none;}
        .pwd-view:hover{background:#1A2A5C;color:white;}
        .pwd-upload-box{background:#FFF3D6;border-radius:8px;padding:10px 12px;margin-top:8px;}
        .pwd-remark{font-size:.78rem;color:#dc3545;margin-top:5px;padding:5px 8px;background:#fff0f0;border-radius:6px;}


        /* Footer */
        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.85);text-align:center;padding:18px;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}
    </style>
</head>
<body>

    <!-- TOP BAR (not a navbar — just brand + back button) -->
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
                        <button class="lang-btn" data-lang="tl" onclick="setLang('tl')">TL</button>
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
                <div class="hero-badge" data-en="PWD Services" data-tl="Mga Serbisyo para sa PWD">PWD Services</div>
                <h1 data-en="PWD Application Guide" data-tl="Gabay sa PWD Application">PWD Application Guide</h1>
                <div class="hero-divider"></div>
                <p class="hero-sub" data-en="A complete, step-by-step guide to applying for a PWD ID and verifying your membership — all in one place, without visiting external websites." data-tl="Isang kumpletong hakbang-hakbang na gabay sa pag-apply ng PWD ID at pag-verify ng iyong pagkakasapi — lahat sa isang lugar, nang hindi kailangang bumisita sa mga external na website.">
                    A complete, step-by-step guide to applying for a PWD ID and verifying your membership — all in one place, without visiting external websites.
                </p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 py-5">
        <div class="container">
            <div class="row g-4">

                <!-- ═══ LEFT: GUIDES ═══ -->
                <div class="col-lg-7">

                    {{-- APPLICATION GUIDE --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">📋</div>
                            <div>
                                <h4 data-en="How to Avail a PWD ID" data-tl="Paano Makuha ang PWD ID">How to Avail a PWD ID</h4>
                                <p data-en="Follow these 5 steps to complete your application" data-tl="Sundin ang 5 hakbang na ito para makumpleto ang iyong aplikasyon">Follow these 5 steps to complete your application</p>
                            </div>
                        </div>
                        <div class="section-body">

                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Visit the Official PWD Website" data-tl="Bisitahin ang Opisyal na PWD Website">Visit the Official PWD Website</div>
                                    <div class="step-desc" data-en="Go to the DOH PWD portal to access all official forms and information." data-tl="Pumunta sa DOH PWD portal para ma-access ang lahat ng opisyal na forms at impormasyon.">
                                        Go to the DOH PWD portal to access all official forms and information.
                                    </div>
                                    <a href="https://pwd.doh.gov.ph" target="_blank" class="step-link mt-2 d-block">🌐 https://pwd.doh.gov.ph</a>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Download the Application Form" data-tl="I-download ang Application Form">Download the Application Form</div>
                                    <div class="step-desc" data-en="Go to the Downloads section of the website and download the official PRPWD Application Form. You may fill it out digitally or print it." data-tl="Pumunta sa Downloads section ng website at i-download ang opisyal na PRPWD Application Form. Maaari mo itong punan nang digital o i-print.">
                                        Go to the Downloads section of the website and download the official PRPWD Application Form. You may fill it out digitally or print it.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Fill Out & Submit the Form with Your Certificate of Disability" data-tl="Punan at Isumite ang Form kasama ang Iyong Sertipiko ng Kapansanan">Fill Out & Submit the Form with Your Certificate of Disability</div>
                                    <div class="step-desc" data-en="Complete all required fields in the form, then upload the completed form along with your Certificate of Disability to the official website." data-tl="Kumpletuhin ang lahat ng kinakailangang fields sa form, pagkatapos ay i-upload ang nakumpletong form kasama ang iyong Sertipiko ng Kapansanan sa opisyal na website.">
                                        Complete all required fields in the form, then upload the completed form along with your Certificate of Disability to the official website.
                                    </div>
                                    <div class="step-note">
                                        💡 <span data-en="You may also personally submit your documents at the MSWDO Office (Municipal Hall, Ground Floor) for faster processing." data-tl="Maaari ka ring personal na isumite ang iyong mga dokumento sa opisina ng MSWDO (Municipal Hall, Ground Floor) para mas mabilis na maproseso.">
                                            You may also personally submit your documents at the MSWDO Office (Municipal Hall, Ground Floor) for faster processing.
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <div class="step-item">
                                <div class="step-num">4</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Submit Two (2) 1×1 ID Pictures to the MSWDO" data-tl="Isumite ang Dalawang (2) 1×1 ID na Larawan sa MSWDO">Submit Two (2) 1×1 ID Pictures to the MSWDO</div>
                                    <div class="step-desc" data-en="Once your application is approved online, bring two (2) recent 1×1 ID pictures to the MSWDO office." data-tl="Kapag naaprubahan na ang iyong aplikasyon online, magdala ng dalawang (2) bagong 1×1 ID na larawan sa opisina ng MSWDO.">
                                        Once your application is approved online, bring two (2) recent 1×1 ID pictures to the MSWDO office.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <div class="step-item">
                                <div class="step-num">5</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Wait for Your Physical PWD ID" data-tl="Hintayin ang Iyong Pisikal na PWD ID">Wait for Your Physical PWD ID</div>
                                    <div class="step-desc" data-en="The MSWDO will process your physical PWD ID. You will be notified once it is ready for release." data-tl="Ipoproseso ng MSWDO ang iyong pisikal na PWD ID. Maabisuhan ka kapag handa na itong makuha.">
                                        The MSWDO will process your physical PWD ID. You will be notified once it is ready for release.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- VERIFICATION GUIDE --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">🔍</div>
                            <div>
                                <h4 data-en="How to Verify PWD Membership" data-tl="Paano Mag-verify ng PWD Membership">How to Verify PWD Membership</h4>
                                <p data-en="Check your PWD registration status in 3 simple steps" data-tl="Suriin ang iyong katayuan ng PWD registration sa 3 simpleng hakbang">Check your PWD registration status in 3 simple steps</p>
                            </div>
                        </div>
                        <div class="section-body">

                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Visit the Verification Page" data-tl="Bisitahin ang Verification Page">Visit the Verification Page</div>
                                    <div class="step-desc" data-en="Open the official PWD ID verification portal. You can use the button on this page to launch it directly." data-tl="Buksan ang opisyal na PWD ID verification portal. Maaari mong gamitin ang button sa pahinang ito para direktang buksan ito.">
                                        Open the official PWD ID verification portal. You can use the button on this page to launch it directly.
                                    </div>
                                    <a href="https://pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php" target="_blank" class="step-link mt-2 d-block">🌐 pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php</a>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Locate the Verification Section" data-tl="Hanapin ang Verification Section">Locate the Verification Section</div>
                                    <div class="step-desc" data-en="On the verification page, find the search box or verification form where you can enter your PWD details." data-tl="Sa verification page, hanapin ang search box o verification form kung saan maaari kang maglagay ng iyong PWD details.">
                                        On the verification page, find the search box or verification form where you can enter your PWD details.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Enter Your 16-Digit PWD ID Number or Full Name" data-tl="Ilagay ang Iyong 16-Digit PWD ID Number o Buong Pangalan">Enter Your 16-Digit PWD ID Number or Full Name</div>
                                    <div class="step-desc" data-en="Type your 16-digit PWD ID number (format: XXXX-XXXX-XXXX-XXXX) or your full registered name to look up your membership status." data-tl="I-type ang iyong 16-digit na PWD ID number (format: XXXX-XXXX-XXXX-XXXX) o ang iyong buong nakarehistrong pangalan para makita ang iyong katayuan ng pagkakasapi.">
                                        Type your 16-digit PWD ID number (format: <strong>XXXX-XXXX-XXXX-XXXX</strong>) or your full registered name to look up your membership status.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ═══ RIGHT: QUICK ACTIONS ═══ -->
                <div class="col-lg-5">

                    {{-- Form Download --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">📥</div>
                            <div>
                                <h4 data-en="Download Application Form" data-tl="I-download ang Application Form">Download Application Form</h4>
                                <p data-en="Official PRPWD Form — issued by DOH, free of charge" data-tl="Opisyal na PRPWD Form — inilabas ng DOH, libre">Official PRPWD Form — issued by DOH, free of charge</p>
                            </div>
                        </div>
                        <div class="section-body">
                            {{-- NEW: Fill Online CTA --}}
                            <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);border-radius:16px;padding:20px 22px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                                <div style="flex:1;min-width:180px;">
                                    <div style="color:var(--secondary-yellow);font-weight:800;font-size:.97rem;margin-bottom:4px;" data-en="📝 Fill Out Online (Recommended)" data-tl="📝 Punan Online (Inirerekomenda)">📝 Fill Out Online (Recommended)</div>
                                    <div style="color:rgba(255,255,255,.8);font-size:.82rem;line-height:1.5;" data-en="Complete the official form right here — then print and/or submit online to MSWDO." data-tl="Kumpletuhin ang opisyal na form dito mismo — pagkatapos ay i-print at/o isumite online sa MSWDO.">Complete the official form right here — then print and/or submit online to MSWDO.</div>
                                </div>
                                <a href="{{ route('user.pwd-form') }}" style="background:var(--secondary-yellow);color:var(--primary-blue);border-radius:10px;padding:12px 24px;font-weight:800;font-size:.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;flex-shrink:0;transition:all .3s;"
                                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 22px rgba(253,185,19,.5)'"
                                   onmouseout="this.style.transform='';this.style.boxShadow=''">
                                    ✏️ <span data-en="Open Fillable Form →" data-tl="Buksan ang Form →">Open Fillable Form →</span>
                                </a>
                            </div>
                            <div class="info-card mb-4">
                                <div class="ic-title" data-en="What you will download" data-tl="Ano ang iyong ma-download">What you will download</div>
                                <div class="ic-body" data-en="The official Persons with Disability (PWD) ID application form (PRPWD form) — recognized by all LGUs in the Philippines. You may print and fill it by hand, or fill it digitally before printing." data-tl="Ang opisyal na Application Form para sa Persons with Disability (PWD) ID (PRPWD form) — kinikilala ng lahat ng LGU sa Pilipinas. Maaari mo itong i-print at punan ng kamay, o punan nang digital bago i-print.">
                                    The official Persons with Disability (PWD) ID application form (PRPWD form) — recognized by all LGUs in the Philippines. You may fill it digitally or print by hand.
                                </div>
                            </div>
                            <a href="https://pwd.doh.gov.ph/downloads/PRPWD-APPLICATION_FORM.pdf"
                               target="_blank" class="btn-yellow w-100 mb-3">
                                <span>📄</span>
                                <span data-en="Download PRPWD Form (PDF)" data-tl="I-download ang PRPWD Form (PDF)">Download PRPWD Form (PDF)</span>
                            </a>
                            <a href="https://pwd.doh.gov.ph" target="_blank" class="btn-blue w-100">
                                <span>🌐</span>
                                <span data-en="Visit Official PWD Portal" data-tl="Bisitahin ang Opisyal na PWD Portal">Visit Official PWD Portal</span>
                            </a>
                        </div>
                    </div>

                    {{-- Office Details --}}
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
                                <div class="ic-title">📋 <span data-en="What to Bring" data-tl="Mga Dapat Dalhin">What to Bring</span></div>
                                <div class="ic-body">
                                    <ul style="margin:0;padding-left:18px;line-height:2;">
                                        <li data-en="Completed PRPWD Application Form" data-tl="Nakumpletong PRPWD Application Form">Completed PRPWD Application Form</li>
                                        <li data-en="Certificate of Disability (original + 1 photocopy)" data-tl="Sertipiko ng Kapansanan (orihinal + 1 photocopy)">Certificate of Disability (original + 1 photocopy)</li>
                                        <li data-en="Two (2) recent 1×1 ID pictures" data-tl="Dalawang (2) bagong 1×1 ID na larawan">Two (2) recent 1×1 ID pictures</li>
                                        <li data-en="Valid government-issued ID" data-tl="Valid na ID na inilabas ng gobyerno">Valid government-issued ID</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ══ UPLOAD REQUIREMENTS CARD (full-width, between columns and verifier) ══ --}}
            @if(session('upload_success'))
            <div style="background:#d4edda;border-left:4px solid #28a745;border-radius:12px;padding:12px 18px;margin-bottom:16px;font-size:.88rem;color:#155724;font-weight:600;">
                ✅ {{ session('upload_success') }}
            </div>
            @endif

            <div class="section-card" style="margin-bottom:28px;">
                <div class="section-header" style="flex-direction:column;align-items:flex-start;gap:4px;">
                    <div style="display:flex;align-items:center;gap:14px;width:100%;">
                        <div class="sec-icon">📤</div>
                        <div style="flex:1;">
                            <h4>Submit Your PWD Requirements Online</h4>
                            <p style="margin:0;opacity:.85;font-size:.85rem;">Upload digital copies of your documents. The admin will review each one.</p>
                        </div>
                        @if(isset($application) && $application)
                        <span style="background:rgba(255,255,255,.15);color:white;border-radius:20px;padding:3px 14px;font-size:.78rem;font-weight:700;">App #{{ $application->id }}</span>
                        @endif
                    </div>
                    @if(isset($application) && $application)
                    @php
                        $totalR    = count($pwdRequirements ?? []);
                        $approvedR = ($uploadedFiles ?? collect())->where('status','approved')->count();
                        $pctR      = $totalR > 0 ? round(($approvedR / $totalR) * 100) : 0;
                    @endphp
                    <div style="width:100%;margin-top:8px;">
                        <div style="height:5px;background:rgba(255,255,255,.2);border-radius:3px;overflow:hidden;">
                            <div style="width:{{ $pctR }}%;height:100%;background:var(--secondary-yellow);border-radius:3px;"></div>
                        </div>
                        <div style="font-size:.72rem;color:rgba(255,255,255,.8);margin-top:4px;">{{ $approvedR }}/{{ $totalR }} approved &mdash; {{ $pctR }}% complete</div>
                    </div>
                    @endif
                </div>

                <div class="section-body" style="padding:18px 22px;">

                    <div class="row g-3">
                    @foreach($pwdRequirements as $reqName)
                    @php
                        $uf      = ($uploadedFiles ?? collect())->firstWhere('requirement_name', $reqName);
                        $fStatus = $uf?->status ?? 'not_uploaded';
                        $cls     = match($fStatus) {
                            'approved'  => 'approved',
                            'rejected'  => 'rejected',
                            'in_review' => 'in_review',
                            'pending'   => 'pending',
                            default     => '',
                        };
                        $badge = match($fStatus) {
                            'approved'  => '<span style="background:#d4edda;color:#155724;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">✅ Approved</span>',
                            'rejected'  => '<span style="background:#f8d7da;color:#721c24;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">❌ Rejected</span>',
                            'in_review' => '<span style="background:#d1ecf1;color:#0c5460;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">🔍 In Review</span>',
                            'pending'   => '<span style="background:#FFF3D6;color:#856404;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">🕐 Pending</span>',
                            default     => '<span style="background:#e9ecef;color:#6c757d;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">📎 Not uploaded</span>',
                        };
                    @endphp
                    <div class="col-md-6">
                        <div class="pwd-req {{ $cls }}">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
                                <div style="flex:1;">
                                    <div class="pwd-req-name">{{ $reqName }}</div>
                                    @if($uf && $uf->uploaded_at)
                                    <div style="font-size:.7rem;color:#94a3b8;margin-top:1px;">{{ \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') }}</div>
                                    @endif
                                </div>
                                <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                                    {!! $badge !!}
                                    @if($uf && $uf->file_path)
                                    @php $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION)); @endphp
                                    @if(in_array($ext,['jpg','jpeg','png','webp']))
                                    <img src="{{ asset('storage/'.$uf->file_path) }}"
                                         onclick="window.open('{{ asset('storage/'.$uf->file_path) }}')"
                                         class="pwd-thumb">
                                    @endif
                                    <a href="{{ asset('storage/'.$uf->file_path) }}" target="_blank" class="pwd-view">View</a>
                                    @endif
                                </div>
                            </div>

                            @if($uf && $uf->admin_remarks)
                            <div class="pwd-remark"><strong>Note:</strong> {{ $uf->admin_remarks }}</div>
                            @endif

                            @if(!$uf || $fStatus === 'rejected')
                            <div class="pwd-upload-box">
                                <div style="font-size:.75rem;font-weight:600;color:#856404;margin-bottom:6px;">
                                    {{ $fStatus === 'rejected' ? '🔄 Choose replacement file' : '📎 Choose file to upload' }}
                                </div>
                                <input type="file"
                                       class="req-file form-control form-control-sm"
                                       data-req="{{ $reqName }}"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       onchange="onFileChosen()">
                                <div style="font-size:.64rem;color:#94a3b8;margin-top:3px;">Max 5 MB &middot; JPG PNG PDF</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    </div>

                    {{-- ── INLINE UPLOAD ALL ── --}}
                    <div id="inline-upload-wrap" style="display:none;margin-top:18px;background:#EEF2FF;border:1.5px solid #A0B6E8;border-radius:12px;padding:14px 18px;">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                            <div style="flex:1;min-width:0;">
                                <div id="inline-label" style="font-weight:800;color:#2C3E8F;font-size:.88rem;">📎 0 files selected</div>
                                <div style="margin-top:6px;height:5px;background:#dbe4ff;border-radius:3px;overflow:hidden;">
                                    <div id="inline-bar" style="height:100%;width:0%;background:#2C3E8F;border-radius:3px;transition:width .3s;"></div>
                                </div>
                                <div id="inline-status" style="font-size:.72rem;color:#64748b;margin-top:3px;"></div>
                            </div>
                            <button id="inline-btn" onclick="uploadAll()"
                                    style="background:#F6C90E;color:#1a2e8a;border:none;border-radius:9px;
                                           padding:9px 24px;font-weight:900;font-size:.85rem;
                                           cursor:pointer;white-space:nowrap;flex-shrink:0;
                                           box-shadow:0 3px 10px rgba(246,201,14,.35);">
                                📤 Upload All Selected
                            </button>
                        </div>
                    </div>

                    <div style="font-size:.8rem;color:#6c757d;margin-top:12px;">
                        💡 Track all your uploaded documents in
                        <a href="{{ route('user.my-requirements') }}" style="color:#2C3E8F;font-weight:600;">My Requirements →</a>
                    </div>
                </div>
            </div>

            {{-- PWD VERIFIER fullwidth CTA --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="sec-icon">🔍</div>
                    <div>
                        <h4 data-en="PWD Membership Verifier" data-tl="PWD Membership Verifier">PWD Membership Verifier</h4>
                        <p data-en="Open the official DOH verification portal to check any PWD registration status" data-tl="Buksan ang opisyal na DOH verification portal para suriin ang anumang katayuan ng PWD registration">Open the official DOH verification portal to check any PWD registration status</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-5">
                            <div style="background:linear-gradient(135deg,#f0f5ff,#e8f0fe);border-radius:16px;padding:28px;border:1px solid rgba(44,62,143,.1);">
                                <div style="font-size:2.4rem;margin-bottom:14px;">🔍</div>
                                <h5 style="font-weight:800;color:var(--primary-blue);margin-bottom:10px;" data-en="Verify Your PWD ID" data-tl="I-verify ang Iyong PWD ID">Verify Your PWD ID</h5>
                                <p style="font-size:.87rem;color:#475569;line-height:1.65;margin-bottom:20px;" data-en="Search using your 16-digit PWD ID number or your full registered name to confirm your membership status in the DOH database." data-tl="Maghanap gamit ang iyong 16-digit na PWD ID number o ang iyong buong nakarehistrong pangalan para kumpirmahin ang iyong katayuan ng pagkakasapi sa DOH database.">
                                    Search using your 16-digit PWD ID number or your full registered name to confirm your membership status in the DOH database.
                                </p>
                                <a href="https://pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php" target="_blank" class="btn-yellow w-100">
                                    <span>🌐</span>
                                    <span data-en="Launch PWD Verifier →" data-tl="Buksan ang PWD Verifier →">Launch PWD Verifier →</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="d-flex flex-column gap-3">
                                <div class="info-card">
                                    <div class="ic-title" data-en="Search Option 1 — PWD ID Number" data-tl="Opsyon sa Paghahanap 1 — PWD ID Number">Search Option 1 — PWD ID Number</div>
                                    <div class="ic-body" data-en="Enter your complete 16-digit PWD ID number in the format: XXXX-XXXX-XXXX-XXXX." data-tl="Ilagay ang inyong kumpletong 16-digit na PWD ID number sa format: XXXX-XXXX-XXXX-XXXX.">
                                        Enter your complete 16-digit PWD ID number. Format: <strong>XXXX-XXXX-XXXX-XXXX</strong>
                                    </div>
                                </div>
                                <div class="info-card">
                                    <div class="ic-title" data-en="Search Option 2 — Full Name" data-tl="Opsyon sa Paghahanap 2 — Buong Pangalan">Search Option 2 — Full Name</div>
                                    <div class="ic-body" data-en="Enter the full registered name of the PWD member, exactly as written on the application form." data-tl="Ilagay ang buong nakarehistrong pangalan ng miyembro ng PWD, nang eksakto tulad ng nakasulat sa application form.">
                                        Enter the full registered name of the PWD member, exactly as written on the application form.
                                    </div>
                                </div>
                                <div class="info-card yellow">
                                    <div class="ic-title">🔒 <span data-en="Privacy & Security" data-tl="Privacy at Seguridad">Privacy & Security</span></div>
                                    <div class="ic-body" data-en="The verification portal is hosted and managed directly by the Department of Health (DOH). MSWDO does not receive or store any information you enter on that portal." data-tl="Ang verification portal ay direktang ino-host at pinapamahalaan ng Department of Health (DOH). Hindi tinatanggap o iniimbak ng MSWDO ang anumang impormasyon na iyong inilalagay sa portal na iyon.">
                                        The verification portal is hosted and managed directly by the Department of Health (DOH). MSWDO does not receive or store any information you enter there.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cta-strip mt-4">
                        <div>
                            <div class="cta-text-main" data-en="Ready to verify your PWD membership?" data-tl="Handa na bang i-verify ang iyong PWD membership?">Ready to verify your PWD membership?</div>
                            <div class="cta-text-sub" data-en="Opens the official DOH verification portal in a new browser tab." data-tl="Magbubukas ng opisyal na DOH verification portal sa bagong browser tab.">Opens the official DOH verification portal in a new browser tab.</div>
                        </div>
                        <a href="https://pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php" target="_blank"
                           style="background:var(--secondary-yellow);color:var(--primary-blue);border-radius:12px;padding:13px 30px;font-weight:800;font-size:.92rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;transition:all .3s;"
                           onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(253,185,19,.5)'"
                           onmouseout="this.style.transform='';this.style.boxShadow=''">
                            🌐 <span data-en="Open Verifier Now" data-tl="Buksan ang Verifier Ngayon">Open Verifier Now</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Bottom back to dashboard --}}
            <div class="text-center py-3">
                <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.92rem;padding:12px 28px;">
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
        /* ── UPLOAD ALL (inline panel) ── */
        const _CSRF2       = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const _UPLOAD_URL2 = '{{ route("user.pwd-upload-requirement") }}';

        function onFileChosen() {
            const selected = [...document.querySelectorAll('.req-file')].filter(i => i.files[0]);
            const wrap = document.getElementById('inline-upload-wrap');
            const lbl  = document.getElementById('inline-label');
            if (selected.length > 0) {
                lbl.textContent = `📎 ${selected.length} file${selected.length > 1 ? 's' : ''} selected — ready to upload`;
                wrap.style.display = 'block';
            } else {
                wrap.style.display = 'none';
            }
        }

        async function uploadAll() {
            const inputs = [...document.querySelectorAll('.req-file')].filter(i => i.files[0]);
            if (!inputs.length) return;

            const btn    = document.getElementById('inline-btn');
            const bar    = document.getElementById('inline-bar');
            const status = document.getElementById('inline-status');
            const lbl    = document.getElementById('inline-label');
            btn.disabled = true;
            btn.style.opacity = '.6';

            let done = 0;
            for (const inp of inputs) {
                status.textContent = `Uploading ${done + 1} of ${inputs.length}: "${inp.dataset.req}"…`;
                const fd = new FormData();
                fd.append('_token',           _CSRF2);
                fd.append('requirement_name', inp.dataset.req);
                fd.append('file',             inp.files[0]);
                try {
                    await fetch(_UPLOAD_URL2, {
                        method: 'POST', body: fd,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    done++;
                    bar.style.width = Math.round((done / inputs.length) * 100) + '%';
                } catch (e) {
                    status.textContent = `❌ Failed: "${inp.dataset.req}"`;
                }
            }
            lbl.textContent = `✅ ${done} of ${inputs.length} uploaded — refreshing…`;
            setTimeout(() => location.reload(), 900);
        }

        /* ── LANGUAGE TOGGLE ── */
        let currentLang = 'en';
        function setLang(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.lang === lang);
            });
            // Swap all [data-en]/[data-tl] elements
            document.querySelectorAll('[data-en]').forEach(el => {
                const text = lang === 'tl' ? (el.dataset.tl || el.dataset.en) : el.dataset.en;
                if (text) el.textContent = text;
            });
            // Swap list items
            document.querySelectorAll('li[data-en]').forEach(li => {
                const text = lang === 'tl' ? (li.dataset.tl || li.dataset.en) : li.dataset.en;
                if (text) li.textContent = text;
            });
            // Update page title
            const t = document.querySelector('title');
            if (t) t.textContent = lang === 'tl' ? t.dataset.tl : t.dataset.en;
        }
    </script>
</body>
</html>
