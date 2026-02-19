<?php

namespace Core\Middlewares;

use Core\Requete;
use Core\APIToken;

/**
 * ======================================================================
 * Middleware d'authentification API - Vérifie le token API
 * ======================================================================
 * 
 * Contrôle d'accès via token Bearer
 * 
 * Utilisation :
 *   Routeur::obtenir('/api/articles', 'ArticleControleur@lister')
 *       ->intergiciel('auth.api');
 * 
 * Envoi du token :
 *   curl -H "Authorization: Bearer TOKEN_ICI" http://localhost/api/articles
 */
class MiddlewareAuthApi
{
    /**
     * Vérifie le token API
     */
    public static function verifier(Requete $requete): bool
    {
        // Vérifier que c'est une requête API
        if (!$requete->estApi()) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Authentification API requise',
                'erreur' => 'En-tête Authorization: Bearer TOKEN manquant'
            ]);
            exit;
        }

        $token = self::extraireToken($requete);

        if (!$token) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Token API invalide',
                'erreur' => 'Format du token incorrect'
            ]);
            exit;
        }

        // Valider le token
        $validateur = new APIToken();
        $donnees = $validateur->verifier($token);

        if (!$donnees) {
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Token API expiré ou invalide',
                'erreur' => 'Authentification échouée'
            ]);
            exit;
        }

        // Stocker les données du token dans la requête via param
        $requete->definirParam('token_data', $donnees);
        $requete->definirParam('token', $token);

        return true;
    }

    /**
     * Extrait le token du header Authorization
     */
    private static function extraireToken(Requete $requete): ?string
    {
        $header = $requete->entete('Authorization');

        if (!$header) {
            return null;
        }

        // Format : Bearer TOKEN_ICI
        if (preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
