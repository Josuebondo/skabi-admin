<?php

namespace Core;

use Core\Routeur;
use Core\Requete;
use Core\Reponse;
use Core\Session;

/**
 * ======================================================================
 * Application - Le Kernel du Framework
 * ======================================================================
 * 
 * Responsabilités principales :
 * - Initialisation globale de l'app
 * - Chargement de la configuration
 * - Gestion des chemins
 * - Dispatcher les requêtes HTTP
 * - Gestion des erreurs
 */
class Application
{
    /**
     * Répertoire racine de l'application
     */
    protected string $racine;

    /**
     * Chemins importants
     */
    protected array $chemins = [];

    /**
     * Configuration chargée
     */
    protected array $config = [];

    /**
     * Instance du routeur
     */
    protected Routeur $routeur;

    /**
     * Instance de la requête
     */
    protected Requete $requete;

    /**
     * Instance de la réponse
     */
    protected Reponse $reponse;

    /**
     * Instance de la session
     */
    protected Session $session;

    /**
     * Constructeur
     * 
     * @param string $racine Chemin racine de l'application
     */
    public function __construct(string $racine)
    {
        $this->racine = $racine;

        // Enregistrer l'instance globalement pour les helpers
        $GLOBALS['application'] = $this;

        // Initialiser
        $this->definirChemins();
        $this->chargerConfiguration();
        $this->initialiserSession();
        $this->initialiserRequeteReponse();
        $this->initialiserRouteur();
    }

    /**
     * Démarre l'application
     * Charge les routes et dispatch la requête
     */
    public function demarrer(): void
    {
        try {
            // Charger les routes
            $this->chargerRoutes();

            // Dispatcher la requête
            $this->routeur->dispatcher($this->requete, $this->reponse);
        } catch (\Throwable $e) {
            // Gestion des erreurs (Exception et Error)
            $this->gererErreur($e);
        }
    }

    /**
     * Définit les chemins principaux de l'application
     */
    protected function definirChemins(): void
    {
        $this->chemins = [
            'racine' => $this->racine,
            'app' => $this->racine . '/app',
            'core' => $this->racine . '/core',
            'public' => $this->racine . '/public',
            'routes' => $this->racine . '/routes',
            'config' => $this->racine . '/config',
            'stockage' => $this->racine . '/stockage',
            'vues' => $this->racine . '/app/Vues',
            'logs' => $this->racine . '/stockage/logs',
            'cache' => $this->racine . '/stockage/cache',
        ];
    }

    /**
     * Charge les fichiers de configuration
     */
    protected function chargerConfiguration(): void
    {
        $configDir = $this->chemins['config'];

        if (!is_dir($configDir)) {
            throw new \Exception("Dossier config introuvable: $configDir");
        }

        foreach (glob($configDir . '/*.php') as $fichier) {
            $nom = basename($fichier, '.php');
            $this->config[$nom] = require $fichier;
        }
    }

    /**
     * Initialise la session
     */
    protected function initialiserSession(): void
    {
        $this->session = new Session();
    }

    /**
     * Initialise la requête et la réponse
     */
    protected function initialiserRequeteReponse(): void
    {
        $this->requete = new Requete($_SERVER, $_GET, $_POST, $_FILES ?? []);
        $this->reponse = new Reponse();
    }

    /**
     * Initialise le routeur
     */
    protected function initialiserRouteur(): void
    {
        $this->routeur = new Routeur();
    }

    /**
     * Charge les routes définies dans routes/web.php
     */
    protected function chargerRoutes(): void
    {
        $fichierRoutes = $this->chemins['routes'] . '/web.php';

        if (!file_exists($fichierRoutes)) {
            throw new \Exception("Fichier routes/web.php introuvable");
        }

        require $fichierRoutes;
    }

    /**
     * Gère les erreurs non capturées
     */
    protected function gererErreur(\Throwable $e): void
    {
        // Nettoyer tout ce qui a pu être affiché
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $debug = env('DEBOGAGE', false) === 'true';

        // Gérer les erreurs 404
        if ($e instanceof \Core\Exceptions\HttpNotFoundException) {
            http_response_code(404);

            if ($debug) {
                // Mode développement : afficher la page technique
                $this->afficherErreurDev($e);
            } else {
                // Mode production : afficher la belle page 404
                $vue = new Vue($this->chemins['vues']);
                $vue->afficher('erreur', [
                    'code' => 404,
                    'message' => 'Impossible de trouver cette page.'
                ]);
            }
            exit;
        }

        if ($debug) {
            // Mode développement : afficher les détails
            $this->afficherErreurDev($e);
        } else {
            // Mode production : page d'erreur simple
            $this->afficherErreurProd();
        }

        // Logger l'erreur
        $this->logerErreur($e);

        // Arrêter l'exécution
        exit;
    }

    /**
     * Affiche les détails de l'erreur (développement)
     */
    protected function afficherErreurDev(\Throwable $e): void
    {
        // Désactiver l'affichage des erreurs PHP
        ini_set('display_errors', 0);
        http_response_code(500);
?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Erreur - BMVC</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    background: #f5f5f5;
                    margin: 0;
                    padding: 20px;
                }

                .container {
                    max-width: 900px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                    padding: 30px;
                }

                h1 {
                    color: #d32f2f;
                    margin-top: 0;
                }

                .message {
                    background: #ffebee;
                    border-left: 4px solid #d32f2f;
                    padding: 15px;
                    margin: 20px 0;
                    border-radius: 4px;
                }

                .stack {
                    background: #f5f5f5;
                    padding: 15px;
                    border-radius: 4px;
                    overflow-x: auto;
                    font-family: monospace;
                    font-size: 0.85em;
                    line-height: 1.5;
                }

                .file {
                    color: #d32f2f;
                    font-weight: bold;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div style="text-align: center; margin-bottom: 30px;">
                    <svg width="100" height="100" viewBox="0 0 120 120" style="animation: bounce 1.5s infinite;" xmlns="http://www.w3.org/2000/svg">
                        <!-- Tête -->
                        <circle cx="60" cy="50" r="35" fill="#FFD700" stroke="#333" stroke-width="2" />
                        <!-- Yeux qui rigolent -->
                        <circle cx="45" cy="40" r="6" fill="#333" />
                        <circle cx="75" cy="40" r="6" fill="#333" />
                        <path d="M 40 48 Q 45 52 50 48" stroke="#333" stroke-width="2" fill="none" stroke-linecap="round" />
                        <path d="M 70 48 Q 75 52 80 48" stroke="#333" stroke-width="2" fill="none" stroke-linecap="round" />
                        <!-- Grand sourire -->
                        <path d="M 45 58 Q 60 70 75 58" stroke="#333" stroke-width="3" fill="none" stroke-linecap="round" />
                    </svg>
                    <h2 style="color: #d32f2f; margin: 10px 0;">Ah ah... t'as eu un bug! 😂</h2>
                    <p style="color: #666; font-size: 14px; margin: 10px 0;">Mais ne t'inquiète pas, tu vas corriger ça en un clin d'oeil! 💪</p>
                </div>

                <h1>⚠️ Erreur</h1>

                <div class="message">
                    <strong>Message:</strong><br>
                    <?= htmlspecialchars($e->getMessage()) ?>
                </div>

                <div class="message">
                    <strong>Fichier:</strong><br>
                    <span class="file"><?= htmlspecialchars($e->getFile()) ?></span>
                    <br><strong>Ligne:</strong> <?= $e->getLine() ?>
                </div>

                <h3>Stack Trace</h3>
                <div class="stack">
                    <?php foreach ($e->getTrace() as $i => $trace): ?>
                        <div style="margin-bottom: 15px;">
                            <strong>#<?= $i ?></strong><br>
                            <?= htmlspecialchars($trace['file'] ?? 'unknown') ?>
                            [<?= $trace['line'] ?? '?' ?>]<br>
                            <?= htmlspecialchars($trace['class'] ?? '') ?>
                            <?= $trace['type'] ?? '' ?>
                            <?= htmlspecialchars($trace['function'] ?? '(anonymous)') ?>
                            (...)
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </body>

        </html>
    <?php
    }

    /**
     * Affiche une page d'erreur simple (production)
     */
    protected function afficherErreurProd(): void
    {
        // Désactiver l'affichage des erreurs PHP
        ini_set('display_errors', 0);
        http_response_code(500);
    ?>
        <!DOCTYPE html>
        <html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>500 - Erreur Serveur</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .container {
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    max-width: 700px;
                    width: 100%;
                    padding: 80px 50px;
                    text-align: center;
                }

                .code {
                    font-size: 140px;
                    font-weight: 900;
                    background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    margin: 0;
                    line-height: 1;
                }

                .title {
                    font-size: 32px;
                    color: #333;
                    margin: 30px 0 20px;
                    font-weight: 600;
                }

                .message {
                    font-size: 18px;
                    color: #888;
                    margin-bottom: 50px;
                    line-height: 1.6;
                }

                .icon {
                    font-size: 100px;
                    margin: 30px 0;
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {

                    0%,
                    100% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-20px);
                    }
                }

                .actions {
                    display: flex;
                    gap: 20px;
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .btn {
                    display: inline-block;
                    padding: 14px 35px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    border: none;
                    cursor: pointer;
                    font-size: 15px;
                }

                .btn-primary {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }

                .btn-primary:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
                }

                .btn-secondary {
                    background: #f0f0f0;
                    color: #333;
                    border: 2px solid #e0e0e0;
                }

                .btn-secondary:hover {
                    background: #e8e8e8;
                    border-color: #d0d0d0;
                    transform: translateY(-3px);
                }

                .footer {
                    margin-top: 60px;
                    padding-top: 30px;
                    border-top: 1px solid #e0e0e0;
                    font-size: 13px;
                    color: #aaa;
                }

                .footer p {
                    margin: 8px 0;
                }

                .footer a {
                    color: #667eea;
                    text-decoration: none;
                }

                @media (max-width: 600px) {
                    .container {
                        padding: 60px 30px;
                    }

                    .code {
                        font-size: 100px;
                    }

                    .icon {
                        font-size: 70px;
                    }

                    .title {
                        font-size: 24px;
                    }

                    .message {
                        font-size: 16px;
                    }

                    .actions {
                        flex-direction: column;
                    }

                    .btn {
                        width: 100%;
                    }
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div class="icon">⚠️</div>

                <h1 class="code">500</h1>

                <div class="title">Erreur Interne du Serveur</div>

                <p class="message">
                    Quelque chose s'est mal passé sur le serveur.<br>
                    Nos équipes sont notifiées du problème.
                </p>

                <div class="actions">
                    <a href="/" class="btn btn-primary">
                        🏠 Retour à l'accueil
                    </a>
                    <button onclick="history.back()" class="btn btn-secondary">
                        ← Page Précédente
                    </button>
                </div>

                <div class="footer">
                    <p>BMVC Framework © 2026</p>
                    <p>Référence: <?= uniqid('ERR_') ?></p>
                </div>
            </div>
        </body>

        </html>
<?php
    }

    /**
     * Log l'erreur dans un fichier
     */
    protected function logerErreur(\Exception $e): void
    {
        $logsDir = $this->chemins['logs'];

        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }

        $logFile = $logsDir . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        $message = "[{$timestamp}] {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}\n";

        file_put_contents($logFile, $message, FILE_APPEND);
    }

    // ===== GETTERS & SETTERS =====

    /**
     * Obtient un chemin de l'application
     */
    public function chemin(string $cle): string
    {
        return $this->chemins[$cle] ?? '';
    }

    /**
     * Obtient une valeur de configuration
     */
    public function config(string $cle, mixed $default = null): mixed
    {
        [$fichier, $key] = explode('.', $cle, 2) + ['', ''];

        if (!isset($this->config[$fichier])) {
            return $default;
        }

        if (empty($key)) {
            return $this->config[$fichier];
        }

        return $this->config[$fichier][$key] ?? $default;
    }

    /**
     * Obtient la requête actuelle
     */
    public function requete(): Requete
    {
        return $this->requete;
    }

    /**
     * Obtient la réponse actuelle
     */
    public function reponse(): Reponse
    {
        return $this->reponse;
    }

    /**
     * Obtient la session actuelle
     */
    public function session(): Session
    {
        return $this->session;
    }

    /**
     * Obtient le routeur
     */
    public function routeur(): Routeur
    {
        return $this->routeur;
    }
}
