<?php

namespace App\Services;

use Core\Validateur;

/**
 * Service de Validation
 * Encapsule les règles de validation réutilisables
 */
class ValidationService
{
    /**
     * Valide un article
     */
    public function validerArticle(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('titre', ['requis', 'min:3', 'max:255']);
        $v->ajouter('contenu', ['requis', 'min:10']);
        $v->valider($donnees);

        return $v;
    }

    /**
     * Valide un email
     */
    public function validerEmail(string $email): Validateur
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);
        $v->valider(['email' => $email]);

        return $v;
    }

    /**
     * Valide un mot de passe fort
     */
    public function validerMotDePasseFort(string $motDePasse): Validateur
    {
        $v = new Validateur();
        $v->ajouter('mot_de_passe', [
            'requis',
            'min:8',
            'regex:/[A-Z]/',  // Au moins une majuscule
            'regex:/[0-9]/',  // Au moins un chiffre
        ], [
            'regex' => 'Le mot de passe doit contenir au minimum une majuscule et un chiffre'
        ]);
        $v->valider(['mot_de_passe' => $motDePasse]);

        return $v;
    }
}
