<?php
session_start();
require_once '../database/connection.php';
require_once '../libraries/vendor/autoload.php';

// Fix the import paths based on the actual file structure
use PhonePe\payments\v1\PhonePePaymentClient;
use phonepe\Env;

require_once __DIR__ . '/../libraries/vendor/phonepe/src/phonepe/sdk/pg/Env.php';

// PhonePe API credentials
$merchantId = "M22DZIHTE7XA8";
$saltKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725";
$saltIndex = "1";
$env = Env::PRODUCTION; // Use Env::PRODUCTION for production
$shouldPublishEvents = true;

// Initialize PhonePe Payment Client
$phonePePaymentsClient = new PhonePePaymentClient($merchantId, $saltKey, $saltIndex, $env, $shouldPublishEvents);

// Handle callback validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the callback data
    $postData = file_get_contents('php://input');
    $xVerify = $_SERVER['HTTP_X_VERIFY'] ?? '';

    // Verify the callback
    $isValid = $phonePePaymentsClient->verifyCallback($postData, $xVerify);

    if ($isValid) {
        // Decode the response
        $responseData = json_decode($postData, true);
        $responseBase64 = $responseData['response'] ?? '';
        $decodedResponse = json_decode(base64_decode($responseBase64), true);

        if (isset($decodedResponse['data'])) {
            $data = $decodedResponse['data'];
            $merchantTransactionId = $data['merchantTransactionId'] ?? '';
            $transactionId = $data['transactionId'] ?? '';
            $amount = $data['amount'] ?? 0;
            $state = $data['state'] ?? '';

            // Convert amount from paise to rupees
            $amount = $amount / 100;

            // Update payment status in database
            $sql = "UPDATE payment_transactions SET 
                    payment_status = ?, 
                    phonepe_transaction_id = ?, 
                    payment_time = ? 
                    WHERE transaction_id = ?";

            $stmt = $conn->prepare($sql);
            $payment_time = time();
            $stmt->bind_param("ssis", $state, $transactionId, $payment_time, $merchantTransactionId);
            $stmt->execute();

            // If payment was successful, update order status
            if ($state === 'COMPLETED') {
                // Extract user_id from merchantTransactionId (format: user_id_kalam2025_timestamp)
                $parts = explode('_', $merchantTransactionId);
                $user_id = $parts[0];

                // Move cart items to orders
                $order_id = uniqid('ORD');

                // Get cart items
                $cart_query = "SELECT p.product_id, p.product_name, c.quantity, c.price 
                              FROM cart_items c 
                              JOIN products p ON c.product_id = p.product_id 
                              WHERE c.user_id = ?";
                $stmt = $conn->prepare($cart_query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $cart_items = $stmt->get_result();

                // Insert order
                $order_sql = "INSERT INTO orders (order_id, user_id, total_amount, payment_id, order_status, order_date) 
                             VALUES (?, ?, ?, ?, 'Confirmed', ?)";
                $stmt = $conn->prepare($order_sql);
                $order_date = date('Y-m-d H:i:s');
                $stmt->bind_param("sidss", $order_id, $user_id, $amount, $merchantTransactionId, $order_date);
                $stmt->execute();

                // Insert order items
                while ($item = $cart_items->fetch_assoc()) {
                    $order_item_sql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                                      VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($order_item_sql);
                    $stmt->bind_param("sisdd", $order_id, $item['product_id'], $item['product_name'], $item['quantity'], $item['price']);
                    $stmt->execute();
                }

                // Clear cart
                $clear_cart_sql = "DELETE FROM cart_items WHERE user_id = ?";
                $stmt = $conn->prepare($clear_cart_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                $clear_cart_total_sql = "DELETE FROM cart WHERE user_id = ?";
                $stmt = $conn->prepare($clear_cart_total_sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
            }

            // Return success response
            http_response_code(200);
            echo json_encode(['status' => 'success']);
            exit();
        }
    }

    // If validation fails
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid callback']);
    exit();
}

// Handle redirect from PhonePe
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $merchantTransactionId = $_GET['merchantTransactionId'] ?? '';

    // Validate merchantTransactionId before proceeding
    if (empty($merchantTransactionId)) {
        $_SESSION['payment_error'] = "Missing transaction ID";
        header("Location: ../payment/payment_failed.php");
        exit();
    }

    // Check payment status
    try {
        $checkStatus = $phonePePaymentsClient->statusCheck($merchantTransactionId);
        $state = $checkStatus->getState();

        // Process based on payment status
        if ($state === 'COMPLETED') {
            $_SESSION['payment_success'] = true;

            // Update transaction in database
            $stmt = $conn->prepare("UPDATE payment_transactions
                           SET cf_payment_id = ?, amount = ?, payment_mode = ?, payment_time = ?,
                               payment_completion_time = ?, payment_status = ?, response_data = ?
                           WHERE transaction_id = ?");
            $stmt->bind_param(
                "sdssssss",
                $cf_payment_id,
                $payment_amount,
                $payment_mode,
                $payment_time,
                $payment_completion_time,
                $payment_status,
                $response_data,
                $transaction_id
            );
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

            // Redirect to orders page
            header("Location: ../orders/orders.php");
            exit();
        } else {
            // Payment failed
            $_SESSION['payment_error'] = "Payment failed. Status: " . $state;

            // Update transaction status
            $response_data = json_encode(['status' => $state, 'message' => 'Payment failed']);
            $stmt = $conn->prepare("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = ? WHERE transaction_id = ?");
            $stmt->bind_param("ss", $response_data, $transaction_id);
            $stmt->execute();

            // Redirect to cart page
            header("Location: ../cart/cart.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['payment_error'] = "Error checking payment status: " . $e->getMessage();

        // Update transaction status
        $response_data = json_encode(['status' => 'ERROR', 'message' => $e->getMessage()]);
        $stmt = $conn->prepare("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = ? WHERE transaction_id = ?");
        $stmt->bind_param("ss", $response_data, $transaction_id);
        $stmt->execute();

        // Redirect to cart page
        header("Location: ../cart/cart.php");
        exit();
    }

}

// If neither POST nor valid GET request
$_SESSION['payment_error'] = "Invalid request";
//header("Location: ../payment/payment_failed.php");
exit();
?>