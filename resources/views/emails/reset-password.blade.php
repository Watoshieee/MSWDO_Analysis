<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <div style="text-align: center; border-bottom: 2px solid #2C3E8F; padding-bottom: 20px; margin-bottom: 20px;">
            <h1 style="color: #2C3E8F;">MSWDO Analysis System</h1>
            <p style="color: #666;">Password Reset Request</p>
        </div>
        
        <div style="text-align: center;">
            <h2>Hello, {{ $user->full_name }}!</h2>
            <p>We received a request to reset your password.</p>
            <p>Click the button below to reset your password:</p>
            
            <div style="margin: 30px 0;">
                <a href="{{ route('password.reset', $token) }}?email={{ urlencode($user->email) }}" 
                   style="background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; display: inline-block;">
                    Reset Password
                </a>
            </div>
            
            <p style="color: #666; font-size: 14px;">This link will expire in <strong>30 minutes</strong>.</p>
            <p style="color: #666; font-size: 14px;">If you did not request this, please ignore this email.</p>
            
            <hr style="margin: 30px 0;">
            
            <p style="color: #999; font-size: 12px;">MSWDO Analysis System - Municipal Social Welfare and Development Office</p>
        </div>
    </div>
</body>
</html>