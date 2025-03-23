<?php
include '../config/db_connection.php';

header('Content-Type: application/json');

$sql = "SELECT id, job_title, location, budget FROM jobs WHERE status NOT IN ('in_progress', 'completed')";
$result = $conn->query($sql);

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}

echo json_encode($jobs);
$conn->close();
?>
