<?php
$DB_HOST = '127.0.0.1';
$DB_PORT = '3306'; 
$DB_NAME = 'project3_db';
$DB_USER = 'root';
$DB_PASS = ''; // blank default password

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    exit("Database connection failed: " . $e->getMessage());
}
