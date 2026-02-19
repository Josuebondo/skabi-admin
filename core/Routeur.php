<?php

namespace Core;

use Closure as Fermeture;

/**
 * ======================================================================
 * Routeur - Gestion avancée des routes
 * ======================================================================
 * 
 * Fonctionnalités :
 * - Supports HTTP: GET, POST, PUT, DELETE, PATCH
 * - Paramètres dynamiques: /utilisateur/{id}
 * - Middlewares par route
 * - Groupes de routes avec préfixes
 * - Nommage des routes
 */
class Routeur
{
    protected static array $routes = [];
    protected static array $groupes = [];
    protected static string $prefixeActuel = '';
    protected static array $middlewaresActuels = [];

    /**
     * Enregistre une route GET
     */
    public static function obtenir(string $chemin, $action): Route
    {
        return self::enregistrer('GET', $chemin, $action);
    }

    /**
     * Enregistre une route POST
     */
    public static function publier(string $chemin, $action): Route
    {
        return self::enregistrer('POST', $chemin, $action);
    }

    /**
     * Enregistre une route PUT
     */
    public static function mettre(string $chemin, $action): Route
    {
        return self::enregistrer('PUT', $chemin, $action);
    }

    /**
     * Enregistre une route DELETE
     */
    public static function supprimer(string $chemin, $action): Route
    {
        return self::enregistrer('DELETE', $chemin, $action);
    }

    /**
     * Enregistre une route PATCH
     */
    public static function patcher(string $chemin, $action): Route
    {
        return self::enregistrer('PATCH', $chemin, $action);
    }

    /**
     * Enregistre une route pour toutes les méthodes
     */
    public static function tous(string $chemin, $action): array
    {
        return [
            self::obtenir($chemin, $action),
            self::publier($chemin, $action),
            self::mettre($chemin, $action),
            self::supprimer($chemin, $action),
            self::patcher($chemin, $action),
        ];
    }

    /**
     * Enregistre une route avec méthode spécifiée
     */
    protected static function enregistrer(string $methode, string $chemin, $action): Route
    {
        $chemin = self::$prefixeActuel . $chemin;

        $route = new Route($methode, $chemin, $action);

        // Ajouter les middlewares du groupe
        foreach (self::$middlewaresActuels as $middleware) {
            $route->middleware($middleware);
        }

        self::$routes[] = $route;
        return $route;
    }

    /**
     * Groupe de routes avec préfixe et middlewares
     */
    public static function groupe(array $options, Fermeture $callback): void
    {
        $prefixePrecedent = self::$prefixeActuel;
        $middlewaresPrecedents = self::$middlewaresActuels;

        // Appliquer le préfixe
        if (isset($options['prefixe'])) {
            self::$prefixeActuel = $prefixePrecedent . '/' . trim($options['prefixe'], '/');
        }

        // Appliquer les middlewares
        if (isset($options['middlewares'])) {
            $middlewares = is_array($options['middlewares']) ? $options['middlewares'] : [$options['middlewares']];
            self::$middlewaresActuels = array_merge(self::$middlewaresActuels, $middlewares);
        }

        // Exécuter le callback
        $callback();

        // Restaurer l'état précédent
        self::$prefixeActuel = $prefixePrecedent;
        self::$middlewaresActuels = $middlewaresPrecedents;
    }

    /**
     * Dispatcher la requête
     */
    public function dispatcher(Requete $requete, Reponse $reponse): void
    {
        $methode = $requete->methode();
        $chemin = $requete->chemin();

        // Trouver la route correspondante
        $route = $this->trouverRoute($methode, $chemin);

        if ($route === null) {
            // Lancer une exception 404
            throw new \Core\Exceptions\HttpNotFoundException("Page non trouvée: $chemin");
        }

        // Exécuter les middlewares
        foreach ($route->obtenirMiddlewares() as $middleware) {
            // À implémenter selon les middlewares disponibles
        }

        // Exécuter la route
        $this->executerRoute($route, $requete, $reponse);
    }

    /**
     * Trouve une route correspondant à la requête
     */
    protected function trouverRoute(string $methode, string $chemin): ?Route
    {
        foreach (self::$routes as $route) {
            if ($route->obtenirMethode() === $methode && $route->correspond($chemin)) {
                return $route;
            }
        }
        return null;
    }

    /**
     * Exécute une route
     */
    protected function executerRoute(Route $route, Requete $requete, Reponse $reponse): void
    {
        $action = $route->obtenirAction();

        // Si c'est une string (contrôleur@méthode)
        if (is_string($action)) {
            $this->executerControleur($action, $requete, $reponse, $route->obtenirParametres());
        }
        // Si c'est une Fermeture
        elseif ($action instanceof Fermeture) {
            call_user_func_array($action, [$requete, $reponse, $route->obtenirParametres()]);
        }
    }

    /**
     * Exécute un contrôleur
     */
    protected function executerControleur(string $action, Requete $requete, Reponse $reponse, array $parametres): void
    {
        [$controleur, $methode] = explode('@', $action);

        $classe = "App\\Controleurs\\$controleur";

        if (!class_exists($classe)) {
            throw new \Exception("Contrôleur non trouvé: $classe");
        }

        $instance = new $classe();

        if (!method_exists($instance, $methode)) {
            throw new \Exception("Méthode non trouvée: $classe@$methode");
        }

        // Appeler la méthode avec injection des dépendances
        $reflexion = new MethodeReflexion($instance, $methode);
        $params = [];

        foreach ($reflexion->obtenirParametres() as $param) {
            $type = $param->getType();

            if ($type && $type->getName() === Requete::class) {
                $params[] = $requete;
            } elseif ($type && $type->getName() === Reponse::class) {
                $params[] = $reponse;
            } else {
                // Essayer de récupérer le paramètre par son nom
                $nomParam = $param->getName();
                $params[] = $parametres[$nomParam] ?? null;
            }
        }

        call_user_func_array([$instance, $methode], $params);
    }

    /**
     * Obtient toutes les routes enregistrées
     */
    public static function obtenirRoutes(): array
    {
        return self::$routes;
    }

    /**
     * Trouve une route par son nom
     */
    public static function trouverParNom(string $nom): ?Route
    {
        foreach (self::$routes as $route) {
            if ($route->obtenirNom() === $nom) {
                return $route;
            }
        }
        return null;
    }
}
