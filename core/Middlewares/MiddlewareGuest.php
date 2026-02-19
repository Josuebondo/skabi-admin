<?php

namespace Core\Middlewares;

use Core\Requete;

/**
 * Middleware Guest - Vérifie que l'utilisateur n'est PAS connecté
 */
class MiddlewareGuest
{
    /**
     * Vérifie que l'utilisateur n'est pas connecté
     */
    public static function verifier(Requete $requete): bool
    {
        if (isset($_SESSION['utilisateur'])) {
            header('Location: /');
            exit;
        }
        return true;
    }
}
