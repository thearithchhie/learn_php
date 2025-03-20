<?php require_once __DIR__ . '/../../layout/admin/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Job Management</h1>
    <a href="/admin/jobs/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add New Job
    </a>
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

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col-md-6">
                <form class="d-flex" action="/admin/jobs" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search jobs..." 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <div class="dropdown me-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="statusDropdown" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                        <li><a class="dropdown-item" href="/admin/jobs">All Jobs</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?status=active">Active</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?status=pending">Pending</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?status=expired">Expired</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?status=closed">Closed</a></li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" 
                            data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="/admin/jobs?sort=newest">Newest First</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?sort=oldest">Oldest First</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?sort=title">Title (A-Z)</a></li>
                        <li><a class="dropdown-item" href="/admin/jobs?sort=company">Company (A-Z)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jobs)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No jobs found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo $job->id; ?></td>
                                <td>
                                    <a href="/admin/jobs/view/<?php echo $job->id; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($job->title); ?>
                                    </a>
                                    <?php if (isset($job->is_featured) && $job->is_featured): ?>
                                        <span class="badge bg-warning ms-1">Featured</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($job->company_name ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($job->location ?? ''); ?></td>
                                <td>
                                    <?php
                                    $typeClass = 'secondary';
                                    if (isset($job->job_type)) {
                                        if ($job->job_type === 'Full-time') $typeClass = 'primary';
                                        elseif ($job->job_type === 'Part-time') $typeClass = 'info';
                                        elseif ($job->job_type === 'Contract') $typeClass = 'warning';
                                        elseif ($job->job_type === 'Internship') $typeClass = 'dark';
                                    }
                                    ?>
                                    <span class="badge bg-<?php echo $typeClass; ?>">
                                        <?php echo htmlspecialchars($job->job_type ?? 'Unknown'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (isset($job->status)): ?>
                                        <?php if ($job->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif ($job->status === 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif ($job->status === 'expired'): ?>
                                            <span class="badge bg-danger">Expired</span>
                                        <?php elseif ($job->status === 'closed'): ?>
                                            <span class="badge bg-secondary">Closed</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($job->created_at) ? date('M d, Y', strtotime($job->created_at)) : 'Unknown'; ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/jobs/view/<?php echo $job->id; ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/admin/jobs/edit/<?php echo $job->id; ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Delete" 
                                                data-bs-toggle="modal" data-bs-target="#deleteJobModal" 
                                                data-job-id="<?php echo $job->id; ?>" 
                                                data-job-title="<?php echo htmlspecialchars($job->title ?? 'Unknown Job'); ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mt-4">
                <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?><?php echo isset($_GET['status']) ? '&status='.$_GET['status'] : ''; ?><?php echo isset($_GET['sort']) ? '&sort='.$_GET['sort'] : ''; ?>" aria-label="Previous">
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
                
                <?php 
                // Show a range of page numbers
                $start = max(1, $currentPage - 2);
                $end = min($totalPages, $currentPage + 2);
                
                // Always show first page
                if ($start > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=1'.
                         (isset($_GET['search']) ? '&search='.$_GET['search'] : '').
                         (isset($_GET['status']) ? '&status='.$_GET['status'] : '').
                         (isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '').'">1</a></li>';
                    if ($start > 2) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    }
                }
                
                // Show page range
                for ($i = $start; $i <= $end; $i++): 
                ?>
                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?><?php echo isset($_GET['status']) ? '&status='.$_GET['status'] : ''; ?><?php echo isset($_GET['sort']) ? '&sort='.$_GET['sort'] : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; 
                
                // Always show last page
                if ($end < $totalPages) {
                    if ($end < $totalPages - 1) {
                        echo '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?page='.$totalPages.
                         (isset($_GET['search']) ? '&search='.$_GET['search'] : '').
                         (isset($_GET['status']) ? '&status='.$_GET['status'] : '').
                         (isset($_GET['sort']) ? '&sort='.$_GET['sort'] : '').'">'.$totalPages.'</a></li>';
                }
                ?>
                
                <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?><?php echo isset($_GET['search']) ? '&search='.$_GET['search'] : ''; ?><?php echo isset($_GET['status']) ? '&status='.$_GET['status'] : ''; ?><?php echo isset($_GET['sort']) ? '&sort='.$_GET['sort'] : ''; ?>" aria-label="Next">
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
    </div>
</div>

<!-- Delete Job Modal -->
<div class="modal fade" id="deleteJobModal" tabindex="-1" aria-labelledby="deleteJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteJobModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the job "<span id="deleteJobTitle"></span>"? 
                This action cannot be undone and will remove all applications associated with this job.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteJobForm" method="POST" action="/admin/jobs/delete/<?php echo $job->id; ?>">
                    <input type="hidden" name="job_id" id="deleteJobId" value="">
                    <button type="submit" class="btn btn-danger">Delete Job</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup delete job modal
    const deleteJobModal = document.getElementById('deleteJobModal');
    if (deleteJobModal) {
        deleteJobModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const jobId = button.getAttribute('data-job-id');
            const jobTitle = button.getAttribute('data-job-title');
            
            document.getElementById('deleteJobId').value = jobId;
            document.getElementById('deleteJobTitle').textContent = jobTitle;
        });
    }
    
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

<?php require_once __DIR__ . '/../../layout/admin/footer.php'; ?>