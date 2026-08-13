<?php

require_once __DIR__ . '/../../config/config.php';

requireAdminLogin();

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$adminRole = $_SESSION['admin_role'] ?? 'admin';

$stats = [];


// Total orders
$sql = "SELECT COUNT(*) AS count FROM orders";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$stats['total_orders'] = $row['count'] ?? 0;


// Total active products
$sql = "SELECT COUNT(*) AS count
        FROM products
        WHERE is_active = 1";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$stats['total_products'] = $row['count'] ?? 0;


// Total active customers
$sql = "SELECT COUNT(*) AS count
        FROM users
        WHERE is_active = 1";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$stats['total_customers'] = $row['count'] ?? 0;


// Total revenue from delivered orders
$sql = "SELECT COALESCE(SUM(final_amount), 0) AS total
        FROM orders
        WHERE status = 'delivered'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$stats['total_revenue'] = $row['total'] ?? 0;


// Pending orders
$sql = "SELECT COUNT(*) AS count
        FROM orders
        WHERE status = 'pending'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$stats['pending_orders'] = $row['count'] ?? 0;


// Pending custom crochet requests
$sql = "SELECT COUNT(*) AS count
        FROM custom_crochet_requests
        WHERE status = 'pending'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$stats['pending_custom'] = $row['count'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($adminTitle) ? htmlspecialchars($adminTitle) . ' | ' : ''; ?>Yarnify Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ADMIN_ASSETS_URL; ?>/css/admin.css">
    <?php if (isset($adminExtraCSS)) echo $adminExtraCSS; ?>
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <div class="logo-img">
                    <img src="<?php echo ASSETS_URL; ?>/images/yarnify.png" alt="logo" class="logo-image">
                </div>
                <h3>Yarnify</h3>
            </div>

            <ul class="admin-nav">
                <li><a href="index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                </a></li>
                <li><a href="products.php" class="<?php echo $currentPage === 'products' ? 'active' : ''; ?>">
                    <i class="fas fa-box-open"></i> <span>Products</span>
                </a></li>
                <li><a href="categories.php" class="<?php echo $currentPage === 'categories' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i> <span>Categories</span>
                </a></li>
                <li><a href="orders.php" class="<?php echo $currentPage === 'orders' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i> <span>Orders</span>
                    <?php if ($stats['pending_orders'] > 0): ?>
                    <span style="background: var(--color-pink); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; margin-left: auto;"><?php echo $stats['pending_orders']; ?></span>
                    <?php endif; ?>
                </a></li>
                <li><a href="customers.php" class="<?php echo $currentPage === 'customers' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> <span>Customers</span>
                </a></li>
                <li><a href="custom-requests.php" class="<?php echo $currentPage === 'custom-requests' ? 'active' : ''; ?>">
                    <i class="fas fa-magic"></i> <span>Custom Requests</span>
                    <?php if ($stats['pending_custom'] > 0): ?>
                    <span style="background: var(--color-pink); color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; margin-left: auto;"><?php echo $stats['pending_custom']; ?></span>
                    <?php endif; ?>
                </a></li>
                <li><a href="reviews.php" class="<?php echo $currentPage === 'reviews' ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i> <span>Reviews</span>
                </a></li>
                <li style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                    <a href="<?php echo SITE_URL; ?>/index.php" target="_blank">
                        <i class="fas fa-store"></i> <span>View Store</span>
                    </a>
                </li>
                <li>
                    <a href="../includes/logout.php">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="toggle-sidebar" onclick="document.getElementById('adminSidebar').classList.toggle('collapsed')">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search..." class="table-search">
                    </div>
                </div>
                <div class="header-right">
                    <div class="admin-profile">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--color-pink), var(--color-yellow)); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--color-dark-brown);">
                            <?php echo strtoupper(substr($_SESSION['admin_name'], 0, 1)); ?>
                        </div>
                        <div class="admin-profile-info">
                            <h4><?php echo htmlspecialchars($_SESSION['admin_name']); ?></h4>
                            <p><?php echo ucfirst($adminRole); ?></p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="admin-content">
