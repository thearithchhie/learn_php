<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?> text-white" href="/admin/dashboard">
          <i class="bi bi-speedometer2 me-2"></i>
          Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'jobs' ? 'active' : ''; ?> text-white" href="/admin/jobs">
          <i class="bi bi-briefcase me-2"></i>
          Jobs
        </a>
      </li>

     <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'users' ? 'active' : ''; ?> text-white" href="/admin/users">
          <i class="bi bi-people me-2"></i>
          Users
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'users' ? 'active' : ''; ?> text-white" href="/admin/users">
          <i class="bi bi-people me-2"></i>
          Users
        </a>
      </li>

      <!-- <li class="nav-item">
    <a class="nav-link <?php echo $currentPage === 'users' ? 'active' : ''; ?> text-white" href="/admin/users">
        <i class="bi bi-people me-2"></i>
        Users
    </a>
</li> -->

      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'companies' ? 'active' : ''; ?> text-white" href="/admin/companies">
          <i class="bi bi-building me-2"></i>
          Companies
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'categories' ? 'active' : ''; ?> text-white" href="/admin/categories">
          <i class="bi bi-tags me-2"></i>
          Categories
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'applications' ? 'active' : ''; ?> text-white" href="/admin/applications">
          <i class="bi bi-file-earmark-text me-2"></i>
          Applications
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?> text-white" href="/admin/settings">
          <i class="bi bi-gear me-2"></i>
          Settings
        </a>
      </li>
    </ul>
    
    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
      <span>Site Management</span>
    </h6>
    <ul class="nav flex-column mb-2">
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'pages' ? 'active' : ''; ?> text-white" href="/admin/pages">
          <i class="bi bi-file-earmark me-2"></i>
          Pages
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo $currentPage === 'emails' ? 'active' : ''; ?> text-white" href="/admin/emails">
          <i class="bi bi-envelope me-2"></i>
          Email Templates
        </a>
      </li>
    </ul>
  </div>
</nav>