<?php
session_start();

// If the user_id is NOT set in the session, they aren't logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); // Kick them back to login
    exit();
}
?>


<h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>


<p>This is your personal expense dashboard.</p>
<a href="../auth/logout.php">Logout</a>



<h1>User Dashboard</h1>
<p>Member 3 will build the UI here.</p>