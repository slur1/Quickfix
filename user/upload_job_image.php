<?php
include '../config/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobId = $_POST["job_id"] ?? null;

    if (!$jobId) {
        echo json_encode(["success" => false, "message" => "Missing job ID."]);
        exit;
    }

    $uploadDir = "uploads/";
    $uploadedImages = [];

    foreach ($_FILES["images"]["tmp_name"] as $key => $tmpName) {
        $fileName = time() . "_" . basename($_FILES["images"]["name"][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            $uploadedImages[] = $filePath;
        }
    }

    if (!empty($uploadedImages)) {
        $stmt = $conn->prepare("SELECT images FROM jobs WHERE id = ?");
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $existingImages = !empty($result["images"]) ? explode(',', $result["images"]) : [];
        $allImages = array_merge($existingImages, $uploadedImages);

        $updatedImages = implode(',', $allImages);
        $stmt = $conn->prepare("UPDATE jobs SET images = ? WHERE id = ?");
        $stmt->bind_param("si", $updatedImages, $jobId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "images" => $uploadedImages]);
        } else {
            echo json_encode(["success" => false, "message" => "Database update failed."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed."]);
    }
}
?>
