<?php

namespace App\Controllers\Front;

use App\Models\JobReport;
use App\Core\View;

class JobReportController {
    private $jobReportModel;

    public function __construct() {
        $this->jobReportModel = new JobReport();
    }

    public function submit() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        // Get POST data
        $jobId = $_POST['job_id'] ?? null;
        $reason = $_POST['reason'] ?? null;
        $description = $_POST['description'] ?? null;

        // Validate required fields
        if (!$jobId || !$reason) {
            http_response_code(400);
            echo json_encode(['error' => 'Job ID and reason are required']);
            return;
        }

        try {
            // Create the report
            $this->jobReportModel->createReport($jobId, $reason, $description);

            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Report submitted successfully'
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to submit report',
                'message' => $e->getMessage()
            ]);
        }
    }
} 