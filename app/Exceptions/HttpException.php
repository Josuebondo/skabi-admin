<?php

namespace App\Exceptions;

/**
 * Exception HTTP
 * 
 * Exception personnalisée pour les erreurs HTTP
 * Permet de lever des erreurs avec code HTTP spécifique
 * 
 * Utilisation :
 *   throw new HttpException(404, 'Utilisateur non trouvé');
 *   throw new HttpException(403, 'Accès refusé');
 *   throw new HttpException(500, 'Erreur interne du serveur');
 * 
 * Codes courants :
 * - 400 : Mauvaise requête
 * - 401 : Non authentifié
 * - 403 : Accès refusé
 * - 404 : Non trouvé
 * - 500 : Erreur serveur
 */

class HttpException extends \Exception
{
    // À compléter avec votre logique d'exception
}
