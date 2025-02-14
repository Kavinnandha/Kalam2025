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

    .gradient-text {
        background: var(--gradient-1);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

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

    .mobile-menu {
        transform: translateX(-100%);
        transition: transform 0.3s ease-in-out;
    }

    .mobile-menu.active {
        transform: translateX(0);
    }

    /* Futuristic Button */
    .btn-futuristic {
        background: var(--gradient-1);
        position: relative;
        overflow: hidden;
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

    .mobile-menu-active {
        transform: translateX(0) !important;
    }
</style>
