<!-- forgot_password.php -->
<?php
require __DIR__ . '/../../config/config.php'; 
$message = '';
$forgot_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'forgot') {
    $femail = trim($_POST['email']);

    // Prépare et exécute la requête pour récupérer l'utilisateur
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $femail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_name);
        $stmt->fetch();

        // Génère un token et stocke-le (table password_resets attendue)
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1h

        $stmt->close();
        $stmt2 = $conn->prepare("REPLACE INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt2->bind_param("iss", $user_id, $token, $expires);
        $stmt2->execute();
        $stmt2->close();

        // Pour dev on affiche le lien ; en production, envoyer un email réel
        $resetLink = "http://{$_SERVER['HTTP_HOST']}/my_website/app/auth/reset_password.php?token=$token";
        $forgot_message = "Si l'email existe, un lien de réinitialisation a été envoyé. (Lien pour dev : $resetLink)";
    } else {
        $forgot_message = "Si l'email existe, un lien de réinitialisation a été envoyé.";
        $stmt->close();
    }
}

?>

<div id="forgotModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeForgot">&times;</span>
        <h1>Forgot Password</h1>
        <form method="POST">
            <input type="hidden" name="form_type" value="forgot">
            <label for="email">Email</label><br>
            <input type="text" id="email" name="email" required><br>
            <button type="submit" class="forgotBtn">Send Email</button>
        </form>   
    </div>
</div>
