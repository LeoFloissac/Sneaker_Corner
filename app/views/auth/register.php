<!-- register.php -->
<?php
// Messages are set by auth_handler.php which is included in header.php
// Variables $registerMessage and $registerMessageType are available from auth_handler.php
?>

<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegister">&times;</span>
        <h2>Register</h2>
        
        <?php if (!empty($registerMessage)): ?>
            <div class="message <?= $registerMessageType ?>"><?= htmlspecialchars($registerMessage) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="form_type" value="register">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="your@email.com" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Min 6 characters" minlength="6" required>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
            <button type="submit" class="registerBtn">Register</button>
        </form>
    </div>
</div>

