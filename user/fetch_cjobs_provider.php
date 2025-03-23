<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}


include '../config/db_connection.php';

$provider_id = $_SESSION['user_id'];

try {
    
    $sql = "SELECT 
                cj.id AS completed_job_id,
                cj.job_id,
                cj.offer_id,
                cj.provider_id,
                cj.user_id,
                cj.job_title,
                cj.location,
                cj.description,
                cj.offer_amount,
                cj.completion_time,
                cj.completed_at,
                cj.rating,
                cj.review,
                CONCAT(u.first_name, ' ', u.last_name) AS employer_name
            FROM completed_jobs cj
            JOIN user u ON cj.user_id = u.id  
            WHERE cj.provider_id = ?  
            ORDER BY cj.completed_at DESC";  

    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("i", $provider_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $completed_jobs = $result->fetch_all(MYSQLI_ASSOC);

    
    echo json_encode($completed_jobs);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>
