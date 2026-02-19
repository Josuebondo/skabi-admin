<?php

namespace Core;

use ReflectionMethod as ReflexionMethodePHP;

/**
 * ======================================================================
 * MethodeReflexion - Wrapper français pour ReflectionMethod de PHP
 * ======================================================================
 */
class MethodeReflexion
{
    protected ReflexionMethodePHP $reflexion;

    public function __construct($classe, string $methode)
    {
        $this->reflexion = new ReflexionMethodePHP($classe, $methode);
    }

    /**
     * Obtient les paramètres de la méthode
     */
    public function obtenirParametres()
    {
        return $this->reflexion->getParameters();
    }

    /**
     * Vérifie si la méthode existe
     */
    public function existe(): bool
    {
        return $this->reflexion !== null;
    }

    /**
     * Obtient la réflexion PHP interne
     */
    public function obtenirReflexion(): ReflexionMethodePHP
    {
        return $this->reflexion;
    }
}
