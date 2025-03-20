<?php
// Set the current page for sidebar highlighting
$currentPage = 'dashboard';
// Include the header (which includes navigation and sidebar)
require_once '../views/layout/admin/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Admin Dashboard</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
      <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
      <button type="button" class="btn btn-sm btn-outline-secondary">Print</button>
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
      <i class="bi bi-calendar"></i>
      This week
    </button>
  </div>
</div>

<!-- Dashboard Stats -->
<div class="row mb-4">
  <div class="col-md-3">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="card-title">Total Jobs</h6>
            <h2 class="mb-0"><?php echo $stats->total_jobs ?? 0; ?></h2>
          </div>
          <i class="bi bi-briefcase fs-1"></i>
        </div>
        <small><?php echo $stats->active_jobs ?? 0; ?> active jobs</small>
      </div>
    </div>
  </div>
  <!-- Add other stat cards here -->
</div>

<!-- Recent Activity -->
<h4 class="mb-3">Recent Activity</h4>
<div class="card mb-4">
  <!-- Activity table content -->
</div>

<!-- Latest Jobs -->
<h4 class="mb-3">Latest Jobs</h4>
<div class="card mb-4">
  <!-- Jobs table content -->
</div>

<!-- Delete Job Modal -->
<div class="modal fade" id="deleteJobModal" tabindex="-1" aria-labelledby="deleteJobModalLabel" aria-hidden="true">
  <!-- Modal content -->
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Delete Job Modal JavaScript
  });
</script>

<?php require_once '../views/layout/admin/footer.php'; ?>