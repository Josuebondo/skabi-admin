<?php

namespace App\Services;

use App\Modeles\Utilisateur;
use Core\Validateur;

/**
 * Service d'Authentification
 * Encapsule la logique d'authentification réutilisable
 */
class AuthService
{
    /**
     * Authentifie un utilisateur
     */
    public function connexion(string $email, string $motDePasse): ?Utilisateur
    {
        $utilisateur = Utilisateur::parEmail($email);

        if (!$utilisateur || !$utilisateur->verifierMotDePasse($motDePasse)) {
            return null;
        }

        return $utilisateur;
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function inscription(array $donnees): ?Utilisateur
    {
        return Utilisateur::creerUtilisateur($donnees);
    }

    /**
     * Valide les données de connexion
     */
    public function validerConnexion(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->valider($donnees);

        return $v;
    }

    /**
     * Valide les données d'inscription
     */
    public function validerInscription(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('nom', ['requis', 'min:3']);
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->ajouter('confirmation_mot_de_passe', ['match:mot_de_passe']);
        $v->valider($donnees);

        return $v;
    }
}
