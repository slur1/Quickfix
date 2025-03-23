<?php
header('Content-Type: application/json'); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/db_connection.php';

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Log input data
file_put_contents("debug_log.txt", print_r($data, true), FILE_APPEND);

// Validate input
$job_id = $data['job_id'] ?? null;
$rating = $data['rating'] ?? null;
$review = $data['review'] ?? null;

if (!$job_id || !$rating || !$review) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

// Update database
$stmt = $conn->prepare("UPDATE completed_jobs SET rating = ?, review = ? WHERE id = ?");
$stmt->bind_param("dsi", $rating, $review, $job_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Review submitted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Database update failed."]);
}

$stmt->close();
$conn->close();
?>
