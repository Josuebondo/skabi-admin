<?php

/**
 * ======================================================================
 * Routes Web - Framework BMVC Production
 * ======================================================================
 */

use Core\Middlewares\MiddlewareAdmin;
use Core\Routeur;
use Core\Middlewares\MiddlewareAuth;
// Route accueil
Routeur::obtenir('/', 'AccueilControleur@index')->nom('accueil');

// Route page de démarrage
Routeur::obtenir('/demarrage', 'DémarrageControlleur@index')->nom('démarrage');

// Route page de documentation
Routeur::obtenir('/documentation', 'DocumentationControleur@index')->nom('documentation');
//Route page de mouvement avec middleware d'authentification
Routeur::obtenir('/mouvements', 'MouvementControleur@index')->middleware(MiddlewareAuth::class)->middleware(MiddlewareAdmin::class)->nom('mouvement');


// Route page de connexion
Routeur::obtenir('/login', 'AuthControleur@index')->nom('login');
Routeur::publier('/login', 'AuthControleur@login')->nom('login.post');

Routeur::obtenir('/logout', 'AuthControleur@logout')->nom('logout');
