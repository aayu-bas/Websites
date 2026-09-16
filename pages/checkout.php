<?php
require_once __DIR__ . '/../config/config.php';
requireLogin();

$pageTitle = 'Checkout';

$userId = (int)getCurrentUserId();

// Get cart items
$cartSql = "SELECT ci.*, p.product_name, p.slug, p.price, p.sale_price, p.stock_quantity,pi.image_path AS primary_image
    FROM cart c
    JOIN cart_items ci ON c.cart_id = ci.cart_id
    JOIN products p ON ci.product_id = p.product_id
    LEFT JOIN product_images pi 
    ON p.product_id = pi.product_id 
    AND pi.is_primary = 1
    WHERE c.user_id = $userId";

$cartResult = mysqli_query($conn, $cartSql);

$cartItems = [];

if ($cartResult) {
  while ($row = mysqli_fetch_assoc($cartResult)) {
    $cartItems[] = $row;
  }
}


if (empty($cartItems)) {
  setFlashMessage('warning','Your cart is empty. Add some items before checkout.');

  redirect(SITE_URL . '/pages/cart.php');
}


// Calculate totals
$subtotal = 0;

foreach ($cartItems as $item) {
  $price = $item['sale_price'] ?? $item['price'];
  $subtotal += $price * $item['quantity'];
}

$shipping = $subtotal > 999 ? 0 : 99;
$total = $subtotal + $shipping;


// Get user's addresses
$addressSql = "SELECT * FROM addresses WHERE user_id = $userId ORDER BY is_default DESC, created_at DESC";

$addressResult = mysqli_query($conn, $addressSql);

$addresses = [];

if ($addressResult) {
  while ($row = mysqli_fetch_assoc($addressResult)) {
    $addresses[] = $row;
  }
}


// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Verify CSRF token
  if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
    setFlashMessage('error', 'Invalid request.');
    redirect(SITE_URL . '/pages/checkout.php');
  }

  $shippingAddressId = intval($_POST['shipping_address'] ?? 0);
  $billingAddressId = intval($_POST['billing_address'] ?? 0);
  $paymentMethod = $_POST['payment_method'] ?? 'cod';
  $orderNotes = sanitize($_POST['order_notes'] ?? '');


  if (!$shippingAddressId) {
    setFlashMessage('error','Please select a shipping address.');

    redirect(SITE_URL . '/pages/checkout.php');
  }


    // Verify address belongs to current user
    $addressSql = "SELECT * FROM addresses WHERE address_id = $shippingAddressId AND user_id = $userId";

    $addressResult = mysqli_query($conn, $addressSql);

    $address = false;

    if ($addressResult) {
      $address = mysqli_fetch_assoc($addressResult);
    }


    if (!$address) {
      setFlashMessage('error','Invalid address selected.'
      );

    redirect(SITE_URL . '/pages/checkout.php');
    }


  // Use shipping address as billing if not specified
  if (!$billingAddressId) {
    $billingAddressId = $shippingAddressId;
  }

  $billingAddressId = (int)$billingAddressId;


  // Escape values that will be inserted as strings
  $paymentMethod = mysqli_real_escape_string($conn, $paymentMethod);
  $orderNotes = mysqli_real_escape_string($conn, $orderNotes);


  // Start MySQLi transaction
  mysqli_begin_transaction($conn);

  try {

    // Create order number
    $orderNumber = generateOrderNumber();
    $orderNumber = mysqli_real_escape_string($conn, $orderNumber);


    // Create order
    $orderSql = "INSERT INTO orders (user_id,order_number,total_amount,shipping_amount,final_amount,shipping_address_id,billing_address_id,notes)
    VALUES ($userId, '$orderNumber', $subtotal, $shipping, $total, $shippingAddressId, $billingAddressId, '$orderNotes')";

    if (!mysqli_query($conn, $orderSql)) {
      throw new Exception(mysqli_error($conn));
    }


    // Get newly created order ID
    $orderId = mysqli_insert_id($conn);

    // Create order items
    foreach ($cartItems as $item) {
      $productId = (int)$item['product_id'];
      $quantity = (int)$item['quantity'];

      $price = $item['sale_price'] ?? $item['price'];
      $price = (float)$price;

      $itemTotal = $price * $quantity;

      $productName = mysqli_real_escape_string($conn,$item['product_name']);


      $itemSql = "INSERT INTO order_items (order_id,product_id,product_name,quantity,unit_price,total_price)
      VALUES ($orderId,$productId,'$productName',$quantity,$price,$itemTotal)";

      if (!mysqli_query($conn, $itemSql)) {
        throw new Exception(mysqli_error($conn));
      }


      // Update stock
      $stockSql = "UPDATE products SET stock_quantity = stock_quantity - $quantity WHERE product_id = $productId";

      if (!mysqli_query($conn, $stockSql)) {
      throw new Exception(mysqli_error($conn));
      }
    }


    // Create payment record
    $paymentStatus = 'pending';

    $paymentSql = "INSERT INTO payments (order_id,payment_method,amount,status)
      VALUES ($orderId,'$paymentMethod',$total,'$paymentStatus')";

    if (!mysqli_query($conn, $paymentSql)) {
      throw new Exception(mysqli_error($conn));
    }


    // Get cart ID
    $cartSql = "SELECT cart_id FROM cart WHERE user_id = $userId";

    $cartResult = mysqli_query($conn, $cartSql);

    if (!$cartResult) {
        throw new Exception(mysqli_error($conn));
    }

    $cartRow = mysqli_fetch_assoc($cartResult);

    if (!$cartRow) {
      throw new Exception('Cart not found.');
    }

    $cartId = (int)$cartRow['cart_id'];

    $deleteCartSql = "DELETE FROM cart_items WHERE cart_id = $cartId";

    if (!mysqli_query($conn, $deleteCartSql)) {
      throw new Exception(mysqli_error($conn));
    }


    // Commit transaction
    mysqli_commit($conn);


    setFlashMessage('success','Order placed successfully! Your order number is: ' . $orderNumber);

    redirect(SITE_URL . '/pages/orders.php');


  } catch (Exception $e) {

    // Rollback if anything fails
    mysqli_rollback($conn);

    error_log(
      "Checkout error: " . $e->getMessage()
    );

    setFlashMessage('error','Order placement failed. Please try again.');

    redirect(SITE_URL . '/pages/checkout.php');
  }
}


$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/cart-checkout.css">';

require_once __DIR__ . '/../includes/header.php';

?>

<div class="checkout-page">
    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-credit-card"></i> Checkout</h1>
            <p>Complete your order by filling in the details below</p>
        </div>

        <form method="POST" action="" class="checkout-grid">
            <?php echo csrfField(); ?>

            <div class="checkout-left">
                <!-- Shipping Address -->
                <div class="checkout-section">
                    <h3><i class="fas fa-map-marker-alt"></i> Shipping Address</h3>

                    <?php if (!empty($addresses)): ?>
                    <div class="addresses-list">
                        <?php foreach ($addresses as $addr): ?>
                        <div class="address-card <?php echo $addr['is_default'] ? 'selected' : ''; ?>" 
                             onclick="selectAddress(this, 'shipping_address')">
                            <input type="radio" name="shipping_address" value="<?php echo $addr['address_id']; ?>" 
                                   <?php echo $addr['is_default'] ? 'checked' : ''; ?> style="display: none;">
                            <h4><?php echo htmlspecialchars($addr['full_name']); ?></h4>
                            <p><?php echo htmlspecialchars($addr['address_line1']); ?></p>
                            <?php if ($addr['address_line2']): ?>
                            <p><?php echo htmlspecialchars($addr['address_line2']); ?></p>
                            <?php endif; ?>
                            <p><?php echo htmlspecialchars($addr['city'] . ', ' . $addr['state'] . ' - ' . $addr['postal_code']); ?></p>
                            <p class="phone"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($addr['phone']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <a href="profile.php?tab=addresses" class="add-address-btn" target="_blank">
                        <i class="fas fa-plus"></i> Add New Address
                    </a>
                </div>

                <!-- Payment Method -->
                <div class="checkout-section">
                    <h3><i class="fas fa-wallet"></i> Payment Method</h3>

                    <div class="payment-methods">
                        <label class="payment-method selected">
                            <input type="radio" name="payment_method" value="cod" checked style="display: none;">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>Cash on Delivery</span>
                        </label>

                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="card" style="display: none;">
                            <i class="fas fa-credit-card"></i>
                            <span>Credit/Debit Card</span>
                        </label>

                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="upi" style="display: none;">
                            <i class="fas fa-mobile-alt"></i>
                            <span>UPI Payment</span>
                        </label>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="checkout-section">
                    <h3><i class="fas fa-sticky-note"></i> Order Notes</h3>
                    <textarea name="order_notes" rows="3" placeholder="Any special instructions for delivery..."></textarea>
                </div>
            </div>

            <div class="checkout-right">
                <!-- Order Summary -->
                <div class="cart-summary">
                    <h3>Order Summary</h3>

                    <div class="order-summary-items">
                        <?php foreach ($cartItems as $item): 
                            $price = $item['sale_price'] ?? $item['price'];
                        ?>
                        <div class="order-item">
                            <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $item['primary_image'] ?? 'placeholder.jpg'; ?>" 
                                 alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <div class="order-item-info">
                                <h4><?php echo htmlspecialchars($item['product_name']); ?></h4>
                                <span class="qty">Qty: <?php echo $item['quantity']; ?></span>
                            </div>
                            <div class="order-item-price"><?php echo formatPrice($price * $item['quantity']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span><?php echo $shipping > 0 ? formatPrice($shipping) : 'FREE'; ?></span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large" style="width: 100%; margin-top: 20px;">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>

                    <p style="text-align: center; margin-top: 15px; font-size: 0.85rem; color: var(--color-gray);">
                        <i class="fas fa-shield-alt"></i> Secure checkout. Your data is protected.
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function selectAddress(card, inputName) {
    // Remove selected from all cards in the same section
    card.parentElement.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    card.querySelector('input[type="radio"]').checked = true;
}

// Payment method selection
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        this.parentElement.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
