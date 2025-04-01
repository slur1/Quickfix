<?php
include '../config/db_connection.php';
require '../includes/PHPMailer-master/src/PHPMailer.php';
require '../includes/PHPMailer-master/src/SMTP.php';
require '../includes/PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json'); 

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo json_encode(['error' => 'No email provided.']);
    exit;
}

$verification_code = substr(md5(uniqid(rand(), true)), 0, 6);

$sql = "UPDATE user SET verification_code = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $verification_code, $email);

if ($stmt->execute()) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'quickfix388@gmail.com';
        $mail->Password   = 'cnnw irox iikh cyoe';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('quickfix388@gmail.com', 'QuickFix');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->AltBody = "Please use the following code to verify your email: $verification_code";
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        </head>
        <body style='margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;'>
            <div style='max-width: 600px; margin: 20px auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                <div style='background-color: #1e3a8a; color: #ffffff; text-align: center; padding: 20px; font-size: 24px;'>
                    Verify Your Email Address
                </div>
                <div style='padding: 20px; color: #374151;'>
                    <p>Hello,</p>
                    <p>Thank you for creating your account. Please use the verification code below to verify your email address:</p>
                    <div style='text-align: center; margin: 20px 0;'>
                        <span style='display: inline-block; background-color: #e0f2fe; color: #1e40af; font-weight: bold; font-size: 20px; padding: 10px 20px; border-radius: 8px;'>
                            $verification_code
                        </span>
                    </div>
                    <p>If you did not request this, please ignore this email.</p>
                    <p>Best regards,</p>
                    <p>The Team</p>
                </div>
                <div style='background-color: #1e3a8a; color: #ffffff; text-align: center; padding: 10px; font-size: 14px;'>
                    <p>© 2024 QuickFix. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->send();

        echo json_encode([
            'success' => true,
            'message' => 'Verification code has been resent to your email.'
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['error' => 'Error updating verification code.']);
}

$stmt->close();
$conn->close();
exit;
?>
