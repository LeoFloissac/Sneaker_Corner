<!-- login.php -->
<?php
// Messages are set by auth_handler.php which is included in header.php
// Variables $loginMessage and $loginMessageType are available from auth_handler.php
?>

<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeLogin">&times;</span>
        <h2>Login</h2>
        
        <?php if (!empty($loginMessage)): ?>
            <div class="message <?= $loginMessageType ?>"><?= htmlspecialchars($loginMessage) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="form_type" value="login">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <a href="#" class="forgot-link" id="openForgotPassword">Forgot password?</a>
            <button type="submit" class="loginBtn">Login</button>
        </form>
    </div>
</div>
