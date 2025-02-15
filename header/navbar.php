<nav class="fixed w-full z-50 nav-blur backdrop-blur-md bg-white/80">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            <!-- Logo with hover animation -->
            <div class="flex items-center space-x-2 group cursor-pointer">
                <div
                    class="w-20 h-10 rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg">
                    <span class="font-orbitron text-white text-xl">2K25</span>
                </div>
                <span
                    class="font-orbitron text-2xl bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent transition-all duration-300 group-hover:opacity-80">KALAM 2025</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/event/index.php"
                    class="group flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/event/index.php') echo 'text-green-600'; ?>">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-poppins">Home</span>
                </a>

                <!-- Events Dropdown -->
                <div class="relative group">
                    <button
                        class="group flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/event/categories/events.php' || $_SERVER['PHP_SELF'] == '/event/categories/departments.php' || $_SERVER['PHP_SELF'] == '/event/categories/event_details.php') echo 'text-green-600'; ?>">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-poppins">Events</span>
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div
                        class="absolute hidden group-hover:block w-48 mt-0 p-2 nav-blur backdrop-blur-md bg-white/90 rounded-lg shadow-xl transform transition-all duration-300 opacity-0 group-hover:opacity-100 scale-95 group-hover:scale-100">
                        <a href="/event/categories/events.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300 hover:text-indigo-600">All
                            Events</a>
                        <a href="/event/categories/departments.php"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300 hover:text-indigo-600">Departmentwise
                            Events</a>
                        <a href="/event/categories/events.php?category=Technical"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300 hover:text-indigo-600">Technical
                            Events</a>
                        <a href="/event/categories/events.php?category=Non-Technical"
                            class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-md transition-colors duration-300 hover:text-indigo-600">Non-Technical
                            Events</a>
                    </div>
                </div>

                <!-- Orders -->
                <a href="/event/orders/orders.php"
                    class="group flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/event/orders/orders.php') echo 'text-green-600'; ?>">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-poppins">Orders</span>
                </a>

                <!-- Cart -->
                <a href="/event/cart/cart.php"
                    class="group flex items-center space-x-2 text-gray-700 hover:text-indigo-600 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/event/cart/cart.php') echo 'text-green-600'; ?>">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-poppins">Cart</span>
                </a>

                <!-- Session-based Button -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button onclick="window.location.href='/event/user/signup.php'"
                        class="btn-futuristic text-white px-6 py-2 rounded-full font-poppins shadow-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                        Register Now
                    </button>
                <?php else: ?>
                    <button onclick="window.location.href='/event/user/signout.php'"
                        class="btn-futuristic text-white px-6 py-2 rounded-full font-poppins shadow-lg bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                        Sign Out
                    </button>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button"
                    class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors duration-300">
                    <svg viewBox="0 0 24 24"
                        class="h-6 w-6 fill-current transition-transform duration-300 hover:scale-110">
                        <path
                            d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Enhanced Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <div class="px-4 py-6 space-y-4">
                <!-- Home Link -->
                <a href="/event/index.php"
                    class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2 <?php if ($_SERVER['PHP_SELF'] == '/event/index.php') echo 'text-green-600'; ?>">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="transform transition-transform duration-300 group-hover:translate-x-1">Home</span>
                </a>

                <!-- Events Menu -->
                <div class="space-y-2">
                    <button id="mobile-events-button"
                        class="group flex items-center justify-between w-full font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2 <?php if ($_SERVER['PHP_SELF'] == '/event/categories/events.php' || $_SERVER['PHP_SELF'] == '/event/categories/departments.php' || $_SERVER['PHP_SELF'] == '/event/categories/event_details.php') echo 'text-green-600'; ?>">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span
                                class="transform transition-transform duration-300 group-hover:translate-x-1">Events</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="mobile-events-submenu"
                        class="hidden pl-8 space-y-3 overflow-hidden transition-all duration-300">
                        <a href="/event/categories/events.php"
                            class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <span class="transform transition-transform duration-300 group-hover:translate-x-1">All
                                Events</span>
                        </a>
                        <a href="/event/categories/departments.php"
                            class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span
                                class="transform transition-transform duration-300 group-hover:translate-x-1">Departmentwise
                                Events</span>
                        </a>
                        <a href="/event/categories/events.php?category=Technical"
                            class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span
                                class="transform transition-transform duration-300 group-hover:translate-x-1">Technical
                                Events</span>
                        </a>
                        <a href="/event/categories/events.php?category=Non-Technical"
                            class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:scale-110" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span
                                class="transform transition-transform duration-300 group-hover:translate-x-1">Non-Technical
                                Events</span>
                        </a>
                    </div>
                </div>

                <!-- Orders Link -->
                <a href="/event/orders/orders.php"
                    class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2 <?php if ($_SERVER['PHP_SELF'] == '/event/orders/orders.php') echo 'text-green-600'; ?>">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="transform transition-transform duration-300 group-hover:translate-x-1">Orders</span>
                </a>

                <!-- Cart Link -->
                <a href="/event/cart/cart.php"
                    class="group flex items-center space-x-3 font-poppins text-gray-700 hover:text-indigo-600 transition-all duration-300 py-2 <?php if ($_SERVER['PHP_SELF'] == '/event/cart/cart.php') echo 'text-green-600'; ?>">
                    <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="transform transition-transform duration-300 group-hover:translate-x-1">Cart</span>
                </a>

                <!-- Login/Register Button -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button onclick="window.location.href='/event/user/signup.php'"
                        class="group w-full btn-futuristic text-white px-6 py-3 rounded-full font-poppins shadow-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="transform transition-transform duration-300 group-hover:translate-x-1">Register
                            Now</span>
                    </button>
                <?php else: ?>
                    <button onclick="window.location.href='/event/user/signout.php'"
                        class="group w-full btn-futuristic text-white px-6 py-3 rounded-full font-poppins shadow-lg transition-all duration-300 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:scale-110" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="transform transition-transform duration-300 group-hover:translate-x-1">Sign Out</span>
                    </button>
                <?php endif; ?>
            </div>
        </div>

    </div>
</nav>