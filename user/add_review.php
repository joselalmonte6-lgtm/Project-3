<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

if (!isset($_GET["game_id"])) {
    header("Location: view_games.php");
    exit;
}

$game_id = intval($_GET["game_id"]);
$user_id = intval($_SESSION["user_id"]);

$error = "";
$success = "";

// Fetch game title
$game = $pdo->query("SELECT title FROM games WHERE id=$game_id")
           ->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $rating = intval($_POST["rating"]);
    $comment = trim($_POST["comment"]);

    if (!$rating || !$comment) {
        $error = "Please fill everything.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, game_id, rating, comment)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $game_id, $rating, $comment]);
        $success = "Review submitted!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Review</title>

<style>
    body { background:#f3f4f6; font-family:Arial; }
    .container {
        max-width:550px; margin:60px auto;
        background:#fff; padding:25px;
        border-radius:10px; text-align:center;
        box-shadow:0 3px 8px rgba(0,0,0,0.1);
    }
    select, textarea {
        width:100%; padding:10px; margin:10px 0;
        border:1px solid #ccc; border-radius:6px;
    }
    textarea { height:120px; resize:none; }
    button {
        width:100%; padding:10px;
        border:none; border-radius:6px;
        cursor:pointer; font-weight:500;
        color:white; margin-top:10px;
    }
    .primary { background:#2563eb; }
    .gray { background:#6b7280; }
    .error { color:red; }
    .success { color:green; }
</style>
</head>

<body>
<div class="container">

    <h2>Review: <?= htmlspecialchars($game["title"]) ?></h2>

    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>

    <form method="POST">
        <select name="rating" required>
            <option value="">Rating (1–10)</option>
            <?php for ($i=1; $i<=10; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <textarea name="comment" placeholder="Write your review..." required></textarea>

        <button class="primary" type="submit">Submit Review</button>
        <button class="gray" type="button" onclick="location.href='view_games.php'">Back</button>
    </form>
</div>
</body>
</html>
