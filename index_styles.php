<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap');

    :root {
        --gradient-1: linear-gradient(45deg, #FFA500, #FFFF00, #FFFFFF);
        --gradient-2: linear-gradient(135deg, #FFA500, #FFFF00, #FFFFFF);
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
        background: radial-gradient(circle at center, rgb(154, 100, 0) 0%, rgb(151, 40, 0) 25%, rgb(146, 80, 0) 60%);
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
        border-color: rgba(144, 224, 239, 0.5);
        box-shadow: 0 10px 20px rgba(255, 115, 0, 0.4);
        background: linear-gradient(135deg, rgba(240, 147, 251, 0.2), rgba(255, 204, 112, 0.2), rgba(144, 224, 239, 0.2));
    }

    .event-card:hover h3 {
        color: #ff8c00;
    }

    .event-card:hover p {
        color: #666;
    }

    .event-card:hover span {
        color: #ff6b6b;
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
        background-color:rgb(149, 158, 171);
        transition: all 0.3s ease;
    }

    .carousel-dot.active {
        width: 24px;
        border-radius: 4px;
        background-color:rgb(216, 29, 0);
    }

    .btn-futuristic {
        background: linear-gradient(135deg, rgb(255, 98, 0) 20%, #FFFF00 100%);
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

    .featured-bg-pattern {
        background-color: rgb(222, 222, 222);
        background-image: radial-gradient(rgba(125, 125, 125, 0.1) 2px, transparent 2px);
        background-size: 20px 20px;
    }
</style>