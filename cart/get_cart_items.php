<?php
session_start();
header('Content-Type: application/json');

include '../database/connection.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }
    
    $user_id = $_SESSION['user_id'];
    
    // First get the user's cart
    $cart_query = "SELECT cart_id FROM cart WHERE user_id = ?";
    $cart_stmt = $conn->prepare($cart_query);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    $cart = $cart_result->fetch_assoc();
    
    if (!$cart) {
        // Create a new cart if one doesn't exist
        $create_cart_query = "INSERT INTO cart (user_id) VALUES (?)";
        $create_cart_stmt = $conn->prepare($create_cart_query);
        $create_cart_stmt->bind_param("i", $user_id);
        $create_cart_stmt->execute();
        $cart_id = $conn->insert_id;
    } else {
        $cart_id = $cart['cart_id'];
    }
    
    // Get cart items with event details
    $items_query = "SELECT 
        ci.cart_item_id,
        e.event_id,
        e.event_name,
        e.event_detail,
        e.category,
        e.description,
        e.event_date,
        e.start_time,
        e.end_time,
        e.venue,
        e.registration_fee,
        e.image_path,
        d.department_name
    FROM cart_items ci
    JOIN events e ON ci.event_id = e.event_id
    LEFT JOIN department d ON e.department_code = d.department_code
    WHERE ci.cart_id = ?";
    
    $items_stmt = $conn->prepare($items_query);
    $items_stmt->bind_param("i", $cart_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    $items = $items_result->fetch_all(MYSQLI_ASSOC);
    
    // Format dates and times
    foreach ($items as &$item) {
        $item['event_date'] = date('F j, Y', strtotime($item['event_date']));
        $item['start_time'] = date('g:i A', strtotime($item['start_time']));
        $item['end_time'] = date('g:i A', strtotime($item['end_time']));
        $item['registration_fee'] = number_format((float)$item['registration_fee'], 2, '.', '');
    }
    
    echo json_encode([
        'success' => true,
        'data' => $items
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>