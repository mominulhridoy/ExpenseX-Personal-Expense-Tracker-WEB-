<?php
// auth/session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Require login. Redirects to login page if not logged in.

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

// Redirect if already logged in.
 
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header("Location: dashboard.php");
        exit;
    }
}

// Get current user ID

function currentUserId() {
    return $_SESSION['user_id'] ?? null;
}


// Set flash message

function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // 'success', 'error', 'info', 'warning'
        'message' => $message
    ];
}


// Get and clear flash message

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
