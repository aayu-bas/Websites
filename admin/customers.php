<?php

$adminTitle = 'Customers';

require_once __DIR__ . '/includes/header.php';

$page = max(1, intval($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');

// Build WHERE condition
$where = "WHERE 1=1";

if ($search) {
    // Escape search value
    $search = mysqli_real_escape_string($conn, $search);

    $where .= " AND (
        u.first_name LIKE '%$search%'
        OR u.last_name LIKE '%$search%'
        OR u.email LIKE '%$search%'
    )";
}


// Get total number of customers
$countSql = "SELECT COUNT(*) AS count FROM users u $where
";

$countResult = mysqli_query($conn, $countSql);

$countRow = mysqli_fetch_assoc($countResult);

$total = $countRow['count'];


// Calculate pagination
$totalPages = ceil($total / PRODUCTS_PER_PAGE);

$offset = ($page - 1) * PRODUCTS_PER_PAGE;


// Get customers
$customersSql = "SELECT u.*, COUNT(DISTINCT o.order_id) AS order_count, COALESCE(SUM(o.final_amount), 0) AS total_spent
    FROM users u
    LEFT JOIN orders o
        ON u.user_id = o.user_id
    $where
    GROUP BY u.user_id
    ORDER BY u.created_at DESC
    LIMIT $offset, " . PRODUCTS_PER_PAGE;

$customersResult = mysqli_query($conn, $customersSql);

$customers = [];

if ($customersResult) {

    while ($row = mysqli_fetch_assoc($customersResult)) {
        $customers[] = $row;
    }
}

?>
<div class="content-header">
    <h2><i class="fas fa-users"></i> Customers</h2>
    <span style="color: var(--color-gray);"><?php echo $total; ?> total customers</span>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Total Spent</th>
                <th>Joined</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td>
                    <strong><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></strong>
                </td>
                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                <td><?php echo htmlspecialchars($customer['phone'] ?? 'N/A'); ?></td>
                <td><?php echo $customer['order_count']; ?></td>
                <td><?php echo formatPrice($customer['total_spent']); ?></td>
                <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                <td>
                    <span class="status-badge <?php echo $customer['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $customer['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo $i === $page ? 'current' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>