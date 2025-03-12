<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <style>
        .gradient-text {
            background: linear-gradient(to right, #16a34a, #eab308);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .custom-shape {
            clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
        }

        .event-image {
            border-radius: 1rem;
            object-fit: cover;
            width: 100%;
            height: 400px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        @media (max-width: 768px) {
            .event-image {
                height: 300px;
            }
        }
        
        /* Notification styles */
        .notification {
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 8px;
            color: white;
            max-width: 300px;
            z-index: 9999;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-100px);
            opacity: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notification.success {
            background: linear-gradient(to right, #10b981, #059669);
        }

        .notification.error {
            background: linear-gradient(to right, #ef4444, #dc2626);
        }

        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .notification-close {
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../header/navbar.php'; ?>
    <?php
    if (!isset($_GET['event_id'])) {
        header("Location: events.php");
        exit();
    }

    require_once '../database/connection.php';

    $event_id = $_GET['event_id'];
    $query = "SELECT e.*, d.department_name 
              FROM events e 
              LEFT JOIN department d ON e.department_code = d.department_code 
              WHERE e.event_id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: events.php");
        exit();
    }

    $event = $result->fetch_assoc();
    ?>

    <!-- Notification element -->
    <div id="notification" class="notification">
        <div class="notification-content">
            <svg id="notification-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
            <span id="notification-message"></span>
        </div>
        <span class="notification-close" onclick="closeNotification()">✕</span>
    </div>

    <div class="pt-20">
        <!-- Hero Section -->
        <div class="custom-shape bg-gradient-to-r from-orange-600 to-yellow-500 text-white p-8 sm:p-12 md:p-16 mb-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="space-y-4 text-center md:text-left md:w-1/2">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold">
                            <?php echo htmlspecialchars($event['event_name']); ?>
                        </h1>
                        <p class="text-xl text-gray-100">
                            <?php echo htmlspecialchars($event['event_detail']); ?>
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                                <?php echo htmlspecialchars($event['category']); ?>
                            </span>
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                                <?php echo htmlspecialchars($event['department_name']); ?>
                            </span>
                        </div>
                    </div>
                    <!-- Event Image in Hero Section -->
                    <div>
                        <?php if (isset($event['image_path']) && !empty($event['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($event['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($event['event_name']); ?>"
                                class="event-image shadow-xl transition-transform duration-300 hover:scale-[1.02]">
                        <?php else: ?>
                            <img src="/kalam/networkingnight.webp" alt="Event placeholder"
                                class="event-image shadow-xl transition-transform duration-300 hover:scale-[1.02] ">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Event Details -->
                <div class="md:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
                        <h2 class="text-2xl font-bold text-gray-800">Event Description</h2>
                        <p class="text-gray-600 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                        </p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Event Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-4">
                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium">
                                <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">
                                <?php
                                echo date('g:i A', strtotime($event['start_time'])) . ' - ' .
                                    date('g:i A', strtotime($event['end_time']));
                                ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">
                                <?php echo htmlspecialchars($event['venue']); ?>
                            </span>
                        </div>

                        <?php if ($event['contact'] != ''): ?>
                            <div class="flex items-center gap-3 text-gray-700">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="font-medium">
                                    <?php echo htmlspecialchars($event['contact']); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center gap-3 text-gray-700">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="font-medium">
                                ₹<?php echo number_format($event['registration_fee'], 2); ?>
                                <?php echo '(' . htmlspecialchars($event['fee_description']) . ')'; ?>
                            </span>
                        </div>

                        <!-- Add to Cart Button -->
                        <?php if ($event['category'] == 'General'): ?>
                            <div class="flex items-start gap-3 text-gray-700">
                                <svg class="w-11 h-11 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium">
                                    For general events, a one-time general event fee of ₹150 applies for the first time while purchasing.
                                </span>
                            </div>


                        <?php else: ?>
                            <button onclick="addToCart(<?php echo $event['event_id']; ?>)"
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white rounded-lg hover:from-orange-600 hover:to-yellow-500 transform hover:scale-105 transition-all duration-300 shadow-md flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Add to Cart
                            </button>
                        <?php endif; ?>

                        <?php if ($event['category'] == 'Hackathon'): ?>
                            <button
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white rounded-lg 
                                           hover:from-orange-600 hover:to-yellow-500 transform hover:scale-105 transition-all duration-300 shadow-md  flex items-center justify-center"
                                onclick="checkEventAccess(<?php echo $event['event_id']; ?>)">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 100-16 8 8 0 000 16z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                </svg>
                                View Hackathon Status
                            </button>
                        <?php endif; ?>

                        <?php if ($event['category'] == 'Culturals'): ?>
                            <button
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white rounded-lg 
                                           hover:from-orange-600 hover:to-yellow-500 transform hover:scale-105 transition-all duration-300 shadow-md  flex items-center justify-center"
                                onclick="culturalEventAccess(<?php echo $event['event_id']; ?>)">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 100-16 8 8 0 000 16z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                                </svg>
                                Cultural Team
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notification functions
        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            const notificationMessage = document.getElementById('notification-message');
            const notificationIcon = document.getElementById('notification-icon');
            
            // Set message
            notificationMessage.textContent = message;
            
            // Set proper class based on type
            notification.className = 'notification';
            notification.classList.add(type);
            
            // Set icon based on type
            if (type === 'success') {
                notificationIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M5 13l4 4L19 7" />
                `;
            } else {
                notificationIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                `;
            }
            
            // Show notification
            notification.classList.add('show');
            
            // Auto-hide after 4 seconds
            setTimeout(closeNotification, 4000);
        }
        
        function closeNotification() {
            const notification = document.getElementById('notification');
            notification.classList.remove('show');
        }

        function checkEventAccess(eventId) {
            fetch('../hackathon/check_event_access.php?event_id=' + eventId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'not_logged_in') {
                        window.location.href = '../user/registration.php';
                    } else if (data.status === 'not_purchased') {
                        showNotification('You need to purchase this event to proceed.', 'error');
                    } else if (data.status === 'allowed') {
                        window.location.href = '../hackathon/hackathon.php?event_id=' + encodeURIComponent(<?php echo $event['event_id'] ?>);
                    }
                });
        }

        function culturalEventAccess(eventId) {
            fetch('../culturals/culturals_event_access.php?event_id=' + eventId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'not_logged_in') {
                        window.location.href = '../user/registration.php';
                    } else if (data.status === 'not_purchased') {
                        showNotification('You need to purchase this event to proceed.', 'error');
                    } else if (data.status === 'allowed') {
                        window.location.href = '../culturals/culturals.php?event_id=' + encodeURIComponent(<?php echo $event['event_id'] ?>);
                    }
                });
        }
        
        function addToCart(eventId) {
            <?php if (!isset($_SESSION['user_id'])): ?>
                window.location.href = '../user/registration.php';
                return;
            <?php endif; ?>

            // AJAX call to add item to cart
            fetch('../cart/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    event_id: eventId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Added to cart successfully!', 'success');
                    } else {
                        showNotification(data.message || 'Error adding to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error adding to cart', 'error');
                });
        }

        // Optional: Add smooth scroll animation for better UX
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>