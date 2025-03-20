<?php

namespace App\Controllers\Front;

use App\Models\SaveJob;
use App\Core\View;

class SaveJobController {
    private $saveJobModel;

    public function __construct() {
        $this->saveJobModel = new SaveJob();
    }

    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to view your saved jobs';
            header('Location: /login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        
        // Get saved jobs with pagination
        $savedJobModel = new SaveJob();
        $savedJobs = $savedJobModel->getSavedJobs($userId, $page, $perPage);
        $totalSavedJobs = $savedJobModel->countSavedJobs($userId);
        $totalPages = ceil($totalSavedJobs / $perPage);
        
        View::render('front/saved_jobs/index', [
            'title' => 'My Saved Jobs',
            'savedJobs' => $savedJobs,
            'totalSavedJobs' => $totalSavedJobs,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function toggle() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Please login to save jobs'
            ]);
            return;
        }

        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Get POST data
        $jobId = $_POST['job_id'] ?? null;

        // Validate required fields
        if (!$jobId) {
            http_response_code(400);
            echo json_encode(['error' => 'Job ID is required']);
            return;
        }

        try {
            $userId = $_SESSION['user_id'];
            $isCurrentlySaved = $this->saveJobModel->isJobSaved($jobId, $userId);

            if ($isCurrentlySaved) {
                // Unsave the job
                $this->saveJobModel->unsaveJob($jobId, $userId);
                $saved = false;
            } else {
                // Save the job
                $this->saveJobModel->saveJob($jobId, $userId);
                $saved = true;
            }

            // Return success response
            echo json_encode([
                'success' => true,
                'saved' => $saved,
                'message' => $saved ? 'Job saved successfully' : 'Job removed from saved jobs'
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to save job'
            ]);
        }
    }

    public function mySavedJobs() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to view saved jobs';
            header('Location: /auth/login?redirect=/candidate/saved-jobs');
            exit;
        }

        // Get page parameter
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        // Get saved jobs with pagination
        $savedJobs = $this->saveJobModel->getSavedJobs($_SESSION['user_id'], $page, $perPage);
        $totalSavedJobs = $this->saveJobModel->getTotalSavedJobs($_SESSION['user_id']);
        $totalPages = ceil($totalSavedJobs / $perPage);

        View::render('front/candidate/saved_jobs', [
            'title' => 'My Saved Jobs',
            'savedJobs' => $savedJobs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSavedJobs' => $totalSavedJobs
        ]);
    }
} 