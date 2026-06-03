<?php
session_start();

require_once__DIR__ . '/database.php';
// require_once __DIR__ . '/database.php';

//define site constants
define('SITE_NAME', 'Yarnify');
define ('SITE_URL', 'http://localhost/yarnify');

//allowed images types
define('ALLOWED_IMAGES_TYPES', ['images/jpeg', 'images/png', 'images/gif', 'images/webg']);
define ('MAX_FILE_SIZE', 5*1024*1024); //5MB

//session
define('SESSION_LIFETIME', 3600) //1 hr

//security
define('CSRF_TOKEN_NAME', 'yarnify_csrf_token')

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
function getCurrentUserId(){
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

?>