<?php

namespace App\Services;

use Core\BaseBD;

class DashboardService
{
    public function getData()
    {
        // Logique pour récupérer les données du tableau de bord
        return [
            'total_users' => 150,
            'total_sales' => 1200,
            'total_products' => 300,
        ];
    }
    /**
     * Calculer la valeur totale du stock unitiale pour un entrepôt donné
     */
    public static function getStock_unitila_Value(int $entrepotId)
    {
        // Logique pour calculer la valeur totale du stock
        $db = BaseBD::obtenir();
        $sql = 'SELECT
                    SUM(s.stock_initial * a.prix) AS stock_initial
                FROM stock s
                JOIN articles a
                    ON a.id = s.article_id
                WHERE s.entrepot_id = :entrepotId';
        $valeurStock = $db->requete($sql, ['entrepotId' => $entrepotId])->fetchColumn();
        return $valeurStock;
    }
    /**
     * Calculer la valeur totale du stock entrant  pour un entrepôt donné
     */
    public static function getStock_entree_Value(int $entrepotId)
    {
        // Logique pour calculer la valeur totale du stock
        $db = BaseBD::obtenir();
        $sql = "
                SELECT
                    SUM(m.quantite * m.prix) AS stock_recu
                FROM mouvements m
                WHERE (
                        (m.type = 'entrée' AND m.entrepot_id = :entrepot1)
                    OR (m.type = 'transfert' AND m.destination_id = :entrepot2)
                )
                AND m.statut IN ('validé','vérifié')
                ";

        $valeurStock = $db->requete($sql, [
            'entrepot1' => $entrepotId,
            'entrepot2' => $entrepotId,
        ])->fetchColumn();

        return $valeurStock;
    }
}
