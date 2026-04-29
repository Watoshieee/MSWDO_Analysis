<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solo Parent Eligibility Notification</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4ff; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(44,62,143,0.10); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 36px 36px 28px; text-align: center; position: relative; }
        .header .emoji { font-size: 2.8rem; display: block; margin-bottom: 12px; }
        .header h1 { color: white; font-size: 1.45rem; font-weight: 800; line-height: 1.3; }
        .header p { color: rgba(255,255,255,0.75); font-size: 0.82rem; margin-top: 6px; }
        .badge-row { padding: 11px 36px; background: #d4edda; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #155724; border-bottom: 1px solid #e2e8f0; }
        .body { padding: 32px 36px; }
        .greeting { font-size: 1rem; font-weight: 700; margin-bottom: 10px; color: #1e293b; }
        .intro { font-size: 0.88rem; color: #475569; line-height: 1.7; margin-bottom: 24px; }
        .congrats-box { background: linear-gradient(135deg, #d4edda, #c3e6cb); border-radius: 14px; border: 1.5px solid #a3d9a5; padding: 20px 24px; margin-bottom: 24px; text-align: center; }
        .congrats-box h2 { color: #155724; font-size: 1.1rem; font-weight: 800; }
        .congrats-box p { color: #1e7e34; font-size: 0.86rem; margin-top: 6px; line-height: 1.6; }
        .req-box { background: #f0f4ff; border-radius: 12px; border: 1px solid #c7d2fe; padding: 20px 24px; margin-bottom: 24px; }
        .req-box h3 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6366f1; font-weight: 700; margin-bottom: 14px; }
        .req-item { display: flex; align-items: flex-start; gap: 10px; padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 0.86rem; color: #334155; }
        .req-item:last-child { border-bottom: none; }
        .req-num { width: 24px; height: 24px; min-width: 24px; border-radius: 50%; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; margin-top: 1px; }
        .cta-btn { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; margin: 8px 0 20px; }
        .warning-box { background: #fff8e1; border-left: 4px solid #FDB913; border-radius: 8px; padding: 12px 16px; font-size: 0.83rem; color: #856404; margin-bottom: 20px; line-height: 1.6; }
        .note { font-size: 0.79rem; color: #94a3b8; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 18px; margin-top: 8px; }
        .footer { background: #f8fafc; padding: 18px 36px; text-align: center; font-size: 0.74rem; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <span class="emoji">🎉</span>
        <h1>Congratulations! You are Eligible for Solo Parent ID</h1>
        <p>Municipal Social Welfare and Development Office</p>
    </div>

    <div class="badge-row">✅ Eligibility Confirmed — Next Step: Submit Your Requirements</div>

    <div class="body">
        <p class="greeting">Hello, {{ $appointment->user->full_name }}!</p>
        <p class="intro">
            We are pleased to inform you that after your interview with the MSWDO office, you have been assessed and found <strong>eligible for the Solo Parent ID program</strong>. 
            Your next step is to submit the required documents to complete your application.
        </p>

        <div class="congrats-box">
            <h2>🏆 You Passed the Eligibility Assessment!</h2>
            <p>Your Solo Parent ID application has been officially started. Please submit all required documents as soon as possible to proceed.</p>
        </div>

        <div class="req-box">
            <h3>📋 Required Documents to Submit</h3>

            <div class="req-item">
                <span class="req-num">1</span>
                <span>Birth Certificate of your child/children (PSA copy)</span>
            </div>
            <div class="req-item">
                <span class="req-num">2</span>
                <span>Barangay Certificate (stating you are a solo parent)</span>
            </div>
            <div class="req-item">
                <span class="req-num">3</span>
                <span>Valid Government-Issued ID (PhilSys, Voter's ID, Passport, etc.)</span>
            </div>
            <div class="req-item">
                <span class="req-num">4</span>
                <span>Certificate of No Marriage / CENOMAR (if single) or PSA Marriage Certificate (if separated/widowed)</span>
            </div>
            <div class="req-item">
                <span class="req-num">5</span>
                <span>Death Certificate of spouse (if widowed) or Police/Court documents (if abandoned)</span>
            </div>
            <div class="req-item">
                <span class="req-num">6</span>
                <span>2×2 ID Photo (recent, white background)</span>
            </div>
        </div>

        <div class="warning-box">
            ⚠️ <strong>Important:</strong> Please upload clear, readable scanned copies or photos of your documents. Blurry or incomplete submissions may be declined and will delay your ID processing.
        </div>

        <div style="text-align:center;margin-bottom:16px;">
            <a href="{{ url('/applications/' . $application->id . '/requirements') }}" class="cta-btn">📁 Submit Requirements Now</a>
        </div>

        <p class="note">
            This is an automated notification from the MSWDO System. Please do not reply to this email directly.<br>
            If you have questions, visit the MSWDO office at Municipal Hall, Ground Floor, Monday–Friday, 8:00 AM–5:00 PM.
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Municipal Social Welfare and Development Office &nbsp;|&nbsp; Automated Notification
    </div>
</div>
</body>
</html>
