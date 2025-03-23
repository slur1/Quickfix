<?php

session_start();

include '../config/db_connection.php';

if (isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];


    $sql = "
        SELECT 
            jobs.job_title, 
            jobs.job_date, 
            jobs.job_time, 
            jobs.location, 
            jobs.description, 
            jobs.budget, 
            jobs.images, 
            categories.name AS category_name, 
            sub_categories.name AS sub_category_name 
        FROM jobs 
        LEFT JOIN categories ON jobs.category_id = categories.id 
        LEFT JOIN sub_categories ON jobs.sub_category_id = sub_categories.id 
        WHERE jobs.id = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("i", $job_id);
        
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $job = $result->fetch_assoc();
            echo json_encode(['success' => true, 'job' => $job]);
        } else {

            echo json_encode(['success' => false, 'message' => 'Job not found.']);
        }


        $stmt->close();
    } else {
        
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
   
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}


$conn->close();
?>
