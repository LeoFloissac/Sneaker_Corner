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
     * User Login
     */
    public function login($email, $password) {
        $result = ['success' => false, 'message' => ''];
        
        if (empty($email) || empty($password)) {
            $result['message'] = "Please fill in all fields";
            return $result;
        }
        
        $user = $this->userModel->verifyPassword($email, $password);
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            session_regenerate_id(true);
            
            $result['success'] = true;
            $result['message'] = "Logged in as " . $user['name'];
            $result['user'] = $user;
        } else {
            // Check if email exists
            if ($this->userModel->emailExists($email)) {
                $result['message'] = "Incorrect password";
            } else {
                $result['message'] = "Email not found";
            }
        }
        
        return $result;
    }
    
    /**
     * User Registration
     */
    public function register($name, $email, $password, $confirmPassword) {
        $result = ['success' => false, 'message' => ''];
        
        // Validation
        if (empty($name) || empty($email) || empty($password)) {
            $result['message'] = "Please fill in all fields";
            return $result;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = "Invalid email address";
            return $result;
        }
        
        if (strlen($password) < 6) {
            $result['message'] = "Password must be at least 6 characters";
            return $result;
        }
        
        if ($password !== $confirmPassword) {
            $result['message'] = "Passwords do not match";
            return $result;
        }
        
        if ($this->userModel->emailExists($email)) {
            $result['message'] = "This email is already in use";
            return $result;
        }
        
        // Create user
        $userId = $this->userModel->create($name, $email, $password);
        
        if ($userId) {
            $result['success'] = true;
            $result['message'] = "Registration successful! You can now log in.";
            $result['user_id'] = $userId;
        } else {
            $result['message'] = "An error occurred during registration";
        }
        
        return $result;
    }
    
    /**
     * Logout
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
        
        return ['success' => true, 'message' => 'Successfully logged out'];
    }
    
    /**
     * Forgot Password
     * Note: Email sending is not implemented
     */
    public function forgotPassword($email) {
        $result = ['success' => false, 'message' => ''];
        
        if (empty($email)) {
            $result['message'] = "Please enter your email";
            return $result;
        }
        
        // Note: Password reset via email is not implemented
        // This just simulates success for demo purposes
        $result['success'] = true;
        $result['message'] = "If this email exists, you would receive a reset link.";
        
        return $result;
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get current logged in user
     */
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $this->userModel->findById($_SESSION['user_id']);
        }
        return null;
    }
}