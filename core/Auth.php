<?php

namespace Core;

use App\Modeles\Utilisateur;

/**
 * ======================================================================
 * Auth - Gestion de l'authentification
 * ======================================================================
 */
class Auth
{
    private static ?Utilisateur $utilisateur_connecte = null;

    /**
     * Connecte un utilisateur
     */
    public static function connecter(Utilisateur $utilisateur): void
    {
        $_SESSION['utilisateur_id'] = $utilisateur->id;
        $_SESSION['utilisateur'] = $utilisateur->toArray();
        self::$utilisateur_connecte = $utilisateur;
    }

    /**
     * Déconnecte l'utilisateur actuel
     */
    public static function deconnecter(): void
    {
        unset($_SESSION['utilisateur_id']);
        unset($_SESSION['utilisateur']);
        self::$utilisateur_connecte = null;
    }

    /**
     * Récupère l'utilisateur connecté
     */
    public static function utilisateur(): ?Utilisateur
    {
        if (self::$utilisateur_connecte !== null) {
            return self::$utilisateur_connecte;
        }

        if (empty($_SESSION['utilisateur_id'])) {
            return null;
        }

        self::$utilisateur_connecte = Utilisateur::trouver($_SESSION['utilisateur_id']);
        return self::$utilisateur_connecte;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function connecte(): bool
    {
        return self::utilisateur() !== null;
    }

    /**
     * Authentifie un utilisateur par email/mot de passe
     */
    public static function authentifier(string $email, string $motdepasse): ?Utilisateur
    {
        $utilisateur = Utilisateur::parEmail($email);

        if (!$utilisateur || !$utilisateur->verifierMotDePasse($motdepasse)) {
            return null;
        }

        return $utilisateur;
    }

    /**
     * Vérifie si l'utilisateur connecté est admin
     */
    public static function estAdmin(): bool
    {
        $user = self::utilisateur();
        return $user && ($user->role === 'admin' || $user->role === '1');
    }

    /**
     * ID de l'utilisateur connecté
     */
    public static function id(): ?int
    {
        $user = self::utilisateur();
        return $user ? $user->id : null;
    }
}
