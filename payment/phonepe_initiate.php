<?php
session_start();
require_once '../database/connection.php';

// PhonePe API credentials
$merchantId = "M22DZIHTE7XA8";
$apiKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725";
$saltKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725"; // Same as API key in your case
$saltIndex = 1;

// First, check if transaction ID is provided in the URL
if (isset($_GET['merchantTransactionId'])) {
    $transaction_id = $_GET['merchantTransactionId'];
} else {
    // If not, check if PhonePe provides callback data
    $input = file_get_contents('php://input');
    $callback_data = json_decode($input, true);
    
    if ($callback_data && isset($callback_data['merchantTransactionId'])) {
        $transaction_id = $callback_data['merchantTransactionId'];
    } else {
        echo "Invalid access! Transaction ID not found.";
        exit();
    }
}

// Step 1: Get OAuth Bearer Token
function getAccessToken($merchantId, $apiKey) {
    // Endpoint for Auth API
    $authEndpoint = "https://api.phonepe.com/apis/hermes-auth/v1/token";
    
    // Create request payload for auth
    $authPayload = [
        "grantType" => "client_credentials",
        "clientId" => $merchantId,
        "clientSecret" => $apiKey
    ];
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $authEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($authPayload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "accept: application/json"
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        error_log("cURL Error in auth request: " . $err);
        return null;
    }
    
    $responseData = json_decode($response, true);
    
    if (isset($responseData['access_token'])) {
        return $responseData['access_token'];
    } else {
        error_log("Auth Error: " . json_encode($responseData));
        return null;
    }
}

// Get access token
$accessToken = getAccessToken($merchantId, $apiKey);

if (!$accessToken) {
    echo "Failed to authenticate with PhonePe API";
    exit();
}

// Step 2: Verify the payment status with PhonePe using the access token
$apiEndpoint = "https://api.phonepe.com/apis/pg/checkout/v2/order/{$merchantId}/status?details=true";

// Prepare API request with OAuth token
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $apiEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: O-Bearer " . $accessToken,
        "X-MERCHANT-ID: " . $merchantId // Include this if you're a TSP/Partner
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
    exit();
}

$responseData = json_decode($response, true);
$response_json = json_encode($responseData);

// Log the response for debugging
error_log("PhonePe Response: " . $response_json);

// Check payment status
if (isset($responseData['state'])) {
    $payment_status = $responseData['state'];
    $payment_amount = $responseData['amount'] / 100; // Convert from paise to rupees
    
    // Get transaction ID from PhonePe
    $phonepe_transaction_id = null;
    if (!empty($responseData['paymentDetails']) && isset($responseData['paymentDetails'][0]['transactionId'])) {
        $phonepe_transaction_id = $responseData['paymentDetails'][0]['transactionId'];
        
        // Get payment mode if available
        $payment_mode = 'UNKNOWN';
        if (isset($responseData['paymentDetails'][0]['paymentMode'])) {
            $payment_mode = $responseData['paymentDetails'][0]['paymentMode'];
        }
    }
    
    $payment_time = time(); // Current timestamp as payment time
    $payment_completion_time = time(); // Current timestamp as completion time
    
    if ($payment_status === 'COMPLETED') {
        // Update payment_transactions table
        $stmt = $conn->prepare("UPDATE payment_transactions 
                               SET cf_payment_id = ?, amount = ?, payment_mode = ?, 
                               payment_time = ?, payment_completion_time = ?, 
                               payment_status = 'SUCCESS', response_data = ?
                               WHERE transaction_id = ?");
        
        $stmt->bind_param("sssssss", $phonepe_transaction_id, $payment_amount, 
                         $payment_mode, $payment_time, $payment_completion_time, 
                         $response_json, $transaction_id);
        $stmt->execute();

        // Start transaction to process order
        $conn->begin_transaction();
        
        $user_id = $_SESSION['user_id'];
        if (!isset($user_id)) {
            // Get user_id from transaction if session is not available
            $get_user_query = "SELECT user_id FROM payment_transactions WHERE transaction_id = ?";
            $stmt = $conn->prepare($get_user_query);
            $stmt->bind_param("s", $transaction_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            $user_id = $user_data['user_id'];
        }
        
        $cart_query = "SELECT cart_id, total_amount FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_data = $result->fetch_assoc();

        $cart_id = $cart_data['cart_id'];
        $total_amount = $cart_data['total_amount'];

        $total_items_query = "SELECT COUNT(*) as count FROM cart_items WHERE cart_id = ?";
        $stmt = $conn->prepare($total_items_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $total_products_row = $result->fetch_assoc();
        $total_products = $total_products_row['count'];

        // Create order
        $order_query = "INSERT INTO orders (user_id, total_amount, total_products, transaction_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($order_query);
        $stmt->bind_param("idis", $user_id, $total_amount, $total_products, $transaction_id);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Update payment_transactions with order_id
        $update_transaction_order_id = "UPDATE payment_transactions SET order_id = ? WHERE transaction_id = ?";
        $stmt = $conn->prepare($update_transaction_order_id);
        $stmt->bind_param("is", $order_id, $transaction_id);
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

        // Redirect to orders page
        header("Location: ../orders/orders.php");
        exit();
    } elseif ($payment_status === 'FAILED' || $payment_status === 'CANCELLED') {
        // Update payment status as failed
        $conn->query("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = '$response_json' WHERE transaction_id = '$transaction_id'");
        header("Location: ../cart/cart.php");
        exit();
    } else {
        // Payment pending or other status
        echo "Payment status: " . $payment_status;
        exit();
    }
} else {
    echo "Error verifying payment: ";
    print_r($responseData);
    exit();
}
?>