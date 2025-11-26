<?php
session_start();

// Must be logged in as Admin
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../config/db.php";

// User ID to delete must be provided
if (!isset($_GET["id"])) {
    header("Location: users.php");
    exit;
}

$delete_id = intval($_GET["id"]);

// Prevent admin from deleting themselves
if ($delete_id === intval($_SESSION["user_id"])) {
    header("Location: users.php?error=cannot_delete_self");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$delete_id]);

header("Location: users.php?success=user_deleted");
exit;
?>
