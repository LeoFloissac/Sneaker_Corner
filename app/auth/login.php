<!-- login.php -->
<?php
require __DIR__ . '/../../config/config.php'; 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prépare et exécute la requête pour récupérer l'utilisateur
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashedPassword);
        $stmt->fetch();

        // Vérifie le mot de passe
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $message = "✅ Connecté en tant que $name";
            session_regenerate_id(true);
            header('Location: /my_website/public/index.php');
            exit;
        } else {
            $message = "❌ Mot de passe incorrect";
        }
    } else {
        $message = "❌ Email non trouvé";
    }

    $stmt->close();
}
$conn->close();
?>


<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeLogin">&times;</span>
        <h1>Login</h1>
        <form method="POST">
            <input type="hidden" name="form_type" value="login">
            <label for="email">Email</label><br>
            <input type="text" id="email" name="email" required><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required><br>
            <button type="submit" class="loginBtn">Login</button>
        </form>    
    </div>
</div>
