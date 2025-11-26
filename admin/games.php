<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

$games = $pdo->query("SELECT * FROM games ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Games</title>

<style>
    body { background: #f3f4f6; font-family: Arial, sans-serif; }
    .container {
        max-width: 900px; margin: 50px auto;
        background: #fff; padding: 25px;
        border-radius: 10px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    .top-bar { display:flex; justify-content:space-between; }
    h2 { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; }
    th, td {
        padding: 12px; text-align:center;
        border-bottom:1px solid #ddd;
    }
    th { background:#2563eb; color:white; }
    .btn {
        padding:8px 12px; border-radius:6px;
        text-decoration:none; color:white;
        font-size:14px; margin:0 3px;
    }
    .btn-primary { background:#2563eb; }
    .btn-edit { background:#059669; }
    .btn-delete { background:#dc2626; }
</style>
</head>

<body>
<div class="container">

    <div class="top-bar">
        <a href="dashboard.php" class="btn btn-primary">Back</a>
        <a href="add_game.php" class="btn btn-primary">Add Game</a>
    </div>

    <h2>Manage Games</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>Genre</th>
            <th>Release Date</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($games as $g): ?>
        <tr>
            <td><?= htmlspecialchars($g["title"]) ?></td>
            <td><?= htmlspecialchars($g["genre"]) ?></td>
            <td><?= htmlspecialchars($g["release_date"]) ?></td>
            <td>
                <a href="edit_game.php?id=<?= $g["id"] ?>" class="btn btn-edit">Edit</a>
                <a href="delete_game.php?id=<?= $g["id"] ?>" class="btn btn-delete"
                   onclick="return confirm('Delete this game?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>
</body>
</html>
