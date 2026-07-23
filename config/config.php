<?php
session_start();

require_once __DIR__ . '/database.php';

/*
|--------------------------------------------------------------------------
| Site Configuration
|--------------------------------------------------------------------------
*/

define('SITE_NAME', 'Yarnify');
define('SITE_URL', 'http://localhost/websites');
define('ADMIN_URL', SITE_URL . '/admin');
define('ASSETS_URL', SITE_URL . '/assets');
define('ADMIN_ASSETS_URL', ADMIN_URL . '/admin/assets');

define('UPLOAD_PATH', __DIR__ . '/../assets/images/uploads/');
define('PRODUCT_IMAGE_PATH', __DIR__ . '/../assets/images/products/');
define('CUSTOM_IMAGE_PATH', __DIR__ . '/../assets/images/custom/');
define('SLIDER_IMAGE_PATH', __DIR__ . '/../assets/images/slider/');


define('ALLOWED_IMAGE_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'
]);

define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB

define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', 'yarnify_csrf_token');


define('PRODUCTS_PER_PAGE', 12);
define('ORDERS_PER_PAGE', 10);

function generateCSRFToken()
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }

    return $_SESSION[CSRF_TOKEN_NAME];
}

function verifyCSRFToken($token)
{
    return isset($_SESSION[CSRF_TOKEN_NAME]) &&
        hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

function csrfField()
{
    $token = generateCSRFToken();

    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . htmlspecialchars($token) . '">';
}


function redirect($url)
{
    header("Location: " . $url);
    exit();
}


function setFlashMessage($type, $message)
{
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessages()
{
    $messages = $_SESSION['flash_messages'] ?? [];

    unset($_SESSION['flash_messages']);

    return $messages;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdminLoggedIn()
{
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function getCurrentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

function getCurrentAdminId()
{
    return $_SESSION['admin_id'] ?? null;
}

function requireLogin()
{
    if (!isLoggedIn()) {

        setFlashMessage('warning', 'Please login to continue.');

        redirect(SITE_URL . '/login.php');
    }
}

function requireAdminLogin()
{
    if (!isAdminLoggedIn()) {

        setFlashMessage('warning', 'Please login as admin to continue.');

        redirect(SITE_URL . '/admin/login.php');
    }
}

function sanitize($data)
{
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function formatPrice($price)
{
    return 'रु ' . number_format($price, 2);
}

function generateOrderNumber()
{
    return 'YNF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

function uploadImage($file, $destinationPath, $prefix = '')
{
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return [
            'success' => false,
            'error' => 'No file uploaded.'
        ];
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES)) {
        return [
            'success' => false,
            'error' => 'Invalid file type. Only JPG, PNG, GIF and WebP images are allowed.'
        ];
    }

    // Check file size
    if ($file['size'] > MAX_FILE_SIZE) {
        return [
            'success' => false,
            'error' => 'File size exceeds 5 MB.'
        ];
    }

    // Create directory if it doesn't exist
    if (!is_dir($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Generate unique filename
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $filename = $prefix . uniqid() . "_" . time() . "." . $extension;

    $filepath = $destinationPath . $filename;

    // Upload file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {

        return [
            'success' => true,
            'filename' => $filename,
            'path' => $filepath
        ];
    }

    return [
        'success' => false,
        'error' => 'Failed to upload file.'
    ];
}

function deleteImage($filename, $path)
{
    $filepath = $path . $filename;

    if (file_exists($filepath)) {
        return unlink($filepath);
    }

    return false;
}
?>