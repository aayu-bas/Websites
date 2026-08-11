<?php
require_once __DIR__ . '/../config/config.php';

$cartCount = getCartCount();

$slug= $_GET['slug'] ?? '';

if(empty($slug)){
    echo "Product not found!";
    // redirect(SITE_URL . '/pages/shop.php');
}

$product = getproductBySlug($slug);

if(!$product){
    redirect(SITE_URL . '/pages/shop.php');
}
$productId = (int)$product['product_id'];
$pageTitle= $product['product_name'];

//get product images
$images= getProductImages($product['product_id']);

//review part

//related products
$categoryId = (int)$product['category_id'];

$sql = "SELECT p.*, c.category_name, pi.image_path AS primary_image
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id
        AND pi.is_primary = 1
        WHERE p.category_id = $categoryId
        AND p.product_id != $productId
        AND p.is_active = 1
        LIMIT 4";

$result = mysqli_query($conn, $sql);

$relatedProducts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $relatedProducts[] = $row;
}

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/product.css">';

require_once __DIR__ . '/../includes/header.php';

?>
<!-- //breadcrumb -->
<div class="product-detail-page">
    <div class="container">
        <div style= "margin-top: 12px;margin-bottom: 20px; font-size:0.9rem; color:var(--color-gray);" >
            <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
            <i class="fas fa-chevron-right" style="margin: 0 8px; font-size: 0.7rem;"></i>
            <a href="<?php echo SITE_URL; ?>/pages/shop.php?category=<?php echo $product['category_slug']; ?>">
                <?php echo htmlspecialchars($product['category_name']); ?>
            </a>   
            <i class="fas fa-chevron-right" style="margin: 0 8px; font-size: 0.7rem;"></i>
            <span><?php echo htmlspecialchars($product['product_name']); ?></span>
        </div>
        <div class="product-detail-grid">
            <div class="product-images">
                <div class="main-image">
                    <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $images[0]['image_path'] ?? 'placeholder.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                         id="main-product-image">
                </div>
                <?php if(count($images)>1): ?>
                    <div class="thumbnail-images">
                    <?php foreach ($images as $index => $image): ?>
                    <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $image['image_path']; ?>" 
                             alt="<?php echo htmlspecialchars($image['alt_text'] ?? $product['product_name']); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="product-info-detail" data-product-id="<?php echo $product['product_id']; ?>">
                <span class="category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
                <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>

                <!-- product rating -->
                <div class="price-detail">
                    <?php if($product['sale_price'] && $product['sale_price']<$product['price']):
                        $discount = round((($product['price']-$product['sale_price'])/$product['price'])*100);
                        ?>
                        <span class="current-price"><?php echo formatPrice($product['sale_price']); ?></span>
                    <span class="original-price"><?php echo formatPrice($product['price']); ?></span>
                    <span class="discount-badge">-<?php echo $discount; ?>% OFF</span>
                    <?php else: ?>
                    <span class="current-price"><?php echo formatPrice($product['price']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="product-description">
                    <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                </div>
                <div class="product-meta">
                    <?php if($product['sku']): ?>
                        <div class="meta-item">
                            <i class="fas fa-barcode"></i>
                            <span>SKU: <?php echo htmlspecialchars($product['sku']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($product['materials']): ?>
                        <div class="meta-item">
                            <i class="fas fa-layer-group"></i>
                            <span><?php echo htmlspecialchars($product['materials']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($product['weight']): ?>
                        <div class="meta-item">
                            <i class="fas fa-weight-hanging"></i>
                            <span>Weight: <?php echo htmlspecialchars($product['weight']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if ($product['dimensions']): ?>
                        <div class="meta-item">
                            <i class="fas fa-ruler-combined"></i>
                            <span>Size: <?php echo htmlspecialchars($product['dimensions']); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="meta-item">
                        <i class="fas fa-boxes"></i>
                        <span>Stock: <?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] . ' available' : 'Out of stock'; ?></span>
                    </div>
                </div>
                <?php if($product['stock_quantity']>0):?>
                <div class="quantity-selector">
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-control">
                        <button type="button" class="detail-qty-minus"><i class="fas fa-minus"></i></button>
                        <input type="number" class="quantity-input" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" data-max="<?php echo $product['stock_quantity']; ?>">
                        <button type="button" class="detail-qty-plus"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <div class="product-actions-detail ">
                    <button class="btn btn-cart btn-large add-to-cart-btn" data-product-id="<?php echo $product['product_id']; ?>" title="Add to Cart">
                        <i class="fas fa-shopping-bag"></i> Add to Cart
                    </button>
                    <button class="btn btn-wishlist btn-large" onclick="toggleWishlistDetail(<?php echo $product['product_id']; ?>)">
                        <i class="fas fa-heart"></i> Wishlist
                    </button>
                </div>
                <?php else: ?>
                <div class="btn btn-large" style="background: var(--color-light-gray); color: var(--color-gray); cursor: not-allowed;">
                    <i class="fas fa-times-circle"></i> Out of Stock
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="description">Description</button>
                <!-- <button class="tab-btn" data-tab="reviews">Reviews (<?php echo $rating['total']; ?>)</button> -->
                <button class="tab-btn" data-tab="shipping">Shipping Info</button>
            </div>

            <div class="tab-content active" id="description">
                <h3>About this Product</h3>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <?php if ($product['care_instructions']): ?>
                <h3 style="margin-top: 25px;">Care Instructions</h3>
                <p><?php echo nl2br(htmlspecialchars($product['care_instructions'])); ?></p>
                <?php endif; ?>
            </div>

            <div class="tab-content" id="shipping">
                <h3>Shipping Information</h3>
                <ul style="list-style: none; line-height: 2;">
                    <li><i class="fas fa-truck" style="color: var(--color-purple); margin-right: 10px;"></i> Free shipping on orders over ₹999</li>
                    <li><i class="fas fa-clock" style="color: var(--color-purple); margin-right: 10px;"></i> Orders processed within 2-3 business days</li>
                    <li><i class="fas fa-box" style="color: var(--color-purple); margin-right: 10px;"></i> Standard delivery: 5-7 business days</li>
                    <li><i class="fas fa-undo" style="color: var(--color-purple); margin-right: 10px;"></i> 7-day return policy for unused items</li>
                    <li><i class="fas fa-shield-alt" style="color: var(--color-purple); margin-right: 10px;"></i> All items are carefully packaged to prevent damage</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>