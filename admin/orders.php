<?php

session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

/* Update order status */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id = intval($_POST["order_id"]);
    $status = $_POST["status"];

    $allowed = [
        "Pending",
        "Processing",
        "Shipped",
        "Delivered",
        "Cancelled"
    ];

    if (in_array($status, $allowed)) {

        $stmt = $conn->prepare(
            "UPDATE orders SET status = ? WHERE id = ?"
        );

        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }

    header("Location: orders.php");
    exit;
}

$result = $conn->query(
    "SELECT orders.*, users.name, users.email
     FROM orders
     JOIN users ON orders.user_id = users.id
     ORDER BY orders.id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Orders - ShopEasy</title>

    <style>

        body {
            font-family: Arial;
            background: #f5f5f5;
            margin: 0;
        }

        .container {
            width: 95%;
            margin: 40px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #222;
            color: white;
        }

        select {
            padding: 7px;
        }

        button {
            padding: 7px 12px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 4px;
        }

        a {
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Manage Orders</h1>

    <table>

        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Update</th>
        </tr>

        <?php while ($order = $result->fetch_assoc()): ?>

        <tr>

            <td>
                #<?php echo $order["id"]; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($order["name"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($order["email"]); ?>
            </td>

            <td>
                ₹<?php echo number_format($order["total_amount"], 2); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($order["status"]); ?>
            </td>

            <td>
                <?php echo $order["created_at"]; ?>
            </td>

            <td>

                <form method="POST">

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?php echo $order["id"]; ?>"
                    >

                    <select name="status">

                        <option value="Pending"
                            <?php if ($order["status"] == "Pending") echo "selected"; ?>>
                            Pending
                        </option>

                        <option value="Processing"
                            <?php if ($order["status"] == "Processing") echo "selected"; ?>>
                            Processing
                        </option>

                        <option value="Shipped"
                            <?php if ($order["status"] == "Shipped") echo "selected"; ?>>
                            Shipped
                        </option>

                        <option value="Delivered"
                            <?php if ($order["status"] == "Delivered") echo "selected"; ?>>
                            Delivered
                        </option>

                        <option value="Cancelled"
                            <?php if ($order["status"] == "Cancelled") echo "selected"; ?>>
                            Cancelled
                        </option>

                    </select>

                    <button type="submit">
                        Update
                    </button>

                </form>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

    <br>

    <a href="dashboard.php">← Back to Dashboard</a>

</div>

</body>

</html>