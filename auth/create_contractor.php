<?php
session_start();
include_once '../config/db.php';
include_once '../controllers/EmailNotification.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(["success" => false, "error" => "Admin access required"]);
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $contractor_name = trim(htmlspecialchars($_POST['contractor_name']));
    $contractor_email = trim(htmlspecialchars($_POST['contractor_email']));
    $contractor_password = htmlspecialchars($_POST['contractor_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    //https://www.w3schools.com/php/php_form_url_email.asp = FILTERING EMAIL
    //https://www.w3schools.com/php/func_string_strlen.asp = PASSWORD VALIDATION

    if(empty($contractor_name) || empty($contractor_email) || empty($contractor_password) || empty($confirm_password)){
        $response['status']  = 'error';
        $response['message'] = "All fields are required!";
    } else if (!filter_var($contractor_email, FILTER_VALIDATE_EMAIL)){
        $response['status']  = 'error';
        $response['message'] = "Invalid email format!";
    } else if (strlen($contractor_name) < 3) {
        $response['status']  = 'error';
        $response['message'] = "Name must be at least 3 characters long!";
    } else if (strlen($contractor_password) < 8) {
        $response['status']  = 'error';
        $response['message'] = "Password must be at least 8 characters long!";
    } else if ($contractor_password != $confirm_password) {
        $response['status']  = 'error';
        $response['message'] = "Password do not match!";
    } else {
        // CHECK IF THERE'S A DUPLICATE EMAIL
        $stmt = $conn->prepare("SELECT * FROM tbl_contractor WHERE contractor_email = ?");
        $stmt->bind_param("s", $contractor_email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0) {
            $response['status']  = 'error';
            $response['message'] = "Email is already registered!";
        } else {
            $hashed_password = password_hash($contractor_password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO tbl_contractor (contractor_name, contractor_email, contractor_password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss",$contractor_name, $contractor_email, $hashed_password);

            if($stmt->execute()){
                $verificationLink = "http://localhost/CPMS/verify_success.php";
                $emailBody = <<<EOT
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
                
                                <table width="600" cellpadding="0" cellspacing="0" border="0" style="border-radius: 10px; color: #e0e0e0;  background: #1c1e25ff; overflow: hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                                    <!-- Header -->
                                    <tr>
                                        <td style="color: #e0e0e0; background: #1f2937; padding: 20px; text-align: center;">
                                            <h2 style="margin: 0;">Verify Your Email</h2>
                                        </td>
                                    </tr>

                                    <!-- Body -->
                                    <tr>
                                        <td style="padding: 30px;">
                                            <h3 style="margin-top: 0;">Hi, <strong>{$contractor_name}</strong></h3>
                                            <p style="line-height: 1.5;">Thank you for signing up! Please verify your email address by clicking the button below!</p>

                                            <!-- Button -->
                                            <table cellpadding="0" cellspacing="0" align="center">
                                                <tr>
                                                    <td align="center" style="background: #5b8fda; border-radius:6px;">
                                                        <a href="{$verificationLink}" style="display:inline-block; padding: 10px 20px; font-weight:bold; 
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
                EOT;
                $contractor_id = $stmt->insert_id;

                $response['status']   = 'success';
                $response['message']  = "Please check your email to verify your account!";
            } else {
                $response['status']   = 'error';
                $response['message']  = "Error: ".$stmt->error;
            }
        }
    }

    EmailNotification::sendEmail("CPMS", $emailBody, 'Verify Your Email', $contractor_email);
    echo json_encode($response);
    exit;
}
?>