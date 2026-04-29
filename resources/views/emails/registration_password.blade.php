<!DOCTYPE html>
<html>
<head>
    <title>Your MSWDO Account Password</title>
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
        .password-box {
            font-size: 24px;
            font-weight: bold;
            color: #4e73df;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fc;
            border-radius: 5px;
            margin: 20px 0;
            letter-spacing: 2px;
            border: 2px dashed #4e73df;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
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
            <h2>MSWDO Member Portal</h2>
        </div>
        <div class="content">
            <h3>Welcome, {{ $fullName }}!</h3>
            <p>Your registration has been successfully submitted. A secure password has been automatically generated for your account.</p>
            
            <div class="password-box">
                {{ $password }}
            </div>
            
            <div class="warning-box">
                <h4 style="margin-top: 0; color: #856404;">⚠️ Important Security Information</h4>
                <ul style="margin: 10px 0; color: #856404;">
                    <li>This is your <strong>temporary password</strong></li>
                    <li>Keep this password secure and do not share it with anyone</li>
                    <li>You can change your password anytime using the "Forgot Password" feature</li>
                    <li>Your account requires OTP verification before you can log in</li>
                </ul>
            </div>
            
            <h4>Next Steps:</h4>
            <ol>
                <li>Check your email for the OTP verification code</li>
                <li>Verify your email address using the OTP</li>
                <li>Wait for MSWDO staff to approve your account</li>
                <li>Once approved, log in using your email/username and this password</li>
            </ol>
            
            <p><strong>Login Credentials:</strong></p>
            <ul>
                <li>Email: {{ $email }}</li>
                <li>Password: (shown above)</li>
            </ul>
            
            <p>If you didn't create this account, please contact MSWDO immediately.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} MSWDO Member Portal. All rights reserved.</p>
            <p>Municipal Social Welfare & Development Office</p>
        </div>
    </div>
</body>
</html>
