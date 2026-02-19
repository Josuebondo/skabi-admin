<?php

/**
 * ======================================================================
 * Configuration Base de Données
 * ======================================================================
 */

return [
    'connection' => env('TYPE_CONNEXION', 'sqlite'),
    'host' => env('HOTE_BD', 'localhost'),
    'port' => env('PORT_BD', 3306),
    'database' => env('NOM_BD', 'bmvc'),
    'username' => env('UTILISATEUR_BD', 'root'),
    'password' => env('MOT_DE_PASSE_BD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
];
