<?php

session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION["cart"])) {
    header("Location: cart.php");
    exit;
}

$total = 0;

foreach ($_SESSION["cart"] as $id => $quantity) {

    $stmt = $conn->prepare(
        "SELECT price FROM products WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $product = $stmt->get_result()->fetch_assoc();

    if ($product) {
        $total += $product["price"] * $quantity;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION["user_id"];

    // Create order
    $stmt = $conn->prepare(
        "INSERT INTO orders (user_id, total_amount, status)
         VALUES (?, ?, 'Pending')"
    );

    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();

    $order_id = $conn->insert_id;

    // Add products to order_items
    foreach ($_SESSION["cart"] as $id => $quantity) {

        $stmt = $conn->prepare(
            "SELECT price FROM products WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        $product = $stmt->get_result()->fetch_assoc();

        if (!$product) {
            continue;
        }

        $price = $product["price"];

        $stmt = $conn->prepare(
            "INSERT INTO order_items
             (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iiid",
            $order_id,
            $id,
            $quantity,
            $price
        );

        $stmt->execute();

        // Reduce stock
        $stmt = $conn->prepare(
            "UPDATE products
             SET stock = stock - ?
             WHERE id = ? AND stock >= ?"
        );

        $stmt->bind_param(
            "iii",
            $quantity,
            $id,
            $quantity
        );

        $stmt->execute();
    }

    // Empty cart
    $_SESSION["cart"] = [];

    header("Location: my_orders.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Checkout - ShopEasy</title>

    <style>

        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .box {
            width: 500px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

    </style>

</head>

<body>

<div class="box">

    <h2>Checkout</h2>

    <h3>
        Total Amount:
        ₹<?php echo number_format($total, 2); ?>
    </h3>

    <p>Payment Method: Cash on Delivery</p>

    <form method="POST">

        <button type="submit">
            Place Order
        </button>

    </form>

    <br>

    <a href="cart.php">← Back to Cart</a>

</div>

</body>

</html>