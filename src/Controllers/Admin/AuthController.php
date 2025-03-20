<?php
namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseAdminController;
use App\Core\View;
use App\Models\Auth;

class AuthController extends BaseAdminController {
    private $authModel;

    public function __construct() {
        $this->authModel = new Auth();
    }

    public function loginPage() {
        // If already logged in, redirect to dashboard
        if ($this->authModel->isLoggedIn()) {
            header('Location: /admin/dashboard');
            exit;
        }
        
        View::render('admin/login', [
            'title' => 'Admin Login'
        ]);
    }

    public function login() {
        // Process login form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                View::render('admin/login', [
                    'title' => 'Admin Login',
                    'error' => 'Email and password are required'
                ]);
                return;
            }
            
            if ($this->authModel->login($email, $password)) {
                header('Location: /admin/dashboard');
                exit;
            } else {
                View::render('admin/login', [
                    'title' => 'Admin Login',
                    'error' => 'Invalid email or password'
                ]);
            }
        } else {
            header('Location: /admin/login');
            exit;
        }
    }

    public function logout() {
        $this->authModel->logout();
        header('Location: /admin/login');
        exit;
    }
    
}
