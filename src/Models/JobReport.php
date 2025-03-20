<?php

namespace App\Models;

use App\Core\Database;

class JobReport {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists() {
        $sql = "CREATE TABLE IF NOT EXISTS job_reports (
            id SERIAL PRIMARY KEY,
            job_id BIGINT NOT NULL REFERENCES jobs(id),
            reason VARCHAR(255) NOT NULL,
            description TEXT,
            status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'reviewed', 'resolved')),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $this->db->execute($sql);
    }

    public function createReport($jobId, $reason, $description = null) {
        $sql = "INSERT INTO job_reports (job_id, reason, description) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [$jobId, $reason, $description]);
    }

    public function getReportsByJobId($jobId) {
        $sql = "SELECT * FROM job_reports WHERE job_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$jobId]);
    }

    public function updateReportStatus($reportId, $status) {
        $sql = "UPDATE job_reports SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->db->execute($sql, [$status, $reportId]);
    }

    public function getReportById($reportId) {
        $sql = "SELECT * FROM job_reports WHERE id = ?";
        return $this->db->fetch($sql, [$reportId]);
    }

    public function getPendingReports() {
        $sql = "SELECT jr.*, j.title as job_title, j.slug as job_slug 
                FROM job_reports jr 
                JOIN jobs j ON jr.job_id = j.id 
                WHERE jr.status = 'pending' 
                ORDER BY jr.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getReportCount($status = null) {
        $sql = "SELECT COUNT(*) FROM job_reports";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        return $this->db->fetchColumn($sql, $params);
    }
} 