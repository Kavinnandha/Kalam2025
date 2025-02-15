<?php
// process_payment.php
include '../database/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cart_id = $_GET['cart_id'];
    
    // Here you would integrate with your actual payment gateway
    // For demonstration, we'll simulate a successful payment
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Get cart details
        $cart_query = "SELECT * FROM cart WHERE cart_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        $cart_data = $cart_result->fetch_assoc();
        
        // Create order
        $order_query = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("id", $cart_data['user_id'], $cart_data['total_amount']);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // Move cart items to order items
        $move_items_query = "INSERT INTO order_items (order_id, event_id, amount)
                            SELECT ?, ci.event_id, e.registration_fee
                            FROM cart_items ci
                            JOIN events e ON ci.event_id = e.event_id
                            WHERE ci.cart_id = ?";
        $stmt = $conn->prepare($move_items_query);
        $stmt->bind_param("ii", $order_id, $cart_id);
        $stmt->execute();
        
        // Delete cart items
        $delete_items_query = "DELETE FROM cart_items WHERE cart_id = ?";
        $stmt = $conn->prepare($delete_items_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        
        // Delete cart
        $delete_cart_query = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $conn->prepare($delete_cart_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'order_id' => $order_id
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while processing your payment'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>