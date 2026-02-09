<!DOCTYPE html>
<html lang="en">
<head>
    <title>Signup - ExpenseX</title>
    <link rel="stylesheet" href="../assets/css/style.css"> </head>
<body>
    <div class="auth-container">
        <h2>Register for ExpenseX</h2>
        <form action="signup_action.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Create Account</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>