<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_name = $_POST['event_name'];
    $event_detail = $_POST['event_detail'];
    $category = $_POST['category'];
    $department_code = $_POST['department_code'];
    $description = $_POST['description'];
    $event_date = $_POST['event_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];
    $contact = $_POST['contact'];
    $registration_fee = $_POST['registration_fee'];
    $fee_description = $_POST['fee_description'];
    
    // Initialize image path
    $image_path = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/";
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = "{$target_dir}{$new_filename}";

        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Validate file type
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type! Only JPG, PNG, and GIF allowed.";
            header("Location: add_event.php");
            exit();
        }

        // Validate file size (2MB max)
        if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "File size exceeds 2MB!";
            header("Location: add_event.php");
            exit();
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if (isset($event['image_path']) && file_exists($event['image_path'])) {
                unlink($event['image_path']);
            }
            $image_path = "/kalam/images/{$new_filename}";
        } else {
            $_SESSION['error'] = "Error uploading file!";
            header("Location: add_event.php");
            exit();
        }
    }

    // Insert into database
    $insert_sql = "INSERT INTO events (event_name, event_detail, category, department_code, description,
                   event_date, start_time, end_time, venue, registration_fee, contact, image_path, fee_description) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssssssssss", 
        $event_name, 
        $event_detail, 
        $category, 
        $department_code, 
        $description, 
        $event_date, 
        $start_time, 
        $end_time, 
        $venue, 
        $registration_fee,
        $contact, 
        $image_path,
        $fee_description
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Event created successfully!";
        header("Location: " . (isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'));
        exit();
    } else {
        $_SESSION['error'] = "Error creating event: {$conn->error}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-tr from-blue-50 via-white to-purple-50 min-h-screen">
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Create New Event</h1>
                        <p class="mt-2 text-gray-600">Add a new event to your calendar</p>
                    </div>
                    <a href="<?php echo isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'; ?>" 
                       class="flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Events
                    </a>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="mt-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                        <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <!-- Image Upload Section -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Event Image</label>
                            <div class="flex items-center justify-center w-full">
                                <div class="w-full h-64 relative border-2 border-dashed border-gray-300 rounded-lg">
                                    <img id="preview" class="hidden w-full h-full object-cover rounded-lg">
                                    <div id="placeholder" class="absolute inset-0 flex flex-col items-center justify-center">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                        <p class="text-gray-500">Click or drag to upload image</p>
                                        <p class="text-sm text-gray-400 mt-2">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                    <input type="file" name="image" accept="image/*" 
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                           onchange="previewImage(this)">
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                                <input type="text" name="event_name" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                                <select name="category" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Select Category</option>
                                    <option value="Technical">Technical</option>
                                    <option value="Non-Technical">Non-Technical</option>
                                    <option value="General">General</option>
                                    <option value="Workshop">Workshop</option>
                                    <option value="Hackathon">Hackathon</option>
                                    <option value="Media">Media</option>
                                    <option value="Culturals">Culturals</option>
                                    <option value="ESAT-Gaming">ESAT-Gaming</option>
                                    <option value="Tech-Hub">Tech-Hub</option>
                                    <option value="NCC">NCC</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                                <?php if (isset($_SESSION['department_code']) && !empty($_SESSION['department_code'])): ?>
                                    
                                    <input type="hidden" name="department_code" value="<?php echo $_SESSION['department_code']; ?>">
                                    <p class="text-gray-700"><?php
                                        $dept_sql = "SELECT department_name FROM department WHERE department_code = ?";
                                        $stmt = $conn->prepare($dept_sql);
                                        $stmt->bind_param("s", $_SESSION['department_code']);
                                        $stmt->execute();
                                        $stmt->bind_result($department_name);
                                        $stmt->fetch();
                                        echo $department_name;
                                        $stmt->close();
                                    ?></p>
                                <?php else: ?>
                                    <select name="department_code" required
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        <option value="">Select Department</option>
                                        <?php
                                        $dept_sql = "SELECT department_code, department_name FROM department";
                                        $dept_result = $conn->query($dept_sql);
                                        if ($dept_result->num_rows > 0) {
                                            while ($row = $dept_result->fetch_assoc()) {
                                                echo '<option value="' . $row['department_code'] . '">' . $row['department_name'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Fee</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₹</span>
                                    <input type="number" name="registration_fee" step="0.1"
                                           class="w-full rounded-lg border-gray-300 pl-8 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Fee Description</label>
                                <select name="fee_description" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                    <option value="">Select Fee Type</option>
                                    <option value="Individual">Individual</option>
                                    <option value="Team">Team</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date and Time Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date</label>
                                <input type="date" name="event_date" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                                <input type="time" name="start_time" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                                <input type="time" name="end_time" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                        </div>

                        <!-- Venue and Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Venue</label>
                                <input type="text" name="venue" required
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact No.</label>
                                <input type="text" name="contact"
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tag Line</label>
                            <input type="text" name="event_detail" required
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4" required
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="<?php echo isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'; ?>"
                               class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const startTime = document.querySelector('input[name="start_time"]').value;
            const endTime = document.querySelector('input[name="end_time"]').value;
            const eventDate = new Date(document.querySelector('input[name="event_date"]').value);
            const today = new Date();
            
            // Set hours to 0 for date comparison
            today.setHours(0, 0, 0, 0);
            
            if (eventDate < today) {
                e.preventDefault();
                alert('Event date cannot be in the past');
                return;
            }

            if (startTime >= endTime) {
                e.preventDefault();
                alert('End time must be after start time');
                return;
            }
        });
    </script>
</body>
</html>