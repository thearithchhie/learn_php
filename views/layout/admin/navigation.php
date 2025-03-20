<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="/admin/dashboard">JobBoard Admin</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="/admin/notifications">
        <i class="bi bi-bell"></i>
        <?php if (isset($unreadNotifications) && $unreadNotifications > 0): ?>
          <span class="position-absolute top-25 start-75 translate-middle badge rounded-pill bg-danger">
            <?php echo $unreadNotifications; ?>
          </span>
        <?php endif; ?>
      </a>
    </div>
  </div>
  <div class="navbar-nav">
    <div class="nav-item dropdown">
      <a class="nav-link px-3 dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle me-1"></i>
        <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li><a class="dropdown-item" href="/admin/profile">My Profile</a></li>
        <li><a class="dropdown-item" href="/admin/settings">Settings</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="/admin/logout">Sign out</a></li>
      </ul>
    </div>
  </div>
</header>