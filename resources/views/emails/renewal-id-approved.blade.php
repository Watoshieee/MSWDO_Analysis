<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewal Valid ID Approved</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4ff; font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 36px auto; background: white; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 30px rgba(44,62,143,.12); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 34px 30px; color: white; text-align: center; }
        .header h1 { margin: 0; font-size: 1.45rem; font-weight: 800; letter-spacing: -0.01em; }
        .header p { margin: 6px 0 0; opacity: .85; font-size: .88rem; }
        .badge { display: inline-block; background: rgba(255,255,255,.18); color: white; border-radius: 20px; padding: 4px 14px; font-size: .78rem; font-weight: 700; margin-top: 10px; }
        .body { padding: 30px 32px; }
        .greeting { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; }
        .msg { line-height: 1.7; font-size: .92rem; color: #475569; margin-bottom: 22px; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #2C3E8F; border-radius: 12px; padding: 18px 20px; margin-bottom: 22px; }
        .info-row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid #edf2f7; font-size: .88rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-val { color: #1e293b; font-weight: 700; text-align: right; }
        .step-box { background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 12px; padding: 16px 18px; font-size: .88rem; line-height: 1.6; color: #065f46; margin-bottom: 24px; }
        .step-box strong { color: #047857; display: block; margin-bottom: 4px; font-size: .92rem; }
        .cta-container { text-align: center; margin: 28px 0 20px; }
        .cta { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 13px 30px; border-radius: 10px; font-weight: 700; font-size: .92rem; }
        .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 24px; text-align: center; color: #94a3b8; font-size: .76rem; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Renewal Valid ID Approved</h1>
            <p>Municipal Social Welfare and Development Office</p>
            <span class="badge">{{ $renewal->municipality }} MSWDO</span>
        </div>
        <div class="body">
            <div class="greeting">Hello, {{ $user->full_name ?? 'Applicant' }}!</div>
            <div class="msg">
                Good news! Your uploaded Valid ID for your <strong>{{ $progLabel }} ID renewal</strong> has been reviewed and <strong>approved</strong> by the MSWDO in <strong>{{ $renewal->municipality }}</strong>.
            </div>

            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Reference ID</span>
                    <span class="info-val">#{{ $renewal->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Program</span>
                    <span class="info-val">{{ $progLabel }} Renewal</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Municipality</span>
                    <span class="info-val">{{ $renewal->municipality }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Valid ID Status</span>
                    <span class="info-val" style="color:#059669;">Approved</span>
                </div>
                @if($formattedDate)
                <div class="info-row">
                    <span class="info-label">Appointment Date</span>
                    <span class="info-val">{{ $formattedDate }}</span>
                </div>
                @endif
                @if($formattedTime)
                <div class="info-row">
                    <span class="info-label">Appointment Time</span>
                    <span class="info-val">{{ $formattedTime }}</span>
                </div>
                @endif
            </div>

            <div class="step-box">
                @if($appointment && $appointment->status === 'confirmed')
                    <strong>Next Step: Attend Your Scheduled Appointment</strong>
                    Your renewal appointment is confirmed. Please visit the {{ $renewal->municipality }} MSWDO office on <strong>{{ $formattedDate ?? 'your scheduled date' }}</strong> at <strong>{{ $formattedTime ?? 'your scheduled time' }}</strong>. Please bring your existing ID card, supporting documents, and appointment slip.
                @elseif($appointment)
                    <strong>Next Step: Awaiting Final Appointment Confirmation</strong>
                    Your uploaded Valid ID is verified. Your renewal appointment on <strong>{{ $formattedDate ?? 'your selected date' }}</strong> at <strong>{{ $formattedTime ?? 'your selected time' }}</strong> is now awaiting final confirmation by the MSWDO admin. You will receive an appointment confirmation notice once approved. Please keep your existing ID card and supporting documents ready.
                @else
                    <strong>Next Step: Check Portal Dashboard</strong>
                    Your renewal Valid ID has been approved. Please log in to your portal dashboard for further updates regarding your renewal request.
                @endif
            </div>

            <div class="cta-container">
                <a href="{{ $portalUrl }}" class="cta">View Renewal Status →</a>
            </div>
        </div>
        <div class="foot">
            &copy; {{ date('Y') }} MSWDO &mdash; Municipal Social Welfare &amp; Development Office.<br>
            This is an automated notification. Please do not reply directly to this email.
        </div>
    </div>
</body>
</html>
