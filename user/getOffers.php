<?php
include '../config/db_connection.php';

$job_id = $_GET['job_id'] ?? null;

if ($job_id) {
    $sql = "
        SELECT 
            offers.id AS offer_id,
            user.id AS provider_id,  /* ADD THIS LINE */
            CONCAT(user.first_name, ' ', user.last_name) AS provider_name,
            user.verification_status,
            user.profile_picture,
            offers.creation_time,
            offers.status AS offer_status,
            offers.offer_amount,
            offers.message,
            offers.completion_time
        FROM offers
        LEFT JOIN user ON offers.provider_id = user.id
        WHERE offers.job_id = ?;
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);  
    $stmt->execute();
    $offers_result = $stmt->get_result();
    $offers = [];
    
    while ($offer = $offers_result->fetch_assoc()) {
        $offers[] = [
            'offer_id' => $offer['offer_id'],
            'provider_id' => $offer['provider_id'],  // ✅ FIXED: Add provider_id here
            'provider_name' => $offer['provider_name'],
            'verification_status' => $offer['verification_status'],
            'profile_picture' => $offer['profile_picture'],
            'creation_time' => $offer['creation_time'],
            'offer_status' => $offer['offer_status'],
            'offer_amount' => $offer['offer_amount'],
            'message' => $offer['message'],
            'completion_time' => $offer['completion_time']
        ];
    }
    
    
    echo json_encode($offers);
}

$stmt->close();
$conn->close();
?>