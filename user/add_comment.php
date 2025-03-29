<?php
include '../config/db_connection.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

if (!empty($_POST['job_id']) && !empty($_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $job_id = intval($_POST['job_id']);
    $comment = trim($_POST['comment']);
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : null;

    if (strlen($comment) > 500) {
        echo json_encode(["error" => "Comment too long (max 500 chars)"]);
        exit;
    }

    // Fetch user's name from database
    $stmt = $conn->prepare("SELECT first_name, last_name FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
    } else {
        echo json_encode(["error" => "User not found"]);
        exit;
    }

    // Insert comment (or reply) into database
    $stmt = $conn->prepare("INSERT INTO comments (job_id, user_id, comment, parent_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iisi", $job_id, $user_id, $comment, $parent_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "id" => $conn->insert_id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "comment" => $comment,
            "created_at" => date("Y-m-d H:i:s"),
            "parent_id" => $parent_id
        ]);
    } else {
        error_log("DB Error: " . $stmt->error);
        echo json_encode(["error" => "Failed to add comment"]);
    }
} else {
    echo json_encode(["error" => "Missing job_id or comment"]);
}

$conn->close();
?>
