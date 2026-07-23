<?php
require_once __DIR__ . '/config/config.php';
$pageTitle='Home ';

global $conn;

$sale_sql = "SELECT p.*, c.category_name, c.slug AS category_slug,
               pi.image_path AS primary_image
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi
            ON p.product_id = pi.product_id
            AND pi.is_primary = 1
        WHERE p.is_on_sale = 1
        AND p.is_active = 1
        LIMIT 3";
$sale_result = mysqli_query($conn, $sale_sql);
$saleProducts = [];

while ($row = mysqli_fetch_assoc($sale_result)) {
    $saleProducts[] = $row;
}


$featured_sql = "SELECT p.*, c.category_name, c.slug AS category_slug,
               pi.image_path AS primary_image
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi
            ON p.product_id = pi.product_id
            AND pi.is_primary = 1
        WHERE p.is_featured = 1
        AND p.is_active = 1
        LIMIT 8";

$featured_result = mysqli_query($conn, $featured_sql);
$featuredProducts = [];

while ($row = mysqli_fetch_assoc($featured_result)) {
    $featuredProducts[] = $row;
}

$category_sql = "SELECT c.*,
               (SELECT COUNT(*)
                FROM products p
                WHERE p.category_id = c.category_id
                AND p.is_active = 1) AS product_count
        FROM categories c
        WHERE c.is_active = 1
        ORDER BY c.display_order";

$category_result = mysqli_query($conn, $category_sql);

$categories = [];

while ($row = mysqli_fetch_assoc($category_result)) {
    $categories[] = $row;
}

if (isset($_SESSION['login_time'])) {
    if ((time() - $_SESSION['login_time']) > $_SESSION['expire_time']) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
require_once __DIR__ . '/includes/header.php';

$slides=[
    [
        'spotlight'=> '# SPOTLIGHT 1',
        'title' => 'Handmade with Love',
        'subtitle' => 'Discover unique crochet creations crafted by skilled artisans. Each piece tells a story of patience and creativity.',
        'bg'=>'url(assets/images/slider/slider1.jpg)',
        'btn_text'=>'Shop All',
        'btn_link'=>'pages/shop.php'
    ],
    [
        'spotlight'=> '# SPOTLIGHT 2',
        'title' => 'Winter Crochet Products',
        'subtitle' => 'Warm up with our latest collection of crochet beanies, scarves, and cozy wearables. Perfect for the season!',
        'bg'=>'url(assets/images/slider/cardi.png)',
        'btn_text'=>'View Collection',
        'btn_link'=>'pages/shop.php'
    ],
    [
        'spotlight'=> '# SPOTLIGHT 3',
        'title' => 'Custom Crochet Orders',
        'subtitle' => 'Have something special in mind? Design your own custom crochet piece with our easy-to-use designer tool.',
        'bg' => 'url(assets/images/slider/slide3.jpg)',
        'btn_text' => 'Design Now',
        'btn_link' => 'pages/custom-designer.php' 
    ]
]
?>
<!DOCTYPE html>
<html>
<body>
    <section class="slideshow-container">
        <?php foreach ($slides as $index => $slide): ?>
        <div class="slide <?php echo $index === 0 ? 'active' : ''; ?>">
            <div class="slide-bg" style="background: <?php echo $slide['bg']; ?>"></div>
            <div class="slide-container">
                <div class="slide-content">
                    <h3><?php echo htmlspecialchars($slide['spotlight']); ?></h3>
                    <h2><?php echo htmlspecialchars($slide['title']); ?></h2>
                    <p><?php echo htmlspecialchars($slide['subtitle']); ?></p>
                    <a href="<?php echo SITE_URL . '/' . $slide['btn_link']; ?>" class="btn btn-large shop-primary">
                        <?php echo htmlspecialchars($slide['btn_text']); ?> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="slider-arrows">
            <button class="slider-arrow prev"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-arrow next"><i class="fas fa-chevron-right"></i></button>
        </div>
        </section>

        
    <?php if (!empty($saleProducts)): ?>
    <section class="sale-section">
        <div class="container">
            <div class="sale-header">
                <h2>Our Sales(40% OFF)🎉</h2>
            </div>
            <div class="products-grid">
                <?php foreach ($saleProducts as $product):
                    $discount = round((($product['price']-$product['sale_price'])/$product['price']) * 100);
                ?>
                <div class="card">
                    <div class="product-image">
                        <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $product['primary_image']?? 'placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <div class="product-badges">
                            <span class="badge badge-sale">-<?php echo $discount; ?>%</span>
                        </div>
                        <div class="product-actions">
                            <button class="action-btn add-to-wishlist-btn" title="Add to Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                            
                            <button class="action-btn add-to-cart-btn" title="Add to Cart">
                                <i class="fas fa-shopping-bag"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <span class="product-category"><?php echo htmlspecialchars($product['category_name']);?></span>
                        <a href="<php echo SITE_URL; ?>/pages/product.php?>" class="product-name">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </a>
                        <div class="product-price">
                            <span class="price"><?php echo formatPrice($product['sale_price']); ?></span>
                            <span class="price-original"><?php echo formatPrice($product['price']);?></span>
                            <span class="price-discount">-<?php echo $discount; ?>%</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <div class="about-me">
        <div id="inner-container">
            <div id="photo-container">
                <img src="us.jpeg" alt="" id="photo">
                <div id="story">
                    <h2>Our Story</h2>
                    <center><p>Hello Cuties(⁠｡⁠•̀⁠ᴗ⁠-⁠)⁠✧</p></center>
                    <p>Starting the bachelors, we started crocheting making cute keychains, charms and other stuff. We took joy in making gifts for our friends. Crocheting helped us find peace in everyday bustling life.
                        So, we started a crochet store where we can share our joy and comfort with you. <br><br>
                        Handmade with love. <br>
                        Inspired by creativity. <br>
                        Created for you<i class="fa-solid fa-heart fa-beat" style="color: #f7a6c9;"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- categories section -->
     <section class="categories-section section-padding">
        <div class="container">
            <div class="section-header">
                <h2>Shop By Category</h2>
                <p>Explore our variety of handmade crochet categories</p>
            </div>
            <div class="categories-grid">
                <?php foreach($categories as $category):
                $iconList =[
                    'amigurumi' => 'fa-ghost',
                    'wearables' => 'fa-hat-cowboy',
                    'decors' => 'fa-home',
                    'characters' => 'fa-book-reader',
                    'keychains' => 'fa-key'
                ];
                $icon = $iconList[$category['slug']] ?? 'fa-box';
                ?>
                <a href="<?php echo SITE_URL; ?>/pages/shop.php?category=<?php echo $category['slug'];?>" class="category-card">
                <div class="category-icon">
                    <i class="fas <?php echo $icon; ?>"></i>
                </div>
                <h3><?php echo htmlspecialchars($category['category_name']); ?></h3>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
     </section>
    <!-- featured products -->
    <section class="section-padding" style="background-color:white">
        <div class="container">
            <div class="section-header">
                <h2>Featured Products</h2>
                <p>Our Most Beloved Creations</p>
            </div>
            <div class="products-grid">
                <?php if (!empty($featuredProducts)): ?>
                    <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?php echo ASSETS_URL;?>/images/products/<?php echo $product['primary_image'] ?? 'placeholder.jpg'; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <div class="product-badges">
                            <?php if($product['is_on_sale']):?>
                                <span class="badge badge-sale">SALE</span>
                                <?php endif;?>
                                <span class="badge badge-featured">FEATURED</span>
                        </div>
                        <div class="product-actions">
                            <button class="action-btn add-to-wishlist-btn" title="Add to Wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="action-btn add-to-cart-btn" data-product-id="<?php echo $product['product_id']; ?>" title="Add to Cart">
                                <i class="fas fa-shopping-bag"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                        <a href="<?php echo SITE_URL; ?>/pages/product.php?>" class="product-name">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </a>
                        <div class="product-price">
                            <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                            <span class="price"><?php echo formatPrice($product['sale_price']); ?></span>
                            <span class="price-original"><?php echo formatPrice($product['price']); ?></span>
                            <?php else: ?>
                            <span class="price"><?php echo formatPrice($product['price']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
            </div>
            <div class="text-center" style="margin-top:40px;">
                <a href="<?php echo SITE_URL; ?>/pages/shop.php" class="btn btn-large btn-outline">
                View All Products <i class="fas fa-arrow-right"></i>
            </a>
            </div>
        </div>
    </section>

    <!-- custom crochet -->
     <section class="section-padding" style="background: linear-gradient(135deg, var(--color-light-pink), var(--color-yellow));">
        <div class="container">
            <div class="text-center"  style="max-width: 600px; margin: 0 auto;">
                <h2 style="font-size: 2.2rem; color: var(--color-dark-brown); margin-bottom: 15px;">
                Design Your Own Crochet!
            </h2>
            <p style="color: var(--color-gray); margin-bottom: 30px; font-size: 1.1rem;">
                Can't find what you're looking for? Create your own custom crochet design with our easy-to-use tool. Choose colors, size, and style!
            </p>
            <a href="<?php echo SITE_URL; ?>/pages/custom-designer.php" class="btn btn-large btn-primary">
                <i class="fas fa-magic"></i> Start Designing
            </a>
            </div>
        </div>
     </section>

     <?php require_once __DIR__ .  '/includes/footer.php';?>

</body>
</html>