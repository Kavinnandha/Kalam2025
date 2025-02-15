<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'header/links.php'; ?>
    <?php include 'header/navbar_styles.php'; ?>
    <?php include 'index_styles.php'; ?>
    <style>
        .hero-bg {
            position: relative;
            overflow: hidden;
        }

        .geometric-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(234, 179, 8, 0.3) 0%, transparent 20%),
                radial-gradient(circle at 80% 80%, rgba(34, 197, 94, 0.3) 0%, transparent 20%);
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
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
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

        .slide-up {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.6s ease-out;
        }

        .slide-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .btn-futuristic {
            background: linear-gradient(135deg, #eab308 0%, #22c55e 100%);
            transition: all 0.3s ease;
        }

        .btn-futuristic:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .event-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .event-card:hover {
            transform: translateY(-10px);
            border-color: #22c55e;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .schedule-card {
            transition: all 0.3s ease;
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
    </style>
</head>

<body>
    <?php include 'header/navbar.php'; ?>
    <?php
    include 'database/connection.php';

    // Fetch random featured events
    $sql = "SELECT image_path, event_id, event_name, description, category, registration_fee, event_date 
            FROM events 
            ORDER BY RAND() 
            LIMIT 20";
    $featured_events = $conn->query($sql);

    // Fetch upcoming schedule
    $schedule_sql = "SELECT event_name, event_date, start_time, end_time, venue 
                    FROM events 
                    ORDER BY RAND()
                    LIMIT 6";
    $schedule_events = $conn->query($schedule_sql);
    ?>

    <!-- Hero Section -->
    <div class="pt-20">
        <div class="hero-bg min-h-screen flex items-center relative">
            <div class="geometric-bg"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="text-white space-y-8">
                        <h1 class="font-orbitron text-5xl md:text-7xl font-bold slide-up">
                            <span class="block">2K25</span>
                            <span
                                class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-green-400">
                                KALAM 2025
                            </span>
                        </h1>
                        <p class="font-poppins text-xl md:text-2xl text-gray-300 slide-up">
                            Dive into a world of innovation and excitement with thrilling competitions, technical
                            events, and captivating non-technical activities.
                        </p>
                        <div class="flex space-x-4 slide-up">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button class="btn-futuristic px-8 py-4 rounded-full text-lg font-semibold text-white"
                                    onclick="window.location.href='/event/categories/departments.php'">
                                    View Events
                                </button>
                            <?php else: ?>
                                <button class="btn-futuristic px-8 py-4 rounded-full text-lg font-semibold text-white"
                                    onclick="window.location.href='/event/user/signup.php'">
                                    Register Now
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="hidden md:block relative">
                        <div
                            class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-yellow-500/20 to-green-500/20 rounded-full filter blur-3xl animate-pulse">
                        </div>
                        <video autoplay loop muted playsinline
                            class="relative z-10 rounded-2xl shadow-2xl animate-float">
                            <source src="EventPromo.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Events -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12 slide-up">Featured Events</h2>
        <div class="relative">
            <button
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-gray-100"
                id="prevBtn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 z-10 bg-white p-2 rounded-full shadow-lg hover:bg-gray-100"
                id="nextBtn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out" id="carousel">
                    <?php while ($event = $featured_events->fetch_assoc()): ?>
                        <div class="flex-none w-full md:w-1/3 px-4">
                            <div class="event-card bg-white rounded-lg shadow-lg overflow-hidden slide-up" onclick="window.location.href='/event/categories/event_details.php?event_id=<?php echo $event['event_id']; ?>'">
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($event['event_name']); ?>"
                                    class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-xl font-semibold text-gray-800">
                                            <?php echo htmlspecialchars($event['event_name']); ?>
                                        </h3>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                            ₹<?php echo number_format($event['registration_fee'], 2); ?>
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mb-4">
                                        <?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?>
                                    </p>
                                    <span class="text-green-600 font-medium">
                                        <?php echo date('d M Y', strtotime($event['event_date'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="flex justify-center mt-8 space-x-2" id="carouselDots">
                <!-- Dots will be added dynamically via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 slide-up">Event Schedule</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <?php while ($schedule = $schedule_events->fetch_assoc()): ?>
                    <div class="schedule-card bg-white p-6 rounded-lg shadow-lg slide-up">
                        <div class="text-yellow-600 text-lg font-semibold mb-2">
                            <?php echo date('d M Y', strtotime($schedule['event_date'])); ?>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-gray-800">
                            <?php echo htmlspecialchars($schedule['event_name']); ?>
                        </h3>
                        <div class="space-y-2">
                            <p class="text-gray-600">
                                Time: <?php echo date('h:i A', strtotime($schedule['start_time'])); ?> -
                                <?php echo date('h:i A', strtotime($schedule['end_time'])); ?>
                            </p>
                            <p class="text-gray-600">
                                Venue: <?php echo htmlspecialchars($schedule['venue']); ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="slide-up">
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <p>Email: info@kalam2025.com</p>
                    <p>Phone: (555) 123-4567</p>
                </div>
                <div class="slide-up">
                    <h3 class="text-xl font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-yellow-400 transition duration-300">Twitter</a>
                        <a href="#" class="hover:text-yellow-400 transition duration-300">LinkedIn</a>
                        <a href="#" class="hover:text-yellow-400 transition duration-300">Facebook</a>
                    </div>
                </div>
                <div class="slide-up">
                    <h3 class="text-xl font-semibold mb-4">Newsletter</h3>
                    <form class="flex">
                        <input type="email" placeholder="Enter your email"
                            class="px-4 py-2 rounded-l-lg w-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-green-400">
                        <button class="bg-green-600 px-4 py-2 rounded-r-lg hover:bg-green-700 transition duration-300">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </footer>

    <?php
    $conn->close();
    include 'header/navbar_scripts.php';
    ?>

    <script>
        // Intersection Observer for scroll animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        // Observe all slide-up elements
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.slide-up').forEach(el => {
                observer.observe(el);
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            const carousel = document.getElementById('carousel');
            const slides = carousel.children;
            const totalSlides = slides.length;
            const dotsContainer = document.getElementById('carouselDots');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            let currentSlide = 0;
            const slidesToShow = window.innerWidth >= 768 ? 3 : 1;
            const totalPages = Math.ceil(totalSlides / slidesToShow);

            // Create dots
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('button');
                dot.classList.add('carousel-dot');
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

            const dots = dotsContainer.children;

            function updateCarousel() {
                const offset = -(currentSlide * (100 / slidesToShow));
                carousel.style.transform = `translateX(${offset}%)`;

                // Update dots
                Array.from(dots).forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentSlide);
                });

                // Update button states
                prevBtn.style.opacity = currentSlide === 0 ? '0.5' : '1';
                nextBtn.style.opacity = currentSlide >= totalPages - 1 ? '0.5' : '1';
            }

            function goToSlide(index) {
                currentSlide = Math.max(0, Math.min(index, totalPages - 1));
                updateCarousel();
            }

            prevBtn.addEventListener('click', () => {
                if (currentSlide > 0) {
                    currentSlide--;
                    updateCarousel();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentSlide < totalPages - 1) {
                    currentSlide++;
                    updateCarousel();
                }
            });

            // Initialize carousel
            updateCarousel();

            // Optional: Auto-play
            let autoplayInterval = setInterval(() => {
                if (currentSlide < totalPages - 1) {
                    currentSlide++;
                } else {
                    currentSlide = 0;
                }
                updateCarousel();
            }, 5000);

            // Pause auto-play on hover
            carousel.parentElement.addEventListener('mouseenter', () => {
                clearInterval(autoplayInterval);
            });

            carousel.parentElement.addEventListener('mouseleave', () => {
                autoplayInterval = setInterval(() => {
                    if (currentSlide < totalPages - 1) {
                        currentSlide++;
                    } else {
                        currentSlide = 0;
                    }
                    updateCarousel();
                }, 5000);
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                const newSlidesToShow = window.innerWidth >= 768 ? 3 : 1;
                if (newSlidesToShow !== slidesToShow) {
                    location.reload();
                }
            });
        });
    </script>
</body>

</html>