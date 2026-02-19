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
    public function enregistrer(string $cle, mixed $valeur): void
    {
        $_SESSION[$cle] = $valeur;
    }

    /**
     * Obtient une valeur de session
     */
    public function obtenir(string $cle, mixed $default = null): mixed
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
    public function vider(): void
    {
        $_SESSION = [];
    }
}
