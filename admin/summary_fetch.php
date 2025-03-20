<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once '../database/connection.php';

try {
    // Query to get registration data with visit status
    $query = "
        SELECT 
            u.user_id, u.phone, u.name, u.college_id, u.visited, e.event_name, e.category, d.department_name, o.total_amount, oi.amount
        FROM 
            orders o
        JOIN
            order_items oi ON o.order_id = oi.order_id
        JOIN
            users u ON o.user_id = u.user_id
        JOIN
            events e ON oi.event_id = e.event_id
        JOIN
            department d ON e.department_code = d.department_code
        WHERE
            e.category != 'General'
        ORDER BY 
            u.phone, u.name
    ";
    
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
} finally {
    // Close connection
    $conn->close();
}
?>