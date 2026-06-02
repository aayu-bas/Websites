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

// a helper function for the prepared statement
function executeQuery($sql, $params = []){
    $pdo= getDBConnection();
    $stmt= $pdo->prepare($sql);
    $stmt->execute($params);
    return stmt;
}

//fetches a single row
function fetchOne($sql, $params=[]){
    $stmt= executeQuery($sql, $params);
    return $stmt->fetch();
}

//to fetch all the rows
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

// to get last insert ID
function lasInsertID(){
    return getDBConnection()->lasInsertID();
}

//to get row count
function rowCount($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->rowCount();
}
?>