<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --gradient-1: linear-gradient(45deg, #63f19c, #7e4cf5);
        --gradient-2: linear-gradient(135deg, #3b82f6, #60a5fa);
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
        -webkit-text-fill-color: transparent;
    }

    .hero-bg {
        position: relative;
        overflow: hidden;
        background: radial-gradient(circle at center, #035d1b 0%, #312e81 100%);
    }

    .hero-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .geometric-bg {
        position: absolute;
        width: 100%;
        height: 100%;
        background-image:
            radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(139, 92, 246, 0.15) 0%, transparent 50%);
        animation: pulse 8s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.5;
        }

        50% {
            opacity: 0.8;
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes glow {

        0%,
        100% {
            filter: drop-shadow(0 0 15px rgba(99, 241, 113, 0.5));
        }

        50% {
            filter: drop-shadow(0 0 25px rgba(92, 246, 169, 0.8));
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-glow {
        animation: glow 2s ease-in-out infinite;
    }

    .slide-up {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.6s ease-out;
    }

    .slide-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .event-card,
    .schedule-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .event-card:hover {
        transform: translateY(-10px);
        border-color: #22c55e;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .schedule-card {
        border-left: 4px solid #eab308;
    }

    .schedule-card:hover {
        transform: scale(1.02);
        border-left-color: #22c55e;
    }

    .carousel-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #D1D5DB;
        transition: all 0.3s ease;
    }

    .carousel-dot.active {
        width: 24px;
        border-radius: 4px;
        background-color: #22c55e;
    }

    .btn-futuristic {
        background: linear-gradient(135deg, #eab308 0%, #22c55e 100%);
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

    .animate-slide-in {
        opacity: 0;
        transform: translateY(20px);
        animation: slideIn 1.5s ease-out forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    video {
        width: 100%;
        height: auto;
        object-fit: cover;
    }

    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
    }
</style>