<?php
include 'db.php';

$metrics = [
    'totalOrders' => 0,
    'totalUsers' => 0,
    'totalProducts' => 0
];

$result = $conn->query("SELECT COUNT(orderID) AS count FROM `order`");
if ($result) {
    $metrics['totalOrders'] = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) AS count FROM `users`");
if ($result) {
    $metrics['totalUsers'] = $result->fetch_assoc()['count'];
}

$result = $conn->query("SELECT COUNT(*) AS count FROM `product`");
if ($result) {
    $metrics['totalProducts'] = $result->fetch_assoc()['count'];
}

header('Content-Type: application/json');
echo json_encode($metrics);
?>
