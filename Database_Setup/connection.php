<?php
$conn = new mysqli("localhost", "root", "", "expense_tracker");
if ($conn->connect_error) { die("Database connection failed"); }
?>
