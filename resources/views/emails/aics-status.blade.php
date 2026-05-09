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
        .head.eligible { background: linear-gradient(135deg, #15803d, #052e16); }
        .head.ready    { background: linear-gradient(135deg, #16a34a, #064e3b); }
        .head h1 { margin: 0; font-size: 1.35rem; font-weight: 800; }
        .body { padding: 24px 28px; color: #334155; }
        .box { background: #eef2ff; border-left: 4px solid #2C3E8F; border-radius: 10px; padding: 12px 14px; margin-top: 14px; font-size: .9rem; line-height: 1.6; }
        .box.green { background: #f0fdf4; border-color: #16a34a; }
        .foot { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 14px 20px; color: #94a3b8; font-size: .75rem; text-align: center; }
        .cta { display:inline-block;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;text-decoration:none;border-radius:10px;padding:11px 28px;font-weight:800;font-size:.9rem;margin-top:18px; }
    </style>
</head>
<body>
@php
    $programLabel = match($application->program_type) {
        'AICS_Medical' => 'AICS Medical Assistance',
        'AICS_Burial'  => 'AICS Burial Assistance',
        default        => 'AICS Assistance',
    };
    $isReady     = $stage === 'ready_for_pickup';
    $isEligible  = $stage === 'validated';
@endphp
<div class="wrap">
    <div class="head {{ $isReady ? 'ready' : ($isEligible ? 'eligible' : '') }}">
        <h1>
            @if($isReady)       🎁 Grant Ready for Claiming
            @elseif($isEligible) 🏆 You Are Eligible!
            @else               ✅ Requirements Validated
            @endif
        </h1>
    </div>
    <div class="body">
        <p>Hello <strong>{{ $user->full_name ?? $application->full_name }}</strong>,</p>
        <p>
            @if($isReady)
                Your <strong>{{ $programLabel }}</strong> assistance is now ready for claiming at your MSWDO office. Please bring a valid ID when you visit.
            @elseif($isEligible)
                Congratulations! You passed the eligibility assessment for <strong>{{ $programLabel }}</strong>. Please log in to the MSWDO portal and submit your requirements to proceed.
            @else
                Your submitted requirements for <strong>{{ $programLabel }}</strong> have been fully validated by MSWDO. Please wait for the grant release notice.
            @endif
        </p>
        <div class="box {{ $isReady || $isEligible ? 'green' : '' }}">
            @if($application->id) Application #{{ $application->id }}<br>@endif
            Program: {{ $programLabel }}<br>
            Municipality: {{ $application->municipality }}<br>
            Status:
            @if($isReady) Grant ready for pickup
            @elseif($isEligible) Eligible — awaiting requirement submission
            @else Requirements validated — awaiting grant release
            @endif
        </div>
        <div style="text-align:center;">
            <a href="{{ url('/user/dashboard') }}" class="cta">View My Applications →</a>
        </div>
    </div>
    <div class="foot">This is an automated notification from the {{ $application->municipality }} MSWDO. Please do not reply.</div>
</div>
</body>
</html>
