<?php
session_start();
include '../config/db_connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized access"]);
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["id_image"])) {
    $idType = $_POST["id_type"];
    $imageTmpPath = $_FILES["id_image"]["tmp_name"];

    if (!file_exists($imageTmpPath)) {
        echo json_encode(["success" => false, "error" => "File upload failed"]);
        exit;
    }

    // Convert image to base64
    $imageData = base64_encode(file_get_contents($imageTmpPath));

    // API credentials
    $apiKey = "qB3mlq4fgpQyjKpGMW8bGYoJaLMjjTQ5";  // Replace with your actual API Key
    $profileId = "e574604c2952418bb47c199d776cbcb4";  // Replace with your KYC Profile ID
    $apiEndpoint = "https://api2.idanalyzer.com/scan";
    
    // Prepare API request payload
    $postData = json_encode([
        "profile" => $profileId,
        "document" => $imageData
    ]);

    // Initialize cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-API-KEY: $apiKey",
        "Accept: application/json",
        "Content-Type: application/json"
    ]);

    // Execute API request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Log API response for debugging
    file_put_contents("id_verification_log.txt", date("Y-m-d H:i:s") . " - Response: " . print_r($response, true) . PHP_EOL, FILE_APPEND);
    
    // Decode API response
    $result = json_decode($response, true);

    // Check if API request failed
    if (!$result || $httpCode !== 200) {
        echo json_encode(["success" => false, "error" => "API request failed"]);
        exit;
    }

    // Normalize decision text
    $decision = strtolower(trim($result["decision"] ?? "reject"));

    // Determine verification status
    $verificationStatus = ($decision === "accept" || $decision === "approved") ? "approved" : "rejected";

    // Log decision for debugging
    file_put_contents("id_verification_log.txt", date("Y-m-d H:i:s") . " - Decision: " . $decision . " - Status: " . $verificationStatus . PHP_EOL, FILE_APPEND);

    // **REMOVE DATABASE UPDATE HERE**
    
    // Return verification status to the frontend
    echo json_encode(["success" => true, "status" => $verificationStatus]);

} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>
