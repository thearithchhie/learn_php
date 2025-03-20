<?php
namespace App\Controllers\Admin;

use App\Models\Auth;

class BaseAdminController {
    protected function checkAdminAuth() {
        $authModel = new Auth();
        if (!$authModel->isLoggedIn() || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header('Location: /admin/login');
            exit;
        }
    }
}