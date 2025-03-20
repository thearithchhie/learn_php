<?php
namespace App\Models;

use App\Core\Database;

class Activity {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // public function logActivity($type, $user_id, $description, $related_id = null) {
    //     return $this->db->execute(
    //         "INSERT INTO activities (type, user_id, description, related_id, created_at) 
    //          VALUES (?, ?, ?, ?, NOW())", 
    //         [$type, $user_id, $description, $related_id]
    //     );
    // }
    
    public function getRecentActivities($limit = 10) {
        return $this->db->fetchAll(
            "SELECT a.*, u.name AS user_name, u.email AS user_email
             FROM activities a
             LEFT JOIN users u ON a.user_id = u.id
             ORDER BY a.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }

    public function logActivity($type, $userId, $description, $relatedId = null) {
        $sql = "INSERT INTO activities (type, user_id, description, related_id, created_at) 
                VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $type,
            $userId,
            $description,
            $relatedId,
            date('Y-m-d H:i:s')
        ];
        
        return $this->db->execute($sql, $params);
    }

    public function getActivitiesByEntityId($type, $entityId, $limit = 5) {
        $sql = "SELECT a.*, u.name as user_name 
                FROM activities a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.type LIKE ? AND a.related_id = ?
                ORDER BY a.created_at DESC
                LIMIT ?";
        
        $params = [
            $type . '%',
            $entityId,
            $limit
        ];
        
        return $this->db->fetchAll($sql, $params);
    }
}