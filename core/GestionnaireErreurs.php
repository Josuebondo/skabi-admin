<?php

namespace Core;

/**
 * Gestionnaire d'Erreurs et Exceptions
 * Mode dev/prod, pages 404/500, logs
 */
class GestionnaireErreurs
{
    private static bool $modeDebug = true;
    private static string $cheminLogs = '';

    /**
     * Initialise le gestionnaire d'erreurs
     */
    public static function initialiser(bool $debug = true, string $cheminLogs = ''): void
    {
        self::$modeDebug = $debug;
        self::$cheminLogs = $cheminLogs ?: __DIR__ . '/../storage/logs/';

        // Crée le dossier logs s'il n'existe pas
        if (!is_dir(self::$cheminLogs)) {
            mkdir(self::$cheminLogs, 0755, true);
        }

        // Enregistre les handlers
        set_error_handler([self::class, 'gererErreur']);
        set_exception_handler([self::class, 'gererException']);
        register_shutdown_function([self::class, 'gererArret']);
    }

    /**
     * Gère les erreurs PHP
     */
    public static function gererErreur(int $niveau, string $message, string $fichier, int $ligne): bool
    {
        self::enregistrer($message, $fichier, $ligne, 'ERROR');

        if (self::$modeDebug) {
            self::afficherErreur($message, $fichier, $ligne, $niveau);
        } else {
            self::afficherPageErreur(500);
        }

        return true;
    }

    /**
     * Gère les exceptions
     */
    public static function gererException(\Throwable $exception): void
    {
        self::enregistrer(
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            'EXCEPTION'
        );

        if (self::$modeDebug) {
            self::afficherException($exception);
        } else {
            self::afficherPageErreur(500);
        }
    }

    /**
     * Gère l'arrêt du script
     */
    public static function gererArret(): void
    {
        $erreur = error_get_last();

        if ($erreur !== null && in_array($erreur['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::enregistrer(
                $erreur['message'],
                $erreur['file'],
                $erreur['line'],
                'FATAL'
            );

            if (!self::$modeDebug) {
                self::afficherPageErreur(500);
            }
        }
    }

    /**
     * Enregistre une erreur dans le log
     */
    private static function enregistrer(string $message, string $fichier, int $ligne, string $type): void
    {
        $contenu = sprintf(
            "[%s] [%s] %s | Fichier: %s (ligne %d)\n",
            date('Y-m-d H:i:s'),
            $type,
            $message,
            $fichier,
            $ligne
        );

        $nomFichier = self::$cheminLogs . 'erreurs-' . date('Y-m-d') . '.log';
        file_put_contents($nomFichier, $contenu, FILE_APPEND);
    }

    /**
     * Affiche une erreur en mode debug
     */
    private static function afficherErreur(string $message, string $fichier, int $ligne, int $niveau): void
    {
        $niveaux = [
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated',
        ];

        $typeNom = $niveaux[$niveau] ?? 'Unknown';

        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Erreur: $typeNom</title>
            <style>
                body { font-family: Segoe UI, Tahoma, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
                .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { border-bottom: 3px solid #dc3545; padding-bottom: 15px; margin-bottom: 20px; }
                .type { font-size: 24px; font-weight: bold; color: #dc3545; }
                .message { font-size: 16px; color: #333; margin: 10px 0 20px 0; }
                .trace { background: #f9f9f9; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 13px; }
                .file { color: #0066cc; margin: 5px 0; }
                .code { background: #fff; padding: 10px; margin: 10px 0; border-radius: 4px; overflow-x: auto; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="type">⚠️ $typeNom</div>
                    <div class="message">$message</div>
                </div>
                <div class="trace">
                    <strong>Fichier:</strong>
                    <div class="file">$fichier</div>
                    <strong>Ligne:</strong> $ligne
                </div>
            </div>
        </body>
        </html>
HTML;
    }

    /**
     * Affiche une exception en mode debug
     */
    private static function afficherException(\Throwable $exception): void
    {
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Exception</title>
            <style>
                body { font-family: Segoe UI, Tahoma, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
                .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                .header { border-bottom: 3px solid #dc3545; padding-bottom: 15px; margin-bottom: 20px; }
                .type { font-size: 24px; font-weight: bold; color: #dc3545; }
                .message { font-size: 16px; color: #333; margin: 10px 0 20px 0; }
                .trace { background: #f9f9f9; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 12px; }
                .file { color: #0066cc; margin: 5px 0; }
                .stack-item { margin: 10px 0; padding: 10px; background: white; border-left: 3px solid #ddd; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <div class="type">❌ {$exception->getClass()}</div>
                    <div class="message">{$exception->getMessage()}</div>
                </div>
                <div class="trace">
                    <strong>Fichier:</strong>
                    <div class="file">{$exception->getFile()} (ligne {$exception->getLine()})</div>
                    <strong>Stack Trace:</strong>
                    <pre>{$exception->getTraceAsString()}</pre>
                </div>
            </div>
        </body>
        </html>
HTML;
    }

    /**
     * Affiche une page d'erreur personnalisée
     */
    private static function afficherPageErreur(int $statut): void
    {
        http_response_code($statut);

        $titre = $statut === 404 ? 'Page non trouvée' : 'Erreur serveur';
        $message = $statut === 404
            ? 'La page que vous recherchez n\'existe pas.'
            : 'Une erreur s\'est produite. Veuillez réessayer plus tard.';

        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Erreur $statut</title>
            <style>
                body { 
                    font-family: Segoe UI, Tahoma, sans-serif; 
                    margin: 0; 
                    padding: 20px; 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                }
                .container { 
                    background: white; 
                    padding: 50px; 
                    border-radius: 10px; 
                    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                    text-align: center;
                    max-width: 500px;
                }
                .numero { 
                    font-size: 80px; 
                    font-weight: bold; 
                    color: #667eea;
                    margin: 0;
                }
                .titre { 
                    font-size: 24px; 
                    color: #333; 
                    margin: 20px 0;
                }
                .message { 
                    color: #666; 
                    font-size: 16px; 
                    margin: 20px 0;
                }
                .retour { 
                    display: inline-block;
                    margin-top: 30px;
                    padding: 12px 30px;
                    background: #667eea;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    font-weight: bold;
                    transition: background 0.3s;
                }
                .retour:hover {
                    background: #764ba2;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1 class="numero">$statut</h1>
                <h2 class="titre">$titre</h2>
                <p class="message">$message</p>
                <a href="/" class="retour">Retour à l'accueil</a>
            </div>
        </body>
        </html>
HTML;
    }
}
