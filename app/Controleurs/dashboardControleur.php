<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

use App\Modeles\test;
use App\Services\DashboardService;

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
    public function getData(Requete $requete, Reponse $response)
    {
        $valeurstock_unitial = DashboardService::getStock_unitila_Value(3);
        $valeurstock_entree = DashboardService::getStock_entree_Value(3);
        $data = [
            'valeurstock_unitial' => $valeurstock_unitial,
            'valeurstock_entree' => $valeurstock_entree,
        ];
        dd($data);
    }
}
