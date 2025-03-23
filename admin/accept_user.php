<?php
require 'db_connection.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/PHPMailer.php';
require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/SMTP.php';
require_once 'C:/xampp/htdocs/Quickfix/public/includes/PHPMailer-master/src/Exception.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $conn = new mysqli("localhost", "root", "", "quickfix_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT * FROM pending_user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($stmt) {
        $stmt->close();
    }

    if ($user) {
        $email = $user['email'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $password_hash = $user['password_hash'];
        $id_type = $user['id_type'];  
        $id_file_path = $user['id_file_path'];  
        $contact_number = $user['contact_number'];
        $email_verified = $user['email_verified'];  
        $verification_code = $user['verification_code']; 

        $checkStmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo json_encode(["error" => "User already exists."]);
            
            $checkStmt->close();
            $conn->close();
            exit;
        }
        $checkStmt->close(); 

     
        $insertQuery = "INSERT INTO user (first_name, last_name, email, id_type, id_file_path, contact_number, email_verified, verification_code, password_hash, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'approved')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssssss", $first_name, $last_name, $email, $id_type, $id_file_path, $contact_number, $email_verified, $verification_code, $password_hash);
        
        if ($stmt->execute()) {
            $deleteQuery = "DELETE FROM pending_user WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $user_id);
            $deleteStmt->execute();
            $deleteStmt->close(); 
            
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'monicalburo12@gmail.com';  
                $mail->Password = 'elwq qivg ixbg bsur';     
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('your-email@gmail.com', 'QuickFix Admin');
                $mail->addAddress($email);
                $mail->Subject = " Congratulations! Your QuickFix Account Has Been Approved!";

                $mail->Body = "Hello $first_name,\n\n"
                            . "We are pleased to inform you that your account request for QuickFix has been approved! 🎉\n\n"
                            . "You can now log in and start connecting with job opportunities or finding skilled workers to help with your tasks.\n\n"
                            . "🔑 **Login Details:**\n"
                            . "- *Email:* $email\n"
                            . "- *Login Here:* [QuickFix Login Page](http://localhost/Quickfix/public/user/userLogin.php)\n\n"
                            . "To ensure the best experience, please take a moment to set up your profile and explore available features.\n\n"
                            . "If you have any questions, feel free to reach out to our support team.\n\n"
                            . "💡 **Tip:** Keep your contact details updated to receive job alerts and important notifications.\n\n"
                            . "Welcome to QuickFix! We’re excited to have you on board. 🚀\n\n"
                            . "Best regards,\n"
                            . "**QuickFix Team**\n"
                            . "[QuickFix Website](http://localhost/Quickfix/public/index.php)\n";
                
                $mail->send();
                echo json_encode(["Success" => "User approved and email sent successfully!"]);
            } catch (Exception $e) {
                echo json_encode(["Error" => "User approved, but email could not be sent. Error: {$mail->ErrorInfo}"]);
            }
        } else {
            echo json_encode(["error" => "Failed to approve user."]);
        }

        $stmt->close(); 
    } else {
        echo json_encode(["error" => "User not found."]);
    }

    $conn->close();
}
?>
