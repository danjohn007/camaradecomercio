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
        // Placeholder - implementar registro de empresas
        $this->view('public/company-registration', [
            'eventSlug' => $slug
        ]);
    }
    
    public function guestRegistration($slug) {
        // Placeholder - implementar registro de invitados
        $this->view('public/guest-registration', [
            'eventSlug' => $slug
        ]);
    }
}
?>