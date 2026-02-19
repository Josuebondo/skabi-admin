<?php

namespace Core\Middlewares;

use Core\Auth;
use Core\Requete;

/**
 * Middleware d'authentification - Vérifie que l'utilisateur est connecté
 */
class MiddlewareAuth
{
    /**
     * Vérifie que l'utilisateur est connecté
     */
    public static function verifier(Requete $requete): bool
    {
        if (!Auth::connecte()) {

            if ($requete->estAjax() || $requete->estApi()) {
                http_response_code(401);
                echo json_encode(['error' => 'Non authentifié']);
                exit;
            }

            header('Location: /connexion');
            exit;
        }

        return true;
    }
}
