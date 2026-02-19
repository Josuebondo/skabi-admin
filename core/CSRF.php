<?php

namespace Core;

/**
 * ======================================================================
 * CSRF - Protection contre les attaques Cross-Site Request Forgery
 * ======================================================================
 */
class CSRF
{
    private static string $token_name = '_csrf_token';
    private static int $token_lifetime = 3600; // 1 heure

    /**
     * Génère un token CSRF
     */
    public static function generer(): string
    {
        if (empty($_SESSION[self::$token_name])) {
            $_SESSION[self::$token_name] = bin2hex(random_bytes(32));
            $_SESSION[self::$token_name . '_time'] = time();
        }
        return $_SESSION[self::$token_name];
    }

    /**
     * Récupère le token CSRF
     */
    public static function token(): string
    {
        return self::generer();
    }

    /**
     * Vérifie si le token est valide
     */
    public static function verifier(string $token): bool
    {
        // Vérifier que le token existe
        if (empty($_SESSION[self::$token_name])) {
            return false;
        }

        // Vérifier que le token correspond
        if (!hash_equals($_SESSION[self::$token_name], $token)) {
            return false;
        }

        // Vérifier que le token n'a pas expiré
        if (time() - $_SESSION[self::$token_name . '_time'] > self::$token_lifetime) {
            return false;
        }

        return true;
    }

    /**
     * Alias pour verifier() - Vérification CSRF
     */
    public static function valider(string $token): bool
    {
        return self::verifier($token);
    }

    /**
     * Génère un input HTML pour le formulaire
     */
    public static function input(): string
    {
        return '<input type="hidden" name="' . self::$token_name . '" value="' . htmlspecialchars(self::token()) . '">';
    }
}
