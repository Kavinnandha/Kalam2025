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
        $create_cart_query = "INSERT INTO cart (user_id, total_amount) VALUES (?, 0)";
        $create_cart_stmt = $conn->prepare($create_cart_query);
        $create_cart_stmt->bind_param("i", $user_id);
        $create_cart_stmt->execute();
        $cart_id = $conn->insert_id;
    } else {
        $cart_id = $cart['cart_id'];
    }
    
    // Check if cart is empty
    $check_cart_items_query = "SELECT COUNT(*) as item_count FROM cart_items WHERE cart_id = ?";
    $check_cart_items_stmt = $conn->prepare($check_cart_items_query);
    $check_cart_items_stmt->bind_param("i", $cart_id);
    $check_cart_items_stmt->execute();
    $cart_items_count = $check_cart_items_stmt->get_result()->fetch_assoc()['item_count'];
    
    // If cart is empty, check if user has order history
    if ($cart_items_count == 0) {
        $order_history_query = "SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?";
        $order_history_stmt = $conn->prepare($order_history_query);
        $order_history_stmt->bind_param("i", $user_id);
        $order_history_stmt->execute();
        $order_count = $order_history_stmt->get_result()->fetch_assoc()['order_count'];
        
        // If no order history, add all 'General' events to cart
        if ($order_count == 0) {
            $general_events_query = "SELECT event_id, registration_fee FROM events WHERE category = 'General'";
            $general_events_stmt = $conn->prepare($general_events_query);
            $general_events_stmt->execute();
            $general_events = $general_events_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            // Add each general event to cart
            if (!empty($general_events)) {
                $insert_cart_item_query = "INSERT INTO cart_items (cart_id, event_id) VALUES (?, ?)";
                $insert_cart_item_stmt = $conn->prepare($insert_cart_item_query);
                
                $total_amount = 0;
                foreach ($general_events as $event) {
                    $insert_cart_item_stmt->bind_param("ii", $cart_id, $event['event_id']);
                    $insert_cart_item_stmt->execute();
                    $total_amount += (float)$event['registration_fee'];
                }
                
                // Update cart total
                $update_cart_query = "UPDATE cart SET total_amount = ? WHERE cart_id = ?";
                $update_cart_stmt = $conn->prepare($update_cart_query);
                $update_cart_stmt->bind_param("di", $total_amount, $cart_id);
                $update_cart_stmt->execute();
            }
        }
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