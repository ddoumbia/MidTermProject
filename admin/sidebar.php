<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-paw"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Pet Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Dashboard Link -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Management
    </div>

    <!-- Users Link -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="users.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Manage Users</span></a>
    </li>

    <!-- Pets Link -->
    <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pets.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="pets.php">
            <i class="fas fa-fw fa-paw"></i>
            <span>Manage Pets</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Logout Link -->
    <li class="nav-item">
        <a class="nav-link" href="../auth/sign-out.php">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Sign Out</span></a>
    </li>

    <!-- Sidebar Toggler (Optional) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
