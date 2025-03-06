<?php
include_once '../database/connection.php'; // Adjust path as needed

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user details
$userQuery = "SELECT name, email, phone FROM users WHERE user_id = ?";
$userStmt = $conn->prepare($userQuery);
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$userResult = $userStmt->get_result();
$userData = $userResult->fetch_assoc();

// Check if user has an existing order
$orderQuery = "SELECT COUNT(*) as order_count FROM orders WHERE user_id = ?";
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->bind_param("i", $user_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$orderData = $orderResult->fetch_assoc();
$hasExistingOrder = $orderData['order_count'] > 0;

// Get cart items
$cartQuery = "SELECT c.cart_id, c.total_amount, ci.cart_item_id, e.event_id, e.event_name, e.registration_fee 
              FROM cart c 
              JOIN cart_items ci ON c.cart_id = ci.cart_id 
              JOIN events e ON ci.event_id = e.event_id 
              WHERE c.user_id = ?";
$cartStmt = $conn->prepare($cartQuery);
if ($cartStmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}
$cartStmt->bind_param("i", $user_id);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

$items = [];
$subtotal = 0;
$cart_id = null;

while ($row = $cartResult->fetch_assoc()) {
    $items[] = [
        'event_id' => $row['event_id'],
        'event_name' => $row['event_name'],
        'registration_fee' => $row['registration_fee']
    ];
    $subtotal += floatval($row['registration_fee']);
    
    if ($cart_id === null) {
        $cart_id = $row['cart_id'];
    }
}

// Apply general fee if no existing order
$generalFee = $hasExistingOrder ? 0 : 150;
$totalAmount = $subtotal + $generalFee;

// Update cart total in database
$updateQuery = "UPDATE cart SET total_amount = ? WHERE cart_id = ? AND user_id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("dii", $totalAmount, $cart_id, $user_id);
$updateStmt->execute();

// Prepare response data
$response = [
    'success' => true,
    'userData' => $userData,
    'items' => $items,
    'subtotal' => $subtotal,
    'generalFee' => $generalFee,
    'totalAmount' => $totalAmount,
    'hasExistingOrder' => $hasExistingOrder
];

echo json_encode($response);
$conn->close();