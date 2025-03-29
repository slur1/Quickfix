<?php
include '../config/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobId = $_POST["job_id"] ?? null;
    $imagePath = $_POST["image_path"] ?? null;

    if (!$jobId || !$imagePath) {
        echo json_encode(["success" => false, "message" => "Missing job ID or image path."]);
        exit;
    }

    // Fetch current images from the database
    $stmt = $conn->prepare("SELECT images FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if (!$result) {
        echo json_encode(["success" => false, "message" => "Job not found."]);
        exit;
    }

    $images = explode(',', $result["images"]);
    $newImages = array_filter($images, fn($img) => trim($img) !== trim($imagePath));

    // Update database with new image list
    $updatedImages = implode(',', $newImages);
    $stmt = $conn->prepare("UPDATE jobs SET images = ? WHERE id = ?");
    $stmt->bind_param("si", $updatedImages, $jobId);
    
    if ($stmt->execute()) {
        // Delete the image file from the server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        echo json_encode(["success" => true, "message" => "Image deleted."]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed."]);
    }
}
?>
