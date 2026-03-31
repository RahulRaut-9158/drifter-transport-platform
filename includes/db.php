<?php
// Shared DB connections — include this file anywhere
// Usage: include ROOT.'/includes/db.php';

define('ROOT', dirname(__DIR__));

// --- db (transport, travel, users) ---
function db() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli('localhost', 'root', '', 'db');
        if ($conn->connect_error) die('DB Error: ' . $conn->connect_error);
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

// --- drifter_courier ---
function courierPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=localhost;dbname=drifter_courier;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

// --- moveeasy ---
function moversPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO('mysql:host=localhost;dbname=moveeasy;charset=utf8mb4', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}
?>
