<?php
session_start();
require_once '../database/connection.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $email_phone = trim($_POST['email_phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email_phone) || empty($password)) {
        throw new Exception('Please fill in all fields');
    }

    // Prepare query to check either email or phone
    $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Database error: Failed to prepare statement');
    }

    $stmt->bind_param("ss", $email_phone, $email_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Invalid credentials');
    }

    $user = $result->fetch_assoc();

    // Verify password using password_verify()
    if (!password_verify($password, $user['password'])) {
        throw new Exception('Invalid credentials');
    }

    // Set session variables securely
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
    $_SESSION['email'] = htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8');

    // Success response
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

if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
