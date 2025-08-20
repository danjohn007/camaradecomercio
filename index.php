<?php
/**
 * Sistema de Eventos CANACO
 * Punto de entrada principal del sistema
 */

// Configuración inicial
require_once 'config/config.php';
require_once 'app/core/Router.php';
require_once 'app/core/Database.php';
require_once 'app/core/BaseController.php';

// Inicializar el router
$router = new Router();

// Definir rutas del sistema
$router->add('', 'HomeController@index');
$router->add('login', 'AuthController@login');
$router->add('logout', 'AuthController@logout');
$router->add('dashboard', 'DashboardController@index');
$router->add('eventos', 'EventController@index');
$router->add('eventos/crear', 'EventController@create');
$router->add('eventos/editar/(\d+)', 'EventController@edit');
$router->add('eventos/eliminar/(\d+)', 'EventController@delete');
$router->add('usuarios', 'UserController@index');
$router->add('asistentes', 'AttendeeController@index');
$router->add('reportes', 'ReportController@index');

// Rutas públicas para registro de eventos
$router->add('evento/([a-zA-Z0-9\-]+)', 'PublicController@eventPage');
$router->add('registro/empresa/([a-zA-Z0-9\-]+)', 'PublicController@companyRegistration');
$router->add('registro/invitado/([a-zA-Z0-9\-]+)', 'PublicController@guestRegistration');

// Ejecutar el router
$router->dispatch();
?>