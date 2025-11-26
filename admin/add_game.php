<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $genre = trim($_POST["genre"]);
    $release_date = trim($_POST["release_date"]);

    if (!$title || !$genre || !$release_date) {
        $error = "All fields are required";
    } else {
        $stmt = $pdo->prepare("INSERT INTO games (title, genre, release_date) VALUES (?, ?, ?)");
        if ($stmt->execute([$title, $genre, $release_date])) {
            $success = "Game added successfully!";
        } else {
            $error = "Failed to add game";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Game</title>

<style>
    body { background:#f3f4f6; font-family:Arial, sans-serif; }
    .container {
        max-width:500px;
        margin:60px auto;
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
        border:1px solid #ccc;
        border-radius:6px;
        font-size:14px;
    }
    button {
        width:100%;
        padding:10px;
        margin-top:10px;
        border:none;
        border-radius:6px;
        color:#fff;
        cursor:pointer;
        font-weight:500;
    }
    .primary { background:#2563eb; }
    .gray { background:#6b7280; }
    .error { color:red; margin-bottom:10px; }
    .success { color:green; margin-bottom:10px; }
</style>

</head>
<body>

<div class="container">
    <h2>Add Game</h2>

    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Game Title" required>
        <input type="text" name="genre" placeholder="Genre" required>
        <input type="date" name="release_date" required>

        <button class="primary" type="submit">Save Game</button>
        <button class="gray" type="button" onclick="location.href='games.php'">Back</button>
    </form>
</div>

</body>
</html>
