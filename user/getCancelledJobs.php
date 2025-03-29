<?php
include '../config/db_connection.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$cancelled_jobs = [];
$sql = "
    SELECT 
        jobs.id, 
        jobs.job_title, 
        COALESCE(jobs.job_date, 'No Date Available') AS job_date,
        COALESCE(NULLIF(jobs.job_time, ''), 'No Time Available') AS job_time,
        jobs.location, 
        jobs.description, 
        jobs.budget, 
        jobs.status, 
        jobs.cancelled_at,
        jobs.cancelled_by,
        jobs.chosen_worker_id,
        employer.first_name AS employer_first_name,
        employer.last_name AS employer_last_name,
        canceller.first_name AS canceller_first_name,
        canceller.last_name AS canceller_last_name,
        worker.first_name AS worker_first_name,
        worker.last_name AS worker_last_name
    FROM jobs
    LEFT JOIN user AS employer ON jobs.user_id = employer.id
    LEFT JOIN user AS canceller ON jobs.cancelled_by = canceller.id
    LEFT JOIN user AS worker ON jobs.chosen_worker_id = worker.id
    WHERE (jobs.user_id = ? OR jobs.chosen_worker_id = ?) 
    AND jobs.status = 'cancelled'
    ORDER BY jobs.cancelled_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cancelled_jobs[] = $row;
}
$stmt->close();
$conn->close();

echo json_encode($cancelled_jobs);
?>
