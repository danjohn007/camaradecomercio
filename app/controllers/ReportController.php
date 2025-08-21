<?php
/**
 * Controlador de reportes
 */

class ReportController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        // Placeholder - implementar reportes
        $this->view('reportes/index', [
            'reportes' => []
        ]);
    }
}
?>