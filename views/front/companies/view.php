<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Company Info -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if ($company->logo): ?>
                        <img src="<?= htmlspecialchars($company->logo) ?>" alt="<?= htmlspecialchars($company->name) ?>" 
                             class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        <?php endif; ?>
                        <h1 class="h3 mb-2"><?= htmlspecialchars($company->name) ?></h1>
                        <p class="text-muted mb-0">
                            <i class="bi bi-briefcase"></i> <?= $company->job_count ?> jobs
                        </p>
                    </div>

                    <?php if ($company->description): ?>
                    <div class="mb-4">
                        <h2 class="h5 mb-3">About</h2>
                        <p class="text-muted"><?= nl2br(htmlspecialchars($company->description)) ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h2 class="h5 mb-3">Company Details</h2>
                        <ul class="list-unstyled">
                            <?php if ($company->location): ?>
                            <li class="mb-2">
                                <i class="bi bi-geo-alt text-primary"></i>
                                <?= htmlspecialchars($company->location) ?>
                            </li>
                            <?php endif; ?>
                            <?php if (!empty($company->website)): ?>
                            <li class="mb-2">
                                <i class="bi bi-globe text-primary"></i>
                                <a href="<?= htmlspecialchars($company->website) ?>" target="_blank" class="text-decoration-none">
                                    <?= htmlspecialchars($company->website) ?>
                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($company->email) && !empty($company->email)): ?>
                            <li class="mb-2">
                                <i class="bi bi-envelope text-primary"></i>
                                <a href="mailto:<?= htmlspecialchars($company->email) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($company->email) ?>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Jobs -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Open Positions</h2>
                    <p class="text-muted mb-0"><?= count($jobs) ?> jobs available</p>
                </div>
            </div>

            <?php if (empty($jobs)): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <h3 class="h5 text-muted">No open positions</h3>
                    <p class="text-muted mb-0">Check back later for job opportunities</p>
                </div>
            </div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($jobs as $job): ?>
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h3 class="h5 mb-0">
                                    <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($job->title) ?>
                                    </a>
                                </h3>
                                <?php if ($job->is_featured): ?>
                                <span class="badge bg-warning">Featured</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($job->location) ?>
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-briefcase"></i> <?= htmlspecialchars($job->job_type) ?>
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($job->category_name) ?>
                                </span>
                            </div>

                            <?php if ($job->salary_min || $job->salary_max): ?>
                            <p class="mb-3">
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
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i> <?= date('M d, Y', strtotime($job->created_at)) ?>
                                </small>
                                <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="btn btn-outline-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Report Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reportForm">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="scam" value="Scam/Fraud">
                        <label class="form-check-label" for="scam">
                            Scam/Fraud
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reason" id="good" value="Good Job">
                        <label class="form-check-label" for="good">
                            Good Job
                        </label>
                    </div>
                    <!-- Add more reasons as needed -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReport()">Submit</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?>
