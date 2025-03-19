<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../database/connection.php';

// PhonePe API credentials - replace with your actual production credentials
$merchantId = "M22DZIHTE7XA8";
$clientId = $merchantId; // Usually same as merchantId
$clientSecret = "bbdc4a8f-806b-4307-ad83-d0efefbe8725"; // Your client secret
$clientVersion = 1; // Use the value provided in your credentials email for production

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

// Get user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get cart total
$cart_query = "SELECT total_amount FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();
$row = $cart_result->fetch_assoc();
$total_amount = $row['total_amount'];
$stmt->close();

// Generate a unique transaction ID and insert into payment_transactions table
$transaction_id = $user_id . "_kalam2025_" . time();
$payment_status = "Initiated";
$payment_time = time();

$payment_transaction_query = "INSERT INTO payment_transactions (transaction_id, amount, user_id, payment_status, payment_time)
                            VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($payment_transaction_query);
$stmt->bind_param("sdisi", $transaction_id, $total_amount, $user_id, $payment_status, $payment_time);
$stmt->execute();

// Get OAuth token
$authData = getAccessToken($clientId, $clientSecret, $clientVersion);

if (!$authData) {
    echo "Failed to authenticate with PhonePe API";
    exit();
}

// Create PhonePe payment request using the new v2 API
$apiEndpoint = "https://api.phonepe.com/apis/pg/checkout/v2/pay";
$callback_url = "https://siet.ac.in/kalam/payment/phonepe_verify.php?merchantOrderId=$transaction_id";
$redirect_url = "https://siet.ac.in/kalam/payment/phonepe_verify.php?merchantOrderId=$transaction_id";

$amount = $total_amount * 100; // PhonePe expects amount in paise

// Create payload as per new API format
$payload = [
    "merchantOrderId" => $transaction_id,
    "amount" => intval($amount),
    "expireAfter" => 1200, // 20 minutes expiry
    "metaInfo" => [
        "udf1" => "kalam2025_order",
        "udf2" => $user_id,
        "udf3" => $user['email'] ?? '',
        "udf4" => $user['phone'] ?? '',
        "udf5" => ""
    ],
    "paymentFlow" => [
        "type" => "PG_CHECKOUT",
        "message" => "Payment for Kalam 2025 event registration",
        "merchantUrls" => [
            "redirectUrl" => $redirect_url
        ]
    ]
];

// Prepare API request with OAuth token
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $apiEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($payload),
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

// Check if $responseData is null or not an array
if (!$responseData || !is_array($responseData)) {
    echo "Error: Invalid response from PhonePe API";
    // Optionally log the raw response
    error_log("PhonePe Invalid Response: " . $response);
    echo "<pre>Response: " . htmlspecialchars($response) . "</pre>";
    exit();
}

// Parse the response from the new API
if (isset($responseData['orderId']) && isset($responseData['redirectUrl'])) {
    $phonepe_order_id = $responseData['orderId']; 
    $redirectUrl = $responseData['redirectUrl'];
    
    // Update the transaction with PhonePe order ID
    $update_query = "UPDATE payment_transactions SET phonepe_order_id = ? WHERE transaction_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ss", $phonepe_order_id, $transaction_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to PhonePe payment page
    header("Location: " . $redirectUrl);
    exit();
} else {
    echo "Error initiating payment: ";
    error_log("PhonePe Error Response: " . json_encode($responseData));
    echo "<pre>"; print_r($responseData); echo "</pre>";
    exit();
}
?>