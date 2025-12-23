// Mobile Navigation Toggle
const openNavBtn = document.querySelector('#open_nav_btn');
const closeNavBtn = document.querySelector('#close_nav_btn');
const navItems = document.querySelector('#nav_items');

if (openNavBtn && closeNavBtn && navItems) {
    openNavBtn.addEventListener('click', () => {
        navItems.classList.add('show');
    });

    closeNavBtn.addEventListener('click', () => {
        navItems.classList.remove('show');
    });
}

// Auto-hide alerts after 5 seconds
const alerts = document.querySelectorAll('.alert');
if (alerts.length > 0) {
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
}

// Confirm delete actions
const deleteLinks = document.querySelectorAll('a[href*="delete"]');
deleteLinks.forEach(link => {
    if (!link.hasAttribute('onclick')) {
        link.addEventListener('click', (e) => {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    }
});