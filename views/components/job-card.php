<div class="card mb-3 job-card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0"><?php echo htmlspecialchars($job->title); ?></h5>
      <span class="badge bg-<?php echo $job->type === 'Full-time' ? 'primary' : 'info'; ?>"><?php echo htmlspecialchars($job->type); ?></span>
    </div>
    <h6 class="card-subtitle mb-2 text-muted mt-2"><?php echo htmlspecialchars($job->company_name); ?></h6>
    <p class="card-text text-truncate"><?php echo htmlspecialchars($job->short_description); ?></p>
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($job->location); ?>
        <span class="ms-3"><i class="bi bi-clock me-1"></i> <?php echo timeAgo($job->created_at); ?></span>
      </div>
      <a href="/jobs/view/<?php echo $job->id; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
    </div>
  </div>
</div>