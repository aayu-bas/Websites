<?php 
require_once __DIR__ . '/../config/config.php';
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
                    <img src="yarnify.png" alt="logo" class="logo-image">
                    <p class="brand-name">Yarnify</p>
                </div>
            </a>
            <ul class="nav-links">
                <li><a href="<?php echo SITE_URL; ?>/index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/shop.php" class="<?php echo $currentPage === 'shop' ? 'active' : ''; ?>">Shop</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/custom_designer.php" >Custom Order</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/about.php">About</a></li>
                <li><a href="<?php echo SITE_URL; ?>/pages/contact.php">Contact</a></li>
            </ul>
    
            <div class="nav-actions">
                <a href="<?php echo SITE_URL; ?>/pages/search.php" class="nav-icon" title="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>/pages/wishlist.php" class="nav-icon" title="Wishlist" >
                    <i class="fa-solid fa-heart"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>/pages/cart.php" class="nav-icon cart-icon" title="Cart" >
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/profile.php" class="nav-icon" title="Wishlist" >
                    <i class="fa-solid fa-user"></i>
                </a>

                <a href="<?php echo SITE_URL; ?>/pages/login.php" class="btn-login btn-small btn-primary">Login</a>

                <button class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>
</body>
</html>