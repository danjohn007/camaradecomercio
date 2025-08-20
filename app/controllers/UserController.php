<?php
/**
 * Controlador de usuarios
 */

class UserController extends BaseController {
    
    public function index() {
        $this->requireRole('superadmin');
        
        // Placeholder - implementar gestión de usuarios
        $this->view('usuarios/index', [
            'usuarios' => []
        ]);
    }
}
?>