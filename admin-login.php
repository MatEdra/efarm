<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Smart Farming Philippines</title>
    <?php include __DIR__ . '/pwa_head.php'; ?>
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
            max-width: 100%;
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

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

        .admin-features {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            margin-top: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .admin-features h4 {
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list i {
            color: var(--primary);
            margin-right: 10px;
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

        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }

            .login-body {
                padding: 2rem 1.5rem;
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
    <header class="brand-header animate__animated animate__fadeInDown">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-2"><i class="fas fa-seedling brand-logo"></i> Smart Farming Philippines</h1>
                    <p class="brand-tagline mb-0">Administrative Portal - Farm Management System</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div id="currentDateTime" class="h5 text-dark fw-bold"></div>
                    <small class="text-muted">Secure Admin Access</small>
                </div>
            </div>
        </div>
    </header>

    <!-- Login Section -->
    <section class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 animate__animated animate__fadeIn">
                    <div class="login-card">
                        <div class="login-header">
                            <h2 class="mb-2"><i class="fas fa-user-shield me-2"></i>Admin Login</h2>
                            <p class="mb-0">Secure access to management system</p>
                        </div>
                        <div class="login-body">
                            <form id="loginForm">
                                <div class="mb-3">
                                    <label for="loginPhone" class="form-label fw-semibold">Admin Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control" id="loginPhone" placeholder="09171234567" required>
                                    </div>
                                    <div class="form-text">Use your registered admin phone number</div>
                                </div>
                                <div class="mb-3">
                                    <label for="loginPassword" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="loginPassword" placeholder="Enter admin password" required>
                                    </div>
                                </div>
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Remember this device</label>
                                    </div>
                                    <a href="#" class="text-decoration-none" style="color: var(--primary);">Forgot password?</a>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mb-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>Access Admin Panel
                                </button>
                                <div class="text-center">
                                    <p class="mb-0">
                                        <a href="index.php" class="text-decoration-none fw-semibold" style="color: var(--secondary);">
                                            <i class="fas fa-arrow-left me-1"></i>Back to Farmer Login
                                        </a>
                                    </p>
                                </div>
                            </form>
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
                    <p class="mb-0">Administrative Management System</p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <p class="mb-2">Contact: admin@smartfarming.ph | (02) 1234-5678</p>
                    <div class="social-links">
                        <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
            <p class="mb-0">&copy; 2025 Smart Farming Philippines. Admin Access Only.</p>
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
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying Credentials...';
                submitBtn.disabled = true;

                // AJAX request to admin login function
                fetch('function/admin-login.php', {
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
                            showSuccessAlert('Admin Access Granted!', `Welcome, ${data.user.name}! Accessing admin panel...`);

                            // Store user data in sessionStorage
                            sessionStorage.setItem('user', JSON.stringify(data.user));

                            // Redirect to admin dashboard
                            setTimeout(() => {
                                window.location.href = 'admin/index.php';
                            }, 2000);
                        } else {
                            showErrorAlert('Access Denied', data.message || 'Invalid admin credentials. Please try again.');
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
        });
    </script>
</body>

</html>