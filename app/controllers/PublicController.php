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
}
?>