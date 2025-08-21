<?php
/**
 * Controlador de asistentes
 */

class AttendeeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener filtros
        $evento_filter = $_GET['evento'] ?? '';
        $estado_filter = $_GET['estado'] ?? '';
        $tipo_filter = $_GET['tipo'] ?? '';
        $buscar = $_GET['buscar'] ?? '';
        
        // Query base para obtener asistentes
        $query = "
            SELECT 
                re.id,
                re.codigo_unico,
                re.tipo_registrante,
                re.estado,
                re.fecha_asistencia,
                re.created_at,
                e.titulo as evento_titulo,
                e.fecha_evento,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN CONCAT(r.nombre_completo, ' (', emp.razon_social, ')')
                    WHEN re.tipo_registrante = 'invitado' THEN i.nombre_completo
                END as nombre_asistente,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.email
                    WHEN re.tipo_registrante = 'invitado' THEN i.email
                END as email_asistente,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.telefono
                    WHEN re.tipo_registrante = 'invitado' THEN i.telefono
                END as telefono_asistente
            FROM registros_eventos re
            JOIN eventos e ON re.evento_id = e.id
            LEFT JOIN empresas emp ON re.empresa_id = emp.id
            LEFT JOIN representantes r ON re.representante_id = r.id
            LEFT JOIN invitados i ON re.invitado_id = i.id
            WHERE 1=1
        ";
        
        $params = [];
        
        // Aplicar filtros
        if (!empty($evento_filter)) {
            $query .= " AND e.id = ?";
            $params[] = $evento_filter;
        }
        
        if (!empty($estado_filter)) {
            $query .= " AND re.estado = ?";
            $params[] = $estado_filter;
        }
        
        if (!empty($tipo_filter)) {
            $query .= " AND re.tipo_registrante = ?";
            $params[] = $tipo_filter;
        }
        
        if (!empty($buscar)) {
            $query .= " AND (
                (re.tipo_registrante = 'empresa' AND (r.nombre_completo LIKE ? OR emp.razon_social LIKE ? OR r.email LIKE ?))
                OR 
                (re.tipo_registrante = 'invitado' AND (i.nombre_completo LIKE ? OR i.email LIKE ?))
                OR re.codigo_unico LIKE ?
            )";
            $buscar_param = "%{$buscar}%";
            $params = array_merge($params, [$buscar_param, $buscar_param, $buscar_param, $buscar_param, $buscar_param, $buscar_param]);
        }
        
        $query .= " ORDER BY re.created_at DESC";
        
        $asistentes = $this->db->fetchAll($query, $params);
        
        // Obtener lista de eventos para el filtro
        $eventos = $this->db->fetchAll(
            "SELECT id, titulo FROM eventos WHERE estado = 'publicado' ORDER BY fecha_evento DESC"
        );
        
        // Obtener estadísticas
        $stats = $this->getAttendeeStats();
        
        $this->view('asistentes/index', [
            'asistentes' => $asistentes,
            'eventos' => $eventos,
            'filtros' => [
                'evento' => $evento_filter,
                'estado' => $estado_filter,
                'tipo' => $tipo_filter,
                'buscar' => $buscar
            ],
            'stats' => $stats
        ]);
    }
    
    public function detail($attendeeId) {
        $this->requireAuth();
        
        // Obtener detalles del asistente
        $asistente = $this->db->fetch("
            SELECT 
                re.*,
                e.titulo as evento_titulo,
                e.fecha_evento,
                e.ubicacion,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.nombre_completo
                    WHEN re.tipo_registrante = 'invitado' THEN i.nombre_completo
                END as nombre_asistente,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.email
                    WHEN re.tipo_registrante = 'invitado' THEN i.email
                END as email_asistente,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.telefono
                    WHEN re.tipo_registrante = 'invitado' THEN i.telefono
                END as telefono_asistente,
                emp.razon_social,
                emp.rfc,
                i.fecha_nacimiento,
                i.ocupacion
            FROM registros_eventos re
            JOIN eventos e ON re.evento_id = e.id
            LEFT JOIN empresas emp ON re.empresa_id = emp.id
            LEFT JOIN representantes r ON re.representante_id = r.id
            LEFT JOIN invitados i ON re.invitado_id = i.id
            WHERE re.id = ?
        ", [$attendeeId]);
        
        if (!$asistente) {
            $_SESSION['error'] = 'Asistente no encontrado';
            $this->redirect('asistentes');
        }
        
        $this->view('asistentes/detail', [
            'asistente' => $asistente
        ]);
    }
    
    public function updateStatus() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('asistentes');
        }
        
        $attendeeId = $_POST['attendee_id'] ?? '';
        $newStatus = $_POST['status'] ?? '';
        
        if (!$attendeeId || !$newStatus) {
            $_SESSION['error'] = 'Datos incompletos';
            $this->redirect('asistentes');
        }
        
        try {
            // Si se marca como "asistio", agregar fecha de asistencia
            if ($newStatus === 'asistio') {
                $this->db->query(
                    "UPDATE registros_eventos SET estado = ?, fecha_asistencia = NOW(), validado_por = ? WHERE id = ?",
                    [$newStatus, $_SESSION['user_id'], $attendeeId]
                );
            } else {
                $this->db->query(
                    "UPDATE registros_eventos SET estado = ? WHERE id = ?",
                    [$newStatus, $attendeeId]
                );
            }
            
            $_SESSION['success'] = 'Estado actualizado correctamente';
            
        } catch (Exception $e) {
            error_log("Error actualizando estado de asistente: " . $e->getMessage());
            $_SESSION['error'] = 'Error interno del servidor';
        }
        
        $this->redirect('asistentes');
    }
    
    public function export() {
        $this->requireAuth();
        
        // Obtener todos los asistentes
        $asistentes = $this->db->fetchAll("
            SELECT 
                re.codigo_unico as 'Código Único',
                e.titulo as 'Evento',
                DATE_FORMAT(e.fecha_evento, '%d/%m/%Y %H:%i') as 'Fecha Evento',
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN 'Empresa'
                    WHEN re.tipo_registrante = 'invitado' THEN 'Invitado'
                END as 'Tipo',
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.nombre_completo
                    WHEN re.tipo_registrante = 'invitado' THEN i.nombre_completo
                END as 'Nombre',
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN r.email
                    WHEN re.tipo_registrante = 'invitado' THEN i.email
                END as 'Email',
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                    ELSE '-'
                END as 'Empresa',
                CASE 
                    WHEN re.estado = 'registrado' THEN 'Registrado'
                    WHEN re.estado = 'confirmado' THEN 'Confirmado'
                    WHEN re.estado = 'asistio' THEN 'Asistió'
                    WHEN re.estado = 'no_asistio' THEN 'No Asistió'
                    WHEN re.estado = 'cancelado' THEN 'Cancelado'
                END as 'Estado',
                DATE_FORMAT(re.created_at, '%d/%m/%Y %H:%i') as 'Fecha Registro',
                COALESCE(DATE_FORMAT(re.fecha_asistencia, '%d/%m/%Y %H:%i'), '-') as 'Fecha Asistencia'
            FROM registros_eventos re
            JOIN eventos e ON re.evento_id = e.id
            LEFT JOIN empresas emp ON re.empresa_id = emp.id
            LEFT JOIN representantes r ON re.representante_id = r.id
            LEFT JOIN invitados i ON re.invitado_id = i.id
            ORDER BY re.created_at DESC
        ");
        
        // Exportar como CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="asistentes_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Escribir BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        if (!empty($asistentes)) {
            fputcsv($output, array_keys($asistentes[0]));
            
            // Escribir datos
            foreach ($asistentes as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
    
    private function getAttendeeStats() {
        return [
            'total' => $this->db->fetch("SELECT COUNT(*) as count FROM registros_eventos")['count'] ?? 0,
            'asistieron' => $this->db->fetch("SELECT COUNT(*) as count FROM registros_eventos WHERE estado = 'asistio'")['count'] ?? 0,
            'registrados' => $this->db->fetch("SELECT COUNT(*) as count FROM registros_eventos WHERE estado = 'registrado'")['count'] ?? 0,
            'cancelados' => $this->db->fetch("SELECT COUNT(*) as count FROM registros_eventos WHERE estado = 'cancelado'")['count'] ?? 0
        ];
    }
}
?>