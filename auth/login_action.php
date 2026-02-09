<?php
session_start(); // This starts the "memory" for the user
include '../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Check if the email exists
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        // 2. Compare the typed password with the hashed password in the DB
        if (password_verify($password, $user['password'])) {
            // 3. SUCCESS: Store user info in the Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the dashboard
            header("Location: ../dashboard/index.php");
            exit();
        } else {
            echo "Incorrect password. <a href='login.php'>Try again</a>";
        }
    } else {
        echo "No account found with that email. <a href='signup.php'>Register here</a>";
    }
}
?>