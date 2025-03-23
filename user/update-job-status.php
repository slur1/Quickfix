<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config/db_connection.php';

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);


if (!$data || !isset($data['jobId']) || !isset($data['status'])) {
    echo json_encode(["success" => false, "message" => "Invalid request data"]);
    exit;
}

$jobId = (int) $data['jobId']; 
$newStatus = $data['status'];

$allowedStatuses = ['open', 'in_progress', 'completed', 'cancelled'];
if (!in_array($newStatus, $allowedStatuses)) {
    echo json_encode(["success" => false, "message" => "Invalid status"]);
    exit;
}

$conn->begin_transaction();

try {
    $stmt1 = $conn->prepare("SELECT job_id FROM in_progress_jobs WHERE id = ?");
    $stmt1->bind_param("i", $jobId);
    $stmt1->execute();
    $result = $stmt1->get_result();
    $stmt1->close();

    if ($result->num_rows === 0) {
        throw new Exception("Job ID not found in in_progress_jobs table");
    }

    $row = $result->fetch_assoc();
    $realJobId = $row['job_id']; 

    $stmt2 = $conn->prepare("UPDATE jobs SET status = ? WHERE id = ?");
    $stmt2->bind_param("si", $newStatus, $realJobId);

    if (!$stmt2->execute()) {
        throw new Exception("Error updating jobs table: " . $stmt2->error);
    }
    $stmt2->close();

    $stmt3 = $conn->prepare("UPDATE in_progress_jobs SET status = ? WHERE id = ?");
    $stmt3->bind_param("si", $newStatus, $jobId);

    if (!$stmt3->execute()) {
        throw new Exception("Error updating in_progress_jobs table: " . $stmt3->error);
    }
    $stmt3->close();

    $conn->commit();

    echo json_encode(["success" => true, "message" => "Job status updated successfully"]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
