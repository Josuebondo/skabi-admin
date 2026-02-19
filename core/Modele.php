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
    protected array $parametres = [];
    protected bool $existe = false;

    public function __construct()
    {
        $this->bd = BaseBD::obtenir();

        // Deduire la table du nom de classe si non définie
        if (!isset($this->table)) {
            $classe = class_basename($this);
            $this->table = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $classe)) . 's';
        }
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
     * Récupère les résultats
     */
    public function obtenir(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(' AND ', $this->conditions);
        }

        $resultats = $this->bd->tous($sql, $this->parametres);

        return array_map(function ($donnees) {
            $modele = new static();
            $modele->donnees = $donnees;
            $modele->existe = true;
            return $modele;
        }, $resultats);
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
