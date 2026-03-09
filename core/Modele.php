<?php

namespace Core;

use Exception;

/**
 * ======================================================================
 * Modele - ORM inspiré d'Eloquent (mini-ORM)
 * ======================================================================
 * 
 * Fonctionnalités :
 * - tout() - Récupère tous les enregistrements
 * - trouver() - Trouve par ID
 * - ou() - Filtre WHERE
 * - obtenir() - Récupère les résultats
 * - premier() - Premier résultat
 * - creer() - Crée un enregistrement
 * - sauvegarder() - Sauvegarde (insert ou update)
 * - supprimer() - Supprime
 * - Sécurité SQL avec prepared statements
 * 
 * Utilisation:
 *   $articles = Article::tout();
 *   $article = Article::trouver(1);
 *   $article = Article::ou('titre', 'PHP')->premier();
 *   Article::creer(['titre' => 'Nouveau', 'contenu' => 'Texte']);
 */
class Modele

{



    protected BaseBD $bd;
    protected string $table;
    protected string $clesPrimaire = 'id';
    protected array $donnees = [];
    protected array $conditions = [];
    protected array $colonnes = ['*'];
    protected array $parametres = [];
    protected bool $existe = false;
    protected array $jointures = [];

    protected array $groupBys = [];
    protected array $orderBys = [];
    protected array $havingConditions = [];
    protected array $havingParametres = [];

    protected ?int $limite = null;
    protected ?int $decalage = null;
    public function __construct()
    {
        $this->bd = BaseBD::obtenir();

        // Deduire la table du nom de classe si non définie

    }
    /**
     * Définit les colonnes à sélectionner (avec alias possibles)
     * @param array $colonnes
     * @return self
     */
    public function selectionner(array $colonnes): self
    {
        $this->colonnes = $colonnes;
        return $this;
    }
    /**
     * Récupère tous les enregistrements
     */
    public static function tout(): array
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table}";
        $resultats = $instance->bd->tous($sql);

        return array_map(function ($donnees) {
            $modele = new static();
            $modele->donnees = $donnees;
            $modele->existe = true;
            return $modele;
        }, $resultats);
    }

    /**
     * Trouve un enregistrement par ID
     */
    public static function trouver($id): ?self
    {
        $instance = new static();
        $sql = "SELECT * FROM {$instance->table} WHERE {$instance->clesPrimaire} = :id LIMIT 1";
        $donnees = $instance->bd->une($sql, [':id' => $id]);

        if (!$donnees) {
            return null;
        }

        $instance->donnees = $donnees;
        $instance->existe = true;
        return $instance;
    }

    /**
     * Filtre avec une condition WHERE
     */
    public static function ou($colonne, $operateur = '=', $valeur = null): self
    {
        if ($valeur === null) {
            $valeur = $operateur;
            $operateur = '=';
        }

        $instance = new static();
        $instance->conditions[] = "$colonne $operateur ?";
        $instance->parametres[] = $valeur;
        return $instance;
    }
    /**
     * Condition HAVING 
     */
    public function a($colonne, $operateur = '=', $valeur = null): self
    {
        if ($valeur === null) {
            $valeur = $operateur;
            $operateur = '=';
        }

        $this->havingConditions[] = "$colonne $operateur ?";
        $this->havingParametres[] = $valeur;

        return $this;
    }
    /**
     * Alias SQL de a()
     */
    public function having($colonne, $operateur = '=', $valeur = null): self
    {
        return $this->a($colonne, $operateur, $valeur);
    }
    /**
     * Ajoute une condition ET
     */
    public function et($colonne, $operateur = '=', $valeur = null): self
    {
        if ($valeur === null) {
            $valeur = $operateur;
            $operateur = '=';
        }

        $this->conditions[] = "$colonne $operateur ?";
        $this->parametres[] = $valeur;
        return $this;
    }
    /**
     * GROUP BY 
     */
    public function grouperPar(string $colonne): self
    {
        $this->groupBys[] = $colonne;
        return $this;
    }

    /**
     * ORDER BY (français)
     */
    public function trierPar(string $colonne, string $direction = 'ASC'): self
    {
        $direction = strtoupper($direction);
        $this->orderBys[] = "$colonne $direction";
        return $this;
    }

    /**
     * Alias SQL
     */
    public function orderBy(string $colonne, string $direction = 'ASC'): self
    {
        return $this->trierPar($colonne, $direction);
    }

    /**
     * OR HAVING (français)
     */
    public function ouA($colonne, $operateur = '=', $valeur = null): self
    {
        if ($valeur === null) {
            $valeur = $operateur;
            $operateur = '=';
        }

        $this->havingConditions[] = "OR $colonne $operateur ?";
        $this->havingParametres[] = $valeur;

        return $this;
    }

    /**
     * Alias SQL
     */
    public function orHaving($colonne, $operateur = '=', $valeur = null): self
    {
        return $this->ouA($colonne, $operateur, $valeur);
    }
    /**
     * Alias SQL
     */
    public function groupBy(string $colonne): self
    {
        return $this->grouperPar($colonne);
    }

    /**
     * Relation appartientA (belongsTo)
     */
    public function appartientA(string $modele, string $cleEtrangere, string $cleProprietaire = 'id')
    {
        $instance = new $modele();

        $valeur = $this->donnees[$cleEtrangere] ?? null;

        if (!$valeur) {
            return null;
        }

        return $modele::ou($cleProprietaire, $valeur)->premier();
    }

    /**
     * Pagination complète
     */
    public function paginer(int $parPage = 10, int $page = 1): array
    {
        $offset = ($page - 1) * $parPage;

        // requête totale
        $sqlTotal = "SELECT COUNT(*) as total FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sqlTotal .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $result = $this->bd->une($sqlTotal, $this->parametres);
        $total = (int)$result['total'];

        // récupérer les données
        $this->limiter($parPage);
        $this->decalage($offset);

        $donnees = $this->obtenir();

        return [
            'donnees' => $donnees,
            'total' => $total,
            'par_page' => $parPage,
            'page' => $page,
            'total_pages' => ceil($total / $parPage)
        ];
    }

    /**
     * Relation aPlusieurs (hasMany)
     */
    public function aPlusieurs(string $modele, string $cleEtrangere, string $cleLocale = 'id'): array
    {
        $valeur = $this->donnees[$cleLocale] ?? null;

        if (!$valeur) {
            return [];
        }

        return $modele::ou($cleEtrangere, $valeur)->obtenir();
    }

    /**
     * Relation plusieursAPlusieurs (many-to-many)
     */
    public function plusieursAPlusieurs(
        string $modele,
        string $tablePivot,
        string $cleLocale,
        string $cleEtrangere
    ): array {

        $instance = new $modele();

        $valeurLocale = $this->donnees[$this->clesPrimaire] ?? null;

        if (!$valeurLocale) {
            return [];
        }

        $tableCible = $instance->table;

        $sql = "
        SELECT $tableCible.*
        FROM $tableCible
        INNER JOIN $tablePivot
        ON $tableCible.{$instance->clesPrimaire} = $tablePivot.$cleEtrangere
        WHERE $tablePivot.$cleLocale = ?
    ";

        $resultats = $this->bd->tous($sql, [$valeurLocale]);

        return array_map(function ($donnees) use ($modele) {

            $modeleInstance = new $modele();
            $modeleInstance->donnees = $donnees;
            $modeleInstance->existe = true;

            return $modeleInstance;
        }, $resultats);
    }
    /**
     * Limite le nombre de résultats
     */
    public function limiter(int $nombre): self
    {
        $this->limite = $nombre;
        return $this;
    }
    /**
     * Décale les résultats
     */
    public function decalage(int $nombre): self
    {
        $this->decalage = $nombre;
        return $this;
    }
    /**
     * Récupère les résultats
     */
    public function obtenir(): array
    {
        $colonnes = !empty($this->colonnes) ? implode(', ', $this->colonnes) : '*';
        $sql = "SELECT $colonnes FROM {$this->table}";

        // Ajout des jointures
        if (!empty($this->jointures)) {
            foreach ($this->jointures as $join) {
                $type = $join['type'] ?? 'INNER JOIN';
                $sql .= " {$type} {$join['table']} ON {$join['colonne_locale']} {$join['operateur']} {$join['colonne_etrangere']}";
            }
        }

        // WHERE
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        // GROUP BY
        if (!empty($this->groupBys)) {
            $sql .= " GROUP BY " . implode(', ', $this->groupBys);
        }

        // HAVING
        if (!empty($this->havingConditions)) {
            $sql .= " HAVING " . implode(' ', $this->havingConditions);
        }

        // ORDER BY
        if (!empty($this->orderBys)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBys);
        }
        // LIMIT
        if ($this->limite !== null) {
            $sql .= " LIMIT {$this->limite}";
        }

        // OFFSET
        if ($this->decalage !== null) {
            $sql .= " OFFSET {$this->decalage}";
        }
        // Exécution
        $params = array_merge($this->parametres, $this->havingParametres);
        $resultats = $this->bd->tous($sql, $params);

        return array_map(function ($donnees) {
            $modele = new static();
            $modele->donnees = $donnees;
            $modele->existe = true;
            return $modele;
        }, $resultats);
    }

    /**
     * Charge une relation
     */
    public function avec(string $table, string $colonneLocale, string $colonneEtrangere): self
    {
        return $this->joindre($table, $colonneLocale, $colonneEtrangere);
    }
    /**
     * Ajoute une jointure INNER JOIN à la requête
     */
    public function joindre(string $tableNom, string $colonneLocale, string $colonneEtrangere, string $operateur = '='): self
    {
        return $this->ajouterJointure('INNER JOIN', $tableNom, $colonneLocale, $colonneEtrangere, $operateur);
    }
    public function compter(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $resultat = $this->bd->une($sql, $this->parametres);

        return (int)$resultat['total'];
    }
    /**
     * Ajoute une jointure LEFT JOIN à la requête
     */
    public function joindreGauche(string $tableNom, string $colonneLocale, string $colonneEtrangere, string $operateur = '='): self
    {
        return $this->ajouterJointure('LEFT JOIN', $tableNom, $colonneLocale, $colonneEtrangere, $operateur);
    }

    /**
     * Ajoute une jointure RIGHT JOIN à la requête
     */
    public function joindreDroit(string $tableNom, string $colonneLocale, string $colonneEtrangere, string $operateur = '='): self
    {
        return $this->ajouterJointure('RIGHT JOIN', $tableNom, $colonneLocale, $colonneEtrangere, $operateur);
    }

    /**
     * Ajoute une jointure FULL OUTER JOIN à la requête
     */
    public function joindreExterne(string $tableNom, string $colonneLocale, string $colonneEtrangere, string $operateur = '='): self
    {
        return $this->ajouterJointure('FULL OUTER JOIN', $tableNom, $colonneLocale, $colonneEtrangere, $operateur);
    }

    /**
     * Ajoute une jointure générique à la requête
     */
    protected function ajouterJointure(string $type, string $tableNom, string $colonneLocale, string $colonneEtrangere, string $operateur = '='): self
    {
        $this->jointures[] = [
            'type' => $type,
            'table' => $tableNom,
            'colonne_locale' => $colonneLocale,
            'colonne_etrangere' => $colonneEtrangere,
            'operateur' => $operateur
        ];
        return $this;
    }
    /**
     * Récupère le premier résultat
     */
    public function premier(): ?self
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $sql .= " LIMIT 1";
        $donnees = $this->bd->une($sql, $this->parametres);

        if (!$donnees) {
            return null;
        }

        $this->donnees = $donnees;
        $this->existe = true;
        return $this;
    }

    /**
     * Crée un nouvel enregistrement
     */
    public static function creer(array $donnees): self
    {
        $instance = new static();
        $instance->donnees = $donnees;
        $instance->sauvegarder();
        return $instance;
    }

    /**
     * Sauvegarde l'enregistrement (insert ou update)
     */
    public function sauvegarder(): void
    {
        if ($this->existe) {
            $this->mettreAJour();
        } else {
            $this->inserer();
        }
    }

    /**
     * Insère un nouvel enregistrement
     */
    protected function inserer(): void
    {
        $colonnes = implode(', ', array_keys($this->donnees));
        $placeholders = implode(', ', array_fill(0, count($this->donnees), '?'));

        $sql = "INSERT INTO {$this->table} ($colonnes) VALUES ($placeholders)";
        $this->bd->executer($sql, array_values($this->donnees));

        // Récupérer l'ID inséré
        $this->donnees[$this->clesPrimaire] = $this->bd->dernierInsertId();
        $this->existe = true;
    }

    /**
     * Met à jour l'enregistrement actuel
     */
    public function mettreAJour(): void
    {
        if (!$this->existe) {
            throw new Exception("Impossible de mettre à jour un enregistrement qui n'existe pas");
        }

        $id = $this->donnees[$this->clesPrimaire];
        $updates = [];
        $valeurs = [];

        foreach ($this->donnees as $colonne => $valeur) {
            if ($colonne !== $this->clesPrimaire) {
                $updates[] = "$colonne = ?";
                $valeurs[] = $valeur;
            }
        }

        $valeurs[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE {$this->clesPrimaire} = ?";
        $this->bd->executer($sql, $valeurs);
    }

    /**
     * Supprime l'enregistrement
     */
    public function supprimer(): void
    {
        if (!$this->existe) {
            throw new Exception("Impossible de supprimer un enregistrement qui n'existe pas");
        }

        $id = $this->donnees[$this->clesPrimaire];
        $sql = "DELETE FROM {$this->table} WHERE {$this->clesPrimaire} = ?";
        $this->bd->executer($sql, [$id]);
        $this->existe = false;
    }

    /**
     * Accède aux attributs dynamiquement
     */
    public function __get($nom)
    {
        return $this->donnees[$nom] ?? null;
    }

    /**
     * Définit les attributs dynamiquement
     */
    public function __set($nom, $valeur)
    {
        $this->donnees[$nom] = $valeur;
    }

    /**
     * Vérifie si un attribut existe
     */
    public function __isset($nom): bool
    {
        return isset($this->donnees[$nom]);
    }

    /**
     * Retourne les données sous forme de tableau
     */
    public function enTableau(): array
    {
        return $this->donnees;
    }

    /**
     * Alias de enTableau() - compatible Laravel/PHP conventions
     */
    public function toArray(): array
    {
        return $this->enTableau();
    }

    /**
     * Retourne les données sous forme de JSON
     */
    public function enJSON(): string
    {
        return json_encode($this->donnees, JSON_UNESCAPED_UNICODE);
    }
}
