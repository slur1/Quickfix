<?php
session_start();
include '../config/db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/PHPMailer.php';
require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/SMTP.php';
require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['reason'])) {
    $userId = (int) $_POST['user_id'];
    $rejectionReason = $conn->real_escape_string($_POST['reason']);

    try {
        $sql = "INSERT INTO rejected_user (first_name, last_name, email, id_type, id_file_path, contact_number, password_hash, created_at, rejection_reason) 
                SELECT first_name, last_name, email, id_type, id_file_path, contact_number, password_hash, created_at, ? 
                FROM pending_user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $rejectionReason, $userId);
        $stmt->execute();
        $stmt->close();

        $query = "SELECT email, first_name FROM pending_user WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($email, $firstName);
        $stmt->fetch();
        $stmt->close();

        $sql = "DELETE FROM pending_user WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();

        if (sendRejectionEmail($email, $firstName, $rejectionReason)) {
            echo "User rejected and email sent.";
        } else {
            echo "User rejected but email failed to send.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function sendRejectionEmail($email, $firstName, $reason) {
    $mail = new PHPMailer(true);
    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'monicalburo12@gmail.com'; 
        $mail->Password = 'elwq qivg ixbg bsur'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        

        $mail->setFrom('no-reply@quickfix.com', 'QuickFix Team');
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = "Application Rejected - QuickFix";
        $mail->Body = "
            <html>
            <head>
                <title>Application Rejected</title>
            </head>
            <body style='font-family: Arial, sans-serif; color: #333;'>
                <p>Dear $firstName,</p>
                <p>We regret to inform you that your application for <strong>QuickFix</strong> has been rejected due to the following reason:</p>
                <blockquote style='background:#f8d7da; padding:10px; border-left:4px solid #dc3545; font-style: italic;'>$reason</blockquote>
        
                <p>If you believe this was a mistake or would like to correct the issue, you may reapply by ensuring that all required documents are clear and accurate.</p>
        
                <p>If you need further assistance or have any questions, feel free to contact our support team at <a href='mailto:support@quickfix.com'>support@quickfix.com</a>.</p>
        
                <p>We appreciate your interest in QuickFix and encourage you to apply again.</p>
        
                <p>Best regards,<br><strong>QuickFix Team</strong></p>
            </body>
            </html>
        ";
        
        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
?>
