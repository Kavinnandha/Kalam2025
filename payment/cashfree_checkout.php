<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['payment_session_id'])) {
    header("Location: ../index.php");
} 
$payment_session_id = $_GET['payment_session_id'];
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
                mode: "sandbox"
            });
            cashfree.checkout({
                paymentSessionId: "<?php echo $payment_session_id; ?>",
                redirectTarget: "_self"
            });
        </script>
    </body>

    </html>