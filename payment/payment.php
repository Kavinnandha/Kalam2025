<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}
include '../database/connection.php';

header('Content-Type: application/json');
    
    try {
        // Start transaction
        $conn->begin_transaction();
        $user_id = $_SESSION['user_id'];
        $cart_query = "SELECT total_amount FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();
        $row = $cart_result->fetch_assoc();
        $total_amount = $row['total_amount'];
        $stmt->close();
      //  if (/*response from the api is success (that is payment is successful */) {
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
     //   }
        
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while processing your payment'
        ]);
    }
?>