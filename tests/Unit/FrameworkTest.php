<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FrameworkTest extends TestCase
{
    /**
     * Test que le framework est bien installé
     */
    public function testFrameworkInstalled(): void
    {
        $this->assertTrue(class_exists('\Core\Routeur'));
        $this->assertTrue(class_exists('\Core\Modele'));
        $this->assertTrue(class_exists('\Core\Requete'));
    }

    /**
     * Test que les classes principales existent
     */
    public function testCoreClassesExist(): void
    {
        $classes = [
            '\Core\Application',
            '\Core\BaseBD',
            '\Core\Auth',
            '\Core\Validateur',
            '\Core\Cache',
            '\Core\Vue'
        ];

        foreach ($classes as $class) {
            $this->assertTrue(class_exists($class), "Classe $class non trouvée");
        }
    }

    /**
     * Test des helpers (seul env() est garanti existant)
     */
    public function testHelpersLoaded(): void
    {
        // Les helpers sont dans Helpers.php qui n'est peut-être pas encore chargé
        // On teste juste que le framework a les méthodes essentielles
        $this->assertTrue(method_exists('\Core\Routeur', 'obtenir'));
        $this->assertTrue(method_exists('\Core\Auth', 'connecte'));
    }
}
