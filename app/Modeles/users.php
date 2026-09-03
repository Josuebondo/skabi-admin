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

    public static function parIdentifiant(string $identifiant): ?array
    {
        $user = self::ou('username', $identifiant)->enTableau();
        return $user;
    }

    public static function verifierIdentifiants(string $identifiant, string $motDePasse): ?array
    {
        $utilisateur = self::parIdentifiant($identifiant);

        if (!$utilisateur) {
            return null;
        }

        $hash = self::extraireHashMotDePasse($utilisateur);

        if ($hash === null) {
            return null;
        }

        if (!password_verify($motDePasse, $hash)) {
            return null;
        }

        return $utilisateur;
    }

    private static function extraireHashMotDePasse(array $utilisateur): ?string
    {
        foreach (['password', 'mot_de_passe', 'mdp'] as $colonne) {
            if (!empty($utilisateur[$colonne])) {
                return $utilisateur[$colonne];
            }
        }

        return null;
    }
}
