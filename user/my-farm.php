<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Farm - Smart Farming Philippines</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <h4 class="mb-1">My Farm Management</h4>
                            <p class="text-muted mb-0">Manage your farm details and track your farming activities</p>
                        </div>
                        <button class="btn btn-primary" onclick="toggleEditMode()">
                            <i class="fas fa-edit me-2"></i>Edit Farm Details
                        </button>
                    </div>
                </div>
            </div>

            <!-- Farm Overview Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card farm-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Crops</h6>
                                    <h3 class="mb-0" id="totalCrops">0</h3>
                                    <small class="text-success">
                                        <i class="fas fa-seedling"></i> Active cultivation
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
                    <div class="card farm-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Farm Size</h6>
                                    <h3 class="mb-0" id="farmSize">0</h3>
                                    <small class="text-info">
                                        <i class="fas fa-ruler-combined"></i> Hectares
                                    </small>
                                </div>
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-map"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card farm-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Experience</h6>
                                    <h3 class="mb-0" id="experienceYears">0</h3>
                                    <small class="text-warning">
                                        <i class="fas fa-award"></i> Years farming
                                    </small>
                                </div>
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card farm-stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Registered</h6>
                                    <h3 class="mb-0" id="monthsRegistered">0</h3>
                                    <small class="text-primary">
                                        <i class="fas fa-calendar"></i> Months with us
                                    </small>
                                </div>
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Farm Details Form -->
                <div class="col-xl-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Farm Information</h5>
                        </div>
                        <div class="card-body">
                            <form id="farmForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="farmName" class="form-label">Farm Name</label>
                                        <input type="text" class="form-control" id="farmName" name="farm_name" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="farmLocation" class="form-label">Farm Location</label>
                                        <input type="text" class="form-control" id="farmLocation" name="farm_location" readonly>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="farmSize" class="form-label">Farm Size (hectares)</label>
                                        <input type="number" class="form-control" id="farmSizeInput" name="farm_size" step="0.01" min="0" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="experienceYears" class="form-label">Farming Experience (years)</label>
                                        <input type="number" class="form-control" id="experienceYearsInput" name="experience_years" min="0" max="100" readonly>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="farmerName" class="form-label">Farmer Name</label>
                                        <input type="text" class="form-control" id="farmerName" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phoneNumber" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phoneNumber" readonly>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="registrationDate" class="form-label">Registration Date</label>
                                    <input type="text" class="form-control" id="registrationDate" readonly>
                                </div>
                                
                                <div id="editButtons" style="display: none;">
                                    <button type="button" class="btn btn-success" onclick="saveFarmDetails()">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                    <button type="button" class="btn btn-secondary ms-2" onclick="cancelEdit()">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Crop Distribution & Quick Actions -->
                <div class="col-xl-4 mb-4">
                    <!-- Crop Distribution -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Crop Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div id="cropDistribution">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading crops...</span>
                                    </div>
                                    <div class="mt-2">Loading crop data...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Farm Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Farm Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="my-crops.php" class="btn btn-outline-primary text-start">
                                    <i class="fas fa-seedling me-2"></i>Manage Crops
                                </a>
                                <a href="weather.php" class="btn btn-outline-info text-start">
                                    <i class="fas fa-cloud-sun me-2"></i>Check Weather
                                </a>
                                <a href="learning-center.php" class="btn btn-outline-success text-start">
                                    <i class="fas fa-graduation-cap me-2"></i>Learning Center
                                </a>
                                
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
    <script src="javascript.js"></script>
    <script>
        const FARM_API = 'function/farm_api.php';
        let isEditMode = false;
        let originalData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadFarmData();
        });

        async function loadFarmData() {
            try {
                // Load farm details
                const detailsResponse = await fetch(`${FARM_API}?action=farm_details`);
                const detailsData = await detailsResponse.json();
                
                if (detailsData.success) {
                    updateFarmDetails(detailsData.data);
                }

                // Load farm stats
                const statsResponse = await fetch(`${FARM_API}?action=farm_stats`);
                const statsData = await statsResponse.json();
                
                if (statsData.success) {
                    updateFarmStats(statsData.data);
                }

            } catch (error) {
                console.error('Error loading farm data:', error);
                showError('Failed to load farm data');
            }
        }

        function updateFarmDetails(data) {
            const farmInfo = data.farm_info;
            const cropsInfo = data.crops_info;
            
            // Update form fields
            document.getElementById('farmName').value = farmInfo.farm_name || '';
            document.getElementById('farmLocation').value = farmInfo.farm_location || '';
            document.getElementById('farmSizeInput').value = farmInfo.farm_size || '';
            document.getElementById('experienceYearsInput').value = farmInfo.experience_years || '';
            document.getElementById('farmerName').value = `${farmInfo.first_name} ${farmInfo.last_name}`;
            document.getElementById('phoneNumber').value = farmInfo.phone_number || '';
            document.getElementById('email').value = farmInfo.email || '';
            
            // Format registration date
            const regDate = new Date(farmInfo.created_at);
            document.getElementById('registrationDate').value = regDate.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Update stats cards
            document.getElementById('farmSize').textContent = farmInfo.farm_size || '0';
            document.getElementById('experienceYears').textContent = farmInfo.experience_years || '0';
            document.getElementById('totalCrops').textContent = cropsInfo.total_crops || '0';

            // Update crop distribution
            updateCropDistribution(cropsInfo);

            // Store original data for cancel functionality
            originalData = {
                farm_name: farmInfo.farm_name || '',
                farm_location: farmInfo.farm_location || '',
                farm_size: farmInfo.farm_size || '',
                experience_years: farmInfo.experience_years || ''
            };
        }

        function updateFarmStats(data) {
            document.getElementById('monthsRegistered').textContent = data.months_registered || '0';
            
            // Update crop distribution with detailed data
            if (data.crop_types && data.crop_types.length > 0) {
                updateCropDistribution({ crop_types: data.crop_types.join(', ') }, data.crop_types);
            }
        }

        function updateCropDistribution(cropsInfo, detailedTypes = null) {
            const container = document.getElementById('cropDistribution');
            
            if (!cropsInfo.crop_types || cropsInfo.crop_types === '') {
                container.innerHTML = `
                    <div class="text-center py-3">
                        <i class="fas fa-seedling fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-2">No crops added yet</p>
                        <a href="my-crops.php" class="btn btn-sm btn-primary">Add Crops</a>
                    </div>
                `;
                return;
            }

            let cropTypes = [];
            if (detailedTypes) {
                cropTypes = detailedTypes;
            } else {
                cropTypes = cropsInfo.crop_types.split(',').map(type => ({
                    type: type.trim(),
                    count: 1
                }));
            }

            container.innerHTML = `
                <div class="crop-distribution">
                    ${cropTypes.map(crop => `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                            <div>
                                <i class="fas fa-seedling text-success me-2"></i>
                                <span class="fw-semibold">${escapeHtml(crop.type)}</span>
                            </div>
                            <span class="badge bg-primary">${crop.count}</span>
                        </div>
                    `).join('')}
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">Total: ${cropsInfo.total_crops || cropTypes.length} crops</small>
                </div>
            `;
        }

        function toggleEditMode() {
            isEditMode = !isEditMode;
            const editableFields = ['farmName', 'farmLocation', 'farmSizeInput', 'experienceYearsInput'];
            const editButton = document.querySelector('button[onclick="toggleEditMode()"]');
            const editButtons = document.getElementById('editButtons');

            if (isEditMode) {
                // Enable editing
                editableFields.forEach(fieldId => {
                    document.getElementById(fieldId).readOnly = false;
                    document.getElementById(fieldId).classList.add('editing');
                });
                
                editButton.innerHTML = '<i class="fas fa-times me-2"></i>Cancel Edit';
                editButton.className = 'btn btn-secondary';
                editButtons.style.display = 'block';
                
            } else {
                // Disable editing and revert changes
                editableFields.forEach(fieldId => {
                    document.getElementById(fieldId).readOnly = true;
                    document.getElementById(fieldId).classList.remove('editing');
                    document.getElementById(fieldId).value = originalData[fieldId.replace('Input', '').replace(/([A-Z])/g, '_$1').toLowerCase()] || '';
                });
                
                editButton.innerHTML = '<i class="fas fa-edit me-2"></i>Edit Farm Details';
                editButton.className = 'btn btn-primary';
                editButtons.style.display = 'none';
            }
        }

        function cancelEdit() {
            isEditMode = false;
            toggleEditMode();
        }

        async function saveFarmDetails() {
            try {
                const formData = {
                    action: 'update_farm',
                    farm_name: document.getElementById('farmName').value,
                    farm_location: document.getElementById('farmLocation').value,
                    farm_size: document.getElementById('farmSizeInput').value,
                    experience_years: document.getElementById('experienceYearsInput').value
                };

                const response = await fetch(FARM_API, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess(result.message);
                    // Update original data
                    originalData = formData;
                    // Reload data to ensure consistency
                    loadFarmData();
                    // Exit edit mode
                    toggleEditMode();
                } else {
                    showError(result.message);
                }

            } catch (error) {
                console.error('Error saving farm details:', error);
                showError('Failed to save farm details');
            }
        }

        function generateFarmReport() {
            Swal.fire({
                title: 'Generate Farm Report',
                text: 'This will generate a PDF report of your farm details and activities.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Generate Report',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Generating Report',
                        text: 'Your farm report is being generated...',
                        icon: 'info',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }

        function escapeHtml(unsafe) {
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
        .farm-stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e3e6f0;
        }

        .farm-stat-card:hover {
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

        .editing {
            border-color: #4e73df !important;
            background-color: #f8f9ff;
        }

        .crop-distribution .border {
            transition: background-color 0.2s ease;
        }

        .crop-distribution .border:hover {
            background-color: #f8f9fa;
        }

        .btn-outline-primary, .btn-outline-info, .btn-outline-success, .btn-outline-warning {
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover, .btn-outline-info:hover, .btn-outline-success:hover, .btn-outline-warning:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</body>
</html>