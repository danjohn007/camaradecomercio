<?php
/**
 * Controlador de usuarios
 */

class UserController extends BaseController {
    
    public function index() {
        $this->requireRole('superadmin');
        
        // Filtros
        $search = $_GET['search'] ?? '';
        $rol = $_GET['rol'] ?? '';
        $activo = $_GET['activo'] ?? '';
        
        // Construir consulta
        $sql = "SELECT u.*, 
                       COUNT(e.id) as total_eventos,
                       COUNT(CASE WHEN e.estado = 'publicado' THEN 1 END) as eventos_publicados
                FROM usuarios u
                LEFT JOIN eventos e ON u.id = e.usuario_id
                WHERE 1=1";
        
        $params = [];
        
        // Aplicar filtros
        if (!empty($search)) {
            $sql .= " AND (u.nombre LIKE ? OR u.email LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($rol)) {
            $sql .= " AND u.rol = ?";
            $params[] = $rol;
        }
        
        if ($activo !== '') {
            $sql .= " AND u.activo = ?";
            $params[] = $activo;
        }
        
        $sql .= " GROUP BY u.id ORDER BY u.created_at DESC";
        
        $usuarios = $this->db->fetchAll($sql, $params);
        
        $this->view('usuarios/index', [
            'usuarios' => $usuarios,
            'search' => $search,
            'rol' => $rol,
            'activo' => $activo
        ]);
    }
    
    public function create() {
        $this->requireRole('superadmin');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processUserForm();
        }
        
        $this->view('usuarios/create');
    }
    
    public function edit($id) {
        $this->requireRole('superadmin');
        
        $usuario = $this->db->fetch(
            "SELECT * FROM usuarios WHERE id = ?",
            [$id]
        );
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('usuarios');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processUserForm($id);
        }
        
        $this->view('usuarios/edit', ['usuario' => $usuario]);
    }
    
    public function delete($id) {
        $this->requireRole('superadmin');
        
        $usuario = $this->db->fetch(
            "SELECT * FROM usuarios WHERE id = ?",
            [$id]
        );
        
        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('usuarios');
        }
        
        // No permitir eliminar al usuario actual
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'No puedes eliminar tu propio usuario';
            $this->redirect('usuarios');
        }
        
        // Verificar si tiene eventos asociados
        $eventos = $this->db->fetch(
            "SELECT COUNT(*) as total FROM eventos WHERE usuario_id = ?",
            [$id]
        );
        
        if ($eventos['total'] > 0) {
            $_SESSION['error'] = 'No se puede eliminar el usuario porque tiene eventos asociados';
            $this->redirect('usuarios');
        }
        
        try {
            $this->db->query("DELETE FROM usuarios WHERE id = ?", [$id]);
            
            $this->logActivity(
                'eliminar_usuario',
                "Usuario eliminado: {$usuario['nombre']} ({$usuario['email']})",
                'usuarios',
                $id
            );
            
            $_SESSION['success'] = 'Usuario eliminado correctamente';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al eliminar el usuario';
        }
        
        $this->redirect('usuarios');
    }
    
    public function changeStatus($id) {
        $this->requireRole('superadmin');
        
        $usuario = $this->db->fetch(
            "SELECT * FROM usuarios WHERE id = ?",
            [$id]
        );
        
        if (!$usuario) {
            $this->json(['error' => 'Usuario no encontrado'], 404);
        }
        
        // No permitir desactivar al usuario actual
        if ($id == $_SESSION['user_id']) {
            $this->json(['error' => 'No puedes cambiar el estado de tu propio usuario'], 400);
        }
        
        $nuevoEstado = $usuario['activo'] ? 0 : 1;
        
        try {
            $this->db->query(
                "UPDATE usuarios SET activo = ? WHERE id = ?",
                [$nuevoEstado, $id]
            );
            
            $accion = $nuevoEstado ? 'activar_usuario' : 'desactivar_usuario';
            $this->logActivity(
                $accion,
                "Usuario " . ($nuevoEstado ? 'activado' : 'desactivado') . ": {$usuario['nombre']}",
                'usuarios',
                $id
            );
            
            $this->json(['success' => true, 'nuevo_estado' => $nuevoEstado]);
        } catch (Exception $e) {
            $this->json(['error' => 'Error al cambiar el estado del usuario'], 500);
        }
    }
    
    private function processUserForm($id = null) {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = $_POST['rol'] ?? 'gestor';
        $telefono = trim($_POST['telefono'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmarPassword = trim($_POST['confirmar_password'] ?? '');
        
        // Validaciones
        $errors = [];
        
        if (empty($nombre)) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email válido es requerido';
        }
        
        if (!in_array($rol, ['superadmin', 'gestor'])) {
            $errors[] = 'Rol inválido';
        }
        
        // Validar password solo en creación o si se proporcionó
        if (!$id || !empty($password)) {
            if (empty($password)) {
                $errors[] = 'La contraseña es requerida';
            } elseif (strlen($password) < 6) {
                $errors[] = 'La contraseña debe tener al menos 6 caracteres';
            } elseif ($password !== $confirmarPassword) {
                $errors[] = 'Las contraseñas no coinciden';
            }
        }
        
        // Verificar email único
        $emailCheck = $this->db->fetch(
            "SELECT id FROM usuarios WHERE email = ?" . ($id ? " AND id != ?" : ""),
            $id ? [$email, $id] : [$email]
        );
        
        if ($emailCheck) {
            $errors[] = 'El email ya está en uso';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            return;
        }
        
        try {
            if ($id) {
                // Actualizar usuario
                $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol = ?, telefono = ?";
                $params = [$nombre, $email, $rol, $telefono];
                
                if (!empty($password)) {
                    $sql .= ", password = ?";
                    $params[] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $sql .= " WHERE id = ?";
                $params[] = $id;
                
                $this->db->query($sql, $params);
                
                $this->logActivity(
                    'editar_usuario',
                    "Usuario editado: {$nombre} ({$email})",
                    'usuarios',
                    $id
                );
                
                $_SESSION['success'] = 'Usuario actualizado correctamente';
            } else {
                // Crear usuario
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                $this->db->query(
                    "INSERT INTO usuarios (nombre, email, password, rol, telefono, activo) VALUES (?, ?, ?, ?, ?, 1)",
                    [$nombre, $email, $passwordHash, $rol, $telefono]
                );
                
                $userId = $this->db->lastInsertId();
                
                $this->logActivity(
                    'crear_usuario',
                    "Usuario creado: {$nombre} ({$email})",
                    'usuarios',
                    $userId
                );
                
                $_SESSION['success'] = 'Usuario creado correctamente';
            }
            
            $this->redirect('usuarios');
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al procesar el usuario';
        }
    }
}
?>