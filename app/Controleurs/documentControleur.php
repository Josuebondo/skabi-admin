<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\document;

/**
 * documentControleur Contrôleur
 */
class documentControleur extends BaseControleur
{
    /**
     * Afficher la liste
     */
    public function index()
    {
        // $document = document::tout();
        return vue('document.index',);
    }
    public function inv()
    {
        // $document = document::tout();
        return vue('document.inventaire',);
    }
    /**
     * Afficher le formulaire de création
     */
    public function creer()
    {
        return vue('document.creer');
    }

    /**
     * Enregistrer un nouvel élément
     */
    public function enregistrer()
    {
        $document = document::creer([
            'nom' => $this->requete()->post('nom'),
        ]);

        return redirection('/');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function editer()
    {
        $id = $this->requete()->param('id');
        $document = document::trouver($id);

        if (!$document) {
            return redirection('/404');
        }

        return vue('document.editer', ['item' => $document]);
    }

    /**
     * Mettre à jour un élément
     */
    public function mettreAJour()
    {
        $id = $this->requete()->param('id');
        $document = document::trouver($id);

        if (!$document) {
            return redirection('/404');
        }

        $document->mettreAJour([
            'nom' => $this->requete()->post('nom'),
        ]);

        return redirection('/');
    }

    /**
     * Supprimer un élément
     */
    public function supprimer()
    {
        $id = $this->requete()->param('id');
        document::supprimer($id);
        return redirection('/');
    }
}
