<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Crops - Smart Farming Philippines</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php include("top_nav.php"); ?>
        
        <div id="content">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">My Crops Management</h4>
                            <p class="text-muted mb-0">Track and manage all your crops in one place</p>
                        </div>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCropModal">
                            <i class="fas fa-plus me-2"></i>Add New Crop
                        </button>
                    </div>
                </div>
            </div>

            <!-- Crop Statistics -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card crop-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Crops</h6>
                                    <h3 class="mb-0" id="totalCrops">0</h3>
                                    <small class="text-success">
                                        <i class="fas fa-seedling"></i> All crops
                                    </small>
                                </div>
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-leaf"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card crop-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Growing</h6>
                                    <h3 class="mb-0" id="growingCrops">0</h3>
                                    <small class="text-info">
                                        <i class="fas fa-chart-line"></i> Active growth
                                    </small>
                                </div>
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-seedling"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card crop-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Flowering</h6>
                                    <h3 class="mb-0" id="floweringCrops">0</h3>
                                    <small class="text-warning">
                                        <i class="fas fa-flower"></i> Blooming stage
                                    </small>
                                </div>
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-flower"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card crop-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Ready Soon</h6>
                                    <h3 class="mb-0" id="harvestingCrops">0</h3>
                                    <small class="text-primary">
                                        <i class="fas fa-tractor"></i> Near harvest
                                    </small>
                                </div>
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-tractor"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Crops List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">My Crops</h5>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    Filter by Status
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="filterCrops('all')">All Crops</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCrops('seedling')">Seedling</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCrops('growing')">Growing</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCrops('flowering')">Flowering</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="filterCrops('harvesting')">Ready for Harvest</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="cropsList">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading crops...</span>
                                    </div>
                                    <div class="mt-3">Loading your crops...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("nav_mobile.php"); ?>

    <!-- Add Crop Modal -->
    <div class="modal fade" id="addCropModal" tabindex="-1" aria-labelledby="addCropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCropModalLabel">Add New Crop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="availableCropsList">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading crops...</span>
                            </div>
                            <div class="mt-2">Loading available crops...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Crop Details Modal -->
    <div class="modal fade" id="cropDetailsModal" tabindex="-1" aria-labelledby="cropDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropDetailsModalLabel">Crop Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cropDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteCropBtn" onclick="deleteSelectedCrop()">Remove Crop</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const CROPS_API = 'function/crops_api.php';
        let currentCrops = [];
        let selectedCropId = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadMyCrops();
            loadAvailableCrops();
        });

        async function loadMyCrops() {
            try {
                const response = await fetch(`${CROPS_API}?action=my_crops`);
                const data = await response.json();
                
                if (data.success) {
                    currentCrops = data.data;
                    updateCropsList(currentCrops);
                    updateCropStats(currentCrops);
                } else {
                    showError('Failed to load crops');
                // Show empty state if no crops
                    document.getElementById('cropsList').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No crops added yet</h5>
                        <p class="text-muted mb-4">Start by adding your first crop to track its growth and progress.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCropModal">
                            <i class="fas fa-plus me-2"></i>Add Your First Crop
                        </button>
                    </div>
                `;
                }
            } catch (error) {
                console.error('Error loading crops:', error);
                showError('Failed to load crops');
                // Show empty state on error
                document.getElementById('cropsList').innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <h5 class="text-danger">Error Loading Crops</h5>
                        <p class="text-muted mb-4">There was a problem loading your crops. Please try again.</p>
                        <button class="btn btn-primary" onclick="loadMyCrops()">
                            <i class="fas fa-redo me-2"></i>Try Again
                        </button>
                    </div>
                `;
            }
        }

        async function loadAvailableCrops() {
            try {
                const response = await fetch(`${CROPS_API}?action=available_crops`);
                const data = await response.json();
                
                if (data.success) {
                    updateAvailableCropsList(data.data);
                } else {
                    document.getElementById('availableCropsList').innerHTML = '<p class="text-muted text-center">No crops available</p>';
                }
            } catch (error) {
                console.error('Error loading available crops:', error);
                document.getElementById('availableCropsList').innerHTML = '<p class="text-danger text-center">Failed to load available crops</p>';
            }
        }

        function updateCropsList(crops) {
            const container = document.getElementById('cropsList');
            
            if (crops.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No crops added yet</h5>
                        <p class="text-muted mb-4">Start by adding your first crop to track its growth and progress.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCropModal">
                            <i class="fas fa-plus me-2"></i>Add Your First Crop
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = crops.map(crop => `
                <div class="crop-card mb-3 p-3 border rounded" data-status="${crop.status}">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-start">
                                <div class="crop-icon me-3">
                                    <i class="fas fa-seedling fa-2x ${getStatusColor(crop.status)}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">${escapeHtml(crop.name)}</h5>
                                    <p class="text-muted mb-1">${escapeHtml(crop.description || 'No description available')}</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge ${getStatusBadge(crop.status)}">${crop.status}</span>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-calendar me-1"></i>Planted: ${formatDate(crop.planted_date)}
                                        </span>
                                        ${crop.season ? `<span class="badge bg-info">${crop.season} Season</span>` : ''}
                                        ${crop.water_requirements ? `<span class="badge bg-primary">Water: ${crop.water_requirements}</span>` : ''}
                                        ${crop.sunlight_requirements ? `<span class="badge bg-warning">Sun: ${crop.sunlight_requirements}</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar ${getProgressBarColor(crop.status)}" 
                                     style="width: ${getGrowthProgress(crop.status)}%"></div>
                            </div>
                            <small class="text-muted d-block">${crop.growth_stage}</small>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewCropDetails(${crop.id})">
                                    <i class="fas fa-eye me-1"></i>Details
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmDeleteCrop(${crop.id})">
                                    <i class="fas fa-trash me-1"></i>Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function updateAvailableCropsList(crops) {
            const container = document.getElementById('availableCropsList');
            
            if (crops.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No crops available</p>';
                return;
            }

            container.innerHTML = `
                <div class="row g-3">
                    ${crops.map(crop => `
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">${escapeHtml(crop.name)}</h6>
                                    <p class="card-text small text-muted">${escapeHtml(crop.description || 'No description available')}</p>
                                    
                                    <div class="crop-details small mb-3">
                                        ${crop.season ? `<div><i class="fas fa-calendar text-info me-1"></i> ${crop.season} Season</div>` : ''}
                                        ${crop.water_requirements ? `<div><i class="fas fa-tint text-primary me-1"></i> ${crop.water_requirements} water</div>` : ''}
                                        ${crop.sunlight_requirements ? `<div><i class="fas fa-sun text-warning me-1"></i> ${crop.sunlight_requirements} sun</div>` : ''}
                                    </div>
                                    
                                    ${crop.planting_guide ? `
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <strong>Planting Guide:</strong> ${escapeHtml(crop.planting_guide.substring(0, 100))}...
                                            </small>
                                        </div>
                                    ` : ''}
                                    
                                    <button class="btn btn-sm btn-success w-100" onclick="addCrop('${escapeHtml(crop.name)}')">
                                        <i class="fas fa-plus me-1"></i>Add to My Farm
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function updateCropStats(crops) {
            const totalCrops = crops.length;
            const growingCrops = crops.filter(crop => crop.status === 'growing').length;
            const floweringCrops = crops.filter(crop => crop.status === 'flowering').length;
            const harvestingCrops = crops.filter(crop => crop.status === 'harvesting').length;

            document.getElementById('totalCrops').textContent = totalCrops;
            document.getElementById('growingCrops').textContent = growingCrops;
            document.getElementById('floweringCrops').textContent = floweringCrops;
            document.getElementById('harvestingCrops').textContent = harvestingCrops;
        }

        function filterCrops(status) {
            if (status === 'all') {
                updateCropsList(currentCrops);
            } else {
                const filteredCrops = currentCrops.filter(crop => crop.status === status);
                updateCropsList(filteredCrops);
            }
        }

        async function addCrop(cropType) {
            try {
                const response = await fetch(CROPS_API, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'add_crop',
                        crop_type: cropType
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(result.message);
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCropModal'));
                    if (modal) modal.hide();
                    // Reload crops
                    loadMyCrops();
                    // Reload available crops to update list
                    loadAvailableCrops();
                } else {
                    showError(result.message);
                }

            } catch (error) {
                console.error('Error adding crop:', error);
                showError('Failed to add crop');
            }
        }

        function confirmDeleteCrop(cropId) {
            selectedCropId = cropId;
            Swal.fire({
                title: 'Remove Crop?',
                text: 'This will remove the crop from your farm. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteSelectedCrop();
                }
            });
        }

        async function deleteSelectedCrop() {
            if (!selectedCropId) {
                showError('No crop selected');
                return;
            }

            try {
                const response = await fetch(CROPS_API, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'delete_crop',
                        crop_id: selectedCropId
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(result.message);
                    // Close modal if open
                    const modal = bootstrap.Modal.getInstance(document.getElementById('cropDetailsModal'));
                    if (modal) modal.hide();
                    // Reload crops
                    loadMyCrops();
                    // Reset selected crop ID
                    selectedCropId = null;
                } else {
                    showError(result.message);
                }

            } catch (error) {
                console.error('Error deleting crop:', error);
                showError('Failed to delete crop');
            }
        }

        function viewCropDetails(cropId) {
            const crop = currentCrops.find(c => c.id == cropId);
            if (!crop) return;

            const modalContent = document.getElementById('cropDetailsContent');
            selectedCropId = cropId;

            modalContent.innerHTML = `
                <div class="text-center mb-3">
                    <i class="fas fa-seedling fa-3x ${getStatusColor(crop.status)} mb-2"></i>
                    <h4>${escapeHtml(crop.name)}</h4>
                    <span class="badge ${getStatusBadge(crop.status)}">${crop.status}</span>
                </div>
                
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Planted Date:</strong>
                    </div>
                    <div class="col-6">
                        ${formatDate(crop.planted_date)}
                    </div>
                </div>
                
                ${crop.description ? `
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Description:</strong>
                    </div>
                    <div class="col-6">
                        ${escapeHtml(crop.description)}
                    </div>
                </div>
                ` : ''}
                
                ${crop.season ? `
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Season:</strong>
                    </div>
                    <div class="col-6">
                        ${crop.season}
                    </div>
                </div>
                ` : ''}
                
                ${crop.water_requirements ? `
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Water Needs:</strong>
                    </div>
                    <div class="col-6">
                        ${crop.water_requirements}
                    </div>
                </div>
                ` : ''}
                
                ${crop.sunlight_requirements ? `
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Sunlight Needs:</strong>
                    </div>
                    <div class="col-6">
                        ${crop.sunlight_requirements}
                    </div>
                </div>
                ` : ''}
                
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Growth Stage:</strong>
                    </div>
                    <div class="col-6">
                        ${crop.growth_stage}
                    </div>
                </div>
                
                <div class="progress mb-3" style="height: 10px;">
                    <div class="progress-bar ${getProgressBarColor(crop.status)}" 
                         style="width: ${getGrowthProgress(crop.status)}%"></div>
                </div>
                <small class="text-muted text-center d-block">Growth Progress</small>
            `;

            const modal = new bootstrap.Modal(document.getElementById('cropDetailsModal'));
            modal.show();
        }

        // Utility functions
        function getStatusColor(status) {
            const colors = {
                'seedling': 'text-info',
                'growing': 'text-success',
                'flowering': 'text-warning',
                'harvesting': 'text-primary'
            };
            return colors[status] || 'text-secondary';
        }

        function getStatusBadge(status) {
            const badges = {
                'seedling': 'bg-info',
                'growing': 'bg-success',
                'flowering': 'bg-warning',
                'harvesting': 'bg-primary'
            };
            return badges[status] || 'bg-secondary';
        }

        function getProgressBarColor(status) {
            const colors = {
                'seedling': 'bg-info',
                'growing': 'bg-success',
                'flowering': 'bg-warning',
                'harvesting': 'bg-primary'
            };
            return colors[status] || 'bg-secondary';
        }

        function getGrowthProgress(status) {
            const progress = {
                'seedling': 25,
                'growing': 50,
                'flowering': 75,
                'harvesting': 100
            };
            return progress[status] || 0;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 3000
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 3000
            });
        }
    </script>

    <style>
        .crop-stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e3e6f0;
        }

        .crop-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .crop-card {
            transition: all 0.3s ease;
        }

        .crop-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-1px);
        }

        .crop-icon {
            width: 50px;
            text-align: center;
        }

        .crop-details div {
            margin-bottom: 0.25rem;
        }

        @media (max-width: 768px) {
            .crop-card .row {
                text-align: center;
            }
            
            .crop-card .text-end {
                text-align: center !important;
                margin-top: 1rem;
            }
        }
    </style>
</body>
</html>