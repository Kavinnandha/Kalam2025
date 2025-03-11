<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../database/connection.php';

// PhonePe API credentials
$merchantId = "MERCHANTUAT";
$apiKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725";
$saltKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725";
$saltIndex = 1;
$apiEndpoint = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";

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

// Generate a unique transaction ID and insert into payment_transactions table
$transaction_id = $user_id . "_kalam2025_" . time();
$payment_status = "Initiated";
$payment_time = time();

$payment_transaction_query = "INSERT INTO payment_transactions (transaction_id, amount, user_id, payment_status, payment_time)
                            VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($payment_transaction_query);
$stmt->bind_param("sdisi", $transaction_id, $total_amount, $user_id, $payment_status, $payment_time);
$stmt->execute();

// Create PhonePe payment request
$callback_url = "https://bc03-103-208-230-95.ngrok-free.app/kalam/payment/phonepe_verify.php";
$redirect_url = "https://bc03-103-208-230-95.ngrok-free.app/kalam/payment/phonepe_verify.php";

$amount = $total_amount * 100; // PhonePe expects amount in paise

$payload = [
    "merchantId" => $merchantId,
    "merchantTransactionId" => $transaction_id,
    "merchantUserId" => (string)$user_id,
    "amount" => $amount,
    "redirectUrl" => $redirect_url,
    "redirectMode" => "REDIRECT",
    "callbackUrl" => $callback_url,
    "mobileNumber" => $user['phone'],
    "paymentInstrument" => [
        "type" => "PAY_PAGE"
    ]
];

$jsonPayload = json_encode($payload);
$base64Payload = base64_encode($jsonPayload);

// Generate checksum
$string = $base64Payload . "/pg/v1/pay" . $saltKey;
$sha256 = hash('sha256', $string);
$checksum = $sha256 . '###' . $saltIndex;

// Prepare API request
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $apiEndpoint,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode(["request" => $base64Payload]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "X-VERIFY: $checksum",
        "X-MERCHANT-ID: $merchantId",
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

if ($responseData['success'] && isset($responseData['data']['instrumentResponse']['redirectInfo']['url'])) {
    $redirectUrl = $responseData['data']['instrumentResponse']['redirectInfo']['url'];
    
    // Update the transaction with PhonePe details if needed
    header("Location: " . $redirectUrl);
    exit();
} else {
    echo "Error initiating payment: ";
    print_r($responseData);
    exit();
}
?>
