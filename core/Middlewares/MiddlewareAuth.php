<?php

namespace Core\Middlewares;

use Core\Reponse;
use Core\Requete;
use Core\Session;

/**
 * Middleware d'authentification - V�rifie que l'utilisateur est connect�
 */
class MiddlewareAuth
{
    public function traiter(Requete $request, callable $next): Reponse
    {
        // Vérifier si l'utilisateur est connecté
        // dd(Session::estActive());
        if (Session::estActive() == false) {
            //garder le route demandé pour redirection après login
            Session::enregistrer('url_intended', $request->url());

            // Rediriger vers la page de connexion
            redirection('/login');
        }
        // dd('redirection vers login', Session::estActive(), $next);
        return $next($request);
    }
}
