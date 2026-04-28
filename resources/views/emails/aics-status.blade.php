<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICS Status Update</title>
    <style>
        body { margin: 0; padding: 0; background: #f0f4ff; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrap { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 6px 24px rgba(44,62,143,.12); }
        .head { background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: #fff; padding: 30px; text-align: center; }
        .head h1 { margin: 0; font-size: 1.35rem; font-weight: 800; }
        .body { padding: 24px 28px; color: #334155; }
        .box { background: #eef2ff; border-left: 4px solid #2C3E8F; border-radius: 10px; padding: 12px 14px; margin-top: 14px; font-size: .9rem; line-height: 1.6; }
        .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px; color: #94a3b8; font-size: .75rem; text-align: center; }
    </style>
</head>
<body>
@php
    $programLabel = match($application->program_type) {
        'AICS_Medical' => 'AICS Medical Assistance',
        'AICS_Burial'  => 'AICS Burial Assistance',
        default        => 'AICS Assistance',
    };
    $isReady = $stage === 'ready_for_pickup';
@endphp
<div class="wrap">
    <div class="head">
        <h1>{{ $isReady ? '🎁 Grant Ready for Claiming' : '✅ Requirements Validated' }}</h1>
    </div>
    <div class="body">
        <p>Hello {{ $user->full_name ?? $application->full_name }},</p>
        <p>
            @if($isReady)
                Your <strong>{{ $programLabel }}</strong> assistance is now ready for claiming at your MSWDO office.
            @else
                Your submitted requirements for <strong>{{ $programLabel }}</strong> have been validated by MSWDO.
            @endif
        </p>
        <div class="box">
            Application #{{ $application->id }}<br>
            Program: {{ $programLabel }}<br>
            Status:
            @if($isReady)
                Claim grant ready for pickup
            @else
                Validated - waiting claim release
            @endif
        </div>
    </div>
    <div class="foot">This is an automated notification from MSWDO.</div>
</div>
</body>
</html>
