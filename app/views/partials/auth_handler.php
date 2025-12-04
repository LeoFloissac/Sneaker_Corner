<?php
/**
 * Authentication Handler
 * This file must be included BEFORE any HTML output to properly handle redirects
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$authController = new AuthController($conn);
$loginMessage = '';
$loginMessageType = '';
$registerMessage = '';
$registerMessageType = '';

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
    $result = $authController->login($_POST['email'] ?? '', $_POST['password'] ?? '');
    
    if ($result['success']) {
        header('Location: /sneaker_corner/');
        exit;
    } else {
        $loginMessage = $result['message'];
        $loginMessageType = 'error';
    }
}

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
    $result = $authController->register(
        $_POST['name'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? '',
        $_POST['confirm_password'] ?? ''
    );
    
    $registerMessage = $result['message'];
    $registerMessageType = $result['success'] ? 'success' : 'error';
    
    if ($result['success']) {
        header('Location: /sneaker_corner/?registered=1');
        exit;
    }
}
?>
