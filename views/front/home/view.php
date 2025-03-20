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

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?>