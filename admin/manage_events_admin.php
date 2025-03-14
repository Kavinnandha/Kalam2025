<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['is_superadmin'] != 'yes') {
    header("Location: login.php");
    exit();
}
include '../database/connection.php';

// Handle search query
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Get basic statistics
$stats_queries = [
    'total_events' => "SELECT COUNT(*) as count FROM events",
    'total_registrations' => "SELECT COUNT(*) as count FROM order_items oi 
        JOIN events e ON e.event_id = oi.event_id"
];

$stats = [];
foreach ($stats_queries as $key => $query) {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stats[$key] = $result['count'];
}

// Get all unique categories and their counts
$categories_query = "SELECT category, COUNT(*) as count FROM events GROUP BY category ORDER BY category";
$stmt = $conn->prepare($categories_query);
$stmt->execute();
$categories_result = $stmt->get_result();
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[$row['category']] = $row['count'];
}

// Fetch all events with search filter if provided
$events_query = "SELECT * FROM events WHERE 1=1";
$params = [];
$types = "";

if (!empty($search_term)) {
    $events_query .= " AND (event_name LIKE ? OR description LIKE ? OR venue LIKE ? OR category LIKE ?)";
    $search_param = "%$search_term%";
    $params = array_fill(0, 4, $search_param);
    $types = "ssss";
}

$events_query .= " ORDER BY event_name";
$stmt = $conn->prepare($events_query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$events = $stmt->get_result();

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
    header("Location: " . (isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'));
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
        <?php include 'navigation.php'; ?>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <form action="" method="GET" class="flex w-full md:w-2/3 mx-auto">
                    <div class="relative flex-grow">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" 
                               placeholder="Search events by name, description, venue or category..." 
                               class="w-full px-4 py-3 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <?php if (!empty($search_term)): ?>
                            <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 rounded-r-md transition duration-300 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo min(count($categories) + 2, 6); ?> gap-6 mb-8">
                <!-- Total Events Card -->
                <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition duration-300 transform hover:-translate-y-1" onclick="window.location.href='events_list.php'">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-primary-100 text-primary-600">
                            <i class="fas fa-calendar-alt text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Events</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $stats['total_events']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Total Registrations Card -->
                <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition duration-300 transform hover:-translate-y-1" onclick="window.location.href='registrations.php'">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-primary-100 text-primary-600">
                            <i class="fas fa-user-check text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total Registrations</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $stats['total_registrations']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Category Cards -->
                <?php 
                $icon_classes = ['fa-microchip', 'fa-users', 'fa-paint-brush', 'fa-music', 'fa-trophy', 'fa-brain', 'fa-gamepad'];
                $icon_index = 0;
                foreach ($categories as $category => $count): 
                    $icon_class = $icon_classes[$icon_index % count($icon_classes)];
                    $icon_index++;
                ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-primary-100 text-primary-600">
                            <i class="fas <?php echo $icon_class; ?> text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500"><?php echo htmlspecialchars($category); ?> Events</p>
                            <p class="text-2xl font-semibold text-gray-700"><?php echo $count; ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6 shadow-sm">
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6 shadow-sm">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Search Results Display -->
            <?php if (!empty($search_term)): ?>
                <div class="mb-6 bg-white rounded-lg shadow-md p-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">
                            Search Results for: "<?php echo htmlspecialchars($search_term); ?>"
                        </h2>
                        <span class="bg-primary-100 text-primary-800 text-sm px-3 py-1 rounded-full">
                            <?php echo $events->num_rows; ?> result<?php echo $events->num_rows != 1 ? 's' : ''; ?> found
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Events Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php if ($events->num_rows > 0): ?>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                            <div class="h-48 overflow-hidden relative">
                                <?php if ($event['image_path']): ?>
                                    <img class="w-full h-full object-cover"
                                        src="<?php echo htmlspecialchars($event['image_path']); ?>"
                                        alt="<?php echo htmlspecialchars($event['event_name']); ?>">
                                <?php else: ?>
                                    <div class="w-full h-full bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-5xl text-primary-300"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute top-0 right-0 bg-primary-600 text-white px-3 py-1 m-2 rounded-full text-xs">
                                    <?php echo htmlspecialchars($event['category']); ?>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                                    <?php echo htmlspecialchars($event['event_name']); ?>
                                </h3>
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="far fa-calendar mr-2 text-primary-500"></i>
                                        <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
                                    </p>
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 text-primary-500"></i>
                                        <?php echo htmlspecialchars($event['venue']); ?>
                                    </p>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <a href="edit_event.php?event_id=<?php echo $event['event_id']; ?>"
                                        class="text-primary-600 hover:text-primary-800 transition duration-300 flex items-center">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                                        <button type="submit" name="delete_event" class="text-red-600 hover:text-red-800 transition duration-300 flex items-center">
                                            <i class="fas fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-10">
                        <div class="text-primary-400 text-5xl mb-4">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-2xl font-semibold text-gray-700 mb-2">No events found</h3>
                        <p class="text-gray-500">Try adjusting your search criteria or create a new event.</p>
                        <a href="add_event.php" class="inline-block mt-4 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition duration-300">
                            <i class="fas fa-plus mr-2"></i> Add New Event
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>

</html>