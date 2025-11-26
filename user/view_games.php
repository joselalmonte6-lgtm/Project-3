<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "user") {
    header("Location: ../login.php");
    exit;
}
require_once "../config/db.php";

$user_id = $_SESSION["user_id"];

// Fetch games
$games = $pdo->query("SELECT * FROM games ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch ALL reviews joined with username + game_id
$reviewsQuery = $pdo->query("
    SELECT r.*, u.username 
    FROM reviews r 
    JOIN users u ON r.user_id = u.id
    ORDER BY r.id DESC
");
$allReviews = $reviewsQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Games & Reviews</title>

<style>
    body { background:#f3f4f6; font-family:Arial, sans-serif; }
    .container {
        max-width:900px; margin:50px auto;
        background:#fff; padding:25px;
        border-radius:10px; box-shadow:0 3px 8px rgba(0,0,0,0.1);
    }
    h2 { text-align:center; margin-bottom:20px; }
    .game-card {
        padding:20px; margin-bottom:25px;
        border-bottom:1px solid #ddd;
    }
    .btn {
        padding:8px 12px; border-radius:6px;
        text-decoration:none; font-size:14px;
        color:#fff; display:inline-block; margin-top:6px;
    }
    .btn-review { background:#2563eb; }
    .btn-edit { background:#059669; }
    .btn-delete { background:#dc2626; }
    .review-box {
        background:#f9fafb; margin-top:12px;
        padding:12px; border-radius:6px;
        text-align:left;
    }
    .review-meta { font-size:13px; font-weight:bold; }
    .review-comment { margin-top:4px; }
    .btn-back {
        background:#2563eb;
        margin-bottom:15px; display:inline-block;
    }
</style>
</head>

<body>
<div class="container">

    <a href="dashboard.php" class="btn btn-back">Back</a>
    <h2>Games & Reviews</h2>

    <?php foreach ($games as $g): ?>
    <div class="game-card">
        <h3><?= htmlspecialchars($g["title"]) ?></h3>
        <p>Genre: <?= htmlspecialchars($g["genre"]) ?></p>
        <p>Release Date: <?= htmlspecialchars($g["release_date"]) ?></p>

        <a href="add_review.php?game_id=<?= $g["id"] ?>" class="btn btn-review">Review</a>

        <?php
        $game_id = $g["id"];
        $gameReviews = array_filter($allReviews, fn($r) => $r["game_id"] == $game_id);
        ?>

        <?php if (count($gameReviews) > 0): ?>
            <?php foreach ($gameReviews as $r): ?>
                <div class="review-box">
                    <div class="review-meta">
                        Rating: <?= $r["rating"] ?>/20 — by <?= htmlspecialchars($r["username"]) ?>
                    </div>
                    <div class="review-comment">
                        <?= htmlspecialchars($r["comment"]) ?>
                    </div>

                    <?php if ($r["user_id"] == $user_id): ?>
                        <a href="edit_review.php?id=<?= $r['id'] ?>"
                           class="btn btn-edit">Edit</a>
                        <a href="delete_review.php?id=<?= $r['id'] ?>"
                           class="btn btn-delete"
                           onclick="return confirm('Delete your review?')">Delete</a>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <p style="color:#666;margin-top:10px;">No reviews yet.</p>
        <?php endif ?>
    </div>
    <?php endforeach ?>

</div>
</body>
</html>
