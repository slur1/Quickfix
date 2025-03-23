<?php
session_start();
include '../config/db_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $job_id = $data['job_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null; 

    if (!$job_id || !$user_id) {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
        exit;
    }

    // Delete the offer
    $stmt = $conn->prepare("DELETE FROM offers WHERE job_id = ? AND provider_id = ?");
    $stmt->bind_param("ii", $job_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }

    $stmt->close();
    $conn->close();
}
?>
