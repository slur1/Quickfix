<?php
session_start();
include '../config/db_connection.php'; 

$sql = "SELECT j.*, u.name AS posted_by FROM jobs j 
        JOIN user u ON j.user_id = u.id 
        ORDER BY j.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse Jobs | QuickFix</title>
</head>
<body>
    <h1>Available Jobs</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <h2><?php echo htmlspecialchars($row['job_title']); ?></h2>
                <p><strong>Posted by:</strong> <?php echo htmlspecialchars($row['posted_by']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                <p><strong>Budget:</strong> ₱<?php echo number_format($row['budget'], 2); ?></p>
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
