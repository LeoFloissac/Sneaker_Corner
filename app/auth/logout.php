<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /my_website/public/index.php');
    exit;
}

session_start();

// Clear session array
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to homepage (ajustez le chemin si nécessaire)
header('Location: /my_website/public/index.php');
exit;
?>