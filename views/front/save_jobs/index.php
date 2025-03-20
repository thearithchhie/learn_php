<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Dashboard</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/dashboard" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="/profile" class="list-group-item list-group-item-action">My Profile</a>
                    <a href="/applications" class="list-group-item list-group-item-action">My Applications</a>
                    <a href="/saved-jobs" class="list-group-item list-group-item-action active">Saved Jobs</a>
                    <a href="/job-alerts" class="list-group-item list-group-item-action">Job Alerts</a>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">My Saved Jobs</h1>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Total Saved Jobs: <?php echo $totalSavedJobs; ?></div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($savedJobs)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-bookmark-heart fs-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted">You haven't saved any jobs yet</h5>
                        <p class="text-muted mb-4">Save jobs you're interested in to apply to them later</p>
                        <a href="/jobs" class="btn btn-primary">Browse Jobs</a>
                    </div>
                    <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($savedJobs as $job): ?>
                        <div class="col-md-12">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                <a href="/jobs/view/<?php echo $job->id; ?>" class="text-decoration-none"><?php echo htmlspecialchars($job->title); ?></a>
                                                <?php if ($job->is_featured): ?>
                                                <span class="badge bg-warning ms-1">Featured</span>
                                                <?php endif; ?>
                                            </h5>
                                            <h6 class="text-muted mb-2"><?php echo htmlspecialchars($job->company_name); ?></h6>
                                        </div>
                                        <div>
                                            <small class="text-muted">Saved on <?php echo date('M d, Y', strtotime($job->saved_at)); ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-primary me-1"><?php echo htmlspecialchars($job->job_type); ?></span>
                                        <span class="text-muted me-3"><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($job->location); ?></span>
                                        <?php if (!empty($job->salary_min) && !empty($job->salary_max)): ?>
                                        <span class="text-success"><i class="bi bi-cash me-1"></i>$<?php echo number_format($job->salary_min); ?> - $<?php echo number_format($job->salary_max); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="card-text small"><?php echo htmlspecialchars(substr($job->description, 0, 150)); ?>...</p>
                                    
                                    <div class="d-flex justify-content-between mt-3">
                                        <div>
                                            <a href="/jobs/view/<?php echo $job->id; ?>" class="btn btn-sm btn-outline-primary me-2">View Details</a>
                                            <?php if (isset($job->application_url) && !empty($job->application_url)): ?>
                                            <a href="<?php echo htmlspecialchars($job->application_url); ?>" target="_blank" class="btn btn-sm btn-primary">Apply Externally</a>
                                            <?php else: ?>
                                            <a href="/jobs/apply/<?php echo $job->id; ?>" class="btn btn-sm btn-primary">Apply Now</a>
                                            <?php endif; ?>
                                        </div>
                                        <form action="/saved-jobs/unsave" method="POST">
                                            <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to remove this job from saved jobs?')">
                                                <i class="bi bi-bookmark-x"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">Previous</a>
                            </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Next</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?>