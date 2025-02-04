<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return file_get_contents(FCPATH . 'index.html');
});


$routes->post('/register_user', 'RegisterUser::index');
$routes->post('/login_user', 'LoginUser::index');
$routes->get('/public_url', 'PublicController::index');

