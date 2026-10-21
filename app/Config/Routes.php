<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// --- Auth (accessible sans être connecté) ---
// --- Public ---
$routes->get('/',              'Auth::index');
$routes->get('/annonces',      'Annonce::publiques');
$routes->get('/annonces/(:num)','Annonce::publique/$1');

// --- Auth interne (existant, inchangé) ---
$routes->get('/login',  'Auth::loginForm');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// --- Candidat ---
$routes->get('/candidat/login',    'Candidat::loginForm');
$routes->post('/candidat/login',   'Candidat::login');
$routes->get('/candidat/register', 'Candidat::registerForm');
$routes->post('/candidat/register','Candidat::register');
$routes->get('/candidat/logout',   'Candidat::logout');
$routes->get('/candidat/dashboard','Candidat::dashboard', ['filter' => 'auth_candidat']);
$routes->get('/candidat/candidature/(:num)',        'Candidat::detail/$1', ['filter' => 'auth_candidat']);
$routes->post('/candidat/candidature/(:num)/action','Candidat::action/$1', ['filter' => 'auth_candidat']);

// --- Postuler (candidat connecté) ---
$routes->get('/postuler/(:num)',  'Postuler::form/$1',       ['filter' => 'auth_candidat']);
$routes->post('/postuler/(:num)', 'Postuler::enregistrer/$1',['filter' => 'auth_candidat']);

// --- RH existant : besoin, validation, annonce, dg ---
$routes->group('besoin', ['filter' => 'auth:manager'], function ($routes) {
    $routes->get('/',        'Besoin::index');
    $routes->get('create',   'Besoin::createForm');
    $routes->post('create',  'Besoin::create');
    $routes->get('(:num)',   'Besoin::show/$1');
});

$routes->group('validation', ['filter' => 'auth:rh'], function ($routes) {
    $routes->get('/',               'Validation::index');
    $routes->get('(:num)',          'Validation::show/$1');
    $routes->post('traiter/(:num)', 'Validation::traiter/$1');
});

$routes->group('annonce', ['filter' => 'auth:rh'], function ($routes) {
    $routes->get('/',               'Annonce::index');
    $routes->get('create/(:num)',   'Annonce::createForm/$1');
    $routes->post('create',         'Annonce::create');
    $routes->get('(:num)',          'Annonce::show/$1');
});

$routes->group('candidature', ['filter' => 'auth:rh'], function ($routes) {
    $routes->get('/',                'Candidature::index');
    $routes->get('(:num)',           'Candidature::show/$1');
    $routes->post('(:num)/action',   'Candidature::action/$1');
});

$routes->group('emails', ['filter' => 'auth:rh'], function ($routes) {
    $routes->get('/',       'Emails::index');
    $routes->get('(:num)',  'Emails::show/$1');
});

$routes->group('dg', ['filter' => 'auth:dg'], function ($routes) {
    $routes->get('/',               'ValidationDG::index');
    $routes->get('(:num)',          'ValidationDG::show/$1');
    $routes->post('traiter/(:num)', 'ValidationDG::traiter/$1');
});