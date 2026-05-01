<!-- Bottom Navigation - Mobile -->
<div class="bottom-nav">
    <a href="index.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
    <a href="my-farm.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'my-farm.php' ? 'active' : ''; ?>">
        <i class="fas fa-tractor"></i>
        <span>My Farm</span>
    </a>
    <a href="learning-center.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'learning-center.php' ? 'active' : ''; ?>">
        <i class="fas fa-graduation-cap"></i>
        <span>Learn</span>
    </a>
    <a href="weather.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'weather.php' ? 'active' : ''; ?>">
        <i class="fas fa-cloud-sun"></i>
        <span>Weather</span>
    </a>
    <a href="profile.php" class="bottom-nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
    <a href="#" class="bottom-nav-item" onclick="confirmLogout()">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </a>
</div>