<?php
session_start();
include "db.php";

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received"]);
    exit;
}

$name = mysqli_real_escape_string($conn, $data['name']);
$email = $_SESSION['email']; // ✅ Secure and consistent
$payment = mysqli_real_escape_string($conn, $data['payment']);
$cart = $data['cart'];

if (empty($name) || empty($payment) || empty($cart)) {
    echo json_encode(["success" => false, "message" => "All fields required"]);
    exit;
}

foreach ($cart as $item) {
    $product_name = mysqli_real_escape_string($conn, $item['name']);
    $product_price = floatval($item['price']);
    $quantity = intval($item['qty']);
    $product_image = mysqli_real_escape_string($conn, $item['img']);

    $sql = "INSERT INTO orders 
        (product_name, product_image, product_price, quantity, username, email, payment_method, order_date, status)
        VALUES 
        ('$product_name', '$product_image', '$product_price', '$quantity', '$name', '$email', '$payment', NOW(), 'Pending')";

    if (!mysqli_query($conn, $sql)) {
        echo json_encode(["success" => false, "message" => "DB Error: " . mysqli_error($conn)]);
        exit;
    }
}

echo json_encode(["success" => true]);


?>