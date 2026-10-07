<?php

session_start();
include "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare(
    "SELECT id, total_amount, status, created_at
     FROM orders
     WHERE user_id = ?
     ORDER BY id DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$orders = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Orders - ShopEasy</title>

    <style>

        body {
            font-family: Arial;
            background: #f5f5f5;
            margin: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
        }

        .order {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .status {
            font-weight: bold;
            color: #00a000;
        }

        a {
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>My Orders</h1>

    <?php if ($orders->num_rows == 0): ?>

        <p>No orders found.</p>

    <?php else: ?>

        <?php while ($order = $orders->fetch_assoc()): ?>

            <div class="order">

                <h3>
                    Order #<?php echo $order["id"]; ?>
                </h3>

                <p>
                    Total:
                    ₹<?php echo number_format($order["total_amount"], 2); ?>
                </p>

                <p>
                    Status:
                    <span class="status">
                        <?php echo htmlspecialchars($order["status"]); ?>
                    </span>
                </p>

                <p>
                    Date:
                    <?php echo $order["created_at"]; ?>
                </p>

            </div>

        <?php endwhile; ?>

    <?php endif; ?>

    <br>

    <a href="index.php">← Continue Shopping</a>

</div>

</body>

</html>