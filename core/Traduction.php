<?php

/**
 * ======================================================================
 * Internationalisation (i18n) - Multi-langues
 * ======================================================================
 */

namespace Core;

class Traduction
{
    protected static string $langueCourante = 'fr';
    protected static array $traductions = [];

    /**
     * Charger une langue
     */
    public static function charger(string $langue = 'fr'): void
    {
        self::$langueCourante = $langue;

        $cheminFichier = __DIR__ . "/../ressources/traductions/{$langue}.php";

        if (file_exists($cheminFichier)) {
            self::$traductions[$langue] = require $cheminFichier;
        }
    }

    /**
     * Obtenir une traduction
     * 
     * Usage: trans('messages.bienvenue')
     *        trans('messages.utilisateur', ['nom' => 'Bondo'])
     */
    public static function obtenir(string $cle, array $remplacements = []): string
    {
        if (empty(self::$traductions[self::$langueCourante])) {
            self::charger(self::$langueCourante);
        }

        $traductions = self::$traductions[self::$langueCourante] ?? [];
        $parties = explode('.', $cle);

        foreach ($parties as $partie) {
            if (isset($traductions[$partie])) {
                $traductions = $traductions[$partie];
            } else {
                return $cle; // Clé non trouvée
            }
        }

        $resultat = $traductions;

        // Remplacer les placeholders
        foreach ($remplacements as $cle => $valeur) {
            $resultat = str_replace(":{$cle}", $valeur, $resultat);
        }

        return $resultat;
    }

    /**
     * Changer la langue
     */
    public static function changer(string $langue): void
    {
        self::$langueCourante = $langue;
    }

    /**
     * Obtenir la langue courante
     */
    public static function langue(): string
    {
        return self::$langueCourante;
    }

    /**
     * Lister les langues disponibles
     */
    public static function languesDisponibles(): array
    {
        $dossier = __DIR__ . '/../ressources/traductions';
        $langues = [];

        if (is_dir($dossier)) {
            foreach (glob("{$dossier}/*.php") as $fichier) {
                $langues[] = basename($fichier, '.php');
            }
        }

        return $langues;
    }
}
