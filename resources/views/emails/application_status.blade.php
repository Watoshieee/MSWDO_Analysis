<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application {{ $status }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7fa; margin: 0; padding: 40px 0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #2C3E8F; padding: 30px; text-align: center;">
                            <h2 style="color: #ffffff; margin: 0; font-size: 24px;">MSWDO Application Update</h2>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                                Dear <strong>{{ $full_name }}</strong>,
                            </p>
                            
                            <p style="font-size: 16px; color: #333333; margin-bottom: 20px; line-height: 1.5;">
                                Your application for <strong>{{ $program }}</strong> has been reviewed. We are writing to inform you that the status of your application is now:
                            </p>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                @if(strtolower($status) === 'approved')
                                    <span style="display: inline-block; padding: 12px 24px; background-color: #d4edda; color: #155724; font-size: 18px; font-weight: bold; border-radius: 30px;">
                                        ✅ APPROVED
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 12px 24px; background-color: #f8d7da; color: #721c24; font-size: 18px; font-weight: bold; border-radius: 30px;">
                                        ❌ REJECTED
                                    </span>
                                @endif
                            </div>
                            
                            @if(strtolower($status) === 'approved')
                                <p style="font-size: 16px; color: #333333; line-height: 1.5; margin-bottom: 20px;">
                                    Congratulations! Your application has been approved. Please wait for further instructions regarding the next steps, or check your MSWDO mobile app for more details.
                                </p>
                            @else
                                <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px 20px; margin-bottom: 25px;">
                                    <h4 style="margin-top: 0; color: #856404; margin-bottom: 10px;">Admin Remarks / Reason for Rejection:</h4>
                                    <p style="margin: 0; color: #333333; font-style: italic; line-height: 1.5;">
                                        "{{ $remarks ?? 'No specific remarks provided.' }}"
                                    </p>
                                </div>
                                
                                <p style="font-size: 16px; color: #333333; line-height: 1.5;">
                                    Please review the remarks above. You may log in to the MSWDO mobile application to correct any issues and resubmit your requirements.
                                </p>
                            @endif
                            
                            <p style="font-size: 16px; color: #333333; margin-top: 40px; margin-bottom: 0;">
                                Best regards,<br>
                                <strong>MSWDO Admin Team</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eeeeee;">
                            <p style="font-size: 12px; color: #999999; margin: 0;">
                                This is an automated message from the Municipal Social Welfare and Development Office.<br>Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
