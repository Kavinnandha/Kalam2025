<?php
session_start();
require_once '../database/connection.php';

// PhonePe API credentials - replace with your actual production credentials
$merchantId = "M22DZIHTE7XA8";
$clientId = $merchantId; // Usually same as merchantId
$clientSecret = "bbdc4a8f-806b-4307-ad83-d0efefbe8725"; // Your client secret
$clientVersion = 1; // Use the value provided in your credentials email for production

// First, check if transaction ID is provided in the URL
if (isset($_GET['merchantOrderId'])) {
    $transaction_id = $_GET['merchantOrderId'];
} else {
    // If not, check if PhonePe provides callback data
    $input = file_get_contents('php://input');
    $callback_data = json_decode($input, true);
    
    if ($callback_data && isset($callback_data['merchantOrderId'])) {
        $transaction_id = $callback_data['merchantOrderId'];
    } else {
        echo "Invalid access! Transaction ID not found.";
        exit();
    }
}

// Function to get OAuth token
function getAccessToken($clientId, $clientSecret, $clientVersion) {
    $authEndpoint = "https://api.phonepe.com/apis/identity-manager/v1/oauth/token";
    
    $postData = http_build_query([
        "client_id" => $clientId,
        "client_version" => $clientVersion,
        "client_secret" => $clientSecret,
        "grant_type" => "client_credentials"
    ]);
    
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $authEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/x-www-form-urlencoded",
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
    
    if (isset($responseData['access_token']) && isset($responseData['token_type'])) {
        return [
            'access_token' => $responseData['access_token'],
            'token_type' => $responseData['token_type'],
            'expires_at' => $responseData['expires_at']
        ];
    } else {
        error_log("Auth Error: " . json_encode($responseData));
        return null;
    }
}

// Get access token
$authData = getAccessToken($clientId, $clientSecret, $clientVersion);

if (!$authData) {
    echo "Failed to authenticate with PhonePe API";
    exit();
}

// Verify the payment status with PhonePe using the access token
$apiEndpoint = "https://api.phonepe.com/apis/pg/checkout/v2/order/$transaction_id/status?details=true&errorContext=true";

// Prepare API request with OAuth token
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $apiEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: {$authData['token_type']} {$authData['access_token']}",
        "accept: application/json"
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

// Check payment status - rely on the 'state' parameter as per documentation
if (isset($responseData['state'])) {
    $payment_status = $responseData['state'];
    $payment_amount = $responseData['amount'] / 100; // Convert from paise to rupees
    
    // Get PhonePe order ID
    $phonepe_order_id = $responseData['orderId'];
    
    // Get transaction details from the latest payment attempt
    $phonepe_transaction_id = null;
    $payment_mode = 'UNKNOWN';
    
    if (isset($responseData['paymentDetails']) && !empty($responseData['paymentDetails'])) {
        $latest_payment = $responseData['paymentDetails'][0];
        
        if (isset($latest_payment['transactionId'])) {
            $phonepe_transaction_id = $latest_payment['transactionId'];
        }
        
        if (isset($latest_payment['paymentMode'])) {
            $payment_mode = $latest_payment['paymentMode'];
        }
    }
    
    $payment_time = time(); // Current timestamp as payment time
    $payment_completion_time = time(); // Current timestamp as completion time
    
    if ($payment_status === 'COMPLETED') {
        // Update payment_transactions table
        $stmt = $conn->prepare("UPDATE payment_transactions 
                               SET cf_payment_id = ?, phonepe_order_id = ?, amount = ?, payment_mode = ?, 
                               payment_time = ?, payment_completion_time = ?, 
                               payment_status = 'SUCCESS', response_data = ?
                               WHERE transaction_id = ?");
        
        $stmt->bind_param("ssdsssss", $phonepe_transaction_id, $phonepe_order_id, $payment_amount, 
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
    } elseif ($payment_status === 'FAILED') {
        // Update payment status as failed
        $conn->query("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = '$response_json' WHERE transaction_id = '$transaction_id'");
        header("Location: ../cart/cart.php?payment=failed");
        exit();
    } elseif ($payment_status === 'PENDING') {
        // Payment is still pending
        echo "Your payment is being processed. Please wait...";
        exit();
    } else {
        // Any other status (like CANCELLED)
        $conn->query("UPDATE payment_transactions SET payment_status = 'FAILED', response_data = '$response_json' WHERE transaction_id = '$transaction_id'");
        header("Location: ../cart/cart.php?payment=cancelled");
        exit();
    }
} else {
    echo "Error verifying payment: ";
    print_r($responseData);
    exit();
}
?>