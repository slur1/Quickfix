<?php
session_start();
include '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || empty($data['id'])) {
    echo json_encode(["success" => false, "error" => "Missing required job ID"]);
    exit;
}

$job_id = intval($data['id']);

$sql = "SELECT sender_id, receiver_id, message AS content, image, created_at 
        FROM chat_messages WHERE job_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = [
        "id" => $row["sender_id"] . "_" . strtotime($row["created_at"]), 
        "sender_id" => $row["sender_id"],
        "receiver_id" => $row["receiver_id"],
        "content" => $row["content"],  
        "image" => $row["image"] ? $row["image"] : null,  
        "created_at" => date("M d, Y h:i A", strtotime($row["created_at"]))  
    ];
}

$stmt->close();
$conn->close();

echo json_encode(["success" => true, "messages" => $messages]);
?>
