<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Solo Parent ID is Ready</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4ff; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 32px rgba(44,62,143,.12); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 40px 32px 32px; text-align: center; }
        .header-icon { font-size: 3.5rem; margin-bottom: 12px; }
        .header h1 { color: white; font-size: 1.6rem; font-weight: 800; margin: 0 0 6px; }
        .header p { color: rgba(255,255,255,.78); font-size: .9rem; margin: 0; }
        .badge { display: inline-block; background: rgba(255,255,255,.15); color: white; border-radius: 20px; padding: 5px 18px; font-size: .8rem; font-weight: 700; margin-top: 12px; letter-spacing: .04em; }
        .body { padding: 32px; }
        .greeting { font-size: 1.05rem; color: #1e293b; font-weight: 700; margin-bottom: 12px; }
        .message { font-size: .9rem; color: #475569; line-height: 1.7; margin-bottom: 24px; }
        .info-card { background: linear-gradient(135deg, #f0f4ff, #e8f0fe); border-left: 4px solid #2C3E8F; border-radius: 12px; padding: 20px 22px; margin-bottom: 24px; }
        .info-card h3 { color: #2C3E8F; font-size: .85rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; margin: 0 0 12px; }
        .info-row { display: flex; gap: 10px; align-items: flex-start; margin-bottom: 8px; font-size: .88rem; color: #334155; }
        .info-row span:first-child { font-weight: 700; min-width: 90px; color: #1e293b; }
        .highlight-box { background: #fff8e1; border: 1.5px solid #fbbf24; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: .88rem; color: #78350f; line-height: 1.6; }
        .highlight-box strong { display: block; margin-bottom: 6px; font-size: .92rem; color: #92400e; }
        .cta-block { text-align: center; margin: 28px 0 24px; }
        .cta-btn { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white; text-decoration: none; border-radius: 12px; padding: 14px 36px; font-weight: 800; font-size: .95rem; letter-spacing: .02em; }
        .footer { background: #f8faff; padding: 22px 32px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { font-size: .78rem; color: #94a3b8; margin: 0 0 4px; }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="header-icon">🎫</div>
        <h1>Your Solo Parent ID is Ready!</h1>
        <p>Municipal Social Welfare and Development Office</p>
        <span class="badge">{{ $application->municipality }} MSWDO</span>
    </div>

    <!-- Body -->
    <div class="body">
        <div class="greeting">Dear {{ $user->name ?? $application->full_name }},</div>

        <div class="message">
            Great news! Your <strong>Solo Parent ID</strong> has been processed and is now ready for pick-up
            at the <strong>{{ $application->municipality }} MSWDO Office</strong>. Please visit during
            office hours to claim your ID.
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <h3>📋 Application Details</h3>
            <div class="info-row">
                <span>Applicant:</span>
                <span>{{ $application->full_name }}</span>
            </div>
            <div class="info-row">
                <span>Program:</span>
                <span>Solo Parent ID</span>
            </div>
            <div class="info-row">
                <span>Municipality:</span>
                <span>{{ $application->municipality }}</span>
            </div>
            <div class="info-row">
                <span>ID Ready:</span>
                <span>{{ \Carbon\Carbon::parse($application->id_ready_at)->format('F d, Y \a\t h:i A') }}</span>
            </div>
        </div>

        <!-- Pickup Reminder -->
        <div class="highlight-box">
            <strong>📍 Pick-Up Instructions</strong>
            Please bring a <strong>valid government-issued ID</strong> when claiming your Solo Parent ID.
            Office hours: <strong>Monday – Friday, 8:00 AM – 5:00 PM</strong> (excluding public holidays).
        </div>

        <div class="cta-block">
            <a href="{{ url('/user/solo-parent-application') }}" class="cta-btn">
                View My Application →
            </a>
        </div>

        <div class="message" style="font-size:.84rem;color:#94a3b8;">
            If you have any questions or concerns, please contact your local MSWDO office directly.
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>{{ $application->municipality }} Municipal Social Welfare and Development Office</strong></p>
        <p>This is an automated notification. Please do not reply to this email.</p>
    </div>
</div>
</body>
</html>
