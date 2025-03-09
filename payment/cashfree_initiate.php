<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../database/connection.php';
require_once '../libraries/vendor/autoload.php';

use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

// Set Cashfree API credentials
Cashfree::$XClientId = "TEST430329ae80e0f32e41a393d78b923034";
Cashfree::$XClientSecret = "TESTaf195616268bd6202eeb3bf8dc458956e7192a85";
Cashfree::$XEnvironment = Cashfree::$SANDBOX;

$x_api_version = "2023-08-01";

$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$cart_query = "SELECT total_amount FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$row = $cart_result->fetch_assoc();
$total_amount = $row['total_amount'];
$stmt->close();

// Generate a unique order ID and Insert into payment_transactions table
$transaction_id = $user_id . "_kalam2025_" . time();
$payment_status = "Initiated";
$payment_time = time();

$payment_transaction_query = "INSERT INTO payment_transactions (transaction_id, amount, user_id, payment_status, payment_time)
                            VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($payment_transaction_query);
$stmt->bind_param("sdisi", $transaction_id, $total_amount, $user_id, $payment_status, $payment_time);
$stmt->execute();

// Create Cashfree order request
$create_order_request = new CreateOrderRequest();
$create_order_request->setOrderAmount($total_amount);
$create_order_request->setOrderCurrency("INR");
$create_order_request->setOrderId($transaction_id);

$customer_details = new CustomerDetails();
$customer_details->setCustomerId((string) $user_id);
$customer_details->setCustomerPhone($user['phone']);
$customer_details->setCustomerEmail($user['email']);
$create_order_request->setCustomerDetails($customer_details);

$order_meta = new OrderMeta();
$order_meta->setReturnUrl("https://bc03-103-208-230-95.ngrok-free.app/kalam/payment/cashfree_verify.php?order_id=$transaction_id");
$create_order_request->setOrderMeta($order_meta);

try {
    $cashfree = new Cashfree();
    $result = $cashfree->PGCreateOrder($x_api_version, $create_order_request);

    // Capture the payment session ID using the getter method
    $orderEntity = $result[0];
    $payment_session_id = $orderEntity->getPaymentSessionId();

    // Auto redirect to Cashfree payment page
    header ("Location: cashfree_checkout.php?payment_session_id=$payment_session_id");
    exit();

} catch (Exception $e) {
    echo 'Exception when calling PGCreateOrder: ', $e->getMessage();
}
?>