<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="h5 mb-3">Filters</h3>
                    
                    <!-- Search Form -->
                    <form action="/jobs" method="GET" class="mb-4">
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?= htmlspecialchars($currentSearch ?? '') ?>" 
                                   placeholder="Search jobs...">
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat->id ?>" <?= $currentCategory == $cat->id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat->name) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="<?= htmlspecialchars($currentLocation ?? '') ?>" 
                                   placeholder="Enter location...">
                        </div>

                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="newest" <?= $currentSort === 'newest' ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= $currentSort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                                <option value="title" <?= $currentSort === 'title' ? 'selected' : '' ?>>Title (A-Z)</option>
                                <option value="company" <?= $currentSort === 'company' ? 'selected' : '' ?>>Company (A-Z)</option>
                                <option value="salary_high" <?= $currentSort === 'salary_high' ? 'selected' : '' ?>>Salary (High to Low)</option>
                                <option value="salary_low" <?= $currentSort === 'salary_low' ? 'selected' : '' ?>>Salary (Low to High)</option>
                                <option value="deadline" <?= $currentSort === 'deadline' ? 'selected' : '' ?>>Application Deadline</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Jobs List -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">All Jobs</h1>
                <div class="text-muted">
                    <?= $totalJobs ?> jobs found
                </div>
            </div>

            <?php if (empty($jobs)): ?>
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <h3 class="h5 text-muted">No jobs found</h3>
                    <p class="text-muted mb-0">Try adjusting your filters or search terms</p>
                </div>
            </div>
            <?php else: ?>
            <div class="row g-4">
                <?php foreach ($jobs as $job): ?>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h2 class="h5 mb-0">
                                    <a href="/jobs/<?= htmlspecialchars($job->slug) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($job->title) ?>
                                    </a>
                                </h2>
                                <?php if ($job->is_featured): ?>
                                <span class="badge bg-warning">Featured</span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="text-muted mb-2"><?= htmlspecialchars($job->company_name) ?></p>
                            
                            <div class="d-flex flex-wrap gap-2 mb-3">
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

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>&category=<?= $currentCategory ?>&location=<?= $currentLocation ?>&search=<?= $currentSearch ?>&sort=<?= $currentSort ?>">
                            Previous
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&category=<?= $currentCategory ?>&location=<?= $currentLocation ?>&search=<?= $currentSearch ?>&sort=<?= $currentSort ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>&category=<?= $currentCategory ?>&location=<?= $currentLocation ?>&search=<?= $currentSearch ?>&sort=<?= $currentSort ?>">
                            Next
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?> 