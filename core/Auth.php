<?php
// core/Auth.php

class Auth {
    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin() {
        if (!self::check()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please login to access this page.'];
            header("Location: index.php?route=login");
            exit;
        }
    }

    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function user() {
        if (self::check()) {
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'] ?? '',
                'role' => $_SESSION['user_role'] ?? 'user',
                'profile_picture' => $_SESSION['user_profile_img'] ?? null
            ];
        }
        return null;
    }

    public static function isAdmin() {
        $user = self::user();
        return $user && ($user['role'] === 'admin');
    }

    public static function requireAdmin() {
        if (!self::isAdmin()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Unauthorized access. Admin privileges required.'];
            header("Location: index.php?route=dashboard");
            exit;
        }
    }
}
?>
