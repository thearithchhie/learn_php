<?php

namespace App\Controllers\Front;

use App\Core\View;
use App\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function loginPage() {
        // Get the redirect URL if any
        $redirect = $_GET['redirect'] ?? '/';
        
        View::render('front/auth/login', [
            'title' => 'Login',
            'redirect' => $redirect
        ]);
    }

    public function login() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $redirect = $_POST['redirect'] ?? '/';

        try {
            // Attempt to login
            $user = $this->userModel->authenticate($email, $password);
            
            if ($user) {
                // Set session
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_email'] = $user->email;
                
                // Redirect to the requested page or home
                header('Location: ' . $redirect);
                exit;
            } else {
                // Login failed
                $_SESSION['error'] = 'Invalid email or password';
                header('Location: /auth/login?redirect=' . urlencode($redirect));
                exit;
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'An error occurred during login';
            header('Location: /auth/login?redirect=' . urlencode($redirect));
            exit;
        }
    }

    public function registerPage() {
        View::render('front/auth/register', [
            'title' => 'Register'
        ]);
    }

    public function register() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /auth/register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate input
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /auth/register');
            exit;
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format';
            header('Location: /auth/register');
            exit;
        }

        // Validate password length
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters long';
            header('Location: /auth/register');
            exit;
        }

        // Validate passwords match
        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: /auth/register');
            exit;
        }

        try {
            // Check if email already exists
            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email already registered';
                header('Location: /auth/register');
                exit;
            }

            // Create user
            $userId = $this->userModel->createUser($name, $email, $password);
            
            if ($userId) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                header('Location: /auth/login');
                exit;
            } else {
                throw new \Exception('Failed to create user account');
            }
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
            header('Location: /auth/register');
            exit;
        }
    }

    public function logout() {
        // Clear all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to home page
        header('Location: /');
        exit;
    }
} 