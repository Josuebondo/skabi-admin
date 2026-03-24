<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\document;
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
        $entrepot = document::entrepot();
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
                'date_document' => $d['date'] ?? date('Y-m-d'),
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
        $d = BaseBD::obtenir();

        $sql = "SELECT 
            d.id AS document_id,
            d.numero,
            d.type,
            d.statut,
            d.total,
            d.user_id,
            d.date_document,
            d.created_at,
            di.id AS item_id,
            di.quantite,
            di.prix,
            di.page,
            a.id AS article_id,
            a.article AS article_nom
        FROM documents d
        LEFT JOIN document_items di ON di.document_id = d.id
        LEFT JOIN articles a ON a.id = di.article_id
        WHERE d.statut = 'brouillon'
        ORDER BY d.id, di.id";

        $rows = $d->tous($sql);
        // dd($rows);
        $documents = [];

        foreach ($rows as $row) {

            $docId = $row['document_id'];

            if (!isset($documents[$docId])) {
                $documents[$docId] = [
                    'id' => $row['document_id'],
                    'numero' => $row['numero'],
                    'type' => $row['type'],
                    'statut' => $row['statut'],
                    'total' => $row['total'],
                    'user_id' => $row['user_id'],
                    'date_document' => $row['date_document'],
                    'created_at' => $row['created_at'],
                    'items' => []
                ];
            }

            if ($row['item_id']) {

                $documents[$docId]['items'][] = [
                    'id' => $row['article_id'],
                    'quantite' => $row['quantite'],
                    'prix' => $row['prix'],
                    'article' => $row['article_nom'],
                    'page' => $row['page']

                ];
            }
        }

        $documents = array_values($documents);




        $documents = array_values($documents);
        $data = json($documents);
        echo $data;
    }
}
