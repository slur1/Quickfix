<?php
include '../config/db_connection.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "User ID not provided"]);
    exit;
}

$user_id = intval($_GET['user_id']);
error_log("Fetching profile for user_id: " . $user_id); // Debugging

$sql = "SELECT id, first_name, last_name, profile_picture, general_location, about_me, verification_status 
        FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $row['name'] = $row['first_name'] . ' ' . $row['last_name'];
    $row['verification_text'] = match ($row['verification_status']) {
        'fully_verified' => 'Fully Verified',
        'identity_verified' => 'Identity Verified',
        default => 'Not Verified'
    };
    echo json_encode($row);
} else {
    echo json_encode(["error" => "User not found"]);
}

$conn->close();
?>
