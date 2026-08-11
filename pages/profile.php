<?php
require_once __DIR__ . '/../config/config.php';

requireLogin();

$pageTitle = 'My Profile';
$userId = (int)getCurrentUserId();
$activeTab = $_GET['tab'] ?? 'profile';

$sql = "SELECT * FROM users WHERE user_id = $userId";
$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);

$errors = [];
$success = '';

// Handle Profile Update

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $errors[] = 'Invalid request.';

    } else {
        $firstName = sanitize($_POST['first_name'] ?? '');
        $lastName = sanitize($_POST['last_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');

        if (empty($firstName) || empty($lastName)) {

            $errors[] = 'First and last name are required.';

        } else {

            // Escape values before putting them into SQL
            $firstName = mysqli_real_escape_string($conn, $firstName);
            $lastName = mysqli_real_escape_string($conn, $lastName);
            $phone = mysqli_real_escape_string($conn, $phone);

            $sql = "UPDATE users SET first_name = '$firstName', last_name = '$lastName', phone = '$phone'
                WHERE user_id = $userId";

            if (mysqli_query($conn, $sql)) {

                $_SESSION['user_name'] = $firstName . ' ' . $lastName;

                $success = 'Profile updated successfully!';

                // Get updated user data
                $sql = "SELECT * FROM users WHERE user_id = $userId";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_assoc($result);

            } else {

                $errors[] = 'Failed to update profile.';
                error_log("Profile update error: " . mysqli_error($conn));
            }
        }
    }
}

// Handle Password Change

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $errors[] = 'Invalid request.';

    } else {

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

        if (!password_verify($currentPassword, $user['password_hash'])) {

            $errors[] = 'Current password is incorrect.';

        } elseif (strlen($newPassword) < 6) {

            $errors[] = 'New password must be at least 6 characters.';

        } elseif ($newPassword !== $confirmNewPassword) {

            $errors[] = 'New passwords do not match.';

        } else {

            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);

            $newHash = mysqli_real_escape_string($conn, $newHash);

            $sql = "UPDATE users
                    SET password_hash = '$newHash'
                    WHERE user_id = $userId";

            if (mysqli_query($conn, $sql)) {

                $success = 'Password changed successfully!';

            } else {

                $errors[] = 'Failed to change password.';
                error_log("Password change error: " . mysqli_error($conn));
            }
        }
    }
}

// Handle Address CRUD

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $errors[] = 'Invalid request.';

    } else {

        $addressId = (int)($_POST['address_id'] ?? 0);

        $fullName = sanitize($_POST['full_name'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $line1 = sanitize($_POST['address_line1'] ?? '');
        $line2 = sanitize($_POST['address_line2'] ?? '');
        $city = sanitize($_POST['city'] ?? '');
        $state = sanitize($_POST['state'] ?? '');
        $postal = sanitize($_POST['postal_code'] ?? '');
        $country = sanitize($_POST['country'] ?? 'India');

        $isDefault = isset($_POST['is_default']) ? 1 : 0;

        if (
            empty($fullName) ||
            empty($line1) ||
            empty($city) ||
            empty($state) ||
            empty($postal)
        ) {

            $errors[] = 'Please fill in all required address fields.';

        } else {

            // Escape address values
            $fullName = mysqli_real_escape_string($conn, $fullName);
            $phone = mysqli_real_escape_string($conn, $phone);
            $line1 = mysqli_real_escape_string($conn, $line1);
            $line2 = mysqli_real_escape_string($conn, $line2);
            $city = mysqli_real_escape_string($conn, $city);
            $state = mysqli_real_escape_string($conn, $state);
            $postal = mysqli_real_escape_string($conn, $postal);
            $country = mysqli_real_escape_string($conn, $country);

            // Remove default status from other addresses
            if ($isDefault) {

                $sql = "UPDATE addresses
                        SET is_default = 0
                        WHERE user_id = $userId";

                mysqli_query($conn, $sql);
            }
            // Update existing address
            if ($addressId) {

                $sql = "UPDATE addresses
                        SET full_name = '$fullName',
                            phone = '$phone',
                            address_line1 = '$line1',
                            address_line2 = '$line2',
                            city = '$city',
                            state = '$state',
                            postal_code = '$postal',
                            country = '$country',
                            is_default = $isDefault
                        WHERE address_id = $addressId
                        AND user_id = $userId";

                if (mysqli_query($conn, $sql)) {

                    $success = 'Address updated successfully!';

                } else {

                    $errors[] = 'Failed to update address.';
                    error_log("Address update error: " . mysqli_error($conn));
                }
            // Create new address
            } else {

                $sql = "INSERT INTO addresses
                        (
                            user_id,
                            full_name,
                            phone,
                            address_line1,
                            address_line2,
                            city,
                            state,
                            postal_code,
                            country,
                            is_default
                        )
                        VALUES
                        (
                            $userId,
                            '$fullName',
                            '$phone',
                            '$line1',
                            '$line2',
                            '$city',
                            '$state',
                            '$postal',
                            '$country',
                            $isDefault
                        )";

                if (mysqli_query($conn, $sql)) {

                    $success = 'Address added successfully!';

                } else {

                    $errors[] = 'Failed to add address.';
                    error_log("Address insert error: " . mysqli_error($conn));
                }
            }

            $activeTab = 'addresses';
        }
    }
}

// Handle Address Delete

if (isset($_GET['delete_address'])) {

    $addressId = (int)$_GET['delete_address'];

    $sql = "DELETE FROM addresses WHERE address_id = $addressId AND user_id = $userId";

    if (mysqli_query($conn, $sql)) {

        setFlashMessage('success', 'Address deleted successfully!');

    } else {

        setFlashMessage('error', 'Failed to delete address.');
    }

    redirect(SITE_URL . '/pages/profile.php?tab=addresses');
}

// Get Addresses

$addresses = [];

$sql = "SELECT *
        FROM addresses
        WHERE user_id = $userId
        ORDER BY is_default DESC";

$result = mysqli_query($conn, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {
        $addresses[] = $row;
    }
}

// Get My Reviews

$myReviews = [];

$sql = "SELECT r.*,
               p.product_name,
               p.slug,
               pi.image_path AS primary_image
        FROM reviews r
        JOIN products p
            ON r.product_id = p.product_id
        LEFT JOIN product_images pi
            ON p.product_id = pi.product_id
            AND pi.is_primary = 1
        WHERE r.user_id = $userId
        ORDER BY r.created_at DESC";

$result = mysqli_query($conn, $sql);

if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {
        $myReviews[] = $row;
    }
}

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/custom-profile.css">';

require_once __DIR__ . '/../includes/header.php';

?>
<div class="profile-page">
    <div class="container">
        <div class="profile-grid">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-placeholder">
                        <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                    </div>
                    <h3><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <ul class="profile-menu">
                    <li><a href="?tab=profile" class="<?php echo $activeTab === 'profile' ? 'active' : ''; ?>">
                        <i class="fas fa-user"></i> Profile
                    </a></li>
                    <li><a href="?tab=addresses" class="<?php echo $activeTab === 'addresses' ? 'active' : ''; ?>">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                    </a></li>
                    <!-- <li><a href="?tab=reviews" class="<?php echo $activeTab === 'reviews' ? 'active' : ''; ?>">
                        <i class="fas fa-star"></i> My Reviews -->
                    </a></li>
                    <li><a href="orders.php">
                        <i class="fas fa-box"></i> My Orders
                    </a></li>
                    <li><a href="custom-requests.php">
                        <i class="fas fa-magic"></i> Custom Requests
                    </a></li>
                    <li><a href="../includes/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a></li>
                </ul>
            </div>

            <!-- Content -->
            <div class="profile-content">
                <?php if (!empty($errors)): ?>
                <div class="flash-message error" style="margin-bottom: 20px; position: static;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($success): ?>
                <div class="flash-message success" style="margin-bottom: 20px; position: static;">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <?php endif; ?>

                <?php if ($activeTab === 'profile'): ?>
                <!-- Profile Tab -->
                <h2>Profile Information</h2>
                <form method="POST" action="">
                    <?php echo csrfField(); ?>
                    <input type="hidden" name="update_profile" value="1">

                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: var(--color-light-gray);">
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>

                <hr style="margin: 40px 0; border: none; border-top: 1px solid var(--color-beige);">

                <h2>Change Password</h2>
                <form method="POST" action="">
                    <?php echo csrfField(); ?>
                    <input type="hidden" name="change_password" value="1">

                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_new_password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </form>

                <?php elseif ($activeTab === 'addresses'): ?>
                <!-- Addresses Tab -->
                <h2>My Addresses</h2>

                <button class="btn btn-secondary" onclick="openAddressModal()" style="margin-bottom: 25px;">
                    <i class="fas fa-plus"></i> Add New Address
                </button>

                <div class="addresses-grid">
                    <?php foreach ($addresses as $addr): ?>
                    <div class="address-card-profile <?php echo $addr['is_default'] ? 'default' : ''; ?>">
                        <?php if ($addr['is_default']): ?>
                        <span class="default-badge">Default</span>
                        <?php endif; ?>
                        <h4><?php echo htmlspecialchars($addr['full_name']); ?></h4>
                        <p><?php echo htmlspecialchars($addr['address_line1']); ?></p>
                        <?php if ($addr['address_line2']): ?>
                        <p><?php echo htmlspecialchars($addr['address_line2']); ?></p>
                        <?php endif; ?>
                        <p><?php echo htmlspecialchars($addr['city'] . ', ' . $addr['state'] . ' - ' . $addr['postal_code']); ?></p>
                        <p><?php echo htmlspecialchars($addr['country']); ?></p>
                        <p style="margin-top: 8px; font-weight: 600;"><i class="fas fa-phone"></i> <?php echo htmlspecialchars($addr['phone']); ?></p>

                        <div class="address-actions">
                            <button class="edit-btn" onclick="editAddress(<?php echo htmlspecialchars(json_encode($addr)); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="?tab=addresses&delete_address=<?php echo $addr['address_id']; ?>" class="delete-btn" onclick="return confirm('Delete this address?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php elseif ($activeTab === 'reviews'): ?>
                <h2>My Reviews</h2>

                <?php if (!empty($myReviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($myReviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <div class="review-user">
                                <img src="<?php echo ASSETS_URL; ?>/images/products/<?php echo $review['primary_image'] ?? 'placeholder.jpg'; ?>" 
                                     alt="" style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover;">
                                <div class="review-user-info">
                                    <h4><a href="product.php?slug=<?php echo $review['slug']; ?>"><?php echo htmlspecialchars($review['product_name']); ?></a></h4>
                                    <span class="date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                                </div>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star<?php echo $i > $review['rating'] ? '-half-alt' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <h4 class="review-title"><?php echo htmlspecialchars($review['title'] ?? 'Review'); ?></h4>
                        <p class="review-text"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                        <div style="margin-top: 15px;">
                            <span class="status-badge <?php echo $review['is_approved'] ? 'approved' : 'pending'; ?>">
                                <?php echo $review['is_approved'] ? 'Approved' : 'Pending Approval'; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="text-align: center; padding: 60px;">
                    <i class="fas fa-comment-slash" style="font-size: 3rem; color: var(--color-beige); margin-bottom: 15px;"></i>
                    <p>You haven't written any reviews yet.</p>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Address Modal -->
<div class="modal-overlay" id="addressModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modalTitle">Add New Address</h3>
            <button class="modal-close" onclick="closeAddressModal()">&times;</button>
        </div>
        <form method="POST" action="">
            <?php echo csrfField(); ?>
            <input type="hidden" name="save_address" value="1">
            <input type="hidden" name="address_id" id="addressId" value="">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" id="addrFullName" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="tel" name="phone" id="addrPhone" required>
            </div>

            <div class="form-group">
                <label>Address Line 1</label>
                <input type="text" name="address_line1" id="addrLine1" required>
            </div>

            <div class="form-group">
                <label>Address Line 2</label>
                <input type="text" name="address_line2" id="addrLine2">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" id="addrCity" required>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" id="addrState" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" name="postal_code" id="addrPostal" required>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" id="addrCountry" value="India">
                </div>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_default" id="addrDefault">
                    Set as default address
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                <i class="fas fa-save"></i> Save Address
            </button>
        </form>
    </div>
</div>

<script>
function openAddressModal() {
    document.getElementById('addressModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Add New Address';
    document.getElementById('addressId').value = '';
    document.getElementById('addrFullName').value = '';
    document.getElementById('addrPhone').value = '';
    document.getElementById('addrLine1').value = '';
    document.getElementById('addrLine2').value = '';
    document.getElementById('addrCity').value = '';
    document.getElementById('addrState').value = '';
    document.getElementById('addrPostal').value = '';
    document.getElementById('addrCountry').value = 'India';
    document.getElementById('addrDefault').checked = false;
}

function editAddress(addr) {
    document.getElementById('addressModal').classList.add('active');
    document.getElementById('modalTitle').textContent = 'Edit Address';
    document.getElementById('addressId').value = addr.address_id;
    document.getElementById('addrFullName').value = addr.full_name;
    document.getElementById('addrPhone').value = addr.phone;
    document.getElementById('addrLine1').value = addr.address_line1;
    document.getElementById('addrLine2').value = addr.address_line2 || '';
    document.getElementById('addrCity').value = addr.city;
    document.getElementById('addrState').value = addr.state;
    document.getElementById('addrPostal').value = addr.postal_code;
    document.getElementById('addrCountry').value = addr.country;
    document.getElementById('addrDefault').checked = addr.is_default == 1;
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.remove('active');
}

// Close modal on overlay click
document.getElementById('addressModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddressModal();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
