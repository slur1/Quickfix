<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json'); 

session_start();
require_once '../config/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id']; 
error_log("Fetching accepted offers for user_id: " . $user_id);


if (!$conn) {
    error_log("Database Connection Error: " . mysqli_connect_error());
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}


$sql = "
    SELECT 
        offers.id, 
        jobs.job_title, 
        jobs.location,  
        jobs.budget, 
        offers.offer_amount, 
        offers.completion_time,
        CONCAT(user.first_name, ' ', user.last_name) AS employer_name --  Fetch employer full name
    FROM offers 
    JOIN jobs ON offers.job_id = jobs.id
    JOIN user ON jobs.user_id = user.id  --  Join to get employer details
    WHERE offers.provider_id = ? 
    AND offers.status = 'Accepted'
    AND jobs.status NOT IN ('completed', 'cancelled')  -- Exclude jobs with status 'completed' or 'cancelled'
";


$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("SQL Prepare Error: " . $conn->error);
    echo json_encode(["error" => "SQL Prepare Failed"]);
    exit;
}


$stmt->bind_param("i", $user_id);


if (!$stmt->execute()) {
    error_log("SQL Execute Error: " . $stmt->error);
    echo json_encode(["error" => "SQL Execute Failed"]);
    exit;
}


$result = $stmt->get_result();
$offers = $result->fetch_all(MYSQLI_ASSOC);


error_log("Fetched Offers: " . print_r($offers, true));
error_log("Total Offers Fetched: " . count($offers));


$stmt->close();
$conn->close();


echo json_encode($offers, JSON_PRETTY_PRINT);
exit;
?>
