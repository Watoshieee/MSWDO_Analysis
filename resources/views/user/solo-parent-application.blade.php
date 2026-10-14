<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="Solo Parent Application — MSWDO" data-tl="Solo Parent Application — MSWDO">Solo Parent Application — MSWDO</title>
    <link rel="icon" type="image/jpeg" href="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #1e2a78;
            --navy-d: #1a2470;
            --gold: #f5b82e;
            --gold-h: #ffc94d;
            --ink: #1b2559;
            --muted: #4d5878;
            --bg: #f4f7fd;
            --line: #d9e1f3;
            --tint: #e8eefc;
            --red: #c93a2f;
            --ok: #0b6b34;
            --primary-blue: #1e2a78;
            --primary-gradient: linear-gradient(135deg, #1a2470 0%, #243088 70%, #2a3b96 100%);
            --secondary-yellow: #f5b82e;
            --secondary-gradient: linear-gradient(135deg, #f5b82e 0%, #e5a500 100%);
            --border-light: #d9e1f3;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; -webkit-text-size-adjust: 100%; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }
        button, input, textarea, select { font-family: inherit; }
        .sr { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0 0 0 0); white-space: nowrap; }

        .i {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex: none;
            display: inline-block;
            vertical-align: middle;
        }
        .i-sm { width: 17px; height: 17px; }
        .i-lg { width: 28px; height: 28px; }

        a:focus-visible, button:focus-visible, textarea:focus-visible, input:focus-visible, select:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 2px;
        }

        .wrap {
            max-width: 1220px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
        }

        /* Hero Header Band */
        .hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(160deg, #1a2470 0%, #243088 70%, #2a3b96 100%);
            color: #fff;
            padding: 18px 0 36px;
            box-shadow: 0 4px 24px rgba(26, 36, 112, 0.28);
        }
        .hero:after {
            content: "";
            position: absolute;
            right: -90px;
            top: 40px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }
        .hero .wrap { position: relative; z-index: 1; }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 22px;
            color: #fff;
        }
        .brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 10px;
            background: #fff;
            padding: 2px;
        }
        .tools {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .lang {
            display: inline-flex;
            border: 2px solid rgba(255, 255, 255, 0.35);
            border-radius: 99px;
            overflow: hidden;
        }
        .lang button {
            border: 0;
            background: transparent;
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            padding: 8px 16px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .lang button.active, .lang button[aria-pressed="true"] {
            background: var(--gold);
            color: #172066;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 99px;
            padding: 8px 20px;
            font-weight: 700;
            font-size: 14px;
            color: #fff;
            white-space: nowrap;
            transition: all 0.2s;
            text-decoration: none;
        }
        .back-btn:hover {
            background: var(--gold);
            color: #172066;
            border-color: var(--gold);
        }
        .eb {
            display: inline-block;
            margin-top: 28px;
            padding: 6px 16px;
            border-radius: 99px;
            background: rgba(245, 184, 46, 0.16);
            border: 1px solid rgba(245, 184, 46, 0.4);
            color: var(--gold);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .hero h1 {
            margin: 12px 0 0;
            font-size: clamp(28px, 4.5vw, 42px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .hero-divider {
            width: 52px;
            height: 4px;
            border-radius: 2px;
            background: var(--gold);
            margin: 14px 0 16px;
        }
        .hero p {
            margin: 0;
            max-width: 720px;
            color: #d4dcf4;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Generic Card Architecture */
        .card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--line);
            box-shadow: 0 4px 20px rgba(20, 34, 79, 0.07);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .card-h {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px 16px;
            flex-wrap: wrap;
            padding: 20px 24px;
            background: linear-gradient(135deg, #1a2470 0%, #243088 100%);
            color: #fff;
        }
        .card-h .l {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }
        .card-h .ico {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.14);
            display: grid;
            place-items: center;
            flex: none;
            color: var(--gold);
            font-size: 1.25rem;
        }
        .card-h h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.2px;
        }
        .card-h p {
            margin: 3px 0 0;
            font-size: 14px;
            color: #d4dcf4;
        }
        .tag {
            padding: 6px 14px;
            border-radius: 99px;
            background: rgba(245, 184, 46, 0.18);
            color: var(--gold);
            font-weight: 800;
            font-size: 12.5px;
            white-space: nowrap;
            letter-spacing: 0.05em;
        }
        .card-b { padding: 26px 28px; }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 50px;
            padding: 0 26px;
            border-radius: 12px;
            border: 2px solid transparent;
            font-weight: 800;
            font-size: 15.5px;
            cursor: pointer;
            transition: transform 0.15s, background 0.15s, box-shadow 0.15s;
            text-decoration: none;
        }
        .btn:hover:not(:disabled) { transform: translateY(-2px); }
        .btn-navy {
            background: var(--navy);
            color: #fff;
            box-shadow: 0 6px 16px rgba(30, 42, 120, 0.28);
        }
        .btn-navy:hover:not(:disabled) {
            background: var(--navy-d);
            color: #fff;
            box-shadow: 0 8px 20px rgba(30, 42, 120, 0.35);
        }
        .btn-out {
            background: #fff;
            border-color: var(--line);
            color: var(--navy);
        }
        .btn-out:hover:not(:disabled) {
            border-color: var(--navy);
            background: var(--tint);
            color: var(--navy);
        }
        .btn-gold {
            background: var(--gold);
            color: #172066;
            font-weight: 800;
        }
        .btn-gold:hover:not(:disabled) {
            background: var(--gold-h);
            color: #172066;
        }
        .btn:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            box-shadow: none;
            transform: none !important;
        }
        .link {
            background: none;
            border: 0;
            padding: 0;
            color: var(--navy);
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
            cursor: pointer;
            font-size: inherit;
        }
        h2.sh {
            margin: 0 0 6px;
            font-size: clamp(22px, 3.2vw, 28px);
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.3px;
        }
        p.ss {
            margin: 0 0 22px;
            color: var(--muted);
            font-size: 15.5px;
            line-height: 1.5;
        }

        /* Choice Section (New vs Renewal) */
        .opts {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 340px), 1fr));
            gap: 18px;
        }
        .opt { display: block; cursor: pointer; }
        .opt-b {
            position: relative;
            height: 100%;
            background: #fff;
            border: 2px solid var(--line);
            border-radius: 20px;
            padding: 26px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: border-color 0.15s, box-shadow 0.15s, transform 0.15s;
        }
        .opt:hover .opt-b {
            border-color: #9fb0e6;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(30, 42, 120, 0.08);
        }
        .opt input:checked + .opt-b {
            border-color: var(--navy);
            background: #f3f6ff;
            box-shadow: 0 0 0 4px rgba(30, 42, 120, 0.12);
        }
        .opt-ic {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: var(--navy);
            color: var(--gold);
            display: grid;
            place-items: center;
        }
        .opt h3 {
            margin: 6px 0 0;
            font-size: 20px;
            font-weight: 800;
            color: var(--ink);
        }
        .opt p {
            margin: 0;
            color: var(--muted);
            line-height: 1.55;
            font-size: 15px;
        }
        .opt .chip {
            align-self: flex-start;
            margin-top: auto;
            padding: 5px 12px;
            border-radius: 99px;
            background: var(--tint);
            color: var(--navy);
            font-weight: 700;
            font-size: 12.5px;
        }
        .opt .tick {
            position: absolute;
            right: 18px;
            top: 18px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid var(--line);
            display: grid;
            place-items: center;
            color: transparent;
            background: #fff;
            transition: all 0.2s;
        }
        .opt input:checked + .opt-b .tick {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }
        .cont {
            display: flex;
            align-items: center;
            gap: 18px;
            flex-wrap: wrap;
            margin-top: 22px;
            padding-top: 14px;
        }
        .hint { margin: 0; color: var(--muted); font-size: 14.5px; }

        .picked {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            background: #fff;
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 14px 20px;
            font-size: 15px;
            margin-bottom: 24px;
            color: var(--ink);
            box-shadow: 0 2px 10px rgba(30, 42, 120, 0.05);
        }
        .picked b, .picked strong { color: var(--navy); font-weight: 800; }

        /* Wizard Header, Stepper & Panels */
        .wz-h {
            padding: 22px 28px 20px;
            background: linear-gradient(135deg, #1a2470 0%, #243088 100%);
            color: #fff;
        }
        .wz-h h2 { margin: 0; font-size: clamp(21px, 3.2vw, 26px); font-weight: 800; }
        .wz-h p { margin: 4px 0 14px; color: #d4dcf4; font-size: 15px; }
        .bar {
            height: 6px;
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        .bar i {
            display: block;
            height: 100%;
            width: 16%;
            background: var(--gold);
            border-radius: 3px;
            transition: width 0.35s ease;
        }
        .stepper {
            list-style: none;
            margin: 0;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            gap: 4px;
            border-bottom: 1px solid var(--line);
            background: #fafcff;
            overflow-x: auto;
        }
        .stp {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            min-width: 80px;
        }
        .stp button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--line);
            background: #fff;
            color: var(--muted);
            font-weight: 800;
            font-size: 15px;
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: all 0.25s;
        }
        .stp.done button {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }
        .stp.active button, .stp.on button {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(30, 42, 120, 0.18);
        }
        .stp span {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--muted);
            text-align: center;
            line-height: 1.25;
            max-width: 95px;
        }
        .stp.active span, .stp.on span { color: var(--navy); }

        .panel { padding: 28px; }
        .panel h3, .panel-heading {
            margin: 0 0 6px;
            font-size: 21px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.2px;
        }
        .panel > p.sub, .panel-sub {
            margin: 0 0 22px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.5;
        }

        /* Overview Step Tiles */
        .tiles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .tile {
            background: var(--tint);
            border: 1px solid #cfd9f4;
            border-radius: 16px;
            padding: 22px 18px;
            text-align: center;
            transition: transform 0.2s;
        }
        .tile:hover { transform: translateY(-2px); }
        .tile b {
            display: block;
            font-size: 38px;
            line-height: 1;
            font-weight: 900;
            color: var(--navy);
        }
        .tile span {
            display: block;
            margin-top: 10px;
            font-weight: 700;
            font-size: 14.5px;
            color: var(--ink);
            line-height: 1.4;
        }
        .notes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
            gap: 16px;
        }
        .note {
            border-radius: 14px;
            padding: 18px 20px;
            line-height: 1.55;
            font-size: 15px;
        }
        .note b {
            display: block;
            margin-bottom: 5px;
            color: var(--navy);
            font-size: 16px;
            font-weight: 800;
        }
        .note.warn { background: #fff3cf; border: 1.5px solid #f0d98c; color: #856404; }
        .note.info { background: #e8eefc; border: 1.5px solid #cfd9f4; color: #1e3a8a; }

        /* Step 3: How to apply vertical line */
        .how { list-style: none; margin: 0; padding: 0; position: relative; }
        .how li { position: relative; display: flex; gap: 18px; padding-bottom: 24px; }
        .how li:last-child { padding-bottom: 0; }
        .how li:before {
            content: "";
            position: absolute;
            left: 17px;
            top: 36px;
            bottom: 2px;
            width: 2px;
            background: var(--line);
        }
        .how li:last-child:before { display: none; }
        .how .dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--navy);
            color: var(--gold);
            display: grid;
            place-items: center;
            flex: none;
            position: relative;
            z-index: 1;
            font-weight: 800;
            font-size: 15px;
        }
        .how h4 { margin: 4px 0 3px; font-size: 16.5px; font-weight: 800; color: var(--navy); }
        .how p { margin: 0; color: var(--muted); font-size: 14.5px; line-height: 1.5; }

        /* Step 4: Office Info */
        .off {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));
            gap: 16px;
        }
        .off div {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            background: var(--tint);
            border: 1px solid #cfd9f4;
            border-radius: 14px;
            padding: 20px;
        }
        .off .i { color: var(--navy); margin-top: 3px; }
        .off b { display: block; margin-bottom: 4px; font-size: 16px; color: var(--navy); font-weight: 800; }
        .off span { color: var(--muted); font-size: 14.5px; line-height: 1.5; }

        /* Wizard Navigation */
        .wz-n {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 28px;
            border-top: 1px solid var(--line);
            background: #fafcff;
        }
        .wz-n .c { font-weight: 700; font-size: 14.5px; color: var(--muted); }

        /* Form elements matching reference */
        .fld { margin-bottom: 20px; }
        .fld > label, .fld > .lb, .sp-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 15px;
            color: var(--ink);
        }
        .req { color: var(--red); }
        .sp-input, .in {
            width: 100%;
            min-height: 50px;
            border: 1.5px solid #c6d1ee;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 15.5px;
            color: var(--ink);
            background: #fff;
            transition: border-color 0.2s;
        }
        .sp-input:focus, .in:focus {
            outline: 3px solid var(--gold);
            outline-offset: 0;
            border-color: transparent;
        }
        .sp-hint, .help { margin: 6px 0 0; font-size: 13.5px; color: var(--muted); }

        /* Booking Card integration */
        .sp-booking-card {
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .sp-booking-header {
            padding: 16px 20px;
            background: #f8faff;
            border-bottom: 1.5px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .sp-booking-title { font-weight: 800; font-size: 1.1rem; color: var(--navy); }
        .sp-booking-sub { font-size: 0.85rem; color: var(--muted); }
        .sp-booking-body { padding: 22px 24px; }
        .sp-office-hours {
            background: var(--tint);
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 16px;
            color: #1e3a8a;
            font-size: 0.88rem;
            margin-bottom: 20px;
        }
        .sp-appt-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }
        .sp-appt-tile {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 12px 14px;
        }
        .sp-appt-label { font-size: 0.72rem; font-weight: 800; color: var(--muted); text-transform: uppercase; }
        .sp-appt-val { font-size: 0.95rem; font-weight: 800; color: var(--ink); margin-top: 3px; }

        .sp-booking-icon {
            width: 44px;
            height: 44px;
            background: rgba(245, 184, 46, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex: none;
        }
        .sp-appt-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 16px;
        }
        .sp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 12px;
            padding: 10px 22px;
            font-weight: 800;
            font-size: 14.5px;
            cursor: pointer;
            border: none;
            transition: transform 0.15s, background 0.15s, box-shadow 0.15s;
            text-decoration: none;
        }
        .sp-btn:hover:not(:disabled) {
            transform: translateY(-2px);
        }
        .sp-btn-primary {
            background: var(--navy);
            color: #fff;
            box-shadow: 0 4px 14px rgba(30, 42, 120, 0.25);
        }
        .sp-btn-primary:hover:not(:disabled) {
            background: var(--navy-d);
            color: #fff;
            box-shadow: 0 6px 18px rgba(30, 42, 120, 0.35);
        }
        .sp-btn-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1.5px solid #fca5a5;
        }
        .sp-btn-danger:hover:not(:disabled) {
            background: #fecaca;
            color: #7f1d1d;
        }
        .sp-btn-outline {
            background: #fff;
            color: var(--navy);
            border: 1.5px solid var(--line);
        }
        .sp-btn-outline:hover:not(:disabled) {
            background: var(--tint);
            border-color: var(--navy);
        }
        .sp-btn-wait {
            background: #e0f2fe;
            color: #0369a1;
            border: 1.5px solid #bae6fd;
            cursor: default;
        }
        .sp-btn-sm {
            padding: 6px 14px;
            font-size: 13px;
            border-radius: 99px;
        }
        .sp-note {
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 14.5px;
            line-height: 1.55;
            margin-bottom: 16px;
        }
        .sp-note.yellow {
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            border-left: 5px solid var(--gold);
            color: #92400e;
        }
        .sp-note.reminder {
            background: #fefce8;
            border: 2px solid var(--gold);
            color: #854d0e;
            font-weight: 700;
        }
        .sp-note.rejected {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-left: 5px solid #dc2626;
            color: #991b1b;
        }
        .sp-timepicker-icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        .sp-beneficiary {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        /* Segmented control for interview type */
        .seg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .seg label { display: block; margin: 0; cursor: pointer; }
        .seg label span {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 48px;
            border: 1.5px solid #c6d1ee;
            border-radius: 12px;
            padding: 8px 14px;
            font-weight: 700;
            font-size: 14.5px;
            color: var(--ink);
            background: #fff;
            transition: all 0.2s;
        }
        .seg input:checked + span {
            background: var(--navy);
            border-color: var(--navy);
            color: #fff;
        }

        /* Time Picker Trigger Button */
        .sp-timepicker-btn {
            width: 100%;
            min-height: 50px;
            border: 1.5px solid #c6d1ee;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 15px;
            font-weight: 700;
            color: var(--navy);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s;
        }
        .sp-timepicker-btn:hover:not(:disabled) {
            border-color: var(--navy);
            background: #f8faff;
        }
        .sp-timepicker-btn.has-value {
            background: #eef3ff;
            border-color: var(--navy);
        }
        .sp-timepicker-btn:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            border-color: #cbd5e1;
            cursor: not-allowed;
        }

        /* Clock Modal Architecture (Preserved UI & Behavior) */
        .clock-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(2px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .clock-modal-overlay.open { display: flex; }
        .clock-modal {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 340px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: clockPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes clockPop { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .clock-header {
            background: linear-gradient(135deg, #1a2470 0%, #243088 100%);
            color: #fff;
            padding: 18px 24px 14px;
            text-align: center;
        }
        .clock-header-label { font-size: 0.76rem; font-weight: 700; opacity: 0.8; letter-spacing: 0.05em; text-transform: uppercase; }
        .clock-digital {
            font-size: 2.2rem;
            font-weight: 900;
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        .clock-digit-hour, .clock-digit-min {
            cursor: pointer;
            padding: 2px 8px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .clock-digit-hour.active, .clock-digit-min.active {
            background: rgba(245, 184, 46, 0.25);
            color: var(--gold);
        }
        .clock-digit-sep { opacity: 0.7; }
        .clock-digit-ampm { font-size: 1.1rem; font-weight: 700; margin-left: 6px; color: var(--gold); }
        .clock-face-wrap {
            padding: 20px 24px;
            display: flex;
            justify-content: center;
            background: #f8faff;
        }
        .clock-face {
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: #e2e8f0;
            position: relative;
            user-select: none;
        }
        .clock-center-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--navy);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 4;
        }
        .clock-hand {
            position: absolute;
            bottom: 50%;
            left: 50%;
            width: 3px;
            background: var(--navy);
            transform-origin: bottom center;
            border-radius: 3px 3px 0 0;
            z-index: 2;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .clock-num {
            position: absolute;
            width: 34px;
            height: 34px;
            margin-left: -17px;
            margin-top: -17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            z-index: 3;
            transition: all 0.15s;
        }
        .clock-num:hover:not(.disabled):not(.full-slot) { background: #cbd5e1; }
        .clock-num.selected { background: var(--navy); color: #fff; }
        .clock-num.disabled { opacity: 0.25; cursor: not-allowed; }
        .clock-num.full-slot { color: #dc2626; text-decoration: line-through; opacity: 0.45; cursor: not-allowed; }
        .clock-slot-info {
            padding: 8px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            text-align: center;
        }
        .clock-slot-info.avail { color: #15803d; background: #dcfce7; }
        .clock-slot-info.full { color: #dc2626; background: #fee2e2; }
        .clock-slot-info.none { color: #64748b; background: #f1f5f9; }
        .clock-actions {
            padding: 12px 18px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            border-top: 1px solid var(--line);
        }
        .clock-btn-cancel {
            background: transparent;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--muted);
            cursor: pointer;
        }
        .clock-btn-ok {
            background: var(--navy);
            color: #fff;
            border: none;
            padding: 8px 22px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.88rem;
            cursor: pointer;
        }
        .clock-btn-ok:disabled { opacity: 0.4; cursor: not-allowed; }

        /* Tracker / Monitor View */
        .monitor-card {
            background: #fff;
            border-radius: 20px;
            border: 1.5px solid var(--line);
            box-shadow: 0 4px 20px rgba(20, 34, 79, 0.07);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .monitor-header {
            background: linear-gradient(135deg, #1a2470 0%, #243088 100%);
            color: #fff;
            padding: 22px 28px;
        }
        .monitor-stats-bar {
            display: flex;
            gap: 12px;
            background: #fafcff;
            border-bottom: 1.5px solid var(--line);
            padding: 14px 24px;
            overflow-x: auto;
        }
        .monitor-stat {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .monitor-stat .sv { color: var(--navy); font-weight: 800; }
        .monitor-body-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 24px;
            padding: 24px 28px;
        }
        @media (max-width: 900px) {
            .monitor-body-grid { grid-template-columns: 1fr; }
        }

        /* Timeline Items */
        .timeline { list-style: none; margin: 0; padding: 0; position: relative; }
        .tl-item { position: relative; display: flex; gap: 14px; padding-bottom: 20px; }
        .tl-item:last-child { padding-bottom: 0; }
        .tl-item:before {
            content: "";
            position: absolute;
            left: 14px;
            top: 30px;
            bottom: 0;
            width: 2px;
            background: var(--line);
        }
        .tl-item:last-child:before { display: none; }
        .tl-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e2e8f0;
            color: var(--muted);
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 800;
            z-index: 1;
            flex: none;
        }
        .tl-item.done .tl-dot { background: #22c55e; color: #fff; }
        .tl-item.active .tl-dot { background: var(--navy); color: #fff; box-shadow: 0 0 0 3px rgba(30, 42, 120, 0.2); }
        .tl-title { font-weight: 800; font-size: 14.5px; color: var(--navy); }
        .tl-desc { font-size: 13px; color: var(--muted); margin-top: 2px; }

        /* Status Pills */
        .status-pill {
            padding: 6px 16px;
            border-radius: 99px;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.03em;
        }
        .status-pill.approved { background: #dcfce7; color: #15803d; }
        .status-pill.validated { background: #e0f2fe; color: #0369a1; }
        .status-pill.confirmed { background: #fef3c7; color: #b45309; }
        .status-pill.pending   { background: #f1f5f9; color: #475569; }

        /* Info notes / callouts */
        .info-card {
            background: #f8faff;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 14px;
        }
        .info-card.yellow { background: #fffbeb; border-color: #fde68a; }
        .ic-title { font-weight: 800; font-size: 0.92rem; color: var(--navy); margin-bottom: 4px; }
        .ic-body { font-size: 0.88rem; color: var(--ink); line-height: 1.5; }

        /* Modals (Cancel, Reschedule) */
        .custom-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(2px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .custom-modal-box {
            background: #fff;
            border-radius: 20px;
            max-width: 540px;
            width: 100%;
            padding: 28px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
        }

        /* Toast Notice */
        .toast-notice {
            position: fixed;
            right: 24px;
            z-index: 99999;
            max-width: 440px;
            width: calc(100% - 48px);
            background: var(--navy-d);
            color: #fff;
            padding: 14px 18px;
            border-radius: 14px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Responsive Breakpoints */
        @media (max-width: 900px) {
            .wrap { padding: 0 16px; }
            .card-b, .panel { padding: 22px 18px; }
            .wz-h { padding: 20px 18px; }
            .wz-n { padding: 16px 18px; }
            .hero { padding: 16px 0 28px; }
        }
        @media (max-width: 700px) {
            .stp span { display: none; }
            .stp.active span, .stp.on span { display: block; position: absolute; margin-top: 46px; white-space: nowrap; }
            .stepper { padding-bottom: 44px; position: relative; }
            .back-btn span { display: none; }
            .back-btn { padding: 8px 14px; }
        }
        @media (max-width: 520px) {
            .btn { width: 100%; }
            .wz-n { flex-wrap: wrap; }
            .wz-n .c { order: -1; flex: 0 0 100%; text-align: center; margin-bottom: 6px; }
            .wz-n .btn { flex: 1; min-width: 120px; }
            .cont .btn { width: 100%; }
            .brand { font-size: 19px; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            * { transition: none !important; }
        }

        /* Uploader & Upload All architecture (Matching Reference) */
        .up { display: grid; gap: 14px; }
        .up-drop {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 26px 20px;
            text-align: center;
            border: 2px dashed #7f93d8;
            border-radius: 16px;
            background: #f7f9ff;
            cursor: pointer;
            transition: background .15s, border-color .15s;
        }
        .up-drop:hover, .up-drop.over { background: #eaf0ff; border-color: var(--navy); }
        .up-drop:focus-within { outline: 3px solid var(--gold); outline-offset: 2px; }
        .up-drop .i { width: 42px; height: 42px; color: var(--navy); }
        .up-drop b { font-size: 17px; color: var(--navy); font-weight: 800; }
        .up-drop small { color: var(--muted); font-size: 13.5px; max-width: 440px; }
        .up-pick {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0 22px;
            border-radius: 12px;
            background: var(--navy);
            color: #fff;
            font-weight: 800;
            font-size: 14.5px;
            margin-top: 6px;
            transition: background 0.15s;
        }
        .up-pick:hover { background: var(--navy-d); }
        .up-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px 20px;
            flex-wrap: wrap;
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 14px;
            padding: 16px 20px;
        }
        .up-sum { flex: 1; min-width: 200px; }
        .up-count { margin: 0 0 6px; font-weight: 700; font-size: 14.5px; color: var(--ink); }
        .up-meter { height: 8px; border-radius: 4px; background: #dfe6f8; overflow: hidden; }
        .up-meter i { display: block; height: 100%; width: 0; background: var(--gold); transition: width .3s; }
        .up-btns { display: flex; gap: 10px; flex-wrap: wrap; }
        .up-btns .btn { min-height: 46px; padding: 0 20px; font-size: 15px; }
        .up-list { list-style: none; margin: 0; padding: 0; display: grid; gap: 10px; }
        .up-row {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 14px;
            align-items: center;
            background: #fff;
            border: 1.5px solid var(--line);
            border-left: 5px solid #c6d1ee;
            border-radius: 14px;
            padding: 14px 16px;
            transition: background .15s, border-color .15s;
        }
        .up-row.over { background: #eaf0ff; border-color: var(--navy); }
        .up-row[data-s="ready"] { border-left-color: var(--navy); }
        .up-row[data-s="uploading"] { border-left-color: var(--gold); }
        .up-row[data-s="uploaded"] { border-left-color: #22a559; }
        .up-row[data-s="error"] { border-left-color: var(--red); }
        .up-th {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--tint);
            color: var(--navy);
            display: grid;
            place-items: center;
            overflow: hidden;
            flex: none;
        }
        .up-th img { width: 100%; height: 100%; object-fit: cover; cursor: pointer; }
        .up-row[data-s="uploaded"] .up-th { background: #d9f3e3; color: var(--ok); }
        .up-main { min-width: 0; }
        .up-name { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; font-weight: 800; font-size: 15.5px; color: var(--ink); }
        .up-req { font-size: 11px; font-weight: 800; letter-spacing: .06em; color: var(--red); }
        .up-meta { display: block; margin-top: 2px; color: var(--muted); font-size: 13.5px; overflow-wrap: anywhere; }
        .up-prog { height: 6px; border-radius: 3px; background: #dfe6f8; margin-top: 8px; overflow: hidden; }
        .up-prog i { display: block; height: 100%; width: 0; background: var(--gold); transition: width .12s linear; }
        .up-err { margin: 4px 0 0; color: var(--red); font-weight: 600; font-size: 13.5px; }
        .up-act { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
        .up-st {
            padding: 4px 12px;
            border-radius: 99px;
            background: #eef0f7;
            color: var(--muted);
            font-weight: 700;
            font-size: 12.5px;
            white-space: nowrap;
        }
        .up-st.ready { background: #e3ecff; color: var(--navy); }
        .up-st.work { background: #fff0c2; color: #6b4b00; }
        .up-st.ok { background: #d9f3e3; color: var(--ok); }
        .up-st.bad { background: #fde0db; color: #a82a1b; }
        .sbtn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 10px;
            border: 1.5px solid var(--line);
            background: #fff;
            color: var(--navy);
            font-weight: 800;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }
        .sbtn:hover { border-color: var(--navy); color: var(--navy); }
        .sbtn.pri { background: var(--navy); border-color: var(--navy); color: #fff; }
        .sbtn.pri:hover { background: var(--navy-d); color: #fff; }
        .sbtn.ico { width: 42px; padding: 0; }
        .sbtn:focus-within { outline: 3px solid var(--gold); outline-offset: 2px; }
        .up-tip {
            margin: 0;
            padding: 12px 14px;
            border-radius: 12px;
            background: #f3f6fd;
            border: 1px solid var(--line);
            color: var(--muted);
            font-size: 13.5px;
            line-height: 1.5;
        }
        .up-tip b { color: var(--navy); }
        @media (max-width: 640px) {
            .up-row { grid-template-columns: auto 1fr; }
            .up-act { grid-column: 1 / -1; justify-content: space-between; }
            .up-btns, .up-btns .btn { width: 100%; }
        }


        /* ID Ready & Celebration Architecture (Matching Reference) */
        .toast {
            position: fixed;
            top: 18px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 120000;
            width: min(540px, calc(100vw - 24px));
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-radius: 14px;
            border-left: 6px solid #22a559;
            padding: 12px 14px;
            box-shadow: 0 14px 40px rgba(20, 34, 79, 0.3);
            animation: drop .35s cubic-bezier(.2, .8, .2, 1);
        }
        .toast .ok {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #d9f3e3;
            color: var(--ok);
            display: grid;
            place-items: center;
            flex: none;
        }
        .toast p {
            margin: 0;
            flex: 1;
            font-weight: 700;
            font-size: 14.5px;
            line-height: 1.4;
            color: var(--ink);
        }
        .toast .sbtn { min-height: 38px; }
        @keyframes drop {
            from { opacity: 0; transform: translate(-50%, -16px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }

        .cel {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 28px;
            align-items: center;
            background: linear-gradient(135deg, #e6f7ec, #fff7dc);
            border: 1px solid #bfe5cb;
            border-radius: 20px;
            padding: 28px;
            margin-bottom: 24px;
        }
        .cel-t { display: flex; gap: 20px; align-items: flex-start; }
        .cel-ic {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #22a559;
            color: #fff;
            display: grid;
            place-items: center;
            flex: none;
            box-shadow: 0 0 0 8px rgba(34, 165, 89, 0.18);
        }
        .cel-ic .i { width: 38px; height: 38px; stroke-width: 3; }
        .cel-chip {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 99px;
            background: #fff;
            border: 1px solid #bfe5cb;
            color: var(--ok);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
        .cel h3 {
            margin: 10px 0 8px;
            font-size: clamp(22px, 3.4vw, 30px);
            line-height: 1.2;
            font-weight: 800;
            color: var(--navy);
        }
        .cel p {
            margin: 0 0 16px;
            max-width: 520px;
            color: #34415f;
            line-height: 1.6;
            font-size: 16px;
        }
        .cel .btn { min-height: 48px; }

        /* Realistic ID Card */
        .idc {
            width: 310px;
            max-width: 100%;
            aspect-ratio: 1.6;
            border-radius: 16px;
            background: linear-gradient(135deg, #1a2470, #2a3b96);
            color: #fff;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 14px 30px rgba(20, 34, 79, 0.3);
            position: relative;
            overflow: hidden;
            flex: none;
        }
        .idc:after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 6px;
            background: var(--gold);
        }
        .idc-top {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 12.5px;
        }
        .idc-logo {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: #fff;
            display: grid;
            place-items: center;
        }
        .idc-valid {
            margin-left: auto;
            padding: 3px 9px;
            border-radius: 99px;
            background: #22a559;
            font-size: 10.5px;
            letter-spacing: .08em;
            font-weight: 800;
        }
        .idc-mid { display: flex; gap: 12px; align-items: center; }
        .idc-ph {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.16);
            display: grid;
            place-items: center;
            flex: none;
        }
        .idc-ph .i { width: 30px; height: 30px; }
        .idc-mid small {
            display: block;
            color: var(--gold);
            font-weight: 800;
            font-size: 10.5px;
            letter-spacing: .1em;
        }
        .idc-mid b {
            display: block;
            font-size: 15px;
            margin: 1px 0;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 190px;
        }
        .idc-mid span { font-size: 12px; color: #cfd8f3; }
        .idc-bot {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            font-size: 10.5px;
            color: #cfd8f3;
            padding-bottom: 4px;
            font-weight: 600;
        }

        /* Confetti Effect */
        .confetti {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .confetti i {
            position: absolute;
            top: -14px;
            width: 9px;
            height: 14px;
            border-radius: 2px;
            animation: fall 3s linear forwards;
        }
        @keyframes fall {
            to { transform: translateY(420px) rotate(540deg); opacity: 0; }
        }

        /* Status Columns */
        .st-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .st-cols > *:only-child { grid-column: 1 / -1; }
        .stbox {
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 20px 24px;
            background: #fafcff;
        }
        .stbox h3 {
            margin: 0 0 16px;
            font-size: 18px;
            font-weight: 800;
            color: var(--navy);
        }
        .tl { list-style: none; margin: 0; padding: 0; }
        .tl-i {
            display: flex;
            gap: 14px;
            position: relative;
            padding-bottom: 18px;
        }
        .tl-i:last-child { padding-bottom: 0; }
        .tl-i:before {
            content: "";
            position: absolute;
            left: 15px;
            top: 34px;
            bottom: 2px;
            width: 2px;
            background: #bfe5cb;
        }
        .tl-i:last-child:before { display: none; }
        .tl-d {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #22a559;
            color: #fff;
            display: grid;
            place-items: center;
            flex: none;
            position: relative;
            z-index: 1;
        }
        .tl-d .i { width: 16px; height: 16px; stroke-width: 3; }
        .tl-i.now .tl-d {
            background: var(--navy);
            box-shadow: 0 0 0 4px rgba(245, 184, 46, 0.55);
        }
        .tl-i b { display: block; font-size: 15.5px; color: var(--navy); font-weight: 800; }
        .tl-i span { display: block; margin-top: 2px; font-size: 14px; color: var(--muted); }

        /* Claim Instructions Box */
        .claim { background: #fff9e6; border-color: #f1d98a; }
        .cl { list-style: none; margin: 0; padding: 0; display: grid; gap: 14px; }
        .cl li { display: flex; gap: 14px; align-items: flex-start; }
        .cl .i {
            width: 40px;
            height: 40px;
            padding: 10px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #f1d98a;
            color: var(--navy);
            flex: none;
        }
        .cl small {
            display: block;
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: .08em;
            color: #7a5d00;
            text-transform: uppercase;
        }
        .cl b { display: block; margin-top: 2px; font-size: 15px; font-weight: 800; color: var(--ink); }

        @media (max-width: 860px) {
            .cel { grid-template-columns: 1fr; padding: 22px 18px; }
            .idc { justify-self: center; width: 100%; max-width: 340px; }
            .st-cols { grid-template-columns: 1fr; }
            .cel-t { flex-direction: column; gap: 14px; }
        }

    </style>
</head>

<body>
    <!-- SVG Icon Definitions (From Reference) -->
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
        <defs>
            <symbol id="i-adduser" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2M19 8v6M16 11h6"/></symbol>
            <symbol id="i-renew" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-15.5 6.2L3 16M3 12A9 9 0 0118.5 5.8L21 8M21 3v5h-5M3 21v-5h5"/></symbol>
            <symbol id="i-check" viewBox="0 0 24 24"><path d="M4 12l5 5L20 6"/></symbol>
            <symbol id="i-cal" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></symbol>
            <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
            <symbol id="i-pin" viewBox="0 0 24 24"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></symbol>
            <symbol id="i-call" viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2z"/></symbol>
            <symbol id="i-file" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8zM14 2v6h6"/></symbol>
            <symbol id="i-left" viewBox="0 0 24 24"><path d="M19 12H5M11 6l-6 6 6 6"/></symbol>
            <symbol id="i-right" viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></symbol>
            <symbol id="i-info" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v5h1"/></symbol>
            <symbol id="i-face" viewBox="0 0 24 24"><path d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM17 11a3 3 0 100-6M21 21v-2a4 4 0 00-3-3.9"/></symbol>
            <symbol id="i-video" viewBox="0 0 24 24"><rect x="3" y="6" width="13" height="12" rx="2"/><path d="M16 10l5-3v10l-5-3z"/></symbol>
            <symbol id="i-cloud" viewBox="0 0 24 24"><path d="M12 16V9M8.5 12L12 8.5 15.5 12M7 19a4.5 4.5 0 01-.5-9A6 6 0 0118 11a4 4 0 010 8z"/></symbol>
            <symbol id="i-x" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></symbol>
            <symbol id="i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
            <symbol id="i-id" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M6 16c.6-1.6 1.8-2.4 3-2.4s2.4.8 3 2.4M14 10h4M14 13h3"/></symbol>
            <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a6 6 0 016-6h4a6 6 0 016 6v1"/></symbol>
            <symbol id="i-bell" viewBox="0 0 24 24"><path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 01-3.4 0"/></symbol>
        </defs>
    </svg>

    <!-- Top Hero Header -->
    <header class="hero">
        <div class="wrap">
            <div class="topbar">
                <a class="brand" href="{{ route('user.dashboard') }}" aria-label="MSWDO home">
                    <img src="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}" alt="MSWDO Logo">
                    <span>MSWDO</span>
                </a>
                <div class="tools">
                    <div class="lang" role="group" aria-label="Language switch">
                        <button type="button" class="lang-btn active" id="langBtnEn" onclick="setLang('en')">EN</button>
                        <button type="button" class="lang-btn" id="langBtnTl" onclick="setLang('tl')">TL</button>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="back-btn">
                        <svg class="i i-sm" aria-hidden="true"><use href="#i-left"/></svg>
                        <span data-en="Back to Programs" data-tl="Bumalik sa Programs">Back to Programs</span>
                    </a>
                </div>
            </div>

            <span class="eb" data-en="SOLO PARENT SERVICES" data-tl="MGA SERBISYO PARA SA SOLO PARENT">SOLO PARENT SERVICES</span>
            <h1 id="hero-title" data-en="Solo Parent Application" data-tl="Solo Parent Application">Solo Parent Application</h1>
            <div class="hero-divider"></div>
            <p id="hero-sub"
                data-en="Book your interview, follow each step, and track your Solo Parent ID application — all in one guided flow."
                data-tl="Mag-book ng panayam, sundin ang bawat hakbang, at subaybayan ang iyong Solo Parent ID application — lahat sa isang gabay na daloy.">
                Book your interview, follow each step, and track your Solo Parent ID application — all in one guided flow.
            </p>
        </div>
    </header>

    @php
        $topNotice = session('appt_success') ?: session('appt_error');

        // Fallback: Ensure $soloParentApplication is loaded if user has a Solo Parent application
        if (!isset($soloParentApplication) || !$soloParentApplication) {
            $soloParentApplication = \App\Models\Application::with(['fileMonitoring.fileUploads'])
                ->where('user_id', Auth::id())
                ->where('program_type', 'Solo_Parent')
                ->latest('id')
                ->first();
        }
        $hasAppointment = isset($appointment) && $appointment && in_array($appointment->status ?? '', ['pending', 'confirmed', 'validated']);
        $apptStatus = $appointment->status ?? null;
        $isValidated = $apptStatus === 'validated';
        $isConfirmed = $apptStatus === 'confirmed';
        $isPending = $apptStatus === 'pending';

        $fm = (isset($soloParentApplication) && $soloParentApplication) ? $soloParentApplication->fileMonitoring : null;
        $uploads = $fm ? $fm->fileUploads : collect();
        $overallStatus = $fm ? $fm->overall_status : null;
        $uploadedCount = $uploads->count();
        $approvedCount = $uploads->where('status', 'approved')->count();
        $totalReqs = 6;

        $wizardSteps = !empty($isSoloParentBeneficiary)
            ? ['overview', 'guide', 'office']
            : ['overview', 'book', 'guide', 'office', 'requirements', 'finish'];

        $isIdReady = $soloParentApplication && (
            ($soloParentApplication->id_status ?? null) === 'ready_for_pickup'
            || !empty($soloParentApplication->id_ready_at)
        );
        $isClaimed = $soloParentApplication && (
            ($soloParentApplication->id_status ?? null) === 'released'
        );
        $hasActiveApp = $soloParentApplication && in_array($soloParentApplication->status, ['pending', 'in_review']) && !$isIdReady && !$isClaimed;
        $isActivelyApplying = $isValidated || $hasActiveApp;
        $isBeneficiary = (!empty($isSoloParentBeneficiary) || $isClaimed) && !$isActivelyApplying;

        $tlBooked = $hasAppointment || $isBeneficiary || $isIdReady;
        $tlConfirmed = in_array($apptStatus, ['confirmed', 'validated']) || $isValidated || $isBeneficiary || $isIdReady;
        $tlValidated = $isValidated || ($soloParentApplication && !empty($soloParentApplication->category_code)) || $isBeneficiary || $isIdReady;
        $tlUploaded = $uploadedCount > 0 || $isBeneficiary || $isIdReady;
        $tlApproved = $overallStatus === 'approved' || $isBeneficiary || $isIdReady;
        $tlIdReady = $isIdReady;

        $readyOnDate = ($soloParentApplication && $soloParentApplication->id_ready_at)
            ? \Carbon\Carbon::parse($soloParentApplication->id_ready_at)->format('F d, Y')
            : \Carbon\Carbon::now('Asia/Manila')->format('F d, Y');

        $validUntilDate = ($soloParentApplication && $soloParentApplication->id_ready_at)
            ? \Carbon\Carbon::parse($soloParentApplication->id_ready_at)->addYear()->format('M d, Y')
            : \Carbon\Carbon::now('Asia/Manila')->addYear()->format('M d, Y');

        $idNumberDisplay = ($soloParentApplication && !empty($soloParentApplication->id_number))
            ? $soloParentApplication->id_number
            : 'SP-' . str_pad($soloParentApplication->id ?? (Auth::id() ?? 1), 5, '0', STR_PAD_LEFT);

        $currentUser = Auth::user();
        $municipalityDisplay = $currentUser->municipality ?: 'Majayjay';
        $barangayDisplay = $currentUser->barangay ?: 'N/A';
        $fullNameDisplay = $currentUser->full_name ?: ($currentUser->first_name . ' ' . $currentUser->last_name);
        $firstNameDisplay = $currentUser->first_name ?: 'Applicant';

        if ($tlIdReady) $activeTimeline = 6;
        elseif ($tlApproved) $activeTimeline = 5;
        elseif ($tlUploaded) $activeTimeline = 4;
        elseif ($tlValidated) $activeTimeline = 3;
        elseif ($tlConfirmed) $activeTimeline = 2;
        elseif ($tlBooked) $activeTimeline = 1;
        else $activeTimeline = 0;

        $overallLabel = match (true) {
            $tlIdReady => 'ID Ready for Pickup',
            $overallStatus === 'approved' => 'Requirements Approved',
            $isValidated || $hasActiveApp => 'Eligible — Upload Documents',
            $isConfirmed => 'Appointment Confirmed',
            $isPending => 'Awaiting Confirmation',
            $isBeneficiary => 'Solo Parent Beneficiary',
            default => 'Not Started',
        };
        $overallPillClass = match (true) {
            $tlIdReady => 'validated',
            $overallStatus === 'approved' => 'approved',
            $isValidated || $hasActiveApp => 'validated',
            $isConfirmed => 'confirmed',
            $isPending => 'pending',
            $isBeneficiary => 'approved',
            default => 'pending',
        };

        $hasRejectedRenewalWithAppt = isset($renewalRequest) && $renewalRequest
            && $renewalRequest->status === 'rejected'
            && ($renewalRequest->appointment ?? $appointment ?? null)
            && !in_array(($renewalRequest->appointment ?? $appointment)->status, ['rejected', 'cancelled']);

        $hasActiveRenewal = isset($renewalRequest) && $renewalRequest
            && (in_array($renewalRequest->status, ['pending', 'approved']) || $hasRejectedRenewalWithAppt)
            && (!$renewalRequest->appointment_id || $renewalRequest->appointment || $appointment);
    @endphp

    @if($topNotice)
        <div id="flashNotification" class="toast-notice"
            style="top:{{ !empty($isSoloParentBeneficiary) ? '150px' : '90px' }};">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                <span>{{ $topNotice }}</span>
                <button onclick="closeFlashNotification()"
                    style="background:transparent;border:none;color:rgba(255,255,255,.7);font-size:1.2rem;cursor:pointer;">&times;</button>
            </div>
            <div id="flashTimer"
                style="position:absolute;bottom:0;left:0;height:3px;background:var(--gold);width:100%;"></div>
        </div>
    @endif

    @if($tlIdReady)
        <div class="toast" id="toast" role="status">
            <span class="ok"><svg class="i" aria-hidden="true"><use href="#i-check"/></svg></span>
            <p id="toastT">Good news! Your Solo Parent ID is ready for pickup.</p>
            <a class="sbtn pri" id="toastGo" href="#claim">View</a>
            <button class="sbtn ico" id="toastX" type="button" aria-label="Dismiss" onclick="document.getElementById('toast').remove()">
                <svg class="i i-sm" aria-hidden="true"><use href="#i-x"/></svg>
            </button>
        </div>
    @endif

    @if(!empty($isSoloParentBeneficiary) && !$isActivelyApplying)
        <div class="toast-notice" style="top:90px;">
            Solo Parent beneficiary na ang account na ito. Re-application is disabled.
        </div>
    @endif

    <main class="wrap py-4 flex-grow-1">

        {{-- Active Picked Indicator --}}
        <div class="picked" id="pickedBanner" style="display:none;" role="status">
            <svg class="i i-sm" aria-hidden="true"><use href="#i-info"/></svg>
            <span>You are viewing: <b id="pickedLabel">New Solo Parent Application</b></span>
            <button class="link ms-auto" id="changeChoiceBtn" type="button" onclick="changeChoice()">Change Option</button>
        </div>

        {{-- OPTION SELECTOR: What would you like to do? (Shown when neither active appointment nor active renewal) --}}
        <section class="card" id="stepChoice" style="display:none;" aria-labelledby="choiceHeading">
            <div class="card-b">
                <h2 class="sh" id="choiceHeading" data-en="What would you like to do?" data-tl="Ano ang nais mong gawin?">What would you like to do?</h2>
                <p class="ss" data-en="Choose whether you are applying for a new Solo Parent ID or renewing an existing one." data-tl="Pumili kung ikaw ay nag-aapply para sa bagong Solo Parent ID o nagre-renew ng umiiral na ID.">Choose whether you are applying for a new Solo Parent ID or renewing an existing one.</p>
                <div class="opts" role="radiogroup" aria-labelledby="choiceHeading">
                    <label class="opt" id="optNew" onclick="selectAppKind('new')">
                        <input class="sr" type="radio" name="appKind" value="new" checked>
                        <div class="opt-b">
                            <span class="tick"><svg class="i"><use href="#i-check"/></svg></span>
                            <span class="opt-ic"><svg class="i i-lg"><use href="#i-adduser"/></svg></span>
                            <h3 data-en="New Application" data-tl="Bagong Application">New Application</h3>
                            <p data-en="I am applying for a Solo Parent ID for the first time. Book an interview, follow the steps, and upload your documents after validation."
                               data-tl="Ako ay nag-aapply para sa Solo Parent ID sa unang pagkakataon. Mag-book ng panayam, sundin ang mga hakbang, at mag-upload ng mga dokumento pagkatapos ma-validate.">
                                I am applying for a Solo Parent ID for the first time. Book an interview, follow the steps, and upload your documents after validation.
                            </p>
                            <span class="chip" data-en="First-time applicant" data-tl="Unang beses mag-apply">First-time applicant</span>
                        </div>
                    </label>

                    <label class="opt" id="optRenewal" onclick="selectAppKind('renewal')">
                        <input class="sr" type="radio" name="appKind" value="renewal">
                        <div class="opt-b">
                            <span class="tick"><svg class="i"><use href="#i-check"/></svg></span>
                            <span class="opt-ic"><svg class="i i-lg"><use href="#i-renew"/></svg></span>
                            <h3 data-en="Renewal" data-tl="Pag-renew">Renewal</h3>
                            <p data-en="I already have a Solo Parent ID, even an expired one. Upload it and choose an appointment slot for validation."
                               data-tl="Mayroon na akong Solo Parent ID, kahit expired na. I-upload ito at pumili ng appointment slot para sa validation.">
                                I already have a Solo Parent ID, even an expired one. Upload it and choose an appointment slot for validation.
                            </p>
                            <span class="chip" data-en="Existing or expired ID" data-tl="May dati o expired na ID">Existing or expired ID</span>
                        </div>
                    </label>
                </div>

                <div class="cont">
                    <button class="btn btn-navy" id="choiceContBtn" type="button" onclick="handleChoiceContinue()">
                        <span data-en="Continue" data-tl="Magpatuloy">Continue</span>
                        <svg class="i i-sm" aria-hidden="true"><use href="#i-right"/></svg>
                    </button>
                    <p class="hint" data-en="Not sure? If you already have a Solo Parent ID, choose Renewal." data-tl="Hindi sigurado? Kung mayroon ka nang Solo Parent ID, piliin ang Renewal.">
                        Not sure? If you already have a Solo Parent ID, choose Renewal.
                    </p>
                </div>
            </div>
        </section>

        {{-- RENEWAL FLOW CONTAINER --}}
        <div id="flowRenew" style="display:none;">
            @include('user.partials.renewal-card', [
        'renewalRequest' => $renewalRequest ?? null,
        'programType'    => 'Solo_Parent',
        'minDate'        => $minDate,
        'maxDate'        => $maxDate,
        'hasClockModal'  => true,
    ])
        </div>

        {{-- MONITOR / TRACKER VIEW --}}
        <div id="sp-monitor-view" style="display:none;">
            <div class="card">
                <div class="card-h">
                    <div class="l">
                        <span class="ico"><svg class="i" aria-hidden="true"><use href="#i-id"/></svg></span>
                        <div>
                            <h2 id="stT" data-en="Your Solo Parent Application" data-tl="Iyong Solo Parent Application">Your Solo Parent Application</h2>
                            <p data-en="Track your appointment, upload documents, and monitor your application progress."
                               data-tl="Subaybayan ang appointment, mag-upload ng dokumento, at i-monitor ang progress ng aplikasyon.">
                                Track your appointment, upload documents, and monitor your application progress.
                            </p>
                        </div>
                    </div>
                    <span class="tag ok" id="stTag">{{ $tlIdReady ? 'ID Ready for Pickup' : ($isBeneficiary ? 'Solo Parent Beneficiary' : $overallLabel) }}</span>
                </div>

                <div class="card-b">
                    @if($tlIdReady || $isBeneficiary)
                        {{-- CELEBRATORY BANNER & REALISTIC ID CARD --}}
                        <div class="cel" id="cel">
                            <div class="cel-t">
                                <span class="cel-ic">
                                    <svg class="i" aria-hidden="true"><use href="#i-check"/></svg>
                                </span>
                                <div>
                                    <span class="cel-chip" id="celChip">{{ $tlIdReady ? 'GOOD NEWS' : 'ALL SET' }}</span>
                                    <h3 id="celH">
                                        @if($tlIdReady)
                                            Congratulations, {{ $firstNameDisplay }}! You are now a verified Solo Parent.
                                        @else
                                            You are a registered Solo Parent beneficiary
                                        @endif
                                    </h3>
                                    <p id="celP">
                                        @if($tlIdReady)
                                            Your application is approved and your Solo Parent ID is ready for pickup at the MSWDO office.
                                        @else
                                            Naka-register ka na bilang Solo Parent beneficiary. Re-application is disabled.
                                        @endif
                                    </p>
                                    @if($isBeneficiary && empty($hasActiveRenewal))
                                        <div style="margin-top:14px;">
                                            <button type="button" class="btn btn-navy" onclick="selectAppKind('renewal'); handleChoiceContinue();" style="display:inline-flex;align-items:center;gap:8px;font-weight:700;">
                                                <svg class="i" style="width:16px;height:16px;"><use href="#i-renew"/></svg>
                                                <span>Renew Solo Parent ID</span>
                                            </button>
                                        </div>
                                    @endif
                                    @if($tlIdReady)
                                        <a class="btn btn-navy" id="celGo" href="#claim">See how to claim your ID</a>
                                    @endif
                                </div>
                            </div>

                            {{-- REALISTIC SOLO PARENT ID CARD --}}
                            <div class="idc" aria-hidden="true">
                                <div class="idc-top">
                                    <span class="idc-logo">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="#d93a3a"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/></svg>
                                    </span>
                                    MSWDO &bull; {{ $municipalityDisplay }}
                                    <span class="idc-valid">VALID</span>
                                </div>
                                <div class="idc-mid">
                                    <span class="idc-ph">
                                        <svg class="i" aria-hidden="true"><use href="#i-user"/></svg>
                                    </span>
                                    <div>
                                        <small>SOLO PARENT ID</small>
                                        <b>{{ $fullNameDisplay }}</b>
                                        <span>Barangay {{ $barangayDisplay }}</span>
                                    </div>
                                </div>
                                <div class="idc-bot">
                                    <span>ID No. {{ $idNumberDisplay }}</span>
                                    <span>Valid until {{ $validUntilDate }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- TWO-COLUMN STATUS & CLAIM INSTRUCTIONS --}}
                        <div class="st-cols">
                            {{-- COMPLETED APPLICATION TIMELINE --}}
                            <div class="stbox">
                                <h3>Application progress</h3>
                                <ol class="tl" id="tl">
                                    @php
                                        $completedStages = [
                                            ['title' => 'Schedule an Appointment', 'desc' => 'Applied for interview appointment.'],
                                            ['title' => 'Attend the Interview', 'desc' => 'Completed interview assessment with MSWDO.'],
                                            ['title' => 'Eligibility Notification', 'desc' => 'Validated and approved as eligible.'],
                                            ['title' => 'Requirements Submission', 'desc' => 'Requirements checklist assigned.'],
                                            ['title' => 'Submit Documents', 'desc' => 'All required documents approved by admin.'],
                                            ['title' => 'ID Processing & Release', 'desc' => $tlIdReady ? 'Solo Parent ID is ready for pickup at MSWDO.' : 'Solo Parent ID issued / claimed.'],
                                        ];
                                    @endphp
                                    @foreach($completedStages as $idx => $stg)
                                        <li class="tl-i {{ $tlIdReady && $idx === 5 ? 'now' : '' }}">
                                            <span class="tl-d"><svg class="i" aria-hidden="true"><use href="#i-check"/></svg></span>
                                            <div>
                                                <b>{{ $stg['title'] }}</b>
                                                <span>{{ $stg['desc'] }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            {{-- CLAIM INSTRUCTIONS BOX (When ID is ready) --}}
                            @if($tlIdReady)
                                <div class="stbox claim" id="claim">
                                    <h3>Claim your ID</h3>
                                    <ul class="cl">
                                        <li>
                                            <svg class="i" aria-hidden="true"><use href="#i-cal"/></svg>
                                            <div>
                                                <small>MARKED READY ON</small>
                                                <b id="readyOn">{{ $readyOnDate }}</b>
                                            </div>
                                        </li>
                                        <li>
                                            <svg class="i" aria-hidden="true"><use href="#i-pin"/></svg>
                                            <div>
                                                <small>WHERE</small>
                                                <b>Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor</b>
                                            </div>
                                        </li>
                                        <li>
                                            <svg class="i" aria-hidden="true"><use href="#i-clock"/></svg>
                                            <div>
                                                <small>WHEN</small>
                                                <b>Monday — Friday, 8:00 AM — 12:00 PM & 1:00 PM — 5:00 PM</b>
                                            </div>
                                        </li>
                                        <li>
                                            <svg class="i" aria-hidden="true"><use href="#i-file"/></svg>
                                            <div>
                                                <small>WHAT TO BRING</small>
                                                <b>Valid Government ID & 1x1 Photo (if requested)</b>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                    @else
                        {{-- STANDARD TRACKER FOR ACTIVE / PRE-ID-READY APPLICANTS --}}
                        <div class="monitor-stats-bar">
                            <div class="monitor-stat">
                                <span class="sv">{{ $hasAppointment ? '✓' : '—' }}</span>
                                <span class="sl" data-en="Booked" data-tl="Naka-book">Booked</span>
                            </div>
                            <div class="monitor-stat">
                                <span class="sv">{{ $isValidated || $isConfirmed ? '✓' : '—' }}</span>
                                <span class="sl" data-en="Interview" data-tl="Panayam">Interview</span>
                            </div>
                            <div class="monitor-stat">
                                <span class="sv">{{ $uploadedCount }}</span>
                                <span class="sl" data-en="Uploaded" data-tl="Na-upload">Uploaded</span>
                            </div>
                            <div class="monitor-stat">
                                <span class="sv">{{ $approvedCount }}</span>
                                <span class="sl" data-en="Approved" data-tl="Naaprubahan">Approved</span>
                            </div>
                        </div>

                        <div class="monitor-body-grid">
                            <aside>
                                <div class="info-card">
                                    <div class="ic-title" data-en="Application Progress" data-tl="Progreso ng Application">Application Progress</div>
                                    <div class="timeline mt-3">
                                        @php
                                            $timelineItems = [
                                                ['done' => $tlBooked, 'active' => $activeTimeline === 1, 'en' => 'Appointment Booked', 'tl' => 'Naka-book ang Appointment', 'descEn' => 'Your interview slot is scheduled.', 'descTl' => 'Naka-schedule na ang iyong panayam.'],
                                                ['done' => $tlConfirmed, 'active' => $activeTimeline === 2, 'en' => 'Appointment Confirmed', 'tl' => 'Na-confirm ang Appointment', 'descEn' => 'MSWDO confirmed your appointment.', 'descTl' => 'Na-confirm ng MSWDO ang iyong appointment.'],
                                                ['done' => $tlValidated, 'active' => $activeTimeline === 3, 'en' => 'Eligibility Validated', 'tl' => 'Na-validate ang Eligibility', 'descEn' => 'You passed the eligibility assessment.', 'descTl' => 'Nakapasa ka sa eligibility assessment.'],
                                                ['done' => $tlUploaded, 'active' => $activeTimeline === 4, 'en' => 'Documents Submitted', 'tl' => 'Na-submit ang Dokumento', 'descEn' => 'Upload required documents online.', 'descTl' => 'I-upload ang mga kinakailangang dokumento.'],
                                                ['done' => $tlApproved, 'active' => $activeTimeline === 5, 'en' => 'Requirements Approved', 'tl' => 'Naaprubahan ang Requirements', 'descEn' => 'Admin approved your documents.', 'descTl' => 'Naaprubahan ng admin ang mga dokumento.'],
                                                ['done' => $tlIdReady, 'active' => $activeTimeline === 6, 'en' => 'ID Ready for Pickup', 'tl' => 'Handa nang Kunin ang ID', 'descEn' => 'Visit MSWDO to claim your ID.', 'descTl' => 'Bumisita sa MSWDO para kunin ang ID.'],
                                            ];
                                        @endphp
                                        @foreach($timelineItems as $i => $tl)
                                            <div class="tl-item {{ $tl['done'] ? 'done' : '' }} {{ $tl['active'] ? 'active' : '' }}">
                                                <div class="tl-dot">{{ $tl['done'] ? '✓' : ($i + 1) }}</div>
                                                <div>
                                                    <div class="tl-title" data-en="{{ $tl['en'] }}" data-tl="{{ $tl['tl'] }}">{{ $tl['en'] }}</div>
                                                    <div class="tl-desc" data-en="{{ $tl['descEn'] }}" data-tl="{{ $tl['descTl'] }}">{{ $tl['descEn'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button type="button" class="btn btn-out w-100 mt-2" onclick="showWizardView()">
                                    <svg class="i i-sm" aria-hidden="true"><use href="#i-file"/></svg>
                                    <span data-en="View Application Guide" data-tl="Tingnan ang Gabay">View Application Guide</span>
                                </button>
                            </aside>

                            <div>
                                @if(!$isValidated && !$hasActiveApp)
                                    <div class="panel-heading" data-en="Your Appointment" data-tl="Iyong Appointment">Your Appointment</div>
                                    <div class="panel-sub" data-en="Manage your interview booking below." data-tl="Pamahalaan ang iyong booking sa panayam sa ibaba.">Manage your interview booking below.</div>
                                    @include('user.partials.solo-parent-booking', ['bookingPrefix' => 'mon'])
                                @endif

                                @if($isValidated || $hasActiveApp)
                                    <div class="panel-heading" data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</div>
                                    <div class="panel-sub" data-en="Upload your documents for admin review." data-tl="I-upload ang iyong mga dokumento para sa review ng admin.">Upload your documents for admin review.</div>
                                    @include('user.partials.solo-parent-requirements', ['uploadCols' => 'col-md-6 col-lg-6', 'reqPrefix' => 'mon'])
                                @elseif($isConfirmed)
                                    <div class="mt-4">@include('user.partials.solo-parent-requirements')</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center py-2">
                <a href="{{ route('user.dashboard') }}" class="btn btn-navy">
                    <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                </a>
            </div>
        </div>

        {{-- NEW APPLICATION GUIDED WIZARD FLOW --}}
        <div id="sp-wizard-view" style="display:none;">
            <div class="card">
                <div class="wz-h">
                    <h2 id="wizard-step-title">Step 1 — Overview</h2>
                    <p id="wizard-step-desc" data-en="Learn how the Solo Parent ID application works." data-tl="Alamin kung paano gumagana ang Solo Parent ID application.">
                        Learn how the Solo Parent ID application works.
                    </p>
                    <div class="bar" role="progressbar" aria-label="Application progress" aria-valuemin="0" aria-valuemax="6" id="wizard-progress-bar">
                        <i id="wizard-progress-fill"></i>
                    </div>
                </div>

                <ol class="stepper" id="wizard-steps-nav">
                    @foreach($wizardSteps as $i => $step)
                        @php
                            $labels = [
                                'overview' => ['en' => 'Overview', 'tl' => 'Pangkalahatan'],
                                'book' => ['en' => 'Book Appointment', 'tl' => 'Mag-book'],
                                'guide' => ['en' => 'How to Apply', 'tl' => 'Paano Mag-apply'],
                                'office' => ['en' => 'Office Info', 'tl' => 'Opisina'],
                                'requirements' => ['en' => 'Documents', 'tl' => 'Dokumento'],
                                'finish' => ['en' => 'Finish', 'tl' => 'Tapos'],
                            ];
                        @endphp
                        <li class="stp {{ $i === 0 ? 'active' : '' }}" data-step="{{ $i }}">
                            <button type="button" onclick="goToStep({{ $i }})">{{ $i + 1 }}</button>
                            <span data-en="{{ $labels[$step]['en'] }}" data-tl="{{ $labels[$step]['tl'] }}">{{ $labels[$step]['en'] }}</span>
                        </li>
                    @endforeach
                </ol>

                <div class="wizard-body">
                    {{-- PANEL 1: OVERVIEW --}}
                    <div class="wizard-panel panel active" data-panel="overview">
                        <h3 data-en="Welcome to the Solo Parent Application" data-tl="Maligayang Pagdating sa Solo Parent Application">Welcome to the Solo Parent Application</h3>
                        <p class="sub" data-en="This wizard guides you from booking an interview to receiving your Solo Parent ID."
                           data-tl="Gagabayan ka ng wizard na ito mula sa pag-book ng panayam hanggang sa pagtanggap ng iyong Solo Parent ID.">
                            This wizard guides you from booking an interview to receiving your Solo Parent ID.
                        </p>
                        <div class="tiles">
                            <div class="tile">
                                <b>1</b>
                                <span data-en="Book an interview appointment" data-tl="Mag-book ng appointment sa panayam">Book an interview appointment</span>
                            </div>
                            <div class="tile">
                                <b>7</b>
                                <span data-en="Steps to complete your application" data-tl="Mga hakbang sa aplikasyon">Steps to complete your application</span>
                            </div>
                            <div class="tile">
                                <b>6</b>
                                <span data-en="Documents to upload after validation" data-tl="Mga dokumentong i-upload pagkatapos ma-validate">Documents to upload after validation</span>
                            </div>
                        </div>
                        <div class="notes">
                            <div class="note warn">
                                <b data-en="Important: Book First" data-tl="Mahalaga: Mag-book Muna">Important: Book First</b>
                                <span data-en="You must schedule an appointment before proceeding. Choose face-to-face or online interview."
                                      data-tl="Kailangan mong mag-schedule ng appointment bago magpatuloy. Pumili ng harapan o online na panayam.">
                                    You must schedule an appointment before proceeding. Choose face-to-face or online interview.
                                </span>
                            </div>
                            <div class="note info">
                                <b data-en="After the Interview" data-tl="Pagkatapos ng Panayam">After the Interview</b>
                                <span data-en="If eligible, you will receive a requirements list via email and can upload documents here."
                                      data-tl="Kung karapat-dapat, makakatanggap ka ng listahan ng mga requirements sa email at maaari kang mag-upload ng mga dokumento dito.">
                                    If eligible, you will receive a requirements list via email and can upload documents here.
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL 2: BOOK APPOINTMENT (PRESERVED CLOCK-STYLE TIME PICKER) --}}
                    <div class="wizard-panel panel" data-panel="book" style="display:none;">
                        <h3 data-en="Book Your Interview" data-tl="Mag-book ng Iyong Panayam">Book Your Interview</h3>
                        <p class="sub" data-en="Choose how you want to be interviewed, then pick a date and time."
                           data-tl="Pumili kung paano mo nais makapanayam, pagkatapos ay pumili ng petsa at oras.">
                            Choose how you want to be interviewed, then pick a date and time.
                        </p>
                        @include('user.partials.solo-parent-booking', ['bookingPrefix' => 'wiz'])
                    </div>

                    {{-- PANEL 3: HOW TO APPLY --}}
                    <div class="wizard-panel panel" data-panel="guide" style="display:none;">
                        <h3 data-en="How to Apply" data-tl="Paano Mag-apply">How to Apply</h3>
                        <p class="sub" data-en="Here is the full 7-step process from your interview to your Solo Parent ID."
                           data-tl="Narito ang buong 7-hakbang na proseso mula sa panayam hanggang sa iyong Solo Parent ID.">
                            Here is the full 7-step process from your interview to your Solo Parent ID.
                        </p>
                        @php
                            $guideSteps = [
                                ['en' => 'Schedule an Appointment', 'tl' => 'Mag-schedule ng Appointment', 'descEn' => 'Apply for a face-to-face or online interview with the MSWDO.', 'descTl' => 'Mag-apply ng harapan o online na panayam sa MSWDO.'],
                                ['en' => 'Wait for Confirmation', 'tl' => 'Hintayin ang Kumpirmasyon', 'descEn' => 'The admin will review and confirm via website and email.', 'descTl' => 'Susuriin at kukumpirmahin ng admin sa website at email.'],
                                ['en' => 'Attend the Interview', 'tl' => 'Dumalo sa Panayam', 'descEn' => 'Go to MSWDO office or attend the scheduled online interview.', 'descTl' => 'Pumunta sa opisina ng MSWDO o dumalo sa online na panayam.'],
                                ['en' => 'Eligibility Notification', 'tl' => 'Abiso sa Pagiging Karapat-dapat', 'descEn' => 'You will be notified via email if you are eligible.', 'descTl' => 'Maabisuhan ka sa email kung karapat-dapat ka.'],
                                ['en' => 'Requirements Submission', 'tl' => 'Pagsusumite ng Requirements', 'descEn' => 'If approved, prepare all required documents.', 'descTl' => 'Kung naaprubahan, ihanda ang lahat ng kinakailangang dokumento.'],
                                ['en' => 'Submit Documents', 'tl' => 'Isumite ang mga Dokumento', 'descEn' => 'Submit hard copies to MSWDO or upload digital copies online.', 'descTl' => 'Isumite ang mga kopya sa MSWDO o mag-upload online.'],
                                ['en' => 'ID Processing and Release', 'tl' => 'Pagpoproseso at Paglalabas ng ID', 'descEn' => 'MSWDO will process your Solo Parent ID. You will be notified when ready.', 'descTl' => 'Ipoproseso ng MSWDO ang iyong Solo Parent ID. Maabisuhan ka kapag handa na.'],
                            ];
                        @endphp
                        <ol class="how">
                            @foreach($guideSteps as $i => $gs)
                                <li>
                                    <div class="dot">{{ $i + 1 }}</div>
                                    <div>
                                        <h4 data-en="{{ $gs['en'] }}" data-tl="{{ $gs['tl'] }}">{{ $gs['en'] }}</h4>
                                        <p data-en="{{ $gs['descEn'] }}" data-tl="{{ $gs['descTl'] }}">{{ $gs['descEn'] }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    {{-- PANEL 4: OFFICE INFO --}}
                    <div class="wizard-panel panel" data-panel="office" style="display:none;">
                        <h3 data-en="Office Information" data-tl="Impormasyon ng Opisina">Office Information</h3>
                        <p class="sub" data-en="Where to go and when to come for your in-person visit."
                           data-tl="Saan pupunta at kailan darating para sa iyong personal na pagbisita.">
                            Where to go and when to come for your in-person visit.
                        </p>
                        <div class="off">
                            <div>
                                <svg class="i"><use href="#i-pin"/></svg>
                                <div>
                                    <b data-en="Address" data-tl="Tirahan / Lokasyon">Address</b>
                                    <span data-en="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor"
                                          data-tl="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor">
                                        Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor
                                    </span>
                                </div>
                            </div>
                            <div>
                                <svg class="i"><use href="#i-clock"/></svg>
                                <div>
                                    <b data-en="Office Hours" data-tl="Oras ng Opisina">Office Hours</b>
                                    <span>Monday — Friday<br>8:00 AM — 12:00 PM & 1:00 PM — 5:00 PM</span>
                                </div>
                            </div>
                            <div>
                                <svg class="i"><use href="#i-call"/></svg>
                                <div>
                                    <b data-en="Interview Options" data-tl="Mga Opsyon sa Panayam">Interview Options</b>
                                    <span data-en="Face-to-face at office or online via phone call"
                                          data-tl="Harapan sa opisina o online sa pamamagitan ng tawag sa telepono">
                                        Face-to-face at office or online via phone call
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(empty($isSoloParentBeneficiary))
                        {{-- PANEL 5: DOCUMENTS --}}
                        <div class="wizard-panel panel" data-panel="requirements" style="display:none;">
                            <h3 data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</h3>
                            <p class="sub" data-en="Available after your interview and eligibility validation."
                               data-tl="Available pagkatapos ng panayam at eligibility validation.">
                                Available after your interview and eligibility validation.
                            </p>
                            @include('user.partials.solo-parent-requirements', ['uploadCols' => 'col-md-6 col-lg-6', 'reqPrefix' => 'wiz'])
                        </div>

                        {{-- PANEL 6: FINISH --}}
                        <div class="wizard-panel panel" data-panel="finish" style="display:none;">
                            <h3 data-en="Review and Finish" data-tl="Suriin at Tapusin">Review and Finish</h3>
                            <p class="sub" data-en="Check your details, then complete the guide and start tracking."
                               data-tl="Suriin ang iyong mga detalye, pagkatapos ay tapusin ang gabay at magsimulang mag-track.">
                                Check your details, then complete the guide and start tracking.
                            </p>
                            <div class="info-card">
                                <div class="ic-title" data-en="What happens next?" data-tl="Ano ang susunod?">What happens next?</div>
                                <div class="ic-body">
                                    <ul style="margin:0;padding-left:18px;line-height:2.2;">
                                        <li data-en="Wait for your appointment confirmation via email and notifications"
                                            data-tl="Hintayin ang kumpirmasyon ng appointment sa email at notifications">Wait for your appointment confirmation via email and notifications</li>
                                        <li data-en="Attend your scheduled interview with MSWDO officers"
                                            data-tl="Dumalo sa nakatakdang panayam kasama ang MSWDO officers">Attend your scheduled interview with MSWDO officers</li>
                                        <li data-en="Upload required documents online once validated as eligible"
                                            data-tl="Mag-upload ng mga kinakailangang dokumento online kapag na-validate na">Upload required documents online once validated as eligible</li>
                                        <li data-en="Track your application anytime in your personal portal"
                                            data-tl="Subaybayan ang iyong aplikasyon anumang oras sa portal">Track your application anytime in your personal portal</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Wizard Navigation Footer --}}
                <div class="wz-n" id="wzNav">
                    <button type="button" class="btn btn-out" id="wiz-btn-prev" disabled onclick="wizardPrev()">
                        <svg class="i i-sm"><use href="#i-left"/></svg>
                        <span data-en="Previous" data-tl="Nakaraan">Previous</span>
                    </button>
                    <span class="c" id="wiz-step-counter">Step 1 of {{ count($wizardSteps) }}</span>
                    <button type="button" class="btn btn-navy" id="wiz-btn-next" onclick="wizardNext()">
                        <span data-en="Next" data-tl="Susunod">Next</span>
                        <svg class="i i-sm"><use href="#i-right"/></svg>
                    </button>
                    <button type="button" class="btn btn-gold" id="wiz-btn-finish" style="display:none;" onclick="finishWizard()">
                        <span data-en="Finish & Track Application" data-tl="Tapusin at Subaybayan">Finish & Track Application</span>
                        <svg class="i i-sm"><use href="#i-check"/></svg>
                    </button>
                </div>
            </div>

            <div class="text-center py-2">
                <a href="{{ route('user.dashboard') }}" class="btn btn-navy">
                    <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                </a>
            </div>
        </div>

    </main>

    {{-- Loading Overlay --}}
    <div id="uiLoadingBackdrop" class="custom-modal-backdrop" aria-hidden="true">
        <div class="custom-modal-box text-center" style="max-width:320px;">
            <div class="spinner-border text-primary mb-3" role="status" style="width:3rem;height:3rem;"></div>
            <h5 style="font-weight:800;color:var(--navy);">Uploading Document</h5>
            <p style="font-size:.85rem;color:var(--muted);margin:0;">Please wait while we process your file.</p>
        </div>
    </div>

    {{-- PRESERVED CLOCK TIME PICKER MODAL --}}
    <div id="clockModal" class="clock-modal-overlay" onclick="if(event.target===this)closeClockModal()">
        <div class="clock-modal">
            <div class="clock-header">
                <div class="clock-header-label">Select appointment time</div>
                <div class="clock-digital">
                    <span class="clock-digit-hour active" id="clockDigitHour" onclick="clockSetMode('hour')">08</span>
                    <span class="clock-digit-sep">:</span>
                    <span class="clock-digit-min" id="clockDigitMin" onclick="clockSetMode('min')">00</span>
                    <span class="clock-digit-ampm" id="clockDigitAmPm">AM</span>
                </div>
            </div>
            <div class="clock-face-wrap">
                <div class="clock-face" id="clockFace">
                    <div class="clock-center-dot"></div>
                    <div class="clock-hand" id="clockHand" style="height:80px;"></div>
                </div>
            </div>
            <div class="clock-slot-info none" id="clockSlotInfo"></div>
            <div class="clock-actions">
                <button type="button" class="clock-btn-cancel" onclick="closeClockModal()">Cancel</button>
                <button type="button" class="clock-btn-ok" id="clockOkBtn" onclick="confirmClockTime()" disabled>OK</button>
            </div>
        </div>
    </div>

    {{-- Cancel Appointment Modal --}}
    <div id="cancelModal" class="custom-modal-backdrop">
        <div class="custom-modal-box">
            <h4 style="font-weight:800;color:var(--navy);margin-bottom:12px;">Cancel Appointment</h4>
            <p style="font-size:.9rem;color:var(--muted);margin-bottom:18px;">Please provide a reason for cancelling your appointment:</p>
            <textarea id="cancelReasonText" rows="3" class="sp-input" placeholder="e.g., May emergency po sa family" required></textarea>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button type="button" onclick="hideCancelModal()" class="btn btn-out" style="min-height:42px;padding:0 20px;">Cancel</button>
                <button type="button" onclick="submitCancel()" class="btn btn-navy" style="background:#dc2626;min-height:42px;padding:0 20px;">Confirm Cancel</button>
            </div>
        </div>
    </div>

    {{-- Reschedule Appointment Modal --}}
    <div id="rescheduleModal" class="custom-modal-backdrop">
        <div class="custom-modal-box">
            <h4 style="font-weight:800;color:var(--navy);margin-bottom:12px;">Request Reschedule</h4>
            <form method="POST" action="{{ route('user.appointments.reschedule', $appointment->id ?? 0) }}" id="rescheduleForm">
                @csrf
                <div class="fld">
                    <label>New Date <span class="req">*</span></label>
                    <input type="date" name="reschedule_date" id="rescheduleDate"
                        min="{{ $minDate ?? \Carbon\Carbon::tomorrow('Asia/Manila')->format('Y-m-d') }}"
                        max="{{ $maxDate ?? \Carbon\Carbon::now('Asia/Manila')->addYears(5)->format('Y-m-d') }}"
                        required class="sp-input">
                </div>
                <div class="fld">
                    <label>New Time <span class="req">*</span></label>
                    <select name="reschedule_time" id="rescheduleTime" required disabled class="sp-input">
                        <option value="">Select date first</option>
                    </select>
                    <div id="rescheduleSlotMsg" class="help"></div>
                </div>
                <div class="fld">
                    <label>Reason for Reschedule <span class="req">*</span></label>
                    <textarea name="reschedule_reason" rows="2" required class="sp-input" placeholder="e.g., Conflict in work schedule"></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:20px;">
                    <button type="button" onclick="hideRescheduleModal()" class="btn btn-out" style="min-height:42px;padding:0 20px;">Cancel</button>
                    <button type="submit" class="btn btn-navy" style="min-height:42px;padding:0 20px;">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const WIZARD_STEPS = @json($wizardSteps);
        const HAS_APPOINTMENT = @json($hasAppointment);
        const IS_BENEFICIARY = @json(!empty($isBeneficiary));
        const IS_ID_READY = @json((bool) $tlIdReady);
        const IS_VALIDATED = @json((bool) $isValidated);
        const HAS_ACTIVE_APP = @json((bool) $hasActiveApp);
        const HAS_ACTIVE_RENEWAL = @json($hasActiveRenewal);
        const APPT_NOTICE = @json((bool) session('appt_success'));
        const WIZARD_META = {
            overview: { titleEn: 'Step 1 — Overview', titleTl: 'Hakbang 1 — Pangkalahatan', descEn: 'Learn how the Solo Parent ID application works.', descTl: 'Alamin kung paano gumagana ang Solo Parent ID application.' },
            book: { titleEn: 'Step 2 — Book Appointment', titleTl: 'Hakbang 2 — Mag-book ng Appointment', descEn: 'Schedule your interview with the MSWDO.', descTl: 'Mag-schedule ng panayam sa MSWDO.' },
            guide: { titleEn: WIZARD_STEPS.includes('book') ? 'Step 3 — How to Apply' : 'Step 2 — How to Apply', titleTl: WIZARD_STEPS.includes('book') ? 'Hakbang 3 — Paano Mag-apply' : 'Hakbang 2 — Paano Mag-apply', descEn: 'Follow the 7-step application process.', descTl: 'Sundin ang 7-hakbang na proseso.' },
            office: { titleEn: WIZARD_STEPS.includes('book') ? 'Step 4 — Office Info' : 'Step 3 — Office Info', titleTl: WIZARD_STEPS.includes('book') ? 'Hakbang 4 — Opisina' : 'Hakbang 3 — Opisina', descEn: 'MSWDO office location and interview options.', descTl: 'Lokasyon ng opisina at mga opsyon sa panayam.' },
            requirements: { titleEn: 'Step 5 — Documents', titleTl: 'Hakbang 5 — Dokumento', descEn: 'Upload requirements after eligibility validation.', descTl: 'Mag-upload pagkatapos ma-validate.' },
            finish: { titleEn: 'Step 6 — Finish', titleTl: 'Hakbang 6 — Tapos', descEn: 'Complete the guide and start tracking.', descTl: 'Tapusin ang gabay at magsimulang mag-track.' },
        };

        let currentStep = 0, currentLang = 'en', selectedKind = 'new';

        function selectAppKind(kind) {
            selectedKind = kind;
            document.querySelectorAll('input[name="appKind"]').forEach(r => {
                r.checked = (r.value === kind);
            });
            document.getElementById('choiceContBtn').disabled = false;
        }

        function handleChoiceContinue() {
            sessionStorage.setItem('spAppKind', selectedKind);
            document.getElementById('stepChoice').style.display = 'none';
            document.getElementById('pickedBanner').style.display = 'flex';

            if (selectedKind === 'renewal') {
                document.getElementById('pickedLabel').textContent = currentLang === 'tl' ? 'Solo Parent ID Renewal' : 'Solo Parent ID Renewal';
                document.getElementById('flowRenew').style.display = 'block';
                document.getElementById('sp-wizard-view').style.display = 'none';
                document.getElementById('sp-monitor-view').style.display = 'none';
            } else {
                document.getElementById('pickedLabel').textContent = currentLang === 'tl' ? 'Bagong Solo Parent Application' : 'New Solo Parent Application';
                document.getElementById('flowRenew').style.display = 'none';
                document.getElementById('sp-wizard-view').style.display = 'block';
                document.getElementById('sp-monitor-view').style.display = 'none';
                goToStep(0, false);
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function changeChoice() {
            sessionStorage.removeItem('spAppKind');
            document.getElementById('pickedBanner').style.display = 'none';
            document.getElementById('flowRenew').style.display = 'none';
            document.getElementById('sp-wizard-view').style.display = 'none';
            document.getElementById('stepChoice').style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goToStep(index, save = true) {
            if (index < 0 || index >= WIZARD_STEPS.length) return;
            currentStep = index;
            const key = WIZARD_STEPS[index];
            const meta = WIZARD_META[key] || WIZARD_META.overview;

            document.querySelectorAll('.wizard-panel').forEach(p => {
                p.style.display = (p.dataset.panel === key) ? 'block' : 'none';
            });

            document.querySelectorAll('.stepper .stp').forEach((btn, i) => {
                btn.classList.remove('active', 'done');
                if (i < index) btn.classList.add('done');
                if (i === index) btn.classList.add('active');
                btn.querySelector('button').textContent = i < index ? '✓' : (i + 1);
            });

            const titleEl = document.getElementById('wizard-step-title');
            const descEl  = document.getElementById('wizard-step-desc');
            titleEl.textContent = currentLang === 'tl' ? meta.titleTl : meta.titleEn;
            descEl.textContent  = currentLang === 'tl' ? meta.descTl  : meta.descEn;

            const fillPct = ((index + 1) / WIZARD_STEPS.length * 100);
            document.getElementById('wizard-progress-fill').style.width = fillPct + '%';

            document.getElementById('wiz-btn-prev').disabled = (index === 0);
            const isLast = (index === WIZARD_STEPS.length - 1);
            document.getElementById('wiz-btn-next').style.display = isLast ? 'none' : 'inline-flex';
            document.getElementById('wiz-btn-finish').style.display = isLast ? 'inline-flex' : 'none';
            document.getElementById('wiz-step-counter').textContent = currentLang === 'tl'
                ? `Hakbang ${index + 1} ng ${WIZARD_STEPS.length}`
                : `Step ${index + 1} of ${WIZARD_STEPS.length}`;

            if (save) sessionStorage.setItem('spWizardStep', String(index));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function wizardNext() { goToStep(currentStep + 1); }
        function wizardPrev() { goToStep(currentStep - 1); }

        function shouldShowMonitor() {
            if (IS_BENEFICIARY || IS_ID_READY || IS_VALIDATED || HAS_ACTIVE_APP) return true;
            if (sessionStorage.getItem('spWizardComplete') === '1') return true;
            if (HAS_APPOINTMENT) return true;
            if (APPT_NOTICE) return true;
            return false;
        }

        function showMonitorView(shouldScroll = false) {
            document.getElementById('stepChoice').style.display = 'none';
            document.getElementById('pickedBanner').style.display = 'none';
            document.getElementById('flowRenew').style.display = 'none';
            document.getElementById('sp-wizard-view').style.display = 'none';
            document.getElementById('sp-monitor-view').style.display = 'block';
            document.getElementById('hero-title').textContent = currentLang === 'tl' ? 'Tracker ng Solo Parent Application' : 'Solo Parent Application Tracker';
            document.getElementById('hero-sub').textContent = currentLang === 'tl' ? 'Subaybayan ang appointment, mag-upload ng dokumento, at i-monitor ang progress.' : 'Track your appointment, upload documents, and monitor your application progress.';
            if (shouldScroll && !sessionStorage.getItem('spScrollRestore')) {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function showWizardView() {
            document.getElementById('sp-monitor-view').style.display = 'none';
            document.getElementById('sp-wizard-view').style.display = 'block';
            document.getElementById('hero-title').textContent = currentLang === 'tl' ? 'Wizard ng Solo Parent Application' : 'Solo Parent Application';
            document.getElementById('hero-sub').textContent = currentLang === 'tl' ? 'Mag-book ng panayam, sundin ang bawat hakbang, at subaybayan ang aplikasyon.' : 'Book your interview, follow each step, and track your Solo Parent ID application.';
            goToStep(currentStep, false);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function finishWizard() {
            sessionStorage.setItem('spWizardComplete', '1');
            showMonitorView(true);
        }

        function setLang(lang) {
            currentLang = lang;
            document.getElementById('langBtnEn').classList.toggle('active', lang === 'en');
            document.getElementById('langBtnTl').classList.toggle('active', lang === 'tl');
            document.getElementById('langBtnEn').setAttribute('aria-pressed', lang === 'en');
            document.getElementById('langBtnTl').setAttribute('aria-pressed', lang === 'tl');

            document.querySelectorAll('[data-en]').forEach(el => {
                const val = lang === 'tl' ? el.getAttribute('data-tl') : el.getAttribute('data-en');
                if (val) el.textContent = val;
            });

            const meta = WIZARD_META[WIZARD_STEPS[currentStep]] || WIZARD_META.overview;
            document.getElementById('wizard-step-title').textContent = lang === 'tl' ? meta.titleTl : meta.titleEn;
            document.getElementById('wizard-step-desc').textContent  = lang === 'tl' ? meta.descTl  : meta.descEn;
            document.getElementById('wiz-step-counter').textContent  = lang === 'tl'
                ? `Hakbang ${currentStep + 1} ng ${WIZARD_STEPS.length}`
                : `Step ${currentStep + 1} of ${WIZARD_STEPS.length}`;
        }

        function closeFlashNotification() {
            const el = document.getElementById('flashNotification');
            if (el) el.remove();
        }

        function updateWordCount(prefix, textarea) {
            const maxWords = 200;
            if (!textarea) return;
            let text = textarea.value;
            const words = text.trim().match(/\S+/g) || [];
            let count = words.length;
            const countEl = document.getElementById('wordCount-' + prefix);
            const errEl   = document.getElementById('wordCountError-' + prefix) || document.getElementById('wordCountMsg-' + prefix);
            const submitBtn = document.getElementById('apptSubmitBtn-' + prefix) || document.getElementById('renewalSubmitBtn');

            if (count > maxWords) {
                // Find cut position after the 200th word
                let wordCount = 0;
                let cutIndex = text.length;
                const regex = /\S+/g;
                let m;
                while ((m = regex.exec(text)) !== null) {
                    wordCount++;
                    if (wordCount === maxWords) {
                        cutIndex = regex.lastIndex;
                        break;
                    }
                }
                textarea.value = text.slice(0, cutIndex);
                count = maxWords;
                if (errEl) {
                    errEl.textContent = 'Maximum 200 words reached. Excess text was trimmed.';
                    errEl.style.display = 'block';
                }
                textarea.style.borderColor = '#dc2626';
            } else if (count === maxWords) {
                if (errEl) {
                    errEl.textContent = 'Maximum 200 words reached.';
                    errEl.style.display = 'block';
                }
                textarea.style.borderColor = '#eab308';
            } else {
                if (errEl) errEl.style.display = 'none';
                textarea.style.borderColor = '';
            }

            if (countEl) {
                countEl.textContent = count + ' / ' + maxWords + ' words';
                countEl.style.color = (count >= maxWords) ? '#dc2626' : '#64748b';
            }
            if (submitBtn) submitBtn.disabled = false;
        }

        // Cancel Appointment modal
        let activeCancelPrefix = 'mon';
        function showCancelModal(prefix) {
            activeCancelPrefix = prefix || 'mon';
            document.getElementById('cancelReasonText').value = '';
            document.getElementById('cancelModal').style.display = 'flex';
        }
        function hideCancelModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }
        function submitCancel() {
            const reason = document.getElementById('cancelReasonText').value.trim();
            if (!reason) { alert('Please provide a reason for cancellation.'); return; }
            const input = document.getElementById('cancelReasonInput-' + activeCancelPrefix);
            if (input) input.value = reason;
            const form = document.getElementById('cancelForm-' + activeCancelPrefix);
            if (form) form.submit();
        }

        // Reschedule modal
        function showRescheduleModal() {
            document.getElementById('rescheduleModal').style.display = 'flex';
        }
        function hideRescheduleModal() {
            document.getElementById('rescheduleModal').style.display = 'none';
        }

        /* ── CLOCK TIME PICKER LOGIC (PRESERVED) ── */
        let clockHour24 = 8, clockHour = 8, clockMin = 0;
        let clockMode   = 'hour', clockPrefix = 'wiz';
        let prefixSlots = {};

        const OFFICE_HOURS_24 = [8, 9, 10, 11, 13, 14, 15, 16];
        const MIN_POSITIONS   = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];

        function autoAmPm(h24) { return h24 >= 12 ? 'PM' : 'AM'; }
        function to12(h24) { if (h24 === 0) return 12; if (h24 > 12) return h24 - 12; return h24; }

        function openClockModal(prefix) {
            clockPrefix = prefix;
            const slots = prefixSlots[prefix] || [];
            if (!slots.length) return;
            const firstAvail = slots.find(s => !s.full);
            if (firstAvail) {
                clockHour24 = firstAvail.hour;
                clockHour   = to12(firstAvail.hour);
                clockMin    = firstAvail.minute;
            } else {
                clockHour24 = 8; clockHour = 8; clockMin = 0;
            }
            clockMode = 'hour';
            syncDigits();
            renderClockFace();
            updateSlotInfo();
            document.getElementById('clockModal').classList.add('open');
        }

        function closeClockModal() {
            document.getElementById('clockModal').classList.remove('open');
        }

        function clockSetMode(mode) {
            clockMode = mode;
            document.getElementById('clockDigitHour').classList.toggle('active', mode === 'hour');
            document.getElementById('clockDigitMin').classList.toggle('active', mode === 'min');
            renderClockFace();
        }

        function findSlot(h24, min) {
            const slots = prefixSlots[clockPrefix] || [];
            return slots.find(s => s.hour === h24 && s.minute === min) || null;
        }

        function syncDigits() {
            document.getElementById('clockDigitHour').textContent = String(clockHour).padStart(2, '0');
            document.getElementById('clockDigitMin').textContent  = String(clockMin).padStart(2, '0');
            document.getElementById('clockDigitAmPm').textContent = autoAmPm(clockHour24);
        }

        function updateSlotInfo() {
            const info  = document.getElementById('clockSlotInfo');
            const okBtn = document.getElementById('clockOkBtn');
            const slot  = findSlot(clockHour24, clockMin);
            if (!slot) {
                info.textContent = 'Not an available slot';
                info.className   = 'clock-slot-info full';
                okBtn.disabled   = true;
                return;
            }
            if (slot.full) {
                info.textContent = slot.past_time ? 'This time has already passed' : 'This slot is full';
                info.className   = 'clock-slot-info full';
                okBtn.disabled   = true;
            } else {
                info.textContent = slot.remaining + ' slot' + (slot.remaining !== 1 ? 's' : '') + ' available';
                info.className   = 'clock-slot-info avail';
                okBtn.disabled   = false;
            }
        }

        function renderClockFace() {
            const face   = document.getElementById('clockFace');
            const hand   = document.getElementById('clockHand');
            const radius = 88;
            const cx = 120, cy = 120;
            face.querySelectorAll('.clock-num').forEach(n => n.remove());

            if (clockMode === 'hour') {
                for (let h = 1; h <= 12; h++) {
                    let h24;
                    if (h >= 8 && h <= 11)     h24 = h;
                    else if (h >= 1 && h <= 4) h24 = h + 12;
                    else                        h24 = h;

                    const slots     = prefixSlots[clockPrefix] || [];
                    const hourSlots = slots.filter(s => s.hour === h24);
                    const anyAvail  = hourSlots.some(s => !s.full);
                    const isOffice  = OFFICE_HOURS_24.includes(h24);

                    const angle = (h / 12) * 360 - 90;
                    const rad   = angle * Math.PI / 180;
                    const x = cx + radius * Math.cos(rad);
                    const y = cy + radius * Math.sin(rad);

                    const el = document.createElement('div');
                    el.className   = 'clock-num';
                    el.textContent = h;
                    el.style.left  = x + 'px';
                    el.style.top   = y + 'px';

                    if (!isOffice) {
                        el.classList.add('disabled');
                    } else if (!anyAvail) {
                        el.classList.add('full-slot');
                    } else {
                        if (h === clockHour) el.classList.add('selected');
                        el.addEventListener('click', () => {
                            clockHour24 = h24;
                            clockHour   = h;
                            const firstMin = hourSlots.find(s => !s.full);
                            if (firstMin) clockMin = firstMin.minute;
                            syncDigits();
                            updateSlotInfo();
                            renderClockFace();
                            setTimeout(() => clockSetMode('min'), 260);
                        });
                    }
                    face.appendChild(el);
                }
                const deg = (clockHour / 12) * 360;
                hand.style.height    = (radius - 10) + 'px';
                hand.style.transform = 'translateX(-50%) rotate(' + deg + 'deg)';

            } else {
                const h24 = clockHour24;
                MIN_POSITIONS.forEach(function(m, i) {
                    const angle = (i / 12) * 360 - 90;
                    const rad   = angle * Math.PI / 180;
                    const x = cx + radius * Math.cos(rad);
                    const y = cy + radius * Math.sin(rad);

                    const slot = findSlot(h24, m);
                    const el   = document.createElement('div');
                    el.className   = 'clock-num';
                    el.textContent = String(m).padStart(2, '0');
                    el.style.left  = x + 'px';
                    el.style.top   = y + 'px';

                    if (!slot || slot.full) {
                        el.classList.add(slot && slot.past_time ? 'disabled' : 'full-slot');
                        el.title = slot && slot.past_time ? 'Time has passed' : 'Slot full';
                    } else {
                        if (m === clockMin) el.classList.add('selected');
                        el.addEventListener('click', function() {
                            clockMin = m;
                            syncDigits();
                            updateSlotInfo();
                            renderClockFace();
                        });
                    }
                    face.appendChild(el);
                });
                const minIdx = MIN_POSITIONS.indexOf(clockMin);
                const deg    = minIdx >= 0 ? (minIdx / 12) * 360 : 0;
                hand.style.height    = (radius - 10) + 'px';
                hand.style.transform = 'translateX(-50%) rotate(' + deg + 'deg)';
            }
        }

        function confirmClockTime() {
            const slot = findSlot(clockHour24, clockMin);
            if (!slot || slot.full) return;
            const timeVal = String(clockHour24).padStart(2, '0') + ':' + String(clockMin).padStart(2, '0');
            document.getElementById('apptTime-' + clockPrefix).value = timeVal;
            const btn = document.getElementById('timePickerBtn-' + clockPrefix);
            btn.querySelector('span:last-child').textContent = slot.label;
            btn.classList.add('has-value');
            closeClockModal();
        }

        function initApptSlotLoader(prefix) {
            const dateInput = document.getElementById('apptDate-' + prefix);
            const btn       = document.getElementById('timePickerBtn-' + prefix);
            const slotMsg   = document.getElementById('slotMsg-' + prefix);
            if (!dateInput || !btn) return;

            dateInput.addEventListener('change', function() {
                const date = this.value;
                document.getElementById('apptTime-' + prefix).value = '';
                btn.querySelector('span:last-child').textContent = 'Select date first';
                btn.classList.remove('has-value');
                btn.disabled = true;
                prefixSlots[prefix] = [];
                if (slotMsg) slotMsg.textContent = '';

                if (!date) return;

                const d = new Date(date + 'T00:00:00');
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (d <= today) {
                    if (slotMsg) { slotMsg.textContent = 'Appointments must be booked for future dates (starting tomorrow).'; slotMsg.style.color = '#dc2626'; }
                    btn.querySelector('span:last-child').textContent = 'Future dates only';
                    return;
                }
                if (d.getDay() === 0 || d.getDay() === 6) {
                    if (slotMsg) { slotMsg.textContent = 'Please select a weekday (Mon–Fri).'; slotMsg.style.color = '#dc2626'; }
                    btn.querySelector('span:last-child').textContent = 'Weekdays only';
                    return;
                }

                btn.querySelector('span:last-child').textContent = 'Loading slots...';

                fetch('/user/appointments/slots?date=' + date, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(r) {
                        return r.json().then(function(data) {
                            if (!r.ok || data.error) {
                                throw new Error(data.error || 'Could not load slots.');
                            }
                            return data;
                        });
                    })
                    .then(function(slots) {
                        prefixSlots[prefix] = slots;
                        const available = slots.filter(function(s) { return !s.full; });
                        if (!available.length) {
                            btn.querySelector('span:last-child').textContent = 'No slots available';
                            btn.disabled = true;
                            if (slotMsg) { slotMsg.textContent = 'All slots on this date are full or holiday. Please choose another date.'; slotMsg.style.color = '#dc2626'; }
                        } else {
                            btn.querySelector('span:last-child').textContent = 'Select time';
                            btn.disabled = false;
                            if (slotMsg) { slotMsg.textContent = available.length + ' time slot(s) available'; slotMsg.style.color = '#15803d'; }
                        }
                    })
                    .catch(function(err) {
                        btn.querySelector('span:last-child').textContent = 'Could not load slots';
                        btn.disabled = true;
                        if (slotMsg) { slotMsg.textContent = err.message || 'Error loading available slots. Please try another date.'; slotMsg.style.color = '#dc2626'; }
                    });
            });
        }

        // Reschedule slots loader
        const rescheduleDate = document.getElementById('rescheduleDate');
        const rescheduleTime = document.getElementById('rescheduleTime');
        const rescheduleSlotMsg = document.getElementById('rescheduleSlotMsg');
        if (rescheduleDate && rescheduleTime) {
            rescheduleDate.addEventListener('change', function() {
                const date = this.value;
                if (!date) {
                    rescheduleTime.innerHTML = '<option value="">Select date first</option>';
                    rescheduleTime.disabled = true;
                    if (rescheduleSlotMsg) rescheduleSlotMsg.textContent = '';
                    return;
                }
                const d = new Date(date + 'T00:00:00');
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (d <= today) {
                    rescheduleTime.innerHTML = '<option value="">Future dates only</option>';
                    rescheduleTime.disabled = true;
                    rescheduleSlotMsg.textContent = 'Appointments must be booked for future dates (starting tomorrow).';
                    rescheduleSlotMsg.style.color = '#dc2626';
                    return;
                }
                if (d.getDay() === 0 || d.getDay() === 6) {
                    rescheduleTime.innerHTML = '<option value="">Weekdays only</option>';
                    rescheduleTime.disabled = true;
                    rescheduleSlotMsg.textContent = 'Please select a weekday (Mon–Fri).';
                    rescheduleSlotMsg.style.color = '#dc2626';
                    return;
                }
                rescheduleTime.innerHTML = '<option value="">Loading slots…</option>';
                rescheduleSlotMsg.textContent = '';
                fetch(`/user/appointments/slots?date=${date}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json().then(data => {
                        if (!r.ok || data.error) {
                            throw new Error(data.error || 'Could not load slots.');
                        }
                        return data;
                    }))
                    .then(slots => {
                        rescheduleTime.innerHTML = '<option value="">Select a time</option>';
                        const available = slots.filter(s => !s.full);
                        if (!available.length) {
                            rescheduleTime.innerHTML = '<option value="">No slots available</option>';
                            rescheduleTime.disabled = true;
                            rescheduleSlotMsg.textContent = 'All slots on this date are full. Please choose another date.';
                            rescheduleSlotMsg.style.color = '#dc2626';
                            return;
                        }
                        slots.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.time;
                            opt.textContent = `${s.label} (${s.remaining} left)`;
                            if (s.full) {
                                opt.disabled = true;
                                opt.textContent = `${s.label} (Full)`;
                            }
                            rescheduleTime.appendChild(opt);
                        });
                        rescheduleTime.disabled = false;
                        rescheduleSlotMsg.textContent = `${available.length} time slot(s) available.`;
                        rescheduleSlotMsg.style.color = '#15803d';
                    })
                    .catch(err => {
                        rescheduleTime.innerHTML = '<option value="">Error loading slots</option>';
                        rescheduleTime.disabled = true;
                        rescheduleSlotMsg.textContent = err.message || 'Error loading slots. Please try another date.';
                        rescheduleSlotMsg.style.color = '#dc2626';
                    });
            });
        }

        // Initialize On Load
        document.addEventListener('DOMContentLoaded', function() {
            initApptSlotLoader('wiz');
            initApptSlotLoader('mon');
            initApptSlotLoader('renew');
            initBulkDropzones();

            const flashEl = document.getElementById('flashNotification');
            if (flashEl) {
                const timerBar = document.getElementById('flashTimer');
                if (timerBar) timerBar.style.transition = 'width 6s linear';
                setTimeout(() => { if (timerBar) timerBar.style.width = '0%'; }, 50);
                setTimeout(() => { closeFlashNotification(); }, 6000);
            }

            // Decide default view: active renewal request takes precedence when awaiting ID review or interview
            if (HAS_ACTIVE_RENEWAL && !IS_VALIDATED && !HAS_ACTIVE_APP) {
                document.getElementById('stepChoice').style.display = 'none';
                document.getElementById('pickedBanner').style.display = 'flex';
                document.getElementById('pickedLabel').textContent = 'Solo Parent ID Renewal';
                document.getElementById('flowRenew').style.display = 'block';
                document.getElementById('sp-wizard-view').style.display = 'none';
                document.getElementById('sp-monitor-view').style.display = 'none';
            } else if (shouldShowMonitor()) {
                showMonitorView(false);
            } else {
                // Check if user previously made a choice in this session
                const savedKind = sessionStorage.getItem('spAppKind');
                if (savedKind === 'renewal') {
                    selectAppKind('renewal');
                    handleChoiceContinue();
                } else if (savedKind === 'new') {
                    selectAppKind('new');
                    handleChoiceContinue();
                } else {
                    document.getElementById('stepChoice').style.display = 'block';
                    document.getElementById('pickedBanner').style.display = 'none';
                    document.getElementById('flowRenew').style.display = 'none';
                    document.getElementById('sp-wizard-view').style.display = 'none';
                    document.getElementById('sp-monitor-view').style.display = 'none';
                }
            }
        });


        function launchCelebrationConfetti() {
            const host = document.getElementById('cel');
            if (!host) return;
            const cols = ['#f5b82e', '#1e2a78', '#22a559', '#c93a2f', '#ffffff'];
            const w = document.createElement('div');
            w.className = 'confetti';
            w.setAttribute('aria-hidden', 'true');
            for (let k = 0; k < 28; k++) {
                const p = document.createElement('i');
                p.style.left = (Math.random() * 100) + '%';
                p.style.background = cols[k % cols.length];
                p.style.animationDelay = (Math.random() * 0.6) + 's';
                p.style.animationDuration = (2 + Math.random() * 1.5) + 's';
                w.appendChild(p);
            }
            host.appendChild(w);
            setTimeout(() => { if (w.parentNode) w.remove(); }, 4400);
        }

        /* ── UPLOAD ALL & FILE VIEWER FUNCTIONS ── */
        const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

        function formatFileSize(bytes) {
            if (bytes > 1048576) return (bytes / 1048576).toFixed(1) + ' MB';
            return Math.max(1, Math.round(bytes / 1024)) + ' KB';
        }

        function showUploadNotification(message, isError = false) {
            const existing = document.getElementById('uploadNotification');
            if (existing) existing.remove();
            const notif = document.createElement('div');
            notif.id = 'uploadNotification';
            notif.className = 'toast-notice';
            notif.style.cssText = `position:fixed;top:84px;right:18px;z-index:100000;max-width:420px;background:${isError ? '#991b1b' : 'var(--navy-d)'};color:white;border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.85rem;font-weight:700;border:1px solid rgba(255,255,255,.2);`;
            notif.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <span>${message}</span>
                    <button onclick="this.closest('#uploadNotification').remove()" style="background:transparent;border:none;color:white;font-size:1.2rem;cursor:pointer;">&times;</button>
                </div>
            `;
            document.body.appendChild(notif);
            setTimeout(() => { if (notif.parentNode) notif.remove(); }, 6000);
        }

        function openSpFileModal(fileUrl, fileName, fileExt) {
            const ext = (fileExt || '').toLowerCase();
            let content;
            if (['jpg','jpeg','png','gif','webp','bmp'].includes(ext)) {
                content = `<img src="${fileUrl}" alt="${fileName}" style="max-width:100%;max-height:70vh;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,.1);display:block;margin:0 auto;">`;
            } else if (ext === 'pdf') {
                content = `<iframe src="${fileUrl}" style="width:100%;height:70vh;border:none;border-radius:8px;"></iframe>`;
            } else {
                content = `<div style="text-align:center;padding:24px;"><div style="font-size:3rem;margin-bottom:12px;">📄</div><p style="font-weight:700;color:var(--navy);">Preview not available for this format.</p><a href="${fileUrl}" class="btn btn-navy" download>Download File</a></div>`;
            }
            const existing = document.getElementById('spFileViewerOverlay');
            if (existing) existing.remove();
            const overlay = document.createElement('div');
            overlay.id = 'spFileViewerOverlay';
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(2px);z-index:100000;display:flex;align-items:center;justify-content:center;padding:16px;';
            overlay.onclick = function(e) { if (e.target === overlay) overlay.remove(); };
            overlay.innerHTML = `
                <div style="background:white;border-radius:18px;max-width:860px;width:100%;max-height:92vh;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,.25);display:flex;flex-direction:column;">
                    <div style="background:linear-gradient(135deg,var(--navy),var(--navy-d));color:white;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-weight:800;font-size:1rem;">${fileName || 'Uploaded Document'}</span>
                        <button onclick="document.getElementById('spFileViewerOverlay').remove()" style="background:transparent;border:none;color:white;font-size:1.4rem;cursor:pointer;">&times;</button>
                    </div>
                    <div style="padding:20px;overflow:auto;flex:1;background:#f8faff;">
                        ${content}
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
        }

        function updateRowReqName(selectEl) {
            const row = selectEl.closest('.up-row');
            if (row) row.dataset.reqName = selectEl.value;
        }

        function syncUploaderState(container) {
            if (!container) return;
            const rows = Array.from(container.querySelectorAll('.up-row'));
            const total = rows.length;
            const staged = rows.filter(r => r.dataset.s === 'ready');
            const uploaded = rows.filter(r => r.dataset.s === 'uploaded');
            const added = rows.filter(r => r.dataset.s === 'ready' || r.dataset.s === 'uploaded');

            const countEl = container.querySelector('.up-count');
            const meterFill = container.querySelector('.up-meter i');
            const allBtn = container.querySelector('.up-btns .btn-navy');
            const btnText = allBtn ? allBtn.querySelector('.up-btn-text') : null;

            if (countEl) {
                let txt = `${added.length} of ${total} documents added`;
                if (uploaded.length > 0) txt += ` · ${uploaded.length} uploaded`;
                countEl.textContent = txt;
            }

            if (meterFill && total > 0) {
                const pct = Math.round((added.length / total) * 100);
                meterFill.style.width = pct + '%';
            }

            if (allBtn && btnText) {
                const rd = staged.length;
                if (rd > 0) {
                    btnText.textContent = `Upload all (${rd})`;
                    allBtn.disabled = false;
                } else if (added.length === total && uploaded.length === total) {
                    btnText.textContent = 'All uploaded';
                    allBtn.disabled = true;
                } else {
                    btnText.textContent = 'Upload all';
                    allBtn.disabled = true;
                }
            }
        }

        function stageFileOnRow(row, file) {
            if (!row || !file) return false;

            // Validate size
            if (file.size > MAX_FILE_SIZE) {
                const errEl = row.querySelector('.inline-err');
                if (errEl) {
                    errEl.textContent = `"${file.name}" exceeds 5MB limit. Please choose a smaller file.`;
                    errEl.style.display = 'block';
                }
                row.dataset.s = 'error';
                return false;
            }

            // Clear errors
            const errEl = row.querySelector('.inline-err');
            if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }

            // Store file reference on the row
            row._stagedFile = file;
            row.dataset.s = 'ready';

            // Update thumbnail
            const th = row.querySelector('.up-th');
            if (th) {
                if (/^image\//.test(file.type)) {
                    const img = document.createElement('img');
                    img.alt = '';
                    img.src = URL.createObjectURL(file);
                    th.replaceChildren(img);
                } else {
                    th.innerHTML = '<svg class="i"><use href="#i-file"/></svg>';
                }
            }

            // Update meta
            const meta = row.querySelector('.up-meta');
            if (meta) {
                meta.textContent = `${file.name} · ${formatFileSize(file.size)}`;
            }

            // Update status pill
            const pill = row.querySelector('.up-st');
            if (pill) {
                pill.className = 'up-st ready';
                pill.textContent = 'Ready to upload';
            }

            // Show individual upload & remove buttons
            const goBtn = row.querySelector('.up-go');
            if (goBtn) goBtn.style.display = 'inline-flex';
            const rmBtn = row.querySelector('.up-remove');
            if (rmBtn) rmBtn.style.display = 'inline-flex';

            // Update chooser label
            const chooserLabel = row.querySelector('.chooser-label');
            if (chooserLabel) chooserLabel.textContent = 'Replace';
            const chooserBtn = row.querySelector('.chooser-btn');
            if (chooserBtn) chooserBtn.classList.remove('pri');

            const container = row.closest('.up');
            syncUploaderState(container);
            return true;
        }

        function handleRowFileSelect(input) {
            const row = input.closest('.up-row');
            if (input.files && input.files[0]) {
                stageFileOnRow(row, input.files[0]);
            }
        }

        function clearRowFile(btn) {
            const row = btn.closest('.up-row');
            if (!row) return;

            delete row._stagedFile;
            row.dataset.s = row.dataset.status === 'rejected' ? 'error' : (row.dataset.status === 'not_uploaded' ? 'empty' : 'uploaded');

            const fileInput = row.querySelector('.row-file-input');
            if (fileInput) fileInput.value = '';

            const errEl = row.querySelector('.inline-err');
            if (errEl) { errEl.textContent = ''; errEl.style.display = 'none'; }

            const th = row.querySelector('.up-th');
            if (th) {
                th.innerHTML = (row.dataset.s === 'uploaded')
                    ? '<svg class="i"><use href="#i-check"/></svg>'
                    : '<svg class="i"><use href="#i-file"/></svg>';
            }

            const meta = row.querySelector('.up-meta');
            if (meta) {
                meta.textContent = row.dataset.s === 'uploaded' ? 'Document uploaded' : 'JPG, PNG or PDF · max 5MB';
            }

            const pill = row.querySelector('.up-st');
            if (pill) {
                if (row.dataset.s === 'uploaded') {
                    pill.className = 'up-st ok';
                    pill.textContent = 'Uploaded';
                } else if (row.dataset.s === 'error') {
                    pill.className = 'up-st bad';
                    pill.textContent = 'Action Needed';
                } else {
                    pill.className = 'up-st';
                    pill.textContent = 'Not added';
                }
            }

            const goBtn = row.querySelector('.up-go');
            if (goBtn) goBtn.style.display = 'none';
            const rmBtn = row.querySelector('.up-remove');
            if (rmBtn) rmBtn.style.display = 'none';

            const chooserLabel = row.querySelector('.chooser-label');
            if (chooserLabel) {
                chooserLabel.textContent = row.dataset.s === 'uploaded' ? 'Replace' : (row.dataset.s === 'error' ? 'Re-upload' : 'Choose file');
            }
            const chooserBtn = row.querySelector('.chooser-btn');
            if (chooserBtn && (row.dataset.s === 'empty' || row.dataset.s === 'error')) {
                chooserBtn.classList.add('pri');
            }

            const container = row.closest('.up');
            syncUploaderState(container);
        }

        function uploadSingleRow(btn) {
            const row = btn.closest('.up-row');
            if (!row || !row._stagedFile) return;

            const container = row.closest('.up');
            const uploadUrl = container ? container.dataset.uploadUrl : '';
            const reqName   = row.dataset.reqName;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            if (!uploadUrl || !reqName) return;

            row.dataset.s = 'uploading';
            const pill = row.querySelector('.up-st');
            if (pill) { pill.className = 'up-st work'; pill.textContent = 'Uploading…'; }

            const prog = row.querySelector('.up-prog');
            const progBar = prog ? prog.querySelector('i') : null;
            if (prog) prog.style.display = 'block';
            if (progBar) progBar.style.width = '30%';

            btn.disabled = true;

            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('requirement_name', reqName);
            formData.append('file', row._stagedFile);

            fetch(uploadUrl, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(res => {
                if (progBar) progBar.style.width = '100%';
                if (res.ok) {
                    setTimeout(() => {
                        if (prog) prog.style.display = 'none';
                        row.dataset.s = 'uploaded';
                        row.dataset.status = 'pending';
                        delete row._stagedFile;

                        if (pill) { pill.className = 'up-st work'; pill.textContent = 'Pending Review'; }
                        const goBtn = row.querySelector('.up-go');
                        if (goBtn) goBtn.style.display = 'none';
                        const rmBtn = row.querySelector('.up-remove');
                        if (rmBtn) rmBtn.style.display = 'none';

                        const chooserLabel = row.querySelector('.chooser-label');
                        if (chooserLabel) chooserLabel.textContent = 'Replace';

                        syncUploaderState(container);
                        showUploadNotification(`"${reqName}" uploaded successfully!`);
                    }, 300);
                } else {
                    throw new Error('Upload failed');
                }
            })
            .catch(err => {
                if (prog) prog.style.display = 'none';
                row.dataset.s = 'error';
                btn.disabled = false;
                if (pill) { pill.className = 'up-st bad'; pill.textContent = 'Upload failed'; }
                const errEl = row.querySelector('.inline-err');
                if (errEl) { errEl.textContent = 'Upload failed. Please try again.'; errEl.style.display = 'block'; }
            });
        }

        function triggerUploadAll(prefix) {
            const container = document.getElementById('upContainer-' + prefix);
            if (!container) return;

            const rows = Array.from(container.querySelectorAll('.up-row')).filter(r => r.dataset.s === 'ready' && r._stagedFile);
            if (!rows.length) {
                showUploadNotification('No staged files ready to upload.', true);
                return;
            }

            const allBtn = document.getElementById('uploadAllBtn-' + prefix);
            const btnText = allBtn ? allBtn.querySelector('.up-btn-text') : null;
            if (allBtn) allBtn.disabled = true;
            if (btnText) btnText.textContent = `Uploading ${rows.length} file(s)…`;

            const uploadUrl = container.dataset.uploadUrl;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            let completed = 0, failed = 0;

            const uploadNext = (index) => {
                if (index >= rows.length) {
                    syncUploaderState(container);
                    if (failed === 0) {
                        showUploadNotification(`All ${completed} documents uploaded successfully!`);
                    } else {
                        showUploadNotification(`${completed} file(s) uploaded, ${failed} failed.`, true);
                    }
                    return;
                }

                const row = rows[index];
                const reqName = row.dataset.reqName;
                row.dataset.s = 'uploading';

                const pill = row.querySelector('.up-st');
                if (pill) { pill.className = 'up-st work'; pill.textContent = 'Uploading…'; }

                const prog = row.querySelector('.up-prog');
                const progBar = prog ? prog.querySelector('i') : null;
                if (prog) prog.style.display = 'block';
                if (progBar) progBar.style.width = '40%';

                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('requirement_name', reqName);
                formData.append('file', row._stagedFile);

                fetch(uploadUrl, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(res => {
                    if (progBar) progBar.style.width = '100%';
                    if (res.ok) {
                        completed++;
                        row.dataset.s = 'uploaded';
                        row.dataset.status = 'pending';
                        delete row._stagedFile;
                        if (prog) prog.style.display = 'none';
                        if (pill) { pill.className = 'up-st work'; pill.textContent = 'Pending Review'; }
                        const goBtn = row.querySelector('.up-go');
                        if (goBtn) goBtn.style.display = 'none';
                        const rmBtn = row.querySelector('.up-remove');
                        if (rmBtn) rmBtn.style.display = 'none';
                        const chooserLabel = row.querySelector('.chooser-label');
                        if (chooserLabel) chooserLabel.textContent = 'Replace';
                    } else {
                        failed++;
                        row.dataset.s = 'error';
                        if (pill) { pill.className = 'up-st bad'; pill.textContent = 'Failed'; }
                    }
                })
                .catch(() => {
                    failed++;
                    row.dataset.s = 'error';
                    if (pill) { pill.className = 'up-st bad'; pill.textContent = 'Failed'; }
                })
                .finally(() => {
                    uploadNext(index + 1);
                });
            };

            uploadNext(0);
        }

        function initBulkDropzones() {
            document.querySelectorAll('.up-drop').forEach(dropZone => {
                const container = dropZone.closest('.up');
                if (!container) return;
                const fileInput = dropZone.querySelector('input[type="file"]');
                const errorMsg = container.querySelector('.sp-status-msg.rejected');

                function handleFiles(files) {
                    if (!files || !files.length) return;
                    const rows = Array.from(container.querySelectorAll('.up-row'));
                    const emptyRows = rows.filter(r => r.dataset.s === 'empty' || r.dataset.s === 'error');

                    if (errorMsg) { errorMsg.textContent = ''; errorMsg.style.display = 'none'; }

                    let stagedCount = 0;
                    let skippedCount = 0;
                    let sizeErrors = [];

                    Array.from(files).forEach(file => {
                        const targetRow = emptyRows.shift();
                        if (targetRow) {
                            const ok = stageFileOnRow(targetRow, file);
                            if (ok) stagedCount++;
                            else sizeErrors.push(`"${file.name}" exceeds 5MB`);
                        } else {
                            skippedCount++;
                        }
                    });

                    if (sizeErrors.length > 0 || skippedCount > 0) {
                        let msg = '';
                        if (sizeErrors.length > 0) msg += sizeErrors.join(', ') + '. ';
                        if (skippedCount > 0) msg += `${skippedCount} file(s) not added (no more empty rows).`;
                        if (errorMsg) {
                            errorMsg.textContent = msg;
                            errorMsg.style.display = 'block';
                        }
                    }

                    if (stagedCount > 0) {
                        showUploadNotification(`${stagedCount} file(s) staged. Click "Upload all" to submit.`);
                    }
                }

                ['dragenter', 'dragover'].forEach(name => {
                    dropZone.addEventListener(name, (e) => { e.preventDefault(); dropZone.classList.add('over'); });
                });
                ['dragleave', 'drop'].forEach(name => {
                    dropZone.addEventListener(name, (e) => { e.preventDefault(); dropZone.classList.remove('over'); });
                });
                dropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files) {
                        handleFiles(e.dataTransfer.files);
                    }
                });
                if (fileInput) {
                    fileInput.addEventListener('change', () => {
                        handleFiles(fileInput.files);
                        fileInput.value = '';
                    });
                }
            });
        }

    </script>
</body>
</html>
