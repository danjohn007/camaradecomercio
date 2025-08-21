<?php
/**
 * Configuración principal del sistema
 */

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercito_reservaciones');
define('DB_USER', 'ejercito_reservaciones');
define('DB_PASS', 'Danjohn007');

// Configuración de la aplicación
define('BASE_URL', '/reservaciones/');
define('APP_NAME', 'Sistema de Eventos CANACO');
define('APP_VERSION', '1.0.0');

// Configuración de email
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USER', '');
define('MAIL_PASS', '');
define('MAIL_FROM', 'eventos@canaco.org.mx');
define('MAIL_FROM_NAME', 'CANACO Eventos');

// Configuración de seguridad
define('SECRET_KEY', 'canaco_eventos_2024_secret_key');
define('SESSION_TIMEOUT', 7200); // 2 horas

// Configuración de archivos
define('UPLOAD_PATH', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Configuración de errores (desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Autoloader básico
spl_autoload_register(function($class) {
    $paths = [
        'app/core/',
        'app/controllers/',
        'app/models/',
        'app/helpers/',
        'vendor/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>
