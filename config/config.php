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
define('PRODUCTS_PER_PAGE',10);

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


