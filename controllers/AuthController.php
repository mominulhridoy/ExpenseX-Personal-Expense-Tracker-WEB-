<?php

class AuthController {
    
    public function login() {
        if (Auth::check()) {
            header("Location: index.php?route=dashboard");
            exit;
        }
        $error = $_SESSION['flash']['message'] ?? '';
        unset($_SESSION['flash']);
        require_once 'views/auth/login.php';
    }

    public function loginPost() {
        if (!CSRF::verifyToken($_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Please enter email and password.'];
            header("Location: index.php?route=login");
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_profile_img'] = $user['profile_picture'];
            
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Welcome back, ' . $user['name'] . '!'];
            header("Location: index.php?route=dashboard");
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Invalid email or password.'];
            header("Location: index.php?route=login");
        }
    }

    public function register() {
        if (Auth::check()) {
            header("Location: index.php?route=dashboard");
            exit;
        }
        $error = $_SESSION['flash']['message'] ?? '';
        unset($_SESSION['flash']);
        require_once 'views/auth/register.php';
    }

    public function registerPost() {
        if (!CSRF::verifyToken($_POST['csrf_token'])) {
            die("CSRF Token Validation Failed.");
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $userModel = new User();

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'All fields are required.'];
            header("Location: index.php?route=register");
            return;
        }

        if ($userModel->findByEmail($email)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Email already registered.'];
            header("Location: index.php?route=register");
            return;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if ($userModel->create($name, $email, $hashed_password)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Registration successful! Please login.'];
            header("Location: index.php?route=login");
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Something went wrong.'];
            header("Location: index.php?route=register");
        }
    }

    public function logout() {
        session_destroy();
        session_start();
        $_SESSION['flash'] = ['type' => 'info', 'message' => 'You have been logged out.'];
        header("Location: index.php?route=login");
        exit;
    }
}
?>
