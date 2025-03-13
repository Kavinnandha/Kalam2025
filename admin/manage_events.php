<?php
session_start();
if (!isset($_SESSION['department_code'])) {
    header("Location: login.php");
    exit();
}
include '../database/connection.php';

$department_code = $_SESSION['department_code'];

$stmt = $conn->prepare("SELECT department_name FROM department WHERE department_code = ?");
$stmt->bind_param("s", $department_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $department_name = $row['department_name'];
} 

// Get statistics using prepared statements
$stats_queries = [
    'total_events' => "SELECT COUNT(*) as count FROM events WHERE department_code = ?",
    'tech_events' => "SELECT COUNT(*) as count FROM events WHERE category = 'Technical' AND department_code = ?",
    'non_tech_events' => "SELECT COUNT(*) as count FROM events WHERE category = 'Non-Technical' AND department_code = ?",
    'total_registrations' => "SELECT COUNT(*) as count FROM order_items oi 
        JOIN events e ON e.event_id = oi.event_id WHERE e.department_code = ?"
];

$stats = [];
foreach ($stats_queries as $key => $query) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $department_code);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stats[$key] = $result['count'];
}

// Fetch events with pagination (Filtered by department)
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$items_per_page = 8;
$offset = ($page - 1) * $items_per_page;

$events_query = "SELECT * FROM events WHERE department_code = ? ORDER BY event_date DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($events_query);
$stmt->bind_param("iii", $department_code, $items_per_page, $offset);
$stmt->execute();
$events = $stmt->get_result();

// Get total pages
$total_events = $stats['total_events'];
$total_pages = ceil($total_events / $items_per_page);

// Handle Delete
if (isset($_POST['delete_event'])) {
    $event_id = $_POST['event_id'];
    $delete_stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $delete_stmt->bind_param("i", $event_id);

    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Event deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting event!";
    }
    header("Location: manage_events.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Event Management</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php if ($department_name == "Culturals"): ?>
                            <a href="view_team.php"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                <i class="fas fa-music mr-2"></i>Team Management
                            </a>
                        <?php endif; ?>
                        <a href="cart_items.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-shopping-cart mr-2"></i>Users Cart
                        </a>
                        <a href="add_event.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Add New Event
                        </a>
                        <a href="hackathon_teams.php"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-users mr-2"></i>Hackathon Teams
                        </a>
                        <a href="logout.php" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Events Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-calendar-alt text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Events</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $stats['total_events']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Technical Events Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-microchip text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Technical Events</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $stats['tech_events']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Non-Technical Events Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Non-Technical Events</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $stats['non_tech_events']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Registrations Card -->
                <div class="bg-white rounded-lg shadow p-6 cursor-pointer"
                    onclick="window.location.href='registrations.php'">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-user-check text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Registrations</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $stats['total_registrations']; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php while ($event = $events->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-48 overflow-hidden">
                            <?php if ($event['image_path']): ?>
                                <img class="w-full h-full object-cover"
                                    src="<?php echo htmlspecialchars($event['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($event['event_name']); ?>">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-4xl text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <?php echo htmlspecialchars($event['event_name']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="far fa-calendar mr-2"></i>
                                <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
                            </p>
                            <p class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?php echo htmlspecialchars($event['venue']); ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <a href="edit_event.php?event_id=<?php echo $event['event_id']; ?>"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="" method="POST" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this event?');">
                                    <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                    <button type="submit" name="delete_event" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="mt-8 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>"
                                class="<?php echo $page === $i ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'; ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </nav>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>