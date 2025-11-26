<?php
// setup.php - run once to create an admin account
session_start();
require_once __DIR__ . '/config/db.php';

$created = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Username and password are required.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $message = 'Username already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
            $stmt->execute([$username, $hash]);
            $created = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Setup - Create Admin</title>

<style>
    body { background:#f3f4f6; font-family:Arial, sans-serif; }
    .container {
        max-width:500px;
        margin:80px auto;
        background:#fff;
        padding:25px;
        border-radius:10px;
        box-shadow:0 3px 8px rgba(0,0,0,0.1);
        text-align:center;
    }
    input {
        width:100%;
        padding:10px;
        margin-bottom:12px;
        border-radius:6px;
        border:1px solid #ccc;
    }
    button {
        width:100%;
        padding:10px;
        background:#2563eb;
        color:#fff;
        border:none;
        border-radius:6px;
        cursor:pointer;
        font-weight:500;
    }
    .error { color:red; margin-bottom:10px; }
    .success { color:green; margin-bottom:10px; }
</style>

</head>
<body>
<div class="container">

    <h2>Admin Setup</h2>

    <?php if ($created): ?>
        <p class="success">Admin account created successfully.</p>
        <button onclick="window.location.href='auth/login.php'">Go to Login</button>
    <?php else: ?>
        <?php if ($message): ?>
            <p class="error"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="New Admin Username" required>
            <input type="password" name="password" placeholder="New Admin Password" required>
            <button type="submit">Create Admin</button>
        </form>
    <?php endif; ?>

</div>
</body>
</html>
