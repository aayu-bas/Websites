<?php

$adminTitle = 'Orders';

require_once __DIR__ . '/../config/config.php';

$action = $_GET['action'] ?? 'list';

// Handle status update

if (isset($_GET['update_status']) && isset($_GET['id'])) {

    $orderId = intval($_GET['id']);
    $newStatus = $_GET['status'] ?? '';

    // Allow only valid statuses
    if (in_array($newStatus, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {

        // Escape status
        $newStatus = mysqli_real_escape_string($conn, $newStatus);

        $updateSql = "UPDATE orders SET status = '$newStatus' WHERE order_id = $orderId";

        mysqli_query($conn, $updateSql);

        // Update payment status if delivered
        if ($newStatus === 'delivered') {

            $paymentSql = "UPDATE orders SET payment_status = 'paid' WHERE order_id = $orderId
            ";

            mysqli_query($conn, $paymentSql);
        }

        setFlashMessage('success', 'Order status updated to ' . ucfirst($newStatus) . '!');
    }

    redirect(ADMIN_URL . '/orders.php');
}

// View single order

if ($action === 'view' && isset($_GET['id'])) {

    $orderId = intval($_GET['id']);

    // Get order details
    $orderSql = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone, sa.full_name AS ship_name, sa.phone AS ship_phone, sa.address_line1 AS ship_line1, sa.address_line2 AS ship_line2, sa.city AS ship_city, sa.state AS ship_state, sa.postal_code AS ship_postal, sa.country AS ship_country
        FROM orders o
        JOIN users u 
            ON o.user_id = u.user_id
        LEFT JOIN addresses sa 
            ON o.shipping_address_id = sa.address_id
        WHERE o.order_id = $orderId
    ";

    $orderResult = mysqli_query($conn, $orderSql);

    $order = null;

    if ($orderResult) {
        $order = mysqli_fetch_assoc($orderResult);
    }

    // Check if order exists
    if (!$order) {

        setFlashMessage('error', 'Order not found.');

        redirect(ADMIN_URL . '/orders.php');
    }


    // Get order items
    $itemsSql = "SELECT  oi.*, p.slug, pi.image_path AS primary_image
        FROM order_items oi
        LEFT JOIN products p 
            ON oi.product_id = p.product_id
        LEFT JOIN product_images pi 
            ON p.product_id = pi.product_id
            AND pi.is_primary = 1
        WHERE oi.order_id = $orderId
    ";

    $itemsResult = mysqli_query($conn, $itemsSql);

    $orderItems = [];

    if ($itemsResult) {

        while ($row = mysqli_fetch_assoc($itemsResult)) {
            $orderItems[] = $row;
        }
    }


    // Get payment information
    $paymentSql = "SELECT * FROM payments WHERE order_id = $orderId
    ";

    $paymentResult = mysqli_query($conn, $paymentSql);

    $payment = null;

    if ($paymentResult) {
        $payment = mysqli_fetch_assoc($paymentResult);
    }


} else {
    // List orders

    $statusFilter = $_GET['status'] ?? '';

    $where = "WHERE 1=1";


    // Status filter
    if ($statusFilter && in_array($statusFilter, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {

        $statusFilter = mysqli_real_escape_string($conn, $statusFilter);

        $where .= " AND o.status = '$statusFilter'";
    }


    // Pagination
    $page = max(1, intval($_GET['page'] ?? 1)
    );


    // Get total orders
    $countSql = "SELECT COUNT(*) AS count FROM orders o $where";

    $countResult = mysqli_query($conn, $countSql);

    $countRow = mysqli_fetch_assoc($countResult);

    $total = $countRow['count'];


    // Calculate pages
    $totalPages = ceil($total / ORDERS_PER_PAGE);

    $offset = ($page - 1) * ORDERS_PER_PAGE;


    // Get orders
    $ordersSql = "SELECT o.*,u.first_name,u.last_name
        FROM orders o
        JOIN users u 
            ON o.user_id = u.user_id
        $where
        ORDER BY o.created_at DESC
        LIMIT $offset, " . ORDERS_PER_PAGE;


    $ordersResult = mysqli_query($conn,$ordersSql);

    $orders = [];

    if ($ordersResult) {

        while ($row = mysqli_fetch_assoc($ordersResult)) {
            $orders[] = $row;
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'view' && $order): ?>
<div class="content-header">
    <h2><i class="fas fa-shopping-cart"></i> Order #<?php echo htmlspecialchars($order['order_number']); ?></h2>
    <a href="orders.php" class="btn btn-small btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
    <div>
        <div class="admin-table-wrapper" style="margin-bottom: 25px;">
            <div style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
                <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);">Order Items</h3>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $item['primary_image'] ?? 'placeholder.jpg'; ?>" 
                                     alt="" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover;">
                                <span><?php echo htmlspecialchars($item['product_name']); ?></span>
                            </div>
                        </td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo formatPrice($item['unit_price']); ?></td>
                        <td><strong><?php echo formatPrice($item['total_price']); ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-table-wrapper">
            <div style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
                <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);">Update Status</h3>
            </div>
            <div style="padding: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                <?php $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled']; ?>
                <?php foreach ($statuses as $status): ?>
                <a href="orders.php?update_status=1&id=<?php echo $order['order_id']; ?>&status=<?php echo $status; ?>" 
                   class="btn btn-small <?php echo $order['status'] === $status ? 'btn-primary' : 'btn-outline'; ?>"
                   <?php echo $order['status'] === $status ? 'style="pointer-events: none;"' : ''; ?>>
                    <?php echo ucfirst($status); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div>
        <div class="admin-table-wrapper" style="margin-bottom: 25px;">
            <div style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
                <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);">Order Summary</h3>
            </div>
            <div style="padding: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Subtotal</span>
                    <span><?php echo formatPrice($order['total_amount']); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <span>Shipping</span>
                    <span><?php echo formatPrice($order['shipping_amount']); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-top: 10px; border-top: 2px solid #f0f0f0; font-weight: 700; font-size: 1.1rem;">
                    <span>Total</span>
                    <span><?php echo formatPrice($order['final_amount']); ?></span>
                </div>
            </div>
        </div>

        <div class="admin-table-wrapper" style="margin-bottom: 25px;">
            <div style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
                <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);">Customer</h3>
            </div>
            <div style="padding: 20px;">
                <p><strong><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></strong></p>
                <p style="color: var(--color-gray); font-size: 0.9rem;"><?php echo htmlspecialchars($order['email']); ?></p>
                <p style="color: var(--color-gray); font-size: 0.9rem;"><?php echo htmlspecialchars($order['phone'] ?? 'N/A'); ?></p>
            </div>
        </div>

        <div class="admin-table-wrapper">
            <div style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
                <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);">Shipping Address</h3>
            </div>
            <div style="padding: 20px;">
                <p><strong><?php echo htmlspecialchars($order['ship_name']); ?></strong></p>
                <p><?php echo htmlspecialchars($order['ship_line1']); ?></p>
                <?php if ($order['ship_line2']): ?>
                <p><?php echo htmlspecialchars($order['ship_line2']); ?></p>
                <?php endif; ?>
                <p><?php echo htmlspecialchars($order['ship_city'] . ', ' . $order['ship_state'] . ' - ' . $order['ship_postal']); ?></p>
                <p><?php echo htmlspecialchars($order['ship_country']); ?></p>
                <p style="margin-top: 10px;"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($order['ship_phone']); ?></p>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="content-header">
    <h2><i class="fas fa-shopping-cart"></i> Orders</h2>
    <div style="display: flex; gap: 10px;">
        <a href="orders.php" class="btn btn-small <?php echo !$statusFilter ? 'btn-primary' : 'btn-outline'; ?>">All</a>
        <?php foreach (['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $s): ?>
        <a href="orders.php?status=<?php echo $s; ?>" class="btn btn-small <?php echo $statusFilter === $s ? 'btn-primary' : 'btn-outline'; ?>">
            <?php echo ucfirst($s); ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($orders as $order):

                $orderId = (int)$order['order_id'];

                $itemCountSql = "SELECT COUNT(*) AS count FROM order_items WHERE order_id = $orderId";

                $itemCountResult = mysqli_query($conn, $itemCountSql);

                $itemCount = 0;

                if ($itemCountResult) {
                    $itemCountRow = mysqli_fetch_assoc($itemCountResult);
                    $itemCount = (int)$itemCountRow['count'];
                }

            ?>
            <tr>
                <td>
                    <strong>
                        <?php echo htmlspecialchars($order['order_number']); ?>
                    </strong>
                </td>

                <td>
                    <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                </td>

                <td>
                    <?php echo $itemCount; ?>
                    item<?php echo $itemCount !== 1 ? 's' : ''; ?>
                </td>

                <td>
                    <strong><?php echo formatPrice($order['final_amount']); ?></strong>
                </td>

                <td>
                    <span class="status-badge <?php echo htmlspecialchars($order['status']); ?>">
                        <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                    </span>
                </td>

                <td>
                    <span class="status-badge <?php echo htmlspecialchars($order['payment_status']); ?>">
                        <?php echo ucfirst(htmlspecialchars($order['payment_status'])); ?>
                    </span>
                </td>

                <td>
                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                </td>

                <td>

                    <div class="table-actions">

                        <a href="orders.php?action=view&id=<?php echo $order['order_id']; ?>"
                           class="view-btn"
                           title="View">

                            <i class="fas fa-eye"></i>

                        </a>

                    </div>

                </td>

            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&status=<?php echo $statusFilter; ?>" class="<?php echo $i === $page ? 'current' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
