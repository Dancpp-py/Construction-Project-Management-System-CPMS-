<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../assets/vendor/autoload.php';
ini_set('display_errors', 0);

class EmailNotification{
    public static function sendEmail($subject, $body, $email_title, $email){
        $recipient = $email;
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'cpms.constructions@gmail.com';  // change this to your email
            $mail->Password = 'vook osfd qfgq pghx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
    
            $mail->setFrom('cpms.constructions@gmail.com', $email_title); // change this to your email
            $mail->addAddress($recipient);
    
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
    
            $mail->send();
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send email.',
                'error' => $mail->ErrorInfo
            ]);
        }
    }
}