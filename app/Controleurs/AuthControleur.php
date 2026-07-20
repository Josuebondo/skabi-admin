<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;
use Core\Session;

/**
 * AuthControleur Contrôleur
 */
class AuthControleur extends BaseControleur
{
    /**
     * Exemple d'action
     */
    public function index(Requete $requete, Reponse $response): string
    {
        return vue('auth.index');
    }
    function login(Requete $requete, Reponse $response)
    {
        if (Session::estActive()) {
            Session::vider();
            Session::detruire();
        }
        // session_start(); // toujours démarrer la session si tu utilises $_SESSION
        $data = $requete->tousCorps();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $url = "https://stock.skabi.shop/users/loginapi"; // Projet A

        $result = auth_service()->connexion($username, $password);

        // Rediriger vers la page d'accueil après la connexion

    }
    public function logout(Requete $requete, Reponse $response)
    {
        Session::demarrer();
        Session::vider();
        Session::detruire();
        header('Location: /login');
        exit;
    }
}
