<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Update</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4ff; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(44,62,143,0.10); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 32px 36px 28px; text-align: center; }
        .header h1 { color: white; font-size: 1.4rem; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.75); font-size: 0.82rem; margin-top: 4px; }
        .badge-row { padding: 10px 36px; font-size: 0.7rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        .badge-rejected  { background: #f8d7da; color: #721c24; }
        .badge-reminder  { background: #FDB913; color: #1A2A5C; }
        .body { padding: 32px 36px; }
        .greeting { font-size: 1rem; font-weight: 600; margin-bottom: 10px; }
        .intro { font-size: 0.88rem; color: #475569; line-height: 1.65; margin-bottom: 24px; }
        .detail-box { background: #f0f4ff; border-radius: 12px; border: 1px solid #c7d2fe; padding: 20px 24px; margin-bottom: 24px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 0.86rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748b; font-weight: 600; }
        .detail-value { color: #1e293b; font-weight: 700; text-align: right; }
        .admin-note { background: #fff8e1; border-left: 4px solid #FDB913; border-radius: 8px; padding: 12px 16px; font-size: 0.85rem; color: #856404; margin-bottom: 20px; }
        .note { font-size: 0.79rem; color: #94a3b8; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 18px; margin-top: 8px; }
        .footer { background: #f8fafc; padding: 18px 36px; text-align: center; font-size: 0.74rem; color: #94a3b8; border-top: 1px solid #e2e8f0; }
        .status-pill-confirmed { background: #d4edda; color: #155724; border-radius: 20px; padding: 4px 14px; font-size: 0.78rem; font-weight: 700; display: inline-block; }
        .status-pill-rejected  { background: #f8d7da; color: #721c24; border-radius: 20px; padding: 4px 14px; font-size: 0.78rem; font-weight: 700; display: inline-block; }
        .status-pill-reminder  { background: #FFF3D6; color: #856404; border-radius: 20px; padding: 4px 14px; font-size: 0.78rem; font-weight: 700; display: inline-block; }
    </style>
</head>
<body>
<div class="wrapper">
    {{-- Header --}}
    <div class="header">
        @if($newStatus === 'confirmed')
        <h1>✅ Appointment Confirmed!</h1>
        @elseif($newStatus === 'rejected')
        <h1>❌ Appointment Not Approved</h1>
        @else
        <h1>⏰ Appointment Reminder</h1>
        @endif
        <p>Municipal Social Welfare &amp; Development Office</p>
    </div>
    <div class="badge-row badge-{{ $newStatus }}">
        {{ strtoupper($appointment->municipality) }} &mdash;
        @if($newStatus === 'confirmed') APPOINTMENT CONFIRMED
        @elseif($newStatus === 'rejected') APPOINTMENT REJECTED
        @else APPOINTMENT TOMORROW
        @endif
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">Hello, {{ $appointment->user?->full_name ?? 'Applicant' }}!</p>

        <p class="intro">
            @if($newStatus === 'confirmed')
                Your appointment for the <strong>Solo Parent ID</strong> application has been <strong>confirmed</strong> by the MSWDO. Please make sure to attend on the scheduled date and time.
            @elseif($newStatus === 'rejected')
                Unfortunately, your appointment request for the <strong>Solo Parent ID</strong> application was not approved. You may re-book a new appointment at your convenience.
            @else
                This is a friendly reminder that you have a scheduled appointment with the MSWDO <strong>tomorrow</strong>. Please prepare and arrive on time.
            @endif
        </p>

        {{-- Details --}}
        <div class="detail-box">
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $appointment->formatted_date }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value">{{ $appointment->formatted_time }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Interview Type</span>
                <span class="detail-value">{{ $appointment->interview_label }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Municipality</span>
                <span class="detail-value">{{ $appointment->municipality }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="status-pill-{{ $newStatus === 'reminder' ? 'reminder' : $newStatus }}">
                        @if($newStatus === 'confirmed') ✅ Confirmed
                        @elseif($newStatus === 'rejected') ❌ Rejected
                        @else ⏰ Tomorrow
                        @endif
                    </span>
                </span>
            </div>
        </div>

        {{-- Admin note --}}
        @if($appointment->admin_notes)
        <div class="admin-note">
            <strong>Note from Admin:</strong> {{ $appointment->admin_notes }}
        </div>
        @endif

        @if($newStatus === 'confirmed')
        <p class="intro" style="margin-bottom:0;">
            📍 <strong>MSWDO Office</strong> — Municipal Hall, Ground Floor.<br>
            Office hours: Monday–Friday, 8:00 AM – 5:00 PM.<br>
            @if($appointment->interview_type === 'online')
            📱 Since you chose <strong>Online Interview</strong>, the admin will contact you on your registered mobile number at the scheduled time.
            @else
            Please bring a valid ID and any documents you have ready.
            @endif
        </p>
        @endif

        <p class="note">
            This notification was sent to your registered email address. Please do not reply to this email.
            Log in to the MSWDO portal to manage your appointment.
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} MSWDO &mdash; Municipal Social Welfare &amp; Development Office.<br>
        This is an automated notification.
    </div>
</div>
</body>
</html>
