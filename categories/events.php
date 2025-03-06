<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>Department Events</title>
</head>

<body class="bg-gradient-to-br from-orange-50 to-yellow-50">
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
            $department = ['department_name' => 'All Departments'];
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
                        <button onclick="addToCart(<?php echo $event['event_id']; ?>)" 
                                class="mt-4 w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-yellow-400 text-white rounded-lg hover:from-orange-600 hover:to-yellow-500 transform hover:scale-105 transition-all duration-300 shadow-md flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Add to Cart
                        </button>
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

    <?php include '../header/navbar_scripts.php'; ?>

    <script>
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
                    // Update cart UI or show success message
                    alert('Added to cart successfully!');
                } else {
                    alert(data.message || 'Error adding to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding to cart');
            });
        }
    </script>
</body>

</html>