<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('login', 'AuthController::login');
$routes->post('register', 'AuthController::register');

$routes->group('content', ['filter' => 'jwt'], function($routes) {
    $routes->get('', 'ContentController::index');
    $routes->post('', 'ContentController::create');
    $routes->put('(:segment)', 'ContentController::update/$1');
    $routes->delete('(:segment)', 'ContentController::delete/$1');
});
