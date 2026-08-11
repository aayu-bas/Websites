<?php

require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Please login to manage your cart.']);
    exit;
}

$userId = (int)getCurrentUserId();
$action = $_POST['action'] ?? '';

// -----------------------------------------------------
// Helpers
// -----------------------------------------------------

function respond($success, $message, $extra = []) {
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}


function getCartTotal($conn, $userId) {
    $userId = (int)$userId;
    $sql = "SELECT ci.quantity, p.price, p.sale_price
            FROM cart c
            JOIN cart_items ci ON c.cart_id = ci.cart_id
            JOIN products p ON ci.product_id = p.product_id
            WHERE c.user_id = $userId";
    $res = mysqli_query($conn, $sql);

    $subtotal = 0;
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $price = ($row['sale_price'] !== null && $row['sale_price'] > 0)
                ? (float)$row['sale_price']
                : (float)$row['price'];
            $subtotal += $price * (int)$row['quantity'];
        }
    }

    $shipping = $subtotal > 999 ? 0 : 99;
    return $subtotal + $shipping;
}

// -----------------------------------------------------
// GET OR CREATE CART
// -----------------------------------------------------

$sql = "SELECT cart_id FROM cart WHERE user_id = $userId";
$result = mysqli_query($conn, $sql);

if (!$result) {
    respond(false, 'Error finding cart.');
}

if (mysqli_num_rows($result) > 0) {

    $cart = mysqli_fetch_assoc($result);
    $cartId = (int)$cart['cart_id'];

} else {

    $sql = "INSERT INTO cart (user_id) VALUES ($userId)";

    if (!mysqli_query($conn, $sql)) {
        respond(false, 'Error creating cart.');
    }

    $cartId = mysqli_insert_id($conn);
}


// =====================================================
// ADD ITEM
// =====================================================

if ($action == 'add') {

    $productId = intval($_POST['product_id'] ?? 0);
    $quantity  = intval($_POST['quantity'] ?? 1);

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Check product
    $sql = "SELECT * FROM products
        WHERE product_id = $productId AND is_active = 1
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        respond(false, 'Error checking product.');
    }

    if (mysqli_num_rows($result) == 0) {
        respond(false, 'Product not found.');
    }

    $product = mysqli_fetch_assoc($result);

    // Check stock
    if ($product['stock_quantity'] < $quantity) {
        respond(false, 'Not enough stock available.');
    }

    // Get product price
    if ($product['sale_price'] !== null && $product['sale_price'] > 0) {
        $price = (float)$product['sale_price'];
    } else {
        $price = (float)$product['price'];
    }

    // Check whether item already exists
    $sql = "SELECT cart_item_id, quantity FROM cart_items
        WHERE cart_id = $cartId AND product_id = $productId
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        respond(false, 'Error checking cart.');
    }

    if (mysqli_num_rows($result) > 0) {

        // Item already exists
        $existing = mysqli_fetch_assoc($result);

        $newQuantity = (int)$existing['quantity'] + $quantity;

        // Do not exceed stock
        if ($newQuantity > $product['stock_quantity']) {
            $newQuantity = (int)$product['stock_quantity'];
        }

        $cartItemId = (int)$existing['cart_item_id'];

        $sql = "UPDATE cart_items SET quantity = $newQuantity,
                unit_price = $price WHERE cart_item_id = $cartItemId";

        if (!mysqli_query($conn, $sql)) {
            respond(false, 'Error updating cart.');
        }

    } else {

        // Add new item
        $sql = "INSERT INTO cart_items(cart_id, product_id, quantity, unit_price)
            VALUES($cartId, $productId, $quantity, $price)";

        if (!mysqli_query($conn, $sql)) {
            respond(false, 'Error adding item to cart.');
        }
    }

        // ADD ITEM — near the end:
    respond(true, 'Added to cart!', [
        'cart_count' => getCartCount()   // was getCartCount($conn, $userId)
    ]);
}


// =====================================================
// UPDATE ITEM
// =====================================================

if ($action == 'update') {

    $itemId   = intval($_POST['item_id'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Check that item belongs to user's cart
    $sql = "SELECT ci.cart_item_id, ci.quantity, p.stock_quantity
        FROM cart_items ci
        JOIN cart c
            ON ci.cart_id = c.cart_id
        JOIN products p
            ON ci.product_id = p.product_id
        WHERE ci.cart_item_id = $itemId
        AND c.user_id = $userId
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        respond(false, 'Error checking cart item.');
    }

    if (mysqli_num_rows($result) == 0) {
        respond(false, 'Item not found.');
    }

    $item = mysqli_fetch_assoc($result);

    // Check stock
    if ($quantity > $item['stock_quantity']) {
        respond(false, 'Not enough stock available.');
    }

    // Update quantity
    $sql = "UPDATE cart_items
        SET quantity = $quantity
        WHERE cart_item_id = $itemId
    ";

    if (!mysqli_query($conn, $sql)) {
        respond(false, 'Error updating quantity.');
    }

    respond(true, 'Quantity updated!', [
        'cart_count' => getCartCount(),  // was getCartCount($conn, $userId)
        'total'      => getCartTotal($conn, $userId)
    ]);
}


// =====================================================
// REMOVE ITEM
// =====================================================

if ($action == 'remove') {

    $itemId = intval($_POST['item_id'] ?? 0);

    // Check that item belongs to user's cart
    $sql = "SELECT ci.cart_item_id
        FROM cart_items ci
        JOIN cart c
            ON ci.cart_id = c.cart_id
        WHERE ci.cart_item_id = $itemId
        AND c.user_id = $userId
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        respond(false, 'Error checking cart item.');
    }

    if (mysqli_num_rows($result) == 0) {
        respond(false, 'Item not found.');
    }

    // Delete item
    $sql = "DELETE FROM cart_items WHERE cart_item_id = $itemId";

    if (!mysqli_query($conn, $sql)) {
        respond(false, 'Error removing item.');
    }

    respond(true, 'Item removed!', [
        'cart_count' => getCartCount(),  // was getCartCount($conn, $userId)
        'total'      => getCartTotal($conn, $userId)
    ]);
}

respond(false, 'Invalid action.');