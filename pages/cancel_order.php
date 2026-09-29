<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$userId = (int)getCurrentUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(SITE_URL . '/pages/orders.php');
}

if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
    setFlashMessage('error', 'Invalid request.');
    redirect(SITE_URL . '/pages/orders.php');
}

$orderId = (int)($_POST['order_id'] ?? 0);
$reason = sanitize($_POST['cancellation_reason'] ?? '');

if (!$orderId) {
    setFlashMessage('error', 'Invalid order.');
    redirect(SITE_URL . '/pages/orders.php');
}

if (empty($reason)) {
    setFlashMessage('error', 'Please provide a cancellation reason.');
    redirect(SITE_URL . '/pages/order_details.php?order_id=' . $orderId);
}

mysqli_begin_transaction($conn);

try {

    $orderSql = "SELECT order_id, order_number, status
        FROM orders WHERE order_id = $orderId AND user_id = $userId LIMIT 1 FOR UPDATE
    ";

    $orderResult = mysqli_query($conn, $orderSql);

    if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
        throw new Exception('Order not found.');
    }

    $order = mysqli_fetch_assoc($orderResult);

    $currentStatus = strtolower($order['status']);

    /*Only pending and processing orders can be cancelled*/
    if (!in_array($currentStatus, ['pending', 'processing'], true)) {
        throw new Exception('This order can no longer be cancelled.');
    }

    /*Get all products and quantities from this order*/
    $itemsSql = "SELECT product_id, quantity FROM order_items WHERE order_id = $orderId FOR UPDATE
    ";

    $itemsResult = mysqli_query($conn, $itemsSql);

    if (!$itemsResult) {
        throw new Exception('Unable to retrieve order items.');
    }

    if (mysqli_num_rows($itemsResult) === 0) {
        throw new Exception('No items found for this order.');
    }

    /* Restore stock*/
    while ($item = mysqli_fetch_assoc($itemsResult)) {

        $productId = (int)$item['product_id'];
        $quantity = (int)$item['quantity'];

        if ($productId <= 0 || $quantity <= 0) {
            continue;
        }

        $restoreStockSql = "
            UPDATE products
            SET stock_quantity = stock_quantity + $quantity
            WHERE product_id = $productId
        ";

        if (!mysqli_query($conn, $restoreStockSql)) {
            throw new Exception(
                'Unable to restore product stock.'
            );
        }
    }

    $reason = mysqli_real_escape_string($conn, $reason);
    $cancelSql = "UPDATE orders SET
            status = 'cancelled',
            cancelled_at = NOW(),
            cancellation_reason = '$reason',
            cancelled_by = 'customer',
            updated_at = NOW()
        WHERE order_id = $orderId
        AND user_id = $userId
    ";

    if (!mysqli_query($conn, $cancelSql)) {
        throw new Exception('Unable to cancel the order.');
    }
    mysqli_commit($conn);

    setFlashMessage(
        'success',
        'Order #' . $order['order_number'] .
        ' has been cancelled successfully and the product stock has been restored.'
    );

    redirect(
        SITE_URL . '/pages/order_details.php?order_id=' . $orderId
    );

} catch (Exception $e) {
    mysqli_rollback($conn);

    error_log(
        'Order cancellation error: ' . $e->getMessage()
    );

    setFlashMessage('error',$e->getMessage());

    redirect(SITE_URL . '/pages/order_details.php?order_id=' . $orderId);
}

