<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Models\SaveJob;

class SaveJobController extends BaseAdminController {
    private $saveJobModel;

    public function __construct() {
        $this->checkAdminAuth();
        $this->saveJobModel = new SaveJob();
    }

    public function index() {
        // Get page parameter
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        // Get saved jobs with pagination
        $savedJobs = $this->saveJobModel->getSavedJobs($page, $perPage);
        $totalSavedJobs = $this->saveJobModel->getSavedJobsCount();
        $totalPages = ceil($totalSavedJobs / $perPage);

        View::render('admin/save_jobs/index', [
            'title' => 'Saved Jobs',
            'savedJobs' => $savedJobs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSavedJobs' => $totalSavedJobs
        ]);
    }

    public function delete($id) {
        try {
            $this->saveJobModel->unsaveJob($id, $_SESSION['user_id']);
            $_SESSION['success'] = 'Job removed from saved jobs successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to remove job from saved jobs';
        }
        
        header('Location: /admin/save-jobs');
        exit;
    }
}


