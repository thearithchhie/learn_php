<?php
namespace App\Models;

use App\Core\Database;

class Category {
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->db->fetchAll($sql);
    }

    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }

    public function getCategoryBySlug($slug) {
        $sql = "SELECT * FROM categories WHERE slug = ?";
        return $this->db->fetch($sql, [$slug]);
    }
} 