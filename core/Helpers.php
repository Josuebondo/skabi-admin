<?php

/**
 * ======================================================================
 * Helpers - Fonctions Globales
 * ======================================================================
 * 
 * Fonctions utilitaires disponibles partout dans l'application
 */

if (!function_exists('env')) {
    /**
     * Obtient une variable d'environnement depuis le fichier .env
     */
    function env(string $cle, mixed $default = null): mixed
    {
        return \Core\Env::obtenir($cle, $default);
    }
}

if (!function_exists('config')) {
    /**
     * Obtient une valeur de configuration
     */
    function config(string $cle, mixed $default = null): mixed
    {
        $app = $GLOBALS['application'] ?? null;
        if (!$app) {
            return $default;
        }
        return $app->config($cle, $default);
    }
}

if (!function_exists('chemin')) {
    /**
     * Obtient un chemin de l'application
     */
    function chemin(string $cle): string
    {
        $app = $GLOBALS['application'] ?? null;
        if (!$app) {
            return '';
        }
        return $app->chemin($cle);
    }
}

if (!function_exists('url')) {
    /**
     * Génère une URL
     */
    function url(string $chemin = ''): string
    {
        $baseUrl = env('URL_APPLICATION', 'http://localhost');
        return rtrim($baseUrl, '/') . '/' . ltrim($chemin, '/');
    }
}

if (!function_exists('vue')) {
    /**
     * Crée et rend une vue
     */
    function vue(string $vue, array $donnees = []): string
    {
        $app = $GLOBALS['application'] ?? null;
        if (!$app) {
            return '';
        }
        $vueInstance = new \Core\Vue($app->chemin('vues'));
        return $vueInstance->rendre($vue, $donnees);
    }
}

if (!function_exists('redirection')) {
    /**
     * Redirige vers une URL
     */
    function redirection(string $url, int $statut = 302): void
    {
        $app = $GLOBALS['application'] ?? null;
        if ($app) {
            $app->reponse()->redirection($url, $statut);
        }
    }
}

if (!function_exists('json')) {
    /**
     * Retourne une réponse JSON
     */
    function json(array $donnees, int $statut = 200): void
    {
        $app = $GLOBALS['application'] ?? null;
        if ($app) {
            $app->reponse()->json($donnees, $statut);
        }
    }
}

if (!function_exists('dd')) {
    /**
     * Debug et dump avec die
     */
    function dd(mixed ...$vars): void
    {
        echo '<pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; font-family: monospace;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('dump')) {
    /**
     * Dump sans die
     */
    function dump(mixed ...$vars): void
    {
        echo '<pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; font-family: monospace;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
    }
}

if (!function_exists('debut_section')) {
    /**
     * Débute une section de vue
     */
    function debut_section(string $nom): void
    {
        \Core\Vue::debut_section($nom);
    }
}

if (!function_exists('fin_section')) {
    /**
     * Termine une section de vue
     */
    function fin_section(string $nom): void
    {
        \Core\Vue::fin_section($nom);
    }
}

if (!function_exists('section')) {
    /**
     * Affiche le contenu d'une section
     */
    function section(string $nom, string $defaut = ''): void
    {
        \Core\Vue::section($nom, $defaut);
    }
}

if (!function_exists('etendre')) {
    /**
     * Définit le layout parent
     */
    function etendre(string $layout): void
    {
        \Core\Vue::extends($layout);
    }
}

if (!function_exists('e')) {
    /**
     * Échappe une valeur (protection XSS)
     */
    function e($valeur): string
    {
        return \Core\Vue::e($valeur);
    }
}

if (!function_exists('input')) {
    /**
     * Obtient un input POST/GET
     */
    function input(string $cle, $defaut = null)
    {
        return $_REQUEST[$cle] ?? $defaut;
    }
}

if (!function_exists('ancien')) {
    /**
     * Obtient une valeur ancien input
     */
    function ancien(string $cle, $defaut = ''): string
    {
        return htmlspecialchars($_SESSION['anciens_inputs'][$cle] ?? $defaut, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('flash')) {
    /**
     * Récupère un message flash
     */
    function flash(string $type = null): ?string
    {
        if ($type === null) {
            $flash = $_SESSION['flash'] ?? [];
            unset($_SESSION['flash']);
            return !empty($flash) ? current($flash) : null;
        }

        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
}

if (!function_exists('url')) {
    /**
     * Génère une URL de l'application
     */
    function url(string $chemin = ''): string
    {
        $base = getenv('URL_APPLICATION') ?: 'http://localhost';
        return $base . '/' . ltrim($chemin, '/');
    }
}

if (!function_exists('auth')) {
    /**
     * Récupère l'utilisateur connecté
     */
    function auth()
    {
        return \Core\Auth::utilisateur();
    }
}

if (!function_exists('est_connecte')) {
    /**
     * Vérifie si l'utilisateur est connecté
     */
    function est_connecte(): bool
    {
        return \Core\Auth::connecte();
    }
}

if (!function_exists('est_admin')) {
    /**
     * Vérifie si l'utilisateur est admin
     */
    function est_admin(): bool
    {
        return \Core\Auth::estAdmin();
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Récupère le token CSRF
     */
    function csrf_token(): string
    {
        return \Core\CSRF::token();
    }
}

if (!function_exists('csrf_input')) {
    /**
     * Génère un input CSRF
     */
    function csrf_input(): string
    {
        return \Core\CSRF::input();
    }
}

if (!function_exists('flash')) {
    /**
     * Enregistre un message flash
     */
    function flash(string $type, string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }
}

if (!function_exists('ancien')) {
    /**
     * Récupère une ancienne valeur d'input
     */
    function ancien(string $cle, string $default = ''): string
    {
        $anciens = $_SESSION['anciens_inputs'] ?? [];
        return htmlspecialchars($anciens[$cle] ?? $default, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('validateur')) {
    /**
     * Crée une nouvelle instance de Validateur
     */
    function validateur(): \Core\Validateur
    {
        return new \Core\Validateur();
    }
}

if (!function_exists('notification')) {
    /**
     * Obtient le service de notification
     */
    function notification(): \App\Services\NotificationService
    {
        static $service;
        if (!$service) {
            $service = new \App\Services\NotificationService();
        }
        return $service;
    }
}

if (!function_exists('upload')) {
    /**
     * Obtient le service d'upload
     */
    function upload(): \App\Services\UploadService
    {
        static $service;
        if (!$service) {
            $service = new \App\Services\UploadService();
        }
        return $service;
    }
}

if (!function_exists('auth_service')) {
    /**
     * Obtient le service d'authentification
     */
    function auth_service(): \App\Services\AuthService
    {
        static $service;
        if (!$service) {
            $service = new \App\Services\AuthService();
        }
        return $service;
    }
}

if (!function_exists('validation_service')) {
    /**
     * Obtient le service de validation
     */
    function validation_service(): \App\Services\ValidationService
    {
        static $service;
        if (!$service) {
            $service = new \App\Services\ValidationService();
        }
        return $service;
    }
}
