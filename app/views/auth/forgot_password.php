<!-- forgot_password.php -->
<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$authController = new AuthController($conn);
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'forgot_password') {
    $result = $authController->forgotPassword($_POST['email'] ?? '');
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'error';
}
?>

<div id="forgotPasswordModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeForgotPassword">&times;</span>
        <h1>Forgot Password</h1>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="form_type" value="forgot_password">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required><br>
            <button type="submit" class="forgotBtn">Reset Password</button>
        </form>
        
        <div id="backToLogin">
            <button id="openLogin">Back to Login</button>
        </div>
    </div>
</div>
