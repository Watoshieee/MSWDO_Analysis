<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 15px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
        .info { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔄 Reschedule Request</h2>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p><strong>{{ $user->full_name }}</strong> has requested to reschedule their appointment.</p>
            
            <div class="info">
                <h3>Current Appointment:</h3>
                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y (l)') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}</p>
                <p><strong>Program:</strong> {{ $appointment->program_type }}</p>
            </div>

            <div class="info">
                <h3>Requested New Schedule:</h3>
                <p><strong>Date:</strong> {{ $appointment->reschedule_date->format('F d, Y (l)') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->reschedule_time)->format('h:i A') }}</p>
                <p><strong>Reason:</strong> {{ $appointment->reschedule_reason }}</p>
            </div>

            <p>Please review and approve/reject this request in the admin panel.</p>
        </div>
        
        <div class="footer">
            <p>MSWDO Appointment System</p>
        </div>
    </div>
</body>
</html>
