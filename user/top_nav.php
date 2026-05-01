<?php


$currentPage = basename($_SERVER['PHP_SELF']);
$pageTitles = [
    'index.php' => 'My Dashboard',
    'my-farm.php' => 'My Farm Management',
    'learning-center.php' => 'Learning Center',
    'weather.php' => 'Weather Forecast',
    'profile.php' => 'My Profile',
    'my-crops.php' => 'My Crops',
    'materials.php' => 'Learning Materials',
    'seasons.php' => 'Season Guide',
    'market-prices.php' => 'Market Prices',
    'help.php' => 'Help & Support'
];
?>

<div class="top-nav">
    <div class="d-flex align-items-center">
        <div>
            <h4 class="mb-0">
                <?php echo $pageTitles[$currentPage] ?? 'Smart Farming PH'; ?>
            </h4>
            <small class="text-muted">
                Welcome back, <?php echo $_SESSION['farmer_name']; ?> 
                <?php if (!empty($_SESSION['farm_name'])): ?>
                    from <?php echo $_SESSION['farm_name']; ?>
                <?php endif; ?>
            </small>
        </div>
    </div>
    <div class="user-info">
        <div class="user-avatar">
            <?php 
            $initials = '';
            $nameParts = explode(' ', $_SESSION['farmer_name']);
            if (count($nameParts) >= 2) {
                $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
            } else {
                $initials = strtoupper(substr($_SESSION['farmer_name'], 0, 2));
            }
            echo $initials;
            ?>
        </div>
        <div class="d-none d-md-block">
            <div class="fw-semibold"><?php echo $_SESSION['farmer_name']; ?></div>
            <small class="text-muted">Farmer</small>
            <?php if (!empty($_SESSION['farm_location'])): ?>
                <small class="text-muted d-block"><?php echo $_SESSION['farm_location']; ?></small>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.top-nav {
    background: #fff;
    border-bottom: 1px solid #e3e6f0;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .top-nav {
        padding: 1rem;
    }
    
    .top-nav h4 {
        font-size: 1.1rem;
    }
    
    .top-nav .text-muted {
        font-size: 0.8rem;
    }
}
</style>
