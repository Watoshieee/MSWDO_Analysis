<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requirements Review Update</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4ff; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(44,62,143,0.10); }
        .header { background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%); padding: 32px 36px 28px; text-align: center; }
        .header h1 { color: white; font-size: 1.35rem; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.75); font-size: 0.82rem; margin-top: 4px; }
        .badge-approved { padding: 11px 36px; background: #d4edda; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #155724; border-bottom: 1px solid #e2e8f0; }
        .badge-rejected  { padding: 11px 36px; background: #f8d7da; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #721c24; border-bottom: 1px solid #e2e8f0; }
        .badge-review    { padding: 11px 36px; background: #fff8e1; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: #856404; border-bottom: 1px solid #e2e8f0; }
        .body { padding: 32px 36px; }
        .greeting { font-size: 1rem; font-weight: 700; margin-bottom: 10px; }
        .intro { font-size: 0.88rem; color: #475569; line-height: 1.7; margin-bottom: 24px; }
        .files-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 0.84rem; }
        .files-table th { background: #f0f4ff; color: #374151; font-weight: 700; padding: 10px 14px; text-align: left; border-bottom: 2px solid #c7d2fe; }
        .files-table td { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .files-table tr:last-child td { border-bottom: none; }
        .pill-approved { background: #d4edda; color: #155724; border-radius: 20px; padding: 3px 11px; font-size: 0.74rem; font-weight: 700; }
        .pill-rejected  { background: #f8d7da; color: #721c24; border-radius: 20px; padding: 3px 11px; font-size: 0.74rem; font-weight: 700; }
        .pill-pending   { background: #fff8e1; color: #856404; border-radius: 20px; padding: 3px 11px; font-size: 0.74rem; font-weight: 700; }
        .cta-btn { display: inline-block; background: linear-gradient(135deg, #2C3E8F, #1A2A5C); color: white !important; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 0.9rem; margin: 8px 0 20px; }
        .warning-box { background: #fff8e1; border-left: 4px solid #FDB913; border-radius: 8px; padding: 12px 16px; font-size: 0.83rem; color: #856404; margin-bottom: 20px; line-height: 1.6; }
        .success-box { background: #d4edda; border-left: 4px solid #28a745; border-radius: 8px; padding: 12px 16px; font-size: 0.83rem; color: #155724; margin-bottom: 20px; line-height: 1.6; }
        .note { font-size: 0.79rem; color: #94a3b8; line-height: 1.6; border-top: 1px solid #e2e8f0; padding-top: 18px; margin-top: 8px; }
        .footer { background: #f8fafc; padding: 18px 36px; text-align: center; font-size: 0.74rem; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        @if($overallStatus === 'approved')
            <h1>✅ Requirements Approved!</h1>
        @elseif($overallStatus === 'rejected')
            <h1>❌ Some Requirements Need Attention</h1>
        @else
            <h1>📋 Requirements Under Review</h1>
        @endif
        <p>Municipal Social Welfare and Development Office</p>
    </div>

    @if($overallStatus === 'approved')
        <div class="badge-approved">✅ All Requirements Approved — ID Processing Will Begin Soon</div>
    @elseif($overallStatus === 'rejected')
        <div class="badge-rejected">❌ Action Required — Some Documents Were Declined</div>
    @else
        <div class="badge-review">🔍 Review In Progress</div>
    @endif

    <div class="body">
        <p class="greeting">Hello, {{ $fileMonitoring->application->user->full_name ?? 'Applicant' }}!</p>
        <p class="intro">
            @if($overallStatus === 'approved')
                Great news! All of your submitted documents for the <strong>Solo Parent ID</strong> application have been reviewed and <strong>approved</strong>. Your ID will now be processed by the MSWDO office.
            @elseif($overallStatus === 'rejected')
                The MSWDO office has reviewed your submitted documents for the <strong>Solo Parent ID</strong> application. Some documents require your attention. Please review the details below and resubmit the declined documents.
            @else
                Your submitted documents for the <strong>Solo Parent ID</strong> application are currently under review. We will notify you once the review is complete.
            @endif
        </p>

        @if($overallStatus === 'approved')
        <div class="success-box">
            🎉 <strong>Congratulations!</strong> Your Solo Parent ID application is now being processed. You will be contacted when your ID is ready for pickup.
        </div>
        @endif

        {{-- File Status Table --}}
        <table class="files-table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Status</th>
                    <th>Admin Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fileUploads as $file)
                <tr>
                    <td>{{ $file->requirement_name }}</td>
                    <td>
                        @if($file->status === 'approved')
                            <span class="pill-approved">✅ Approved</span>
                        @elseif($file->status === 'rejected')
                            <span class="pill-rejected">❌ Declined</span>
                        @else
                            <span class="pill-pending">⏳ Pending</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:0.82rem;">{{ $file->admin_remarks ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($overallStatus === 'rejected')
        <div class="warning-box">
            ⚠️ <strong>Action Required:</strong> Please resubmit the declined documents. Make sure they are clear, readable, and complete. Log in to your account to resubmit.
        </div>
        <div style="text-align:center;margin-bottom:16px;">
            <a href="{{ url('/user/my-requirements') }}" class="cta-btn">🔄 Resubmit Documents</a>
        </div>
        @elseif($overallStatus === 'approved')
        <div style="text-align:center;margin-bottom:16px;">
            <a href="{{ url('/user/dashboard') }}" class="cta-btn">🏠 Go to Dashboard</a>
        </div>
        @endif

        <p class="note">
            This is an automated notification from the MSWDO System. Please do not reply to this email directly.<br>
            For assistance, visit the MSWDO office at Municipal Hall, Ground Floor, Monday–Friday, 8:00 AM–5:00 PM.
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} Municipal Social Welfare and Development Office &nbsp;|&nbsp; Automated Notification
    </div>
</div>
</body>
</html>
