<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { padding: 15px; text-align: center; color: white; }
        .approved { background: #28a745; }
        .rejected { background: #dc3545; }
        .content { background: #f9f9f9; padding: 20px; margin: 20px 0; }
        .info { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header {{ $action === 'approved' ? 'approved' : 'rejected' }}">
            <h2>{{ $action === 'approved' ? '✅ Reschedule Approved' : '❌ Reschedule Rejected' }}</h2>
        </div>
        
        <div class="content">
            <p>Hello {{ $appointment->user->full_name }},</p>
            
            @if($action === 'approved')
                <p>Your reschedule request has been <strong>approved</strong>.</p>
                
                <div class="info">
                    <h3>New Appointment Schedule:</h3>
                    <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y (l)') }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}</p>
                    <p><strong>Program:</strong> {{ $appointment->program_type }}</p>
                    <p><strong>Type:</strong> {{ $appointment->interview_type === 'online' ? 'Online Interview' : 'Face-to-Face' }}</p>
                </div>

                <p>Please make sure to attend on the new schedule.</p>
            @else
                <p>Your reschedule request has been <strong>rejected</strong>.</p>
                
                <div class="info">
                    <h3>Original Appointment Schedule:</h3>
                    <p><strong>Date:</strong> {{ $appointment->appointment_date->format('F d, Y (l)') }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::createFromFormat('H:i', $appointment->appointment_time)->format('h:i A') }}</p>
                    <p><strong>Program:</strong> {{ $appointment->program_type }}</p>
                </div>

                <p>Please attend your original appointment or contact the admin for assistance.</p>
            @endif
        </div>
        
        <div class="footer">
            <p>MSWDO Appointment System</p>
        </div>
    </div>
</body>
</html>
