<?php
session_start();
include '../database/connection.php';

$response = [];

if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'not_logged_in';
} else {
    $user_id = $_SESSION['user_id'];
    $event_id = $_GET['event_id'] ?? null;

    $sql = "SELECT o.order_id 
            FROM orders o 
            JOIN order_items oi ON o.order_id = oi.order_id 
            WHERE o.user_id = ? AND oi.event_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $response['status'] = 'error';
        $response['message'] = 'Failed to prepare statement';
    } else {
        $stmt->bind_param("ii", $user_id, $event_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $response['status'] = 'allowed';
        } else {
            $response['status'] = 'not_purchased';
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
