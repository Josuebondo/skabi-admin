<?php

namespace Core\Middlewares;

use Core\Requete;
use Core\Reponse;

/**
 * Middleware d'authentification - Vérifie que l'utilisateur est connecté
 */
class MiddlewareAuth
{
    public function traiter(Requete $request, callable $next): Reponse
    {
        if (!isset($_SESSION['user'])) {
            $reponse = new Reponse();
            $reponse->redirection('/login');
            return $reponse;
        }

        return $next($request);
    }
}
