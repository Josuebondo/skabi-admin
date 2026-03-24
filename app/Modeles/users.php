<?php

namespace App\Modeles;

use Core\Modele;
use Core\BaseBD;

/**
 * users Modèle
 */
class users extends Modele
{
    protected string $table = 'users';
    public static function entrepot()
    {
        $sql = 'SELECT * FROM entrepots';
        $db = BaseBD::obtenir();
        return $db->tous($sql);
    }
}
