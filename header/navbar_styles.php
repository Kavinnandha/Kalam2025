<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --gradient-1: linear-gradient(45deg, #63f19c, #7e4cf5);
    }

    .font-orbitron {
        font-family: 'Orbitron', sans-serif;
    }

    .font-poppins {
        font-family: 'Poppins', sans-serif;
    }

    /* Navigation styles */
    .nav-blur {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.8);
    }

    .menu-item {
        position: relative;
        overflow: hidden;
    }

    .menu-item::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--gradient-1);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .menu-item:hover::after {
        transform: translateX(0);
    }

    /* Mobile menu styles */
    .mobile-menu {
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
        z-index: 40;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .mobile-menu.active {
        transform: translateX(0);
    }

    /* Button styles */
    .btn-futuristic {
        background: linear-gradient(135deg, #eab308 0%, #22c55e 100%);
        transition: all 0.3s ease;
    }

    .btn-futuristic::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .btn-futuristic:hover::before {
        left: 100%;
    }

    /* Animation classes */
    .menu-icon-transition {
        transition: transform 0.3s ease;
    }

    .rotate-180 {
        transform: rotate(180deg);
    }

    .group:hover .group-hover\:translate-x-1 {
        transform: translateX(0.25rem);
    }

    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    /* Smooth submenu animation */
    #mobile-events-submenu {
        max-height: 0;
        opacity: 0;
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out, padding 0.3s ease-out;
    }

    #mobile-events-submenu.show {
        max-height: 500px;
        opacity: 1;
    }
</style>