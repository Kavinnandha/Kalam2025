<?php
session_start();
if (!isset($_SESSION['department_code'])) {
    header("Location: login.php");
    exit();
}
include '../database/connection.php';

if (!isset($_SESSION['department_code'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['is_superadmin'] !== 'yes') {
    $events_query = "SELECT e.event_id, e.event_name, COUNT(DISTINCT o.user_id) as participant_count
                     FROM events e
                     LEFT JOIN order_items oi ON e.event_id = oi.event_id
                     LEFT JOIN orders o ON o.order_id = oi.order_id
                     GROUP BY e.event_id
                     ORDER BY e.event_name";
    
    $stmt = $conn->prepare($events_query);
    $stmt->execute();
    $events_result = $stmt->get_result();
    
} else {
    $department_code = $_SESSION['department_code'];
    
    // Fetch events with registration counts
    $events_query = "SELECT e.event_id, e.event_name, COUNT(DISTINCT o.user_id) as participant_count
                     FROM events e
                     LEFT JOIN order_items oi ON e.event_id = oi.event_id
                     LEFT JOIN orders o ON o.order_id = oi.order_id
                     WHERE e.department_code = ?
                     GROUP BY e.event_id
                     ORDER BY e.event_name";
    
    $stmt = $conn->prepare($events_query);
    $stmt->bind_param("s", $department_code);
    $stmt->execute();
    $events_result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Event Registrations</h1>
                    <p class="text-gray-600 mt-1">Department: <?php echo htmlspecialchars($department_code); ?></p>
                </div>
                <a href="manage_events.php" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-6">
            <?php while ($event = $events_result->fetch_assoc()): 
                // Fetch participants for each event
                $participants_query = "SELECT DISTINCT u.name, u.email, u.phone, o.order_date as registration_date
                                     FROM users u
                                     JOIN orders o ON u.user_id = o.user_id
                                     JOIN order_items oi ON o.order_id = oi.order_id
                                     WHERE oi.event_id = ?
                                     ORDER BY o.order_date DESC";
                
                $stmt = $conn->prepare($participants_query);
                $stmt->bind_param("i", $event['event_id']);
                $stmt->execute();
                $participants_result = $stmt->get_result();
            ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Event Header -->
                    <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                <?php echo htmlspecialchars($event['event_name']); ?>
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Total Registrations: 
                                <span class="font-semibold text-blue-600">
                                    <?php echo $event['participant_count']; ?>
                                </span>
                            </p>
                        </div>
                        <button onclick="toggleParticipants(<?php echo $event['event_id']; ?>)"
                                class="text-gray-500 hover:text-gray-700">
                            <i id="icon-<?php echo $event['event_id']; ?>" 
                               class="fas fa-chevron-down transition-transform duration-200"></i>
                        </button>
                    </div>

                    <!-- Participants List -->
                    <div id="participants-<?php echo $event['event_id']; ?>" class="hidden">
                        <?php if ($participants_result->num_rows > 0): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php while ($participant = $participants_result->fetch_assoc()): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($participant['name']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($participant['email']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($participant['phone']); ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo date('M d, Y h:i A', strtotime($participant['registration_date'])); ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="px-6 py-4 text-gray-500 text-sm">
                                No registrations found for this event.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>

            <?php if ($events_result->num_rows === 0): ?>
                <div class="text-center py-8">
                    <p class="text-gray-500">No events found for your department.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleParticipants(eventId) {
            const participantsDiv = document.getElementById(`participants-${eventId}`);
            const icon = document.getElementById(`icon-${eventId}`);
            
            participantsDiv.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>