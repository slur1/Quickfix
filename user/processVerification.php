<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "error" => "Invalid request method",
        "received_method" => $_SERVER["REQUEST_METHOD"]
    ]);
    exit;
}

// ✅ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}

include '../config/db_connection.php'; // Database connection
$userId = $_SESSION['user_id'];

// ✅ Check if file and ID type are provided
if (!isset($_FILES['id_image']) || !isset($_POST['id_type'])) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

$idType = $_POST['id_type'];
$idImage = $_FILES['id_image'];

// ✅ Allowed file types
$allowedExtensions = ['jpg', 'jpeg', 'png'];
$fileExtension = strtolower(pathinfo($idImage["name"], PATHINFO_EXTENSION));

if (!in_array($fileExtension, $allowedExtensions)) {
    echo json_encode(["success" => false, "error" => "Invalid file type. Only JPG, JPEG, PNG allowed."]);
    exit;
}

// ✅ Check for file upload errors
if ($idImage['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "error" => "File upload error: " . $idImage['error']]);
    exit;
}

// ✅ Secure file upload
$uploadDir = "../uploads/ids/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$newFileName = uniqid("id_", true) . "." . $fileExtension;
$filePath = $uploadDir . $newFileName;

if (!move_uploaded_file($idImage["tmp_name"], $filePath)) {
    echo json_encode(["success" => false, "error" => "Failed to save uploaded file"]);
    exit;
}

// ✅ Send ID to ID Analyzer API for verification
$apiKey = "qB3mlq4fgpQyjKpGMW8bGYoJaLMjjTQ5";
$apiUrl = "https://api.idanalyzer.com/api/v2/scan";

$data = [
    "api_key" => $apiKey,
    "file" => new CURLFile($filePath),
    "documentType" => $idType
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

// ✅ DEBUG: Print full API response
var_dump($response);
exit;

// ✅ Check API response
if (!$result || $httpCode !== 200 || !isset($result["result"])) {
    echo json_encode([
        "success" => false,
        "error" => "ID verification failed",
        "api_response" => $result
    ]);
    exit;
}

var_dump($response); 
exit;

// ✅ Check ID verification result (Modify as per API response structure)
if ($result["result"]["faceMatch"] !== "Match Found") {
    echo json_encode([
        "success" => false,
        "error" => "Face mismatch. ID verification failed."
    ]);
    exit;
}

// ✅ If verification is successful, update the database
$sql = "UPDATE user SET verification_status = 'identity_verified', id_type = ?, id_file_path = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $idType, $filePath, $userId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "ID verified successfully"]);
} else {
    echo json_encode(["success" => false, "error" => "Database update failed"]);
}
?>
