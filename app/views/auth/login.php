<!-- login.php -->
<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$authController = new AuthController($conn);
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
    $result = $authController->login($_POST['email'] ?? '', $_POST['password'] ?? '');
    
    if ($result['success']) {
        header('Location: /sneaker_corner/public/index.php');
        exit;
    } else {
        $message = $result['message'];
        $messageType = 'error';
    }
}
?>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeLogin">&times;</span>
        <h1>Login</h1>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="form_type" value="login">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>
            <button type="submit" class="loginBtn">Login</button>
        </form>
        
        <div id="forgottenPassword">
            <button id="openForgotPassword">Forgot Password</button>
        </div>
    </div>
</div>
