<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="margin: 0; padding 0; font-family: sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                
                <table width="600" cellpadding="0" cellspacing="0" border="0" 
                style="border-radius: 10px; color: #e0e0e0;  background: #1c1e25ff; overflow: hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="color: #e0e0e0; background: #1f2937; padding: 20px; text-align: center;">
                            <h2 style="margin: 0;">Verify Your Email</h2>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <h3 style="margin-top: 0;">Hi, <strong>${admin_name}</strong></h3>
                            <p style="line-height: 1.5;">Thank you for signing up! Please verify your email address by clicking the button below!</p>

                            <!-- Button -->
                            <table cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td align="center" style="background: #5b8fda; border-radius:6px;">
                                        <a href="${verificationLink}" style="display:inline-block; padding: 10px 20px; font-weight:bold; 
                                        color:#ffffff; text-decoration:none; ">
                                            Verify Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:14px; color:#8a98b8; line-height:1.5; text-align: center;">
                                If the button above doesn't work, copy and paste this link into your browser:
                                <br>
                                <a href="{$verificationLink}" style="color:#5b8fda;">{$verificationLink}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 15px; background-color: #2c3a59; color: #e0e0e0;">
                            <p style="margin: 0;">
                                &copy; 2025 <strong>Construction Project Management System</strong>
                                <br>
                                <small>All rights reserved.</small>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>