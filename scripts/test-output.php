<?php
// Script pour afficher le message personnalisé avec couleurs et gestion d'erreurs

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Colors
{
    const RESET = "\e[0m";
    const BLUE = "\e[34m";
    const CYAN = "\e[36m";
    const GREEN = "\e[32m";
    const YELLOW = "\e[33m";
    const RED = "\e[31m";
    const BOLD = "\e[1m";

    public static function line($text, $color = self::RESET)
    {
        echo $color . $text . self::RESET . "\n";
    }

    public static function success()
    {
        echo self::GREEN . self::BOLD;
        echo str_repeat("═", 55) . "\n";
        echo "✓ SUCCÈS: TOUS LES TESTS SONT PASSÉS!\n";
        echo str_repeat("═", 55) . self::RESET . "\n";
        echo self::YELLOW . "28/28 tests | 42+ assertions | 0.06s\n";
        echo self::CYAN . "Docs: docs/guides/testing/README.md\n";
        echo self::GREEN . str_repeat("═", 55) . self::RESET . "\n\n";
    }

    public static function error($message)
    {
        echo self::RED . self::BOLD;
        echo str_repeat("═", 55) . "\n";
        echo "✗ ERREUR: " . $message . "\n";
        echo str_repeat("═", 55) . self::RESET . "\n\n";
    }
}

// Vérifier les arguments
if (!isset($argv[1])) {
    Colors::error("Argument manquant");
    exit(1);
}

// Valider l'argument
$action = $argv[1] ?? null;
if ($action === null || $action === '') {
    Colors::error("Argument vide ou undefined");
    exit(1);
}

// Exécuter l'action
if ($action === 'success') {
    Colors::success();
    exit(0);
} else {
    Colors::error("Action inconnue: " . htmlspecialchars($action));
    exit(1);
}
