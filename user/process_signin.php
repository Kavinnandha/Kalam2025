<?php
session_start();
require_once '../database/connection.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $email_phone = trim($_POST['email_phone']);
    $password = $_POST['password'];

    if (empty($email_phone) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    // Prepare query to check either email or phone
    $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_phone, $email_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Invalid credentials');
    }

    $user = $result->fetch_assoc();

    // Verify password using sha256
    if (hash('sha256', $password) !== $user['password']) {
        throw new Exception('Invalid credentials');
    }

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful! Redirecting...'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

// Close database connection
$stmt->close();
$conn->close();
?>