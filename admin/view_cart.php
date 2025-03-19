<?php
// Start the session to access session variables
session_start();

// Include database connection
require_once '../database/connection.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
}

// Initialize search query
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Base SQL query
$sql = "SELECT e.event_id, e.event_name, d.department_name, 
               u.user_id, u.name, u.email, u.phone, u.college_id
        FROM events e
        JOIN department d ON e.department_code = d.department_code
        JOIN cart_items ci ON e.event_id = ci.event_id
        JOIN cart c ON ci.cart_id = c.cart_id
        JOIN users u ON c.user_id = u.user_id";

// Initialize where clause and params array
$where_conditions = [];
$params = [];
$types = '';

// Add department filter if department_code is in session
if (isset($_SESSION['department_code'])) {
    $where_conditions[] = "e.department_code = ?";
    $params[] = $_SESSION['department_code'];
    $types .= 's';
}

// Add search filter if available
if ($search_query) {
    $where_conditions[] = "(e.event_name LIKE ? OR u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ? OR u.college_id LIKE ?)";
    $params = array_merge($params, ["%$search_query%", "%$search_query%", "%$search_query%", "%$search_query%", "%$search_query%"]);
    $types .= 'sssss';
}

// Combine where conditions if any exist
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Order by event name to group results
$sql .= " ORDER BY e.event_name, u.name";

try {
    $stmt = $conn->prepare($sql);
    
    // Bind parameters if any exist
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Group results by event
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $event_id = $row['event_id'];
        $event_name = $row['event_name'];
        
        if (!isset($events[$event_id])) {
            $events[$event_id] = [
                'event_name' => $event_name,
                'department_name' => $row['department_name'],
                'users' => []
            ];
        }
        
        $events[$event_id]['users'][] = [
            'user_id' => $row['user_id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'college_id' => $row['college_id']
        ];
    }
} catch (Exception $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users' Carts by Event</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        orange: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
<?php include 'navigation.php'; ?>
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-orange-600">All Users' Carts by Event</h1>
            <p class="text-gray-600">View all users registered for each event</p>
        </header>

        <!-- Search Form -->
        <div class="mb-6">
            <form action="" method="GET" class="flex gap-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by event, user name, email, phone or college ID" 
                    value="<?php echo htmlspecialchars($search_query); ?>" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 flex-grow"
                >
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition">
                    Search
                </button>
                <?php if ($search_query): ?>
                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
                        Clear
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($events) && !isset($error_message)): ?>
            <div class="bg-orange-50 border-l-4 border-orange-500 text-orange-700 p-4 mb-6" role="alert">
                <p>No registrations found. Try a different search term or check if there are any registrations in the system.</p>
            </div>
        <?php endif; ?>

        <!-- Events and Users -->
        <?php if (!empty($events) && !isset($error_message)): ?>
            <div class="space-y-8">
                <?php foreach ($events as $event_id => $event): ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <div class="bg-orange-100 px-6 py-4">
                            <h2 class="text-xl font-semibold text-orange-800">
                                <?php echo htmlspecialchars($event['event_name']); ?>
                                <span class="text-sm font-normal text-orange-600 ml-2">
                                    (<?php echo htmlspecialchars($event['department_name']); ?>)
                                </span>
                            </h2>
                            <p class="text-orange-600 text-sm mt-1">
                                <?php echo count($event['users']); ?> registered users
                            </p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
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
                                    <?php foreach ($event['users'] as $user): ?>
                                        <tr class="hover:bg-orange-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($user['name']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo htmlspecialchars($user['email']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo htmlspecialchars($user['phone']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo htmlspecialchars($user['college_id']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>