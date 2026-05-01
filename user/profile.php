<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Smart Farming Philippines</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-header-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .stats-card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
    </style>
</head>

<body>
    <?php
    include("sidebar.php");
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php
        include("top_nav.php");
        ?>
        <div id="content">
            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading profile data...</p>
            </div>

            <!-- Profile Content -->
            <div id="profileContent" style="display: none;"></div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProfileForm">
                    <div class="modal-body" id="editFormContent">
                        <!-- Form will be loaded here by JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveProfileBtn">
                            <span class="spinner-border spinner-border-sm me-2 d-none" id="saveSpinner"></span>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    include("nav_mobile.php");
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript.js"></script>

    <script>
        // Load profile data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadProfileData();
        });

        // Function to load profile data using fetch
        async function loadProfileData() {
            try {
                showLoading();
                const response = await fetch('function/get_profile.php');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();

                if (data.success) {
                    displayProfileData(data.data);
                    hideLoading();
                } else {
                    throw new Error(data.message || 'Failed to load profile data');
                }
            } catch (error) {
                console.error('Error loading profile:', error);
                showError('Failed to load profile data: ' + error.message);
            }
        }

        function showLoading() {
            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('profileContent').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('profileContent').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('loadingSpinner').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${message}
                </div>
                <button class="btn btn-primary mt-2" onclick="loadProfileData()">
                    <i class="fas fa-redo me-2"></i>Retry
                </button>
            `;
        }

        // Function to display profile data
        function displayProfileData(profile) {
            const profileContent = document.getElementById('profileContent');
            
            const memberSince = new Date(profile.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short' 
            });
            
            const lastLogin = profile.last_login ? 
                new Date(profile.last_login).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'First login';

            profileContent.innerHTML = `
                <div class="container-fluid py-4">
                    <!-- Profile Header -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card profile-header-card mb-4">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(profile.first_name + ' ' + profile.last_name)}&size=150&background=667eea&color=fff&bold=true" 
                                                 class="rounded-circle border" 
                                                 width="120" 
                                                 height="120"
                                                 alt="Profile Picture">
                                        </div>
                                        <div class="col">
                                            <h2 class="mb-1">${profile.first_name} ${profile.last_name}</h2>
                                            <p class="mb-2">
                                                <i class="fas fa-envelope me-2"></i>${profile.email}
                                            </p>
                                            <p class="mb-0">
                                                <i class="fas fa-phone me-2"></i>${profile.phone_number}
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-light" onclick="openEditModal()">
                                                <i class="fas fa-edit me-2"></i>Edit Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary stats-card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Farm Size</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ${profile.farm_size || '0'} hectares
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tractor fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success stats-card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Experience</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ${profile.experience_years || '0'} years
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-award fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info stats-card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Crop Types</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ${profile.crops.length}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-seedling fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning stats-card h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Member Since</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ${memberSince}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Information -->
                    <div class="row">
                        <!-- Farm Information -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-tractor me-2"></i>Farm Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Farm Name:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            ${profile.farm_name || 'Not specified'}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Location:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            ${profile.farm_location || 'Not specified'}
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <strong>Farm Size:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            ${profile.farm_size || '0'} hectares
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Crop Information -->
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">
                                        <i class="fas fa-seedling me-2"></i>Crop Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    ${profile.crops.length > 0 ? `
                                        <div class="mb-3">
                                            <strong>Crop Types:</strong>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            ${profile.crops.map(crop => `
                                                <span class="badge bg-success">${crop.charAt(0).toUpperCase() + crop.slice(1)}</span>
                                            `).join('')}
                                        </div>
                                    ` : `
                                        <p class="text-muted">No crops registered yet.</p>
                                        <button class="btn btn-sm btn-outline-success" onclick="openEditModal()">
                                            <i class="fas fa-plus me-1"></i>Add Crops
                                        </button>
                                    `}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Experience Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-info">
                                        <i class="fas fa-award me-2"></i>Farming Experience
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Years of Experience:</strong>
                                            <p class="mt-1">${profile.experience_years || '0'} years</p>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Last Login:</strong>
                                            <p class="mt-1">${lastLogin}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Function to open edit modal
        async function openEditModal() {
            try {
                const response = await fetch('function/get_profile.php');
                const data = await response.json();

                if (data.success) {
                    displayEditForm(data.data);
                    const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                    modal.show();
                } else {
                    throw new Error(data.message || 'Failed to load profile data');
                }
            } catch (error) {
                console.error('Error loading edit form:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load profile data for editing'
                });
            }
        }

        // Function to display edit form
        function displayEditForm(profile) {
            const allCrops = ['rice', 'corn', 'vegetables', 'fruits', 'root_crops', 'legumes'];
            
            const editFormContent = document.getElementById('editFormContent');
            editFormContent.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" 
                               value="${profile.first_name}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" 
                               value="${profile.last_name}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" 
                               value="${profile.email}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="edit_phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="edit_phone_number" name="phone_number" 
                               value="${profile.phone_number}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_farm_name" class="form-label">Farm Name</label>
                        <input type="text" class="form-control" id="edit_farm_name" name="farm_name" 
                               value="${profile.farm_name || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="edit_farm_location" class="form-label">Farm Location</label>
                        <input type="text" class="form-control" id="edit_farm_location" name="farm_location" 
                               value="${profile.farm_location || ''}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="edit_farm_size" class="form-label">Farm Size (hectares)</label>
                        <input type="number" step="0.01" class="form-control" id="edit_farm_size" name="farm_size" 
                               value="${profile.farm_size || ''}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="edit_experience_years" class="form-label">Years of Experience</label>
                        <input type="number" class="form-control" id="edit_experience_years" name="experience_years" 
                               value="${profile.experience_years || '0'}">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Crop Types</label>
                    <div class="d-flex flex-wrap gap-2">
                        ${allCrops.map(crop => `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="crops[]" 
                                       value="${crop}" id="edit_crop_${crop}" ${profile.crops.includes(crop) ? 'checked' : ''}>
                                <label class="form-check-label" for="edit_crop_${crop}">
                                    ${crop.charAt(0).toUpperCase() + crop.slice(1).replace('_', ' ')}
                                </label>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Handle edit form submission
        document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const saveBtn = document.getElementById('saveProfileBtn');
            const spinner = document.getElementById('saveSpinner');
            
            // Show loading state
            saveBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('function/update_profile.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                        modal.hide();
                        // Reload profile data
                        loadProfileData();
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'An error occurred while updating profile.'
                });
            } finally {
                // Reset button state
                saveBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
</body>

</html>