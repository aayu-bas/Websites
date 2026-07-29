<?php 
require_once __DIR__ . '/../config/config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$wishlistCount = 0;
$cartCount = 0;
// $categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle)? htmlspecialchars($pageTitle). '| ': '';?>Yarnify- Handmade Crochet Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="icon" href="yarnify.png">
    <?php if (isset($extraCSS)) echo $extraCSS; ?>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                <div class="logo-img">
                    <img src="<?php echo ASSETS_URL; ?>/images/yarnify.png" alt="logo" class="logo-image">
                    <p class="brand-name">Yarnify</p>
                </div>
            </a>
            <ul class="nav-links">
                <li><a href="<?php echo SITE_URL; ?>/index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/shop.php" class="<?php echo $currentPage === 'shop' ? 'active' : ''; ?>">Shop</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/custom_designer.php"class="<?php echo $currentPage === 'custom_design' ? 'active' : ''; ?>">Custom Order</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/about.php" class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/contact.php" class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
            </ul>
    
            <div class="nav-actions">
                <a href="<?php echo SITE_URL; ?>/pages/search.php" class="nav-icon" title="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>

                <!-- <a href="<?php echo SITE_URL; ?>/pages/wishlist.php" class="nav-icon" title="Wishlist" >
                    <i class="fa-solid fa-heart"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="nav-icon cart-icon" title="Cart" >
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="nav-icon" title="Wishlist" >
                    <i class="fa-solid fa-user"></i>
                </a> -->
                <?php if (isLoggedIn()): ?>
                <a href="<?php echo SITE_URL; ?>/pages/wishlist.php" class="nav-icon" title="Wishlist">
                    <i class="fas fa-heart"></i>
                    <?php if ($wishlistCount > 0): ?>
                    <span class="badge wishlist-badge"><?php echo $wishlistCount; ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="nav-icon cart-icon" title="Cart">
                    <i class="fas fa-shopping-bag"></i>
                    <?php if ($cartCount > 0): ?>
                    <span class="badge cart-badge"><?php echo $cartCount; ?></span>
                    <?php endif; ?>
                </a>
                <!-- <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="nav-icon" title="My Account">
                    <i class="fas fa-user"></i>
                </a> -->
                <div class="user-menu">
                    <button type="button" class="user-btn">
                        <i class="fas fa-user"></i>
                        <!-- <i class="fas fa-chevron-down dropdown-arrow"></i> -->
                    </button>

                    <div class="user-dropdown">
                        <div class="dropdown-header">
                            <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                            <small><?php echo htmlspecialchars($_SESSION['user_email']); ?></small>
                        </div>
                        <a href="<?php echo SITE_URL; ?>/pages/profile.php">
                            <i class="fas fa-user-circle"></i>
                            My Profile
                        </a>
                        <a href="<?php echo SITE_URL; ?>/pages/orders.php">
                            <i class="fas fa-box"></i>
                            My Orders
                        </a>
                        <a href="<?php echo SITE_URL; ?>/pages/settings.php">
                            <i class="fas fa-cog"></i>
                            Account Settings
                        </a>
                        <hr>
                        <a href="<?php echo SITE_URL; ?>/includes/logout.php" class="logout-link" style="background-color: white;">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?php echo SITE_URL; ?>/pages/login.php" class="btn btn-small btn-primary">Login</a>
                <?php endif; ?>

                <!-- <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button> -->
            </div>
        </div>
    </nav>
    <script>
    document.addEventListener("DOMContentLoaded", function () {

    const userBtn = document.querySelector(".user-btn");
    const dropdown = document.querySelector(".user-dropdown");

    if(userBtn){

    userBtn.addEventListener("click", function(e){
        e.stopPropagation();
        dropdown.classList.toggle("show");
    });

    document.addEventListener("click", function(){
        dropdown.classList.remove("show");
    });

    }

    });

    </script>
</body>
</html>