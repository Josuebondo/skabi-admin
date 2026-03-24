<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;

/**
 * FolderControler Contrôleur
 */
class FolderControler extends BaseControleur
{
    /**
     * Exemple d'action
     */
    public function index(Requete $requete, Reponse $response): string
    {
        return vue('folder.index');
    }
}
