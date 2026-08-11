<?php 
require_once __DIR__ . '/../config/config.php';

$yarnColors = [
    ['name' => 'Soft Pink', 'hex' => '#f4b9e8', 'value' => 'Soft Pink'],
    ['name' => 'Lavender', 'hex' => '#8d6cf7', 'value' => 'Lavender'],
    ['name' => 'Mint Green', 'hex' => '#e0f8c5', 'value' => 'Mint Green'],
    ['name' => 'Cream', 'hex' => '#fff8e7', 'value' => 'Cream'],
    ['name' => 'Beige', 'hex' => '#f5e6d3', 'value' => 'Beige'],
    ['name' => 'Brown', 'hex' => '#8b6f47', 'value' => 'Brown'],
    ['name' => 'Yellow', 'hex' => '#ffffb7', 'value' => 'Yellow'],
    ['name' => 'Olive', 'hex' => '#7d9b45', 'value' => 'Olive Green'],
    ['name' => 'White', 'hex' => '#ffffff', 'value' => 'White'],
    ['name' => 'Red', 'hex' => '#e74c3c', 'value' => 'Red'],
    ['name' => 'Blue', 'hex' => '#3498db', 'value' => 'Blue'],
    ['name' => 'Black', 'hex' => '#000', 'value' => 'Black'],
];

$sizes = [
    ['label' => 'Mini (3-4 inches)', 'value' => 'Mini (3-4 inches)'],
    ['label' => 'Small (5-6 inches)', 'value' => 'Small (5-6 inches)'],
    ['label' => 'Medium (7-8 inches)', 'value' => 'Medium (7-8 inches)'],
    ['label' => 'Large (9-10 inches)', 'value' => 'Large (9-10 inches)'],
    ['label' => 'Extra Large (11+ inches)', 'value' => 'Extra Large (11+ inches)'],
    ['label' => 'Custom Size', 'value' => 'Custom Size'],
];

$productTypes = [
    ['value' => 'amigurumi', 'label' => 'Amigurumi (Stuffed Toy)', 'icon' => 'fa-paw'],
    ['value' => 'wearable', 'label' => 'Wearable (Clothing/Accessory)', 'icon' => 'fa-hat-cowboy'],
    ['value' => 'decor', 'label' => 'Home Decor', 'icon' => 'fa-home'],
    ['value' => 'character', 'label' => 'Character/Figure', 'icon' => 'fa-star'],
    ['value' => 'keychain', 'label' => 'Keychain/Bag Charm', 'icon' => 'fa-key'],
    ['value' => 'other', 'label' => 'Other (Specify in Notes)', 'icon' => 'fa-question-circle'],
];

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {
    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $productType = $_POST['product_type'] ?? '';
        $color = $_POST['color'] ?? '';
        $size = $_POST['size'] ?? '';
        $quantity = max(1, intval($_POST['quantity'] ?? 1));
        $budget = floatval($_POST['budget'] ?? 0);
        $notes = sanitize($_POST['notes'] ?? '');

        if (empty($productType)) $errors[] = 'Please select a product type.';
        if (empty($color)) $errors[] = 'Please select a color.';
        if (empty($size)) $errors[] = 'Please select a size.';
        if ($budget <= 0) $errors[] = 'Please enter a valid budget.';

        $referenceImage = null;
        if (isset($_FILES['reference_image']) && $_FILES['reference_image']['error'] === UPLOAD_ERR_OK) {
            $upload = uploadImage($_FILES['reference_image'], CUSTOM_IMAGE_PATH, 'custom_');
            if ($upload['success']) {
                $referenceImage = $upload['filename'];
            } else {
                $errors[] = $upload['error'];
            }
        }

        if (empty($errors)) {

            $userId = (int)getCurrentUserId();

            // Escape string values before putting them into SQL
            $productType = mysqli_real_escape_string($conn, $productType);
            $color = mysqli_real_escape_string($conn, $color);
            $size = mysqli_real_escape_string($conn, $size);
            $quantity = (int)$quantity;
            $budget = (float)$budget;
            $referenceImage = $referenceImage !== null
                ? mysqli_real_escape_string($conn, $referenceImage)
                : '';
            $notes = mysqli_real_escape_string($conn, $notes);

            $sql = "INSERT INTO custom_crochet_requests
                    (user_id, product_type, color, size, quantity, budget, reference_image, notes, status)
                    VALUES
                    ($userId, '$productType', '$color', '$size', $quantity, $budget,
                    " . ($referenceImage !== '' ? "'$referenceImage'" : "NULL") . ",
                    '$notes', 'pending')";

            if (mysqli_query($conn, $sql)) {

                $success = 'Your custom crochet request has been submitted! We will review it and get back to you soon.';

                // Clear form
                $_POST = [];

            } else {

                $errors[] = 'Failed to submit request. Please try again.';
                error_log("Custom request error: " . mysqli_error($conn));
            }
        }
    }
}

// Get user's custom requests
$userRequests = [];

if (isLoggedIn()) {

    $userId = (int)getCurrentUserId();

    $sql = "SELECT *
            FROM custom_crochet_requests
            WHERE user_id = $userId
            ORDER BY created_at DESC";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userRequests[] = $row;
        }
    }
}

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/custom-designer.css">';
$extraJS = '<script src="' . ASSETS_URL . '/js/custom.js"></script>';

require_once __DIR__ . '/../includes/header.php';
?>

<div class="custom-designer-page">
    <div class="container">
        <div class="designer-intro">
            <h1>Custom Crochet Designer</h1>
            <p>Can't find what you are looking for? Design and share your envision with the custom crochet designer. Choose the type, colors, and size accordingly</p>
        </div>
        <?php if(!empty($errors)): ?>
        <div class="flash-message-error" style = "max-width:700px; margin:0 auto 20px; position: static;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="flash-message success" style="max-width: 700px; margin: 0 auto 20px; position: static;">
            <i class="fas fa-check-circle"></i>
            <span><?php echo htmlspecialchars($success); ?></span>
        </div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data" class="designer-form custom-designer-form">
            <?php echo csrfField(); ?>

            <!-- product type -->
            <div class="form-group">
                <label> Select Product Type</label>
                <div class="size-options" style="justify-content: flex-start;">
                    <?php foreach ($productTypes as $type): ?>
                    <div class="size-option" data-value="<?php echo $type['value']; ?>" onclick="selectProductType(this)">
                        <i class="fas <?php echo $type['icon']; ?>"></i> <?php echo $type['label']; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="product_type" id="productTypeInput" required>
            </div>
            
            <!-- color selection -->
            <div class="form-group">
                <label><i class="fas fa-palette"></i> Select Color</label>
                <div class="color-options">
                    <?php foreach ($yarnColors as $color): ?>
                    <div class="color-option" 
                         style="background-color: <?php echo $color['hex']; ?>;" 
                         data-color="<?php echo $color['value']; ?>"
                         title="<?php echo $color['name']; ?>"
                         onclick="selectColor(this)">
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="color" id="colorInput" required>
                <p style="margin-top: 8px; color: var(--color-gray); font-size: 0.9rem;" id="selectedColorName">Click a color to select</p>
            </div>

            <!-- size selection -->
             <div class="form-group">
                <label> Select Size</label>
                <div class="size-options" style="justify-content: flex-start;">
                    <?php foreach ($sizes as $size): ?>
                    <div class="size-option" data-size="<?php echo $size['value']; ?>" onclick="selectSize(this)">
                        <?php echo $size['label']; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="size" id="sizeInput" required>
            </div>

            <!-- Quantity & Budget -->
            <div class="form-row">
                <div class="form-group">
                    <label for="quantity"> Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="50" required 
                           style="padding: 12px 16px; border: 2px solid var(--color-beige); border-radius:20px; font-family: inherit; width: 100%;">
                </div>

                <div class="form-group">
                    <label for="budget"> Your Budget (₹)</label>
                    <input type="number" id="budget" name="budget" placeholder="Enter your budget" min="50" required
                        style="padding: 12px 16px; border: 2px solid var(--color-beige); border-radius: 20px; font-family: inherit; width: 100%;">
                </div>
            </div>

            <!-- Reference Image -->
            <div class="form-group">
                <label><i class="fas fa-image"></i> Reference Image (Optional but Preferred)</label>
                <div class="file-upload-area">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Drag & drop an image here or click to browse</p>
                    <p style="font-size: 0.8rem; color: var(--color-gray);">JPG, PNG, GIF, WebP (max 5MB)</p>
                    <p class="file-name"></p>
                    <input type="file" name="reference_image" accept="image/*" style="display: none;">
                </div>
            </div>

            <!-- Notes -->
            <div class="form-group">
                <label for="notes"><i class="fas fa-sticky-note"></i> Additional Notes</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Describe your vision in detail - any specific features, patterns, accessories, or special requests..."
                    style="padding: 12px 16px; border: 2px solid var(--color-beige); border-radius:16px; font-family: inherit; width: 100%; resize: vertical;"></textarea>
            </div>

            <?php if (isLoggedIn()): ?>
            <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">
                <i class="fas fa-paper-plane"></i> Submit Custom Request
            </button>
            <?php else: ?>
            <div style="text-align: center; padding: 20px; background: var(--color-light-pink); border-radius: var(--radius-md);">
                <p><a href="login.php" style="color: var(--color-purple); font-weight: 600;">Login</a> or <a href="register.php" style="color: var(--color-purple); font-weight: 600;">Register</a> to submit a custom request.</p>
            </div>
            <?php endif; ?>
        </form>

        <!-- My Custom Requests -->
        <?php if (!empty($userRequests)): ?>
        <div style="margin-top: 60px;">
            <div class="section-header">
                <h2>My Custom Requests</h2>
                <p>Track the status of your custom crochet orders</p>
            </div>
            <div class="requests-list">
                <?php foreach ($userRequests as $request): ?>
                <div class="request-card">
                    <div class="request-info">
                        <h4><?php echo ucfirst($request['product_type']); ?> - <?php echo htmlspecialchars($request['color']); ?></h4>
                        <div class="request-meta">
                            <span><i class="fas fa-ruler"></i> <?php echo htmlspecialchars($request['size']); ?></span>
                            <span><i class="fas fa-sort-numeric-up"></i> Qty: <?php echo $request['quantity']; ?></span>
                            <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($request['created_at'])); ?></span>
                        </div>
                        <?php if ($request['notes']): ?>
                        <p style="color: var(--color-gray); font-size: 0.9rem; margin-top: 10px;">
                            <i class="fas fa-comment"></i> <?php echo htmlspecialchars($request['notes']); ?>
                        </p>
                        <?php endif; ?>
                        <?php if ($request['admin_remarks']): ?>
                        <p style="color: var(--color-purple); font-size: 0.9rem; margin-top: 10px;">
                            <i class="fas fa-reply"></i> Admin: <?php echo htmlspecialchars($request['admin_remarks']); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="request-price">
                        <span class="request-status <?php echo $request['status']; ?>"><?php echo ucfirst($request['status']); ?></span>
                        <div style="margin-top: 10px;">
                            <span class="budget">Budget: <?php echo formatPrice($request['budget']); ?></span>
                            <?php if ($request['final_price']): ?>
                            <div class="final-price">Final: <?php echo formatPrice($request['final_price']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
function selectProductType(element) {
    // Remove selected only from product type options
    const productTypeOptions = element
        .closest('.form-group')
        .querySelectorAll('.size-option');

    productTypeOptions.forEach(option => {
        option.classList.remove('selected');
    });

    // Select clicked product type
    element.classList.add('selected');

    // Store selected value
    document.getElementById('productTypeInput').value = element.dataset.value;
}


function selectColor(element) {
    // Remove selected from colors only
    const colorOptions = element
        .closest('.form-group')
        .querySelectorAll('.color-option');

    colorOptions.forEach(option => {
        option.classList.remove('selected');
    });

    // Select clicked color
    element.classList.add('selected');

    // Store selected value
    document.getElementById('colorInput').value = element.dataset.color;

    // Display selected color
    document.getElementById('selectedColorName').textContent =
        'Selected: ' + element.dataset.color;
}


function selectSize(element) {
    // Remove selected only from size options
    const sizeOptions = element
        .closest('.form-group')
        .querySelectorAll('.size-option');

    sizeOptions.forEach(option => {
        option.classList.remove('selected');
    });

    // Select clicked size
    element.classList.add('selected');

    // Store selected value
    document.getElementById('sizeInput').value = element.dataset.size;
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
