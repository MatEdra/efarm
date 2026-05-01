<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmers Management - Smart Farming Philippines</title>
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

        <!-- Farmers Management Content -->
        <div id="farmers">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Farmers Management</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFarmerModal">
                    <i class="fas fa-user-plus me-2"></i>Add New Farmer
                </button>
            </div>

            <!-- Filters and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search Farmer</label>
                            <input type="text" class="form-control" id="searchFarmer" placeholder="Search by name, phone, or location...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Province</label>
                            <select class="form-select" id="filterLocation">
                                <option value="">All Provinces</option>
                                <optgroup label="Region I - Ilocos Region">
                                    <option>Ilocos Norte</option><option>Ilocos Sur</option><option>La Union</option><option>Pangasinan</option>
                                </optgroup>
                                <optgroup label="Region II - Cagayan Valley">
                                    <option>Batanes</option><option>Cagayan</option><option>Isabela</option><option>Nueva Vizcaya</option><option>Quirino</option>
                                </optgroup>
                                <optgroup label="Region III - Central Luzon">
                                    <option>Aurora</option><option>Bataan</option><option>Bulacan</option><option>Nueva Ecija</option><option>Pampanga</option><option>Tarlac</option><option>Zambales</option>
                                </optgroup>
                                <optgroup label="Region IV-A - CALABARZON">
                                    <option>Batangas</option><option>Cavite</option><option>Laguna</option><option>Quezon</option><option>Rizal</option>
                                </optgroup>
                                <optgroup label="Region IV-B - MIMAROPA">
                                    <option>Marinduque</option><option>Occidental Mindoro</option><option>Oriental Mindoro</option><option>Palawan</option><option>Romblon</option>
                                </optgroup>
                                <optgroup label="Region V - Bicol Region">
                                    <option>Albay</option><option>Camarines Norte</option><option>Camarines Sur</option><option>Catanduanes</option><option>Masbate</option><option>Sorsogon</option>
                                </optgroup>
                                <optgroup label="Region VI - Western Visayas">
                                    <option>Aklan</option><option>Antique</option><option>Capiz</option><option>Guimaras</option><option>Iloilo</option><option>Negros Occidental</option>
                                </optgroup>
                                <optgroup label="Region VII - Central Visayas">
                                    <option>Bohol</option><option>Cebu</option><option>Negros Oriental</option><option>Siquijor</option>
                                </optgroup>
                                <optgroup label="Region VIII - Eastern Visayas">
                                    <option>Biliran</option><option>Eastern Samar</option><option>Leyte</option><option>Northern Samar</option><option>Samar</option><option>Southern Leyte</option>
                                </optgroup>
                                <optgroup label="Region IX - Zamboanga Peninsula">
                                    <option>Zamboanga del Norte</option><option>Zamboanga del Sur</option><option>Zamboanga Sibugay</option>
                                </optgroup>
                                <optgroup label="Region X - Northern Mindanao">
                                    <option>Bukidnon</option><option>Camiguin</option><option>Lanao del Norte</option><option>Misamis Occidental</option><option>Misamis Oriental</option>
                                </optgroup>
                                <optgroup label="Region XI - Davao Region">
                                    <option>Davao de Oro</option><option>Davao del Norte</option><option>Davao del Sur</option><option>Davao Occidental</option><option>Davao Oriental</option>
                                </optgroup>
                                <optgroup label="Region XII - SOCCSKSARGEN">
                                    <option>Cotabato</option><option>Sarangani</option><option>South Cotabato</option><option>Sultan Kudarat</option>
                                </optgroup>
                                <optgroup label="Region XIII - Caraga">
                                    <option>Agusan del Norte</option><option>Agusan del Sur</option><option>Dinagat Islands</option><option>Surigao del Norte</option><option>Surigao del Sur</option>
                                </optgroup>
                                <optgroup label="CAR - Cordillera Administrative Region">
                                    <option>Abra</option><option>Apayao</option><option>Benguet</option><option>Ifugao</option><option>Kalinga</option><option>Mountain Province</option>
                                </optgroup>
                                <optgroup label="NCR - National Capital Region">
                                    <option>Caloocan</option><option>Las Piñas</option><option>Makati</option><option>Malabon</option><option>Mandaluyong</option><option>Manila</option><option>Marikina</option><option>Muntinlupa</option><option>Navotas</option><option>Parañaque</option><option>Pasay</option><option>Pasig</option><option>Pateros</option><option>Quezon City</option><option>San Juan</option><option>Taguig</option><option>Valenzuela</option>
                                </optgroup>
                                <optgroup label="BARMM - Bangsamoro">
                                    <option>Basilan</option><option>Lanao del Sur</option><option>Maguindanao del Norte</option><option>Maguindanao del Sur</option><option>Sulu</option><option>Tawi-Tawi</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select class="form-select" id="filterGender">
                                <option value="">All Genders</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Farmers Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="farmersTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact Info</th>
                                    <th>Farm Details</th>
                                    <th>Crops Grown</th>
                                    <th>Gender</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="farmersTableBody">
                                <!-- Data will be loaded via JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Farmers pagination">
                        <ul class="pagination justify-content-center mt-4" id="pagination">
                            <!-- Pagination will be loaded via JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <?php include("nav_mobile.php"); ?>

    <!-- Add Farmer Modal -->
    <div class="modal fade" id="addFarmerModal" tabindex="-1" aria-labelledby="addFarmerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFarmerModalLabel">Add New Farmer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addFarmerForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" class="form-control" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" name="last_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" name="phone_number" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender *</label>
                                    <select class="form-select" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Farm Name</label>
                                    <input type="text" class="form-control" name="farm_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Farm Location</label>
                                    <input type="hidden" name="farm_location" id="addLocationValue">
                                    <select class="form-select form-select-sm mb-1" id="addRegion">
                                        <option value="">— Select Region —</option>
                                    </select>
                                    <select class="form-select form-select-sm mb-1" id="addProvince" disabled>
                                        <option value="">— Select Province —</option>
                                    </select>
                                    <select class="form-select form-select-sm mb-1" id="addMunicipality" disabled>
                                        <option value="">— Select Municipality / City —</option>
                                    </select>
                                    <select class="form-select form-select-sm mb-1" id="addBarangay" disabled>
                                        <option value="">— Select Barangay —</option>
                                    </select>
                                    <div class="form-text text-success fw-semibold" id="addLocationDisplay"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Farm Size (hectares)</label>
                                    <input type="number" class="form-control" name="farm_size" step="0.1" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Years of Experience</label>
                                    <input type="number" class="form-control" name="experience_years" min="0" max="50">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Crops Grown *</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="rice">
                                        <label class="form-check-label">Rice</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="corn">
                                        <label class="form-check-label">Corn</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="vegetables">
                                        <label class="form-check-label">Vegetables</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="fruits">
                                        <label class="form-check-label">Fruits</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="root_crops">
                                        <label class="form-check-label">Root Crops</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="others">
                                        <label class="form-check-label">Others</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveFarmerBtn">
                            <i class="fas fa-save me-2"></i>Save Farmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Farmer Modal -->
    <div class="modal fade" id="editFarmerModal" tabindex="-1" aria-labelledby="editFarmerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFarmerModalLabel">Edit Farmer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFarmerForm">
                    <input type="hidden" name="farmer_id" id="editFarmerId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name *</label>
                                    <input type="text" class="form-control" name="first_name" id="editFirstName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" name="last_name" id="editLastName" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" name="email" id="editEmail" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" name="phone_number" id="editPhone" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Gender *</label>
                                    <select class="form-select" name="gender" id="editGender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" id="editDateOfBirth">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Farm Name</label>
                                    <input type="text" class="form-control" name="farm_name" id="editFarmName">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Farm Location</label>
                                    <input type="hidden" name="farm_location" id="editLocationValue">
                                    <select class="form-select form-select-sm mb-1" id="editRegion">
                                        <option value="">— Select Region —</option>
                                    </select>
                                    <select class="form-select form-select-sm mb-1" id="editProvince" disabled>
                                        <option value="">— Select Province —</option>
                                    </select>
                                    <select class="form-select form-select-sm mb-1" id="editMunicipality" disabled>
                                        <option value="">— Select Municipality / City —</option>
                                    </select>
                                    <select class="form-select form-select-sm mb-1" id="editBarangay" disabled>
                                        <option value="">— Select Barangay —</option>
                                    </select>
                                    <div class="form-text text-success fw-semibold" id="editLocationDisplay"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Farm Size (hectares)</label>
                                    <input type="number" class="form-control" name="farm_size" id="editFarmSize" step="0.1" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Years of Experience</label>
                                    <input type="number" class="form-control" name="experience_years" id="editExperience" min="0" max="50">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Crops Grown *</label>
                            <div class="row" id="editCropsContainer">
                                <!-- Crops checkboxes will be populated dynamically -->
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" name="password" minlength="6">
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateFarmerBtn">
                            <i class="fas fa-save me-2"></i>Update Farmer
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
    <script>
        // ── PSGC Location Cascade ─────────────────────────────────────────────
        const PSGC = 'https://psgc.gitlab.io/api';
        const _locCache = {};

        async function psgcGet(path) {
            if (_locCache[path]) return _locCache[path];
            try {
                const res = await fetch(PSGC + path);
                if (!res.ok) return [];
                const data = await res.json();
                const list = Array.isArray(data) ? data : [];
                _locCache[path] = list.sort((a, b) => a.name.localeCompare(b.name));
                return _locCache[path];
            } catch { return []; }
        }

        function $id(id) { return document.getElementById(id); }

        function fillSelect(id, items, placeholder) {
            const s = $id(id);
            s.innerHTML = `<option value="">${placeholder}</option>` +
                items.map(i => `<option value="${i.code}" data-name="${i.name}">${i.name}</option>`).join('');
            s.disabled = items.length === 0;
        }

        function clearSelect(id, placeholder) {
            const s = $id(id);
            s.innerHTML = `<option value="">${placeholder}</option>`;
            s.disabled = true;
        }

        function getSelName(id) {
            const s = $id(id);
            return s?.value ? (s.options[s.selectedIndex]?.dataset?.name || '') : '';
        }

        function updateLocationValue(pfx) {
            const parts = [
                getSelName(pfx + 'Region'),
                getSelName(pfx + 'Province'),
            ];
            // Municipality is absent for NCR (disabled placeholder)
            const muniEl = $id(pfx + 'Municipality');
            if (muniEl && muniEl.value) parts.push(getSelName(pfx + 'Municipality'));
            const brgy = getSelName(pfx + 'Barangay');
            if (brgy) parts.push(brgy);

            const val = parts.filter(Boolean).join(' > ');
            $id(pfx + 'LocationValue').value = val;
            $id(pfx + 'LocationDisplay').textContent = val ? '📍 ' + val : '';
        }

        async function onRegionChange(pfx) {
            const code = $id(pfx + 'Region').value;
            clearSelect(pfx + 'Province', '— Select Province —');
            clearSelect(pfx + 'Municipality', '— Select Municipality / City —');
            clearSelect(pfx + 'Barangay', '— Select Barangay —');
            delete $id(pfx + 'Province').dataset.direct;
            updateLocationValue(pfx);
            if (!code) return;

            $id(pfx + 'Province').innerHTML = '<option value="">Loading...</option>';
            $id(pfx + 'Province').disabled = true;

            const provinces = await psgcGet(`/regions/${code}/provinces.json`);
            if (provinces.length > 0) {
                fillSelect(pfx + 'Province', provinces, '— Select Province —');
            } else {
                // NCR / no-province region: load cities + municipalities directly
                const [cities, munis] = await Promise.all([
                    psgcGet(`/regions/${code}/cities.json`),
                    psgcGet(`/regions/${code}/municipalities.json`)
                ]);
                const combined = [...cities, ...munis].sort((a, b) => a.name.localeCompare(b.name));
                fillSelect(pfx + 'Province', combined, '— Select City / Municipality —');
                $id(pfx + 'Province').dataset.direct = 'true';
                // Municipality level is not applicable
                $id(pfx + 'Municipality').innerHTML = '<option value="">— N/A —</option>';
            }
        }

        async function onProvinceChange(pfx) {
            const pSel = $id(pfx + 'Province');
            const code = pSel.value;
            clearSelect(pfx + 'Municipality', '— Select Municipality / City —');
            clearSelect(pfx + 'Barangay', '— Select Barangay —');
            updateLocationValue(pfx);
            if (!code) return;

            if (pSel.dataset.direct === 'true') {
                // NCR: province slot holds the city — load barangays directly
                $id(pfx + 'Barangay').innerHTML = '<option value="">Loading...</option>';
                $id(pfx + 'Barangay').disabled = true;
                const [b1, b2] = await Promise.all([
                    psgcGet(`/cities/${code}/barangays.json`),
                    psgcGet(`/municipalities/${code}/barangays.json`)
                ]);
                fillSelect(pfx + 'Barangay', b1.length ? b1 : b2, '— Select Barangay —');
                return;
            }

            $id(pfx + 'Municipality').innerHTML = '<option value="">Loading...</option>';
            $id(pfx + 'Municipality').disabled = true;
            const [cities, munis] = await Promise.all([
                psgcGet(`/provinces/${code}/cities.json`),
                psgcGet(`/provinces/${code}/municipalities.json`)
            ]);
            const combined = [...cities, ...munis].sort((a, b) => a.name.localeCompare(b.name));
            fillSelect(pfx + 'Municipality', combined, '— Select Municipality / City —');
        }

        async function onMunicipalityChange(pfx) {
            const code = $id(pfx + 'Municipality').value;
            clearSelect(pfx + 'Barangay', '— Select Barangay —');
            updateLocationValue(pfx);
            if (!code) return;

            $id(pfx + 'Barangay').innerHTML = '<option value="">Loading...</option>';
            $id(pfx + 'Barangay').disabled = true;
            const [b1, b2] = await Promise.all([
                psgcGet(`/cities/${code}/barangays.json`),
                psgcGet(`/municipalities/${code}/barangays.json`)
            ]);
            fillSelect(pfx + 'Barangay', b1.length ? b1 : b2, '— Select Barangay —');
        }

        async function initRegions() {
            const regions = await psgcGet('/regions.json');
            ['add', 'edit'].forEach(pfx => {
                fillSelect(pfx + 'Region', regions, '— Select Region —');
            });
        }

        // Restore a stored breadcrumb "Region > Province > Municipality > Barangay"
        // into the edit form cascade selects
        async function restoreLocation(storedValue) {
            const pfx = 'edit';
            if (!storedValue) return;
            $id(pfx + 'LocationValue').value = storedValue;
            $id(pfx + 'LocationDisplay').textContent = '📍 ' + storedValue;

            const parts = storedValue.split(' > ').map(s => s.trim());
            const [regionName, level2, level3, level4] = parts;
            if (!regionName) return;

            const regions = await psgcGet('/regions.json');
            const region = regions.find(r => r.name === regionName);
            if (!region) return;
            $id(pfx + 'Region').value = region.code;

            if (!level2) return;
            const provinces = await psgcGet(`/regions/${region.code}/provinces.json`);

            if (provinces.length > 0) {
                // Standard path: level2=Province, level3=Municipality, level4=Barangay
                fillSelect(pfx + 'Province', provinces, '— Select Province —');
                const province = provinces.find(p => p.name === level2);
                if (!province) return;
                $id(pfx + 'Province').value = province.code;

                if (!level3) return;
                const [cities, munis] = await Promise.all([
                    psgcGet(`/provinces/${province.code}/cities.json`),
                    psgcGet(`/provinces/${province.code}/municipalities.json`)
                ]);
                const combined = [...cities, ...munis].sort((a, b) => a.name.localeCompare(b.name));
                fillSelect(pfx + 'Municipality', combined, '— Select Municipality / City —');
                const muni = combined.find(m => m.name === level3);
                if (!muni) return;
                $id(pfx + 'Municipality').value = muni.code;

                if (!level4) return;
                const [b1, b2] = await Promise.all([
                    psgcGet(`/cities/${muni.code}/barangays.json`),
                    psgcGet(`/municipalities/${muni.code}/barangays.json`)
                ]);
                const barangays = b1.length ? b1 : b2;
                fillSelect(pfx + 'Barangay', barangays, '— Select Barangay —');
                const brgy = barangays.find(b => b.name === level4);
                if (brgy) $id(pfx + 'Barangay').value = brgy.code;

            } else {
                // NCR path: level2=City, level3=Barangay
                const [cities, munis] = await Promise.all([
                    psgcGet(`/regions/${region.code}/cities.json`),
                    psgcGet(`/regions/${region.code}/municipalities.json`)
                ]);
                const combined = [...cities, ...munis].sort((a, b) => a.name.localeCompare(b.name));
                fillSelect(pfx + 'Province', combined, '— Select City / Municipality —');
                $id(pfx + 'Province').dataset.direct = 'true';
                $id(pfx + 'Municipality').innerHTML = '<option value="">— N/A —</option>';
                const city = combined.find(c => c.name === level2);
                if (!city) return;
                $id(pfx + 'Province').value = city.code;

                if (!level3) return;
                const [b1, b2] = await Promise.all([
                    psgcGet(`/cities/${city.code}/barangays.json`),
                    psgcGet(`/municipalities/${city.code}/barangays.json`)
                ]);
                const barangays = b1.length ? b1 : b2;
                fillSelect(pfx + 'Barangay', barangays, '— Select Barangay —');
                const brgy = barangays.find(b => b.name === level3);
                if (brgy) $id(pfx + 'Barangay').value = brgy.code;
            }
        }
        // ──────────────────────────────────────────────────────────────────────

        // API endpoints
        const FARMERS_API = 'function/farmers_api.php';
        let currentPage = 1;
        let totalPages = 1;
        let currentFilters = {};

        // Load farmers data
        async function loadFarmers(page = 1, filters = {}) {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: page,
                    ...filters
                });

                const response = await fetch(`${FARMERS_API}?${params}`);
                const data = await response.json();

                if (data.success) {
                    displayFarmers(data.data.farmers);
                    updatePagination(data.data.pagination);
                    currentPage = data.data.pagination.current_page;
                    totalPages = data.data.pagination.total_pages;
                } else {
                    throw new Error(data.error || 'Failed to load farmers');
                }
            } catch (error) {
                console.error('Error loading farmers:', error);
                showError('Failed to load farmers data');
            } finally {
                hideLoading();
            }
        }

        // Display farmers in table
        function displayFarmers(farmers) {
            const tbody = document.getElementById('farmersTableBody');

            if (farmers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-3 d-block"></i>
                            No farmers found
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = farmers.map((farmer, index) => `
                <tr>
                    <td>${farmer.id}</td>
                    <td>
                        <div class="fw-semibold">${escapeHtml(farmer.first_name)} ${escapeHtml(farmer.last_name)}</div>
                        <small class="text-muted">ID: ${farmer.id}</small>
                    </td>
                    <td>
                        <div><i class="fas fa-phone me-2"></i>${escapeHtml(farmer.phone_number)}</div>
                        <div><i class="fas fa-envelope me-2"></i>${escapeHtml(farmer.email)}</div>
                    </td>
                    <td>
                        <div><strong>${farmer.farm_name || 'Not specified'}</strong></div>
                        <div><i class="fas fa-map-marker-alt me-2"></i>${farmer.farm_location || 'Not specified'}</div>
                        <div><i class="fas fa-ruler me-2"></i>${farmer.farm_size ? farmer.farm_size + ' ha' : 'N/A'}</div>
                    </td>
                    <td>
                        ${formatCrops(farmer.crops)}
                    </td>
                    <td>
                        ${formatGender(farmer.gender)}
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="editFarmer(${farmer.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="deleteFarmer(${farmer.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-outline-info" onclick="viewFarmer(${farmer.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Format crops for display
        function formatCrops(crops) {
            if (!crops || crops.length === 0) {
                return '<span class="text-muted">No crops specified</span>';
            }

            const cropLabels = {
                'rice': 'Rice',
                'corn': 'Corn',
                'vegetables': 'Vegetables',
                'fruits': 'Fruits',
                'root_crops': 'Root Crops',
                'others': 'Others'
            };

            return crops.map(crop => `
                <span class="badge bg-success me-1 mb-1">${cropLabels[crop] || crop}</span>
            `).join('');
        }

        // Format gender for display
        function formatGender(gender) {
            if (!gender) {
                return '<span class="text-muted">Not specified</span>';
            }

            const genderIcons = {
                'Male': 'fas fa-mars',
                'Female': 'fas fa-venus',
                'Other': 'fas fa-transgender'
            };

            const genderColors = {
                'Male': 'primary',
                'Female': 'pink',
                'Other': 'info'
            };

            const iconClass = genderIcons[gender] || 'fas fa-user';
            const colorClass = genderColors[gender] || 'secondary';

            return `
                <span class="badge bg-${colorClass}">
                    <i class="${iconClass} me-1"></i>${gender}
                </span>
            `;
        }

        // Update pagination
        function updatePagination(pagination) {
            const paginationEl = document.getElementById('pagination');
            const {
                current_page,
                total_pages
            } = pagination;

            let paginationHTML = '';

            // Previous button
            paginationHTML += `
                <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${current_page - 1})">Previous</a>
                </li>
            `;

            // Page numbers
            for (let i = 1; i <= total_pages; i++) {
                if (i === 1 || i === total_pages || (i >= current_page - 1 && i <= current_page + 1)) {
                    paginationHTML += `
                        <li class="page-item ${i === current_page ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                        </li>
                    `;
                } else if (i === current_page - 2 || i === current_page + 2) {
                    paginationHTML += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                }
            }

            // Next button
            paginationHTML += `
                <li class="page-item ${current_page === total_pages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${current_page + 1})">Next</a>
                </li>
            `;

            paginationEl.innerHTML = paginationHTML;
        }

        // Change page
        function changePage(page) {
            if (page >= 1 && page <= totalPages) {
                loadFarmers(page, currentFilters);
            }
        }

        // Search and filter functions
        function setupFilters() {
            const searchInput = document.getElementById('searchFarmer');
            const locationFilter = document.getElementById('filterLocation');
            const genderFilter = document.getElementById('filterGender');

            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });

            locationFilter.addEventListener('change', applyFilters);
            genderFilter.addEventListener('change', applyFilters);
        }

        function applyFilters() {
            const search = document.getElementById('searchFarmer').value;
            const location = document.getElementById('filterLocation').value;
            const gender = document.getElementById('filterGender').value;

            currentFilters = {};
            if (search) currentFilters.search = search;
            if (location) currentFilters.location = location;
            if (gender) currentFilters.gender = gender;

            loadFarmers(1, currentFilters);
        }

        function resetFilters() {
            document.getElementById('searchFarmer').value = '';
            document.getElementById('filterLocation').value = '';
            document.getElementById('filterGender').value = '';
            currentFilters = {};
            loadFarmers(1);
        }

        // Add new farmer
        document.getElementById('addFarmerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await saveFarmer();
        });

        async function saveFarmer() {
            try {
                const form = document.getElementById('addFarmerForm');
                const formData = new FormData(form);

                // Validate passwords match
                const password = formData.get('password');
                const confirmPassword = formData.get('confirm_password');

                if (password !== confirmPassword) {
                    showError('Passwords do not match');
                    return;
                }

                // Validate at least one crop is selected
                const selectedCrops = form.querySelectorAll('input[name="crops[]"]:checked');
                if (selectedCrops.length === 0) {
                    showError('Please select at least one crop');
                    return;
                }

                // Get selected crops
                const crops = [];
                selectedCrops.forEach(checkbox => {
                    crops.push(checkbox.value);
                });
                formData.append('crops', JSON.stringify(crops));

                const saveBtn = document.getElementById('saveFarmerBtn');
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';

                const response = await fetch(FARMERS_API, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Farmer added successfully',
                        timer: 2000
                    });

                    // Close modal and reset form
                    // In saveFarmer function, update the modal closing:
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addFarmerModal'));
                    modal.hide();
                    form.reset();

                    // Reload farmers list
                    loadFarmers(currentPage, currentFilters);
                } else {
                    throw new Error(data.error || 'Failed to add farmer');
                }
            } catch (error) {
                console.error('Error adding farmer:', error);
                showError(error.message);
            } finally {
                const saveBtn = document.getElementById('saveFarmerBtn');
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save me-2"></i>Save Farmer';
            }
        }

        // Edit farmer
        async function editFarmer(farmerId) {
            try {
                const response = await fetch(`${FARMERS_API}?id=${farmerId}`);
                const data = await response.json();

                if (data.success) {
                    const farmer = data.data;
                    populateEditForm(farmer);

                    // Fix: Use the correct way to show the modal
                    const modalElement = document.getElementById('editFarmerModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    throw new Error(data.error || 'Failed to load farmer data');
                }
            } catch (error) {
                console.error('Error loading farmer:', error);
                showError('Failed to load farmer data: ' + error.message);
            }
        }

        function populateEditForm(farmer) {
            document.getElementById('editFarmerId').value = farmer.id;
            document.getElementById('editFirstName').value = farmer.first_name;
            document.getElementById('editLastName').value = farmer.last_name;
            document.getElementById('editEmail').value = farmer.email;
            document.getElementById('editPhone').value = farmer.phone_number;
            document.getElementById('editGender').value = farmer.gender || '';
            document.getElementById('editDateOfBirth').value = farmer.date_of_birth || '';
            document.getElementById('editFarmName').value = farmer.farm_name || '';
            document.getElementById('editFarmSize').value = farmer.farm_size || '';
            // Restore 4-level location cascade asynchronously
            restoreLocation(farmer.farm_location || '');
            document.getElementById('editExperience').value = farmer.experience_years || '';

            // Populate crops checkboxes
            const cropsContainer = document.getElementById('editCropsContainer');
            const crops = ['rice', 'corn', 'vegetables', 'fruits', 'root_crops', 'others'];

            cropsContainer.innerHTML = crops.map(crop => `
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="crops[]" value="${crop}" 
                               ${farmer.crops && farmer.crops.includes(crop) ? 'checked' : ''}>
                        <label class="form-check-label">${crop.charAt(0).toUpperCase() + crop.slice(1).replace('_', ' ')}</label>
                    </div>
                </div>
            `).join('');
        }

        // Update farmer
        document.getElementById('editFarmerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await updateFarmer();
        });

        async function updateFarmer() {
            try {
                const form = document.getElementById('editFarmerForm');
                const formData = new FormData(form);

                // Validate at least one crop is selected
                const selectedCrops = form.querySelectorAll('input[name="crops[]"]:checked');
                if (selectedCrops.length === 0) {
                    showError('Please select at least one crop');
                    return;
                }

                // Convert FormData to object
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (key === 'crops[]') {
                        if (!data.crops) data.crops = [];
                        data.crops.push(value);
                    } else {
                        data[key] = value;
                    }
                }

                // Convert farmer_id to number
                data.farmer_id = parseInt(data.farmer_id);

                const updateBtn = document.getElementById('updateFarmerBtn');
                updateBtn.disabled = true;
                updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';

                const response = await fetch(FARMERS_API, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const responseText = await response.text();
                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Failed to parse JSON response:', parseError);
                    throw new Error(`Server returned invalid JSON: ${responseText.substring(0, 200)}`);
                }

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Farmer updated successfully',
                        timer: 2000
                    });

                    // In updateFarmer function, update the modal closing:
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editFarmerModal'));
                    modal.hide();

                    // Reload farmers list
                    loadFarmers(currentPage, currentFilters);
                } else {
                    throw new Error(result.error || 'Failed to update farmer');
                }
            } catch (error) {
                console.error('Error updating farmer:', error);
                showError(error.message);
            } finally {
                const updateBtn = document.getElementById('updateFarmerBtn');
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Farmer';
            }
        }

        // Delete farmer
        async function deleteFarmer(farmerId) {
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
                    const response = await fetch(FARMERS_API, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: farmerId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Farmer has been deleted',
                            timer: 2000
                        });

                        // Reload farmers list
                        loadFarmers(currentPage, currentFilters);
                    } else {
                        throw new Error(data.error || 'Failed to delete farmer');
                    }
                } catch (error) {
                    console.error('Error deleting farmer:', error);
                    showError('Failed to delete farmer');
                }
            }
        }

        // View farmer details
        function viewFarmer(farmerId) {
            // Redirect to farmer details page or show in modal
            Swal.fire({
                title: 'Farmer Details',
                text: 'Redirecting to farmer details page...',
                icon: 'info',
                timer: 1500
            });
            window.location.href = `farmer-details.php?id=${farmerId}`;
        }

        // Utility functions
        function showLoading() {
            document.getElementById('farmersTableBody').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2">Loading farmers...</div>
                    </td>
                </tr>
            `;
        }

        function hideLoading() {
            // Loading state is handled in displayFarmers
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

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            initRegions();

            ['add', 'edit'].forEach(pfx => {
                $id(pfx + 'Region').addEventListener('change', () => onRegionChange(pfx));
                $id(pfx + 'Province').addEventListener('change', () => onProvinceChange(pfx));
                $id(pfx + 'Municipality').addEventListener('change', () => onMunicipalityChange(pfx));
                $id(pfx + 'Barangay').addEventListener('change', () => updateLocationValue(pfx));
            });

            // Reset location when add modal closes
            document.getElementById('addFarmerModal').addEventListener('hidden.bs.modal', () => {
                ['Province', 'Municipality', 'Barangay'].forEach(lvl => {
                    clearSelect('add' + lvl, `— Select ${lvl} —`);
                    delete $id('add' + lvl).dataset.direct;
                });
                $id('addLocationValue').value = '';
                $id('addLocationDisplay').textContent = '';
            });

            loadFarmers();
            setupFilters();
        });
    </script>

    <style>
        .bg-pink {
            background-color: #e83e8c !important;
        }

        .text-pink {
            color: #e83e8c !important;
        }
    </style>
</body>

</html>