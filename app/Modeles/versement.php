<?php

namespace App\Modeles;

use Core\Modele;

/**
 * versement Modèle
 */
class versement extends Modele
{
    protected string $table = 'versement';


    /**
     * Générer une référence unique pour un versement
     */
    public static function genererReference(string $prefix = 'VERS', ?string $date = null): string
    {
        // Date du jour si non fournie
        $date = $date ?? date('Y-m-d');
        $dateStr = date('Ymd', strtotime($date));

        // Compter les versements déjà créés aujourd'hui
        $count = versement::ou('date_versement', '=', $date)
            ->selectionner(['COUNT(*) as total'])
            ->premier()
            ->total ?? 0;

        // Incrémenter de 1 pour obtenir le numéro du jour
        $numero = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        // Construire la référence
        return "{$prefix}-{$dateStr}-{$numero}";
    }
}
