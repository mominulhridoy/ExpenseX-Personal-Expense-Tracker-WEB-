<?php
$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbname = "expense_tracker";

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $dbname);

// Check if it worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
