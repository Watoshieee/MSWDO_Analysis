<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cancellation {{ ucfirst($response) }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #2C3E8F;">Cancellation Request {{ ucfirst($response) }}</h2>
        
        @if($response === 'approved')
            <p>Your cancellation request has been <strong>approved</strong>.</p>
            <p>Your appointment for <strong>{{ $appointment->program_type }}</strong> on <strong>{{ $appointment->formatted_date }}</strong> at <strong>{{ $appointment->formatted_time }}</strong> has been cancelled.</p>
            <p>You may book a new appointment if needed.</p>
        @else
            <p>Your cancellation request has been <strong>rejected</strong>.</p>
            <p>Your appointment for <strong>{{ $appointment->program_type }}</strong> on <strong>{{ $appointment->formatted_date }}</strong> at <strong>{{ $appointment->formatted_time }}</strong> remains active.</p>
            @if($appointment->cancellation_admin_notes)
                <p><strong>Admin Notes:</strong> {{ $appointment->cancellation_admin_notes }}</p>
            @endif
        @endif
        
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ddd;">
        <p style="font-size: 0.9em; color: #666;">
            Thank you,<br>
            <strong>MSWDO Team</strong>
        </p>
    </div>
</body>
</html>
