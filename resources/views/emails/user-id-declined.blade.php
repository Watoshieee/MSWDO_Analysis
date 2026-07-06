<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valid ID Declined</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4ff; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 600px; margin: 40px auto; background: white; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 30px rgba(44,62,143,.12); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 34px 30px; color: white; text-align: center; }
        .header h1 { margin: 0; font-size: 1.4rem; font-weight: 800; }
        .header p { margin: 6px 0 0; opacity: .82; font-size: .88rem; }
        .badge-row { padding: 10px 30px; background: #fff8e1; font-size: .72rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: #856404; border-bottom: 1px solid #e2e8f0; text-align: center; }
        .body { padding: 28px 30px; color: #334155; }
        .title { font-size: 1.02rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
        .msg { line-height: 1.7; font-size: .9rem; margin-bottom: 18px; }
        .box { background: #fef2f2; border-left: 4px solid #dc3545; border-radius: 10px; padding: 14px 16px; font-size: .86rem; line-height: 1.6; margin-bottom: 20px; color: #991b1b; }
        .cta { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 12px 26px; border-radius: 10px; font-weight: 700; font-size: .9rem; }
        .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px 24px; text-align: center; color: #94a3b8; font-size: .76rem; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>Valid ID Declined</h1>
            <p>Municipal Social Welfare and Development Office</p>
        </div>
        <div class="badge-row">Registration Not Approved</div>
        <div class="body">
            <div class="title">Hello, {{ $fullName }}!</div>
            <div class="msg">
                We reviewed your registration for <strong>{{ $municipality }}</strong>, but your uploaded valid ID was <strong>declined</strong>.
                Your account was not saved in our system. Please register again with a valid ID if you wish to continue.
            </div>
            <div class="box">
                <strong>Reason for decline:</strong><br>
                {{ $reason }}
            </div>
            <a href="{{ $registerUrl }}" class="cta">Register Again</a>
        </div>
        <div class="foot">
            This is an automated notification from MSWDO.
        </div>
    </div>
</body>
</html>
