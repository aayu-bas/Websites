<?php
require_once__DIR__ . '/../config/config.php';
// require_once __DIR__ . '/../config/config.php';

//redirect if already logged in
if(isLoggedIn()){
    redirect(SITE_URL .'/index.php');
}
$error = '';

if ($_SERVER['REQUEST_METHOD']== 'POST') {
    //verify CSRF Token
    if(!verifyCSRFToken($POST[CSRF_TOKEN_NAME]?? '')){
        error='Invalid request. Please try again.';
    }else{
        $email = $_POST['email'];
        $password = $_POST['password'];
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password';
        }else{
            //fetch user by email
            $user= fetchOne("SELECT * FROM users WHERE email=? AND is_active=1", [$email]);

            if($user && password_verify($password, $user['password_hash'])){
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];

                //update last login
                executeQuery("UPDATE users SET last_login = NOW() WHERE user_id = ?", [$user['user_id']]);

                //handle remember me
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/');
                }
                setFlashMessage('success', 'Welcome back, ' . htmlspecialchars($user['first_name']) . '!');

                //Redirect to intended page
                $redirect=$_SESSION['redirect_after_login'] ?? SITE_URL. '/index.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redirect);
            }else{
                $error = 'Invalid email or password. Please try again.';
            }
        }
    }
}
$pageTitle = 'Login';
$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/auth.css">';

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Yarnify</title>
    <link rel="icon" href="yarnify.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="login-container">
    <div class="form-container">
        <div class="form-blob">
            <img src="login/blob.png" alt="blob" class="blob-image blob-image--1">
            <img src="login/blob.png" alt="blob" class="blob-image blob-image--2">
            <img src="login/blob.png" alt="blob" class="blob-image blob-image--3">
        </div>
        <div class="form-header">
            <p>Yarnify</p>
            <h1>Welcome Back</h1>
        </div>

        <?php if ($error): ?>
            <div class="flash-message error" style="margin-bottom: 20px; position: static;">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="">
            <?php echo csrfField(); ?>

            <div class="input-group">
                <input type="email" id="email" name="email" class="input-field" placeholder="Email address" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" class="input-field" placeholder="Password" required>
                </div>
            </div>
            <div class="input-group checkbox-group">
                <div class="form-col remember-me">
                    <input type="checkbox" name="remember" value="1" class="checkbox-field">
                    <label>Remember me</label>
                </div>
                <div class="form-col">
                    <a href="forgot-password.php" class="form-link">Forgot Password?</a>
                </div>
            </div>

            <button type="submit" name="submit" class="form-btn form-btn--submit">
                Sign In</button>
        </form>
            
        <div class="form-divider">
            <p>Or</p>
        </div>

        <div class="form-bottom">
            <div class="form-socials">
                <p style="text-align: center;">Don't have an account?
                <a href="register.php" class="form-link">Create one now</a>
                </p>
            </div>
        </div>
</div>
</body>
</html>