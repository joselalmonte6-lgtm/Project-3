<?php
// admin/delete_game.php
session_start();
require_once __DIR__ . '/../config/db.php';
if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: /gamehub/login.php');
    exit;
}
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: games.php');
exit;
