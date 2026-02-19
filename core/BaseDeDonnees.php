<?php

namespace Core;

/**
 * Classe BaseDeDonnees
 * 
 * Encapsule la connexion PDO
 * Gère les requêtes SQL brutes
 * 
 * Responsabilités :
 * - Créer la connexion PDO
 * - Exécuter les requêtes SQL
 * - Récupérer les résultats
 * - Gérer les transactions
 * - Gérer les erreurs
 * 
 * Utilisation :
 *   $db = new BaseDeDonnees();
 *   $stmt = $db->executer('SELECT * FROM users WHERE id = ?', [1]);
 *   $user = $stmt->fetch(PDO::FETCH_ASSOC);
 *   
 *   // Ou statiquement :
 *   BaseDeDonnees::insert('users', ['nom' => 'Jean', 'email' => 'jean@example.com']);
 *   BaseDeDonnees::update('users', ['nom' => 'Jean Dupont'], ['id' => 1]);
 *   BaseDeDonnees::delete('users', ['id' => 1]);
 */

class BaseDeDonnees
{
    /**
     * Insère un enregistrement dans une table
     * 
     * Utilisation :
     *   BaseDeDonnees::inserer('utilisateurs', ['nom' => 'Jean', 'email' => 'jean@example.com']);
     * 
     * @param string $table Nom de la table
     * @param array $donnees Données à insérer ['colonne' => 'valeur', ...]
     * @return string ID de l'enregistrement inséré
     * @throws Exception
     */
    public static function inserer(string $table, array $donnees): string
    {
        try {
            $bd = BaseBD::obtenir();
            $colonnes = implode(', ', array_keys($donnees));
            $placeholders = implode(', ', array_fill(0, count($donnees), '?'));

            $sql = "INSERT INTO {$table} ({$colonnes}) VALUES ({$placeholders})";
            $bd->executer($sql, array_values($donnees));

            return $bd->dernierInsertId();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de l'insertion dans {$table}: " . $e->getMessage());
        }
    }

    /**
     * Met à jour des enregistrements dans une table
     * 
     * Utilisation :
     *   BaseDeDonnees::mettre_a_jour('utilisateurs', ['nom' => 'Jean Dupont'], ['id' => 1]);
     *   BaseDeDonnees::mettre_a_jour('utilisateurs', ['actif' => 1], ['role' => 'admin']);
     * 
     * @param string $table Nom de la table
     * @param array $donnees Données à mettre à jour ['colonne' => 'valeur', ...]
     * @param array $conditions Conditions WHERE ['colonne' => 'valeur', ...]
     * @return bool Succès de l'opération
     * @throws Exception
     */
    public static function mettre_a_jour(string $table, array $donnees, array $conditions): bool
    {
        try {
            $bd = BaseBD::obtenir();

            // Construire les SET
            $updates = [];
            $valeurs = [];
            foreach ($donnees as $colonne => $valeur) {
                $updates[] = "{$colonne} = ?";
                $valeurs[] = $valeur;
            }

            // Construire les WHERE
            $wheres = [];
            foreach ($conditions as $colonne => $valeur) {
                $wheres[] = "{$colonne} = ?";
                $valeurs[] = $valeur;
            }

            $sql = "UPDATE {$table} SET " . implode(', ', $updates);
            if (!empty($wheres)) {
                $sql .= " WHERE " . implode(' AND ', $wheres);
            }

            return $bd->executer($sql, $valeurs);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la mise à jour dans {$table}: " . $e->getMessage());
        }
    }

    /**
     * Supprime des enregistrements d'une table
     * 
     * Utilisation :
     *   BaseDeDonnees::supprimer('utilisateurs', ['id' => 1]);
     *   BaseDeDonnees::supprimer('sessions', ['expiration' => '<' . time()]);
     * 
     * @param string $table Nom de la table
     * @param array $conditions Conditions WHERE ['colonne' => 'valeur', ...]
     * @return bool Succès de l'opération
     * @throws Exception
     */
    public static function supprimer(string $table, array $conditions): bool
    {
        try {
            $bd = BaseBD::obtenir();

            // Construire les WHERE
            $wheres = [];
            $valeurs = [];
            foreach ($conditions as $colonne => $valeur) {
                $wheres[] = "{$colonne} = ?";
                $valeurs[] = $valeur;
            }

            $sql = "DELETE FROM {$table}";
            if (!empty($wheres)) {
                $sql .= " WHERE " . implode(' AND ', $wheres);
            }

            return $bd->executer($sql, $valeurs);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la suppression dans {$table}: " . $e->getMessage());
        }
    }

    /**
     * Récupère un enregistrement
     * 
     * Utilisation :
     *   $user = BaseDeDonnees::obtenir('utilisateurs', ['id' => 1]);
     * 
     * @param string $table Nom de la table
     * @param array $conditions Conditions WHERE
     * @return array|null Enregistrement ou null
     * @throws Exception
     */
    public static function obtenir(string $table, array $conditions): ?array
    {
        try {
            $bd = BaseBD::obtenir();

            $wheres = [];
            $valeurs = [];
            foreach ($conditions as $colonne => $valeur) {
                $wheres[] = "{$colonne} = ?";
                $valeurs[] = $valeur;
            }

            $sql = "SELECT * FROM {$table}";
            if (!empty($wheres)) {
                $sql .= " WHERE " . implode(' AND ', $wheres);
            }
            $sql .= " LIMIT 1";

            return $bd->une($sql, $valeurs);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la lecture de {$table}: " . $e->getMessage());
        }
    }

    /**
     * Récupère tous les enregistrements
     * 
     * Utilisation :
     *   $utilisateurs = BaseDeDonnees::tous('utilisateurs');
     *   $actifs = BaseDeDonnees::tous('utilisateurs', ['actif' => 1]);
     * 
     * @param string $table Nom de la table
     * @param array $conditions Conditions WHERE (optionnel)
     * @return array Liste des enregistrements
     * @throws Exception
     */
    public static function tous(string $table, array $conditions = []): array
    {
        try {
            $bd = BaseBD::obtenir();

            $wheres = [];
            $valeurs = [];
            foreach ($conditions as $colonne => $valeur) {
                $wheres[] = "{$colonne} = ?";
                $valeurs[] = $valeur;
            }

            $sql = "SELECT * FROM {$table}";
            if (!empty($wheres)) {
                $sql .= " WHERE " . implode(' AND ', $wheres);
            }

            return $bd->tous($sql, $valeurs);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la lecture de {$table}: " . $e->getMessage());
        }
    }
}
