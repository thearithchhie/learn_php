<?php
namespace App\Models;

use App\Core\Database;

class SaveJob {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ensureTableExists();
    }

    private function ensureTableExists() {
        $sql = "CREATE TABLE IF NOT EXISTS saved_jobs (
            id SERIAL PRIMARY KEY,
            job_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT saved_jobs_user_id_job_id_key UNIQUE (user_id, job_id),
            CONSTRAINT saved_jobs_job_id_fkey FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
            CONSTRAINT saved_jobs_user_id_fkey FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";
        
        $this->db->execute($sql);
    }

    public function saveJob($jobId, $userId) {
        try {
            error_log("Attempting to save job. Job ID: " . $jobId . ", User ID: " . $userId);
            
            // First check if job exists
            $jobExists = $this->db->fetchColumn("SELECT COUNT(*) FROM jobs WHERE id = ?", [$jobId]);
            if (!$jobExists) {
                error_log("Job does not exist with ID: " . $jobId);
                return false;
            }

            // Check if user exists
            $userExists = $this->db->fetchColumn("SELECT COUNT(*) FROM users WHERE id = ?", [$userId]);
            if (!$userExists) {
                error_log("User does not exist with ID: " . $userId);
                return false;
            }

            $sql = "INSERT INTO saved_jobs (job_id, user_id) VALUES (?, ?)";
            $result = $this->db->execute($sql, [$jobId, $userId]);
            
            if ($result) {
                error_log("Successfully saved job. Job ID: " . $jobId . ", User ID: " . $userId);
            } else {
                error_log("Failed to save job. Job ID: " . $jobId . ", User ID: " . $userId);
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("PDO Error saving job: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("SQL State: " . $e->errorInfo[0]);
            
            // If job is already saved, ignore the error
            if ($e->getCode() == '23505') { // Unique violation in PostgreSQL
                error_log("Job already saved (unique constraint violation)");
                return false;
            }
            throw $e;
        } catch (\Exception $e) {
            error_log("General Error saving job: " . $e->getMessage());
            throw $e;
        }
    }

    public function unsaveJob($jobId, $userId) {
        try {
            error_log("Attempting to unsave job. Job ID: " . $jobId . ", User ID: " . $userId);
            $sql = "DELETE FROM saved_jobs WHERE job_id = ? AND user_id = ?";
            $result = $this->db->execute($sql, [$jobId, $userId]);
            
            if ($result) {
                error_log("Successfully unsaved job. Job ID: " . $jobId . ", User ID: " . $userId);
            } else {
                error_log("Failed to unsave job. Job ID: " . $jobId . ", User ID: " . $userId);
            }
            
            return $result;
        } catch (\PDOException $e) {
            error_log("Error unsaving job: " . $e->getMessage());
            throw $e;
        }
    }

    public function isJobSaved($jobId, $userId) {
        try {
            error_log("Checking if job is saved. Job ID: " . $jobId . ", User ID: " . $userId);
            $sql = "SELECT COUNT(*) FROM saved_jobs WHERE job_id = ? AND user_id = ?";
            $result = $this->db->fetchColumn($sql, [$jobId, $userId]) > 0;
            error_log("Job saved status: " . ($result ? "Yes" : "No"));
            return $result;
        } catch (\PDOException $e) {
            error_log("Error checking if job is saved: " . $e->getMessage());
            throw $e;
        }
    }

    public function getSavedJobs($userId, $page = 1, $perPage = 10) {
        try {
            $offset = ($page - 1) * $perPage;
            $sql = "SELECT j.*, c.name as company_name, cat.name as category_name, sj.created_at as saved_at
                    FROM saved_jobs sj 
                    JOIN jobs j ON sj.job_id = j.id 
                    LEFT JOIN companies c ON j.company_id = c.id 
                    LEFT JOIN categories cat ON j.category_id = cat.id 
                    WHERE sj.user_id = ? AND j.deleted_at IS NULL 
                    ORDER BY sj.created_at DESC 
                    LIMIT ? OFFSET ?";
            return $this->db->fetchAll($sql, [$userId, $perPage, $offset]);
        } catch (\PDOException $e) {
            error_log("Error getting saved jobs: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function countSavedJobs($userId) {
        $sql = "SELECT COUNT(*) FROM saved_jobs WHERE user_id = ?";
        return $this->db->fetchColumn($sql, [$userId]);
    }

    public function getTotalSavedJobs($userId) {
        try {
            $sql = "SELECT COUNT(*) FROM saved_jobs sj 
                    JOIN jobs j ON sj.job_id = j.id 
                    WHERE sj.user_id = ? AND j.deleted_at IS NULL";
            return $this->db->fetchColumn($sql, [$userId]);
        } catch (\PDOException $e) {
            error_log("Error getting total saved jobs: " . $e->getMessage());
            throw $e;
        }
    }

    public function getSavedJobsCount() {
        return $this->db->fetchColumn("SELECT COUNT(*) FROM saved_jobs");
    }
}