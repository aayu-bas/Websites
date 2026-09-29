<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$pageTitle = 'My Orders';
$userId = (int) getCurrentUserId();

$orders = [];

$orderSql = "SELECT
        o.order_id,
        o.order_number,
        o.status,
        o.payment_status,
        o.total_amount,
        o.final_amount,
        o.created_at,
        COUNT(oi.order_item_id) AS item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.order_id = oi.order_id
    WHERE o.user_id = $userId
    GROUP BY
        o.order_id,
        o.order_number,
        o.status,
        o.payment_status,
        o.total_amount,
        o.final_amount,
        o.created_at
    ORDER BY o.created_at DESC
";

$orderResult = mysqli_query($conn, $orderSql);

if ($orderResult) {
    while ($row = mysqli_fetch_assoc($orderResult)) {
        $orders[] = $row;
    }
}

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/orders.css">';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="orders-page">
    <div class="orders-container">
        <div class="orders-header">
            <div>
                <h1><i class="fas fa-box-open"></i> My Orders</h1>
                <p>View and manage your Yarnify orders.</p>
            </div>

            <a href="<?php echo SITE_URL; ?>/index.php" class="continue-shopping">
                <i class="fas fa-shopping-bag"></i>
                Continue Shopping
            </a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="empty-orders">
                <div class="empty-orders-icon">
                    <i class="fas fa-box-open"></i>
                </div>

                <h2>No Orders Yet</h2>
                <p>
                    You haven't placed any orders yet.
                    Start exploring our handmade crochet products!
                </p>

                <a href="<?php echo SITE_URL; ?>/index.php" class="shop-btn">
                    <i class="fas fa-shopping-bag"></i>
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                    <?php $status = strtolower($order['status'] ?? 'pending'); ?>

                    <div class="order-card">
                        <div class="order-card-main">
                            <div class="order-info">
                                <span class="order-label">Order</span>
                                <h2>
                                    #<?php echo htmlspecialchars($order['order_number']); ?>
                                </h2>
                                <p class="order-date">
                                    Placed on
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </p>
                            </div>

                            <div class="order-summary">
                                <div class="order-items-count">
                                    <strong><?php echo (int) $order['item_count']; ?></strong>
                                    <?php echo $order['item_count'] == 1 ? 'Item' : 'Items'; ?>
                                </div>

                                <div class="order-total">
                                    <?php echo formatPrice($order['final_amount']); ?>
                                </div>
                            </div>

                            <div class="order-status">
                                <span class="status-badge <?php echo htmlspecialchars($status); ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            </div>

                            <div class="order-action">
                                <a href="<?php echo SITE_URL; ?>/pages/order_details.php?order_id=<?php echo (int) $order['order_id']; ?>"
                                    class="view-order-btn">
                                    View Order Details
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>