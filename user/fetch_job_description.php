<?php
include '../config/db_connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php-error.log');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Query to fetch the description for the specific job
    $sql_description = "SELECT description FROM jobs WHERE id = ?";
    $stmt_description = $conn->prepare($sql_description);
    $stmt_description->bind_param("i", $job_id);
    $stmt_description->execute();
    $result_description = $stmt_description->get_result();

    if ($result_description->num_rows > 0) {
        $description_row = $result_description->fetch_assoc();
        echo json_encode(["description" => $description_row['description']]);
    } else {
        echo json_encode(["error" => "Description not found"]);
    }

    $stmt_description->close();
} else {
    echo json_encode(["error" => "Job ID is required"]);
}

$conn->close();
?>
