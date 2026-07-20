<?php

namespace App\Services;

use Bmvc\BAuth\Adapters\BMVC\BmvcAuthProvider;
use Bmvc\BAuth\Auth as BAuth;
use Bmvc\BAuth\Config as BAuthConfig;
use Core\Validateur;
use Core\Session;

/**
 * Service d'Authentification
 * Encapsule la logique d'authentification réutilisable
 */
class AuthService
{
    protected BAuth $auth;


    private const ACCESS_TOKEN_EXPIRES = 3600; // 1 hour
    private const REFRESH_TOKEN_EXPIRES = 604800; // 7 days

    public function __construct()
    {
        $config = new BAuthConfig([
            'jwt' => [
                'secret' => env('AUTH_JWT_SECRET'),
                'expiresIn' => self::ACCESS_TOKEN_EXPIRES,
                'algorithm' => 'HS256',
            ],
            'password' => [
                'algorithm' => PASSWORD_BCRYPT,
                'options' => ['cost' => 12],
            ],
        ]);

        $auth = new BAuth($config);
        $adapter = new BmvcAuthProvider($config, 'users');
        $auth->setAuthProvider($adapter);
        $this->auth = $auth;
    }
    /**
     * Authentifie un utilisateur via Bauth et la base de données
     */
    public function connexion(string $identifiant, string $motDePasse): array
    {
        try {
            if (session::estActive()) {
                session::vider();
                session::detruire();
            }
            $result = $this->auth->login($identifiant, $motDePasse);
            $user = $result['user'] ?? null;
            if ($user) {
                $jwtPayload = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ];
                $jwtprovider = $this->auth->getTokenProvider();
                $accessToken = $jwtprovider->generate($jwtPayload, self::ACCESS_TOKEN_EXPIRES);
                $refreshToken = $jwtprovider->generate($jwtPayload, self::REFRESH_TOKEN_EXPIRES);
                // $this->auth->getSessionProvider()->start($user, $accessToken);
                session::demarrer();
                session::enregistrer('access_token', $accessToken);
                session::enregistrer('refresh_token', $refreshToken);
                session::enregistrer('auth_user', $user);
                return [
                    'success' => true,
                    'message' => 'Connexion réussie',
                    'data' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'nom' => $user['nom_complet'],
                        'photo' => $user['photo'],
                        'role' => $user['role'],
                        'access_token' => $accessToken,
                        'refresh_token' => $refreshToken,
                        'redirection' => session::obtenir('url_intended'),


                    ],
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Identifiant ou mot de passe incorrect',
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la connexion: ' . $e->getMessage(),
            ];
        }
    }






    /**
     * Valide les données de connexion
     */
    public function validerConnexion(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->valider($donnees);

        return $v;
    }

    /**
     * Valide les données d'inscription
     */
    public function validerInscription(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('nom', ['requis', 'min:3']);
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->ajouter('confirmation_mot_de_passe', ['match:mot_de_passe']);
        $v->valider($donnees);

        return $v;
    }
    public  function getAuth(): BAuth
    {
        return $this->auth;
    }
}
