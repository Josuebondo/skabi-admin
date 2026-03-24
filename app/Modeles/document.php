<?php

namespace App\Modeles;

use Core\Modele;
use Core\BaseBD;
use PHPUnit\Framework\Constraint\ExceptionMessage;

/**
 * document Modèle
 */
class document extends Modele
{
    protected string $table = 'documents';
    public static function entrepot()
    {
        $bd = BaseBD::obtenir();
        $sql = 'SELECT * FROM entrepots';
        return $bd->tous($sql);
    }

    public static function creerDoc($data, $items)
    {

        $bd = BaseBD::obtenir();
        $bd->commencer();

        try {

            $documentId = Document::creer($data);
            // dd($documentId->id);

            foreach ($items as $item) {
                document::addItems($documentId->id, $item);
            }

            $bd->valider();
            return true;
        } catch (ExceptionMessage $e) {
            $bd->annuler();
            return $e;
        }
        // return true;
    }

    /**
     * 🔹 Générer un code document selon le type (ex: BS2025-10-1010, BE2025-10-1011, BT2025-10-1012)
     */
    public static function generateDocumentCode($date, $type = 'invertaire',)
    {
        // Déterminer le préfixe selon le type
        $prefix = match (strtolower($type)) {
            'entree' => 'BE',
            'sortie' => 'FAC',
            'transfert' => 'BT',
            'initial' => 'BI',
            'echange' => 'BC',
            'ajustement' => 'BA',
            'commande' => 'Com',
            'rapportj' => 'RJ',
            'invertaire' => 'IN',
            default => 'BM'  // Bon Mouvement (générique)
        };

        $d = new document();
        $numero = $d->selectionner(['numero'])->premier();


        $last = document::ou('numero', '=', $numero)->enTableau();

        // $today = date('Y-m-d');
        $dateCode = $date;

        if ($last) {
            // Extraire la partie compteur du dernier code
            $parts = explode('-', $last['numero']);
            $counter = intval(end($parts)) + 1;
        } else {
            $counter = 1; // Premier bon du jour pour ce type
        }
        // Générer le nouveau code
        do {
            $newCode = $prefix . '-' . $dateCode . '-E'  . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
            $counter++;
        } while (document::documentExisteBon($newCode));

        return $newCode;
    }

    // Mise à jour de la vérification d'unicité
    private static function documentExisteBon($code): bool
    {
        $r = document::ou('numero', $code)->premier();
        if (!$r) {
            return false;
        }
        return true;
    }
    public static function update(array $data)
    {
        $doc = document::trouver($data['id']);
        if ($doc) {
            $doc->total += $data['total'] ?? 0;
            $doc->statut = $data['statut'] ?? 'brouillon';
            $doc->mettreAJour();
        } else {
            // Gérer le cas où le document n'existe pas
        }
    }
    public static function addItems($documentId, $items)
    {
        $bd = BaseBD::obtenir();
        $sql = 'INSERT INTO document_items (document_id, article_id, quantite, prix, page) VALUES(:doc, :art, :qty, :prix, :page)';
        $params = [
            ':doc' => $documentId,
            ':art' => $items['id'],
            ':qty' => $items['qty'],
            ':prix' => $items['prix'],
            ':page' => $items['num']
        ];
        return $bd->executer($sql, $params);
    }
}
