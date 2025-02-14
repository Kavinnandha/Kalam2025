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

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, college_id, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $college_id, $department);

    try {
        $stmt->execute();
        $response['success'] = true;
        $response['message'] = "Registration successful!";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {  // Duplicate entry error code
            if (strpos($e->getMessage(), 'email') !== false) {
                $response['message'] = "This email address is already registered";
            } elseif (strpos($e->getMessage(), 'phone') !== false) {
                $response['message'] = "This phone number is already registered";
            } else {
                $response['message'] = "This record already exists";
            }
        } else {
            $response['message'] = "Error occurred while registering: " . $e->getMessage();
        }
    }

    $stmt->close();
    $conn->close();
} else {
    $response['message'] = "Invalid request method";
}

header('Content-Type: application/json');
echo json_encode($response);
?>