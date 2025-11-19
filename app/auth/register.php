<!-- register.php -->
<?php
require __DIR__ . '/../../config/config.php'; 
 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'register') {
    $user = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $email, $pass);

    if ($stmt->execute()) {
        $message = "User Registered !";
    } else {
        $message = "Erreur : " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>


<div id="registerModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeRegister">&times;</span>
        <h1>Register</h1>
        <form method="POST">
            <input type="hidden" name="form_type" value="register">
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name"><br>
            <label for="email">Email</label><br>
            <input type="text" id="email" name="email"><br>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password"><br>
            <button type="submit" class="registerBtn">Register</button>
        </form>    
    </div>
</div>

