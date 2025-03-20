<?php require_once __DIR__ . '/../layout/admin/header.php'; ?>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-primary text-white h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white-50 mb-1">TOTAL USERS</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo $stats['users']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-success text-white h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white-50 mb-1">JOBS POSTED</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo $stats['jobs']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-briefcase-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-info text-white h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white-50 mb-1">APPLICATIONS</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo $stats['applications']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-file-earmark-text-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card dashboard-card bg-warning text-white h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs text-white-50 mb-1">COMPANIES</div>
                        <div class="h5 mb-0 font-weight-bold"><?php echo $stats['companies']; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-building-fill fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Recent Activities</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($activities)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">No recent activities found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($activities as $activity): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($activity->description); ?></td>
                                        <td><?php echo htmlspecialchars($activity->user_name ?? $activity->user_email ?? 'Unknown User'); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($activity->created_at)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Latest Jobs</h6>
                <a href="/admin/jobs" class="btn btn-sm btn-primary">View All Jobs</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Posted On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recentJobs)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No jobs found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentJobs as $job): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($job->title); ?></td>
                                        <td><?php echo htmlspecialchars($job->company_name); ?></td>
                                        <td>
                                            <?php if ($job->status === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif ($job->status === 'inactive'): ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php elseif ($job->status === 'expired'): ?>
                                                <span class="badge bg-danger">Expired</span>
                                            <?php elseif ($job->status === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($job->created_at)); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/admin/jobs/view/<?php echo $job->id; ?>" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="/admin/jobs/edit/<?php echo $job->id; ?>" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteJobModal" data-job-id="<?php echo $job->id; ?>">
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
            </div>
        </div>
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
                Are you sure you want to delete this job? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteJobForm" method="POST" action="/admin/jobs/delete/<?php echo $job->id; ?>">
                    <input type="hidden" name="job_id" id="deleteJobId" value="">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up delete job modal
    const deleteJobModal = document.getElementById('deleteJobModal');
    if (deleteJobModal) {
        deleteJobModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const jobId = button.getAttribute('data-job-id');
            document.getElementById('deleteJobId').value = jobId;
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layout/admin/footer.php'; ?>