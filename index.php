<?php

// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// 1. Configuration
define('APP_PATH', dirname(__DIR__));

// 2. Charger l'autoload Composer
try {
    require_once APP_PATH . '/vendor/autoload.php';
} catch (Exception $e) {
    echo "❌ Erreur lors du chargement de l'autoload: " . $e->getMessage();
    exit;
}

// 3. Charger les variables d'environnement
\Core\Env::charger(APP_PATH . '/.env');

// Debug: Afficher les informations de la requête si DEBOGAGE est actif
if (env('DEBOGAGE') === 'true') {
    error_log("=== REQUÊTE HTTP ===");
    error_log("REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET'));
    error_log("REQUEST_METHOD: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET'));
    error_log("SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET'));
    error_log("SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'NOT SET'));
    error_log("PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET'));
}

// 4. Créer et démarrer l'application
try {
    $app = new \Core\Application(APP_PATH);
    $app->demarrer();
} catch (Exception $e) {
    echo "❌ Erreur Application: " . $e->getMessage();
    echo "<br>File: " . $e->getFile();
    echo "<br>Line: " . $e->getLine();
    echo "<br><pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}
