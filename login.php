<?php
session_start();
require_once 'config/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {
                header("Location: admin/dashboard.php");
                exit;
            } else {
                header("Location: user/dashboard.php");
                exit;
            }
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Please fill out all fields";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | GameHub</title>

    <style>
        body { background-color: #f3f4f6; font-family: Arial, sans-serif; }
        .container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; font-weight: 600; margin-bottom: 20px; }
        input {
            width: 100%; padding: 10px;
            border-radius: 6px; border: 1px solid #ccc;
            margin-bottom: 12px; font-size: 15px;
        }
        button {
            width: 100%; padding: 10px;
            border: none; border-radius: 6px;
            margin-top: 8px; cursor: pointer;
            font-weight: 500; color: #fff;
        }
        .primary { background-color: #2563eb; }
        .secondary { background-color: #6b7280; }
        .error {
            text-align: center; color: red;
            margin-bottom: 10px; font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <h2>User Login</h2>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="primary">Login</button>

            <button type="button" class="secondary"
                onclick="location.href='register.php'">
                Create Account
            </button>
        </form>
    </div>
</body>
</html>
