<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
    body {
        background-color: #f3f4f6;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 800px;
        margin: 60px auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        text-align: center;
    }
    h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .btn {
        display: inline-block;
        padding: 10px 16px;
        text-decoration: none;
        border-radius: 6px;
        color: #fff;
        font-weight: 500;
        margin: 6px;
    }
    .btn-primary { background-color: #2563eb; }
    .btn-gray { background-color: #6b7280; }
    .btn-logout { background-color: #dc2626; }
</style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?></h2>

    <a href="games.php" class="btn btn-primary">Manage Games</a>
    <a href="users.php" class="btn btn-gray">Manage Users</a>

    <br><br>
    <a href="../logout.php" class="btn btn-logout">Logout</a>
</div>

</body>
</html>
