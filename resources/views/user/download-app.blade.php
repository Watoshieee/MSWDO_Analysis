<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Download Mobile App - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: {{ $primaryColor ?? '#2C3E8F' }};
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: {{ $secondaryColor ?? '#FDB913' }};
            --secondary-yellow-light: #FFF3D6;
            --accent-green: #28a745;
            --accent-red: {{ $accentColor ?? '#C41E24' }};
            --accent-red-light: #FCE8E8;
            --primary-gradient: linear-gradient(135deg, {{ $primaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, {{ $secondaryColor ?? '#FDB913' }} 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-mid: #475569;
            --text-light: #94a3b8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { margin: 0; padding: 0; overscroll-behavior: none; }
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        /* Shared navbar styles handled by components.user-navbar */
        /* HERO BANNER */
        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 48px 0 40px;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -90px;
            right: -90px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(253,185,19,0.10);
        }
        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -50px;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .hero-banner h1 {
            font-weight: 900;
            font-size: 2.2rem;
            margin-bottom: 8px;
            line-height: 1.15;
        }
        .hero-divider {
            width: 50px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 14px 0 12px;
        }
        .hero-banner p {
            font-size: 1rem;
            opacity: 0.88;
            max-width: 560px;
            line-height: 1.65;
        }

        /* BACK BUTTON */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.85);
            font-size: 0.88rem;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 30px;
            padding: 7px 18px;
            transition: all 0.25s;
            margin-bottom: 18px;
        }
        .back-btn:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            border-color: rgba(255,255,255,0.5);
        }

        /* PANEL CARDS */
        .panel-card {
            background: white;
            border-radius: 18px;
            border: 1px solid var(--border-light);
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
            overflow: hidden;
            height: 100%;
        }
        .panel-header {
            background: var(--primary-gradient);
            color: white;
            padding: 18px 24px;
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: 0.02em;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-header .ph-badge {
            background: rgba(253,185,19,0.25);
            color: var(--secondary-yellow);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .panel-body { padding: 28px; }

        /* APP CARD */
        .app-icon-wrapper {
            width: 88px;
            height: 88px;
            margin: 0 auto 16px;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 6px 22px rgba(44,62,143,0.16);
            border: 3px solid white;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .app-icon-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }
        .app-name {
            font-weight: 900;
            font-size: 1.45rem;
            color: var(--primary-blue);
            margin-bottom: 4px;
        }
        .app-subtitle {
            color: var(--text-mid);
            font-size: 0.88rem;
            margin-bottom: 14px;
        }
        .app-desc {
            font-size: 0.92rem;
            color: var(--text-mid);
            line-height: 1.6;
            margin: 0 auto 22px;
            max-width: 480px;
        }

        /* META GRID */
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 24px;
        }
        .meta-item {
            background: var(--bg-soft-blue);
            border-radius: 12px;
            padding: 12px 10px;
            text-align: center;
            border: 1px solid rgba(44,62,143,0.08);
        }
        .meta-label {
            font-size: 0.68rem;
            color: var(--text-light);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .meta-value {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--primary-blue);
            white-space: nowrap;
        }

        
        /* PLATFORM NOTICE */
        .platform-notice {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--bg-soft-blue);
            border: 1px solid rgba(44, 62, 143, 0.15);
            border-radius: 12px;
            padding: 12px 18px;
            margin-bottom: 22px;
            font-size: 0.86rem;
            color: var(--text-mid);
            text-align: start;
            line-height: 1.5;
        }
        .platform-notice strong {
            color: var(--primary-blue);
        }
        .platform-notice i {
            font-size: 1.25rem;
            color: var(--primary-blue);
            flex-shrink: 0;
        }

        /* DOWNLOAD BUTTON */
        .download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: var(--secondary-gradient);
            color: var(--text-dark);
            font-weight: 800;
            font-size: 1.12rem;
            padding: 16px 28px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 18px rgba(253,185,19,0.36);
            text-decoration: none;
            width: 100%;
        }
        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(253,185,19,0.48);
            color: var(--text-dark);
        }
        .download-btn:active { transform: translateY(0); }
        .download-btn i { font-size: 1.35rem; }
        .download-btn-disabled {
            opacity: 0.55;
            cursor: not-allowed;
            pointer-events: none;
            background: #cbd5e1;
            color: #94a3b8;
            box-shadow: none;
        }

        /* BACK TO DASHBOARD BUTTON */
        .btn-dashboard {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: white;
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 13px 24px;
            border-radius: 14px;
            border: 2px solid var(--primary-blue);
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            width: 100%;
            margin-top: 12px;
        }
        .btn-dashboard:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-1px);
        }
        .btn-dashboard i { font-size: 1.1rem; }

        /* GOOGLE PLAY NOTE */
        .google-play-note {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--secondary-yellow-light);
            color: #856404;
            font-size: 0.84rem;
            font-weight: 600;
            padding: 11px 16px;
            border-radius: 10px;
            margin-top: 14px;
            border-left: 4px solid var(--secondary-yellow);
            text-align: start;
        }
        .google-play-note i {
            color: #ca9a00;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* INSTALLATION STEPS */
        .install-step {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border-light);
        }
        .install-step:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .step-num {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            background: var(--primary-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
        }
        .step-content h6 {
            font-weight: 700;
            font-size: 0.92rem;
            margin-bottom: 4px;
            color: var(--text-dark);
        }
        .step-content p {
            font-size: 0.84rem;
            color: var(--text-mid);
            margin-bottom: 0;
            line-height: 1.5;
        }
        .install-tip {
            background: var(--bg-soft-blue);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 0.82rem;
            color: var(--text-mid);
            line-height: 1.5;
            margin-top: 20px;
            border-left: 4px solid var(--primary-blue);
        }

        /* FOOTER */
        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255,255,255,0.7);
            text-align: center;
            padding: 20px 0;
            font-size: 0.84rem;
            margin-top: auto;
        }
        .footer-strip strong { color: white; }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .meta-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 767px) {
            .hero-banner { padding: 36px 0 30px; }
            .hero-banner h1 { font-size: 1.65rem; }
            .download-btn { font-size: 1.02rem; padding: 15px 20px; }
            .app-icon-wrapper { width: 76px; height: 76px; }
            .panel-body { padding: 20px 18px; }
        }
        @media (max-width: 480px) {
            .meta-grid { grid-template-columns: repeat(2, 1fr); }
            .app-name { font-size: 1.25rem; }
        }
    
    
        html { background: #1A2A5C; }
        body { padding-bottom: 0 !important; }
</style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    @include('components.user-navbar', ['active' => 'download-app'])

    <!-- ===== HERO BANNER ===== -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <a href="{{ route('user.dashboard') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
                <div class="hero-badge">Official Application</div>
                <h1>MSWDO Mobile App</h1>
                <div class="hero-divider"></div>
                <p>Download the official MSWDO mobile app to access programs, track applications, and receive updates &mdash; right from your phone.</p>
            </div>
        </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="container mt-4 mb-5" style="flex:1;">
        <div class="row justify-content-center g-4 align-items-start">

            <!-- Main Download Card -->
            <div class="col-lg-7">
                <div class="panel-card">
                    <div class="panel-header">
                        <span><i class="bi bi-phone-fill me-2"></i>Download the App</span>
                        <span class="ph-badge">Android</span>
                    </div>
                    <div class="panel-body text-center">

                        <!-- App Icon -->
                        <div class="app-icon-wrapper">
                            <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWDO App Icon">
                        </div>
                        <div class="app-name">MSWDO Beneficiary App</div>
                        <div class="app-subtitle">Municipal Social Welfare and Development Office</div>
                        <p class="app-desc">Access MSWDO services, submit required documents, and track your application status conveniently using your mobile phone.</p>

                        <!-- App Metadata -->
                        <div class="meta-grid text-start">
                            <div class="meta-item">
                                <div class="meta-label">Version</div>
                                <div class="meta-value">{{ $appInfo['version'] ?? '1.0.2' }}</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Size</div>
                                <div class="meta-value">{{ $appInfo['fileSizeMB'] ?? '25' }} MB</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Platform</div>
                                <div class="meta-value"><i class="bi bi-android2 me-1"></i>Android Only</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-label">Requires</div>
                                <div class="meta-value">Android 7.0+</div>
                            </div>
                        </div>

                                                <!-- Platform Compatibility Notice -->
                        <div class="platform-notice">
                            <i class="bi bi-info-circle-fill"></i>
                            <div>
                                <strong>Android only:</strong> Currently available for Android devices only. iOS/iPhone version is not currently available.
                            </div>
                        </div>

                        <!-- Download Button -->
                        @if($appInfo['googlePlayAvailable'] && $appInfo['googlePlayUrl'])
                            <a href="{{ $appInfo['googlePlayUrl'] }}" target="_blank" rel="noopener" class="download-btn">
                                <i class="bi bi-google-play"></i>
                                Get it on Google Play
                            </a>
                        @elseif($appInfo['fileExists'])
                            <a href="{{ asset('downloads/' . $appInfo['fileName']) }}" class="download-btn" download>
                                <i class="bi bi-download"></i>
                                Download Mobile App ({{ $appInfo['fileSizeMB'] }} MB)
                            </a>
                            <div class="google-play-note">
                                <i class="bi bi-info-circle-fill"></i>
                                <span>Google Play Store listing is under review. Direct APK download is available now.</span>
                            </div>
                        @else
                            <button class="download-btn download-btn-disabled" disabled>
                                <i class="bi bi-exclamation-circle"></i>
                                Download Currently Unavailable
                            </button>
                        @endif

                        <!-- Back to Dashboard Button -->
                        <a href="{{ route('user.dashboard') }}" class="btn-dashboard">
                            <i class="bi bi-house-door"></i>
                            Back to Dashboard
                        </a>

                    </div>
                </div>
            </div>

            <!-- Right Column: Simple Installation Guide -->
            <div class="col-lg-5">
                <div class="panel-card">
                    <div class="panel-header">
                        <span><i class="bi bi-list-ol me-2"></i>How to Install</span>
                        <span class="ph-badge">Easy Steps</span>
                    </div>
                    <div class="panel-body">
                        <div class="install-step">
                            <div class="step-num">1</div>
                            <div class="step-content">
                                <h6>Download the APK</h6>
                                <p>Tap the download button. If your phone asks for confirmation, select <strong>Download anyway</strong>.</p>
                            </div>
                        </div>
                        <div class="install-step">
                            <div class="step-num">2</div>
                            <div class="step-content">
                                <h6>Open the Downloaded File</h6>
                                <p>When download is complete, tap the notification or open the file from your phone's <strong>Downloads</strong> folder.</p>
                            </div>
                        </div>
                        <div class="install-step">
                            <div class="step-num">3</div>
                            <div class="step-content">
                                <h6>Install &amp; Log In</h6>
                                <p>Tap <strong>Install</strong> when prompted. Once complete, open the MSWDO app and log in with your account.</p>
                            </div>
                        </div>

                        <div class="install-tip">
                            <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                            <strong>Tip:</strong> If prompted by Android to allow apps from unknown sources, enable permission for your browser in <em>Settings</em> to finish installing.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- ===== NOTIFICATIONS MODAL ===== -->
    <div class="modal fade" id="announcementsModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:18px 24px;">
                    <h5 class="modal-title" style="font-weight:800;">Notifications</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:24px;max-height:60vh;overflow-y:auto;">
                    @if(isset($newAnnouncements) && $newAnnouncements->count() > 0)
                        @foreach($newAnnouncements as $ann)
                        <div style="padding:12px 0;border-bottom:1px solid var(--border-light);">
                            <strong style="color:var(--primary-blue);font-size:0.92rem;">{{ $ann->title }}</strong>
                            <p style="font-size:0.85rem;color:var(--text-mid);margin:4px 0 0;">{{ Str::limit($ann->content, 120) }}</p>
                            <small style="color:var(--text-light);">{{ $ann->created_at ? $ann->created_at->diffForHumans() : '' }}</small>
                        </div>
                        @endforeach
                    @else
                        <p style="text-align:center;color:var(--text-light);padding:24px 0;">No notifications</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('announcementsModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route('user.mark-notifications-viewed') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const badge = document.querySelector('.btn[data-bs-target="#announcementsModal"] span');
                      if (badge) { badge.style.display = 'none'; }
                  }
              });
        });
    </script>


    <div class="footer-strip"></div>
</body>
</html>