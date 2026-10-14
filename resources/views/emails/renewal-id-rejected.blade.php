<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renewal Valid ID Not Accepted</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4ff; font-family: 'Segoe UI', Arial, sans-serif; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 36px auto; background: white; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 30px rgba(44,62,143,.12); }
        .header { background: linear-gradient(135deg, #7f1d1d 0%, #450a0a 100%); padding: 34px 30px; color: white; text-align: center; }
        .header h1 { margin: 0; font-size: 1.45rem; font-weight: 800; letter-spacing: -0.01em; }
        .header p { margin: 6px 0 0; opacity: .85; font-size: .88rem; }
        .badge { display: inline-block; background: rgba(255,255,255,.18); color: white; border-radius: 20px; padding: 4px 14px; font-size: .78rem; font-weight: 700; margin-top: 10px; }
        .body { padding: 30px 32px; }
        .greeting { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 12px; }
        .msg { line-height: 1.7; font-size: .92rem; color: #475569; margin-bottom: 22px; }
        .info-card { background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #dc2626; border-radius: 12px; padding: 18px 20px; margin-bottom: 22px; }
        .info-row { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid #edf2f7; font-size: .88rem; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #64748b; font-weight: 600; }
        .info-val { color: #1e293b; font-weight: 700; text-align: right; }
        .remarks-box { background: #fef2f2; border: 1.5px solid #fca5a5; border-radius: 12px; padding: 16px 18px; font-size: .88rem; line-height: 1.6; color: #7f1d1d; margin-bottom: 24px; }
        .remarks-box strong { color: #dc2626; display: block; margin-bottom: 4px; font-size: .92rem; }
        .next-box { background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 12px; padding: 16px 18px; font-size: .88rem; line-height: 1.6; color: #1e40af; margin-bottom: 24px; }
        .next-box strong { color: #1d4ed8; display: block; margin-bottom: 4px; font-size: .92rem; }
        .cta-container { text-align: center; margin: 28px 0 20px; }
        .cta { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 13px 30px; border-radius: 10px; font-weight: 700; font-size: .92rem; }
        .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 24px; text-align: center; color: #94a3b8; font-size: .76rem; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Renewal Valid ID Not Accepted</h1>
            <p>Municipal Social Welfare and Development Office</p>
            <span class="badge">{{ $renewal->municipality }} MSWDO</span>
        </div>
        <div class="body">
            <div class="greeting">Hello, {{ $user->full_name ?? 'Applicant' }}!</div>
            <div class="msg">
                We have reviewed your uploaded Valid ID for your <strong>{{ $progLabel }} ID renewal</strong> and unfortunately it could not be accepted at this time. Please see the reason below and resubmit a valid copy.
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
                    <span class="info-val" style="color:#dc2626;">Not Accepted</span>
                </div>
            </div>

            @if($remarks)
            <div class="remarks-box">
                <strong>Reason for Rejection</strong>
                {{ $remarks }}
            </div>
            @endif

            <div class="next-box">
                <strong>What to Do Next</strong>
                Please log in to your portal and upload a new, clear copy of your valid {{ $progLabel }} ID. Make sure the ID is not expired and all details are legible before submitting.
            </div>

            <div class="cta-container">
                <a href="{{ $portalUrl }}" class="cta">Resubmit Your ID</a>
            </div>
        </div>
        <div class="foot">
            &copy; {{ date('Y') }} MSWDO &mdash; Municipal Social Welfare &amp; Development Office.<br>
            This is an automated notification. Please do not reply directly to this email.
        </div>
    </div>
</body>
</html>
