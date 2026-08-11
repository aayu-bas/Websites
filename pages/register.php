<?php
require_once __DIR__ . '/../config/config.php';

if(isLoggedIn()){
    redirect(SITE_URL . '/index.php');
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
      $errors[] = 'Invalid request. Please try again.';
  }else{
    $firstName = sanitize($_POST['first_name'] ?? '');
    $lastName = sanitize($_POST['last_name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($firstName)) {
      $errors[] = 'First name is required.';
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $firstName)) {
      $errors[] = 'First name can only contain letters, spaces, hyphens, and apostrophes.';
    }

    if (empty($lastName)) {
      $errors[] = 'Last name is required.';
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $lastName)) {
      $errors[] = 'Last name can only contain letters, spaces, hyphens, and apostrophes.';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
      $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
      $errors[] = 'Password must be at least 6 characters long.';
    }
    if ($password !== $confirmPassword) {
      $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
      $email = mysqli_real_escape_string($conn, $email);
      $checkSql = "SELECT user_id FROM users WHERE email = '$email'";

      $checkResult = mysqli_query($conn, $checkSql);

      if (mysqli_num_rows($checkResult) > 0) {
        $errors[] = 'An account with this email already exists. Please login instead.';
      }
    }

    if (empty($errors)) {
      $passwordHash = password_hash($password, PASSWORD_BCRYPT);

      $firstName = mysqli_real_escape_string($conn, $firstName);
      $lastName = mysqli_real_escape_string($conn, $lastName);
      $email = mysqli_real_escape_string($conn, $email);
      $phone = mysqli_real_escape_string($conn, $phone);

      $insertSql = "INSERT INTO users (first_name, last_name, email, password_hash, phone)
                    VALUES ('$firstName', '$lastName', '$email', '$passwordHash', '$phone')";

      if (mysqli_query($conn, $insertSql)) {
        $userId = mysqli_insert_id($conn);

        $cartSql = "INSERT INTO cart (user_id) VALUES ($userId)";

        mysqli_query($conn, $cartSql);

        // Auto login
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $firstName . ' ' . $lastName;
        $_SESSION['user_email'] = $email;

        setFlashMessage( 'success', 'Welcome to Yarnify, ' . htmlspecialchars($firstName) . '! Your account has been created successfully.');
        redirect(SITE_URL . '/index.php');
      } else {
        $errors[] = 'Registration failed. Please try again later.';

        error_log(mysqli_error($conn));
      }
    }
  }
}

$pageTitle = 'Register ';
$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/auth_regis.css">';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo isset($pageTitle)? htmlspecialchars($pageTitle). '| ': '';?>Yarnify- Handmade Crochet Store</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <?php echo $extraCSS; ?>
</head>
<body>
<div class="auth-page">
  <div class="auth-container">
    <div class="auth-visual">
      <div class="logo-img">
        <a href="../index.php"><img src="../assets/images/yarnify.png" alt="yarnify logo" style="height:150px; width=150px; "></a>
      </div>
      <h2>Join Yarnify!</h2>
      <p>Create your account to start shopping, save your favorites, track orders, and design custom crochet pieces just for you.</p>
    </div>

    <div class="auth-form-wrapper">
      <h2>Create Account</h2>
      <p class="subtitle">Fill in your details to get started</p>

        <?php if (!empty($errors)): ?>
        <div class="flash-message error" style="margin-bottom: 20px; position: static;">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></span>
        </div>
        <?php endif; ?>

      <form method="POST" action="" class="auth-form">
        <?php echo csrfField(); ?>

        <div class="form-row">
          <div class="form-group">
            <label for="first_name">First Name</label>
            <div class="input-icon">
              <i class="fas fa-user"></i>
              <input type="text" id="first_name" name="first_name" placeholder="Your First Name" pattern="[A-Za-z\s'-]+" required
                    value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="last_name">Last Name</label>
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="last_name" name="last_name" placeholder="Your Last Name" pattern="[A-Za-z\s'-]+" required
                      value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="abc@email.com" required
                      value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
          </div>

        <div class="form-group">
          <label for="phone">Phone Number (Optional)</label>
          <div class="input-icon">
            <i class="fas fa-phone"></i>
            <input type="tel" id="phone" name="phone" placeholder="+977 98765 43210"
                  value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Enter the password" required>
          </div>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <div class="input-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
          </div>
        </div>

        <button type="submit" class="btn btn-primary auth-btn">
          <i class="fas fa-user-plus"></i> Create Account
        </button>
        </form>

        <div class="auth-divider">
          <span>OR</span>
        </div>

        <div class="auth-footer">
          Already have an account? <a href="login.php">Sign in here</a>
        </div>

    </div>
  </div>
</div>

</body>
</html>
