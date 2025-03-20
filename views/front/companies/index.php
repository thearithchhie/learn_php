<?php require_once __DIR__ . '/../../layout/front/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-1">Companies</h1>
            <p class="text-muted mb-0">Browse all companies posting jobs</p>
        </div>
        <div class="text-muted">
            <?= $totalCompanies ?> companies found
        </div>
    </div>

    <?php if (empty($companies)): ?>
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <h3 class="h5 text-muted">No companies found</h3>
            <p class="text-muted mb-0">Check back later for company listings</p>
        </div>
    </div>
    <?php else: ?>
    <div class="row g-4">
        <?php foreach ($companies as $company): ?>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <?php if (!empty($company->logo)): ?>
                        <img src="<?= htmlspecialchars($company->logo) ?>" alt="<?= htmlspecialchars($company->name) ?>" 
                             class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                        <?php endif; ?>
                        <div>
                            <h2 class="h5 mb-1">
                                <a href="/companies/<?= htmlspecialchars($company->slug ?? '') ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($company->name) ?>
                                </a>
                            </h2>
                            <p class="text-muted mb-0">
                                <i class="bi bi-briefcase"></i> <?= $company->job_count ?? 0 ?> jobs
                            </p>
                        </div>
                    </div>

                    <?php if (!empty($company->description)): ?>
                    <p class="text-muted mb-3">
                        <?= htmlspecialchars(substr($company->description, 0, 100)) ?>...
                    </p>
                    <?php endif; ?>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <?php if (!empty($company->location)): ?>
                        <span class="badge bg-light text-dark">
                            <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($company->location) ?>
                        </span>
                        <?php endif; ?>
                        <?php if (!empty($company->website)): ?>
                        <a href="<?= htmlspecialchars($company->website) ?>" target="_blank" class="badge bg-light text-dark text-decoration-none">
                            <i class="bi bi-globe"></i> Website
                        </a>
                        <?php endif; ?>
                    </div>

                    <a href="/companies/<?= htmlspecialchars($company->slug ?? '') ?>" class="btn btn-outline-primary btn-sm w-100">
                        View Company
                    </a>
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
                <a class="page-link" href="?page=<?= $currentPage - 1 ?>">Previous</a>
            </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
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

<?php require_once __DIR__ . '/../../layout/front/footer.php'; ?>
