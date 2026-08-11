<?php

require_once __DIR__ . '/../config/config.php';
requireLogin();

$pageTitle = 'Shopping Cart';

$userId = (int)getCurrentUserId();

// Get cart items with product details
$sql = "SELECT ci.*, p.product_name,p.slug,
               p.price,
               p.sale_price,
               p.stock_quantity,
               cat.category_name,
               pi.image_path AS primary_image
        FROM cart crt
        JOIN cart_items ci
            ON crt.cart_id = ci.cart_id
        JOIN products p
            ON ci.product_id = p.product_id
        JOIN categories cat
            ON p.category_id = cat.category_id
        LEFT JOIN product_images pi
            ON p.product_id = pi.product_id
            AND pi.is_primary = 1
        WHERE crt.user_id = $userId";

$result = mysqli_query($conn, $sql);

$cartItems = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $cartItems[] = $row;
    }
}

// Calculate totals
$subtotal = 0;
$discount = 0;
foreach ($cartItems as $item) {
    $price = $item['sale_price'] ?? $item['price'];
    $subtotal += $price * $item['quantity'];
    if ($item['sale_price'] && $item['sale_price'] < $item['price']) {
        $discount += ($item['price'] - $item['sale_price']) * $item['quantity'];
    }
}

$shipping = $subtotal > 999 ? 0 : 99;
$total = $subtotal + $shipping;

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/cart-checkout.css">';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="cart-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-shopping-bag"></i> Shopping Cart</h1>
            <p><?php echo count($cartItems); ?> item<?php echo count($cartItems) !== 1 ? 's' : ''; ?> in your cart</p>
        </div>

        <?php if (!empty($cartItems)): ?>
        <div class="cart-container">
            <div class="cart-items">
                <?php foreach ($cartItems as $item): 
                    $itemPrice = $item['sale_price'] ?? $item['price'];
                    $itemTotal = $itemPrice * $item['quantity'];
                ?>
                <div class="cart-item" data-cart-item="<?php echo $item['cart_item_id']; ?>">
                    <div class="cart-item-image">
                        <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $item['primary_image'] ?? 'placeholder.jpg'; ?>" 
                             alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                    </div>
                    <div class="cart-item-details">
                        <span class="category"><?php echo htmlspecialchars($item['category_name']); ?></span>
                        <h3><a href="product.php?slug=<?php echo $item['slug']; ?>"><?php echo htmlspecialchars($item['product_name']); ?></a></h3>
                        <div class="cart-item-actions">
                            <div class="quantity-control">
                                <button type="button" class="qty-minus"><i class="fas fa-minus"></i></button>
                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" data-item-id="<?php echo $item['cart_item_id']; ?>">
                                <button type="button" class="qty-plus"><i class="fas fa-plus"></i></button>
                            </div>
                            <a href="#" class="remove-btn remove-cart-item" data-item-id="<?php echo $item['cart_item_id']; ?>">
                                <i class="fas fa-trash"></i> Remove
                            </a>
                        </div>
                    </div>
                    <div class="cart-item-price">
                        <div class="price"><?php echo formatPrice($itemTotal); ?></div>
                        <?php if ($item['sale_price'] && $item['sale_price'] < $item['price']): ?>
                        <div class="original-price"><?php echo formatPrice($item['price'] * $item['quantity']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span><?php echo formatPrice($subtotal); ?></span>
                </div>

                <?php if ($discount > 0): ?>
                <div class="summary-row discount">
                    <span>Discount</span>
                    <span>-<?php echo formatPrice($discount); ?></span>
                </div>
                <?php endif; ?>

                <div class="summary-row">
                    <span>Shipping</span>
                    <span><?php echo $shipping > 0 ? formatPrice($shipping) : 'FREE'; ?></span>
                </div>

                <div class="summary-row total">
                    <span>Total</span>
                    <span class="cart-total-amount"><?php echo formatPrice($total); ?></span>
                </div>

                <?php if ($shipping > 0): ?>
                <p style="font-size: 0.85rem; color: var(--color-gray); margin-top: 10px;">
                    <i class="fas fa-info-circle"></i> Add <?php echo formatPrice(999 - $subtotal); ?> more for free shipping!
                </p>
                <?php endif; ?>

                <a href="checkout.php" class="btn btn-primary btn-large">
                    <i class="fas fa-credit-card"></i> Proceed to Checkout
                </a>

                <a href="shop.php" class="btn btn-outline" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
        <?php else: ?>
        <div class="cart-empty">
            <i class="fas fa-shopping-basket"></i>
            <h3>Your Cart is Empty</h3>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="shop.php" class="btn btn-primary btn-large" style="background-color: var(--color-pink)">
                <i class="fas fa-shopping-bag" style = "color: var(--color-brown); font-size:2rem; margin-bottom:5px;"></i> Start Shopping
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
