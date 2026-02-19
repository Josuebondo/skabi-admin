<?php

namespace Core;

/**
 * ======================================================================
 * Requete - Encapsule la requête HTTP
 * ======================================================================
 * 
 * Responsabilités :
 * - Accéder aux données GET, POST, FILES
 * - Récupérer les paramètres d'URL
 * - Obtenir les informations de la requête
 */
class Requete
{
    protected array $server;
    protected array $get;
    protected array $post;
    protected array $files;
    protected array $params = [];

    public function __construct(array $server, array $get, array $post, array $files = [])
    {
        $this->server = $server;
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
    }

    /**
     * Obtient une valeur GET
     */
    public function obtenir(string $cle, mixed $default = null): mixed
    {
        return $this->get[$cle] ?? $default;
    }

    /**
     * Obtient une valeur POST
     */
    public function publier(string $cle, mixed $default = null): mixed
    {
        return $this->post[$cle] ?? $default;
    }

    /**
     * Obtient une valeur des paramètres d'URL
     */
    public function param(string $cle, mixed $default = null): mixed
    {
        return $this->params[$cle] ?? $default;
    }

    /**
     * Définit un paramètre d'URL
     */
    public function definirParam(string $cle, mixed $valeur): void
    {
        $this->params[$cle] = $valeur;
    }

    /**
     * Obtient la méthode HTTP
     */
    public function methode(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Obtient le chemin de la requête
     */
    public function chemin(): string
    {
        // Essayer d'abord PATH_INFO (défini par Apache avec mod_rewrite)
        if (!empty($this->server['PATH_INFO'])) {
            return $this->server['PATH_INFO'];
        }

        // Fallback sur REQUEST_URI
        $uri = $this->server['REQUEST_URI'] ?? '/';
        // Supprimer la query string
        $chemin = explode('?', $uri)[0];

        // Supprimer le préfixe du répertoire (ex: /BMVC/)
        if (isset($this->server['SCRIPT_NAME'])) {
            $scriptPath = $this->server['SCRIPT_NAME'];
            // Obtenir le répertoire parent du script (ex: /BMVC/public/index.php -> /BMVC/)
            $appDir = dirname(dirname($scriptPath));

            // Si appDir n'est pas '/', supprimer le préfixe du chemin
            if ($appDir !== '/' && strlen($appDir) > 1 && strpos($chemin, $appDir) === 0) {
                $chemin = substr($chemin, strlen($appDir));
            }
        }

        // S'assurer que le chemin commence par /
        if (empty($chemin)) {
            $chemin = '/';
        } elseif ($chemin[0] !== '/') {
            $chemin = '/' . $chemin;
        }

        return $chemin;
    }

    /**
     * Obtient l'URL complète
     */
    public function url(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }
    public function fichier(string $nom): ?array
    {
        return $this->files[$nom] ?? null;
    }

    /**
     * Vérifie si c'est une requête AJAX
     */
    public function estAjax(): bool
    {
        return ($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    /**
     * Vérifie si c'est une requête API (header Authorization Bearer présent)
     */
    public function estApi(): bool
    {
        $header = $this->entete('Authorization');
        return !empty($header) && preg_match('/Bearer\s+.+/i', $header);
    }

    /**
     * Obtient un en-tête HTTP
     */
    public function entete(string $nom): ?string
    {
        // Convertir le nom en format HTTP (ex: authorization -> HTTP_AUTHORIZATION)
        $cle = 'HTTP_' . strtoupper(str_replace('-', '_', $nom));
        return $this->server[$cle] ?? null;
    }

    /**
     * Obtient une valeur GET ou POST
     */
    public function input(string $cle, mixed $default = null): mixed
    {
        return $this->post[$cle] ?? $this->get[$cle] ?? $default;
    }

    /**
     * Vérifie si une clé existe en GET ou POST
     */
    public function a(string $cle): bool
    {
        return isset($this->post[$cle]) || isset($this->get[$cle]);
    }
}
