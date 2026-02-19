<?php

namespace Core;

/**
 * ======================================================================
 * Route - Classe représentant une route
 * ======================================================================
 */
class Route
{
    protected string $methode;
    protected string $chemin;
    protected $action;
    protected array $parametres = [];
    protected array $middlewares = [];
    protected string $nom = '';
    protected array $contraintes = [];

    public function __construct(string $methode, string $chemin, $action)
    {
        $this->methode = $methode;
        $this->chemin = $chemin;
        $this->action = $action;
    }

    /**
     * Ajoute une contrainte regex pour un paramètre
     * Exemple: $route->ou('id', '[0-9]+')
     */
    public function ou(string $param, string $regex): self
    {
        $this->contraintes[$param] = $regex;
        return $this;
    }

    /**
     * Obtient la méthode HTTP
     */
    public function obtenirMethode(): string
    {
        return $this->methode;
    }

    /**
     * Obtient le chemin
     */
    public function obtenirChemin(): string
    {
        return $this->chemin;
    }

    /**
     * Obtient l'action (contrôleur@méthode ou closure)
     */
    public function obtenirAction()
    {
        return $this->action;
    }

    /**
     * Obtient les paramètres extraits
     */
    public function obtenirParametres(): array
    {
        return $this->parametres;
    }

    /**
     * Définit les paramètres
     */
    public function definirParametres(array $parametres): self
    {
        $this->parametres = $parametres;
        return $this;
    }

    /**
     * Ajoute un middleware
     */
    public function middleware($middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Obtient les middlewares
     */
    public function obtenirMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Nomme la route
     */
    public function nom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * Obtient le nom
     */
    public function obtenirNom(): string
    {
        return $this->nom;
    }

    /**
     * Vérifie si le chemin correspond
     */
    public function correspond(string $chemin): bool
    {
        // Chemin exact
        if ($this->chemin === $chemin) {
            return true;
        }

        // Chemin avec paramètres
        $pattern = $this->creerPattern($this->chemin);
        if (preg_match($pattern, $chemin, $matches)) {
            // Extraire les paramètres
            array_shift($matches); // Supprimer la première correspondance complète
            $parametres = [];
            preg_match_all('/{(\w+)}/', $this->chemin, $noms);
            foreach ($noms[1] as $index => $nom) {
                $parametres[$nom] = $matches[$index] ?? null;
            }
            $this->definirParametres($parametres);
            return true;
        }

        return false;
    }

    /**
     * Crée un pattern regex à partir du chemin
     */
    protected function creerPattern(string $chemin): string
    {
        $pattern = preg_replace_callback('/{(\w+)}/', function ($matches) {
            $param = $matches[1];
            // Utiliser la contrainte si elle existe, sinon un wildcard par défaut
            $regex = $this->contraintes[$param] ?? '[^/]+';
            return "($regex)";
        }, $chemin);
        return '#^' . $pattern . '$#';
    }
}
