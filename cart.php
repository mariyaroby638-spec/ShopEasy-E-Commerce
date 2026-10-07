<?php

session_start();
include "config/db.php";

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

/* Add product to cart */
if (isset($_GET["add"])) {

    $id = intval($_GET["add"]);

    if (isset($_SESSION["cart"][$id])) {
        $_SESSION["cart"][$id]++;
    } else {
        $_SESSION["cart"][$id] = 1;
    }

    header("Location: cart.php");
    exit;
}

/* Remove product */
if (isset($_GET["remove"])) {

    $id = intval($_GET["remove"]);

    unset($_SESSION["cart"][$id]);

    header("Location: cart.php");
    exit;
}

$total = 0;

?>

<!DOCTYPE html>
<html>

<head>

    <title>Cart - ShopEasy</title>

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

        .product {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
        }

        .remove {
            color: red;
            text-decoration: none;
        }

        .checkout {
            display: inline-block;
            background: #00bcd4;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Shopping Cart</h1>

    <?php if (empty($_SESSION["cart"])): ?>

        <p>Your cart is empty.</p>

        <a href="index.php">← Continue Shopping</a>

    <?php else: ?>

        <?php foreach ($_SESSION["cart"] as $id => $quantity): ?>

            <?php

            $stmt = $conn->prepare(
                "SELECT * FROM products WHERE id = ?"
            );

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $product = $stmt->get_result()->fetch_assoc();

            if (!$product) {
                continue;
            }

            $subtotal = $product["price"] * $quantity;
            $total += $subtotal;

            ?>

            <div class="product">

                <div>

                    <h3>
                        <?php echo htmlspecialchars($product["name"]); ?>
                    </h3>

                    <p>
                        Price: ₹<?php echo number_format($product["price"], 2); ?>
                    </p>

                    <p>
                        Quantity: <?php echo $quantity; ?>
                    </p>

                </div>

                <div>

                    <strong>
                        ₹<?php echo number_format($subtotal, 2); ?>
                    </strong>

                    <br><br>

                    <a
                        class="remove"
                        href="cart.php?remove=<?php echo $id; ?>"
                    >
                        Remove
                    </a>

                </div>

            </div>

        <?php endforeach; ?>

        <h2>
            Total: ₹<?php echo number_format($total, 2); ?>
        </h2>

        <a class="checkout" href="checkout.php">
            Proceed to Checkout
        </a>

    <?php endif; ?>

</div>

</body>

</html>