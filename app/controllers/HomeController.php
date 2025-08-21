<?php
/**
 * Controlador de inicio/público
 */

class HomeController extends BaseController {
    
    public function index() {
        // Si está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        // Si no está autenticado, redirigir al login
        $this->redirect('login');
    }
}
?>