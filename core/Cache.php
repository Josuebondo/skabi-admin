<?php

namespace Core;

/**
 * Système de Cache Fichier
 * Cache pour fichiers, config, routes
 */
class Cache
{
    private static string $cheminCache = '';
    private static int $ttl = 3600; // 1 heure par défaut

    /**
     * Initialise le cache
     */
    public static function initialiser(string $chemin = '', int $ttl = 3600): void
    {
        self::$cheminCache = $chemin ?: __DIR__ . '/../storage/cache/';
        self::$ttl = $ttl;

        // Crée le dossier s'il n'existe pas
        if (!is_dir(self::$cheminCache)) {
            mkdir(self::$cheminCache, 0755, true);
        }
    }

    /**
     * Récupère une valeur du cache
     */
    public static function obtenir(string $cle, mixed $default = null): mixed
    {
        $chemin = self::obtenirChemin($cle);

        // Vérifie si le fichier existe et est valide
        if (!file_exists($chemin)) {
            return $default;
        }

        // Vérifie l'expiration
        $mtime = filemtime($chemin);
        if (time() - $mtime > self::$ttl) {
            unlink($chemin);
            return $default;
        }

        // Décode et retourne la valeur
        $contenu = file_get_contents($chemin);
        return unserialize($contenu);
    }

    /**
     * Enregistre une valeur en cache
     */
    public static function mettre(string $cle, mixed $valeur, ?int $ttl = null): void
    {
        $chemin = self::obtenirChemin($cle);
        $contenu = serialize($valeur);

        // Crée le répertoire si nécessaire
        $dir = dirname($chemin);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($chemin, $contenu);

        // Si un TTL personnalisé, met à jour le timestamp
        if ($ttl !== null) {
            $nouveauTemps = time() + $ttl;
            touch($chemin, $nouveauTemps);
        }
    }

    /**
     * Vérifie si une clé existe en cache et est valide
     */
    public static function existe(string $cle): bool
    {
        $chemin = self::obtenirChemin($cle);

        if (!file_exists($chemin)) {
            return false;
        }

        // Vérifie l'expiration
        $mtime = filemtime($chemin);
        if (time() - $mtime > self::$ttl) {
            unlink($chemin);
            return false;
        }

        return true;
    }

    /**
     * Supprime une valeur du cache
     */
    public static function oublier(string $cle): void
    {
        $chemin = self::obtenirChemin($cle);
        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }

    /**
     * Vide tout le cache
     */
    public static function vider(): void
    {
        $fichiers = glob(self::$cheminCache . '*');
        foreach ($fichiers as $fichier) {
            if (is_file($fichier)) {
                unlink($fichier);
            }
        }
    }

    /**
     * Récupère ou met en cache une valeur
     */
    public static function souvenir(string $cle, callable $callback, ?int $ttl = null): mixed
    {
        if (self::existe($cle)) {
            return self::obtenir($cle);
        }

        $valeur = $callback();
        self::mettre($cle, $valeur, $ttl);

        return $valeur;
    }

    /**
     * Génère le chemin du fichier cache
     */
    private static function obtenirChemin(string $cle): string
    {
        $cle = preg_replace('/[^a-z0-9_\-\.]/', '_', strtolower($cle));
        return self::$cheminCache . $cle . '.cache';
    }
}

/**
 * Cache pour les configuration
 * Sérialise et cache les fichiers de config
 */
class CacheConfig
{
    private static array $cache = [];

    /**
     * Obtient une configuration avec cache
     */
    public static function obtenir(string $cle, mixed $default = null): mixed
    {
        $parts = explode('.', $cle, 2);
        $fichier = $parts[0];
        $cleNested = $parts[1] ?? null;

        // Charge depuis le cache ou le fichier
        if (!isset(self::$cache[$fichier])) {
            self::$cache[$fichier] = self::chargerFichier($fichier);
        }

        $config = self::$cache[$fichier];

        // Si pas de clé nested, retourne tout
        if ($cleNested === null) {
            return $config ?? $default;
        }

        // Navigue dans la config
        $parts = explode('.', $cleNested);
        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return $default;
            }
            $config = $config[$part];
        }

        return $config;
    }

    /**
     * Définit une configuration
     */
    public static function set(string $cle, mixed $valeur): void
    {
        $parts = explode('.', $cle, 2);
        $fichier = $parts[0];
        $cleNested = $parts[1] ?? null;

        if (!isset(self::$cache[$fichier])) {
            self::$cache[$fichier] = [];
        }

        if ($cleNested === null) {
            self::$cache[$fichier] = $valeur;
        } else {
            // Navigue et définit la valeur
            $ref = &self::$cache[$fichier];
            $parts = explode('.', $cleNested);
            foreach ($parts as $i => $part) {
                if ($i === count($parts) - 1) {
                    $ref[$part] = $valeur;
                } else {
                    if (!isset($ref[$part])) {
                        $ref[$part] = [];
                    }
                    $ref = &$ref[$part];
                }
            }
        }
    }

    /**
     * Charge un fichier de configuration
     */
    private static function chargerFichier(string $fichier): array
    {
        // Recherche le fichier dans les répertoires standards
        $chemins = [
            __DIR__ . '/../config/' . $fichier . '.php',
            __DIR__ . '/../app/config/' . $fichier . '.php',
        ];

        foreach ($chemins as $chemin) {
            if (file_exists($chemin)) {
                return include $chemin;
            }
        }

        return [];
    }

    /**
     * Vide le cache de config
     */
    public static function flush(): void
    {
        self::$cache = [];
    }
}

/**
 * Cache pour les Routes
 * Cache la compilation des routes
 */
class CacheRoutes
{
    private static Cache $cache;
    private const CLE_ROUTES = 'routes_compilees';

    /**
     * Obtient les routes compilées du cache
     */
    public static function obtenir(): ?array
    {
        if (!isset(self::$cache)) {
            self::$cache = new Cache();
        }

        return self::$cache->obtenir(self::CLE_ROUTES);
    }

    /**
     * Met en cache les routes compilées
     */
    public static function sauvegarder(array $routes): void
    {
        if (!isset(self::$cache)) {
            self::$cache = new Cache();
        }

        self::$cache->mettre(self::CLE_ROUTES, $routes, 86400); // 24 heures
    }

    /**
     * Efface le cache des routes
     */
    public static function oublier(): void
    {
        if (!isset(self::$cache)) {
            self::$cache = new Cache();
        }

        self::$cache->oublier(self::CLE_ROUTES);
    }

    /**
     * Vérifie si les routes sont en cache
     */
    public static function existe(): bool
    {
        if (!isset(self::$cache)) {
            self::$cache = new Cache();
        }

        return self::$cache->existe(self::CLE_ROUTES);
    }
}
