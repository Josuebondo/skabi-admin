<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\document;
use App\Modeles\entrepot;
use Core\Reponse;
use Core\Requete;
use Core\BaseBD;

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
        $entrepot = entrepot::tous();
        // $entrepot = json($ent);
        // dd($entrepot);
        return vue('document.index', $entrepot);
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


    public function store(Requete $req, Reponse $res)
    {
        $d = $req->tousCorps();
        $numero = document::generateDocumentCode($d['date']);
        if (empty($d['id'])) {
            // Création d'un nouveau document
            $data = [
                'numero' => $numero,
                'source_id' => $d['source'] ?? null,
                'destination_id' => $d['destination'] ?? null,
                'total' => $d['total'] ?? 0,
                // Ensure a valid DATETIME value (MySQL strict mode rejects '0000-00-00 00:00:00')
                'date_document' => !empty($d['date']) ? date('Y-m-d H:i:s', strtotime($d['date'])) : date('Y-m-d H:i:s'),
                'statut' => 'brouillon',
                'type' => $d['type'] ?? 'standard',
            ];

            $items = $d['items'] ?? [];

            $result = Document::creerDoc($data, $items);
        } else {
            // Mise à jour d'un document existant
            $data = [
                'id' => $d['id'],
                'total' => $d['total'] ?? 0,
                'statut' => 'brouillon',
            ];

            Document::update($data);

            $items = $d['items'] ?? [];
            $result = Document::addItems($d['id'], $items);
        }


        // dd($data);

        return $res->json(['res' => $result]);
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
    public function brouillons()
    {
        // Utiliser l'ORM BMVC pour récupérer les documents brouillons et leurs items
        $docs = \App\Modeles\document::ou('statut', 'brouillon')->trierPar('id', 'ASC')->obtenir();

        $result = [];

        foreach ($docs as $doc) {
            $items = $doc->aPlusieurs('App\\Modeles\\document_item', 'document_id', 'id');

            $itemList = [];
            foreach ($items as $it) {
                $article = \App\Modeles\article::trouver($it->article_id);

                $itemList[] = [
                    'id' => $article->id ?? $it->article_id,
                    'quantite' => $it->quantite,
                    'prix' => $it->prix,
                    'article' => $article->article ?? null,
                    'page' => $it->page
                ];
            }

            $result[] = [
                'id' => $doc->id,
                'numero' => $doc->numero,
                'type' => $doc->type,
                'statut' => $doc->statut,
                'total' => $doc->total,
                'user_id' => $doc->user_id,
                'date_document' => $doc->date_document,
                'created_at' => $doc->created_at,
                'items' => $itemList
            ];
        }

        return json($result);
    }
}
