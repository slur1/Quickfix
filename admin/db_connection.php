<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = ""; 
$db_name = "quickfix_db";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    
    error_log("Database connection failed: " . $conn->connect_error);
    
    die("There was a problem connecting to the database. Please try again later.");
}


$conn->set_charset("utf8mb4"); 


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>