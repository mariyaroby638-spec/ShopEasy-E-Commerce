<?php
include "config/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopEasy - E-Commerce Store</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        header {
            background: #222;
            color: white;
            padding: 18px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            color: #00bcd4;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
        }

        .hero {
            background: #00bcd4;
            color: white;
            text-align: center;
            padding: 70px 20px;
        }

        .hero h2 {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .hero p {
            font-size: 18px;
        }

        .products {
            padding: 40px 50px;
        }

        .products h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .product-card h3 {
            margin-bottom: 10px;
        }

        .price {
            color: #00a000;
            font-size: 20px;
            font-weight: bold;
            margin: 10px;
        }

        .btn {
            display: inline-block;
            background: #222;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }

        footer {
            background: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>

<body>

<header>
    <h1>ShopEasy</h1>

   <nav>
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
    <a href="cart.php">Cart</a>
</nav>
   
</header>

<section class="hero">
    <h2>Welcome to ShopEasy</h2>
    <p>Your simple and secure online shopping destination.</p>
</section>

<section class="products">

    <h2>Our Products</h2>

    <div class="product-grid">

        <?php

        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");

        if ($result && $result->num_rows > 0) {

            while ($product = $result->fetch_assoc()) {

                echo '<div class="product-card">';

                echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';

                echo '<p>' . htmlspecialchars($product['description']) . '</p>';

                echo '<div class="price">₹' . number_format($product['price'], 2) . '</div>';

                echo '<a class="btn" href="cart.php?add=' . $product['id'] . '">Add to Cart</a>';
                echo '</div>';
            }

        } else {

            echo "<p>No products available yet.</p>";

        }

        ?>

    </div>

</section>

<footer>
    <p>© 2026 ShopEasy | Thiranex Internship Project</p>
</footer>

</body>
</html>