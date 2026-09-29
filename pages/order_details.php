<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$pageTitle = 'Order Details';
$userId = (int)getCurrentUserId();
$orderId = (int)($_GET['order_id'] ?? 0);

if (!$orderId) {
    setFlashMessage('error', 'Invalid order.');
    redirect(SITE_URL . '/pages/orders.php');
}

$orderSql = "SELECT o.*,
    a.full_name AS shipping_name,
    a.phone AS shipping_phone,
    a.address_line1,
    a.address_line2,
    a.city,
    a.state,
    a.postal_code,
    a.country,
    p.payment_method,
    p.status AS transaction_status
FROM orders o
LEFT JOIN addresses a ON o.shipping_address_id = a.address_id
LEFT JOIN payments p ON o.order_id = p.order_id
WHERE o.order_id = $orderId
AND o.user_id = $userId
LIMIT 1";

$orderResult = mysqli_query($conn, $orderSql);

if (!$orderResult || mysqli_num_rows($orderResult) === 0) {
    setFlashMessage('error', 'Order not found.');
    redirect(SITE_URL . '/pages/orders.php');
}

$order = mysqli_fetch_assoc($orderResult);

$items = [];

$itemSql = "SELECT oi.*, pi.image_path
FROM order_items oi
LEFT JOIN product_images pi
    ON oi.product_id = pi.product_id
    AND pi.is_primary = 1
WHERE oi.order_id = $orderId
ORDER BY oi.order_item_id ASC";

$itemResult = mysqli_query($conn, $itemSql);

if ($itemResult) {
    while ($row = mysqli_fetch_assoc($itemResult)) {
        $items[] = $row;
    }
}

switch (strtolower($order['payment_method'] ?? 'cod')) {
    case 'cod':
        $paymentLabel = 'Cash on Delivery';
        break;
    case 'card':
        $paymentLabel = 'Credit/Debit Card';
        break;
    case 'upi':
        $paymentLabel = 'UPI Payment';
        break;
    default:
        $paymentLabel = ucfirst($order['payment_method'] ?? 'Unknown');
        break;
}

$canCancel = in_array(
    strtolower($order['status']),
    ['pending', 'processing'],
    true
);

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/order_details.css">';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="order-details-page">
    <div class="order-details-container">
        <div class="details-header">
            <div>
                <a href="<?php echo SITE_URL; ?>/pages/orders.php" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    Back to My Orders
                </a>
                <h1>Order #<?php echo sanitize($order['order_number']); ?></h1>
                <p>Placed on <?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
            </div>

            <div class="details-header-actions">
                <a href="<?php echo SITE_URL; ?>/pages/invoice.php?order_id=<?php echo $orderId; ?>" class="invoice-btn" target="_blank">
                    <i class="fas fa-file-invoice"></i>
                    Invoice
                </a>
            </div>
        </div>

        <div class="order-status-box">
            <div>
                <span class="status-label">Order Status</span>
                <span class="status-badge <?php echo htmlspecialchars(strtolower($order['status'])); ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>

            <div>
                <span class="status-label">Payment Status</span>
                <span class="payment-status">
                    <?php echo ucfirst($order['payment_status'] ?? 'pending'); ?>
                </span>
            </div>
        </div>

        <div class="details-card">
            <h2>
                <i class="fas fa-shopping-bag"></i>
                Order Items
            </h2>

            <?php foreach ($items as $item): ?>
                <div class="detail-item">
                    <div class="detail-item-image">
                        <img
                            src="<?php echo ASSETS_URL; ?>/images/products/<?php echo htmlspecialchars($item['image_path'] ?? 'placeholder.jpg'); ?>"
                            alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                        >
                    </div>

                    <div class="detail-item-info">
                        <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                        <p>Quantity: <?php echo (int)$item['quantity']; ?></p>
                        <p>Unit Price: <?php echo formatPrice($item['unit_price']); ?></p>
                    </div>

                    <strong class="detail-item-total">
                        <?php echo formatPrice($item['total_price']); ?>
                    </strong>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="details-grid">
            <div class="details-card">
                <h2>
                    <i class="fas fa-map-marker-alt"></i>
                    Shipping Address
                </h2>

                <div class="address-details">
                    <strong><?php echo htmlspecialchars($order['shipping_name']); ?></strong>
                    <p>
                        <?php echo htmlspecialchars($order['address_line1']); ?>
                        <?php if (!empty($order['address_line2'])): ?>
                            <br><?php echo htmlspecialchars($order['address_line2']); ?>
                        <?php endif; ?>
                        <br>
                        <?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' - ' . $order['postal_code']); ?>
                        <br>
                        <?php echo htmlspecialchars($order['country']); ?>
                        <br>
                        <i class="fas fa-phone"></i>
                        <?php echo htmlspecialchars($order['shipping_phone']); ?>
                    </p>
                </div>
            </div>

            <div class="details-card">
                <h2>
                    <i class="fas fa-wallet"></i>
                    Payment
                </h2>

                <div class="payment-details">
                    <div>
                        <span>Method</span>
                        <strong><?php echo htmlspecialchars($paymentLabel); ?></strong>
                    </div>
                    <div>
                        <span>Status</span>
                        <strong><?php echo ucfirst($order['payment_status'] ?? 'pending'); ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="details-card price-summary">
            <h2>
                <i class="fas fa-receipt"></i>
                Order Summary
            </h2>

            <div class="summary-line">
                <span>Subtotal</span>
                <span><?php echo formatPrice($order['total_amount']); ?></span>
            </div>

            <?php if ((float)$order['discount_amount'] > 0): ?>
                <div class="summary-line discount">
                    <span>Discount</span>
                    <span>- <?php echo formatPrice($order['discount_amount']); ?></span>
                </div>
            <?php endif; ?>

            <div class="summary-line">
                <span>Shipping</span>
                <span>
                    <?php echo (float)$order['shipping_amount'] > 0 ? formatPrice($order['shipping_amount']) : 'FREE'; ?>
                </span>
            </div>

            <div class="summary-line grand-total">
                <span>Total</span>
                <strong><?php echo formatPrice($order['final_amount']); ?></strong>
            </div>
        </div>

        <div class="order-actions">
            <a href="<?php echo SITE_URL; ?>/pages/invoice.php?order_id=<?php echo $orderId; ?>" target="_blank"
                class="action-btn invoice-action">
                <i class="fas fa-print"></i>
                Download Invoice
            </a>

            <?php if ($canCancel): ?>
                <button type="button" class="action-btn cancel-action" onclick="openCancelModal()">
                    <i class="fas fa-times-circle"></i>
                    Cancel Order
                </button>
            <?php endif; ?>
        </div>

        <?php if ($order['status'] === 'cancelled'): ?>
            <div class="cancelled-box">
                <h3>
                    <i class="fas fa-ban"></i>
                    Order Cancelled
                </h3>

                <?php if (!empty($order['cancelled_at'])): ?>
                    <p>
                        Cancelled on
                        <?php echo date('M d, Y h:i A', strtotime($order['cancelled_at'])); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($order['cancellation_reason'])): ?>
                    <p>Reason:
                        <?php echo htmlspecialchars($order['cancellation_reason']); ?>
                    </p>
                <?php endif; ?>


                <?php if (!empty($order['cancelled_by'])): ?> 
                    <p class="cancelled-by"> Cancelled by: 
                        <?php echo htmlspecialchars( ucfirst($order['cancelled_by']), ENT_QUOTES, 'UTF-8' ); ?>
                    </p> 
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($canCancel): ?>
    <div class="cancel-modal" id="cancelModal">
        <div class="cancel-modal-content">
            <button type="button" class="modal-close" onclick="closeCancelModal()">&times;</button>

            <div class="cancel-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <h2>Cancel Order?</h2>

            <p>
                Are you sure you want to cancel
                order #<?php echo sanitize($order['order_number']); ?>?
            </p>

            <form method="POST" action="<?php echo SITE_URL; ?>/pages/cancel_order.php">
                <?php echo csrfField(); ?>

                <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">

                <label for="cancellation_reason">Cancellation Reason</label>

                <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required maxlength="500"
                    placeholder="Please tell us why you want to cancel this order..."></textarea>

                <div class="modal-actions">
                    <button type="button" class="modal-cancel-btn" onclick="closeCancelModal()">
                        Keep Order
                    </button>

                    <button type="submit" class="modal-confirm-btn">
                        Cancel Order
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.add('show');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('show');
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>