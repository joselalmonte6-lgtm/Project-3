<?php
session_start();
require_once 'config/db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $confirm = trim($_POST["confirm_password"]);

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "All fields are required";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = "Username already taken";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, role)
                                   VALUES (?, ?, 'user')");
            if ($stmt->execute([$username, $hash])) {
                // Redirect to login page after success
                header("Location: login.php?msg=registered");
                exit;
            } else {
                $error = "Something went wrong";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | GameHub</title>

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
        .error { text-align: center; color: red; margin-bottom: 10px; font-weight: 500; }
        .success { text-align: center; color: green; margin-bottom: 10px; font-weight: 500; }
    </style>
</head>

<body>
    <div class="container">

        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <h2>Create Account</h2>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Choose Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>

            <button type="submit" class="primary">Sign Up</button>

            <button type="button" class="secondary"
                onclick="location.href='login.php'">
                Back to Login
            </button>
        </form>
    </div>
</body>
</html>
