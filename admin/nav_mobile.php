<!-- Bottom Navigation - Mobile -->
<div class="bottom-nav">
    <a href="index.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="farmers.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'farmers.php' ? 'active' : ''; ?>">
        <i class="fas fa-users"></i>
        <span>Farmers</span>
    </a>
    <a href="materials.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'materials.php' ? 'active' : ''; ?>">
        <i class="fas fa-book"></i>
        <span>Materials</span>
    </a>
    <a href="weather.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'weather.php' ? 'active' : ''; ?>">
        <i class="fas fa-cloud-sun"></i>
        <span>Weather</span>
    </a>
    <a href="analytics.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'analytics.php' ? 'active' : ''; ?>">
        <i class="fas fa-chart-bar"></i>
        <span>Analytics</span>
    </a>
    <a href="#" class="bottom-nav-item" onclick="confirmLogout()">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>