<?php
session_start();
include '../config/db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['job_id'], $data['rating'], $data['review'])) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

$job_id = intval($data['job_id']);
$rating = floatval($data['rating']);
$review = trim($data['review']);
$user_id = $_SESSION['user_id'] ?? null; 

if (!$user_id) {
    echo json_encode(["success" => false, "error" => "User not authenticated"]);
    exit;
}

$query = "UPDATE completed_jobs SET rating = ?, review = ? WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("dsii", $rating, $review, $job_id, $user_id);
$success = $stmt->execute();
$stmt->close();

if ($success) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Database error"]);
}
?>
