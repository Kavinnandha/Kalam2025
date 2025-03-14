<?php
// Start the session to get department_code if available
session_start();

// Include database connection
require_once '../database/connection.php';

// Get department code from session if available
$department_filter = isset($_SESSION['department_code']) ? $_SESSION['department_code'] : null;

// Handle search query
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query
$sql = "SELECT h.team_id, h.team_name, h.title, h.problem_statement, h.event_id, h.project_file,
               e.event_name, d.department_name, d.department_code
        FROM hackathon h
        INNER JOIN events e ON h.event_id = e.event_id
        INNER JOIN department d ON e.department_code = d.department_code
        WHERE 1=1";

// Apply department filter if available
if ($department_filter) {
    $sql .= " AND e.department_code = '$department_filter'";
}

// Apply search filter if provided
if (!empty($search_query)) {
    $sql .= " AND (h.team_name LIKE '%$search_query%' 
                  OR h.title LIKE '%$search_query%' 
                  OR h.problem_statement LIKE '%$search_query%'
                  OR e.event_name LIKE '%$search_query%')";
}

$sql .= " ORDER BY h.team_id DESC";

// Execute query
$result = $conn->query($sql);

// Get total team count
$count_sql = "SELECT COUNT(DISTINCT h.team_id) as team_count FROM hackathon h 
              INNER JOIN events e ON h.event_id = e.event_id";
              
if ($department_filter) {
    $count_sql .= " WHERE e.department_code = '$department_filter'";
}

$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$team_count = $count_row['team_count'];

// Function to get team members for a given team_id
function getTeamMembers($conn, $team_id) {
    $members_sql = "SELECT u.name, u.email, u.phone, u.college_id 
                    FROM hackathon_teams ht
                    INNER JOIN users u ON ht.user_id = u.user_id
                    WHERE ht.team_id = ?";
    
    $stmt = $conn->prepare($members_sql);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $members_result = $stmt->get_result();
    
    $members = [];
    while ($member = $members_result->fetch_assoc()) {
        $members[] = $member;
    }
    
    return $members;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hackathon Projects</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
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
    <header class="bg-primary-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold">Hackathon Projects</h1>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <!-- Search Form and Stats -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                <div class="bg-white px-4 py-2 rounded-lg shadow-sm flex items-center mb-4 md:mb-0">
                    <span class="font-bold text-primary-600 text-xl mr-2"><?php echo $team_count; ?></span>
                    <span class="text-gray-600">Total Teams</span>
                    <?php if ($department_filter): ?>
                        <span class="ml-2 text-sm text-gray-500">(<?php echo htmlspecialchars($department_filter); ?> department)</span>
                    <?php endif; ?>
                </div>
                
                <form action="" method="GET" class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                    <div class="flex-grow">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by team name, project title, or problem statement" 
                            value="<?php echo htmlspecialchars($search_query); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        >
                    </div>
                    <button type="submit" class="px-6 py-2 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors">
                        Search
                    </button>
                    <?php if (!empty($search_query)): ?>
                        <a href="?" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Clear
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($row = $result->fetch_assoc()): 
                    // Get team members for this project
                    $team_members = getTeamMembers($conn, $row['team_id']);
                ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="bg-primary-500 px-4 py-3 text-white">
                            <h2 class="text-xl font-bold truncate"><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="text-primary-100">Team: <?php echo htmlspecialchars($row['team_name']); ?></p>
                        </div>
                        
                        <div class="p-4">
                            <div class="mb-4">
                                <span class="inline-block bg-primary-100 text-primary-800 text-xs px-2 py-1 rounded-full">
                                    <?php echo htmlspecialchars($row['event_name']); ?> | <?php echo htmlspecialchars($row['department_name']); ?>
                                </span>
                            </div>
                            
                            <h3 class="font-semibold text-gray-700 mb-2">Problem Statement:</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3"><?php echo htmlspecialchars($row['problem_statement']); ?></p>
                            
                            <div class="mb-4">
                                <h3 class="font-semibold text-gray-700 mb-2">Team Members:</h3>
                                <div class="space-y-3">
                                    <?php foreach ($team_members as $member): ?>
                                        <div class="bg-gray-50 p-3 rounded-md">
                                            <div class="font-medium text-primary-700 mb-1">
                                                <?php echo htmlspecialchars($member['name']); ?>
                                            </div>
                                            <div class="grid grid-cols-1 text-sm text-gray-600">
                                                <div><span class="font-medium">Email:</span> <?php echo htmlspecialchars($member['email']); ?></div>
                                                <div><span class="font-medium">Phone:</span> <?php echo htmlspecialchars($member['phone']); ?></div>
                                                <div><span class="font-medium">College:</span> <?php echo htmlspecialchars($member['college_id']); ?></div>
                                                <div><span class="font-medium">Department:</span> <?php echo htmlspecialchars($row['department_name']); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($row['project_file'])): ?>
                                <a 
                                    href="<?php echo htmlspecialchars($row['project_file']); ?>" 
                                    class="inline-block px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors"
                                    target="_blank"
                                >
                                    View Project PDF
                                </a>
                            <?php else: ?>
                                <span class="inline-block px-4 py-2 bg-gray-200 text-gray-600 rounded-lg">
                                    No PDF Available
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg p-8 text-center shadow">
                <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-xl font-bold text-gray-700 mb-2">No projects found</h2>
                <p class="text-gray-500">
                    <?php if (!empty($search_query)): ?>
                        No projects match your search criteria. Please try different keywords.
                    <?php else: ?>
                        There are no hackathon projects to display at this time.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>