<?php
namespace App\Models;

use App\Core\Database;

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($email, $password) {
        error_log("Attempting login for email: " . $email);
        
        // $sql =  "SELECT * FROM users WHERE email = ? AND user_type = 'admin'";
        $sql =  "SELECT * FROM users WHERE email = ?";
        $user = $this->db->fetch($sql, [$email]);
        
        error_log("User found: " . ($user ? "Yes" : "No"));
        
        // password_verify: build-in function
        if ($user && password_verify($password, $user->password)) {
            // Set session variables
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_type'] = $user->user_type;
            $_SESSION['is_admin'] = true;
            
            return true;
        };
        return false;
    }

    public function logout() {
        // Unset all session variables
        $_SESSION = [];
        
        // Destroy the session
        session_destroy();
        
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
}
