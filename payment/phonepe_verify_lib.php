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

// Handle redirect from PhonePe
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $merchantTransactionId = $_GET['merchantTransactionId'] ?? '';

    // Validate merchantTransactionId before proceeding
    if (empty($merchantTransactionId)) {
        $_SESSION['payment_error'] = "Missing transaction ID";
        header("Location: ../cart/cart.php");
        exit();
    }

    // Check payment status
    try {
        // Get the status check response
        $checkStatusResponse = $phonePePaymentsClient->statusCheck($merchantTransactionId);
        
        // Get the state which is the only method we know exists
        $state = $checkStatusResponse->getState();
        
        // Log all available methods for future debugging
        ob_start();
        var_dump(get_class_methods($checkStatusResponse));
        $methods = ob_get_clean();
        
        // Create basic response data from what we know
        $response_data = json_encode([
            'state' => $state,
            'merchantTransactionId' => $merchantTransactionId,
            'debug_info' => $methods
        ]);
        
        // Set transaction ID
        $transaction_id = $merchantTransactionId;
        
        // Set default values since we can't access the API response directly
        $cf_payment_id = $merchantTransactionId;
        $payment_amount = 0; // Will need to be updated from your database
        $payment_mode = 'PhonePe';
        $payment_time = date('Y-m-d H:i:s');
        $payment_completion_time = $payment_time;
        $payment_status = $state;
        
        // Try to get the amount from the existing payment_transactions table
        $stmt = $conn->prepare("SELECT amount FROM payment_transactions WHERE transaction_id = ?");
        $stmt->bind_param("s", $transaction_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $payment_amount = $row['amount'];
        }

        // Process based on payment status
        if ($state === 'COMPLETED') {
            $_SESSION['payment_success'] = true;

            // Update transaction in database
            $stmt = $conn->prepare("UPDATE payment_transactions
                           SET cf_payment_id = ?, payment_mode = ?, payment_time = ?,
                               payment_completion_time = ?, payment_status = ?, response_data = ?
                           WHERE transaction_id = ?");
            $stmt->bind_param(
                "sssssss",
                $cf_payment_id,
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
header("Location: ../cart/cart.php");
exit();
?>