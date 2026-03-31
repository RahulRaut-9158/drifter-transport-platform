<?php
// Require login — redirect if not logged in
// Usage: require_once ROOT.'/includes/auth.php'; requireLogin();
if (!defined('ROOT')) define('ROOT', dirname(__DIR__));

function requireLogin($redirect = null) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        $back = $redirect ?? $_SERVER['REQUEST_URI'];
        header('Location: ' . ROOT_URL . '/front/login.php?redirect=' . urlencode($back));
        exit;
    }
}

// Root URL helper — works on localhost/Drifter
if (!defined('ROOT_URL')) {
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host  = $_SERVER['HTTP_HOST'];
    // detect base path (e.g. /Drifter)
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $parts  = explode('/', trim($script, '/'));
    $base   = '/' . $parts[0]; // e.g. /Drifter
    define('ROOT_URL', $proto . '://' . $host . $base);
}
?>
