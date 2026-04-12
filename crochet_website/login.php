<?php
include 'connect.php';

$error = '';

if (isset($_POST['submit'])) {
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
        @import url('https://fonts.googleapis.com/css2?family=Lilita+One&family=Pacifico&family=Sono:wght,MONO@200..800,1&display=swap');
        *{
           font-family: "Lilita One", sans-serif;
            font-weight: 200;
            font-style: normal; 
            margin:0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
           background: linear-gradient(135deg, #fde2e4,#fff1c1,#e6f4ea);
        }
        .login-container{
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
        }
        .form-container{
        position: relative;
        padding: 50px 40px;
        background-color: white;
        width: 100%;
        max-width: 450px;
        border-radius: 8px;
        border: 1px solid rgb(0, 0, 0);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        gap: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .form-header p{
        font-size: 30px;
        color: #f4b9e8;
        font-family: "Pacifico", cursive;
        font-weight: 400;
        font-style: normal;
        text-align: center;
        }     
        .form-header h1{
            font-size: 32px;
            text-align: center;
            color: #614c37;
        }   

        .form-box{
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 10px;
        }
        .input-group{
            position: relative;
            display: flex;
        }
        .input-field{
            height: 48px;
            padding-inline: 14px;
            border: 1px solid rgb(184, 184, 184);
            border-radius: 6px;
            width: 100%;
            outline:none;
        }

        #password{
            padding-right: 40px;
        }

        .input-field:focus{
            border-color:rgb(182, 253, 207);
        }

        .eye-icon{
            position: absolute;
            right: 15px;
            display: flex;
            top: 50%;
            transform: translateY(-50%);
        }
        .eye-icon .fa-eye{
            width: 20px;
            height: 20px;
            cursor: pointer;
            color: rgb(187, 187, 187);
        }
        .fa-eye-slash{
            width: 20px;
            height: 20px;
            cursor: pointer;
            color: rgb(187, 187, 187);
        }
        .checkbox-group{
            display: flex;
            justify-content: space-between;
            gap:10px;
            font-size: 14px;
        }
        .remember-me{
            display: flex;
            align-items: center;
            gap:5px;
        }
        .form-btn{
            display: flex;
            justify-content: center;
            align-items: center;
            gap:10px;
            height:48px;
            background: none;
            border-radius: 6px;
            border: none;
            cursor:pointer;  
        }
        .form-btn--submit{
            background-color: rgb(62, 124, 223);
            width: 100%;
            color: white;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .form-btn--submit:hover{
            background-color: rgb(30, 94, 245);
        }
        .form-bottom{
            display: flex;
            flex-direction: column;
            gap:20px;
            margin-top: 20px;  
        }

        .form-divider{
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
            color: rgb(187, 187, 187);
            margin-top: 6px;
        }
        .form-divider:before,
        .form-divider::after{
            content: "";
            flex: 1;
            height: 1px;
            background-color: rgb(183, 181, 181);
            margin-bottom: 14px ;
        }
        .form-socials{
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap:20px;
        }
        .form-btn--social{
            transition: .3s ease;
            background: white;
            border: 1px solid #e0e0e0;
        }
        .form-btn--social:hover{
            background-color: rgb(225, 225, 225);
        }
        .btn-icon{
            width: 50px;
        }
        .form-link{
            color: #47a7f5;
            text-decoration: none;
        }
        .form-link:hover{
            text-decoration: underline;
        }
        .form-blob{
            position: absolute;
            width:100px;
            height:100px;
            top:0px;
            right:0px;
            z-index:0;
        }
        .blob-image{
            position: absolute;
            rotate:20deg;
            pointer-events: none;
            opacity: 0; 
            transform: scale(0.5) translateX(100px);
        }
        .blob-image--1{
            top:-210px;
            right:-240px;
            animation: welcomeIn 1.2s ease-out 0.3s forwards;
            --final-opacity: 1;
        }
        .blob-image--2{
            top:-205px;
            right:-240px;
            animation: welcomeIn 1.2s ease-out 0.6s forwards;
            --final-opacity: 0.6;
        }
        .blob-image--3{
            top:-200px;
            right:-240px;
            animation: welcomeIn 1.2s ease-out 0.9s forwards;
            --final-opacity: 0.3;
        }
        @keyframes welcomeIn {
            0%{
                opacity: 0;
                transform: scale(0.5) translateX(100px) rotate(180deg);
            }
            60%{
                opacity: calc(var(--final-opacity)*0.8);
                transform: scale(1.1) translateX(-10px) rotate(200deg);
            }
            100%{
                opacity: calc(var(--final-opacity));
                transform: scale(1) translateX(0px) rotate(205deg);
            }
        }

        /* Error message styling */
        .error-message {
            background: #ffe0e0;
            color: #ff6b6b;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
            margin-bottom: 10px;
        }

        @media (max-width: 480px) {
            .form-container{
                padding: 60px 30px;
            }
            .form-header p {
                font-size: 26px;
            }
        }
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
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form class="form-box" method="POST">
                <div class="input-group">
                    <input type="email" name="email" class="input-field" placeholder="Email address" required
                           value="<?php echo $remembered_email; ?>">
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="input-field" placeholder="Password" required>
                    <div class="eye-icon" onclick="togglePassword()">
                        <i class='far fa-eye'></i>
                    </div>
                </div>
                <div class="input-group checkbox-group">
                    <div class="form-col remember-me">
                        <input type="checkbox" name="remember_me" class="checkbox-field">
                        <label>Remember me</label>
                    </div>
                    <div class="form-col">
                        <a href="#" class="form-link">Forgot Password?</a>
                    </div>
                </div>

                <button type="submit" name="submit" class="form-btn form-btn--submit">Sign In</button>
            </form>
        <div class="form-divider">
            <p>Or</p>
        </div>
        <div class="form-bottom">
            <div class="form-socials">
                <button type="button" class="form-btn form-btn--social">
                    <img src="login/google.png" alt="google icon" class="btn-icon">
                </button>

                <button type="button" class="form-btn form-btn--social">
                    <img src="login/facebook.png" alt="facebook icon" class="btn-icon">
                </button>

                <button type="button" class="form-btn form-btn--social">
                    <img src="login/apple.png" alt="apple icon" class="btn-icon">
                </button>
                </div>

                <p style="text-align: center;">Don't have an account?
                <a href="register.php" class="form-link">Sign up</a>
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