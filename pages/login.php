<?php
require_once__DIR__ . '/../config/config.php';
// require_once __DIR__ . '/../config/config.php';

//redirect if already logged in
if(isLoggedIn()){
    redirect(SITE_URL .'/index.php');
}
$error = '';

if ($_SERVER['REQUEST_METHOD']== 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password';
    } 
    else {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['logged_in'] = true;

                if (isset($_POST['remember_me'])) {
                    setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), '/');
                }

                header("Location: index.php");
                exit();
            } else {
                $error = 'Invalid email or password';
            }
        } else {
            $error = 'Invalid email or password';
        }
    }
}

// Check if user is already logged in
// if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
//     header("Location: index.php");
//     exit();
// }

// Pre-fill email from cookie if it exists
$remembered_email = '';
if (isset($_COOKIE['user_email'])) {
    $remembered_email = $_COOKIE['user_email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Yarnify</title>
    <link rel="icon" href="yarnify.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

    </style>
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
    <script>
        function togglePassword(){
            const passwordInput = document.getElementById("password")
            const eyeIcon= document.querySelector(".eye-icon")

            if(passwordInput.type=== "password"){
                passwordInput.type="text"
                eyeIcon.innerHTML=`<i class="fa fa-eye-slash"></i>`
            } else{
                passwordInput.type="password"
                eyeIcon.innerHTML=`<i class='far fa-eye'></i>`
            }
        }
    </script>
</body>
</html>