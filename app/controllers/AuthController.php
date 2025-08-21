<?php
/**
 * Controlador de autenticación
 */

class AuthController extends BaseController {
    
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = 'Todos los campos son obligatorios';
            } else {
                $user = $this->db->fetch(
                    "SELECT * FROM usuarios WHERE email = ? AND activo = 1",
                    [$email]
                );
                
                if ($user && password_verify($password, $user['password'])) {
                    // Login exitoso
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['rol'];
                    $_SESSION['last_activity'] = time();
                    
                    $this->logActivity('login', 'Usuario inició sesión');
                    
                    $this->redirect('dashboard');
                } else {
                    $error = 'Credenciales inválidas';
                }
            }
        }
        
        $this->view('auth/login', ['error' => $error]);
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity('logout', 'Usuario cerró sesión');
        }
        
        session_destroy();
        $this->redirect('login');
    }
    
    public function forgotPassword() {
        $message = '';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                $error = 'El email es obligatorio';
            } else {
                $user = $this->db->fetch(
                    "SELECT * FROM usuarios WHERE email = ? AND activo = 1",
                    [$email]
                );
                
                if ($user) {
                    // Generar token de recuperación
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    
                    // Guardar token en base de datos (necesitaremos crear tabla tokens)
                    // Por ahora solo simulamos el envío
                    $message = 'Se ha enviado un enlace de recuperación a tu email';
                } else {
                    $error = 'Email no encontrado';
                }
            }
        }
        
        $this->view('auth/forgot-password', [
            'message' => $message,
            'error' => $error
        ]);
    }
}
?>