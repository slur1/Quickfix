<?php
session_start();
include '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$uploadDir = "user-uploads/"; 
$uploadedFile = null;

if (!empty($_FILES['image']['name'])) {
    $fileName = time() . "_" . basename($_FILES['image']['name']);
    $targetFile = __DIR__ . "/" . $uploadDir . $fileName; 

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        echo json_encode(["success" => false, "error" => "Invalid file type. Only JPG, JPEG, PNG, and GIF allowed."]);
        exit;
    }

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        $uploadedFile = $uploadDir . $fileName; 
    } else {
        echo json_encode(["success" => false, "error" => "Failed to upload image."]);
        exit;
    }
}

$data = json_decode($_POST['data'], true);
if (!isset($data['id']) || (empty($data['message']) && !$uploadedFile)) {
    echo json_encode(["success" => false, "error" => "Message or image is required"]);
    exit;
}

$job_id = intval($data['id']);
$message = trim($data['message'] ?? "");

$sql = "SELECT provider_id, user_id FROM in_progress_jobs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode(["success" => false, "error" => "Job not found"]);
    exit;
}

$provider_id = $row['provider_id'];
$job_user_id = $row['user_id'];
$receiver_id = ($user_id == $job_user_id) ? $provider_id : $job_user_id;

$sql = "INSERT INTO chat_messages (job_id, sender_id, receiver_id, message, image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiss", $job_id, $user_id, $receiver_id, $message, $uploadedFile);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Message sent successfully"]);
} else {
    echo json_encode(["success" => false, "error" => "Database error"]);
}

$stmt->close();
$conn->close();
?>
