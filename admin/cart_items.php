<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Items by Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php
    // Start session
    session_start();

    // Get department code from session
    $department_code = isset($_SESSION['department_code']) ? $_SESSION['department_code'] : '';

    // Database connection
    require_once '../database/connection.php';

    // Query to get cart items grouped by events for the specific department
    $sql = "
        SELECT 
            e.event_id,
            e.event_name,
            c.cart_id,
            c.user_id,
            ci.cart_item_id,
            COUNT(ci.cart_item_id) as item_count
        FROM 
            cart c
        JOIN 
            cart_items ci ON c.cart_id = ci.cart_id
        JOIN 
            events e ON ci.event_id = e.event_id
        WHERE 
            e.department_code = ?
        GROUP BY 
            e.event_id
        ORDER BY 
            e.event_name
    ";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("s", $department_code);
        
        // Execute query
        $stmt->execute();
        
        // Get results
        $result = $stmt->get_result();
        
        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    ?>

    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Cart Items by Event</h1>
        </div>

        <?php if(isset($result) && $result->num_rows > 0): ?>
            <div class="bg-white rounded-lg shadow-md">
                <?php
                $event_groups = [];
                
                // Group items by event
                while($row = $result->fetch_assoc()) {
                    $event_id = $row['event_id'];
                    if(!isset($event_groups[$event_id])) {
                        $event_groups[$event_id] = [
                            'event_name' => $row['event_name'],
                            'items' => [],
                            'user_count' => 0,
                            'unique_users' => []
                        ];
                    }
                    
                    // Get detailed cart items for this event and count unique users
                    $item_sql = "
                        SELECT 
                            ci.cart_item_id,
                            e.event_name,
                            c.user_id,
                            u.name,
                            u.email,
                            u.phone,
                            u.college_id,
                            u.department
                        FROM 
                            cart_items ci
                        JOIN 
                            cart c ON ci.cart_id = c.cart_id
                        JOIN 
                            users u ON u.user_id = c.user_id
                        JOIN 
                            events e ON ci.event_id = e.event_id
                        WHERE 
                            ci.event_id = ?
                    ";
                    
                    $item_stmt = $conn->prepare($item_sql);
                    $item_stmt->bind_param("i", $event_id);
                    $item_stmt->execute();
                    $item_result = $item_stmt->get_result();
                    
                    while($item = $item_result->fetch_assoc()) {
                        $event_groups[$event_id]['items'][] = $item;
                        
                        // Track unique users
                        if (!in_array($item['user_id'], $event_groups[$event_id]['unique_users'])) {
                            $event_groups[$event_id]['unique_users'][] = $item['user_id'];
                        }
                    }
                    
                    $event_groups[$event_id]['user_count'] = count($event_groups[$event_id]['unique_users']);
                    $item_stmt->close();
                }
                ?>
                
                <!-- Events summary table -->
                <div class="p-4 mb-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Events Summary</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Event Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Number of Users
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item Count
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach($event_groups as $event_id => $event_data): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($event_data['event_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                                <?php echo $event_data['user_count']; ?> users
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <?php echo count($event_data['items']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Event groups detailed display -->
                <div class="divide-y divide-gray-200">
                    <?php foreach($event_groups as $event_id => $event_data): ?>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h2 class="text-lg font-semibold text-gray-700"><?php echo htmlspecialchars($event_data['event_name']); ?></h2>
                                <div class="flex space-x-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        <?php echo count($event_data['items']); ?> items
                                    </span>
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        <?php echo $event_data['user_count']; ?> users
                                    </span>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Item ID
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                User Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Email
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Phone
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                College ID
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach($event_data['items'] as $item): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($item['cart_item_id']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($item['email']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($item['phone']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($item['college_id']); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h2 class="mt-2 text-lg font-medium text-gray-900">No cart items found</h2>
                <p class="mt-1 text-sm text-gray-500">No items found for this department</p>
            </div>
        <?php endif; ?>
    </div>

    <?php
    // Close database connection
    $conn->close();
    ?>
</body>
</html>