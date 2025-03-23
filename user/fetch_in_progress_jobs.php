<?php
session_start(); 
require_once '../config/db_connection.php';

header('Content-Type: application/json');

if (!isset($conn) || !$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}


if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized access."]);
    exit;
}

$user_id = intval($_SESSION['user_id']); 

$sql = "
    SELECT 
        ipj.id, 
        ipj.job_title, 
        ipj.job_date, 
        ipj.job_time,
        ipj.offer_amount, 
        CONCAT(u.first_name, ' ', u.last_name) AS assigned_to, 
        ipj.status 
    FROM in_progress_jobs ipj
    LEFT JOIN user u ON ipj.provider_id = u.id
    WHERE ipj.user_id = ?  -- Only fetch jobs for the logged-in user
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$jobs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $jobs[] = $row;
}

mysqli_close($conn);

echo json_encode($jobs ?: []);
?>
