<?php

require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$query = trim($_GET['q'] ?? '');

if ($query === '') {
    echo json_encode([]);
    exit;
}

$query = mysqli_real_escape_string($conn, $query);

$sql = "SELECT p.product_id, p.product_name, p.slug, p.price, p.sale_price, c.category_name, pi.image_path AS image
    FROM products p
    JOIN categories c
        ON p.category_id = c.category_id
    LEFT JOIN product_images pi
        ON p.product_id = pi.product_id
        AND pi.is_primary = 1
    WHERE p.is_active = 1
      AND (
          p.product_name LIKE '%$query%'
          OR c.category_name LIKE '%$query%'
      )
    LIMIT 8
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'error' => mysqli_error($conn)
    ]);
    exit;
}

$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = [
        'slug' => $row['slug'],
        'name' => $row['product_name'],
        'category_name' => $row['category_name'],
        'price' => (
            !empty($row['sale_price']) &&
            $row['sale_price'] < $row['price']
        )
            ? $row['sale_price']
            : $row['price'],
        'image' => $row['image'] ?? 'placeholder.jpg'
    ];
}

echo json_encode($products);
exit;