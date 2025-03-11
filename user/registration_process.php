<?php
include '../database/connection.php';

$response = array(
    'success' => false,
    'message' => ''
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and trim input data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $college_id = trim($_POST['college_id']);
    $department = trim($_POST['department']);
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, college_id, department, password) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $college_id, $department, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Registration successful!";
    } else {
        // Check for MySQL Error
        if ($stmt->errno == 1062) {
            // Duplicate entry - Check which field caused the issue
            if (strpos($stmt->error, 'email') !== false) {
                $response['message'] = "This email address is already registered.";
            } elseif (strpos($stmt->error, 'phone') !== false) {
                $response['message'] = "This phone number is already registered.";
            } else {
                $response['message'] = "This record already exists.";
            }
        } else {
            // Other SQL errors
            $response['message'] = "Error occurred while registering: " . $stmt->errno . " - " . $stmt->error;
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    $response['message'] = "Invalid request method.";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
