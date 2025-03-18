<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../database/connection.php';
require_once '../libraries/vendor/autoload.php';

use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CustomerDetails;
use Cashfree\Model\OrderMeta;

// Set Cashfree API credentials
Cashfree::$XClientId = "926727802c7772f7b5131518e4727629";
Cashfree::$XClientSecret = "cfsk_ma_prod_1d6c34c09b03491ed89f684ec0913dc0_31a7fd0a";
Cashfree::$XEnvironment = Cashfree::$PRODUCTION;

$x_api_version = "2023-08-01";

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

// Generate a unique order ID and Insert into payment_transactions table
$transaction_id = $user_id . "_kalam2025_" . time();
$payment_status = "Initiated";
$payment_time = time();

$payment_transaction_query = "INSERT INTO payment_transactions (transaction_id, amount, user_id, payment_status, payment_time)
                            VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($payment_transaction_query);
$stmt->bind_param("sdisi", $transaction_id, $total_amount, $user_id, $payment_status, $payment_time);
$stmt->execute();

$customer_id = str_pad((string) $user_id, 4, '0', STR_PAD_LEFT);

// Create Cashfree order request
$create_order_request = new CreateOrderRequest();
$create_order_request->setOrderAmount($total_amount);
$create_order_request->setOrderCurrency("INR");
$create_order_request->setOrderId($transaction_id);

$customer_details = new CustomerDetails();
$customer_details->setCustomerId($customer_id);
$customer_details->setCustomerPhone($user['phone']);
$customer_details->setCustomerEmail($user['email']);
$create_order_request->setCustomerDetails($customer_details);

$order_meta = new OrderMeta();
$order_meta->setReturnUrl("https://siet.ac.in/kalam/payment/cashfree_verify.php?order_id=$transaction_id");
$create_order_request->setOrderMeta($order_meta);

try {
    $cashfree = new Cashfree();
    $result = $cashfree->PGCreateOrder($x_api_version, $create_order_request);

    // Capture the payment session ID using the getter method
    $orderEntity = $result[0];
    $payment_session_id = $orderEntity->getPaymentSessionId();

    // End PHP processing and output HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Cashfree</title>
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
</head>
<body>
    <script>
        const cashfree = Cashfree({
            mode: "production"
        });
        cashfree.checkout({
            paymentSessionId: "<?php echo $payment_session_id; ?>",
            redirectTarget: "_self"
        });
    </script>
</body>
</html>
<?php
} catch (Exception $e) {
    echo 'Exception when calling PGCreateOrder: ', $e->getMessage();
}
?>