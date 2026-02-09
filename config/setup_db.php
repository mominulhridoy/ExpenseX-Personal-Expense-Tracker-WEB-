<?php
$host = "localhost";
$user = "root";
$pass = ""; // Default XAMPP password is empty

// 1. Connect to MySQL (without a specific database)
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create the Database
$sql_db = "CREATE DATABASE IF NOT EXISTS expense_tracker";
if ($conn->query($sql_db) === TRUE) {
    echo "Database 'expense_tracker' created successfully.<br>";
}

// 3. Connect to the new database
$conn->select_db("expense_tracker");

// 4. Create the Users Table (Your main task for Security)
$sql_table = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_table) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>