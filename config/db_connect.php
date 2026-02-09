<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "expense_tracker"; 

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check if the connection works
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>