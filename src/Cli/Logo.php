<?php

namespace Bmvc\Cli;

/**
 * Logo officiel BMVC colorisé
 * Style CLI professionnel avec codes ANSI
 */
class Logo
{
    /**
     * Affiche le logo BMVC colorisé
     */
    public static function afficher(): void
    {
        echo "\n";

        // Logo ASCII colorisé par lettre : B (bleu), M (cyan), V (orange), C (vert)
        echo Colors::$blue . "     ██████╗" . Colors::$reset;
        echo Colors::$cyan . "  ███╗   ███╗" . Colors::$reset;
        echo Colors::$orange . " ██╗   ██╗" . Colors::$reset;
        echo Colors::$green . "  ██████╗ \n" . Colors::$reset;

        echo Colors::$blue . "     ██╔══██╗" . Colors::$reset;
        echo Colors::$cyan . "  ████╗ ████║" . Colors::$reset;
        echo Colors::$orange . " ██║   ██║" . Colors::$reset;
        echo Colors::$green . " ██╔════╝\n" . Colors::$reset;

        echo Colors::$blue . "     ██████╔╝" . Colors::$reset;
        echo Colors::$cyan . "  ██╔████╔██║" . Colors::$reset;
        echo Colors::$orange . " ██║   ██║" . Colors::$reset;
        echo Colors::$green . " ██║\n" . Colors::$reset;

        echo Colors::$blue . "     ██╔══██╗" . Colors::$reset;
        echo Colors::$cyan . "  ██║╚██╔╝██║" . Colors::$reset;
        echo Colors::$orange . " ╚██╗ ██╔╝" . Colors::$reset;
        echo Colors::$green . " ██║\n" . Colors::$reset;

        echo Colors::$blue . "     ██████╔╝" . Colors::$reset;
        echo Colors::$cyan . "  ██║ ╚═╝ ██║" . Colors::$reset;
        echo Colors::$orange . "  ╚████╔╝" . Colors::$reset;
        echo Colors::$green . "  ╚██████╗ \n" . Colors::$reset;

        echo Colors::$blue . "     ╚═════╝" . Colors::$reset;
        echo Colors::$cyan . "   ╚═╝     ╚═╝" . Colors::$reset;
        echo Colors::$white . "  — Bondo MVC Framework\n";

        echo Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" . Colors::$reset;

        echo Colors::$green;
        echo "✔ Framework PHP MVC moderne\n";
        echo "✔ CLI natif : php bmvc\n";
        echo "✔ Génération Controller / Model / Views\n";
        echo "✔ Inspiré de Laravel & Symfony\n";
        echo "✔ Léger • Rapide • Clair\n";
        echo Colors::$reset;

        echo Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n" . Colors::$reset;
        echo Colors::$white . "Version : " . Colors::$orange . self::obtenirVersion() . "\n";
        echo Colors::$white . "Framework : " . Colors::$cyan . "BMVC\n";
        echo Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n" . Colors::$reset;
    }

    /**
     * Récupérer la version depuis composer.json
     */
    private static function obtenirVersion(): string
    {
        $composerPath = dirname(__DIR__, 2) . '/composer.json';

        if (!file_exists($composerPath)) {
            return '1.0.0';
        }

        try {
            $composer = json_decode(file_get_contents($composerPath), true);

            if (isset($composer['version'])) {
                return $composer['version'];
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner la version par défaut
        }

        return '1.0.0';
    }

    /**
     * Affiche le logo condensé (pour les messages rapides)
     */
    public static function afficherCondense(): void
    {
        echo Colors::$blue . "BM" . Colors::$reset;
        echo Colors::$orange . "VC" . Colors::$reset;
    }

    /**
     * Affiche un message de succès stylisé
     */
    public static function succes(string $message): void
    {
        echo Colors::$green . "✔ " . Colors::$reset;
        echo Colors::$white . $message . Colors::$reset . "\n";
    }

    /**
     * Affiche un message d'erreur stylisé
     */
    public static function erreur(string $message): void
    {
        echo Colors::$red . "✘ " . Colors::$reset;
        echo Colors::$white . $message . Colors::$reset . "\n";
    }

    /**
     * Affiche un message info stylisé
     */
    public static function info(string $message): void
    {
        echo Colors::$cyan . "➡ " . Colors::$reset;
        echo Colors::$white . $message . Colors::$reset . "\n";
    }
}
