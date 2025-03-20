<?php require_once __DIR__ . '/../../layout/admin/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><?php echo htmlspecialchars($job->title); ?></h1>
    <div>
        <a href="/admin/jobs/edit/<?php echo $job->id; ?>" class="btn btn-primary me-2">
            <i class="bi bi-pencil"></i> Edit Job
        </a>
        <button type="button" class="btn btn-danger me-2" data-bs-toggle="modal" data-bs-target="#deleteJobModal">
            <i class="bi bi-trash"></i> Delete Job
        </button>
        <a href="/admin/jobs" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Jobs
        </a>
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
                <p>Are you sure you want to delete this job?</p>
                <p class="mb-0"><strong>Title:</strong> <?php echo htmlspecialchars($job->title); ?></p>
                <p class="mb-0"><strong>Company:</strong> <?php echo htmlspecialchars($job->company_name); ?></p>
                <p class="text-danger mt-3 mb-0">This action cannot be undone. All associated data will be permanently deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="/admin/jobs/delete/<?php echo $job->id; ?>" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Delete Job</button>
                </form>
            </div>
        </div>
    </div>
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

<div class="row">
    <div class="col-md-8">
        <!-- Main Job Details -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Job Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($job->title); ?></h5>
                            <p class="text-muted mb-0">
                                at <?php echo htmlspecialchars($job->company_name); ?>
                            </p>
                        </div>
                        <span class="badge bg-<?php echo $job->status === 'active' ? 'success' : ($job->status === 'pending' ? 'warning' : 'secondary'); ?>">
                            <?php echo ucfirst($job->status); ?>
                        </span>
                    </div>
                    <div class="d-flex gap-3 text-muted small">
                        <span><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($job->location); ?></span>
                        <span><i class="bi bi-briefcase me-1"></i><?php echo htmlspecialchars($job->job_type); ?></span>
                        <?php if ($job->salary_min || $job->salary_max): ?>
                            <span><i class="bi bi-currency-dollar me-1"></i>
                                <?php
                                if ($job->salary_min && $job->salary_max) {
                                    echo number_format($job->salary_min) . ' - ' . number_format($job->salary_max);
                                } elseif ($job->salary_min) {
                                    echo 'From ' . number_format($job->salary_min);
                                } elseif ($job->salary_max) {
                                    echo 'Up to ' . number_format($job->salary_max);
                                }
                                ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($job->deadline): ?>
                            <span><i class="bi bi-calendar-event me-1"></i>Deadline: <?php echo date('M d, Y', strtotime($job->deadline)); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Description</h6>
                    <div class="formatted-content">
                        <?php echo nl2br(htmlspecialchars($job->description)); ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold">Requirements</h6>
                    <div class="formatted-content">
                        <?php echo nl2br(htmlspecialchars($job->requirements)); ?>
                    </div>
                </div>

                <?php if (!empty($job->benefits)): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Benefits</h6>
                    <div class="formatted-content">
                        <?php echo nl2br(htmlspecialchars($job->benefits)); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($job->application_url)): ?>
                <div class="mb-4">
                    <h6 class="fw-bold">Application Link</h6>
                    <a href="<?php echo htmlspecialchars($job->application_url); ?>" target="_blank" class="text-decoration-none">
                        <?php echo htmlspecialchars($job->application_url); ?>
                        <i class="bi bi-box-arrow-up-right ms-1"></i>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Job Status Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Job Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="small text-muted d-block">Created</label>
                    <div><?php echo date('M d, Y \a\t h:i A', strtotime($job->created_at)); ?></div>
                </div>
                <?php if ($job->updated_at): ?>
                <div class="mb-3">
                    <label class="small text-muted d-block">Last Updated</label>
                    <div><?php echo date('M d, Y \a\t h:i A', strtotime($job->updated_at)); ?></div>
                </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label class="small text-muted d-block">Status</label>
                    <div>
                        <span class="badge bg-<?php echo $job->status === 'active' ? 'success' : ($job->status === 'pending' ? 'warning' : 'secondary'); ?>">
                            <?php echo ucfirst($job->status); ?>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block">Featured</label>
                    <div>
                        <?php if ($job->is_featured == 't'): ?>
                            <span class="badge bg-warning">Featured</span>
                        <?php else: ?>
                            <span class="text-muted">Not featured</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <?php if (!empty($activities)): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Recent Activities</h6>
            </div>
            <div class="card-body">
                <div class="timeline small">
                    <?php foreach ($activities as $activity): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <div class="text-muted mb-1"><?php echo date('M d, Y h:i A', strtotime($activity->created_at)); ?></div>
                                <div><?php echo htmlspecialchars($activity->description); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <h5 class="mb-3">Quick Stats</h5>
        <div class="d-flex flex-wrap gap-3">
            <div class="badge bg-light text-dark">
                <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($job->location) ?>
            </div>
            <div class="badge bg-light text-dark">
                <i class="bi bi-briefcase"></i> <?= htmlspecialchars($job->job_type) ?>
            </div>
            <?php if (!empty($job->category_name)): ?>
            <div class="badge bg-light text-dark">
                <i class="bi bi-tag"></i> <?= htmlspecialchars($job->category_name) ?>
            </div>
            <?php endif; ?>
            <?php if ($job->salary_min || $job->salary_max): ?>
                <div class="badge bg-light text-dark">
                    <i class="bi bi-cash"></i> 
                    <?php
                    if ($job->salary_min && $job->salary_max) {
                        echo '$' . number_format($job->salary_min) . ' - $' . number_format($job->salary_max);
                    } elseif ($job->salary_min) {
                        echo 'From $' . number_format($job->salary_min);
                    } else {
                        echo 'Up to $' . number_format($job->salary_max);
                    }
                    ?>
                </div>
            <?php endif; ?>
            <?php if ($job->deadline): ?>
                <div class="badge bg-light text-dark">
                    <i class="bi bi-calendar"></i> Deadline: <?= date('M d, Y', strtotime($job->deadline)) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.formatted-content {
    white-space: pre-line;
}
.timeline {
    position: relative;
    padding-left: 1rem;
}
.timeline-item {
    position: relative;
    padding-bottom: 1.5rem;
}
.timeline-marker {
    position: absolute;
    left: -0.75rem;
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background-color: #e9ecef;
    border: 2px solid #adb5bd;
}
.timeline-content {
    padding-left: 1rem;
    border-left: 2px solid #e9ecef;
}
</style>

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

<?php require_once __DIR__ . '/../../layout/admin/footer.php'; ?>
