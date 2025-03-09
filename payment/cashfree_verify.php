<?php
// File: /kalam/payment/cashfree_return.php

session_start();
require_once '../database/connection.php';
require_once '../libraries/vendor/autoload.php';

use Cashfree\Cashfree;
use Cashfree\Model\PaymentEntity;

if (!isset($_GET['order_id'])) {
    echo "Invalid access!";
    exit();
}

$order_id = $_GET['order_id'];

$x_api_version = "2023-08-01";
Cashfree::$XClientId = "TEST430329ae80e0f32e41a393d78b923034";
Cashfree::$XClientSecret = "TESTaf195616268bd6202eeb3bf8dc458956e7192a85";
Cashfree::$XEnvironment = Cashfree::$SANDBOX;

try {
    $cashfree = new Cashfree();
    $response = $cashfree->PGOrderFetchPayments($x_api_version, $order_id);


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

        $stmt->bind_param("ssssssss", $cf_payment_id, $payment_amount, $payment_mode, $payment_time, $payment_completion_time, $payment_status, $response_data, $order_id);
        $stmt->execute();

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