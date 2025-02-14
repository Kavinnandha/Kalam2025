<nav class="fixed w-full z-50 nav-blur">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 animate-glow flex items-center justify-center">
                    <span class="font-orbitron text-white text-xl">EF</span>
                </div>
                <span class="font-orbitron text-2xl gradient-text">EventFusion</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/event/index.php"
                    class="menu-item font-poppins text-gray-700 hover:text-indigo-600 transition-colors duration-300">Home</a>
                <div class="relative group">
                    <button
                        class="menu-item font-poppins text-gray-700 hover:text-indigo-600 transition-colors duration-300 flex items-center">
                        Events
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="absolute hidden group-hover:block w-48 mt-0 p-2 nav-blur rounded-lg shadow-xl">
                        <a href="#"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300">Conferences</a>
                        <a href="#"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300">Workshops</a>
                        <a href="#"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300">Seminars</a>
                    </div>
                </div>
                <a href="#"
                    class="menu-item font-poppins text-gray-700 hover:text-indigo-600 transition-colors duration-300">Speakers</a>
                <a href="#"
                    class="menu-item font-poppins text-gray-700 hover:text-indigo-600 transition-colors duration-300">Contact</a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button class="btn-futuristic text-white px-6 py-2 rounded-full font-poppins shadow-lg" onclick="window.location.href='/event/user/signup.php'">
                        Register Now
                    </button>
                <?php else: ?>
                    <button class="btn-futuristic text-white px-6 py-2 rounded-full font-poppins shadow-lg" onclick="window.location.href='/event/user/signout.php'">
                        Sign Out
                    </button>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button"
                    class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600"
                    aria-label="toggle menu">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current">
                        <path fill-rule="evenodd"
                            d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu absolute top-20 left-0 w-full nav-blur md:hidden">
            <div class="px-4 py-6 space-y-4">
                <a href="/event/index.php"
                    class="block text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Home</a>
                <div class="space-y-2">
                    <button
                        class="w-full text-left text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Events
                        ▾</button>
                    <div class="pl-4 space-y-2">
                        <a href="#"
                            class="block text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Conferences</a>
                        <a href="#"
                            class="block text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Workshops</a>
                        <a href="#"
                            class="block text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Seminars</a>
                    </div>
                </div>
                <a href="#"
                    class="block text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Speakers</a>
                <a href="#"
                    class="block text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-poppins">Contact</a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button class="btn-futuristic text-white px-6 py-2 rounded-full font-poppins shadow-lg w-full" onclick="window.location.href='/event/user/signup.php'">
                    Register Now
                </button>
                <?php else: ?>
                    <button class="btn-futuristic text-white px-6 py-2 rounded-full font-poppins shadow-lg w-full" onclick="window.location.href='/event/user/signout.php'">
                        Sign Out
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>