<nav class="fixed w-full z-50 backdrop-blur-md shadow-xl">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            <!-- Logo with hover animation -->
            <div class="flex items-center space-x-2 group cursor-pointer"
                onclick="window.location.href='/kalam/index.php'">
                <img src="/kalam/images/kalam2025-hor.png" alt="Kalam 2025"
                    class="w-50 h-13 transition-all duration-300 group-hover:scale-103 group-hover:shadow-lg opacity-90 mix-blend-multiply"
                    style="filter: invert(0.9);">
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/kalam/index.php" class="group flex items-center space-x-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/index.php')
                    echo 'text-red-600'; ?>">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-poppins">Home</span>
                </a>

                <!-- Events Dropdown -->
                <div class="relative group">
                    <button class="group flex items-center space-x-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/categories/events.php' || $_SERVER['PHP_SELF'] == '/kalam/categories/departments.php' || $_SERVER['PHP_SELF'] == '/kalam/categories/event_details.php')
                        echo 'text-red-600'; ?>">
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
                    <div class="absolute hidden group-hover:block">
                        <div
                            class="w-60 mt-4 p-2 bg-white/90 backdrop-blur-lg rounded-lg shadow-xl transform transition-all duration-300 opacity-0 group-hover:opacity-100 scale-95 group-hover:scale-100">
                            <a href="/kalam/categories/events.php"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">All
                                Events</a>
                            <a href="/kalam/categories/departments.php"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">Departmentwise
                                Events</a>
                            <a href="/kalam/categories/events.php?category=General"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">General
                                Events</a>
                            <a href="/kalam/categories/events.php?category=Technical"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">Technical
                                Events</a>
                            <a href="/kalam/categories/events.php?category=Non-Technical"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">Non-Technical
                                Events</a>
                            <a href="/kalam/categories/events.php?category=Workshop"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                Workshops</a>
                            <a href="/kalam/categories/events.php?category=Hackathon"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                Hackathons</a>
                            <a href="/kalam/categories/events.php?category=Media"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                Media</a>
                            <a href="/kalam/categories/events.php?category=Culturals"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                Culturals</a>
                            <!--    
                            <a href="/kalam/categories/events.php?category=ESAT-Gaming"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                ESAT-Gaming</a>  -->
                            <a href="/kalam/categories/events.php?category=Tech-Hub"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                Tech-Hub</a>
                            <a href="/kalam/categories/events.php?category=NCC"
                                class="block px-4 py-2 text-gray-700 hover:bg-red-50 rounded-md transition-colors duration-300 hover:text-orange-400">
                                NCC</a>
                        </div>
                    </div>
                </div>

                <!-- Orders -->
                <a href="/kalam/orders/orders.php" class="group flex items-center space-x-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/orders/orders.php')
                    echo 'text-red-600'; ?>">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-poppins">Orders</span>
                </a>

                <!-- Cart -->
                <a href="/kalam/cart/cart.php" class="group flex items-center space-x-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/cart/cart.php')
                    echo 'text-red-600'; ?>">
                    <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-poppins">Cart</span>
                </a>

                <!-- Session-based Button -->
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <button onclick="window.location.href='/kalam/user/registration.php'"
                        class="relative group px-6 py-2 bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 rounded-full overflow-hidden hover:shadow-lg hover:shadow-orange-500/50 transition-all duration-300 cursor-pointer">
                        <span
                            class="relative text-white font-semibold text-lg group-hover:scale-105 transition-transform duration-300 inline-block">
                            Register Now
                        </span>
                    </button>
                <?php else: ?>
                    <button onclick="window.location.href='/kalam/user/signout.php'"
                        class="relative group px-6 py-2 bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 rounded-full overflow-hidden hover:shadow-lg hover:shadow-orange-500/50 transition-all duration-300 cursor-pointer">
                        <span
                            class="relative text-white font-semibold text-lg group-hover:scale-105 transition-transform duration-300 inline-block">
                            Sign Out
                        </span>
                    </button>
                <?php endif; ?>
            </div>

            <!-- Mobile Logo Only (No Menu Button) -->
            <div class="md:hidden">
                <!-- No menu button here, just the logo alone -->
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white shadow-lg z-100 border-t border-gray-200">
    <div class="flex justify-around items-center h-16">
        <!-- Home -->
        <a href="/kalam/index.php" class="flex flex-col items-center justify-center w-full py-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/index.php')
            echo 'text-red-600'; ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-xs">Home</span>
        </a>

        <!-- Events -->
        <button id="mobile-bottom-events" class="flex flex-col items-center justify-center w-full py-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/categories/events.php' || $_SERVER['PHP_SELF'] == '/kalam/categories/departments.php' || $_SERVER['PHP_SELF'] == '/kalam/categories/event_details.php')
            echo 'text-red-600'; ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-xs">Events</span>
        </button>

        <!-- Orders -->
        <a href="/kalam/orders/orders.php" class="flex flex-col items-center justify-center w-full py-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/orders/orders.php')
            echo 'text-red-600'; ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <span class="text-xs">Orders</span>
        </a>

        <!-- Cart -->
        <a href="/kalam/cart/cart.php" class="flex flex-col items-center justify-center w-full py-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/cart/cart.php')
            echo 'text-red-600'; ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-xs">Cart</span>
        </a>

        <!-- Account/Register -->
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="/kalam/user/registration.php" class="flex flex-col items-center justify-center w-full py-2 text-gray-700 hover:text-orange-400 transition-all duration-300 <?php if ($_SERVER['PHP_SELF'] == '/kalam/user/registration.php' || $_SERVER['PHP_SELF'] == '/kalam/user/login.php')
                echo 'text-red-600'; ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs">Register</span>
            </a>
        <?php else: ?>
            <a href="/kalam/user/signout.php"
                class="flex flex-col items-center justify-center w-full py-2 text-gray-700 hover:text-orange-400 transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-xs">Sign Out</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Mobile Events Popup Menu -->
<div id="mobile-events-popup" class="fixed inset-0 z-99 hidden">
    <div
        class="bg-white rounded-t-xl absolute bottom-16 left-0 right-0 p-4 transform transition-transform duration-300 ease-out">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Events</h3>
            <button id="close-events-popup" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <a href="/kalam/categories/events.php"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>All Events</span>
            </a>
            <a href="/kalam/categories/departments.php"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Departmentwise Events</span>
            </a>
            <a href="/kalam/categories/events.php?category=General"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>General Events</span>
            </a>
            <a href="/kalam/categories/events.php?category=Technical"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span>Technical Events</span>
            </a>
            <a href="/kalam/categories/events.php?category=Non-Technical"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Non-Technical Events</span>
            </a>
            <a href="/kalam/categories/events.php?category=Workshop"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10M7 7v2M17 7v2" />
                </svg>
                <span>Workshops</span>
            </a>
            <a href="/kalam/categories/events.php?category=Hackathon"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                <span>Hackathons</span>
            </a>
            <a href="/kalam/categories/events.php?category=Media"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span>Media</span>
            </a>
            <a href="/kalam/categories/events.php?category=Culturals"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
                <span>Culturals</span>
            </a>
            <!--
            <a href="/kalam/categories/events.php?category=ESAT-Gaming"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5zm-1 9h16" />
                </svg>
                <span>ESAT-Gaming</span>
            </a>
            -->
            <a href="/kalam/categories/events.php?category=Tech-Hub"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
                <span>Tech-Hub</span>
            </a>
            <a href="/kalam/categories/events.php?category=NCC"
                class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                </svg>
                <span>NCC</span>
            </a>
        </div>
    </div>
</div>

<!-- JavaScript for Mobile Events Menu -->
<script>
    // Enhanced JavaScript for Mobile Events Menu with resize handling
    // Replace the existing Mobile Events Popup Menu with a scrollable dropdown
    document.addEventListener('DOMContentLoaded', function () {
        const eventsButton = document.getElementById('mobile-bottom-events');
        const mobileEventsPopup = document.getElementById('mobile-events-popup');

        // Create new scrollable dropdown
        const scrollableDropdown = document.createElement('div');
        scrollableDropdown.id = 'mobile-events-scrollable';
        scrollableDropdown.className = 'fixed left-0 right-0 bg-white z-50 shadow-lg border-t border-gray-200 overflow-y-auto hidden';
        scrollableDropdown.style.maxHeight = '70vh'; // Set maximum height
        scrollableDropdown.style.bottom = '4rem'; // Position above bottom nav

        // Copy the grid content from popup to scrollable area
        const gridContent = mobileEventsPopup.querySelector('.grid');
        const headerContent = mobileEventsPopup.querySelector('.flex.justify-between');

        const contentWrapper = document.createElement('div');
        contentWrapper.className = 'p-4';

        // Clone the header without the close button
        const newHeader = document.createElement('div');
        newHeader.className = 'mb-4';
        newHeader.innerHTML = '<h3 class="text-lg font-semibold text-gray-800">Events</h3>';

        contentWrapper.appendChild(newHeader);
        contentWrapper.appendChild(gridContent.cloneNode(true));
        scrollableDropdown.appendChild(contentWrapper);

        // Add to the body
        document.body.appendChild(scrollableDropdown);

        // Toggle the scrollable dropdown when events button is clicked
        eventsButton.addEventListener('click', function () {
            if (scrollableDropdown.classList.contains('hidden')) {
                scrollableDropdown.classList.remove('hidden');
                scrollableDropdown.classList.add('animate-slide-up');
            } else {
                scrollableDropdown.classList.add('animate-slide-down');
                setTimeout(function () {
                    scrollableDropdown.classList.add('hidden');
                    scrollableDropdown.classList.remove('animate-slide-down', 'animate-slide-up');
                }, 300);
            }
        });

        // Hide popup menu as we're replacing it
        if (mobileEventsPopup) {
            mobileEventsPopup.style.display = 'none';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!scrollableDropdown.contains(e.target) &&
                e.target !== eventsButton &&
                !eventsButton.contains(e.target) &&
                !scrollableDropdown.classList.contains('hidden')) {
                scrollableDropdown.classList.add('animate-slide-down');
                setTimeout(function () {
                    scrollableDropdown.classList.add('hidden');
                    scrollableDropdown.classList.remove('animate-slide-down', 'animate-slide-up');
                }, 300);
            }
        });

        // Add additional styles
        const styleElement = document.createElement('style');
        styleElement.textContent = `
    #mobile-events-scrollable {
      transition: transform 0.3s ease-out;
    }
    
    /* Add some inertia scrolling for iOS */
    #mobile-events-scrollable {
      -webkit-overflow-scrolling: touch;
    }
    
    /* Add a subtle gradient fade at the bottom to indicate more content */
    #mobile-events-scrollable::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 20px;
      background: linear-gradient(to top, rgba(255,255,255,1), rgba(255,255,255,0));
      pointer-events: none;
    }
  `;
        document.head.appendChild(styleElement);
    });
</script>