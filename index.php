<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'header/links.php'; ?>
    <?php include 'header/navbar_styles.php'; ?>

    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        @keyframes gradientText {
            from {
                opacity: 0;
                transform: translateY(50px);
                background-position: 0% 50%;
            }

            to {
                opacity: 1;
                transform: translateY(0);
                background-position: 100% 50%;
            }
        }

        .animate-gradient-letter {
            display: inline-block;
            opacity: 0;
            background: linear-gradient(45deg, #ff4d4d, #ff8c00, #ffd700);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradientText 0.5s ease forwards;
        }

        .carousel-dot {
            width: 10px;
            height: 8px;
            background-color:rgba(179, 171, 162, 0.4);
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            width: 20px;
            background: linear-gradient(to right, #ff4d4d, #ff8c00);
        }

        /* Touch slider styles */
        .carousel-container {
            touch-action: pan-y pinch-zoom;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-50 to-red-100 min-h-screen">
    <?php include 'header/navbar.php'; ?>
    <?php include 'database/connection.php';

    $sql = "SELECT image_path, event_id, event_name, description, category, registration_fee, event_date 
            FROM events 
            ORDER BY RAND() 
            LIMIT 18";
    $featured_events = $conn->query($sql);

    $schedule_sql = "SELECT event_id, event_name, event_date, start_time, end_time, venue 
                    FROM events 
                    ORDER BY RAND()
                    LIMIT 6";
    $schedule_events = $conn->query($schedule_sql);
    ?>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
        <div
            class="absolute inset-0 bg-gradient-to-br from-orange-400/30 via-red-300/30 to-yellow-200/30 animate-pulse">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="space-y-8 text-center lg:text-left">
                    <h1 id="animatedTitle" class="font-orbitron text-6xl md:text-8xl font-bold opacity-0">
                        <span class="block" data-text="KALAM"></span>
                        <span class="block" data-text="2025"></span>
                    </h1>

                    <p class="font-poppins text-xl md:text-2xl text-gray-700 bg-white/40 backdrop-blur-sm p-6 rounded-2xl shadow-xl animate-[fadeInUp_1s_ease_forwards] opacity-0"
                        style="animation-delay: 1s;">
                        Be part of an exciting journey of tech and creativity, perfect for both technical and
                        non-technical enthusiasts!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="<?php if (isset($_SESSION['user_id'])) { echo 'categories/events.php'; } else { echo 'user/registration.php'; } ?>">
                            <button
                                class="relative group px-8 py-4 bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 rounded-full overflow-hidden hover:shadow-lg hover:shadow-orange-500/50 transition-all duration-300 cursor-pointer">
                                <span
                                    class="relative text-white font-semibold text-lg group-hover:scale-105 transition-transform duration-300 inline-block">
                                    Join Us
                                </span>
                            </button>
                        </a>
                    </div>
                </div>

                <div class="relative flex items-center justify-center">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-red-500/30 via-orange-500/30 to-yellow-500/30 rounded-full filter blur-3xl animate-pulse">
                    </div>
                    <img src="images/kalam2025-ver.png" alt="Event Promo"
                        class="relative w-77 h-122 rounded-3xl shadow-2xl transform hover:scale-105 transition-transform duration-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Events -->
    <div class="relative py-24 bg-gradient-to-b from-orange-100 to-red-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2
                class="text-4xl md:text-5xl font-bold text-center mb-16 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">
                Featured Events
            </h2>

            <div class="relative">
                <!-- Navigation Arrows -->
                <button id="prevBtn"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 z-10 bg-white p-3 rounded-full shadow-lg hover:bg-orange-50 transition-all duration-300 group hidden md:block cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 group-hover:text-red-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button id="nextBtn"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 z-10 bg-white p-3 rounded-full shadow-lg hover:bg-orange-50 transition-all duration-300 group hidden md:block cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 group-hover:text-red-500"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div class="carousel-container relative overflow-hidden md:mx-8">
                    <div class="flex transition-transform duration-500 ease-in-out" id="carousel">
                        <?php while ($event = $featured_events->fetch_assoc()): ?>
                            <div class="flex-none w-full md:w-1/3 p-4 cursor-pointer" onclick="window.location.href='categories/event_details.php?event_id=<?php echo $event['event_id']; ?>'">
                                <div
                                    class="group relative bg-white rounded-2xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-orange-500/20">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-yellow-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                    <img src="<?php echo htmlspecialchars($event['image_path']); ?>"
                                        alt="<?php echo htmlspecialchars($event['event_name']); ?>"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" />
                                    <div class="p-6">
                                        <h3
                                            class="text-xl font-semibold text-gray-800 group-hover:text-orange-500 transition-colors">
                                            <?php echo htmlspecialchars($event['event_name']); ?>
                                        </h3>
                                        <p class="text-gray-600 mt-2">
                                            <?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?>
                                        </p>
                                        <div class="mt-4 flex justify-between items-center">
                                            <span class="text-red-700 font-medium">
                                                <?php echo date('d M Y', strtotime($event['event_date'])); ?>
                                            </span>
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                                                ₹<?php echo number_format($event['registration_fee'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div class="flex justify-center mt-8 space-x-3" id="carouselDots"></div>
            </div>
        </div>
    </div>

    <!-- Event Schedule section -->
    <div class="relative py-24 bg-gradient-to-b from-red-50 to-orange-100">
        <div class="max-w-7xl mx-auto px-4">
            <h2
                class="text-4xl md:text-5xl font-bold text-center mb-16 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">
                Event Schedule
            </h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($schedule = $schedule_events->fetch_assoc()): ?>
                    <div
                        class="group bg-white p-6 rounded-2xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-orange-500/20 border-l-4 border-orange-400 hover:border-red-500 cursor-pointer"
                        onclick="window.location.href='categories/event_details.php?event_id=<?php echo $schedule['event_id']; ?>'">
                        <div class="text-orange-500 text-lg font-semibold mb-2">
                            <?php echo date('d M Y', strtotime($schedule['event_date'])); ?>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-gray-800 group-hover:text-orange-500 transition-colors">
                            <?php echo htmlspecialchars($schedule['event_name']); ?>
                        </h3>
                        <div class="space-y-2 text-gray-600">
                            <p>
                                Time: <?php echo date('h:i A', strtotime($schedule['start_time'])); ?> -
                                <?php echo date('h:i A', strtotime($schedule['end_time'])); ?>
                            </p>
                            <p>
                                Venue: <?php echo htmlspecialchars($schedule['venue']); ?>
                            </p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="relative bg-gradient-to-b from-orange-100 to-red-200 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-12">
                <div class="flex flex-col items-center text-center">
                    <h3 class="text-2xl font-semibold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Contact Us</h3>
                    <div class="space-y-3 text-gray-700">
                        <p>kalam@siet.ac.in</p>
                        <p>(+91) 1234567890</p>
                    </div>
                </div>

                <div class="flex justify-center items-center">
                    <img src="images/kalam2025-hor.png" alt="KALAM Logo" class="mt-5 h-25" style="filter: invert(1);">
                </div>

                <div class="flex flex-col items-center text-center">
                    <h3 class="text-2xl font-semibold mb-6 text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">Follow Us</h3>
                    <div class="flex space-x-6">
                        <a href="#" class="text-gray-700 hover:text-orange-500 transition duration-300">Twitter</a>
                        <a href="#" class="text-gray-700 hover:text-orange-500 transition duration-300">LinkedIn</a>
                        <a href="#" class="text-gray-700 hover:text-orange-500 transition duration-300">Facebook</a>
                    </div>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-orange-200 text-center text-gray-600">
                <p>&copy; 2025 KALAM. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php $conn->close(); ?>
    <?php include 'header/navbar_scripts.php' ?>

    <script>
        // Text animation
        document.addEventListener('DOMContentLoaded', () => {
            const titleSpans = document.querySelectorAll('#animatedTitle span');

            titleSpans.forEach((titleSpan) => {
                const text = titleSpan.getAttribute('data-text');
                titleSpan.textContent = '';

                [...text].forEach((letter, i) => {
                    const span = document.createElement('span');
                    span.textContent = letter === ' ' ? '\u00A0' : letter;
                    span.style.animationDelay = `${i * 0.1}s`;
                    span.classList.add('animate-gradient-letter');
                    titleSpan.appendChild(span);
                });
            });

            document.getElementById('animatedTitle').style.opacity = '1';
        });

        // Carousel with touch support
        document.addEventListener('DOMContentLoaded', () => {
            const carousel = document.getElementById('carousel');
            const slides = carousel.children;
            const dotsContainer = document.getElementById('carouselDots');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');

            let currentSlide = 0;
            const slidesToShow = window.innerWidth >= 768 ? 3 : 1;
            const totalSlides = slides.length;
            const maxSlideIndex = totalSlides - slidesToShow;

            // Touch handling variables
            let touchStartX = 0;
            let touchEndX = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let isDragging = false;

            // Create carousel dots based on total slides
            function createDots() {
                dotsContainer.innerHTML = '';
                const totalDots = Math.ceil(totalSlides / slidesToShow);

                for (let i = 0; i < totalDots; i++) {
                    const dot = document.createElement('button');
                    dot.classList.add('carousel-dot');
                    dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
                    dot.addEventListener('click', () => goToSlide(i * slidesToShow));
                    dotsContainer.appendChild(dot);
                }
                updateDots();
            }

            function updateDots() {
                const dots = dotsContainer.children;
                const currentDotIndex = Math.floor(currentSlide / slidesToShow);
                Array.from(dots).forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentDotIndex);
                });
            }

            function updateCarousel() {
                const offset = -(currentSlide * (100 / slidesToShow));
                carousel.style.transform = `translateX(${offset}%)`;
                updateDots();
            }

            function goToSlide(index) {
                currentSlide = Math.min(Math.max(index, 0), maxSlideIndex);
                updateCarousel();
            }

            // Touch event handlers
            function touchStart(event) {
                touchStartX = event.touches[0].clientX;
                isDragging = true;
                prevTranslate = currentTranslate;
            }

            function touchMove(event) {
                if (!isDragging) return;

                touchEndX = event.touches[0].clientX;
                const diff = touchStartX - touchEndX;

                // Add resistance at edges
                if ((currentSlide === 0 && diff < 0) ||
                    (currentSlide === maxSlideIndex && diff > 0)) {
                    currentTranslate = prevTranslate + diff * 0.3;
                } else {
                    currentTranslate = prevTranslate + diff;
                }

                carousel.style.transform = `translateX(${-currentTranslate}px)`;
            }

            function touchEnd() {
                isDragging = false;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > 100) { // Threshold for slide change
                    if (diff > 0) {
                        currentSlide = Math.min(currentSlide + 1, maxSlideIndex);
                    } else {
                        currentSlide = Math.max(currentSlide - 1, 0);
                    }
                }

                updateCarousel();
            }

            // Add touch event listeners
            carousel.addEventListener('touchstart', touchStart);
            carousel.addEventListener('touchmove', touchMove);
            carousel.addEventListener('touchend', touchEnd);

            // Navigation buttons
            prevBtn.addEventListener('click', () => {
                currentSlide = Math.max(currentSlide - 1, 0);
                updateCarousel();
            });

            nextBtn.addEventListener('click', () => {
                currentSlide = Math.min(currentSlide + 1, maxSlideIndex);
                updateCarousel();
            });

            // Auto-play
            setInterval(() => {
                if (!isDragging) {
                    currentSlide = (currentSlide >= maxSlideIndex) ? 0 : currentSlide + 1;
                    updateCarousel();
                }
            }, 2000);

            // Initialize
            createDots();
            updateCarousel();

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