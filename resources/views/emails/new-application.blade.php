<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Program Application</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f4ff;
            color: #1e293b;
        }

        .wrapper {
            max-width: 600px;
            margin: 32px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(44, 62, 143, 0.10);
        }

        .header {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            padding: 32px 36px 28px;
            text-align: center;
        }

        .header img {
            width: 52px;
            height: 52px;
            margin-bottom: 12px;
        }

        .header h1 {
            color: white;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .header p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.82rem;
            margin-top: 4px;
        }

        .badge-row {
            background: #FDB913;
            padding: 10px 36px;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #1A2A5C;
        }

        .body {
            padding: 32px 36px;
        }

        .greeting {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .intro {
            font-size: 0.88rem;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 24px;
        }

        .detail-box {
            background: #f0f4ff;
            border-radius: 12px;
            border: 1px solid #c7d2fe;
            padding: 20px 24px;
            margin-bottom: 24px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.86rem;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-weight: 600;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 700;
            text-align: right;
        }

        .program-pill {
            display: inline-block;
            background: #2C3E8F;
            color: white;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .status-pill {
            display: inline-block;
            background: #FFF8E1;
            color: #856404;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 0.78rem;
            font-weight: 700;
            border: 1px solid #FFE082;
        }

        .cta-wrap {
            text-align: center;
            margin: 24px 0;
        }

        .cta-btn {
            display: inline-block;
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 10px;
            padding: 14px 32px;
            font-size: 0.92rem;
            font-weight: 800;
            letter-spacing: 0.02em;
        }

        .note {
            font-size: 0.79rem;
            color: #94a3b8;
            line-height: 1.6;
            border-top: 1px solid #e2e8f0;
            padding-top: 18px;
            margin-top: 8px;
        }

        .footer {
            background: #f8fafc;
            padding: 18px 36px;
            text-align: center;
            font-size: 0.74rem;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        {{-- Header --}}
        <div class="header">
            <h1>🔔 New Program Application</h1>
            <p>Municipal Social Welfare &amp; Development Office</p>
        </div>
        <div class="badge-row">{{ strtoupper($application->municipality ?? 'Municipality') }} &mdash; Action Required
        </div>

        {{-- Body --}}
        <div class="body">
            <p class="greeting">Hello, Admin!</p>
            <p class="intro">
                A resident from <strong>{{ $application->municipality }}</strong> has submitted a new program
                application
                through the MSWDO portal. Please review the application at your earliest convenience.
            </p>

            {{-- Application Details --}}
            <div class="detail-box">
                <div class="detail-row">
                    <span class="detail-label">Application #</span>
                    <span class="detail-value">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Program</span>
                    <span class="detail-value"><span class="program-pill">{{ $programLabel }}</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Applicant Name</span>
                    <span class="detail-value">{{ $application->full_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Municipality</span>
                    <span class="detail-value">{{ $application->municipality ?? 'N/A' }}</span>
                </div>
                @if($application->barangay)
                    <div class="detail-row">
                        <span class="detail-label">Barangay</span>
                        <span class="detail-value">{{ $application->barangay }}</span>
                    </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Date Submitted</span>
                    <span class="detail-value">
                        {{ \Carbon\Carbon::parse($application->application_date)->format('F d, Y – h:i A') }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value"><span class="status-pill">PENDING REVIEW</span></span>
                </div>
            </div>

            {{-- CTA --}}
            <div class="cta-wrap">
                <a href="{{ url('/admin/requirements/' . $application->id) }}" class="cta-btn">
                    Review Application &rarr;
                </a>
            </div>

            <p class="note">
                This notification was sent because your admin account is registered under
                <strong>{{ $application->municipality }}</strong> in the MSWDO portal.
            </p>
        </div>

        {{-- Footer --}}
        <div class="footer">
            &copy; {{ date('Y') }} MSWDO &mdash; Municipal Social Welfare &amp; Development Office.<br>
            This is an automated notification. Please do not reply to this email.
        </div>
    </div>
</body>

</html>