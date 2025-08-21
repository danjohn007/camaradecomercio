<?php
/**
 * Controlador de reportes
 */

class ReportController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener estadísticas generales
        $stats = $this->getGeneralStats();
        
        // Obtener datos para gráficos
        $eventStats = $this->getEventStats();
        $attendanceStats = $this->getAttendanceStats();
        $userStats = $this->getUserStats();
        $monthlyStats = $this->getMonthlyStats();
        
        $this->view('reportes/index', [
            'stats' => $stats,
            'eventStats' => $eventStats,
            'attendanceStats' => $attendanceStats,
            'userStats' => $userStats,
            'monthlyStats' => $monthlyStats
        ]);
    }
    
    public function export() {
        $this->requireAuth();
        
        $type = $_GET['type'] ?? 'general';
        $format = $_GET['format'] ?? 'csv';
        
        switch ($type) {
            case 'eventos':
                $this->exportEvents($format);
                break;
            case 'asistentes':
                $this->exportAttendees($format);
                break;
            case 'usuarios':
                $this->exportUsers($format);
                break;
            default:
                $this->exportGeneral($format);
                break;
        }
    }
    
    private function getGeneralStats() {
        // Total de eventos
        $totalEventos = $this->db->fetch("SELECT COUNT(*) as total FROM eventos");
        
        // Eventos publicados
        $eventosPublicados = $this->db->fetch("SELECT COUNT(*) as total FROM eventos WHERE estado = 'publicado'");
        
        // Total de registros
        $totalRegistros = $this->db->fetch("SELECT COUNT(*) as total FROM registros_eventos WHERE estado != 'cancelado'");
        
        // Total de asistentes confirmados
        $asistentesConfirmados = $this->db->fetch("SELECT COUNT(*) as total FROM registros_eventos WHERE estado = 'asistio'");
        
        // Total de usuarios del sistema
        $totalUsuarios = $this->db->fetch("SELECT COUNT(*) as total FROM usuarios WHERE activo = 1");
        
        // Eventos próximos (próximos 30 días)
        $eventosProximos = $this->db->fetch(
            "SELECT COUNT(*) as total FROM eventos 
             WHERE estado = 'publicado' AND fecha_evento >= NOW() AND fecha_evento <= DATE_ADD(NOW(), INTERVAL 30 DAY)"
        );
        
        return [
            'total_eventos' => $totalEventos['total'],
            'eventos_publicados' => $eventosPublicados['total'],
            'total_registros' => $totalRegistros['total'],
            'asistentes_confirmados' => $asistentesConfirmados['total'],
            'total_usuarios' => $totalUsuarios['total'],
            'eventos_proximos' => $eventosProximos['total'],
            'tasa_asistencia' => $totalRegistros['total'] > 0 ? round(($asistentesConfirmados['total'] / $totalRegistros['total']) * 100, 2) : 0
        ];
    }
    
    private function getEventStats() {
        return $this->db->fetchAll(
            "SELECT 
                e.titulo,
                e.fecha_evento,
                e.cupo_maximo,
                COUNT(re.id) as total_registrados,
                COUNT(CASE WHEN re.estado = 'asistio' THEN 1 END) as total_asistieron,
                ROUND((COUNT(CASE WHEN re.estado = 'asistio' THEN 1 END) / NULLIF(COUNT(re.id), 0)) * 100, 2) as tasa_asistencia
             FROM eventos e
             LEFT JOIN registros_eventos re ON e.id = re.evento_id AND re.estado != 'cancelado'
             WHERE e.estado IN ('publicado', 'cerrado')
             GROUP BY e.id
             ORDER BY e.fecha_evento DESC
             LIMIT 10"
        );
    }
    
    private function getAttendanceStats() {
        return $this->db->fetchAll(
            "SELECT 
                re.estado,
                COUNT(*) as total
             FROM registros_eventos re
             WHERE re.estado != 'cancelado'
             GROUP BY re.estado
             ORDER BY total DESC"
        );
    }
    
    private function getUserStats() {
        $roles = $this->db->fetchAll(
            "SELECT rol, COUNT(*) as total FROM usuarios WHERE activo = 1 GROUP BY rol"
        );
        
        $actividad = $this->db->fetchAll(
            "SELECT 
                u.nombre,
                COUNT(e.id) as eventos_creados,
                COUNT(CASE WHEN e.estado = 'publicado' THEN 1 END) as eventos_publicados
             FROM usuarios u
             LEFT JOIN eventos e ON u.id = e.usuario_id
             WHERE u.activo = 1
             GROUP BY u.id
             ORDER BY eventos_creados DESC
             LIMIT 5"
        );
        
        return [
            'roles' => $roles,
            'actividad' => $actividad
        ];
    }
    
    private function getMonthlyStats() {
        return $this->db->fetchAll(
            "SELECT 
                DATE_FORMAT(e.fecha_evento, '%Y-%m') as mes,
                COUNT(e.id) as eventos,
                COUNT(re.id) as registros,
                COUNT(CASE WHEN re.estado = 'asistio' THEN 1 END) as asistencias
             FROM eventos e
             LEFT JOIN registros_eventos re ON e.id = re.evento_id AND re.estado != 'cancelado'
             WHERE e.fecha_evento >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(e.fecha_evento, '%Y-%m')
             ORDER BY mes"
        );
    }
    
    private function exportEvents($format) {
        $eventos = $this->db->fetchAll(
            "SELECT 
                e.id,
                e.titulo,
                e.fecha_evento,
                e.ubicacion,
                e.cupo_maximo,
                e.estado,
                u.nombre as creado_por,
                COUNT(re.id) as total_registrados,
                COUNT(CASE WHEN re.estado = 'asistio' THEN 1 END) as total_asistieron
             FROM eventos e
             INNER JOIN usuarios u ON e.usuario_id = u.id
             LEFT JOIN registros_eventos re ON e.id = re.evento_id AND re.estado != 'cancelado'
             GROUP BY e.id
             ORDER BY e.fecha_evento DESC"
        );
        
        if ($format === 'csv') {
            $this->exportToCsv($eventos, 'reporte_eventos_' . date('Y-m-d'), [
                'ID', 'Título', 'Fecha Evento', 'Ubicación', 'Cupo Máximo', 'Estado', 
                'Creado Por', 'Total Registrados', 'Total Asistieron'
            ]);
        }
    }
    
    private function exportAttendees($format) {
        $asistentes = $this->db->fetchAll(
            "SELECT 
                re.codigo_unico,
                e.titulo as evento,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                    ELSE inv.nombre_completo
                END as nombre,
                CASE 
                    WHEN re.tipo_registrante = 'empresa' THEN rep.email
                    ELSE inv.email
                END as email,
                re.estado,
                re.fecha_asistencia,
                re.created_at as fecha_registro
             FROM registros_eventos re
             INNER JOIN eventos e ON re.evento_id = e.id
             LEFT JOIN empresas emp ON re.empresa_id = emp.id
             LEFT JOIN representantes rep ON re.representante_id = rep.id
             LEFT JOIN invitados inv ON re.invitado_id = inv.id
             WHERE re.estado != 'cancelado'
             ORDER BY re.created_at DESC"
        );
        
        if ($format === 'csv') {
            $this->exportToCsv($asistentes, 'reporte_asistentes_' . date('Y-m-d'), [
                'Código', 'Evento', 'Nombre', 'Email', 'Estado', 'Fecha Asistencia', 'Fecha Registro'
            ]);
        }
    }
    
    private function exportUsers($format) {
        $this->requireRole('superadmin');
        
        $usuarios = $this->db->fetchAll(
            "SELECT 
                u.id,
                u.nombre,
                u.email,
                u.rol,
                u.telefono,
                u.activo,
                u.created_at,
                COUNT(e.id) as eventos_creados
             FROM usuarios u
             LEFT JOIN eventos e ON u.id = e.usuario_id
             GROUP BY u.id
             ORDER BY u.created_at DESC"
        );
        
        if ($format === 'csv') {
            $this->exportToCsv($usuarios, 'reporte_usuarios_' . date('Y-m-d'), [
                'ID', 'Nombre', 'Email', 'Rol', 'Teléfono', 'Activo', 'Fecha Registro', 'Eventos Creados'
            ]);
        }
    }
    
    private function exportGeneral($format) {
        $stats = $this->getGeneralStats();
        $data = [
            ['Métrica', 'Valor'],
            ['Total de Eventos', $stats['total_eventos']],
            ['Eventos Publicados', $stats['eventos_publicados']],
            ['Total de Registros', $stats['total_registros']],
            ['Asistentes Confirmados', $stats['asistentes_confirmados']],
            ['Total de Usuarios', $stats['total_usuarios']],
            ['Eventos Próximos', $stats['eventos_proximos']],
            ['Tasa de Asistencia (%)', $stats['tasa_asistencia']]
        ];
        
        if ($format === 'csv') {
            $this->exportToCsv($data, 'reporte_general_' . date('Y-m-d'));
        }
    }
    
    private function exportToCsv($data, $filename, $headers = null) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM para Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        if ($headers) {
            fputcsv($output, $headers, ';');
            foreach ($data as $row) {
                fputcsv($output, array_values($row), ';');
            }
        } else {
            foreach ($data as $row) {
                fputcsv($output, $row, ';');
            }
        }
        
        fclose($output);
        exit;
    }
}
?>