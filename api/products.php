<?php

header("Content-Type: application/json");

include "../config/db.php";

$result = $conn->query(
    "SELECT id, name, description, price, stock
     FROM products
     ORDER BY id DESC"
);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "success" => true,
    "products" => $products
]);

?>