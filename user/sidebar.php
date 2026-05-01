<!-- Sidebar - Desktop -->
<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0"><i class="fas fa-seedling me-2"></i>Smart Farming PH</h4>
        <small class="text-light">Farmer Dashboard</small>
    </div>
    
    <div class="sidebar-menu">
        <a href="index.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="my-farm.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'my-farm.php' ? 'active' : ''; ?>">
            <i class="fas fa-tractor"></i>
            My Farm
        </a>
        <a href="learning-center.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'learning-center.php' ? 'active' : ''; ?>">
            <i class="fas fa-graduation-cap"></i>
            Learn
        </a>
        <a href="weather.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'weather.php' ? 'active' : ''; ?>">
            <i class="fas fa-cloud-sun"></i>
            Weather
        </a>
        <a href="profile.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
            <i class="fas fa-user"></i>
            Profile
        </a>
    </div>
    
    <div class="sidebar-footer p-3">
        <div class="text-light mb-2">
            <small>Logged in as:</small>
            <div class="fw-bold"><?php echo $_SESSION['farmer_name']; ?></div>
        </div>
        <a href="#" class="btn btn-outline-light w-100" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>
</div>