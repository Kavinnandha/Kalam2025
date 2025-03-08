<?php
// Start session if not already started
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit(); 
}
include '../database/connection.php';

// Set headers for JSON response
header('Content-Type: application/json');

// PhonePe API Configuration
$merchantId = "PGTESTPAYUAT";
$saltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
$saltIndex = "1";
$apiEndpoint = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/";



// Function to verify payment status with PhonePe
function verifyPaymentStatus($transactionId) {
    global $merchantId, $saltKey, $saltIndex, $apiEndpoint;
    
    // Create the checksum: SHA256(merchantId + "/" + transactionId + "/pg/v1/status" + salt key) + ### + salt index
    $checksum = hash('sha256', $merchantId . "/" . $transactionId . "/pg/v1/status" . $saltKey) . "###" . $saltIndex;
    
    // Initialize cURL session
    $curl = curl_init();
    
    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiEndpoint . $merchantId . "/" . $transactionId,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-VERIFY: " . $checksum,
            "X-MERCHANT-ID: " . $merchantId
        ],
    ]);
    
    // Execute the request
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        return [
            'success' => false,
            'message' => "cURL Error: " . $err
        ];
    } else {
        return json_decode($response, true);
    }
}

// Function to update transaction status in database
function updateTransactionStatus($transactionId, $status, $responseData = null) {
    global $conn;
    
    $updated_at = date('Y-m-d H:i:s');
    $response_json = $responseData ? json_encode($responseData) : null;
    
    $query = "UPDATE payment_transactions 
              SET status = ?, response_data = ?, updated_at = ? 
              WHERE transaction_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $status, $response_json, $updated_at, $transactionId);
    $stmt->execute();
    $stmt->close();
}

// Function to process successful order
function processSuccessfulOrder($userId, $cartId, $transactionId) {
    global $conn;
    
    // Get cart data
    $cart_query = "SELECT total_amount FROM cart WHERE cart_id = ? AND user_id = ?";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("ii", $cartId, $userId);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    
    if ($cart_result->num_rows === 0) {
        throw new Exception("Cart not found");
    }
    
    $cart_data = $cart_result->fetch_assoc();
    $stmt->close();
    
    // Create order
    $order_query = "INSERT INTO orders (user_id, total_amount, transaction_id, order_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("ids", $userId, $cart_data['total_amount'], $transactionId);
    $stmt->execute();
    $order_id = $conn->insert_id;
    $stmt->close();
    
    // Move cart items to order items
    $move_items_query = "INSERT INTO order_items (order_id, event_id, amount)
                        SELECT ?, ci.event_id, e.registration_fee
                        FROM cart_items ci
                        JOIN events e ON ci.event_id = e.event_id
                        WHERE ci.cart_id = ?";
    $stmt = $conn->prepare($move_items_query);
    $stmt->bind_param("ii", $order_id, $cartId);
    $stmt->execute();
    $stmt->close();
    
    // Delete cart items
    $delete_items_query = "DELETE FROM cart_items WHERE cart_id = ?";
    $stmt = $conn->prepare($delete_items_query);
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $stmt->close();
    
    // Delete cart
    $delete_cart_query = "DELETE FROM cart WHERE cart_id = ?";
    $stmt = $conn->prepare($delete_cart_query);
    $stmt->bind_param("i", $cartId);
    $stmt->execute();
    $stmt->close();
    
    return $order_id;
}

// Main execution flow
try {
    // Start transaction
    $conn->begin_transaction();
    
    $user_id = $_SESSION['user_id'];
    
    // Check if we have a transaction ID and cart ID in session
    if (!isset($_SESSION['phonepe_transaction_id']) || !isset($_SESSION['pending_cart_id'])) {
        throw new Exception("Transaction information missing");
    }
    
    $transactionId = $_SESSION['phonepe_transaction_id'];
    $cartId = $_SESSION['pending_cart_id'];
    
    // Verify payment status with PhonePe
    $paymentStatus = verifyPaymentStatus($transactionId);
    
    if ($paymentStatus['success']) {
        $code = $paymentStatus['code'];
        $paymentState = $paymentStatus['data']['state'] ?? '';
        
        // Check if payment is successful (PAYMENT_SUCCESS)
        if ($code === "PAYMENT_SUCCESS" && $paymentState === "COMPLETED") {
            // Update transaction status
            updateTransactionStatus($transactionId, "SUCCESS", $paymentStatus);
            
            // Process the order
            $orderId = processSuccessfulOrder($user_id, $cartId, $transactionId);
            
            // Clear session variables
            unset($_SESSION['phonepe_transaction_id']);
            unset($_SESSION['pending_cart_id']);
            
            // Redirect to success page with order ID
            echo json_encode([
                'success' => true,
                'orderId' => $orderId,
                'message' => 'Payment successful! Your order has been placed.'
            ]);
        } 
        // Payment is pending or in progress
        else if ($code === "PAYMENT_PENDING" || $paymentState === "INITIATED") {
            updateTransactionStatus($transactionId, "PENDING", $paymentStatus);
            
            echo json_encode([
                'success' => false,
                'pending' => true,
                'message' => 'Your payment is being processed. We will update you once confirmed.'
            ]);
        } 
        // Payment failed
        else {
            updateTransactionStatus($transactionId, "FAILED", $paymentStatus);
            
            echo json_encode([
                'success' => false,
                'message' => 'Payment failed. Please try again.'
            ]);
        }
    } else {
        // Failed to get status from PhonePe
        updateTransactionStatus($transactionId, "VERIFICATION_FAILED", $paymentStatus);
        
        echo json_encode([
            'success' => false,
            'message' => 'Failed to verify payment status. Please contact support.'
        ]);
    }
    
    // Commit transaction
    $conn->commit();
    
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>