<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated (add your authentication check here)
// For example: if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

// Get file path from request
$file_path = isset($_GET['file']) ? $_GET['file'] : '';
$action = isset($_GET['action']) ? $_GET['action'] : 'view';

// Validate file path (security check to prevent directory traversal)
if (empty($file_path) || !file_exists($file_path) || strpos(realpath($file_path), realpath('../uploads/')) !== 0) {
    header('HTTP/1.0 404 Not Found');
    echo "File not found or access denied";
    exit;
}

// Check file extension
$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
if ($file_extension !== 'pdf') {
    header('HTTP/1.0 403 Forbidden');
    echo "Only PDF files can be accessed";
    exit;
}

// Get file info
$filename = basename($file_path);

// Set headers based on action
if ($action === 'download') {
    // Force download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
} else {
    // View in browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
}

header('Content-Length: ' . filesize($file_path));
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Clear output buffer
ob_clean();
flush();

// Output file
readfile($file_path);
exit;
?>