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
    // À compléter avec votre logique de base de données
}
