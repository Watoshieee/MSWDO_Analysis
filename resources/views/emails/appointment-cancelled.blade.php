<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 15px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
        .info { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #dc3545; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🚫 Appointment Cancelled</h2>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p><strong>{{ $user->full_name }}</strong> has cancelled their appointment.</p>
            
            <div class="info">
                <h3>Cancelled Appointment Details:</h3>
                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y (l)') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}</p>
                <p><strong>Program:</strong> {{ $appointment->program_type }}</p>
                <p><strong>Interview Type:</strong> {{ $appointment->interview_label }}</p>
            </div>

            <div class="info">
                <h3>Cancellation Reason:</h3>
                <p>{{ $reason }}</p>
            </div>
        </div>
        
        <div class="footer">
            <p>MSWDO Appointment System</p>
        </div>
    </div>
</body>
</html>
