<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Solo Parent Appointment</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4ff; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(44,62,143,0.10); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 32px 36px 28px; text-align: center; }
        .header h1 { color: white; font-size: 1.35rem; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.75); font-size: 0.82rem; margin-top: 4px; }
        .badge-row { padding: 10px 36px; background: #fff8e1; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #856404; border-bottom: 1px solid #e2e8f0; }
        .body { padding: 32px 36px; }
        .greeting { font-size: 1rem; font-weight: 700; margin-bottom: 10px; color: #1e293b; }
        .intro { font-size: 0.88rem; color: #475569; line-height: 1.7; margin-bottom: 24px; }
        .detail-box { background: #f0f4ff; border-radius: 12px; border: 1px solid #c7d2fe; padding: 20px 24px; margin-bottom: 24px; }
        .detail-box h3 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6366f1; font-weight: 700; margin-bottom: 14px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 0.86rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748b; font-weight: 600; }
        .detail-value { color: #1e293b; font-weight: 700; text-align: right; }
        .cta-btn { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; margin: 8px 0 20px; }
        .note { font-size: 0.79rem; color: #94a3b8; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 18px; margin-top: 8px; }
        .footer { background: #f8fafc; padding: 18px 36px; text-align: center; font-size: 0.74rem; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .applicant-pill { display: inline-block; background: #e0e7ff; color: #3730a3; border-radius: 20px; padding: 4px 14px; font-size: 0.8rem; font-weight: 700; margin-top: 6px; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>📋 New Solo Parent Appointment</h1>
        <p>Municipal Social Welfare and Development Office</p>
    </div>

    <div class="badge-row">⚠️ Action Required — New Appointment Pending Review</div>

    <div class="body">
        <p class="greeting">Hello, Admin!</p>
        <p class="intro">
            A new <strong>Solo Parent ID</strong> appointment has been booked in your municipality and is now waiting for your review. Please confirm or reject it at your earliest convenience.
        </p>

        <div class="detail-box">
            <h3>📋 Applicant Details</h3>
            <div class="detail-row">
                <span class="detail-label">Applicant Name</span>
                <span class="detail-value">{{ $applicant->full_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $applicant->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Municipality</span>
                <span class="detail-value">{{ $appointment->municipality }}</span>
            </div>
        </div>

        <div class="detail-box">
            <h3>📅 Appointment Details</h3>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $appointment->appointment_date->format('F d, Y (l)') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value">{{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Type</span>
                <span class="detail-value">{{ $appointment->interview_type === 'online' ? 'Online Interview' : 'Face-to-Face' }}</span>
            </div>
            @if($appointment->user_notes)
            <div class="detail-row">
                <span class="detail-label">Applicant Notes</span>
                <span class="detail-value" style="max-width:60%;word-break:break-word;">{{ $appointment->user_notes }}</span>
            </div>
            @endif
        </div>

        <div style="text-align:center;margin-bottom:16px;">
            <a href="{{ url('/admin/requirements') }}" class="cta-btn">🔍 Review Appointment</a>
        </div>

        <p class="note">
            This is an automated notification from the MSWDO System. Please do not reply to this email directly.<br>
            Log in to the admin dashboard to confirm, reject, or validate this appointment.
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Municipal Social Welfare and Development Office &nbsp;|&nbsp; Automated Notification
    </div>
</div>
</body>
</html>
