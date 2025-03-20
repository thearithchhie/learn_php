<?php
namespace App\Models;

use App\Core\Database;

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists() {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            user_type VARCHAR(50) DEFAULT 'candidate',
            status VARCHAR(50) DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL
        )";
        
        $this->db->execute($sql);
    }

    public function getCount() {
        return $this->db->fetchColumn("SELECT COUNT(*) FROM users");
    }
    
    // public function findAll() {
    //     return $this->db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
    // }
    public function findAll() {
        return $this->db->fetchAll("SELECT * FROM users ORDER BY created_at DESC");
    }


    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function create($userData) {
        $sql = "INSERT INTO users (name, email, password, user_type, status, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW()) RETURNING id";
        
        $this->db->query($sql, [
            $userData['name'],
            $userData['email'],
            $userData['password'], // Make sure this is hashed
            $userData['user_type'],
            $userData['status'] ?? 'active'
        ]);
        
        return $this->db->lastInsertId();
    }
    
    public function update($id, $userData) {
        $sql = "UPDATE users SET 
                name = ?, 
                email = ?, 
                user_type = ?, 
                status = ?, 
                updated_at = NOW() 
                WHERE id = ?";
        
        return $this->db->query($sql, [
            $userData['name'],
            $userData['email'],
            $userData['user_type'],
            $userData['status'],
            $id
        ])->rowCount();
    }
    
    public function delete($id) {
        return $this->db->query("DELETE FROM users WHERE id = ?", [$id])->rowCount();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }
    

    public function getAllUsers() {
        return $this->db->fetchAll("SELECT * FROM users ORDER BY created_at DESC");
    }

    public function getAllUsersPaginated($page = 1, $perPage = 2, $search = null) {
        $sql = "SELECT * FROM users";
        $params = [];
        
        // Add search condition if provided
        if ($search) {
            $sql .= " WHERE name LIKE ? OR email LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->fetchAllPaginated($sql, $page, $perPage, $params);
    }

    public function countAllUsers($search = null) {
        $sql = "SELECT * FROM users";
        $params = [];
        
        // Add search condition if provided
        if ($search) {
            $sql .= " WHERE name LIKE ? OR email LIKE ?";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        return $this->db->countRows($sql, $params);
    }

    public function authenticate($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ? AND deleted_at IS NULL LIMIT 1";
        $user = $this->db->fetch($sql, [$email]);

        if ($user && password_verify($password, $user->password)) {
            unset($user->password); // Don't send password in session
            return $user;
        }

        return false;
    }

    public function emailExists($email) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ? AND deleted_at IS NULL";
        return $this->db->fetchColumn($sql, [$email]) > 0;
    }

    public function createUser($name, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (name, email, password, user_type, status, created_at) 
                    VALUES (?, ?, ?, 'candidate', 'active', CURRENT_TIMESTAMP) RETURNING id";
            
            return $this->db->fetchColumn($sql, [$name, $email, $hashedPassword]);
        } catch (\Exception $e) {
            // Log the error for debugging
            error_log("Error creating user: " . $e->getMessage());
            throw $e;
        }
    }
}