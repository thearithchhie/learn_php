<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\Job;
use App\Models\Company;
use App\Models\Category;
use App\Models\Activity;

class JobController extends BaseAdminController {
    private $jobModel, $companyModel, $categoryModel, $activityModel;

    public function __construct()
    {
        $this->checkAdminAuth();
        $this->jobModel = new Job();
        $this->companyModel = new Company();
        $this->categoryModel = new Category();
        $this->activityModel = new Activity();
    }
    
    public function index() {
        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $search = $_GET['search'] ?? null;
        $status = $_GET['status'] ?? null;
        
        // Get paginated jobs
        $jobs = $this->jobModel->getAllJobsPaginated($page, $perPage, $search, $status);
        $totalJobs = $this->jobModel->countAllJobs($search, $status);
        
        // Calculate total pages
        $totalPages = ceil($totalJobs / $perPage);
        
        // Make sure current page is valid
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        
        View::render('admin/jobs/index', [
            'title' => 'Job Management',
            'currentPage' => 'jobs',
            'jobs' => $jobs,
            'totalJobs' => $totalJobs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status
        ]);
    }


    public function create() {
        // Get all companies and categories for the dropdowns
        $companies = $this->companyModel->getAllCompanies();
        $categories = $this->categoryModel->getAllCategories();
        
        View::render('admin/jobs/create', [
            'title' => 'Create Job',
            'currentPage' => 'jobs',
            'companies' => $companies,
            'categories' => $categories
        ]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process and validate form data
            $jobData = [
                'title' => $_POST['title'] ?? '',
                'company_id' => $_POST['company_id'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'location' => $_POST['location'] ?? '',
                'job_type' => $_POST['job_type'] ?? '',
                'salary_min' => !empty($_POST['salary_min']) ? $_POST['salary_min'] : null,
                'salary_max' => !empty($_POST['salary_max']) ? $_POST['salary_max'] : null,
                'description' => $_POST['description'] ?? '',
                'requirements' => $_POST['requirements'] ?? '',
                'benefits' => $_POST['benefits'] ?? '',
                'application_url' => $_POST['application_url'] ?? null,
                'deadline' => !empty($_POST['deadline']) ? $_POST['deadline'] : null,
                'status' => $_POST['status'] ?? 'active',
                'is_featured' => isset($_POST['is_featured']) ? 'true' : 'false',
                'created_at' => date('Y-m-d H:i:s'),
                'user_id' => $_SESSION['user_id']
            ];
            
            // Generate a slug
            $jobData['slug'] = $this->createSlug($jobData['title']);
            
            // Validate required fields
            if (empty($jobData['title']) || empty($jobData['company_id']) || empty($jobData['category_id']) || empty($jobData['description'])) {
                $_SESSION['error'] = 'Required fields are missing';
                header('Location: /admin/jobs/create');
                exit;
            }
            
            // Create the job
            $jobId = $this->jobModel->createJob($jobData);
            
            if ($jobId) {
                // Log activity if you have activity logging
                if (isset($this->activityModel)) {
                    $this->activityModel->logActivity(
                        'job_posted',
                        $_SESSION['user_id'],
                        "Job '{$jobData['title']}' was created",
                        $jobId
                    );
                }
                
                $_SESSION['success'] = 'Job created successfully';
                header('Location: /admin/jobs');
            } else {
                $_SESSION['error'] = 'Failed to create job';
                header('Location: /admin/jobs/create');
            }
            exit;
        }
    }

    private function createSlug($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }

    public function edit($id) {
        // Get the job details
        $job = $this->jobModel->getJobById($id);
        
        if (!$job) {
            $_SESSION['error'] = 'Job not found';
            header('Location: /admin/jobs');
            exit;
        }
        
        // Get all companies and categories for the dropdowns
        $companies = $this->companyModel->getAllCompanies();
        $categories = $this->categoryModel->getAllCategories();
        
        View::render('admin/jobs/edit', [
            'title' => 'Edit Job',
            'currentPage' => 'jobs',
            'job' => $job,
            'companies' => $companies,
            'categories' => $categories
        ]);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process and validate form data
            $jobData = [
                'id' => $id,
                'title' => $_POST['title'] ?? '',
                'company_id' => $_POST['company_id'] ?? '',
                'category_id' => $_POST['category_id'] ?? '',
                'location' => $_POST['location'] ?? '',
                'job_type' => $_POST['job_type'] ?? '',
                'salary_min' => !empty($_POST['salary_min']) ? $_POST['salary_min'] : null,
                'salary_max' => !empty($_POST['salary_max']) ? $_POST['salary_max'] : null,
                'description' => $_POST['description'] ?? '',
                'requirements' => $_POST['requirements'] ?? '',
                'benefits' => $_POST['benefits'] ?? '',
                'application_url' => $_POST['application_url'] ?? null,
                'deadline' => !empty($_POST['deadline']) ? $_POST['deadline'] : null,
                'status' => $_POST['status'] ?? 'active',
                'is_featured' => isset($_POST['is_featured']) ? 'true' : 'false',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Generate a slug
            $jobData['slug'] = $this->createSlug($jobData['title']);
            
            // Validate required fields
            if (empty($jobData['title']) || empty($jobData['company_id']) || empty($jobData['category_id']) || empty($jobData['description'])) {
                $_SESSION['error'] = 'Required fields are missing';
                header("Location: /admin/jobs/edit/{$id}");
                exit;
            }
            
            // Update the job
            if ($this->jobModel->updateJob($jobData)) {
                // Log activity
                if (isset($this->activityModel)) {
                    $this->activityModel->logActivity(
                        'job_updated',
                        $_SESSION['user_id'],
                        "Job '{$jobData['title']}' was updated",
                        $id
                    );
                }
                
                $_SESSION['success'] = 'Job updated successfully';
                header('Location: /admin/jobs');
            } else {
                $_SESSION['error'] = 'Failed to update job';
                header("Location: /admin/jobs/edit/{$id}");
            }
            exit;
        }
    }

    public function view($id) {
        // Get the job details
        $job = $this->jobModel->getJobById($id);
        
        if (!$job) {
            $_SESSION['error'] = 'Job not found';
            header('Location: /admin/jobs');
            exit;
        }
        
        // Get all companies for reference
        $companies = $this->companyModel->getAllCompanies();
        
        // Get recent activities for this job
        $activities = $this->activityModel->getActivitiesByEntityId('job', $id, 5);
        
        View::render('admin/jobs/view', [
            'title' => 'View Job',
            'currentPage' => 'jobs',
            'job' => $job,
            'companies' => $companies,
            'activities' => $activities
        ]);
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/jobs');
            exit;
        }

        // Get the job details first for the activity log
        $job = $this->jobModel->getJobById($id);
        
        if (!$job) {
            $_SESSION['error'] = 'Job not found';
            header('Location: /admin/jobs');
            exit;
        }

        // Try to soft delete the job
        if ($this->jobModel->deleteJob($id)) {
            // Log the activity
            $this->activityModel->logActivity(
                'job_deleted',
                $_SESSION['user_id'],
                "Job '{$job->title}' was moved to trash",
                $id
            );

            $_SESSION['success'] = 'Job has been moved to trash';
        } else {
            $_SESSION['error'] = 'Failed to delete job';
        }

        header('Location: /admin/jobs');
        exit;
    }
}
