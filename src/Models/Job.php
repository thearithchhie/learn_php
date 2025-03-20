<?php
namespace App\Models;

use App\Core\Database;
use App\Models\Activity;

class Job {
    private $db, $activityModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->activityModel = new Activity();
    }


    public function getAllJobsPaginated($page = 1, $perPage = 10, $search = null, $status = null) {
        $sql = "SELECT j.*, c.name AS company_name 
                FROM jobs j
                LEFT JOIN companies c ON j.company_id = c.id
                WHERE j.deleted_at IS NULL";
        $params = [];
        
        // Build WHERE clause
        $whereClauses = [];
        
        if ($search) {
            $whereClauses[] = "(j.title LIKE ? OR c.name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status) {
            $whereClauses[] = "j.status = ?";
            $params[] = $status;
        }
        
        // Add WHERE clauses to SQL if any
        if (!empty($whereClauses)) {
            $sql .= " AND " . implode(" AND ", $whereClauses);
        }
        
        $sql .= " ORDER BY j.created_at DESC";
        
        return $this->db->fetchAllPaginated($sql, $page, $perPage, $params);
    }

    public function countAllJobs($search = null, $status = null) {
        $sql = "SELECT COUNT(*) FROM jobs j
                LEFT JOIN companies c ON j.company_id = c.id
                WHERE j.deleted_at IS NULL";
        $params = [];
        
        // Build WHERE clause
        $whereClauses = [];
        
        if ($search) {
            $whereClauses[] = "(j.title LIKE ? OR c.name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status) {
            $whereClauses[] = "j.status = ?";
            $params[] = $status;
        }
        
        // Add WHERE clauses to SQL if any
        if (!empty($whereClauses)) {
            $sql .= " AND " . implode(" AND ", $whereClauses);
        }
        
        return $this->db->fetchColumn($sql, $params);
    }

     
    public function getCount() {
        return $this->db->fetchColumn("SELECT COUNT(*) FROM jobs WHERE deleted_at IS NULL");
    }
    
    public function getActiveCount() {
        return $this->db->fetchColumn("SELECT COUNT(*) FROM jobs WHERE status = 'active' AND deleted_at IS NULL");
    }

    public function getAllJobs() {
        $sql = "SELECT j.*, c.name AS company_name 
                FROM jobs j
                LEFT JOIN companies c ON j.company_id = c.id
                ORDER BY j.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }

    public function createJob($jobData) {
        // Convert is_featured to PostgreSQL boolean format
        $is_featured = $jobData['is_featured'] === 'true' ? 't' : 'f';
        
        $sql = "INSERT INTO jobs (
            title, company_id, category_id, location, job_type, salary_min, salary_max,
            description, requirements, benefits, application_url, deadline,
            status, is_featured, created_at, user_id, slug
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?
        ) RETURNING id";
        
        $params = [
            $jobData['title'],
            $jobData['company_id'],
            $jobData['category_id'],
            $jobData['location'],
            $jobData['job_type'],
            $jobData['salary_min'],
            $jobData['salary_max'],
            $jobData['description'],
            $jobData['requirements'],
            $jobData['benefits'],
            $jobData['application_url'],
            $jobData['deadline'],
            $jobData['status'],
            $is_featured,
            $jobData['created_at'],
            $jobData['user_id'],
            $jobData['slug']
        ];
        
        return $this->db->insert($sql, $params);
    }

    private function createSlug($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }

    public function getAllJobsWithPagination($page = 1, $limit = 10, $search = null, $status = null, $sort = null) {
        $offset = ($page - 1) * $limit;
        $params = [];
        
        $sql = "SELECT j.*, c.name AS company_name 
                FROM jobs j
                JOIN companies c ON j.company_id = c.id";
        
        // Add WHERE clauses for filtering
        $whereClauses = [];
        
        if ($search) {
            $whereClauses[] = "(j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($status) {
            $whereClauses[] = "j.status = ?";
            $params[] = $status;
        }
        
        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }
        
        // Add ORDER BY for sorting
        if ($sort) {
            switch ($sort) {
                case 'newest':
                    $sql .= " ORDER BY j.created_at DESC";
                    break;
                case 'oldest':
                    $sql .= " ORDER BY j.created_at ASC";
                    break;
                case 'title':
                    $sql .= " ORDER BY j.title ASC";
                    break;
                case 'company':
                    $sql .= " ORDER BY c.name ASC";
                    break;
                default:
                    $sql .= " ORDER BY j.created_at DESC";
            }
        } else {
            $sql .= " ORDER BY j.created_at DESC";
        }
        
        // Add LIMIT and OFFSET for pagination
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getTotalJobs($search = null, $categoryId = null, $location = null) {
        $sql = "SELECT COUNT(*) FROM jobs j 
                LEFT JOIN companies c ON j.company_id = c.id 
                LEFT JOIN categories cat ON j.category_id = cat.id 
                WHERE j.deleted_at IS NULL";
        $params = [];
        
        // Build WHERE clause
        $whereClauses = [];
        
        if ($search) {
            $whereClauses[] = "(j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($categoryId) {
            $whereClauses[] = "j.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($location) {
            $whereClauses[] = "j.location ILIKE ?";
            $params[] = "%{$location}%";
        }
        
        // Add WHERE clauses to SQL if any
        if (!empty($whereClauses)) {
            $sql .= " AND " . implode(" AND ", $whereClauses);
        }
        
        return $this->db->fetchColumn($sql, $params);
    }

    public function getJobById($id) {
        $sql = "SELECT j.*, c.name AS company_name, cat.name AS category_name 
                FROM jobs j
                LEFT JOIN companies c ON j.company_id = c.id
                LEFT JOIN categories cat ON j.category_id = cat.id
                WHERE j.id = ? AND j.deleted_at IS NULL";
        
        return $this->db->fetch($sql, [$id]);
    }

    public function updateJob($jobData) {
        // Convert is_featured to PostgreSQL boolean format
        $is_featured = $jobData['is_featured'] === 'true' ? 't' : 'f';
        
        $sql = "UPDATE jobs SET 
                title = ?, 
                company_id = ?, 
                category_id = ?,
                location = ?, 
                job_type = ?, 
                salary_min = ?, 
                salary_max = ?,
                description = ?, 
                requirements = ?, 
                benefits = ?, 
                application_url = ?, 
                deadline = ?,
                status = ?, 
                is_featured = ?, 
                updated_at = ?,
                slug = ?
                WHERE id = ?";
        
        $params = [
            $jobData['title'],
            $jobData['company_id'],
            $jobData['category_id'],
            $jobData['location'],
            $jobData['job_type'],
            $jobData['salary_min'],
            $jobData['salary_max'],
            $jobData['description'],
            $jobData['requirements'],
            $jobData['benefits'],
            $jobData['application_url'],
            $jobData['deadline'],
            $jobData['status'],
            $is_featured,
            $jobData['updated_at'],
            $jobData['slug'],
            $jobData['id']
        ];
        
        return $this->db->execute($sql, $params);
    }

    public function deleteJob($id) {
        // First check if the job exists
        $job = $this->getJobById($id);
        if (!$job) {
            return false;
        }

        // Soft delete by updating deleted_at timestamp
        $sql = "UPDATE jobs SET deleted_at = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }


    //! Frontend
    public function getFeaturedJobs($limit = 6, $categoryId = null, $location = null, $search = '') {
        $sql = "SELECT j.*, c.name as company_name, cat.name as category_name 
                FROM jobs j 
                LEFT JOIN companies c ON j.company_id = c.id 
                LEFT JOIN categories cat ON j.category_id = cat.id 
                WHERE j.deleted_at IS NULL AND j.is_featured = true";
        
        $params = [];
        
        if ($categoryId) {
            $sql .= " AND j.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($location) {
            $sql .= " AND j.location ILIKE ?";
            $params[] = "%{$location}%";
        }
        
        if ($search) {
            $sql .= " AND (j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $sql .= " ORDER BY j.created_at DESC LIMIT ?";
        $params[] = $limit;
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getLatestJobs($limit = 10, $category = null, $location = null, $search = null, $page = 1, $sort = 'newest') {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT j.*, c.name as company_name, cat.name as category_name 
                FROM jobs j 
                LEFT JOIN companies c ON j.company_id = c.id 
                LEFT JOIN categories cat ON j.category_id = cat.id 
                WHERE j.deleted_at IS NULL";
        
        $params = [];
        
        // Add category filter
        if ($category) {
            $sql .= " AND j.category_id = ?";
            $params[] = $category;
        }
        
        // Add location filter
        if ($location) {
            $sql .= " AND j.location ILIKE ?";
            $params[] = "%{$location}%";
        }
        
        // Add search condition
        if ($search) {
            $sql .= " AND (j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Add sorting
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY j.created_at ASC";
                break;
            case 'title':
                $sql .= " ORDER BY j.title ASC";
                break;
            case 'company':
                $sql .= " ORDER BY c.name ASC";
                break;
            case 'salary_high':
                $sql .= " ORDER BY j.salary_max DESC NULLS LAST, j.salary_min DESC NULLS LAST";
                break;
            case 'salary_low':
                $sql .= " ORDER BY j.salary_min ASC NULLS LAST, j.salary_max ASC NULLS LAST";
                break;
            case 'deadline':
                $sql .= " ORDER BY j.deadline ASC NULLS LAST";
                break;
            default: // newest
                $sql .= " ORDER BY j.created_at DESC";
        }
        
        // Add pagination
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }

    public function getJobBySlug($slug) {
        $sql = "SELECT j.*, c.name as company_name, cat.name as category_name 
                FROM jobs j 
                LEFT JOIN companies c ON j.company_id = c.id 
                LEFT JOIN categories cat ON j.category_id = cat.id 
                WHERE j.slug = ? AND j.deleted_at IS NULL";
        
        return $this->db->fetch($sql, [$slug]);
    }

    public function getSimilarJobs($currentJobId, $categoryId, $limit = 3) {
        $sql = "SELECT j.*, c.name as company_name 
                FROM jobs j 
                LEFT JOIN companies c ON j.company_id = c.id 
                WHERE j.category_id = ? 
                AND j.id != ? 
                AND j.deleted_at IS NULL 
                ORDER BY j.created_at DESC 
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$categoryId, $currentJobId, $limit]);
    }

}

