<?php
// Include database connection
require_once '../database/connection.php';

// Query to fetch registration data
$query = "SELECT u.name, u.email, u.phone, u.college_id, u.department, 
          e.event_name, e.category, o.order_date, o.transaction_id, oi.amount 
          FROM orders o
          JOIN order_items oi ON oi.order_id = o.order_id
          JOIN events e ON e.event_id = oi.event_id
          JOIN users u ON u.user_id = o.user_id
          JOIN department d ON d.department_code = e.department_code";

try {
    // Execute query
    $result = $conn->query($query);
    
    // Fetch all results as associative array
    $registrations = [];
    while ($row = $result->fetch_assoc()) {
        $registrations[] = $row;
    }
    
    // Set header to return JSON
    header('Content-Type: application/json');
    
    // Return JSON data
    echo json_encode($registrations);
} catch (Exception $e) {
    // Handle error
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>