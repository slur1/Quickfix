<?php
include '../config/db_connection.php';

if (!isset($_GET['job_id'])) {
    echo json_encode(["error" => "Job ID not provided"]);
    exit;
}

$job_id = intval($_GET['job_id']);

$stmt = $conn->prepare("SELECT c.id, c.parent_id, u.first_name, u.last_name, c.comment, c.created_at
                        FROM comments c
                        JOIN user u ON c.user_id = u.id
                        WHERE c.job_id = ? ORDER BY c.parent_id IS NULL DESC, c.created_at ASC");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $row['comment'] = htmlspecialchars($row['comment']); // Prevent XSS
    $comments[] = $row;
}

echo json_encode($comments);
$conn->close();
?>
