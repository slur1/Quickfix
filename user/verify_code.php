<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../config/db_connection.php';

    if (!$conn) {
        echo json_encode(["success" => false, "message" => "Database connection failed."]);
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $verification_code = trim($_POST['verification_code'] ?? '');

    if (empty($email) || empty($verification_code)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT email_verified FROM user WHERE email = ? AND verification_code = ?");
    $stmt->bind_param("ss", $email, $verification_code);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Query failed: " . $stmt->error]);
        exit;
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ((int)$row['email_verified'] === 0) {
            $stmt_update = $conn->prepare("UPDATE user SET email_verified = 1 WHERE email = ? AND verification_code = ?");
            $stmt_update->bind_param("ss", $email, $verification_code);

            if ($stmt_update->execute()) {
                echo json_encode(["success" => true, "message" => "Your account has been verified!"]);
                exit;
            } else {
                echo json_encode(["success" => false, "message" => "Error updating verification status."]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "Email already verified."]);
            exit;
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid verification code."]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}
?>
