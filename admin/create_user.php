<?php
session_start();
if (!isset($_SESSION["admin_id"]) || $_SESSION["is_superadmin"] != "yes") {
    header("Location: login.php");
    exit();
}
require_once '../database/connection.php';

$error = '';
$success = '';


function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

// Add/Edit Admin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : null;
    $name = sanitizeInput($_POST['name']);
    $phone_no = sanitizeInput($_POST['phone_no']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $department_code = isset($_POST['department_code']) && $_POST['department_code'] !== '' ? intval($_POST['department_code']) : null;
    $is_superadmin = sanitizeInput($_POST['is_superadmin']);

    try {
        if ($admin_id) {
            $query = "UPDATE admin SET 
                      name = ?, 
                      phone_no = ?, 
                      email = ?, 
                      department_code = ?, 
                      is_superadmin = ?";
            
            $params = [$name, $phone_no, $email, $department_code, $is_superadmin, $admin_id];
            $types = "sisssi";

            // Update password only if provided
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query .= ", password = ?";
                $params = [$name, $phone_no, $email, $hashed_password, $department_code, $is_superadmin, $admin_id];
                $types = "sissisi";
            }

            $query .= " WHERE admin_id = ?";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $result = $stmt->execute();

            $success = "Admin updated successfully!";
        } else {
            // Add new admin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO admin (name, phone_no, email, password, department_code, is_superadmin) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissss", $name, $phone_no, $email, $hashed_password, $department_code, $is_superadmin);
            $result = $stmt->execute();

            $success = "Admin added successfully!";
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Handle Delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    try {
        $stmt = $conn->prepare("DELETE FROM admin WHERE admin_id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        
        $success = "Admin deleted successfully!";
    } catch (Exception $e) {
        $error = "Error deleting admin: " . $e->getMessage();
    }
}

// Prepare Edit Mode
$edit_admin = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_admin = $stmt->get_result()->fetch_assoc();
}

// Get Departments
$departments = [];
$dept_query = $conn->query("SELECT department_code, department_name FROM department");
if ($dept_query) {
    $departments = $dept_query->fetch_all(MYSQLI_ASSOC);
}

// Get All Admins
$admins = [];
$admin_query = $conn->query("SELECT a.*, d.department_name FROM admin a LEFT JOIN department d ON a.department_code = d.department_code");
if ($admin_query) {
    $admins = $admin_query->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="container mx-auto max-w-4xl">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Admin Management</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="post" class="space-y-4">
                <?php if ($edit_admin): ?>
                    <input type="hidden" name="admin_id" value="<?php echo $edit_admin['admin_id']; ?>">
                <?php endif; ?>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo $edit_admin ? sanitizeInput($edit_admin['name']) : ''; ?>" 
                               required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="phone_no" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" id="phone_no" name="phone_no" 
                               value="<?php echo $edit_admin ? sanitizeInput($edit_admin['phone_no']) : ''; ?>" 
                               required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $edit_admin ? sanitizeInput($edit_admin['email']) : ''; ?>" 
                               required 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password<?php echo $edit_admin ? ' (leave blank to keep current)' : ''; ?>
                        </label>
                        <input type="password" id="password" name="password" 
                               <?php echo $edit_admin ? '' : 'required'; ?> 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="department_code" class="block text-sm font-medium text-gray-700">Department</label>
                        <select id="department_code" name="department_code"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">None</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['department_code']; ?>"
                                    <?php echo ($edit_admin && $edit_admin['department_code'] == $dept['department_code']) ? 'selected' : ''; ?>>
                                    <?php echo sanitizeInput($dept['department_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="is_superadmin" class="block text-sm font-medium text-gray-700">Superadmin</label>
                        <select id="is_superadmin" name="is_superadmin" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="no" <?php echo ($edit_admin && $edit_admin['is_superadmin'] == 'no') ? 'selected' : ''; ?>>No</option>
                            <option value="yes" <?php echo ($edit_admin && $edit_admin['is_superadmin'] == 'yes') ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <?php echo $edit_admin ? 'Update Admin' : 'Add Admin'; ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-2xl font-semibold p-4 bg-gray-100 border-b">Existing Admins</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Superadmin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $admin['admin_id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo sanitizeInput($admin['name']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo sanitizeInput($admin['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo sanitizeInput($admin['department_name'] ?? 'N/A'); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="<?php echo $admin['is_superadmin'] == 'yes' ? 'text-green-600' : 'text-gray-600'; ?>">
                                        <?php echo sanitizeInput($admin['is_superadmin']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="?edit=<?php echo $admin['admin_id']; ?>" 
                                       class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <a href="?delete=<?php echo $admin['admin_id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this admin?');" 
                                       class="text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>