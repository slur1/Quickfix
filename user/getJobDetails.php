<?php
include '../config/db_connection.php';

$job_id = $_GET['job_id'] ?? null;
$job_details = [];

if ($job_id) {
    $sql = "
        SELECT 
            jobs.id, 
            jobs.job_title, 
            jobs.job_date, 
            jobs.location, 
            jobs.description, 
            jobs.budget, 
            jobs.images, 
            jobs.status
        FROM jobs
        WHERE jobs.id = ?;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $job_details = $result->fetch_assoc();
        
        if (empty($job_details['images'])) {
            $job_details['images'] = 'default-image.jpg';  
        } else {
            $job_details['images'] = 'user/user-uploads/' . $job_details['images'];
        }
    }

    $stmt->close();
}

echo json_encode($job_details);
$conn->close();
?>
