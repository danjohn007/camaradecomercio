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
$router->add('eventos/cambiar-estado/(\d+)', 'EventController@changeStatus');
$router->add('usuarios', 'UserController@index');
$router->add('usuarios/crear', 'UserController@create');
$router->add('usuarios/editar/(\\d+)', 'UserController@edit');
$router->add('usuarios/eliminar/(\\d+)', 'UserController@delete');
$router->add('usuarios/cambiar-estado/(\\d+)', 'UserController@changeStatus');
$router->add('asistentes', 'AttendeeController@index');
$router->add('asistentes/export', 'AttendeeController@export');
$router->add('reportes', 'ReportController@index');
$router->add('reportes/export', 'ReportController@export');
$router->add('qr', 'QrController@index');
$router->add('qr/validar', 'QrController@validate');
$router->add('qr/historial', 'QrController@history');
$router->add('qr/stats', 'QrController@stats');
$router->add('perfil', 'ProfileController@index');
$router->add('perfil/editar', 'ProfileController@edit');
$router->add('configuracion', 'ProfileController@settings');
$router->add('configuracion/password', 'ProfileController@changePassword');

// Rutas públicas para registro de eventos
$router->add('evento/([a-zA-Z0-9\-]+)', 'PublicController@eventPage');
$router->add('registro/empresa/([a-zA-Z0-9\-]+)', 'PublicController@companyRegistration');
$router->add('registro/invitado/([a-zA-Z0-9\-]+)', 'PublicController@guestRegistration');
$router->add('registro/confirmacion/([a-zA-Z0-9\-]+)', 'PublicController@confirmation');
$router->add('historial-boletos', 'PublicController@ticketHistory');
$router->add('buscar-historial-boletos', 'PublicController@searchTicketHistory');

// Rutas API
$router->add('api/buscar-empresa', 'ApiController@buscarEmpresa');
$router->add('api/buscar-invitado', 'ApiController@buscarInvitado');
$router->add('api/registro-empresa', 'ApiController@registroEmpresa');
$router->add('api/registro-invitado', 'ApiController@registroInvitado');

// Ejecutar el router
$router->dispatch();
?>