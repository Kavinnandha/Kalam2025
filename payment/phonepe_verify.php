<?php
require_once '../database/connection.php';

$saltKey = "bbdc4a8f-806b-4307-ad83-d0efefbe8725";
$saltIndex = 1;

if (!isset($_GET['transactionId'])) {
    echo "Invalid access!";
    exit();
}

$transactionId = $_GET['transactionId'];

$checksum = hash('sha256', "/pg/v1/status/$transactionId" . $saltKey);

$ch = curl_init("https://api.phonepe.com/apis/pg/v1/status/$transactionId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "accept: application/json",
    "X-VERIFY: $checksum###$saltIndex"
]);

$response = curl_exec($ch);
curl_close($ch);
$responseData = json_decode($response, true);

if ($responseData['success']) {
    $status = $responseData['data']['state'];

    if ($status == 'COMPLETED') {
        // Update database for payment success
        echo "Payment successful!";
    } else {
        echo "Payment status: $status";
    }
} else {
    echo "Payment verification failed.";
}
?>