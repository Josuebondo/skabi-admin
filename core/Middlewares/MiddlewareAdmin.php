<?php

namespace Core\Middlewares;

use Core\Reponse;
use Core\Requete;
use Core\Session;

/**
 * MiddlewareAdmin - Vérifie que l'utilisateur est un administrateur
 */
class MiddlewareAdmin
{
    public function traiter(Requete $request, callable $next): Reponse
    {
        $user = auth()->user();
        if (!$user || $user['role'] !== 'admin') {
            // Rediriger vers une page d'erreur ou afficher un message d'accès refusé
            Session::enregistrer('url_intended', $request->url());
            redirection('/403');
        }

        return $next($request);
    }
}
