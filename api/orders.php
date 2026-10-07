<?php

header("Content-Type: application/json");

include "../config/db.php";

$result = $conn->query(
    "SELECT
        orders.id,
        users.name AS customer,
        users.email,
        orders.total_amount,
        orders.status,
        orders.created_at
     FROM orders
     JOIN users ON orders.user_id = users.id
     ORDER BY orders.id DESC"
);

$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

echo json_encode([
    "success" => true,
    "orders" => $orders
]);

?>