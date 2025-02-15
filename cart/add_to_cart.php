<?php
session_start();
header('Content-Type: application/json');

// Include database connection
include_once '../database/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not logged in'
    ]);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$eventId = $data['event_id'] ?? null;
$userId = $_SESSION['user_id'];

if (!$eventId) {
    echo json_encode([
        'success' => false,
        'message' => 'Event ID is required'
    ]);
    exit;
}

// Check if user has a cart
$checkCart = mysqli_query($conn, "SELECT cart_id, total_amount FROM cart WHERE user_id = '$userId'");
$cart = mysqli_fetch_assoc($checkCart);

$cartId = null;
$totalAmount = 0;
if (!$cart) {
    // Create new cart if doesn't exist
    mysqli_query($conn, "INSERT INTO cart (user_id, total_amount) VALUES ('$userId', 0)");
    $cartId = mysqli_insert_id($conn);
} else {
    $cartId = $cart['cart_id'];
    $totalAmount = $cart['total_amount'];
}

// Check if item already exists in cart
$checkItem = mysqli_query($conn, "SELECT cart_item_id FROM cart_items WHERE cart_id = '$cartId' AND event_id = '$eventId'");
if (mysqli_fetch_assoc($checkItem)) {
    echo json_encode([
        'success' => false,
        'message' => 'Item already in cart'
    ]);
    exit;
}

// Fetch the price of the event
$eventResult = mysqli_query($conn, "SELECT registration_fee FROM events WHERE event_id = '$eventId'");
$event = mysqli_fetch_assoc($eventResult);
if (!$event) {
    echo json_encode([
        'success' => false,
        'message' => 'Event not found'
    ]);
    exit;
}
$eventPrice = $event['registration_fee'];

// Add item to cart
$addItem = mysqli_query($conn, "INSERT INTO cart_items (cart_id, event_id) VALUES ('$cartId', '$eventId')");

if ($addItem) {
    // Update total amount in cart
    $totalAmount += $eventPrice;
    mysqli_query($conn, "UPDATE cart SET total_amount = '$totalAmount' WHERE cart_id = '$cartId'");
    
    echo json_encode([
        'success' => true,
        'message' => 'Item added to cart successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error adding item to cart'
    ]);
}
?>