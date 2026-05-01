<!-- Sidebar - Desktop -->
<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0"><i class="fas fa-seedling me-2"></i>Smart Farming PH</h4>
        <small class="text-light">Admin Panel</small>
    </div>
    
    <div class="sidebar-menu">
        <a href="index.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="farmers.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'farmers.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            Farmers Management
        </a>
        <a href="materials.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'materials.php' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            Learning Materials
        </a>
        <a href="seasons.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'seasons.php' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt"></i>
            Season Planning
        </a>
        <a href="weather.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'weather.php' ? 'active' : ''; ?>">
            <i class="fas fa-cloud-sun"></i>
            Weather Data
        </a>
        <a href="analytics.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            Analytics
        </a>
        <!-- <a href="settings.php" class="menu-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            Settings
        </a> -->
    </div>
    
    <div class="sidebar-footer p-3">
        <a href="#" class="btn btn-outline-light w-100" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt me-2"></i>Logout
        </a>
    </div>
</div>