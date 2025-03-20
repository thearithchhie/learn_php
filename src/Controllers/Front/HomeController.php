<?php

namespace App\Controllers\Front;

use App\Models\Job;
use App\Models\Category;
use App\Core\View;

class HomeController {
    private $jobModel;
    private $categoryModel;

    public function __construct()
    {
        $this->jobModel = new Job();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        // Get filter parameters
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Get all categories for the filter dropdown
        $categories = $this->categoryModel->getAllCategories();
        
        // Get featured and latest jobs with filters
        $featuredJobs = $this->jobModel->getFeaturedJobs(6, $categoryId, $location, $search);
        $latestJobs = $this->jobModel->getLatestJobs(6, $categoryId, $location, $search);
        
        View::render('front/home/index', [
            'title' => 'Welcome to Job Board',
            'featuredJobs' => $featuredJobs,
            'latestJobs' => $latestJobs,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'location' => $location,
            'search' => $search
        ]);
    }
}