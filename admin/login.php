<?php
require_once __DIR__ . '/../config/config.php';

// Redirect if already logged in as admin
if (isAdminLoggedIn()) {
    redirect(ADMIN_URL . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $error = 'Invalid request. Please try again.';

    } else {

        $username = sanitize($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {

            $error = 'Please enter both username and password.';

        } else {

            // Escape username before using it in SQL
            $username = mysqli_real_escape_string($conn, $username);

            // Fetch admin by username
            $sql = "SELECT * FROM admins WHERE username = '$username'AND is_active = 1";

            $result = mysqli_query($conn, $sql);

            $admin = false;

            if ($result) {
                $admin = mysqli_fetch_assoc($result);
            }

            // Verify password
            if ($admin && password_verify($password, $admin['password_hash'])) {

                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name'] = $admin['full_name'];
                $_SESSION['admin_role'] = $admin['role'];

                // Update last login
                $adminId = (int)$admin['admin_id'];

                $updateSql = "UPDATE admins SET last_login = NOW() WHERE admin_id = $adminId";

                mysqli_query($conn, $updateSql);

                setFlashMessage('success', 'Welcome back, ' . htmlspecialchars($admin['full_name']) . '!');

                redirect(ADMIN_URL . '/index.php');

            } else {
                $error = 'Invalid username or password.';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Yarnify</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo ADMIN_ASSETS_URL; ?>/css/admin_login.css">
</head>
<body class="admin-body">
    <div class="admin-login-page">
        <div class="admin-login-box">
            <div class="logo" style="justify-content: center; margin-bottom: 30px;">
                <div class="logo-icon"><img src="<?php echo ASSETS_URL; ?>/images/yarnify.png" alt="logo" class="logo-image"></div>
                <span style="font-size: 1.5rem; font-weight: 700; color: var(--color-dark-brown);">Yarnify Admin</span>
            </div>

            <?php if ($error): ?>
            <div class="flash-message error" style="margin-bottom: 20px; position: static;">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="">
                <?php echo csrfField(); ?>

                <div class="form-group">
                    <label>Username</label>
                    <div class="input-icon">
                        <i class="fas fa-user-shield"></i>
                        <input type="text" name="username" placeholder="Enter admin username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Enter password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary auth-btn" style="width: 100%; margin-top: 10px;">
                    <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font-size: 0.9rem; color: var(--color-gray);">
                <a href="<?php echo SITE_URL; ?>/index.php" style="color: var(--color-purple);">
                    <i class="fas fa-arrow-left"></i> Back to Store
                </a>
            </div>
        </div>
    </div>
</body>
</html>
