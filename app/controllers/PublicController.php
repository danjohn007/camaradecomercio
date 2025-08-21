<?php
/**
 * Controlador público para páginas de eventos y registro
 */

class PublicController extends BaseController {
    
    public function eventPage($slug) {
        // Obtener evento por slug
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE slug = ? AND estado = 'publicado'",
            [$slug]
        );
        
        if (!$evento) {
            $this->redirect('404');
        }
        
        // Obtener número de asistentes registrados
        $asistentesRegistrados = $this->db->fetch(
            "SELECT COUNT(*) as total FROM registros_eventos 
             WHERE evento_id = ? AND estado != 'cancelado'",
            [$evento['id']]
        )['total'];
        
        // Verificar si hay cupo disponible
        $cupoDisponible = $evento['cupo_maximo'] - $asistentesRegistrados;
        
        $this->view('public/event-page', [
            'evento' => $evento,
            'asistentesRegistrados' => $asistentesRegistrados,
            'cupoDisponible' => $cupoDisponible
        ]);
    }
    
    public function companyRegistration($slug) {
        // Obtener evento por slug
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE slug = ? AND estado = 'publicado'",
            [$slug]
        );
        
        if (!$evento) {
            $this->redirect('404');
        }
        
        // Verificar que el evento permita empresas
        if ($evento['tipo_publico'] === 'invitados') {
            $_SESSION['error'] = 'Este evento es solo para invitados generales';
            $this->redirect('evento/' . $slug);
        }
        
        $this->view('public/company-registration', [
            'evento' => $evento,
            'eventSlug' => $slug
        ]);
    }
    
    public function guestRegistration($slug) {
        // Obtener evento por slug
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE slug = ? AND estado = 'publicado'",
            [$slug]
        );
        
        if (!$evento) {
            $this->redirect('404');
        }
        
        // Verificar que el evento permita invitados
        if ($evento['tipo_publico'] === 'empresas') {
            $_SESSION['error'] = 'Este evento es solo para empresas';
            $this->redirect('evento/' . $slug);
        }
        
        $this->view('public/guest-registration', [
            'evento' => $evento,
            'eventSlug' => $slug
        ]);
    }
    
    public function confirmation($codigoUnico) {
        // Obtener registro por código único
        $registro = $this->db->fetch(
            "SELECT re.*, e.titulo as evento_titulo, e.fecha_evento, e.ubicacion, e.descripcion, e.slug as evento_slug,
                    CASE 
                        WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                        ELSE inv.nombre_completo
                    END as nombre_registrante,
                    CASE 
                        WHEN re.tipo_registrante = 'empresa' THEN rep.email
                        ELSE inv.email
                    END as email_registrante
             FROM registros_eventos re
             INNER JOIN eventos e ON re.evento_id = e.id
             LEFT JOIN empresas emp ON re.empresa_id = emp.id
             LEFT JOIN representantes rep ON re.representante_id = rep.id
             LEFT JOIN invitados inv ON re.invitado_id = inv.id
             WHERE re.codigo_unico = ?",
            [$codigoUnico]
        );
        
        if (!$registro) {
            $_SESSION['error'] = 'Registro no encontrado';
            $this->redirect('');
        }
        
        $this->view('public/confirmation', [
            'registro' => $registro
        ]);
    }
    
    public function ticketHistory() {
        // Vista para mostrar el historial de boletos por RFC o teléfono
        $this->view('public/ticket-history');
    }
    
    public function searchTicketHistory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            $this->redirect('historial-boletos');
        }
        
        $identifier = trim($_POST['identifier'] ?? '');
        $identifierType = $_POST['identifier_type'] ?? '';
        
        if (empty($identifier)) {
            $_SESSION['error'] = 'Debe proporcionar un RFC o teléfono';
            $this->redirect('historial-boletos');
        }
        
        $tickets = [];
        
        if ($identifierType === 'rfc') {
            // Buscar boletos por RFC (empresas)
            $tickets = $this->db->fetchAll(
                "SELECT re.codigo_unico, re.estado, re.fecha_asistencia, re.created_at,
                        e.titulo as evento_titulo, e.fecha_evento, e.ubicacion,
                        emp.razon_social as nombre_registrante, rep.email,
                        'empresa' as tipo_registrante
                 FROM registros_eventos re
                 INNER JOIN eventos e ON re.evento_id = e.id
                 INNER JOIN empresas emp ON re.empresa_id = emp.id
                 INNER JOIN representantes rep ON re.representante_id = rep.id
                 WHERE emp.rfc = ? AND re.estado != 'cancelado'
                 ORDER BY re.created_at DESC",
                [$identifier]
            );
        } else {
            // Buscar boletos por teléfono (invitados)
            $tickets = $this->db->fetchAll(
                "SELECT re.codigo_unico, re.estado, re.fecha_asistencia, re.created_at,
                        e.titulo as evento_titulo, e.fecha_evento, e.ubicacion,
                        inv.nombre_completo as nombre_registrante, inv.email,
                        'invitado' as tipo_registrante
                 FROM registros_eventos re
                 INNER JOIN eventos e ON re.evento_id = e.id
                 INNER JOIN invitados inv ON re.invitado_id = inv.id
                 WHERE inv.telefono = ? AND re.estado != 'cancelado'
                 ORDER BY re.created_at DESC",
                [$identifier]
            );
        }
        
        $this->view('public/ticket-history', [
            'tickets' => $tickets,
            'searchPerformed' => true,
            'identifier' => $identifier,
            'identifierType' => $identifierType
        ]);
    }
}
?>