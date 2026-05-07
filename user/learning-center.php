<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Center - Smart Farming Philippines</title>
    <?php include __DIR__ . '/../pwa_head.php'; ?>
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
        .material-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
        }

        .material-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .material-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .category-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .search-box {
            max-width: 400px;
        }

        .filter-btn.active {
            background-color: #198754;
            color: white;
        }

        .progress {
            height: 6px;
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
                <p class="mt-2">Loading learning materials...</p>
            </div>

            <!-- Learning Center Content -->
            <div id="learningContent" style="display: none;">
                <!-- Page Header -->
                <div class="container-fluid py-4">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h1 class="h3 mb-1">Learning Center</h1>
                                    <p class="text-muted mb-0">Access farming guides, tutorials, and educational materials</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <div class="input-group search-box">
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search materials...">
                                        <button class="btn btn-outline-primary" type="button" id="searchBtn">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Filter -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Filter by Category</h6>
                                    <div class="d-flex flex-wrap gap-2" id="categoryFilters">
                                        <button class="btn btn-outline-primary filter-btn active" data-category="all">
                                            All Materials
                                        </button>
                                        <button class="btn btn-outline-primary filter-btn" data-category="crop_management">
                                            <i class="fas fa-seedling me-1"></i>Crop Management
                                        </button>
                                        <button class="btn btn-outline-primary filter-btn" data-category="soil_health">
                                            <i class="fas fa-mountain me-1"></i>Soil Health
                                        </button>
                                        <button class="btn btn-outline-primary filter-btn" data-category="pest_control">
                                            <i class="fas fa-bug me-1"></i>Pest Control
                                        </button>
                                        <button class="btn btn-outline-primary filter-btn" data-category="irrigation">
                                            <i class="fas fa-tint me-1"></i>Irrigation
                                        </button>
                                        <button class="btn btn-outline-primary filter-btn" data-category="harvesting">
                                            <i class="fas fa-harvest me-1"></i>Harvesting
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Materials</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalMaterials">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                PDF Guides</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pdfCount">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-pdf fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Videos</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="videoCount">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-video fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning h-100">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Articles</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="articleCount">0</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Learning Materials Grid -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Learning Materials</h5>
                                </div>
                                <div class="card-body">
                                    <div id="materialsGrid" class="row">
                                        <!-- Materials will be loaded here by JavaScript -->
                                    </div>
                                    <div id="noMaterials" class="text-center py-5" style="display: none;">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No learning materials found</h5>
                                        <p class="text-muted">Try adjusting your search or filter criteria</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Material Details Modal -->
    <div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="materialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="materialModalLabel">Material Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="materialModalContent">
                    <!-- Content will be loaded here by JavaScript -->
                </div>
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
    let allMaterials = [];
    let currentFilter = 'all';
    let currentSearch = '';

    // Load learning materials when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, starting to load materials...');
        loadLearningMaterials();
        setupEventListeners();
    });

    function setupEventListeners() {
        // Search functionality
        document.getElementById('searchBtn').addEventListener('click', performSearch);
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.category;
                console.log('Filter changed to:', currentFilter);
                filterMaterials();
            });
        });
    }

    function performSearch() {
        currentSearch = document.getElementById('searchInput').value.toLowerCase();
        console.log('Search performed:', currentSearch);
        filterMaterials();
    }

    // Function to load learning materials using fetch
    async function loadLearningMaterials() {
        try {
            console.log('Loading learning materials from API...');
            showLoading();
            const response = await fetch('function/get_learning_materials.php');
            
            console.log('API Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('API Response data:', data);

            if (data.success) {
                allMaterials = data.data;
                console.log('Materials loaded:', allMaterials.length);
                
                // DEBUG: Log all material IDs and titles
                console.log('All material IDs:', allMaterials.map(m => ({id: m.id, title: m.title})));
                
                updateStatistics();
                filterMaterials();
                hideLoading();
            } else {
                throw new Error(data.message || 'Failed to load learning materials');
            }
        } catch (error) {
            console.error('Error loading materials:', error);
            showError('Failed to load learning materials: ' + error.message);
        }
    }

    function updateStatistics() {
        const total = allMaterials.length;
        const pdfCount = allMaterials.filter(m => m.file_type === 'pdf').length;
        const videoCount = allMaterials.filter(m => m.file_type === 'video').length;
        const articleCount = allMaterials.filter(m => m.file_type === 'article').length;

        console.log('Statistics - Total:', total, 'PDF:', pdfCount, 'Video:', videoCount, 'Article:', articleCount);

        document.getElementById('totalMaterials').textContent = total;
        document.getElementById('pdfCount').textContent = pdfCount;
        document.getElementById('videoCount').textContent = videoCount;
        document.getElementById('articleCount').textContent = articleCount;
    }

    function filterMaterials() {
        let filteredMaterials = allMaterials;

        // Apply search filter
        if (currentSearch) {
            filteredMaterials = filteredMaterials.filter(material =>
                material.title.toLowerCase().includes(currentSearch) ||
                material.description.toLowerCase().includes(currentSearch)
            );
        }

        // Apply category filter
        if (currentFilter !== 'all') {
            filteredMaterials = filteredMaterials.filter(material =>
                material.categories.includes(currentFilter)
            );
        }

        console.log('Filtered materials:', filteredMaterials.length);
        console.log('Filtered material IDs:', filteredMaterials.map(m => m.id));
        displayMaterials(filteredMaterials);
    }

    function displayMaterials(materials) {
        const materialsGrid = document.getElementById('materialsGrid');
        const noMaterials = document.getElementById('noMaterials');

        console.log('Displaying materials:', materials.length);
        console.log('Materials to display IDs:', materials.map(m => m.id));

        if (materials.length === 0) {
            materialsGrid.innerHTML = '';
            noMaterials.style.display = 'block';
            console.log('No materials to display');
            return;
        }

        noMaterials.style.display = 'none';

        materialsGrid.innerHTML = materials.map(material => {
            console.log('Rendering material:', material.id, material.title);
            return `
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card material-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="material-icon text-${getFileTypeColor(material.file_type)}">
                                <i class="fas fa-${getFileTypeIcon(material.file_type)}"></i>
                            </div>
                            <div class="d-flex gap-1">
                                ${material.categories && material.categories.map(category => `
                                    <span class="badge bg-${getCategoryColor(category)}">${formatCategoryName(category)}</span>
                                `).join('')}
                            </div>
                        </div>
                        <h5 class="card-title">${escapeHtml(material.title)}</h5>
                        <p class="card-text text-muted">${escapeHtml(material.description ? material.description.substring(0, 100) : 'No description')}${material.description && material.description.length > 100 ? '...' : ''}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-${getFileTypeIcon(material.file_type)} me-1"></i>
                                ${material.file_type.toUpperCase()} • ${material.file_size || 'N/A'}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-download me-1"></i>
                                ${material.download_count || 0}
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                ${new Date(material.created_at).toLocaleDateString()}
                            </small>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewMaterial(${material.id})">
                                    <i class="fas fa-eye me-1"></i>View
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="downloadMaterial(${material.id})">
                                    <i class="fas fa-download me-1"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
        }).join('');
    }

    function getFileTypeIcon(fileType) {
        const icons = {
            'pdf': 'file-pdf',
            'video': 'video',
            'article': 'newspaper'
        };
        return icons[fileType] || 'file';
    }

    function getFileTypeColor(fileType) {
        const colors = {
            'pdf': 'danger',
            'video': 'primary',
            'article': 'success'
        };
        return colors[fileType] || 'secondary';
    }

    function getCategoryColor(category) {
        const colors = {
            'crop_management': 'success',
            'soil_health': 'warning',
            'pest_control': 'danger',
            'irrigation': 'info',
            'harvesting': 'primary'
        };
        return colors[category] || 'secondary';
    }

    function formatCategoryName(category) {
        return category.split('_').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    function viewMaterial(materialId) {
        console.log('=== VIEW MATERIAL DEBUG ===');
        console.log('View material clicked:', materialId);
        console.log('All materials array:', allMaterials);
        console.log('Looking for material ID:', materialId);
        
        const material = allMaterials.find(m => {
            console.log('Checking material:', m.id, m.title);
            return m.id == materialId;
        });
        
        if (!material) {
            console.error('Material not found in array. Available IDs:', allMaterials.map(m => m.id));
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Material with ID ${materialId} not found. Available IDs: ${allMaterials.map(m => m.id).join(', ')}`
            });
            return;
        }

        console.log('Found material for view:', material);

        const modalContent = document.getElementById('materialModalContent');
        modalContent.innerHTML = `
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4>${escapeHtml(material.title)}</h4>
                            <div class="d-flex gap-2 mb-2">
                                ${material.categories && material.categories.map(category => `
                                    <span class="badge bg-${getCategoryColor(category)}">${formatCategoryName(category)}</span>
                                `).join('')}
                            </div>
                        </div>
                        <div class="text-${getFileTypeColor(material.file_type)}">
                            <i class="fas fa-${getFileTypeIcon(material.file_type)} fa-2x"></i>
                        </div>
                    </div>
                    
                    <p class="text-muted mb-4">${escapeHtml(material.description || 'No description available')}</p>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>File Type:</strong>
                            <p class="mb-1">${material.file_type.toUpperCase()}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>File Size:</strong>
                            <p class="mb-1">${material.file_size || 'N/A'}</p>
                        </div>
                        ${material.duration ? `
                            <div class="col-md-6">
                                <strong>Duration:</strong>
                                <p class="mb-1">${material.duration}</p>
                            </div>
                        ` : ''}
                        <div class="col-md-6">
                            <strong>Downloads:</strong>
                            <p class="mb-1">${material.download_count || 0}</p>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="downloadMaterial(${material.id})">
                            <i class="fas fa-download me-2"></i>Download Material
                        </button>
                    </div>
                </div>
            </div>
        `;

        const modal = new bootstrap.Modal(document.getElementById('materialModal'));
        modal.show();
        console.log('Modal shown successfully');
    }

    async function downloadMaterial(materialId) {
        try {
            console.log('Download material clicked:', materialId);
            
            // Show loading state
            Swal.fire({
                title: 'Preparing download...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Use the download endpoint that handles both counting and file serving
            const downloadUrl = `function/download_material.php?id=${materialId}`;
            console.log('Download URL:', downloadUrl);

            // Create a hidden link and trigger download
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.target = '_blank';
            link.style.display = 'none';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Close the loading dialog after a short delay
            setTimeout(() => {
                Swal.close();

                Swal.fire({
                    icon: 'success',
                    title: 'Download Started!',
                    text: 'Your download should begin shortly.',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reload materials to get updated counts
                setTimeout(() => {
                    loadLearningMaterials();
                }, 1000);

            }, 1000);

        } catch (error) {
            console.error('Error downloading material:', error);
            Swal.fire({
                icon: 'error',
                title: 'Download Error',
                text: error.message || 'Failed to download material'
            });
        }
    }

    function showLoading() {
        document.getElementById('loadingSpinner').style.display = 'block';
        document.getElementById('learningContent').style.display = 'none';
    }

    function hideLoading() {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('learningContent').style.display = 'block';
    }

    function showError(message) {
        document.getElementById('loadingSpinner').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
            <button class="btn btn-primary mt-2" onclick="loadLearningMaterials()">
                <i class="fas fa-redo me-2"></i>Retry
            </button>
        `;
    }

    // Utility function to escape HTML
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
</body>

</html>