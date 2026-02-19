<?php

/**
 * Script de vérification de la syntaxe PHP
 * Vérifie tous les fichiers .php dans app/, core/, src/
 */

$dirs = ['app', 'core', 'src'];
$errors = 0;
$files = 0;

echo "🔍 Vérification de la syntaxe PHP...\n\n";

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }

    $iterator = new RecursiveDirectoryIterator($dir);
    $iterator = new RecursiveIteratorIterator($iterator);
    $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

    foreach ($regex as $file) {
        $file = $file[0];
        $files++;

        $output = [];
        $code = 0;
        exec("php -l \"" . addslashes($file) . "\"", $output, $code);

        if ($code !== 0) {
            $errors++;
            echo "❌ " . $file . "\n";
            foreach ($output as $line) {
                echo "   " . $line . "\n";
            }
        } else {
            echo "✓ " . $file . "\n";
        }
    }
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ Total: $files fichier(s) vérifiés\n";

if ($errors > 0) {
    echo "❌ Erreurs trouvées: $errors\n";
    exit(1);
} else {
    echo "✅ Aucune erreur de syntaxe!\n";
    exit(0);
}
