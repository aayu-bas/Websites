<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// Load .env from the project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

function sendOTPEmail($email, $name, $otp)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['HOST_USER'];
        $mail->Password = $_ENV['HOST_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = (int) $_ENV['PORT'];

        $mail->setFrom($_ENV['HOST_USER'], 'Yarnify');
        $mail->addAddress($email, $name);
        $mail->addReplyTo($_ENV['HOST_REPLY_TO'], 'Yarnify');

        $mail->isHTML(true);
        $mail->Subject = 'Yarnify Email Verification';

        $mail->Body = "
            <h2>Yarnify Email Verification</h2>
            <p>Hello {$name},</p>
            <p>Your verification code is:</p>
            <h1>{$otp}</h1>
            <p>This code will expire in 10 minutes.</p>
        ";

        $mail->send();

        return true;
    } catch (Exception $e) {
        error_log("Email could not be sent: {$mail->ErrorInfo}");
        return false;
    }
}