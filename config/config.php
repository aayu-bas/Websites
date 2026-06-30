<?php
session_start();

require_once __DIR__ . '/database.php';


//define site constants
define('SITE_NAME', 'Yarnify');
define ('SITE_URL', 'http://localhost/yarnify');

//file upload paths
define('UPLOAD_PATH', __DIR__ . '/../assets/images/uploads/');
define('PRODUCT_IMAGE_PATH', __DIR__ . '/../assets/images/products/');
define('CUSTOM_IMAGE_PATH', __DIR__ . '/../assets/images/custom/');
define('SLIDER_IMAGE_PATH', __DIR__ . '/../assets/images/slider/');

//allowed images types
define('ALLOWED_IMAGES_TYPES', ['images/jpeg', 'images/png', 'images/gif', 'images/webg']);
define ('MAX_FILE_SIZE', 5*1024*1024); //5MB

//session
define('SESSION_LIFETIME', 3600); //1 hr

//security
define('CSRF_TOKEN_NAME', 'yarnify_csrf_token');

//Pagination
define('PRODUCTS_PER_PAGE',12);
define('ORDERS_PER_PAGE',10);


//function to generate csrf token
function generateCSRFToken(){
    if(empty($_SESSION['CSRF_TOKEN_NAME'])){
        $_SESSION[CSRF_TOKEN_NAME]= bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

//verify CSRF token
function verifyCSRFToken($token){
    return isset($SESSION[CSRF_TOKEN_NAME]) && hash_equals($SESSION[CSRF_TOKEN_NAME],$token);
}

//get CSRF token input field
function csrfField(){
    $token = generateCSRFToken();
    return '<input type="hidden" name="'.CSRF_TOKEN_NAME. '"value="'.htmlspecialchars($token). '">';
}

function redirect($url){
    header("Location: ". $url);
    exit();
}

//flash message system
function setFlashMessage($type, $message){
    $_SESSION['flash_message'][]=['type'=> $type, 'message'=> $message];
}

function getFlashMessage(){
    $messages = $_SESSION['flash_messages']??[];
    unset($_SESSION['flash_messages']);
    return $messages;
}

//check if user is logged in
function isLoggedIn(){
    return isset($_SESSION['user_id'])&& !empty($_SESSION['user_id']);
}

//check if admin is logged in
function isAdminLoggedIn(){
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

//get current user ID
function getCurrentUserId(){
    return $_SESSION['user_id']??null;
}

// Get current admin ID
function getCurrentAdminId(){
    return $_SESSION['admin_id']??null;
}

//require login
function requireLogin(){
    if(!isLoggedIn()){
        setFlashMessage('warning', 'Please Login to continue.');
        redirect(SITE_URL, 'login.php'); //might need to change
    }
}

//require admin Login
function requireAdminLogin(){
    if(!isAdminLoggedIn()){
        setFlashMessage('warning', 'Please Login as admin to continue.');
        redirect(SITE_URL, 'login.php');
    }
}

//sanitize input
function sanitize($data){
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function formatPrice($price){
    return 'रु'. number_format($price,2);
}

//generate unique order number for the customer
function generateOrderNumber(){
    return 'YNF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
}

function uploadImage($file, $destinationPath, $prefix = ''){
    if(!isset($file['tmp_name']) || empty($file['tmp_name'])){
        return['success'=>false, 'error'=> 'No file uploaded'];
    }
    //check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if(!in_array($mimeType, ALLOWED_IMAGE_TYPES)){
        return['success'=> false, 'error'=> 'Invlaid file type. Ony JPG, PNG, GIF, and WEbP are allowed.'];
    }

    //check the file size
    if($file['size']>MAX_FILE_SIZE){
        return ['success'=> false, 'error'=> 'File too large. Maz=ximum size is 5MB.'];
    
    }
    //generate unique file name
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . uniqid() . '_' .  time() . '.' . $extension;
    $filepath = $destinationPath. $filename;

    //creating directory if not exists
    if(!is_dir($destinationPath)){
        mkdir($destinationPath, 0755, true);
    }
    //move uploaded file
    if(move_uploaded_file($file['tmp_name'], $filepath)){
        return['success'=> true, 'filename'=>$filename,  'path' =>$filepath];
    }
    return ['success' => false, 'error' => 'Failed to upload file.'];
}

//delete image file
function deleteImage($filename, $path){
    $filepath=$path . $filename;
    if(file_exists($filepath)){
        return unlink($filepath);
    }
    return false;
}
?>