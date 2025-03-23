<?php
session_start(); // Start the session
include '../config/db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : null;
$provider_id = $_SESSION['user_id']; // Ensure the user is logged in

if (!$job_id) {
    echo json_encode(["error" => "Missing job_id parameter"]);
    exit;
}

$query = $conn->prepare("SELECT id FROM offers WHERE job_id = ? AND provider_id = ?");
$query->bind_param("ii", $job_id, $provider_id);
$query->execute();
$result = $query->get_result();
$hasOffer = $result->num_rows > 0;

echo json_encode(['hasOffer' => $hasOffer]);

$query->close();
$conn->close();
?>
