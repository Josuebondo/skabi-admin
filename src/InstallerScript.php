<?php

namespace Bmvc;

use Composer\Script\Event;
use Bmvc\Cli\Logo;
use Bmvc\Cli\Colors;

class InstallerScript
{
    /**
     * Affiche le logo BMVC lors de l'installation
     */
    public static function afficherLogo(Event $event): void
    {
        echo "\n";

        // Affiche le logo colorisé
        Logo::afficher();

        // Messages d'installation réussie
        echo Colors::$cyan . "PROCHAINES ÉTAPES :" . Colors::$reset . "\n\n";

        Logo::info("Accédez au dossier de votre projet");
        echo Colors::$white . "   \$ cd votre-projet" . Colors::$reset . "\n";

        echo "\n";
        Logo::info("Démarrez le serveur de développement");

        echo Colors::$white . "   \$ php bmvc -d " . Colors::$reset . "\n";
        echo Colors::$white . "   OU" . Colors::$reset . "\n";
        echo Colors::$white . "   \$ php -S localhost:8000 -t public" . Colors::$reset . "\n";

        echo "\n";
        Logo::info("Ouvrez votre navigateur");
        echo Colors::$white . "   Accédez à l'URL suivante :" . Colors::$reset . "\n";
        echo Colors::$white . "   http://localhost:8000" . Colors::$reset . "\n";

        echo "\n";
        Logo::info("Consultez la documentation");
        echo Colors::$white . "   https://github.com/Josuebondo/bmvc/blob/main/README_FR.md" . Colors::$reset . "\n";

        echo "\n" . Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . Colors::$reset . "\n\n";

        Logo::succes("Installation réussie ! Bienvenue dans BMVC !");

        echo "\n" . Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . Colors::$reset . "\n\n";
    }
}
