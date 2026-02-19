<?php

namespace Bmvc\Cli;

/**
 * Gestion des codes ANSI pour colorisation CLI
 * Style officiel BMVC
 */
class Colors
{
    // Couleurs officielles BMVC
    public static string $blue = "\033[34m";      // BM
    public static string $orange = "\033[33m";    // VC
    public static string $cyan = "\033[36m";      // Cube/Logo
    public static string $green = "\033[32m";     // Succès
    public static string $red = "\033[31m";       // Erreur
    public static string $white = "\033[37m";     // Texte
    public static string $bold = "\033[1m";       // Gras
    public static string $reset = "\033[0m";      // Reset

    /**
     * Colore un texte en bleu (BM)
     */
    public static function blue(string $text): string
    {
        return self::$blue . $text . self::$reset;
    }

    /**
     * Colore un texte en orange (VC)
     */
    public static function orange(string $text): string
    {
        return self::$orange . $text . self::$reset;
    }

    /**
     * Colore un texte en cyan (logo/séparation)
     */
    public static function cyan(string $text): string
    {
        return self::$cyan . $text . self::$reset;
    }

    /**
     * Colore un texte en vert (succès)
     */
    public static function green(string $text): string
    {
        return self::$green . $text . self::$reset;
    }

    /**
     * Colore un texte en rouge (erreur)
     */
    public static function red(string $text): string
    {
        return self::$red . $text . self::$reset;
    }

    /**
     * Colore un texte en blanc
     */
    public static function white(string $text): string
    {
        return self::$white . $text . self::$reset;
    }

    /**
     * Texte gras
     */
    public static function bold(string $text): string
    {
        return self::$bold . $text . self::$reset;
    }
}
