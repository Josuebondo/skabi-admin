<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\article;
use Core\Reponse;
use Core\Requete;

/**
 * articleControleur Contrôleur
 */
class articleControleur extends BaseControleur
{
    /**
     * Afficher la liste
     */
    public function index()
    {
        // $article = article::tout();
        return vue('article.index', ['titre' => 'liste des articles ']);
    }

    /**
     * Afficher le formulaire de création
     */
    public function creer()
    {
        return vue('article.creer');
    }
    public function getAll(Requete $req, Reponse $res)
    {
        $a = new article();
        $article = $a->selectionner(['articles.*', 'stock.*', "articles.id as id_article", 'entrepots.nom as entrepot_nom'])->joindreGauche('stock', 'articles.id', 'stock.article_id')->joindreGauche('entrepots', 'stock.entrepot_id', 'entrepots.id')->obtenir();
        // dd($article);

        if (!$article) {
            $res->json([
                'success' => false,
                'message' => 'erreur articles non trouver',
                'erreur' => $article
            ]);
            # code...
        }
        $data = [];

        foreach ($article as $a) {

            $id = $a->id_article;

            if (!isset($data[$id])) {
                $data[$id] = [
                    'id' => $a->id_article,
                    'nom' => $a->article,
                    'prix' => $a->prix,
                    'stocks' => []
                ];
            }

            $data[$id]['stocks'][] = [
                'entrepot' => $a->entrepot_nom,
                'entrepot_id' => $a->entrepot_id,
                'stock' => $a->stock_disponible
            ];
        }

        $data = array_values($data);
        $res->json([
            'success' => true,
            'message' => 'articles recuperé avec succéss',
            "data" => $data
        ], 200);
    }
    /**
     * Enregistrer un nouvel élément
     */
    public function enregistrer()
    {
        $article = article::creer([
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
        $article = article::trouver($id);

        if (!$article) {
            return redirection('/404');
        }

        return vue('article.editer', ['item' => $article]);
    }

    /**
     * Mettre à jour un élément
     */
    public function mettreAJour()
    {
        $id = $this->requete()->param('id');
        $article = article::trouver($id);

        if (!$article) {
            return redirection('/404');
        }

        $article->mettreAJour([
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
        article::supprimer($id);
        return redirection('/');
    }
}
