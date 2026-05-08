<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #ffc107; color: #333; padding: 15px; text-align: center; }
        .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
        .info { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #ffc107; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📅 Your Appointment Has Been Rescheduled</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $appointment->user->full_name }},</p>
            <p>The admin has rescheduled your appointment to a new date and time.</p>
            
            <div class="info">
                <h3>New Appointment Schedule:</h3>
                <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y (l)') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}</p>
                <p><strong>Program:</strong> {{ $appointment->program_type }}</p>
                <p><strong>Interview Type:</strong> {{ $appointment->interview_label }}</p>
            </div>

            @if($appointment->admin_notes)
            <div class="info">
                <h3>Admin Notes:</h3>
                <p>{{ $appointment->admin_notes }}</p>
            </div>
            @endif

            <p>Please make sure to attend on the new schedule. If you have any concerns, please contact the admin.</p>
        </div>
        
        <div class="footer">
            <p>MSWDO Appointment System</p>
        </div>
    </div>
</body>
</html>
