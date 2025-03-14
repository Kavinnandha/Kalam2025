<?php
session_start();
include '../database/connection.php';

if ($_SESSION['is_superadmin'] != 'yes') {
    header("Location: login.php");
    exit();
}

$department_code = $_SESSION['department_code'];

// Fetch departments with their events categorized
$query = "SELECT d.department_code, d.department_name, 
          e.event_id, e.event_name, e.category
          FROM department d
          LEFT JOIN events e ON d.department_code = e.department_code
          ORDER BY d.department_name, e.category, e.event_name";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Organize data by department and category
$departments = [];
while ($row = $result->fetch_assoc()) {
    $dept_code = $row['department_code'];
    if (!isset($departments[$dept_code])) {
        $departments[$dept_code] = [
            'name' => $row['department_name'],
            'technical' => [],
            'non_technical' => []
        ];
    }
    
    if ($row['event_id']) {  // Check if there are any events
        $category = strtolower($row['category']) === 'technical' ? 'technical' : 'non_technical';
        $departments[$dept_code][$category][] = [
            'id' => $row['event_id'],
            'name' => $row['event_name']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
<?php include 'navigation.php'; ?>
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Department Events</h1>
                    <p class="text-gray-600 mt-1">View events by department and category</p>
                </div>
                <a href="<?php echo $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'; ?>" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Departments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($departments as $dept_code => $dept): ?>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Department Header -->
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <?php echo htmlspecialchars($dept['name']); ?>
                        </h2>
                    </div>

                    <!-- Categories -->
                    <div class="p-6 space-y-6">
                        <!-- Technical Events -->
                        <div>
                            <h3 class="text-md font-medium text-gray-700 mb-3">
                                <i class="fas fa-laptop-code mr-2"></i>Technical Events
                            </h3>
                            <?php if (!empty($dept['technical'])): ?>
                                <ul class="ml-6 space-y-2">
                                    <?php foreach ($dept['technical'] as $event): ?>
                                        <li class="text-gray-600">
                                            <i class="fas fa-circle text-xs mr-2"></i>
                                            <?php echo htmlspecialchars($event['name']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm ml-6">No technical events found</p>
                            <?php endif; ?>
                        </div>

                        <!-- Non-Technical Events -->
                        <div>
                            <h3 class="text-md font-medium text-gray-700 mb-3">
                                <i class="fas fa-users mr-2"></i>Non-Technical Events
                            </h3>
                            <?php if (!empty($dept['non_technical'])): ?>
                                <ul class="ml-6 space-y-2">
                                    <?php foreach ($dept['non_technical'] as $event): ?>
                                        <li class="text-gray-600">
                                            <i class="fas fa-circle text-xs mr-2"></i>
                                            <?php echo htmlspecialchars($event['name']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500 text-sm ml-6">No non-technical events found</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($departments)): ?>
                <div class="col-span-2 text-center py-8">
                    <p class="text-gray-500">No departments found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>