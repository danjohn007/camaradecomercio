<?php
/**
 * Controlador de asistentes
 */

class AttendeeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener parámetros de búsqueda y filtrado
        $search = $_GET['search'] ?? '';
        $evento = $_GET['evento'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Construir consulta base
        $whereConditions = [];
        $params = [];
        
        // Aplicar filtros según el rol del usuario
        if ($_SESSION['user_role'] === 'gestor') {
            $whereConditions[] = "e.usuario_id = ?";
            $params[] = $_SESSION['user_id'];
        }
        
        // Filtro de búsqueda
        if (!empty($search)) {
            $whereConditions[] = "(
                rep.nombre_completo LIKE ? OR 
                rep.email LIKE ? OR 
                inv.nombre_completo LIKE ? OR 
                inv.email LIKE ? OR 
                emp.razon_social LIKE ? OR 
                re.codigo_unico LIKE ?
            )";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        // Filtro por evento
        if (!empty($evento)) {
            $whereConditions[] = "re.evento_id = ?";
            $params[] = $evento;
        }
        
        // Filtro por estado
        if (!empty($estado)) {
            $whereConditions[] = "re.estado = ?";
            $params[] = $estado;
        }
        
        // Filtro por tipo de registrante
        if (!empty($tipo)) {
            $whereConditions[] = "re.tipo_registrante = ?";
            $params[] = $tipo;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        // Consulta principal de asistentes
        $query = "
            SELECT re.*, e.titulo as evento_titulo, e.fecha_evento,
                   CASE 
                       WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                       ELSE inv.nombre_completo
                   END as nombre_registrante,
                   CASE 
                       WHEN re.tipo_registrante = 'empresa' THEN rep.email
                       ELSE inv.email
                   END as email_registrante,
                   CASE 
                       WHEN re.tipo_registrante = 'empresa' THEN rep.telefono
                       ELSE inv.telefono
                   END as telefono_registrante,
                   emp.nombre_comercial,
                   rep.puesto,
                   inv.ocupacion
            FROM registros_eventos re
            INNER JOIN eventos e ON re.evento_id = e.id
            LEFT JOIN empresas emp ON re.empresa_id = emp.id
            LEFT JOIN representantes rep ON re.representante_id = rep.id
            LEFT JOIN invitados inv ON re.invitado_id = inv.id
            $whereClause
            ORDER BY re.created_at DESC
            LIMIT $perPage OFFSET $offset
        ";
        
        $asistentes = $this->db->fetchAll($query, $params);
        
        // Contar total de registros para paginación
        $countQuery = "
            SELECT COUNT(*) as total
            FROM registros_eventos re
            INNER JOIN eventos e ON re.evento_id = e.id
            LEFT JOIN empresas emp ON re.empresa_id = emp.id
            LEFT JOIN representantes rep ON re.representante_id = rep.id
            LEFT JOIN invitados inv ON re.invitado_id = inv.id
            $whereClause
        ";
        
        $totalResult = $this->db->fetch($countQuery, $params);
        $total = $totalResult['total'];
        $totalPages = ceil($total / $perPage);
        
        // Obtener lista de eventos para el filtro
        $eventosQuery = "SELECT id, titulo FROM eventos";
        if ($_SESSION['user_role'] === 'gestor') {
            $eventosQuery .= " WHERE usuario_id = ?";
            $eventos = $this->db->fetchAll($eventosQuery, [$_SESSION['user_id']]);
        } else {
            $eventos = $this->db->fetchAll($eventosQuery);
        }
        
        // Estadísticas rápidas
        $statsQuery = "
            SELECT 
                COUNT(*) as total_registros,
                SUM(CASE WHEN estado = 'asistio' THEN 1 ELSE 0 END) as total_asistieron,
                SUM(CASE WHEN estado = 'registrado' THEN 1 ELSE 0 END) as total_pendientes,
                SUM(CASE WHEN estado = 'cancelado' THEN 1 ELSE 0 END) as total_cancelados
            FROM registros_eventos re
            INNER JOIN eventos e ON re.evento_id = e.id
            " . ($_SESSION['user_role'] === 'gestor' ? "WHERE e.usuario_id = ?" : "");
        
        $statsParams = $_SESSION['user_role'] === 'gestor' ? [$_SESSION['user_id']] : [];
        $stats = $this->db->fetch($statsQuery, $statsParams);
        
        $this->view('asistentes/index', [
            'asistentes' => $asistentes,
            'eventos' => $eventos,
            'stats' => $stats,
            'search' => $search,
            'evento' => $evento,
            'estado' => $estado,
            'tipo' => $tipo,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'perPage' => $perPage
        ]);
    }
    
    public function export() {
        $this->requireAuth();
        
        // Aplicar los mismos filtros que en index pero sin paginación
        $search = $_GET['search'] ?? '';
        $evento = $_GET['evento'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        
        $whereConditions = [];
        $params = [];
        
        if ($_SESSION['user_role'] === 'gestor') {
            $whereConditions[] = "e.usuario_id = ?";
            $params[] = $_SESSION['user_id'];
        }
        
        if (!empty($search)) {
            $whereConditions[] = "(
                rep.nombre_completo LIKE ? OR 
                rep.email LIKE ? OR 
                inv.nombre_completo LIKE ? OR 
                inv.email LIKE ? OR 
                emp.razon_social LIKE ? OR 
                re.codigo_unico LIKE ?
            )";
            $searchTerm = "%$search%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($evento)) {
            $whereConditions[] = "re.evento_id = ?";
            $params[] = $evento;
        }
        
        if (!empty($estado)) {
            $whereConditions[] = "re.estado = ?";
            $params[] = $estado;
        }
        
        if (!empty($tipo)) {
            $whereConditions[] = "re.tipo_registrante = ?";
            $params[] = $tipo;
        }
        
        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
        
        $query = "
            SELECT re.*, e.titulo as evento_titulo, e.fecha_evento,
                   CASE 
                       WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                       ELSE inv.nombre_completo
                   END as nombre_registrante,
                   CASE 
                       WHEN re.tipo_registrante = 'empresa' THEN rep.email
                       ELSE inv.email
                   END as email_registrante,
                   CASE 
                       WHEN re.tipo_registrante = 'empresa' THEN rep.telefono
                       ELSE inv.telefono
                   END as telefono_registrante,
                   emp.nombre_comercial,
                   rep.puesto,
                   inv.ocupacion
            FROM registros_eventos re
            INNER JOIN eventos e ON re.evento_id = e.id
            LEFT JOIN empresas emp ON re.empresa_id = emp.id
            LEFT JOIN representantes rep ON re.representante_id = rep.id
            LEFT JOIN invitados inv ON re.invitado_id = inv.id
            $whereClause
            ORDER BY re.created_at DESC
        ";
        
        $asistentes = $this->db->fetchAll($query, $params);
        
        // Configurar headers para descarga CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=asistentes_' . date('Y-m-d_H-i-s') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // Escribir BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Escribir encabezados
        fputcsv($output, [
            'Código QR',
            'Evento',
            'Fecha Evento',
            'Tipo Registrante',
            'Nombre/Razón Social',
            'Email',
            'Teléfono',
            'Empresa/Ocupación',
            'Puesto',
            'Estado',
            'Fecha Registro',
            'Fecha Asistencia'
        ]);
        
        // Escribir datos
        foreach ($asistentes as $asistente) {
            fputcsv($output, [
                $asistente['codigo_unico'],
                $asistente['evento_titulo'],
                date('d/m/Y H:i', strtotime($asistente['fecha_evento'])),
                ucfirst($asistente['tipo_registrante']),
                $asistente['nombre_registrante'],
                $asistente['email_registrante'],
                $asistente['telefono_registrante'] ?? '',
                $asistente['tipo_registrante'] === 'empresa' ? $asistente['nombre_comercial'] : $asistente['ocupacion'],
                $asistente['puesto'] ?? '',
                ucfirst($asistente['estado']),
                date('d/m/Y H:i', strtotime($asistente['created_at'])),
                $asistente['fecha_asistencia'] ? date('d/m/Y H:i', strtotime($asistente['fecha_asistencia'])) : ''
            ]);
        }
        
        fclose($output);
        exit;
    }
}
?>