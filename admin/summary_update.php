<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../database/connection.php';

// Set header to return JSON
header('Content-Type: application/json');

// Check if required parameters are provided
if (!isset($_POST['user_id']) || !isset($_POST['visited'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit();
}

// Get parameters
$userId = $_POST['user_id'];
$visited = $_POST['visited'];

// Validate visited status
if ($visited !== 'yes' && $visited !== 'no') {
    echo json_encode(['success' => false, 'message' => 'Invalid visited status']);
    exit();
}

try {
    // Prepare statement to update visit status
    $stmt = $conn->prepare("UPDATE users SET visited = ? WHERE user_id = ?");
    $stmt->bind_param("si", $visited, $userId);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update record']);
    }
    
    // Close statement
    $stmt->close();
} catch (Exception $e) {
    // Handle error
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit();
} finally {
    // Close connection
    $conn->close();
}
?>