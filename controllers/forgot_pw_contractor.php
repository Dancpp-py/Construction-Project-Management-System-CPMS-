<?php
session_start();
    include_once '../config/db.php';
    include_once 'EmailNotification.php';
    header('Content-Type: application/json');
    
  
    $contractor_name = $_POST['contractor_name'];
    $contractor_email = $_POST['contractor_email'];

    if (empty($contractor_email)) {
        echo json_encode(['message' => 'error', 'error' => 'email are required']);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT contractor_id, contractor_name, contractor_email FROM tbl_contractor WHERE contractor_email = ?");
        $stmt->bind_param("s", $contractor_email);
        $stmt->execute();
        $result =$stmt->get_result();
        $contractor = $result->fetch_assoc();

        if(!$contractor) {
            echo json_encode(['message' => 'error', 'error' =>'Account does not exist.']);
            exit;
        }
        
        $temporaryPassword = bin2hex(random_bytes(4)); // Generate a random temporary password
        $hashed_password = password_hash($temporaryPassword, PASSWORD_BCRYPT);
        $stmt->$conn->prepare("UPDATE tbl_contractor SET contractor_password = ? WHERE contractor_email = ?");
        $stmt->bind_param("ss", $hashed_password, $contractor_email);
        $stmt->execute();

        $emailBody = <<<EOT
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>Your New Password</title>
                    </head>
                    <body style="margin:0; padding:0; background-color:#f5f7fa; font-family: Arial, sans-serif;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f7fa; padding:20px;">
                            <tr>
                            <td align="center">
                                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                                <tr>
                                    <td style="background:#28a745; padding:20px; text-align:center;">
                                    <h1 style="margin:0; color:#ffffff; font-size:24px;">Your New Password</h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:30px;">
                                    <p style="font-size:16px; color:#333333; margin-bottom:20px;">
                                        Hi <strong>{$contractor_name}</strong>,
                                    </p>
                                    <p style="font-size:16px; color:#333333; line-height:1.5; margin-bottom:20px;">
                                        As requested, here is your new temporary password. Please log in and change it immediately for security reasons.
                                    </p>
                                    <div style="background:#f8f9fa; padding:20px; border-radius:6px; text-align:center; border:2px dashed #28a745;">
                                        <p style="font-size:14px; color:#666666; margin:0 0 10px 0;">Your temporary password:</p>
                                        <p style="font-size:18px; font-weight:bold; color:#28a745; margin:0; font-family: monospace;">{$temporaryPassword}</p>
                                    </div>
                                    <p style="text-align:center; margin:30px 0;">
                                        <a href="http://localhost/payment_demo/index.php" 
                                        style="display:inline-block; padding:12px 24px; font-size:16px; font-weight:bold; 
                                                color:#ffffff; background:#28a745; text-decoration:none; border-radius:6px;">
                                        Log In Now
                                        </a>
                                    </p>
                                    <div style="background:#fff3cd; padding:15px; border-radius:6px; border-left:4px solid #ffc107;">
                                        <p style="font-size:14px; color:#856404; margin:0;">
                                            <strong>Security Notice:</strong> For your security, please change this password immediately after logging in.
                                        </p>
                                    </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="background:#f0f0f0; text-align:center; padding:15px; font-size:12px; color:#888888;">
                                    &copy; 2023 Your Company. All rights reserved.
                                    </td>
                                </tr>
                                </table>
                            </td>
                            </tr>
                        </table>
                    </body>
                </html>
            EOT;

        EmailNotification::sendEmail("FORGOT PASSWORD", $emailBody, 'NEW PASSWORD', $contractor_email);
        echo json_encode(['message' => 'SUCCESS']);

    } catch(Exception $e) {
        echo json_encode([
            'success'=> false,
            'message' => 'Error',
            'error' => 'Faied to send new temporary password'
        ]);
    }
    
    exit;