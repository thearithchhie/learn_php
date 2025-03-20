<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'JobBoard - Find Your Dream Job'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation Bar -->
    <header class="bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <span class="fw-bold text-primary">Job</span><span class="fw-bold">Board</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarMain">
                    <!-- Category dropdown -->
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown">
                            All Categories
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/jobs?category=tech">Technology</a></li>
                            <li><a class="dropdown-item" href="/jobs?category=finance">Finance</a></li>
                            <li><a class="dropdown-item" href="/jobs?category=healthcare">Healthcare</a></li>
                            <li><a class="dropdown-item" href="/jobs?category=education">Education</a></li>
                            <li><a class="dropdown-item" href="/jobs?category=service">Customer Service</a></li>
                        </ul>
                    </div>
                    
                    <!-- Search form -->
                    <form class="d-flex me-auto" action="/jobs/search" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="What job are you looking for...">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                    
                    <!-- User menu -->
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="/dashboard"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                                    <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i> My Profile</a></li>
                                    <li><a class="dropdown-item" href="/applications"><i class="bi bi-file-earmark-text me-2"></i> My Applications</a></li>
                                    <li><a class="dropdown-item" href="/saved-jobs"><i class="bi bi-bookmark-heart me-2"></i> Saved Jobs</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="/logout"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item me-2">
                                <a class="nav-link" href="/login">Login</a>
                            </li>
                            <li class="nav-item me-2">
                                <a class="nav-link" href="/register">Register</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="btn btn-warning" href="<?php echo isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'employer' ? '/jobs/create' : '/register?type=employer'; ?>">
                                <i class="bi bi-plus-circle me-1"></i> Post Job
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>