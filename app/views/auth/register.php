<!-- register.php -->
<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$authController = new AuthController($conn);
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
    $result = $authController->register(
        $_POST['name'] ?? '',
        $_POST['email'] ?? '',
        $_POST['password'] ?? '',
        $_POST['confirm_password'] ?? ''
    );
    
    $message = $result['message'];
    $messageType = $result['success'] ? 'success' : 'error';
    
    if ($result['success']) {
        header('Location: /sneaker_corner/public/index.php?registered=1');
        exit;
    }
}
?>

<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegister">&times;</span>
        <h1>Register</h1>
        
        <?php if ($message): ?>
            <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="form_type" value="register">
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" minlength="6" required><br>
            <label for="confirm_password">Confirm Password</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <button type="submit" class="registerBtn">Register</button>
        </form>
    </div>
</div>

