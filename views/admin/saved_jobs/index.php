<?php require_once __DIR__ . '../../layout/front/header.php'; ?>

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
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Saved Jobs</h1>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (empty($savedJobs)): ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h4 class="mb-3">You haven't saved any jobs yet</h4>
                        <p class="text-muted mb-3">When you find interesting jobs, save them for later by clicking the "Save Job" button.</p>
                        <a href="/jobs" class="btn btn-primary">Browse Jobs</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($savedJobs as $job): ?>
                        <div class="col-12 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0">
                                            <a href="/jobs/view/<?php echo $job->id; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($job->title); ?>
                                            </a>
                                        </h5>
                                        <span class="badge bg-<?php echo getJobTypeBadgeClass($job->job_type); ?>"><?php echo htmlspecialchars($job->job_type); ?></span>
                                    </div>
                                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($job->company_name); ?></h6>
                                    <div class="mb-3">
                                        <span class="text-muted me-3"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($job->location); ?></span>
                                        <span class="text-muted"><i class="bi bi-calendar me-1"></i> Saved on <?php echo date('M d, Y', strtotime($job->saved_at)); ?></span>
                                    </div>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($job->description, 0, 150)); ?>...</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="/jobs/view/<?php echo $job->id; ?>" class="btn btn-primary btn-sm">View Details</a>
                                            <?php if (isset($job->application_url) && !empty($job->application_url)): ?>
                                                <a href="<?php echo htmlspecialchars($job->application_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm">Apply Externally</a>
                                            <?php else: ?>
                                                <a href="/jobs/apply/<?php echo $job->id; ?>" class="btn btn-outline-primary btn-sm">Apply Now</a>
                                            <?php endif; ?>
                                        </div>
                                        <form action="/saved-jobs/unsave" method="POST" class="d-inline">
                                            <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this job from saved jobs?')">
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
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center mt-4">
                        <?php if ($currentPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Helper function for job type badge colors
function getJobTypeBadgeClass($jobType) {
    switch ($jobType) {
        case 'Full-time':
            return 'primary';
        case 'Part-time':
            return 'info';
        case 'Contract':
            return 'warning';
        case 'Freelance':
            return 'success';
        case 'Internship':
            return 'dark';
        default:
            return 'secondary';
    }
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>

<?php require_once __DIR__ . '../../layout/front/footer.php'; ?>