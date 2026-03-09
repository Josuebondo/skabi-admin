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
// document
Routeur::obtenir('/documents', 'documentControleur@index')->nom('document');
Routeur::obtenir('/documents/creer', 'documentControleur@creer')->nom('document.creer');
Routeur::publier('/documents/creer', 'documentControleur@enregistrer')->nom('document.envoyer');
Routeur::obtenir('/documents/{id}/editer', 'documentControleur@editer')->ou('id', '[0-9]+')->nom('document.editer');
Routeur::publier('/documents/{id}/editer', 'documentControleur@mettreAJour')->ou('id', '[0-9]+')->nom('document.mettre');
Routeur::obtenir('/documents/{id}/supprimer', 'documentControleur@supprimer')->ou('id', '[0-9]+')->nom('document.supprimer');


//route inventaire
Routeur::obtenir('/inventaire', 'documentControleur@inv')->nom('inventaire');
// article
Routeur::obtenir('/articles', 'articleControleur@index')->nom('article');

//api 
Routeur::obtenir('/api/articles', 'articleControleur@getAll')->nom('article.api');

Routeur::obtenir('/articles/creer', 'articleControleur@creer')->nom('article.creer');
Routeur::publier('/articles/creer', 'articleControleur@enregistrer')->nom('article.envoyer');
Routeur::obtenir('/articles/{id}/editer', 'articleControleur@editer')->ou('id', '[0-9]+')->nom('article.editer');
Routeur::publier('/articles/{id}/editer', 'articleControleur@mettreAJour')->ou('id', '[0-9]+')->nom('article.mettre');
Routeur::obtenir('/articles/{id}/supprimer', 'articleControleur@supprimer')->ou('id', '[0-9]+')->nom('article.supprimer');


Routeur::obtenir('/dashboard', 'dashboardControleur@index')->nom('dashbord.gerent');
Routeur::obtenir('/test', 'dashboardControleur@test')->nom('dashbord.test');
// versement
Routeur::obtenir('/versements', 'versementControleur@index')->nom('versement');
Routeur::obtenir('/versements/creer', 'versementControleur@creer')->nom('versement.creer');
Routeur::publier('/versements/creer', 'versementControleur@enregistrer')->nom('versement.envoyer');
Routeur::obtenir('/versements/{id}/editer', 'versementControleur@editer')->ou('id', '[0-9]+')->nom('versement.editer');
Routeur::publier('/versements/{id}/editer', 'versementControleur@mettreAJour')->ou('id', '[0-9]+')->nom('versement.mettre');
Routeur::obtenir('/versements/{id}/supprimer', 'versementControleur@supprimer')->ou('id', '[0-9]+')->nom('versement.supprimer');
