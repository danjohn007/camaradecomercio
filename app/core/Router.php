<?php
/**
 * Router del sistema
 * Maneja las rutas amigables y dispatching
 */

class Router {
    private $routes = [];
    
    public function add($pattern, $handler) {
        $this->routes[$pattern] = $handler;
    }
    
    public function dispatch() {
        $url = $_GET['url'] ?? '';
        $url = rtrim($url, '/');
        
        foreach ($this->routes as $pattern => $handler) {
            if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                $this->callHandler($handler, array_slice($matches, 1));
                return;
            }
        }
        
        // Ruta no encontrada
        http_response_code(404);
        $this->callHandler('ErrorController@notFound');
    }
    
    private function callHandler($handler, $params = []) {
        list($controller, $method) = explode('@', $handler);
        
        $controllerFile = "app/controllers/{$controller}.php";
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                
                if (method_exists($controllerInstance, $method)) {
                    call_user_func_array([$controllerInstance, $method], $params);
                } else {
                    throw new Exception("Método {$method} no encontrado en {$controller}");
                }
            } else {
                throw new Exception("Controlador {$controller} no encontrado");
            }
        } else {
            throw new Exception("Archivo del controlador {$controllerFile} no encontrado");
        }
    }
}
?>