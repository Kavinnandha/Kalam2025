<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>Department Events</title>
    <style>
        .notification {
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            max-width: 350px;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification-success {
            background: linear-gradient(to right, #34d399, #10b981);
        }
        
        .notification-error {
            background: linear-gradient(to right, #f87171, #ef4444);
        }
        
        .notification-icon {
            margin-right: 12px;
        }
        
        .notification-message {
            flex-grow: 1;
            font-weight: 500;
        }
        
        .notification-close {
            cursor: pointer;
            margin-left: 12px;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-50 to-yellow-50 pb-16 md:hidden">
    <?php include '../header/navbar.php'; ?>
    <?php
        include '../database/connection.php';
        
        // Get department code and category from URL
        $department_code = isset($_GET['code']) ? $_GET['code'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        
        // Get department name if department code is set
        if (!empty($department_code)) {
            $dept_query = "SELECT department_name FROM department WHERE department_code = ?";
            $stmt = $conn->prepare($dept_query);
            $stmt->bind_param("s", $department_code);
            $stmt->execute();
            $dept_result = $stmt->get_result();
            $department = $dept_result->fetch_assoc();
        } else {
            $department = ['department_name' => ' '];
        }
        
        // Get events based on department code and category
        if (!empty($department_code) && !empty($category)) {
            $event_query = "SELECT * FROM events WHERE department_code = ? AND category = ? ORDER BY event_name";
            $stmt = $conn->prepare($event_query);
            $stmt->bind_param("ss", $department_code, $category);
        } elseif (!empty($department_code)) {
            $event_query = "SELECT * FROM events WHERE department_code = ? ORDER BY event_name";
            $stmt = $conn->prepare($event_query);
            $stmt->bind_param("s", $department_code);
        } elseif (!empty($category)) {
            $event_query = "SELECT * FROM events WHERE category = ? ORDER BY event_name";
            $stmt = $conn->prepare($event_query);
            $stmt->bind_param("s", $category);
        } else {
            $event_query = "SELECT * FROM events ORDER BY event_name";
            $stmt = $conn->prepare($event_query);
        }
        $stmt->execute();
        $events = $stmt->get_result();
    ?>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <div class="pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="mb-12">
                <div class="relative inline-block">
                    <a href="departments.php" class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Departments
                    </a>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-orange-600 to-yellow-500 text-transparent bg-clip-text">
                        <?php echo htmlspecialchars($department['department_name']); ?> Events
                    </h1>
                    <div class="absolute -bottom-2 left-0 h-1 w-24 bg-gradient-to-r from-orange-500 to-yellow-400 rounded-full"></div>
                </div>
            </div>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while($event = $events->fetch_assoc()): ?>
                    <div class="h-full flex flex-col">
                        <!-- Clickable card area -->
                        <a href="event_details.php?event_id=<?php echo urlencode($event['event_id']); ?>" 
                        class="flex-grow block cursor-pointer">
                            <div class="bg-white h-full rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                                <!-- Image Container with fixed height -->
                                <div class="h-48 w-full rounded-t-2xl overflow-hidden">
                                    <img src="<?php echo !empty($event['image_path']) ? htmlspecialchars($event['image_path']) : '../networkingnight.webp'; ?>"
                                         alt="<?php echo htmlspecialchars($event['event_name']); ?>"
                                         class="w-full h-full object-cover">
                                </div>

                                <div class="p-6 flex flex-col h-[calc(100%-12rem)]">
                                    <!-- Category Badge -->
                                    <div class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-700 mb-4 w-fit">
                                        <?php echo htmlspecialchars($event['category']); ?>
                                    </div>

                                    <!-- Event Name -->
                                    <h2 class="text-2xl font-bold text-gray-800 mb-3 line-clamp-2">
                                        <?php echo htmlspecialchars($event['event_name']); ?>
                                    </h2>

                                    <!-- Event Details -->
                                    <p class="text-gray-600 mb-4 line-clamp-3 flex-grow">
                                        <?php echo htmlspecialchars($event['event_detail']); ?>
                                    </p>

                                    <!-- Price and Cart Button -->
                                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="text-lg font-semibold text-gray-800">
                                                ₹<?php echo number_format($event['registration_fee'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- Separate cart button outside the clickable area -->
                        <?php if ($event['category'] == 'General'): ?>
                            <button onclick="window.location.href='event_details.php?event_id=<?php echo urlencode($event['event_id']); ?>'" 
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white rounded-lg hover:from-orange-600 hover:to-yellow-500 transform hover:scale-105 transition-all duration-300 shadow-md flex items-center justify-center">
                            General Event
                        </button>
                        <?php else: ?>
                        <button onclick="addToCart(<?php echo $event['event_id']; ?>)" 
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white rounded-lg hover:from-orange-600 hover:to-yellow-500 transform hover:scale-105 transition-all duration-300 shadow-md flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Add to Cart
                        </button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>

                <?php if($events->num_rows === 0): ?>
                    <div class="col-span-full text-center py-12">
                        <div class="bg-white rounded-2xl shadow p-8 max-w-lg mx-auto">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No Events Found</h3>
                            <p class="text-gray-500">There are currently no events scheduled for this department.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php $conn->close(); ?>
        </div>
    </div>

    <script>
        // Notification system
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            // Icon based on notification type
            let icon = '';
            if (type === 'success') {
                icon = `<svg class="notification-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>`;
            } else {
                icon = `<svg class="notification-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>`;
            }
            
            // Create notification content
            notification.innerHTML = `
                ${icon}
                <span class="notification-message">${message}</span>
                <span class="notification-close" onclick="this.parentElement.remove()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </span>
            `;
            
            // Add to container
            container.appendChild(notification);
            
            // Show notification with animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto-remove after 4 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 4000);
        }

        function addToCart(eventId) {
            <?php if(!isset($_SESSION['user_id'])): ?>
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
                if(data.success) {
                    // Show success notification instead of alert
                    showNotification('Added to cart successfully!', 'success');
                } else {
                    // Show error notification instead of alert
                    showNotification(data.message || 'Error adding to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error notification
                showNotification('Error adding to cart', 'error');
            });
        }
    </script>
</body>

</html>