<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "yarnify_db";
$port = 3307;

$conn = mysqli_connect($host, $username, $password, $database, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// mysqli_set_charset($conn, "utf8mb4");
?>