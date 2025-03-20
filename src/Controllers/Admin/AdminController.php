<?php
namespace App\Controllers;

use App\Controllers\Admin\BaseAdminController;
use App\Core\View;
use App\Models\Application;
use App\Models\User;
use App\Models\Company;
use App\Models\Job;

class AdminController extends BaseAdminController {
    private $userModel, 
            $authModel,
            $applicationModel,
            $companyModel,
            $jobModel;

    public function __construct() {
         // Check admin authentication
        //  $this->authModel = new Auth();
        //  if (!$this->authModel->isLoggedIn()) {
        //      header('Location: /admin/login');
        //      exit;
        //  }
        $this->checkAdminAuth();


        
        $this->userModel = new User();
        $this->jobModel = new Job();
        $this->applicationModel = new Application();
        $this->companyModel = new Company();
    }

    public function dashboard() {
        $stats = [
            "users" => $this->userModel->getCount() ?? 0,
            "jobs" => $this->jobModel->getCount() ?? 0,
            "applications" => $this->applicationModel->getCount() ?? 0,
            "companies" => $this->companyModel->getCount() ?? 0
        ];
        
        View::render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ]);
    }

   


    // public function users() {
    //     // Get users data
    //     $users = $this->userModel->getAllUsers(); // Implement this method in your User model
        
    //     View::render('admin/users', [
    //         'title' => 'User Management',
    //         'currentPage' => 'users', // This is important for highlighting the active nav item
    //         'users' => $users
    //     ]);
    // }

     // Implement the jobs page
    //  public function jobs() {
    //     $jobs = $this->jobModel->getRecentJobs(20);
        
    //     View::render('admin/jobs', [
    //         'title' => 'Job Management',
    //         'jobs' => $jobs
    //     ]);
    // }

    // public function jobs() {
    //    // Get query parameters for filtering and pagination
    // $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    // $search = $_GET['search'] ?? null;
    // $status = $_GET['status'] ?? null;
    // $sort = $_GET['sort'] ?? 'newest';
    // $limit = 10; // Jobs per page
    
    // // Get jobs with pagination
    // $jobs = $this->jobModel->getAllJobs($page, $limit, $search, $status, $sort);
    // $totalJobs = $this->jobModel->getTotalJobs($search, $status);
    // $totalPages = ceil($totalJobs / $limit);
    
    // // Render view
    // View::render('admin/jobs', [
    //     'title' => 'Job Management',
    //     'currentPage' => 'jobs',
    //     'jobs' => $jobs,
    //     'totalJobs' => $totalJobs,
    //     'currentPage' => $page,
    //     'totalPages' => $totalPages,
    //     'search' => $search,
    //     'status' => $status,
    //     'sort' => $sort
    // ]);
    // }

   
    
}