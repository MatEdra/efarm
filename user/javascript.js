

// Mobile sidebar toggle
function setupMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking on a menu item on mobile
    if (window.innerWidth <= 768) {
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function () {
                sidebar.classList.remove('active');
            });
        });
    }
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function () {
    // Add click handlers for bottom nav items
    document.querySelectorAll('.bottom-nav-item').forEach(item => {
        item.addEventListener('click', function (e) {
            // Remove active class from all items
            document.querySelectorAll('.bottom-nav-item').forEach(navItem => {
                navItem.classList.remove('active');
            });
            // Add active class to clicked item
            this.classList.add('active');
        });
    });
});

// Handle window resize
window.addEventListener('resize', function () {
    // Reinitialize charts if needed on resize
    if (window.innerWidth <= 768) {
        document.querySelector('.sidebar').classList.remove('active');
    }
});
function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of the system!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2e7d32',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel',
        background: '#fff',
        iconColor: '#2e7d32'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to logout page
            window.location.href = '../function/logout.php';
        }
    });
}