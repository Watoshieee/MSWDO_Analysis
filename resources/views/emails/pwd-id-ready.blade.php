<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWD ID Ready</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4ff; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: white; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 30px rgba(44,62,143,.12); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 34px 30px; color: white; text-align: center; }
        .header h1 { margin: 0; font-size: 1.4rem; font-weight: 800; }
        .header p { margin: 6px 0 0; opacity: .82; font-size: .88rem; }
        .body { padding: 28px 30px; color: #334155; }
        .title { font-size: 1.02rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
        .msg { line-height: 1.7; font-size: .9rem; margin-bottom: 18px; }
        .box { background: #ecfdf5; border-left: 4px solid #16a34a; border-radius: 10px; padding: 14px 16px; font-size: .86rem; line-height: 1.6; margin-bottom: 20px; }
        .cta { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 12px 26px; border-radius: 10px; font-weight: 700; font-size: .9rem; }
        .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center; color: #94a3b8; font-size: .76rem; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>🎫 Your PWD ID is Ready</h1>
            <p>Municipal Social Welfare and Development Office</p>
        </div>
        <div class="body">
            <div class="title">Hello, {{ $user->full_name ?? $application->full_name }}!</div>
            <div class="msg">
                Your <strong>PWD ID</strong> is now ready for pick-up at
                <strong>{{ $application->municipality }} MSWDO Office</strong>.
                Please bring a valid government-issued ID when claiming.
            </div>
            <div class="box">
                <strong>Application #{{ $application->id }}</strong><br>
                Municipality: {{ $application->municipality }}<br>
                ID Ready at: {{ optional($application->id_ready_at)->setTimezone('Asia/Manila')->format('M d, Y h:i A') ?? now()->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}<br>
                Office hours: Monday - Friday, 8:00 AM - 5:00 PM
            </div>
            <a href="{{ url('/user/dashboard') }}" class="cta">Go to Dashboard</a>
        </div>
        <div class="foot">
            This is an automated notification from MSWDO.
        </div>
    </div>
</body>
</html>
