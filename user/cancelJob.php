<?php
include '../config/db_connection.php';
header('Content-Type: application/json');
session_start(); // Ensure the session is started

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id']; // Store user_id after session check
error_log("User ID: " . $user_id); // Log the user_id

// Read the input data
$data = json_decode(file_get_contents("php://input"), true);
error_log("Received Data (Decoded): " . print_r($data, true)); // Log the decoded received data

// Get job_id from the request
$job_id = isset($data['job_id']) ? intval(trim($data['job_id'])) : 0;

// Log the job_id
error_log("Job ID: " . $job_id);

// Validate job_id
if ($job_id <= 0) {
    echo json_encode(["success" => false, "error" => "Invalid job ID"]);
    exit;
}

// Check if the job exists and belongs to the logged-in user OR if the user is the chosen worker
$stmt = $conn->prepare("SELECT id, user_id, chosen_worker_id, status FROM jobs WHERE id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();
$stmt->close();

// Log the fetched job data
if ($job) {
    error_log("Job Found: " . print_r($job, true)); // Log job details if found
} else {
    error_log("Job not found for Job ID: " . $job_id); // Log job not found
}

// Check authorization
if (!$job) {
    echo json_encode(["success" => false, "error" => "Job not found"]);
    exit;
}

if ($job['user_id'] !== $user_id && $job['chosen_worker_id'] !== $user_id) {
    echo json_encode(["success" => false, "error" => "Not authorized to cancel this job"]);
    exit;
}

// Determine cancellation reason (who is cancelling)
$cancelled_by = $user_id;
$cancelled_at = date('Y-m-d H:i:s');

// Update the job with status, cancelled_by, and cancelled_at
$update_stmt = $conn->prepare("UPDATE jobs SET status = 'cancelled', cancelled_by = ?, cancelled_at = ? WHERE id = ?");
$update_stmt->bind_param("isi", $cancelled_by, $cancelled_at, $job_id);

if ($update_stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Job successfully cancelled"]);
} else {
    echo json_encode(["success" => false, "error" => "Database error: " . $update_stmt->error]);
}

// Close statements and connection
$update_stmt->close();
$conn->close();
?>
