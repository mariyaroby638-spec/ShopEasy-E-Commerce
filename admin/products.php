<?php

session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

/* Delete product */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: products.php");
    exit;
}

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Products - ShopEasy</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
        }

        header {
            background: #222;
            color: white;
            padding: 20px 50px;
        }

        header h1 {
            color: #00bcd4;
        }

        .container {
            padding: 40px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn {
            background: #00bcd4;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #222;
            color: white;
        }

        .edit {
            background: #2196f3;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        .delete {
            background: #f44336;
            color: white;
            padding: 7px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

    </style>

</head>

<body>

<header>

    <h1>ShopEasy - Manage Products</h1>

</header>

<div class="container">

    <div class="top">

        <h2>Products</h2>

        <a class="btn" href="add_product.php">
            + Add Product
        </a>

    </div>

    <table>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>

        <?php while ($product = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?php echo $product["id"]; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($product["name"]); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($product["description"]); ?>
            </td>

            <td>
                ₹<?php echo number_format($product["price"], 2); ?>
            </td>

            <td>
                <?php echo $product["stock"]; ?>
            </td>

            <td>

                <a
                    class="edit"
                    href="edit_product.php?id=<?php echo $product["id"]; ?>"
                >
                    Edit
                </a>

                <a
                    class="delete"
                    href="products.php?delete=<?php echo $product["id"]; ?>"
                    onclick="return confirm('Are you sure you want to delete this product?');"
                >
                    Delete
                </a>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

    <br>

    <a href="dashboard.php">
        ← Back to Dashboard
    </a>

</div>

</body>

</html>