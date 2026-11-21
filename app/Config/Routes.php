<?php
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// rutas q no piden sesion 
$routes->get('/', 'Viajes::index');
$routes->get('panel', 'Viajes::index');


// Autenticación
$routes->get('login', 'Auth::index');
$routes->post('validarLogin', 'Auth::validarLogin');
$routes->get('registro', 'Auth::registro');
$routes->post('registro/guardar', 'Auth::registrar');
$routes->get('logout', 'Auth::logout');

// Rutas Protegidas por Sesión
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Perfil de Usuario
    $routes->post('perfil/actualizar', 'Perfil::actualizar');

    // panel de la gestion del chofer
    $routes->get('chofer/gestion', 'Chofer::gestion');

    // Vehículos (Chofer)
    $routes->post('vehiculos/insertar', 'Vehiculos::insertar');
    $routes->post('vehiculos/actualizar', 'Vehiculos::actualizar');
    $routes->get('vehiculos/eliminar/(:num)', 'Vehiculos::eliminar/$1');

    // viajes (Chofer)
    $routes->post('viajes/insertar', 'Viajes::insertar');
    $routes->post('viajes/actualizar', 'Viajes::actualizar');
    $routes->get('viajes/eliminar/(:num)', 'Viajes::eliminar/$1');
    
    // Zonas (Chofer)
    $routes->post('zonas/insertar', 'Zonas::insertar');
    $routes->post('zonas/actualizar', 'Zonas::actualizar');
    $routes->get('zonas/eliminar/(:num)', 'Zonas::eliminar/$1');

    // Consultas para chofer
    $routes->get('consultas', 'Consultas::index');
    $routes->post('consultas/guardar', 'Consultas::guardar');

      //reportes para chofer
    $routes->get('reportes', 'Reportes::index');

});