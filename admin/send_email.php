<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/PHPMailer.php';
require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/SMTP.php';
require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['firstName'], $_POST['reason'])) {
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $reason = $_POST['reason'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'monicalburo12@gmail.com';
        $mail->Password = 'elwq qivg ixbg bsur';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->SMTPKeepAlive = true; 

        $mail->setFrom('no-reply@quickfix.com', 'QuickFix Team');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Application Rejected - QuickFix";
        $mail->Body = "<html>...Email Body...</html>";

        $mail->send();
        echo "Email sent successfully.";
    } catch (Exception $e) {
        echo "Error sending email: " . $mail->ErrorInfo;
    }
}
?>
