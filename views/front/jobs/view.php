<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Main Job Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h1 class="h2 mb-2"><?= htmlspecialchars($job->title) ?></h1>
                            <h2 class="h5 text-muted mb-0"><?= htmlspecialchars($job->company_name) ?></h2>
                        </div>
                        <?php if ($job->is_featured): ?>
                        <span class="badge bg-warning">Featured</span>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($job->location) ?>
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-briefcase"></i> <?= htmlspecialchars($job->job_type) ?>
                        </span>
                        <?php if (!empty($job->category_name)): ?>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-tag"></i> <?= htmlspecialchars($job->category_name) ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($job->salary_min || $job->salary_max): ?>
                    <div class="mb-4">
                        <h3 class="h5 mb-2">Salary Range</h3>
                        <p class="mb-0">
                            <i class="bi bi-currency-dollar"></i>
                            <?php
                            if ($job->salary_min && $job->salary_max) {
                                echo number_format($job->salary_min) . ' - ' . number_format($job->salary_max);
                            } elseif ($job->salary_min) {
                                echo 'From ' . number_format($job->salary_min);
                            } else {
                                echo 'Up to ' . number_format($job->salary_max);
                            }
                            ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h3 class="h5 mb-2">Job Description</h3>
                        <div class="text-muted">
                            <?= nl2br(htmlspecialchars($job->description)) ?>
                        </div>
                    </div>

                    <?php if (!empty($job->requirements)): ?>
                    <div class="mb-4">
                        <h3 class="h5 mb-2">Requirements</h3>
                        <div class="text-muted">
                            <?= nl2br(htmlspecialchars($job->requirements)) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($job->benefits)): ?>
                    <div class="mb-4">
                        <h3 class="h5 mb-2">Benefits</h3>
                        <div class="text-muted">
                            <?= nl2br(htmlspecialchars($job->benefits)) ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($job->application_url)): ?>
                    <div class="mt-4">
                        <a href="<?= htmlspecialchars($job->application_url) ?>" target="_blank" class="btn btn-primary">
                            <i class="bi bi-box-arrow-up-right"></i> Apply Now
                        </a>
                    </div>
                    <?php endif; ?>

                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-outline-primary btn-sm me-2" onclick="toggleSaveJob(<?= $job->id ?>)">
                            <i class="bi bi-bookmark<?= isset($_SESSION['user_id']) && $isJobSaved ? '-fill' : '' ?>"></i> 
                            <span id="saveJobText"><?= isset($_SESSION['user_id']) && $isJobSaved ? 'Saved' : 'Save Job' ?></span>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reportModal">
                            <i class="bi bi-flag"></i> Report Job
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Job Status Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Job Details</h3>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-calendar"></i> Posted: <?= date('F j, Y', strtotime($job->created_at)) ?>
                        </li>
                        <?php if ($job->deadline): ?>
                        <li class="mb-2">
                            <i class="bi bi-clock"></i> Deadline: <?= date('F j, Y', strtotime($job->deadline)) ?>
                        </li>
                        <?php endif; ?>
                        <li>
                            <i class="bi bi-building"></i> Company: <?= htmlspecialchars($job->company_name) ?>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Similar Jobs -->
            <?php if (!empty($similarJobs)): ?>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h5 mb-3">Similar Jobs</h3>
                    <div class="list-group list-group-flush">
                        <?php foreach ($similarJobs as $similarJob): ?>
                        <a href="/jobs/<?= htmlspecialchars($similarJob->slug) ?>" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h4 class="h6 mb-1"><?= htmlspecialchars($similarJob->title) ?></h4>
                                <small class="text-muted"><?= date('M d', strtotime($similarJob->created_at)) ?></small>
                            </div>
                            <p class="mb-1 small text-muted"><?= htmlspecialchars($similarJob->company_name) ?></p>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($similarJob->location) ?>
                            </small>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <input type="hidden" name="job_id" value="<?= $job->id ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Select Reason</label>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason1" value="Suspicious Account">
                            <label class="form-check-label" for="reason1">Suspicious Account</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason2" value="Product already sold">
                            <label class="form-check-label" for="reason2">Product already sold</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason3" value="Seller not responding/phone unreachable">
                            <label class="form-check-label" for="reason3">Seller not responding/phone unreachable</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason4" value="Scam/Fraud">
                            <label class="form-check-label" for="reason4">Scam/Fraud</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason5" value="Selling counterfeit items">
                            <label class="form-check-label" for="reason5">Selling counterfeit items</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason6" value="Product no tax">
                            <label class="form-check-label" for="reason6">Product no tax</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason7" value="Selling prohibited item">
                            <label class="form-check-label" for="reason7">Selling prohibited item</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason8" value="Invalid pricing">
                            <label class="form-check-label" for="reason8">Invalid pricing</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason9" value="Items wrongly categorized">
                            <label class="form-check-label" for="reason9">Items wrongly categorized</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason10" value="Duplicate posts">
                            <label class="form-check-label" for="reason10">Duplicate posts</label>
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="reason" id="reason11" value="Irrelevant content">
                            <label class="form-check-label" for="reason11">Irrelevant content</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Additional Details (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Please provide any additional information..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReport()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSaveJob(jobId) {
    console.log('Attempting to toggle save for job ID:', jobId);
    
    fetch('/jobs/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'job_id=' + encodeURIComponent(jobId)
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            if (response.status === 401) {
                // User is not logged in, redirect to login page with return URL
                const currentPath = window.location.pathname;
                window.location.href = '/auth/login?redirect=' + encodeURIComponent(currentPath);
                throw new Error('Please login to save jobs');
            }
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            const icon = document.querySelector('button[onclick="toggleSaveJob(' + jobId + ')"] i');
            const text = document.querySelector('button[onclick="toggleSaveJob(' + jobId + ')"] span');
            
            if (data.saved) {
                icon.classList.remove('bi-bookmark');
                icon.classList.add('bi-bookmark-fill');
                text.textContent = 'Saved';
            } else {
                icon.classList.remove('bi-bookmark-fill');
                icon.classList.add('bi-bookmark');
                text.textContent = 'Save Job';
            }
        } else {
            console.error('Failed to save job:', data.message);
            alert(data.message || 'Failed to save job');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (!error.message.includes('Please login')) {
            alert('Error: ' + error.message);
        }
    });
}

function submitReport() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    
    // Validate form
    if (!formData.get('reason')) {
        alert('Please select a reason for reporting.');
        return;
    }

    // Submit the report
    fetch('/jobs/report', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            modal.hide();
            
            // Show success message
            alert('Thank you for your report. We will review it shortly.');
            
            // Reset form
            form.reset();
        } else {
            throw new Error(data.message || 'Failed to submit report');
        }
    })
    .catch(error => {
        alert('Error: ' + error.message);
    });
}
</script>

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?> 