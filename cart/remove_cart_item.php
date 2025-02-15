<?php
session_start();
include "../database/connection.php";
header('Content-Type: application/json');

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }
    
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['cart_item_id'])) {
        throw new Exception('Cart item ID is required');
    }
    
    $cart_item_id = $data['cart_item_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify the cart item belongs to the user
    $verify_query = "SELECT ci.cart_item_id 
                    FROM cart_items ci 
                    JOIN cart c ON ci.cart_id = c.cart_id 
                    WHERE ci.cart_item_id = ? AND c.user_id = ?";
    
    $verify_stmt = $conn->prepare($verify_query);
    $verify_stmt->bind_param("ii", $cart_item_id, $user_id);
    $verify_stmt->execute();
    $verify_stmt->store_result();
    
    if ($verify_stmt->num_rows === 0) {
        throw new Exception('Unauthorized access to cart item');
    }
    
    // Remove the cart item
    $delete_query = "DELETE FROM cart_items WHERE cart_item_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("i", $cart_item_id);
    $delete_stmt->execute();
    
    // Check if deletion was successful
    if ($delete_stmt->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Item removed successfully'
        ]);
    } else {
        throw new Exception('Failed to remove item');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

$conn->close();
?>