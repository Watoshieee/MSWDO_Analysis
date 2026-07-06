<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New User Registration – Valid ID Review</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:24px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #dddddd;max-width:600px;width:100%;">

                <!-- Header -->
                <tr>
                    <td style="background:#2C3E8F;padding:28px 32px;">
                        <p style="margin:0;font-size:18px;font-weight:bold;color:#ffffff;">MSWDO Member Portal</p>
                        <p style="margin:4px 0 0;font-size:13px;color:#c7d2fe;">Municipal Social Welfare and Development Office</p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:32px;">
                        <p style="margin:0 0 16px;font-size:15px;color:#1e293b;">Hello Admin,</p>

                        <p style="margin:0 0 16px;font-size:14px;color:#475569;line-height:1.6;">
                            A new user has registered in <strong style="color:#1e293b;">{{ $newUser->municipality }}</strong>
                            and completed email verification. Their uploaded valid ID is waiting for your review
                            before the account can be activated.
                        </p>

                        <!-- User Details Table -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;margin-bottom:24px;">
                            <tr style="background:#f8fafc;">
                                <td colspan="2" style="padding:10px 16px;font-size:11px;font-weight:bold;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #e2e8f0;">
                                    User Details
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 16px;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;width:40%;">Full Name</td>
                                <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:bold;border-bottom:1px solid #f1f5f9;">{{ $newUser->full_name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 16px;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;">Email</td>
                                <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:bold;border-bottom:1px solid #f1f5f9;">{{ $newUser->email }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 16px;font-size:13px;color:#64748b;border-bottom:1px solid #f1f5f9;">Barangay</td>
                                <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:bold;border-bottom:1px solid #f1f5f9;">{{ $newUser->barangay ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 16px;font-size:13px;color:#64748b;">Municipality</td>
                                <td style="padding:10px 16px;font-size:13px;color:#1e293b;font-weight:bold;">{{ $newUser->municipality }}</td>
                            </tr>
                        </table>

                        <!-- CTA -->
                        <table cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background:#2C3E8F;padding:12px 28px;">
                                    <a href="{{ $reviewUrl }}" style="color:#ffffff;font-size:14px;font-weight:bold;text-decoration:none;display:inline-block;">
                                        Review Valid ID
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;font-size:13px;color:#94a3b8;line-height:1.6;border-top:1px solid #e2e8f0;padding-top:16px;">
                            This is an automated message from the MSWDO System.<br>
                            Log in to the admin Users Management page to approve or decline the registration.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f8fafc;padding:16px 32px;border-top:1px solid #e2e8f0;">
                        <p style="margin:0;font-size:12px;color:#94a3b8;text-align:center;">
                            &copy; {{ date('Y') }} Municipal Social Welfare and Development Office
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
