<?php

// Include database connection
require_once '../database/connection.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// include the PHPMailer files
require '../libraries/vendor/phpmailer/src/Exception.php';
require '../libraries/vendor/phpmailer/src/PHPMailer.php';
require '../libraries/vendor/phpmailer/src/SMTP.php';

// Set header to return JSON response
header('Content-Type: application/json');

// Function to generate a random 6-digit code
function generateVerificationCode() {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

// Process only POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Start or resume session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Get email from the request
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    // Validate email
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    try {
        // Store email in session for verification step
        $_SESSION['reset_email'] = $email;
        
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            // Don't reveal that the email doesn't exist for security reasons
            echo json_encode(['success' => true, 'message' => 'If your email exists in our system, a verification code has been sent']);
            exit;
        }
        
        // Generate verification code
        $verificationCode = generateVerificationCode();
        
        // Set expiration time (30 minutes from now) in 24-hour format
        $expiryTime = date('Y-m-d H:i:s', time() + 1800); // 30 minutes from now
        
        // Adjust expiry time to match the correct format and time
        $adjustedExpiryTime = date('Y-m-d H:i:s', strtotime($expiryTime . ' + 5 hours'));
        
        // Store the verification code in the database
        // First, check if there's an existing reset request
        $stmt = $conn->prepare("SELECT id FROM password_resets WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE password_resets SET code = ?, expires_at = ?, created_at = NOW(), used = 0 WHERE email = ?");
            $stmt->bind_param("sss", $verificationCode, $adjustedExpiryTime, $email);
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO password_resets (email, code, expires_at, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $email, $verificationCode, $adjustedExpiryTime);
        }
        $stmt->execute();
        
        // Prepare email content
        $subject = "Password Reset Verification Code";
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
                .header {
                    background: linear-gradient(135deg, #ff4500, #ff8c00);
                    color: white;
                    padding: 15px;
                    border-radius: 5px 5px 0 0;
                    text-align: center;
                }
                .content {
                    padding: 20px;
                }
                .code {
                    font-size: 24px;
                    font-weight: bold;
                    text-align: center;
                    padding: 15px;
                    margin: 20px 0;
                    background-color: #f5f5f5;
                    border-radius: 5px;
                    letter-spacing: 5px;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 12px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Password Reset Request</h2>
                </div>
                <div class='content'>
                    <p>Hello,</p>
                    <p>We received a request to reset your password. Please use the following verification code to complete the process:</p>
                    <div class='code'>{$verificationCode}</div>
                    <p>This code will expire in 30 minutes.</p>
                    <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
                </div>
                <div class='footer'>
                    <p>This is an automated email. Please do not reply to this message.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Send email with verification code using PHPMailer
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'sietproducts@siet.ac.in';
            $mail->Password = '$!3T@CSE';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('sietproducts@siet.ac.in', 'Kalam 2025');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Verification code has been sent to your email']);
        } catch (Exception $e) {
            error_log("Failed to send password reset email to $email. Mailer Error: {$mail->ErrorInfo}");
            echo json_encode(['success' => false, 'message' => 'Failed to send verification code. Please try again later.']);
        }
        
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}