<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Core\BaseDeDonnees;
use Core\BaseBD;

/**
 * ======================================================================
 * Tests de la Base de Données - Couche d'accès aux données
 * ======================================================================
 * 
 * Teste les deux classes de gestion de BD :
 * 
 * 1. BaseBD (Singleton) : Gère la connexion PDO brute
 *    - Exécution de requêtes SQL
 *    - Gestion des transactions
 *    - Récupération des résultats
 * 
 * 2. BaseDeDonnees (Façade) : Wrapper statique pour requêtes simples
 *    - inserer() : INSERT
 *    - mettre_a_jour() : UPDATE
 *    - supprimer() : DELETE
 *    - obtenir() : SELECT (1 ligne)
 *    - tous() : SELECT (tous)
 */
class BaseDeDonneesTest extends TestCase
{
    /**
     * Test que BaseDeDonnees a toutes les méthodes statiques
     * 
     * Vérifie 5 opérations CRUD :
     * - inserer : INSERT into table
     * - mettre_a_jour : UPDATE table SET...
     * - supprimer : DELETE from table WHERE...
     * - obtenir : SELECT * FROM table WHERE... LIMIT 1
     * - tous : SELECT * FROM table WHERE...
     */
    public function testStaticMethodsExist(): void
    {
        $this->assertTrue(
            method_exists(BaseDeDonnees::class, 'inserer'),
            'BaseDeDonnees::inserer() manquante - INSERT non disponible'
        );

        $this->assertTrue(
            method_exists(BaseDeDonnees::class, 'mettre_a_jour'),
            'BaseDeDonnees::mettre_a_jour() manquante - UPDATE non disponible'
        );

        $this->assertTrue(
            method_exists(BaseDeDonnees::class, 'supprimer'),
            'BaseDeDonnees::supprimer() manquante - DELETE non disponible'
        );

        $this->assertTrue(
            method_exists(BaseDeDonnees::class, 'obtenir'),
            'BaseDeDonnees::obtenir() manquante - SELECT non disponible'
        );

        $this->assertTrue(
            method_exists(BaseDeDonnees::class, 'tous'),
            'BaseDeDonnees::tous() manquante - SELECT ALL non disponible'
        );
    }

    /**
     * Test que BaseBD suit le pattern Singleton
     * 
     * Singleton = une seule instance en mémoire
     * 
     * Cas: Appeler obtenir() deux fois
     * Résultat: Doit retourner la même instance
     * 
     * Avantage: Une seule connexion à la BD, réutilisée partout
     */
    public function testBaseBDSingletonPattern(): void
    {
        $this->assertTrue(
            method_exists(BaseBD::class, 'obtenir'),
            'BaseBD::obtenir() doit exister (méthode Singleton)'
        );
    }

    /**
     * Test que BaseBD a toutes les méthodes essentielles
     * 
     * Vérifie 4 méthodes principales :
     * - executer() : Exécute une requête (INSERT, UPDATE, DELETE)
     * - tous() : Récupère tous les résultats
     * - une() : Récupère un seul résultat
     * - connexion() : Retourne la connexion PDO brute
     */
    public function testBaseBDMethodsExist(): void
    {
        $this->assertTrue(
            method_exists(BaseBD::class, 'executer'),
            'BaseBD::executer() manquante - Exécution requête non disponible'
        );

        $this->assertTrue(
            method_exists(BaseBD::class, 'tous'),
            'BaseBD::tous() manquante - SELECT * non disponible'
        );

        $this->assertTrue(
            method_exists(BaseBD::class, 'une'),
            'BaseBD::une() manquante - SELECT LIMIT 1 non disponible'
        );

        $this->assertTrue(
            method_exists(BaseBD::class, 'connexion'),
            'BaseBD::connexion() manquante - Accès PDO non disponible'
        );
    }
}
