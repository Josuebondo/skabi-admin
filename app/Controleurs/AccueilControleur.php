<?php

namespace App\Controleurs;

use App\BaseControleur;

class AccueilControleur extends BaseControleur
{
    public function index()
    {
        $this->afficher('accueil', [
            'titre' => 'Bienvenue sur BMVC',
            'message' => 'Framework PHP MVC prêt pour la production'
        ]);
    }
}
