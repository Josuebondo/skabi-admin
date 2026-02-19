<?php

/**
 * ======================================================================
 * Configuration Application
 * ======================================================================
 */

return [
    'name' => env('NOM_APPLICATION', 'BMVC'),
    'env' => env('ENVIRONNEMENT', 'production'),
    'debug' => env('DEBOGAGE', false) === 'true',
    'url' => env('URL_APPLICATION', 'http://localhost'),
    'timezone' => env('FUSEAU_HORAIRE', 'UTC'),
    'locale' => env('LOCALE', 'fr'),
    'charset' => 'UTF-8',
    'key' => env('CLE_SECRETE', ''),
];
