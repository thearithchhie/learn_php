<?php
namespace App\Models;

use App\Core\Database;

class Company {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->ensureSlugColumnExists();
    }

    private function ensureSlugColumnExists() {
        try {
            // Try to select the slug column
            $this->db->fetch("SELECT slug FROM companies LIMIT 1");
        } catch (\PDOException $e) {
            // If column doesn't exist, create it
            $this->db->execute("ALTER TABLE companies ADD COLUMN slug VARCHAR(255) UNIQUE");
        }
    }

    public function getAllCompanies($page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM jobs WHERE company_id = c.id AND deleted_at IS NULL) as job_count 
                FROM companies c 
                WHERE c.deleted_at IS NULL 
                ORDER BY c.name ASC 
                LIMIT ? OFFSET ?";
        $companies = $this->db->fetchAll($sql, [$perPage, $offset]);

        // Generate slugs for companies that don't have them
        foreach ($companies as $company) {
            if (empty($company->slug)) {
                $company->slug = $this->generateSlug($company->name);
                // Update the company with the new slug
                $this->updateCompanySlug($company->id, $company->slug);
            }
        }

        return $companies;
    }

    public function getCompanyBySlug($slug) {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM jobs WHERE company_id = c.id AND deleted_at IS NULL) as job_count 
                FROM companies c 
                WHERE c.slug = ? AND c.deleted_at IS NULL";
        $company = $this->db->fetch($sql, [$slug]);

        if ($company && empty($company->slug)) {
            $company->slug = $this->generateSlug($company->name);
            $this->updateCompanySlug($company->id, $company->slug);
        }

        return $company;
    }

    public function getCompanyJobs($companyId, $limit = 5) {
        $sql = "SELECT j.*, c.name as company_name, cat.name as category_name 
                FROM jobs j 
                LEFT JOIN companies c ON j.company_id = c.id 
                LEFT JOIN categories cat ON j.category_id = cat.id 
                WHERE j.company_id = ? AND j.deleted_at IS NULL 
                ORDER BY j.created_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$companyId, $limit]);
    }

    public function getTotalCompanies() {
        $sql = "SELECT COUNT(*) as total FROM companies WHERE deleted_at IS NULL";
        $result = $this->db->fetch($sql);
        return $result->total;
    }

    public function getCount() {
        $sql = "SELECT COUNT(*) FROM companies";
        $param = [];
        return $this->db->fetchColumn($sql, $param);
    }
    
    public function getVerifiedCount() {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) FROM companies WHERE status = ?", 
            ['verified']
        );
    }

    private function generateSlug($name) {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        return $slug;
    }

    private function updateCompanySlug($companyId, $slug) {
        // Check if slug already exists
        $existingCompany = $this->db->fetch(
            "SELECT id FROM companies WHERE slug = ? AND id != ?",
            [$slug, $companyId]
        );

        // If slug exists, append a number to make it unique
        if ($existingCompany) {
            $counter = 1;
            do {
                $newSlug = $slug . '-' . $counter;
                $existingCompany = $this->db->fetch(
                    "SELECT id FROM companies WHERE slug = ? AND id != ?",
                    [$newSlug, $companyId]
                );
                $counter++;
            } while ($existingCompany);
            $slug = $newSlug;
        }

        // Update the company with the new slug
        $this->db->execute(
            "UPDATE companies SET slug = ? WHERE id = ?",
            [$slug, $companyId]
        );
    }
}