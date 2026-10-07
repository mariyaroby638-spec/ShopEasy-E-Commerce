<?php

session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $stmt = $conn->prepare(
        "UPDATE products
         SET name = ?, description = ?, price = ?, stock = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssdii",
        $name,
        $description,
        $price,
        $stock,
        $id
    );

    $stmt->execute();

    header("Location: products.php");
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>

    <style>
        body {
            font-family: Arial;
            background: #f5f5f5;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00bcd4;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Edit Product</h2>

    <form method="POST">

        <label>Name</label>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($product['name']); ?>"
            required
        >

        <label>Description</label>

        <textarea name="description"><?php
            echo htmlspecialchars($product['description']);
        ?></textarea>

        <label>Price</label>

        <input
            type="number"
            step="0.01"
            name="price"
            value="<?php echo $product['price']; ?>"
            required
        >

        <label>Stock</label>

        <input
            type="number"
            name="stock"
            value="<?php echo $product['stock']; ?>"
            required
        >

        <button type="submit">
            Update Product
        </button>

    </form>

    <br>

    <a href="products.php">← Back to Products</a>

</div>

</body>

</html>