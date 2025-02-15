<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
   
    const mobileEventsButton = document.getElementById('mobile-events-button');
    const mobileEventsSubmenu = document.getElementById('mobile-events-submenu');
    const eventsArrow = mobileEventsButton.querySelector('svg:last-child');
    let isMenuOpen = false;

    // Toggle main mobile menu
    function toggleMenu() {
        isMenuOpen = !isMenuOpen;
        mobileMenu.classList.toggle('active');
        
        // Reset submenu when main menu is closed
        if (!isMenuOpen) {
            mobileEventsSubmenu.classList.add('hidden');
            mobileEventsButton.querySelector('svg').classList.remove('rotate-180');
        }
    }

    // Mobile menu button click
    mobileMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleMenu();
    });

    // Events submenu toggle
    mobileEventsButton.addEventListener('click', (e) => {
        mobileEventsSubmenu.classList.toggle('hidden');
        mobileEventsSubmenu.classList.toggle('show');
        eventsArrow.style.transform = mobileEventsSubmenu.classList.contains('hidden') 
            ? 'rotate(0deg)' 
            : 'rotate(180deg)';
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            toggleMenu();
        }
    });

    // Prevent menu close when clicking inside menu
    mobileMenu.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    // Close menu on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && isMenuOpen) {
            toggleMenu();
        }
    });
});
</script>