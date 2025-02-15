<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../header/links.php'; ?>
    <?php include '../header/navbar_styles.php'; ?>
    <title>My Orders</title>
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-out forwards;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-scale-in {
            animation: scaleIn 0.3s ease-out forwards;
        }

        .stagger-animation > * {
            opacity: 0;
        }

        .hover-scale {
            transition: transform 0.2s ease-in-out;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body class="bg-gray-50">
    <?php include '../header/navbar.php'; ?>
    <?php
   include '../database/connection.php';

    // Get user_id from session
    $user_id = $_SESSION['user_id'];

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

    <div class="pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-4 animate-slide-in">
                <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                <p class="mt-2 text-sm text-gray-600">View your event bookings</p>
            </div>

            <!-- Orders Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-animation">
                <?php 
                $current_order_id = null;
                $delay = 0;
                if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
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
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-100">
                                <form action="../categories/event_details.php" method="POST">
                                    <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                    <button type="submit" class="w-full text-left group">
                                        <div class="p-6">
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full transition-all duration-300 group-hover:bg-yellow-200">
                                                    <?php echo date('d M Y', strtotime($row['event_date'])); ?>
                                                </span>
                                                <span class="text-green-600 font-semibold transition-colors duration-300 group-hover:text-green-700">
                                                    ₹<?php echo number_format($row['amount'], 2); ?>
                                                </span>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2 transition-colors duration-300 group-hover:text-green-600">
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
                                        <div class="px-6 py-3 bg-gray-50 text-sm text-green-600 transition-all duration-300 group-hover:bg-green-50 group-hover:text-green-700">
                                            View Event Details 
                                            <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">→</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php $delay += 0.1; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full animate-fade-in">
                        <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                            <p class="text-gray-600">You haven't placed any orders yet.</p>
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

    <?php include '../header/navbar_scripts.php'; ?>

    <script>
        // Add stagger animation to grid items
        document.addEventListener('DOMContentLoaded', function() {
            const gridItems = document.querySelectorAll('.stagger-animation > *');
            gridItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.style.opacity = '1';
            });
        });
    </script>
</body>
</html>