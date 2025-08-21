<?php
/**
 * Controlador de perfil de usuario
 */

class ProfileController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener datos del usuario actual
        $usuario = $this->db->fetch(
            "SELECT * FROM usuarios WHERE id = ?",
            [$_SESSION['user_id']]
        );
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('dashboard');
        }
        
        $this->view('profile/index', [
            'usuario' => $usuario,
            'pageTitle' => 'Mi Perfil',
            'hideSidebar' => true
        ]);
    }
    
    public function edit() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processProfileUpdate();
        }
        
        // Obtener datos del usuario actual
        $usuario = $this->db->fetch(
            "SELECT * FROM usuarios WHERE id = ?",
            [$_SESSION['user_id']]
        );
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('dashboard');
        }
        
        $this->view('profile/edit', [
            'usuario' => $usuario,
            'pageTitle' => 'Editar Perfil',
            'hideSidebar' => true
        ]);
    }
    
    public function settings() {
        $this->requireAuth();
        
        // Obtener configuraciones del usuario (simuladas por ahora)
        $configuraciones = [
            'notificaciones_email' => true,
            'notificaciones_evento' => true,
            'tema_oscuro' => false,
            'idioma' => 'es'
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processSettingsUpdate();
        }
        
        $this->view('profile/settings', [
            'configuraciones' => $configuraciones,
            'pageTitle' => 'Configuración',
            'hideSidebar' => true
        ]);
    }
    
    public function changePassword() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processPasswordChange();
        }
        
        $this->view('profile/change-password', [
            'pageTitle' => 'Cambiar Contraseña',
            'hideSidebar' => true
        ]);
    }
    
    private function processProfileUpdate() {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        
        // Validaciones
        $errors = [];
        
        if (empty($nombre)) $errors[] = 'El nombre es obligatorio';
        if (empty($email)) $errors[] = 'El email es obligatorio';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
        
        // Verificar que el email no esté en uso por otro usuario
        $emailExistente = $this->db->fetch(
            "SELECT id FROM usuarios WHERE email = ? AND id != ?",
            [$email, $_SESSION['user_id']]
        );
        
        if ($emailExistente) {
            $errors[] = 'El email ya está en uso por otro usuario';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            return;
        }
        
        try {
            // Actualizar datos del usuario
            $this->db->query(
                "UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, updated_at = NOW() WHERE id = ?",
                [$nombre, $email, $telefono, $_SESSION['user_id']]
            );
            
            // Actualizar nombre en la sesión
            $_SESSION['user_name'] = $nombre;
            
            $_SESSION['success'] = 'Perfil actualizado correctamente';
            $this->redirect('perfil');
            
        } catch (Exception $e) {
            error_log("Error actualizando perfil: " . $e->getMessage());
            $_SESSION['error'] = 'Error interno del servidor';
        }
    }
    
    private function processPasswordChange() {
        $passwordActual = $_POST['password_actual'] ?? '';
        $passwordNuevo = $_POST['password_nuevo'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        // Validaciones
        $errors = [];
        
        if (empty($passwordActual)) $errors[] = 'Contraseña actual requerida';
        if (empty($passwordNuevo)) $errors[] = 'Nueva contraseña requerida';
        if (strlen($passwordNuevo) < 6) $errors[] = 'La nueva contraseña debe tener al menos 6 caracteres';
        if ($passwordNuevo !== $passwordConfirm) $errors[] = 'Las contraseñas no coinciden';
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            return;
        }
        
        // Verificar contraseña actual
        $usuario = $this->db->fetch(
            "SELECT password FROM usuarios WHERE id = ?",
            [$_SESSION['user_id']]
        );
        
        if (!$usuario || !password_verify($passwordActual, $usuario['password'])) {
            $_SESSION['error'] = 'Contraseña actual incorrecta';
            return;
        }
        
        try {
            // Actualizar contraseña
            $passwordHash = password_hash($passwordNuevo, PASSWORD_DEFAULT);
            
            $this->db->query(
                "UPDATE usuarios SET password = ?, updated_at = NOW() WHERE id = ?",
                [$passwordHash, $_SESSION['user_id']]
            );
            
            $_SESSION['success'] = 'Contraseña actualizada correctamente';
            $this->redirect('configuracion');
            
        } catch (Exception $e) {
            error_log("Error cambiando contraseña: " . $e->getMessage());
            $_SESSION['error'] = 'Error interno del servidor';
        }
    }
    
    private function processSettingsUpdate() {
        // Por ahora solo simular el guardado de configuraciones
        $_SESSION['success'] = 'Configuraciones guardadas correctamente';
        $this->redirect('configuracion');
    }
}
?>