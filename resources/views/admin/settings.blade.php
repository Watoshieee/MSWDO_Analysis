<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>UI Settings – MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: {{ $primaryColor }};
            --secondary-yellow: {{ $secondaryColor }};
            --accent-red: {{ $accentColor }};
            --primary-gradient: linear-gradient(135deg, var(--primary-blue) 0%, color-mix(in srgb, var(--primary-blue) 80%, black) 100%);
            --secondary-gradient: linear-gradient(135deg, var(--secondary-yellow) 0%, color-mix(in srgb, var(--secondary-yellow) 90%, black) 100%);
            --bg-light: #e2e8f0;
            --border-light: #cbd5e1;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-light);
            min-height: 100vh;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44, 62, 143, .18);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.55rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, .88) !important;
            font-weight: 600;
            transition: all .25s;
            border-radius: 8px;
            padding: 10px 18px !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .15);
            color: white !important;
        }

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 700;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, .1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: .92rem;
            font-weight: 500;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, .8);
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 700;
            transition: all .3s;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 52px 0 42px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner h1 {
            font-size: 2.4rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .settings-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .03);
            border: 1px solid var(--border-light);
            margin-bottom: 28px;
        }

        .color-picker-group {
            margin-bottom: 24px;
        }

        .color-picker-group label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
            display: block;
        }

        .color-input-wrapper {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .color-input-wrapper input[type="color"] {
            width: 80px;
            height: 50px;
            border: 2px solid var(--border-light);
            border-radius: 10px;
            cursor: pointer;
        }

        .color-input-wrapper input[type="text"] {
            flex: 1;
            border: 1.5px solid var(--border-light);
            border-radius: 10px;
            padding: 10px 14px;
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .preview-box {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 2px solid var(--border-light);
        }

        .preview-navbar {
            background: var(--primary-gradient);
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-weight: 700;
        }

        .preview-button {
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            border: none;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 700;
            margin-right: 12px;
        }

        .preview-accent {
            background: var(--accent-red);
            color: white;
            padding: 10px 24px;
            border-radius: 10px;
            font-weight: 700;
        }

        .btn-save {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 32px;
            font-weight: 700;
            transition: all .3s;
            cursor: pointer;
        }

        .btn-save:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-reset {
            background: transparent;
            color: #64748b;
            border: 1.5px solid var(--border-light);
            border-radius: 30px;
            padding: 12px 32px;
            font-weight: 600;
            cursor: pointer;
        }

        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, .75);
            text-align: center;
            padding: 18px 0;
            font-size: .85rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Detailed Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.applications') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Requirements</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.settings') }}">⚙️ Settings</a></li>
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

    <section class="hero-banner">
        <div class="container">
            <h1>⚙️ UI Settings</h1>
            <p style="opacity:.87;">Customize ang kulay ng system interface</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <div class="col-lg-6">
                <div class="settings-card">
                    <h5 style="color:var(--primary-blue);font-weight:700;margin-bottom:24px;">Color Customization</h5>
                    
                    <div class="color-picker-group">
                        <label>Primary Color (Navbar, Headers)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="primaryColor" value="{{ $primaryColor }}">
                            <input type="text" id="primaryColorText" value="{{ $primaryColor }}" readonly>
                        </div>
                    </div>

                    <div class="color-picker-group">
                        <label>Secondary Color (Buttons, Highlights)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="secondaryColor" value="{{ $secondaryColor }}">
                            <input type="text" id="secondaryColorText" value="{{ $secondaryColor }}" readonly>
                        </div>
                    </div>

                    <div class="color-picker-group">
                        <label>Accent Color (Alerts, Delete Actions)</label>
                        <div class="color-input-wrapper">
                            <input type="color" id="accentColor" value="{{ $accentColor }}">
                            <input type="text" id="accentColorText" value="{{ $accentColor }}" readonly>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button class="btn-save" onclick="saveColors()">💾 Save Changes</button>
                        <button class="btn-reset" onclick="resetColors()">🔄 Reset to Default</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="settings-card">
                    <h5 style="color:var(--primary-blue);font-weight:700;margin-bottom:24px;">Live Preview</h5>
                    <div class="preview-box">
                        <div class="preview-navbar" id="previewNavbar">
                            Sample Navbar
                        </div>
                        <button class="preview-button" id="previewButton">Sample Button</button>
                        <button class="preview-accent" id="previewAccent">Delete Action</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> – Municipal Social Welfare & Development Office © {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // Update text inputs and preview when color picker changes
        document.getElementById('primaryColor').addEventListener('input', function(e) {
            document.getElementById('primaryColorText').value = e.target.value;
            updatePreview();
        });

        document.getElementById('secondaryColor').addEventListener('input', function(e) {
            document.getElementById('secondaryColorText').value = e.target.value;
            updatePreview();
        });

        document.getElementById('accentColor').addEventListener('input', function(e) {
            document.getElementById('accentColorText').value = e.target.value;
            updatePreview();
        });

        function updatePreview() {
            const primary = document.getElementById('primaryColor').value;
            const secondary = document.getElementById('secondaryColor').value;
            const accent = document.getElementById('accentColor').value;

            document.getElementById('previewNavbar').style.background = `linear-gradient(135deg, ${primary} 0%, color-mix(in srgb, ${primary} 80%, black) 100%)`;
            document.getElementById('previewButton').style.background = `linear-gradient(135deg, ${secondary} 0%, color-mix(in srgb, ${secondary} 90%, black) 100%)`;
            document.getElementById('previewButton').style.color = primary;
            document.getElementById('previewAccent').style.background = accent;
        }

        function saveColors() {
            const data = {
                primary_color: document.getElementById('primaryColor').value,
                secondary_color: document.getElementById('secondaryColor').value,
                accent_color: document.getElementById('accentColor').value
            };

            fetch('{{ route("admin.settings.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Error saving settings.', 'danger');
                }
            })
            .catch(() => showToast('Network error.', 'danger'));
        }

        function resetColors() {
            if (!confirm('Reset all colors to default values?')) return;

            fetch('{{ route("admin.settings.reset") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(result => {
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('Error resetting settings.', 'danger');
                }
            })
            .catch(() => showToast('Network error.', 'danger'));
        }

        function showToast(message, type = 'success') {
            const colors = { success: '#2C3E8F', danger: '#C41E24', warning: '#E5A500' };
            const t = document.createElement('div');
            t.style.cssText = `position:fixed;bottom:28px;right:28px;z-index:9999;background:${colors[type]};color:white;padding:14px 22px;border-radius:12px;font-weight:600;font-size:.9rem;box-shadow:0 8px 24px rgba(0,0,0,.18);`;
            t.textContent = message;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3500);
        }
    </script>
</body>
</html>
