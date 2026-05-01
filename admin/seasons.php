<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasons Management - Smart Farming Philippines</title>
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

        <!-- Seasons Management Content -->
        <div id="seasons">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Seasons Management</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSeasonModal">
                    <i class="fas fa-plus me-2"></i>Add New Season
                </button>
            </div>

            <!-- Stats Overview -->
            <div class="stats-grid mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-sun"></i>
                    </div>
                    <div class="stat-number" id="totalSeasons">0</div>
                    <div class="stat-label">Total Seasons</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <div class="stat-number" id="totalCrops">0</div>
                    <div class="stat-label">Total Crops</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-number" id="drySeasonCrops">0</div>
                    <div class="stat-label">Dry Season Crops</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cloud-rain"></i>
                    </div>
                    <div class="stat-number" id="wetSeasonCrops">0</div>
                    <div class="stat-label">Wet Season Crops</div>
                </div>
            </div>

            <!-- Seasons and Crops Grid -->
            <div class="row">
                <!-- Seasons List -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Seasons</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group" id="seasonsList">
                                <!-- Seasons will be loaded here -->
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="mt-2">Loading seasons...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Crops for Selected Season -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0" id="selectedSeasonTitle">Select a Season</h5>
                            <button class="btn btn-sm btn-primary" id="addCropBtn" style="display: none;"
                                data-bs-toggle="modal" data-bs-target="#addCropModal">
                                <i class="fas fa-plus me-1"></i>Add Crop
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="cropsContainer">
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-seedling fa-3x mb-3"></i>
                                    <p>Select a season to view crops</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("nav_mobile.php"); ?>

    <!-- Add Season Modal -->
    <div class="modal fade" id="addSeasonModal" tabindex="-1" aria-labelledby="addSeasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSeasonModalLabel">Add New Season</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSeasonForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Season Name *</label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="e.g., Dry Season, Wet Season">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"
                                placeholder="Describe the season characteristics..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Month *</label>
                                    <select class="form-select" name="start_month" required>
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Month *</label>
                                    <select class="form-select" name="end_month" required>
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="addSeasonBtn">
                            <i class="fas fa-plus me-2"></i>Add Season
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Season Modal -->
    <div class="modal fade" id="editSeasonModal" tabindex="-1" aria-labelledby="editSeasonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSeasonModalLabel">Edit Season</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSeasonForm">
                    <input type="hidden" name="season_id" id="editSeasonId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Season Name *</label>
                            <input type="text" class="form-control" name="name" id="editSeasonName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editSeasonDescription" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Month *</label>
                                    <select class="form-select" name="start_month" id="editStartMonth" required>
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Month *</label>
                                    <select class="form-select" name="end_month" id="editEndMonth" required>
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateSeasonBtn">
                            <i class="fas fa-save me-2"></i>Update Season
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Crop Modal -->
    <div class="modal fade" id="addCropModal" tabindex="-1" aria-labelledby="addCropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCropModalLabel">Add New Crop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addCropForm">
                    <input type="hidden" name="season_id" id="cropSeasonId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Crop Name *</label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="e.g., Rice, Corn, Tomatoes">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Season</label>
                                    <p class="form-control-plaintext" id="cropSeasonName">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"
                                placeholder="Describe the crop characteristics..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Planting Guide *</label>
                            <textarea class="form-control" name="planting_guide" rows="5" required
                                placeholder="Provide detailed planting instructions..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Water Requirements</label>
                                    <select class="form-select" name="water_requirements">
                                        <option value="">Select Level</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sunlight Requirements</label>
                                    <select class="form-select" name="sunlight_requirements">
                                        <option value="">Select Level</option>
                                        <option value="full_sun">Full Sun</option>
                                        <option value="partial_sun">Partial Sun</option>
                                        <option value="shade">Shade</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="addCropBtnModal">
                            <i class="fas fa-plus me-2"></i>Add Crop
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Crop Modal -->
    <div class="modal fade" id="editCropModal" tabindex="-1" aria-labelledby="editCropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCropModalLabel">Edit Crop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCropForm">
                    <input type="hidden" name="crop_id" id="editCropId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Crop Name *</label>
                                    <input type="text" class="form-control" name="name" id="editCropName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Season</label>
                                    <p class="form-control-plaintext" id="editCropSeasonName">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editCropDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Planting Guide *</label>
                            <textarea class="form-control" name="planting_guide" id="editCropPlantingGuide" rows="5" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Water Requirements</label>
                                    <select class="form-select" name="water_requirements" id="editCropWater">
                                        <option value="">Select Level</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Sunlight Requirements</label>
                                    <select class="form-select" name="sunlight_requirements" id="editCropSunlight">
                                        <option value="">Select Level</option>
                                        <option value="full_sun">Full Sun</option>
                                        <option value="partial_sun">Partial Sun</option>
                                        <option value="shade">Shade</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateCropBtn">
                            <i class="fas fa-save me-2"></i>Update Crop
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="javascript.js"></script>
    <script>
        // API endpoints
        const SEASONS_API = 'function/seasons_api.php';
        let currentSelectedSeasonId = null;

        // Load seasons data
        async function loadSeasons() {
            try {
                showSeasonsLoading();
                const response = await fetch(SEASONS_API);
                const data = await response.json();

                if (data.success) {
                    displaySeasons(data.data.seasons);
                    updateStats(data.data.stats);
                } else {
                    throw new Error(data.error || 'Failed to load seasons');
                }
            } catch (error) {
                console.error('Error loading seasons:', error);
                showError('Failed to load seasons data');
            }
        }

        // Display seasons in list
        function displaySeasons(seasons) {
            const seasonsList = document.getElementById('seasonsList');

            if (seasons.length === 0) {
                seasonsList.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-seedling fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-3">No seasons found</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSeasonModal">
                            <i class="fas fa-plus me-1"></i>Add First Season
                        </button>
                    </div>
                `;
                return;
            }

            seasonsList.innerHTML = seasons.map(season => `
                <div class="list-group-item list-group-item-action ${currentSelectedSeasonId === season.id ? 'active' : ''}" 
                     onclick="selectSeason(${season.id}, '${escapeHtml(season.name)}')">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <h6 class="mb-1">${escapeHtml(season.name)}</h6>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-${currentSelectedSeasonId === season.id ? 'light' : 'secondary'} dropdown-toggle" 
                                    type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="editSeason(${season.id})"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#" onclick="deleteSeason(${season.id})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <p class="mb-1 small text-muted">${escapeHtml(season.description || 'No description')}</p>
                    <small class="text-muted">${getMonthName(season.start_month)} - ${getMonthName(season.end_month)}</small>
                </div>
            `).join('');
        }

        // Select season and load its crops
        async function selectSeason(seasonId, seasonName) {
            currentSelectedSeasonId = seasonId;

            // Update UI
            document.getElementById('selectedSeasonTitle').textContent = `${seasonName} - Crops`;
            document.getElementById('addCropBtn').style.display = 'block';
            document.getElementById('cropSeasonId').value = seasonId;
            document.getElementById('cropSeasonName').textContent = seasonName;

            // Load crops for this season
            await loadCrops(seasonId);

            // Reload seasons list to update active state
            loadSeasons();
        }

        // Load crops for selected season
        async function loadCrops(seasonId) {
            try {
                showCropsLoading();
                const response = await fetch(`${SEASONS_API}?season_id=${seasonId}`);
                const data = await response.json();

                if (data.success) {
                    displayCrops(data.data.crops);
                } else {
                    throw new Error(data.error || 'Failed to load crops');
                }
            } catch (error) {
                console.error('Error loading crops:', error);
                showError('Failed to load crops data');
            }
        }

        // Display crops in grid
        function displayCrops(crops) {
            const cropsContainer = document.getElementById('cropsContainer');

            if (crops.length === 0) {
                cropsContainer.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No crops found</h5>
                        <p class="text-muted">Add crops for this season to get started</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCropModal">
                            <i class="fas fa-plus me-2"></i>Add Crop
                        </button>
                    </div>
                `;
                return;
            }

            cropsContainer.innerHTML = crops.map(crop => `
                <div class="card crop-card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title">${escapeHtml(crop.name)}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="editCrop(${crop.id})"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCrop(${crop.id})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="card-text">${escapeHtml(crop.description || 'No description available')}</p>
                        
                        <div class="crop-details">
                            <h6 class="text-primary">Planting Guide:</h6>
                            <p class="small">${escapeHtml(crop.planting_guide || 'No planting guide available')}</p>
                            
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-tint me-1"></i>
                                        Water: ${crop.water_requirements ? crop.water_requirements.charAt(0).toUpperCase() + crop.water_requirements.slice(1) : 'Not specified'}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="fas fa-sun me-1"></i>
                                        Sunlight: ${crop.sunlight_requirements ? crop.sunlight_requirements.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ') : 'Not specified'}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Update stats
        function updateStats(stats) {
            document.getElementById('totalSeasons').textContent = stats.total_seasons || 0;
            document.getElementById('totalCrops').textContent = stats.total_crops || 0;
            document.getElementById('drySeasonCrops').textContent = stats.dry_season_crops || 0;
            document.getElementById('wetSeasonCrops').textContent = stats.wet_season_crops || 0;
        }

        // Add season
        document.getElementById('addSeasonForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await addSeason();
        });

        async function addSeason() {
            try {
                const form = document.getElementById('addSeasonForm');
                const formData = new FormData(form);

                const addBtn = document.getElementById('addSeasonBtn');
                addBtn.disabled = true;
                addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';

                const response = await fetch(SEASONS_API, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Season added successfully',
                        timer: 2000
                    });

                    // Close modal and reset form
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSeasonModal'));
                    modal.hide();
                    form.reset();

                    // Reload seasons
                    loadSeasons();
                } else {
                    throw new Error(data.error || 'Failed to add season');
                }
            } catch (error) {
                console.error('Error adding season:', error);
                showError(error.message);
            } finally {
                const addBtn = document.getElementById('addSeasonBtn');
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add Season';
            }
        }

        // Edit season
        async function editSeason(seasonId) {
            try {
                const response = await fetch(`${SEASONS_API}?id=${seasonId}`);
                const data = await response.json();

                if (data.success) {
                    const season = data.data;
                    populateEditSeasonForm(season);

                    const modal = new bootstrap.Modal(document.getElementById('editSeasonModal'));
                    modal.show();
                } else {
                    throw new Error(data.error || 'Failed to load season data');
                }
            } catch (error) {
                console.error('Error loading season:', error);
                showError('Failed to load season data');
            }
        }

        function populateEditSeasonForm(season) {
            document.getElementById('editSeasonId').value = season.id;
            document.getElementById('editSeasonName').value = season.name;
            document.getElementById('editSeasonDescription').value = season.description || '';
            document.getElementById('editStartMonth').value = season.start_month;
            document.getElementById('editEndMonth').value = season.end_month;
        }

        // Update season
        document.getElementById('editSeasonForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await updateSeason();
        });

        async function updateSeason() {
            try {
                const form = document.getElementById('editSeasonForm');
                const formData = new FormData(form);

                const updateBtn = document.getElementById('updateSeasonBtn');
                updateBtn.disabled = true;
                updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';

                const response = await fetch(SEASONS_API, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Season updated successfully',
                        timer: 2000
                    });

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editSeasonModal'));
                    modal.hide();

                    // Reload seasons
                    loadSeasons();
                } else {
                    throw new Error(data.error || 'Failed to update season');
                }
            } catch (error) {
                console.error('Error updating season:', error);
                showError(error.message);
            } finally {
                const updateBtn = document.getElementById('updateSeasonBtn');
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Season';
            }
        }

        // Delete season
        async function deleteSeason(seasonId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will also delete all crops associated with this season!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(SEASONS_API, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: seasonId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Season has been deleted',
                            timer: 2000
                        });

                        // Reset selection if deleted season was selected
                        if (currentSelectedSeasonId === seasonId) {
                            currentSelectedSeasonId = null;
                            document.getElementById('selectedSeasonTitle').textContent = 'Select a Season';
                            document.getElementById('addCropBtn').style.display = 'none';
                            document.getElementById('cropsContainer').innerHTML = `
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-seedling fa-3x mb-3"></i>
                                    <p>Select a season to view crops</p>
                                </div>
                            `;
                        }

                        // Reload seasons
                        loadSeasons();
                    } else {
                        throw new Error(data.error || 'Failed to delete season');
                    }
                } catch (error) {
                    console.error('Error deleting season:', error);
                    showError('Failed to delete season');
                }
            }
        }

        // Add crop
        document.getElementById('addCropForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await addCrop();
        });

        // Debug function to log form data
function debugFormData(formData) {
    console.log('=== FORM DATA DEBUG ===');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    console.log('=== END FORM DATA ===');
}

// Add crop
async function addCrop() {
    try {
        const form = document.getElementById('addCropForm');
        const formData = new FormData(form);
        
        // Add action parameter to help the API identify this as a crop add operation
        formData.append('action', 'add_crop');

        // Debug: Log form data
        console.log('Form elements:');
        console.log('Season ID:', document.getElementById('cropSeasonId').value);
        console.log('Crop Name:', document.getElementById('addCropForm').querySelector('input[name="name"]').value);
        console.log('Planting Guide:', document.getElementById('addCropForm').querySelector('textarea[name="planting_guide"]').value);
        
        debugFormData(formData);

        const addBtn = document.getElementById('addCropBtnModal');
        addBtn.disabled = true;
        addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';

        const response = await fetch(SEASONS_API, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        console.log('API Response:', data);

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Crop added successfully',
                timer: 2000
            });

            // Close modal and reset form
            const modal = bootstrap.Modal.getInstance(document.getElementById('addCropModal'));
            modal.hide();
            form.reset();

            // Reload crops for current season
            if (currentSelectedSeasonId) {
                await loadCrops(currentSelectedSeasonId);
                loadSeasons(); // To update stats
            }
        } else {
            throw new Error(data.error || 'Failed to add crop');
        }
    } catch (error) {
        console.error('Error adding crop:', error);
        showError(error.message);
    } finally {
        const addBtn = document.getElementById('addCropBtnModal');
        addBtn.disabled = false;
        addBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add Crop';
    }
}

        // Update the addCrop function to include an action parameter
        async function addCrop() {
            try {
                const form = document.getElementById('addCropForm');
                const formData = new FormData(form);

                // Add action parameter to help the API identify this as a crop add operation
                formData.append('action', 'add_crop');

                // Debug: Log form data
                debugFormData(formData);

                const addBtn = document.getElementById('addCropBtnModal');
                addBtn.disabled = true;
                addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';

                const response = await fetch(SEASONS_API, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                console.log('API Response:', data);

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Crop added successfully',
                        timer: 2000
                    });

                    // Close modal and reset form
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addCropModal'));
                    modal.hide();
                    form.reset();

                    // Reload crops for current season
                    if (currentSelectedSeasonId) {
                        await loadCrops(currentSelectedSeasonId);
                        loadSeasons(); // To update stats
                    }
                } else {
                    throw new Error(data.error || 'Failed to add crop');
                }
            } catch (error) {
                console.error('Error adding crop:', error);
                showError(error.message);
            } finally {
                const addBtn = document.getElementById('addCropBtnModal');
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fas fa-plus me-2"></i>Add Crop';
            }
        }

        // Edit crop
        async function editCrop(cropId) {
            try {
                const response = await fetch(`${SEASONS_API}?crop_id=${cropId}`);
                const data = await response.json();

                if (data.success) {
                    const crop = data.data;
                    populateEditCropForm(crop);

                    const modal = new bootstrap.Modal(document.getElementById('editCropModal'));
                    modal.show();
                } else {
                    throw new Error(data.error || 'Failed to load crop data');
                }
            } catch (error) {
                console.error('Error loading crop:', error);
                showError('Failed to load crop data');
            }
        }

        function populateEditCropForm(crop) {
            document.getElementById('editCropId').value = crop.id;
            document.getElementById('editCropName').value = crop.name;
            document.getElementById('editCropDescription').value = crop.description || '';
            document.getElementById('editCropPlantingGuide').value = crop.planting_guide || '';
            document.getElementById('editCropWater').value = crop.water_requirements || '';
            document.getElementById('editCropSunlight').value = crop.sunlight_requirements || '';
            document.getElementById('editCropSeasonName').textContent = crop.season_name || 'Unknown Season';
        }

        // Update crop
        document.getElementById('editCropForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await updateCrop();
        });

        async function updateCrop() {
            try {
                const form = document.getElementById('editCropForm');
                const formData = new FormData(form);

                const updateBtn = document.getElementById('updateCropBtn');
                updateBtn.disabled = true;
                updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';

                const response = await fetch(SEASONS_API, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Crop updated successfully',
                        timer: 2000
                    });

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editCropModal'));
                    modal.hide();

                    // Reload crops for current season
                    if (currentSelectedSeasonId) {
                        await loadCrops(currentSelectedSeasonId);
                        loadSeasons(); // To update stats
                    }
                } else {
                    throw new Error(data.error || 'Failed to update crop');
                }
            } catch (error) {
                console.error('Error updating crop:', error);
                showError(error.message);
            } finally {
                const updateBtn = document.getElementById('updateCropBtn');
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Crop';
            }
        }

        // Delete crop
        async function deleteCrop(cropId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(SEASONS_API, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            crop_id: cropId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Crop has been deleted',
                            timer: 2000
                        });

                        // Reload crops for current season
                        if (currentSelectedSeasonId) {
                            await loadCrops(currentSelectedSeasonId);
                            loadSeasons(); // To update stats
                        }
                    } else {
                        throw new Error(data.error || 'Failed to delete crop');
                    }
                } catch (error) {
                    console.error('Error deleting crop:', error);
                    showError('Failed to delete crop');
                }
            }
        }

        // Utility functions
        function showSeasonsLoading() {
            document.getElementById('seasonsList').innerHTML = `
                <div class="text-center py-3">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2">Loading seasons...</div>
                </div>
            `;
        }

        function showCropsLoading() {
            document.getElementById('cropsContainer').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading crops...</span>
                    </div>
                    <div class="mt-2">Loading crops...</div>
                </div>
            `;
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 3000
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

        function getMonthName(monthNumber) {
            const months = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            return months[monthNumber - 1] || 'Unknown';
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadSeasons();
        });
    </script>

    <style>
        .crop-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e9ecef;
        }

        .crop-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: #e8f5e8;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: #2e7d32;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 0.5rem;
        }

        .list-group-item.active {
            background-color: #2e7d32;
            border-color: #2e7d32;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }
        }
    </style>
</body>

</html>