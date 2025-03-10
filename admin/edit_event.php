<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../database/connection.php';

if (!isset($_GET['event_id'])) {
    header("Location: ");
    exit();
}

$event_id = $_GET['event_id'];

// Fetch event details
$stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    header("Location: " . (isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'));
    exit();
}

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
    $registration_fee = $_POST['registration_fee'];
    $contact = $_POST['contact'];
    $fee_description = $_POST['fee_description'];

    // Handle file upload
    $image_path = $event['image_path'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/";
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = "{$target_dir}{$new_filename}";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type! Only JPG, PNG, and GIF allowed.";
            header("Location: " . (isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'));
            exit();
        }
        // Validate file size (2MB max)
        if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "File size exceeds 2MB!";
            header("Location: edit_event.php?event_id=$event_id");
            exit();
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            if ($event['image_path'] && file_exists($event['image_path'])) {
            unlink($event['image_path']);
            }
            $image_path = "/kalam/images/{$new_filename}";
        }
    }

    $update_sql = "UPDATE events SET 
                   event_name = ?, 
                   event_detail = ?, 
                   category = ?, 
                   department_code = ?,
                   description = ?, 
                   event_date = ?, 
                   start_time = ?, 
                   end_time = ?, 
                   venue = ?, 
                   registration_fee = ?, 
                   image_path = ?,
                   contact = ?,
                   fee_description = ?
                   WHERE event_id = ?";

    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param(
        "sssisssssdsssi",
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
        $image_path,
        $contact,
        $fee_description,
        $event_id
    );

    if ($update_stmt->execute()) {
        $_SESSION['message'] = "Event updated successfully!";
        
        header("Location: " . (isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Edit Event</h1>
                    <p class="text-gray-600 mt-2">Update event details and information</p>
                </div>
                <a href="<?php echo isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'; ?>" class="flex items-center px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Events
                </a>
            </div>
            <!-- Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <!-- Main Form Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8">
                    <form action="" method="POST" enctype="multipart/form-data" class="space-y-8">
                        <!-- Image Section -->
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <div class="w-full md:w-1/3">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Event Image</label>
                                <div class="relative h-64 w-full rounded-lg overflow-hidden bg-gray-100 shadow-inner">
                                    <?php if ($event['image_path']): ?>
                                        <img src="<?php echo htmlspecialchars($event['image_path']); ?>"
                                            alt="Current event image" class="h-full w-full object-cover" id="currentImage">
                                    <?php else: ?>
                                        <div class="h-full w-full flex items-center justify-center bg-gray-200">
                                            <i class="fas fa-image text-4xl text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="image" accept="image/*" class="mt-4 w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    cursor-pointer" onchange="previewImage(this)">
                            </div>

                            <!-- Basic Info Section -->
                            <div class="w-full md:w-2/3 space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Name</label>
                                    <input type="text" name="event_name"
                                        value="<?php echo htmlspecialchars($event['event_name']); ?>" required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                                        <select name="category" required
                                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                            <option value="Technical" <?php echo $event['category'] == 'Technical' ? 'selected' : ''; ?>>Technical</option>
                                            <option value="Non-Technical" <?php echo $event['category'] == 'Non-Technical' ? 'selected' : ''; ?>>Non-Technical</option>
                                            <option value="General" <?php echo $event['category'] == 'General' ? 'selected' : ''; ?>>General</option>
                                            <option value="Workshop" <?php echo $event['category'] == 'Workshop' ? 'selected' : ''; ?>>Workshop</option>
                                            <option value="Hackathon" <?php echo $event['category'] == 'Hackathon' ? 'selected' : ''; ?>>Hackathon</option>
                                            <option value="Media" <?php echo $event['category'] == 'Media' ? 'selected' : ''; ?>>Media</option>
                                            <option value="Culturals" <?php echo $event['category'] == 'Culturals' ? 'selected' : ''; ?>>Culturals</option>
                                            <option value="Culturals" <?php echo $event['category'] == 'ESAT-Gaming' ? 'selected' : ''; ?>>ESAT-Gaming</option>
                                            <option value="Culturals" <?php echo $event['category'] == 'Tech_Hub' ? 'selected' : ''; ?>>Tech-Hub</option>
                                            <option value="Culturals" <?php echo $event['category'] == 'NCC' ? 'selected' : ''; ?>>NCC</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Registration Fee</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">₹</span>
                                            <input type="number" name="registration_fee"
                                                value="<?php echo $event['registration_fee']; ?>" step="0.1"
                                                class="w-full rounded-lg border-gray-300 pl-8 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Fee Type</label>
                                        <select name="fee_description" required
                                            class="mb-5 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                            <option value="Individual" <?php echo $event['fee_description'] == 'Individual' ? 'selected' : ''; ?>>Individual</option>
                                            <option value="Team" <?php echo $event['fee_description'] == 'Team' ? 'selected' : ''; ?>>Team</option>
                                        </select>
                                    </div>
                                    
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
                            </div>
                        </div>

                        <!-- Date and Time Section -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date</label>
                                <input type="date" name="event_date" value="<?php echo $event['event_date']; ?>"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                                <input type="time" name="start_time" value="<?php echo $event['start_time']; ?>"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                                <input type="time" name="end_time" value="<?php echo $event['end_time']; ?>"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>
                        </div>

                        <!-- Venue and Details Section -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Venue</label>
                                    <input type="text" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>"
                                        required
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact No.</label>
                                    <input type="text" name="contact" value="<?php echo htmlspecialchars($event['contact']); ?>"
                                        
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tagline</label>
                                <input type="text" name="event_detail"
                                    value="<?php echo htmlspecialchars($event['event_detail']); ?>" required
                                    maxlength="250"
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Details and Guidelines</label>
                                <textarea name="description" rows="4" required
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"><?php echo htmlspecialchars($event['description']); ?></textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="<?php echo isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] == 'yes' ? 'manage_events_admin.php' : 'manage_events.php'; ?>"
                                class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('currentImage').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const startTime = document.querySelector('input[name="start_time"]').value;
            const endTime = document.querySelector('input[name="end_time"]').value;

            if (startTime >= endTime) {
                e.preventDefault();
                alert('End time must be after start time');
            }
        });
    </script>
</body>
</html>