<?php

require_once __DIR__ . '/../config/config.php';

if (isLoggedIn()) {
    redirect(SITE_URL . '/index.php');
}

if (!isset($_SESSION['pending_registration'])) {
    redirect(SITE_URL . '/pages/register.php');
}

$errors = [];

$registration = $_SESSION['pending_registration'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyCSRFToken($_POST[CSRF_TOKEN_NAME] ?? '')) {

        $errors[] = 'Invalid request. Please try again.';

    } else {

        $enteredOTP = trim($_POST['otp'] ?? '');

        if (empty($enteredOTP)) {

            $errors[] = 'Please enter the OTP.';

        } elseif (!preg_match('/^\d{6}$/', $enteredOTP)) {

            $errors[] = 'OTP must be 6 digits.';

        } elseif (time() > $registration['otp_expiry']) {

            $errors[] = 'OTP has expired. Please register again.';

            unset($_SESSION['pending_registration']);

        } elseif (!hash_equals($registration['otp'], $enteredOTP)) {

            $errors[] = 'Invalid OTP. Please try again.';

        } else {

            // OTP is correct
            $firstName = mysqli_real_escape_string(
                $conn,
                $registration['first_name']
            );

            $lastName = mysqli_real_escape_string(
                $conn,
                $registration['last_name']
            );

            $email = mysqli_real_escape_string(
                $conn,
                $registration['email']
            );

            $phone = mysqli_real_escape_string(
                $conn,
                $registration['phone']
            );

            $passwordHash = mysqli_real_escape_string(
                $conn,
                $registration['password_hash']
            );

            // Create the account only after OTP verification
            $insertSql = "INSERT INTO users
                (first_name, last_name, email, password_hash, phone)
                VALUES
                ('$firstName', '$lastName', '$email', '$passwordHash', '$phone')";

            if (mysqli_query($conn, $insertSql)) {

                $userId = mysqli_insert_id($conn);

                // Create cart
                $cartSql = "INSERT INTO cart (user_id)
                            VALUES ($userId)";

                mysqli_query($conn, $cartSql);

                // Remove temporary registration data
                unset($_SESSION['pending_registration']);

                // Login user
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] =
                    $registration['first_name'] . ' ' .
                    $registration['last_name'];

                $_SESSION['user_email'] =
                    $registration['email'];

                redirect(SITE_URL . '/index.php');

            } else {

                $errors[] =
                    'Account creation failed. Please try again later.';

                error_log(mysqli_error($conn));
            }
        }
    }
}

$pageTitle = 'Verify Email';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email | Yarnify</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #fff4fc, #f1edff);
            color: #3f3454;
        }

        .auth-page {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
        }

        .auth-form-wrapper {
            background: #ffffff;
            padding: 45px 40px;
            border: 1px solid #f1dff0;
            border-radius: 25px;
            box-shadow: 0 12px 35px rgba(141, 108, 247, 0.12);
            text-align: center;
        }

        .auth-form-wrapper h2 {
            margin-bottom: 12px;
            color: #8d6cf7;
            font-size: 30px;
            font-weight: 700;
        }

        .subtitle {
            margin-bottom: 30px;
            color: #777080;
            font-size: 15px;
            line-height: 1.6;
        }

        .flash-message {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 22px;
            padding: 13px 15px;
            border-radius: 10px;
            font-size: 14px;
            line-height: 1.5;
            text-align: left;
        }

        .flash-message.error {
            background: #fff0f2;
            border: 1px solid #f4c5ce;
            color: #b34d61;
        }

        .flash-message i {
            margin-top: 3px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 9px;
            color: #514561;
            font-size: 14px;
            font-weight: 600;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: #8d6cf7;
            font-size: 16px;
        }

        .input-icon input {
            width: 100%;
            height: 52px;
            padding: 0 16px 0 45px;
            border: 1px solid #ded5ee;
            border-radius: 12px;
            outline: none;
            background: #fcfaff;
            color: #3f3454;
            font-size: 18px;
            letter-spacing: 7px;
            transition: 0.3s ease;
        }

        .input-icon input::placeholder {
            color: #aaa1b8;
            font-size: 14px;
            letter-spacing: 0;
        }

        .input-icon input:focus {
            border-color: #8d6cf7;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(141, 108, 247, 0.12);
        }

        .btn {
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: 0.3s ease;
        }

        .btn-primary {
            background: #8d6cf7;
            color: #ffffff;
        }

        .auth-btn {
            width: 100%;
            min-height: 52px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            border-radius: 12px;
        }

        .auth-btn:hover {
            background: #7655e6;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(141, 108, 247, 0.22);
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            body {
                padding: 18px;
            }

            .auth-form-wrapper {
                padding: 35px 24px;
                border-radius: 20px;
            }

            .auth-form-wrapper h2 {
                font-size: 26px;
            }

            .subtitle {
                font-size: 14px;
            }
        }
    </style>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

</head>

<body>

<div class="auth-page">

    <div class="auth-container">

        <div class="auth-form-wrapper">

            <h2>Verify Your Email</h2>

            <p class="subtitle">
                We have sent a 6-digit OTP to your email address.
            </p>

            <?php if (!empty($errors)): ?>

                <div class="flash-message error">

                    <i class="fas fa-exclamation-circle"></i>

                    <span> <?php echo implode('<br>', array_map('htmlspecialchars', $errors) ); ?>
                    </span>

                </div>

            <?php endif; ?>

            <form method="POST" class="auth-form">

                <?php echo csrfField(); ?>

                <div class="form-group">

                    <label for="otp">
                        Enter OTP
                    </label>

                    <div class="input-icon">

                        <i class="fas fa-key"></i>

                        <input type="text" id="otp" name="otp" placeholder="Enter 6-digit OTP"
                            maxlength="6" inputmode="numeric" required>

                    </div>

                </div>

                <button type="submit" class="btn btn-primary auth-btn">

                    <i class="fas fa-check"></i>
                    Verify Email

                </button>

            </form>

        </div>

    </div>

</div>

</body>

</html>