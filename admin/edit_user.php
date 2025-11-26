<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

if (!isset($_GET["id"])) {
    header("Location: users.php");
    exit;
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: users.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $role = trim($_POST["role"]);

    if (!$username || !$role) {
        $error = "All fields are required";
    } else {
        $update = $pdo->prepare("UPDATE users SET username=?, role=? WHERE id=?");
        if ($update->execute([$username, $role, $id])) {
            $success = "User updated!";
        } else {
            $error = "Update failed";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>

<style>
    body { background:#f3f4f6; font-family:Arial; }
    .container {
        max-width:500px; margin:60px auto;
        background:#fff; padding:25px;
        border-radius:10px; text-align:center;
        box-shadow:0 3px 8px rgba(0,0,0,0.1);
    }
    input, select {
        width:100%; padding:10px;
        margin-bottom:10px;
        border:1px solid #ccc; border-radius:6px;
    }
    button {
        width:100%; padding:10px; border:none;
        border-radius:6px; margin-top:10px;
        cursor:pointer; font-weight:500; color:#fff;
    }
    .primary { background:#2563eb; }
    .gray { background:#6b7280; }
    .error { color:red; }
    .success { color:green; }
</style>
</head>

<body>
<div class="container">
    <h2>Edit User</h2>

    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <input type="text" name="username" value="<?= htmlspecialchars($user["username"]) ?>" required>

        <select name="role" required>
            <option value="user" <?= $user["role"]=="user"?"selected":"" ?>>User</option>
            <option value="admin" <?= $user["role"]=="admin"?"selected":"" ?>>Admin</option>
        </select>

        <button type="submit" class="primary">Save Changes</button>
        <button class="gray" type="button" onclick="location.href='users.php'">Back</button>
    </form>
</div>
</body>
</html>
