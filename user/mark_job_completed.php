<?php
session_start();
include '../config/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$job_id = $data['job_id'];

if (!$job_id) {
    echo json_encode(["success" => false, "error" => "Invalid Job ID"]);
    exit;
}

$query = $conn->prepare("SELECT * FROM in_progress_jobs WHERE id = ?");
$query->bind_param("i", $job_id);
$query->execute();
$result = $query->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    echo json_encode(["success" => false, "error" => "Job not found"]);
    exit;
}

$insert = $conn->prepare("INSERT INTO completed_jobs 
    (job_id, offer_id, provider_id, user_id, job_title, location, description, offer_amount, completion_time, completed_at, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'completed')");

$insert->bind_param(
    "iiiisssds", 
    $job['job_id'], 
    $job['offer_id'], 
    $job['provider_id'], 
    $job['user_id'], 
    $job['job_title'], 
    $job['location'], 
    $job['description'], 
    $job['offer_amount'], 
    $job['completion_time']
);

if ($insert->execute()) {
    $update_job_status = $conn->prepare("UPDATE jobs SET status = 'completed' WHERE id = ?");
    $update_job_status->bind_param("i", $job['job_id']);
    $update_job_status->execute();

    $delete = $conn->prepare("DELETE FROM in_progress_jobs WHERE id = ?");
    $delete->bind_param("i", $job_id);
    $delete->execute();

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Error moving job to completed_jobs"]);
}
?>
