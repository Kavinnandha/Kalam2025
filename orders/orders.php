<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>My Orders</title>

</head>

<body class="bg-gray-50 pb-16">
    <?php include '../header/navbar.php'; ?>
    <?php
    include '../database/connection.php';

    // Get user_id from session
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }

    // Fetch orders and order items with event details
    $sql = "SELECT o.order_id, o.order_date, 
            oi.order_item_id, oi.amount, 
            e.event_id, e.event_name, e.event_date, e.start_time, e.venue
            FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN events e ON oi.event_id = e.event_id
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC, o.order_id DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="pt-20 bg-lime-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="pt-5 mb-4 animate-slide-in">
                <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                <p class="mt-2 text-sm text-gray-600">View your event bookings</p>
            </div>

            <!-- Orders Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-animation">
                <?php
                $current_order_id = null;
                $delay = 0;
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        // If this is a new order, show the order date
                        if ($current_order_id !== $row['order_id']):
                            $current_order_id = $row['order_id'];
                            $delay = 0;
                            ?>
                            <div class="col-span-full mt-6 mb-2 animate-fade-in" style="animation-delay: <?php echo $delay; ?>s;">
                                <h2 class="text-lg font-semibold text-gray-900">
                                    Order #<?php echo $row['order_id']; ?> -
                                    <?php echo date('d M Y', strtotime($row['order_date'])); ?>
                                </h2>
                            </div>
                        <?php endif; ?>

                        <!-- Order Item Card -->
                        <div class="animate-scale-in hover-scale mb-6" style="animation-delay: <?php echo $delay; ?>s;">
                            <div
                                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                                <form action="../categories/event_details.php" method="GET">
                                    <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                    <button type="submit" class="w-full text-left group">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <span
                                                    class="px-3 py-1 text-sm bg-lime-100 text-lime-800 rounded-full transition-all duration-300 group-hover:bg-lime-200">
                                                    <?php echo date('d M Y', strtotime($row['event_date'])); ?>
                                                </span>
                                                <span
                                                    class="text-orange-600 font-semibold transition-colors duration-300 group-hover:text-orange-700">
                                                    ₹<?php echo number_format($row['amount'], 2); ?>
                                                </span>
                                            </div>
                                            <h3
                                                class="text-lg font-semibold text-gray-900 mb-2 transition-colors duration-300 group-hover:text-orange-600">
                                                <?php echo htmlspecialchars($row['event_name']); ?>
                                            </h3>
                                            <div class="text-sm text-gray-600">
                                                <p class="mb-1">
                                                    <span class="inline-block w-20">Time:</span>
                                                    <?php echo date('h:i A', strtotime($row['start_time'])); ?>
                                                </p>
                                                <p>
                                                    <span class="inline-block w-20">Venue:</span>
                                                    <?php echo htmlspecialchars($row['venue']); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div
                                            class="px-6 py-3 bg-gray-50 text-sm text-orange-600 transition-all duration-300 group-hover:bg-orange-50 group-hover:text-orange-700">
                                            View Event Details
                                            <span
                                                class="inline-block transition-transform duration-300 group-hover:translate-x-1">→</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php $delay += 0.1; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full">
                        <div class="text-center py-16 bg-white rounded-lg border border-gray-200 animate-fade-in">
                            <div class="inline-block p-6 bg-lime-100 rounded-full mb-6 animate-bounce">
                                <svg class="w-12 h-12 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2 animate-fade-in">No Orders Found</h3>
                            <p class="text-gray-600 mb-8 animate-fade-in">Start your journey by exploring our exciting
                                events!</p>
                            <?php isset($_SESSION['user_id']) ? $link = '../categories/events.php' : $link = '../user/login.php'; ?>
                            <a href="<?php echo $link; ?>"
                                class="inline-flex items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg transition-all duration-300 hover:bg-orange-700 animate-scale-in">
                                <?php echo isset($_SESSION['user_id']) ? 'Browse Events' : 'Login To View Your Events'; ?>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    $stmt->close();
    $conn->close();
    ?>

    <script>
        // Add stagger animation to grid items
        document.addEventListener('DOMContentLoaded', function () {
            const gridItems = document.querySelectorAll('.stagger-animation > *');
            gridItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.style.opacity = '1';
            });
        });
    </script>
</body>

</html>