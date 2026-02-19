<?php

namespace Core\Middlewares;

use Core\CSRF;
use Core\Requete;

/**
 * Middleware CSRF - Protège contre les attaques CSRF
 */
class MiddlewareCSRF
{
    /**
     * Vérifie le token CSRF pour les requêtes POST/PUT/DELETE
     */
    public static function verifier(Requete $requete): bool
    {
        if (in_array($requete->methode(), ['POST', 'PUT', 'DELETE'])) {
            $token = $requete->post('_token') ?? $requete->header('X-CSRF-Token');

            if (!CSRF::verifier($token)) {
                http_response_code(419);
                exit('Token CSRF invalide');
            }
        }
        return true;
    }
}
