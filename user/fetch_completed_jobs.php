<?php
header('Content-Type: application/json');
include '../config/db_connection.php';


session_start();
$user_id = $_SESSION['user_id']; 

$sql = "SELECT cj.id, cj.job_id, cj.job_title, cj.location, cj.description, 
               cj.offer_amount, cj.completion_time, cj.completed_at, 
               cj.provider_id, cj.user_id, cj.rating, cj.review,
               CONCAT(u.first_name, ' ', u.last_name) AS assigned_to
        FROM completed_jobs cj
        JOIN user u ON cj.provider_id = u.id
        WHERE cj.user_id = ? 
        ORDER BY cj.completed_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); 
$stmt->execute();
$result = $stmt->get_result();

$completed_jobs = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $completed_jobs[] = $row;
    }
}

echo json_encode($completed_jobs);

$stmt->close();
$conn->close();
?>
