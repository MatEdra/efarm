<div class="top-nav">
    <div class="d-flex align-items-center">
        <div>
            <h4 class="mb-0">
                <?php
                $currentPage = basename($_SERVER['PHP_SELF']);
                $pageTitles = [
                    'dashboard.php' => 'Dashboard Overview',
                    'farmers.php' => 'Manage Farmers',
                    'materials.php' => 'Learning Materials',
                    'seasons.php' => 'Crop Season Planning',
                    'weather.php' => 'Weather Monitoring',
                    'analytics.php' => 'Reports & Analytics',
                    'settings.php' => 'System Configuration'
                ];
                echo $pageTitles[$currentPage] ?? 'Smart Farming Admin';
                ?>
            </h4>
            <small class="text-muted">Welcome back, <?php echo $user['name']; ?></small>
        </div>
    </div>
    <div class="user-info">
        <div class="user-avatar">
            <?php echo strtoupper(substr($user['name'], 0, 2)); ?>
        </div>
        <div class="d-none d-md-block">
            <div class="fw-semibold"><?php echo $user['name']; ?></div>
            <small class="text-muted"><?php echo ucfirst($user['role']); ?> Administrator</small>
        </div>
    </div>
</div>