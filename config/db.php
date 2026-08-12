<?php

$host = "db";
$username = "crochet_user";
$password = "crochet_password";
$database = "yarnify_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>