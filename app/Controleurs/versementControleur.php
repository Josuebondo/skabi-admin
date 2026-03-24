<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\versement;
use App\Modeles\users;
use Core\Reponse;
use Core\Requete;
use PhpParser\Node\Scalar\Float_;

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
        $u = new users();
        $us = $u->selectionner(['users.nom_complet', 'users.id'])->obtenir();
        $entrepot  = users::entrepot();
        // json($entrepot);
        $users = [];
        foreach ($us as $u) {
            $users[] = [
                'nom' => $u->nom_complet,
                'id' => $u->id
            ];
        }
        // echo json($data);
        // exit;
        return vue('versement.index', ['entrepots' => $entrepot, 'users' => $users]);
    }
    /**
     * Afficher la liste
     */
    public function all()
    {
        // dd($_SESSION['user']['user_id']);

        $v = new versement();

        $versements = $v
            ->selectionner([
                'versement.*',
                'e.nom as entrepot_nom',
                'u.nom_complet as createur_nom'
            ])
            ->joindreGauche('entrepots as e', 'versement.entrepot_id', 'e.id')
            ->joindreGauche('users as u', 'versement.created_by', 'u.id')->orderBy('versement.date_versement', 'DESC')
            ->obtenir();
        $data = [];

        foreach ($versements as $v) {
            $data[] = [
                'id' => $v->id,
                'date_versement' => $v->date_versement,
                'entrepot_id' => $v->entrepot_id,
                'entrepot' => $v->entrepot_nom,
                'montant' => $v->montant,
                'statut' => $v->statut,
                'reference' => $v->reference,
                'created_at' => $v->created_at,
                'created_by' => $v->createur_nom,
                'user_id' => $v->created_by,
                'validated_by' => $v->validated_by,
                'validated_at' => $v->validated_at
            ];
        }

        return json($data);
    }
    public function store(Requete $req, Reponse $res)
    {
        try {
            $d = $req->tousCorps();
            $userId = $_SESSION['user']['user_id'] ?? null;

            // Vérification basique des données
            if (!$d['date'] || !$d['entrepot'] || !$d['montant']) {
                return $res->json([
                    'success' => false,
                    'message' => 'Veuillez remplir tous les champs requis.'
                ], 400);
            }

            if (!$userId) {
                return $res->json([
                    'success' => false,
                    'message' => 'Utilisateur non authentifié.'
                ], 401);
            }

            // Génération de la référence
            $ref = versement::genererReference();

            // Création du versement
            $versement = versement::creer([
                'date_versement' => $d['date'],
                'entrepot_id' => (int) $d['entrepot'],
                'montant' => (float) $d['montant'],
                'created_by' => $userId,
                'reference' => $ref,
            ]);

            // Réponse réussie
            $res->json([
                'success' => true,
                'message' => 'Versement créé avec succès.',
                'data' => [
                    'id' => $versement->id ?? null,
                    'reference' => $ref,
                    'date_versement' => $d['date'],
                    'entrepot_id' => (int) $d['entrepot'],
                    'montant' => (float) $d['montant']
                ]
            ], 201);
        } catch (\Exception $e) {
            // Gestion d'erreurs
            $res->json([
                'success' => false,
                'message' => 'Erreur lors de la création du versement : ' . $e->getMessage()
            ], 500);
        }
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
