<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// --- Auth (accessible sans être connecté) ---
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::loginForm');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// --- Dashboard (connecté, tous rôles) ---
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// --- Zones par rôle (pour la suite du projet) ---
$routes->group('besoin', ['filter' => 'auth:manager'], function ($routes) {
    $routes->get('/',          'Besoin::index');
    $routes->get('create',     'Besoin::createForm');
    $routes->post('create',    'Besoin::create');
    $routes->get('(:num)',     'Besoin::show/$1');
});

$routes->group('validation', ['filter' => 'auth:rh'], function ($routes) {
    $routes->get('/',              'Validation::index');
    $routes->get('(:num)',         'Validation::show/$1');
    $routes->post('traiter/(:num)','Validation::traiter/$1');
});
$routes->group('dg', ['filter' => 'auth:dg'], function ($routes) {
    $routes->get('/',               'ValidationDG::index');
    $routes->get('(:num)',          'ValidationDG::show/$1');
    $routes->post('traiter/(:num)', 'ValidationDG::traiter/$1');
});



$routes->group('annonce', ['filter' => 'auth:rh'], function ($routes) {
    $routes->get('/',                 'Annonce::index');
    $routes->get('create/(:num)',     'Annonce::createForm/$1');
    $routes->post('create',           'Annonce::create');
    $routes->get('(:num)',            'Annonce::show/$1');
});