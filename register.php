<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Farming Philippines</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary: #2e7d32;
            --primary-light: #4caf50;
            --primary-dark: #1b5e20;
            --secondary: #ff9800;
            --accent: #8bc34a;
            --light: #f1f8e9;
            --dark: #1b5e20;
            --gradient: linear-gradient(135deg, #2e7d32 0%, #4caf50 50%, #8bc34a 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--gradient);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        .floating-icon {
            position: absolute;
            font-size: 2rem;
            color: var(--primary-light);
            opacity: 0.6;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        .floating-icon:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-icon:nth-child(2) {
            top: 20%;
            right: 15%;
            animation-delay: 1s;
        }

        .floating-icon:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 2s;
        }

        .floating-icon:nth-child(4) {
            bottom: 15%;
            right: 25%;
            animation-delay: 3s;
        }

        .floating-icon:nth-child(5) {
            top: 40%;
            left: 30%;
            animation-delay: 4s;
        }

        .brand-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            position: relative;
            z-index: 100;
        }

        .brand-logo {
            font-size: 2.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-tagline {
            font-size: 1.1rem;
            color: var(--primary-dark);
            font-weight: 500;
        }

        .register-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 0;
            position: relative;
        }

        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 0 !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .register-header {
            background: var(--gradient);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .register-body {
            padding: 3rem;
            min-height: 600px;
        }

        .form-control {
            border-radius: 12px;
            padding: 16px 20px;
            border: 2px solid #e8f5e9;
            background: #f8fdf8;
            transition: all 0.3s ease;
            font-size: 1rem;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.15);
            transform: translateY(-2px);
        }

        .input-group-text {
            background: #e8f5e9;
            border: 2px solid #e8f5e9;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: var(--primary);
            padding: 16px 20px;
        }

        .btn-primary {
            background: var(--gradient);
            border: none;
            border-radius: 12px;
            padding: 16px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(46, 125, 50, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 12px;
            padding: 14px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .progress-container {
            background: #f8fdf8;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2.5rem;
            border: 2px solid #e8f5e9;
        }

        .progress {
            height: 12px;
            border-radius: 6px;
            background: #e8f5e9;
        }

        .progress-bar {
            background: var(--gradient);
            border-radius: 6px;
            transition: width 0.5s ease;
        }

        .step-indicators {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .step-indicator {
            text-align: center;
            flex: 1;
            font-weight: 600;
            color: var(--primary-dark);
            position: relative;
        }

        .step-indicator.active {
            color: var(--primary);
        }

        .form-step {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            color: var(--primary-dark);
            border-bottom: 2px solid var(--accent);
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }

        .crops-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .crop-option {
            border: 2px solid #e8f5e9;
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fdf8;
        }

        .crop-option:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .crop-option.selected {
            border-color: var(--primary);
            background: var(--light);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.1);
        }

        .summary-card {
            background: #f8fdf8;
            border: 2px solid #e8f5e9;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e8f5e9;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .form-check {
            padding: 1rem 0;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-right: 0.75rem;
        }

        .form-check-label {
            font-size: 1rem;
        }

        footer {
            background: rgba(27, 94, 32, 0.95);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: auto;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-light);
            transform: translateY(-3px);
        }

        /* Gender specific styles */
        .gender-option {
            border: 2px solid #e8f5e9;
            border-radius: 12px;
            padding: 1.5rem 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8fdf8;
        }

        .gender-option:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .gender-option.selected {
            border-color: var(--primary);
            background: var(--light);
            box-shadow: 0 5px 15px rgba(46, 125, 50, 0.1);
        }

        .gender-option.male.selected {
            border-color: #007bff;
            background: #e3f2fd;
        }

        .gender-option.female.selected {
            border-color: #e83e8c;
            background: #fce4ec;
        }

        .gender-option.other.selected {
            border-color: #6f42c1;
            background: #f3e8fd;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .register-container {
                padding: 0.5rem;
            }

            .register-body {
                padding: 2rem 1.5rem;
            }

            .register-header {
                padding: 2rem 1.5rem;
            }

            .crops-grid {
                grid-template-columns: 1fr;
            }

            .progress-container {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .register-body {
                padding: 1.5rem 1rem;
            }

            .btn-primary,
            .btn-outline-primary {
                padding: 14px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="background-animation">
        <i class="fas fa-seedling floating-icon"></i>
        <i class="fas fa-tractor floating-icon"></i>
        <i class="fas fa-cloud-sun floating-icon"></i>
        <i class="fas fa-leaf floating-icon"></i>
        <i class="fas fa-tint floating-icon"></i>
    </div>

    <!-- Header -->
    <header class="brand-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2"><i class="fas fa-seedling brand-logo"></i> Smart Farming Philippines</h1>
                    <p class="brand-tagline mb-0">Join our community of Filipino farmers</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div id="currentDateTime" class="h5 text-dark fw-bold"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Registration Section -->
    <section class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="register-card">
                    <div class="register-header">
                        <h2 class="mb-3"><i class="fas fa-user-plus me-3"></i>Create Your Farmer Account</h2>
                        <p class="mb-0 fs-5">Start your journey with modern farming technology</p>
                    </div>
                    <div class="register-body">
                        <!-- Progress Bar -->
                        <div class="progress-container">
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="step-indicators">
                                <div class="step-indicator active">Account Details</div>
                                <div class="step-indicator">Farm Information</div>
                                <div class="step-indicator">Confirmation</div>
                            </div>
                        </div>

                        <form id="registerForm">
                            <!-- Step 1: Account Details -->
                            <div class="form-step active" id="step1">
                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-user-circle me-2"></i>Personal Information</h3>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="firstName" class="form-label fw-semibold fs-6">First Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="firstName" placeholder="Enter your first name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="lastName" class="form-label fw-semibold fs-6">Last Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="lastName" placeholder="Enter your last name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-venus-mars me-2"></i>Gender</h3>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="gender-option male" data-gender="Male">
                                                <i class="fas fa-mars fa-2x mb-2 text-primary"></i>
                                                <div class="fw-semibold">Male</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="gender-option female" data-gender="Female">
                                                <i class="fas fa-venus fa-2x mb-2 text-pink"></i>
                                                <div class="fw-semibold">Female</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="gender-option other" data-gender="Other">
                                                <i class="fas fa-transgender fa-2x mb-2 text-info"></i>
                                                <div class="fw-semibold">Other</div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="gender" name="gender" required>
                                </div>

                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-phone me-2"></i>Contact Information</h3>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="phoneNumber" class="form-label fw-semibold fs-6">Phone Number</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                <input type="tel" class="form-control" id="phoneNumber" placeholder="09171234567" required>
                                            </div>
                                            <div class="form-text">We'll use this for login and important updates</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="registerEmail" class="form-label fw-semibold fs-6">Email Address</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" id="registerEmail" placeholder="farmer@example.com" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-lock me-2"></i>Security</h3>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="registerPassword" class="form-label fw-semibold fs-6">Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" id="registerPassword" placeholder="Create a secure password" required>
                                            </div>
                                            <div class="form-text">Minimum 8 characters with letters and numbers</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="confirmPassword" class="form-label fw-semibold fs-6">Confirm Password</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                <input type="password" class="form-control" id="confirmPassword" placeholder="Re-enter your password" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-5">
                                    <a href="index.php" class="btn btn-outline-primary px-4">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                                    </a>
                                    <button type="button" class="btn btn-primary px-5" id="nextStep1">
                                        Continue <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Farm Information -->
                            <div class="form-step" id="step2">
                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-tractor me-2"></i>Farm Details</h3>
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label for="farmName" class="form-label fw-semibold fs-6">Farm Name</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-tractor"></i></span>
                                                <input type="text" class="form-control" id="farmName" placeholder="e.g., Dela Cruz Family Farm">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-map-marker-alt me-2"></i>Location & Size</h3>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="farmLocation" class="form-label fw-semibold fs-6">Farm Region</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                                <select class="form-control" id="farmLocation" required>
                                                    <option value="">Select your region</option>
                                                    <option value="NCR">National Capital Region</option>
                                                    <option value="CAR">Cordillera Administrative Region</option>
                                                    <option value="Ilocos Region">Ilocos Region</option>
                                                    <option value="Cagayan Valley">Cagayan Valley</option>
                                                    <option value="Central Luzon">Central Luzon</option>
                                                    <option value="CALABARZON">CALABARZON</option>
                                                    <option value="MIMAROPA">MIMAROPA</option>
                                                    <option value="Bicol Region">Bicol Region</option>
                                                    <option value="Western Visayas">Western Visayas</option>
                                                    <option value="Central Visayas">Central Visayas</option>
                                                    <option value="Eastern Visayas">Eastern Visayas</option>
                                                    <option value="Zamboanga Peninsula">Zamboanga Peninsula</option>
                                                    <option value="Northern Mindanao">Northern Mindanao</option>
                                                    <option value="Davao Region">Davao Region</option>
                                                    <option value="SOCCSKSARGEN">SOCCSKSARGEN</option>
                                                    <option value="Caraga">Caraga</option>
                                                    <option value="BARMM">BARMM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="farmSize" class="form-label fw-semibold fs-6">Farm Size (hectares)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-ruler-combined"></i></span>
                                                <input type="number" class="form-control" id="farmSize" placeholder="e.g., 2.5" min="0.1" step="0.1">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-seedling me-2"></i>Primary Crops</h3>
                                    <p class="text-muted mb-3">Select the main crops you grow (select all that apply)</p>
                                    <div class="crops-grid">
                                        <div class="crop-option" data-crop="rice">
                                            <i class="fas fa-leaf fa-2x mb-2 text-primary"></i>
                                            <div class="fw-semibold">Rice</div>
                                        </div>
                                        <div class="crop-option" data-crop="corn">
                                            <i class="fas fa-seedling fa-2x mb-2 text-primary"></i>
                                            <div class="fw-semibold">Corn</div>
                                        </div>
                                        <div class="crop-option" data-crop="vegetables">
                                            <i class="fas fa-carrot fa-2x mb-2 text-primary"></i>
                                            <div class="fw-semibold">Vegetables</div>
                                        </div>
                                        <div class="crop-option" data-crop="fruits">
                                            <i class="fas fa-apple-alt fa-2x mb-2 text-primary"></i>
                                            <div class="fw-semibold">Fruits</div>
                                        </div>
                                        <div class="crop-option" data-crop="root_crops">
                                            <i class="fas fa-potato fa-2x mb-2 text-primary"></i>
                                            <div class="fw-semibold">Root Crops</div>
                                        </div>
                                        <div class="crop-option" data-crop="others">
                                            <i class="fas fa-ellipsis-h fa-2x mb-2 text-primary"></i>
                                            <div class="fw-semibold">Other Crops</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-5">
                                    <button type="button" class="btn btn-outline-primary px-4" id="prevStep2">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </button>
                                    <button type="button" class="btn btn-primary px-5" id="nextStep2">
                                        Continue <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Confirmation -->
                            <div class="form-step" id="step3">
                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-clipboard-check me-2"></i>Review Your Information</h3>
                                    <div class="summary-card">
                                        <h5 class="mb-3 text-primary">Account Summary</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="summary-item">
                                                    <strong>Full Name:</strong>
                                                    <span id="summaryName">Juan Dela Cruz</span>
                                                </div>
                                                <div class="summary-item">
                                                    <strong>Gender:</strong>
                                                    <span id="summaryGender">Male</span>
                                                </div>
                                                <div class="summary-item">
                                                    <strong>Phone:</strong>
                                                    <span id="summaryPhone">09171234567</span>
                                                </div>
                                                <div class="summary-item">
                                                    <strong>Email:</strong>
                                                    <span id="summaryEmail">farmer@example.com</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="summary-item">
                                                    <strong>Farm Name:</strong>
                                                    <span id="summaryFarm">Dela Cruz Family Farm</span>
                                                </div>
                                                <div class="summary-item">
                                                    <strong>Location:</strong>
                                                    <span id="summaryLocation">Central Luzon</span>
                                                </div>
                                                <div class="summary-item">
                                                    <strong>Farm Size:</strong>
                                                    <span id="summarySize">2.5 hectares</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="summary-item mt-2">
                                            <strong>Primary Crops:</strong>
                                            <span id="summaryCrops">Rice, Vegetables</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-section">
                                    <h3 class="form-section-title"><i class="fas fa-file-signature me-2"></i>Agreements</h3>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-primary">Terms and Conditions</a> and <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal" class="text-primary">Privacy Policy</a>
                                        </label>
                                    </div>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="newsletter">
                                        <label class="form-check-label" for="newsletter">
                                            I would like to receive farming updates, weather alerts, and market information
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-5">
                                    <button type="button" class="btn btn-outline-primary px-4" id="prevStep3">
                                        <i class="fas fa-arrow-left me-2"></i>Back
                                    </button>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start text-center mb-3 mb-md-0">
                    <h5 class="mb-2"><i class="fas fa-seedling me-2"></i> Smart Farming PH</h5>
                    <p class="mb-0">Modern Agricultural Solutions for Filipino Farmers</p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <p class="mb-2">Contact us: info@smartfarming.ph | (02) 1234-5678</p>
                    <div class="social-links">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
            <p class="mb-0">&copy; 2025 Smart Farming Philippines. All rights reserved.</p>
        </div>
    </footer>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Welcome to Smart Farming Philippines. By registering for an account, you agree to the following terms and conditions:</p>
                    <ol>
                        <li>You are a legitimate farmer or agricultural stakeholder in the Philippines.</li>
                        <li>You will provide accurate information about your farming activities.</li>
                        <li>You will use the platform for agricultural purposes only.</li>
                        <li>You agree to receive weather alerts and farming recommendations.</li>
                        <li>You will not share your account credentials with others.</li>
                    </ol>
                    <p>Smart Farming Philippines reserves the right to suspend accounts that violate these terms.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>At Smart Farming Philippines, we are committed to protecting your privacy and personal information.</p>
                    <h6>Information We Collect</h6>
                    <ul>
                        <li>Personal details (name, email, phone, location)</li>
                        <li>Farm information (size, crops, location)</li>
                        <li>Usage data and preferences</li>
                    </ul>
                    <h6>How We Use Your Information</h6>
                    <ul>
                        <li>To provide personalized farming recommendations</li>
                        <li>To send weather alerts and agricultural updates</li>
                        <li>To improve our services and platform</li>
                        <li>To connect you with agricultural experts and resources</li>
                    </ul>
                    <p>We do not sell your personal information to third parties.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update current date and time
            function updateDateTime() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-PH', options);
            }

            updateDateTime();
            setInterval(updateDateTime, 60000);

            // Multi-step form functionality
            let currentStep = 1;
            const totalSteps = 3;
            const progressBar = document.querySelector('.progress-bar');
            const selectedCrops = new Set();
            let selectedGender = '';

            // Update progress bar
            function updateProgressBar() {
                const progress = (currentStep / totalSteps) * 100;
                progressBar.style.width = `${progress}%`;
                progressBar.setAttribute('aria-valuenow', progress);

                // Update step indicators
                document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                    if (index + 1 <= currentStep) {
                        indicator.classList.add('active');
                    } else {
                        indicator.classList.remove('active');
                    }
                });
            }

            // Show specific step
            function showStep(step) {
                document.querySelectorAll('.form-step').forEach(formStep => {
                    formStep.classList.remove('active');
                });
                document.getElementById(`step${step}`).classList.add('active');
                currentStep = step;
                updateProgressBar();
            }

            // Gender selection functionality
            document.querySelectorAll('.gender-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all gender options
                    document.querySelectorAll('.gender-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked option
                    this.classList.add('selected');
                    
                    // Update hidden input
                    selectedGender = this.dataset.gender;
                    document.getElementById('gender').value = selectedGender;
                });
            });

            // Crop selection functionality
            document.querySelectorAll('.crop-option').forEach(option => {
                option.addEventListener('click', function() {
                    this.classList.toggle('selected');
                    const crop = this.dataset.crop;

                    if (this.classList.contains('selected')) {
                        selectedCrops.add(crop);
                    } else {
                        selectedCrops.delete(crop);
                    }
                });
            });

            // Step navigation
            document.getElementById('nextStep1').addEventListener('click', function() {
                const firstName = document.getElementById('firstName').value;
                const lastName = document.getElementById('lastName').value;
                const phoneNumber = document.getElementById('phoneNumber').value;
                const email = document.getElementById('registerEmail').value;
                const password = document.getElementById('registerPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (!firstName || !lastName || !phoneNumber || !email || !password || !confirmPassword) {
                    showErrorAlert('Missing Information', 'Please fill in all required fields.');
                    return;
                }

                if (!selectedGender) {
                    showErrorAlert('Gender Required', 'Please select your gender.');
                    return;
                }

                if (password !== confirmPassword) {
                    showErrorAlert('Password Mismatch', 'Your passwords do not match. Please try again.');
                    return;
                }

                if (password.length < 8) {
                    showErrorAlert('Weak Password', 'Password must be at least 8 characters long.');
                    return;
                }

                showStep(2);
            });

            document.getElementById('prevStep2').addEventListener('click', function() {
                showStep(1);
            });

            document.getElementById('nextStep2').addEventListener('click', function() {
                const farmLocation = document.getElementById('farmLocation').value;

                if (!farmLocation) {
                    showErrorAlert('Missing Information', 'Please select your farm location.');
                    return;
                }

                // Update summary
                document.getElementById('summaryName').textContent =
                    `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}`;
                document.getElementById('summaryGender').textContent = selectedGender;
                document.getElementById('summaryPhone').textContent =
                    document.getElementById('phoneNumber').value;
                document.getElementById('summaryEmail').textContent =
                    document.getElementById('registerEmail').value;
                document.getElementById('summaryFarm').textContent =
                    document.getElementById('farmName').value || 'Not specified';
                document.getElementById('summaryLocation').textContent =
                    document.getElementById('farmLocation').options[document.getElementById('farmLocation').selectedIndex].text;
                document.getElementById('summarySize').textContent =
                    document.getElementById('farmSize').value ? `${document.getElementById('farmSize').value} hectares` : 'Not specified';
                document.getElementById('summaryCrops').textContent =
                    Array.from(selectedCrops).map(crop => {
                        const cropNames = {
                            'rice': 'Rice',
                            'corn': 'Corn',
                            'vegetables': 'Vegetables',
                            'fruits': 'Fruits',
                            'root_crops': 'Root Crops',
                            'others': 'Other'
                        };
                        return cropNames[crop];
                    }).join(', ') || 'Not specified';

                showStep(3);
            });

            document.getElementById('prevStep3').addEventListener('click', function() {
                showStep(2);
            });

            // Form submission
            document.getElementById('registerForm').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!document.getElementById('agreeTerms').checked) {
                    showErrorAlert('Agreement Required', 'You must agree to the Terms and Conditions to continue.');
                    return;
                }

                // Show loading state
                const submitBtn = document.querySelector('#registerForm button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
                submitBtn.disabled = true;

                // Get form data
                const formData = {
                    firstName: document.getElementById('firstName').value,
                    lastName: document.getElementById('lastName').value,
                    gender: selectedGender,
                    phoneNumber: document.getElementById('phoneNumber').value,
                    email: document.getElementById('registerEmail').value,
                    password: document.getElementById('registerPassword').value,
                    farmName: document.getElementById('farmName').value,
                    farmLocation: document.getElementById('farmLocation').value,
                    farmSize: document.getElementById('farmSize').value,
                    crops: Array.from(selectedCrops),
                    newsletter: document.getElementById('newsletter').checked
                };

                // AJAX request to register function
                fetch('function/register.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessAlert('Registration Successful!', 'Your account has been created. Redirecting to login...');

                            // Redirect to login after delay
                            setTimeout(() => {
                                window.location.href = 'index.php';
                            }, 2000);
                        } else {
                            showErrorAlert('Registration Failed', data.message || 'An error occurred. Please try again.');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        showErrorAlert('Registration Failed', 'Unable to connect to server. Please try again.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });

            // Show success alert with animation
            function showSuccessAlert(title, text) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    background: 'rgba(255, 255, 255, 0.95)',
                    backdrop: 'rgba(46, 125, 50, 0.4)'
                });
            }

            // Show error alert with animation
            function showErrorAlert(title, text) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'error',
                    showConfirmButton: true,
                    confirmButtonColor: '#d32f2f',
                    background: 'rgba(255, 255, 255, 0.95)',
                    backdrop: 'rgba(255, 255, 255, 0.4)'
                });
            }
        });
    </script>

    <style>
        .text-pink {
            color: #e83e8c !important;
        }
    </style>
</body>

</html>