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

    <section class="section-padding" style="background-color:white">
        <div class="container">
            <div class="section-header">
                <h2>Featured Products</h2>
                <p>My Most Beloved Creations</p>
            </div>
        </div>
    </section>

   <!-- <div id="product-sections"></div>

    <!-- pop up cart -->
    <div class="modal" id="cartModal">
        <div class="modal-content">
            <button onclick="closeModal()" id="closed">✕</button>
            <div id="bear-icon">🧸</div>
            <h3>⋆｡‧˚ʚAdded to the Cartɞ˚‧｡⋆</h3>
            <p id="productname"></p>
            <button class="checkout" onclick="checkout()">
                Go to Checkout
            </button>
        </div>
    </div> -->

    <div class="browse">
        <p>Browse to other categories<span class="pointer" onclick="result()">→</span></p>
    </div>




    <!-- ==============footer==================== -->
    <footer>
        <div class="footer-container">
            <div class="newsletter">
                <h3>Stay  Updated! <i class="fa-solid fa-envelope"></i></h3>
                <p>Get Free patterns and crochet tips delivered to your inbox! ^^</p>
                <form action="post" class="mail">
                    <input type="email" name="Email" id="" placeholder="Your email address" required/>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            <div class="footer-content">
                <div class="footer-section">
                    <h3>ABOUT</h3>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li> <a href="#">Support</a></li>
                        <li><a href="#">Help Center</a></li>
                    </ul>  
                </div>

                <div class="footer-section">
                    <h3>LEARN</h3>
                    <ul>
                        <li><a href="#">Tools Required</a></li>
                        <li><a href="#">Beginner Tutorials</a></li>
                        <li><a href="#">Video Guides</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>SUPPORT</h3>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Shipping Info</a></li>
                        <li><a href="#">Return Policy</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>LEGAL</h3>
                    <ul>
                        <li><a href="#">Terms and Condition</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">Cookie Setting</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">General Product Safety Return</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    
                    <h3>CONNECT WITH US</h3>
                    <div class="social-links"></div>
                        <a href="#"><i class="fa-brands fa-instagram fa-xl"></i></i></a>
                        <a href="#"><i class="fa-brands fa-facebook fa-xl"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube fa-xl"></i></a>
                        <a href="#"><i class="fa-brands fa-pinterest fa-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <p>Made with Love and Care by Yarnify &copy; 2025</p>
            </div>
        </div>
    </footer>
    <script src="assets/js/script.js"></script>
</body>
</html>