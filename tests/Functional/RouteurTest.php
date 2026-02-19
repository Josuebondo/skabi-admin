<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use Core\Routeur;
use Core\Route;

/**
 * ======================================================================
 * Tests du Routeur - Système de routing HTTP
 * ======================================================================
 * 
 * Teste la création et la configuration des routes HTTP.
 * 
 * Utilisation réelle :
 *   Routeur::obtenir('/articles', 'ArticleControleur@lister')
 *       ->nom('articles.lister')
 *       ->middleware('auth');
 */
class RouteurTest extends TestCase
{
    /**
     * Test que toutes les méthodes HTTP peuvent être enregistrées
     * 
     * Cas: Créer des routes pour chaque verbe HTTP
     * Méthodes: GET, POST, PUT, DELETE
     * Résultat: Chaque méthode doit être disponible
     */
    public function testRoutesCanBeRegistered(): void
    {
        $this->assertTrue(
            method_exists(Routeur::class, 'obtenir'),
            'Routeur::obtenir() doit exister pour créer une route GET'
        );

        $this->assertTrue(
            method_exists(Routeur::class, 'publier'),
            'Routeur::publier() doit exister pour créer une route POST'
        );

        $this->assertTrue(
            method_exists(Routeur::class, 'mettre'),
            'Routeur::mettre() doit exister pour créer une route PUT'
        );

        $this->assertTrue(
            method_exists(Routeur::class, 'supprimer'),
            'Routeur::supprimer() doit exister pour créer une route DELETE'
        );
    }

    /**
     * Test que les routes retournent un objet Route
     * 
     * Cas: Créer une route GET
     * Résultat: obtenir() retourne un objet Route (pour le chaînage)
     */
    public function testObtenerReturnsRoute(): void
    {
        $route = Routeur::obtenir('/test', 'TestControleur@action');

        $this->assertInstanceOf(
            Route::class,
            $route,
            'Routeur::obtenir() doit retourner un objet Route pour permettre le chaînage'
        );
    }

    /**
     * Test que les routes peuvent être nommées
     * 
     * Cas: Créer une route GET avec un nom
     * Route: /articles/{id}
     * Nom: 'articles.afficher'
     * Résultat: nom() retourne toujours la route (pour le chaînage)
     * 
     * Utilité: Générer des URLs sans hardcoder les chemins
     * url('articles.afficher', ['id' => 1]) → /articles/1
     */
    public function testRouteNaming(): void
    {
        $route = Routeur::obtenir('/articles/{id}', 'ArticleControleur@afficher');
        $routeNamed = $route->nom('articles.afficher');

        $this->assertInstanceOf(
            Route::class,
            $routeNamed,
            'La méthode nom() doit retourner la route pour permettre le chaînage'
        );
    }

    /**
     * Test que les routes acceptent des middlewares
     * 
     * Cas: Protéger une route avec authentification
     * Route: /admin
     * Middleware: 'auth'
     * Résultat: middleware() retourne la route (pour le chaînage)
     * 
     * Utilité: Vérifier l'authentification avant d'exécuter le contrôleur
     */
    public function testRouteMiddleware(): void
    {
        $route = Routeur::obtenir('/admin', 'AdminControleur@index');
        $routeWithMiddleware = $route->middleware('auth');

        $this->assertInstanceOf(
            Route::class,
            $routeWithMiddleware,
            'La méthode middleware() doit retourner la route pour permettre le chaînage'
        );
    }
}
