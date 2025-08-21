<?php
/**
 * Controlador del dashboard principal
 */

class DashboardController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Obtener métricas del dashboard
        $metrics = $this->getMetrics();
        
        // Obtener eventos próximos
        $eventosProximos = $this->getEventosProximos();
        
        // Obtener actividad reciente
        $actividadReciente = $this->getActividadReciente();
        
        $this->view('dashboard/index', [
            'metrics' => $metrics,
            'eventosProximos' => $eventosProximos,
            'actividadReciente' => $actividadReciente
        ]);
    }
    
    private function getMetrics() {
        // Total de eventos
        $totalEventos = $this->db->fetch(
            "SELECT COUNT(*) as total FROM eventos WHERE estado != 'cancelado'"
        )['total'];
        
        // Eventos hoy
        $eventosHoy = $this->db->fetch(
            "SELECT COUNT(*) as total FROM eventos 
             WHERE DATE(fecha_evento) = CURDATE() AND estado = 'publicado'"
        )['total'];
        
        // Total de asistentes registrados
        $totalAsistentes = $this->db->fetch(
            "SELECT COUNT(*) as total FROM registros_eventos WHERE estado != 'cancelado'"
        )['total'];
        
        // Asistentes hoy
        $asistentesHoy = $this->db->fetch(
            "SELECT COUNT(*) as total FROM registros_eventos re
             INNER JOIN eventos e ON re.evento_id = e.id
             WHERE DATE(e.fecha_evento) = CURDATE() AND re.estado != 'cancelado'"
        )['total'];
        
        // Porcentaje de ocupación promedio
        $ocupacion = $this->db->fetch(
            "SELECT AVG(ocupacion_porcentaje) as promedio FROM (
                SELECT (COUNT(re.id) * 100.0 / e.cupo_maximo) as ocupacion_porcentaje
                FROM eventos e
                LEFT JOIN registros_eventos re ON e.id = re.evento_id AND re.estado != 'cancelado'
                WHERE e.estado = 'publicado' AND e.cupo_maximo > 0
                GROUP BY e.id
             ) as ocupaciones"
        )['promedio'] ?? 0;
        
        return [
            'totalEventos' => $totalEventos,
            'eventosHoy' => $eventosHoy,
            'totalAsistentes' => $totalAsistentes,
            'asistentesHoy' => $asistentesHoy,
            'ocupacionPromedio' => round($ocupacion, 1)
        ];
    }
    
    private function getEventosProximos() {
        return $this->db->fetchAll(
            "SELECT e.*, COUNT(re.id) as asistentes_registrados
             FROM eventos e
             LEFT JOIN registros_eventos re ON e.id = re.evento_id AND re.estado != 'cancelado'
             WHERE e.fecha_evento >= NOW() AND e.estado = 'publicado'
             GROUP BY e.id
             ORDER BY e.fecha_evento ASC
             LIMIT 5"
        );
    }
    
    private function getActividadReciente() {
        return $this->db->fetchAll(
            "SELECT la.*, u.nombre as usuario_nombre
             FROM log_actividades la
             INNER JOIN usuarios u ON la.usuario_id = u.id
             ORDER BY la.created_at DESC
             LIMIT 10"
        );
    }
}
?>