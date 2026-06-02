<?php
define('Host', 'localhost');
define('User','root');
define('Port', '3307');
define('Pass', '');
define('db_name', 'yarnify_db');
define('DB_CHARSET', 'utf8mb4');

//creating db connection
function getDBConnection(){
    static $pdo= null;

    if($pdo === null){
        try{
            $dsn= "mysql:host=". Host . ";port=". PORT . ";dbname". db_name . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE   =>PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES  => false,
            ];
            $pdo= new PDO($dsn, User, Pass, $options);
        }catch(PDOException $e){
            error_log("Database Connection Error: "$e->getMessage());
            die("Database connectin failed. Please check your configuration.");
        }
    }
    return $pdo;
}


?>