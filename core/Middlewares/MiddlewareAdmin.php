<?php

namespace Core\Middlewares;

use Core\Auth;
use Core\Requete;

/**
 * Middleware Admin - Vérifie que l'utilisateur est administrateur
 */
class MiddlewareAdmin
{
    /**
     * Vérifie que l'utilisateur est admin
     */
    public static function verifier(Requete $requete): bool
    {
        if (!Auth::estAdmin()) {
            header('Location: /');
            exit;
        }
        return true;
    }
}
