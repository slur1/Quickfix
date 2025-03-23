<?php
session_start(); // Ensure session is started
include '../config/db_connection.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$status = $data["status"] ?? null;
$userId = $_SESSION["user_id"] ?? null; // Get user ID from session

// Debugging: Log request
file_put_contents("debug_log.txt", "UserID: $userId, Status: $status\n", FILE_APPEND);

// Check if user is logged in and status is valid
if (!$userId) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

if (!in_array($status, ["unverified", "identity_verified", "fully_verified"])) {
    echo json_encode(["success" => false, "error" => "Invalid status"]);
    exit;
}

// Prepare and execute SQL update
$sql = "UPDATE user SET verification_status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $userId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
