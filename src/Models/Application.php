<?php
namespace App\Models;

use App\Core\Database;

class Application {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getCount() {
        return $this->db->fetchColumn("SELECT COUNT(*) FROM applications");
    }
    
    public function getRecentApplications($limit = 5) {
        return $this->db->fetchAll(
            "SELECT a.*, j.title AS job_title, u.name AS applicant_name, c.name AS company_name
             FROM applications a
             JOIN jobs j ON a.job_id = j.id
             JOIN users u ON a.user_id = u.id
             JOIN companies c ON j.company_id = c.id
             ORDER BY a.created_at DESC
             LIMIT ?", 
            [$limit]
        );
    }
}