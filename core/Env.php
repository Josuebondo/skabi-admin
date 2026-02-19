<?php

namespace Core;

/**
 * ======================================================================
 * Env - Gestionnaire des variables d'environnement
 * ======================================================================
 * 
 * Charge et accède aux variables du fichier .env
 */
class Env
{
    private static array $variables = [];
    private static bool $charge = false;

    /**
     * Charge les variables d'environnement depuis le fichier .env
     */
    public static function charger(string $cheminEnv = '.env'): void
    {
        if (self::$charge) {
            return;
        }

        if (!file_exists($cheminEnv)) {
            throw new \Exception("Fichier .env non trouvé: $cheminEnv");
        }

        $contenu = file_get_contents($cheminEnv);
        $lignes = explode("\n", $contenu);

        foreach ($lignes as $ligne) {
            $ligne = trim($ligne);

            // Ignorer les commentaires et les lignes vides
            if (empty($ligne) || $ligne[0] === '#') {
                continue;
            }

            // Parser les variables
            if (strpos($ligne, '=') !== false) {
                [$cle, $valeur] = explode('=', $ligne, 2);
                $cle = trim($cle);
                $valeur = trim($valeur);

                // Retirer les guillemets si présents
                if (($valeur[0] ?? '') === '"' && ($valeur[-1] ?? '') === '"') {
                    $valeur = substr($valeur, 1, -1);
                }

                self::$variables[$cle] = $valeur;
            }
        }

        self::$charge = true;
    }

    /**
     * Obtient une variable d'environnement
     * 
     * @param string $cle Clé de la variable
     * @param mixed $defaut Valeur par défaut
     * @return mixed Valeur de la variable
     */
    public static function obtenir(string $cle, mixed $defaut = null): mixed
    {
        if (!self::$charge) {
            self::charger();
        }

        return self::$variables[$cle] ?? $defaut;
    }

    /**
     * Définit une variable d'environnement
     */
    public static function definir(string $cle, mixed $valeur): void
    {
        if (!self::$charge) {
            self::charger();
        }

        self::$variables[$cle] = $valeur;
    }

    /**
     * Vérifie si une variable existe
     */
    public static function existe(string $cle): bool
    {
        if (!self::$charge) {
            self::charger();
        }

        return isset(self::$variables[$cle]);
    }

    /**
     * Obtient toutes les variables
     */
    public static function tous(): array
    {
        if (!self::$charge) {
            self::charger();
        }

        return self::$variables;
    }
}
