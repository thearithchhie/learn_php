<?php

namespace App\Controllers\Front;

use App\Models\Job;
use App\Models\Category;
use App\Models\SaveJob;
use App\Core\View;

class JobController {
    private $jobModel;
    private $categoryModel;
    private $saveJobModel;

    public function __construct()
    {
        $this->jobModel = new Job();
        $this->categoryModel = new Category();
        $this->saveJobModel = new SaveJob();
    }

    public function index() {
        // Get filter parameters
        $page = $_GET['page'] ?? 1;
        $category = $_GET['category'] ?? null;
        $location = $_GET['location'] ?? null;
        $search = $_GET['search'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';

        // Get all categories for filter
        $categories = $this->categoryModel->getAllCategories();

        // Get paginated jobs with sorting
        $jobs = $this->jobModel->getLatestJobs(10, $category, $location, $search, $page, $sort);
        
        // Get total count for pagination
        $totalJobs = $this->jobModel->getTotalJobs($search, $category, $location);
        $totalPages = ceil($totalJobs / 10);

        View::render('front/jobs/index', [
            'title' => 'All Jobs',
            'jobs' => $jobs,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentCategory' => $category,
            'currentLocation' => $location,
            'currentSearch' => $search,
            'currentSort' => $sort,
            'totalJobs' => $totalJobs
        ]);
    }

    public function view($slug) {
        // Get job details by slug
        $job = $this->jobModel->getJobBySlug($slug);
        
        if (!$job) {
            // Job not found
            View::render('front/errors/404', [
                'title' => 'Job Not Found'
            ]);
            return;
        }

        // Check if job is saved by current user
        $isJobSaved = false;
        if (isset($_SESSION['user_id'])) {
            $isJobSaved = $this->saveJobModel->isJobSaved($job->id, $_SESSION['user_id']);
        }

        // Get similar jobs (same category)
        $similarJobs = $this->jobModel->getSimilarJobs($job->id, $job->category_id, 3);
        
        View::render('front/jobs/view', [
            'title' => $job->title . ' - ' . $job->company_name,
            'job' => $job,
            'similarJobs' => $similarJobs,
            'isJobSaved' => $isJobSaved
        ]);
    }

    public function browseByCategory($slug) {
        // Get category by slug
        $category = $this->categoryModel->getCategoryBySlug($slug);
        
        if (!$category) {
            // Category not found
            View::render('front/errors/404', [
                'title' => 'Category Not Found'
            ]);
            return;
        }

        // Get filter parameters
        $page = $_GET['page'] ?? 1;
        $location = $_GET['location'] ?? null;
        $search = $_GET['search'] ?? null;
        $sort = $_GET['sort'] ?? 'newest';

        // Get all categories for filter
        $categories = $this->categoryModel->getAllCategories();

        // Get paginated jobs for this category
        $jobs = $this->jobModel->getLatestJobs(10, $category->id, $location, $search, $page, $sort);
        
        // Get total count for pagination
        $totalJobs = $this->jobModel->getTotalJobs($search, $category->id, $location);
        $totalPages = ceil($totalJobs / 10);

        View::render('front/jobs/category', [
            'title' => $category->name . ' Jobs',
            'category' => $category,
            'jobs' => $jobs,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'currentCategory' => $category->id,
            'currentLocation' => $location,
            'currentSearch' => $search,
            'currentSort' => $sort,
            'totalJobs' => $totalJobs
        ]);
    }
} 