<?php

namespace Core;

/**
 * ======================================================================
 * Session - Gère les sessions utilisateur
 * ======================================================================
 */
class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Enregistre une valeur en session
     */
    public static function enregistrer(string $cle, mixed $valeur): void
    {
        $_SESSION[$cle] = $valeur;
    }

    /**
     * Obtient une valeur de session
     */
    public static function obtenir(string $cle, mixed $default = null): mixed
    {
        return $_SESSION[$cle] ?? $default;
    }

    /**
     * Supprime une valeur de session
     */
    public function supprimer(string $cle): void
    {
        unset($_SESSION[$cle]);
    }

    /**
     * Vide la session
     */
    public static function vider(): void
    {
        $_SESSION = [];
    }
    public static function detruire(): void
    {
        session_destroy();
    }
    public function trouver(string $cle): bool
    {
        return isset($_SESSION[$cle]);
    }
    public function tous(): array
    {
        return $_SESSION;
    }
    public function regenerer(): void
    {
        session_regenerate_id(true);
    }

    public function estConnecte(): bool
    {
        return isset($_SESSION['user']);
    }
    public function deconnecter(): void
    {
        $this->vider();
        $this->detruire();
    }
    public static function estActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
    public static function demarrer(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
