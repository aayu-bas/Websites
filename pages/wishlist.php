<?php

require_once __DIR__ . '/../config/config.php';
requireLogin();

$pageTitle = 'My Wishlist';

$userId = (int)getCurrentUserId();

$sql = "SELECT w.*, p.product_name, p.slug, p.price, p.sale_price, p.stock_quantity,
               c.category_name, pi.image_path as primary_image
        FROM wishlist w
        JOIN products p ON w.product_id = p.product_id
        JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        WHERE w.user_id = $userId
        ORDER BY w.added_at DESC";

$result = mysqli_query($conn, $sql);

$wishlistItems = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $wishlistItems[] = $row;
    }
}
$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/cart-checkout.css">';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="wishlist-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-heart"></i> My Wishlist</h1>
            <p><?php echo count($wishlistItems); ?> item<?php echo count($wishlistItems) !== 1 ? 's' : ''; ?> saved</p>
        </div>

        <?php if (!empty($wishlistItems)): ?>
        <div class="wishlist-grid">
            <?php foreach ($wishlistItems as $item): ?>
            <div class="wishlist-item" id="wishlist-item-<?php echo $item['product_id']; ?>">
                <a href="#" class="remove-wishlist" onclick="removeWishlistItem(<?php echo $item['product_id']; ?>); return false;" title="Remove from Wishlist">
                    <i class="fas fa-times"></i>
                </a>
                <div class="product-image">
                    <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $item['primary_image'] ?? 'placeholder.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                </div>
                <div class="product-info" style="padding: 20px;">
                    <span class="product-category"><?php echo htmlspecialchars($item['category_name']); ?></span>
                    <a href="product.php?slug=<?php echo $item['slug']; ?>" class="product-name">
                        <?php echo htmlspecialchars($item['product_name']); ?>
                    </a>
                    <div class="product-price" style="margin-top: 10px;">
                        <?php if ($item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                        <span class="price"><?php echo formatPrice($item['sale_price']); ?></span>
                        <span class="price-original"><?php echo formatPrice($item['price']); ?></span>
                        <?php else: ?>
                        <span class="price"><?php echo formatPrice($item['price']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 15px; display: flex; gap: 10px;">
                        <button class="btn btn-small btn-primary" onclick="addWishlistToCart(<?php echo $item['product_id']; ?>, 1)" style="flex: 1;">
                            <i class="fas fa-shopping-bag"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="cart-empty">
            <i class="fas fa-heart-broken" style="color: var(--color-brown)"></i>
            <h3>Your Wishlist is Empty</h3>
            <p>Save your favorite items to your wishlist and come back to them anytime.</p>
            <a href="shop.php" class="btn btn-primary btn-large">
                <i class="fas fa-shopping-bag" style="font-size:2.3rem; margin-top:12px;"></i> Explore Products
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Get the base URL from PHP for reliable path resolution
const BASE_URL = '<?php echo SITE_URL; ?>';

function addWishlistToCart(productId, quantity) {
    fetch(BASE_URL + '/includes/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=add&product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Added to cart!');
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = data.cart_count;
                cartBadge.style.display = data.cart_count > 0 ? 'flex' : 'none';
            }
        } else {
            alert(data.message || 'Failed to add to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
}

function removeWishlistItem(productId) {
    fetch(BASE_URL + '/includes/wishlist_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=toggle&product_id=' + productId
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Removed from wishlist!');
            const item = document.getElementById('wishlist-item-' + productId);
            if (item) {
                item.style.transition = 'all 0.3s ease';
                item.style.opacity = '0';
                item.style.transform = 'translateX(100px)';
                setTimeout(() => item.remove(), 300);
            }
            const wishlistBadge = document.querySelector('.wishlist-badge');
            if (wishlistBadge) {
                wishlistBadge.textContent = data.wishlist_count;
                wishlistBadge.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
            }
        } else {
            alert(data.message || 'Failed to remove');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>