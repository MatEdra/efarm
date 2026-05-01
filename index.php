<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Login - Smart Farming Philippines</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
            --glass: rgba(255, 255, 255, 0.1);
            --shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
            overflow-x: hidden;
        }

        /* Animated Background */
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
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
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

        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            position: relative;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .login-header {
            background: var(--gradient);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }


        .login-body {
            padding: 2.5rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 15px 20px;
            border: 2px solid #e8f5e9;
            background: #f8fdf8;
            transition: all 0.3s ease;
            font-size: 1rem;
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
        }

        .btn-primary {
            background: var(--gradient);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(46, 125, 50, 0.4);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;

            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
        }

        .feature-icon {
            font-size: 3rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stats-counter {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .success-animation {
            color: var(--primary);
        }

        .error-animation {
            color: #d32f2f;
        }

        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100px;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        @keyframes x-mark {
            0% {
                stroke-dashoffset: 100px;
            }

            100% {
                stroke-dashoffset: 0;
            }
        }

        .checkmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: var(--primary);
            stroke-miterlimit: 10;
            margin: 0 auto;
            animation: checkmark 0.6s ease-in-out both;
        }

        .x-mark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 2;
            stroke: #d32f2f;
            stroke-miterlimit: 10;
            margin: 0 auto;
            animation: x-mark 0.6s ease-in-out both;
        }

        footer {
            background: rgba(27, 94, 32, 0.9);
            backdrop-filter: blur(10px);
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .stats-counter {
                flex-direction: column;
                gap: 1rem;
            }

            .brand-header .col-md-4 {
                text-align: left !important;
                margin-top: 1rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
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
    <header class="brand-header animate__animated animate__fadeInDown">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2"><i class="fas fa-seedling brand-logo"></i> Smart Farming Philippines</h1>
                    <p class="brand-tagline mb-0">Empowering Filipino Farmers with Modern Agricultural Technology</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div id="currentDateTime" class="h5 text-dark fw-bold"></div>
                    <small class="text-muted">Real-time Farming Solutions</small>
                </div>
            </div>
        </div>
    </header>

    <!-- Login Section -->
    <section class="login-container">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-5 animate__animated animate__fadeInLeft">
                    <div class="login-card">
                        <div class="login-header">
                            <h2 class="mb-2"><i class="fas fa-tractor me-2"></i>Farmer Login</h2>
                            <p class="mb-0">Access your farming dashboard</p>
                        </div>
                        <div class="login-body">
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label for="loginPhone" class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control" id="loginPhone" placeholder="09171234567" required>
                                    </div>
                                    <div class="form-text">Use your registered phone number</div>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
                                    </div>
                                </div>
                                <!-- <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <a href="#" class="text-decoration-none" style="color: var(--primary);">Forgot password?</a>
                                </div> -->
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Access Farmer Dashboard
                                </button>
                                <div class="text-center">
                                    <p class="mb-2">New to Smart Farming? <a href="register.php" class="fw-bold text-decoration-none" style="color: var(--primary);">Create Farmer Account</a></p>
                                    <p class="mb-0">
                                        <small>
                                            Are you an admin?
                                            <a href="admin-login.php" class="text-decoration-none fw-semibold" style="color: var(--secondary);">
                                                <i class="fas fa-user-shield me-1"></i>Admin Login
                                            </a>
                                        </small>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 mt-5 mt-lg-0  animate__fadeInRight">
                    <!-- Features Grid -->
                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <h4>Smart Planting Guide</h4>
                            <p>Get personalized crop recommendations based on seasons and weather conditions for optimal yield.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-cloud-sun"></i>
                            </div>
                            <h4>Weather Intelligence</h4>
                            <p>Access accurate 7-day weather forecasts and farming alerts for better planning.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h4>Expert Resources</h4>
                            <p>Learn from agricultural experts with comprehensive guides and best practices.</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4>Farm Analytics</h4>
                            <p>Track your farm performance and get insights to improve productivity.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="animate__animated animate__fadeInUp">
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
                    minute: '2-digit',
                    second: '2-digit'
                };
                document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-PH', options);
            }

            updateDateTime();
            setInterval(updateDateTime, 1000);

            // Login form submission
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const phone_number = document.getElementById('loginPhone').value;
                const password = document.getElementById('loginPassword').value;

                // Show loading state
                const submitBtn = document.querySelector('#loginForm button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Authenticating...';
                submitBtn.disabled = true;

                // AJAX request to farmer login function
                fetch('function/farmer-login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            phone_number,
                            password
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessAlert('Login Successful!', `Welcome back, ${data.user.name}! Redirecting to dashboard...`);

                            // Store user data in sessionStorage
                            sessionStorage.setItem('user', JSON.stringify(data.user));

                            // Redirect to user dashboard
                            setTimeout(() => {
                                window.location.href = 'user/index.php';
                            }, 2000);
                        } else {
                            showErrorAlert('Login Failed', data.message || 'Invalid phone number or password. Please try again.');
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        showErrorAlert('Connection Error', 'Unable to connect to server. Please check your internet connection.');
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
                    backdrop: 'rgba(46, 125, 50, 0.4)',
                    didOpen: () => {
                        // Add custom animation
                        const successIcon = document.createElement('div');
                        successIcon.innerHTML = `
                            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                            </svg>
                        `;
                        Swal.getHtmlContainer().prepend(successIcon);
                    }
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
                    backdrop: 'rgba(211, 47, 47, 0.4)',
                    didOpen: () => {
                        // Add custom animation
                        const errorIcon = document.createElement('div');
                        errorIcon.innerHTML = `
                            <svg class="x-mark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                <circle class="x-mark__circle" cx="26" cy="26" r="25" fill="none"/>
                                <path class="x-mark__path" fill="none" d="M16 16 36 36 M36 16 16 36"/>
                            </svg>
                        `;
                        Swal.getHtmlContainer().prepend(errorIcon);
                    }
                });
            }

            // Add floating animation to feature cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.feature-card').forEach(card => {
                observer.observe(card);
            });
        });
    </script>
</body>

</html>