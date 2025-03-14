<?php
// Include database connection
require_once '../database/connection.php';
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['is_superadmin'] != 'yes') {
    header("Location: login.php");
    exit();
}
// Initialize search variables
$search = '';
$searchField = 'name'; // Default search field
$whereClause = '';

// Handle search form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $search = trim($_POST['search']);
    $searchField = $_POST['searchField'];
    
    if (!empty($search)) {
        $search = mysqli_real_escape_string($conn, $search);
        $whereClause = "WHERE $searchField LIKE '%$search%'";
    }
}

// Count total users
$countQuery = "SELECT COUNT(*) as total FROM users $whereClause";
$countResult = mysqli_query($conn, $countQuery);
$totalUsers = mysqli_fetch_assoc($countResult)['total'];

// Fetch users from database
$query = "SELECT * FROM users $whereClause ORDER BY name ASC";
$result = mysqli_query($conn, $query);

// Close the connection after fetching data
// mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#f97316', // Orange-500
                            light: '#fdba74',   // Orange-300
                            dark: '#c2410c',    // Orange-700
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
        <h1 class="text-3xl font-bold text-primary mb-8">User Management System</h1>
        
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Search Users</h2>
            <form method="POST" action="" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Enter search term..." 
                        value="<?php echo htmlspecialchars($search); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                </div>
                <div class="w-full md:w-48">
                    <select 
                        name="searchField" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="name" <?php echo $searchField === 'name' ? 'selected' : ''; ?>>Name</option>
                        <option value="email" <?php echo $searchField === 'email' ? 'selected' : ''; ?>>Email</option>
                        <option value="phone" <?php echo $searchField === 'phone' ? 'selected' : ''; ?>>Phone</option>
                        <option value="college_id" <?php echo $searchField === 'college_id' ? 'selected' : ''; ?>>College ID</option>
                        <option value="department" <?php echo $searchField === 'department' ? 'selected' : ''; ?>>Department</option>
                    </select>
                </div>
                <div>
                    <button 
                        type="submit" 
                        class="w-full md:w-auto px-6 py-2 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition duration-200"
                    >
                        Search
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Results Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 bg-primary text-white flex justify-between items-center">
                <h2 class="text-xl font-semibold">User List</h2>
                <span class="bg-white text-primary px-3 py-1 rounded-full font-medium">
                    Total: <?php echo $totalUsers; ?>
                </span>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">College ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['name']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['email']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['phone']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['college_id']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['department']); ?></div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="p-6 text-center">
                    <p class="text-gray-500">No users found matching your search criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
    // Close the connection at the end of the script
    mysqli_close($conn);
    ?>
</body>
</html>