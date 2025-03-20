<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Panel'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }
        .sidebar a:hover {
            color: white;
        }
        .sidebar .active {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .dashboard-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="d-flex flex-column p-3">
                    <h4 class="mb-4 px-3 py-2 border-bottom">Job Board Admin</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a href="/admin/dashboard" class="nav-link px-3 py-2 active">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="/admin/jobs" class="nav-link px-3 py-2">
                                <i class="bi bi-briefcase me-2"></i> Jobs
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="/admin/jobs" class="nav-link px-3 py-2">
                                <i class="bi bi-briefcase me-2"></i> Save Jobs
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="/admin/users" class="nav-link px-3 py-2">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="/admin/companies" class="nav-link px-3 py-2">
                                <i class="bi bi-building me-2"></i> Companies
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="/admin/settings" class="nav-link px-3 py-2">
                                <i class="bi bi-gear me-2"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main content -->
            <div class="col-md-9 col-lg-10 p-4">
                <nav class="navbar bg-white shadow-sm mb-4 rounded">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1"><?php echo $title ?? 'Admin Panel'; ?></span>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle text-decoration-none text-dark" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/admin/logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>