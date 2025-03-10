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
    mysqli_query($conn, "INSERT INTO cart (user_id) VALUES ('$userId')");
    $cartId = mysqli_insert_id($conn);
} else {
    $cartId = $cart['cart_id'];
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
// Fetch the event details of the event being added
$newEventQuery = mysqli_query($conn, "SELECT event_id, start_time, end_time, event_date FROM events WHERE event_id = '$eventId'");
$newEvent = mysqli_fetch_assoc($newEventQuery);

if (!$newEvent) {
    echo json_encode([
        'success' => false,
        'message' => 'Event not found'
    ]);
    exit;
}

// Check for time overlaps with existing cart items (EXCLUDE BOUNDARIES)
$overlapCheck = "
    SELECT e.event_id, e.event_name, e.event_date, e.start_time, e.end_time 
    FROM cart_items ci
    JOIN events e ON ci.event_id = e.event_id
    WHERE ci.cart_id = '$cartId'
    AND e.event_date = '{$newEvent['event_date']}'
    AND (
        ('{$newEvent['start_time']}' > e.start_time AND '{$newEvent['start_time']}' < e.end_time) OR
        ('{$newEvent['end_time']}' > e.start_time AND '{$newEvent['end_time']}' < e.end_time) OR
        (e.start_time > '{$newEvent['start_time']}' AND e.end_time < '{$newEvent['end_time']}')
    )
";

$overlappingEvents = mysqli_query($conn, $overlapCheck);

if (mysqli_num_rows($overlappingEvents) > 0) {
    $conflictingEvents = [];
    while ($event = mysqli_fetch_assoc($overlappingEvents)) {
        $conflictingEvents[] = $event;
    }
    
    echo json_encode([
        'success' => false,
        'message' => 'An event has already been added to your cart for this time slot.',
        'overlapping_events' => $conflictingEvents
    ]);
    exit;
}

// Add item to cart
$addItem = mysqli_query($conn, "INSERT INTO cart_items (cart_id, event_id) VALUES ('$cartId', '$eventId')");

if ($addItem) {    
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