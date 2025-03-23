<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

include '../config/db_connection.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$offer_id = $data['offer_id'] ?? null;
$job_id = $data['job_id'] ?? null;

if (!$offer_id || !$job_id) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}


$sql = "SELECT * FROM jobs WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $job_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();
$stmt->close();

if (!$job) {
    echo json_encode(["success" => false, "message" => "Permission denied"]);
    exit;
}

$sql = "SELECT * FROM offers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $offer_id);
$stmt->execute();
$result = $stmt->get_result();
$offer = $result->fetch_assoc();
$stmt->close();

if (!$offer) {
    echo json_encode(["success" => false, "message" => "Offer not found"]);
    exit;
}

$provider_id = $offer['provider_id'];
$completion_time = $offer['completion_time']; 

$conn->begin_transaction();
try {
    $stmt1 = $conn->prepare("UPDATE offers SET status = 'accepted' WHERE id = ?");
    $stmt1->bind_param("i", $offer_id);
    $stmt1->execute();
    $stmt1->close();

    $stmt2 = $conn->prepare("UPDATE jobs SET status = 'in_progress' WHERE id = ?");
    $stmt2->bind_param("i", $job_id);
    $stmt2->execute();
    $stmt2->close();

    $stmt3 = $conn->prepare("UPDATE offers SET status = 'rejected' WHERE job_id = ? AND id != ?");
    $stmt3->bind_param("ii", $job_id, $offer_id);
    $stmt3->execute();
    $stmt3->close();

    $stmt4 = $conn->prepare("
        INSERT INTO in_progress_jobs (offer_id, job_id, provider_id, user_id, job_title, job_date, job_time, location, description, budget, offer_amount, offer_message, completion_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt4->bind_param("iiissssssddss", 
        $offer_id, 
        $job_id, 
        $provider_id, 
        $user_id, 
        $job['job_title'], 
        $job['job_date'], 
        $job['job_time'], 
        $job['location'], 
        $job['description'], 
        $job['budget'], 
        $offer['offer_amount'], 
        $offer['message'], 
        $completion_time
    );
    $stmt4->execute();
    $stmt4->close();

    $conn->commit();

    echo json_encode(["success" => true, "message" => "Offer accepted and job moved to in-progress"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => "Transaction failed"]);
}

$conn->close();
?>
