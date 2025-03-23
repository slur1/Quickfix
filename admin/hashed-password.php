<?php
include '../config/db_connection.php';

try {

  $sql = "SELECT admin_id, password FROM admin";
  $result = $conn->query($sql);

  while ($row = $result->fetch_assoc()) {
    $id = $row['admin_id'];
    $plain_password = $row['password'];

    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE admin SET password = ? WHERE admin_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $hashed_password, $id);
    $stmt->execute();
  }

  echo "Passwords have been hashed successfully.";
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
} finally {
  
  $conn->close();
}
