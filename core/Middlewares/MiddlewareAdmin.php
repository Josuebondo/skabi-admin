<?php

namespace Core\Middlewares;

use Core\Requete;
use Core\Reponse;

/**
 * MiddlewareAdmin - Vérifie que l'utilisateur est un administrateur
 */
class MiddlewareAdmin
{
    public function traiter(Requete $request, callable $next): Reponse
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $reponse = new Reponse();
            $reponse->redirection('/login');
            return $reponse;
        }
        return $next($request);
    }
}
