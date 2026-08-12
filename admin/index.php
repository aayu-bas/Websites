<?php

$adminTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Recent orders
$recentOrders = [];

$sql = "SELECT o.*, u.first_name, u.last_name, u.email
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        ORDER BY o.created_at DESC
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recentOrders[] = $row;
    }
}
// Recent custom requests

$recentCustom = [];

$sql = "SELECT ccr.*, 
               u.first_name,
               u.last_name
        FROM custom_crochet_requests ccr
        JOIN users u ON ccr.user_id = u.user_id
        ORDER BY ccr.created_at DESC
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recentCustom[] = $row;
    }
}
// Top products

$topProducts = [];

$sql = "SELECT p.product_name, p.slug,
               COUNT(oi.order_item_id) AS order_count,
               SUM(oi.quantity) AS total_sold
        FROM order_items oi
        JOIN products p ON oi.product_id = p.product_id
        GROUP BY p.product_id
        ORDER BY total_sold DESC
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $topProducts[] = $row;
    }
}

?>

<div class="content-header">
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
    <span style="color: var(--color-gray);"><?php echo date('l, F j, Y'); ?></span>
</div>

<!-- Stats Cards -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon orders">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['total_orders']); ?></h3>
            <p>Total Orders</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon products">
            <i class="fas fa-box-open"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['total_products']); ?></h3>
            <p>Active Products</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon customers">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo number_format($stats['total_customers']); ?></h3>
            <p>Customers</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon revenue">
            <i class="fas fa-rupee-sign"></i>
        </div>
        <div class="stat-info">
            <h3><?php echo formatPrice($stats['total_revenue']); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-top: 30px;">
    <!-- Recent Orders -->
    <div class="admin-table-wrapper">
        <div style="padding: 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);"><i class="fas fa-clock"></i> Recent Orders</h3>
            <a href="orders.php" class="btn btn-small btn-outline">View All</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentOrders as $order): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                    <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
                    <td><?php echo formatPrice($order['final_amount']); ?></td>
                    <td><span class="status-badge <?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Recent Custom Requests -->
    <div class="admin-table-wrapper">
        <div style="padding: 20px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);"><i class="fas fa-magic"></i> Custom Requests</h3>
            <a href="custom-requests.php" class="btn btn-small btn-outline">View All</a>
        </div>
        <div style="padding: 20px;">
            <?php foreach ($recentCustom as $custom): ?>
            <div style="padding: 15px 0; border-bottom: 1px solid #f0f0f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <strong style="color: var(--color-dark-brown);"><?php echo ucfirst($custom['product_type']); ?></strong>
                    <span class="status-badge <?php echo $custom['status']; ?>"><?php echo ucfirst($custom['status']); ?></span>
                </div>
                <p style="font-size: 0.85rem; color: var(--color-gray); margin-bottom: 5px;">
                    <?php echo htmlspecialchars($custom['first_name'] . ' ' . $custom['last_name']); ?> • 
                    <?php echo formatPrice($custom['budget']); ?>
                </p>
                <p style="font-size: 0.8rem; color: #aaa;"><?php echo date('M d, Y', strtotime($custom['created_at'])); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Top Products -->
<div class="admin-table-wrapper" style="margin-top: 25px;">
    <div style="padding: 20px; border-bottom: 1px solid #f0f0f0;">
        <h3 style="font-size: 1.1rem; color: var(--color-dark-brown);"><i class="fas fa-fire"></i> Top Selling Products</h3>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Orders</th>
                <th>Total Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topProducts as $product): ?>
            <tr>
                <td><a href="<?php echo SITE_URL; ?>/pages/product.php?slug=<?php echo $product['slug']; ?>" target="_blank" style="color: var(--color-purple); font-weight: 500;"><?php echo htmlspecialchars($product['product_name']); ?></a></td>
                <td><?php echo $product['order_count']; ?></td>
                <td><?php echo $product['total_sold']; ?> units</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
