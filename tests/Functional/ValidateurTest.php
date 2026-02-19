<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Core\Validateur;

/**
 * ======================================================================
 * Tests du Validateur - Validation des données des formulaires
 * ======================================================================
 * 
 * Teste la validation complète des données : email, requis, min, max, etc.
 * 
 * Utilisation réelle :
 *   $validateur = new Validateur();
 *   $validateur->ajouter('email', ['requis', 'email']);
 *   if ($validateur->valider($_POST)) {
 *       // Données valides
 *   } else {
 *       $erreurs = $validateur->erreurs();
 *   }
 */
class ValidateurTest extends TestCase
{
    /**
     * Test la validation d'un email valide
     * 
     * Cas: Utilisateur entre un email correct: user@example.com
     * Résultat: valider() retourne true
     * Règles: ['requis', 'email']
     */
    public function testValidEmail(): void
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);

        $resultat = $v->valider(['email' => 'user@example.com']);

        $this->assertTrue(
            $resultat,
            'Un email valide (user@example.com) doit passer la validation'
        );
    }

    /**
     * Test le rejet d'un email invalide
     * 
     * Cas: Utilisateur entre un email incorrect: invalid-email
     * Résultat: valider() retourne false et ajoute une erreur
     * Règles: ['requis', 'email']
     */
    public function testInvalidEmail(): void
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);

        $resultat = $v->valider(['email' => 'invalid-email']);

        $this->assertFalse(
            $resultat,
            'Un email invalide (invalid-email) doit échouer la validation'
        );

        $erreurs = $v->erreurs();
        $this->assertNotEmpty(
            $erreurs,
            'Un email invalide doit générer une liste d\'erreurs'
        );
    }

    /**
     * Test la validation d'un champ requis (obligatoire)
     * 
     * Cas: Utilisateur laisse le champ vide
     * Résultat: valider() retourne false (champ obligatoire)
     * Règles: ['requis']
     */
    public function testRequired(): void
    {
        $v = new Validateur();
        $v->ajouter('nom', ['requis']);

        $resultat = $v->valider(['nom' => '']);

        $this->assertFalse(
            $resultat,
            'Un champ vide doit échouer si marqué "requis"'
        );

        $erreurs = $v->erreurs();
        $this->assertArrayHasKey(
            'nom',
            $erreurs,
            'Le champ "nom" doit être dans les erreurs'
        );
    }

    /**
     * Test la validation de longueur minimum
     * 
     * Cas: Utilisateur entre un mot de passe assez long: secure123 (9 chars)
     * Résultat: valider() retourne true
     * Règles: ['requis', 'min:8']
     */
    public function testMin(): void
    {
        $v = new Validateur();
        $v->ajouter('password', ['requis', 'min:8']);

        $resultat = $v->valider(['password' => 'secure123']);

        $this->assertTrue(
            $resultat,
            'Un mot de passe de 9 caractères doit passer min:8'
        );
    }

    /**
     * Test le rejet de longueur minimum insuffisante
     * 
     * Cas: Utilisateur entre un mot de passe trop court: short (5 chars)
     * Résultat: valider() retourne false (< 8 caractères)
     * Règles: ['requis', 'min:8']
     */
    public function testMinFail(): void
    {
        $v = new Validateur();
        $v->ajouter('password', ['requis', 'min:8']);

        $resultat = $v->valider(['password' => 'short']);

        $this->assertFalse(
            $resultat,
            'Un mot de passe de 5 caractères doit échouer min:8'
        );

        $erreurs = $v->erreurs();
        $this->assertArrayHasKey(
            'password',
            $erreurs,
            'Le champ "password" doit être dans les erreurs'
        );
    }

    /**
     * Test la validation de longueur maximum
     * 
     * Cas: Utilisateur entre un titre court: Mon Titre (9 chars)
     * Résultat: valider() retourne true
     * Règles: ['requis', 'max:50']
     */
    public function testMax(): void
    {
        $v = new Validateur();
        $v->ajouter('titre', ['requis', 'max:50']);

        $resultat = $v->valider(['titre' => 'Mon Titre']);

        $this->assertTrue(
            $resultat,
            'Un titre de 9 caractères doit passer max:50'
        );
    }

    /**
     * Test la validation avec plusieurs règles simultanées
     * 
     * Cas: Formulaire d'inscription avec 3 champs à valider
     * Données: 
     *   - nom: 'Jean Dupont' ✓
     *   - email: 'jean@example.com' ✓
     *   - age: 30 ✓
     * Résultat: Toutes les règles passent
     */
    public function testMultipleRules(): void
    {
        $v = new Validateur();
        $v->ajouter('nom', ['requis', 'min:3', 'max:50']);
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('age', ['requis']);

        $donnees = [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'age' => 30
        ];

        $resultat = $v->valider($donnees);

        $this->assertTrue(
            $resultat,
            'Toutes les règles (requis, min, max, email) doivent passer pour des données valides'
        );
    }
}
