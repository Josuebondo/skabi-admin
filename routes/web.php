<?php

/**
 * ======================================================================
 * Routes Web - Framework BMVC Production
 * ======================================================================
 */

use Core\Routeur;

// Route accueil
Routeur::obtenir('/', 'AccueilControleur@index')->nom('accueil');

// Route page de démarrage
Routeur::obtenir('/demarrage', 'DémarrageControlleur@index')->nom('démarrage');

// Route page de documentation
Routeur::obtenir('/documentation', 'DocumentationControleur@index')->nom('documentation');
//Route page de mouvement
Routeur::obtenir('/mouvements', 'MouvementControleur@index')->nom('mouvements');
