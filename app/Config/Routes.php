<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->post('connexion', 'Auth::connexion');
$routes->get('accueil', 'Auth::accueil');
$routes->get('besoin', 'Besoin::formulaire');
$routes->post('besoin/enregistrer', 'Besoin::enregistrer');

$routes->get('rh', 'Besoin::rh');
$routes->post('rh/traiter', 'Besoin::traiterRh');
$routes->get('dg', 'Besoin::dg');
$routes->post('dg/traiter', 'Besoin::traiterDg');


$routes->get('annonces', 'Besoin::annonces');
$routes->post('annonces/creer', 'Besoin::creerAnnonce');
$routes->get('annonces/liste', 'Besoin::listeAnnonces');




$routes->get('candidat/login', 'Auth::loginCandidat');
$routes->get('candidat/inscription', 'Auth::inscriptionCandidat');

$routes->post('candidat/enregistrer', 'Auth::enregistrerCandidat');
$routes->post('candidat/connexion', 'Auth::connexionCandidat');
$routes->post('candidat/postuler', 'Auth::postuler');
$routes->get('candidat/cv/(:num)', 'Auth::formulaireCV/$1');



$routes->get('candidat/accueil', 'Auth::accueilCandidat');
$routes->get('candidat/logout', 'Auth::logoutCandidat');