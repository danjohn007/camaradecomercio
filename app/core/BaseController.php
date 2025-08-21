<?php
/**
 * Controlador base del sistema
 */

class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        // Cargar vista específica
        $viewFile = "app/views/{$view}.php";
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("Vista {$view} no encontrada");
        }
    }
    
    protected function redirect($url) {
        header("Location: " . BASE_URL . ltrim($url, '/'));
        exit;
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }
    
    protected function requireRole($role) {
        $this->requireAuth();
        
        if ($_SESSION['user_role'] !== $role && $_SESSION['user_role'] !== 'superadmin') {
            http_response_code(403);
            die('Acceso denegado');
        }
    }
    
    protected function logActivity($accion, $descripcion, $tabla = null, $registro_id = null) {
        if (isset($_SESSION['user_id'])) {
            $sql = "INSERT INTO log_actividades (usuario_id, accion, descripcion, tabla_afectada, registro_id, ip_address, user_agent) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $this->db->query($sql, [
                $_SESSION['user_id'],
                $accion,
                $descripcion,
                $tabla,
                $registro_id,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]);
        }
    }
}
?>