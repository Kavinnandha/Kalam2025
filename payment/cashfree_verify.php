<?php

session_start();
require_once '../database/connection.php';
require_once '../libraries/vendor/autoload.php';

use Cashfree\Cashfree;
use Cashfree\Model\PaymentEntity;

if (!isset($_GET['order_id'])) {
    echo "Invalid access!";
    exit();
}

$transaction_id = $_GET['order_id'];

$x_api_version = "2023-08-01";
Cashfree::$XClientId = "926727802c7772f7b5131518e4727629";
Cashfree::$XClientSecret = "cfsk_ma_prod_1d6c34c09b03491ed89f684ec0913dc0_31a7fd0a";
Cashfree::$XEnvironment = Cashfree::$PRODUCTION;

try {
    $cashfree = new Cashfree();
    $response = $cashfree->PGOrderFetchPayments($x_api_version, $transaction_id);

    // Check if response is empty (payment was canceled or not yet processed)
    if (empty($response) || empty($response[0])) {
        // Update the transaction as CANCELLED
        $stmt = $conn->prepare("UPDATE payment_transactions SET payment_status = 'CANCELLED' WHERE transaction_id = ?");
        $stmt->bind_param("s", $transaction_id);
        $stmt->execute();
        
        // Redirect to cart page
        header("Location: ../cart/cart.php?payment=cancelled");
        exit();
    }

    // Extract Payment Details
    $paymentEntity = $response[0][0]; // Access the nested object

    $cf_payment_id = $paymentEntity->getCfPaymentId();
    $order_id = $paymentEntity->getOrderId();
    $payment_status = $paymentEntity->getPaymentStatus();
    $payment_amount = $paymentEntity->getPaymentAmount();
    $payment_time = $paymentEntity->getPaymentTime();
    $payment_completion_time = $paymentEntity->getPaymentCompletionTime();
    $payment_mode = $paymentEntity->getPaymentGroup();

    // Convert object to JSON string for storage
    $response_data = json_encode($paymentEntity);
    
    // Process Payment Status
    if ($payment_status === 'SUCCESS') {
        $stmt = $conn->prepare("UPDATE payment_transactions 
                               SET cf_payment_id = ?, amount = ?, payment_mode = ?, payment_time = ?, 
                                   payment_completion_time = ?, payment_status = ?, response_data = ?
                               WHERE transaction_id = ?");

        $stmt->bind_param("sdssssss", $cf_payment_id, $payment_amount, $payment_mode, $payment_time, 
                         $payment_completion_time, $payment_status, $response_data, $transaction_id);
        $stmt->execute();

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo "User session not found!";
            exit();
        }

        // Start transaction
        $conn->begin_transaction();
        
        $user_id = $_SESSION['user_id'];
        $cart_query = "SELECT cart_id, total_amount FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $conn->rollback();
            echo "Cart not found!";
            exit();
        }
        
        $cart_data = $result->fetch_assoc();
        $cart_id = $cart_data['cart_id'];
        $total_amount = $cart_data['total_amount'];

        // Get total number of products
        $total_items_query = "SELECT COUNT(*) as count FROM cart_items WHERE cart_id = ?";
        $stmt = $conn->prepare($total_items_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_products = $row['count'];

        // Create the order
        $order_query = "INSERT INTO orders (user_id, total_amount, total_products, transaction_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("idis", $user_id, $total_amount, $total_products, $transaction_id);
        $stmt->execute();
        $new_order_id = $conn->insert_id;

        // Update transaction with order ID
        $update_transaction_order_id = "UPDATE payment_transactions SET order_id = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($update_transaction_order_id);
        $stmt->bind_param("is", $new_order_id, $transaction_id);
        $stmt->execute();
        
        // Move cart items to order items
        $move_items_query = "INSERT INTO order_items (order_id, event_id, amount)
                            SELECT ?, ci.event_id, e.registration_fee
                            FROM cart_items ci
                            JOIN events e ON ci.event_id = e.event_id
                            WHERE ci.cart_id = ?";
        $stmt = $conn->prepare($move_items_query);
        $stmt->bind_param("ii", $new_order_id, $cart_id);
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

        header("Location: ../orders/orders.php");
        exit();

    } elseif ($payment_status === 'FAILED') {
        // Payment Failed - Convert response data to JSON string
        $stmt = $conn->prepare("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = ? WHERE transaction_id = ?");
        $stmt->bind_param("ss", $response_data, $transaction_id);
        $stmt->execute();
        
        header("Location: ../cart/cart.php?payment=failed");
        exit();
    } else {
        echo "Payment is still pending.";
        exit();
    }

} catch (Exception $e) {
    // Log the error
    error_log('Exception when calling PGOrderFetchPayments: ' . $e->getMessage());
    
    // Update the transaction as ERROR
    $error_message = $e->getMessage();
    $stmt = $conn->prepare("UPDATE payment_transactions SET payment_status = 'ERROR', response_data = ? WHERE transaction_id = ?");
    $stmt->bind_param("ss", $error_message, $transaction_id);
    $stmt->execute();
    
    // Redirect to cart page with error
    header("Location: ../cart/cart.php?payment=error");
    exit();
}
?>