<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../login.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Admin Dashboard - ShopEasy</title>

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
            display: flex;
            justify-content: space-between;
        }

        header h1 {
            color: #00bcd4;
        }

        .container {
            padding: 40px;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #00bcd4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout {
            color: white;
            text-decoration: none;
        }

    </style>

</head>

<body>

<header>

    <h1>ShopEasy Admin</h1>

    <a class="logout" href="../logout.php">
        Logout
    </a>

</header>

<div class="container">

    <div class="welcome">

        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>!</h2>

        <p>You are logged in as an administrator.</p>

    </div>

    <div class="cards">

        <div class="card">

            <h3>Products</h3>

            <p>Manage store products.</p>

            <a href="products.php">
                Manage Products
            </a>

        </div>

        <div class="card">

            <h3>Add Product</h3>

            <p>Add a new product.</p>

            <a href="add_product.php">
                Add Product
            </a>

        </div>

        <div class="card">

            <h3>Orders</h3>

            <p>Manage customer orders.</p>

            <a href="orders.php">
                Manage Orders
            </a>

        </div>

    </div>

</div>

</body>
</html>