<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new User($conn);
    }
    
    /**
     * Connexion utilisateur
     */
    public function login($email, $password) {
        $result = ['success' => false, 'message' => ''];
        
        if (empty($email) || empty($password)) {
            $result['message'] = "Veuillez remplir tous les champs";
            return $result;
        }
        
        $user = $this->userModel->verifyPassword($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            session_regenerate_id(true);
            
            $result['success'] = true;
            $result['message'] = "Connecté en tant que " . $user['name'];
            $result['user'] = $user;
        } else {
            // Vérifier si l'email existe
            if ($this->userModel->emailExists($email)) {
                $result['message'] = "Mot de passe incorrect";
            } else {
                $result['message'] = "Email non trouvé";
            }
        }
        
        return $result;
    }
    
    /**
     * Inscription utilisateur
     */
    public function register($name, $email, $password, $confirmPassword) {
        $result = ['success' => false, 'message' => ''];
        
        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            $result['message'] = "Veuillez remplir tous les champs";
            return $result;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "Email invalide";
            return $result;
        }
        
        if (strlen($password) < 6) {
            $result['message'] = "Le mot de passe doit contenir au moins 6 caractères";
            return $result;
        }
        
        if ($password !== $confirmPassword) {
            $result['message'] = "Les mots de passe ne correspondent pas";
            return $result;
        }
        
        if ($this->userModel->emailExists($email)) {
            $result['message'] = "Cet email est déjà utilisé";
            return $result;
        }
        
        // Création
        $userId = $this->userModel->create($name, $email, $password);
        
        if ($userId) {
            $result['success'] = true;
            $result['message'] = "Inscription réussie ! Vous pouvez vous connecter.";
            $result['user_id'] = $userId;
        } else {
            $result['message'] = "Erreur lors de l'inscription";
        }
        
        return $result;
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        return ['success' => true, 'message' => 'Déconnexion réussie'];
    }
    
    /**
     * Mot de passe oublié
     */
    public function forgotPassword($email) {
        $result = ['success' => false, 'message' => ''];
        
        if (empty($email)) {
            $result['message'] = "Veuillez entrer votre email";
            return $result;
        }
        
        // Ici tu pourrais générer un token et envoyer un email
        // Pour l'instant on simule juste le succès
        $result['success'] = true;
        $result['message'] = "Si cet email existe, vous recevrez un lien de réinitialisation.";
        
        return $result;
    }
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Récupérer l'utilisateur connecté
     */
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $this->userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
}