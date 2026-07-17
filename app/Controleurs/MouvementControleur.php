<?php

namespace App\Controleurs;

use App\BaseControleur;

class MouvementControleur extends BaseControleur
{
    public function index()
    {

        dd('teste deployement');
        $this->afficher('mouvement/index', [
            'titre' => 'Liste des mouvements',
            'mouvements' => [] // Vous pouvez remplacer ceci par une récupération réelle des mouvements depuis la base de données
        ]);
    }
}
