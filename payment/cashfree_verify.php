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
Cashfree::$XClientId = "TEST430329ae80e0f32e41a393d78b923034";
Cashfree::$XClientSecret = "TESTaf195616268bd6202eeb3bf8dc458956e7192a85";
Cashfree::$XEnvironment = Cashfree::$SANDBOX;

try {
    $cashfree = new Cashfree();
    $response = $cashfree->PGOrderFetchPayments($x_api_version, $transaction_id);


    // Extract Payment Details
    $paymentEntity = $response[0][0]; // Access the nested object

    $cf_payment_id = $paymentEntity->getCfPaymentId();
    $order_id = $paymentEntity->getOrderId();
    $payment_status = $paymentEntity->getPaymentStatus();
    $payment_amount = $paymentEntity->getPaymentAmount();
    $payment_time = $paymentEntity->getPaymentTime();
    $payment_completion_time = $paymentEntity->getPaymentCompletionTime();
    $payment_mode = $paymentEntity->getPaymentGroup();

    $response_data = $paymentEntity;
    
    // Debug the response (optional)
    // print_r($paymentEntity);

    // Process Payment Status
    if ($payment_status === 'SUCCESS') {
        $stmt = $conn->prepare("UPDATE payment_transactions 
                                       SET cf_payment_id = ?, amount = ?, payment_mode = ?, payment_time = ?, payment_completion_time = ?, payment_status = ?, response_data = ?
                                       WHERE transaction_id = ? ");

        $stmt->bind_param("ssssssss", $cf_payment_id, $payment_amount, $payment_mode, $payment_time, $payment_completion_time, $payment_status, $response_data, $transaction_id);
        $stmt->execute();

        // Start transaction
        $conn->begin_transaction();
        $user_id = $_SESSION['user_id'];
        $cart_query = "SELECT cart_id, total_amount FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_data = $result->fetch_assoc();

        $cart_id = $cart_data['cart_id'];
        $total_amount = $cart_data['total_amount'];

        $total_items_query = "SELECT COUNT(*) FROM cart_items WHERE cart_id = ?";
        $stmt = $conn->prepare($total_items_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_products = $result->fetch_assoc();

        $order_query = "INSERT INTO orders (user_id, total_amount, total_products, transaction_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("idis", $user_id, $total_amount, $total_products, $order_id);
        $stmt->execute();
        $order_id = $conn->insert_id;

        $update_transaction_order_id = "UPDATE payment_transactions SET order_id = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($update_transaction_order_id);
        $stmt->bind_param("ii", $order_id, $transaction_id);
        $stmt->execute();
        
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

        header("Location: ../orders/orders.php");
        exit();

    } elseif ($payment_status === 'FAILED') {
        // Payment Failed
        $conn->query("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = $response_data WHERE transaction_id = '$order_id'");
        header("Location: ../cart/cart.php");
        exit();
    } else {
        echo "Payment is still pending.";
        exit();
    }

} catch (Exception $e) {
    echo 'Exception when calling PGOrderFetchPayments: ', $e->getMessage();
}
?>