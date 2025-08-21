<?php
/**
 * Controlador de eventos
 */

class EventController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Filtros
        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $fechaInicio = $_GET['fecha_inicio'] ?? '';
        $fechaFin = $_GET['fecha_fin'] ?? '';
        
        // Construir consulta
        $sql = "SELECT e.*, u.nombre as usuario_nombre, 
                       COUNT(re.id) as total_asistentes,
                       COUNT(CASE WHEN re.estado = 'asistio' THEN 1 END) as asistentes_confirmados
                FROM eventos e
                INNER JOIN usuarios u ON e.usuario_id = u.id
                LEFT JOIN registros_eventos re ON e.id = re.evento_id AND re.estado != 'cancelado'
                WHERE 1=1";
        
        $params = [];
        
        // Aplicar filtros
        if (!empty($search)) {
            $sql .= " AND (e.titulo LIKE ? OR e.descripcion LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        
        if (!empty($estado)) {
            $sql .= " AND e.estado = ?";
            $params[] = $estado;
        }
        
        if (!empty($fechaInicio)) {
            $sql .= " AND DATE(e.fecha_evento) >= ?";
            $params[] = $fechaInicio;
        }
        
        if (!empty($fechaFin)) {
            $sql .= " AND DATE(e.fecha_evento) <= ?";
            $params[] = $fechaFin;
        }
        
        // Si es gestor, solo ver sus eventos
        if ($_SESSION['user_role'] === 'gestor') {
            $sql .= " AND e.usuario_id = ?";
            $params[] = $_SESSION['user_id'];
        }
        
        $sql .= " GROUP BY e.id ORDER BY e.created_at DESC";
        
        $eventos = $this->db->fetchAll($sql, $params);
        
        $this->view('eventos/index', [
            'eventos' => $eventos,
            'search' => $search,
            'estado' => $estado,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin
        ]);
    }
    
    public function create() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEventForm();
        }
        
        $this->view('eventos/create');
    }
    
    public function edit($id) {
        $this->requireAuth();
        
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE id = ?",
            [$id]
        );
        
        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            $this->redirect('eventos');
        }
        
        // Verificar permisos
        if ($_SESSION['user_role'] === 'gestor' && $evento['usuario_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'No tienes permisos para editar este evento';
            $this->redirect('eventos');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEventForm($id);
        }
        
        $this->view('eventos/edit', ['evento' => $evento]);
    }
    
    public function changeStatus($id) {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $newStatus = $input['estado'] ?? '';
        
        $validStatuses = ['borrador', 'publicado', 'cerrado', 'cancelado'];
        if (!in_array($newStatus, $validStatuses)) {
            $this->json(['error' => 'Estado inválido'], 400);
        }
        
        $evento = $this->db->fetch("SELECT * FROM eventos WHERE id = ?", [$id]);
        
        if (!$evento) {
            $this->json(['error' => 'Evento no encontrado'], 404);
        }
        
        // Verificar permisos
        if ($_SESSION['user_role'] === 'gestor' && $evento['usuario_id'] != $_SESSION['user_id']) {
            $this->json(['error' => 'No tienes permisos para modificar este evento'], 403);
        }
        
        // Actualizar estado
        $this->db->query(
            "UPDATE eventos SET estado = ?, updated_at = NOW() WHERE id = ?",
            [$newStatus, $id]
        );
        
        $this->logActivity(
            'cambiar_estado_evento', 
            "Estado del evento '{$evento['titulo']}' cambiado a '{$newStatus}'", 
            'eventos', 
            $id
        );
        
        $this->json(['success' => true]);
    }
        $this->requireAuth();
        
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE id = ?",
            [$id]
        );
        
        if (!$evento) {
            $this->json(['error' => 'Evento no encontrado'], 404);
        }
        
        // Verificar permisos
        if ($_SESSION['user_role'] === 'gestor' && $evento['usuario_id'] != $_SESSION['user_id']) {
            $this->json(['error' => 'No tienes permisos para eliminar este evento'], 403);
        }
        
        // Eliminar evento
        $this->db->query(
            "DELETE FROM eventos WHERE id = ?",
            [$id]
        );
        
        $this->logActivity('eliminar_evento', "Evento eliminado: {$evento['titulo']}", 'eventos', $id);
        
        $this->json(['success' => true]);
    }
    
    private function processEventForm($id = null) {
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fechaEvento = $_POST['fecha_evento'] ?? '';
        $ubicacion = trim($_POST['ubicacion'] ?? '');
        $cupoMaximo = (int)($_POST['cupo_maximo'] ?? 0);
        $costo = (float)($_POST['costo'] ?? 0);
        $tipoPublico = $_POST['tipo_publico'] ?? 'todos';
        $estado = $_POST['estado'] ?? 'borrador';
        
        // Validaciones
        $errors = [];
        
        if (empty($titulo)) $errors[] = 'El título es obligatorio';
        if (empty($descripcion)) $errors[] = 'La descripción es obligatoria';
        if (empty($fechaEvento)) $errors[] = 'La fecha del evento es obligatoria';
        if (empty($ubicacion)) $errors[] = 'La ubicación es obligatoria';
        if ($cupoMaximo <= 0) $errors[] = 'El cupo máximo debe ser mayor a 0';
        
        // Validar fecha futura
        if (!empty($fechaEvento) && strtotime($fechaEvento) <= time()) {
            $errors[] = 'La fecha del evento debe ser futura';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            return;
        }
        
        // Generar slug
        $slug = $this->generateSlug($titulo, $id);
        
        // Procesar imagen si se subió
        $imagenBanner = null;
        if (isset($_FILES['imagen_banner']) && $_FILES['imagen_banner']['error'] === UPLOAD_ERR_OK) {
            $imagenBanner = $this->uploadImage($_FILES['imagen_banner']);
            if (!$imagenBanner) {
                $_SESSION['error'] = 'Error al subir la imagen';
                return;
            }
        }
        
        if ($id) {
            // Actualizar evento
            $sql = "UPDATE eventos SET 
                    titulo = ?, descripcion = ?, fecha_evento = ?, ubicacion = ?, 
                    cupo_maximo = ?, costo = ?, tipo_publico = ?, estado = ?, slug = ?";
            $params = [$titulo, $descripcion, $fechaEvento, $ubicacion, $cupoMaximo, $costo, $tipoPublico, $estado, $slug];
            
            if ($imagenBanner) {
                $sql .= ", imagen_banner = ?";
                $params[] = $imagenBanner;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $this->db->query($sql, $params);
            
            $this->logActivity('editar_evento', "Evento editado: {$titulo}", 'eventos', $id);
            $_SESSION['success'] = 'Evento actualizado correctamente';
        } else {
            // Crear evento
            $sql = "INSERT INTO eventos 
                    (titulo, descripcion, fecha_evento, ubicacion, cupo_maximo, costo, tipo_publico, estado, slug, imagen_banner, usuario_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $this->db->query($sql, [
                $titulo, $descripcion, $fechaEvento, $ubicacion, $cupoMaximo, 
                $costo, $tipoPublico, $estado, $slug, $imagenBanner, $_SESSION['user_id']
            ]);
            
            $eventoId = $this->db->lastInsertId();
            
            $this->logActivity('crear_evento', "Evento creado: {$titulo}", 'eventos', $eventoId);
            $_SESSION['success'] = 'Evento creado correctamente';
        }
        
        $this->redirect('eventos');
    }
    
    private function generateSlug($titulo, $excludeId = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $titulo)));
        $originalSlug = $slug;
        $counter = 1;
        
        while (true) {
            $sql = "SELECT id FROM eventos WHERE slug = ?";
            $params = [$slug];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $existing = $this->db->fetch($sql, $params);
            
            if (!$existing) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    private function uploadImage($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $uploadPath = UPLOAD_PATH . 'eventos/' . $filename;
        
        // Crear directorio si no existe
        if (!is_dir(dirname($uploadPath))) {
            mkdir(dirname($uploadPath), 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $filename;
        }
        
        return false;
    }
}
?>