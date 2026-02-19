<?php

namespace Core;

use PDO;
use Exception;

/**
 * ======================================================================
 * BaseBD - Singleton PDO pour la connexion à la base de données
 * ======================================================================
 * 
 * Fonctionnalités :
 * - Connexion unique (singleton)
 * - Configuration via .env
 * - Gestion des erreurs BD
 * - Support de plusieurs drivers (MySQL, SQLite, PostgreSQL)
 */
class BaseBD
{
    private static ?BaseBD $instance = null;
    private PDO $connexion;
    private bool $debug;

    private function __construct()
    {
        $this->debug = env('DEBOGAGE', false) === 'true';
        $this->connexion = $this->creerConnexion();
    }

    /**
     * Obtient l'instance unique (singleton)
     */
    public static function obtenir(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Crée la connexion PDO
     */
    private function creerConnexion(): PDO
    {
        $driver = env('TYPE_CONNEXION', 'mysql');

        try {
            $dsn = $this->construireDSN($driver);

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $connexion = new PDO(
                $dsn,
                env('UTILISATEUR_BD', 'root'),
                env('MOT_DE_PASSE_BD', ''),
                $options
            );

            if ($this->debug) {
                // Log de succès en debug
                error_log('Connexion BD établie avec succès');
            }

            return $connexion;
        } catch (Exception $e) {
            throw new Exception('Erreur de connexion à la base de données: ' . $e->getMessage());
        }
    }

    /**
     * Construit la chaîne DSN selon le driver
     */
    private function construireDSN(string $driver): string
    {
        return match ($driver) {
            'mysql' => 'mysql:host=' . env('HOTE_BD', 'localhost')
                . ';port=' . env('PORT_BD', 3306)
                . ';dbname=' . env('NOM_BD', 'bmvc')
                . ';charset=utf8mb4',

            'sqlite' => 'sqlite:' . env('NOM_BD', 'database.sqlite'),

            'pgsql' => 'pgsql:host=' . env('HOTE_BD', 'localhost')
                . ';port=' . env('PORT_BD', 5432)
                . ';dbname=' . env('NOM_BD', 'bmvc'),

            default => throw new Exception("Driver DB non supporté: $driver")
        };
    }

    /**
     * Obtient la connexion PDO
     */
    public function connexion(): PDO
    {
        return $this->connexion;
    }

    /**
     * Exécute une requête simple
     */
    public function executer(string $sql, array $params = []): bool
    {
        try {
            $stmt = $this->connexion->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            if ($this->debug) {
                throw new Exception("Erreur SQL: {$e->getMessage()}\nRequête: $sql");
            }
            throw $e;
        }
    }

    /**
     * Récupère tous les résultats
     */
    public function tous(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->connexion->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            if ($this->debug) {
                throw new Exception("Erreur SQL: {$e->getMessage()}\nRequête: $sql");
            }
            throw $e;
        }
    }

    /**
     * Récupère une seule ligne
     */
    public function une(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->connexion->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() ?: null;
        } catch (Exception $e) {
            if ($this->debug) {
                throw new Exception("Erreur SQL: {$e->getMessage()}\nRequête: $sql");
            }
            throw $e;
        }
    }

    /**
     * Récupère la dernière ligne insérée
     */
    public function dernierInsertId(): string
    {
        return $this->connexion->lastInsertId();
    }

    /**
     * Commence une transaction
     */
    public function commencer(): void
    {
        $this->connexion->beginTransaction();
    }

    /**
     * Valide la transaction
     */
    public function valider(): void
    {
        $this->connexion->commit();
    }

    /**
     * Annule la transaction
     */
    public function annuler(): void
    {
        $this->connexion->rollBack();
    }
}
