<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Materials - Smart Farming Philippines</title>
    <?php include __DIR__ . '/../pwa_head.php'; ?>
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

        <!-- Learning Materials Content -->
        <div id="materials">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Learning Materials Management</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                    <i class="fas fa-upload me-2"></i>Upload New Material
                </button>
            </div>

            <!-- Stats Overview -->
            <div class="stats-grid mb-4">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-number" id="totalPdf">0</div>
                    <div class="stat-label">PDF Documents</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="stat-number" id="totalVideos">0</div>
                    <div class="stat-label">Videos</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-number" id="totalArticles">0</div>
                    <div class="stat-label">Articles</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-number" id="totalDownloads">0</div>
                    <div class="stat-label">Total Downloads</div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search Materials</label>
                            <input type="text" class="form-control" id="searchMaterial" placeholder="Search by title, description...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Material Type</label>
                            <select class="form-select" id="filterType">
                                <option value="">All Types</option>
                                <option value="pdf">PDF Documents</option>
                                <option value="video">Videos</option>
                                <option value="article">Articles</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sort By</label>
                            <select class="form-select" id="filterSort">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="popular">Most Popular</option>
                                <option value="title">Title A-Z</option>
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

            <!-- Materials Grid -->
            <div class="row" id="materialsGrid">
                <!-- Materials will be loaded via JavaScript -->
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading materials...</span>
                    </div>
                    <div class="mt-2">Loading materials...</div>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Materials pagination" class="mt-4">
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- Pagination will be loaded via JavaScript -->
                </ul>
            </nav>
        </div>
    </div>

    <?php include("nav_mobile.php"); ?>

    <!-- Upload Material Modal -->
    <div class="modal fade" id="uploadMaterialModal" tabindex="-1" aria-labelledby="uploadMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadMaterialModalLabel">Upload Learning Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadMaterialForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Material Title *</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Material Type *</label>
                                    <select class="form-select" name="file_type" required onchange="toggleFileFields(this.value)">
                                        <option value="">Select Type</option>
                                        <option value="pdf">PDF Document</option>
                                        <option value="video">Video</option>
                                        <option value="article">Article</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Enter material description..."></textarea>
                        </div>

                        <!-- File Upload Section -->
                        <div class="mb-3" id="fileUploadSection">
                            <label class="form-label">Upload File *</label>
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                <h5>Drop files here or click to upload</h5>
                                <p class="text-muted" id="fileRequirements">
                                    Supported: PDF files (Max: 50MB)
                                </p>
                                <input type="file" class="d-none" id="fileInput" name="file" accept=".pdf,.mp4,.mov,.avi,.jpg,.jpeg,.png,.doc,.docx">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('fileInput').click()">
                                    Select File
                                </button>
                                <div class="mt-3" id="filePreview"></div>
                            </div>
                        </div>

                        <!-- Video Duration (for videos) -->
                        <div class="row" id="videoFields" style="display: none;">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Duration (minutes)</label>
                                    <input type="number" class="form-control" name="duration" min="1" placeholder="e.g., 45">
                                </div>
                            </div>
                        </div>

                        <!-- Article Content (for articles) -->
                        <div class="mb-3" id="articleFields" style="display: none;">
                            <label class="form-label">Article Content</label>
                            <textarea class="form-control" name="content" rows="6" placeholder="Enter article content here..."></textarea>
                        </div>

                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="crop_management">
                                        <label class="form-check-label">Crop Management</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="pest_control">
                                        <label class="form-check-label">Pest Control</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="soil_health">
                                        <label class="form-check-label">Soil Health</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="irrigation">
                                        <label class="form-check-label">Irrigation</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="organic_farming">
                                        <label class="form-check-label">Organic Farming</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="season_planning">
                                        <label class="form-check-label">Season Planning</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="uploadMaterialBtn">
                            <i class="fas fa-upload me-2"></i>Upload Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Material Modal -->
    <div class="modal fade" id="editMaterialModal" tabindex="-1" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMaterialModalLabel">Edit Learning Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editMaterialForm">
                    <input type="hidden" name="material_id" id="editMaterialId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Material Title *</label>
                                    <input type="text" class="form-control" name="title" id="editTitle" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Material Type</label>
                                    <p class="form-control-plaintext" id="editFileType"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
                        </div>

                        <!-- Current File Info -->
                        <div class="mb-3">
                            <label class="form-label">Current File</label>
                            <div class="alert alert-info">
                                <i class="fas fa-file me-2"></i>
                                <span id="currentFileName">No file uploaded</span>
                                <small class="d-block text-muted" id="currentFileInfo"></small>
                            </div>
                        </div>

                        <!-- Replace File -->
                        <div class="mb-3">
                            <label class="form-label">Replace File (Optional)</label>
                            <input type="file" class="form-control" name="new_file" id="editFileInput">
                            <div class="form-text">Leave blank to keep current file</div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <div class="row" id="editCategoriesContainer">
                                <!-- Categories will be populated dynamically -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateMaterialBtn">
                            <i class="fas fa-save me-2"></i>Update Material
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Material Modal -->
    <div class="modal fade" id="viewMaterialModal" tabindex="-1" aria-labelledby="viewMaterialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMaterialModalLabel">Material Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 id="viewTitle"></h4>
                            <p class="text-muted" id="viewDescription"></p>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Type:</strong> <span id="viewType" class="badge bg-primary"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>File Size:</strong> <span id="viewSize"></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Uploaded:</strong> <span id="viewUploadDate"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Downloads:</strong> <span id="viewDownloads" class="badge bg-success"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Categories:</strong>
                                <div id="viewCategories" class="mt-1"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3" id="viewFileIcon"></i>
                                    <h6 id="viewFileName"></h6>
                                    <button class="btn btn-primary btn-sm mt-2" onclick="downloadMaterial()">
                                        <i class="fas fa-download me-1"></i>Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editCurrentMaterial()">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // API endpoints
        const MATERIALS_API = 'function/materials_api.php';
        let currentPage = 1;
        let totalPages = 1;
        let currentFilters = {};
        let currentViewMaterialId = null;

        // Load materials data
        async function loadMaterials(page = 1, filters = {}) {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: page,
                    ...filters
                });

                const response = await fetch(`${MATERIALS_API}?${params}`);
                const data = await response.json();

                if (data.success) {
                    displayMaterials(data.data.materials);
                    updatePagination(data.data.pagination);
                    updateStats(data.data.stats);
                    currentPage = data.data.pagination.current_page;
                    totalPages = data.data.pagination.total_pages;
                } else {
                    throw new Error(data.error || 'Failed to load materials');
                }
            } catch (error) {
                console.error('Error loading materials:', error);
                showError('Failed to load materials data');
            } finally {
                hideLoading();
            }
        }

        // Display materials in grid
        function displayMaterials(materials) {
            const grid = document.getElementById('materialsGrid');

            if (materials.length === 0) {
                grid.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No materials found</h5>
                        <p class="text-muted">Upload your first learning material to get started</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadMaterialModal">
                            <i class="fas fa-upload me-2"></i>Upload Material
                        </button>
                    </div>
                `;
                return;
            }

            grid.innerHTML = materials.map(material => `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card material-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge ${getTypeBadgeClass(material.file_type)}">
                                    ${material.file_type.toUpperCase()}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewMaterial(${material.id})"><i class="fas fa-eye me-2"></i>View</a></li>
                                        <li><a class="dropdown-item" href="#" onclick="editMaterial(${material.id})"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteMaterial(${material.id})"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <h6 class="card-title">${escapeHtml(material.title)}</h6>
                            <p class="card-text text-muted small">${escapeHtml(material.description || 'No description')}</p>
                            
                            <div class="material-info">
                                <div class="d-flex justify-content-between text-muted small mb-2">
                                    <span><i class="fas fa-file me-1"></i> ${formatFileSize(material.file_size)}</span>
                                    <span><i class="fas fa-download me-1"></i> ${material.download_count || 0}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">${formatDate(material.created_at)}</small>
                                    <button class="btn btn-sm btn-outline-primary" onclick="downloadMaterial(${material.id})">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
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

        // Update stats
        function updateStats(stats) {
            document.getElementById('totalPdf').textContent = stats.total_pdf || 0;
            document.getElementById('totalVideos').textContent = stats.total_videos || 0;
            document.getElementById('totalArticles').textContent = stats.total_articles || 0;
            document.getElementById('totalDownloads').textContent = stats.total_downloads || 0;
        }

        // Change page
        function changePage(page) {
            if (page >= 1 && page <= totalPages) {
                loadMaterials(page, currentFilters);
            }
        }

        // Search and filter functions
        function setupFilters() {
            const searchInput = document.getElementById('searchMaterial');
            const typeFilter = document.getElementById('filterType');
            const sortFilter = document.getElementById('filterSort');

            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    applyFilters();
                }, 500);
            });

            typeFilter.addEventListener('change', applyFilters);
            sortFilter.addEventListener('change', applyFilters);
        }

        function applyFilters() {
            const search = document.getElementById('searchMaterial').value;
            const type = document.getElementById('filterType').value;
            const sort = document.getElementById('filterSort').value;

            currentFilters = {};
            if (search) currentFilters.search = search;
            if (type) currentFilters.type = type;
            if (sort) currentFilters.sort = sort;

            loadMaterials(1, currentFilters);
        }

        function resetFilters() {
            document.getElementById('searchMaterial').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterSort').value = 'newest';
            currentFilters = {};
            loadMaterials(1);
        }

        // File upload handling
        function toggleFileFields(fileType) {
            const fileRequirements = document.getElementById('fileRequirements');
            const videoFields = document.getElementById('videoFields');
            const articleFields = document.getElementById('articleFields');
            const fileInput = document.getElementById('fileInput');

            // Reset fields
            videoFields.style.display = 'none';
            articleFields.style.display = 'none';

            switch (fileType) {
                case 'pdf':
                    fileRequirements.textContent = 'Supported: PDF files (Max: 50MB)';
                    fileInput.accept = '.pdf';
                    break;
                case 'video':
                    fileRequirements.textContent = 'Supported: MP4, MOV, AVI (Max: 500MB)';
                    fileInput.accept = '.mp4,.mov,.avi';
                    videoFields.style.display = 'flex';
                    break;
                case 'article':
                    fileRequirements.textContent = 'Supported: Images, Documents (Max: 20MB)';
                    fileInput.accept = '.jpg,.jpeg,.png,.doc,.docx';
                    articleFields.style.display = 'block';
                    break;
                default:
                    fileRequirements.textContent = 'Select a file type first';
                    fileInput.accept = '';
            }
        }

        // Handle file selection
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('filePreview');

            if (file) {
                preview.innerHTML = `
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <strong>${file.name}</strong>
                            <div class="small">${formatFileSize(file.size)}</div>
                        </div>
                    </div>
                `;
            } else {
                preview.innerHTML = '';
            }
        });

        // Upload material
        document.getElementById('uploadMaterialForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            await uploadMaterial();
        });

        async function uploadMaterial() {
            try {
                const form = document.getElementById('uploadMaterialForm');
                const formData = new FormData(form);

                // Validate file
                const fileInput = document.getElementById('fileInput');
                if (!fileInput.files[0]) {
                    showError('Please select a file to upload');
                    return;
                }

                const uploadBtn = document.getElementById('uploadMaterialBtn');
                uploadBtn.disabled = true;
                uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

                const response = await fetch(MATERIALS_API, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Material uploaded successfully',
                        timer: 2000
                    });

                    // Close modal and reset form
                    const modal = bootstrap.Modal.getInstance(document.getElementById('uploadMaterialModal'));
                    modal.hide();
                    form.reset();
                    document.getElementById('filePreview').innerHTML = '';

                    // Reload materials list
                    loadMaterials(currentPage, currentFilters);
                } else {
                    throw new Error(data.error || 'Failed to upload material');
                }
            } catch (error) {
                console.error('Error uploading material:', error);
                showError(error.message);
            } finally {
                const uploadBtn = document.getElementById('uploadMaterialBtn');
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Material';
            }
        }

        // View material
        async function viewMaterial(materialId) {
            try {
                const response = await fetch(`${MATERIALS_API}?id=${materialId}`);
                const data = await response.json();

                if (data.success) {
                    const material = data.data;
                    populateViewModal(material);

                    const modal = new bootstrap.Modal(document.getElementById('viewMaterialModal'));
                    modal.show();
                } else {
                    throw new Error(data.error || 'Failed to load material data');
                }
            } catch (error) {
                console.error('Error loading material:', error);
                showError('Failed to load material data');
            }
        }

        function populateViewModal(material) {
            currentViewMaterialId = material.id;

            document.getElementById('viewTitle').textContent = material.title;
            document.getElementById('viewDescription').textContent = material.description || 'No description';
            document.getElementById('viewType').textContent = material.file_type.toUpperCase();
            document.getElementById('viewSize').textContent = formatFileSize(material.file_size);
            document.getElementById('viewUploadDate').textContent = formatDate(material.created_at);
            document.getElementById('viewDownloads').textContent = material.download_count || 0;
            document.getElementById('viewFileName').textContent = material.file_path ? material.file_path.split('/').pop() : 'No file';

            // Set file icon
            const fileIcon = document.getElementById('viewFileIcon');
            fileIcon.className = getFileIconClass(material.file_type) + ' fa-3x mb-3';

            // Populate categories
            const categoriesContainer = document.getElementById('viewCategories');
            if (material.categories && material.categories.length > 0) {
                categoriesContainer.innerHTML = material.categories.map(cat => `
                    <span class="badge bg-light text-dark me-1">${cat.replace('_', ' ')}</span>
                `).join('');
            } else {
                categoriesContainer.innerHTML = '<span class="text-muted">No categories</span>';
            }
        }

        // Edit material
        async function editMaterial(materialId) {
            try {
                const response = await fetch(`${MATERIALS_API}?id=${materialId}`);
                const data = await response.json();

                if (data.success) {
                    const material = data.data;
                    populateEditForm(material);

                    const modal = new bootstrap.Modal(document.getElementById('editMaterialModal'));
                    modal.show();
                } else {
                    throw new Error(data.error || 'Failed to load material data');
                }
            } catch (error) {
                console.error('Error loading material:', error);
                showError('Failed to load material data');
            }
        }

        function populateEditForm(material) {
            document.getElementById('editMaterialId').value = material.id;
            document.getElementById('editTitle').value = material.title;
            document.getElementById('editDescription').value = material.description || '';
            document.getElementById('editFileType').textContent = material.file_type.toUpperCase();

            // Current file info
            document.getElementById('currentFileName').textContent = material.file_path ? material.file_path.split('/').pop() : 'No file uploaded';
            document.getElementById('currentFileInfo').textContent = `${formatFileSize(material.file_size)} • Uploaded ${formatDate(material.created_at)}`;

            // Populate categories
            const categoriesContainer = document.getElementById('editCategoriesContainer');
            const categories = ['crop_management', 'pest_control', 'soil_health', 'irrigation', 'organic_farming', 'season_planning'];

            categoriesContainer.innerHTML = categories.map(cat => `
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" value="${cat}" 
                               ${material.categories && material.categories.includes(cat) ? 'checked' : ''}>
                        <label class="form-check-label">${cat.replace('_', ' ')}</label>
                    </div>
                </div>
            `).join('');
        }

        // Update material form submission
        document.getElementById('editMaterialForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();
            await updateMaterial();
        });


        // Update material
        async function updateMaterial() {
            try {
                const form = document.getElementById('editMaterialForm');
                const formData = new FormData(form);
                const materialId = document.getElementById('editMaterialId').value;

                // Get categories
                const categories = [];
                document.querySelectorAll('#editCategoriesContainer input[type="checkbox"]:checked').forEach(checkbox => {
                    categories.push(checkbox.value);
                });

                const updateBtn = document.getElementById('updateMaterialBtn');
                updateBtn.disabled = true;
                updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';

                // Create FormData for the request
                const requestData = new FormData();
                requestData.append('material_id', materialId);
                requestData.append('title', document.getElementById('editTitle').value);
                requestData.append('description', document.getElementById('editDescription').value);

                // Add categories
                categories.forEach(category => {
                    requestData.append('categories[]', category);
                });

                // Check if a new file was selected
                const fileInput = document.getElementById('editFileInput');
                if (fileInput.files[0]) {
                    requestData.append('new_file', fileInput.files[0]);
                }

                const response = await fetch(MATERIALS_API, {
                    method: 'POST',
                    body: requestData
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Material updated successfully',
                        timer: 2000
                    });

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editMaterialModal'));
                    modal.hide();

                    // Reload materials list
                    loadMaterials(currentPage, currentFilters);
                } else {
                    throw new Error(result.error || 'Failed to update material');
                }
            } catch (error) {
                console.error('Error updating material:', error);
                showError('Failed to update material: ' + error.message);
            } finally {
                const updateBtn = document.getElementById('updateMaterialBtn');
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Material';
            }
        }

        // Handle update response
        function handleUpdateResponse(result) {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Material updated successfully',
                    timer: 2000
                });

                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editMaterialModal'));
                modal.hide();

                // Reload materials list
                loadMaterials(currentPage, currentFilters);
            } else {
                throw new Error(result.error || 'Failed to update material');
            }
        }

        // Delete material
        async function deleteMaterial(materialId) {
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
                    const response = await fetch(MATERIALS_API, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: materialId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Material has been deleted',
                            timer: 2000
                        });

                        // Reload materials list
                        loadMaterials(currentPage, currentFilters);
                    } else {
                        throw new Error(data.error || 'Failed to delete material');
                    }
                } catch (error) {
                    console.error('Error deleting material:', error);
                    showError('Failed to delete material');
                }
            }
        }

        // Download material - Using direct download endpoint
async function downloadMaterial(materialId = null) {
    const id = materialId || currentViewMaterialId;
    if (!id) return;

    try {
        // Show loading state
        Swal.fire({
            title: 'Preparing download...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Use direct download endpoint
        const downloadUrl = `function/download_file.php?id=${id}`;
        
        // Create download link
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.target = '_blank';
        link.style.display = 'none';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        Swal.close();

        Swal.fire({
            icon: 'success',
            title: 'Download Started!',
            text: 'Your download should begin shortly',
            timer: 2000
        });

        // Reload to update download count in UI
        setTimeout(() => {
            loadMaterials(currentPage, currentFilters);
        }, 1000);

    } catch (error) {
        console.error('Error downloading material:', error);
        Swal.fire({
            icon: 'error',
            title: 'Download Failed',
            text: 'Could not download the material. Please try again.'
        });
    }
}
        // Helper function to get file extension
        function getFileExtension(fileType) {
            switch (fileType) {
                case 'pdf':
                    return 'pdf';
                case 'video':
                    return 'mp4';
                case 'article':
                    return 'txt';
                default:
                    return 'file';
            }
        }

        // Utility functions
        function showLoading() {
            document.getElementById('materialsGrid').innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading materials...</span>
                    </div>
                    <div class="mt-2">Loading materials...</div>
                </div>
            `;
        }

        function hideLoading() {
            // Loading state is handled in displayMaterials
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

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function getTypeBadgeClass(type) {
            switch (type) {
                case 'pdf':
                    return 'bg-danger';
                case 'video':
                    return 'bg-primary';
                case 'article':
                    return 'bg-success';
                default:
                    return 'bg-secondary';
            }
        }

        function getFileIconClass(type) {
            switch (type) {
                case 'pdf':
                    return 'fas fa-file-pdf text-danger';
                case 'video':
                    return 'fas fa-file-video text-primary';
                case 'article':
                    return 'fas fa-file-alt text-success';
                default:
                    return 'fas fa-file text-secondary';
            }
        }

        function editCurrentMaterial() {
            if (currentViewMaterialId) {
                const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewMaterialModal'));
                viewModal.hide();

                setTimeout(() => {
                    editMaterial(currentViewMaterialId);
                }, 500);
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadMaterials();
            setupFilters();
        });
    </script>
    <script src="javascript.js"></script>
    <style>
        .material-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e9ecef;
        }

        .material-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .upload-area:hover {
            border-color: #0d6efd;
            background: #e7f1ff;
        }

        .upload-area.dragover {
            border-color: #0d6efd;
            background: #e7f1ff;
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