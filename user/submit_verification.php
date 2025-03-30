<?php
session_start();
include '../config/db_connection.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid data received"]);
    exit;
}

$api_key = "jiYY9wGCQN1GrwYd79UZ2ghPwHDEOp3t"; 
$api_url = "https://api2.idanalyzer.com/scan";

$uploads_dir = 'uploads';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0755, true);
}

function saveBase64Image($base64Data, $filename) {
    if (!$base64Data) return null;
    
    $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
    $imageData = base64_decode($imageData);
    
    if (!$imageData) {
        return null;
    }

    $filePath = "uploads/" . $filename;
    file_put_contents($filePath, $imageData);
    return $filePath;
}
$user_id = $_SESSION['user_id'];
$front_id_path = saveBase64Image($data['front_id'] ?? '', "front_id_" . uniqid() . ".png");
$back_id_path = saveBase64Image($data['back_id'] ?? '', "back_id_" . uniqid() . ".png");
$selfie_path = saveBase64Image($data['selfie_data'] ?? '', "selfie_" . uniqid() . ".png");

if (!$front_id_path || !$back_id_path || !$selfie_path) {
    echo json_encode(["success" => false, "message" => "Failed to save images"]);
    exit;
}

$payload = json_encode([
    "profile" => "security_medium",
    "document" => base64_encode(file_get_contents($front_id_path)),
    "face" => base64_encode(file_get_contents($selfie_path)),
    "documentBack" => base64_encode(file_get_contents($back_id_path))
]);

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-KEY: ' . $api_key,
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['success' => false, 'message' => 'API Error: ' . $error]);
    exit;
}

$response_data = json_decode($response, true);
$decision = $response_data['decision'] ?? 'Unknown'; 

if (isset($response_data['document']['expiry'])) {
    $expiry_date = $response_data['document']['expiry'];
    $expiry_timestamp = strtotime($expiry_date);
    if ($expiry_timestamp < time()) {
        echo json_encode(["success" => false, "message" => "ID is expired"]);
        exit;
    }
}

$warnings = [];
$warningMessages = [
    'DOCUMENT_FACE_NOT_FOUND' => 'Document Face Not Found',
    'FAKE_ID' => 'The ID is Fake',
    'MISSING_ENDORSEMENT' => 'Missing Endorsement',
    'MISSING_ISSUE_DATE' => 'Missing Issue Date',
    'DOCUMENT_EXPIRED' => 'Document Expired',
    'IMAGE_EDITED' => 'Image Edited',
    'FACE_MISMATCH' => 'Face Mismatch',
    'SELFIE_FACE_NOT_FOUND' => 'Selfie Face Not Found',
    'IMAGE_FORGERY' => 'Image Forgery'
];

if (isset($response_data['warning']) && is_array($response_data['warning'])) {
    foreach ($response_data['warning'] as $warning) {
        if ($warning['decision'] !== 'accept') {
            $code = $warning['code'];
            $warnings[] = $warningMessages[$code] ?? $code;
        }
    }
}

try {
    if ($decision === 'reject') {
        echo json_encode([
            'error' => 'Your ID verification has been rejected.',
            'warnings' => $warnings
        ]);
        exit;
    } elseif ($decision === 'Unknown') {
        echo json_encode([
            'error' => 'The ID verification decision is unknown. Please contact support.',
            'warnings' => $warnings
        ]);
        exit;
    }
    elseif ($decision === 'accept') {
        echo json_encode(['decision' => $decision]);
        $sql = "INSERT INTO user_verifications (
            user_id, front_id, back_id, selfie, verification_status
        ) VALUES (?, ?, ?, ?, ?)";

        $params = [
            $user_id,           
            $front_id_path,     
            $back_id_path,             
            $selfie_path,           
            'identity_verified'               
        ];

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        $sqlUpdate = "UPDATE user set verification_status = 'identity_verified'";
        $stmtUp = $conn->prepare($sqlUpdate);
        $stmtUp->execute();

        exit;
    }

} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to process request: ' . $e->getMessage()]);
    exit;
}

?>
