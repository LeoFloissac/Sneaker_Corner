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

<div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeForgot">&times;</span>
        <h2>Forgot Password</h2>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <p style="color: #707072; font-size: 0.9rem; margin-bottom: 1rem;">Enter your email address and we'll send you a link to reset your password.</p>
        
        <form method="POST">
            <input type="hidden" name="form_type" value="forgot_password">
            <label for="forgot_email">Email</label>
            <input type="email" id="forgot_email" name="email" placeholder="your@email.com" required>
            <button type="submit" class="forgotBtn">Reset Password</button>
        </form>
        
        <div class="modal-footer">
            <a href="#" id="backToLogin">Back to Login</a>
        </div>
    </div>
</div>
