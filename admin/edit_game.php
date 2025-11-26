<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

if (!isset($_GET["id"])) {
    header("Location: games.php");
    exit;
}

$id = $_GET["id"];
$stmt = $pdo->prepare("SELECT * FROM games WHERE id=?");
$stmt->execute([$id]);
$game = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$game) {
    header("Location: games.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $genre = trim($_POST["genre"]);
    $release_date = trim($_POST["release_date"]);

    if (!$title || !$genre || !$release_date) {
        $error = "All fields are required";
    } else {
        $update = $pdo->prepare("UPDATE games SET title=?, genre=?, release_date=? WHERE id=?");
        if ($update->execute([$title, $genre, $release_date, $id])) {
            $success = "Game updated successfully!";
        } else {
            $error = "Failed to update game";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Game</title>

<style>
    body { background:#f3f4f6; font-family:Arial; }
    .container {
        max-width:500px; margin:60px auto;
        background:#fff; padding:25px;
        border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,.1);
        text-align:center;
    }
    input {
        width:100%; padding:10px; margin-bottom:10px;
        border:1px solid #ccc; border-radius:6px;
    }
    button {
        width:100%; padding:10px; border:none;
        margin-top:10px; border-radius:6px;
        cursor:pointer; font-weight:500;
        color:#fff;
    }
    .primary { background:#2563eb; }
    .gray { background:#6b7280; }
    .error { color:red; }
    .success { color:green; }
</style>
</head>

<body>
<div class="container">
    <h2>Edit Game</h2>

    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($game["title"]) ?>" required>
        <input type="text" name="genre" value="<?= htmlspecialchars($game["genre"]) ?>" required>
        <input type="date" name="release_date" value="<?= htmlspecialchars($game["release_date"]) ?>" required>

        <button class="primary" type="submit">Save Changes</button>
        <button class="gray" type="button" onclick="location.href='games.php'">Back</button>
    </form>
</div>
</body>
</html>
