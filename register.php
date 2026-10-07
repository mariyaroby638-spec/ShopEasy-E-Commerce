<?php

include "config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "Email already registered.";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, 'user')"
        );

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $hashed_password
        );

        if ($stmt->execute()) {

            $message = "Registration successful! You can now login.";

        } else {

            $message = "Registration failed.";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register - ShopEasy</title>

    <style>

        body {
            font-family: Arial;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .box {
            width: 350px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            color: green;
        }

        .links {
            text-align: center;
            margin-top: 15px;
        }

    </style>

</head>

<body>

<div class="box">

    <h2>Create Account</h2>

    <?php if ($message != ""): ?>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter your name"
            required
        >

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter your email"
            required
        >

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Create a password"
            required
        >

        <button type="submit">
            Register
        </button>

    </form>

    <div class="links">

        Already have an account?

        <a href="login.php">Login</a>

    </div>

</div>

</body>

</html>
