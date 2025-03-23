<?php
require 'db_connection.php'; // Include your connection file

if ($conn) {
    echo "✅ Database connection successful!";
} else {
    echo "❌ Database connection failed!";
}
?>
