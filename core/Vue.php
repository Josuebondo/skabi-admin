<?php

namespace Core;

/**
 * ======================================================================
 * Vue - Moteur de vues avancé avec layouts et sections
 * ======================================================================
 * 
 * Fonctionnalités :
 * - Layouts et héritage de vues
 * - Sections nommées
 * - Inclusion de vues partielles
 * - Protection XSS avec echapper()
 */
class Vue
{
    protected string $cheminVues;
    protected array $donnees = [];
    protected array $sections = [];
    protected ?string $layoutActuel = null;
    protected static ?Vue $instance = null;

    public function __construct(string $cheminVues)
    {
        $this->cheminVues = $cheminVues;
        self::$instance = $this;
    }

    /**
     * Rend une vue
     */
    public function rendre(string $vue, array $donnees = []): string
    {
        $this->donnees = $donnees;
        $this->sections = [];
        $this->layoutActuel = null;

        $fichier = $this->resolveCheminVue($vue);

        if (!file_exists($fichier)) {
            throw new \Exception("Vue non trouvée: $fichier");
        }

        ob_start();
        extract($donnees, EXTR_SKIP);
        include $fichier;
        $contenu = ob_get_clean();

        // Si un layout est défini, le rendre avec le contenu
        if ($this->layoutActuel !== null) {
            $contenu = $this->rendreLayout($this->layoutActuel, $contenu);
            $this->layoutActuel = null;
        }

        return $contenu;
    }

    /**
     * Affiche une vue
     */
    public function afficher(string $vue, array $donnees = []): void
    {
        echo $this->rendre($vue, $donnees);
    }

    /**
     * Défini un layout pour la vue actuelle
     */
    public static function extends(string $layout): void
    {
        if (self::$instance) {
            self::$instance->layoutActuel = $layout;
        }
    }

    /**
     * Commence une section
     */
    public static function debut_section(string $nom): void
    {
        ob_start();
    }

    /**
     * Termine une section et la stocke
     */
    public static function fin_section(string $nom): void
    {
        $contenu = ob_get_clean();
        if (self::$instance) {
            self::$instance->sections[$nom] = $contenu;
        }
    }

    /**
     * Affiche le contenu d'une section
     */
    public static function section(string $nom, string $contenuParDefaut = ''): void
    {
        if (self::$instance && isset(self::$instance->sections[$nom])) {
            echo self::$instance->sections[$nom];
        } else {
            echo $contenuParDefaut;
        }
    }

    /**
     * Inclue une vue partielle
     */
    public function inclure(string $vue, array $donnees = []): string
    {
        $donnees = array_merge($this->donnees, $donnees);
        $fichier = $this->resolveCheminVue($vue);

        if (!file_exists($fichier)) {
            throw new \Exception("Vue partielle non trouvée: $fichier");
        }

        ob_start();
        extract($donnees, EXTR_SKIP);
        include $fichier;
        return ob_get_clean();
    }

    /**
     * Affiche une variable échappée (protection XSS)
     */
    public static function echapper($valeur): string
    {
        return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Raccourci pour échapper et afficher
     */
    public static function e($valeur): string
    {
        return self::echapper($valeur);
    }

    /**
     * Résout le chemin complet d'une vue
     */
    protected function resolveCheminVue(string $vue): string
    {
        $cheminVues = str_replace('\\', '/', $this->cheminVues);
        $chemin = $cheminVues . '/' . str_replace('.', '/', $vue) . '.php';
        return str_replace('/', DIRECTORY_SEPARATOR, $chemin);
    }

    /**
     * Rend un layout avec son contenu
     */
    protected function rendreLayout(string $layout, string $contenu): string
    {
        // Stocker le contenu principal dans une section spéciale
        // SEULEMENT si la section 'contenu' n'a pas déjà été définie
        if (!isset($this->sections['contenu'])) {
            $this->sections['contenu'] = $contenu;
        }

        $fichierLayout = $this->resolveCheminVue($layout);

        if (!file_exists($fichierLayout)) {
            throw new \Exception("Layout non trouvé: $fichierLayout");
        }

        ob_start();
        extract($this->donnees, EXTR_SKIP);
        include $fichierLayout;
        return ob_get_clean();
    }
}
