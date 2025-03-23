<?php
include '../config/db_connection.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "User ID not provided"]);
    exit;
}

$user_id = intval($_GET['user_id']);

$stmt = $conn->prepare("SELECT verification_status FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($user) {
    echo json_encode(["verification_status" => $user['verification_status']]);
} else {
    echo json_encode(["verification_status" => "not_verified"]);
}
?>
