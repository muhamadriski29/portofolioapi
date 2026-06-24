<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Catch-all route untuk menangkap semua preflight OPTIONS request
// Ini memastikan CI4 tidak mengembalikan 404/405 sebelum filter CORS berjalan
$routes->options('(:any)', static function () {
    return service('response')->setStatusCode(200);
});

$routes->resource('api/projects', ['controller' => 'Api\Projects']);
