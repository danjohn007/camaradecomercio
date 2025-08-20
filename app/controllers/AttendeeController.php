<?php
/**
 * Controlador de asistentes
 */

class AttendeeController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Placeholder - implementar gestión de asistentes
        $this->view('asistentes/index', [
            'asistentes' => []
        ]);
    }
}
?>