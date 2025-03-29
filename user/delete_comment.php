<?php
include '../config/db_connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

if (!empty($_POST['comment_id'])) {
    $comment_id = intval($_POST['comment_id']);
    $user_id = $_SESSION['user_id'];

    // Check if the comment exists and belongs to the user
    $stmt = $conn->prepare("SELECT id FROM comments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Comment not found or unauthorized"]);
        exit;
    }

    // Delete the comment and its replies
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? OR parent_id = ?");
    $stmt->bind_param("ii", $comment_id, $comment_id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        error_log("DB Error: " . $stmt->error);
        echo json_encode(["error" => "Failed to delete comment"]);
    }
} else {
    echo json_encode(["error" => "Missing comment_id"]);
}

$conn->close();
?>
