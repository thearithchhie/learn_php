<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4">My Saved Jobs</h1>

            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); endif; ?>

            <?php if (empty($savedJobs)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <h2 class="h5 text-muted mb-3">No saved jobs found</h2>
                    <p class="mb-3">Start exploring jobs and save the ones you're interested in.</p>
                    <a href="/jobs" class="btn btn-primary">Browse Jobs</a>
                </div>
            </div>
            <?php else: ?>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Saved Jobs: <?= $totalSavedJobs ?></span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($savedJobs as $job): ?>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="h5 mb-1">
                                        <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($job->title) ?>
                                        </a>
                                        <?php if ($job->is_featured): ?>
                                        <span class="badge bg-warning">Featured</span>
                                        <?php endif; ?>
                                    </h2>
                                    <p class="mb-1 text-muted">
                                        <i class="bi bi-building"></i> <?= htmlspecialchars($job->company_name) ?>
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($job->location) ?>
                                        <?php if (!empty($job->category_name)): ?>
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-tag"></i> <?= htmlspecialchars($job->category_name) ?>
                                        <?php endif; ?>
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        Saved on <?= date('F j, Y', strtotime($job->created_at)) ?>
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="btn btn-outline-primary btn-sm me-2">
                                        View Details
                                    </a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="toggleSaveJob(<?= $job->id ?>)">
                                        <i class="bi bi-bookmark-fill"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === (int)$currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleSaveJob(jobId) {
    if (!confirm('Are you sure you want to remove this job from your saved jobs?')) {
        return;
    }

    fetch('/jobs/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'job_id=' + jobId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show updated list
            window.location.reload();
        } else {
            alert(data.message || 'Failed to remove job');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}
</script>

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?> 