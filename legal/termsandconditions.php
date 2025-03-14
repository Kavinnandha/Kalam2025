<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>Terms and Conditions | KALAM 2025</title>
    <!-- Add additional styles for animations and mobile optimization -->
    <style>
        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
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

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

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

<body class="bg-gradient-to-b from-gray-50 to-orange-50 min-h-screen pb-16">
    <?php include '../header/navbar.php'; ?>

    <!-- Hero Section with Animation -->
    <div class="relative overflow-hidden">
        <div
            class="animate-gradient bg-gradient-to-r from-orange-500 to-red-600 h-90 w-full absolute -skew-y-3 transform -translate-y-16">
        </div>
        <div class="container mx-auto px-4 pt-24 pb-12 relative mobile-hero">
            <div class="text-center slide-in-bottom">
                <h1 class="mt-4 text-4xl md:text-5xl mobile-title font-extrabold mb-3 text-white drop-shadow-lg">Terms &
                    Conditions</h1>
                <div class="h-1 w-24 bg-white rounded-full mx-auto mt-2 mb-6"></div>
                <p class="text-white text-lg max-w-2xl mx-auto leading-relaxed mobile-text">
                    Please review the following terms and conditions governing participation in KALAM 2025 events.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 -mt-6 relative z-10 mobile-p">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl p-8 slide-in-bottom delay-100 mobile-p">
            <!-- Decorative Elements -->
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 bg-orange-500 h-16 w-16 rounded-full opacity-20 hidden sm:block">
            </div>
            <div
                class="absolute bottom-0 left-0 -mb-4 -ml-4 bg-red-500 h-10 w-10 rounded-full opacity-20 hidden sm:block">
            </div>

            <!-- Table of Contents -->
            <div
                class="mb-10 p-5 bg-gradient-to-br from-orange-50 to-red-50 rounded-lg border border-orange-100 shadow-sm mobile-section">
                <h2 class="text-xl font-bold mb-4 text-orange-600 mobile-subtitle">Table of Contents</h2>
                <div id="toc-container" class="overflow-x-auto">
                    <div class="grid grid-cols-1 gap-2 min-w-full">
                        <a href="#section1"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">1.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Event Registration
                                Policy</span>
                        </a>
                        <a href="#section2"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">2.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Eligibility</span>
                        </a>
                        <a href="#section3"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">3.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Event Fee and
                                Payment</span>
                        </a>
                        <a href="#section4"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">4.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">No Refund Policy</span>
                        </a>
                        <a href="#section5"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">5.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Code of Conduct</span>
                        </a>
                        <a href="#section6"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">6.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Liability
                                Disclaimer</span>
                        </a>
                        <a href="#section7"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">7.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Event Postponement or
                                Cancellation</span>
                        </a>
                        <a href="#section8"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">8.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Photography and Media
                                Consent</span>
                        </a>
                        <a href="#section9"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">9.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Data Privacy</span>
                        </a>
                        <a href="#section10"
                            class="group flex items-center p-2 hover:bg-white rounded-md transition-all duration-300">
                            <span class="text-orange-500 font-bold mr-2 group-hover:text-red-500">10.</span>
                            <span class="text-gray-700 group-hover:text-orange-600 mobile-text">Agreement to
                                Terms</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sections -->
            <div id="section1"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        1</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Event Registration Policy</h2>
                </div>
                <p class="ml-14 text-gray-700 leading-relaxed mobile-ml mobile-text">
                    By registering for any event conducted by <strong class="text-orange-600">Sri Shakthi Institute of
                        Engineering and Technology</strong>
                    (hereinafter referred to as "the organizer"), you agree to comply with all the terms and conditions
                    mentioned herein.
                </p>
            </div>

            <div id="section2"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        2</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Eligibility</h2>
                </div>
                <p class="ml-14 text-gray-700 leading-relaxed mobile-ml mobile-text">
                    Participation in the event is open to students from any educational institution, provided they meet
                    the eligibility criteria defined for each event.
                    Participants must provide valid identification proof (College ID, Government ID, etc.) during the
                    event if requested.
                </p>
            </div>

            <div id="section3"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        3</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Event Fee and Payment</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Participants are required to pay a
                        one-time <span class="font-semibold">general event registration fee of ₹150</span> for attending
                        any general event.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Any technical or premium event may
                        have an additional fee specified during registration.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Payments are accepted through the
                        authorized payment gateway integrated on our website.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Ensure that you review all event
                        details and fees before completing your payment.
                    </p>
                </div>
            </div>

            <div id="section4"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-red-500 to-red-600 text-white font-bold mr-3 mobile-icon min-w-10">
                        4</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">No Refund Policy</h2>
                </div>
                <div
                    class="ml-14 text-red-600 leading-relaxed space-y-2 p-4 bg-red-50 rounded-lg border border-red-100 mobile-ml">
                    <p class="font-medium mobile-text">
                        <i class="fas fa-exclamation-circle mr-2"></i> <strong>All payments made towards event
                            registrations are non-refundable and non-transferable under any circumstances.</strong>
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs mr-2"></i> Once the payment is successful, no refunds or
                        cancellations will be entertained.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs mr-2"></i> Failure to attend the event will not qualify for any
                        refunds or future credits.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs mr-2"></i> In case the event is postponed or rescheduled, the
                        participant will be notified, and the registration will remain valid.
                    </p>
                </div>
            </div>

            <div id="section5"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        5</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Code of Conduct</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> All participants are expected to
                        maintain decorum and adhere to the guidelines set by the organizers.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Any form of misbehavior, misconduct,
                        or violation of rules may lead to <span class="font-semibold text-red-600">immediate
                            disqualification</span> without a refund.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Damaging event property, misconduct,
                        or failure to follow instructions from event organizers may lead to legal action.
                    </p>
                </div>
            </div>

            <div id="section6"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        6</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Liability Disclaimer</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> The organizer shall not be
                        responsible for any loss, damage, injury, or accidents caused to the participants during the
                        event.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> The participants are responsible for
                        their own belongings and are encouraged to take care of their valuables.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Any injuries, accidents, or health
                        issues during the event are the participant's sole responsibility, and the organizers will not
                        provide any insurance coverage.
                    </p>
                </div>
            </div>

            <div id="section7"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        7</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Event Postponement or Cancellation</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> In case of unforeseen circumstances
                        such as natural calamities, emergencies, or government restrictions, the event may be postponed
                        or canceled.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> In the event of cancellation, the
                        registration fees will <span class="font-semibold text-red-600">not</span> be refunded but may
                        be carried forward to future events at the discretion of the organizers.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> The organizer reserves the right to
                        change the venue, date, or time of the event without prior notice.
                    </p>
                </div>
            </div>

            <div id="section8"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        8</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Photography and Media Consent</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> By participating in the event, you
                        grant the organizer the right to use your photographs, videos, or any media captured during the
                        event for promotional and marketing purposes.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> If you do not wish to have your media
                        published, you must notify the organizer in advance via email.
                    </p>
                </div>
            </div>

            <div id="section9"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        9</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Data Privacy</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> The information provided by
                        participants during registration (such as Name, Email, Phone Number, College Name) will be used
                        solely for event communication purposes.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Your personal information will not be
                        shared with third-party entities without your consent.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> By registering, you consent to
                        receive updates and communications regarding future events organized by the institution.
                    </p>
                </div>
            </div>

            <div id="section10"
                class="mb-8 hover:shadow-md transition-all duration-300 p-5 rounded-lg border-l-4 border-orange-400 hover:border-red-500 mobile-section">
                <div class="flex items-start sm:items-center mb-3">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-r from-orange-400 to-red-500 text-white font-bold mr-3 mobile-icon min-w-10">
                        10</div>
                    <h2 class="text-2xl font-bold text-gray-800 mobile-subtitle">Agreement to Terms</h2>
                </div>
                <div class="ml-14 text-gray-700 leading-relaxed space-y-2 mobile-ml">
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> By registering and participating in
                        the event, you acknowledge that you have read, understood, and agree to all the terms and
                        conditions mentioned above.
                    </p>
                    <p class="mobile-text">
                        <i class="fas fa-circle text-xs text-orange-500 mr-2"></i> Violation of any of these terms may
                        result in <span class="font-semibold text-red-600">disqualification</span> from the event
                        without any refund or future consideration.
                    </p>
                </div>
            </div>

            <!-- Contact Section -->
            <div
                class="mt-10 p-6 bg-gradient-to-r from-orange-100 to-red-100 rounded-lg text-center shadow-sm slide-in-bottom delay-300 mobile-section">
                <div class="flex flex-col items-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-3 mobile-subtitle">Questions or Concerns?</h3>
                    <p class="text-gray-700 mb-4 mobile-text">
                        If you have any questions about these Terms and Conditions, please contact us:
                    </p>
                    <a href="mailto:kalam@siet.ac.in"
                        class="group flex items-center justify-center space-x-2 text-orange-600 font-bold hover:text-red-600 transition-all duration-300">
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
            &copy; Copyright @ 2025 By Kalam | Thiru.S.SengodaGounder Educational Trust and Charitable Trust
        </div>
    </div>

    <!-- Add animation JavaScript -->
    <script>
        // Reveal animations when scrolling
        document.addEventListener('DOMContentLoaded', function () {
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
                anchor.addEventListener('click', function (e) {
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