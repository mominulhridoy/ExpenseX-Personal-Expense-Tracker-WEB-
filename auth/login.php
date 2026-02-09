<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - ExpenseX</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <h2>Login to ExpenseX</h2>
        <form action="login_action.php" method="POST">
            <input type="email" name="email" placeholder="Enter Email" required><br>
            <input type="password" name="password" placeholder="Enter Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
    </div>
</body>
</html>