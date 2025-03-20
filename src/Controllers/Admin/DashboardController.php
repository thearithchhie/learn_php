<?php
namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseAdminController;
use App\Core\View;
use App\Models\User;
use App\Models\Job;
use App\Models\Company;
use App\Models\Application;

class DashboardController extends BaseAdminController {
    private $userModel;
    private $jobModel;
    private $companyModel;
    private $applicationModel;
    
    public function __construct() {
        $this->checkAdminAuth();
        
        $this->userModel = new User();
        $this->jobModel = new Job();
        $this->companyModel = new Company();
        $this->applicationModel = new Application();
    }
    
    public function index() {
        // Dashboard statistics
        $stats = [
            'users' => $this->userModel->getCount() ?? 0,
            'jobs' => $this->jobModel->getCount() ?? 0,
            'applications' => $this->applicationModel->getCount() ?? 0,
            'companies' => $this->companyModel->getCount() ?? 0
        ];
        
        // Get recent items
       // $recentJobs = $this->jobModel->getRecentJobs(5);
        
        View::render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'currentPage' => 'dashboard',
            'stats' => $stats,
            // 'recentJobs' => $recentJobs
        ]);
    }
}