<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>Privacy Policy | KALAM 2025</title>
    <!-- Add additional styles for animations and mobile optimization -->
    <style>
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradientFlow 8s ease infinite;
        }
        .slide-in-bottom {
            animation: slideInBottom 0.8s ease-out forwards;
            opacity: 0;
        }
        @keyframes slideInBottom {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        /* Mobile optimization styles */
        @media (max-width: 640px) {
            .mobile-p {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            .mobile-text {
                font-size: 0.95rem !important;
            }
            .mobile-title {
                font-size: 1.5rem !important;
            }
            .mobile-subtitle {
                font-size: 1.25rem !important;
            }
            .mobile-ml {
                margin-left: 0.5rem !important;
            }
            .mobile-section {
                padding: 1rem !important;
            }
            .mobile-hero {
                padding-top: 5rem !important;
                padding-bottom: 2rem !important;
            }
            .mobile-icon {
                min-width: 2rem !important;
                height: 2rem !important;
                width: 2rem !important;
            }
            #toc-container {
                overflow-x: auto;
                padding-bottom: 1rem;
            }
        }
    </style>
    <!-- Add viewport meta tag for proper mobile display -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
</head>

<body class="bg-gradient-to-b from-gray-50 to-orange-50 min-h-screen">
    <?php include '../header/navbar.php'; ?>

    <!-- Hero Section with Animation -->
    <div class="relative overflow-hidden">
        <div class="animate-gradient bg-gradient-to-r from-orange-500 to-red-600 h-90 w-full absolute -skew-y-3 transform -translate-y-16"></div>
        <div class="container mx-auto px-4 pt-24 pb-12 relative mobile-hero">
            <div class="text-center slide-in-bottom">
                <h1 class="mt-4 text-4xl md:text-5xl mobile-title font-extrabold mb-3 text-white drop-shadow-lg">Privacy Policy</h1>
                <div class="h-1 w-24 bg-white rounded-full mx-auto mt-2 mb-6"></div>
                <p class="text-white text-lg max-w-2xl mx-auto leading-relaxed mobile-text">
                    Important information about how we collect, use, and protect your data when using the KALAM 2025 platform.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 -mt-6 relative z-10 mobile-p">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-8 slide-in-bottom delay-100 mobile-p">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 bg-orange-500 h-16 w-16 rounded-full opacity-20 hidden sm:block"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 bg-red-500 h-10 w-10 rounded-full opacity-20 hidden sm:block"></div>
            
            <!-- Table of Contents -->
            <div class="mb-10 p-5 bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border border-orange-100 shadow-sm mobile-section">
                <h2 class="text-xl font-bold mb-4 text-orange-600 mobile-subtitle">Table of Contents</h2>
                <div id="toc-container" class="overflow-x-auto">
                    <div class="grid grid-cols-1 gap-2 min-w-full">
                        <a href="#section1" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">1.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Collection of Information</span>
                        </a>
                        <a href="#section2" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">2.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Use of Your Information</span>
                        </a>
                        <a href="#section3" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">3.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Sharing of Personal Information</span>
                        </a>
                        <a href="#section4" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">4.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Links to Other Sites</span>
                        </a>
                        <a href="#section5" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">5.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Security Measures</span>
                        </a>
                        <a href="#section6" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">6.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Your Choices</span>
                        </a>
                        <a href="#section7" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">7.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Account Deletion</span>
                        </a>
                        <a href="#section8" class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">8.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Policy Updates</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Introduction Section -->
            <div class="mb-8 p-5 rounded-lg border-l-4 border-orange-400 bg-orange-50 mobile-section">
                <p class="text-gray-700 leading-relaxed mobile-text">
                    This Privacy Policy details how <strong class="text-orange-600">SRI SHAKTHI INSTITUTE OF ENGINEERING AND TECHNOLOGY ("SIET")</strong> 
                    collects, uses, and protects information that you share when using the Kalam Platform. By accessing our services through the website 
                    <a href="http://www.siet.ac.in/kalam/" class="text-orange-600 underline hover:text-red-600">http://www.siet.ac.in/kalam/</a> or our mobile application, 
                    you consent to the data practices described in this statement.
                </p>
            </div>

            <!-- Sections -->
            <div id="section1" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">1</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Collection of Information</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We collect personally identifiable information (such as your name, email address, phone number, and payment details) when you register on our platform.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We automatically collect certain information about your device, including your IP address, browser type, and access times.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We use data collection devices such as "cookies" on certain pages of the Kalam Platform to enhance your user experience.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> If you engage with our message boards or forums, we collect information you voluntarily provide.
                    </p>
                </div>
            </div>

            <div id="section2" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">2</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Use of Your Information</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We use your personal information to provide the services you request and to communicate with you about events.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Your information helps us resolve disputes, troubleshoot problems, and enforce our terms and conditions.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We analyze demographic data and user profiles to enhance our service offerings and customize your experience.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We may use your information to send you promotional materials about our events and services based on your interests.
                    </p>
                </div>
            </div>

            <div id="section3" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">3</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Sharing of Personal Information</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We may share your information with our affiliates and Event Organizers to facilitate your participation in events.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We may disclose personal information if required by law or in good faith belief that such action is necessary for legal compliance.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> In the event of a merger, acquisition, or reorganization, your information may be transferred to the new entity.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We will not sell your personal information to third parties for marketing purposes without your explicit consent.
                    </p>
                </div>
            </div>

            <div id="section4" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">4</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Links to Other Sites</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed mobile-ml">
                    <p class="mobile-text">
                        The Kalam Platform may contain links to other websites. Please be aware that we are not responsible for the privacy practices or content of these external sites. We encourage our users to read the privacy statements of each website that collects personally identifiable information.
                    </p>
                </div>
            </div>

            <div id="section5" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">5</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Security Measures</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed mobile-ml">
                    <p class="mobile-text">
                        The Kalam Platform implements stringent security measures to protect against unauthorized access, alteration, disclosure, or destruction of your personal information. When you access your account information, we offer the use of a secure server, and we adhere to strict security guidelines once your information is in our possession.
                    </p>
                </div>
            </div>

            <div id="section6" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">6</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Your Choices</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed mobile-ml">
                    <p class="mobile-text">
                        We provide all users with the opportunity to opt-out of receiving non-essential communications from us and our partners. If you wish to remove your contact information from our lists and newsletters, please click on the unsubscribe links provided in our email communications.
                    </p>
                </div>
            </div>

            <div id="section7" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">7</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Account Deletion</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> If you wish to delete your account, please send a request from your registered email to <a href="mailto:kalam@siet.ac.in" class="text-orange-600 underline hover:text-red-600">kalam@siet.ac.in</a>.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Your account will be deleted within 5-7 working days, and a confirmation will be sent to your registered email.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> After deletion, you will be automatically unsubscribed from all communications, and your login access will be disabled.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> You may create a new account with the same email, but your previous transaction history will not be retrievable.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> We may retain certain information as required by law even after account deletion.
                    </p>
                </div>
            </div>

            <div id="section8" class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">8</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Policy Updates</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        We reserve the right to modify this Privacy Policy at any time without prior notice. Changes will be posted on the Kalam Platform, and it is your responsibility to review this policy periodically. Your continued use of the platform after modifications indicates your acceptance of the updated terms.
                    </p>
                </div>
            </div>

            <!-- Contact Section -->
            <div class="mt-10 p-6 bg-gradient-to-r from-orange-100 to-red-100 rounded-lg text-center shadow-sm slide-in-bottom delay-300 mobile-section">
                <div class="flex flex-col items-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 mobile-subtitle">Questions or Concerns?</h3>
                    <p class="text-gray-700 mb-4 mobile-text">
                        If you have any questions about our Privacy Policy, please contact us:
                    </p>
                    <a href="mailto:kalam@siet.ac.in" class="group flex items-center justify-center space-x-2 text-orange-600 font-bold hover:text-red-600 transition-all duration-300">
                        <i class="fas fa-envelope transform group-hover:rotate-12 transition-all duration-300"></i>
                        <span class="underline">kalam@siet.ac.in</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gradient-to-b from-orange-100 to-red-200 py-6 mt-16">
        <div class="text-center text-gray-700 font-medium">
            &copy; 2025 KALAM. All Rights Reserved.
        </div>
    </div>

    <!-- Add animation JavaScript -->
    <script>
        // Reveal animations when scrolling
        document.addEventListener('DOMContentLoaded', function() {
            // Lazy load animations for better mobile performance
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.transition = 'all 0.5s ease-out';
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('div[id^="section"]').forEach(section => {
                section.style.opacity = 0;
                section.style.transform = 'translateY(20px)';
                observer.observe(section);
            });
            
            // Add smooth scrolling for anchor links on mobile
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        // Add offset for fixed header if needed
                        const offset = 80; 
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Optimize performance by reducing animations on mobile
            if (window.innerWidth < 640) {
                document.querySelectorAll('.animate-gradient').forEach(el => {
                    el.style.animation = 'none';
                });
            }
        });
    </script>
</body>

</html>