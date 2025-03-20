<?php
namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseAdminController;
use App\Core\View;
use App\Models\User;

class UserController extends BaseAdminController {

    private $userModel;

    public function __construct()
    {
        $this->checkAdminAuth();
        $this->userModel = new User();
    }

    public function index() {
        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $search = $_GET['search'] ?? null;
        
        // Get paginated users
        $users = $this->userModel->getAllUsersPaginated($page, $perPage, $search);
        $totalUsers = $this->userModel->countAllUsers($search);
        
        // Calculate total pages
        $totalPages = ceil($totalUsers / $perPage);
        
        // Make sure current page is valid
        if ($page < 1) $page = 1;
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        
        View::render('admin/users', [
            'title' => 'User Management',
            'currentPage' => 'users',
            'users' => $users,
            'totalUsers' => $totalUsers,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search
        ]);
    }
}
