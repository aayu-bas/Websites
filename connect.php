<?php
$host = "localhost";
$user = "root";
$pd = "";
$db = "yarnify_db";
$port=3307;

$conn = mysqli_connect($host, $user, $pd, $db, $port);

if (!$conn) {
    die("Database is not connected: " . mysqli_connect_error());
}

// Start session
session_start();
?>