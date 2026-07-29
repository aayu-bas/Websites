<?php
require_once __DIR__ . '/../config/config.php';
$pageTitle = 'Login ';

//redirect if already logged in
if(isLoggedIn()){
    redirect(SITE_URL .'/index.php');
}

$error = '';
if(isset($_SESSION['login_error'])){
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if ($_SERVER['REQUEST_METHOD']== 'POST') {
    //verify CSRF Token
    if(!verifyCSRFToken($_POST[CSRF_TOKEN_NAME]?? '')){
        $error='Invalid request. Please try again.';
    }else{
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password';
        }else{
            //fetch user by email
            $email = mysqli_real_escape_string($conn, $email);
            $sql = "SELECT * FROM users
                    WHERE email = '$email'
                    AND is_active = 1";
                
            $result = mysqli_query($conn, $sql);
            if($result){
                $user = mysqli_fetch_assoc($result);
            }else{
                $error = "Database error.";
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error = "Please enter a valid email address.";
            }
            

            if($user && password_verify($password, $user['password_hash'])){
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];

                //update last login
                $userId = (int)$user['user_id'];

                $updateSql = "UPDATE users
                  SET last_login = NOW()
                  WHERE user_id = $userId";

                mysqli_query($conn, $updateSql);
                //handle remember me

                if ($remember) {

                $token = bin2hex(random_bytes(32));

                $userId = (int)$user['user_id'];

                mysqli_query($conn,
                    "UPDATE users
                    SET remember_token='$token'
                    WHERE user_id=$userId"
                );

                setcookie("remember_token", $token, time() + 3600,"/");
            }
                setFlashMessage('success', 'Welcome back, ' . htmlspecialchars($user['first_name']) . '!');

                //Redirect to intended page
                $redirect=$_SESSION['redirect_after_login'] ?? SITE_URL. '/index.php';
                unset($_SESSION['redirect_after_login']);
                redirect($redirect);
            }else{
                $_SESSION['login_error'] = 'Invalid email or password. Please try again.';
                redirect($_SERVER['PHP_SELF']);
            }
        }
    }
}

$extraCSS = '<link rel="stylesheet" href="' . ASSETS_URL . '/css/auth.css">';
?>
<!DOCTYPE html>
<html>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle)? htmlspecialchars($pageTitle). '| ': '';?>Yarnify- Handmade Crochet Store</title>
    <link rel="icon" href="yarnify.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php echo $extraCSS; ?>
</head>   
<body>
<div class="login-container">
    <div class="form-container">

        <div class="form-header">
            <a href="../index.php"><p>Yarnify</p></a>
            <h1>Welcome Back</h1>
        </div>

        <?php if ($error): ?>
            <div class="flash-message error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <form class="auth-form" method="POST" action="">
            <?php echo csrfField(); ?>

            <div class="input-group">
                <input type="email" id="email" name="email" class="input-field" autocomplete="current-password" placeholder="Email address" required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" autocomplete="current-password" class="input-field" placeholder="Password" required>
            </div>

            <div class="checkbox-group">

                <div class="remember-me">
                    <input type="checkbox" name="remember" value="1" class="checkbox-field" id="remember">

                    <label for="remember">Remember me</label>
                </div>

                <a href="forgot-password.php" class="form-link">
                    Forgot Password?
                </a>

            </div>

            <button type="submit" name="submit" class="form-btn">
                Sign In
            </button>

        </form>

        <div class="form-divider">
            <p>Or</p>
        </div>

        <div class="form-bottom">
            <p style="text-align:center;">
                Don't have an account?
                <a href="register.php" class="form-link">
                    Create one now
                </a>
            </p>
        </div>

    </div>
</div>
</body>
</html>