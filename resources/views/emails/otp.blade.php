<!DOCTYPE html>
<html>
<head>
    <title>Email Verification OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #4e73df;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #4e73df;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fc;
            border-radius: 5px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            color: #858796;
            font-size: 12px;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>MSWDO Analysis System</h2>
        </div>
        <div class="content">
            <h3>Hello, {{ $full_name }}!</h3>
            <p>Thank you for registering. Please use the following OTP to verify your email address:</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>
            
            <p>This OTP will expire in <strong>10 minutes</strong>.</p>
            <p>If you didn't request this, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MSWDO Analysis System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>