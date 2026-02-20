<?php

namespace Core;

use Closure as Fermeture;

/**
 * ======================================================================
 * Routeur - Gestion avanc�e des routes
 * ======================================================================
 *
 * Fonctionnalit�s :
 * - Supports HTTP: GET, POST, PUT, DELETE, PATCH
 * - Param�tres dynamiques: /utilisateur/{id}
 * - Middlewares par route
 * - Groupes de routes avec pr�fixes
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
     * Enregistre une route pour toutes les m�thodes
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
     * Enregistre une route avec m�thode sp�cifi�e
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
     * Groupe de routes avec pr�fixe et middlewares
     */
    public static function groupe(array $options, Fermeture $callback): void
    {
        $prefixePrecedent = self::$prefixeActuel;
        $middlewaresPrecedents = self::$middlewaresActuels;

        // Appliquer le pr�fixe
        if (isset($options['prefixe'])) {
            self::$prefixeActuel = $prefixePrecedent . '/' . trim($options['prefixe'], '/');
        }

        // Appliquer les middlewares
        if (isset($options['middlewares'])) {
            $middlewares = is_array($options['middlewares']) ? $options['middlewares'] : [$options['middlewares']];
            self::$middlewaresActuels = array_merge(self::$middlewaresActuels, $middlewares);
        }

        // Ex�cuter le callback
        $callback();

        // Restaurer l'�tat pr�c�dent
        self::$prefixeActuel = $prefixePrecedent;
        self::$middlewaresActuels = $middlewaresPrecedents;
    }

    /**
     * Dispatcher la requ�te
     */
    public function dispatcher(Requete $requete, Reponse $reponse): void
    {
        $methode = $requete->methode();
        $chemin = $requete->chemin();

        // Trouver la route correspondante
        $route = $this->trouverRoute($methode, $chemin);

        if ($route === null) {
            // Lancer une exception 404
            throw new \Core\Exceptions\HttpNotFoundException("Page non trouv�e: $chemin");
        }

        $middlewares = $route->obtenirMiddlewares();
        $index = 0;

        $suivant = function (Requete $req) use (&$index, $middlewares, $route, $reponse, &$suivant): Reponse {
            if ($index >= count($middlewares)) {
                $this->executerRoute($route, $req, $reponse);
                return $reponse;
            }

            $middleware = $middlewares[$index++];

            if ($middleware instanceof Fermeture) {
                $resultat = $middleware($req, $suivant);
                return $resultat instanceof Reponse ? $resultat : $reponse;
            }

            if (is_string($middleware)) {
                $classeMiddleware = class_exists($middleware) ? $middleware : "Core\\Middlewares\\$middleware";

                if (!class_exists($classeMiddleware)) {
                    throw new \Exception("Middleware non trouvé: $classeMiddleware");
                }

                $instance = new $classeMiddleware();

                if (!method_exists($instance, 'traiter')) {
                    throw new \Exception("Methode traiter() introuvable sur le middleware: $classeMiddleware");
                }

                $resultat = $instance->traiter($req, $suivant);
                return $resultat instanceof Reponse ? $resultat : $reponse;
            }

            throw new \Exception('Type de middleware invalide.');
        };

        $suivant($requete);
    }

    /**
     * Trouve une route correspondant � la requ�te
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
     * Ex�cute une route
     */
    protected function executerRoute(Route $route, Requete $requete, Reponse $reponse): void
    {
        $action = $route->obtenirAction();

        // Si c'est une string (contr�leur@m�thode)
        if (is_string($action)) {
            $this->executerControleur($action, $requete, $reponse, $route->obtenirParametres());
        }
        // Si c'est une Fermeture
        elseif ($action instanceof Fermeture) {
            call_user_func_array($action, [$requete, $reponse, $route->obtenirParametres()]);
        }
    }

    /**
     * Ex�cute un contr�leur
     */
    protected function executerControleur(string $action, Requete $requete, Reponse $reponse, array $parametres): void
    {
        [$controleur, $methode] = explode('@', $action);

        $classe = "App\\Controleurs\\$controleur";

        if (!class_exists($classe)) {
            throw new \Exception("Contr�leur non trouv�: $classe");
        }

        $instance = new $classe();

        if (!method_exists($instance, $methode)) {
            throw new \Exception("M�thode non trouv�e: $classe@$methode");
        }

        // Appeler la m�thode avec injection des d�pendances
        $reflexion = new MethodeReflexion($instance, $methode);
        $params = [];

        foreach ($reflexion->obtenirParametres() as $param) {
            $type = $param->getType();

            if ($type && $type->getName() === Requete::class) {
                $params[] = $requete;
            } elseif ($type && $type->getName() === Reponse::class) {
                $params[] = $reponse;
            } else {
                // Essayer de r�cup�rer le param�tre par son nom
                $nomParam = $param->getName();
                $params[] = $parametres[$nomParam] ?? null;
            }
        }

        $result = call_user_func_array([$instance, $methode], $params);

        // Si le contr�leur retourne une cha�ne (ex: `return vue('...')`), l'afficher
        if (is_string($result)) {
            echo $result;
        }
    }

    /**
     * Obtient toutes les routes enregistr�es
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
