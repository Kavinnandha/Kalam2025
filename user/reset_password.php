<?php
// Include database connection
require_once '../database/connection.php';

// Function to sanitize input data
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate reset request
function validateResetRequest($conn, $email, $code) {
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM password_resets 
                           WHERE email = ? AND code = ? AND used = 0 
                           AND expires_at > NOW()");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return true;
    }
    return false;
}

// Function to update password
function updatePassword($conn, $email, $code, $newPassword) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update user's password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashedPassword, $email);
        $stmt->execute();
        
        if ($stmt->affected_rows !== 1) {
            throw new Exception("Failed to update user password");
        }
        
        // Mark reset code as used
        $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE email = ? AND code = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        
        if ($stmt->affected_rows !== 1) {
            throw new Exception("Failed to mark reset code as used");
        }
        
        // Commit transaction
        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        return false;
    }
}

// Main code execution
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $email = sanitize($_POST["email"] ?? "");
    $code = sanitize($_POST["code"] ?? "");
    $newPassword = $_POST["new_password"] ?? ""; // Don't sanitize password to allow special chars
    
    // Validate input
    if (empty($email) || empty($code) || empty($newPassword)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format"]);
        exit;
    }
    
    // Validate password length
    if (strlen($newPassword) < 8) {
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters"]);
        exit;
    }
    
    // Verify reset code
    if (!validateResetRequest($conn, $email, $code)) {
        echo json_encode(["success" => false, "message" => "Invalid or expired reset code"]);
        exit;
    }
    
    // Update password
    if (updatePassword($conn, $email, $code, $newPassword)) {
        echo json_encode(["success" => true, "message" => "Password updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update password"]);
    }
} else {
    // Not a POST request
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>