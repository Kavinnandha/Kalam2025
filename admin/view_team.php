<?php
// Start session to get department code
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
}

// Include database connection
require_once '../database/connection.php';

// Get department code from session if it exists
$department_code = isset($_SESSION['department_code']) ? $_SESSION['department_code'] : null;

// Handle search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare SQL based on whether department code exists and search query
$sql = "
    SELECT t.team_id, t.team_name, e.event_name, d.department_name, 
           GROUP_CONCAT(u.name SEPARATOR '|') as member_names,
           GROUP_CONCAT(u.email SEPARATOR '|') as member_emails,
           GROUP_CONCAT(u.phone SEPARATOR '|') as member_phones,
           GROUP_CONCAT(u.college_id SEPARATOR '|') as member_college
    FROM team t
    JOIN events e ON t.event_id = e.event_id
    JOIN department d ON d.department_code = e.department_code
    LEFT JOIN team_members tm ON t.team_id = tm.team_id
    LEFT JOIN users u ON tm.user_id = u.user_id
";

// Add conditions
$conditions = [];
$params = [];

if ($department_code !== null) {
    $conditions[] = "e.department_code = ?";
    $params[] = $department_code;
}

if (!empty($search)) {
    $conditions[] = "(t.team_name LIKE ? OR e.event_name LIKE ? OR u.name LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Group and order
$sql .= " GROUP BY t.team_id ORDER BY t.team_name";

// Prepare and execute statement
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $types = str_repeat('s', count($params)); // Assuming all params are strings
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Listing</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
<?php include 'navigation.php'; ?>
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Team Directory</h1>
            
            <!-- Search Form -->
            <form method="GET" class="mb-6">
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search by team, event or member name..." 
                        value="<?php echo htmlspecialchars($search); ?>"
                        class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none"
                    >
                    <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Search
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                            Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Results count -->
            <p class="text-gray-600">
                <?php echo $result->num_rows; ?> team<?php echo $result->num_rows !== 1 ? 's' : ''; ?> found
                <?php if (!empty($search)): ?>
                    for "<?php echo htmlspecialchars($search); ?>"
                <?php endif; ?>
            </p>
        </header>

        <?php if ($result->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php 
                    // Parse member data from grouped results
                    $member_names = explode('|', $row['member_names'] ?? '');
                    $member_emails = explode('|', $row['member_emails'] ?? '');
                    $member_phones = explode('|', $row['member_phones'] ?? '');
                    $member_college = explode('|', $row['member_college'] ?? '');
                    
                    // Create member array
                    $members = [];
                    for ($i = 0; $i < count($member_names); $i++) {
                        if (!empty($member_names[$i])) {
                            $members[] = [
                                'name' => $member_names[$i],
                                'email' => $member_emails[$i] ?? '',
                                'phone' => $member_phones[$i] ?? '',
                                'college'=> $member_college[$i] ??'',
                            ];
                        }
                    }
                    ?>

                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-orange-600 text-white p-4">
                            <h2 class="text-xl font-bold"><?php echo htmlspecialchars($row['team_name']); ?></h2>
                            <p class="text-orange-100">Event: <?php echo htmlspecialchars($row['event_name']); ?></p>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-medium text-gray-700 mb-2">Team Members (<?php echo count($members); ?>)</h3>
                            
                            <?php if (!empty($members)): ?>
                                <ul class="divide-y divide-gray-200">
                                    <?php foreach ($members as $member): ?>
                                        <li class="py-3">
                                            <p class="font-medium"><?php echo htmlspecialchars($member['name']); ?></p>
                                            <?php if (!empty($member['email'])): ?>
                                                <p class="text-sm text-gray-600">
                                                    <span class="inline-block w-14">Email:</span>
                                                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="text-orange-600 hover:underline">
                                                        <?php echo htmlspecialchars($member['email']); ?>
                                                    </a>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($member['phone'])): ?>
                                                <p class="text-sm text-gray-600">
                                                    <span class="inline-block w-14">Phone:</span>
                                                    <a href="tel:<?php echo htmlspecialchars($member['phone']); ?>" class="text-orange-600 hover:underline">
                                                        <?php echo htmlspecialchars($member['phone']); ?>
                                                    </a>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($member['college'])): ?>
                                                <p class="text-sm text-gray-600">
                                                    <span class="inline-block w-14">College:</span>
                                                    <a <?php echo htmlspecialchars($member['college']); ?>" class="text-orange-600 hover:underline">
                                                        <?php echo htmlspecialchars($member['college']); ?>
                                                    </a>
                                                </p>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="text-gray-500 italic">No team members found</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 text-right">
                            <span class="inline-block bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                                <?php echo htmlspecialchars($row['department_name']); ?>
                            </span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No teams found</h3>
                <p class="text-gray-500">
                    <?php if (!empty($search)): ?>
                        No results match your search criteria. Try a different search term or clear the search.
                    <?php else: ?>
                        There are no teams available at this time.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Optional JavaScript for enhancing the UI
        document.addEventListener('DOMContentLoaded', function() {
            // Add any client-side functionality here
        });
    </script>
</body>
</html>