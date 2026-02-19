<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Core\Requete;

/**
 * Tests de la classe Requete
 * Vérifie la gestion des requêtes HTTP
 */
class RequeteTest extends TestCase
{
    private Requete $requete;

    protected function setUp(): void
    {
        // Créer une requête fictive pour les tests
        $this->requete = new Requete(
            server: [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/articles/1?page=2',
                'PATH_INFO' => '/articles/1',
                'SCRIPT_NAME' => '/BMVC/public/index.php',
                'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
                'HTTP_AUTHORIZATION' => 'Bearer token123',
            ],
            get: ['page' => '2', 'sort' => 'titre'],
            post: ['nom' => 'Jean', 'email' => 'jean@example.com'],
            files: ['photo' => ['name' => 'photo.jpg', 'type' => 'image/jpeg']]
        );
    }

    /**
     * Test la récupération des données GET
     * 
     * Cas: Utilisateur accède à /articles?page=2&sort=titre
     * Résultat: obtenir('page') retourne '2'
     */
    public function testObtenir(): void
    {
        $page = $this->requete->obtenir('page');
        $this->assertEquals('2', $page, 'La méthode obtenir() doit récupérer les données GET');
    }

    /**
     * Test la récupération des données POST
     * 
     * Cas: Utilisateur soumet un formulaire avec nom=Jean
     * Résultat: publier('nom') retourne 'Jean'
     */
    public function testPublier(): void
    {
        $nom = $this->requete->publier('nom');
        $this->assertEquals('Jean', $nom, 'La méthode publier() doit récupérer les données POST');
    }

    /**
     * Test la récupération de la méthode HTTP
     * 
     * Cas: Vérifier si c'est GET, POST, PUT ou DELETE
     * Résultat: methode() retourne 'GET'
     */
    public function testMethode(): void
    {
        $methode = $this->requete->methode();
        $this->assertEquals('GET', $methode, 'La méthode methode() doit retourner la méthode HTTP (GET, POST, etc)');
    }

    /**
     * Test la récupération du chemin de la requête
     * 
     * Cas: Utilisateur accède à /articles/1
     * Résultat: chemin() retourne '/articles/1'
     */
    public function testChemin(): void
    {
        $chemin = $this->requete->chemin();
        $this->assertStringContainsString('articles', $chemin, 'La méthode chemin() doit retourner le chemin URI');
    }

    /**
     * Test la détection d'une requête AJAX
     * 
     * Cas: Requête envoyée via XMLHttpRequest (jQuery, fetch, axios)
     * Résultat: estAjax() retourne true
     * 
     * En-tête: X-Requested-With: XMLHttpRequest
     */
    public function testEstAjax(): void
    {
        $this->assertTrue(
            $this->requete->estAjax(),
            'estAjax() doit détecter les requêtes AJAX (header X-Requested-With: XMLHttpRequest)'
        );
    }

    /**
     * Test la détection d'une requête API
     * 
     * Cas: Requête API avec token Bearer
     * Résultat: estApi() retourne true
     * 
     * En-tête: Authorization: Bearer token123
     */
    public function testEstApi(): void
    {
        $this->assertTrue(
            $this->requete->estApi(),
            'estApi() doit détecter les requêtes API (header Authorization: Bearer ...)'
        );
    }

    /**
     * Test la récupération d'un en-tête HTTP
     * 
     * Cas: Récupérer la valeur d'un en-tête personnalisé
     * Résultat: entete('Authorization') retourne 'Bearer token123'
     */
    public function testEntete(): void
    {
        $auth = $this->requete->entete('Authorization');
        $this->assertEquals(
            'Bearer token123',
            $auth,
            'entete() doit récupérer la valeur d\'un en-tête HTTP'
        );
    }

    /**
     * Test la récupération d'un fichier uploadé
     * 
     * Cas: Utilisateur upload une photo
     * Résultat: fichier('photo') retourne l'infos du fichier
     */
    public function testFichier(): void
    {
        $photo = $this->requete->fichier('photo');
        $this->assertIsArray($photo, 'fichier() doit retourner un tableau');
        $this->assertEquals('photo.jpg', $photo['name'], 'Le nom du fichier doit correspondre');
    }

    /**
     * Test la méthode input() qui récupère GET ou POST
     * 
     * Cas: Ne pas savoir si la donnée vient de GET ou POST
     * Résultat: input() cherche d'abord POST, puis GET
     * 
     * Priorité: POST > GET
     */
    public function testInput(): void
    {
        // Récupère du GET
        $page = $this->requete->input('page');
        $this->assertEquals('2', $page, 'input() doit récupérer les données GET');

        // Récupère du POST
        $nom = $this->requete->input('nom');
        $this->assertEquals('Jean', $nom, 'input() doit récupérer les données POST');
    }

    /**
     * Test la vérification d'existence d'une clé
     * 
     * Cas: Vérifier si une donnée existe sans la récupérer
     * Résultat: a('nom') retourne true si 'nom' existe
     */
    public function testA(): void
    {
        $this->assertTrue(
            $this->requete->a('nom'),
            'a() doit retourner true si la clé existe en GET ou POST'
        );

        $this->assertTrue(
            $this->requete->a('page'),
            'a() doit déterminer l\'existence d\'une clé'
        );

        $this->assertFalse(
            $this->requete->a('inexistant'),
            'a() doit retourner false pour une clé inexistante'
        );
    }

    /**
     * Test les valeurs par défaut
     * 
     * Cas: Récupérer une donnée qui n'existe pas, avec une valeur par défaut
     * Résultat: obtenir('inexistant', 'default') retourne 'default'
     */
    public function testDefault(): void
    {
        $inexistant = $this->requete->obtenir('inexistant', 'default');
        $this->assertEquals(
            'default',
            $inexistant,
            'Les méthodes doivent supporter une valeur par défaut'
        );
    }
}
