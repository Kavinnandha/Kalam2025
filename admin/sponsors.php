<?php
// Initialize database connection
include '../database/connection.php';
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add new sponsor
    if (isset($_POST['add_sponsor'])) {
        $sponsor_name = $conn->real_escape_string($_POST['sponsor_name']);
        $upload_dir = "../uploads/sponsors/";
        $target_file = "";
        
        // Handle file upload
        if (isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['error'] == 0) {
            $file_name = time() . '_' . basename($_FILES['sponsor_image']['name']);
            $target_file = $upload_dir . $file_name;
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['sponsor_image']['tmp_name'], $target_file)) {
                $image_path = "uploads/sponsors/" . $file_name;
                $sql = "INSERT INTO sponsors (sponsor_name, sponsor_image_path) VALUES ('$sponsor_name', '$image_path')";
                if ($conn->query($sql)) {
                    $success_message = "Sponsor added successfully!";
                } else {
                    $error_message = "Error adding sponsor: " . $conn->error;
                }
            } else {
                $error_message = "Error uploading file.";
            }
        } else {
            $error_message = "Please select an image file.";
        }
    }
    
    // Update sponsor
    if (isset($_POST['update_sponsor'])) {
        $sponsor_id = $conn->real_escape_string($_POST['sponsor_id']);
        $sponsor_name = $conn->real_escape_string($_POST['sponsor_name']);
        
        // Get current image path
        $result = $conn->query("SELECT sponsor_image_path FROM sponsors WHERE sponsor_id = $sponsor_id");
        $sponsor = $result->fetch_assoc();
        $image_path = $sponsor['sponsor_image_path'];
        
        // Handle file upload if new image is provided
        if (isset($_FILES['sponsor_image']) && $_FILES['sponsor_image']['error'] == 0) {
            $upload_dir = "../uploads/sponsors/";
            $file_name = time() . '_' . basename($_FILES['sponsor_image']['name']);
            $target_file = $upload_dir . $file_name;
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES['sponsor_image']['tmp_name'], $target_file)) {
                // Delete old image if it exists
                if (!empty($sponsor['sponsor_image_path']) && file_exists('../' . $sponsor['sponsor_image_path'])) {
                    unlink('../' . $sponsor['sponsor_image_path']);
                }
                
                $image_path = "uploads/sponsors/" . $file_name;
            } else {
                $error_message = "Error uploading file.";
            }
        }
        
        $sql = "UPDATE sponsors SET sponsor_name = '$sponsor_name', sponsor_image_path = '$image_path' WHERE sponsor_id = $sponsor_id";
        if ($conn->query($sql)) {
            $success_message = "Sponsor updated successfully!";
        } else {
            $error_message = "Error updating sponsor: " . $conn->error;
        }
    }
    
    // Delete sponsor
    if (isset($_POST['delete_sponsor'])) {
        $sponsor_id = $conn->real_escape_string($_POST['sponsor_id']);
        
        // Get image path before deleting
        $result = $conn->query("SELECT sponsor_image_path FROM sponsors WHERE sponsor_id = $sponsor_id");
        $sponsor = $result->fetch_assoc();
        
        $sql = "DELETE FROM sponsors WHERE sponsor_id = $sponsor_id";
        if ($conn->query($sql)) {
            // Delete image file if it exists
            if (!empty($sponsor['sponsor_image_path']) && file_exists('../' . $sponsor['sponsor_image_path'])) {
                unlink('../' . $sponsor['sponsor_image_path']);
            }
            $success_message = "Sponsor deleted successfully!";
        } else {
            $error_message = "Error deleting sponsor: " . $conn->error;
        }
    }
}

// Get all sponsors
$sponsors = $conn->query("SELECT * FROM sponsors ORDER BY sponsor_id ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsor Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <?php include 'navigation.php'; ?>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Sponsor Management</h1>
                <button id="addSponsorBtn" class="bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add New Sponsor
                </button>
            </div>

            <!-- Notifications -->
            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <!-- Add Sponsor Form Modal -->
            <div id="addSponsorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Add New Sponsor</h2>
                        <button class="closeModal text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="sponsor_name" class="block text-gray-700 text-sm font-bold mb-2">Sponsor Name</label>
                            <input type="text" id="sponsor_name" name="sponsor_name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-6">
                            <label for="sponsor_image" class="block text-gray-700 text-sm font-bold mb-2">Sponsor Logo</label>
                            <input type="file" id="sponsor_image" name="sponsor_image" accept="image/*" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Recommended size: 200x100 pixels. PNG or SVG with transparent background preferred.</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="closeModal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg mr-2">
                                Cancel
                            </button>
                            <button type="submit" name="add_sponsor" class="bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-bold py-2 px-4 rounded-lg">
                                Add Sponsor
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Sponsor Form Modal -->
            <div id="editSponsorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Edit Sponsor</h2>
                        <button class="closeModal text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data" id="editSponsorForm">
                        <input type="hidden" id="edit_sponsor_id" name="sponsor_id">
                        <div class="mb-4">
                            <label for="edit_sponsor_name" class="block text-gray-700 text-sm font-bold mb-2">Sponsor Name</label>
                            <input type="text" id="edit_sponsor_name" name="sponsor_name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="edit_sponsor_image" class="block text-gray-700 text-sm font-bold mb-2">Current Logo</label>
                            <img id="current_logo" src="" alt="Current Logo" class="h-20 object-contain mb-2 border p-2 rounded">
                        </div>
                        <div class="mb-6">
                            <label for="edit_sponsor_image" class="block text-gray-700 text-sm font-bold mb-2">New Logo (leave blank to keep current)</label>
                            <input type="file" id="edit_sponsor_image" name="sponsor_image" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Recommended size: 200x100 pixels. PNG or SVG with transparent background preferred.</p>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="closeModal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg mr-2">
                                Cancel
                            </button>
                            <button type="submit" name="update_sponsor" class="bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-bold py-2 px-4 rounded-lg">
                                Update Sponsor
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteSponsorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Delete Sponsor</h2>
                        <button class="closeModal text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <p class="mb-6 text-gray-600">Are you sure you want to delete this sponsor? This action cannot be undone.</p>
                    <form action="" method="POST" id="deleteSponsorForm">
                        <input type="hidden" id="delete_sponsor_id" name="sponsor_id">
                        <div class="flex justify-end">
                            <button type="button" class="closeModal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg mr-2">
                                Cancel
                            </button>
                            <button type="submit" name="delete_sponsor" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                                Delete Sponsor
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sponsors Table -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-red-600 to-orange-500 text-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Sponsor Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Logo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($sponsors->num_rows > 0): ?>
                                <?php while ($sponsor = $sponsors->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?php echo $sponsor['sponsor_id']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo htmlspecialchars($sponsor['sponsor_name']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="../<?php echo $sponsor['sponsor_image_path']; ?>" alt="<?php echo htmlspecialchars($sponsor['sponsor_name']); ?>" class="h-12 object-contain">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button 
                                                class="edit-sponsor bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded mr-2"
                                                data-id="<?php echo $sponsor['sponsor_id']; ?>"
                                                data-name="<?php echo htmlspecialchars($sponsor['sponsor_name']); ?>"
                                                data-image="../<?php echo $sponsor['sponsor_image_path']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button 
                                                class="delete-sponsor bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded"
                                                data-id="<?php echo $sponsor['sponsor_id']; ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No sponsors found. Add your first sponsor using the button above.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const addSponsorBtn = document.getElementById('addSponsorBtn');
        const addSponsorModal = document.getElementById('addSponsorModal');
        const editSponsorModal = document.getElementById('editSponsorModal');
        const deleteSponsorModal = document.getElementById('deleteSponsorModal');
        const closeModalButtons = document.querySelectorAll('.closeModal');
        
        // Open Add Sponsor modal
        addSponsorBtn.addEventListener('click', function() {
            addSponsorModal.classList.remove('hidden');
        });
        
        // Close any modal when clicking close buttons
        closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                addSponsorModal.classList.add('hidden');
                editSponsorModal.classList.add('hidden');
                deleteSponsorModal.classList.add('hidden');
            });
        });
        
        // Edit sponsor buttons functionality
        const editButtons = document.querySelectorAll('.edit-sponsor');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const image = this.getAttribute('data-image');
                
                document.getElementById('edit_sponsor_id').value = id;
                document.getElementById('edit_sponsor_name').value = name;
                document.getElementById('current_logo').src = image;
                
                editSponsorModal.classList.remove('hidden');
            });
        });
        
        // Delete sponsor buttons functionality
        const deleteButtons = document.querySelectorAll('.delete-sponsor');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_sponsor_id').value = id;
                deleteSponsorModal.classList.remove('hidden');
            });
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === addSponsorModal) {
                addSponsorModal.classList.add('hidden');
            }
            if (event.target === editSponsorModal) {
                editSponsorModal.classList.add('hidden');
            }
            if (event.target === deleteSponsorModal) {
                deleteSponsorModal.classList.add('hidden');
            }
        });
    </script>
</body>
</html>