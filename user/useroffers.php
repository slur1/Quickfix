<?php
session_start();
include '../config/db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$offers = [];

// Update SQL query to exclude non-pending offers
$sql = "
    SELECT 
        offers.id AS offer_id,
        jobs.job_title,
        COALESCE(jobs.job_date, 'No Date Available') AS job_date,
        jobs.location,
        offers.offer_amount,
        offers.status AS offer_status,
        offers.creation_time
    FROM offers
    LEFT JOIN jobs ON offers.job_id = jobs.id
    WHERE offers.provider_id = ? AND offers.status = 'pending';  -- Exclude non-pending offers
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
while ($row = $result->fetch_assoc()) {
    $offers[] = $row;
}

// If no offers, return empty array
if (empty($offers)) {
    $offers = [];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($offers);

$stmt->close();
$conn->close();
?>
