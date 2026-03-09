<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\versement;

/**
 * versementC Contrôleur
 */
class versementControleur extends BaseControleur
{

    /**
     * Afficher la liste
     */
    public function index()
    {
        $versement = versement::tout();
        // dd($versement);
        $data = [];
        foreach ($versement as $v) {
            $data['id'] = $v->id;
            $data['date_versement'] = $v->date_versement;
            $data['entrepot_id'] = $v->entrepot_id;
            $data['montant'] = $v->montant;
            $data['statut'] = $v->statut;
            $data['reference'] = $v->reference;
            $data['created_at'] = $v->created_at;
            $data['created_by'] = $v->created_by;
            $data['validated_by'] = $v->validated_by;
            $data['validated_at'] = $v->validated_at;
        }
        echo json($data);
        exit;
        return vue('versement.index', ['items' => $versement]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function creer()
    {
        return vue('versement.creer');
    }


    /**
     * Afficher le formulaire d'édition
     */
    public function editer()
    {
        $id = $this->requete()->param('id');
        $versement = versement::trouver($id);

        if (!$versement) {
            return redirection('/404');
        }

        return vue('versement.editer', ['item' => $versement]);
    }



    /**
     * Supprimer un élément
     */
    public function supprimer()
    {
        $id = $this->requete()->param('id');
        versement::trouver($id)->supprimer();
        return redirection('/');
    }
}
