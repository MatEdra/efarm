<?php
include_once 'include/auth.php';

// Get farmer ID from URL
$farmerId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($farmerId === 0) {
    header('Location: farmers.php');
    exit();
}

// Fetch farmer details
include_once '../include/conn.php';
$farmer = null;
$crops = [];

try {
    $stmt = $conn->prepare("
        SELECT f.*, GROUP_CONCAT(fc.crop_type) as crop_list
        FROM farmers f
        LEFT JOIN farmer_crops fc ON f.id = fc.farmer_id
        WHERE f.id = ?
        GROUP BY f.id
    ");
    $stmt->bind_param("i", $farmerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $farmer = $result->fetch_assoc();
        $crops = $farmer['crop_list'] ? explode(',', $farmer['crop_list']) : [];
    } else {
        header('Location: farmers.php');
        exit();
    }
} catch (Exception $e) {
    // Handle error
    header('Location: farmers.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Details - Smart Farming Philippines</title>
    <?php include __DIR__ . '/../pwa_head.php'; ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php include("top_nav.php"); ?>

        <!-- Farmer Details Content -->
        <div id="farmerDetails">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="farmers.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Farmers
                </a>
            </div>

            <div class="row">
                <!-- Farmer Information -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user me-2"></i>Farmer Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Full Name</label>
                                        <p class="form-control-static"><?php echo htmlspecialchars($farmer['first_name'] . ' ' . $farmer['last_name']); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Farmer ID</label>
                                        <p class="form-control-static">#<?php echo $farmer['id']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Email Address</label>
                                        <p class="form-control-static">
                                            <i class="fas fa-envelope me-2 text-muted"></i>
                                            <?php echo htmlspecialchars($farmer['email']); ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Phone Number</label>
                                        <p class="form-control-static">
                                            <i class="fas fa-phone me-2 text-muted"></i>
                                            <?php echo htmlspecialchars($farmer['phone_number']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Years of Experience</label>
                                        <p class="form-control-static">
                                            <span class="badge bg-info"><?php echo $farmer['experience_years'] ?? 0; ?> years</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Member Since</label>
                                        <p class="form-control-static">
                                            <?php echo date('F j, Y', strtotime($farmer['created_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farm Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-tractor me-2"></i>Farm Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Farm Name</label>
                                        <p class="form-control-static"><?php echo htmlspecialchars($farmer['farm_name'] ?? 'Not specified'); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Farm Location</label>
                                        <p class="form-control-static">
                                            <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                            <?php echo htmlspecialchars($farmer['farm_location'] ?? 'Not specified'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Farm Size</label>
                                        <p class="form-control-static">
                                            <?php echo $farmer['farm_size'] ? number_format($farmer['farm_size'], 2) . ' hectares' : 'Not specified'; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Last Updated</label>
                                        <p class="form-control-static">
                                            <?php echo date('F j, Y g:i A', strtotime($farmer['updated_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Crops Grown -->
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-seedling me-2"></i>Crops Grown
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($crops) && !empty($crops[0])): ?>
                                <div class="row">
                                    <?php foreach ($crops as $crop): ?>
                                        <div class="col-md-4 mb-2">
                                            <span class="badge bg-success p-2">
                                                <i class="fas fa-leaf me-1"></i>
                                                <?php echo ucfirst(str_replace('_', ' ', $crop)); ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted mb-0">No crops specified</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Actions and Stats -->
                <div class="col-lg-4">
                    <!-- Statistics -->
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="card-title mb-0">Profile Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Account Created</small>
                                <div class="fw-semibold"><?php echo date('M j, Y', strtotime($farmer['created_at'])); ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Last Updated</small>
                                <div class="fw-semibold"><?php echo date('M j, Y', strtotime($farmer['updated_at'])); ?></div>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">Farm Details</small>
                                <div class="fw-semibold">
                                    <?php echo ($farmer['farm_name'] && $farmer['farm_location']) ? 'Complete' : 'Incomplete'; ?>
                                </div>
                            </div>
                            <div>
                                <small class="text-muted">Crops Registered</small>
                                <div class="fw-semibold"><?php echo count(array_filter($crops)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("nav_mobile.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function editFarmer(farmerId) {
            window.location.href = `farmers.php?edit=${farmerId}`;
        }

        function deleteFarmer(farmerId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to farmers page with delete parameter
                    window.location.href = `farmers.php?delete=${farmerId}`;
                }
            });
        }

        // Print functionality
        function printProfile() {
            window.print();
        }

        // Add print button event listener
        document.addEventListener('DOMContentLoaded', function() {
            const printBtn = document.querySelector('button:contains("Print Profile")');
            if (printBtn) {
                printBtn.addEventListener('click', printProfile);
            }
        });
    </script>
</body>

</html>