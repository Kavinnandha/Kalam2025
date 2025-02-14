<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'header/links.php'; ?>
    <?php include 'header/navbar_styles.php'; ?>
    <?php include 'index_styles.php'; ?>
</head>

<body>
    <!-- Enhanced Navbar -->
    <?php include 'header/navbar.php'; ?>

    <!-- Enhanced Hero Section -->
    <div class="pt-20">
        <div class="hero-bg min-h-screen flex items-center relative">
            <div class="geometric-bg"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="text-white space-y-8">
                        <h1 class="font-orbitron text-5xl md:text-7xl font-bold animate-slide-in">
                            <span class="block">Event</span>
                            <span
                                class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Fusion
                                2025</span>
                        </h1>
                        <p class="font-poppins text-xl md:text-2xl text-gray-300 animate-slide-in">
                            Experience the future of tech conferences with cutting-edge presentations and networking
                            opportunities.
                        </p>
                        <div class="flex space-x-4 animate-slide-in">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button class="btn-futuristic px-8 py-4 rounded-full text-lg font-semibold" onclick="window.location.href='/event/categories/departments.php'">
                                    View Events
                                </button>
                            <?php else: ?>
                                <button class="btn-futuristic px-8 py-4 rounded-full text-lg font-semibold" onclick="window.location.href='/event/user/signup.php'">
                                    Register Now
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="user/signin.php" class="text-white text-sm underline hover:text-purple-400 transition duration-300">Already Registered? Sign in here.</a>
                        <?php endif; ?>
                    </div>
                    <div class="hidden md:block relative">
                        <div
                            class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-full filter blur-3xl animate-pulse">
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


    <!-- Featured Events Carousel -->
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Events</h2>
        <div class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500" id="carousel">
                    <div class="w-full md:w-1/3 p-4 flex-shrink-0">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/api/placeholder/400/300" alt="Event 1" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2">Tech Summit</h3>
                                <p class="text-gray-600">Join industry leaders for insights into emerging technologies.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 p-4 flex-shrink-0">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/api/placeholder/400/300" alt="Event 2" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2">AI Workshop</h3>
                                <p class="text-gray-600">Hands-on session with cutting-edge AI technologies.</p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 p-4 flex-shrink-0">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <img src="/api/placeholder/400/300" alt="Event 3" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2">Networking Night</h3>
                                <p class="text-gray-600">Connect with professionals in a relaxed atmosphere.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg"
                onclick="previousSlide()">←</button>
            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow-lg"
                onclick="nextSlide()">→</button>
        </div>
    </div>

    <!-- Schedule Section -->
    <div class="bg-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Event Schedule</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="text-purple-600 text-lg font-semibold mb-2">Day 1</div>
                    <h3 class="text-xl font-bold mb-4">Opening Ceremony</h3>
                    <p class="text-gray-600">9:00 AM - Welcome Speech</p>
                    <p class="text-gray-600">10:00 AM - Keynote Session</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="text-purple-600 text-lg font-semibold mb-2">Day 2</div>
                    <h3 class="text-xl font-bold mb-4">Technical Sessions</h3>
                    <p class="text-gray-600">9:00 AM - Workshop A</p>
                    <p class="text-gray-600">2:00 PM - Workshop B</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="text-purple-600 text-lg font-semibold mb-2">Day 3</div>
                    <h3 class="text-xl font-bold mb-4">Closing Events</h3>
                    <p class="text-gray-600">10:00 AM - Panel Discussion</p>
                    <p class="text-gray-600">4:00 PM - Closing Ceremony</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Contact Us</h3>
                    <p>Email: info@eventfusion.com</p>
                    <p>Phone: (555) 123-4567</p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-purple-400">Twitter</a>
                        <a href="#" class="hover:text-purple-400">LinkedIn</a>
                        <a href="#" class="hover:text-purple-400">Facebook</a>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Newsletter</h3>
                    <form class="flex">
                        <input type="email" placeholder="Enter your email"
                            class="px-4 py-2 rounded-l-lg w-full text-gray-800">
                        <button
                            class="bg-purple-600 px-4 py-2 rounded-r-lg hover:bg-purple-700 transition duration-300">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </footer>

    <?php include 'header/navbar_scripts.php'; ?>
</body>

</html>