<?php
require_once __DIR__ . '/../config/config.php';

$pageTitle = 'Shop';

$categorySlug = $_GET['category']?? '';
$searchQuery = trim($_GET['q'] ?? '');
$sortBy = $_GET['sort'] ?? 'newest';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));

//building query
$whereConditions = [];
$whereConditions[] = "p.is_active = 1";

if ($categorySlug) {
  $categorySlug = mysqli_real_escape_string($conn, $categorySlug);
  $whereConditions[] = "c.slug = '$categorySlug'";
}
if ($searchQuery) {
  $searchQuery = mysqli_real_escape_string($conn, $searchQuery);

  $whereConditions[] =
  "(p.product_name LIKE '%$searchQuery%'
  OR p.description LIKE '%$searchQuery%'
  OR p.short_description LIKE '%$searchQuery%')";
}

if ($minPrice !== '' && is_numeric($minPrice)) {
  $minPrice = (float)$minPrice;
  $whereConditions[] = "COALESCE(p.sale_price, p.price) >= $minPrice";
}

if ($maxPrice !== '' && is_numeric($maxPrice)) {
  $maxPrice = (float)$maxPrice;
  $whereConditions[] = "COALESCE(p.sale_price, p.price) <= $maxPrice";
}

$whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

// Sort options
$orderBy = match($sortBy) {
  'price_low' => 'COALESCE(p.sale_price, p.price) ASC',
  'price_high' => 'COALESCE(p.sale_price, p.price) DESC',
  'name' => 'p.product_name ASC',
  'rating' => 'p.product_id DESC',
  default => 'p.created_at DESC'
};

$countSql = "SELECT COUNT(*) AS total FROM products p JOIN categories c ON p.category_id = c.category_id $whereClause";
$totalResult = mysqli_query($conn, $countSql);
$countRow = mysqli_fetch_assoc($totalResult);
$totalProducts = $countRow['total'];
$totalPages = ceil($totalProducts / PRODUCTS_PER_PAGE);
$offset = ($page - 1) * PRODUCTS_PER_PAGE;

// Fetch products
$sql = "SELECT p.*, c.category_name, c.slug as category_slug, pi.image_path as primary_image
        FROM products p
        JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN product_images pi ON p.product_id = pi.product_id AND pi.is_primary = 1
        $whereClause
        ORDER BY $orderBy
        LIMIT $offset, " . PRODUCTS_PER_PAGE;

$productResult = mysqli_query($conn, $sql);
$products = [];
if ($productResult) {
  while ($row = mysqli_fetch_assoc($productResult)) {
    $products[] = $row;
  }
}

// Get all categories for filter
$categories = getAllCategories();

// Current category info
$currentCategory = null;
if ($categorySlug) {
    $currentCategory = getCategoryBySlug($categorySlug);
}
$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/style.css">';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="shop-page" style="padding-top: 90px; padding-bottom: 60px;">
  <div class="container">
    <!-- Page Header -->
    <div class="page-header">
      <h1>
        <?php 
        if ($currentCategory) {
          echo htmlspecialchars($currentCategory['category_name']);
        } elseif ($searchQuery) {
          echo 'Search Results for "' . htmlspecialchars($searchQuery) . '"';
        } else {
          echo 'All Products';
        }
        ?>
      </h1>
      <p><?php echo $totalProducts; ?> product<?php echo $totalProducts !== 1 ? 's' : ''; ?> found</p>
    </div>

    <!-- Filters Bar -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; flex-wrap: wrap; gap: 15px;">
        <!-- Category Filter -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
          <a href="shop.php" class="btn btn-small <?php echo !$categorySlug ? 'btn-primary' : 'btn-outline'; ?>">
          All
          </a>
          <?php foreach ($categories as $cat): ?>
          <a href="shop.php?category=<?php echo $cat['slug']; ?>" 
            class="btn btn-small <?php echo $categorySlug === $cat['slug'] ? 'btn-primary' : 'btn-outline'; ?>">
            <?php echo htmlspecialchars($cat['category_name']); ?>
          </a>
          <?php endforeach; ?>
        </div>

          <!-- Sort & Search -->
        <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
          <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <?php if ($categorySlug): ?>
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($categorySlug); ?>">
            <?php endif; ?>

              <input type="text" name="q" placeholder="Search products..." 
                value="<?php echo htmlspecialchars($searchQuery); ?>"
                style="padding: 8px 15px; border: 2px solid var(--color-beige); border-radius:24px; font-family: inherit;">

              <select name="sort" onchange="this.form.submit()" 
                  style="padding: 8px 15px; border: 2px solid var(--color-beige); border-radius:24px; font-family: inherit; cursor: pointer;">
                  <option value="newest" <?php echo $sortBy === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                  <option value="price_low" <?php echo $sortBy === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                  <option value="price_high" <?php echo $sortBy === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                  <option value="name" <?php echo $sortBy === 'name' ? 'selected' : ''; ?>>Name A-Z</option>
              </select>
          </form>
        </div>
    </div>

    <!-- Products Grid -->
    <?php if (!empty($products)): ?>
    <div class="products-grid">
      <?php foreach ($products as $product): 
        $rating = getAverageRating($product['product_id']);
      ?>
      <div class="card">
        <div class="product-image">
          <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $product['primary_image'] ?? 'placeholder.jpg'; ?>" 
            alt="<?php echo htmlspecialchars($product['product_name']); ?>">
          <div class="product-badges">
            <?php if ($product['is_on_sale']): ?>
            <span class="badge badge-sale">SALE</span>
            <?php endif; ?>
            <?php if ($product['is_featured']): ?>
            <span class="badge badge-featured">Featured</span>
            <?php endif; ?>
          </div>
            <div class="product-actions">
              <button class="action-btn add-to-wishlist-btn" data-product-id="<?php echo $product['product_id']; ?>" title="Add to Wishlist">
                  <i class="fas fa-heart"></i>
              </button>
              <button class="action-btn add-to-cart-btn" data-product-id="<?php echo $product['product_id']; ?>" title="Add to Cart">
                <i class="fas fa-shopping-bag"></i>
              </button>
            </div>
        </div>
        <div class="product-info">
          <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
          <a href="product.php?slug=<?php echo $product['slug']; ?>" class="product-name">
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
          <?php if ($rating['total'] > 0): ?>
          <div class="product-rating">
            <span class="stars">
              <?php for ($i = 1; $i <= 5; $i++): ?>
              <i class="fas fa-star<?php echo $i > round($rating['average']) ? '-half-alt' : ''; ?>"></i>
              <?php endfor; ?>
            </span>
            <span>(<?php echo $rating['total']; ?>)</span>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="pagination" style="margin-top: 40px;">
      <?php if ($page > 1): ?>
      <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
          <i class="fas fa-chevron-left"></i>
        </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 1 && $i <= $page + 1)): ?>
          <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
            class="<?php echo $i === $page ? 'current' : ''; ?>">
            <?php echo $i; ?>
          </a>
          <?php elseif ($i == $page - 2 || $i == $page + 2): ?>
          <span>...</span>
          <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
            <i class="fas fa-chevron-right"></i>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

      <?php else: ?>
      <div class="cart-empty" style="padding: 80px 20px;">
          <i class="fas fa-search"></i>
          <h3>No Products Found</h3>
          <p>Try adjusting your search or filters to find what you're looking for.</p>
          <a href="shop.php" class="btn btn-primary">View All Products</a>
      </div>
      <?php endif; ?>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
