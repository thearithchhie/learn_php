<?php require_once '../views/layout/header.php'; ?>
<?php require_once '../views/layout/navigation.php'; ?>

<div class="container mt-4">
  <div class="row">
    <!-- Left sidebar with filters -->
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Search Filters</h5>
        </div>
        <div class="card-body">
          <form action="/jobs" method="GET">
            <div class="mb-3">
              <label for="keywords" class="form-label">Keywords</label>
              <input type="text" class="form-control" id="keywords" name="keywords" 
                     value="<?php echo htmlspecialchars($_GET['keywords'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
              <label for="location" class="form-label">Location</label>
              <input type="text" class="form-control" id="location" name="location"
                     value="<?php echo htmlspecialchars($_GET['location'] ?? ''); ?>">
            </div>
            
            <div class="mb-3">
              <label class="form-label">Job Type</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="job_type[]" value="Full-time" id="fullTime">
                <label class="form-check-label" for="fullTime">Full-time</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="job_type[]" value="Part-time" id="partTime">
                <label class="form-check-label" for="partTime">Part-time</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="job_type[]" value="Contract" id="contract">
                <label class="form-check-label" for="contract">Contract</label>
              </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Right content with job listings -->
    <div class="col-md-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Available Jobs</h2>
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
            Sort By
          </button>
          <ul class="dropdown-menu" aria-labelledby="sortDropdown">
            <li><a class="dropdown-item" href="?sort=newest">Newest</a></li>
            <li><a class="dropdown-item" href="?sort=salary_high">Salary (High to Low)</a></li>
            <li><a class="dropdown-item" href="?sort=salary_low">Salary (Low to High)</a></li>
          </ul>
        </div>
      </div>
      
      <?php if (empty($jobs)): ?>
        <div class="alert alert-info">No jobs found matching your criteria.</div>
      <?php else: ?>
        <?php foreach ($jobs as $job): ?>
          <?php include '../views/components/job-card.php'; ?>
        <?php endforeach; ?>
        
        <!-- Pagination -->
        <?php include '../views/components/pagination.php'; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once '../views/layout/footer.php'; ?>