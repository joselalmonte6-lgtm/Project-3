<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Users</title>

<style>
    body { background:#f3f4f6; font-family:Arial; }
    .container {
        max-width:900px; margin:50px auto;
        background:#fff; padding:25px;
        border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,0.1);
    }
    h2 { text-align:center; margin-bottom:20px; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:12px; text-align:center; border-bottom:1px solid #ddd; }
    th { background:#2563eb; color:#fff; }
    .btn {
        padding:8px 12px; border-radius:6px; text-decoration:none; color:#fff;
        font-size:14px; margin:0 3px;
    }
    .btn-edit { background:#059669; }
    .btn-delete { background:#dc2626; }
    .btn-back { background:#2563eb; display:inline-block; margin-bottom:10px; }
</style>
</head>

<body>
<div class="container">

    <a href="dashboard.php" class="btn btn-back">Back</a>

    <h2>Manage Users</h2>

    <table>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>

        <?php foreach($users as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u["username"]) ?></td>
            <td><?= htmlspecialchars($u["role"]) ?></td>
            <td>
                <a href="edit_user.php?id=<?= $u["id"] ?>" class="btn btn-edit">Edit</a>

                <?php if ($u["id"] != $_SESSION["user_id"]): ?>
                    <a href="delete_user.php?id=<?= $u["id"] ?>"
                        class="btn btn-delete"
                        onclick="return confirm('Remove this user?')">
                        Delete
                    </a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>
</body>
</html>
