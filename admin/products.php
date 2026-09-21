<?php

$adminTitle = 'Products';

require_once __DIR__ . '/../config/config.php';

$action = $_GET['action'] ?? 'list';

$errors = [];
$success = '';

// Handle Delete

if (isset($_GET['delete'])) {

    $productId = (int)$_GET['delete'];

    // Get product images first
    $sql = "SELECT image_path FROM product_images WHERE product_id = $productId";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        while ($img = mysqli_fetch_assoc($result)) {

            deleteImage($img['image_path'], PRODUCT_IMAGE_PATH);
        }
    }

    // Delete product
    $sql = "DELETE FROM products WHERE product_id = $productId";

    mysqli_query($conn, $sql);

    setFlashMessage('success','Product deleted successfully!');

    redirect(ADMIN_URL . '/products.php');
}

// Handle Create / Update

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($action === 'create' || $action === 'edit')
) {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $errors[] = 'Invalid request.';

    } else {

        $productId = (int)($_POST['product_id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);

        $productName = sanitize($_POST['product_name'] ?? '');

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/','-',$productName),'-'));

        $description = sanitize($_POST['description'] ?? '');
        $shortDesc = sanitize($_POST['short_description'] ?? '');

        $price = (float)($_POST['price'] ?? 0);

        $salePrice = !empty($_POST['sale_price'])? (float)$_POST['sale_price']: null;

        $stock = (int)($_POST['stock_quantity'] ?? 0);

        $sku = sanitize($_POST['sku'] ?? '');
        $materials = sanitize($_POST['materials'] ?? '');
        $weight = sanitize($_POST['weight'] ?? '');
        $dimensions = sanitize($_POST['dimensions'] ?? '');
        $care = sanitize($_POST['care_instructions'] ?? '');

        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $isOnSale = isset($_POST['is_on_sale']) ? 1 : 0;


        // Validation
        if (empty($productName) || $categoryId <= 0 ||$price <= 0) {
            $errors[] = 'Product name, category, and price are required.';
        } else {
            // Escape string values

            $productName = mysqli_real_escape_string($conn,$productName);

            $slug = mysqli_real_escape_string($conn,$slug);

            $description = mysqli_real_escape_string($conn,$description);

            $shortDesc = mysqli_real_escape_string($conn,$shortDesc);

            $sku = mysqli_real_escape_string($conn,$sku);

            $materials = mysqli_real_escape_string($conn,$materials);

            $weight = mysqli_real_escape_string($conn,$weight);

            $dimensions = mysqli_real_escape_string($conn,$dimensions);

            $care = mysqli_real_escape_string($conn,$care);

            // Check slug uniqueness

            $sql = "SELECT product_id FROM products WHERE slug = '$slug' AND product_id != $productId";

            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $slug .= '-' . uniqid();
            }
            // Update existing product

            if ($productId) {

                // Handle NULL sale price
                if ($salePrice === null) {
                    $salePriceSQL = "NULL";
                } else {
                    $salePriceSQL = $salePrice;
                }

                $sql = "UPDATE products SET category_id = $categoryId,product_name = '$productName', slug = '$slug', description = '$description',
                    short_description = '$shortDesc',price = $price,sale_price = $salePriceSQL,stock_quantity = $stock,sku = '$sku',materials = '$materials',
                    weight = '$weight',dimensions = '$dimensions',care_instructions = '$care',is_featured = $isFeatured,is_on_sale = $isOnSale
                    WHERE product_id = $productId";

                if (mysqli_query($conn, $sql)) {

                    $success = 'Product updated successfully!';

                } else {

                    $errors[] = 'Failed to update product.';

                    error_log("Product update error: " .mysqli_error($conn));
                }
            // Create new product
            } else {
                if ($salePrice === null) {
                    $salePriceSQL = "NULL";
                } else {
                    $salePriceSQL = $salePrice;
                }

                $sql = "INSERT INTO products(category_id,product_name,slug,description,short_description,price,sale_price,stock_quantity,
                    sku,materials,weight,dimensions,care_instructions,is_featured,is_on_sale)
                    VALUES($categoryId,'$productName','$slug','$description','$shortDesc',$price,$salePriceSQL,$stock,'$sku','$materials',
                        '$weight','$dimensions','$care',$isFeatured,$isOnSale)";

                if (mysqli_query($conn, $sql)) {

                    // Get newly created product ID
                    $productId = mysqli_insert_id($conn);

                    $success = 'Product created successfully!';

                } else {
                    $errors[] = 'Failed to create product.';

                    error_log("Product creation error: " .mysqli_error($conn));
                }
            }
            // Handle Product Image Upload
            if (empty($errors) && isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {

                $upload = uploadImage($_FILES['product_image'],PRODUCT_IMAGE_PATH, 'prod_');

                if ($upload['success']) {

                    // Remove old primary image
                    $sql = "UPDATE product_images SET is_primary = 0 WHERE product_id = $productId";

                    mysqli_query($conn, $sql);

                    // Insert new primary image
                    $imagePath = mysqli_real_escape_string($conn,$upload['filename']);

                    $imageAlt = mysqli_real_escape_string(
                        $conn, $productName);

                    $sql = "INSERT INTO product_images(product_id,image_path,is_primary, alt_text)
                            VALUES($productId,'$imagePath',1,'$imageAlt')";
                    mysqli_query($conn, $sql);
                }
            }
            // Redirect after successful operation

            if (empty($errors)) {

                setFlashMessage('success',$success);

                redirect(ADMIN_URL . '/products.php');
            }
        }
    }
}

// Get Categories for Dropdown

$categories = getAllCategories();
// Get Product for Edit

$editProduct = null;

if ($action === 'edit' &&isset($_GET['id'])) {

    $editId = (int)$_GET['id'];

    $sql = "SELECT * FROM products WHERE product_id = $editId";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $editProduct = mysqli_fetch_assoc($result);
    }
}

// List Products

if ($action === 'list') {
    $page = max(1,(int)($_GET['page'] ?? 1));

    $search = trim($_GET['search'] ?? '');

    $where = "WHERE 1=1";

    // Search products
    if ($search !== '') {

        $search = mysqli_real_escape_string($conn,$search);

        $where .= " AND (p.product_name LIKE '%$search%'OR p.sku LIKE '%$search%')";
    }
    // Get total products

    $sql = "SELECT COUNT(*) AS count FROM products p $where";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    $total = $row['count'] ?? 0;

    $totalPages = ceil(
        $total / PRODUCTS_PER_PAGE
    );
    // Pagination
    $offset = ($page - 1) * PRODUCTS_PER_PAGE;

    // Get products

    $limit = (int)PRODUCTS_PER_PAGE;
    $offset = (int)$offset;

    $sql = "SELECT p.*,c.category_name,pi.image_path AS primary_image FROM products p LEFT JOIN categories c 
            ON p.category_id = c.category_id
            LEFT JOIN product_images pi
            ON p.product_id = pi.product_id
            AND pi.is_primary = 1
            $where
            ORDER BY p.created_at DESC
            LIMIT $limit OFFSET $offset";

    $result = mysqli_query($conn, $sql);

    $products = [];

    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>


<?php if ($action === 'create' || $action === 'edit'): ?>
<!-- Product Form -->
<div class="content-header">
    <h2><i class="fas fa-<?php echo $action === 'edit' ? 'edit' : 'plus'; ?>"></i> 
        <?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Product</h2>
    <a href="products.php" class="btn btn-small btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="flash-message error" style="margin-bottom: 20px; position: static;">
    <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>
</div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" class="admin-form">
    <?php echo csrfField(); ?>
    <input type="hidden" name="product_id" value="<?php echo $editProduct['product_id'] ?? ''; ?>">

    <div class="form-row">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="product_name" value="<?php echo htmlspecialchars($editProduct['product_name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Category *</label>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['category_id']; ?>" <?php echo ($editProduct['category_id'] ?? '') == $cat['category_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['category_name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Price (₹) *</label>
            <input type="number" name="price" step="0.01" value="<?php echo $editProduct['price'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Sale Price (₹)</label>
            <input type="number" name="sale_price" step="0.01" value="<?php echo $editProduct['sale_price'] ?? ''; ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" name="stock_quantity" value="<?php echo $editProduct['stock_quantity'] ?? '0'; ?>">
        </div>
        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="sku" value="<?php echo htmlspecialchars($editProduct['sku'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Short Description</label>
        <input type="text" name="short_description" value="<?php echo htmlspecialchars($editProduct['short_description'] ?? ''); ?>">
    </div>

    <div class="form-group">
        <label>Full Description</label>
        <textarea name="description" rows="5"><?php echo htmlspecialchars($editProduct['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Materials</label>
            <input type="text" name="materials" value="<?php echo htmlspecialchars($editProduct['materials'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Weight</label>
            <input type="text" name="weight" value="<?php echo htmlspecialchars($editProduct['weight'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Dimensions</label>
            <input type="text" name="dimensions" value="<?php echo htmlspecialchars($editProduct['dimensions'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Care Instructions</label>
            <input type="text" name="care_instructions" value="<?php echo htmlspecialchars($editProduct['care_instructions'] ?? ''); ?>">
        </div>
    </div>

    <div class="form-group checkbox-group">
        <label class="checkbox-option">
            <input 
                type="checkbox" 
                name="is_featured" 
                <?php echo ($editProduct['is_featured'] ?? 0) ? 'checked' : ''; ?>
            >
            <span class="checkbox-custom"></span>
            <span class="checkbox-content">
                <strong>Featured Product</strong>
                <small>Show this product in featured sections.</small>
            </span>
        </label>
    </div>


    <div class="form-group checkbox-group">
        <label class="checkbox-option">
            <input 
                type="checkbox" 
                name="is_on_sale" 
                <?php echo ($editProduct['is_on_sale'] ?? 0) ? 'checked' : ''; ?>
            >
            <span class="checkbox-custom"></span>
            <span class="checkbox-content">
                <strong>On Sale</strong>
                <small>Mark this product as currently on sale.</small>
            </span>
        </label>
    </div>


    <div class="form-group">
        <label>Product Image</label>
        <?php if ($editProduct && !empty($editProduct['primary_image'])): ?>
        <div class="image-preview">
            <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $editProduct['primary_image']; ?>" alt="Current Image">
        </div>
        <?php endif; ?>
        <input type="file" name="product_image" accept="image/*" class="image-upload-input" data-preview="imagePreview">
    </div>

    <div style="display: flex; gap: 15px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?> Product
        </button>
        <a href="products.php" class="btn btn-outline">Cancel</a>
    </div>
</form>

<?php else: ?>
<!-- Product List -->
<div class="content-header">
    <h2><i class="fas fa-box-open"></i> Products</h2>
    <a href="products.php?action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Product
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table" id="productsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr data-id="<?php echo $product['product_id']; ?>">
                <td><?php echo $product['product_id'];?></td>
                <td>
                    <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $product['primary_image'] ?? 'placeholder.jpg'; ?>" 
                         alt="" class="product-thumb">
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
                    <?php if ($product['sku']): ?>
                    <br><small style="color: #aaa;"><?php echo htmlspecialchars($product['sku']); ?></small>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?></td>
                <td>
                    <?php if ($product['sale_price'] && $product['sale_price'] < $product['price']): ?>
                    <strong style="color: var(--color-green);"><?php echo formatPrice($product['sale_price']); ?></strong>
                    <br><small style="text-decoration: line-through; color: #aaa;"><?php echo formatPrice($product['price']); ?></small>
                    <?php else: ?>
                    <?php echo formatPrice($product['price']); ?>
                    <?php endif; ?>
                </td>
                <td><?php echo $product['stock_quantity']; ?></td>
                <td>
                    <?php if ($product['is_active']): ?>
                    <span class="status-badge active">Active</span>
                    <?php else: ?>
                    <span class="status-badge inactive">Inactive</span>
                    <?php endif; ?>
                    <?php if ($product['is_featured']): ?>
                    <span class="status-badge approved" style="margin-top: 5px;">Featured</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="<?php echo SITE_URL; ?>/pages/product.php?slug=<?php echo $product['slug']; ?>" target="_blank" class="view-btn" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="products.php?action=edit&id=<?php echo $product['product_id']; ?>" class="edit-btn" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="products.php?delete=<?php echo $product['product_id']; ?>" class="delete-btn delete-action" title="Delete" onclick="return confirm('Delete this product?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo $i === $page ? 'current' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
