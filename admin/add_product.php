<?php

session_start();
include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    $stmt = $conn->prepare(
        "INSERT INTO products (name, description, price, stock)
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssdi",
        $name,
        $description,
        $price,
        $stock
    );

    if ($stmt->execute()) {
        $message = "Product added successfully!";
    } else {
        $message = "Error adding product.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Product - ShopEasy</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            color: green;
            margin-bottom: 15px;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Add New Product</h2>

    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Product Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter product name"
            required
        >

        <label>Description</label>

        <textarea
            name="description"
            placeholder="Enter product description"
        ></textarea>

        <label>Price</label>

        <input
            type="number"
            name="price"
            step="0.01"
            placeholder="Enter price"
            required
        >

        <label>Stock</label>

        <input
            type="number"
            name="stock"
            placeholder="Enter stock quantity"
            required
        >

        <button type="submit">
            Add Product
        </button>

    </form>

    <a class="back" href="dashboard.php">
        ← Back to Dashboard
    </a>

</div>

</body>

</html>