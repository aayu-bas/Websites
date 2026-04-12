<?php
$host = "localhost";
$db_user = "root";  
$db_pass = "";      
$db_name = "yarnify_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name, 3307);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['place_order_btn'])) {
    
    $fname   = $_POST['first_name'];
    $lname   = $_POST['last_name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];
    $province = $_POST['province'];
    $pin_code= $_POST['pin_code'];
    $pay_method = $_POST['payment_method'];
    $payment_status= $_POST['payment_status'];
    $order_date= $_POST['order_date'];

    $sql = "INSERT INTO orders (first_name, last_name, email, phone, address, province,pin_code, payment_method, payment_status,  order_date) 
            VALUES ('$fname', '$lname', '$email', '$phone', '$address', '$province','$pin_code','$pay_method', 'payment_status','order_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Order placed Successfully!! ";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>