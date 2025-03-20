<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container py-5">
    <!-- Search and Filter Form -->
    <div class="card shadow-sm mb-5">
        <div class="card-body">
            <form action="/" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Search jobs..." value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" class="form-control" name="location" placeholder="Location..." value="<?= htmlspecialchars($location ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>" <?= ($selectedCategory == $category->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category->name) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter Jobs</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Jobs Section -->
    <?php if (!empty($featuredJobs)): ?>
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">Featured Jobs</h2>
            <a href="/jobs" class="btn btn-outline-primary">View All Jobs</a>
        </div>
        
        <div class="row">
            <?php foreach ($featuredJobs as $job): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">
                                    <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($job->title) ?>
                                    </a>
                                </h5>
                                <p class="text-muted mb-0"><?= htmlspecialchars($job->company_name) ?></p>
                            </div>
                            <span class="badge bg-warning">Featured</span>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-2">
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
                        </div>
                        
                        <p class="card-text text-muted small">
                            <?= htmlspecialchars(substr($job->description, 0, 150)) ?>...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <?php if ($job->salary_min || $job->salary_max): ?>
                            <span class="text-muted small">
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
                            </span>
                            <?php endif; ?>
                            <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="btn btn-sm btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Latest Jobs Section -->
    <section>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">Latest Jobs</h2>
            <a href="/jobs" class="btn btn-outline-primary">View All Jobs</a>
        </div>
        
        <div class="row">
            <?php foreach ($latestJobs as $job): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-1">
                            <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($job->title) ?>
                            </a>
                        </h5>
                        <p class="text-muted mb-3"><?= htmlspecialchars($job->company_name) ?></p>
                        
                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-2">
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
                        </div>
                        
                        <p class="card-text text-muted small">
                            <?= htmlspecialchars(substr($job->description, 0, 150)) ?>...
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <?php if ($job->salary_min || $job->salary_max): ?>
                            <span class="text-muted small">
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
                            </span>
                            <?php endif; ?>
                            <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="btn btn-sm btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?>