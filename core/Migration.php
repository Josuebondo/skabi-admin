<?php

namespace Core;

use PDO;

/**
 * ======================================================================
 * Migration - Système de versionnement des bases de données
 * ======================================================================
 * 
 * Permet de créer et gérer les migrations des tables
 * 
 * Utilisation:
 *   class CreerTableArticles extends Migration {
 *       public function vers(): void {
 *           $this->creerTable('articles', function($table) {
 *               $table->incremente('id');
 *               $table->chaine('titre');
 *               $table->texte('contenu');
 *               $table->timestamps();
 *           });
 *       }
 *       
 *       public function retour(): void {
 *           $this->supprimerTable('articles');
 *       }
 *   }
 */
abstract class Migration
{
    protected BaseBD $bd;
    protected PDO $connexion;
    protected string $table;

    public function __construct()
    {
        $this->bd = BaseBD::obtenir();
        $this->connexion = $this->bd->connexion();
    }

    /**
     * Exécute la migration
     */
    abstract public function vers(): void;

    /**
     * Annule la migration
     */
    abstract public function retour(): void;

    /**
     * Crée une table
     */
    protected function creerTable(string $nom, callable $callback): void
    {
        $this->table = $nom;

        $sql = "CREATE TABLE IF NOT EXISTS $nom (\n";

        $colonnes = [];
        $callback($this);

        $sql = rtrim($sql, ",\n") . "\n)";

        $this->connexion->exec($sql);
    }

    /**
     * Supprime une table
     */
    protected function supprimerTable(string $nom): void
    {
        $sql = "DROP TABLE IF EXISTS $nom";
        $this->connexion->exec($sql);
    }

    /**
     * Ajoute une colonne auto-incrémentée
     */
    protected function incremente(string $colonne): void
    {
        // À implémenter selon le driver BD
    }

    /**
     * Ajoute une colonne texte
     */
    protected function chaine(string $colonne, int $longueur = 255): void
    {
        // À implémenter selon le driver BD
    }

    /**
     * Ajoute une colonne texte long
     */
    protected function texte(string $colonne): void
    {
        // À implémenter selon le driver BD
    }

    /**
     * Ajoute des timestamps (created_at, updated_at)
     */
    protected function timestamps(): void
    {
        // À implémenter selon le driver BD
    }
}
