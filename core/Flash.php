<?php

namespace Core;

/**
 * ======================================================================
 * Flash - Gestion des messages flash et données anciennes
 * ======================================================================
 * 
 * Utile pour les messages temporaires (succès, erreur, etc.)
 * et pour les formulaires (réaffichage des données saisies)
 */
class Flash
{
    const SUCCES = 'succes';
    const ERREUR = 'erreur';
    const ALERTE = 'alerte';
    const INFO = 'info';

    /**
     * Enregistre un message flash
     */
    public static function ajouter(string $type, string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Récupère tous les messages flash
     */
    public static function tous(): array
    {
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Récupère un message flash spécifique
     */
    public static function obtenir(string $type): ?string
    {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }

    /**
     * Vérifie s'il y a un message flash d'un certain type
     */
    public static function a(string $type): bool
    {
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Stocke les données pour les réafficher dans les formulaires
     */
    public static function sauvegarderInputs(array $donnees): void
    {
        $_SESSION['_ancien_input'] = $donnees;
    }

    /**
     * Récupère une valeur ancien input
     */
    public static function ancien(string $cle, $defaut = ''): string
    {
        $valeur = $_SESSION['_ancien_input'][$cle] ?? $defaut;
        unset($_SESSION['_ancien_input'][$cle]);
        return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Nettoie les anciens inputs
     */
    public static function nettoyerAnciens(): void
    {
        unset($_SESSION['_ancien_input']);
    }
}
