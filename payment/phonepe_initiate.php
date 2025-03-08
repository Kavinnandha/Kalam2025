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

// Initialize PhonePe integration function
function initiatePhonePePayment($amount, $userId) {
    // PhonePe API Configuration
    $merchantId = "PGTESTPAYUAT";
    $apiKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
    $saltKey = "099eb0cd-02cf-4e2a-8aca-3e6c6aff0399"; // Usually the same as API key in sandbox
    $saltIndex = "1";
    $apiEndpoint = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
    
    // Generate a unique transaction ID - combining timestamp and user ID
    $merchantTransactionId = "TX" . time() . $userId;
    
    // Store transaction ID in session for verification later
    $_SESSION['phonepe_transaction_id'] = $merchantTransactionId;
    
    // Prepare the payment request payload
    $paymentData = [
        "merchantId" => $merchantId,
        "merchantTransactionId" => $merchantTransactionId,
        "merchantUserId" => "MUID" . $userId,
        "amount" => $amount * 100, // PhonePe expects amount in paise (1 rupee = 100 paise)
        "redirectUrl" => $_SERVER['HTTP_HOST'] . "/index.php",
        "redirectMode" => "POST",
        "callbackUrl" => $_SERVER['HTTP_HOST'] . "/phonepe_callback.php",
        "mobileNumber" => "9999999999", // Ideally fetch from user profile
        "paymentInstrument" => [
            "type" => "PAY_PAGE"
        ]
    ];
    
    // Convert to JSON
    $jsonPayload = json_encode($paymentData);
    
    // Base64 encode the payload
    $base64Payload = base64_encode($jsonPayload);
    
    // Create checksum: SHA256(base64 payload + "/pg/v1/pay" + salt key) + ### + salt index
    $checksum = hash('sha256', $base64Payload . "/pg/v1/pay" . $saltKey) . "###" . $saltIndex;
    
    // Prepare the final request
    $requestData = [
        "request" => $base64Payload
    ];
    
    // Initialize cURL session
    $curl = curl_init();
    
    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $apiEndpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($requestData),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "X-VERIFY: " . $checksum,
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
        $responseData = json_decode($response, true);
        
        // If payment initiation is successful, return the payment URL
        if (isset($responseData['success']) && $responseData['success'] === true) {
            // Store transaction details in database for reconciliation
            storeTransactionDetails($merchantTransactionId, $amount, $userId);
            
            return [
                'success' => true,
                'paymentUrl' => $responseData['data']['instrumentResponse']['redirectInfo']['url'],
                'transactionId' => $merchantTransactionId
            ];
        } else {
            return [
                'success' => false,
                'message' => isset($responseData['message']) ? $responseData['message'] : "Payment initiation failed"
            ];
        }
    }
}

// Function to store transaction details in database
function storeTransactionDetails($transactionId, $amount, $userId) {
    global $conn;
    
    $status = "INITIATED";
    $created_at = date('Y-m-d H:i:s');
    
    $query = "INSERT INTO payment_transactions (transaction_id, user_id, amount, status, created_at) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sidss", $transactionId, $userId, $amount, $status, $created_at);
    $stmt->execute();
    $stmt->close();
}

// Main execution flow
try {
    // Start transaction
    $conn->begin_transaction();
    
    $user_id = $_SESSION['user_id'];
    
    // Get cart total amount
    $cart_query = "SELECT cart_id, total_amount FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();
    
    if ($cart_result->num_rows === 0) {
        throw new Exception("Cart not found");
    }
    
    $cart_data = $cart_result->fetch_assoc();
    $total_amount = $cart_data['total_amount'];
    $cart_id = $cart_data['cart_id'];
    $stmt->close();
    
    // Initiate PhonePe payment
    $payment = initiatePhonePePayment($total_amount, $user_id);
    
    if ($payment['success']) {
        // Store the cart ID in session for processing after payment
        $_SESSION['pending_cart_id'] = $cart_id;
        
        echo json_encode([
            'success' => true,
            'redirect' => true,
            'paymentUrl' => $payment['paymentUrl'],
            'transactionId' => $payment['transactionId']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $payment['message']
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