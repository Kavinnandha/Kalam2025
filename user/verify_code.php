<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once '../database/connection.php';

// Function to send JSON response
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Invalid request method');
}

// Get the raw POST data and decode JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if code is provided
if (!isset($data['code']) || empty($data['code'])) {
    sendResponse(false, 'Verification code is required');
}

// Sanitize the code
$code = trim($data['code']);

// Validate code format (6 digits)
if (strlen($code) !== 6 || !ctype_digit($code)) {
    sendResponse(false, 'Invalid verification code format');
}

// Check if email is in session (from password reset request)
if (!isset($_SESSION['reset_email']) || empty($_SESSION['reset_email'])) {
    sendResponse(false, 'Password reset session expired. Please restart the process.');
}

$email = $_SESSION['reset_email'];

try {
    // Prepare the query to check the code
    $query = "SELECT * FROM password_resets 
              WHERE email = ? AND code = ? AND used = 0 
              AND expires_at > NOW() 
              ORDER BY created_at DESC LIMIT 1";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if a valid record was found
    if ($result->num_rows === 0) {
        // Check if the code exists but is expired
        $expiredQuery = "SELECT * FROM password_resets 
                         WHERE email = ? AND code = ? AND used = 0 
                         AND expires_at <= NOW() LIMIT 1";
        
        $expiredStmt = $conn->prepare($expiredQuery);
        $expiredStmt->bind_param("ss", $email, $code);
        $expiredStmt->execute();
        $expiredResult = $expiredStmt->get_result();
        
        if ($expiredResult->num_rows > 0) {
            sendResponse(false, 'This verification code has expired. Please request a new code.');
        } else {
            sendResponse(false, 'Invalid verification code. Please try again.');
        }
    }
    
    $resetData = $result->fetch_assoc();
    $resetId = $resetData['id'];
    
    // Mark the code as used - we'll update this once password is actually reset
    // But we want to flag it as verified
    $_SESSION['reset_verified'] = true;
    $_SESSION['reset_id'] = $resetId;
    
    sendResponse(true, 'Verification successful');
    
} catch (Exception $e) {
    error_log('Verification error: ' . $e->getMessage());
    sendResponse(false, 'An error occurred during verification. Please try again later.');
}
?>