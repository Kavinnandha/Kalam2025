<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../database/connection.php';
require_once '../libraries/vendor/autoload.php';


use PhonePe\payments\v1\PhonePePaymentClient;
use PhonePe\Env;
use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;
use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;

// PhonePe API credentials  
$merchantId = "M22DZIHTE7XA8";
$saltKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725";
$saltIndex = "1";
$env = Env::PRODUCTION; // Use Env::PRODUCTION for production
$shouldPublishEvents = true;

// Initialize PhonePe Payment Client
$phonePePaymentsClient = new PhonePePaymentClient($merchantId, $saltKey, $saltIndex, $env, $shouldPublishEvents);

// Get user information
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

// Generate a unique transaction ID
$transaction_id = $user_id . "_kalam2025_" . time();
$payment_status = "Initiated";
$payment_time = time();

// Record the transaction in database
$payment_transaction_query = "INSERT INTO payment_transactions (transaction_id, amount, user_id, payment_status, payment_time)
                            VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($payment_transaction_query);
$stmt->bind_param("sdisi", $transaction_id, $total_amount, $user_id, $payment_status, $payment_time);
$stmt->execute();

// Set callback and redirect URLs
$callback_url = "https://siet.ac.in/kalam/payment/phonepe_verify_lib.php?merchantTransactionId=" . $transaction_id;
$redirect_url = "https://siet.ac.in/kalam/payment/phonepe_verify_lib.php?merchantTransactionId=" . $transaction_id;

// Amount in paise
$amount_in_paise = (int)($total_amount * 100);

try {
    // Build the payment request using the SDK
    $request = PgPayRequestBuilder::builder()
        ->mobileNumber($user['phone'])
        ->callbackUrl($callback_url)
        ->merchantId($merchantId)
        ->merchantUserId((string)$user_id)
        ->amount($amount_in_paise)
        ->merchantTransactionId($transaction_id)
        ->redirectUrl($redirect_url)
        ->redirectMode("REDIRECT")
        ->paymentInstrument(InstrumentBuilder::buildPayPageInstrument())
        ->build();

    // Make the payment request
    $response = $phonePePaymentsClient->pay($request);
    
    // Check if we have a valid response with redirect URL
    if ($response && $response->getInstrumentResponse() && $response->getInstrumentResponse()->getRedirectInfo()) {
        $redirectUrl = $response->getInstrumentResponse()->getRedirectInfo()->getUrl();
        
        // Redirect to PhonePe payment page
        header("Location: " . $redirectUrl);
        exit();
    } else {
        echo "Error: Invalid response structure from PhonePe";
        echo "<pre>"; print_r($response); echo "</pre>";
        exit();
    }
} catch (Exception $e) {
    echo "Error initiating payment: " . $e->getMessage();
    exit();
}
?>