<?php
$adminTitle = 'Categories';

require_once __DIR__ . '/../config/config.php';

$action = $_GET['action'] ?? 'list';
$errors = [];

// Handle Delete
if (isset($_GET['delete'])) {

    $categoryId = intval($_GET['delete']);

    // Check if category has products
    $checkSql = "SELECT COUNT(*) AS count FROM products WHERE category_id = $categoryId";

    $checkResult = mysqli_query($conn, $checkSql);
    $checkRow = mysqli_fetch_assoc($checkResult);

    $hasProducts = $checkRow['count'];

    if ($hasProducts > 0) {

        setFlashMessage('error','Cannot delete category with existing products. Move or delete products first.');

    } else {

        $deleteSql = "DELETE FROM categories WHERE category_id = $categoryId";

        mysqli_query($conn, $deleteSql);

        setFlashMessage('success','Category deleted successfully!');
    }

    redirect(ADMIN_URL . '/categories.php');
}


// Handle Create/Update
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($action === 'create' || $action === 'edit')
) {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $errors[] = 'Invalid request.';

    } else {

        $categoryId = intval($_POST['category_id'] ?? 0);

        $categoryName = sanitize($_POST['category_name'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);

        // Generate slug
        $slug = strtolower(
            trim(
                preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    '-',
                    $categoryName
                ),
                '-'
            )
        );

        if (empty($categoryName)) {

            $errors[] = 'Category name is required.';

        } else {

            // Escape values before putting them into SQL
            $categoryName = mysqli_real_escape_string($conn, $categoryName);
            $slug = mysqli_real_escape_string($conn, $slug);
            $description = mysqli_real_escape_string($conn, $description);

            // Check slug uniqueness
            $checkSlugSql = "SELECT category_id FROM categories WHERE slug = '$slug' AND category_id != $categoryId";

            $checkSlugResult = mysqli_query($conn, $checkSlugSql);

            if (mysqli_num_rows($checkSlugResult) > 0) {

                $slug = $slug . '-' . uniqid();

            }

            // Update existing category
            if ($categoryId) {

                $updateSql = "
                    UPDATE categories
                    SET
                        category_name = '$categoryName',
                        slug = '$slug',
                        description = '$description',
                        display_order = $displayOrder
                    WHERE category_id = $categoryId
                ";

                if (mysqli_query($conn, $updateSql)) {

                    setFlashMessage('success','Category updated successfully!');

                    redirect(ADMIN_URL . '/categories.php');

                } else {
                    $errors[] = 'Failed to update category.';
                    error_log(mysqli_error($conn));
                }

            } else {

                // Create new category
                $insertSql = "INSERT INTO categories(category_name, slug, description, display_order)
                VALUES('$categoryName', '$slug', '$description', $displayOrder)";

                if (mysqli_query($conn, $insertSql)) {

                    setFlashMessage('success','Category created successfully!');

                    redirect(ADMIN_URL . '/categories.php');

                } else {

                    $errors[] = 'Failed to create category.';
                    error_log(mysqli_error($conn));
                }
            }
        }
    }
}


// Get category for edit
$editCategory = null;

if ($action === 'edit' && isset($_GET['id'])) {

    $categoryId = intval($_GET['id']);

    $editSql = "SELECT *FROM categories WHERE category_id = $categoryId";

    $editResult = mysqli_query($conn, $editSql);

    if ($editResult) {
        $editCategory = mysqli_fetch_assoc($editResult);
    }
}


// List categories
if ($action === 'list') {

    $categoriesSql = "SELECT c.*, COUNT(p.product_id) AS product_count
        FROM categories c
        LEFT JOIN products p
            ON c.category_id = p.category_id
            AND p.is_active = 1
        GROUP BY c.category_id
        ORDER BY c.display_order
    ";

    $categoriesResult = mysqli_query($conn, $categoriesSql);

    $categories = [];

    if ($categoriesResult) {

        while ($row = mysqli_fetch_assoc($categoriesResult)) {
            $categories[] = $row;
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'create' || $action === 'edit'): ?>
<div class="content-header">
    <h2><i class="fas fa-<?php echo $action === 'edit' ? 'edit' : 'plus'; ?>"></i> 
        <?php echo $action === 'edit' ? 'Edit' : 'Add'; ?> Category</h2>
    <a href="categories.php" class="btn btn-small btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<?php if (!empty($errors)): ?>
<div class="flash-message error" style="margin-bottom: 20px; position: static;">
    <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>
</div>
<?php endif; ?>

<form method="POST" action="" class="admin-form">
    <?php echo csrfField(); ?>
    <input type="hidden" name="category_id" value="<?php echo $editCategory['category_id'] ?? ''; ?>">

    <div class="form-group">
        <label>Category Name *</label>
        <input type="text" name="category_name" value="<?php echo htmlspecialchars($editCategory['category_name'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"><?php echo htmlspecialchars($editCategory['description'] ?? ''); ?></textarea>
    </div>

    <div class="form-group">
        <label>Display Order</label>
        <input type="number" name="display_order" value="<?php echo $editCategory['display_order'] ?? '0'; ?>">
    </div>

    <div style="display: flex; gap: 15px;">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> <?php echo $action === 'edit' ? 'Update' : 'Create'; ?>
        </button>
        <a href="categories.php" class="btn btn-outline">Cancel</a>
    </div>
</form>

<?php else: ?>
<div class="content-header">
    <h2><i class="fas fa-tags"></i> Categories</h2>
    <a href="categories.php?action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Category
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Category Name</th>
                <th>Slug</th>
                <th>Products</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?php echo $cat['display_order']; ?></td>
                <td><strong><?php echo htmlspecialchars($cat['category_name']); ?></strong></td>
                <td><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                <td><?php echo $cat['product_count']; ?></td>
                <td>
                    <span class="status-badge <?php echo $cat['is_active'] ? 'active' : 'inactive'; ?>">
                        <?php echo $cat['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="categories.php?action=edit&id=<?php echo $cat['category_id']; ?>" class="edit-btn" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="categories.php?delete=<?php echo $cat['category_id']; ?>" class="delete-btn delete-action" title="Delete" onclick="return confirm('Delete this category?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
