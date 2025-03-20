<?php

namespace App\Controllers\Front;

use App\Models\Company;
use App\Core\View;

class CompanyController {
    private $companyModel;

    public function __construct()
    {
        $this->companyModel = new Company();
    }

    public function index() {
        // Get page parameter
        $page = $_GET['page'] ?? 1;
        $perPage = 12;

        // Get paginated companies
        $companies = $this->companyModel->getAllCompanies($page, $perPage);
        
        // Get total count for pagination
        $totalCompanies = $this->companyModel->getTotalCompanies();
        $totalPages = ceil($totalCompanies / $perPage);

        View::render('front/companies/index', [
            'title' => 'Companies',
            'companies' => $companies,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCompanies' => $totalCompanies
        ]);
    }

    public function view($slug) {
        // Get company details by slug
        $company = $this->companyModel->getCompanyBySlug($slug);
        
        if (!$company) {
            // Company not found
            View::render('front/errors/404', [
                'title' => 'Company Not Found'
            ]);
            return;
        }

        // Get company's latest jobs
        $jobs = $this->companyModel->getCompanyJobs($company->id);
        
        View::render('front/companies/view', [
            'title' => $company->name,
            'company' => $company,
            'jobs' => $jobs
        ]);
    }
} 