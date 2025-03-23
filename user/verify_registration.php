<?php
// Correct include path based on your directory structure
include '../config/db_connection.php';

// Check if $conn is initialized
if (!$conn) {
    die('Database connection failed!');
}

// Your code to handle verification
$verification_code = $_GET['code'] ?? null;

if ($verification_code) {
    // Example of querying the database with the connection
    $sql = "SELECT * FROM user WHERE verification_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Handle successful verification
        echo "Email verified successfully!";
    } else {
        echo "Invalid or expired verification code.";
    }
} else {
    echo "No verification code provided.";
}

$conn->close();
?>
