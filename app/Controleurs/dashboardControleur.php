<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

use App\Modeles\test;

/**
 * dashboardControker Contrôleur
 */
class dashboardControleur extends BaseControleur
{
    /**
     * Exemple d'action
     */
    public function index(Requete $requete, Reponse $response): string
    {
        return vue('dashboard.gerant');
    }
    public function test(Requete $requete, Reponse $response)
    {
        $db = test::ou('produit_id', '=', "3")->enTableau();
        // $data = test::->enTableau();
        dd($db);
    }
}
