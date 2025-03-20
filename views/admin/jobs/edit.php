<?php require_once __DIR__ . '/../../layout/admin/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Edit Job</h1>
    <a href="/admin/jobs" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Jobs
    </a>
</div>

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
        <h6 class="m-0 font-weight-bold">Job Details</h6>
    </div>
    <div class="card-body">
        <form action="/admin/jobs/update/<?php echo htmlspecialchars($job->id); ?>" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($job->id); ?>">
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="title" class="form-label">Job Title*</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($job->title); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="company_id" class="form-label">Company*</label>
                    <select class="form-select" id="company_id" name="company_id" required>
                        <option value="">Select a company</option>
                        <?php foreach ($companies as $company): ?>
                            <option value="<?= $company->id ?>" <?= $job->company_id == $company->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($company->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Category*</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->id ?>" <?= $job->category_id == $category->id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="location" class="form-label">Location*</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($job->location); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="job_type" class="form-label">Job Type*</label>
                    <select class="form-select" id="job_type" name="job_type" required>
                        <?php
                        $jobTypes = ['Full-time', 'Part-time', 'Contract', 'Freelance', 'Internship'];
                        foreach ($jobTypes as $type):
                        ?>
                            <option value="<?php echo $type; ?>" <?php echo ($job->job_type == $type) ? 'selected' : ''; ?>>
                                <?php echo $type; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="salary_min" class="form-label">Minimum Salary</label>
                    <input type="number" class="form-control" id="salary_min" name="salary_min" value="<?php echo htmlspecialchars($job->salary_min); ?>">
                </div>
                <div class="col-md-4">
                    <label for="salary_max" class="form-label">Maximum Salary</label>
                    <input type="number" class="form-control" id="salary_max" name="salary_max" value="<?php echo htmlspecialchars($job->salary_max); ?>">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status*</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php
                        $statuses = ['active', 'pending', 'closed'];
                        foreach ($statuses as $status):
                        ?>
                            <option value="<?php echo $status; ?>" <?php echo ($job->status == $status) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Job Description*</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($job->description); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements*</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="4" required><?php echo htmlspecialchars($job->requirements); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="benefits" class="form-label">Benefits</label>
                <textarea class="form-control" id="benefits" name="benefits" rows="3"><?php echo htmlspecialchars($job->benefits); ?></textarea>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="application_url" class="form-label">Application URL</label>
                    <input type="url" class="form-control" id="application_url" name="application_url" placeholder="https://..." value="<?php echo htmlspecialchars($job->application_url); ?>">
                    <small class="form-text text-muted">External application link (leave empty to use internal applications)</small>
                </div>
                <div class="col-md-6">
                    <label for="deadline" class="form-label">Application Deadline</label>
                    <input type="date" class="form-control" id="deadline" name="deadline" value="<?php echo $job->deadline ? date('Y-m-d', strtotime($job->deadline)) : ''; ?>">
                </div>
            </div>
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo ($job->is_featured == 't') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_featured">
                        Feature this job (featured jobs appear at the top of listings)
                    </label>
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="/admin/jobs" class="btn btn-secondary me-md-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Job</button>
            </div>
        </form>
    </div>
</div>

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
